<?php

use PHPUnit\Framework\TestCase;

require_once 'AbstractMigTestCase.class.php';

final class GetFileExtensionTest extends AbstractFileBasedTestCase
{
    public function test()
    {
        include_once 'getFileExtension.php';
        $this->assertEquals('', getFileExtension(''));
        $this->assertEquals('foo', getFileExtension('foo'));
        $this->assertEquals('bar', getFileExtension('foo.bar'));
        $this->assertEquals('baz', getFileExtension('foo.bar.baz'));
    }
}
