# $Id$
#
# Makefile for jhead
#
#     MiG - http://mig.sourceforge.net/
#     Dan Lowe <dan@tangledhelix.com>
#
# jhead is NOT my software.  It was written by Matthias Wendel.
# You can find the official jhead web page at
#
#     http://www.sentex.net/~mwandel/jhead/index.html
#

CC=	gcc
LIBS=	-lm
CFLAGS=	-O3

default: jhead

jhead:
	@echo " "
	@echo "jhead - written by Matthias Wendel"
	@echo "    http://www.sentex.net/~mwandel/jhead/index.html"
	@echo "    jhead is public domain software."
	@echo " "
	$(CC) $(LIBS) $(CFLAGS) -o jhead jhead.c exif.c

clean:
	/bin/rm -f jhead

