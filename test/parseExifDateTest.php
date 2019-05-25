<?php

use PHPUnit\Framework\TestCase;

final class ParseExifDateTest extends TestCase
{
    public function setUp() : void
    {
        global $mig_config;
        $mig_config = array();
        include_once 'parseExifDate.php';
        require 'en.php';
        require 'de.php';
        require 'si.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
    }

    public function test()
    {
        $this->assertEquals(array('2013', 'Dec', '05', '12:37AM'), parseExifDate('2013:12:05 00:37:14'));
        $this->assertEquals(array('2013', 'Dec', '05', '01:37AM'), parseExifDate('2013:12:05 01:37:14'));
        $this->assertEquals(array('2013', 'Dec', '05', '11:37AM'), parseExifDate('2013:12:05 11:37:14'));
        $this->assertEquals(array('2013', 'Dec', '05', '06:37PM'), parseExifDate('2013:12:05 18:37:14'));
        $this->assertEquals(array('2013', 'Dec', '05', '11:37PM'), parseExifDate('2013:12:05 23:37:14'));
    }

    public function testDifferentLanguage1()
    {
        global $mig_config;
        $mig_config['lang'] = $mig_config['lang_lib']['de'];
        $this->assertEquals(array('2013', 'Dez', '05', '01:37AM'), parseExifDate('2013:12:05 01:37:14'));
        $this->assertEquals(array('2013', 'Dez', '05', '06:37PM'), parseExifDate('2013:12:05 18:37:14'));
    }

    public function testDifferentLanguage2()
    {
        global $mig_config;
        $mig_config['lang'] = $mig_config['lang_lib']['si'];
        $this->assertEquals(array('2013', 'Dec', '05', '01:37dopoldan'), parseExifDate('2013:12:05 01:37:14'));
        $this->assertEquals(array('2013', 'Dec', '05', '06:37popoldan'), parseExifDate('2013:12:05 18:37:14'));
    }


    public function testInvalidValues()
    {
        $this->assertEquals(array('2013', 'Dec', '05', '24:37:14'), parseExifDate('2013:12:05 24:37:14'));

        $this->assertEquals(array('', '', '', '', ''), parseExifDate('asfdasdf'));
        $this->assertEquals(array('', '', '', '', ''), parseExifDate(''));
        $this->assertEquals(array('', '', '', '', ''), parseExifDate('2013'));
        $this->assertEquals(array('', '', '', '', ''), parseExifDate('2013:12'));
        $this->assertEquals(array('2013', 'Dec', '05', ''), parseExifDate('2013:12:05'));
        $this->assertEquals(array('2013', 'Dec', '05', '23'), parseExifDate('2013:12:05 23'));
        $this->assertEquals(array('2013', 'Dec', '05', '23:37'), parseExifDate('2013:12:05 23:37'));
        $this->assertEquals(array('2013', 'Dec', '05', '11:37PM'), parseExifDate('2013:12:05 23:37:14'));
        $this->assertEquals(array('2013', 'Dec', '05', '23:37:14:15'), parseExifDate('2013:12:05 23:37:14:15'));
    }
}
