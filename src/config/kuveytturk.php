<?php
/**
 * Kuveyt turk required parameters for payment
 */

return [
    "Type"                => "Sale",
    "APIVersion"          => "1.0.0",
    "CurrencyCode"        => "0949",
    'CustomerId'          => env('KUVEYT_TURK_API_CUSTOMER_ID'),
    "MerchantId"          => env('KUVEYT_TURK_API_MERCHANT_ID'),
    "OkUrl"               => env('KUVEYT_TURK_API_OKURL'),
    "FailUrl"             => env('KUVEYT_TURK_API_FAILURL'),
    "UserName"            => env('KUVEYT_TURK_API_USERNAME'),
    "Password"            => env('KUVEYT_TURK_API_PASSWORD'),
    "TransactionSecurity" => "3" // 3d Secure = 3 , without 3d Secure = 1
];