PODMAN_NAME_PHPUNIT=mig-phpunit

PODMAN_PHP_VERSION='cli-alpine'

TMP_DIR=tmp
TMP_MARKER=$(TMP_DIR)/.marker
PHPUNIT_URL=https://phar.phpunit.de
PHPUNIT_VERSION=$(shell phpunit --version|grep '^PHPUnit'|cut -d' ' -f 2|cut -c 1)
PHPUNIT_PARAMS=$(shell test $(PHPUNIT_VERSION) -ne 5 && echo '--globals-backup')
PHPUNIT_VERSIONS=5.7.27 8.5.8 9.4.1
PHPUNIT_FILES=$(addsuffix .phar, $(addprefix $(TMP_DIR)/phpunit-, $(PHPUNIT_VERSIONS) ))

default:
	@echo "    make unittests                                           Run unittests with the default php cli-alpine version"
	@echo "    make podman-unittests PODMAN_PHP_VERSION=8.0-cli-alpine  Run unittests with in a container"

$(TMP_MARKER):
	mkdir -p $(TMP_DIR)
	touch $@

$(PHPUNIT_FILES): $(TMP_MARKER)
	curl --silent --show-error --location $(PHPUNIT_URL)/$(shell basename $@) --output $@
	chmod 700 $@

unittests:
	phpunit $(PHPUNIT_PARAMS) --include-path ../functions/:../main/:../languages/ .

coverage:
	phpunit $(PHPUNIT_PARAMS) --coverage-html coverage --whitelist ../functions/ --include-path ../functions/:../main/:../languages/ .

podman-unittests: $(PHPUNIT_FILES)
	@echo "Running unittests with container '$(PODMAN_PHP_VERSION)'"
	podman build --build-arg PHP_VERSION=$(PODMAN_PHP_VERSION) -t $(PODMAN_NAME_PHPUNIT) .
	podman run -it --rm -v "$$PWD/..":/usr/src/mig -w /usr/src/mig $(PODMAN_NAME_PHPUNIT)

podman-unittests-all-versions:
	for version in 5.6 7.0 7.1 7.2 7.3 7.4 8.0 rc; do \
		make podman-unittests PODMAN_PHP_VERSION=$${version}-$(PODMAN_PHP_VERSION) || exit ${?}; \
	done

clean:
	rm -f $(PHPUNIT_FILES) $(TMP_MARKER)
	rm -rf $(TMP_DIR)

.PHONY: unittests podman-unittests podman-unittests-all-versions coverage clean
