//--------------------------------------------------------------------------
// Program to pull the information out of various types of EFIF digital 
// camera files and show it in a reasonably consistent way
//
// Version 1.0
//
// Compiles with MSVC on Windows, or with GCC on Linux
//
// Compileing under linux: (have to make it work again) Must include math library.
// Use: cc -lm -O3 -o jhead jhead.c exif.c 
//
// Compiling under Windows:  Use MSVC5 or MSVC6, from command line:
// cl -Ox jhead.c exif.c myglob.c
//
// Matthias Wandel,  Dec 1999 - Oct 2000
//--------------------------------------------------------------------------
#include <stdio.h>
#include <stdlib.h>
#include <memory.h>
#include <malloc.h>
#include <string.h>
#include <time.h>
#include <sys/stat.h>
#include <errno.h>
#include <ctype.h>

#ifdef _WIN32
    #include <process.h>
    #include <io.h>
    #include <sys/utime.h>
#else
    #include <utime.h>
    #include <sys/types.h>
    #include <unistd.h>
    #include <errno.h>
#endif

#include "jhead.h"

// Storage for simplified info extracted from file.
ImageInfo_t ImageInfo;

//--------------------------------------------------------------------------
// This structure is used to store jpeg file sections in memory.
typedef struct {
    uchar *  Data;
    int      Type;
    unsigned Size;
}Section_t;

static Section_t Sections[20];
static int SectionsRead;
static int HaveAll;

static int FilesMatched;
static const char * CurrentFile;

typedef enum {
    READ_EXIF = 1,
    READ_IMAGE = 2,
    READ_ALL = 3
}ReadMode_t;

//--------------------------------------------------------------------------
// Command line options flags
static int remove_thumbnails = FALSE;
static int DoSubdirs    = FALSE;
static int RenameToDate = FALSE;
static int SetFileTime  = FALSE;
static int DoAction     = FALSE;
       int ShowTags     = FALSE;    // Do not show raw by default.
static int ShowConcise  = FALSE;
static char * ApplyCommand = NULL;  // Apply this command to all images.
static char * FilterModel = NULL;

//--------------------------------------------------------------------------
// Error exit handler
//--------------------------------------------------------------------------
void ErrExit(char * msg)
{
    fprintf(stderr,"Error : %s\n", msg);
    fprintf(stderr,"in file '%s'\n",CurrentFile);
    exit(EXIT_FAILURE);
} 


#define PSEUDO_IMAGE_MARKER 0x123; // Extra value.
//--------------------------------------------------------------------------
// Get 16 bits motorola order (always) for jpeg header stuff.
//--------------------------------------------------------------------------
static int Get16m(const void * Short)
{
    return (((uchar *)Short)[0] << 8) | ((uchar *)Short)[1];
}


//--------------------------------------------------------------------------
// Process a COM marker.
// We want to print out the marker contents as legible text;
// we must guard against random junk and varying newline representations.
//--------------------------------------------------------------------------
static void process_COM (const uchar * Data, int length)
{
    int ch;
    char Comment[250];
    int nch;
    int a;

    nch = 0;

    if (length > 200) length = 200; // Truncate if it won't fit in our structure.

    for (a=2;a<length;a++){
        ch = Data[a];

        if (ch == '\r' && Data[a+1] == '\n') continue; // Remove cr followed by lf.

        if (isprint(ch) || ch == '\n' || ch == '\t'){
            Comment[nch++] = (char)ch;
        }else{
            Comment[nch++] = '?';
        }
    }

    Comment[nch] = '\0'; // Null terminate

    if (ShowTags){
        printf("COM marker comment: %s\n",Comment);
    }

    strcpy(ImageInfo.Comments,Comment);
}

 
//--------------------------------------------------------------------------
// Process a SOFn marker.  This is useful for the image dimensions
//--------------------------------------------------------------------------
static void process_SOFn (const uchar * Data, int marker)
{
    int data_precision, num_components;

    data_precision = Data[2];
    ImageInfo.Height = Get16m(Data+3);
    ImageInfo.Width = Get16m(Data+5);
    num_components = Data[7];

    if (num_components == 3){
        ImageInfo.IsColor = 1;
    }else{
        ImageInfo.IsColor = 0;
    }

    ImageInfo.Process = marker;

    if (ShowTags){
        printf("JPEG image is %uw * %uh, %d color components, %d bits per sample\n",
                   ImageInfo.Width, ImageInfo.Height, num_components, data_precision);
    }
}


 
// Command line parsing code
static const char * progname;   // program name for error messages
 
