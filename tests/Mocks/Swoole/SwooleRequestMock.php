<?php

namespace Tests\Mocks\Swoole;

use Swoole\Http\Request;

class SwooleRequestMock extends Request {
    public $server = [
        'request_method' => 'GET',
        'request_uri' => '/test',
        'protocol' => 'HTTP/1.1'
    ];
    public $header = [
        'host' => 'localhost',
        'user-agent' => 'PHPUnit',
        'accept' => 'application/json'
    ];
    public $get = ['foo' => 'bar'];
    public $post = [];
    public $cookie = [];
    public $files = [];
};