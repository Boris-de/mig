Mig - Utilities
===============

## Contributed Utilities

`mkGallery.pl` - Create a gallery from images in the current directory.
This was a bundled utility that was moved to a separate repository
that can be found at [https://github.com/Boris-de/mig-contrib](https://github.com/Boris-de/mig-contrib).

## mkGallery.pl

`mkGallery.pl` can do three things:

1\. Take a directory full of images and create thumbnails for them.

2\. Read EXIF header information from image files and cache it in
text files for Mig to use.

3\. Create blank comment templates in `mig.cf` files.

It can do all of them at the same time, too.  It can accept a list of
images to work with, or simply process all images in the current directory.
It can also recurse directories and do an entire album.

`mkGallery.pl` ignores audio and video content.  It only cares about
images.

(Note that `mkGallery.pl` requires Perl.  Perl is not required for Mig
itself.  It is required only to use the `mkGallery.pl` utility.
To generate thumbnails, the `convert` utility from
the ImageMagick suite is also required.)

`mkGallery.pl` uses the following Perl modules.  When I first wrote
`mkGallery.pl` I was using Perl 5.005\_03, and these were all included (no
extra installations were needed).

    strict
    Cwd
    File::Basename
    File::Find
    Getopt::Std

`mkGallery.pl` has not been tested under Microsoft Windows by the author.

       Usage:

       mkGallery.pl [ -h ] [ -a ] [ -w ] [ -t ] [ -e ] [ -c ] [ -i ]
           [ -s <size> ] [ -q <quality> ] [ -M <type> ] [ -m <label> ]
           [ -n ] [ -r ] [ -d ] [ -D <dir> ] [ -E <ext> ] [ -f <file> ]
           [ <file1> <file2> <...> ]

         -h : Prints this help message.
         -f : Use alternate configuration file (config.php)
         -a : Process all image files in current directory.
         -w : Turn over-write on.  By default, files written such
              as the EXIF file will be appended to rather than
              over-written.  Using "-w" indicates the file should
              be over-written instead.
         -t : Generate thumbnail images.
         -e : Build "exif.inf" file.  You must compile the jhead
              utility (included) before you can use the -e option.
         -c : Generate blank comments for uncommented images.
         -i : "Interactive" mode for comments.
         -s : Set pixel size for thumbnails.
         -q : Set quality level for thumbnails.
         -M : Define type of "prefix" or "suffix".
         -m : thumbnail marker label (default "th").
         -n : Only process thumbnails that don't exist (new-only).
              Will also process thumbnails which are older than the
              full-size images they are associated with.
              If using with -e, only files not already cached
              in exif.inf will be processed for EXIF data.
         -r : Recursive mode - process this folder as well as any
              folders and subfolders beneath it.
         -d : Use thumbnail subdirectories (instead of using _th, etc)
         -D : Name of thumbnail subdirectory to use (default is "thumbs" or
              whatever is in your config.php file).
         -E : File extension to use for thumbnails.
         -K : Keep profiles in thumbnails.  Normally this should
              be off because profiles in thumbnails are not useful
              but add a lot to the file size.

    * If creating thumbnails, "convert" must be in your $PATH.
    * This program supports JPEG, PNG and GIF formats.
    * The "-e" feature only supports JPEG files.
    * See the "utilities" document for more information.

      Mig - https://mig.wcht.de/
      

For people who don't like to type in the same options to `mkGallery.pl`
every time they use it, you may store memorized options in the file
`mkGallery.opt` (this goes in the same directory as `mkGallery.pl`).
Put the options in as you would type them at the command line.  For
example if you usually do this:

    ../../util/mkGallery -rantK
    

Then you would just put the '-rantK' in `mkGallery.opt`, and
`mkGallery.pl` will know to read it from that file.

## Generating EXIF files

You can generate EXIF cache files (exif.inf) in each album directory.  Just
use the `-e` flag.

**NOTE**: If you use `-e` without `-w`, your EXIF files will be appended
to, instead of overwritten.  In some circumstances this ends up making your
EXIF files grow and grow by appending the same data over and over again.
For this reason, `-e` should always be used with either `-w` or `-n`.
The author recommends `-n` as it reads the existing EXIF file to see what
was already cached, then only parses new images for data - this is more
efficient than erasing the file each time and starting over with all images.

## A note about pixel sizes

ImageMagick will be given a geometry of `SIZExSIZE` where `SIZE` is the
value passed to `mkGallery.pl` with the `-s` flag.  For instance,
specifying `-s 250` will give ImageMagick a geometry of `250x250`.  This
means it will create a thumbnail image where the maximum value of height
(or the maximum value of width) is 250 pixels.  It will not exceed that
value for either width or height.  However, it will maintain the aspect
ratio of the image.

Here are some examples I got from testing using the default setting which
is 100.

    Original size     Thumbnail size

      1280 x 960        100 x  75
       505 x 250        100 x  49
       347 x 202        100 x  58
       160 x 205         78 x 100

## A note about quality levels

Some image formats such as JPEG can have varying quality levels.  The
default level is 50 but any value from 1 to 100 is valid.  The
higher the number, the better the quality of the thumbnail.  I have found
that 50 is a good number and produces a fairly clear thumbnail from even
large detailed photographs yet still keeping a very good file size
(usually below 2.5K).

A quality level can be specified with `-q number`.

## Thumbnails

Thumbnails are stored in a subdirectory called `thumbs` by
default.  (This can be changed - see the `$thumbSubdir` option in
`config.php`.)

A thumbnail has the same filename as the full-size image it represents.
An image called `img_4190.jpg` would have a thumbnail file
called `thumbs/img_4190.jpg`.

It is not necessary to create the directories ahead of time -
`mkGallery.pl` will create any directories it needs if they don't already
exist.

## Interactive mode

When using the `-c` option to generate comment fields in `mig.cf`, you can
also optionally specify `-i` or Interactive mode.  Basically all this will
do is prompt for each image which does not already have a comment, and
you can (optionally) type in a comment for that image.  If you don't want
to do so, just hit `Enter` and that image will be skipped - `mkGallery.pl`
will move along to the next one in the list.

## New-only mode

The new-only mode (`-n`) basically follows this set of rules.

1\. If an image file exists without a thumbnail associated with it,
generate a thumbnail for it.

2\. If an image file exists, has a thumbnail, but is newer than its
thumbnail file, re-generate the thumbnail.

If `-n` is not specified, a new thumbnail will be generated for every
image.

If using `-n` along with `-e` (EXIF mode), only images not already
cached in the exif.inf file will be processed for EXIF data.  If `-w`
is also being used this is not the case, since the exif.inf file is
removed before processing takes place (so no cache is present).

## Recursive mode

Recursive mode makes it simple to do large chunks of an album.  For
example, if a new folder has been added called `Travel` and there are a
total of 17 folders that are located underneath it (as subdirectories, or
sub-subdirectories), handling each one would be tedious.  It's easier to
just go to the top of the tree you want to work on (in this case `Travel`)
and invoke `mkGallery.pl` with the recursive flag (`-r`).

New users can simply go to their root (`albums`) and invoke `mkGallery.pl
\-art`, and `mkGallery.pl` will generate thumbnails for everything in the
entire gallery.

Personally I find `-rant` to be the most useful combination of modes.
It's also easy to remember.  Add -e for EXIF parsing (`-trane`).

Recursive mode works with all the action modes (`-c`, `-e`, `-t`).

### jhead

`jhead` was written by Matthias Wandel and can be found at:

    https://www.sentex.net/~mwandel/jhead/index.html

It should be available in virtually every distribution these days and
a Windows version can be downloaded from the author's website.

(`jhead` is not something you need to run directly - it is used by
`mkGallery.pl` if you invoke `mkGallery.pl` with the `-e` flag.)
