#
# $Revision$
#
# Makefile for Mig documentation
#

hcmd=	pod2html
tcmd=	pod2text
hargs=	--noindex --title="Mig Documentation"

# HTML files
html=	changelog.html credits.html license.html

# Text files
text=	changelog.txt credits.txt license.txt

# Default is to rebuild doc files
docs: $(html) $(text) sort clean

$(html): %.html: %.pod
	@echo "+ $@"
	@$(hcmd) $(hargs) --infile=$< --outfile=$@

$(text): %.txt: %.pod
	@echo "+ $@"
	@$(tcmd) $< $@

sort:
	@mkdir -p html text
	@mv *.html html
	@mv *.txt text

clean:
	@/bin/rm -f pod2htm*
