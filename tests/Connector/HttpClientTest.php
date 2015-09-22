<?php

use GuzzleHttp\Client;
use MatheusMariano\Tumblr\Connector\OAuth;
use MatheusMariano\Tumblr\Connector\HttpClient;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testIntanceMethodWithoutOAuth()
    {
        $oauth = Mockery::mock(OAuth::class);
        $oauth->shouldNotReceive('getHandler');
        
        $httpClient = new HttpClient('https://example.com/');
    }

    public function testIntanceMethodWithOAuth()
    {
        $oauth = Mockery::mock(OAuth::class);
        $oauth->shouldReceive('getHandler')->once();

        $httpClient = new HttpClient('https://example.com/', $oauth);
    }

    public function testRequestMethodWithoutParameters()
    {
        $httpClient = Mockery::mock(
            HttpClient::class . '[getClient]',
            ['https://example.com']
        );

        $httpClient
            ->shouldReceive('getClient')
            ->once()
            ->andReturn($client = Mockery::mock(Client::class));

        $client
            ->shouldReceive('request')
            ->with('get', 'path', ['form_params' => []])
            ->once();

        $httpClient->request('get', 'path');
    }

    public function testRequestMethodWithParameters()
    {
        $httpClient = Mockery::mock(
            HttpClient::class . '[getClient]',
            ['https://example.com']
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
