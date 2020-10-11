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

RELEASE_TAG=v$(ver)

PHP_FILES=main/pathConvert.php main/defaults.php functions/*.php languages/*.php main/body.php
TEMPLATE_FILES=templates/*html templates/*.css

PODMAN_NAME=mig-php-app
PODMAN_PHP_VERSION=''

USED_PODMAN_PHP_VERSION=$(PODMAN_PHP_VERSION)
ifneq ($(PODMAN_PHP_VERSION), '')
  # append "-" to get something like "7.1-apache"
  USED_PODMAN_PHP_VERSION=$(PODMAN_PHP_VERSION)-
endif


default:
	@echo "    make dist ver=X            Builds distribution bundle (in $(DISTDIR))"
	@echo "    make release ver=X         Builds distribution bundle (in $(DISTDIR)), tags and signs it"
	@echo "    make index.php ver=X       Builds just index.php"
	@echo "    make docpublish            Publishes docs to mig.sf.net"
	@echo "    make test-album            Create a random sample album"
	@echo "    make unittests             Runs unittests with default PHP version"
	@echo "    make coverage              Runs unittests with default PHP version to generate coverage"
	@echo "    make podman-unittests      Runs unittests with different PHP versions using podman"
	@echo "    make clean"
	@echo " "
	@echo "    make mig.sf.net ver=X      index.php & templates to mig.sf.net"

mig: dist

has-version:
	@if test -z "${ver}"; then echo "Missing version, run make with ver=..."; false; fi

dist: has-version index.php unittests podman-unittests
	make -C docs
	rm -rf $(SPOOLDIR) $(ARCHIVE)
	mkdir -m 0755 -p $(DISTDIR) $(SPOOLDIR)
	cd $(SPOOLDIR); mkdir -m 0755 -p images templates docs/text docs/html
	mv index.php $(SPOOLDIR)
	cp config.php $(SPOOLDIR)/config.php.default
	cp images/*.png $(SPOOLDIR)/images
	cp templates/*.[hc]* $(SPOOLDIR)/templates
	cp docs/html/*.html $(SPOOLDIR)/docs/html
	cp docs/text/*.txt $(SPOOLDIR)/docs/text
	find $(SPOOLDIR) -type d -exec chmod 0755 {} \;
	find $(SPOOLDIR) -type f -exec chmod 0644 {} \;
	tar czf $(ARCHIVE) --owner=0 --group=0 $(SPOOLDIR)
	rm -rf $(SPOOLDIR)
	chmod 0644 $(ARCHIVE)
	@echo " "
	@echo "=> Mig $(ver) bundle complete: $(ARCHIVE) <="

index.php: $(PHP_FILES) main/preamble.php
	rm -f index.php
	( sed "s/VeRsIoN/$(ver)/" main/preamble.php ; \
	  cat $(PHP_FILES) \
	) > index.php

has-release-vars: has-version
	! test -e $(ARCHIVE) # check if archive already exists
	test -n "$(MIG_GPG_KEY)" # MIG_GPG_KEY
	test -n "$(MIG_GPG_EMAIL)" # MIG_GPG_EMAIL
	test -n "$(MIG_SITE_DIR)" # MIG_SITE_DIR

release: has-release-vars clean
	make dist ver=$(ver)
	git -c "user.signingkey=$(MIG_GPG_KEY)" -c "user.email=$(MIG_GPG_EMAIL)" tag -s $(RELEASE_TAG) -m "Tagging $(RELEASE_TAG)"
	make docpublish MIG_SITE_DIR=$(MIG_SITE_DIR)
	make mig.sf.net MIG_SITE_DIR=$(MIG_SITE_DIR)
	gpg --local-user "$(MIG_GPG_KEY)" --detach-sign --sign $(ARCHIVE)
	gpg --local-user "$(MIG_GPG_KEY)" --detach-sign --sign --armor $(ARCHIVE)
	@echo
	@echo "Release is finished, see $(ARCHIVE) and $(MIG_SITE_DIR)"
	@echo "You can run ./utilities/release-test.sh $(ARCHIVE) to run final tests"

docpublish:
	make -C docs publish MIG_SITE_DIR=$(MIG_SITE_DIR)

mig.sf.net: index.php
	cp index.php $(MIG_SITE_DIR)/gallery/
	cp $(TEMPLATE_FILES) $(MIG_SITE_DIR)/gallery/templates/

test-album: utilities/create-random-album.sh
	rm -rf test-album
	./utilities/create-random-album.sh test-album

unittests:
	make -C test unittests

coverage:
	make -C test coverage

podman-unittests:
	make -C test podman-unittests-php-versions

podman: index.php test-album
	podman build --build-arg PHP_VERSION=$(USED_PODMAN_PHP_VERSION) -t $(PODMAN_NAME) .
	podman run --publish=127.0.0.1::80 -d --name $(PODMAN_NAME) $(PODMAN_NAME)
	@set -e ;\
	PORT=$$(podman port $(PODMAN_NAME) | grep ^80/tcp|cut -d: -f 2) ;\
	echo -e "\nContainer \"$(PODMAN_NAME)\" is started" ;\
	echo -e " find the application at http://localhost:$${PORT}/index.php" ;\
	echo -e " find the PHP-version at http://localhost:$${PORT}/phpinfo.php" ;\
	echo -e "Press enter to shut it down"
	@read UNUSED
	podman stop $(PODMAN_NAME)
	podman rm $(PODMAN_NAME)

clean:
	rm -rf docs/html docs/text index.php test-album

.PHONY: test clean unittests podman podman-unittests coverage has-version
