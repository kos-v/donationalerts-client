<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\OAuth;

use Kosv\DonationalertsClient\OAuth\AuthorizeUrlBuilder;
use Kosv\DonationalertsClient\OAuth\Enums\GrantTypeEnum;
use Kosv\DonationalertsClient\OAuth\Enums\ScopeEnum;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

final class AuthorizeUrlBuilderTest extends TestCase
{
    public function testBuildWithCorrectGrantType(): void
    {
        $builder1 = new AuthorizeUrlBuilder(GrantTypeEnum::IMPLICIT, 9999999, '', []);
        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=&" .
            "response_type=token&" .
            "scope=",
            $builder1->build()
        );

        $builder2 = new AuthorizeUrlBuilder(GrantTypeEnum::AUTHORIZATION_CODE, 9999999, '', []);
        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=&" .
            "response_type=code&" .
            "scope=",
            $builder2->build()
        );
    }

    public function testBuildWithIncorrectGrantType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported grant type "refresh_token"');

        $builder = new AuthorizeUrlBuilder(GrantTypeEnum::REFRESH_TOKEN, 9999999, '', []);
        $builder->build();
    }

    public function testBuildWithoutRedirectUriAndScopes(): void
    {
        $builder = new AuthorizeUrlBuilder(GrantTypeEnum::IMPLICIT, 9999999, '', []);
        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=&" .
            "response_type=token&" .
            "scope=",
            $builder->build()
        );
    }

    public function testBuildWithRedirectUri(): void
    {
        $builder = new AuthorizeUrlBuilder(GrantTypeEnum::IMPLICIT, 9999999, 'http://host.local/confirm', []);
        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=http%3A%2F%2Fhost.local%2Fconfirm&" .
            "response_type=token&" .
            "scope=",
            $builder->build()
        );
    }

    public function testBuildWithOneScope(): void
    {
        $builder = new AuthorizeUrlBuilder(GrantTypeEnum::IMPLICIT, 9999999, 'http://host.local/confirm', [
            ScopeEnum::USER_SHOW,
        ]);
        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=http%3A%2F%2Fhost.local%2Fconfirm&" .
            "response_type=token&" .
            "scope=oauth-user-show",
            $builder->build()
        );
    }

    public function testBuildWithAllScopes(): void
    {
        $builder = new AuthorizeUrlBuilder(GrantTypeEnum::IMPLICIT, 9999999, 'http://host.local/confirm', [
            ScopeEnum::CUSTOM_ALERT_STORE,
            ScopeEnum::DONATION_INDEX,
            ScopeEnum::DONATION_SUBSCRIBE,
            ScopeEnum::GOAL_SUBSCRIBE,
            ScopeEnum::POLL_SUBSCRIBE,
            ScopeEnum::USER_SHOW,
        ]);
        $this->assertEquals(
            "https://www.donationalerts.com/oauth/authorize?" .
            "client_id=9999999&" .
            "redirect_uri=http%3A%2F%2Fhost.local%2Fconfirm&" .
            "response_type=token&" .
            "scope=oauth-custom_alert-store+oauth-donation-index+oauth-donation-subscribe+oauth-goal-subscribe+oauth-poll-subscribe+oauth-user-show",
            $builder->build()
        );
    }
}
