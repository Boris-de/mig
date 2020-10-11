#!/bin/sh
#
# Written and contributed by Dan Lowe <dan@tangledhelix.com>
#
# I use this when uploading my images.  My digital camera outputs every
# picture the same size, so I just call this with something like:
#
#   for i in *jpg; do gallery.sh $i; done
#
# After that I use "mkGallery.pl" to create the thumbnails.
#
# This basically reduces the image to half the original size and puts a
# 4-pixel black border around it.
#
# It requires mogrify, which is part of the ImageMagick suite.
# http://www.imagemagick.org/
#
# Actually I don't use this anymore, I use Photoshop.  But I used to
# use this and it didn't work too badly for me when I did.
#

echo "processing $1 ..."
mogrify -border 8x8 -sample 50%x50% -bordercolor black "${1}"

