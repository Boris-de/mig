<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

require_once 'migHtmlSpecialChars.php';

final class GetFileExtensionTest extends TestCase
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
