<?php

namespace App\Support;

final class NewUserActivationMessage
{
    public static function subject(): string
    {
        return 'Activation de votre compte';
    }

    public static function toSms(string $activationUrl): string
    {
        // SMS must stay short: only include the activation/reset link.
        return trim($activationUrl);
    }
}
