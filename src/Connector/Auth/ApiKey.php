<?php

namespace MatheusMariano\Tumblr\Connector\Auth;

class ApiKey implements Authenticable
{
    /**
     * The consumer key.
     *
     * @var string
     */
    public $consumerKey;

    /**
     * Create a new ApiKey instance.
     *
     * @param string $consumerKey
     */
    public function __construct($consumerKey)
    {
        $this->consumerKey = $consumerKey;
    }

    /**
     * Get the HTTP client auth method.
     *
     * @return string
     */
    public function getAuth()
    {
        return '';
    }

    /**
     * Get a configured HandlerStack for the HTTP client.
     *
     * @return GuzzleHttp\HandlerStack
     */
    public function getHandler()
    {
        return \GuzzleHttp\HandlerStack::create();
    }
}
