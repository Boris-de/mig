#!/bin/sh
#
# $Revision$
#
# Installation script for Mig
#
# - See the file docs/INSTALL for directions
# - WINDOWS USERS: also see docs/Windows.txt
# - PHP-NUKE USERS: also see docs/phpNuke.txt
#
# Mig - A general purpose photo gallery management system.
#
# Copyright 2000-2002 Daniel M. Lowe <dan@tangledhelix.com>
# Mig is available at http://mig.sourceforge.net/
#
# LICENSE INFORMATION
# -------------------
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
# A copy of the GPL can be found at http://www.gnu.org/copyleft/gpl.html
# or in the file docs/LICENSE.
#

date=`date`
uname=`uname -s`
mypwd=`pwd`
perl=`which perl`

ICONS="folder.gif no_thumb.gif"

# Perl utility scripts
PERL_UTILS="mkGallery"

# Template files for "normal" mode
TMPL="folder.html image.html style.css"
# Template files for "PHP-Nuke" mode
NTMPL="mig_folder.php mig_image.php"

# Where you can find Mig online
HOMEPAGE="http://mig.sourceforge.net/"
# Location of the license file
LICENSE="docs/LICENSE"

# Try to make sure the web server can read the things we touch during this
# install process.
umask 0022

# Solaris /bin/echo doesn't work correctly when used with "-n".
if [ "${uname}" = "SunOS" ]; then
    echo="/usr/ucb/echo"
else
    echo="/bin/echo"
fi

# Initial marquis
echo " "
echo " "
echo "Mig Installer, Copyright (C) 2000-2001 Dan Lowe"
echo " "
echo "Mig comes with ABSOLUTELY NO WARRANTY.  This is free software, and"
echo "you are welcome to redistribute it under certain conditions."
echo " "
echo "Mig is released under the GNU General Public License (GPL)."
echo " "
echo "You can type \"quit\" at any prompt to exit the installer."
echo " "

# Get the license stuff out of the way.
answer=x
while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
    ${echo} -n "Do you wish to read the license now? (y/n) "
    read yn
    answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
    if [ "${answer}" = "quit" ]; then
        exit
    fi
done

if [ "${answer}" = "y" ]; then
    more ${LICENSE}
else
    echo " "
    echo "OK.  If you wish to view the license later, it can be found at"
    echo "http://www.gnu.org/copyleft/gpl.html or in ${LICENSE}"
    echo " "
fi

echo " "

answer=x
while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
    ${echo} -n "Do you accept the terms of this license? (y/n) "
    read yn
    answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
    if [ "${answer}" = "quit" ]; then
        exit
    fi
done

# Quit if the license is not acceptable.
if [ "${answer}" = "n" ]; then
    echo "Quitting."
    exit 0
fi

echo " "

# Determine if this is a phpNuke site or not.
answer=x
while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
    echo "(If you don't know what phpNuke is, just answer no)"
    ${echo} -n "Are you installing on a phpNuke-based site? (y/n) "
    read yn
    answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
    if [ "${answer}" = "quit" ]; then
        exit
    fi
done

if [ "${answer}" = "y" ]; then
    phpNuke=ON
fi

# If this is a phpNuke site, figure out where the root install is.

if [ "${phpNuke}" = "ON" ]; then
    phpnukeroot=""
    default_phpnukeroot=""
    finaldirconfirm=x
    dirisok=x

    echo " "
    echo "I need to know where the root of your PHP-Nuke installation is"
    echo "because Mig includes the header & footer files from there."
    echo "No changes will be made to your PHP-Nuke installation directory"
    echo "unless you explicitly set it as your Mig install directory in"
    echo "the following step."
    echo " "
    echo "The directory we are asking for here is where the root of your"
    echo "PHP-Nuke site lives.  A subdirectory called \"mig\" will be"
    echo "created there, and Mig will be installed into that subdirectory."
    echo "I will also place the script \"mig.php\" there.  \"mig.php\" is"
    echo "what you will point to when you want to point to your image"
    echo "gallery."
    echo " "
    echo "header.php and footer.php should be located in the directory you"
    echo "are about to specify."

    while [ "${dirisok}" != "y" ]; do
        while [ "${finaldirconfirm}" != "y" ]; do
            echo " "
            echo "What is your PHP-Nuke root directory?"
            ${echo} -n "=> [${default_phpnukeroot}]: "
            read phpnukeroot
            if [ "${phpnukeroot}" = "quit" ]; then
                exit
            fi
            if [ "x${phpnukeroot}" = "x" ]; then
                phpnukeroot=${default_phpnukeroot}
            fi
            echo " "
            echo "=> Selected \"${phpnukeroot}\""
            ${echo} -n "Is this OK? (y/n): "
            read yn
            if [ "${yn}" = "quit" ]; then
                exit
            fi
            finaldirconfirm=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
            default_phpnukeroot=${phpnukeroot}
        done
        # make sure it's a directory, and we can write to it.
        if [ -d ${phpnukeroot} -a -w ${phpnukeroot} ]; then
            dirisok=y
        else
            echo " "
            echo "Can't write to ${phpnukeroot}, or isn't a directory."
            dirisok=n
            finaldirconfirm=n
        fi
    done
