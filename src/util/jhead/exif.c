//--------------------------------------------------------------------------
// Program to pull the information out of various types of EFIF digital 
// camera files and show it in a reasonably consistent way
//
// This module parses the very complicated exif structures.
//
// Matthias Wandel,  Dec 1999 - August 2000
//--------------------------------------------------------------------------
#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <string.h>
#include <time.h>
#include <errno.h>
#include <ctype.h>

#ifdef _WIN32
    #include <sys/utime.h>
#else
    #include <utime.h>
    #include <sys/types.h>
    #include <unistd.h>
    #include <errno.h>
#endif

#include "jhead.h"

static char * LastExifRefd;
static int ExifNonThumbnailLength;
static double FocalplaneXRes;
static double FocalplaneUnits;
static int ExifImageWidth;
static int MotorolaOrder = 0;

typedef struct {
    unsigned short Tag;
    char * Desc;
}TagTable_t;


//--------------------------------------------------------------------------
// Table of Jpeg encoding process names
static TagTable_t ProcessTable[] = {
    { M_SOF0,   "Baseline"},
    { M_SOF1,   "Extended sequential"},
    { M_SOF2,   "Progressive"},
    { M_SOF3,   "Lossless"},
    { M_SOF5,   "Differential sequential"},
    { M_SOF6,   "Differential progressive"},
    { M_SOF7,   "Differential lossless"},
    { M_SOF9,   "Extended sequential, arithmetic coding"},
    { M_SOF10,  "Progressive, arithmetic coding"},
    { M_SOF11,  "Lossless, arithmetic coding"},
    { M_SOF13,  "Differential sequential, arithmetic coding"},
    { M_SOF14,  "Differential progressive, arithmetic coding"},
    { M_SOF15,  "Differential lossless, arithmetic coding"},
    { 0,        "Unknown"}
};


//--------------------------------------------------------------------------
// Describes format descriptor
static int BytesPerFormat[] = {0,1,1,2,4,8,1,1,2,4,8,4,8};
#define NUM_FORMATS 12

#define FMT_BYTE       1 
#define FMT_STRING     2
#define FMT_USHORT     3
#define FMT_ULONG      4
#define FMT_URATIONAL  5
#define FMT_SBYTE      6
#define FMT_UNDEFINED  7
#define FMT_SSHORT     8
#define FMT_SLONG      9
#define FMT_SRATIONAL 10
#define FMT_SINGLE    11
#define FMT_DOUBLE    12

//--------------------------------------------------------------------------
// Describes tag values

#define TAG_EXIF_OFFSET       0x8769
#define TAG_INTEROP_OFFSET    0xa005

#define TAG_MAKE              0x010F
#define TAG_MODEL             0x0110

#define TAG_EXPOSURETIME      0x829A
#define TAG_FNUMBER           0x829D

#define TAG_SHUTTERSPEED      0x9201
#define TAG_APERTURE          0x9202
#define TAG_MAXAPERTURE       0x9205
#define TAG_FOCALLENGTH       0x920A

#define TAG_DATETIME_ORIGINAL 0x9003
#define TAG_USERCOMMENT       0x9286

#define TAG_SUBJECT_DISTANCE  0x9206
#define TAG_FLASH             0x9209

#define TAG_FOCALPLANEXRES    0xa20E
#define TAG_FOCALPLANEUNITS   0xa210
#define TAG_EXIF_IMAGEWIDTH   0xA002
#define TAG_EXIF_IMAGELENGTH  0xA003

// the following is added 05-jan-2001 vcs
#define TAG_EXPOSURE_BIAS     0x9204
#define TAG_WHITEBALANCE      0x9208
#define TAG_METERING_MODE     0x9207
#define TAG_EXPOSURE_PROGRAM  0x8822
#define TAG_ISO_EQUIVALENT    0x8827
#define TAG_COMPRESSION_LEVEL 0x9102


