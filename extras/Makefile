#
# $Id$
#

# Where to store bundles
DISTDIR=../../bundles/mig

# Contrib archive
CONTRIB=$(DISTDIR)/mig-contrib.tar.gz

# Gallery archive
GALLERY=$(DISTDIR)/mig-example-gallery.tar.gz

default:
	@echo "make contrib     Bundle contrib directory"
	@echo "make gallery     Bundle example gallery"

contrib:
	rm -f $(CONTRIB)
	tar cfz $(CONTRIB) mig-contrib

gallery:
	rm -f $(GALLERY)
	tar cfz $(GALLERY) Mig_Example_Gallery
