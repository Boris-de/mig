#
# $Id$
#
# Makefile for Mig documentation
#

hcmd=	pod2html --noindex
tcmd=	pod2text

# HTML files
html=	changelog.html credits.html install.html license.html \
	passwords.html jump.html apache.html phpnuke.html \
	utilities.html mig_cf.html templates.html

# Text files
text=	changelog.txt credits.txt install.txt license.txt \
	passwords.txt jump.txt apache.txt phpnuke.txt \
	utilities.txt mig_cf.txt templates.txt

# Default is to rebuild doc files
docs: mkdirs $(html) $(text) sort clean

$(html): %.html: %.pod
	$(hcmd) --title="Mig Documentation - $@" \
		--infile=$< --outfile=$@

$(text): %.txt: %.pod
	$(tcmd) $< $@

mkdirs:
	mkdir -p html text

sort:
	mv *.html html
	mv *.txt text

clean:
	rm -f pod2htm*

publish:
	scp html/changelog.html html/install.html mig.sf.net:web/htdocs