static TagTable_t TagTable[] = {
  {   0x100,   "ImageWidth"},
  {   0x101,   "ImageLength"},
  {   0x102,   "BitsPerSample"},
  {   0x103,   "Compression"},
  {   0x106,   "PhotometricInterpretation"},
  {   0x10A,   "FillOrder"},
  {   0x10D,   "DocumentName"},
  {   0x10E,   "ImageDescription"},
  {   0x10F,   "Make"},
  {   0x110,   "Model"},
  {   0x111,   "StripOffsets"},
  {   0x112,   "Orientation"},
  {   0x115,   "SamplesPerPixel"},
  {   0x116,   "RowsPerStrip"},
  {   0x117,   "StripByteCounts"},
  {   0x11A,   "XResolution"},
  {   0x11B,   "YResolution"},
  {   0x11C,   "PlanarConfiguration"},
  {   0x128,   "ResolutionUnit"},
  {   0x12D,   "TransferFunction"},
  {   0x131,   "Software"},
  {   0x132,   "DateTime"},
  {   0x13B,   "Artist"},
  {   0x13E,   "WhitePoint"},
  {   0x13F,   "PrimaryChromaticities"},
  {   0x156,   "TransferRange"},
  {   0x200,   "JPEGProc"},
  {   0x201,   "JPEGInterchangeFormat"},
  {   0x202,   "JPEGInterchangeFormatLength"},
  {   0x211,   "YCbCrCoefficients"},
  {   0x212,   "YCbCrSubSampling"},
  {   0x213,   "YCbCrPositioning"},
  {   0x214,   "ReferenceBlackWhite"},
  {   0x828D,  "CFARepeatPatternDim"},
  {   0x828E,  "CFAPattern"},
  {   0x828F,  "BatteryLevel"},
  {   0x8298,  "Copyright"},
  {   0x829A,  "ExposureTime"},
  {   0x829D,  "FNumber"},
  {   0x83BB,  "IPTC/NAA"},
  {   0x8769,  "ExifOffset"},
  {   0x8773,  "InterColorProfile"},
  {   0x8822,  "ExposureProgram"},
  {   0x8824,  "SpectralSensitivity"},
  {   0x8825,  "GPSInfo"},
  {   0x8827,  "ISOSpeedRatings"},
  {   0x8828,  "OECF"},
  {   0x9000,  "ExifVersion"},
  {   0x9003,  "DateTimeOriginal"},
  {   0x9004,  "DateTimeDigitized"},
  {   0x9101,  "ComponentsConfiguration"},
  {   0x9102,  "CompressedBitsPerPixel"},
  {   0x9201,  "ShutterSpeedValue"},
  {   0x9202,  "ApertureValue"},
  {   0x9203,  "BrightnessValue"},
  {   0x9204,  "ExposureBiasValue"},
  {   0x9205,  "MaxApertureValue"},
  {   0x9206,  "SubjectDistance"},
  {   0x9207,  "MeteringMode"},
  {   0x9208,  "LightSource"},
  {   0x9209,  "Flash"},
  {   0x920A,  "FocalLength"},
  {   0x927C,  "MakerNote"},
  {   0x9286,  "UserComment"},
  {   0x9290,  "SubSecTime"},
  {   0x9291,  "SubSecTimeOriginal"},
  {   0x9292,  "SubSecTimeDigitized"},
  {   0xA000,  "FlashPixVersion"},
  {   0xA001,  "ColorSpace"},
  {   0xA002,  "ExifImageWidth"},
  {   0xA003,  "ExifImageLength"},
  {   0xA005,  "InteroperabilityOffset"},
  {   0xA20B,  "FlashEnergy"},                 // 0x920B in TIFF/EP
  {   0xA20C,  "SpatialFrequencyResponse"},  // 0x920C    -  -
  {   0xA20E,  "FocalPlaneXResolution"},     // 0x920E    -  -
  {   0xA20F,  "FocalPlaneYResolution"},      // 0x920F    -  -
  {   0xA210,  "FocalPlaneResolutionUnit"},  // 0x9210    -  -
  {   0xA214,  "SubjectLocation"},             // 0x9214    -  -
  {   0xA215,  "ExposureIndex"},            // 0x9215    -  -
  {   0xA217,  "SensingMethod"},            // 0x9217    -  -
  {   0xA300,  "FileSource"},
  {   0xA301,  "SceneType"},
  {      0, NULL}
} ;



//--------------------------------------------------------------------------
// Convert a 16 bit unsigned value from file's native byte order
//--------------------------------------------------------------------------
static int Get16u(void * Short)
{
    if (MotorolaOrder){
        return (((uchar *)Short)[0] << 8) | ((uchar *)Short)[1];
    }else{
        return (((uchar *)Short)[1] << 8) | ((uchar *)Short)[0];
    }
}

