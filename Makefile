#
# $Id$
#
# Makefile to build Mig distributions
#

# Path to (GNU) tar
TAR=/usr/bin/gnutar

# Where to keep distributions (directory)
DISTDIR=../bundles/mig

# Temporary directory to build a Mig install in (this gets tarred up)
SPOOLDIR=mig-$(ver)

# Archive name (output file)
ARCHIVE=$(DISTDIR)/$(SPOOLDIR).tar.gz

default:
	@echo "make dist ver={version}"

dist: index
	cd docs; make; cd ..
	rm -rf $(SPOOLDIR) $(ARCHIVE)
	mkdir -m 0755 -p $(DISTDIR) $(SPOOLDIR)
	cd $(SPOOLDIR); mkdir -m 0755 -p images templates/phpnuke \
		docs/text docs/html utilities/jhead
	mv index.php $(SPOOLDIR)
	cp config.php $(SPOOLDIR)/config.php.default
	cp utilities/mkGallery.pl $(SPOOLDIR)/utilities
	cd $(SPOOLDIR)/utilities; tar xfz ../../utilities/jhead.tar.gz; cd ..
	cp images/*.gif          $(SPOOLDIR)/images
	cp templates/*.[hc]*     $(SPOOLDIR)/templates
	cp templates/*.php       $(SPOOLDIR)/templates/phpnuke
	cp docs/html/*.html      $(SPOOLDIR)/docs/html
	cp docs/text/*.txt       $(SPOOLDIR)/docs/text
	find $(SPOOLDIR) -type d -exec chmod 0755 {} \;
	find $(SPOOLDIR) -type f -exec chmod 0644 {} \;
	chmod 0755 $(SPOOLDIR)/utilities/mkGallery.pl
	tar cfz $(ARCHIVE) $(SPOOLDIR)
	rm -rf $(SPOOLDIR)
	chmod 0644 $(ARCHIVE)

index:
	( echo '<?php'; sed "s/VeRsIoN/$(ver)/" main/preamble.php ;    \
	  cat main/defaults.php;                                       \
	  echo '//'; echo '// Function library'; echo '//';            \
	  cat functions/*.php;                                         \
	  echo '//'; echo '// Language library'; echo '//';            \
	  cat languages/*.php;                                         \
	  echo '//'; echo '// Main logic'; echo '//';                  \
	  cat main/body.php; echo '?>' ) > index.php

