<?php

use PHPUnit\Framework\TestCase;

require_once 'AbstractMigTestCase.class.php';
include_once 'ConvertIncludePath.class.php';

abstract class AbstractFileBasedTest extends AbstractFileBasedTestCase
{
    protected $NO_PATH_CONVERT;
    protected $mig_dir;
    protected $album_dir;

    public function setUp() : void
    {
        $this->NO_PATH_CONVERT = new ConvertIncludePath(FALSE, '', '');

        $tempfile = tempnam(sys_get_temp_dir(), 'mig_phpunit_');
        $this->assertTrue($tempfile !== FALSE);
        unlink($tempfile);
        $mig_dir = $tempfile . '.dir';
        $this->assertTrue(mkdir($mig_dir, 0700) !== FALSE);
        $this->mig_dir = $mig_dir;
        $this->album_dir = $mig_dir . '/albums';
        mkdir($this->album_dir);

        $this->setupMigFake();
    }

    abstract protected function setupMigFake();

    protected function set_mig_config($key, $value) {
        global $mig_config;
        $mig_config[$key] = $value;
    }

    public function tearDown() : void
    {
        if ($this->mig_dir != '' && is_dir($this->mig_dir . '/albums')) {
            $this->remove_recursive($this->mig_dir);
        }
    }

    public function remove_recursive($dir)
    {
        $fs_nodes = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($fs_nodes as $node) {
            $todo = $node->isDir() ? 'rmdir' : 'unlink';
            $todo($node->getRealPath());
        }

        rmdir($dir);
    }

    protected function touchWithSize($filename, $size) {
        $this->touchWithContent($filename, str_pad('', $size));
    }

    protected function touchWithContent($filename, $content) {
        touch($filename);
        $f = fopen($filename, 'w');
        fwrite($f, $content);
        fclose($f);
    }

    protected function mkdir($dir) {
        $this->assertTrue(mkdir($dir) !== FALSE);
    }
}
