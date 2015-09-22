<?php

namespace MatheusMariano\Tumblr\Connector\Auth;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1 as Middleware;

class OAuth implements Authenticable
{
    /**
     * The Consumer Key.
     *
     * @var string
     */
    public $consumerKey;

    /**
     * The Consumer Secret.
     *
     * @var string
     */
    public $consumerSecret;

    /**
     * The OAuth Token.
     *
     * @var string
     */
    public $oauthToken;

    /**
     * The OAuth Token Secret.
     *
     * @var string
     */
    public $oauthTokenSecret;

    /**
     * Create a new OAuth instance.
     *
     * @param string $ck
     * @param string $cs
     * @param string $tk
     * @param string $ts
     */
    public function __construct($ck, $cs, $tk = '', $ts = '')
    {
        $this->consumerKey = $ck;
        $this->consumerSecret = $cs;
        $this->oauthToken = $tk;
        $this->oauthTokenSecret = $ts;
    }

    /**
     * Get the HTTP client auth method.
     *
     * @return string
     */
    public function getAuth()
    {
        return 'oauth';
    }

    /**
     * Get a configured HandlerStack for the HTTP client.
     *
     * @return GuzzleHttp\HandlerStack
     */
    public function getHandler()
    {
        $stack = HandlerStack::create();

        $middleware = new Middleware([
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret,
            'token' => $this->oauthToken,
            'token_secret' => $this->oauthTokenSecret,
        ]);

        $stack->push($middleware);

        return $stack;
    }
}
