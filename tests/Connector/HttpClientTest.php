<?php

use GuzzleHttp\Client;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;
use MatheusMariano\Tumblr\Connector\HttpClient;

class HttpClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->auth = Mockery::mock(Authenticable::class);
        $this->auth->shouldReceive('getAuth')->once();
        $this->auth->shouldReceive('getHandler')->once();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testIntanceMethod()
    {
        $httpClient = new HttpClient($this->auth, 'https://example.com/');
    }

    public function testGetRequestMethodWithoutParameters()
    {
        $httpClient = Mockery::mock(
            HttpClient::class . '[getClient]',
            [$this->auth, 'https://example.com']
        );

        $httpClient
            ->shouldReceive('getClient')
            ->once()
            ->andReturn($client = Mockery::mock(Client::class));

        $client
            ->shouldReceive('request')
            ->with('get', 'path', ['query' => []])
            ->once();

        $httpClient->request('get', 'path');
    }

    public function testPostRequestMethodWithParameters()
    {
        $httpClient = Mockery::mock(
            HttpClient::class . '[getClient]',
            [$this->auth, 'https://example.com']
        );

        $httpClient
            ->shouldReceive('getClient')
            ->once()
            ->andReturn($client = Mockery::mock(Client::class));

        $client
            ->shouldReceive('request')
            ->with('post', 'path', ['form_params' => ['foo' => 'bar']])
            ->once();

        $httpClient->request('post', 'path', ['foo' => 'bar']);
    }
}
