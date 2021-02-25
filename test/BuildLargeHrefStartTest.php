<?php

use PHPUnit\Framework\TestCase;

require_once 'AbstractMigTestCase.class.php';

final class BuildLargeHrefStartTest extends AbstractFileBasedTestCase
{
    public function setUp() : void
    {
        include_once 'migURLencode.php';
        include_once 'buildLargeHrefStart.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['baseurl'] = '/albums';
        $mig_config['enc_image'] = 'test.jpg';
        $mig_config['unsafe_image'] = 'test.jpg';
        $mig_config['mig_dl'] = NULL;
        $mig_config['startfrom'] = NULL;
    }

    public function test()
    {
        $this->assertEquals('<a href="/albums?currDir=.&amp;pageType=large&amp;image=test.jpg">',
            buildLargeHrefStart('.'));
        $this->assertEquals('<a href="/albums?currDir=./foo&amp;pageType=large&amp;image=test.jpg">',
            buildLargeHrefStart('./foo'));
    }

    public function testStartFrom()
    {
        global $mig_config;
        $mig_config['startfrom'] = 1;
        $this->assertEquals('<a href="/albums?currDir=.&amp;pageType=large&amp;image=test.jpg&amp;startFrom=1">',
            buildLargeHrefStart('.'));
        $this->assertEquals('<a href="/albums?currDir=./foo&amp;pageType=large&amp;image=test.jpg&amp;startFrom=1">',
            buildLargeHrefStart('./foo'));
    }

    public function testLanguageCode()
    {
        global $mig_config;
        $mig_config['mig_dl'] = 'de';
        $this->assertEquals('<a href="/albums?currDir=.&amp;pageType=large&amp;image=test.jpg&amp;mig_dl=de">',
            buildLargeHrefStart('.'));
        $this->assertEquals('<a href="/albums?currDir=./foo&amp;pageType=large&amp;image=test.jpg&amp;mig_dl=de">',
            buildLargeHrefStart('./foo'));
    }
}
