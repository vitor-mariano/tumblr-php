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
     * @param string     $baseUri
     * @param OAuth|null $oauth
     */
    public function __construct($baseUri, OAuth $oauth = null)
    {
        $this->params = ['base_uri' => $baseUri];

        if (! is_null($oauth)) {
            $this->params['auth'] = 'oauth';
            $this->params['handler'] = $oauth->getHandler();
        }
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
