Notes on Jhead

Compiling:
    Under Windows, with Microsoft Visual C on your path, type:
    cl -Ox jhead.c exif.c myglob.c

    Under Linux, type:
    cc -lm -O3 -o jhead jhead.c exif.c 

Liscence:

    Jhead is public domain software - that is, you can do whatever you want
    with it, and include it software that is liscensed under the GNU or the 
    BSD liscence, or whatever other liscence you chose.
    If you do integrate the code into some software of yours, I'd appreciate
    knowing about it though. You can reach me at matt@rim.net

Matthias Wandel


Change notes:
1.1 --> 1.2
* Now recognizes more Exif tags (Contributions by Volker C Schoen)
* One hour off on -ft option fixed (uninitialized DST structure element)
* More flexible date-renaming option using strftime function
* -n and -nf option no longer overwrite pre-existing target names under Unix
