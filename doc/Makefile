# $Revision$
#
# Makefile for MiG documentation

TITLE=	MiG Documentation
HARGS=	--noindex --title="$(TITLE)"

HTML= ../src/docs/html/Apache.html ../src/docs/html/ChangeLog.html \
      ../src/docs/html/Credits.html ../src/docs/html/Features.html \
      ../src/docs/html/Install.html ../src/docs/html/Jump.html \
      ../src/docs/html/mig_cf.html ../src/docs/html/License.html \
      ../src/docs/html/Passwords.html ../src/docs/html/phpNuke.html \
      ../src/docs/html/Templates.html ../src/docs/html/Windows.html \
      ../src/docs/html/Utilities.html ../src/docs/html/Readme.html \
      ../src/docs/html/FTPinstall.html

TEXT= ../src/docs/Apache.txt ../src/docs/CHANGELOG ../src/docs/CREDITS \
      ../src/docs/FEATURES ../src/docs/INSTALL ../src/docs/Jump.txt \
      ../src/docs/LICENSE ../src/docs/mig_cf.txt ../src/docs/Passwords.txt \
      ../src/docs/phpNuke.txt ../src/docs/Templates.txt \
      ../src/docs/Utilities.txt ../src/docs/Windows.txt ../src/README \
      ../src/docs/FTPinstall.txt

default: all

all: $(HTML) $(TEXT) cleanup

../src/docs/html/Apache.html: Apache.pod
	pod2html $(HARGS) < Apache.pod > $@

../src/docs/Apache.txt: Apache.pod
	pod2text Apache.pod > $@

../src/docs/html/ChangeLog.html: ChangeLog.pod
	pod2html $(HARGS) < ChangeLog.pod > $@

../src/docs/CHANGELOG: ChangeLog.pod
	pod2text ChangeLog.pod > $@

../src/docs/html/Credits.html: Credits.pod
	pod2html $(HARGS) < Credits.pod > $@

../src/docs/CREDITS: Credits.pod
	pod2text Credits.pod > $@

../src/docs/html/Features.html: Features.pod
	pod2html $(HARGS) < Features.pod > $@

../src/docs/FEATURES: Features.pod
	pod2text Features.pod > $@

../src/docs/html/Install.html: Install.pod
	pod2html $(HARGS) < Install.pod > $@

../src/docs/INSTALL: Install.pod
	pod2text Install.pod > $@

../src/docs/html/Jump.html: Jump.pod
	pod2html $(HARGS) < Jump.pod > $@

../src/docs/Jump.txt: Jump.pod
	pod2text Jump.pod > $@

../src/docs/html/License.html: License.pod
	pod2html $(HARGS) < License.pod > $@

../src/docs/LICENSE: License.pod
	pod2text License.pod > $@

../src/docs/html/mig_cf.html: mig_cf.pod
	pod2html $(HARGS) < mig_cf.pod > $@

../src/docs/mig_cf.txt: mig_cf.pod
	pod2text mig_cf.pod > $@

../src/docs/html/Passwords.html: Passwords.pod
	pod2html $(HARGS) < Passwords.pod > $@

../src/docs/Passwords.txt: Passwords.pod
	pod2text Passwords.pod > $@

../src/docs/html/phpNuke.html: phpNuke.pod
	pod2html $(HARGS) < phpNuke.pod > $@

../src/docs/phpNuke.txt: phpNuke.pod
	pod2text phpNuke.pod > $@

../src/docs/html/Templates.html: Templates.pod
	pod2html $(HARGS) < Templates.pod > $@

../src/docs/Templates.txt: Templates.pod
	pod2text Templates.pod > $@

../src/docs/html/Utilities.html: Utilities.pod
	pod2html $(HARGS) < Utilities.pod > $@

../src/docs/Utilities.txt: Utilities.pod
	pod2text Utilities.pod > $@

../src/docs/html/Windows.html: Windows.pod
	pod2html $(HARGS) < Windows.pod > $@

../src/docs/Windows.txt: Windows.pod
	pod2text Windows.pod > $@

../src/docs/html/Readme.html: Readme.pod
	pod2html $(HARGS) < Readme.pod > $@

../src/README: Readme.pod
	pod2text Readme.pod > $@

../src/docs/html/FTPinstall.html: FTPinstall.pod
	pod2html $(HARGS) < FTPinstall.pod > $@

../src/docs/FTPinstall.txt: FTPinstall.pod
	pod2text FTPinstall.pod > $@

cleanup:
	/bin/rm -f pod2htm*

