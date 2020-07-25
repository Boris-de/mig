PODMAN_NAME_PHPUNIT=mig-phpunit

PODMAN_PHP_VERSION='cli'

USED_PODMAN_PHP_VERSION=$(PODMAN_PHP_VERSION)
ifneq ($(PODMAN_PHP_VERSION), 'cli')
  # append "-cli"
  USED_PODMAN_PHP_VERSION=$(PODMAN_PHP_VERSION)-cli
endif

PHPUNIT_VERSION=$(shell phpunit --version|grep '^PHPUnit'|cut -d' ' -f 2|cut -c 1)
PHPUNIT_PARAMS=$(shell test $(PHPUNIT_VERSION) -ne 5 && echo '--globals-backup')

default:
	@echo "    make unittests                                Run unittests with the default php-cli"
	@echo "    make podman-unittests PODMAN_PHP_VERSION=7.0  Run unittests with in a docker container"

unittests:
	phpunit $(PHPUNIT_PARAMS) --include-path ../functions/:../main/:../languages/ .

coverage:
	phpunit $(PHPUNIT_PARAMS) --coverage-html coverage --whitelist ../functions/ --include-path ../functions/:../main/:../languages/ .

podman-unittests:
	podman build --build-arg PHP_VERSION=$(PODMAN_PHP_VERSION) -t $(PODMAN_NAME_PHPUNIT) .
	podman run -it --rm -v "$$PWD/..":/usr/src/mig -w /usr/src/mig $(PODMAN_NAME_PHPUNIT)

podman-unittests-php-versions:
	$(foreach version,5.6 7.0 7.1 7.2 7.3 7.4, make podman-unittests PODMAN_PHP_VERSION=$(version) || exit;)

.PHONY: unittests podman-unittests podman-unittests-php-versions coverage
