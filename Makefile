
VERSION     = dev
BUILD_DIR   = build
DIST_DIR    = $(BUILD_DIR)/mig
INDEX_PHP   = $(BUILD_DIR)/index.php
PHPUNIT_DIR = $(BUILD_DIR)/phpunit
COVERAGE_DIR= $(BUILD_DIR)/coverage

ARCHIVE_NAME = mig-$(VERSION)
# Temporary directory to build a Mig install in (this gets tarred up)
SPOOL_DIR    = $(BUILD_DIR)/$(ARCHIVE_NAME)
ARCHIVE      = $(DIST_DIR)/$(ARCHIVE_NAME).tar.gz
RELEASE_TAG  = v$(VERSION)

# allow to try to run with "docker" command
PODMAN = podman
TEST_ALBUM_DIR = test-album

PSALM_MARKER = $(BUILD_DIR)/.psalm
COVERAGE_MARKER = $(BUILD_DIR)/coverage/.marker
MIG_SITE_MARKER = $(BUILD_DIR)/.site
BUILD_DIR_MARKER = $(BUILD_DIR)/.marker
UNITTESTS_MARKER = $(BUILD_DIR)/.unittests
TEST_ALBUM_MARKER = $(TEST_ALBUM_DIR)/.marker
PODMAN_UNITTESTS_MARKER = $(BUILD_DIR)/.podman-unittests
PODMAN_UNITTESTS_ALL_MARKER = $(BUILD_DIR)/.podman-unittests-all

