<?php

namespace Websoftwares\Test\Domain;

use Websoftwares\Domain\RandomString;

/**
 * Class RandomStringTest.
 */
class RandomStringTest extends \PHPUnit_Framework_TestCase
{
    public $randomString;

    public function setUp()
    {
        $this->randomString = new RandomString();
    }

    public function testGenerateSucceeds()
    {
        $this->assertInternalType('string', $this->randomString->generate());

        $expected  = $this->randomString->generate(64);
        $encoded = $this->randomString->base64urlEncode($expected);
        $actual = $this->randomString->base64urlDecode($encoded);

        $this->assertEquals($expected, $actual);
        $this->assertInternalType('string', $encoded);
        $this->assertInternalType('string', $actual);

        $this->assertInternalType('string', $this->randomString->generate(64, true));
    }
}
