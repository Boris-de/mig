//--------------------------------------------------------------------------
// Module to do recursive directory file matching under windows, similar
// to using dir /s /b <pattern> on Win32.
// 
// Doesn't duplicate everything that I found when experimenting with dir /s,
// but will behave similar with most common parameters
// 
// Matthias Wandel Sept 29 2000
//--------------------------------------------------------------------------
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <ctype.h>
#include <io.h>

#include "jhead.h"


// Internal storage...
static void (*FileFunc)(const char * FileName); // Variable for function pointer.

//#define DEBUGGING

#ifdef DEBUGGING
//--------------------------------------------------------------------------
// Dummy function to show operation.
//--------------------------------------------------------------------------
void ShowName(const char * FileName)
{
    printf("     %s\n",FileName);
}
#endif

//--------------------------------------------------------------------------
// Simple path splicing (assumes no '\' in either part)
//--------------------------------------------------------------------------
static void CatPath(char * dest, const char * p1, const char * p2)
{
    int l;
    l = strlen(p1);
    if (!l){
        strcpy(dest, p2);
    }else{
        if (l+strlen(p2) > 200){
            fprintf(stderr,"Path too long\n");
            exit(-1);
        }
        memcpy(dest, p1, l+1);
        if (dest[l-1] != '\\'){
            dest[l++] = '\\';
        }
        strcpy(dest+l, p2);
    }
}
//--------------------------------------------------------------------------
// Match using findfirst - non recursive or at bottom of recursion.
//--------------------------------------------------------------------------
static void MatchFiles(const char * Path, const char * FilePattern)
{
    struct _finddata_t finddata;
    struct _finddata_t * FileList = NULL;
    int NumAllocated = 0;
    int NumFiles = 0;
    long find_handle;
    char ThisPattern[200];
    int a;

    #ifdef DEBUGGING
        printf("MatchFiles '%s' '%s'\n",Path,FilePattern);
    #endif

    CatPath(ThisPattern, Path, FilePattern);

    
    find_handle = _findfirst(ThisPattern, &finddata);

    for (;;){
        if (find_handle == -1) break;

        if (finddata.attrib & _A_SUBDIR) goto next_file; // Just files.

        // Got a matching file.

        // Instead of processing the files right away, make a list of matching files
        // for this directory.  That way, _findnext doesn't mess up, because it doesn't
        // expect the directory it is scanning to get modified.

        #ifdef DEBUGGING
            printf("Match:%s\n",finddata.name);
        #endif

        if (NumFiles >= NumAllocated){
            // Progressively allocate bigger chunks to store names.
            // (simpler and more efficient than building a linked list)
            NumAllocated = NumAllocated + 2 + NumAllocated / 4;
            FileList = realloc(FileList, NumAllocated * sizeof(struct _finddata_t));
            if (FileList == NULL){
                ErrExit("malloc failed");
            }
        }
        FileList[NumFiles++] = finddata;

        next_file:
        if (_findnext(find_handle, &finddata) != 0) break;
    }

    _findclose(find_handle);

    for (a=0;a<NumFiles;a++){
        static char CombinedPath[400];
        CatPath(CombinedPath, Path, FileList[a].name);
        FileFunc(CombinedPath);
    }

    free(FileList);    
}

//--------------------------------------------------------------------------
// Recursive match directories.
//--------------------------------------------------------------------------
static void RecurseDirs(const char * Path, const char * DirPattern, const char * FilePattern)
{
    struct _finddata_t finddata;
    long find_handle;
    char ThisPattern[200];

    #ifdef DEBUGGING
        printf("RecurseDir '%s' '%s' '%s'\n",Path, DirPattern, FilePattern);
    #endif

    // First check files that might match.
    if (strcmp(DirPattern, "*") == 0){
        MatchFiles(Path, FilePattern);
    }

    CatPath(ThisPattern, Path, DirPattern);

    find_handle = _findfirst(ThisPattern, &finddata);

    for (;;){
        char CombinedPath[200];
        if (find_handle == -1) break;

        if (!(finddata.attrib & _A_SUBDIR)) goto next_file; // Just directories.
        if (finddata.name[0] == '.'){
            if (finddata.name[1] == '.' || finddata.name[1] == '\0') goto next_file;
        }

        CatPath(CombinedPath, Path, finddata.name);

        // Matched directory once, no need to match again.
        RecurseDirs(CombinedPath, "*", FilePattern);

        next_file:
        if (_findnext(find_handle, &finddata) != 0) break;
    }

    _findclose(find_handle);
}
         
