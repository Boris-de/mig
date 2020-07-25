<?php

use PHPUnit\Framework\TestCase;

require_once 'AbstractMigTestCase.class.php';

final class GetFileTypeTest extends AbstractFileBasedTestCase
{
    /**
     * @before
     */
    public function setupMigFake()
    {
        include_once 'getFileExtension.php';
        include_once 'getFileType.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['image_extensions'] = array('jpg', 'jpeg');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
    }

    public function test()
    {
        $this->assertEquals('image', getFileType('foo.jpg'));
        $this->assertEquals('image', getFileType('foo.jpeg'));
        $this->assertEquals('image', getFileType('foo.JPEG'));

        $this->assertEquals('video', getFileType('foo.mp4'));

        $this->assertEquals('audio', getFileType('foo.mp3'));

        // unknown extension
        $this->assertEquals(FALSE, getFileType('foo.exe'));
    }
}
