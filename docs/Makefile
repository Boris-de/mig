# $Id$
#
# Makefile for MiG documentation
#

HTML=	/usr/perl5/5.00503/bin/pod2html
TEXT=	/usr/perl5/5.00503/bin/pod2text
TITLE=	MiG Documentation

default: all

all:
	$(HTML) --title="$(TITLE)" --noindex < POD/CHANGELOG.pod \
		> html/CHANGELOG.html
	$(TEXT) POD/CHANGELOG.pod > text/CHANGELOG.txt


