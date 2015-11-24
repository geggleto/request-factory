<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2015-11-24
 * Time: 2:46 PM
 */

namespace Geggleto\Foundation\Factory;


use Zend\Diactoros\Request;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;

class ServerRequestFactory extends \Zend\Diactoros\ServerRequestFactory
{
    /**
     * @param string $message
     * @return bool|\Zend\Diactoros\ServerRequest
     */
    public function parseFromString($message = '') {
        $message = $this->http_parse_headers($message);

        $uri = explode(" ", $message[0]);

        if (is_null($message)) {
            return false;
        }

        //Remove the URI
        unset($message[0]);

        //Remove the Message Body
        $body = $message['body'];
        unset($message['body']);


        $sr = new ServerRequest([], [], $message["Host"].$uri[1], $uri[0], new Stream('php://memory', "wb+"), $message);
        $sr->getBody()->write($body);
        return $sr;
    }



   protected function http_parse_headers($raw_headers)
    {
        $headers = array();
        $body = [];
        $key = ''; // [+]

        foreach(explode("\n", $raw_headers) as $i => $h)
        {
            $o = $h;
            $h = explode(':', $h, 2);

            if (isset($h[1]))
            {
                if (!isset($headers[$h[0]]))
                    $headers[$h[0]] = trim($h[1]);
                elseif (is_array($headers[$h[0]]))
                {
                    // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1]))); // [+]
                }
                else
                {
                    // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [+]
                }

                $key = $h[0]; // [+]
            }
            else // [+]
            { // [+]
                if (substr($h[0], 0, 1) == "\t") { // [+]
                    $headers[ $key ] .= "\r\n\t" . trim($h[0]); // [+]
                }
                elseif (!$key) { // [+]
                    $headers[0] = trim($h[0]);
                    trim($h[0]); // [+]
                }
                else {
                    $body[] .= $o;
                }
            } // [+]
        }

        $headers['body'] = implode("\n", $body);
        return $headers;
    }
}