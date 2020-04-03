<?php
namespace Ecopro\Http\Concerns\Features;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

trait Baser
{
    /**
     * @var Client
     */
    protected $_client;
    protected $_baseUri;
    protected $_options = [];
    protected $_headers = [];

    /**
     * @return static
     */
    public function init()
    {
        $this->_client = new Client();

        return $this;
    }
}
