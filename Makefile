# $Id$
#
# Makefile to build MiG distributions
#

default: help

help:
	@echo "Usage: make version={version}"

mig:
	@echo "Making MiG version $(version)..."
	@rm -f mig-$(version).tar.gz
	@mv src mig-$(version)
	@/usr/local/bin/tar --exclude CVS -c -f mig-$(version).tar \
	  mig-$(version)
	@/usr/local/bin/gzip mig-$(version).tar
	@mv mig-$(version) src
	@ls -l mig-$(version).tar.gz