PHP_FILES = main/pathConvert.php main/defaults.php functions/*.php languages/*.php main/body.php
TEST_FILES = test/*.php
TEMPLATE_FILES = templates/*html templates/*.css

DEV_SERVER_PORT=8080

PODMAN_NAME = mig-php-app
PODMAN_NAME_PHPUNIT = mig-phpunit
PODMAN_PHPUNIT_VERSION = cli-alpine
PODMAN_UNITTEST_TMP = test/tmp

ifeq ($(PODMAN_PHP_VERSION),)
 USED_PODMAN_PHP_VERSION := $(PODMAN_PHP_VERSION)
else
 USED_PODMAN_PHP_VERSION := $(PODMAN_PHP_VERSION)-
endif

PHPUNIT_URL = https://phar.phpunit.de
PHPUNIT_VERSION = $(shell phpunit --version|grep '^PHPUnit'|cut -d' ' -f 2|cut -c 1)
PHPUNIT_PARAMS = $(shell test $(PHPUNIT_VERSION) -ne 5 && echo '--globals-backup')
PHPUNIT_VERSIONS = 5.7.27 8.5.13 9.5.0
PHPUNIT_FILES = $(addsuffix .phar, $(addprefix $(PHPUNIT_DIR)/phpunit-, $(PHPUNIT_VERSIONS) ))
PHPUNIT_FILTER := .
PHP_PATH_SEPARATOR = $(shell php -r 'echo PATH_SEPARATOR;')
PHPUNIT_INCLUDE_PATH = functions$(PHP_PATH_SEPARATOR)main$(PHP_PATH_SEPARATOR)languages


default:
	@echo "    make dist VERSION=X        Builds distribution bundle (in $(DIST_DIR))"
	@echo "    make release VERSION=X     Builds distribution bundle (in $(DIST_DIR)), tags and signs it"
	@echo "    make $(INDEX_PHP)       Builds just index.php"
	@echo "    make docpublish            Publishes docs to MIG_SITE_DIR"
	@echo "    make mig-site              Update mig in MIG_SITE_DIR"
	@echo "    make test-album            Create a random sample album"
	@echo "    make unittests             Runs unittests with default PHP version"
	@echo "    make coverage              Runs unittests with default PHP version to generate coverage"
	@echo "    make podman-unittests      Runs unittests with a current PHP version using podman container"
	@echo "    make podman-unittests-all  Runs unittests with different PHP versions using podman containers"
	@echo "    make clean"

index.php: $(INDEX_PHP)
$(INDEX_PHP): $(PHP_FILES) main/preamble.php $(BUILD_DIR_MARKER)
	( sed "s/VeRsIoN/$(VERSION)/" main/preamble.php ; \
	  cat $(PHP_FILES) \
	) > $(INDEX_PHP)

has-version:
	@if test -z "${VERSION}"; then echo "Missing version, run make with VERSION=..."; false; fi
	@if test "${VERSION}" = "dev"; then echo "Invalid version, run make with VERSION=..."; false; fi

docs:
	make -C docs

mig: dist
dist: has-version $(INDEX_PHP) unittests podman-unittests-all-versions docs $(BUILD_DIR_MARKER)
	rm -rf $(SPOOL_DIR) $(ARCHIVE)
	mkdir -m 0755 -p $(DIST_DIR) $(SPOOL_DIR)
	cd $(SPOOL_DIR); mkdir -m 0755 -p images templates docs/text docs/html
	cp $(INDEX_PHP) $(SPOOL_DIR)
	cp config.php $(SPOOL_DIR)/config.php.default
	cp images/*.png $(SPOOL_DIR)/images
	cp templates/*.[hc]* $(SPOOL_DIR)/templates
	cp docs/html/*.html $(SPOOL_DIR)/docs/html
	cp docs/text/*.txt $(SPOOL_DIR)/docs/text
	find $(SPOOL_DIR) -type d -exec chmod 0755 {} \;
	find $(SPOOL_DIR) -type f -exec chmod 0644 {} \;
	tar czf $(ARCHIVE) --owner=0 --group=0 -C $$(dirname $(SPOOL_DIR)) $$(basename $(SPOOL_DIR))
	rm -rf $(SPOOL_DIR)
	chmod 0644 $(ARCHIVE)
	@echo " "
	@echo "=> Mig $(VERSION) bundle complete: $(ARCHIVE) <="

has-release-vars: has-version
	! test -e $(ARCHIVE) # check if archive already exists
	test -n "$(MIG_GPG_KEY)" # MIG_GPG_KEY
	test -n "$(MIG_GPG_EMAIL)" # MIG_GPG_EMAIL
	test -n "$(MIG_SITE_DIR)" # MIG_SITE_DIR

release: has-release-vars clean podman-unittests-all $(BUILD_DIR_MARKER)
	make dist VERSION=$(VERSION)
	git -c "user.signingkey=$(MIG_GPG_KEY)" -c "user.email=$(MIG_GPG_EMAIL)" tag -s $(RELEASE_TAG) -m "Tagging $(RELEASE_TAG)"
	make docpublish MIG_SITE_DIR=$(MIG_SITE_DIR)
	make mig-site MIG_SITE_DIR=$(MIG_SITE_DIR)
	gpg --local-user "$(MIG_GPG_KEY)" --detach-sign --sign $(ARCHIVE)
	gpg --local-user "$(MIG_GPG_KEY)" --detach-sign --sign --armor $(ARCHIVE)
	@echo
	@echo "Release is finished, see $(ARCHIVE) and $(MIG_SITE_DIR)"
	@echo "You can run './utilities/release-test.sh $(ARCHIVE)' to run final tests"

docpublish:
	make -C docs publish MIG_SITE_DIR=$(MIG_SITE_DIR)

mig-site: $(MIG_SITE_MARKER)
$(MIG_SITE_MARKER): $(INDEX_PHP) $(BUILD_DIR_MARKER)
	cp $(INDEX_PHP) $(MIG_SITE_DIR)/gallery/
	cp $(TEMPLATE_FILES) $(MIG_SITE_DIR)/gallery/templates/
	@touch $(MIG_SITE_MARKER)

$(TEST_ALBUM_DIR): $(TEST_ALBUM_MARKER)
$(TEST_ALBUM_MARKER): utilities/create-random-album.sh
	rm -rf $(TEST_ALBUM_DIR)
	./utilities/create-random-album.sh $(TEST_ALBUM_DIR)
	@touch $(TEST_ALBUM_MARKER)

unittests: $(UNITTESTS_MARKER)
$(UNITTESTS_MARKER): $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	phpunit $(PHPUNIT_PARAMS) --filter $(PHPUNIT_FILTER) --include-path "$(PHPUNIT_INCLUDE_PATH)" test
	@touch $@

coverage: $(COVERAGE_MARKER)
$(COVERAGE_MARKER): $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	XDEBUG_MODE=coverage phpunit $(PHPUNIT_PARAMS) --coverage-html $(COVERAGE_DIR) --whitelist functions \
		--filter $(PHPUNIT_FILTER) --include-path "$(PHPUNIT_INCLUDE_PATH)" test
	@touch $@

$(PHPUNIT_FILES): $(BUILD_DIR_MARKER) $(BUILD_DIR_MARKER)
	mkdir -p $(PHPUNIT_DIR)
	curl --silent --show-error --location $(PHPUNIT_URL)/$(shell basename $@) --output $@
	chmod 700 $@

podman-unittests: $(PODMAN_UNITTESTS_MARKER)-$(PODMAN_PHPUNIT_VERSION)
$(PODMAN_UNITTESTS_MARKER)-$(PODMAN_PHPUNIT_VERSION): $(PHPUNIT_FILES) $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	@echo "Running unittests with container '$(PODMAN_PHPUNIT_VERSION)'"
	rm -rf $(PODMAN_UNITTEST_TMP) && cp -r $(PHPUNIT_DIR) $(PODMAN_UNITTEST_TMP)
	$(PODMAN) build --build-arg PHP_VERSION=$(PODMAN_PHPUNIT_VERSION) -t $(PODMAN_NAME_PHPUNIT) test
	$(PODMAN) run -it --rm -v .:/usr/src/mig -w /usr/src/mig $(PODMAN_NAME_PHPUNIT)
	@touch $@

podman-unittests-all: $(PODMAN_UNITTESTS_ALL_MARKER)
podman-unittests-all-versions: $(PODMAN_UNITTESTS_ALL_MARKER)
$(PODMAN_UNITTESTS_ALL_MARKER): $(PHPUNIT_FILES) $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	for version in 5.6 7.0 7.1 7.2 7.3 7.4 8.0 rc; do \
		make podman-unittests PODMAN_PHPUNIT_VERSION=$${version}-$(PODMAN_PHPUNIT_VERSION) || exit ${?}; \
	done
	@touch $@

podman: $(INDEX_PHP) $(TEST_ALBUM_DIR)
	$(PODMAN) build --build-arg PHP_VERSION=$(USED_PODMAN_PHP_VERSION) -t $(PODMAN_NAME) .
	$(PODMAN) run --publish=127.0.0.1::80 -d --name $(PODMAN_NAME) $(PODMAN_NAME)
	@set -e ;\
	PORT=$$($(PODMAN) port $(PODMAN_NAME) | grep ^80/tcp|cut -d: -f 2) ;\
	echo -e "\nContainer \"$(PODMAN_NAME)\" is started" ;\
	echo -e " find the application at http://localhost:$${PORT}/index.php" ;\
	echo -e " find the PHP-version at http://localhost:$${PORT}/phpinfo.php" ;\
	echo -e "Press enter to shut it down"
	@read UNUSED
	$(PODMAN) stop $(PODMAN_NAME)
	$(PODMAN) rm $(PODMAN_NAME)

psalm: $(PSALM_MARKER)
$(PSALM_MARKER): $(INDEX_PHP) $(BUILD_DIR_MARKER)
	psalm $<
	psalm --taint-analysis $<
	@touch $(PSALM_MARKER)

albums: $(TEST_ALBUM_MARKER)
	ln -s $(TEST_ALBUM_DIR) albums

dev-server: albums
	@echo -e "Starting dev server at http://localhost:$(DEV_SERVER_PORT)/dev.php\n"
	php --server localhost:$(DEV_SERVER_PORT) --docroot .

clean-marker:
	rm -f $(PSALM_MARKER) $(COVERAGE_MARKER) $(MIG_SITE_MARKER) $(BUILD_DIR_MARKER) $(UNITTESTS_MARKER) \
		$(TEST_ALBUM_MARKER) $(PODMAN_UNITTESTS_MARKER) $(PODMAN_UNITTESTS_ALL_MARKER)

clean: clean-marker
	make -C docs clean
	rm -f $(INDEX_PHP) albums $(PHPUNIT_FILES)
	rm -rf test-album $(PHPUNIT_DIR) $(BUILD_DIR) $(PODMAN_UNITTEST_TMP)

$(BUILD_DIR_MARKER):
	mkdir -p $(BUILD_DIR)
	@touch $@

.PHONY: default docs test clean clean-marker unittests podman podman-unittests podman-unittests-all podman-unittests-all-versions coverage has-version index.php coverage dev-server
