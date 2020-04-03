<?php
namespace Ecopro\Http\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

trait Setter
{
    /**
     * 设置基础URI地址
     * @return static
     */
    public function setBaseUri($baseUri)
    {
        $this->_baseUri = $baseUri;

        return $this;
    }

    /**
     * 设置一条头信息
     * @return static
     */
    public function setHeader($key, $value)
    {
        $this->_headers[strtolower($key)] = $value;

        return $this;
    }

    /**
     * 设置多条头信息
     * @return static
     */
    public function setHeaders($headers)
    {
        $this->_headers = array_merge($this->_headers, $headers);

        return $this;
    }

    /**
     * 设置认证头信息
     * @return static
     */
    public function setAuthorization($auth)
    {
        return $this->setHeader('authorization', $auth);
    }

    /**
     * 设置一条头信息
     * @see Client
     * @return static
     */
    public function setOption($key, $value)
    {
        $this->_options[strtolower($key)] = $value;

        return $this;
    }

    /**
     * 设置多条可选项信息
     * @see Client
     * @return static
     */
    public function setOptions($options)
    {
        $this->_options = array_merge($this->_options, $options);

        return $this;
    }

    /**
     * 设置超时
     * @return static
     */
    public function setTimeout($timeout)
    {
        return $this->setOption('timeout', intval($timeout));
    }

}
