<?php

require_once 'migHtmlSpecialChars.php';

final class PrintTemplateTest extends AbstractFileBasedTest
{
    private $SMALL_PNG_IMAGE = 'iVBORw0KGgoAAAANSUhEUgAAAAIAAAABCAAAAADRSSBWAAAACXBIWXMAAA5NAAAOnAHe9pxXAAAAC0lEQVQIHWP4/x8AAwAB/wvA7J0AAAAASUVORK5CYII=';
    private $LARGE_PNG_IMAGE = 'iVBORw0KGgoAAAANSUhEUgAAAAQAAAACCAAAAABawyK/AAAACXBIWXMAAA5NAAAOnAHe9pxXAAAADklEQVQI12P4DwQMIAIAJ+IH+WGw+XcAAAAASUVORK5CYII=';

    protected function setupMigFake()
    {
        include_once 'ConvertIncludePath.class.php';
        include_once 'printTemplate.php';
        global $mig_config, $REQUEST_URI, $_SERVER, $HTTP_SERVER_VARS;
        $HTTP_SERVER_VARS = array();
        $REQUEST_URI = 'https://example/uri?foo=bar';
        $mig_config = array();
        $mig_config['basedir'] = $this->mig_dir;
        $mig_config['templatedir'] = $this->mig_dir;
        $mig_config['hidden'] = array();
        $mig_config['pagetitle'] = 'pagetitle';
        $mig_config['charset'] = 'UTF-8';
        $mig_config['httpContentType'] = 'text/html';
        $mig_config['usethumbfile'] = array();
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['largesubdir'] = 'large';
        $mig_config['albumurlroot'] = '/albums';
        $mig_config['albumdir'] = $mig_config['basedir'] . '/albums';
        $mig_config['baseurl'] = 'https://example.com/baseurl';
        $mig_config['image_extensions'] = array('jpg', 'jpeg', 'png');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
        $mig_config['nothumb_icon'] = 'nothumb_icon.png';
        $mig_config['movie_icon'] = 'movie_icon.png';
        $mig_config['music_icon'] = 'audio_icon.png';
        $this->set_mig_config_image(NULL);
        $mig_config['pagetype'] = NULL;
        $mig_config['version'] = '1.0.0';
        $mig_config['distURL'] = 'distURL';
        $mig_config['maxFolderColumns'] = 1;
        $mig_config['maxThumbColumns'] = 1;
        $mig_config['maintAddr'] = 1;
    }

    public function test()
    {
        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "
%%pageTitle%%
%%charset%%
%%httpContentType%%
%%youAreHere%%
%%folderList%%
%%imageList%%
%%description%%
%%prevLink%%
%%nextLink%%
%%backLink%%
%%distURL%%
%%version%%
%%baseURL%%
%%maintAddr%%
%%currDir%%
%%newCurrDir%%
%%image%%
%%albumURLroot%%
%%albumURLroot%%
%%currPos%%
%%encodedImageURL%%
%%imageSize%%
%%newLang%%
%%largeLink%%
%%largeHrefStart%%
%%largeHrefEnd%%
%%largeLinkBorder%%
");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
pagetitle
UTF-8
text/html
youAreHere
folder list
image list
description
prevLink
nextLink
back link
distURL
1.0.0
https://example.com/baseurl
test@example.com
.
newCurrDir

/albums
/albums
currPos


https://example/uri?foo=bar&mig_dl
largeLink
largeHrefStart
largeHrefEnd
largeLinkBorder
", $output);
    }

    public function testImage()
    {
        global $mig_config;
        $this->set_mig_config_image('test.png');
        $mig_config['pagetype'] = 'image';

        $this->touchWithContent($this->album_dir . '/test.png', base64_decode($this->SMALL_PNG_IMAGE));

        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "
%%pageTitle%%
%%charset%%
%%httpContentType%%
%%youAreHere%%
%%folderList%%
%%imageList%%
%%description%%
%%prevLink%%
%%nextLink%%
%%backLink%%
%%distURL%%
%%version%%
%%baseURL%%
%%maintAddr%%
%%currDir%%
%%newCurrDir%%
%%image%%
%%albumURLroot%%
%%albumURLroot%%
%%currPos%%
%%encodedImageURL%%
%%imageSize%%
%%newLang%%
%%largeLink%%
%%largeHrefStart%%
%%largeHrefEnd%%
%%largeLinkBorder%%
");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
pagetitle
UTF-8
text/html
youAreHere
folder list
image list
description
prevLink
nextLink
back link
distURL
1.0.0
https://example.com/baseurl
test@example.com
.
newCurrDir
test.png
/albums
/albums
currPos
test.png
width=\"2\" height=\"1\"
https://example/uri?foo=bar&mig_dl
largeLink
largeHrefStart
largeHrefEnd
largeLinkBorder
", $output);
    }

