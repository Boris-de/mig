<?php

require_once 'migHtmlSpecialChars.php';

final class GetRandomThumbTest extends AbstractFileBasedTest
{
    private $counter = 0;

    protected function setupMigFake()
    {
        include_once 'getFileExtension.php';
        include_once 'getFileType.php';
        include_once 'getRandomThumb.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['hidden'] = array();
        $mig_config['pagetitle'] = NULL;
        $mig_config['usethumbfile'] = array();
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['userealrandthumbs'] = FALSE;
        $mig_config['ignoredotdirectories'] = TRUE;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['markerlabel'] = '';
        $mig_config['markertype'] = '';
        $mig_config['image_extensions'] = array('jpg', 'jpeg');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
        $mig_config['albumurlroot'] = '/albums';
        $this->counter = 0;
    }

    public function testRealRandomWithPrefix()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        $mig_config['userealrandthumbs'] = TRUE;
        $this->createFiles('/thumb_test', 10);
        $this->assertEquals("/albums/./file/thumb_test7.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/thumb_test7.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/thumb_test2.jpg", $this->getRandomThumb());
    }

    public function testRealRandomWithSuffix()
    {
        global $mig_config;
        $mig_config['markertype'] = 'suffix';
        $mig_config['markerlabel'] = 'thumb';
        $mig_config['userealrandthumbs'] = TRUE;
        $this->createFiles('/test', 10, $suffix="_thumb");
        $this->assertEquals("/albums/./file/test7_thumb.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/test7_thumb.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/test2_thumb.jpg", $this->getRandomThumb());
    }

    public function testRealRandom_DirectoryWithOnlySubdirectoriesAndNoImages()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        $mig_config['userealrandthumbs'] = TRUE;
        $this->mkdir($this->album_dir . '/dir1');
        $this->mkdir($this->album_dir . '/dir2');
        $this->createFiles('/dir1/thumb_test', 5);
        $this->createFiles('/dir2/thumb_test', 5);
        $this->assertEquals("/albums/./file/dir2/thumb_test3.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/dir1/thumb_test2.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/dir1/thumb_test1.jpg", $this->getRandomThumb());
    }

    public function testRealRandom_DirectoryWithOnlySubdirectoriesAndHiddenDirs()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        $mig_config['hidden'] = array('dir1' => TRUE);
        $mig_config['userealrandthumbs'] = TRUE;
        $this->mkdir($this->album_dir . '/.dotdir');
        $this->mkdir($this->album_dir . '/dir1');
        $this->mkdir($this->album_dir . '/dir2');
        $this->createFiles('/.dotdir/thumb_test', 5);
        $this->createFiles('/dir1/thumb_test', 5);
        $this->createFiles('/dir2/thumb_test', 5);
        $this->assertEquals("/albums/./file/dir2/thumb_test3.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/dir2/thumb_test2.jpg", $this->getRandomThumb());
        // repeat a couple of times to see that we really skip those
        for ($i = 0; $i < 20; ++$i) {
            $thumb = $this->getRandomThumb();
            $this->assertStringNotContainsString("dotdir", $thumb);
            $this->assertStringNotContainsString("dir1", $thumb);
        }
    }

    public function testRealRandom_DirectoryWithOnlySubdirectoriesIncludeDotDir()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        $mig_config['userealrandthumbs'] = TRUE;
        $mig_config['ignoredotdirectories'] = FALSE;
        $this->mkdir($this->album_dir . '/.dotdir');
        $this->mkdir($this->album_dir . '/dir1');
        $this->createFiles('/.dotdir/thumb_test', 5);
        $this->createFiles('/dir1/thumb_test', 1);
        $this->assertEquals("/albums/./file/dir1/thumb_test1.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/.dotdir/thumb_test2.jpg", $this->getRandomThumb());
    }

    public function testRealRandomMissingThumbs()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'label that is not used';
        $mig_config['userealrandthumbs'] = TRUE;
        $this->createFiles('/thumb_test', 5);
        $this->assertEquals(FALSE, $this->getRandomThumb());
    }

    public function testRealRandom_ThumbDir()
    {
        global $mig_config;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['userealrandthumbs'] = TRUE;
        $mig_config['usethumbsubdir'] = TRUE;
        $this->mkdir($this->album_dir . '/thumbs');
        $this->createFiles('/thumbs/test', 10);
        $this->assertEquals("/albums/./file/thumbs/test7.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/thumbs/test7.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/thumbs/test2.jpg", $this->getRandomThumb());
    }

    public function testRealRandom_MissingThumbDir()
    {
        global $mig_config;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['userealrandthumbs'] = TRUE;
        $mig_config['usethumbsubdir'] = TRUE;
        $this->assertEquals(NULL, $this->getRandomThumb());
    }

    public function testRealRandom_ThumbDir_DirectoryWithOnlySubdirectoriesAndHiddenDirs()
    {
        global $mig_config;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['userealrandthumbs'] = TRUE;
        $mig_config['usethumbsubdir'] = TRUE;
        $mig_config['hidden'] = array('dir1' => TRUE, 'test1.jpg' => TRUE);
        $this->mkdir($this->album_dir . '/.dotdir');
        $this->mkdir($this->album_dir . '/.dotdir/thumbs');
        $this->mkdir($this->album_dir . '/dir1');
        $this->mkdir($this->album_dir . '/dir1/thumbs');
        $this->mkdir($this->album_dir . '/dir2');
        $this->mkdir($this->album_dir . '/dir2/thumbs');
        $this->mkdir($this->album_dir . '/dir3');
        $this->mkdir($this->album_dir . '/dir3/thumbs');
        $this->createFiles('/.dotdir/thumbs/test', 5);
        $this->createFiles('/dir1/thumbs/test', 5);
        $this->createFiles('/dir2/thumbs/test', 5);
        $this->createFiles('/dir3/thumbs/test', 5);
        $this->assertEquals("/albums/./file/dir3/thumbs/test3.jpg", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/dir2/thumbs/test2.jpg", $this->getRandomThumb());
        // repeat a couple of times to see that we really skip those
        for ($i = 0; $i < 20; ++$i) {
            $thumb = $this->getRandomThumb();
            $this->assertStringNotContainsString("dotdir", $thumb);
            $this->assertStringNotContainsString("dir1", $thumb);
            $this->assertStringNotContainsString("test1.jpg", $thumb);
        }
    }

    public function testFakeRandom_ThumbDir()
    {
        global $mig_config;
        $mig_config['thumbsubdir'] = 'thumbs';
        $mig_config['userealrandthumbs'] = FALSE;
        $mig_config['usethumbsubdir'] = TRUE;
        $this->mkdir($this->album_dir . '/thumbs');
        $this->createFiles('/thumbs/test', 10);
        $fakeRandomFile = $this->getFirstFile($this->album_dir . '/thumbs');
        $this->assertEquals("/albums/./file/thumbs/$fakeRandomFile", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/thumbs/$fakeRandomFile", $this->getRandomThumb());
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
        $this->assertEquals("/albums/./file/$fakeRandomFile", $this->getRandomThumb());
    }

    public function testSingleFileWithPrefix()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/thumb_test.jpg');
        $this->assertEquals('/albums/./file/thumb_test.jpg', $this->getRandomThumb());
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
        $this->assertEquals("/albums/./file/$fakeRandomFile", $this->getRandomThumb());
    }

    public function testSingleFileWithSuffix()
    {
        global $mig_config;
        $mig_config['markertype'] = 'suffix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/test_thumb.jpg');
        $this->assertEquals('/albums/./file/test_thumb.jpg', $this->getRandomThumb());
    }

    public function testMissingFolder()
    {
        $this->assertEquals(FALSE, $this->getRandomThumb('non-existing'));
    }

    public function testGetRandomFromArray()
    {
        // can't really test that it's random, but can test that whatever it does looks ok
        $list = array(1);
        $this->assertEquals(1, _getRandomFromArray($list, 'rand', FALSE));
        $list = array(1, 2);
        $tmp = _getRandomFromArray($list, 'rand', FALSE);
        $this->assertEquals(TRUE, $tmp == 1 || $tmp == 2);
    }

    public function testFakeRandom_DirectoryWithOnlySubdirectoriesAndNoImages()
    {
        global $mig_config;
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        $this->mkdir($this->album_dir . '/dir1');
        $this->mkdir($this->album_dir . '/dir2');
        $this->createFiles('/dir1/thumb_test', 5);
        $this->createFiles('/dir2/thumb_test', 5);
        $fakeRandomDir = $this->getFirstFile($this->album_dir);
        $fakeRandomFile = $this->getFirstFile($this->album_dir . '/' . $fakeRandomDir);
        $this->assertEquals("/albums/./file/$fakeRandomDir/$fakeRandomFile", $this->getRandomThumb());
        $this->assertEquals("/albums/./file/$fakeRandomDir/$fakeRandomFile", $this->getRandomThumb());
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

    private function createFiles($prefix, $count, $suffix='') {
        for ($i = 1; $i <= $count; ++$i) {
            touch($this->album_dir . "/{$prefix}{$i}{$suffix}.jpg");
        }
    }

    private function getRandomThumb($dir="") {
        $folder = $this->album_dir;
        if ($dir) {
            $folder .= "/$dir";
        }
        // PHP 7.1 changed the seed algorithm for mt_rand, even with MT_RAND_PHP and the same seed you get different
        // results in PHP <7.1 vs PHP >=7.1. This lambda is a workaround for that.
        return getRandomThumb('file', $folder, '.', $stable_order=TRUE, function($min, $max) {
            $counter = $this->counter++;
            $hashed = hexdec(hash('crc32', $counter));
            return $min + ($hashed % ($max - $min + 1));
        });
    }
}
