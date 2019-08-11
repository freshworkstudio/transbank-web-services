<?php
/*
|--------------------------------------------------------------------------
| Configuración Webpay
|--------------------------------------------------------------------------
| Para los certificados y llaves privadas se puede utilizar tanto el path del certificado, como el contenido
| del archivo como string.
|
*/

return [
    'env' => env('TBK_ENV', env('APP_ENV', 'production')),
    'normal' => [
        'client_private_key' => env('TBK_NORMAL_PRIVATE_KEY', storage_path('app/certs/normal/client.key')),
        'client_certificate' => env('TBK_NORMAL_CERTIFICATE', storage_path('app/certs/normal/client.crt')),
        'transbank_certificate' => null, // Dejar en null para usar el certificado que viene en el paquete. Se cambia automáticamente para el ambiente de integración o certificación
    ],
    'patpass' => [
        'client_private_key' => env('TBK_PATPASS_PRIVATE_KEY', storage_path('app/certs/patpass/client.key')),
        'client_certificate' => env('TBK_PATPASS_CERTIFICATE', storage_path('app/certs/patpass/client.crt')),
        'transbank_certificate' => null, // Dejar en null para usar el certificado que viene en el paquete. Se cambia automáticamente para el ambiente de integración o certificación
    ],
    'oneclick' => [
        'client_private_key' => env('TBK_ONECLICK_PRIVATE_KEY', storage_path('app/certs/oneclick/client.key')),
        'client_certificate' => env('TBK_ONECLICK_CERTIFICATE', storage_path('app/certs/oneclick/client.crt')),
        'transbank_certificate' => null, // Dejar en null para usar el certificado que viene en el paquete. Se cambia automáticamente para el ambiente de integración o certificación
    ],
    'deferred' => [
        'client_private_key' => env('TBK_DEFERRED_PRIVATE_KEY', storage_path('app/certs/deferred/client.key')),
        'client_certificate' => env('TBK_DEFERRED_CERTIFICATE', storage_path('app/certs/deferred/client.crt')),
        'transbank_certificate' => null, // Dejar en null para usar el certificado que viene en el paquete. Se cambia automáticamente para el ambiente de integración o certificación
    ],
];
