<?php

namespace Lighthouse\CoreBundle\Tests\Util;

use Lighthouse\CoreBundle\Util\Url;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    public function testParts()
    {
        $url = new Url('http://user:password@example.com/path/to/site?query=foo&limit=5#first-found');

        $this->assertTrue($url->issetPart(Url::SCHEME));
        $this->assertEquals('http', $url->getPart(Url::SCHEME));

        $this->assertTrue($url->issetPart(Url::USER));
        $this->assertEquals('user', $url->getPart(Url::USER));

        $this->assertTrue($url->issetPart(Url::PASS));
        $this->assertEquals('password', $url->getPart(Url::PASS));

        $this->assertTrue($url->issetPart(Url::HOST));
        $this->assertEquals('example.com', $url->getPart(Url::HOST));

        $this->assertTrue($url->issetPart(Url::PATH));
        $this->assertEquals('/path/to/site', $url->getPart(Url::PATH));

        $this->assertTrue($url->issetPart(Url::QUERY));
        $this->assertEquals('query=foo&limit=5', $url->getPart(Url::QUERY));

        $this->assertTrue($url->issetPart(Url::FRAGMENT));
        $this->assertEquals('first-found', $url->getPart(Url::FRAGMENT));
    }

    public function testToString()
    {
        $url = new Url('http://user:password@example.com/path/to/site?query=foo&limit=5#first-found');

        $this->assertEquals(
            'http://user:password@example.com/path/to/site?query=foo&limit=5#first-found',
            $url->toString()
        );

        $url->unsetPart(Url::USER);

        $this->assertEquals(
            'http://example.com/path/to/site?query=foo&limit=5#first-found',
            $url->toString()
        );

        $this->assertEquals($url->toString(), (string) $url);

        $url->setPart(Url::PORT, '8080');
        $this->assertEquals(
            'http://example.com:8080/path/to/site?query=foo&limit=5#first-found',
            $url->toString()
        );
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Invalid url part
     */
    public function testInvalidPart()
    {
        $url = new Url('http://user:password@example.com/path/to/site?query=foo&limit=5#first-found');
        $url->getPart('parameter');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Failed to parse url
     */
    public function testInvalidUrl()
    {
        new Url('http:///host');
    }

    public function testGetParts()
    {
        $url = new Url('http://user:password@example.com/path/to/site?query=foo&limit=5#first-found');
        $expected = array(
            'scheme' => 'http',
            'host' => 'example.com',
            'port' => '',
            'user' => 'user',
            'pass' => 'password',
            'path' => '/path/to/site',
            'query' => 'query=foo&limit=5',
            'fragment' => 'first-found'
        );
        $this->assertEquals($expected, $url->getParts());

        $expected = array(
            'scheme' => 'http',
            'host' => 'example.com',
            'user' => 'user',
            'pass' => 'password',
            'path' => '/path/to/site',
            'query' => 'query=foo&limit=5',
            'fragment' => 'first-found'
        );
        $this->assertEquals($expected, $url->getParts(true));
    }
}
