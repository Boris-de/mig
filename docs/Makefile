#
# $Revision$
#
# Makefile for Mig documentation
#

hcmd=	pod2html
tcmd=	pod2text
hargs=	--noindex --title="Mig Documentation"

# HTML files
html=	install.html

# Text files
text=	install.txt

# Default is to rebuild doc files
docs: $(html) $(text) sort clean

$(html): %.html: %.pod
	$(hcmd) $(hargs) --infile=$< --outfile=$@

$(text): %.txt: %.pod
	$(tcmd) $< $@

sort:
	mkdir -p html text
	mv *.html html
	mv *.txt text

clean:
	/bin/rm -f pod2htm*
