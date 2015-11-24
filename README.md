# request-factory
Turn raw HTTP Strings into PSR-7 Requests

# Usage

// ...
use \Geggleto\Foundation\Factory\ServerRequestFactory;

$factory = new ServerRequestFactory();

$request = $factory->parseFromString("GET /docs/index.html HTTP/1.1
Host: http://www.test101.com
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

// $request is PSR-7!
