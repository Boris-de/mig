<?php /** @noinspection HtmlDeprecatedAttribute */

require_once 'AbstractFileBasedTestCase.class.php';

final class PrintPageTest extends AbstractFileBasedTest
{
    const SIMPLE_PNG_1X1_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==';

    protected function setupMigFake()
    {
        include_once 'printPage.php';
        include_once 'printTemplate.php';
        include_once 'buildNextPrevLinks.php';
        global $mig_config, $REQUEST_URI, $_SERVER, $HTTP_SERVER_VARS;
        $HTTP_SERVER_VARS = array();
        $REQUEST_URI = 'https://example/uri?foo=bar';
        $mig_config = array();
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
        $mig_config['basedir'] = $this->mig_dir;
        $mig_config['charset'] = 'UTF-8';
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
        $mig_config['maxThumbRows'] = 2;
        $mig_config['maintAddr'] = 1;

        $mig_config['startfrom'] = 0;
        $mig_config['sorttype'] = 'default';
        $mig_config['foldersorttype'] = 'default';
        $mig_config['ignoredotdirectories'] = TRUE;
        $mig_config['currDirNameRegexpr'] = '=^([^<>]|\.\.)*$=';
        $mig_config['viewfoldercount'] = FALSE;
        $mig_config['randomfolderthumbs'] = FALSE;
        $mig_config['mig_dl'] = '';
        $mig_config['imagedir'] = 'imagedir';
        $mig_config['folder_icon'] = 'folder.png';
        $mig_config['imageFilenameRegexpr'] = '=^[^<>/]*$=';
        $mig_config['commentfileperimage'] = FALSE;
        $mig_config['suppressalttags'] = FALSE;
        $mig_config['imagepopup'] = FALSE;
        $mig_config['showshortonthumbpage'] = FALSE;
        $mig_config['fileinfoformatstring'] = array('image' => '%n', 'audio' => '%n', 'video' => '%n');
        $mig_config['albumurlroot'] = '/albums';
        $mig_config['markertype'] = '';
        $mig_config['markerlabel'] = '';
        $mig_config['imagepopmaxwidth'] = NULL;
        $mig_config['imagepopmaxheight'] = NULL;
        $mig_config['imagepoptype'] = NULL;
        $mig_config['imagepoplocationbar'] = NULL;
        $mig_config['imagepoptoolbar'] = NULL;
        $mig_config['imagepopmenubar'] = NULL;
        $mig_config['startfrom'] = 0;
        $mig_config['nothumbs'] = FALSE;
        $mig_config['nothumb_icon'] = 'nothumb_icon.png';
        $mig_config['showTotalImagesString'] = TRUE;
        $mig_config['prevformatstring'] = '%l';
        $mig_config['nextformatstring'] = '%l';
        $mig_config['exifFormatString'] = '|%c|';
        $mig_config['omitimagename'] = FALSE;
        $mig_config['commentfileshortcomments'] = FALSE;
    }

    public function testFolderWithSubFoldersAndImages()
    {
        $this->set_mig_config('pagetype', 'folder');
        $template = $this->mig_dir . '/folder.html';
        $this->mkdir($this->album_dir . '/folder1');
        $this->mkdir($this->album_dir . '/folder2');
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');
        touch($this->album_dir.'/test4.jpg');
        touch($this->album_dir.'/test5.jpg');
        $this->touchWithContent($template, "
<html lang=\"en\">
<head><title>%%pageTitle%%</title></head>
<body>
<strong>%%youAreHere%%</strong>
<small>%%backLink%%</small>
<!-- folder list -->
%%folderList%%
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  %%imageList%%
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->
%%description%%
<!-- navigation -->
<small>%%prevLink%% %%nextLink%% %%backLink%%<br /></small>
</body>
</html>
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, '');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<html lang=\"en\">
<head><title>pagetitle</title></head>
<body>
<strong>Main</strong>
<small><!-- no backLink in root tree --></small>
<!-- folder list -->
<table summary=\"Folders Frame\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody>
 <tr><td class=\"foldertext\">
   <table summary=\"Folder Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"folder1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder1\">folder1</a></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"folder2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder2\">folder2</a></td>
   </tr>
  </tbody></table>
 </td></tr>
</tbody></table>
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td colspan=\"1\" align=\"center\"><small>Showing&nbsp;images&nbsp;1-2&nbsp;of&nbsp;5&nbsp;total<br /><b>1</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">2</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">3</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">&raquo;</a></small></td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
   </tr>
   <tr>
    <td colspan=\"1\" align=\"center\"><small>Showing&nbsp;images&nbsp;1-2&nbsp;of&nbsp;5&nbsp;total<br /><b>1</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">2</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">3</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">&raquo;</a></small></td>
   </tr>
  </tbody></table>
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->

<!-- navigation -->
<small>  <!-- no backLink in root tree --><br /></small>
</body>
</html>
", $output);
    }

