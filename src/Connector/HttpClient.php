<?php

namespace MatheusMariano\Tumblr\Connector;

use Psr\Http\Message\ResponseInterface;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;

class HttpClient
{
    /**
     * The parameters used by the client;
     *
     * @var array
     */
    protected $params;

    /**
     * Create a HttpClient instance.
     *
     * @param Authenticable $auth
     * @param string $baseUri
     */
    public function __construct(Authenticable $auth, $baseUri)
    {
        $this->params = [
            'base_uri' => $baseUri,
            'auth' => $auth->getAuth(),
            'handler' => $auth->getHandler(),
        ];
    }

    /**
     * Send an HTTP request.
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array   $params
     * @return string
     */
    public function request($method, $path, array $params = [])
    {
        $client = $this->getClient();

        $response = $client->request($method, $path, [
            strtolower($method) == 'get' ? 'query' : 'form_params' => $params
        ]);

        return $this->parseResponse($response);
    }

    /**
     * Parse the raw response.
     *
     * @param  ResponseInterface  $response
     * @return string
     */
    protected function parseResponse(ResponseInterface $response)
    {
        return (string) $response->getBody();
    }

    /**
     * Get the HTTP client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient()
    {
        return new \GuzzleHttp\Client($this->params);
    }
}