fi

echo " "
if [ "${phpNuke}" = "ON" ]; then
    echo " "
    echo "  * Will use ${phpnukeroot}/mig"
    echo "  * as the installation directory for Mig."
    echo " "
    installdir="${phpnukeroot}/mig"
    if [ ! -d ${installdir} ]; then
        mkdir -m 0755 ${phpnukeroot}/mig
    fi
else
    echo "Please choose an installation directory.  This will be the directory"
    echo "from which your pages are served.  It must be located within your"
    echo "web server's document root.  For example, on my system I use an"
    echo "install directory of \"/usr/apache/htdocs/gallery\".  This lets me"
    echo "use the URL \"http://tangledhelix.com/gallery/\"."
    echo " "
    echo "NOTE: shortcuts like \$HOME and ~ probably won't work.  Please"
    echo "use the full correct path to the directory you wish to use."
    echo " "

    # The big while block following basically tries to figure out if the
    # install directory is OK, and makes sure it's OK with the user before
    # moving along.

    default_installdir=""
    finaldirconfirm=x
    dirisok=x

    while [ "${dirisok}" != "y" ]; do
        while [ "${finaldirconfirm}" != "y" ]; do
            ${echo} -n "Install directory [${default_installdir}]: "
            read installdir
            if [ "${installdir}" = "quit" ]; then
                exit
            fi
            if [ "x${installdir}" = "x" ]; then
                installdir=${default_installdir}
            fi
            echo "=> Selected ${installdir}"
            ${echo} -n "Is this OK? (y/n): "
            read yn
            if [ "${yn}" = "quit" ]; then
                exit
            fi
            finaldirconfirm=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
            default_installdir=${installdir}
        done
        if [ -f ${installdir} ]; then
            echo "** FATAL ERROR: ${installdir} exists and is a file!"
            exit 1
        fi
        if [ -d ${installdir} ]; then
            dirisok=y
        else
            echo "${installdir} does not exist."
            answer=x
            while [ "${answer}" != "y" -a "${answer}" != "n" ]; do
                ${echo} -n "Do you want me to create it? (y/n): "
                read yn
                answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
                if [ "${answer}" = "quit" ]; then
                    exit
                fi
            done
            if [ "${answer}" = "y" ]; then
                mkdir -p -m 0755 ${installdir}
                dirisok=y
            else
                echo "Skipping creation of ${installdir}"
                dirisok=y
            fi
        fi
    done
fi

echo " "
answer=x
while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
    ${echo} -n "Ready to do the installation.  Should I proceed? (y/n) "
    read yn
    if [ "${yn}" = "quit" ]; then
        exit
    fi
    answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
done
if [ "${answer}" = "n" ]; then
    echo " "
    echo "Aborting installation."
    exit
fi

echo " "
echo "OK then, Installing Mig files..."
echo " "

# Install things as appropriate
echo "  => icons"
mkdir -m 0755 -p ${installdir}/images
for i in ${ICONS}; do
    if [ -f ${installdir}/images/${i} ]; then
        answer=x
        while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
            ${echo} -n "OK to over-write ${installdir}/images/${i} ? (y/n) "
            read yn
            if [ "${yn}" = "quit" ]; then
                exit
            fi
            answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
        done
        if [ "${answer}" = "y" ]; then
            cp images/${i} ${installdir}/images/${i}
            chmod 0644 ${installdir}/images/${i}
        else
            echo "OK, installing as ${installdir}/images/${i}.new instead."
            echo " "
            cp images/${i} ${installdir}/images/${i}.new
            chmod 0644 ${installdir}/images/${i}.new
        fi
    else
        cp images/${i} ${installdir}/images/${i}
        chmod 0644 ${installdir}/images/${i}
    fi
