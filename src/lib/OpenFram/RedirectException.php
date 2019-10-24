<?php


namespace OpenFram;


use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;
use function Http\Response\send;

class RedirectException extends \Exception
{

    protected $response;

    public function __construct(Response $response , ?string $message)
    {
        parent::__construct(
            $message ? (string)$message :  $response->getReasonPhrase(),
            $response->getStatusCode()
        );

        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function run($page = null)
    {
        send($this->response->withBody(stream_for($page)));
    }
}
