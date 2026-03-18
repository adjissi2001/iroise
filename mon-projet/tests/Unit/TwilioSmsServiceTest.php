<?php

use App\Services\Sms\TwilioSmsService;

test('phone normalization returns E.164 for french mobile numbers', function () {
    $svc = new TwilioSmsService();

    expect($svc->normalizeToE164('0612345678'))->toBe('+33612345678')
        ->and($svc->normalizeToE164('06 12 34 56 78'))->toBe('+33612345678')
        ->and($svc->normalizeToE164('+33612345678'))->toBe('+33612345678')
        ->and($svc->normalizeToE164('33612345678'))->toBe('+33612345678');
});

test('phone normalization returns null for invalid numbers', function () {
    $svc = new TwilioSmsService();

    expect($svc->normalizeToE164(''))->toBeNull()
        ->and($svc->normalizeToE164('123'))->toBeNull()
        ->and($svc->normalizeToE164('abcdef'))->toBeNull();
});
