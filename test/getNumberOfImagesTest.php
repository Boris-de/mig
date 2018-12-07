<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class GetNumberOfImagesTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'parseMigCf.php';
        include_once 'getNumberOfImages.php';
        global $mig_config;
        $mig_config['hidden'] = array();
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['thumbsubdir'] = NULL;
        $mig_config['largesubdir'] = NULL;
        $mig_config['markertype'] = NULL;
        $mig_config['markerlabel'] = NULL;
    }

    public function test()
    {
        $dir = $this->album_dir . '/test';

        $this->assertEquals(0, getNumberOfImages("$dir/non-existing-folder"));

        $this->mkdir($dir);
        $this->assertEquals(0, getNumberOfImages($dir));

        touch("$dir/test.jpg");
        $this->assertEquals(1, getNumberOfImages($dir));

        $this->mkdir("$dir/dir1");
        $this->assertEquals(1, getNumberOfImages($dir));
    }

    public function testHidden()
    {
        $dir = $this->album_dir . '/test';

        $this->mkdir($dir);
        touch("$dir/test1.jpg");
        touch("$dir/test2.jpg");
        $this->touchWithContent("$dir/mig.cf", "<hidden>\ntest1.jpg\n</hidden>");
        $this->assertEquals(1, getNumberOfImages($dir));
    }

    public function testHiddenThumbWithSuffix()
    {
        $this->set_mig_config('usethumbsubdir', FALSE);
        $this->set_mig_config('markertype', 'suffix');
        $this->set_mig_config('markerlabel', 'thumb');
        $dir = $this->album_dir . '/test';

        $this->mkdir($dir);
        touch("$dir/test1.jpg");
        touch("$dir/test2.jpg");
        touch("$dir/test1_thumb.jpg");
        $this->assertEquals(2, getNumberOfImages($dir));
    }

    public function testHiddenThumbWithPrefix()
    {
        $this->set_mig_config('usethumbsubdir', FALSE);
        $this->set_mig_config('markertype', 'prefix');
        $this->set_mig_config('markerlabel', 'thumb');
        $dir = $this->album_dir . '/test';

        $this->mkdir($dir);
        touch("$dir/test1.jpg");
        touch("$dir/test2.jpg");
        touch("$dir/thumb_test1.jpg");
        $this->assertEquals(2, getNumberOfImages($dir));
    }
}
