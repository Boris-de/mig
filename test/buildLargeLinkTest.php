<?php

use PHPUnit\Framework\TestCase;

final class BuildLargeLinkTest extends TestCase
{
    public function setUp(): void
    {
        global $mig_config;
        $mig_config = array();
        include_once 'migURLencode.php';
        include_once 'buildLargeLink.php';
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
        $mig_config['baseurl'] = '/albums';
        $mig_config['image'] = 'test.jpg';
        $mig_config['mig_dl'] = NULL;
        $mig_config['startfrom'] = NULL;
    }

    public function test()
    {
        $this->assertEquals('<a href="/albums?currDir=.&amp;pageType=large&amp;image=test.jpg">view&nbsp;full-size&nbsp;image</a>',
            buildLargeLink('.'));
        $this->assertEquals('<a href="/albums?currDir=./foo&amp;pageType=large&amp;image=test.jpg">view&nbsp;full-size&nbsp;image</a>',
            buildLargeLink('./foo'));
    }

    public function testStartFrom()
    {
        global $mig_config;
        $mig_config['startfrom'] = 1;
        $this->assertEquals('<a href="/albums?currDir=.&amp;pageType=large&amp;image=test.jpg&amp;startFrom=1">view&nbsp;full-size&nbsp;image</a>',
            buildLargeLink('.'));
        $this->assertEquals('<a href="/albums?currDir=./foo&amp;pageType=large&amp;image=test.jpg&amp;startFrom=1">view&nbsp;full-size&nbsp;image</a>',
            buildLargeLink('./foo'));
    }

    public function testLanguageCode()
    {
        global $mig_config;
        $mig_config['mig_dl'] = 'de';
        $this->assertEquals('<a href="/albums?currDir=.&amp;pageType=large&amp;image=test.jpg&amp;mig_dl=de">view&nbsp;full-size&nbsp;image</a>',
            buildLargeLink('.'));
        $this->assertEquals('<a href="/albums?currDir=./foo&amp;pageType=large&amp;image=test.jpg&amp;mig_dl=de">view&nbsp;full-size&nbsp;image</a>',
            buildLargeLink('./foo'));
    }
}
