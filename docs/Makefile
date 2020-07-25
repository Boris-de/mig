#
# $Id$
#
# Makefile for Mig documentation
#

hcmd=	pod2html --noindex
tcmd=	pod2text

html_dir = html
text_dir = text

pods=	changelog.pod credits.pod install.pod license.pod \
	jump.pod apache.pod utilities.pod mig_cf.pod templates.pod

html = $(addsuffix .html, $(basename $(pods)))
text =$(addsuffix .txt, $(basename $(pods)))
html_with_dir = $(patsubst %,$(html_dir)/%,$(html))
text_with_dir = $(patsubst %,$(text_dir)/%,$(text))

# Default is to rebuild doc files
docs: mkdirs $(html_with_dir) $(text_with_dir)

license.pod: ../COPYING
	cat license.pod.pre ../COPYING > license.pod
  
$(html_dir)/%.html: %.pod
	$(hcmd) --title="Mig Documentation - $(shell basename $@ .html)" \
		--infile=$< --outfile=$@
	rm -f pod2htm*

$(text_dir)/%.txt: %.pod
	$(tcmd) $< $@

$(html_dir) $(text_dir):
	mkdir -p $@

mkdirs: | $(html_dir) $(text_dir)

clean:
	rm -f $(html_dir)/*.html $(text_dir)/*.txt
	test ! -d $(html_dir) || rmdir $(html_dir)
	test ! -d $(text_dir) || rmdir $(text_dir)

$(MIG_SF_NET_DIR)/docs:
	mkdir -p $(MIG_SF_NET_DIR)/docs

publish: docs | $(MIG_SF_NET_DIR)/docs
	cp html/*.html $(MIG_SF_NET_DIR)/docs
	cd $(MIG_SF_NET_DIR) ; make
