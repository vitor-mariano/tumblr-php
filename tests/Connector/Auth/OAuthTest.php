<?php

use MatheusMariano\Tumblr\Connector\Auth\OAuth;

class OAuthTest extends PHPUnit_Framework_TestCase
{
    public function testInstanceMethod()
    {
        $oauth = new OAuth('key', 'secret', 'token', 'tokensecret');

        $this->assertEquals('key', $oauth->consumerKey);
        $this->assertEquals('secret', $oauth->consumerSecret);
        $this->assertEquals('token', $oauth->oauthToken);
        $this->assertEquals('tokensecret', $oauth->oauthTokenSecret);
    }

    public function testGetAuth()
    {
        $oauth = new OAuth('key', 'secret');

        $this->assertEquals('oauth', $oauth->getAuth());
    }

    public function testGetHandler()
    {
        $oauth = new OAuth('key', 'secret');

        $this->assertInstanceOf('GuzzleHttp\HandlerStack', $oauth->getHandler());
    }
}
