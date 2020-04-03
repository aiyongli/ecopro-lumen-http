<?php
namespace Ecopro\Http\Concerns\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

trait Sender
{
    /**
     * GET
     * @param string $uri
     * @param array|stdClass|null $params
     */
    public function get($uri, $params = null)
    {
        if(!empty($params)) {
            $params = is_a($params, \stdClass::class) ? get_object_vars($params)  : $params;// 对象转成数组
        } else {
            $params = [];
        }
        if(!is_array($params)) {
            throw new \InvalidArgumentException('Invalid body type: ' . gettype($params));
        }
        $query = json_decode(json_encode($params), true);
        $querystring = http_build_query($query);
        $uri = !empty($query) ? Str::contains($uri, '?') ? "$uri&$querystring" : "$uri?$querystring" : $uri;

        return $this->request('GET', $uri);
    }

    /**
     * POST
     * @param string $uri
     * @param string|array|stdClass|null $body
     */
    public function post($uri, $body = null)
    {
        if(isset($body)) {
            $body = is_a($body, stdClass::class) ? get_object_vars($body)  : $body;// 对象转成数组
            if(!is_scalar($body) && !is_array($body)) {
                throw new \InvalidArgumentException('Invalid body type: ' . gettype($body));
            }
            if(!is_scalar($body)) {
                $form = $body;
                $this->_options['form_params'] = $form;
                $body = null;
            } else {
                $this->setHeader('content-type', 'application/x-www-form-urlencoded');
            }
        }

        return $this->request('POST', $uri, $body);
    }

    /**
     * 远程请求
     * @param string $uri
     * @param string $body
     * @return ResponseInterface
     */
    public function request($method, $uri, $body = null)
    {
        $this->_options = array_merge($this->_options, ['headers' => $this->_headers]);
        if(isset($body)) {
            $this->_options['body'] = $body;
        }

        $url = Str::startsWith($uri, 'http') ? $uri : (rtrim($this->_baseUri, '/').'/'.ltrim($uri, '/'));

        try {
            $response = $this->_client->request($method, $url, $this->_options);

            return $response;
        } catch (ClientException $e) {
            $content = $e->getResponse()->getBody()->getContents();
            $code = $e->getResponse()->getStatusCode();
            throw new \Exception("$code|$content");
        }
    }
}
