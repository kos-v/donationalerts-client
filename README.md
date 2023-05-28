# DONATIONALERTS API Client
**Status:** In development of the first stable version

## Table of Contents
- [OAuth 2.0](#oauth-2.0)
    - [Authorization](#authorization)
    - [Request access token by authorize code grant](#request-access-token-by-authorize-code-grant)
    - [Refresh Access Token](#refresh-access-token)
- [API](#api)  
    - [User](#user)
      - [User Profile Information](#user-profile-information)
    - [Alerts](#alerts)
      - [Donation List](#donation-list)
      - [Create Custom Alert](#crate-custom-alert)
- [Other documentation resources](#other-documentation-resources)    

    
## OAuth 2.0

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

## API
To access the API, create an object of the `Kosv\DonationalertsClient\API\Api` class:
```php
use Kosv\DonationalertsClient\API\Api;
use Kosv\DonationalertsClient\API\Config as ApiConfig;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;

/** @var AccessToken $accessToken */

$apiConfig = new ApiConfig($accessToken);
$api = new Api($apiConfig);
```


#### User
##### User Profile Information
Action: `GET https://www.donationalerts.com/api/v1/user/oauth`  
Code example:
```php
/** @var Kosv\DonationalertsClient\API\Api $api */
$profileInfo = $api->v1()->user()->getProfileInfo();
$profileInfo->getAvatar();
$profileInfo->getCode();
$profileInfo->getEmail();
$profileInfo->getId();
$profileInfo->getName();
$profileInfo->getSocketConnectionToken();
```

#### Alerts
##### Donation List
Action: `GET https://www.donationalerts.com/api/v1/alerts/donations`  
Code example:
```php
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\Donations\GetListItem;
/** @var Kosv\DonationalertsClient\API\Api $api */

$page = 1;

// Get a list of all donation starting from page $page
$donations = $api->v1()->alerts()->donations()->getList($page)->getAll();

// Get a list of donation from the $page page
$donations = $api->v1()->alerts()->donations()->getList($page)->getAllOfPage();

// Lazy loading a list of donation via an iterator, starting from page \$page
foreach ($api->v1()->alerts()->donations()->getList($page) as $donation) {
    ...
}

// Getting donation data
/** @var GetListItem $donation */
$donation->getId();
$donation->getName();
$donation->getAmount();
$donation->getUsername()
$donation->getCurrency();
$donation->getMessage();
$donation->getMessageType();
$donation->getIsShown();
$donation->getShownAt();
$donation->getCreatedAt();
```

##### Create Custom Alert
Action: `https://www.donationalerts.com/api/v1/custom_alert`  
Code example:
```php
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\CreateCustom as CreateCustomPayload;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\CreateCustom as CreateCustomResource;

/** @var Kosv\DonationalertsClient\API\Api $api */

$createCustomResource = $api->v1()->alerts()->createCustom(new CreateCustomPayload([
    CreateCustomPayload::F_EXTERNAL_ID => '12',
    CreateCustomPayload::F_HEADER => 'User',
    CreateCustomPayload::F_MESSAGE => 'Test message',
    CreateCustomPayload::F_IS_SHOWN => 0,
    CreateCustomPayload::F_IMAGE_URL => 'http://example.local/image.png',
    CreateCustomPayload::F_SOUND_URL => 'http://example.local/audio.ogg',
]));

$createCustomResource->getId();
$createCustomResource->getExternalId();
$createCustomResource->getHeader();
$createCustomResource->getMessage();
$createCustomResource->getIsShown();
$createCustomResource->getImageUrl();
$createCustomResource->getSoundUrl();
$createCustomResource->getShownAt();
$createCustomResource->getCreatedAt();
```

## Other documentation resources
[Official API documentation](https://www.donationalerts.com/apidoc)