<?php

use PHPUnit\Framework\TestCase;

require_once 'AbstractMigTestCase.class.php';

final class GetFileNameTest extends AbstractFileBasedTestCase
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
