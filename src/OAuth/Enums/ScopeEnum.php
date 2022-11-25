<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\OAuth\Enums;

final class ScopeEnum
{
    public const CUSTOM_ALERT_STORE = 'oauth-custom_alert-store';
    public const DONATION_INDEX = 'oauth-donation-index';
    public const DONATION_SUBSCRIBE = 'oauth-donation-subscribe';
    public const GOAL_SUBSCRIBE = 'oauth-goal-subscribe';
    public const POLL_SUBSCRIBE = 'oauth-poll-subscribe';
    public const USER_SHOW = 'oauth-user-show';

    public static function getAll(): array
    {
        return [
            self::CUSTOM_ALERT_STORE,
            self::DONATION_INDEX,
            self::DONATION_SUBSCRIBE,
            self::GOAL_SUBSCRIBE,
            self::POLL_SUBSCRIBE,
            self::USER_SHOW,
        ];
    }
}
