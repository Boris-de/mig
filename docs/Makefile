#
# $Id$
#
# Makefile for Mig documentation
#

hcmd=	pod2html
tcmd=	pod2text
hargs=	--noindex --title="Mig Documentation"

# HTML files
html=	changelog.html credits.html install.html license.html \
	passwords.html jump.html apache.html phpnuke.html \
	utilities.html mig_cf.html templates.html

# Text files
text=	changelog.txt credits.txt install.txt license.txt \
	passwords.txt jump.txt apache.txt phpnuke.txt \
	utilities.txt mig_cf.txt templates.txt

# Default is to rebuild doc files
docs: $(html) $(text) sort clean

$(html): %.html: %.pod
	$(hcmd) $(hargs) \
		--infile=$< --outfile=$@

$(text): %.txt: %.pod
	$(tcmd) $< $@

sort:
	mkdir -p html text
	mv *.html html
	mv *.txt text

clean:
	/bin/rm -f pod2htm*