done

echo "  => utilities"
mkdir -m 0755 -p ${installdir}/util
for i in ${PERL_UTILS}; do
    if [ "${perl}" != "/usr/bin/perl" -a -x "${perl}" ]
    then
        sed "s,/usr/bin/perl,${perl}," \
            util/${i} > ${installdir}/util/${i}
    else
        cp util/${i} ${installdir}/util/${i}
    fi
    chmod 0755 ${installdir}/util/${i}
done

# Clean up "old" way of handling jhead.
/bin/rm -f ${installdir}/util/Makefile
/bin/rm -f ${installdir}/util/jhead.c
if [ -f ${installdir}/util/jhead ]; then
    /bin/rm -f ${installdir}/util/jhead
fi

# Install jhead
cd ${mypwd}/util
if [ -w ${installdir}/util ]; then
    tar cf - jhead | ( cd ${installdir}/util ; tar xf - )
else
    echo " "
    echo "Uh oh... can't write to ${installdir}/util !"
    echo "Error installing jhead utility files."
    echo " "
fi
cd ${mypwd}

echo "  => default config file (config.php.default)"
if [ "${phpNuke}" = "ON" ]; then
    cat config.php.default |\
        sed "s,phpNukeCompatible = FALSE,phpNukeCompatible = TRUE," |\
        sed "s,phpNukeRoot = '',phpNukeRoot = '${phpnukeroot}'," |\
        > ${installdir}/config.php.default
else
    cp config.php.default ${installdir}/config.php.default
fi
chmod 0644 ${installdir}/config.php.default

# Get rid of old default, mig.cfg.default...
/bin/rm -f ${installdir}/mig.cfg.default

# Change over to new file if it seems necessary
if [ -f ${installdir}/mig.cfg -a ! -f ${installdir}/config.php ]; then
    echo "You have a mig.cfg file. The new config file name is config.php."
    answer=x
    while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
        ${echo} -n "Should I change it for you? (y/n) "
        read yn
        if [ "${yn}" = "quit" ]; then
            exit
        fi
        answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
    done
    if [ "${answer}" = "y" ]; then
        mv ${installdir}/mig.cfg ${installdir}/config.php
        chmod 0644 ${installdir}/config.php
    else
        echo "Okay, but without config.php, Mig will use config.php.default"
        echo "for its settings.  You've been warned."
    fi
fi

# Get rid of mig.cfg if it seems necessary
if [ -f ${installdir}/mig.cfg -a -f ${installdir}/config.php ];  then
    echo "You have a mig.cfg file, but you also have a config.php."
    echo "mig.cfg is outdated - the proper config file is config.php."
    answer=x
    while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
        ${echo} -n "Should I remove mig.cfg? (y/n) "
        read yn
        if [ "${yn}" = "quit" ]; then
            exit
        fi
        answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
    done
    if [ "${answer}" = "y" ]; then
        /bin/rm -f ${installdir}/mig.cfg
        /bin/rm -f ${installdir}/mig.cfg.default
    else
        echo "Okay, but you should get rid of mig.cfg and mig.cfg.default"
        echo "at some point."
    fi
fi

# If mig.cfg.default still exists, kill it
/bin/rm -f ${installdir}/mig.cfg.default

echo "  => album directory"
echo "     (${installdir}/albums)"
if [ -d ${installdir}/albums ]; then
    chmod 0755 ${installdir}/albums
else
    mkdir -m 0755 -p ${installdir}/albums
fi

echo " "
echo "  => templates directory"
mkdir -m 0755 -p ${installdir}/templates

if [ -d "${installdir}/albums/Examples and Such" ]; then
    echo "I found ${installdir}/albums/Examples and Such/"
    answer=x
    while [ "${answer}" != "n" -a "${answer}" != "y" ]; do
        ${echo} -n "Should I remove it?  It's old and outdated. (y/n) "
        read yn
        if [ "${yn}" = "quit" ]; then
            exit
        fi
        answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
    done
    if [ "${answer}" = "y" ]; then
        echo "  => removing old example album \"Examples and Such\" ..."
        /bin/rm -rf ${installdir}/albums/Examples\ and\ Such
    else
        echo "OK, leaving it alone."
    fi
fi

echo " "
echo "An example gallery is included with Mig to provide a tactile"
echo "installation which you can use to learn more about how a gallery"
echo "might be set up."
echo " "

