## General

This document aims to explain how `mig.cf` works and what it is.

Each folder under the `album` subdirectory can optionally have a `mig.cf`
file in it.  This file contains things like image comments, a list of
hidden items (if any) and so on.

The format is borrowed from the config file format of Apache, and is sort
of similar to HTML.  Hopefully this means that it will be simple for most
people to figure out and use.

An example `mig.cf` might look like this:

    # Beginning of example mig.cf
    #
    <Bulletin>
    We spent four days in Rome.  Even with four whole days to tour
    the area, we found we couldn't cover everything.  The Vatican
    alone took most of the second day, and we only saw half of it.
    </Bulletin>

    <Comment "AUT_2323.JPG">
    Massive mosaic found in the Basilica of Saint Peter in the Vatican.
    </Comment>

    <Comment "AUT_2406.JPG">
    The Colloseum of Rome.
    </Comment>
    #
    # End of example mig.cf

An element is opened by a tag (such as <Bulletin>), and closed by
the associated close-tag (the tag with its name preceded by a slash) such
as &lt;/Bulletin>.

An element can have an argument, as in <Comment
"AUT\_2406.JPG">.

These tags must be at the beginning of a line.  Case in the tag name is not
important, so <Comment> is the same as &lt;comment> or
<CoMMenT>.

(If you installed the example gallery, look inside for `mig.cf` files for
some useful examples.)

## Remarks and blank lines

Any line starting with `#` is considered a remark and is ignored by Mig.
Blank lines are also ignored.  Neither are ignored inside an element block
such as &lt;comment>, so it's best if they don't appear inside blocks.

## Using a Windows text editor

If you are editing `mig.cf` files using a Windows text editor, you may
want to leave one or more blank lines at the end of the file.  Some Windows
text editors apparently don't add end-of-line characters to the end of the
last line in the file, and this confuses Mig.

## Bulletins

    <Bulletin>
    [some text]
    </Bulletin>

A bulletin is just like a comment, only it's attached to the folder rather
than a single image.   An example is shown near the top of this document.

## Image Comments

    <Comment "image_file">
    [some text]
    </Comment>

A comment is attached to an image.  When viewing that image, the comment
text associated with it will be displayed in a box below the image. An
example can be found near the top of this file.

The argument to `Comment` must be enclosed in quotes so Mig can properly
recognize files with spaces in their names.

As of 0.90, comments are loaded into the ALT tag of thumbnail images,
so you can hover over an image and view its description.  (As of 1.3.0
they're also loaded into the TITLE modifier for better cross-browser
compatibility).

Certain HTML elements don't mix well with ALT and TITLE, and should be
avoided, notably things like &lt;A HREF>.  You can turn ALT/TITLE
tags off if you wish, by setting `$suppressAltTags` to `TRUE` in
`config.php`.

## Short Comments

    <Short "image_file">
    [some text]
    </Short>

Sometimes you want a long comment on the image itself, but a shorter one
used in the ALT and TITLE tags on the thumbnail page (for hovering over
links).  You can use the <Short> tag in `mig.cf` files for that.
This element works just like <Comment> except that it is used only
in the hover-over tags on thumbnail pages.

If there is a <Comment> block but no <Short> block for an
image, the <Comment> block will be used for the hover-over.

## Folder Icons

    FolderIcon folder_name icon_file.gif

It is sometimes desirable to use a custom icon for a given folder.
This can be done using the `FolderIcon` entity.  Given a folder
`Trips/Rome`, and an icon `colloseum.gif`, the following can be
defined in `Trips/mig.cf`:

    FolderIcon Rome colloseum.gif

The `colloseum.gif` file should be placed in Mig's `images` folder,
located in the root directory of your Mig installation.

If you are using `$randomFolderThumbs`, note that `FolderIcon` overrides
that setting.

If you want to use a specific thumbnail for a given directory as its
folder icon, you instead want to look at the `UseThumb` directive.

## Thumbnails as Folder Icons

    UseThumb folder_name image_name 

