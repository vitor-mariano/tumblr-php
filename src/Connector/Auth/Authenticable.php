<?php

namespace MatheusMariano\Tumblr\Connector\Auth;

interface Authenticable
{
    /**
     * Get the HTTP client auth method.
     *
     * @return string|array
     */
    public function getAuth();

    /**
     * Get a configured HandlerStack for the HTTP client.
     *
     * @return GuzzleHttp\HandlerStack
     */
    public function getHandler();
}
