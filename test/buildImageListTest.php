<?php

use PHPUnit\Framework\TestCase;

final class BuildImageListTest extends TestCase
{
    private $mig_dir;
    private $album_dir;

    public function setUp()
    {
        $tempfile = tempnam(sys_get_temp_dir(), 'mig_phpunit_');
        $this->assertTrue($tempfile !== FALSE);
        unlink($tempfile);
        $mig_dir = $tempfile . '.dir';
        $this->assertTrue(mkdir($mig_dir, 0700) !== FALSE);
        $this->mig_dir = $mig_dir;
        $this->album_dir = $mig_dir . '/albums';
        mkdir($this->album_dir);

        $this->setupMigFake();
    }

    private function setupMigFake()
    {
        include_once 'buildImageList.php';
        include_once 'getFileExtension.php';
        include_once 'migURLencode.php';
        include_once 'getFileType.php';
        include_once 'buildImageURL.php';
        include_once 'getFileName.php';
        include_once 'getNewCurrDir.php';
        include_once 'getImageDescription.php';
        include_once 'replaceString.php';
        global $mig_config;
        $mig_config['basedir'] = $this->mig_dir;
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
        $mig_config['nothumb_icon'] = 'nothumb_icon.png';
        $mig_config['markerlabel'] = '';
        $mig_config['usethumbsubdir'] = TRUE;
        $mig_config['image_extensions'] = array('jpg', 'jpeg');
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
    }

    public function test()
    {
        mkdir($this->album_dir.'/test1'); // directory -> not shown
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

        $this->assertContains("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>",
            buildImageList('.', 2, 2, array('test3.jpg' => true), array(), array()));
    }

    public function testPaging()
    {
        touch($this->album_dir.'/test1.jpg');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->assertContains("
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
        $this->assertContains("
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

        $this->assertContains("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testSortByDateDescending()
    {
        $this->set_mig_config('sorttype', 'bydate-descend');
        touch($this->album_dir.'/test2.jpg');
        touch($this->album_dir.'/test3.jpg');

        $this->assertContains("
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test3.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test3.jpg</td>
    <td align=\"center\" class=\"image\"><a title=\"\" href=\"https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=test2.jpg\"><img src=\"https://example.com/images/nothumb_icon.png\" alt=\"\" class=\"imagethumb\"  /></a><br />test2.jpg</td>",
            buildImageList('.', 4, 1, array('test-presorted' => TRUE), array(), array()));
    }

    public function testHiding()
    {
        $this->set_mig_config('hidden', array('test-hidden.jpg' => true, '/test-hidden-dir' => true));
        mkdir($this->album_dir.'/test-hidden-dir');
        touch($this->album_dir.'/test-hidden.jpg');

        $this->assertNotContains('test-hidden', buildImageList('.', 4, 1, array(), array(), array()));
    }

    public function testNoImages()
    {
        $this->assertEquals('NULL', buildImageList('.', 4, 1, array(), array(), array()));
    }

    private function set_mig_config($key, $value) {
        global $mig_config;
        $mig_config[$key] = $value;
    }

    public function tearDown()
    {
        if ($this->mig_dir != '' && is_dir($this->mig_dir . '/albums')) {
            $this->remove_recursive($this->mig_dir);
        }
    }

    public function remove_recursive($dir)
    {
        $fs_nodes = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($fs_nodes as $node) {
            $todo = $node->isDir() ? 'rmdir' : 'unlink';
            $todo($node->getRealPath());
        }

        rmdir($dir);
    }
}
