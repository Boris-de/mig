<?php

use PHPUnit\Framework\TestCase;

final class FormatExifDataTest extends TestCase
{
    public function setUp()
    {
        global $mig_config;
        $mig_config = array();
        include_once 'formatExifData.php';
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
    }

    public function test()
    {
        $exifData = array ( 'comment'   => 'comment1',
            'model'     => 'Model',
            'year'      => '2018',
            'month'     => 'Dec',
            'day'       => '10',
            'time'      => '11:13',
            'iso'       => '100',
            'foclen'    => '123',
            'shutter'   => '321',
            'aperture'  => 'x',
            'flash'     => 'yes');
        $this->assertEquals('comment1 x yes 100 123 Model 321 2018 Dec 10 11:13', formatExifData('%c %a %f %i %l %m %s %Y %M %D %T', $exifData));
        $this->assertEquals('', formatExifData('', $exifData));
        $this->assertEquals('abcd', formatExifData('abcd', $exifData));
        $this->assertEquals('%%', formatExifData('%%', $exifData));
        $this->assertEquals('', formatExifData('%X', $exifData));
    }
}
