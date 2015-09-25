<?php

namespace MatheusMariano\Tumblr;

use MatheusMariano\Tumblr\Connector\HttpClient;

trait HttpClientTrait
{
    /**
     * Create a new HTTP Client instance.
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return new HttpClient($this->auth, self::BASE_URI);
    }
}
