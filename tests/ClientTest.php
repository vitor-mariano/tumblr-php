<?php

use MatheusMariano\Tumblr\Client;
use MatheusMariano\Tumblr\Connector\HttpClient;
use MatheusMariano\Tumblr\Connector\Auth\Authenticable;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $auth = Mockery::mock(Authenticable::class);
        $auth->consumerKey = 'fakecs';

        $this->client = Mockery::mock(Client::class . '[getHttpClient]', [$auth]);
        $this->client
            ->shouldReceive('getHttpClient')
            ->once()
            ->andReturn($this->httpClient = Mockery::mock(HttpClient::class));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetMethod()
    {
        $this->httpClient
            ->shouldReceive('request')
            ->with('get', 'blog/name/action', ['foo' => 'bar'])
            ->once()
            ->andReturn(json_encode(['response' => ['baz' => 'inga']]));

        $response = $this->client->get('blog/name/action', ['foo' => 'bar']);

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals('inga', $response->baz);
    }

    public function testGetMethodWithApiKey()
    {
        $this->httpClient
            ->shouldReceive('request')
            ->with('get', 'blog/name/action', ['api_key' => 'fakecs'])
            ->once()
            ->andReturn(json_encode(['response' => ['baz' => 'inga']]));

        $response = $this->client->get('blog/name/action', ['api_key' => true]);

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals('inga', $response->baz);
    }

    public function testPostMethod()
    {
        $this->httpClient
            ->shouldReceive('request')
            ->with('post', 'blog/name/action', ['foo' => 'bar'])
            ->once();

        $this->client->post('blog/name/action', ['foo' => 'bar']);
    }
}
