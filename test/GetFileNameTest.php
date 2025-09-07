<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

require_once 'migHtmlSpecialChars.php';

final class GetFileNameTest extends TestCase
{
    public function test()
    {
        include_once 'getFileName.php';
        $this->assertEquals('', getFileName(''));
        $this->assertEquals('foo', getFileName('foo'));
        $this->assertEquals('foo', getFileName('foo.bar'));
        $this->assertEquals('foo.bar', getFileName('foo.bar.baz'));
    }
}
