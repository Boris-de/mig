#
# $Revision$
#
# Makefile for Mig documentation
#

compile= pod2html --noindex --title="Mig Documentation"

objects= install.html changelog.html

all: $(objects)

$(objects): %.html: %.pod
	$(compile) --infile=$< --outfile=$@

clean:
	/bin/rm -f pod2htm* *html

