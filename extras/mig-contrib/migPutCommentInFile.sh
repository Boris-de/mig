#!/bin/bash
#
# Written and contributed by:
# 	Artem G. Abanov <aga@andrey.physics.wisc.edu>
#
#######################################################################
#
# Here is a very amateurish script to show a jpg image an ask for a
# new comment to be put into the jpeg file.
# It does not do any checking or anything whatsoever.
#
#             IT REPLACES ALL OLD COMMENTS!!!!!
#
#######################################################################
#
# This script requires these external programs:
#
# "rdjpgcom"	(part of the "jpeg" package)
# "wrjpgcom"	(part of the "jpeg" package)
#
# If you do not have these programs, you can get a copy of the jpeg package
# by anonymous FTP at ftp://ftp.uu.net/graphics/jpeg/ or if you are using
# something like Linux you can probably install it with an RPM (these are
# part of the "libjpeg-6b-9" Redhat package at time of this writing).
#
# "eog"  (this can be replaced by any other image viewer... ee, xv, etc.
#

# Put here the name of the comand you want to view your jpegs with
viewcommand=eog

echo ""
echo "          Look at the picture and enter new comment at prompt."
echo "          To finish your comment enter <RETURN> twice."
echo ""

for filename in $@; do

# Start the view command
	$viewcommand $filename&

# Get the old comment
	oldcomment=`rdjpgcom $filename`

# Ask for the new comment
	echo "$filename"
	echo  "Enter new comment [ $oldcomment ]? "
	read  comment1
	while test "${comment1}"; do
		sep=""
		if test "$comment"; then
			sep='\n'
		fi
		comment="${comment}${sep}${comment1}"
		read comment1
	done

# Kill the viewer
	kill $!

# Echo the new comment back (use the old comment if the new wasn't set)
	echo -e "new comment:\n"${comment:=$oldcomment}
	echo ""

# Replace the comment in the file
	wrjpgcom -replace -comment \"$comment\" $filename > $filename"_com"

# Move the newly commented file back
	mv $filename"_com" $filename

done

