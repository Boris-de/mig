#
# $Revision$
#
# Makefile to build MiG distributions
#

# Location of tar binary (should be GNU tar)
TAR=	/sw/bin/tar

# Where bundles go
BUN=	bundles

# Mig directory
DIR=	mig-$(ver)

# What to call the distro file
DIST=	$(DIR).tar.gz

# Exclusions (used by tar to avoid including crap like
# .AppleDouble or Vim .swp files)
EXCEPT=	--exclude=".CFUserTextEncoding" --exclude=".DS_Store" \
	--exclude="CVS" --exclude=".FBC*" --exclude=".Apple*" \
	--exclude=".*.swp"

# Arguments to pass to find for cleantree
FINDARGS=	-exec rm {} \; -print

default: help

help:
	@echo "To build a distribution bundle:"
	@echo "    make mig ver={version}"
	@echo "To clean cruft out of the tree"
	@echo "    make cleantree"

mig: docs cleantree
	@/bin/rm -f $(BUN)/$(DIST)
	@/bin/mv src $(DIR)
	@$(TAR) --gzip $(EXCEPT) -cf $(BUN)/$(DIST) $(DIR)
	@/bin/mv $(DIR) src
	@/bin/chmod 0644 $(BUN)/$(DIST)
	@/bin/ls -l $(BUN)/$(DIST)

docs:
	@cd doc ; make ; cd ..

cleantree:
	@echo "Cleaning up garbage files..."
	@find . -name '.CFUserTextEncoding' $(FINDARGS)
	@find . -name '.DS_Store' $(FINDARGS)
	@find . -name '.FBCIndex' $(FINDARGS)
	@find . -name '.FBCLockFolder' $(FINDARGS)
	@find . -name '.AppleDouble' $(FINDARGS)
	@find . -name '.*.swp' -exec echo "WARNING: found {}" \;