//--------------------------------------------------------------------------
// Decide how a particular pattern should be handled, and call function for each.
//--------------------------------------------------------------------------
void MyGlob(const char * Pattern , int DoSubdirs, void (*FileFuncParm)(const char * FileName))
{
    char DirPattern[200];
    char FilePattern[200];
    char Path[200];

    int i,a,b;
    int PrePat;

    FileFunc = FileFuncParm;

    PrePat = TRUE;

    // Path splits into three components: Fixed/Dirpattern/Filepattern
    // a = boundary between Fixed and Dirpattern
    // b = boundary between DirPattern and FilePattern

    b=a=-1;
    for (i=0;;i++){
        if (Pattern[i] == '?' || Pattern[i] == '*') PrePat = FALSE;
        if (Pattern[i] == '\\' || Pattern[i] == '\0'){
            if (Pattern[i] == '\\'){
                b = i;
            }
            if (PrePat) a = i;
        }
        if (Pattern[i] == 0) break;
    }

    if (b == -1){
        // No '\' in pattern.  Try to guess wether this means a directory or a file.
        strcpy(DirPattern, "*");
        strcpy(FilePattern, "*");
        Path[0] = 0;

        if (PrePat && DoSubdirs){
            strcpy(DirPattern, Pattern);
        }else{
            strcpy(FilePattern, Pattern);
        }
    }else{
        if (PrePat && !DoSubdirs){
            // The plain and simple case.  No pattern, no recursive, 
            // no guessing what this means.
            memcpy(Path, Pattern, b);
            Path[b] = 0;
            DirPattern[0] = 0;
            strcpy(FilePattern, Pattern+b+1);
        }else{

            if (b < a) b = a;

            if (a >= 0){
                memcpy(Path, Pattern, a);
                Path[a] = '\\';
                Path[a+1] = 0;
            }else{
                Path[a] = 0;
            }

            if (b > a){        
                memcpy(DirPattern, Pattern+a+1, b-a-1);
                DirPattern[b-a-1] = 0;
            }else{
                memcpy(DirPattern, "*", 2);
            }

            strcpy(FilePattern, Pattern+b+1);

            // Empty means everything.
            if (DirPattern[0] == '\0') memcpy(DirPattern, "*",2);
            if (FilePattern[0] == '\0') memcpy(FilePattern, "*",2);

            if (!DoSubdirs){
                if (a != b){
                    ErrExit("Illegal wildcard combo for non recursive");
                }
            }
        }
    }
    #ifdef DEBUGGING
        printf("Path:%s\n",Path);
        printf("Dir :%s\n",DirPattern);
        printf("File:%s\n",FilePattern);
    #endif

    if (DoSubdirs){
        RecurseDirs(Path, DirPattern, FilePattern);
    }else{
        MatchFiles(Path, FilePattern);
    }
}

/*
#ifdef DEBUGGING
//--------------------------------------------------------------------------
// The main program.
//--------------------------------------------------------------------------
int main (int argc, char **argv)
 {
    int argn;
    char * arg;
    int Subdirs = 0;

    for (argn=1;argn<argc;argn++){
        arg = argv[argn];
        if (arg[0] != '-') break; // Filenames from here on.
        if (!strcmp(arg,"-r")){
            printf("do recursive\n");
            Subdirs = 1;
        }else{
            fprintf(stderr, "Argument '%s' not understood\n",arg);
        }
    }
    if (argn == argc){
        fprintf(stderr,"Error: Must supply a file name\n");
    }

    for (;argn<argc;argn++){
        MyGlob(argv[argn], Subdirs, ShowName);
    }
    return EXIT_SUCCESS;
}
#endif
*/
