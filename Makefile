#
# $Id$
#
# Makefile to build Mig distributions
#

# Where to keep distributions (directory)
DISTDIR= bundles/mig

# Temporary directory to build a Mig install in (this gets tarred up)
SPOOLDIR= mig-$(ver)

# Archive name (output file)
ARCHIVE= $(DISTDIR)/$(SPOOLDIR).tar.gz

RELEASE_TAG=RELEASE_$(shell echo ${ver} | sed "s/\./_/g")

PHP_FILES=main/pathConvert.php main/defaults.php functions/*.php languages/*.php main/body.php

DOCKER_NAME=mig-php-app
DOCKER_PHP_VERSION=''

USED_DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)
ifneq ($(DOCKER_PHP_VERSION), '')
  # append "-" to get something like "7.1-apache"
  USED_DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)-
endif


default:
	@echo "    make dist ver=X            Builds distribution bundle (in $(DISTDIR))"
	@echo "    make release ver=X         Builds distribution bundle (in $(DISTDIR)), tags and signs it"
	@echo "    make index.php ver=X       Builds just index.php"
	@echo "    make docpublish            Publishes docs to mig.sf.net"
	@echo "    make clean"
	@echo " "
	@echo "    make mig.sf.net ver=X      index.php & templates to mig.sf.net"

mig: dist

dist: index.php
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
	cp images/*.png $(SPOOLDIR)/images
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

index.php: $(PHP_FILES) main/preamble.php
	rm -f index.php
	( sed "s/VeRsIoN/$(ver)/" main/preamble.php ; \
	  cat $(PHP_FILES) \
	) > index.php

release: clean
	@if test `hg status -m -a -r -d | wc -l` -gt 0; then \
		hg status -m -a -r -d; \
		echo "The working copy has uncommited changes (see above)"; \
		false; \
	fi
	@if hg outgoing -q; then \
		echo "The working copy has outgoing changes"; \
		false; \
	fi
	@if test -z "${ver}"; then \
		echo "Please specify a version for dist!"; \
		false; \
	fi
	@if test -z "$(MIG_GPG_KEY)"; then \
		echo "Please specify a key you want to use to sign this release (MIG_GPG_KEY=...)"; \
		false; \
	fi
	hg tag $(RELEASE_TAG)
	hg sign --key "$(MIG_GPG_KEY)" $(RELEASE_TAG)
	make dist
	make docpublish
	make mig.sf.net
	gpg --local-user "$(MIG_GPG_KEY)" --detach-sign --sign $(ARCHIVE)
	gpg --local-user "$(MIG_GPG_KEY)" --detach-sign --sign --armor $(ARCHIVE)

docpublish:
	cd docs ; make publish

mig.sf.net: index.php
	cp index.php $(MIG_SF_NET_DIR)/gallery/
	cp templates/*html templates/*.css $(MIG_SF_NET_DIR)/gallery/templates/
	cd $(MIG_SF_NET_DIR) ; make
	@echo "URL: http://mig.sf.net/gallery/"

test-album: create-random-album.sh
	rm -rf test-album
	./create-random-album.sh test-album

unittests:
	make -C test unittests

docker-unittests:
	make -C test docker-unittests-php-versions

docker: index.php test-album
	@echo "This target uses \"sudo\" to run docker, abort now if you don't want this. Press Enter to continue"
	@read UNUSED
	sudo docker build --build-arg PHP_VERSION=$(USED_DOCKER_PHP_VERSION) -t $(DOCKER_NAME) .
	sudo docker run --publish=127.0.0.1::80 -d --name $(DOCKER_NAME) $(DOCKER_NAME)
	@set -e ;\
	PORT=$$(sudo docker inspect --format '{{ (index (index .NetworkSettings.Ports "80/tcp") 0).HostPort }}' $(DOCKER_NAME)) ;\
	echo -e "\nContainer \"$(DOCKER_NAME)\" is started" ;\
	echo -e " find the application at http://localhost:$${PORT}/index.php" ;\
	echo -e " find the PHP-version at http://localhost:$${PORT}/phpinfo.php" ;\
	echo -e "Press enter to shut it down"
	@read UNUSED
	sudo docker stop $(DOCKER_NAME)
	sudo docker rm $(DOCKER_NAME)

clean:
	rm -rf docs/html docs/text index.php test-album

.PHONY: test clean unittests docker docker-unittests
