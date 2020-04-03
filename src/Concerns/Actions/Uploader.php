<?php
namespace Ecopro\Http\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

trait Uploader
{

    /**
     * 使用文件路径方式上传文件
     * @param string $uri
     * @param string|UploadedFile $path 文件路径或上传文件
     * @param string $filename 文件名
     * @param string $mime 文件MIME
     * @param string $name 上传文件时使用的参数名
     * @return ResponseInterface
     */
    public function uploadPath($uri, $path, $filename, $mime = null, $name = 'file')
    {
        $tmp = false;
        if($path instanceof UploadedFile) {
            $tmp = true;
            $file = $path;
            $mime = $file->getClientMimeType();
            $dir = storage_path("tmp");
            file_exists($dir) || create_directory($dir);
            $file->move($dir, $filename);
            $path = storage_path("tmp/$filename");
        }
        // 组合表单
        $form = [];
        $form['name'] = $filename;
        $form['path'] = $path;

        try {
            $response = $this->post($uri, $form);
            $tmp && unlink($path);
            return $response;
        } catch(\Exception $e) {
            $tmp && unlink($path);
            throw $e;
        }
    }

    /**
     * 上传文件
     * @param string $uri
     * @param string|UploadedFile $path 文件路径或上传文件
     * @param string $filename 文件名
     * @param string $mime 文件MIME
     * @param string $name 上传文件时使用的参数名
     * @return ResponseInterface
     */
    public function upload($uri, $path, $filename, $mime = null, $name = 'file')
    {
        // 清除content-type
        unset($this->_headers['content-type']);
        $tmp = false;
        if($path instanceof UploadedFile) {
            $tmp = true;
            $file = $path;
            $mime = $file->getClientMimeType();
            $dir = storage_path("tmp");
            file_exists($dir) || create_directory($dir);
            $file->move($dir, $filename);
            $path = storage_path("tmp/$filename");
        }
        // multipart
        $multipart = [];
        $part = [
            'name' => $name,
            'filename' => $filename,
            'contents' => fopen($path, 'r')
        ];
        if(!empty($mime)) {
            $part['headers']['content-type'] = $mime;
        }
        $multipart[] = $part;
        $this->_options['multipart'] = $multipart;

        try {
            $response = $this->request('POST', $uri, null);
            $tmp && unlink($path);
            return $response;
        } catch(\Exception $e) {
            $tmp && unlink($path);
            throw $e;
        }
    }
}
