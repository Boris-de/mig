#
# $Id$
#
# Makefile to build Mig distributions
#

# Where to keep distributions (directory)
DISTDIR= ../bundles/mig

# Webserver root
WEB= /Library/WebServer/Documents

# Temporary directory to build a Mig install in (this gets tarred up)
SPOOLDIR= mig-$(ver)

# Archive name (output file)
ARCHIVE= $(DISTDIR)/$(SPOOLDIR).tar.gz

default:
	@echo "    make dist ver=X            Builds distribution bundle"
	@echo "    make index ver=X           Builds just index.php"
	@echo "    make docpublish            Publishes docs to mig.sf.net"
	@echo " "
	@echo "    make index2                index2.php to tangledhelix.com"
	@echo "    make test                  index to local test gallery"
	@echo "    make testgalleries         gallery_subdir & gallery_th"
	@echo "    make cms                   index to phpnuke, etc"
	@echo "    make mig.sf.net ver=X      index & templates to mig.sf.net"
	@echo "    make ingeni.us ver=X       index to ingeni.us"
	@echo "    make monkeysr.us ver=X     index to monkeysr.us"

mig: dist

dist: index
	cd docs; make; cd ..
	rm -rf $(SPOOLDIR) $(ARCHIVE)
	mkdir -m 0755 -p $(DISTDIR) $(SPOOLDIR)
	cd $(SPOOLDIR); mkdir -m 0755 -p images templates/portals \
		docs/text docs/html utilities/jhead \
		xoops/language/english
	mv index.php $(SPOOLDIR)
	cp config.php $(SPOOLDIR)/config.php.default
	cp utilities/mkGallery.pl $(SPOOLDIR)/utilities
	cd $(SPOOLDIR)/utilities; tar xfz ../../utilities/jhead.tar.gz; cd ..
	cp images/*.gif $(SPOOLDIR)/images
	cp templates/*.[hc]* $(SPOOLDIR)/templates
	cp templates/*.php $(SPOOLDIR)/templates/portals
	cp docs/html/*.html $(SPOOLDIR)/docs/html
	cp docs/text/*.txt $(SPOOLDIR)/docs/text
	cp xoops/mig_logo.jpg xoops/xoops_version.php $(SPOOLDIR)/xoops
	cp xoops/modinfo.php $(SPOOLDIR)/xoops/language/english
	touch $(SPOOLDIR)/xoops/language/english/index.html
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
	scp index.php snoopy.net:/www/ingeni.us/html/gallery/index2.php
	@echo "URL: http://ingeni.us/gallery/index2.php"

mig.sf.net: index
	scp index.php mig.sf.net:web/gallery
	scp templates/*html templates/*.css mig.sf.net:web/gallery/templates
	@echo "URL: http://mig.sf.net/gallery/"

ingeni.us: index
	scp index.php snoopy.net:/www/ingeni.us/html/gallery
	@echo "URL: http://tangledhelix.com/gallery/"

monkeysr.us: index
	scp index.php snoopy.net:/www/monkeysr.us/html/gallery
	@echo "URL: http://monkeysr.us/gallery/"

testgalleries: gallery_subdir gallery_th

gallery_subdir: index
	cp index.php $(WEB)/gallery_subdir
	cp templates/*.html $(WEB)/gallery_subdir/templates
	cp templates/*.css $(WEB)/gallery_subdir/templates

gallery_th: index
	cp index.php $(WEB)/gallery_th
	cp templates/*.html $(WEB)/gallery_th/templates
	cp templates/*.css $(WEB)/gallery_th/templates

test: index
	cp index.php $(WEB)/gallery

phpnuke: index
	cp index.php $(WEB)/phpnuke/mig.php

postnuke: index
	cp index.php $(WEB)/postnuke/mig.php

phpwebsite: index
	cp index.php $(WEB)/phpwebsite/mig.php

phpwebthings: index
	cp index.php $(WEB)/phpwebthings/mig.php

xoops: index
	cp index.php $(WEB)/xoops/modules/mig/index.php

cms: phpnuke postnuke phpwebsite phpwebthings xoops