//--------------------------------------------------------------------------
// Parse the marker stream until SOS or EOI is seen;
//--------------------------------------------------------------------------
static int ReadJpegSections (FILE * infile,ReadMode_t ReadMode)
{
    int a;
    int HaveCom = FALSE;

    a = fgetc(infile);
    if (a != 0xff || fgetc(infile) != M_SOI){
        return FALSE;
    }

    for(;SectionsRead < 19;){
        int itemlen;
        int marker = 0;
        int ll,lh, got;
        uchar * Data;

        for (a=0;a<7;a++){
            marker = fgetc(infile);
            if (marker != 0xff) break;
        }
        if (marker == 0xff){
            // 0xff is legal padding, but if we get that many, something's wrong.
            ErrExit("too many padding bytes!");
        }

        Sections[SectionsRead].Type = marker;
  
        // Read the length of the section.
        lh = fgetc(infile);
        ll = fgetc(infile);

        itemlen = (lh << 8) | ll;

        if (itemlen < 2){
            ErrExit("invalid marker");
        }

        Sections[SectionsRead].Size = itemlen;

        Data = (uchar *)malloc(itemlen+1); // Add 1 to allow sticking a 0 at the end.
        if (Data == NULL){
            ErrExit("Could not allocate memory");
        }
        Sections[SectionsRead].Data = Data;

        // Store first two pre-read bytes.
        Data[0] = (uchar)lh;
        Data[1] = (uchar)ll;

        got = fread(Data+2, 1, itemlen-2, infile); // Read the whole section.
        if (got != itemlen-2){
            ErrExit("reading from file");
        }
        SectionsRead += 1;

        //printf("Marker '%x' size %d\n",marker, itemlen);
        switch(marker){
            case M_SOS:   // stop before hitting compressed data 
                // If reading entire image is requested, read the rest of the data.
                if (ReadMode & READ_IMAGE){
                    int cp, ep, size;
                    // Determine how much file is left.
                    cp = ftell(infile);
                    fseek(infile, 0, SEEK_END);
                    ep = ftell(infile);
                    fseek(infile, cp, SEEK_SET);

                    size = ep-cp;
                    Data = (uchar *)malloc(size);
                    if (Data == NULL){
                        ErrExit("could not allocate data for entire image");
                    }

                    got = fread(Data, 1, size, infile);
                    if (got != size){
                        ErrExit("could not read the rest of the image");
                    }

                    Sections[SectionsRead].Data = Data;
                    Sections[SectionsRead].Size = size;
                    Sections[SectionsRead].Type = PSEUDO_IMAGE_MARKER;
                    SectionsRead ++;
                    HaveAll = 1;
                }
                return TRUE;

            case M_EOI:   // in case it's a tables-only JPEG stream
                printf("No image in jpeg!\n");
                return FALSE;

            case M_COM: // Comment section
                if (HaveCom || ((ReadMode & READ_EXIF) == 0)){
                    // Discard this section.
                    free(Sections[--SectionsRead].Data);
                }else{
                    process_COM(Data, itemlen);
                    HaveCom = TRUE;
                }
                break;

            case M_JFIF:
                // Regular jpegs always have this tag, exif images have the exif
                // marker instead, althogh ACDsee will write images with both markers.
                // this program will re-create this marker on absence of exif marker.

                free(Sections[--SectionsRead].Data);
                break;

            case M_EXIF:
                if (SectionsRead <= 2){
                    // Seen files from some 'U-lead' software with Vivitar scanner
                    // that uses marker 31 later in the file (no clue what for!)
                    process_EXIF((char *)Data, itemlen);
                }else{
                    // Discard this section.
                    free(Sections[--SectionsRead].Data);
                }
                break;

            case M_SOF0: 
            case M_SOF1: 
            case M_SOF2: 
            case M_SOF3: 
            case M_SOF5: 
            case M_SOF6: 
            case M_SOF7: 
            case M_SOF9: 
            case M_SOF10:
            case M_SOF11:
            case M_SOF13:
            case M_SOF14:
            case M_SOF15:
                process_SOFn(Data, marker);
                break;
            default:
                // Skip any other unknown sections.
                if (ShowTags){
                    printf("Unknown Jpeg section marker 0x%02x size %d\n",marker, itemlen);
                }
                break;
        }
    }
    return TRUE;
}

