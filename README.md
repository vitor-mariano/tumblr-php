# PHP Tumblr Client

[![Build Status](https://travis-ci.org/matheusmariano/tumblr-php.svg)](https://travis-ci.org/matheusmariano/tumblr-php)

An unofficial PHP client for the [Tumblr API](https://www.tumblr.com/docs/en/api/v2)

## Installing
Through the [Composer](https://getcomposer.org/), you must require the package `matheusmariano/tumblr`.
## Using
``` php
use MatheusMariano\Tumblr\Client;
use MatheusMariano\Tumblr\Connector\Auth\ApiKey;

$auth = new ApiKey('my-api-key');
$client = new Client($auth);

$object = $client->get('blog/nutright.tumblr.com/posts', [
    'api_key' => true,
    'tag' => 'fruit'
]);
```
### Authentication
Before request any method from API, it's necessary to authenticate our client. To do it, there are two authentication levels:

- API key
- OAuth

The API key level is the simplest one, because it just needs the **consumer key** given by the Tumblr when [registering your application](https://www.tumblr.com/oauth/apps). To use this, you should import the `ApiKey` class
``` php
use MatheusMariano\Tumblr\Connector\Auth\ApiKey;
```
and then instantiate it with your **consumer key**.
``` php
$auth = new ApiKey('your-consumer-key');
```
You can use the OAuth level practically the same way, importing the `OAuth` class
``` php
use MatheusMariano\Tumblr\Connector\Auth\OAuth;
```
and then instantiating with all the necessary keys.
``` php
$auth = new OAuth; // Also accepts ordered parameters.
$auth->consumerKey = '...';
$auth->consumerSecret = '...';
$auth->oauthToken = '...';
$auth->oauthTokenSecret = '...';
```
### OAuth tokens and Authorizer
Getting the tokens from users is a little bit different task, because they need to be notified and give authorization to your application. It's a proccess that involves a lot of steps, but the `Authorizer` class turns everything easier. For every used page, you should import the class this way.
``` php
use MatheusMariano\Tumblr\Authorizer;
```
The first step is to send your consumers to Tumblr with your callback URI. Let's consider it should be `https://example.com/auth/tumblr/callback`.
``` php
$auth = new OAuth;
$auth->consumerKey = '...';
$auth->consumerSecret = '...';

$authorizer = new Authorizer($auth);
$tokens = $authorizer->getTemporaryTokens('https://example.com/auth/tumblr/callback');
```
If consumers are accepted, you should receive temporary tokens.
``` php
['oauth_token' => '...', 'oauth_token_secret' => '...']
```
Save these tokens, because they are necessary for the next session. Now you need to redirect your users to `https://www.tumblr.com/oauth/authorize?oauth_token={$tokens['oauth_token']}`. There, they are going to be able to authorize your application and then going to be redirected to the callback URI.

In the `https://example.com/auth/tumblr/callback`, the step is to send the consumers and the temporary tokens together with GET parameter `oauth_verifier` received from Tumblr.
``` php
$auth = new OAuth;
$auth->consumerKey = '...';
$auth->consumerSecret = '...';
$auth->oauthToken = $oauthToken;
$auth->oauthTokenSecret = $oauthTokenSecret;

$authorizer = new Authorizer($auth);
$tokens = $authorizer->getTokens($oauthVerifier);
```
If you prefer, you can use the global `$_GET` to get the `oauth_verifier`.
``` php
$oauthVerifier = $_GET['oauth_verifier'];
```
If everything runs as plained, you should receive the user definitive tokens.
### Client
After configure one of those authenticators, you can import the `Client` class
``` php
use MatheusMariano\Tumblr\Client;
```
and then instantiate it with the authenticator.
``` php
$client = new Client($auth);
```
### Methods
In the version `0.1` of this package, the `Client` has only 2 very basic methods

- `get`
- `post`

It's important to follow the [Tumblr API](https://www.tumblr.com/docs/en/api/v2) to use these methods and your responses correctly.

Example: getting the [text posts](https://www.tumblr.com/docs/en/api/v2#text-posts) that has the `fruit` tag.
``` php
$object = $client->get('blog/nutright.tumblr.com/posts/text', [
    'api_key' => true,
    'tag' => 'fruit',
]);
```
The response should be an `stdClass` object with all content of `response`, following the [Tumblr API](https://www.tumblr.com/docs/en/api/v2).
``` php
$object->total_posts; // int
$object->posts; // array
$object->blog; // stdClass
$object->blog->title; // string
```
The `post` method works the same way.
``` php
$client->post('blog/nutright.tumblr.com/post', [
    'type' => 'text',
    'tags' => 'fruit, apple, red',
    'title' => 'My new post title',
    'body' => 'My new post body...',
]);
```
### Exceptions
Request methods may receive errors, generaly `401 not authorized` and `404 not found`, throwing exceptions like `GuzzleHttp\Exception\ClientException`, `GuzzleHttp\Exception\ServerException` etc., which should be treated with `try...catch`. See the [Guzzle documentation](http://docs.guzzlephp.org/en/latest/quickstart.html#exceptions) for more information.
``` php
try {
    $client->get('blog/nutright.tumblr.com/followers', ['api_key' => true]);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    // Do something
}
```
## License
PHP Tumblr Client a is open-sourced software licensed under the MIT license.
