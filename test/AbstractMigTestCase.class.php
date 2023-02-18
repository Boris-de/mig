<?php

use PHPUnit\Framework\TestCase;

require_once 'migHtmlSpecialChars.php';

abstract class AbstractFileBasedTestCase extends TestCase {
    static function migAssertStringContainsString($needle, $haystack, $message = ''): void
    {
        if (method_exists('TestCase', 'assertStringContainsString')) {
            TestCase::assertStringContainsString($needle, $haystack, $message);
        } else {
            TestCase::assertTrue(strpos($haystack, $needle) != FALSE, $message);
        }
    }

    static function migAssertStringNotContainsString($needle, $haystack, $message = ''): void
    {
        if (method_exists('TestCase', 'assertStringNotContainsString')) {
            TestCase::assertStringNotContainsString($needle, $haystack, $message);
        } else {
            TestCase::assertTrue(strpos($haystack, $needle) === FALSE, $message);
        }
    }
}
