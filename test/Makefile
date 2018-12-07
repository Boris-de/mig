DOCKER_NAME_PHPUNIT=mig-phpunit

DOCKER_PHP_VERSION='cli'

USED_DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)
ifneq ($(DOCKER_PHP_VERSION), 'cli')
  # append "-cli"
  USED_DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)-cli
endif

default:
	@echo "    make unittests                                Run unittests with the default php-cli"
	@echo "    make docker-unittests DOCKER_PHP_VERSION=7.0  Run unittests with in a docker container"

unittests:
	phpunit --include-path ../functions/:../main/:../languages/ .

docker-unittests:
	@if ! [[ $$MIG_DOCKER_SUDO_OK = 'true' ]]; then \
	  echo "This target uses \"sudo\" to run docker, abort now if you don't want this. Press Enter to continue"; \
	  read UNUSED; \
	fi
	sudo docker build --build-arg PHP_VERSION=$(DOCKER_PHP_VERSION) -t $(DOCKER_NAME_PHPUNIT) .
	sudo docker run -it --rm -v "$$PWD/..":/usr/src/mig -w /usr/src/mig $(DOCKER_NAME_PHPUNIT)

docker-unittests-php-versions:
	$(foreach version,5.6 7.0 7.1 7.2 7.3 rc, make docker-unittests DOCKER_PHP_VERSION=$(version);)

.PHONY: unittests docker-unittests docker-unittests-php-versions
