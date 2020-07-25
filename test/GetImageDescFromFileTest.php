<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class GetImageDescFromFileTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'getImageDescFromFile.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['commentfileshortcomments'] = TRUE;
        $mig_config['albumdir'] = $this->album_dir;
    }

    public function test()
    {
        touch($this->album_dir.'/test3.jpg');
        $this->touchWithContent($this->album_dir.'/test3.txt', "foo\nbar\nbaz");

        $this->assertEquals(array('foo', 'bar baz'), getImageDescFromFile('.', 'test3.jpg'));
    }

    public function testWithOutShortCommentsOnlyOneLine()
    {
        touch($this->album_dir.'/test3.jpg');
        $this->touchWithContent($this->album_dir.'/test3.txt', "foo");

        $this->assertEquals(array('foo', 'foo'), getImageDescFromFile('.', 'test3.jpg'));
    }

    public function testWithoutShortComments()
    {
        global $mig_config;
        $mig_config['commentfileshortcomments'] = FALSE;
        touch($this->album_dir.'/test3.jpg');
        $this->touchWithContent($this->album_dir.'/test3.txt', "foo\nbar\nbaz");

        $this->assertEquals(array('foo bar baz', 'foo bar baz'), getImageDescFromFile('.', 'test3.jpg'));
    }

    public function testFileNotExisting()
    {
        touch($this->album_dir.'/test3.jpg');

        $this->assertEquals(FALSE, getImageDescFromFile('.', 'test3.jpg'));
    }
}
