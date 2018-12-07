<?php

require_once 'AbstractFileBasedTestCase.class.php';

final class ParseMigCfTest extends AbstractFileBasedTest
{
    protected function setupMigFake()
    {
        include_once 'parseMigCf.php';
        global $mig_config;
        $mig_config = array();
        $mig_config['hidden'] = array();
        $mig_config['pagetitle'] = NULL;
        $mig_config['usethumbfile'] = array();
        $mig_config['usethumbsubdir'] = FALSE;
        $mig_config['uselargeimages'] = FALSE;
        $mig_config['thumbsubdir'] = 'thumbs';
    }

    public function testMissingFile()
    {
        global $mig_config;
        list($presort_dir, $presort_img, $desc, $short_desc, $bulletin, $ficons, $template, $fcols, $tcols, $trows, $maintaddr) = parseMigCf($this->album_dir);
        $this->assertEquals(array(), $mig_config['hidden']);
        $this->assertEquals(array(), $mig_config['usethumbfile']);
        $this->assertEquals(NULL, $mig_config['pagetitle']);
        $this->assertEquals(array(), $presort_dir);
        $this->assertEquals(array(), $presort_img);
        $this->assertEquals(array(), $desc);
        $this->assertEquals(array(), $short_desc);
        $this->assertEquals(NULL, $bulletin);
        $this->assertEquals(array(), $ficons);
        $this->assertEquals(NULL, $template);
        $this->assertEquals(NULL, $fcols);
        $this->assertEquals(NULL, $tcols);
        $this->assertEquals(NULL, $trows);
        $this->assertEquals(NULL, $maintaddr);
    }

    public function testEmptyFile()
    {
        global $mig_config;
        $this->touchWithContent($this->album_dir . '/mig.cf', '');

        list($presort_dir, $presort_img, $desc, $short_desc, $bulletin, $ficons, $template, $fcols, $tcols, $trows, $maintaddr) = parseMigCf($this->album_dir);
        $this->assertEquals(array(), $mig_config['hidden']);
        $this->assertEquals(array(), $mig_config['usethumbfile']);
        $this->assertEquals(NULL, $mig_config['pagetitle']);
        $this->assertEquals(array(), $presort_dir);
        $this->assertEquals(array(), $presort_img);
        $this->assertEquals(array(), $desc);
        $this->assertEquals(array(), $short_desc);
        $this->assertEquals(NULL, $bulletin);
        $this->assertEquals(array(), $ficons);
        $this->assertEquals(NULL, $template);
        $this->assertEquals(NULL, $fcols);
        $this->assertEquals(NULL, $tcols);
        $this->assertEquals(NULL, $trows);
        $this->assertEquals(NULL, $maintaddr);
    }

    public function testParse()
    {
        global $mig_config;
        $this->touchWithContent($this->album_dir . '/mig.cf',
            "<hidden>\nfoo\nbar\n</hidden>\n" .
            "<sort>\nnon_existing\ndir2\nfile2.jpg\nfile1.jpg\ndir1\n</sort>\n" .
            "<bulletin>\nbulletin\ntext\n</bulletin>\n" .
            "<comment \"file1.jpg\">\ncomment file1\n</comment>\n" .
            "<comment \"file2.jpg\">\ncomment file2\n</comment>\n" .
            "<short \"file1.jpg\">\nshort file1\n</short>\n" .
            "<short \"file2.jpg\">\nshort file2\n</short>\n" .
            "foldericon dir1 foo.jpg\n" .
            "foldericon dir2 bar.jpg\n" .
            "usethumb file1.jpg file1.thumb.jpg\n" .
            "usethumb file2.jpg file2.thumb.jpg\n" .
            "foldertemplate ftemplate\n" .
            "pagetitle Page Title\n" .
            "maintaddr test@example.com\n" .
            "maxfoldercolumns 1\n" .
            "maxthumbcolumns 2\n" .
            "maxthumbrows 3\n");

        $this->mkdir($this->album_dir . '/dir1');
        $this->mkdir($this->album_dir . '/dir2');
        touch($this->album_dir . '/file1.jpg');
        touch($this->album_dir . '/file2.jpg');

        list($presort_dir, $presort_img, $desc, $short_desc, $bulletin, $ficons, $template, $fcols, $tcols, $trows, $maintaddr) = parseMigCf($this->album_dir);
        $this->assertEquals(array('foo' => TRUE, 'bar' => TRUE), $mig_config['hidden']);
        $this->assertEquals(array('file1.jpg' => 'file1.thumb.jpg', 'file2.jpg' => 'file2.thumb.jpg'), $mig_config['usethumbfile']);
        $this->assertEquals('Page Title', $mig_config['pagetitle']);
        $this->assertEquals(array('dir2' => TRUE, 'dir1' => TRUE), $presort_dir);
        $this->assertEquals(array('file2.jpg' => TRUE, 'file1.jpg' => TRUE), $presort_img);
        $this->assertEquals(array('file1.jpg' => 'comment file1 ', 'file2.jpg' => 'comment file2 '), $desc);
        $this->assertEquals(array('file1.jpg' => 'short file1 ', 'file2.jpg' => 'short file2 '), $short_desc);
        $this->assertEquals("bulletin\ntext\n", $bulletin);
        $this->assertEquals(array('dir1' => 'foo.jpg', 'dir2' => 'bar.jpg'), $ficons);
        $this->assertEquals('ftemplate', $template);
        $this->assertEquals(1, $fcols);
        $this->assertEquals(2, $tcols);
        $this->assertEquals(3, $trows);
        $this->assertEquals("test@example.com", $maintaddr);
    }
}
