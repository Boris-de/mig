# $Id$
#
# Makefile to build MiG distributions
#

default: help

help:
	@echo "Usage:    make mig version={version}"
	@echo "Example:  make mig version=1.2.5"

mig:
	@echo "Making MiG version $(version)..."
	@make docs
	@rm -f mig-$(version).tar.gz
	@mv src mig-$(version)
	@/usr/local/bin/tar --exclude CVS -c -f mig-$(version).tar \
	  mig-$(version)
	@/usr/local/bin/gzip mig-$(version).tar
	@mv mig-$(version) src
	@ls -l mig-$(version).tar.gz

docs:
	@cd doc ; make
