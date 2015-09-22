<?php

namespace MatheusMariano\Tumblr\Connector;

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
     * @param Auth\Authenticable $auth
     * @param string $baseUri
     */
    public function __construct(Auth\Authenticable $auth, $baseUri)
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $path, array $params = [])
    {
        $client = $this->getClient();

        return $client->request($method, $path, [
            'form_params' => $params
        ]);
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
