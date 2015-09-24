<?php

use MatheusMariano\Tumblr\Authorizer;
use MatheusMariano\Tumblr\Connector\HttpClient;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;

class AuthorizerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $auth = Mockery::mock(Authenticable::class);

        $this->authorizer = Mockery::mock(Authorizer::class . '[getHttpClient]', [$auth]);
        $this->authorizer
            ->shouldReceive('getHttpClient')
            ->once()
            ->andReturn($this->httpClient = Mockery::mock(HttpClient::class));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetTemporaryTokens()
    {
        $this->httpClient
            ->shouldReceive('request')
            ->with('post', 'request_token', ['oauth_callback' => 'https://example.com/callback'])
            ->once()
            ->andReturn('foo=bar&baz=inga');

        $tokens = $this->authorizer->getTemporaryTokens('https://example.com/callback');

        $this->assertEquals(['foo' => 'bar', 'baz' => 'inga'], $tokens);
    }

    public function testGetTokens()
    {
        $this->httpClient
            ->shouldReceive('request')
            ->with('post', 'access_token', ['oauth_verifier' => 'verifier'])
            ->once()
            ->andReturn('foo=bar&baz=inga');

        $tokens = $this->authorizer->getTokens('verifier');

        $this->assertEquals(['foo' => 'bar', 'baz' => 'inga'], $tokens);
    }
}