//--------------------------------------------------------------------------
// Discard read data.
//--------------------------------------------------------------------------
void DiscardData(void)
{
    int a;
    for (a=0;a<SectionsRead;a++){
        free(Sections[a].Data);
    }
    SectionsRead = 0;
    HaveAll = 0;
}

//--------------------------------------------------------------------------
// Read image data.
//--------------------------------------------------------------------------
int ReadJpegFile(const char * FileName, ReadMode_t ReadMode)
{
    FILE * infile;
    int ret;

    infile = fopen(FileName, "rb"); // Unix ignores 'b', windows needs it.

    if (infile == NULL) {
        fprintf(stderr, "%s: can't open '%s'\n", progname, FileName);
        return FALSE;
    }

    // Scan the JPEG headers.
    ret = ReadJpegSections(infile, ReadMode);
    if (!ret){
        printf("Not JPEG: %s\n",FileName);
    }

    fclose(infile);

    if (ret == FALSE){
        DiscardData();
    }
    return ret;
}

//--------------------------------------------------------------------------
// Remove exif thumbnail
//--------------------------------------------------------------------------
int RemoveThumbnail(void)
{
    int a;
    for (a=0;a<SectionsRead-1;a++){
        if (Sections[a].Type == M_EXIF){
            // Truncate the thumbnail section of the exif.
            unsigned int Newsize = GetExifNonThumbnailSize();
            if (Sections[a].Size == Newsize) return FALSE; // Thumbnail already gonne.
            Sections[a].Size = Newsize;
            Sections[a].Data[0] = (uchar)(Newsize >> 8);
            Sections[a].Data[1] = (uchar)Newsize;
            return TRUE;
        }
    }
    // Not an exif image.  Don't know how to get rid of thumbnails.
    return FALSE;
}


//--------------------------------------------------------------------------
// Write image data back to disk.
//--------------------------------------------------------------------------
void WriteJpegFile(const char * FileName)
{
    FILE * outfile;
    int a;

    if (!HaveAll){
        ErrExit("Can't write back - didn't read all");
    }

    outfile = fopen(FileName,"wb");
    if (outfile == NULL){
        ErrExit("Could not open file for write");
    }

    // Initial static jpeg marker.
    fputc(0xff,outfile);
    fputc(0xd8,outfile);
    
    if (Sections[0].Type != M_EXIF && Sections[0].Type != M_JFIF){
        // The image must start with an exif or jfif marker.  If we threw those away, create one.
        static uchar JfifHead[18] = {
            0xff, M_JFIF,
            0x00, 0x10, 'J' , 'F' , 'I' , 'F' , 0x00, 0x01, 
            0x01, 0x01, 0x01, 0x2C, 0x01, 0x2C, 0x00, 0x00 
        };
        fwrite(JfifHead, 18, 1, outfile);
    }

    // Write all the misc sections
    for (a=0;a<SectionsRead-1;a++){
        fputc(0xff,outfile);
        fputc(Sections[a].Type, outfile);
        fwrite(Sections[a].Data, Sections[a].Size, 1, outfile);
    }

    // Write the remaining image data.
    fwrite(Sections[a].Data, Sections[a].Size, 1, outfile);
       
    fclose(outfile);
}

//--------------------------------------------------------------------------
// Invoke an editor for editing a sting.
//--------------------------------------------------------------------------
static void FileEditComment(char * TempFileName, char * Comment)
{
    FILE * file;
    int a;
    char QuotedPath[300];

    file = fopen(TempFileName, "w");
    if (file == NULL){
        fprintf(stderr, "Can't create file '%s'\n",TempFileName);
        ErrExit("could not create temporary file");
    }
    fprintf(file, "%s", Comment);

    fclose(file);

    fflush(stdout); // So logs are contiguous.

    #ifdef _WIN32
        sprintf(QuotedPath, "\"%s\"",TempFileName);
        a = _spawnl(_P_WAIT, "c:\\windows\\notepad.exe", "notepad",TempFileName, NULL);
    #else
        sprintf(QuotedPath, "kwrite \"%s\"",TempFileName);
        a = system(QuotedPath);
    #endif

    if (a != 0){
        ErrExit("Editor terminated abnormally");
    }

    file = fopen(TempFileName, "r");
    if (file == NULL){
        ErrExit("could not open temp file for read");
    }

    // Read the file back in.
    a = fread(Comment, 1, 999, file);

    Comment[a] = '\0';
    fclose(file);

    unlink(TempFileName);
}



