#
# $Revision$
#
# Makefile to build Mig distributions
#

# Location of tar binary (should be GNU tar)
TAR=	/sw/bin/tar

# Where bundles go
BUN=	bundles

# Mig directory
DIR=	mig-$(ver)

# What to call the distro file
DIST=	$(DIR).tar.gz

# Don't include CVS directories or Vim swap files in the bundle
EXCEPT=	--exclude="CVS" --exclude=".*.swp"

# Arguments to pass to find for cleantree
FINDARGS=	-exec rm {} \; -print

default: help

help:
	@echo "make cleantree           Get rid of garbage"
	@echo "make docs                Rebuild documentation from POD"
	@echo "make mig ver={version}   Build distro bundle for {version}"
	@echo " "
	@echo "   (Note that building a distro bundle rebuilds docs and"
	@echo "    cleans the tree also)"

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
