#
# $Id$
#

# Where to store bundles
DISTDIR=../../bundles/mig

# path to (GNU) tar
TAR=/usr/bin/gnutar

# What to ignore when tarring
EXCEPT= --exclude='CVS' --exclude='.DS_Store' --exclude='.Apple*' \
	--exclude='.FBC*' --exclude='.*.swp'

# Contrib archive
CONTRIB=$(DISTDIR)/mig-contrib.tar.gz

# Gallery archive
GALLERY=$(DISTDIR)/mig-example-gallery.tar.gz

default:
	@echo "make contrib     Bundle contrib directory"
	@echo "make gallery     Bundle example gallery"

contrib:
	rm -f $(CONTRIB)
	$(TAR) --gzip $(EXCEPT) -cf $(CONTRIB) mig-contrib

gallery:
	rm -f $(GALLERY)
	$(TAR) --gzip $(EXCEPT) -cf $(GALLERY) Mig_Example_Gallery
