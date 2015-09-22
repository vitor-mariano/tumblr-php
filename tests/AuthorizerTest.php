<?php

use Psr\Http\Message\ResponseInterface;
use MatheusMariano\Tumblr\Authorizer;
use MatheusMariano\Tumblr\Connector\HttpClient;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;

class AuthorizerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetTemporaryTokens()
    {
        $auth = Mockery::mock(Authenticable::class);

        $authorizer = Mockery::mock(Authorizer::class . '[getHttpClient]', [$auth]);
        $authorizer
            ->shouldReceive('getHttpClient')
            ->once()
            ->andReturn($httpClient = Mockery::mock(HttpClient::class));

        $httpClient
            ->shouldReceive('request')
            ->with('post', 'request_token', ['oauth_callback' => 'https://example.com/callback'])
            ->once()
            ->andReturn($response = Mockery::mock(ResponseInterface::class));

        $response
            ->shouldReceive('getBody')
            ->once()
            ->andReturn('foo=bar&baz=inga');

        $tokens = $authorizer->getTemporaryTokens('https://example.com/callback');

        $this->assertEquals(['foo' => 'bar', 'baz' => 'inga'], $tokens);
    }

    public function testGetTokens()
    {
        $auth = Mockery::mock(Authenticable::class);

        $authorizer = Mockery::mock(Authorizer::class . '[getHttpClient]', [$auth]);
        $authorizer
            ->shouldReceive('getHttpClient')
            ->once()
            ->andReturn($httpClient = Mockery::mock(HttpClient::class));

        $httpClient
            ->shouldReceive('request')
            ->with('post', 'access_token', ['oauth_verifier' => 'verifier'])
            ->once()
            ->andReturn($response = Mockery::mock(ResponseInterface::class));

        $response
            ->shouldReceive('getBody')
            ->once()
            ->andReturn('foo=bar&baz=inga');

        $tokens = $authorizer->getTokens('verifier');

        $this->assertEquals(['foo' => 'bar', 'baz' => 'inga'], $tokens);
    }
}
