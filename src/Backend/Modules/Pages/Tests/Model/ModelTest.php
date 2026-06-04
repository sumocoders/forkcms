<?php

namespace Backend\Modules\Pages\Tests\Model;

use Backend\Modules\Pages\Engine\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testUrlIsEncoded(): void
    {
        self::assertEquals(
            'http://www.google.be/Quote',
            Model::getEncodedRedirectUrl('http://www.google.be/Quote')
        );
        self::assertEquals(
            'http://www.google.be/Quote%22HelloWorld%22',
            Model::getEncodedRedirectUrl('http://www.google.be/Quote"HelloWorld"')
        );
        self::assertEquals(
            'http://www.google.be/Quote%27HelloWorld%27',
            Model::getEncodedRedirectUrl("http://www.google.be/Quote'HelloWorld'")
        );
        self::assertEquals(
            'http://cédé.be/Quote%22HelloWorld%22',
            Model::getEncodedRedirectUrl('http://cédé.be/Quote"HelloWorld"')
        );
        self::assertEquals(
            'http://example.com/test#événements',
            Model::getEncodedRedirectUrl('http://example.com/test#événements')
        );
        self::assertEquals(
            'https://user:pass@example.com:8443/foo%22bar?x=1#frag',
            Model::getEncodedRedirectUrl('https://user:pass@example.com:8443/foo"bar?x=1#frag')
        );
        self::assertEquals(
            'https://example.com?foo=bar#frag',
            Model::getEncodedRedirectUrl('https://example.com?foo=bar#frag')
        );
    }
}
