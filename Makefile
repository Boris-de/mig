#
# $Id$
#
# Makefile to build Mig distributions
#

# Where to keep distributions (directory)
DISTDIR= ../bundles/mig

# Webserver root
WEB= /Users/dan/Sites
WTEST= $(WEB)/mig_test
WPORT= $(WEB)/mig_portal

# Temporary directory to build a Mig install in (this gets tarred up)
SPOOLDIR= mig-$(ver)

# Archive name (output file)
ARCHIVE= $(DISTDIR)/$(SPOOLDIR).tar.gz

default:
	@echo "    make dist ver=X            Builds distribution bundle"
	@echo "    make index ver=X           Builds just index.php"
	@echo "    make docpublish            Publishes docs to mig.sf.net"
	@echo "    make clean"
	@echo " "
	@echo "    make mig.sf.net ver=X      index & templates to mig.sf.net"

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
	@echo " "
	@echo "=> Mig $(ver) bundle complete <="

index:
	rm -f index.php
	( sed "s/VeRsIoN/$(ver)/" main/preamble.php ; \
	  cat main/pathConvert.php main/defaults.php \
	      functions/*.php languages/*.php main/body.php \
	) > index.php

docpublish:
	cd docs ; make publish

mig.sf.net: index
	scp index.php mig.sf.net:web/gallery
	scp templates/*html templates/*.css mig.sf.net:web/gallery/templates
	@echo "URL: http://mig.sf.net/gallery/"

clean:
	rm -rf docs/html docs/text index.php

