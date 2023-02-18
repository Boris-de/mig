<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class BuildImageListTest extends AbstractFileBasedTest
{
    const SIMPLE_PNG_1X1_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==';
    const SIMPLE_PNG_1X2_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAQAAAAziH6sAAAACXBIWXMAAA5NAAAOnAHe9pxXAAAADElEQVQIHWMAgv//AQMDAf9tZCPeAAAAAElFTkSuQmCC';

    protected function setupMigFake()
    {
        global $mig_config;
        $mig_config = array();
        include_once 'buildImageList.php';
        include_once 'getFileExtension.php';
        include_once 'migURLencode.php';
        include_once 'getFileType.php';
        include_once 'buildImageURL.php';
        include_once 'getFileName.php';
        include_once 'getNewCurrDir.php';
        include_once 'getImageDescription.php';
        include_once 'replaceString.php';
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
        $mig_config['basedir'] = $this->mig_dir;
        $mig_config['charset'] = 'UTF-8';
        $mig_config['albumdir'] = $mig_config['basedir'] . '/albums';
        $mig_config['foldersorttype'] = 'default';
        $mig_config['hidden'] = array();
        $mig_config['ignoredotdirectories'] = TRUE;
        $mig_config['currDirNameRegexpr'] = '=^([^<>]|\.\.)*$=';
        $mig_config['viewfoldercount'] = FALSE;
        $mig_config['randomfolderthumbs'] = FALSE;
        $mig_config['baseurl'] = 'https://example.com/baseurl';
        $mig_config['mig_dl'] = '';
        $mig_config['usethumbfile'] = FALSE;
        $mig_config['imagedir'] = 'imagedir';
        $mig_config['folder_icon'] = 'folder.png';
        $mig_config['nothumbs'] = FALSE;
        $mig_config['nothumb_icon'] = 'nothumb_icon.png';
        $mig_config['movie_icon'] = 'movie_icon.png';
        $mig_config['music_icon'] = 'audio_icon.png';
        $mig_config['markerlabel'] = '';
        $mig_config['usethumbsubdir'] = TRUE;
        $mig_config['image_extensions'] = array('jpg', 'jpeg', 'png');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
        $mig_config['imageFilenameRegexpr'] = '=^[^<>/]*$=';
        $mig_config['sorttype'] = 'default';
        $mig_config['showTotalImagesString'] = TRUE;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['thumbext'] = '';
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
    }

    public function test()
    {
        $this->mkdir($this->album_dir.'/test1'); // directory -> not shown
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testPresorted()
    {
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->migAssertStringContainsString("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>",
            buildImageList('.', 2, 2, array('test3.jpg' => true), array(), array()));
    }

    public function testPaging()
    {
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->migAssertStringContainsString("
   <tr>
    <td colspan=\"2\" align=\"center\"><small>Showing&nbsp;images&nbsp;1-2&nbsp;of&nbsp;3&nbsp;total<br /><b>1</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">2</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">&raquo;</a></small></td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
   </tr>
   <tr>
    <td colspan=\"2\" align=\"center\"><small>Showing&nbsp;images&nbsp;1-2&nbsp;of&nbsp;3&nbsp;total<br /><b>1</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">2</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=1\">&raquo;</a></small></td>
   </tr>",
            buildImageList('.', 2, 1, array('test3.jpg' => true), array(), array()));

        // page 2
        $this->set_mig_config('startfrom', '1');
        $this->migAssertStringContainsString("
   <tr>
    <td colspan=\"1\" align=\"center\"><small>Showing&nbsp;images&nbsp;2-2&nbsp;of&nbsp;3&nbsp;total<br /><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=0\">&laquo;</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=0\">1</a>&nbsp;&nbsp;<b>2</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">3</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">&raquo;</a></small></td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg&amp;startFrom=1\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
   </tr>
   <tr>
    <td colspan=\"1\" align=\"center\"><small>Showing&nbsp;images&nbsp;2-2&nbsp;of&nbsp;3&nbsp;total<br /><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=0\">&laquo;</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=0\">1</a>&nbsp;&nbsp;<b>2</b>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">3</a>&nbsp;&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=.&amp;startFrom=2\">&raquo;</a></small></td>
   </tr>",
            buildImageList('.', 1, 1, array('test3.jpg' => true), array(), array()));
    }

    public function testSortByDateAscending()
    {
        $this->set_mig_config('sorttype', 'bydate-ascend');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->migAssertStringContainsString("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testSortByDateDescending()
    {
        $this->set_mig_config('sorttype', 'bydate-descend');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->migAssertStringContainsString("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testHiding()
    {
        $this->set_mig_config('hidden', array('test-hidden.jpg' => true, '/test-hidden-dir' => true));
        $this->mkdir($this->album_dir.'/test-hidden-dir');
        touch($this->album_dir.'/test-hidden.jpg');

        $this->migAssertStringNotContainsString('test-hidden', buildImageList('.', 4, 1, array(), array(), array()));
    }

    public function testNoImages()
    {
        $this->assertEquals('', buildImageList('.', 4, 1, array(), array(), array()));
    }

    public function testVideo()
    {
        touch($this->album_dir.'/test.mp4');

        $this->migAssertStringContainsString("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"/albums/./test.mp4\"><img src=\"https://example.com/images/movie_icon.png\" /></a><br />test.mp4</td>",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testAudio()
    {
        touch($this->album_dir.'/test.mp3');

        $this->migAssertStringContainsString("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"/albums/./test.mp3\"><img src=\"https://example.com/images/audio_icon.png\" /></a><br />test.mp3</td>",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testUnknownFile()
    {
        touch($this->album_dir.'/test.foo');

        $this->migAssertStringNotContainsString("test.foo",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testFileSize()
    {
        $this->set_mig_config('fileinfoformatstring', array('image' => '%s %n', 'audio' => '%s %n', 'video' => '%s %n'));
        $this->touchWithSize($this->album_dir.'/test1.jpg', 1024);
        $this->touchWithSize($this->album_dir.'/test2.jpg', 1025);
        $this->touchWithSize($this->album_dir.'/test3.jpg', 1048576);
        $this->touchWithSize($this->album_dir.'/test4.jpg', 1048577);

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />1024&nbsp;bytes test1.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />1.0KB test2.jpg</td>
   </tr>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />1024.0KB test3.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test4.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />1.0MB test4.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImageSize()
    {
        $this->mkdir($this->album_dir.'/thumbs');
        $this->set_mig_config('fileinfoformatstring', array('image' => '%i %n', 'audio' => '%n', 'video' => '%n'));
        $this->touchWithContent($this->album_dir.'/test1.png', base64_decode(self::SIMPLE_PNG_1X2_BASE64));
        $this->touchWithContent($this->album_dir.'/thumbs/test1.png', base64_decode(self::SIMPLE_PNG_1X1_BASE64));

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.png\"><img src=\"/albums/./thumbs/test1.png\" alt=\"\" class=\"imagethumb\" width=\"1\" height=\"1\" /></a><br />1x2 test1.png</td>
  </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testThumbDir()
    {
        $this->mkdir($this->album_dir.'/thumbs');
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/thumbs/test1.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"/albums/./thumbs/test1.jpg\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testThumbDirWithThumbExt()
    {
        $this->set_mig_config('thumbext', 'thumb.jpg');
        $this->mkdir($this->album_dir.'/thumbs');
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/thumbs/test1.thumb.jpg');
        touch($this->album_dir.'/thumbs/test2.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"/albums/./thumbs/test1.thumb.jpg\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testThumbPrefix()
    {
        $this->set_mig_config('usethumbsubdir', FALSE);
        $this->set_mig_config('markertype', 'prefix');
        $this->set_mig_config('markerlabel', 'thumb');
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/thumb_test1.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"/albums/./thumb_test1.jpg\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testThumbSuffix()
    {
        $this->set_mig_config('usethumbsubdir', FALSE);
        $this->set_mig_config('markertype', 'suffix');
        $this->set_mig_config('markerlabel', 'thumb');
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test1_thumb.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"/albums/./test1_thumb.jpg\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testJustThumbExtensionNotSupported()
    {
        $this->set_mig_config('usethumbsubdir', FALSE);
        $this->set_mig_config('thumbext', 'thumb.jpg');
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test1.thumb.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.thumb.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test1.thumb.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testNoThumbs()
    {
        $this->set_mig_config('nothumbs', TRUE);
        touch($this->album_dir.'/test1.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg\">test1.jpg</a><br /></td>
  </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testUnicodeImages()
    {
        touch($this->album_dir.'/麻婆豆腐.jpg');
        touch($this->album_dir.'/Łódź.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=%C5%81%C3%B3d%C5%BA.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />Łódź.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=%E9%BA%BB%E5%A9%86%E8%B1%86%E8%85%90.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />麻婆豆腐.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    /**
     * Does not work on windows because of the special chars in image name
     * @requires OSFAMILY Linux
     */
    public function testSpecialCharImages()
    {
        $this->set_mig_config('imageFilenameRegexpr', '=.*=');
        touch($this->album_dir.'/aaa bbb_ccc.jpg');
        touch($this->album_dir.'/test<>&.jpg');

        $this->assertEquals("
  <table summary=\"Image Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=aaa%20bbb_ccc.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />aaa bbb_ccc.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test%3C%3E%26.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test&lt;&gt;&amp;.jpg</td>
   </tr>
  </tbody></table>", buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImagePopup()
    {
        $this->setUpPopupTest();
        $this->migAssertStringContainsString("<a title=\"\" href=\"#\" onClick=\"window.open('https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg','mig_window_11190874','width=30,height=150,resizable=yes,scrollbars=1');return false;\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a>",
            buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImagePopupWithLargerSize()
    {
        $this->setUpPopupTest();
        $this->set_mig_config('imagepopmaxwidth', 10);
        $this->set_mig_config('imagepopmaxheight', 10);

        $this->migAssertStringContainsString("<a title=\"\" href=\"#\" onClick=\"window.open('https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg','mig_window_11190874','width=10,height=10,resizable=yes,scrollbars=1');return false;\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a>",
            buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImagePopupWithDisabledLocationBar()
    {
        $this->setUpPopupTest();
        $this->set_mig_config('imagepoplocationbar', TRUE);

        $this->migAssertStringContainsString("<a title=\"\" href=\"#\" onClick=\"window.open('https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg','mig_window_11190874','width=30,height=150,resizable=yes,scrollbars=1,location=1');return false;\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a>",
            buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImagePopupWithDisabledToolBar()
    {
        $this->setUpPopupTest();
        $this->set_mig_config('imagepoptoolbar', TRUE);

        $this->migAssertStringContainsString("<a title=\"\" href=\"#\" onClick=\"window.open('https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg','mig_window_11190874','width=30,height=150,resizable=yes,scrollbars=1,toolbar=1');return false;\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a>",
            buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImagePopupWithDisabledMenuBar()
    {
        $this->setUpPopupTest();
        $this->set_mig_config('imagepopmenubar', TRUE);

        $this->migAssertStringContainsString("<a title=\"\" href=\"#\" onClick=\"window.open('https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg','mig_window_11190874','width=30,height=150,resizable=yes,scrollbars=1,menubar=1');return false;\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a>",
            buildImageList('.', 2, 2, array(), array(), array()));
    }

    public function testImagePopupWithRandomWindow()
    {
        $this->setUpPopupTest();
        $this->set_mig_config('imagepoptype', NULL);

        $imageList = buildImageList('.', 2, 2, array(), array(), array());
        $this->migAssertStringContainsString("<a title=\"\" href=\"#\" onClick=\"window.open('https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test1.jpg','mig_window_", $imageList);
        $this->migAssertStringNotContainsString("mig_window_11190874", $imageList);
    }

    private function setUpPopupTest() {
        $this->set_mig_config('imagepopup', TRUE);
        $this->set_mig_config('imagepopmaxwidth', 640);
        $this->set_mig_config('imagepopmaxheight', 480);
        $this->set_mig_config('imagepoptype', 'reuse');
        $this->set_mig_config('imagepoplocationbar', FALSE);
        $this->set_mig_config('imagepoptoolbar', FALSE);
        $this->set_mig_config('imagepopmenubar', FALSE);
        touch($this->album_dir.'/test1.jpg');
    }
}
