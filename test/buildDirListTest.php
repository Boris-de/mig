<?php

use PHPUnit\Framework\TestCase;

final class BuildDirListTest extends TestCase
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
        include_once 'buildDirList.php';
        include_once 'getFileExtension.php';
        include_once 'migURLencode.php';
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
    }

    public function test()
    {
        mkdir($this->album_dir.'/test1');
        mkdir($this->album_dir.'/test2');
        mkdir($this->album_dir.'/<xxx>'); // ignored because of currDirNameRegexpr
        mkdir($this->album_dir.'/test-presorted'); // presorted -> should be first
        mkdir($this->album_dir.'/.test-dot-directory'); // dot-directory -> has to be ignored with ignoredotdirectories
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
        mkdir($this->album_dir.'/test1');
        mkdir($this->album_dir.'/test2');
        mkdir($this->album_dir.'/test3');

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
        mkdir($this->album_dir.'/test1');
        mkdir($this->album_dir.'/test2');

        $this->assertContains("
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a></td>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a></td>",
            buildDirList('.', 2, array(), array()));
    }

    public function testSortByDateDescending()
    {
        $this->set_mig_config('foldersorttype', 'bydate-descend');
        mkdir($this->album_dir.'/test1');
        mkdir($this->album_dir.'/test2');

        $this->assertContains("
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test2\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test2\">test2</a></td>
     <td valign=\"middle\" class=\"foldertext\" align=\"left\"><a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1\">test1</a></td>",
            buildDirList('.', 2, array(), array()));
    }

    public function testHiding()
    {
        $this->set_mig_config('hidden', array('test-hidden' => true));
        mkdir($this->album_dir.'/test1');
        mkdir($this->album_dir.'/test-hidden');

        $this->assertNotContains('test-hidden', buildDirList('.', 2, array(), array()));
    }

    public function testMigDl()
    {
        $this->set_mig_config('mig_dl', 'nl');
        mkdir($this->album_dir.'/test1');

        $this->assertContains("<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\"><img src=\"imagedir/folder.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\">test1</a>",
            buildDirList('.', 2, array(), array()));
    }

    public function testFolderIcon()
    {
        $this->set_mig_config('mig_dl', 'nl');
        mkdir($this->album_dir.'/test1');

        $this->assertContains("<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\"><img src=\"imagedir/special_icon.png\" border=\"0\" alt=\"test1\"/></a>&nbsp;<a href=\"https://example.com/baseurl?pageType=folder&amp;currDir=./test1&amp;mig_dl=nl\">test1</a>",
            buildDirList('.', 2, array(), array('test1' => 'special_icon.png')));
    }

    public function testNoSubDirs()
    {
        $this->assertContains("NULL", buildDirList('.', 1, array(), array()));
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