//--------------------------------------------------------------------------
// Convert a 32 bit signed value from file's native byte order
//--------------------------------------------------------------------------
static int Get32s(void * Long)
{
    if (MotorolaOrder){
        return  ((( char *)Long)[0] << 24) | (((uchar *)Long)[1] << 16)
              | (((uchar *)Long)[2] << 8 ) | (((uchar *)Long)[3] << 0 );
    }else{
        return  ((( char *)Long)[3] << 24) | (((uchar *)Long)[2] << 16)
              | (((uchar *)Long)[1] << 8 ) | (((uchar *)Long)[0] << 0 );
    }
}

//--------------------------------------------------------------------------
// Convert a 32 bit unsigned value from file's native byte order
//--------------------------------------------------------------------------
static unsigned Get32u(void * Long)
{
    return (unsigned)Get32s(Long) & 0xffffffff;
}

//--------------------------------------------------------------------------
// Display a number as one of its many formats
//--------------------------------------------------------------------------
static void PrintFormatNumber(void * ValuePtr, int Format)
{
    switch(Format){
        case FMT_SBYTE:
        case FMT_BYTE:      printf("%02x ",*(uchar *)ValuePtr);             break;
        case FMT_USHORT:    printf("%d\n",Get16u(ValuePtr));                break;
        case FMT_ULONG:     
        case FMT_SLONG:     printf("%d\n",Get32s(ValuePtr));                break;
        case FMT_SSHORT:    printf("%hd\n",(signed short)Get16u(ValuePtr)); break;
        case FMT_URATIONAL:
        case FMT_SRATIONAL: 
           printf("%d/%d\n",Get32s(ValuePtr), Get32s(4+(char *)ValuePtr)); break;

        case FMT_SINGLE:    printf("%f\n",(double)*(float *)ValuePtr);   break;
        case FMT_DOUBLE:    printf("%f\n",*(double *)ValuePtr);          break;
    }
}


//--------------------------------------------------------------------------
// Evaluate number, be it int, rational, or float from directory.
//--------------------------------------------------------------------------
static double ConvertAnyFormat(void * ValuePtr, int Format)
{
    double Value;
    Value = 0;

    switch(Format){
        case FMT_SBYTE:     Value = *(signed char *)ValuePtr;  break;
        case FMT_BYTE:      Value = *(uchar *)ValuePtr;        break;

        case FMT_USHORT:    Value = Get16u(ValuePtr);          break;
        case FMT_ULONG:     Value = Get32u(ValuePtr);          break;

        case FMT_URATIONAL:
        case FMT_SRATIONAL: 
            {
                int Num,Den;
                Num = Get32s(ValuePtr);
                Den = Get32s(4+(char *)ValuePtr);
                if (Den == 0){
                    Value = 0;
                }else{
                    Value = (double)Num/Den;
                }
                break;
            }

        case FMT_SSHORT:    Value = (signed short)Get16u(ValuePtr);  break;
        case FMT_SLONG:     Value = Get32s(ValuePtr);                break;

        // Not sure if this is correct (never seen float used in Exif format)
        case FMT_SINGLE:    Value = (double)*(float *)ValuePtr;      break;
        case FMT_DOUBLE:    Value = *(double *)ValuePtr;             break;
    }
    return Value;
}

