#
# $Id$
#
# Makefile to build Mig distributions
#

# Where to keep distributions (directory)
DISTDIR= ../bundles/mig

# Temporary directory to build a Mig install in (this gets tarred up)
SPOOLDIR= mig-$(ver)

# Archive name (output file)
ARCHIVE= $(DISTDIR)/$(SPOOLDIR).tar.gz

default:
	@echo "make dist ver={ver}            Builds distribution bundle"
	@echo "make index ver={ver}           Builds just index.php"
	@echo "make docpublish                Publishes docs to mig.sf.net"
	@echo "make index2                    Pushes index2.php to snoopy"
	@echo " "
	@echo "make sf-net ver={ver}          Pushes files to mig.sf.net"
	@echo "                               (including templates)"
	@echo " "
	@echo "==> These just install index.php"
	@echo "make tangledhelix ver={ver}    Install on tangledhelix.com"
	@echo "make gallery_th ver={ver}      Install on /gallery_th/"
	@echo "make monkeysrus ver={ver}      Install on monkeysr.us"

mig: dist

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
	cp images/*.gif $(SPOOLDIR)/images
	cp templates/*.[hc]* $(SPOOLDIR)/templates
	cp templates/*.php $(SPOOLDIR)/templates/phpnuke
	cp docs/html/*.html $(SPOOLDIR)/docs/html
	cp docs/text/*.txt $(SPOOLDIR)/docs/text
	find $(SPOOLDIR) -type d -exec chmod 0755 {} \;
	find $(SPOOLDIR) -type f -exec chmod 0644 {} \;
	chmod 0755 $(SPOOLDIR)/utilities/mkGallery.pl
	tar cfz $(ARCHIVE) $(SPOOLDIR)
	rm -rf $(SPOOLDIR)
	chmod 0644 $(ARCHIVE)

index:
	( echo '<?php'; sed "s/VeRsIoN/$(ver)/" main/preamble.php ; \
	  cat main/defaults.php; \
	  echo '//'; echo '// Function library'; echo '//'; \
	  cat functions/*.php; \
	  echo '//'; echo '// Language library'; echo '//'; \
	  cat languages/*.php; \
	  echo '//'; echo '// Main logic'; echo '//'; \
	  cat main/body.php; echo '?>' \
	) > index.php

docpublish:
	cd docs ; make publish

index2: index
	scp index.php snoopy.net:/www/tangledhelix.com/html/gallery/index2.php
	@echo "URL: http://ingeni.us/gallery/index2.php"

sf-net: index
	scp index.php mig.sf.net:web/gallery
	scp templates/*html templates/*.css mig.sf.net:web/gallery/templates
	@echo "URL: http://mig.sf.net/gallery/"

tangledhelix: index
	scp index.php snoopy.net:/www/tangledhelix.com/html/gallery
	@echo "URL: http://tangledhelix.com/gallery/"

gallery_th: index
	scp index.php snoopy.net:/www/tangledhelix.com/html/dev/gallery_th
	@echo "URL: http://tangledhelix.com/dev/gallery_th/"

monkeysrus: index
	scp index.php snoopy.net:/www/monkeysr.us/html/gallery
	@echo "URL: http://monkeysr.us/gallery/"

