<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QR Token Signing Secret
    |--------------------------------------------------------------------------
    | Used for HMAC-SHA256 signing of QR verification tokens.
    | Set QR_SECRET in your .env — never access env() outside of config files.
    */
    'secret' => env('QR_SECRET'),
];
