<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\OAuth;

use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\OAuth\ClientAuthorizeRequest;
use Kosv\DonationalertsClient\OAuth\Config;
use Kosv\DonationalertsClient\Exceptions\OAuth\AccessDeniedException;
use Kosv\DonationalertsClient\Exceptions\OAuth\Exception;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use Kosv\DonationalertsClient\Exceptions\OAuth\InvalidAccessTokenRequestException;
use Kosv\DonationalertsClient\Exceptions\OAuth\ServerException;
use Kosv\DonationalertsClient\OAuth\Enums\GrantTypeEnum;
use Kosv\DonationalertsClient\OAuth\Enums\ScopeEnum;
use Kosv\DonationalertsClient\OAuth\OAuth;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;
use Kosv\DonationalertsClient\Transport\Response;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use const JSON_THROW_ON_ERROR;

final class OAuthTest extends TestCase
{
    public function testCompleteAuthorizeWhenAccessDeniedByImplicitGrant(): void
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('OAuth error: access_denied');

        $oauth = new OAuth($this->makeOAuthConfig());
        $oauth->completeAuthorize(new ClientAuthorizeRequest('#error=access_denied&state=foo'));
    }

    public function testCompleteAuthorizeWhenHaveErrorByImplicitGrant(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Request contains error. Error: foo_error');

        $oauth = new OAuth($this->makeOAuthConfig());
        $oauth->completeAuthorize(new ClientAuthorizeRequest('#error=foo_error&state=bar'));
    }

    public function testCompleteAuthorizeWhenValidationErrorByImplicitGrant(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Request not contains required fields. No fields: expires_in, token_type');

        $oauth = new OAuth($this->makeOAuthConfig());
        $oauth->completeAuthorize(new ClientAuthorizeRequest('#access_token=abc'));
    }

    public function testCompleteAuthorizeOkByImplicitGrant(): void
    {
        $oauth = new OAuth($this->makeOAuthConfig());
        $token = $oauth->completeAuthorize(new ClientAuthorizeRequest('#access_token=abc&token_type=Bearer&expires_in=661253000'));

        $this->assertEquals('abc', $token->getToken());
        $this->assertEquals(661253000, $token->getExpirationTime());
        $this->assertEquals('Bearer', $token->getType());
    }

    public function testCompleteAuthorizeByAccessTokenGrant(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response(json_encode([
            'token_type' => 'Bearer',
            'access_token' => 'qwerty123',
            'expires_in' => 631151999,
            'refresh_token' => 'qwerty456'
        ], JSON_THROW_ON_ERROR), 200));

        $oauth = new OAuth($this->makeOAuthConfig(), $transport);
        $token = $oauth->completeAuthorize(new ClientAuthorizeRequest('#code=abc'));

        $this->assertEquals('qwerty123', $token->getToken());
        $this->assertEquals('qwerty456', $token->getRefreshToken());
        $this->assertEquals(631151999, $token->getExpirationTime());
        $this->assertEquals('Bearer', $token->getType());
    }

    public function testCompleteAuthorizeWhenNotHaveGrantType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Bad request. Not specified grant type');

        $oauth = new OAuth($this->makeOAuthConfig());
        $oauth->completeAuthorize(new ClientAuthorizeRequest('#foo=bar'));
    }

    public function testMakeAuthorizeUrl(): void
    {
        $oauth = new OAuth($this->makeOAuthConfig());

        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=https%3A%2F%2Fhost.loc%2Foauth%2Fcomplete&" .
            "response_type=token&" .
            "scope=oauth-user-show",
            $oauth->makeAuthorizeUrl(GrantTypeEnum::IMPLICIT, [
                ScopeEnum::USER_SHOW,
            ])
        );

        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=https%3A%2F%2Fhost.loc%2Foauth%2Fcomplete&" .
            "response_type=code&" .
            "scope=oauth-user-show",
            $oauth->makeAuthorizeUrl(GrantTypeEnum::AUTHORIZATION_CODE, [
                ScopeEnum::USER_SHOW,
            ])
        );
    }

    public function testRefreshAccessTokenWhenOk(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response(json_encode([
            'token_type' => 'Bearer',
            'access_token' => 'asdfg123',
            'expires_in' => 631152000,
            'refresh_token' => 'asdfg456'
        ], JSON_THROW_ON_ERROR), 200));

        $oauth = new OAuth($this->makeOAuthConfig(), $transport);
        $token = $oauth->refreshAccessToken(new AccessToken(
            'qwerty123',
            631151999,
            'Bearer',
            'asdfg456'
        ));

        $this->assertEquals('asdfg123', $token->getToken());
        $this->assertEquals('asdfg456', $token->getRefreshToken());
        $this->assertEquals(631152000, $token->getExpirationTime());
        $this->assertEquals('Bearer', $token->getType());
    }

    public function testRefreshAccessTokenWhenRefreshTokenEmpty(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response('{}', 200));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage(AccessToken::class . ' object not contains refresh token');
        $oauth->refreshAccessToken(new AccessToken(
            'qwerty123',
            631151999,
            'Bearer'
        ));
    }

    public function testRefreshAccessTokenWhenResponseNotJson(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response('<html></html>', 200));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The server returned response in not json format. Response: <html></html>');
        $oauth->refreshAccessToken(new AccessToken(
            'qwerty123',
            631151999,
            'Bearer',
            'asdfg456'
        ));
    }

    public function testRefreshAccessTokenWhenResponseContainsOtherStatusCode(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response('{"error": "server error"}', 500));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('Unexpected response code. Response: {"error": "server error"}');
        $oauth->refreshAccessToken(new AccessToken(
            'qwerty123',
            631151999,
            'Bearer',
            'asdfg456'
        ));
    }

    public function testRefreshAccessTokenWhenInvalidAccessToken(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response(json_encode([
            'error' => 'invalid_request',
            'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.',
            'hint' => 'Refresh token has expired',
            'message' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.'
        ], JSON_THROW_ON_ERROR), 400));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(InvalidAccessTokenRequestException::class);
        $this->expectExceptionMessage('Refresh token has expired');
        $oauth->refreshAccessToken(new AccessToken(
            'qwerty123',
            631151999,
            'Bearer',
            'asdfg456'
        ));
    }

    public function testRequestAccessTokenWhenOk(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response(json_encode([
            'token_type' => 'Bearer',
            'access_token' => 'qwerty123',
            'expires_in' => 631151999,
            'refresh_token' => 'qwerty456'
        ], JSON_THROW_ON_ERROR), 200));

        $oauth = new OAuth($this->makeOAuthConfig(), $transport);
        $token = $oauth->requestAccessToken('abc');

        $this->assertEquals('qwerty123', $token->getToken());
        $this->assertEquals('qwerty456', $token->getRefreshToken());
        $this->assertEquals(631151999, $token->getExpirationTime());
        $this->assertEquals('Bearer', $token->getType());
    }

    public function testRequestAccessTokenWhenResponseNotJson(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response('<html></html>', 200));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The server returned response in not json format. Response: <html></html>');
        $oauth->requestAccessToken('abc');
    }

    public function testRequestAccessTokenWhenResponseContainsOtherStatusCode(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response('{"error": "server error"}', 500));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('Unexpected response code. Response: {"error": "server error"}');
        $oauth->requestAccessToken('abc');
    }

    public function testRequestAccessTokenWhenInvalidAccessToken(): void
    {
        $transport = $this->createMock(TransportClient::class);
        $transport->method('post')->willReturn(new Response(json_encode([
            'error' => 'invalid_request',
            'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.',
            'hint' => 'Authorization code has expired',
            'message' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.'
        ], JSON_THROW_ON_ERROR), 400));
        $oauth = new OAuth($this->makeOAuthConfig(), $transport);

        $this->expectException(InvalidAccessTokenRequestException::class);
        $this->expectExceptionMessage('Authorization code has expired');
        $oauth->requestAccessToken('abc');
    }

    private function makeOAuthConfig(): Config
    {
        return new Config(
            9999999,
            '123456',
            'https://host.loc/oauth/complete'
        );
    }
}
