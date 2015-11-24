<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-11-24
 * Time: 2:53 PM
 */

class ServerRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $body = 'junkmessage
body';

    /**
     * @var \Geggleto\Foundation\Factory\ServerRequestFactory
     */
    protected $factory;

    public function setUp ()
    {
        parent::setUp();

        $this->factory = new \Geggleto\Foundation\Factory\ServerRequestFactory();

    }

    public function getRequest() {
        return "GET /docs/index.html HTTP/1.1
Host: http://www.test101.com
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)
" . $this->body;

    }

    public function testGETRequestCreation() {
        $request = $this->factory->parseFromString($this->getRequest());
        $request->getBody()->rewind();

        $this->assertEquals('/docs/index.html', $request->getUri()->getPath());
        $this->assertEquals($this->body, $request->getBody()->getContents());
        $this->assertEquals("www.test101.com", $request->getUri()->getHost());
        $this->assertEquals("http", $request->getUri()->getScheme());
    }

}
