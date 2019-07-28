PODMAN_NAME_PHPUNIT=mig-phpunit

PODMAN_PHP_VERSION='cli'

USED_PODMAN_PHP_VERSION=$(PODMAN_PHP_VERSION)
ifneq ($(PODMAN_PHP_VERSION), 'cli')
  # append "-cli"
  USED_PODMAN_PHP_VERSION=$(PODMAN_PHP_VERSION)-cli
endif

default:
	@echo "    make unittests                                Run unittests with the default php-cli"
	@echo "    make podman-unittests PODMAN_PHP_VERSION=7.0  Run unittests with in a docker container"

unittests:
	phpunit --include-path ../functions/:../main/:../languages/ .

coverage:
	phpunit --coverage-html coverage --whitelist ../functions/ --include-path ../functions/:../main/:../languages/ .

podman-unittests:
	podman build --build-arg PHP_VERSION=$(PODMAN_PHP_VERSION) -t $(PODMAN_NAME_PHPUNIT) .
	podman run -it --rm -v "$$PWD/..":/usr/src/mig -w /usr/src/mig $(PODMAN_NAME_PHPUNIT)

podman-unittests-php-versions:
	$(foreach version,5.6 7.0 7.1 7.2 7.3 7.4-rc, make podman-unittests PODMAN_PHP_VERSION=$(version) || exit;)

.PHONY: unittests podman-unittests podman-unittests-php-versions coverage
