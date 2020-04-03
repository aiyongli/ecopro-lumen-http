<?php
namespace Ecopro\Http\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

trait Passager
{

    /**
     * 通道请求，使用当前的请求的方法，参数，请求体，发送内部服务远程请求（不支付文件上传）
     * @param Request $request
     * @param string $uri
     * @param UploadedFile[] $files
     * @return ResponseInterface
     */
    public function passage($request, $uri)
    {
        $query = $request->query();
        $querystring = $query ? is_array($query) ? http_build_query($query) : $query : $query;
        $uri = !empty($query) ? Str::contains($uri, '?') ? "$uri&$querystring" : "$uri?$querystring" : $uri;

        $this->setHeader('content-type', $request->header('content-type'));

        return $this->request($request->getMethod(), $uri, $request->getContent());
    }
}
