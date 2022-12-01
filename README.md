# DONATIONALERTS API Client
**Status:** In development of the first stable version

### Table of Contents
- [OAuth 2.0](#oauth-2.0)
    - [Authorization](#authorization)
    - [Request access token by authorize code grant](#request-access-token-by-authorize-code-grant)
    - [Refresh Access Token](#refresh-access-token)

    
### OAuth 2.0

#### Authorization
1. Create an oauth object with your configuration parameters
```php
use Kosv\DonationalertsClient\OAuth\Config as OAuthConfig;
use Kosv\DonationalertsClient\OAuth\OAuth;

$clientId = 999999;
$clientSecret = '***********************';
$redirectUri = 'https://myhost.local/oauth/complete';
$oauth = new OAuth(new OAuthConfig($clientId, $clientSecret, $redirectUri));
 
```

 2. If necessary, generate an URL for oauth-authorization on a frontend-client
```php
...
use Kosv\DonationalertsClient\OAuth\Enums\GrantTypeEnum;
use Kosv\DonationalertsClient\OAuth\Enums\ScopeEnum;

...
$grantType = GrantTypeEnum::IMPLICIT; // or GrantTypeEnum::AUTHORIZATION_CODE
$scopes = [
    ScopeEnum::CUSTOM_ALERT_STORE,
    ScopeEnum::DONATION_INDEX,
    ScopeEnum::DONATION_SUBSCRIBE,
    ScopeEnum::GOAL_SUBSCRIBE,
    ScopeEnum::POLL_SUBSCRIBE,
    ScopeEnum::USER_SHOW,
];
echo $oauth->makeAuthorizeUrl($grantType, $scopes);
```

3. After redirecting to `$redirectUri` in your controller's handler, you need to get authorize request string and pass it to `Kosv\DonationalertsClient\OAuth\ClientAuthorizeRequest`.
```php
...
use Kosv\DonationalertsClient\OAuth\ClientAuthorizeRequest;

...
$authorizeRequestString = 'https://myhost.local/oauth/complete?code=def50200a1...';
$authorizeRequest = new ClientAuthorizeRequest($authorizeRequestString);
```
If for some reason you cannot pass the authorization request string to `Kosv\DonationalertsClient\OAuth\ClientAuthorizeRequest`, then you can write your own `ClientAuthorizeRequest` implementation by implementing the `Kosv\DonationalertsClient\Contracts\OAuthClientAuthorizeRequest` interface.

4. Now you need to complete authorization and get AccessToken
```php
...
use Kosv\DonationalertsClient\Exceptions\OAuth\AccessDeniedException;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;

...
try {
    /** @var AccessToken $accessToken */
    $accessToken = $oauth->completeAuthorize($authorizeRequest);
} catch (AccessDeniedException $e) {
    die('Access denied!');
}
```

#### Request access token by authorize code grant
```php
...
$authorizeCode = 'def50200a1...';
/** @var AccessToken $accessToken */
$accessToken = $oauth->requestAccessToken($authorizeCode);
```

#### Refresh access token
```php
...
/** @var AccessToken $oldAccessToken */
$newScopes = [
    ScopeEnum::DONATION_INDEX,
    ScopeEnum::USER_SHOW,
];
$newAccessToken = $oauth->refreshAccessToken($oldAccessToken, $newScopes);
```