//--------------------------------------------------------------------------
// Include file for jhead program.
//
// This include file only defines stuff that goes across modules.  I like
// to keep the definitions for macros and structures as close to where
// they get used as possible, so include files only get stuff that gets used
// in more than one file.
//--------------------------------------------------------------------------

typedef unsigned char uchar;

#ifndef TRUE
    #define TRUE 1
    #define FALSE 0
#endif

#define MAX_COMMENT 1000

#ifndef _WIN32
    // Don't know what the unix equivalent of _MAX_PATH is.
    // I hope this is long enough.
    #define _MAX_PATH 300
#endif


//--------------------------------------------------------------------------
// This structure stores Exif header image elements in a simple manner
// Used to store camera data as extracted from the various ways that it can be
// stored in an exif header
typedef struct {
    char  FileName     [_MAX_PATH+1];
    time_t FileDateTime;
    unsigned FileSize;
    char  CameraMake   [32];
    char  CameraModel  [40];
    char  DateTime     [20];
    int   Height, Width;
    int   IsColor;
    int   Process;
    int   FlashUsed;
    float FocalLength;
    float ExposureTime;
    float ApertureFNumber;
    float Distance;
    float CCDWidth;
    float ExposureBias;
    int   Whitebalance;
    int   MeteringMode;
    int   ExposureProgram;
    int   ISOequivalent;
    int   CompressionLevel;
    char  Comments[MAX_COMMENT];
}ImageInfo_t;


#define EXIT_FAILURE  1
#define EXIT_SUCCESS  0



// prototypes for jhead.c functions
extern void ErrExit(char * msg);

// Prototypes for exif.c functions.
extern int Exif2tm(struct tm * timeptr, char * ExifTime);
extern void process_EXIF (char * CharBuf, unsigned int length);
extern int GetExifNonThumbnailSize(void);

// Prototype for myglob.c module
extern void MyGlob(const char * Pattern , void (*FileFuncParm)(const char * FileName));


// Variables from jhead.c used by exif.c
extern ImageInfo_t ImageInfo;
extern int ShowTags;
extern void ShowImageInfo(void);
extern void ShowConciseImageInfo(void);



//--------------------------------------------------------------------------
// JPEG markers consist of one or more 0xFF bytes, followed by a marker
// code byte (which is not an FF).  Here are the marker codes of interest
// in this program.  (See jdmarker.c for a more complete list.)
//--------------------------------------------------------------------------

#define M_SOF0  0xC0            // Start Of Frame N
#define M_SOF1  0xC1            // N indicates which compression process
#define M_SOF2  0xC2            // Only SOF0-SOF2 are now in common use
#define M_SOF3  0xC3
#define M_SOF5  0xC5            // NB: codes C4 and CC are NOT SOF markers
#define M_SOF6  0xC6
#define M_SOF7  0xC7
#define M_SOF9  0xC9
#define M_SOF10 0xCA
#define M_SOF11 0xCB
#define M_SOF13 0xCD
#define M_SOF14 0xCE
#define M_SOF15 0xCF
#define M_SOI   0xD8            // Start Of Image (beginning of datastream)
#define M_EOI   0xD9            // End Of Image (end of datastream)
#define M_SOS   0xDA            // Start Of Scan (begins compressed data)
#define M_JFIF  0xE0            // Jfif marker
#define M_EXIF  0xE1            // Exif marker
#define M_COM   0xFE            // COMment 
