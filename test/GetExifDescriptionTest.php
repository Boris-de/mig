<?php

use PHPUnit\Framework\TestCase;

final class GetExifDescriptionTest extends AbstractFileBasedTest
{
    public function setupMigFake()
    {
        global $mig_config;
        $mig_config = array();
        include_once 'parseExifDate.php';
        include_once 'formatExifData.php';
        include_once 'getExifDescription.php';
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
        $mig_config['albumdir'] = $this->album_dir;
        $this->set_mig_config_image(NULL);
    }

    public function testNoExifInfo()
    {
        $this->assertEquals('', getExifDescription('.', '%a%f%i%l%m%s%Y%M%D%T'));
    }

    public function test()
    {
        $this->set_mig_config_image('test.jpg');
        $dir = $this->album_dir . '/foo';
        $this->mkdir($dir);
        $this->touchWithContent($dir . '/exif.inf', "File name    : test.jpg\n
File size    : 5865474 bytes\n
File date    : 2017:12:19 10:55:04\n
Camera make  : Canon\n
Camera model : Canon EOS 70D\n
Date/Time    : 2013:12:05 18:37:14\n
Resolution   : 5472 x 3648\n
Flash used   : Yes\n
Focal length : 40.0mm  (35mm equivalent: 63mm)\n
CCD width    : 22.83mm\n
Exposure time: 0.017 s  (1/60)\n
Aperture     : f/2.8\n
ISO equiv.   : 640\n
Whitebalance : Auto\n
Metering Mode: partial\n
Exposure     : aperture priority (semi-auto)\n
GPS Latitude : ? ?\n
GPS Longitude: ? ?\n
JPEG Quality : 97\n
Comment      : foobar\n
");
        $this->assertEquals('foobar f2.8 flash&nbsp;used 640 63mm Canon EOS 70D 1/60 2013 Dec 05 06:37PM',
            getExifDescription('./foo', '%c %a %f %i %l %m %s %Y %M %D %T'));
    }

    public function testEmptyFile()
    {
        $this->set_mig_config_image('test.jpg');
        $dir = $this->album_dir . '/foo';
        $this->mkdir($dir);
        $this->touchWithContent($dir . '/exif.inf', "");
        $this->assertEquals('',
            getExifDescription('./foo', '%c %a %f %i %l %m %s %Y %M %D %T'));
    }

    public function testOnlyNewline()
    {
        $this->set_mig_config_image('test.jpg');
        $dir = $this->album_dir . '/foo';
        $this->mkdir($dir);
        $this->touchWithContent($dir . '/exif.inf', "\n");
        $this->assertEquals('',
            getExifDescription('./foo', '%c %a %f %i %l %m %s %Y %M %D %T'));
    }

    public function testContentAfterEmptyLine()
    {
        $this->set_mig_config_image('test.jpg');
        $dir = $this->album_dir . '/foo';
        $this->mkdir($dir);
        $this->touchWithContent($dir . '/exif.inf', "\nFile name    : test.jpg\nCamera model : Canon EOS 70D");
        $this->assertEquals('Canon EOS 70D', getExifDescription('./foo', '%m'));
    }

    public function testMultipleFiles()
    {
        $this->touchWithContent($this->album_dir . '/exif.inf', "File name    : test1.jpg\n
Camera model : Canon EOS 70D\n
Date/Time    : 2013:12:05 18:37:14\n
Flash used   : Yes\n
Exposure time: 0.017 s  (1/60)\n
Aperture     : f/2.8\n
ISO equiv.   : 640\n
Focal length : 40.0mm  (35mm equivalent: 63mm)\n
Comment      : foobar\n
\n
File name    : test2.jpg\n
Camera model : Test\n
Date/Time    : 2000:01:01 12:34:50\n
Flash used   : No\n
Exposure time: 0.123 s\n
Aperture     : f/2.9\n
ISO equiv.   : 100\n
Focal length : 41.0mm\n
Comment      : comment2\n
");
        $this->set_mig_config_image('test1.jpg');
        $this->assertEquals('foobar f2.8 flash&nbsp;used 640 63mm Canon EOS 70D 1/60 2013 Dec 05 06:37PM',
            getExifDescription('.', '%c %a %f %i %l %m %s %Y %M %D %T'));

        $this->set_mig_config_image('test2.jpg');
        $this->assertEquals('comment2 f2.9  100 41.0mm Test 0.123 s 2000 Jan 01 12:34PM',
            getExifDescription('.', '%c %a %f %i %l %m %s %Y %M %D %T'));
    }

    public function testFileNotInExifData()
    {
        $this->set_mig_config_image('not_existing.jpg');
        $dir = $this->album_dir . '/foo';
        $this->mkdir($dir);
        $this->touchWithContent($dir . '/exif.inf', "File name    : test.jpg\nComment      : foobar\n
");
        $this->assertEquals('',
            getExifDescription('./foo', '%c %a %f %i %l %m %s %Y %M %D %T'));
    }
}
