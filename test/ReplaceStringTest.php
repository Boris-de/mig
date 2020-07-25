<?php

use PHPUnit\Framework\TestCase;

require_once 'AbstractMigTestCase.class.php';

final class ReplaceStringTest extends AbstractFileBasedTestCase
{
    public function test()
    {
        include_once 'replaceString.php';
        // if nothing is there to replace, an empty string will be returned.
        $this->assertEquals('', replaceString('abc', array()));

        $this->assertEquals('abcfoo', replaceString('abc%x', array('x' => 'foo')));
        $this->assertEquals('abcfoo', replaceString('abc%x%y', array('x' => 'foo')));
        $this->assertEquals('abcfooyfoo', replaceString('abc%xy%x', array('x' => 'foo')));
        $this->assertEquals('abcfoobar', replaceString('abc%x%y', array('x' => 'foo', 'y' => 'bar')));
    }
}
