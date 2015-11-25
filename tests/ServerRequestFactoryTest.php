<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-11-24
 * Time: 2:53 PM
 */
use \Geggleto\Foundation\Factory\ServerRequestFactory;

class ServerRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $postBody = "username=zurfyx&pass=password";
    protected $postBodyArray = [
        "username" => "zurfyx",
        "pass" => "password"
    ];

    protected $body = 'junkmessage
body';

    /**
     * @var \Geggleto\Foundation\Factory\ServerRequestFactory
     */
    protected $factory;

    public function setUp ()
    {
        parent::setUp();

        $this->factory = new ServerRequestFactory();

    }

    public function getRequest() {
        return "GET /docs/index.html HTTP/1.1
Host: test101.com
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)
" . $this->body;

    }

    public function postRequest() {
        return "POST /pass.php HTTP/1.1
Host: 127.0.0.1
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
DNT: 1
Referer: http://127.0.0.1/pass.php
Cookie: passx=87e8af376bc9d9bfec2c7c0193e6af70; PHPSESSID=l9hk7mfh0ppqecg8gialak6gt5
Connection: keep-alive
Content-Type: application/x-www-form-urlencoded
Content-Length: 30
" . $this->postBody;
    }

    public function testGETRequestCreation() {
        $request = $this->factory->parseFromString($this->getRequest());
        $request->getBody()->rewind();


        $this->assertEquals('/docs/index.html', $request->getUri()->getPath());
        $this->assertEquals($this->body, $request->getBody()->getContents());
        $this->assertEquals("test101.com", $request->getUri()->getHost());
        $this->assertEquals("http", $request->getUri()->getScheme());
    }

    public function testPOSTRequestCreation() {
        $request = $this->factory->parseFromString($this->postRequest());
        $request->getBody()->rewind();

        $this->assertEquals('/pass.php', $request->getUri()->getPath());
        $this->assertEquals($this->postBody, $request->getBody()->getContents());
        $this->assertEquals("127.0.0.1", $request->getUri()->getHost());
        $this->assertEquals($this->postBodyArray, $request->getParsedBody());
    }

}
