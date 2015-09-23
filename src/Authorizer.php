<?php

namespace MatheusMariano\Tumblr;

use Psr\Http\Message\ResponseInterface;

class Authorizer
{
    /**
     * Base URI.
     */
    const BASE_URI = 'https://www.tumblr.com/oauth/';

    /**
     * The Authenticable instance.
     *
     * @var Connector\Auth\Authenticable
     */
    protected $auth;

    /**
     * Create a new Authorizer instance.
     *
     * @param Connector\Auth\Authenticable $auth
     */
    public function __construct(Connector\Auth\Authenticable $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Send a request to get temporary tokens.
     *
     * @param  string  $callback
     * @return ResponseInterface
     */
    public function getTemporaryTokens($callback)
    {
        $httpClient = $this->getHttpClient();

        $response = $httpClient->request('post', 'request_token', [
            'oauth_callback' => $callback
        ]);

        return $this->parseResponse($response);
    }

    /**
     * Send a request to get the definitive tokens.
     *
     * @param  string  $verifier
     * @return ResponseInterface
     */
    public function getTokens($verifier)
    {
        $httpClient = $this->getHttpClient();

        $response = $httpClient->request('post', 'access_token', [
            'oauth_verifier' => $verifier
        ]);

        return $this->parseResponse($response);
    }

    /**
     * Parse the raw response.
     *
     * @param  ResponseInterface  $response
     * @return array
     */
    protected function parseResponse(ResponseInterface $response)
    {
        parse_str((string) $response->getBody(), $tokens);

        return $tokens;
    }

    /**
     * Create a new HTTP Client instance.
     *
     * @return Connector\HttpClient
     */
    public function getHttpClient()
    {
        return new Connector\HttpClient($this->auth, self::BASE_URI);
    }
}