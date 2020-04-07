<?php

namespace Ecopro\Http;

use Ecopro\Http\Concerns\Base;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * 远程请求
 */
class Broker
{
    use Base;

    public function __construct()
    {
        $this->init();
        $this->setTimeout(5);
    }
}
