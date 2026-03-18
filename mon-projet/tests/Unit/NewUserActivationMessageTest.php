<?php

use App\Support\NewUserActivationMessage;

test('activation SMS message contains key information', function () {
    $activationUrl = 'https://example.test/reset?token=abc&email=a%40b.com';
    $body = NewUserActivationMessage::toSms($activationUrl);

    expect($body)->toBe($activationUrl);
});
