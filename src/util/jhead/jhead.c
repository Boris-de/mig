//--------------------------------------------------------------------------
// Program to pull the information out of various types of EXIF digital 
// camera files and show it in a reasonably consistent way
// Version 1.6
//
//
// Compiling under Unix:
// Use: cc -O3 -o jhead jhead.c exif.c -lm
//
// Compiling under Windows:  Use MSVC5 or MSVC6, from command line:
// cl -Ox jhead.c exif.c myglob.c
//
// Dec 1999 - Jan 2002
//
// by Matthias Wandel (mwandel@rim.net),
//--------------------------------------------------------------------------
#include <stdio.h>
#include <stdlib.h>
#include <memory.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <sys/stat.h>
#include <errno.h>
#include <ctype.h>

#define JHEAD_VERSION "1.6"

// This #define turns on features that are too very specific to 
// how I organize my photos.  Best to ignore everything inside #ifdef MATTHIAS
//#define MATTHIAS

#ifdef _WIN32
    #include <process.h>
    #include <io.h>
    #include <sys/utime.h>
#else
    #include <utime.h>
    #include <sys/types.h>
    #include <unistd.h>
    #include <errno.h>
    #include <limits.h>
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

#define MAX_SECTIONS 20
static Section_t Sections[MAX_SECTIONS];
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
static int TrimExif = FALSE;        // Cut off exif beyond interesting data.
static int RenameToDate = FALSE;
static char * strftime_args = NULL; // Format for new file name.
static int Exif2FileTime  = FALSE;
static int DoModify     = FALSE;
static int DoReadAction = FALSE;
       int ShowTags     = FALSE;    // Do not show raw by default.
static int ShowConcise  = FALSE;
static char * ApplyCommand = NULL;  // Apply this command to all images.
static char * FilterModel = NULL;
static int    ExifOnly    = FALSE;
static time_t ExifTimeAdjust = 0;   // Timezone adjust
static time_t ExifTimeSet = 0;      // Set exif time to a value.

static int DeleteComments = FALSE;
static int DeleteExif = FALSE;
static char * ThumbnailName = NULL; // If not NULL, use this string to make up
                                    // the filename to store the thumbnail to.

static char * ExifXferScrFile = NULL;// Extract Exif header from this file, and
                                    // put it into the jpegs processed.

static int EditComment = FALSE;     // Invoke an editor for editing the comment


#ifdef MATTHIAS
    // This #ifdef to take out less than elegant stuff for editing
    // the comments in a jpeg.  The programs rdjpgcom and wrjpgcom
    // included with Linux distributions do a better job.

    static char * AddComment = NULL; // Add this tag.
    static char * RemComment = NULL; // Remove this tag
    static int AutoResize = FALSE;
#endif // MATTHIAS