    public function testFolderWithOnlyImages()
    {
        $this->set_mig_config('pagetype', 'folder');
        $template = $this->mig_dir . '/folder.html';
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');
        touch($this->album_dir.'/test4.jpg');
        touch($this->album_dir.'/test5.jpg');
        $this->touchWithContent($template, "
<html lang=\"en\">
<head><title>%%pageTitle%%</title></head>
<body>
<strong>%%youAreHere%%</strong>
<small>%%backLink%%</small>
<!-- folder list -->
%%folderList%%
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  %%imageList%%
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->
%%description%%
<!-- navigation -->
<small>%%prevLink%% %%nextLink%% %%backLink%%<br /></small>
</body>
</html>
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, '');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<html lang=\"en\">
<head><title>pagetitle</title></head>
<body>
<strong>Main</strong>
<small><!-- no backLink in root tree --></small>
<!-- folder list -->

<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td colspan=\"1\" align=\"center\"><small>Showing&nbsp;images&nbsp;1-2&nbsp;of&nbsp;5&nbsp;total<br /><b>1</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">2</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">3</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">&raquo;</a></small></td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
   </tr>
   <tr>
    <td colspan=\"1\" align=\"center\"><small>Showing&nbsp;images&nbsp;1-2&nbsp;of&nbsp;5&nbsp;total<br /><b>1</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">2</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">3</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">&raquo;</a></small></td>
   </tr>
  </tbody></table>
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->

<!-- navigation -->
<small>  <!-- no backLink in root tree --><br /></small>
</body>
</html>
", $output);
    }

    public function testFolderWithOnlySubFolders()
    {
        $this->set_mig_config('pagetype', 'folder');
        $template = $this->mig_dir . '/folder.html';
        $this->mkdir($this->album_dir . '/folder1');
        $this->mkdir($this->album_dir . '/folder2');
        $this->touchWithContent($template, "
<html lang=\"en\">
<head><title>%%pageTitle%%</title></head>
<body>
<strong>%%youAreHere%%</strong>
<small>%%backLink%%</small>
<!-- folder list -->
%%folderList%%
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  %%imageList%%
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->
%%description%%
<!-- navigation -->
<small>%%prevLink%% %%nextLink%% %%backLink%%<br /></small>
</body>
</html>
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, '');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<html lang=\"en\">
<head><title>pagetitle</title></head>
<body>
<strong>Main</strong>
<small><!-- no backLink in root tree --></small>
<!-- folder list -->
<table summary=\"Folders Frame\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody>
 <tr><td class=\"foldertext\">
   <table summary=\"Folder Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"folder1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder1\">folder1</a></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"folder2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./folder2\">folder2</a></td>
   </tr>
  </tbody></table>
 </td></tr>
</tbody></table>
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->

<!-- navigation -->
<small>  <!-- no backLink in root tree --><br /></small>
</body>
</html>
", $output);
    }

