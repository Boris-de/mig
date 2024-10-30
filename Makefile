
VERSION     = dev
BUILD_DIR   = build
DIST_DIR    = $(BUILD_DIR)/mig
INDEX_PHP   = $(BUILD_DIR)/index.php
PHPUNIT_DIR = $(BUILD_DIR)/phpunit
COVERAGE_DIR= $(BUILD_DIR)/coverage
PHPUNIT_CACHE_DIR = $(BUILD_DIR)/phpunit-cache

ARCHIVE_NAME = mig-$(VERSION)
# Temporary directory to build a Mig install in (this gets tarred up)
SPOOL_DIR    = $(BUILD_DIR)/$(ARCHIVE_NAME)
ARCHIVE      = $(DIST_DIR)/$(ARCHIVE_NAME).tar.gz
RELEASE_TAG  = v$(VERSION)

# allow to try to run with "podman" command
DOCKER ?= docker
TEST_ALBUM_DIR = test-album

PSALM_MARKER = $(BUILD_DIR)/.psalm
COMPOSER_PSALM_MARKER = $(BUILD_DIR)/.composer-psalm
COVERAGE_MARKER = $(BUILD_DIR)/coverage/.marker
MIG_SITE_MARKER = $(BUILD_DIR)/.site
BUILD_DIR_MARKER = $(BUILD_DIR)/.marker
UNITTESTS_MARKER = $(BUILD_DIR)/.marker-unittests
PHPUNIT_DIR_MARKER = $(PHPUNIT_DIR)/.marker
TEST_ALBUM_MARKER = $(TEST_ALBUM_DIR)/.marker
CONTAINER_UNITTESTS_MARKER = $(BUILD_DIR)/.marker-container-unittests
CONTAINER_UNITTESTS_ALL_MARKER = $(BUILD_DIR)/.marker-container-unittests-all