//--------------------------------------------------------------------------
// Error exit handler
//--------------------------------------------------------------------------
void ErrExit(char * msg)
{
    fprintf(stderr,"Error : %s\n", msg);
    if (CurrentFile) fprintf(stderr,"in file '%s'\n",CurrentFile);
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
    char Comment[MAX_COMMENT+1];
    int nch;
    int a;

    nch = 0;

    if (length > MAX_COMMENT) length = MAX_COMMENT; // Truncate if it won't fit in our structure.

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
static int ReadJpegSections (FILE * infile, ReadMode_t ReadMode)
{
    int a;
    int HaveCom = FALSE;

    a = fgetc(infile);


    if (a != 0xff || fgetc(infile) != M_SOI){
        return FALSE;
    }
    for(;SectionsRead < MAX_SECTIONS-1;){
        int itemlen;
        int marker = 0;
        int ll,lh, got;
        uchar * Data;

        for (a=0;a<7;a++){
            marker = fgetc(infile);
            if (marker != 0xff) break;

            if (a >= 6){
                printf("too many padding bytes\n");
                return FALSE;
            }
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

        Data = (uchar *)malloc(itemlen);
        if (Data == NULL){
            ErrExit("Could not allocate memory");
        }
        Sections[SectionsRead].Data = Data;

        // Store first two pre-read bytes.
        Data[0] = (uchar)lh;
        Data[1] = (uchar)ll;

        got = fread(Data+2, 1, itemlen-2, infile); // Read the whole section.
        if (got != itemlen-2){
            ErrExit("Premature end of file?");
        }
        SectionsRead += 1;

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
                // hence no need to keep the copy from the file.
                free(Sections[--SectionsRead].Data);
                break;

            case M_EXIF:
                // Seen files from some 'U-lead' software with Vivitar scanner
                // that uses marker 31 for non exif stuff.  Thus make sure 
                // it says 'Exif' in the section before treating it as exif.
                if ((ReadMode & READ_EXIF) && memcmp(Data+2, "Exif", 4) == 0){
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
                // Skip any other sections.
                if (ShowTags){
                    printf("Jpeg section marker 0x%02x size %d\n",marker, itemlen);
                }
                break;
        }
    }
    return TRUE;
}

//--------------------------------------------------------------------------
// Discard read data.
//--------------------------------------------------------------------------
static void DiscardData(void)
{
    int a;
    for (a=0;a<SectionsRead;a++){
        free(Sections[a].Data);
    }
    memset(&ImageInfo, 0, sizeof(ImageInfo));
    SectionsRead = 0;
    HaveAll = 0;
}

//--------------------------------------------------------------------------
// Read image data.
//--------------------------------------------------------------------------
static int ReadJpegFile(const char * FileName, ReadMode_t ReadMode)
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
static int TrimExifFunc(void)
{
    int a;
    for (a=0;a<SectionsRead-1;a++){
        if (Sections[a].Type == M_EXIF && memcmp(Sections[a].Data+2, "Exif",4)==0){
            unsigned int NewSize;
            NewSize = RemoveThumbnail(Sections[a].Data, Sections[a].Size);
            // Truncate the thumbnail section of the exif.
            printf("%d bytes removed from %s\n",Sections[a].Size-NewSize, CurrentFile);
            if (Sections[a].Size == NewSize) return FALSE; // Nothing removed.
            Sections[a].Size = NewSize;
            Sections[a].Data[0] = (uchar)(NewSize >> 8);
            Sections[a].Data[1] = (uchar)NewSize;
            return TRUE;
        }
    }
    // Not an exif image.  Can't remove exif thumbnail.
    return FALSE;
}

//--------------------------------------------------------------------------
// Discard everything but the exif and comment sections.
//--------------------------------------------------------------------------
static void DiscardAllButExif(void)
{
    Section_t ExifKeeper;
    Section_t CommentKeeper;
    int a;

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

//--------------------------------------------------------------------------
// Remove comments
//--------------------------------------------------------------------------
static int RemoveSectionType(int SectionType)
{
    int a;
    for (a=0;a<SectionsRead-1;a++){
        if (Sections[a].Type == SectionType){
            // Free up this section
            free (Sections[a].Data);
            // Move succeding sections back by one to close space in array.
            memmove(Sections+a, Sections+a+1, sizeof(Section_t) * (SectionsRead-a));
            SectionsRead -= 1;
            return TRUE;
        }
    }
    return FALSE;
}

//--------------------------------------------------------------------------
// Write image data back to disk.
//--------------------------------------------------------------------------
static void WriteJpegFile(const char * FileName)
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
static int FileEditComment(char * TempFileName, char * Comment, int CommentSize)
{
    FILE * file;
    int a;
    char QuotedPath[300];

    file = fopen(TempFileName, "w");
    if (file == NULL){
        fprintf(stderr, "Can't create file '%s'\n",TempFileName);
        ErrExit("could not create temporary file");
    }
    fwrite(Comment, CommentSize, 1, file);

    fclose(file);

    fflush(stdout); // So logs are contiguous.

    {
	char * Editor;
	Editor = getenv("EDITOR");
	if (Editor == NULL){
	    #ifdef _WIN32
		Editor = "notepad";
	    #else
		Editor = "vi";
	    #endif
	}

        sprintf(QuotedPath, "%s \"%s\"",Editor, TempFileName);
        a = system(QuotedPath);
    }
    
    if (a != 0){
        perror("Editor failed to launch");
        exit(-1);
    }

    file = fopen(TempFileName, "r");
    if (file == NULL){
        ErrExit("could not open temp file for read");
    }

    // Read the file back in.
    CommentSize = fread(Comment, 1, 999, file);

    fclose(file);

    unlink(TempFileName);

    return CommentSize;
}

#ifdef MATTHIAS
//--------------------------------------------------------------------------
// Modify one of the lines in the comment field.
// This very specific to the photo album program stuff.
//--------------------------------------------------------------------------
static char KnownTags[][10] = {"descript","date", "orig_path", "desc","scan_date","author",
                               "mwnum", "crop", "rotate", "subpic", 
                               "related", "infopic", "keyword","videograb",
                               "show_raw","panorama",""};

static int ModifyDescriptComment(char * OutComment, char * SrcComment)
{
    char Line[500];
    int Len;
    int a,i;
    unsigned l;
    int DescriptFormat;
    int HasScandate = FALSE;
    int TagExists = FALSE;
    int Modified = FALSE;
    DescriptFormat = FALSE;
    Len = 0;

    OutComment[0] = 0;

    strcat(OutComment, "descript\n");

    for (i=0;;i++){
        if (SrcComment[i] == '\r' || SrcComment[i] == '\n' || SrcComment[i] == 0 || Len >= 199){
            // Process the line.
            if (Len > 0){
                Line[Len] = 0;
                //printf("Line: '%s'\n",Line);
                if (!strcmp(Line, "descript")){
                    DescriptFormat = TRUE;
                }else{
                    if (DescriptFormat){
                        for (a=0;;a++){
                            l = strlen(KnownTags[a]);
                            if (!l){
                                // Unknown tag.  Discard it.
                                printf("Error: Unknown tag '%s'\n", Line); // Deletes the tag.
                                Modified = TRUE;
				break;
                            }
                            if (memcmp(Line, KnownTags[a], l) == 0){
                                if (Line[l] == ' ' || Line[l] == '=' || Line[l] == 0){
                                    // Its a good tag.
                                    if (Line[l] == ' ') Line[l] = '='; // Use equal sign for clarity.
                                    if (a == 2) break; // Delete 'orig_path' tag.
                                    if (a == 4) HasScandate = TRUE;
                                    if (RemComment){
                                        if (strlen(RemComment) == l){
                                            if (!memcmp(Line, RemComment, l)){
                                                Modified = TRUE;
                                                break;
                                            }
                                        }
                                    }
                                    if (AddComment){
                                        // Overwrite old comment of same tag with new one.
                                        if (!memcmp(Line, AddComment, l+1)){
                                            TagExists = TRUE;
                                            strcpy(Line, AddComment);
                                            Modified = TRUE;
                                        }
                                    }
                                    strcat(OutComment, Line);
                                    strcat(OutComment, "\n");
                                    break;
                                }
                            }
                        }
                    }else{
                        printf("Junk tag: %s\n",Line);
			Modified = TRUE;
                    }
                }
            }
            Line[Len = 0] = 0;
            if (SrcComment[i] == 0) break;
        }else{
            Line[Len++] = SrcComment[i];
        }
    }

    if (AddComment && TagExists == FALSE){
        strcat(OutComment, AddComment);
        strcat(OutComment, "\n");
        Modified = TRUE;
    }

    if (!HasScandate && !ImageInfo.DateTime[0]){
        // Scan date is not in the file yet, and it doesn't have one built in.  Add it.
        char Temp[30];
        sprintf(Temp, "scan_date=%s", ctime(&ImageInfo.FileDateTime));
        strcat(OutComment, Temp);
        Modified = TRUE;
    }
    return Modified;
}
//--------------------------------------------------------------------------
// Automatic make smaller command stuff
//--------------------------------------------------------------------------
static int AutoResizeCmdStuff(void)
{
    static char CommandString[500];
    double scale;

    ApplyCommand = CommandString;

    if (ImageInfo.Height < 640 && ImageInfo.Width < 640){
        printf("not redizing %dx%x '%s'\n",ImageInfo.Height, ImageInfo.Width, ImageInfo.FileName);
        return FALSE;
    }

    scale = 640.0 / ImageInfo.Height;
    if (640.0 / ImageInfo.Width < scale) scale = 640.0 / ImageInfo.Width;

    if (scale < 0.5) scale = 0.5; // Don't scale down by more than a factor of two.

    if (scale > 0.9) return FALSE; // Don't rescale by really small amounts (not worth it!)

    sprintf(CommandString, "mogrify -geometry %dx%d &i",(int)(ImageInfo.Width*scale), (int)(ImageInfo.Height*scale));
    return TRUE;
}


#endif // MATTHIAS


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
                if (strstr(FileName, " ")){
                    e += sprintf(ExecString+e, "\"%s\"",FileName);
                }else{
                    // No need for quoting (that way I can put a relative path in front)
                    e += sprintf(ExecString+e, "%s",FileName);
                }
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
static int CheckFileSkip(void)
{
    // I sometimes add code here to only process images based on certain
    // criteria - for example, only to convert non progressive jpegs to progressives, etc..

    if (FilterModel){
        // Filtering processing by camera model.
        if (strstr(ImageInfo.CameraModel, FilterModel) == NULL){
            return TRUE;

        }
    }

    if (ExifOnly){
        // Filtering by EXIF only.  Skip all files that have no Exif.
        int a;
        for (a=0;a<SectionsRead-1;a++){
            if (Sections[a].Type == M_EXIF){
                goto has_exif;
            }
        }
        // No Exif.
        return TRUE;
        has_exif:;
    }

    return FALSE;
}

//--------------------------------------------------------------------------
// Subsititute original name for '&i' if present in specified name.
// This to allow specifying relative names when manipulating multiple files.
//--------------------------------------------------------------------------
static void RelativeName(char * OutFileName, const char * NamePattern, const char * OrigName)
{
    // If the filename contains substring "&i", then substitute the 
    // filename for that.  This gives flexibility in terms of processing
    // multiple files at a time.
    char * Subst;
    Subst = strstr(NamePattern, "&i");
    if (Subst){
        strncpy(OutFileName, NamePattern, Subst-NamePattern);
        OutFileName[Subst-NamePattern] = 0;
        strncat(OutFileName, OrigName, PATH_MAX);
        strncat(OutFileName, Subst+2, PATH_MAX);
    }else{
        strcpy(OutFileName, NamePattern); 
    }
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

    ImageInfo.FlashUsed = -1;
    ImageInfo.MeteringMode = -1;

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

    strncpy(ImageInfo.FileName, FileName, PATH_MAX);

    if (ApplyCommand){
        // Applying a command is special - the headers from the file have to be
        // pre-read, then the command executed, and then the image part of the file read.

        if (!ReadJpegFile(FileName, READ_EXIF)) return;

        #ifdef MATTHIAS
            if (AutoResize){
                // Automatic resize computation - to customize for each run...
                if (AutoResizeCmdStuff() == 0){
                    DiscardData();
                    return;
                }
            }
        #endif // MATTHIAS


        if (CheckFileSkip()){
            DiscardData();
            return;
        }

        DiscardAllButExif();

        DoCommand(FileName);
        Modified = TRUE;

        ReadMode = READ_IMAGE;   // Don't re-read exif section again on next read.
    }else if (ExifXferScrFile){
        char RelativeExifName[PATH_MAX+1];

        // Make a relative name.
        RelativeName(RelativeExifName, ExifXferScrFile, FileName);

        if(!ReadJpegFile(RelativeExifName, READ_EXIF)) return;

        DiscardAllButExif();    // Don't re-read exif section again on next read.

        Modified = TRUE;
        ReadMode = READ_IMAGE;
    }

    FilesMatched += 1;

    FilesMatched = TRUE; // Turns off complaining that nothing matched.

    if (DoModify){
        ReadMode |= READ_IMAGE;
    }

    if (!ReadJpegFile(FileName, ReadMode)) return;

    if (CheckFileSkip()){
        DiscardData();
        return;
    }

    if (ShowConcise){
        ShowConciseImageInfo();
    }else{
        if (!(DoModify || DoReadAction) || ShowTags){
            ShowImageInfo();
        }
    }

    if (ThumbnailName){
        if (ImageInfo.ThumbnailPointer){
            FILE * ThumbnailFile;
            char OutFileName[PATH_MAX+1];

            // Make a relative name.
            RelativeName(OutFileName, ThumbnailName, FileName);

#ifndef _WIN32
            if (strcmp(ThumbnailName, "-") == 0){
                // A filename of '-' indicates thumbnail goes to stdout.
                // This doesn't make much sense under Windows, so this feature is unix only.
                ThumbnailFile = stdout;
            }else
#endif
            {
                ThumbnailFile = fopen(OutFileName,"wb");
            }

            if (ThumbnailFile){
                fwrite(ImageInfo.ThumbnailPointer, ImageInfo.ThumbnailSize ,1, ThumbnailFile);
                fclose(ThumbnailFile);
                if (ThumbnailFile != stdout){
                    printf("Created: '%s'\n", OutFileName);
                }else{
                    // No point in printing to stdout when that is where the thumbnail goes!
                }
            }else{
                ErrExit("Could not write thumbnail file");
            }
        }else{
            printf("Image '%s' contains no thumbnail\n",FileName);
        }
    }

#ifdef MATTHIAS
    if (AddComment || RemComment|| EditComment){
#else
    if (EditComment){
#endif
        Section_t * CommentSec;
        char Comment[1000];
        int CommentSize;

        // Find the comment.
        for (a=0;a<SectionsRead-1;a++){
            if (Sections[a].Type == M_COM){
                CommentSec = Sections+a;
                goto foundit;
            }
        }
        // No comment found.  Add one in the middle.
        for (a=19;a>2;a--){
            Sections[a] = Sections[a-1];          
        }
        SectionsRead += 1;
        CommentSec = Sections+2;

        CommentSec->Type = M_COM;
        CommentSec->Size = 2;
        CommentSec->Data = (uchar *) malloc(3);
        CommentSec->Data[0] = 0;
        CommentSec->Data[1] = 2;

        foundit:
        CommentSize = CommentSec->Size-2;

#ifdef MATTHIAS
        if (ModifyDescriptComment(Comment, (char *)CommentSec->Data+2)){
            Modified = TRUE;
            CommentSize = strlen(Comment);
        }
        if (EditComment)
#else
        memcpy(Comment, (char *)CommentSec->Data+2, CommentSize);
#endif
        {
            char EditFileName[PATH_MAX+4];
            strcpy(EditFileName, FileName);
            strcat(EditFileName, ".txt");

            CommentSize = FileEditComment(EditFileName, Comment, CommentSize);
        }

        if (strcmp(Comment, (char *)CommentSec->Data+2)){
            // Discard old comment section and put a new one in.
            int size;
            size = CommentSize+2;
            free(CommentSec->Data);
            CommentSec->Size = size;
            CommentSec->Data = malloc(size);
            CommentSec->Data[0] = (uchar)(size >> 8);
            CommentSec->Data[1] = (uchar)(size);
            memcpy((CommentSec->Data)+2, Comment, size-2);
            Modified = TRUE;
        }
        if (!Modified){
            printf("Comment not modified\n");
        }
    }

    if (ExifTimeAdjust || ExifTimeSet){
        if (ImageInfo.DatePointer){
            struct tm tm;
            time_t UnixTime;
            char TempBuf[50];

            if (ExifTimeSet){
                // A time to set was specified.
                UnixTime = ExifTimeSet;
            }else{
                // A time offset to adjust by was specified.
                if (!Exif2tm(&tm, ImageInfo.DateTime)) goto badtime;

                // Convert to unix 32 bit time value, add offset, and convert back.
                UnixTime = mktime(&tm);
                if ((int)UnixTime == -1) goto badtime;
                UnixTime += ExifTimeAdjust;
            }
            tm = *localtime(&UnixTime);

            // Print to temp buffer first to avoid putting null termination in destination.
            // snprintf() would do the trick ,but not available everywhere (like FreeBSD 4.4)
            sprintf(TempBuf, "%04d:%02d:%02d %02d:%02d:%02d",
                tm.tm_year+1900, tm.tm_mon+1, tm.tm_mday,
                tm.tm_hour, tm.tm_min, tm.tm_sec);

            memcpy(ImageInfo.DatePointer, TempBuf, 19);

            Modified = TRUE;
        }else{
            printf("File '%s' contains no Exif timestamp to change\n", FileName);
        }
    }

    if (TrimExif){
        if (TrimExifFunc()) Modified = TRUE;
    }
    
    if (DeleteComments){
        if (RemoveSectionType(M_COM)) Modified = TRUE;
    }
    if (DeleteExif){
        if (RemoveSectionType(M_EXIF)) Modified = TRUE;
    }


    if (Modified){
        char BackupName[400];
        printf("Modified: %s\n",FileName);

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


    if (Exif2FileTime){
        // Set the file date to the date from the exif header.
        if (ImageInfo.DateTime[0]){
            // Converte the file date to Unix time.
            struct tm tm;
            time_t UnixTime;
            struct utimbuf mtime;
            if (!Exif2tm(&tm, ImageInfo.DateTime)) goto badtime;

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
        }else{
            printf("File '%s' contains no Exif timestamp\n", FileName);
        }
    }

    // Feature to rename image according to date and time from camera.
    // I use this feature to put images from multiple digicams in sequence.

    if (RenameToDate){
        int NumAlpha = 0;
        int NumDigit = 0;
        int PrefixPart = 0;
        
        for (a=0;FileName[a];a++){
            if (FileName[a] == '/' || FileName[a] == '\\'){
                // Don't count path compoenent.
                NumAlpha = 0;
                NumDigit = 0;
                PrefixPart = a+1;
            }
            if (isalpha(FileName[a])) NumAlpha += 1;
            if (isdigit(FileName[a])) NumDigit += 1;
        }

        if ((NumAlpha <= 8 && NumDigit >= 2) || RenameToDate > 1){
            if (ImageInfo.DateTime[0]){
                struct tm tm;
                if (Exif2tm(&tm, ImageInfo.DateTime)){
                    char NewBaseName[PATH_MAX*2];

                    strcpy(NewBaseName, FileName); // Get path component of name.

                    if (strftime_args){
                        // Complicated scheme for flexibility.  Just pass the args to strftime.
                        time_t UnixTime;

                        // Call mktime to get weekday and such filled in.
                        UnixTime = mktime(&tm);
                        if ((int)UnixTime == -1) goto badtime;
                        strftime(NewBaseName+PrefixPart, 99, strftime_args, &tm);
                    }else{
                        // My favourite scheme.
                        sprintf(NewBaseName+PrefixPart, "%02d%02d-%02d%02d%02d",
                             tm.tm_mon+1, tm.tm_mday, tm.tm_hour, tm.tm_min, tm.tm_sec);
                    }

                    for (a=0;;a++){
                        char NewName[120];
                        char NameExtra[3];
                        struct stat dummy;

                        if (a){
                            // Generate a suffix for the file name if previous choice of names is taken.
                            // depending on wether the name ends in a letter or digit, pick the opposite to separate
                            // it.  This to avoid using a separator character - this because any good separator
                            // is before the '.' in ascii, and so sorting the names would put the later name before
                            // the name without suffix, causing the pictures to more likely be out of order.
                            if (isdigit(NewBaseName[strlen(NewBaseName)-1])){
                                NameExtra[0] = 'a'-1+a; // Try a,b,c,d... for suffix if it ends in a letter.
                            }else{
                                NameExtra[0] = '0'-1+a; // Try 1,2,3,4... for suffix if it ends in a char.
                            }
                            NameExtra[1] = 0;
                        }else{
                            NameExtra[0] = 0;
                        }

                        sprintf(NewName, "%s%s.jpg", NewBaseName, NameExtra);

                        if (!strcmp(FileName, NewName)) break; // Skip if its already this name.

                        if (stat(NewName, &dummy)){
                            // This name does not pre-exist.
                            if (rename(FileName, NewName) == 0){
                                printf("%s --> %s\n",FileName, NewName);
                            }else{
                                printf("Error: Couldn't rename '%s' to '%s'\n",FileName, NewName);
                            }
                            break;
                        }

                        if (a >= 9){
                            printf("Dest name for '%s' already exists\n",FileName);
                            break;
                        }
                    }
                }else{
                    printf("File '%s' contains no Exif timestamp\n", FileName);
                }
            }
        }
    }
    if(0){
        badtime:
        printf("Error: Time '%s': cannot convert to Unix time\n",ImageInfo.DateTime);
    }
    DiscardData();
}

//--------------------------------------------------------------------------
// complain about bad state of the command line.
//--------------------------------------------------------------------------
static void Usage (void)
{
    printf("Program for extracting Digicam setting information from Exif Jpeg headers\n"
           "used by most Digital Cameras.  v"JHEAD_VERSION" Matthias Wandel, Oct 25 2001.\n"
           "http://www.sentex.net/~mwandel/jhead  mwandel@sentex.net\n"
           "\n");

    printf("Usage: %s [options] files\n", progname);
    printf("Where:\n"
           "[otpions] are:\n"
           "  -dc   -->  Delete comment field (as left by progs like Photoshop & Compupic)\n"
           "  -ce   -->  Edit comment field.  Uses environment variable 'editor' to\n"
	   "             determine which editor to use.  If editor not set, uses VI\n"
	   "             under Unix and notepad with windows\n"
           "  -de   -->  Strip Exif section (smaller jpeg file, but loose digicam info)\n"
#ifdef MATTHIAS
           "  -cl   -->  \"commets..\" Insert literal comment\n"
           "  -cr   -->  Remove comment tag (my way)\n"
           "  -ca   -->  Add comment tag (my way)\n"
           "  -ar   -->  Auto resize to fit in 640x640, but never less than half\n"
#endif //MATTHIAS
           "  -st <name> Save Exif thumbnail, if there is one, in file <name>\n"
           "             If output file name contains the substring \"&i\" then the\n"
           "             image file name is subsitute for the &i.  Note that quotes around\n"
           "             the argument are required for the '&' to be passed to the program.\n"
#ifndef _WIN32
           "             An output name of '-' causes thumbnail to be written to stdout\n"
#endif
           "  -te <name> Transfer exif header from another image file <name>\n"
           "             Uses same name mangling as '-st' option\n"
           "  -dt   -->  Remove exif integral thumbnails and other non camera setting\n"
           "             parts of exif header.  Typically trims 10k\n"
           "  -h    -->  help (this text)\n"
           "  -v    -->  even more verbose output\n"
           "  -c    -->  concise output\n"
           "  -model model\n"
           "        -->  Only process files from digicam containing model substring in\n"
           "             camera model description\n"
           "  -exonly    Skip all files that don't have an exif header (skip all jpegs that\n"
           "             were not created by digicam)\n"
           "  -ft   -->  Set file modification time to Exif time.\n"
           "  -n[format-string]\n"
           "        -->  Rename files according to date.  If the optional format-string is\n"
           "             not supplied, the format is mmdd-hhmmss.  If a format-string is\n"
           "             given, it is passed to the 'strftime' function for formatting\n"
           "             This feature is useful for ordering files from multipe digicams to\n"
           "             sequence of taking.  Only renames files whose names are mostly\n"
           "             numerical (as assigned by digicam)\n"
           "             The '.jpg' is automatically added to the end of the name.  If the\n"
           "             destination name already exists, a letter or digit is added to \n"
           "             the end of the name to make it uniqe.\n"
           "  -nf[format-string]\n"
           "        -->  Same as -n, but rename regardless of original name\n"
           "  -ta<+|->h[:mm]\n"
           "        -->  Adjust time by h:mm backwards of forwards.  Useful when having\n"
           "             taken pictures with the wrong time set on the camera, such as when\n"
           "             travelling across time zones or DST changes.\n"
           "  -cmd command\n"
           "        -->  Apply 'command' to every file, then re-insert exif and command\n"
           "             sections into the image. &i will be substituted for the input file\n"
           "             name, and &o (if &o is used). Use quotes around the command string\n"
           "             This is most useful in conjunction with the free ImageMagic tool. \n"
           "             For example, with My Cannon S100, which suboptimally compresses\n"
           "             jpegs I can specify\n"
           "                jhead -cmd \"mogrify -quality 80 %%i\" *.jpg\n"
           "             to re-compress a lot of images using ImageMagic to half the size,\n" 
           "             and no visible loss of quality while keeping the exif header\n"
           "             Another invocation I like to use is jpegtran (hard to find for\n"
           "             windows).  I type:\n"
           "                jhead -cmd \"jpegtran -progressive &i &o\" *.jpg\n"
           "             to convert jpegs to progressive jpegs (Unix jpegtran syntax\n"
           "             differs slightly)\n"
#ifdef _WIN32
           "  -r    -->  No longer supported.  Use the ** wildcard to recurse directories\n"
           "             with instead.\n"
           "             examples:\n"
           "                 jhead **/*.jpg\n"
           "                 jhead \"c:\\my photos\\**\\*.jpg\"\n"
#endif
           " files  -->  path/filenames with or without wildcards\n"
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
        }else if (!strcmp(arg,"-V")){
            printf("Jhead version: "JHEAD_VERSION"   Compiled: "__DATE__"\n");
            exit(0);
        }else if (!strcmp(arg,"-dt")){
            TrimExif = TRUE;
            DoModify = TRUE;
        }else if (!strcmp(arg,"-st")){
            ThumbnailName = argv[++argn];
            DoReadAction = TRUE;
        }else if (!strcmp(arg,"-te")){
            ExifXferScrFile = argv[++argn];
            DoModify = TRUE;
        }else if (!memcmp(arg,"-n",2)){
            RenameToDate = 1;
            DoReadAction = TRUE; // Rename doesn't modify file, so count as read action.
            arg+=2;
            if (*arg == 'f'){
                RenameToDate = 2;
                arg++;
            }
            if (*arg){
                // A strftime format string is supplied.
                strftime_args = arg;
                //printf("strftime_args = %s\n",arg);
            }
        }else if (!strcmp(arg,"-ft")){
            Exif2FileTime = TRUE;
            DoReadAction = TRUE;
        }else if (!memcmp(arg,"-ta",3)){
            // Timezone adjust feature.
            int hours, minutes, seconds, n;
            minutes = seconds = 0;
            if (arg[3] != '-' && arg[3] != '+'){
                ErrExit("Error: -ta must be followed by +/- and a time\n");
            }
            n = sscanf(arg+4, "%d:%d:%d", &hours, &minutes, &seconds);

            if (n < 1){
                ErrExit("Error: -tz must be immediately followed by time\n");
            }

            ExifTimeAdjust = hours*3600 + minutes*60 + seconds;
            if (arg[3] == '-') ExifTimeAdjust = -ExifTimeAdjust;
            DoModify = TRUE;
        }else if (!memcmp(arg,"-ts",3)){
            // Set the exif time.
            // Time must be specified as "yyyy:mm:dd-hh:mm:ss"
            char * c;
            struct tm tm;

            c = strstr(arg+1, "-");
            if (c) *c = ' '; // Replace '-' with a space.
            
            if (!Exif2tm(&tm, arg+3)){
                ErrExit("-ts option must be followed by time in format yyyy:mmm:dd-hh:mm:ss\n"
                        "Example: jhead -ts2001:01:01-12:00:00 foo.jpg\n");
            }

            ExifTimeSet  = mktime(&tm);

            if ((int)ExifTimeSet == -1) ErrExit("Time specified is out of range");
            DoModify = TRUE;

        }else if (!strcmp(arg,"-cmd")){
            if (argn+1 >= argc) Usage(); // No extra argument.
            ApplyCommand = argv[++argn];
            DoModify = TRUE;
        }else if (!strcmp(arg,"-model")){
            if (argn+1 >= argc) Usage(); // No extra argument.
            FilterModel = argv[++argn];
        }else if (!strcmp(arg,"-exonly")){
            ExifOnly = 1;
        }else if (!strcmp(arg,"-c")){
            ShowConcise = TRUE;
        }else if (!strcmp(arg,"-h")){
            Usage();
        }else if (!strcmp(arg,"-dc")){
            DeleteComments = TRUE;
            DoModify = TRUE;
        }else if (!strcmp(arg,"-de")){
            DeleteExif = TRUE;
            DoModify = TRUE;
        }else if (!strcmp(arg,"-ce")){
            EditComment = TRUE;
            DoModify = TRUE;
#ifdef MATTHIAS
        }else if (!strcmp(arg,"-ca")){
            // Its a literal comment.  Add.
            AddComment = argv[++argn];
            DoModify = TRUE;
        }else if (!strcmp(arg,"-cr")){
            // Its a literal comment.  Remove this keyword.
            RemComment = argv[++argn];
            DoModify = TRUE;
        }else if (!strcmp(arg,"-ar")){
            AutoResize = TRUE;
            ShowConcise = TRUE;
            ApplyCommand = (char *)1; // Must be non null so it does commands.
            DoModify = TRUE;
#endif // MATTHIAS
        }else{
            printf("Argument '%s' not understood\n",arg);
            printf("Use jhead -h for list of arguments\n");
            exit(-1);
        }
        if (argn >= argc){
            // Used an extr argument - becuase the last argument 
            // used up an extr argument.
            ErrExit("Extra argument required");
        }
    }
    if (argn == argc){
        ErrExit("No files to process.  Use -h for help");
    }

    if (ExifXferScrFile){
        if (FilterModel || ApplyCommand){
            ErrExit("Error: Filter by model and/or applying command to files\n"
            "   invalid while transfering Exif headers\n");
        }
    }

    for (;argn<argc;argn++){
        FilesMatched = FALSE;

        #ifdef _WIN32
            {
                int a;
                for (a=0;;a++){
                    if (argv[argn][a] == '\0') break;
                    if (argv[argn][a] == '/') argv[argn][a] = '\\';
                }
            }

            // Use my globbing module to do fancier wildcard expansion with recursive
            // subdirectories under Windows.
            MyGlob(argv[argn], ProcessFile);
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

