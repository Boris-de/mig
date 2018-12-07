<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class BuildNextPrevLinksest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        global $mig_config;
        $mig_config = array();
        include_once 'parseMigCf.php';
        include_once 'buildNextPrevLinks.php';
        require 'en.php';
        $mig_config['lang'] = $mig_config['lang_lib']['en'];
        $mig_config['baseurl'] = 'https://example.com/baseurl';
        $mig_config['hidden'] = array();
        $mig_config['albumdir'] = $this->album_dir;
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['thumbsubdir'] = NULL;
        $mig_config['largesubdir'] = NULL;
        $mig_config['prevformatstring'] = '%l';
        $mig_config['nextformatstring'] = '%l';
        $mig_config['image'] = NULL;
        $mig_config['pagetype'] = 'image';
        $mig_config['markertype'] = 'suffix';
        $mig_config['markerlabel'] = NULL;
        $mig_config['sorttype'] = 'default';
        $mig_config['image_extensions'] = array('jpg', 'jpeg');
        $mig_config['video_extensions'] = array('mp4', 'avi');
        $mig_config['audio_extensions'] = array('mp3', 'm4a');
        $mig_config['imageFilenameRegexpr'] = '=^[^<>/]*$=';
        $mig_config['startfrom'] = 0;
        $mig_config['mig_dl'] = '';
    }

    public function test()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        touch($this->album_dir . '/test1.jpg');
        touch($this->album_dir . '/test2.jpg');
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = 'test2.jpg';
        $this->assertEquals(array(
                0 => '<span class="inactivelink">next&nbsp;image</span>',
                1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test1.jpg">previous&nbsp;image</a>',
                2 => '#2&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));
    }

    public function testStartFrom()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['startfrom'] = 1;
        touch($this->album_dir . '/test1.jpg');
        touch($this->album_dir . '/test2.jpg');
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg&amp;startFrom=1">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));
    }

    public function testLanguage()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['mig_dl'] = 'de';
        touch($this->album_dir . '/test1.jpg');
        touch($this->album_dir . '/test2.jpg');
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg&amp;mig_dl=de">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));
    }

    public function testSortByDateAscend()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['sorttype'] = 'bydate-ascend';
        touch($this->album_dir . '/test1.jpg', 3);
        touch($this->album_dir . '/test2.jpg', 2);
        $this->assertEquals(array(
                0 => '<span class="inactivelink">next&nbsp;image</span>',
                1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg">previous&nbsp;image</a>',
                2 => '#2&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));
    }

    public function testSortByDateDescend()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['sorttype'] = 'bydate-descend';
        touch($this->album_dir . '/test1.jpg', 3);
        touch($this->album_dir . '/test2.jpg', 2);
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));
    }

    public function testWithHiddenFiles()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['hidden'] = array('test2.jpg' => TRUE);
        touch($this->album_dir . '/test1.jpg');
        touch($this->album_dir . '/test2.jpg');
        touch($this->album_dir . '/test3.jpg');
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test3.jpg">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = 'test3.jpg';
        $this->assertEquals(array(
                0 => '<span class="inactivelink">next&nbsp;image</span>',
                1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test1.jpg">previous&nbsp;image</a>',
                2 => '#2&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = 'test2.jpg';
        $mig_config['hidden'] = array('test3.jpg' => TRUE);
        $this->assertEquals(array(
                0 => '<span class="inactivelink">next&nbsp;image</span>',
                1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test1.jpg">previous&nbsp;image</a>',
                2 => '#2&nbsp;of&nbsp;2'
        ), buildNextPrevLinks(".", array()));
    }

    public function testWithSuffixThumbs()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['markertype'] = 'suffix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/test1.jpg');
        touch($this->album_dir . '/test1_thumb.jpg');
        touch($this->album_dir . '/test2.jpg');
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = 'test2.jpg';
        $this->assertEquals(array(
                0 => '<span class="inactivelink">next&nbsp;image</span>',
                1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test1.jpg">previous&nbsp;image</a>',
                2 => '#2&nbsp;of&nbsp;2'
        ), buildNextPrevLinks(".", array()));
    }

    public function testWithPrefixThumbs()
    {
        global $mig_config;
        $mig_config['image'] = 'test1.jpg';
        $mig_config['markertype'] = 'prefix';
        $mig_config['markerlabel'] = 'thumb';
        touch($this->album_dir . '/test1.jpg');
        touch($this->album_dir . '/thumb_test1.jpg');
        touch($this->album_dir . '/test2.jpg');
        $this->assertEquals(array(
                0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test2.jpg">next&nbsp;image</a>',
                1 => '<span class="inactivelink">previous&nbsp;image</span>',
                2 => '#1&nbsp;of&nbsp;2'
            ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = 'test2.jpg';
        $this->assertEquals(array(
                0 => '<span class="inactivelink">next&nbsp;image</span>',
                1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=test1.jpg">previous&nbsp;image</a>',
                2 => '#2&nbsp;of&nbsp;2'
        ), buildNextPrevLinks(".", array()));
    }

    public function testWithInvalidFileNames()
    {
        global $mig_config;
        $mig_config['image'] = '2-test.jpg';
        $mig_config['imageFilenameRegexpr'] = '=^.*-test\.jpg$=';
        touch($this->album_dir . '/1-invalid.jpg');
        touch($this->album_dir . '/2-test.jpg');
        touch($this->album_dir . '/3-invalid.jpg');
        touch($this->album_dir . '/4-test.jpg');
        touch($this->album_dir . '/5-invalid.jpg');
        touch($this->album_dir . '/6-test.jpg');
        touch($this->album_dir . '/7-invalid.jpg');
        $this->assertEquals(array(
            0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=4-test.jpg">next&nbsp;image</a>',
            1 => '<span class="inactivelink">previous&nbsp;image</span>',
            2 => '#2&nbsp;of&nbsp;7' // FIXME invalid files should not be counted here
        ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = '4-test.jpg';
        $this->assertEquals(array(
            0 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=6-test.jpg">next&nbsp;image</a>',
            1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=2-test.jpg">previous&nbsp;image</a>',
            2 => '#4&nbsp;of&nbsp;7'
        ), buildNextPrevLinks(".", array()));

        $mig_config['image'] = '6-test.jpg';
        $this->assertEquals(array(
            0 => '<span class="inactivelink">next&nbsp;image</span>',
            1 => '<a href="https://example.com/baseurl?pageType=image&amp;currDir=.&amp;image=4-test.jpg">previous&nbsp;image</a>',
            2 => '#6&nbsp;of&nbsp;7'
        ), buildNextPrevLinks(".", array()));
    }
}