//--------------------------------------------------------------------------
// Process one of the nested EXIF directories.
//--------------------------------------------------------------------------
static void ProcessExifDir(char * DirStart, char * OffsetBase, unsigned ExifLength)
{
    int de;
    int a;
    int NumDirEntries;

    NumDirEntries = Get16u(DirStart);

    if ((DirStart+2+NumDirEntries*12) > (OffsetBase+ExifLength)){
        ErrExit("Illegally sized directory");
    }

    if (ShowTags){
        printf("Directory with %d entries\n",NumDirEntries);
    }

    for (de=0;de<NumDirEntries;de++){
        int Tag, Format, Components;
        char * ValuePtr;
        int ByteCount;
        char * DirEntry;
        DirEntry = DirStart+2+12*de;

        Tag = Get16u(DirEntry);
        Format = Get16u(DirEntry+2);
        Components = Get32u(DirEntry+4);

        if ((Format-1) >= NUM_FORMATS) {
            // (-1) catches illegal zero case as unsigned underflows to positive large.
            ErrExit("Illegal format code in EXIF dir");
        }

        ByteCount = Components * BytesPerFormat[Format];

        if (ByteCount > 4){
            unsigned OffsetVal;
            OffsetVal = Get32u(DirEntry+8);
            // If its bigger than 4 bytes, the dir entry contains an offset.
            if (OffsetVal+ByteCount > ExifLength){
                // Bogus pointer offset and / or bytecount value
                printf("Offset %d bytes %d ExifLen %d\n",OffsetVal, ByteCount, ExifLength);

                ErrExit("Illegal pointer offset value in EXIF");
            }
            ValuePtr = OffsetBase+OffsetVal;
        }else{
            // 4 bytes or less and value is in the dir entry itself
            ValuePtr = DirEntry+8;
        }

        if (LastExifRefd < ValuePtr+ByteCount){
            // Keep track of last byte in the exif header that was actually referenced.
            // That way, we know where the discardable thumbnail data begins.
            LastExifRefd = ValuePtr+ByteCount;
        }

        if (ShowTags){
            // Show tag name
            for (a=0;;a++){
                if (TagTable[a].Tag == 0){
                    printf("  Unknown Tag %04x Value = ", Tag);
                    break;
                }
                if (TagTable[a].Tag == Tag){
                    printf("    %s = ",TagTable[a].Desc);
                    break;
                }
            }

            // Show tag value.
            switch(Format){

                case FMT_UNDEFINED:
                    // Undefined is typically an ascii string.

                case FMT_STRING:
                    // String arrays printed without function call (different from int arrays)
                    printf("\"");
                    for (a=0;a<ByteCount;a++){
                        if (isprint((ValuePtr)[a])){
                            putchar((ValuePtr)[a]);
                        }
                    }
                    printf("\"\n");
                    break;

                default:
                    // Handle arrays of numbers later (will there ever be?)
                    PrintFormatNumber(ValuePtr, Format);
            }
        }

        // Extract useful components of tag
        switch(Tag){

            case TAG_MAKE:
                strncpy(ImageInfo.CameraMake, ValuePtr, 31);
                break;

            case TAG_MODEL:
                strncpy(ImageInfo.CameraModel, ValuePtr, 39);
                break;

            case TAG_DATETIME_ORIGINAL:
                strncpy(ImageInfo.DateTime, ValuePtr, 19);
                break;

            case TAG_USERCOMMENT:
                // Olympus has this padded with trailing spaces.  Remove these first.
                for (a=ByteCount;;){
                    a--;
                    if ((ValuePtr)[a] == ' '){
                        (ValuePtr)[a] = '\0';
                    }else{
                        break;
                    }
                    if (a == 0) break;
                }

                // Copy the comment
                if (memcmp(ValuePtr, "ASCII",5) == 0){
                    for (a=5;a<10;a++){
                        int c;
                        c = (ValuePtr)[a];
                        if (c != '\0' && c != ' '){
                            strncpy(ImageInfo.Comments, a+ValuePtr, 199);
                            break;
                        }
                    }
                    
                }else{
                    strncpy(ImageInfo.Comments, ValuePtr, 199);
                }
                break;

            case TAG_FNUMBER:
                // Simplest way of expressing aperture, so I trust it the most.
                // (overwrite previously computd value if there is one)
                ImageInfo.ApertureFNumber = (float)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_APERTURE:
            case TAG_MAXAPERTURE:
                // More relevant info always comes earlier, so only use this field if we don't 
                // have appropriate aperture information yet.
                if (ImageInfo.ApertureFNumber == 0){
                    ImageInfo.ApertureFNumber 
                        = (float)exp(ConvertAnyFormat(ValuePtr, Format)*log(2)*0.5);
                }
                break;

            case TAG_FOCALLENGTH:
                // Nice digital cameras actually save the focal length as a function
                // of how farthey are zoomed in.
                ImageInfo.FocalLength = (float)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_SUBJECT_DISTANCE:
                // Inidcates the distacne the autofocus camera is focused to.
                // Tends to be less accurate as distance increases.
                ImageInfo.Distance = (float)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_EXPOSURETIME:
                // Simplest way of expressing exposure time, so I trust it most.
                // (overwrite previously computd value if there is one)
                ImageInfo.ExposureTime = (float)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_SHUTTERSPEED:
                // More complicated way of expressing exposure time, so only use
                // this value if we don't already have it from somewhere else.
                if (ImageInfo.ExposureTime == 0){
                    ImageInfo.ExposureTime 
                        = (float)(1/exp(ConvertAnyFormat(ValuePtr, Format)*log(2)));
                }
                break;

            case TAG_FLASH:
                if (ConvertAnyFormat(ValuePtr, Format)){
                    ImageInfo.FlashUsed = 1;
                }
                break;

            case TAG_EXIF_IMAGELENGTH:
            case TAG_EXIF_IMAGEWIDTH:
                // Use largest of height and width to deal with images that have been
                // rotated to portrait format.
                a = (int)ConvertAnyFormat(ValuePtr, Format);
                if (ExifImageWidth < a) ExifImageWidth = a;
                break;

            case TAG_FOCALPLANEXRES:
                FocalplaneXRes = ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_FOCALPLANEUNITS:
                switch((int)ConvertAnyFormat(ValuePtr, Format)){
                    case 1: FocalplaneUnits = 25.4; break; // inch
                    case 2: 
                        // According to the information I was using, 2 means meters.
                        // But looking at the Cannon powershot's files, inches is the only
                        // sensible value.
                        FocalplaneUnits = 25.4;
                        break;

                    case 3: FocalplaneUnits = 10;   break;  // centimeter
                    case 4: FocalplaneUnits = 1;    break;  // milimeter
                    case 5: FocalplaneUnits = .001; break;  // micrometer
                }
                break;

                // Remaining cases contributed by: Volker C. Schoech (schoech@gmx.de)

            case TAG_EXPOSURE_BIAS:
                ImageInfo.ExposureBias = (float)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_WHITEBALANCE:
                ImageInfo.Whitebalance = (int)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_METERING_MODE:
                ImageInfo.MeteringMode = (int)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_EXPOSURE_PROGRAM:
                ImageInfo.ExposureProgram = (int)ConvertAnyFormat(ValuePtr, Format);
                break;

            case TAG_ISO_EQUIVALENT:
                ImageInfo.ISOequivalent = (int)ConvertAnyFormat(ValuePtr, Format);
                if ( ImageInfo.ISOequivalent < 80 ) ImageInfo.ISOequivalent *= 200;
                break;

            case TAG_COMPRESSION_LEVEL:
                ImageInfo.CompressionLevel = (int)ConvertAnyFormat(ValuePtr, Format);
                break;
        }

        if (Tag == TAG_EXIF_OFFSET || Tag == TAG_INTEROP_OFFSET){
            char * SubdirStart;
            SubdirStart = OffsetBase + Get32u(ValuePtr);
            if (SubdirStart < OffsetBase || SubdirStart > OffsetBase+ExifLength){
                ErrExit("Illegal subdirectory link");
            }
            ProcessExifDir(SubdirStart, OffsetBase, ExifLength);
            continue;
        }
    }
}

