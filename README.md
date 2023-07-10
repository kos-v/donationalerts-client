# DONATIONALERTS API Client
**Status:** In development of the first stable version

## Table of Contents
- [OAuth 2.0](#oauth-2.0)
    - [Authorization](#authorization)
    - [Request access token by authorize code grant](#request-access-token-by-authorize-code-grant)
    - [Refresh access token](#refresh-access-token)
- [API](#api)  
    - [Users](#users)
      - [User profile information](#user-profile-information)
    - [Alerts](#alerts)
      - [Create custom alert](#create-custom-alert)
    - [Donations](#donations)
      - [Get list](#get-list)
    - [Merchandises](#merchandises)
      - [Create](#create)
      - [Update](#update)
- [References](#references)    

    
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


#### Users
##### User profile information
Action: `GET https://www.donationalerts.com/api/v1/user/oauth`  
Code example:
```php
use Kosv\DonationalertsClient\API\Resources\V1\Users\ProfileInfo as Resource;

/** 
 * @var Kosv\DonationalertsClient\API\Api $api
 * @var Resource $resource
 */
$resource = $api->v1()->users()->getProfileInfo();
$resource->getAvatar();
$resource->getCode();
$resource->getEmail();
$resource->getId();
$resource->getName();
$resource->getSocketConnectionToken();
```

#### Alerts
##### Create custom alert
Action: `POST https://www.donationalerts.com/api/v1/custom_alert`  
Code example:
```php
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\CreateCustom as Payload;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\CreateCustom as Resource;

/** 
 * @var Kosv\DonationalertsClient\API\Api $api
 * @var Resource $resource
 */

$resource = $api->v1()->alerts()->createCustom(new Payload([
    Payload::F_EXTERNAL_ID => '12',
    Payload::F_HEADER => 'User',
    Payload::F_MESSAGE => 'Test message',
    Payload::F_IS_SHOWN => 0,
    Payload::F_IMAGE_URL => 'http://example.local/image.png',
    Payload::F_SOUND_URL => 'http://example.local/audio.ogg',
]));

$resource->getId();
$resource->getExternalId();
$resource->getHeader();
$resource->getMessage();
$resource->getIsShown();
$resource->getImageUrl();
$resource->getSoundUrl();
$resource->getShownAt();
$resource->getCreatedAt();
```
#### Donations
##### Get list
Action: `GET https://www.donationalerts.com/api/v1/alerts/donations`  
Code example:
```php
use Kosv\DonationalertsClient\API\Resources\V1\Donations\GetListItem;
/** @var Kosv\DonationalertsClient\API\Api $api */

$page = 1;

// Get a list of all donation starting from page $page
$donations = $api->v1()->donations()->getList($page)->getAll();

// Get a list of donation from the $page page
$donations = $api->v1()->donations()->getList($page)->getAllOfPage();

// Lazy loading a list of donation via an iterator, starting from page \$page
foreach ($api->v1()->donations()->getList($page) as $donation) {
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

#### Merchandises
##### Create
Action: `POST https://www.donationalerts.com/api/v1/merchandise`  
Code example:
```php
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Create as Payload;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\CreateUpdate as Resource;

/** 
 * @var Kosv\DonationalertsClient\API\Api $api
 * @var Resource $resource
 */

$resource = $api->v1()->merchandises()->create(new Payload([
    Payload::F_MERCHANT_IDENTIFIER => 'merchant_id',
    Payload::F_MERCHANDISE_IDENTIFIER => 'merchandise_id',
    Payload::F_TITLE => [
        LangEnum::ENGLISH_USA => 'Title',
    ],
    Payload::F_IS_ACTIVE => 1,
    Payload::F_IS_PERCENTAGE => 1,
    Payload::F_CURRENCY => CurrencyEnum::USD,
    Payload::F_PRICE_USER => 25.7,
    Payload::F_PRICE_SERVICE => 10.3,
    Payload::F_URL => 'http://example.local/product_id=merchandise_id',
    Payload::F_IMG_URL => 'http://example.local/product_id=merchandise_id/img.png',
    Payload::F_END_AT_TS => 1687357916
]));

$resource->getId();
$resource->getMerchant()->getIdentifier();
$resource->getMerchant()->getName();
$resource->getIdentifier();
$resource->getTitle()->getEnglishUsa();
$resource->getIsActive();
$resource->getIsPercentage();
$resource->getCurrency();
$resource->getPriceUser();
$resource->getPriceService();
$resource->getUrl();
$resource->getImgUrl();
$resource->getEndAt();
```

##### Update
Action: `PUT https://www.donationalerts.com/api/v1/merchandise`  
Code example:
```php
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Update as Payload;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\CreateUpdate as Resource;

/** 
 * @var Kosv\DonationalertsClient\API\Api $api
 * @var Resource $resource
 */

$merchandiseId = 99999999;
$resource = $api->v1()->merchandises()->update($merchandiseId, new Payload([
    Payload::F_PRICE_USER => 30,
    Payload::F_PRICE_SERVICE => 20.3,
    // ...
]));

$resource->getPriceUser();
$resource->getPriceService();
// ...
```
## References
[Official API documentation](https://www.donationalerts.com/apidoc)