    public function testImageLarge()
    {
        global $mig_config;
        $this->set_mig_config_image('test.png');
        $mig_config['pagetype'] = 'large';

        $this->mkdir($this->album_dir . '/large');
        $this->touchWithContent($this->album_dir . '/test.png', base64_decode($this->SMALL_PNG_IMAGE));
        $this->touchWithContent($this->album_dir . '/large/test.png', base64_decode($this->LARGE_PNG_IMAGE));

        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "
%%currDir%%
%%newCurrDir%%
%%image%%
%%albumURLroot%%
%%albumURLroot%%
%%currPos%%
%%encodedImageURL%%
%%imageSize%%
%%newLang%%
%%largeLink%%
%%largeHrefStart%%
%%largeHrefEnd%%
%%largeLinkBorder%%
");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
.
newCurrDir
test.png
/albums
/albums
currPos
test.png
width=\"4\" height=\"2\"
https://example/uri?foo=bar&mig_dl
largeLink
largeHrefStart
largeHrefEnd
largeLinkBorder
", $output);
    }

    public function testVideo()
    {
        global $mig_config;
        $this->set_mig_config_image('test.mp4');
        $mig_config['pagetype'] = 'image';

        touch($this->album_dir . '/test.mp4');

        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "
%%currDir%%
%%newCurrDir%%
%%image%%
%%albumURLroot%%
%%albumURLroot%%
%%encodedImageURL%%
%%imageSize%%
%%largeLink%%
%%largeHrefStart%%
%%largeHrefEnd%%
%%largeLinkBorder%%
");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
.
images
test.mp4
.
.
movie_icon.png

largeLink
<a href=\"/albums/images/test.mp4\">
</a><br />test.mp4
largeLinkBorder
", $output);
    }

    public function testAudio()
    {
        global $mig_config;
        $this->set_mig_config_image('test.mp3');
        $this->set_mig_config_image('test.mp3');
        $mig_config['pagetype'] = 'image';

        touch($this->album_dir . '/test.mp3');

        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "
%%currDir%%
%%newCurrDir%%
%%image%%
%%albumURLroot%%
%%albumURLroot%%
%%encodedImageURL%%
%%imageSize%%
%%largeLink%%
%%largeHrefStart%%
%%largeHrefEnd%%
%%largeLinkBorder%%
");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
.
images
test.mp3
.
.
audio_icon.png

largeLink
<a href=\"/albums/images/test.mp3\">
</a><br />test.mp3
largeLinkBorder
", $output);
    }

    public function testInclude()
    {
        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "foo\n#include \"include.inc\";\nbar\n");
        $this->touchWithContent($this->mig_dir . '/include.inc', "included content\n");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("foo\nincluded content\nbar\n", $output);
    }

    public function testIncludeInvalid()
    {
        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "\n#include \"/path.inc\";\n");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("\n<!-- ERROR: #include directive failed. Path included a \"/\" character, indicating an absolute or relative path.  All included files must be located in the templates/ subdirectory. Directive was:
     #include \"/path.inc\";

-->\n", $output);
    }

    public function testIncludeNotExisting()
    {
        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "\n#include \"path.inc\";\n");
        ob_start();
        printTemplate($template, 'test@example.com', 'folder list', 'image list',
            'back link', '.', 'newCurrDir', 'prevLink', 'nextLink',
            'currPos', 'description', 'youAreHere',
            $this->NO_PATH_CONVERT, 'largeLink',
            'largeHrefStart', 'largeHrefEnd', 'largeLinkBorder');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("\n<!-- ERROR: #include directive failed. Named file path.inc does not exist.  Directive was:
    #include \"path.inc\";

-->\n", $output);
    }

    public function testNewLanguage()
    {
        global $REQUEST_URI;
        $REQUEST_URI = 'https://example/uri?foo=bar&mig_dl=de';
        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "%%newLang%%\n");
        ob_start();
        printTemplate($template, '', '', '', '', '.', '', '', '', '', '', '', new ConvertIncludePath(FALSE, '', ''), '', '', '', '');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("https://example/uri?foo=bar&mig_dl\n", $output);
    }

    public function testNewLanguageFromHTTP_SERVER_VARS()
    {
        global $HTTP_SERVER_VARS;
        $HTTP_SERVER_VARS['REQUEST_URI'] = 'https://example/uri2?foo=bar&mig_dl=de';
        $template = $this->mig_dir . '/template.html';
        $this->touchWithContent($template, "%%newLang%%\n");
        ob_start();
        printTemplate($template, '', '', '', '', '.', '', '', '', '', '', '', new ConvertIncludePath(FALSE, '', ''), '', '', '', '');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("https://example/uri2?foo=bar&mig_dl\n", $output);
    }
}