//--------------------------------------------------------------------------
// Process a EXIF marker
// Describes all the drivel that most digital cameras include...
//--------------------------------------------------------------------------
void process_EXIF (char * CharBuf, unsigned int length)
{
    ImageInfo.FlashUsed = 0; // If it s from a digicam, and it used flash, it says so.

    FocalplaneXRes = 0;
    FocalplaneUnits = 0;
    ExifImageWidth = 0;

    if (ShowTags){
        printf("Exif header %d bytes long\n",length);
    }

    {   // Check the EXIF header component
        static const uchar ExifHeader[] = {0x45, 0x78, 0x69, 0x66, 0x00, 0x00};
        if (memcmp(CharBuf+2, ExifHeader,6)){
            ErrExit("Incorrect Exif header");
        }
    }

    if (memcmp(CharBuf+8,"II",2) == 0){
        if (ShowTags) printf("Exif section in Intel order\n");
        MotorolaOrder = 0;
    }else{
        if (memcmp(CharBuf+8,"MM",2) == 0){
            if (ShowTags) printf("Exif section in Motorola order\n");
            MotorolaOrder = 1;
        }else{
            ErrExit("Invalid Exif alignment marker.");
        }
    }

    // Check the next two values for correctness.
    if (Get16u(CharBuf+10) != 0x2a
      || Get32u(CharBuf+12) != 0x08){
        ErrExit("Invalid Exif start (1)");
    }

    LastExifRefd = CharBuf;

    // First directory starts 16 bytes in.  Offsets start at 8 bytes in.
    ProcessExifDir(CharBuf+16, CharBuf+8, length-6);

    // This is how far the interesting (non thumbnail) part of the exif went.
    ExifNonThumbnailLength = LastExifRefd - CharBuf;

    // Compute the CCD width, in milimeters.
    if (FocalplaneXRes != 0){
        ImageInfo.CCDWidth = (float)(ExifImageWidth * FocalplaneUnits / FocalplaneXRes);
    }

    if (ShowTags){
        printf("Thunbnail size of Exif header: %d\n",length-ExifNonThumbnailLength);
    }
}

