#
# Makefile for Mig documentation
#

pandoc_cmd = docker run --rm --volume "$${PWD}:/data" --user "$$(id -u):$$(id -g)" --network none pandoc/minimal

html_dir = html
text_dir = text

markdown = changelog.md credits.md install.md license.md jump.md apache.md utilities.md mig_cf.md templates.md
template = templates/pandoc.template.html

html = $(addsuffix .html, $(basename $(markdown)))
text = $(addsuffix .txt, $(basename $(markdown)))
html_with_dir = $(patsubst %,$(html_dir)/%,$(html))
text_with_dir = $(patsubst %,$(text_dir)/%,$(text))

# Default is to rebuild doc files
docs: mkdirs $(html_with_dir) $(text_with_dir)

license.md: license.md.pre ../COPYING
	cat license.md.pre ../COPYING > $@
  
$(html_dir)/%.html: %.md $(template)
	$(pandoc_cmd) -f markdown -t html --template="$(template)" \
			--title-prefix="Mig Documentation" -M title="$(shell head -n 1 $<|sed -e 's#^Mig - ##')" -M lang=en \
			$< > $@

$(text_dir)/%.txt: %.md
	$(pandoc_cmd) -f markdown -t plain $< > $@

$(html_dir) $(text_dir):
	mkdir -p $@

mkdirs: | $(html_dir) $(text_dir)

clean:
	rm -f $(html_dir)/*.html $(text_dir)/*.txt
	test ! -d $(html_dir) || rmdir $(html_dir)
	test ! -d $(text_dir) || rmdir $(text_dir)

publish: docs
	cp html/*.html $(MIG_SITE_DIR)/docs/
