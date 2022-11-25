<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\OAuth;

use Kosv\DonationalertsClient\OAuth\ClientAuthorizeRequest;
use PHPUnit\Framework\TestCase;

final class ClientAuthorizeRequestTest extends TestCase
{
    private const BASE_URL = 'https://host.loc/oauth/complete';

    public function testGetAccessToken(): void
    {
        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=Bearer&expires_in=661253000');
        $this->assertEquals('abc', $request1->getAccessToken());

        $request2 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=&token_type=Bearer&expires_in=661253000');
        $this->assertEquals('', $request2->getAccessToken());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token&token_type=Bearer&expires_in=661253000');
        $this->assertEquals('', $request3->getAccessToken());

        $request4 = new ClientAuthorizeRequest(self::BASE_URL . '#token_type=Bearer&expires_in=661253000');
        $this->assertNull($request4->getAccessToken());

        $request5 = new ClientAuthorizeRequest('#access_token=abc&token_type=Bearer&expires_in=661253000');
        $this->assertEquals('abc', $request5->getAccessToken());
    }

    public function testGetAccessTokenExpirationTime(): void
    {
        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=Bearer&expires_in=661253000');
        $this->assertEquals(661253000, $request1->getAccessTokenExpirationTime());

        $request2 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=Bearer&expires_in=');
        $this->assertEquals(0, $request2->getAccessTokenExpirationTime());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=Bearer&expires_in');
        $this->assertEquals(0, $request3->getAccessTokenExpirationTime());

        $request4 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=Bearer');
        $this->assertNull($request4->getAccessTokenExpirationTime());

        $request5 = new ClientAuthorizeRequest('#access_token=abc&token_type=Bearer&expires_in=661253000');
        $this->assertEquals(661253000, $request5->getAccessTokenExpirationTime());
    }

    public function testGetAccessTokenType(): void
    {
        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=Bearer&expires_in=661253000');
        $this->assertEquals('Bearer', $request1->getAccessTokenType());

        $request2 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type=&expires_in=661253000');
        $this->assertEquals('', $request2->getAccessTokenType());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&token_type&expires_in=661253000');
        $this->assertEquals('', $request3->getAccessTokenType());

        $request4 = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc&expires_in=661253000');
        $this->assertNull($request4->getAccessTokenType());

        $request5 = new ClientAuthorizeRequest('#access_token=abc&token_type=Bearer&expires_in=661253000');
        $this->assertEquals('Bearer', $request5->getAccessTokenType());
    }

    public function testGetAuthorizeCode(): void
    {
        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '?code=abc');
        $this->assertEquals('abc', $request1->getAuthorizeCode());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '?code=');
        $this->assertEquals('', $request3->getAuthorizeCode());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '?code');
        $this->assertEquals('', $request3->getAuthorizeCode());

        $request4 = new ClientAuthorizeRequest(self::BASE_URL . '?foo=bar');
        $this->assertNull($request4->getAuthorizeCode());

        $request5 = new ClientAuthorizeRequest('?code=abc');
        $this->assertEquals('abc', $request5->getAuthorizeCode());
    }

    public function testGetError(): void
    {
        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '#error=access_denied&state=foo');
        $this->assertEquals('access_denied', $request1->getError());

        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '?error=access_denied&state=foo');
        $this->assertEquals('access_denied', $request1->getError());

        $request2 = new ClientAuthorizeRequest(self::BASE_URL . '#error=&state=foo');
        $this->assertEquals('', $request2->getError());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '#error&state=foo');
        $this->assertEquals('', $request3->getError());

        $request4 = new ClientAuthorizeRequest(self::BASE_URL . '#state=foo');
        $this->assertNull($request4->getError());
    }

    public function testGetState(): void
    {
        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '#error=access_denied&state=foo');
        $this->assertEquals('foo', $request1->getState());

        $request1 = new ClientAuthorizeRequest(self::BASE_URL . '?error=access_denied&state=foo');
        $this->assertEquals('foo', $request1->getState());

        $request2 = new ClientAuthorizeRequest(self::BASE_URL . '#error=access_denied&state=');
        $this->assertEquals('', $request2->getState());

        $request3 = new ClientAuthorizeRequest(self::BASE_URL . '#error=access_denied&state');
        $this->assertEquals('', $request3->getState());

        $request4 = new ClientAuthorizeRequest(self::BASE_URL . '#error=access_denied');
        $this->assertNull($request4->getState());
    }

    public function testIsisAuthorizeCodeGrant(): void
    {
        $request = new ClientAuthorizeRequest(self::BASE_URL . '?code=abc');
        $this->assertTrue($request->isAuthorizeCodeGrant());
        $this->assertFalse($request->isImplicitGrant());
    }

    public function testIsImplicitGrant(): void
    {
        $request = new ClientAuthorizeRequest(self::BASE_URL . '#access_token=abc');
        $this->assertTrue($request->isImplicitGrant());
        $this->assertFalse($request->isAuthorizeCodeGrant());
    }

    public function testIsGrantTypeWhenNoData(): void
    {
        $request = new ClientAuthorizeRequest(self::BASE_URL);
        $this->assertFalse($request->isAuthorizeCodeGrant());
        $this->assertFalse($request->isImplicitGrant());
    }

    public function testIsGrantTypeWhenBothTypesGiven(): void
    {
        $request = new ClientAuthorizeRequest(self::BASE_URL . '?code=abc&access_token=abc');
        $this->assertTrue($request->isAuthorizeCodeGrant());
        $this->assertTrue($request->isImplicitGrant());
    }
}
