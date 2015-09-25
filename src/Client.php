<?php

namespace MatheusMariano\Tumblr;

use MatheusMariano\Tumblr\Connector\HttpClient;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;

class Client
{
    use HttpClientTrait;

    /**
     * Base URI.
     */
    const BASE_URI = 'https://api.tumblr.com/v2/';

    /**
     * The Authenticable instance.
     *
     * @var Authenticable
     */
    protected $auth;

    /**
     * Create a new Client instance.
     *
     * @param Authenticable $auth
     */
    public function __construct(Authenticable $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Send a GET request to the Tumblr API.
     *
     * @param  string  $path
     * @param  array   $params
     * @return stdClass
     */
    public function get($path, array $params = [])
    {
        $httpClient = $this->getHttpClient();

        if (array_key_exists('api_key', $params) and $params['api_key'] === true) {
            $params['api_key'] = $this->auth->consumerKey;
        }

        $response = $httpClient->request('get', $path, $params);

        return $this->parseResponse($response);
    }

    /**
     * Send a POST request to the Tumblr API.
     *
     * @param  string  $path
     * @param  array   $params
     * @return void
     */
    public function post($path, array $params = [])
    {
        $httpClient = $this->getHttpClient();

        $httpClient->request('post', $path, $params);
    }

    /**
     * Parse the raw response.
     *
     * @param  string  $raw
     * @return stdClass
     */
    protected function parseResponse($raw)
    {
        return json_decode($raw)->response;
    }
}
