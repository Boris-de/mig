# $Revision$
#
# Makefile to build MiG distributions
#

default: help

help:
	@echo "Usage:    make mig ver={version}"
	@echo "Example:  make mig ver=1.2.5"

mig:
	@echo "Making MiG version $(ver)..."
	@make docs
	@rm -f bundles/mig-$(ver).tar.gz
	@mv src mig-$(ver)
	@/sw/bin/tar --exclude CVS -cf bundles/mig-$(ver).tar mig-$(ver)
	@/usr/bin/gzip bundles/mig-$(ver).tar
	@mv mig-$(ver) src
	@ls -l bundles/mig-$(ver).tar.gz

docs:
	@cd doc ; make