To pick a specific image to use as a thumbnail for your folder, use the
`UseThumb` directive.  The thumbnail file associated with that image will
be displayed as the folder's icon.

    UseThumb Rome IMG_4935.JPG

In that case, the image `IMG_4935.JPG`, which should be located in the
`Rome` folder, will be used as the folder's icon.

## Per-folder Template

    FolderTemplate /path/to/file.tmpl
    FolderTemplate file.tmpl

It is sometimes desirable to use a custom template for a given folder
rather than using the site-wide template.  This can be defined with the
`FolderTemplate` entity.

If the `FolderTemplate` entity is followed by a filename, the file is
assumed to be in the folder in question.

If the `FolderTemplate` entity is followed by a full file path, beginning
with a `/` character, that full path is instead used.

## Per-folder Page Title

    PageTitle This is my Page Title for this Folder

A folder-specific page title can be defined with the `PageTitle` entity,
as shown above.  This will override the site-wide page title on a
folder-by-folder basis.

## Per-folder Maintainer Address

    MaintAddr joe@mama.com

A folder-specific maintainer email address can be specified using the
`MaintAddr` entity.  This will override the site-wide value `$maintAddr`.

This is handy in the case where different folders site are really
owned by different people.

## Per-folder Column Settings

    MaxFolderColumns 2
    MaxThumbColumns 4

`MaxFolderColumns` overrides the site-wide value `$maxFolderColumns` in a
given folder.

`MaxThumbColumns` overrides the site-wide value `$maxThumbColumns` in a
given folder.

## Hidden Items

    <Hidden>
    [item]
    [item]
    </Hidden>

`Hidden` elements are lists of items which are invisible to the browser.
This is useful for things that should not be publicly viewable, or for
album directories that exist but are not yet complete.  Each line between
the start and end tags is either a file or a directory (Mig can sort out
which is which on its own).

    <Hidden>
    New folder
    England
    </Hidden>

`Hidden` is not considered a security feature.  It's not difficult for
someone to get around this if they know the name of the folder or image
being hidden.  This is security through obscurity (which is only one step
above no security).

## Sort Order

    <Sort>
    [item]
    [item]
    </Sort>

By default, items are sorted by their ASCII value (for the purpose of this
discussion, this is close to being alphabetically).  However, it can be
desirable to control the order in which items (either images or folders)
appear.

For example, this is what the contents of a directory might look like:

    Ceremony/     Cut the Cake/  Home/         Reception/    Cigars/
    AUT_3706.JPG  AUT_3712.JPG   AUT_3714.JPG  AUT_3716.JPG
    AUT_3707.JPG  AUT_3713.JPG   AUT_3715.JPG  AUT_3717.JPG

Note that the first five are directories, and the other eight are files.
This will display by default as a list of folders, then a list of images,
each list in alphabetical (ASCII) order.

Let's say that `Home` should move to the top of the list and `Cigars` to
just above `Reception`.

    <Sort>
    Home
    Ceremony
    Cutting the Cake
    Cigars
    Reception
    </Sort>

Now let's move images 3716 and 3717 to the third and fourth position in the
list of images.

    <Sort>
    AUT_3706.JPG
    AUT_3707.JPG
    AUT_3716.JPG
    AUT_3717.JPG
    </Sort>

Attentive readers will have noticed that there are four images that aren't
even mentioned here.  What happens to them?  They're sorted alphabetically
independently of the pre-sorted list, and tacked on afterward.  The final
list would be:

    AUT_3706.JPG
    AUT_3707.JPG
    AUT_3716.JPG
    AUT_3717.JPG
    AUT_3712.JPG
    AUT_3713.JPG
    AUT_3714.JPG
    AUT_3715.JPG

If everything goes into one &lt;sort> block, Mig can figure it out
(which are files, which are directories).  This is arguably sloppy from the
point of view of a human though.  It's easier to define multiple sort
blocks in those situations; one for files, one for directories.  Mig can
detect and use multiple sort blocks.

## Security

See the `apache` document if you are running Apache and would like to
prevent people from browsing your `mig.cf` and/or `exif.inf` files.