//--------------------------------------------------------------------------
// Figure out how much of the exif to keep
//--------------------------------------------------------------------------
int GetExifNonThumbnailSize(void)
{
    // This function must be called after ProcessExif has been called!
    return ExifNonThumbnailLength;
}


//--------------------------------------------------------------------------
// Convert exif time to Unix time structure
//--------------------------------------------------------------------------
int Exif2tm(struct tm * timeptr, char * ExifTime)
{
    int a;

    timeptr->tm_wday = -1;

    // Check for format: YYYY:MM:DD HH:MM:SS format.
    a = sscanf(ExifTime, "%d:%d:%d %d:%d:%d",
            &timeptr->tm_year, &timeptr->tm_mon, &timeptr->tm_mday,
            &timeptr->tm_hour, &timeptr->tm_min, &timeptr->tm_sec);
        
    if (a == 6){
        timeptr->tm_isdst = 0;     // Should ideally be set to what it was when the 
                                   // image was taken, I think? 

        timeptr->tm_mon -= 1;      // Adjust for unix zero-based months 
        timeptr->tm_year -= 1900;  // Adjust for year starting at 1900 
        return TRUE; // worked. 
    }

    return FALSE; // Wasn't in Exif date format.
}

//--------------------------------------------------------------------------
// Show the collected image info, displaying camera F-stop and shutter speed
// in a consistent and legible fashion.
//--------------------------------------------------------------------------
void ShowImageInfo(void)
{
    int a;
    printf("File name    : %s\n",ImageInfo.FileName);
    printf("File size    : %d bytes\n",ImageInfo.FileSize);

    {
        char Temp[20];
        struct tm ts;
        ts = *localtime(&ImageInfo.FileDateTime);
        strftime(Temp, 20, "%Y:%m:%d %H:%M:%S", &ts);
        printf("File date    : %s\n",Temp);
    }

    if (ImageInfo.CameraMake[0]){
        printf("Camera make  : %s\n",ImageInfo.CameraMake);
        printf("Camera model : %s\n",ImageInfo.CameraModel);
    }
    if (ImageInfo.DateTime[0]){
        printf("Date/Time    : %s\n",ImageInfo.DateTime);
    }
    printf("Resolution   : %d x %d\n",ImageInfo.Width, ImageInfo.Height);
    if (ImageInfo.IsColor == 0){
        printf("Color/bw     : Black and white\n");
    }
    if (ImageInfo.FlashUsed >= 0){
        printf("Flash used   : %s\n",ImageInfo.FlashUsed ? "Yes" :"No");
    }
    if (ImageInfo.FocalLength){
        printf("Focal length : %4.1fmm",(double)ImageInfo.FocalLength);
        if (ImageInfo.CCDWidth){
            printf("  (35mm equivalent: %dmm)",
                        (int)(ImageInfo.FocalLength/ImageInfo.CCDWidth*35 + 0.5));
        }
        printf("\n");
    }

    if (ImageInfo.CCDWidth){
        printf("CCD Width    : %4.2fmm\n",(double)ImageInfo.CCDWidth);
    }

    if (ImageInfo.ExposureTime){
        printf("Exposure time:%6.3f s ",(double)ImageInfo.ExposureTime);
        if (ImageInfo.ExposureTime <= 0.5){
            printf(" (1/%d)",(int)(0.5 + 1/ImageInfo.ExposureTime));
        }
        printf("\n");
    }
    if (ImageInfo.ApertureFNumber){
        printf("Aperture     : f/%3.1f\n",(double)ImageInfo.ApertureFNumber);
    }
    if (ImageInfo.Distance){
        if (ImageInfo.Distance < 0){
            printf("Focus Dist.  : Infinite\n");
        }else{
            printf("Focus Dist.  :%5.2fm\n",(double)ImageInfo.Distance);
        }
    }





    if (ImageInfo.ISOequivalent){ // 05-jan-2001 vcs
        printf("ISO equiv.   : %2d\n",(int)ImageInfo.ISOequivalent);
    }
    if (ImageInfo.ExposureBias){ // 05-jan-2001 vcs
        printf("Exposure bias:%4.2f\n",(double)ImageInfo.ExposureBias);
    }
        
    if (ImageInfo.Whitebalance){ // 05-jan-2001 vcs
        switch(ImageInfo.Whitebalance) {
        case 1:
            printf("Whitebalance : sunny\n");
            break;
        case 2:
            printf("Whitebalance : fluorescent\n");
            break;
        case 3:
            printf("Whitebalance : incandescent\n");
            break;
        default:
            printf("Whitebalance : cloudy\n");
        }
    }
    if (ImageInfo.MeteringMode){ // 05-jan-2001 vcs
        switch(ImageInfo.MeteringMode) {
        case 2:
            printf("Metering Mode: center weight\n");
            break;
        case 3:
            printf("Metering Mode: spot\n");
            break;
        case 5:
            printf("Metering Mode: matrix\n");
            break;
        }
    }
    if (ImageInfo.ExposureProgram){ // 05-jan-2001 vcs
        switch(ImageInfo.ExposureProgram) {
        case 2:
            printf("Exposure     : program (auto)\n");
            break;
        case 3:
            printf("Exposure     : aperture priority (semi-auto)\n");
            break;
        case 4:
            printf("Exposure     : shutter priority (semi-auto)\n");
            break;
        }
    }
    if (ImageInfo.CompressionLevel){ // 05-jan-2001 vcs
        switch(ImageInfo.CompressionLevel) {
        case 1:
            printf("JPG Quality  : basic\n");
            break;
        case 2:
            printf("JPG Quality  : normal\n");
            break;
        case 4:
            printf("JPG Quality  : fine\n");
            break;
       }
    }

         

    for (a=0;;a++){
        if (ProcessTable[a].Tag == ImageInfo.Process || ProcessTable[a].Tag == 0){
            printf("Jpeg process : %s\n",ProcessTable[a].Desc);
            break;
        }
    }



    // Print the comment. Print 'Comment:' for each new line of comment.
    if (ImageInfo.Comments[0]){
        int a,c;
        printf("Comment      : ");
        for (a=0;a<200;a++){
            c = ImageInfo.Comments[a];
            if (c == '\0') break;
            if (c == '\n'){
                printf("\nComment      : ");
            }else{
                putchar(c);
            }
        }
        printf("\n");
    }

    printf("\n");
}


//--------------------------------------------------------------------------
// Summarize highlights of image info on one line (suitable for grep-ing)
//--------------------------------------------------------------------------
void ShowConciseImageInfo(void)
{
    printf("\"%s\"",ImageInfo.FileName);

    printf(" %dx%d",ImageInfo.Width, ImageInfo.Height);

    if (ImageInfo.ExposureTime){
        printf(" (1/%d)",(int)(0.5 + 1/ImageInfo.ExposureTime));
    }

    if (ImageInfo.ApertureFNumber){
        printf(" f/%3.1f",(double)ImageInfo.ApertureFNumber);
    }

    if (ImageInfo.FocalLength){
        if (ImageInfo.CCDWidth){
            // 35 mm equivalent focal length.
            printf(" f(35)=%dmm",(int)(ImageInfo.FocalLength/ImageInfo.CCDWidth*35 + 0.5));
        }
    }

    if (ImageInfo.FlashUsed > 0){
        printf(" (flash)");
    }

    if (ImageInfo.IsColor == 0){
        printf(" (bw)");
    }

    printf("\n");
}