PHP_FILES = main/pathConvert.php main/defaults.php functions/*.php languages/*.php main/body.php
TEST_FILES = test/*.php
TEMPLATE_FILES = templates/*html templates/*.css

DEV_SERVER_PORT=8080

CONTAINER_NAME = mig-php-app
CONTAINER_NAME_PHPUNIT = mig-phpunit
CONTAINER_PHPUNIT_VERSION = cli-alpine
CONTAINER_UNITTEST_TMP = test/tmp

ifeq ($(CONTAINER_PHP_VERSION),)
 USED_CONTAINER_PHP_VERSION := $(CONTAINER_PHP_VERSION)
else
 USED_CONTAINER_PHP_VERSION := $(CONTAINER_PHP_VERSION)-
endif

PHPUNIT_URL = https://phar.phpunit.de
PHPUNIT_VERSION = $(shell phpunit --version|grep '^PHPUnit'|cut -d' ' -f 2|cut -d '.' -f 1)
PHPUNIT_PARAMS = $(shell test $(PHPUNIT_VERSION) -ne 5 && echo '--globals-backup'; \
						test $(PHPUNIT_VERSION) -ge 10 && echo '--cache-directory $(PHPUNIT_CACHE_DIR)')
PHPUNIT_VERSIONS = 5.7.27 8.5.32 10.0.7
PHPUNIT_FILES = $(addsuffix .phar, $(addprefix $(PHPUNIT_DIR)/phpunit-, $(PHPUNIT_VERSIONS) ))
PHPUNIT_FILTER := .
PHP_PATH_SEPARATOR = $(shell php -r 'echo PATH_SEPARATOR;')
PHPUNIT_INCLUDE_PATH = functions$(PHP_PATH_SEPARATOR)main$(PHP_PATH_SEPARATOR)languages
ifeq ($(OS),Windows_NT)
	PHPUNIT_PARAMETER := --no-configuration
endif


default:
	@echo "    make dist VERSION=X        Builds distribution bundle (in $(DIST_DIR))"
	@echo "    make release VERSION=X     Builds distribution bundle (in $(DIST_DIR)), tags and signs it"
	@echo "    make $(INDEX_PHP)       Builds just index.php"
	@echo "    make docpublish            Publishes docs to MIG_SITE_DIR"
	@echo "    make mig-site              Update mig in MIG_SITE_DIR"
	@echo "    make test-album            Create a random sample album"
	@echo "    make unittests             Runs unittests with default PHP version"
	@echo "    make coverage              Runs unittests with default PHP version to generate coverage"
	@echo "    make container-unittests      Runs unittests with a current PHP version using container"
	@echo "    make container-unittests-all  Runs unittests with different PHP versions using containers"
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
dist: has-version $(INDEX_PHP) unittests container-unittests-all-versions docs $(BUILD_DIR_MARKER)
	rm -rf $(SPOOL_DIR) $(ARCHIVE)
	mkdir -m 0755 -p $(DIST_DIR) $(SPOOL_DIR)
	cd $(SPOOL_DIR); mkdir -m 0755 -p images templates docs/text docs/html
	cp $(INDEX_PHP) $(SPOOL_DIR)
	cp config.php $(SPOOL_DIR)/config.php.default
	cp images/*.png $(SPOOL_DIR)/images
	cp templates/*.[hc]* $(SPOOL_DIR)/templates
	cp $(BUILD_DIR)/docs/html/*.html $(SPOOL_DIR)/docs/html
	cp $(BUILD_DIR)/docs/text/*.txt $(SPOOL_DIR)/docs/text
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

release: has-release-vars clean container-unittests-all $(BUILD_DIR_MARKER)
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

test: unittests container-unittests-all

unittests: $(UNITTESTS_MARKER)
$(UNITTESTS_MARKER): $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	phpunit $(PHPUNIT_PARAMS) --filter $(PHPUNIT_FILTER) --include-path "$(PHPUNIT_INCLUDE_PATH)" $(PHPUNIT_PARAMETER) test
	@touch $@

coverage: $(COVERAGE_MARKER)
$(COVERAGE_MARKER): $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	XDEBUG_MODE=coverage phpunit $(PHPUNIT_PARAMS) --coverage-html $(COVERAGE_DIR) --whitelist functions \
		--filter $(PHPUNIT_FILTER) --include-path "$(PHPUNIT_INCLUDE_PATH)" $(PHPUNIT_PARAMETER) test
	@touch $@

$(PHPUNIT_FILES): $(BUILD_DIR_MARKER) $(PHPUNIT_DIR_MARKER)
	curl --silent --fail --show-error --location $(PHPUNIT_URL)/$(shell basename $@) --output $@
	chmod 700 $@

container-unittests: $(CONTAINER_UNITTESTS_MARKER)-$(CONTAINER_PHPUNIT_VERSION)
$(CONTAINER_UNITTESTS_MARKER)-$(CONTAINER_PHPUNIT_VERSION): $(PHPUNIT_FILES) $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	@echo "Running unittests with container '$(CONTAINER_PHPUNIT_VERSION)'"
	rm -rf $(CONTAINER_UNITTEST_TMP) && cp -r $(PHPUNIT_DIR) $(CONTAINER_UNITTEST_TMP)
	$(DOCKER) build --build-arg PHP_VERSION=$(CONTAINER_PHPUNIT_VERSION) -t $(CONTAINER_NAME_PHPUNIT) test
	$(DOCKER) run --rm -v $${PWD}:/usr/src/mig -w /usr/src/mig $(CONTAINER_NAME_PHPUNIT)
	$(DOCKER) image rm $(CONTAINER_NAME_PHPUNIT) 1>/dev/null 2>&1 || true
	@touch $@

container-unittests-all: $(CONTAINER_UNITTESTS_ALL_MARKER)
container-unittests-all-versions: $(CONTAINER_UNITTESTS_ALL_MARKER)
$(CONTAINER_UNITTESTS_ALL_MARKER): $(PHPUNIT_FILES) $(PHP_FILES) $(TEST_FILES) $(BUILD_DIR_MARKER)
	@set -e ;\
	for version in 5.6 7.0 7.1 7.2 7.3 7.4 8.0 8.1 8.2 8.3 8.4-rc; do \
		make container-unittests CONTAINER_PHPUNIT_VERSION=$${version}-$(CONTAINER_PHPUNIT_VERSION); \
	done
	@touch $@

container-webserver: $(INDEX_PHP) $(TEST_ALBUM_DIR)
	$(DOCKER) build --build-arg PHP_VERSION=$(USED_CONTAINER_PHP_VERSION) -t $(CONTAINER_NAME) .
	$(DOCKER) run --publish=127.0.0.1::80 -d --name $(CONTAINER_NAME) $(CONTAINER_NAME)
	@set -e ;\
	PORT=$$($(DOCKER) port $(CONTAINER_NAME) | grep ^80/tcp|cut -d: -f 2) ;\
	echo -e "\nContainer \"$(CONTAINER_NAME)\" is started" ;\
	echo -e " find the application at http://localhost:$${PORT}/index.php" ;\
	echo -e " find the PHP-version at http://localhost:$${PORT}/phpinfo.php" ;\
	echo -e "Press enter to shut it down"
	@read UNUSED
	$(DOCKER) stop $(CONTAINER_NAME)
	$(DOCKER) rm $(CONTAINER_NAME)

$(COMPOSER_PSALM_MARKER):
	composer install
	@touch $(COMPOSER_PSALM_MARKER)

psalm: $(PSALM_MARKER)
$(PSALM_MARKER): $(INDEX_PHP) $(BUILD_DIR_MARKER) $(COMPOSER_PSALM_MARKER) psalm.xml
	composer exec psalm $<
	composer exec psalm -- --taint-analysis --report=$(BUILD_DIR)/psam-github-results.sarif --output-format=github $<
	@touch $(PSALM_MARKER)

albums: $(TEST_ALBUM_MARKER)
	ln -s $(TEST_ALBUM_DIR) albums

dev-server: albums
	@echo -e "Starting dev server at http://localhost:$(DEV_SERVER_PORT)/dev.php\n"
	php --server localhost:$(DEV_SERVER_PORT) --docroot .

clean-marker:
	rm -f $(PSALM_MARKER) $(COVERAGE_MARKER) $(MIG_SITE_MARKER) $(BUILD_DIR_MARKER) $(UNITTESTS_MARKER) \
		$(TEST_ALBUM_MARKER) $(CONTAINER_UNITTESTS_MARKER) $(CONTAINER_UNITTESTS_ALL_MARKER) $(PHPUNIT_DIR_MARKER) \
		$(COMPOSER_PSALM_MARKER)

clean: clean-marker
	make -C docs clean
	$(DOCKER) image rm $(CONTAINER_NAME) $(CONTAINER_NAME_PHPUNIT) 1>/dev/null 2>&1 || true
	rm -f $(INDEX_PHP) albums $(PHPUNIT_FILES)
	rm -rf test-album $(PHPUNIT_DIR) $(BUILD_DIR) $(CONTAINER_UNITTEST_TMP) vendor

$(BUILD_DIR_MARKER):
	mkdir -p $(BUILD_DIR)
	@touch $@

$(PHPUNIT_DIR_MARKER): $(BUILD_DIR_MARKER)
	mkdir -p $(shell dirname $@)
	@touch $@

.PHONY: default docs test clean clean-marker unittests container-webserver container-unittests container-unittests-all container-unittests-all-versions coverage has-version index.php coverage dev-server
