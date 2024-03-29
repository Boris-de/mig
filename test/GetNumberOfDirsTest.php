<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class GetNumberOfDirsTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'parseMigCf.php';
        include_once 'getNumberOfDirs.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['hidden'] = array();
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['thumbsubdir'] = NULL;
        $mig_config['largesubdir'] = NULL;
        $mig_config['maxFolderColumns'] = 2;
        $mig_config['maxThumbColumns'] = 2;
        $mig_config['maintAddr'] = 'default@example.com';
        $mig_config['templatedir'] = 'templates';
    }

    public function test()
    {
        $dir = $this->album_dir . '/test';

        $this->assertEquals(0, getNumberOfDirs("$dir/non-existing-folder"));

        $this->mkdir($dir);
        $this->assertEquals(0, getNumberOfDirs($dir));

        touch("$dir/test.jpg");
        $this->assertEquals(0, getNumberOfDirs($dir));

        $this->mkdir("$dir/dir1");
        $this->assertEquals(1, getNumberOfDirs($dir));

        $this->mkdir("$dir/dir2");
        $this->assertEquals(2, getNumberOfDirs($dir));
    }

    public function testHidden()
    {
        $dir = $this->album_dir . '/test';

        $this->mkdir($dir);
        $this->mkdir("$dir/dir1");
        $this->mkdir("$dir/dir2");
        $this->touchWithContent("$dir/mig.cf", "<hidden>\ndir1\n</hidden>");
        $this->assertEquals(1, getNumberOfDirs($dir));
    }

    public function testHiddenThumbDir()
    {
        $this->set_mig_config('usethumbsubdir', TRUE);
        $this->set_mig_config('thumbsubdir', 'thumbs');
        $dir = $this->album_dir . '/test';

        $this->mkdir($dir);
        $this->mkdir("$dir/dir1");
        $this->mkdir("$dir/dir2");
        $this->mkdir("$dir/thumbs");
        $this->assertEquals(2, getNumberOfDirs($dir));
    }

    public function testHiddenLargeDir()
    {
        $this->set_mig_config('uselargeimages', TRUE);
        $this->set_mig_config('largesubdir', 'large');
        $dir = $this->album_dir . '/test';

        $this->mkdir($dir);
        $this->mkdir("$dir/dir1");
        $this->mkdir("$dir/dir2");
        $this->mkdir("$dir/large");
        $this->assertEquals(2, getNumberOfDirs($dir));
    }
}
