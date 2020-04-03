<?php
namespace Ecopro\Http\Concerns;

use Ecopro\Http\Concerns\Actions\Sender;
use Ecopro\Http\Concerns\Features\Baser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use stdClass;

trait Base
{
    use Baser, Setter, Sender, Uploader, Passager;
}
