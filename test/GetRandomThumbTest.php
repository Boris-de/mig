<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class GetRandomThumbTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'getRandomThumb.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['hidden'] = array();
        $mig_config['pagetitle'] = NULL;
        $mig_config['usethumbfile'] = array();
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['userealrandthumbs'] = FALSE;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['markerlabel'] = '';
        $mig_config['markertype'] = '';
        $mig_config['image_extensions'] = array('jpg', 'jpeg');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
        $mig_config['albumurlroot'] = '/albums';
    }

    public function testTwoFilesWithPrefixFakeRandom()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/thumb_test1.jpg');
        touch($this->album_dir . '/thumb_test2.jpg');
        // fake random uses the order in which the filesystem lists the files
        $fakeRandomFile = $this->getFirstFile($this->album_dir);
        $this->assertEquals("/albums/./file/$fakeRandomFile", getRandomThumb('file', $this->album_dir, '.'));
    }

    public function testSingleFileWithPrefix()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/thumb_test.jpg');
        $this->assertEquals('/albums/./file/thumb_test.jpg', getRandomThumb('file', $this->album_dir, '.'));
    }

    public function testTwoFilesWithSuffixFakeRandom()
    {
        global $mig_config;
        $mig_config['markertype'] = 'suffix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/test1_thumb.jpg');
        touch($this->album_dir . '/test2_thumb.jpg');
        // fake random uses the order in which the filesystem lists the files
        $fakeRandomFile = $this->getFirstFile($this->album_dir);
        $this->assertEquals("/albums/./file/$fakeRandomFile", getRandomThumb('file', $this->album_dir, '.'));
    }

    public function testSingleFileWithSuffix()
    {
        global $mig_config;
        $mig_config['markertype'] = 'suffix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/test_thumb.jpg');
        $this->assertEquals('/albums/./file/test_thumb.jpg', getRandomThumb('file', $this->album_dir, '.'));
    }

    public function testMissingFolder()
    {
        $this->assertEquals(FALSE, getRandomThumb('file', $this->album_dir . '/non-existing', '.'));
    }

    private function getFirstFile($dir)
    {
        $dir_fd = opendir($dir);
        $return = FALSE;
        while ($file = readdir($dir_fd)) {
            if ($file[0] !== '.') {
                $return = $file;
                break;
            }
        }
        closedir($dir_fd);
        return $return;
    }
}
