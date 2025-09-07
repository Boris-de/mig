<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

final class BuildBackLinkTest extends TestCase
{
    public function set_up()
    {
        global $mig_config;
        $mig_config = array();

        include_once 'buildBackLink.php';
        include_once 'migURLencode.php';
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];

        $mig_config['basedir'] = '.';
        $mig_config['baseurl'] = 'https://example.com/baseurl';
        $mig_config['mig_dl'] = '';
        $mig_config['homelink'] = '';
        $mig_config['homelabel'] = '';
        $mig_config['startfrom'] = 0;
        $mig_config['pagetype'] = 'image';
        $mig_config['nothumbs'] = FALSE;
    }

    public function testTopHomeLink()
    {
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->assertEquals('&nbsp;[&nbsp;<a href="https://example.com/home">back&nbsp;to&nbsp;https://example.com/home</a>&nbsp;]&nbsp;',
            buildBackLink('.', 'back'));
    }

    public function testTopHomeLinkWithLabel()
    {
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->set_mig_config('homelabel', 'home');
        $this->assertEquals('&nbsp;[&nbsp;<a href="https://example.com/home">back&nbsp;to&nbsp;home</a>&nbsp;]&nbsp;',
            buildBackLink('.', 'back'));
    }

    public function testTopNoBackLink()
    {
        $this->assertEquals('<!-- no backLink in root tree -->', buildBackLink('.', 'back'));
    }

    public function testTreeBack()
    {
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">up&nbsp;one&nbsp;level</a>',
            buildBackLink('./test', 'back'));
    }

    public function testTreeBackNoThumbs()
    {
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->set_mig_config('nothumbs', 'https://example.com/home');
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">up&nbsp;one&nbsp;level</a>',
            buildBackLink('./test', 'xxx'));
    }

    public function testTreeUpImage()
    {
        $this->set_mig_config('pagetype', 'image');
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">back&nbsp;to&nbsp;thumbnail&nbsp;view</a>',
            buildBackLink('./test', 'up'));
    }

    public function testTreeUpLarge()
    {
        $this->set_mig_config('enc_image', 'image.jpg');
        $this->set_mig_config('pagetype', 'large');
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.&amp;pageType=image&amp;image=image.jpg">back&nbsp;to&nbsp;web-sized&nbsp;view</a>',
            buildBackLink('./test', 'up'));
    }

    public function testMigLanguage()
    {
        $this->set_mig_config('mig_dl', 'nl');
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.&amp;mig_dl=nl">up&nbsp;one&nbsp;level</a>',
            buildBackLink('./test', 'back'));
    }

    public function testStartFrom()
    {
        $this->set_mig_config('startfrom', 1);
        $this->set_mig_config('homelink', 'https://example.com/home');
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.&amp;startFrom=1">up&nbsp;one&nbsp;level</a>',
            buildBackLink('./test', 'back'));
    }

    private function set_mig_config($key, $value) {
        global $mig_config;
        $mig_config[$key] = $value;
    }
}
