<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class BuildDirListTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'buildDirList.php';
        include_once 'getFileExtension.php';
        include_once 'getNumberOfDirs.php';
        include_once 'getNumberOfImages.php';
        include_once 'parseMigCf.php';
        include_once 'getFileType.php';
        include_once 'migURLencode.php';
        global $mig_config;
        $mig_config = array();
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
        $mig_config['usethumbfile'] = array();
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['imagedir'] = 'imagedir';
        $mig_config['folder_icon'] = 'folder.png';
        $mig_config['markerlabel'] = NULL;
        $mig_config['markertype'] = NULL;
        $mig_config['image_extensions'] = array('jpg', 'jpeg');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
        $mig_config['maxFolderColumns'] = 2;
        $mig_config['maxThumbColumns'] = 2;
        $mig_config['maintAddr'] = 'default@example.com';
        $mig_config['templatedir'] = 'templates';
    }

    public function test()
    {
        $this->mkdir($this->album_dir.'/test1');
        $this->mkdir($this->album_dir.'/test2');
        $this->mkdir($this->album_dir.'/<xxx>'); // ignored because of currDirNameRegexpr
        $this->mkdir($this->album_dir.'/test-presorted'); // presorted -> should be first
        $this->mkdir($this->album_dir.'/.test-dot-directory'); // dot-directory -> has to be ignored with ignoredotdirectories
        touch($this->album_dir.'/test.jpg'); // file -> has to be ignored

        $this->assertEquals("
   <table summary=\"Folder Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test-presorted\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test-presorted\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test-presorted\">test-presorted</a></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a></td>
   </tr>
  </tbody></table>", buildDirList('.', 1, array('test-presorted' => TRUE), array()));
    }

    public function testColumns()
    {
        $this->mkdir($this->album_dir.'/test1');
        $this->mkdir($this->album_dir.'/test2');
        $this->mkdir($this->album_dir.'/test3');

        $this->assertEquals("
   <table summary=\"Folder Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a></td>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test3\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test3\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test3\">test3</a></td>
   </tr>
  </tbody></table>", buildDirList('.', 2, array(), array()));
    }

    public function testSortByDateAscending()
    {
        $this->set_mig_config('foldersorttype', 'bydate-ascend');
        $this->mkdir($this->album_dir.'/test1');
        $this->mkdir($this->album_dir.'/test2');

        $this->assertStringContainsString("
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a></td>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a></td>",
            buildDirList('.', 2, array(), array()));
    }

    public function testSortByDateDescending()
    {
        $this->set_mig_config('foldersorttype', 'bydate-descend');
        $this->mkdir($this->album_dir.'/test1');
        $this->mkdir($this->album_dir.'/test2');

        $this->assertStringContainsString("
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a></td>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a></td>",
            buildDirList('.', 2, array(), array()));
    }

    public function testHiding()
    {
        $this->set_mig_config('hidden', array('test-hidden' => true));
        $this->mkdir($this->album_dir.'/test1');
        $this->mkdir($this->album_dir.'/test-hidden');

        $this->assertStringNotContainsString('test-hidden', buildDirList('.', 2, array(), array()));
    }

    public function testMigDl()
    {
        $this->set_mig_config('mig_dl', 'nl');
        $this->mkdir($this->album_dir.'/test1');

        $this->assertStringContainsString("<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\">test1</a>",
            buildDirList('.', 2, array(), array()));
    }

    public function testFolderIcon()
    {
        $this->set_mig_config('mig_dl', 'nl');
        $this->mkdir($this->album_dir.'/test1');

        $this->assertStringContainsString("<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\"><img src=\"imagedir/special_icon.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\">test1</a>",
            buildDirList('.', 2, array(), array('test1' => 'special_icon.png')));
    }

    public function testNoSubDirs()
    {
        $this->assertEquals("", buildDirList('.', 1, array(), array()));
    }

    public function testViewFolderCount()
    {
        global $mig_config;
        $mig_config['viewfoldercount'] = TRUE;
        $this->mkdir($this->album_dir.'/test');
        $this->mkdir($this->album_dir.'/test1');
        $this->mkdir($this->album_dir.'/test1/test');
        touch($this->album_dir.'/test1/test.jpg');
        $this->mkdir($this->album_dir.'/test2');
        $this->mkdir($this->album_dir.'/test2/test1');
        $this->mkdir($this->album_dir.'/test2/test2');
        touch($this->album_dir.'/test2/test1.jpg');
        touch($this->album_dir.'/test2/test2.jpg');
        $this->assertEquals("
   <table summary=\"Folder Links\" border=\"0\" cellspacing=\"0\"><tbody>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test\">test</a></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a>&nbsp;<acronym title=\"(folders/files)\">(1/1)</acronym></td>
   </tr>
   <tr>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a>&nbsp;<acronym title=\"(folders/files)\">(2/2)</acronym></td>
   </tr>
  </tbody></table>", buildDirList('.', 1, array(), array()));
    }
}