//--------------------------------------------------------------------------
// Apply the specified command to the jpeg file.
//--------------------------------------------------------------------------
static void DoCommand(const char * FileName)
{
    int a,e;
    char ExecString[400];
    char TempName[200];
    int TempUsed = FALSE;

    e = 0;

    // Make a temporary file in the destination directory by changing last char.
    strcpy(TempName, FileName);
    a = strlen(TempName)-1;
    TempName[a] = TempName[a] == 't' ? 'z' : 't';

    // Build the exec string.  %i and %o in the exec string get replaced by input and output files.
    for (a=0;;a++){
        if (ApplyCommand[a] == '&'){
            if (ApplyCommand[a+1] == 'i'){
                // Input file.
                e += sprintf(ExecString+e, "\"%s\"",FileName);
                a += 1;
                continue;
            }
            if (ApplyCommand[a+1] == 'o'){
                // Needs an output file distinct from the input file.
                e += sprintf(ExecString+e, "\"%s\"",TempName);
                a += 1;
                TempUsed = TRUE;
                unlink(TempName);// Remove any pre-existing temp file
                continue;
            }
        }
        ExecString[e++] = ApplyCommand[a];
        if (ApplyCommand[a] == 0) break;
    }

    printf("Cmd:%s\n",ExecString);

    errno = 0;
    a = system(ExecString);

    if (a || errno){
        // A command can however fail without errno getting set or system returning an error.
        if (errno) perror("system");
        ErrExit("Problem executing specified command");
    }

    if (TempUsed){
        // Don't delete original file until we know a new one was created by the command.
        struct stat dummy;
        if (stat(TempName, &dummy) == 0){
            unlink(FileName);
            rename(TempName, FileName);
        }else{
            ErrExit("specified command did not produce expected output file");
        }
    }
}

//--------------------------------------------------------------------------
// check if this file should be skipped based on contents.
//--------------------------------------------------------------------------
int CheckFileSkip(void)
{
    // I sometimes add code here to only process images based on certain
    // criteria - for example, only to convert non progressive jpegs to progressives, etc..

    if (FilterModel){
        // Filtering processing by camera model.
        if (strstr(ImageInfo.CameraModel, FilterModel) == NULL){
            DiscardData();
            return TRUE;
        }
    }
    return FALSE;
}

