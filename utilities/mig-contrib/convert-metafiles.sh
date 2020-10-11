#!/bin/sh
#
# Contributed & written by: Dan Lowe <dan@tangledhelix.com>
# This used to be in the main Mig distribution but is now deprecated and
# can be found here in the contrib package instead.
#
# This script calls mf_conv.pl recursively to do subfolders, so you'll need
# to make sure mf_conv.pl is available as well!
#

conv="$(dirname "${0}")/mf_conv.pl"

echo "Converting Mig metafiles..."
echo " "

find . -type d -print > /tmp/$$.mv_conv
${conv} -I /tmp/$$.mv_conv
rm /tmp/$$.mv_conv

