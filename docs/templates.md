Mig - Templates
===============

Page layout is handled using template files wherever possible.

## About Tags

Template files are simply HTML files with special tags embedded in
them.  Mig expands these tags at runtime.

Tags are surrounded by `%%` marks, such as this example:

    <title>%%pageTitle%%</title>

In this case, "pageTitle" is the relevant tag name.

There is a glossary of tags later in this document.

If you don't know anything about HTML you can try checking out
[htmlhelp.com](https://www.htmlhelp.com/) or looking for some other
online help sites by using a search engine such as [Google](https://www.google.com).

Make backups of template files before modifying them in case things don't
work out as expected.

## Including Files

An "include" function is provided.  To include a file,
place a directive like this one on a line by itself:

    #include "filename";

Such as:

    #include "custom.html";

The contents of the specified file will be inserted in place of the
`#include` placeholder.

If the file mentioned by `#include` is a CGI file then it will be
executed, and its output will be placed there instead.  NOTE: CGI can only
be used with Apache servers (and not even every installation
of Apache will do this correctly).  Sorry, it's a limitation in PHP
that I can't work around.

If including a CGI make sure it prints appropriate HTTP headers
before anything else (just like any other situation where CGI is used).

Please note some things about `#include`:

1. Included files must be located in the `templates` directory.
2. If that's undesirable, a symbolic link will also work.  For example:

        ln -s /www/htdocs/includes/custom.html /www/htdocs/mig/templates

    This would create a symbolic link `/www/htdocs/mig/templates/custom.html`
    which would point to the real file `/www/htdocs/includes/custom.html`.
    Thus, there's only one copy to maintain.

3. The `#include` directive must be on a line by itself.  It will not
function if anything else is on that line.  Also, the filename
must be in quotation marks, and the command must be terminated with
a semicolon.  Here are some examples:

        #include "custom.html";           # RIGHT
        #include "custom.html"            # WRONG - no semicolon
        #include custom.html;             # WRONG - no quotes
        <p>#include "custom.html";</p>    # WRONG - not alone on line

4. As of version 1.2.5 it is possible to include PHP files as well as other
types of files.  The one difference is that for PHP files the filename must
have an extension of either ".php" or ".php3".

## Special Files

There are three special files that Mig uses for its own purposes.

- `templates/folder.html` - used for any view where folders and/or
thumbnail images are shown.
- `templates/image.html` - used for any view where an image is shown
by itself.
- `templates/style.css` - Contains `text/css` (Cascading Style Sheet)
markup.

## Defined Tags

The following is a glossary of recognized Mig template tags, and what they
are expanded to.

1. Tags for use in any template
    - `baseURL`

        URL to call this script (Mig) again.

    - `maintAddr`

        Email address of album maintainer (as defined in `config.php`).
        This can be customized per-folder using a `mig.cf` file.

    - `version`

        Version number of this Mig installation.

    - `backLink`

        This is the "up one level" link on each page.

    - `currDir`

        Current directory, in URL-encoded format.

    - `newCurrDir`

        Same as "currDir" with leading "./" removed.

    - `pageTitle`

        &lt;TITLE> tag for this page.

    - `youAreHere`

        This is the "you are here" path at the top of each page.

    - `distURL`

        URL of Mig home page

    - `description`

        Description of the image, taken from the comments file(s).  For folders,
        this is &lt;Bulletin>.

    - `newLang`

        Lets you switch from one language to another.  For example, if your default
        language is English (en) but you choose to publish also in Spanish and
        Italian, you can add links like these to your template files:

            <a href="%%newLang%%=es">Espanol</a>

            <a href="%%newLang%%=it">Italiano</a>

        Then, anyone visiting your pages would be given a default page in English
        (or whatever `$mig_language` is set to in `config.php`) but clicking on
        the links as shown above would tell Mig to switch over to the new language
        (in this case either Spanish or Italian).

        You can also link directly to a version of your site before the visitor
        ever gets there (so they don't have to get English first, then switch).  To
        directly link you'd add a "mig\_dl=LANG" parameter to your URL.  So if your
        usual link looks like this:

            https://example.com/mig/index.php

        You'd instead use this:

            https://example.com/mig/index.php?mig_dl=es

        Or you can link even if there are already parameters in the URL; just use
        an & instead of ?

            https://example.com/mig/index.php?currDir=./My_Stuff&mig_dl=es
2. Tags used only in `folder.html` (or `mig_folder.php` in Portal mode)
    - `folderList`

        Expands to a section of &lt;TABLE> code which
        displays a list of folders in the current folder.

    - `imageList`

        Expands to a section of &lt;TABLE> code which
        displays a list of images in the current folder.
3. Tags used only in `image.html` (or `mig_image.php` in Portal mode)
    - `image`

        The current image being shown.

    - `albumURLroot`

        Root URL of the actual album where images live
        (used in &lt;IMG SRC="..."> HTML tags).

    - `nextLink`

        A link to the next item in the sequence.

    - `prevLink`

        A link to the previous item in the sequence.

    - `currPos`

        Current position in the list (i.e. #5 of 7)

    - `encodedImageURL`

        Image filename run through `rawurlencode()` in case
        there's a space embedded in it or something.

    - `imageSize`

        HTML that gives WIDTH=nnn and HEIGHT=nnn tags
        for the image being displayed.

    - `largeLink`

        Expands to a navigation link pointing to the large version of an image when
        using large-image support

    - `largeHrefStart`

        Expands to an &lt;a href> surrounding a medium size image when using
        large-image support

    - `largeHrefEnd`

        Expands to &lt;/a> when using large-image support

    - `largeLinkBorder`

        Used to turn borders on or off in accordance with `$largeLinkUseBorders`

## Custom Per-folder Templates

If desired, one can define a per-folder template file.  This can be done
with the `FolderTemplate` entity, as discussed in the "mig\_cf"
document.

## Managing Colors

Mig uses a Cascading Style Sheet (CSS) file to manage all of its element
colorization.  Things like the page background, background colors for table
cells, all are managed by the CSS file `templates/style.css`.

I don't have the inclination or time to write a tutorial on how CSS works,
so please see [http://www.htmlhelp.com/reference/css/](http://www.htmlhelp.com/reference/css/) or whatever else you
come across to figure out how CSS works.  There are some books on this
topic available as well (try searching for "CSS" at Amazon.com).
A basic example follows, though.

To change background color of description tables from `#f0f0f0` (grey)
to `#FF0000` (red):

    Before:
        TD.desc {
            color: #333333;          padding-top: 4px;
            background: #f0f0f0;     padding-bottom: 3px;
            font-size: .9em;         padding-left: 4px;
            text-align: center;      padding-right: 6px;
        }

    After:
        TD.desc {
            color: #333333;          padding-top: 4px;
            background: #ff0000;     padding-bottom: 3px;
            font-size: .9em;         padding-left: 4px;
            text-align: center;      padding-right: 6px;
        }

In this example, only the third line (background) was changed.