//--------------------------------------------------------------------------
// Do selected operations to one file at a time.
//--------------------------------------------------------------------------
void ProcessFile(const char * FileName)
{
    int Modified = FALSE;
    ReadMode_t ReadMode = READ_EXIF;
    int a;

    CurrentFile = FileName;

    // Start with an empty image information structure.
    memset(&ImageInfo, 0, sizeof(ImageInfo));
    memset(&Sections, 0, sizeof(Sections));

    ImageInfo.FocalLength = 0;
    ImageInfo.ExposureTime = 0;
    ImageInfo.ApertureFNumber = 0;
    ImageInfo.Distance = 0;
    ImageInfo.CCDWidth = 0;
    ImageInfo.FlashUsed = -1;

    // Store file date/time.
    {
        struct stat st;
        if (stat(FileName, &st) >= 0){
            ImageInfo.FileDateTime = st.st_mtime;
            ImageInfo.FileSize = st.st_size;
        }else{
            ErrExit("No such file");
        }
    }

    if (ApplyCommand){
        // Applying a command is special - the headers from the file have to be
        // rpe-read, then the command executed, and then the image part of the file read.

        ReadJpegFile(FileName, READ_EXIF);

        if (CheckFileSkip()) return;

        // Discard everything but the exif and comment sections.
        {
            Section_t ExifKeeper;
            Section_t CommentKeeper;
            memset(&ExifKeeper, 0, sizeof(ExifKeeper));
            memset(&CommentKeeper, 0, sizeof(ExifKeeper));

            for (a=0;a<SectionsRead;a++){
                if (Sections[a].Type == M_EXIF && ExifKeeper.Type == 0){
                    ExifKeeper = Sections[a];
                }else if (Sections[a].Type == M_COM && CommentKeeper.Type == 0){
                    CommentKeeper = Sections[a];
                }else{
                    free(Sections[a].Data);
                }
            }
            SectionsRead = 0;
            if (ExifKeeper.Type){
                Sections[SectionsRead++] = ExifKeeper;
            }
            if (CommentKeeper.Type){
                Sections[SectionsRead++] = CommentKeeper;
            }
        }    

        DoCommand(FileName);
        Modified = TRUE;

        // Don't re-read exif section (this also clears the READ_EXIF bit)
        ReadMode = READ_IMAGE;
    }

    FilesMatched += 1;

    FilesMatched = TRUE; // Turns off complaining that nothing matched.

    if (remove_thumbnails){
        ReadMode |= READ_IMAGE;
    }

    if (!ReadJpegFile(FileName, ReadMode)) return;

    if (CheckFileSkip()) return;

    // Fill in the file name.
    if (DoSubdirs){
        // If doing subdirs, the actual path matters.
        strncpy(ImageInfo.FileName, FileName, 119);
    }else{
        // If not doing subdirs, cut off the path part.
        for (a = strlen(FileName);a;){
            --a;
            #ifdef _WIN32
            if (FileName[a] == '\\') {
            #else
            if (FileName[a] == '/') {
            #endif
                a++;
                break; // Found first slash.
            }
        }
        strncpy(ImageInfo.FileName, FileName+a, 119);
    }

    if (ShowConcise){
        ShowConciseImageInfo();
    }else{
        if (!DoAction || ShowTags){
            ShowImageInfo();
        }
    }


    if (remove_thumbnails){
        if (RemoveThumbnail()){
            Modified = TRUE;
        }
    }

    if (Modified){
        char BackupName[400];
        strcpy(BackupName, FileName);
        strcat(BackupName, ".t");

        // Remove any .old file name that may pre-exist
        unlink(BackupName);

        // Rename the old file.
        rename(FileName, BackupName);

        // Write the new file.
        WriteJpegFile(FileName);

        // Now that we are done, remove original file.
        unlink(BackupName);
    }

    DiscardData();


    if (SetFileTime){
        // Set the file date to the date from the exif header.
        if (ImageInfo.DateTime[0]){
            // Converte the file date to Unix time.
            struct tm tm;
            time_t UnixTime;
            if (Exif2tm(&tm, ImageInfo.DateTime)){
                struct utimbuf mtime;

                UnixTime = mktime(&tm);
                if ((int)UnixTime == -1){
                    goto badtime;
                }

                mtime.actime = UnixTime;
                mtime.modtime = UnixTime;

                if (utime(FileName, &mtime) != 0){
                    printf("Error: Could not change time of file '%s'\n",FileName);
                }else{
                    printf("%s\n",FileName);
                }
            }
        }else{
            badtime:
            printf("Error: Time '%s': cannot convert to Unix time\n",ImageInfo.DateTime);
        }
    }

    // Feature to rename image according to date and time from camera.
    // I use this feature to put images from multiple digicams in sequence.

    if (RenameToDate){
        int NumAlpha = 0;
        int NumDigit = 0;
        
        for (a=0;FileName[a];a++){
            if (isalpha(FileName[a])) NumAlpha += 1;
            if (isdigit(FileName[a])) NumDigit += 1;
        }
        if ((NumAlpha <= 8 && NumDigit >= 2) || RenameToDate > 1){
            if (ImageInfo.DateTime[0]){
                struct tm tm;
                if (Exif2tm(&tm, ImageInfo.DateTime)){
                    char NewName[20];

                    for (a=0;a<3;a++){
                        sprintf(NewName, "%02d%02d-%02d%02d%02d.jpg",
                             tm.tm_mon+1, tm.tm_mday,
                             tm.tm_hour, tm.tm_min, tm.tm_sec);
                        
                        // Try bumping up the second count 2 times if name is already taken.
                        // That way, pix taken in same second will not conflict.
                        if (rename(FileName, NewName) == 0){
                            printf("%s-->%s\n",FileName, NewName);
                            goto success;
                        }

                        // Rename failed.  Try renaming to one second later.
                        tm.tm_sec += 1;

                        if (tm.tm_sec > 99){
                            break; // Whoops - no longer a valid name.
                        }
                    }
                    printf("Error: Could rename '%s'\n",FileName);
                    success:;
                }
            }
        }
    }
}

//--------------------------------------------------------------------------
// complain about bad state of the command line.
//--------------------------------------------------------------------------
static void Usage (void)
{
    fprintf(stderr,"Program for extracting Digicam setting information from Exif Jpeg headers\n"
                   "used by most Digital Cameras.  v1.0  Matthias Wandel, May 2000.\n"
                   "http://www.sentex.net/~mwandel/jhead  mwandel@sentex.net\n"
                   "\n");

    fprintf(stderr,"Usage: %s [options] files\n", progname);
    fprintf(stderr,"Where:\n"
                   "[otpions] are:\n"
                   "  -dt   -->  Remove exif header thumbnails from exif files\n"
                   "\n"
                   "  -h    -->  help (this text)\n"
                   "\n"
                   "  -v    -->  even more verbose output\n"
                   "\n"
                   "  -c    -->  concise output\n"
                   "\n"
                   "  -model model\n"
                   "        -->  Only process files from digicam containing model substring in\n"
                   "             camera model description\n"
                   "\n"
                   "  -ft   -->  Set file modification time to Exif time.\n"
                   "\n"
                   "  -n    -->  Rename files according to date mmdd-hhmmss.  This\n"
                   "             feature is useful for ordering files from multipe \n"
                   "             digicams to sequence of taking.  Only renames files\n"
                   "             Whose names are mostly numerical (as assigned by digicam)\n"
                   "\n"
                   "  -nf   -->  Same as -n, but rename regardless of original name\n"
                   "\n"
                   "  -cmd command\n"
                   "        -->  Apply 'command' to every file, then re-insert exif and command\n"
                   "             sections into the image.  file name is always added to arguments\n"
                   "             'command' (use quotes to supply arguments).  This is most useful\n"
                   "             in conjunction with the free ImageMagic tool.  For example, with\n"
                   "             My Cannon S100, which suboptimally compresses jpegs, I can specify\n"
                   "                jhead -cmd \"mogrify -quality 80 %%i\" *.jpg\n"
                   "             to re-compress a lot of images using ImageMagic to half the size,\n" 
                   "             and no visible loss of quality while keeping the exif header\n"
                   "             &i will be substituted for the input file name, and &o (if used)\n"
                   "             for the output file name\n"
                   "\n"
#ifdef _WIN32
                   "  -r    -->  Descend subdirectories on wildcard file matching\n"
                   "\n"
#endif
                   " files  -->  path/filenames with or without widlcards\n"
           );

    exit(EXIT_FAILURE);
}


//--------------------------------------------------------------------------
// The main program.
//--------------------------------------------------------------------------
int main (int argc, char **argv)
{
    int argn;
    char * arg;
    progname = argv[0];

    for (argn=1;argn<argc;argn++){
        arg = argv[argn];
        if (arg[0] != '-') break; // Filenames from here on.
        if (!strcmp(arg,"-v")){
            ShowTags = TRUE;
        }else if (!strcmp(arg,"-dt")){
            remove_thumbnails = TRUE;
            DoAction = TRUE;
#ifdef _WIN32
        }else if (!strcmp(arg,"-r")){
            DoSubdirs = TRUE;
#endif
        }else if (!strcmp(arg,"-n")){
            RenameToDate = 1;
            DoAction = TRUE;
        }else if (!strcmp(arg,"-nf")){
            RenameToDate = 2;
            DoAction = TRUE;
        }else if (!strcmp(arg,"-ft")){
            SetFileTime = TRUE;
            DoAction = TRUE;
        }else if (!strcmp(arg,"-cmd")){
            if (argn+1 >= argc) Usage(); // No extra argument.
            ApplyCommand = argv[++argn];
            DoAction = TRUE;
        }else if (!strcmp(arg,"-model")){
            if (argn+1 >= argc) Usage(); // No extra argument.
            FilterModel = argv[++argn];
        }else if (!strcmp(arg,"-c")){
            ShowConcise = TRUE;
        }else if (!strcmp(arg,"-h")){
            Usage();
        }else{
            printf("Argument '%s' not understood\n",arg);
            Usage();
        }
    }
    if (argn == argc){
        fprintf(stderr,"Error: Must supply a file name\n");
        Usage();
    }

    for (;argn<argc;argn++){
        FilesMatched = FALSE;

        #ifdef _WIN32
            // Use my globbing module to do fancier wildcard expansion with recursive
            // subdirectories under Windows.
            MyGlob(argv[argn],DoSubdirs, ProcessFile);
            if (!FilesMatched){
                fprintf(stderr, "Error: No files matched '%s'\n",argv[argn]);
            }
        #else
            // Under linux, don't do any extra fancy globbing - shell globbing is 
            // pretty fancy as it is.
            ProcessFile(argv[argn]);
        #endif
    }
    return EXIT_SUCCESS;
}

