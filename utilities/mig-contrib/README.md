## mig contrib

This folder contains scripts and things that Mig users have contributed
back to the community.  They are for you to use and enjoy.

For a description of the different files, see index at the end of this file.

I make no guarantees about anything in this package.  Please exercise your
own judgement before blindly using them.  I will make an effort to validate
them and make sure they aren't blatantly destructive, but I didn't write
them (well, not all of them) and I can't say for sure what they will or
won't do.

If you have questions or comments on them, you can contact me, or the
author of the item in question (they should be identified in its contents)
or you can post to one of the Mig mailing lists.

They are provided as-is, and you may need to adjust them slightly to work in
your particular environment.

  Dan Lowe
  dan@tangledhelix.com


## Index

#### mkGallery.pl
  A script that was formally bundled as part of mig. It can be used to create
  a gallery from images in the current directory.
  You can check the documentation for [utilities](https://mig.wcht.de/docs/utilities.html)
  for details.
  (mkGallery.pl requires perl)

#### gallery.sh
  Automates resizing of image files using ImageMagick (mogrify).
  (gallery.sh requires a shell and a shell for mkGallery.pl)

#### migPutCommentInFile.sh
  Inserts comments into JPEG files.
  (migPutCommentInFile.sh requires a shell)

#### grabexif-0.1.tar.gz
  Grabexif generates exif.inf files for use by mig (mig.sf.net),
  from the html files found on the Casio QV-7000SX (and others?).
  (Grabexif requires a C compiler)