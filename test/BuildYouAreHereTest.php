<?php

require_once 'migHtmlSpecialChars.php';

final class BuildYouAreHereTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'buildYouAreHere.php';
        global $mig_config;
        $mig_config = array();
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
        $this->set_mig_config_image('');
        $mig_config['baseurl'] = 'https://example.com/baseurl';
        $mig_config['mig_dl'] = '';
        $mig_config['albumdir'] = $this->album_dir;
        $mig_config['omitimagename'] = FALSE;
    }

    public function testImage()
    {
        global $mig_config;
        $this->set_mig_config_image('test.jpg');
        $this->assertEquals('Main&nbsp;&gt;&nbsp;test.jpg', buildYouAreHere('.'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo">foo</a>&nbsp;&gt;&nbsp;test.jpg',
            buildYouAreHere('./foo'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo">foo</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo/bar">bar</a>&nbsp;&gt;&nbsp;test.jpg',
            buildYouAreHere('./foo/bar'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo_bar">foo&nbsp;bar</a>&nbsp;&gt;&nbsp;test.jpg',
            buildYouAreHere('./foo_bar'));
    }

    public function testImageWithImageNameOmitted()
    {
        global $mig_config;
        $this->set_mig_config_image('test.jpg');
        $mig_config['omitimagename'] = TRUE;
        $this->assertEquals('Main', buildYouAreHere('.'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo">foo</a>',
            buildYouAreHere('./foo'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo">foo</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo/bar">bar</a>',
            buildYouAreHere('./foo/bar'));
    }

    public function testFolder()
    {
        $this->assertEquals('Main', buildYouAreHere('.'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;foo',
            buildYouAreHere('./foo'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo">foo</a>&nbsp;&gt;&nbsp;bar',
            buildYouAreHere('./foo/bar'));

    }

    public function testFolderWithLanguage()
    {
        global $mig_config;
        $mig_config['mig_dl'] = 'de';
        $this->assertEquals('Main', buildYouAreHere('.'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.&amp;mig_dl=de">Main</a>&nbsp;&gt;&nbsp;foo',
            buildYouAreHere('./foo'));
        $this->assertEquals('<a href="https://example.com/baseurl?currDir=.&amp;mig_dl=de">Main</a>&nbsp;&gt;&nbsp;<a href="https://example.com/baseurl?currDir=./foo&amp;mig_dl=de">foo</a>&nbsp;&gt;&nbsp;bar',
            buildYouAreHere('./foo/bar'));
    }
}
