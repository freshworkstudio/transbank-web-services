<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include '../vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay Normal.
$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

$transaction = [
    'id' => 'Orden' . rand(10000, 99999),
    'amount' => 1000,
];

// Para transacciones normales, solo puedes añadir una linea de detalle de transacción.
$plus->addTransactionDetail($transaction['amount'], $transaction['id']); // Monto e identificador de la orden

// Debes además, registrar las URLs a las cuales volverá el cliente durante y después del flujo de Webpay
$response = $plus->initTransaction('http://localhost:8888/webpay-plus/response.php', 'http://localhost:8888/webpay-plus/finish.php');

//Guardar transacción (probablemente uses unaa base de datos para esto)
$transaction['token'] = $response->token;
$transaction['status'] = 'pending';
file_put_contents(__DIR__  . '/../storage/webpayplus.json', json_encode($transaction));

// Utilidad para generar formulario y realizar redirección POST
echo RedirectorHelper::redirectHTML($response->url, $response->token);