answer=x
while [ "${answer}" != "y" -a "${answer}" != "n" ]; do
    ${echo} -n "Do you wish to install the example gallery? (y/n) "
    read yn
    if [ "${yn}" = "quit" ]; then
        exit
    fi
    answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
done

if [ "${answer}" = "n" ]; then
    echo " "
    echo "  => skipping example gallery"
fi

if [ "${answer}" = "y" ]; then
    echo " "
    echo "  => installing example gallery in \"Example_Gallery\"..."
    cd ${mypwd}
    if [ -w ${installdir}/albums ]; then
        tar cf - Example_Gallery | ( cd ${installdir}/albums ; tar xf - )
    else
        echo " "
        echo "Uh oh... can't write to ${installdir}/albums !"
        echo "Can't install example gallery."
        echo " "
    fi
    cd ${mypwd}
fi

echo " "

if [ "${phpNuke}" = "ON" ]; then
    J="${NTMPL}"
else
    J="${TMPL}"
fi

for file in ${J}; do
    if [ -f ${installdir}/templates/${file} ]; then
        echo " "
        echo "templates/${file} already exists.  I can install a"
        echo "new version of this template, but if I do so, any customizations"
        echo "you have made to this file will be lost.  I can either over-"
        echo "write the template with a new version, or I can put the new"
        echo "version into \"templates/new_${file}\"."
        echo " "
        answer=x
        while [ "${answer}" != "y" -a "${answer}" != "n" ]; do
            ${echo} -n "Shall I over-write the existing template? (y/n) "
            read yn
            if [ "${yn}" = "quit" ]; then
                exit
            fi
            answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
        done
        if [ "${answer}" = "y" ]; then
            echo "  => templates/${file}"
            echo "     (making backup copy in templates/${file}.bak)"
            cp ${installdir}/templates/${file} \
              ${installdir}/templates/${file}.bak
            cp templates/${file} ${installdir}/templates
            chmod 0644 ${installdir}/templates/${file}
        fi
        if [ "${answer}" = "n" ]; then
            echo "  => templates/new_${file}"
            cp templates/${file} ${installdir}/templates/new_${file}
            chmod 0644 ${installdir}/templates/new_${file}
        fi
    else
        echo "  => templates/${file}"
        cp templates/${file} ${installdir}/templates
        chmod 0644 ${installdir}/templates/${file}
    fi
done

echo " "
echo "  => function library (funcs.php)"
cp funcs.php ${installdir}
chmod 0644 ${installdir}/funcs.php

echo "  => language library (lang.php)"
cp lang.php ${installdir}
chmod 0644 ${installdir}/lang.php

if [ "${phpNuke}" = "ON" ]; then
    mainFile="mig"
    mainRoot="${phpnukeroot}"
else
    mainFile="index"
    mainRoot="${installdir}"
fi

echo " "
echo "If you are using PHP3 and not PHP4, you might need to have a filename"
echo "ending in \".php3\".  I can install the main Mig PHP code as"
echo "either \"${mainFile}.php\" or \"${mainFile}.php3\"."
echo " "

answer=x
while [ "${answer}" != "y" -a "${answer}" != "n" ]; do
    ${echo} -n "Should I install the file as \"${mainFile}.php3\"?  (y/n) "
    read yn
    if [ "${yn}" = "quit" ]; then
        exit
    fi
    answer=`echo "${yn}" | tr '[A-Z]' '[a-z]'`
done

if [ "${answer}" = "y" ]; then
    mainExt="php3"
else
    mainExt="php"
fi

echo "  => ${mainFile}.${mainExt}"
cp index.php ${mainRoot}/${mainFile}.$mainExt
chmod 0644 ${mainRoot}/${mainFile}.$mainExt

echo " "
echo "(Note that if you find this choice was incorrect, you can simply"
echo "rename the file... either ${mainFile}.php or ${mainFile}.php3 as"
echo "appropriate for your system.)"

echo " "
echo "Installation complete.  Please report any bugs to"
echo "<dan@tangledhelix.com>."

if [ "${phpNuke}" = "ON" ]; then
    echo " "
    echo "If this is your first time installing Mig on your PHP-Nuke site,"
    echo "you will need to copy over the config file:"
    echo "   ${installdir}/config.php.default"
    echo "to:"
    echo "   ${phpnukeroot}/config.php"
    echo "and customize it to your needs."
fi

echo " "
echo "Mig - ${HOMEPAGE}"
echo " "

exit 0
