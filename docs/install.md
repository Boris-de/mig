## Mig - A general purpose photo gallery

Copyright (c) 2000-2005, Daniel M. Lowe<br>
Copyright (c) 2005-2021, Boris Wachtmeister

All rights reserved.

## License Information

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this
list of conditions and the following disclaimer.

Redistributions in binary form must reproduce the above copyright notice, this
list of conditions and the following disclaimer in the documentation and/or
other materials provided with the distribution.

The name of Daniel M. Lowe may not be used to endorse or promote products
derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

## Requirements

Mig requires a web server which supports [PHP](https://www.php.net/).
At least PHP 3.0.9 is required, or any version of PHP4.
The development platform is Apple OSX, so the code tends to assume
a unix-like environment.  As such it may or may not work (and has not
been tested by the author) on non-unix-like platforms.  Mig has been tested
with Apache (and reportedly it works with Microsoft's IIS server).

Windows users, please see the section labeled
"Running Mig Under Windows" below.

### mkGallery.pl

The `mkGallery.pl` is a separate utility that requires [Perl](https://www.perl.com).  To
generate thumbnail images, it also requires [ImageMagick](https://www.imagemagick.org/).
Neither are required to use Mig itself.

The `jhead` utility which is called by `mkGallery.pl` for EXIF header
parsing is a C source code file - it must be compiled before use.  On most
unix-like systems this is as easy as going into the `jhead` directory and
typing "make" (GNU make is recommended).

Windows users can download the pre-built `jhead` program from Mathias
Wandel's site at [https://www.sentex.net/~mwandel/jhead/](https://www.sentex.net/~mwandel/jhead/)

See the "utilities" document for more information about
`mkGallery.pl` and `jhead`.

## New Installation

Read this section if you are installing Mig for the first time.  If you
are upgrading, skip to the next section which deals with upgrading.

1. Create a directory for Mig to live in, somewhere the web server can read
the files and serve them as web pages.
2. Copy the file `index.php` to the new Mig directory.
3. Copy the file `config.php.default` to the new Mig directory and call it
`config.php`.  Any customization of Mig (except layout and colors) can be
done by editing `config.php`.
4. Create three subdirectories called `images`, `templates` and `albums`
under the new Mig directory.
5. Copy the images from the `images` subdirectory to your new `images`
subdirectory.
6. Copy the files `image.html`, `folder.html` and `style.css` from the
`templates` subdirectory to the new `templates` subdirectory.
7. Put image files (photos, etc.) into the `albums` subdirectory.  You can
make subdirectories to contain different categories of images, and those
subdirectories can contain subdirectories, and so on.  Mig doesn't place
any restriction on how many levels deep subdirectories go.  Arrange the
album any way you wish.  The name of each album is the name of the
directory, so keep that in mind.  For example:

        People
            Family
            Friends
        The_Zoo
            Dolphins
            Bears
            Lions
        Cars

    Notice that I used an underline character in **The\_Zoo**.  Mig will display
    that as a space, so the album will be called **The Zoo** as far as visitors
    will know.  This makes things simpler since some systems make using
    underlines in directory names unfriendly.

    The above example ends up being these eight gallery folders:

        People
        People : Family
        People : Friends
        The Zoo
        The Zoo : Dolphins
        The Zoo : Bears
        The Zoo : Lions
        Cars

    Mig supports JPEG, GIF and PNG images.  So files with any of these
    extensions will be recognized as images:

        .gif .png .jpg .jpe .jpeg

    Files with these extensions are recognized as audio:

        .mp3 .wav .ra .ram

    File with these extensions are recognized as video:

        .mov .avi .mpg .mpeg .wmv .mp4

8. Point your web browser to the newly-installed Mig and browse away!
9. If you can run Perl scripts on the commandline on your server, and you have
Perl and ImageMagick available, use `mkGallery.pl` to create thumbnail
images.  See the `utilities` document for more information on how.

    You should create a `utilities` subdirectory in your Mig installation and
    copy `mkGallery.pl` there, as well as `jhead` if you plan to use
    `jhead`.  If Perl is somewhere other than `/usr/bin/perl` on your system
    you may need to modify the first line of `mkGallery.pl` to reflect the
    proper location of Perl.

    If you can't run Perl scripts, you can make your own thumbnail images in
    whatever way is handy for you (an image editing program, for example).
    In each album folder, create a subdirectory called `thumbs`, and put
    thumbnail images inside.  A thumbnail should have the same filename as the
    full-size image it represents.

    The thumbnail subdirectory is `thumbs` by default, but this can be
    customized (see the `$thumbSubdir` option in `config.php`).

## Upgrading

If you are upgrading from a previous version of Mig, follow these steps:

1. Copy the `index.php` file to the Mig directory.  This should replace the
`index.php` which was already there.  If the existing file was called
`index.php3`, then the new file should also be called `index.php3`.
2. Starting with version 1.3.1, `mig.cfg` is now called `config.php`.
If `mig.cfg` is present, rename it to `config.php`.  The distribution
comes with `config.php.default`, which is the new default config file.
`config.php.default` is just an example.  There is no need to copy it over
to the Mig directory, but you might want to look it over to see if new
settings are available which you'd want to use.
3. Starting with version 1.3.2, the `funcs.php` and `lang.php` files are no
longer used.  If they are present, delete them.  All code is now contained in
`index.php`.  Mig will not fail if `funcs.php` or `lang.php` are
present, but it's sloppy to leave them lying around.
4. In version 1.3.5 a lot of changes to the layout system were made.  As a
result, anyone upgrading to 1.3.5 or later (from 1.3.4 or earlier) will
want to use the new template files provided with the Mig distribution.  If
you have customized your template files, don't fret - it should be easy for
you to merge your customizations into the new template files (they're not
very different from the old template files).  Make sure especially to use
the new CSS file.
5. If you wish to use `mkGallery.pl`, copy mkGallery.pl from the `utilities`
directory to the `utilities` subdirectory of your Mig installation.  You
may need to modify the first line if Perl is somewhere other than
`/usr/bin/perl` on your system.  If you already have `mkGallery.pl`,
check the `changelog` document to see if it's been changed since you
last installed it.  You may want to copy a fresh version of it over your
old copy.
6. If you wish to use `jhead` (note that using the EXIF functions of
`mkGallery.pl` implies using `jhead`), you may wish to compile a new copy
of `jhead` if it's a newer version than you're using now (to find out when
`jhead` was last updated, check the "changelog" document).

    If you want `mkGallery.pl` to be able to use `jhead`, put `jhead` in the
    `utilities` subdirectory of your Mig installation with `mkGallery.pl`.

    If you already have `jhead`, check the `changelog` document to see if
    it's been upgraded since you last installed it.  You may want to install
    a fresh version over your old copy.

7. If you are upgrading to 1.4.0 from an earlier version, you should copy the
`movie.gif` and `music.gif` icons from the `images` folder to your Mig
installation's `images` folder, if you plan to have any audio or video
content on your site.
8. Please be aware, that 1.5.0 has changed the templates, so that it can be customized better.

## Running Mig Under Windows

Mig can run under Windows (so I'm told) as long as the web server supports
PHP.  The Windows version of PHP is available from [https://www.php.net/](https://www.php.net/)

Mig is reported to work with IIS, Personal Web Server, and the Windows port
of Apache.  One user noted that a fairly recent version of PHP is needed
for it to work, so if you're having trouble, make sure you're using the
most recent release of PHP.

Another user reported that having a comma (,) in the directory name of any
album would cause problems, so avoid using commas.

I've had reports that Windows text editors leave files without trailing end
of line characters, which apparently confuses Mig.  If you're editing files
in a Windows text editor (such as `mig.cf` files), you may want to leave
one blank line at the end of the file, just so there's at least one end of
line character after the last "real" line.

## The Example Gallery

If you want to see an example gallery (that is, install one on your system
and examine the files that it contains), you can download one in the
[Download-Section](https://mig.wcht.de/downloads/archive/extras/Example_Gallery_1.2/).
After downloading the file, unpack it in the albums-subdirectory, and it should
appear as "Mig Example Gallery".

## Other people using Mig

You can see a list of sites using Mig at: [https://mig.wcht.de/users.php](https://mig.wcht.de/users.php)

If you'd like to list your site there, you can find my email on that page too.

## How much does Mig cost?

Mig is free.  But I don't object to getting postcards if you feel like
telling me how much you like Mig.  Postcards can be sent to:

    Dan Lowe
    P.O. Box 5725
    Cleveland, OH  44095
    USA

## Optional Customization

1. HTML templates (and colors)

    Mig is template-based.  Any change to layout or colors can be achieved by
    modifying the template files in the `templates` subdirectory.  See the
    `templates` document for more information.

    A per-folder custom template can be used in any given folder.  See the
    `FolderTemplate` section of the `mig_cf` document for more information.

2. Comments

    A comment can be attached to any image.  Comments are stored in a
    file called `mig.cf` in the same directory as the image.  See the
    `mig_cf` document for information about the format of `mig.cf` files.

    Comments are shown in a box below the image in image views.  In
    thumbnail views, they are shown as the ALT tag text.  (That behavior can
    be suppressed by setting `$suppressAltTags` to `TRUE` in `config.php`).

    As an example, let's say you have files `house.jpg`, `car.gif` and
    `dog.jpg`.  Let's say you wanted a comment on the house and the dog,
    but nothing in particular to say about the car.  Add this to `mig.cf`:

        <Comment "house.jpg">
        This is my house, which I bought in the fall of 1998.
        </Comment>

        <Comment "dog.jpg">
        This is the dog we had when I was growing up.  She was a lot of
        fun!  Sadly, she died when I was 12 years old (she had cancer).
        We had other dogs afterward, but none of them were as much fun.
        </Comment>

    (`mkGallery.pl` users, see the `-c` option which handles comments.)

    The description can be multiple lines, as long as it's enclosed inside the
    &lt;comment>...&lt;/comment> structure.  Tags such as
    &lt;comment> must be at the start of a new line.

    (If you want to use shorter comments for thumbnail hover-overs and leave
    your longer comments only on the image page itself, see the `mig_cf`
    document and read about <Short> tags).

3. EXIF data

    Many images contain EXIF header data.  `mkGallery.pl` can extract this
    data using `jhead`.  See the `utilities` document for details.

    EXIF data generally contains information such as shutter speed, aperture,
    ISO rating, the original shooting date and time, and sometimes comments.
    Mig can read some, but not all, of this data.  Future versions of Mig may
    understand more of the data than it currently does.

    By default, Mig only shows embedded comments.  To show more, adjust the
    format string `$exifFormatString` found in `config.php`.  There are
    examples in that file that explain how the formatting works.

    Note that most graphic editors destroy EXIF blocks!  They don't know how to
    write them so when they save images, they are simply not written back into
    the file.

4. Item sorting

    Items can be sorted in a custom order if desired (rather than relying on
    the default alphabetical order).  To do this define a &lt;sort> block
    in `mig.cf`.  See the `mig_cf` document for details.

5. Languages other than English

    Mig has support for other languages - however, it only supports those
    languages someone has supplied translations for.  If you want to translate
    it to another language, please get in touch with me, and I'll be glad to add
    your translations to the available list of languages.

    See `config.php.default` for more information - Mig now supports English,
    French, German, Norwegian, Portuguese, Finnish, Romanian, Russian
    (Windows-1251 and KOI8-R), Turkish, Swedish, Danish, Italian, Spanish,
    Slovak, Dutch, Polish (including an ISO-8859-2 version), Estonian,
    Japanese (ISO-2022-JP), Traditional Chinese (big5) and Czech.

    It's also possible to use multiple languages with Mig, although you must
    pick one to be the default language - that is the one you define in
    `config.php` in the `$mig_language` variable.  To change the language,
    simply add the parameter `mig_dl=LANG` to a URL as a parameter.  For
    example, take this URL:

        https://example.com/gallery/index.php?currDir=./MyStuff

    Assuming the default is something else, you can tell Mig to display it in
    Spanish by doing this:

        https://example.com/gallery/index.php?currDir=./MyStuff&mig_dl=es

    You can add links to your template files which switch languages on the fly
    using the template keyword `newLang` like this:

        <a href="%%newLang%%=es">Espanol</a>

        <a href="%%newLang%%=it">Italiano</a>

6. Pop-up windows

    Pop-up windows can be used (see the `$imagePopup` option in
    `config.php`).

7. Pagination of large galleries

    Sometimes a gallery gets so large, fitting all the thumbnails on one page
    becomes a problem (think of a gallery with 500 images).  To resolve this,
    Mig paginates large galleries.  By default, any gallery with more than 20
    images will go into a paged mode (4 images across, in 5 rows).

    This can be controlled using two options in `config.php`:
    `$maxThumbColumns` and `$maxThumbRows`.  Their names are
    self-explanatory.  Set `$maxThumbColumns` to the maximum number of columns
    desired (how many thumbnails across from left to right in one row).  Set
    `$maxThumbRows` to the maximum number of rows per page/screen.  The
    defaults are 4 columns and 5 rows (for 20 images per page).

    Note that even with pagination, Mig still has to look over the entire
    directory (to sort it, decide what is on which page, etc.) and in really
    large directories, you'll still run into problems.  Mig may take a long
    time to run, etc.  It's best to keep directories down to more reasonable
    sizes (after all, that's why directories were invented - organization).

8. Using random thumbnails instead of folder icons

    Instead of using the generic folder icon for folder views, Mig can use a
    randomly selected thumbnail from the folder as the icon.  To turn this
    behavior on, set `$randomFolderThumbs` to `TRUE` in `config.php`.
    (See also `$folderNameLength` and `$useRealRandThumbs`.)

    If the folder itself contains only other folders, and no images, Mig will
    traverse subdirectories until it finds a usable thumbnail to use as an
    icon.  So the only time the generic icon will be used is in a situation
    where there is a folder that contains no thumbnails, whose subfolders and
    their subfolders, etc., contain no thumbnails.  In other words, only a
    useless folder branch would result in the generic folder icon being shown.

9. Using a specific thumbnail instead of a folder icon

    If you want to use thumbnails as icons, but would rather pick your own
    instead of letting Mig pick for you, check out the `UseThumb` option in
    `mig.cf` files.  See the `mig_cf` document for more information.

## Common problems

1.  Why aren't there any thumbnails?

    You have to create thumbnails with `mkGallery.pl`.  See the document on
    `utilities`.  `mkGallery.pl` requires Perl and the "convert"
    program from ImageMagick.

    If you can't do it that way, don't despair - you can make your own
    thumbnails in any image editing program.  Earlier in this document are
    instructions on how to name the files and where to put them.

2.  I am getting a bunch of errors in my browser!

    You (or your web server admin) probably have `error_reporting` set too
    high in the PHP configuration in your web server.  Put this line
    near the top of `index.php` (but not before the "&lt;?php"
    line at the top).

        error_reporting (E_ALL & ~E_NOTICE);    // for PHP3 systems
        error_reporting (2039);                 // for PHP4 systems

3.  I'm getting thumbnails like image.jpg.0 or image.jpg.0.0.  Why?

    I'm not exactly sure, but it appears to happen with certain versions of
    the `convert` program.  If it is happening to you, try running
    `mkGallery.pl` using the `-K` flag and see if your images come out
    properly then.

4. Some of my images and folders are not displayed

    From 1.5.0 a new way of handling file- and directorynames is used. Several non-ascii-letters are not allowed in the default configuration. If you want to use files with these names be sure that you add these letters to `$imageFilenameRegexpr` and `$currDirNameRegexpr` in your `config.php`.

## Bug Reporting

To report a bug, just drop me a mail. See https://mig.wcht.de/ for the current address (it changes due to spam)

## Email List

[mig-announce](https://lists.sourceforge.net/lists/listinfo/mig-announce) is an announcements-only list.
Generally speaking that means the only traffic it gets are announcements of new versions posted by the author.
