<?php

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use MatheusMariano\Tumblr\Connector\HttpClient;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;

class HttpClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $auth = Mockery::mock(Authenticable::class);
        $auth->shouldReceive('getAuth')->once();
        $auth->shouldReceive('getHandler')->once();

        $this->httpClient = Mockery::mock(
            HttpClient::class . '[getClient]',
            [$auth, 'https://example.com']
        );

        $this->httpClient
            ->shouldReceive('getClient')
            ->once()
            ->andReturn($this->client = Mockery::mock(Client::class));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetRequest()
    {
        $this->client
            ->shouldReceive('request')
            ->with('get', 'path', ['query' => ['foo' => 'bar']])
            ->once()
            ->andReturn($response = Mockery::mock(ResponseInterface::class));

        $response->shouldReceive('getBody')->once()->andReturn('raw');

        $string = $this->httpClient->request('get', 'path', ['foo' => 'bar']);

        $this->assertEquals('raw', $string);
    }

    public function testPostRequest()
    {
        $this->client
            ->shouldReceive('request')
            ->with('post', 'path', ['form_params' => ['foo' => 'bar']])
            ->once()
            ->andReturn($response = Mockery::mock(ResponseInterface::class));

        $response->shouldReceive('getBody')->once()->andReturn('raw');

        $string = $this->httpClient->request('post', 'path', ['foo' => 'bar']);

        $this->assertEquals('raw', $string);
    }

    public function testRequestWithUppercaseHttpMethod()
    {
        $this->client
            ->shouldReceive('request')
            ->with('GET', 'path', ['query' => []])
            ->once()
            ->andReturn($response = Mockery::mock(ResponseInterface::class));

        $response->shouldReceive('getBody')->once()->andReturn('raw');

        $string = $this->httpClient->request('GET', 'path');

        $this->assertEquals('raw', $string);
    }
}