    public function testImage()
    {
        $this->set_mig_config('pagetype', 'image');
        $this->set_mig_config_image('test1.png');
        $template = $this->mig_dir . '/image.html';
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($template, "
<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html lang=\"en\">
<head><title>%%pageTitle%% (%%image%%)</title></head>
<strong>%%youAreHere%%&nbsp;&nbsp;(%%currPos%%)<br /></strong>
<!-- navigation -->
<small>[&nbsp;%%prevLink%%&nbsp;]&nbsp;&nbsp;[&nbsp;%%nextLink%%&nbsp;]&nbsp;&nbsp;[&nbsp;%%backLink%%&nbsp;]&nbsp;&nbsp;[&nbsp;%%largeLink%%&nbsp;]<br /></small>
<!-- image display -->
%%largeHrefStart%%<img src=\"%%albumURLroot%%/%%newCurrDir%%/%%encodedImageURL%%\" alt=\"%%image%%\" %%imageSize%%%%largeLinkBorder%% />%%largeHrefEnd%%<br/>
<!-- image description -->
%%description%%
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, 'test1.png');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html lang=\"en\">
<head><title>pagetitle (test1.png)</title></head>
<strong>Main&nbsp;&gt;&nbsp;test1.png&nbsp;&nbsp;(#1&nbsp;of&nbsp;1)<br /></strong>
<!-- navigation -->
<small>[&nbsp;<span class=\"inactivelink\">previous&nbsp;image</span>&nbsp;]&nbsp;&nbsp;[&nbsp;<span class=\"inactivelink\">next&nbsp;image</span>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href=\"https://example.com/baseurl?currDir=.\">back&nbsp;to&nbsp;thumbnail&nbsp;view</a>&nbsp;]&nbsp;&nbsp;[&nbsp;&nbsp;]<br /></small>
<!-- image display -->
<img src=\"/albums/./test1.png\" alt=\"test1.png\" width=\"1\" height=\"1\" /><br/>
<!-- image description -->

", $output);
    }

    public function testLarge()
    {
        $this->set_mig_config('pagetype', 'large');
        $this->set_mig_config_image('test1.png');
        $template = $this->mig_dir . '/large.html';
        $this->mkdir($this->album_dir.'/large');
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($this->album_dir.'/large/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($template, "
<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html lang=\"en\">
<head><title>%%pageTitle%% (%%image%%)</title></head>
<strong>%%youAreHere%%&nbsp;&nbsp;(%%currPos%%)<br /></strong>
<!-- navigation -->
<small>[&nbsp;%%prevLink%%&nbsp;]&nbsp;&nbsp;[&nbsp;%%nextLink%%&nbsp;]&nbsp;&nbsp;[&nbsp;%%backLink%%&nbsp;]&nbsp;&nbsp;[&nbsp;%%largeLink%%&nbsp;]<br /></small>
<!-- image display -->
%%largeHrefStart%%<img src=\"%%albumURLroot%%/%%newCurrDir%%/%%encodedImageURL%%\" alt=\"%%image%%\" %%imageSize%%%%largeLinkBorder%% />%%largeHrefEnd%%<br/>
<!-- image description -->
%%description%%
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, 'test1.png');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html lang=\"en\">
<head><title>pagetitle (test1.png)</title></head>
<strong>Main&nbsp;&gt;&nbsp;test1.png&nbsp;&nbsp;(#1&nbsp;of&nbsp;1)<br /></strong>
<!-- navigation -->
<small>[&nbsp;<span class=\"inactivelink\">previous&nbsp;image</span>&nbsp;]&nbsp;&nbsp;[&nbsp;<span class=\"inactivelink\">next&nbsp;image</span>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.png\">back&nbsp;to&nbsp;web-sized&nbsp;view</a>&nbsp;]&nbsp;&nbsp;[&nbsp;&nbsp;]<br /></small>
<!-- image display -->
<img src=\"/albums/./test1.png\" alt=\"test1.png\" width=\"1\" height=\"1\" /><br/>
<!-- image description -->

", $output);
    }

    public function testImageCommentPerImage()
    {
        $this->set_mig_config('pagetype', 'image');
        $this->set_mig_config('commentfileperimage', TRUE);
        $this->set_mig_config_image('test1.png');
        $template = $this->mig_dir . '/image.html';
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($this->album_dir.'/test1.txt', "comment-file-content");
        $this->touchWithContent($template, "
%%largeHrefStart%%<img src=\"%%albumURLroot%%/%%newCurrDir%%/%%encodedImageURL%%\" alt=\"%%image%%\" %%imageSize%%%%largeLinkBorder%% />%%largeHrefEnd%%<br/>
%%description%%
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, 'test1.png');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<img src=\"/albums/./test1.png\" alt=\"test1.png\" width=\"1\" height=\"1\" /><br/>
comment-file-content
", $output);
    }

    public function testImageWithExif()
    {
        $this->set_mig_config('pagetype', 'image');
        $this->set_mig_config('commentfileperimage', TRUE);
        $this->set_mig_config_image('test1.png');
        $template = $this->mig_dir . '/image.html';
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($this->album_dir . '/exif.inf', "File name    : test1.png\nComment      : exif-comment\n
");
        $this->touchWithContent($template, "
%%largeHrefStart%%<img src=\"%%albumURLroot%%/%%newCurrDir%%/%%encodedImageURL%%\" alt=\"%%image%%\" %%imageSize%%%%largeLinkBorder%% />%%largeHrefEnd%%<br/>
%%description%%
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, 'test1.png');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<img src=\"/albums/./test1.png\" alt=\"test1.png\" width=\"1\" height=\"1\" /><br/>
exif-comment
", $output);
    }

    public function testImageWithDescriptionAndExif()
    {
        $this->set_mig_config('pagetype', 'image');
        $this->set_mig_config('commentfileperimage', TRUE);
        $this->set_mig_config_image('test1.png');
        $template = $this->mig_dir . '/image.html';
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($this->album_dir.'/test1.txt', "comment-file-content");
        $this->touchWithContent($this->album_dir . '/exif.inf', "File name    : test1.png\nComment      : exif-comment\n
");
        $this->touchWithContent($template, "
%%largeHrefStart%%<img src=\"%%albumURLroot%%/%%newCurrDir%%/%%encodedImageURL%%\" alt=\"%%image%%\" %%imageSize%%%%largeLinkBorder%% />%%largeHrefEnd%%<br/>
%%description%%
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, 'test1.png');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<img src=\"/albums/./test1.png\" alt=\"test1.png\" width=\"1\" height=\"1\" /><br/>
comment-file-content<hr />exif-comment
", $output);
    }

    public function testImageWithLargeLinks()
    {
        $this->set_mig_config('pagetype', 'image');
        $this->set_mig_config('uselargeimages', TRUE);
        $this->set_mig_config('largeLinkFromMedium', TRUE);
        $this->set_mig_config('largeLinkUseBorders', FALSE);
        $this->set_mig_config_image('test1.png');
        $template = $this->mig_dir . '/image.html';
        $this->mkdir($this->album_dir.'/large');
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($this->album_dir.'/large/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));
        $this->touchWithContent($template, "
%%largeHrefStart%%<img src=\"%%albumURLroot%%/%%newCurrDir%%/%%encodedImageURL%%\" alt=\"%%image%%\" %%imageSize%%%%largeLinkBorder%% />%%largeHrefEnd%%<br/>
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, 'test1.png');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<a href=\"https://example.com/baseurl?currDir=.&amp;pageType=large&amp;image=test1.png\"><img src=\"/albums/./test1.png\" alt=\"test1.png\" width=\"1\" height=\"1\" border=\"0\" /></a><br/>
", $output);
    }

    public function testNoContent()
    {
        $this->set_mig_config('pagetype', 'folder');
        $template = $this->mig_dir . '/folder.html';
        $this->touchWithContent($template, "
<html lang=\"en\">
<head><title>%%pageTitle%%</title></head>
<body>
<strong>%%youAreHere%%</strong>
<small>%%backLink%%</small>
<!-- folder list -->
%%folderList%%
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  %%imageList%%
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->
%%description%%
<!-- navigation -->
<small>%%prevLink%% %%nextLink%% %%backLink%%<br /></small>
</body>
</html>
");
        ob_start();
        printPage('.', $this->NO_PATH_CONVERT, '');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("
<html lang=\"en\">
<head><title>pagetitle</title></head>
<body>
<strong>Main</strong>
<small><!-- no backLink in root tree --></small>
<!-- folder list -->
<table summary=\"Folders Frame\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody>
 <tr><td class=\"foldertext\">No&nbsp;contents.
 </td></tr>
</tbody></table>
<!-- image list -->
<table><tbody>
 <tr><td class=\"image\">
  
 </td></tr>
</tbody></table>
<br />
<!-- bulletin -->

<!-- navigation -->
<small>  <!-- no backLink in root tree --><br /></small>
</body>
</html>
", $output);
    }
}
