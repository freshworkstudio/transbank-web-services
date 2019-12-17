<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include '../vendor/autoload.php';

$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

$response = $plus->getTransactionResult();

// Obtenemos los datos de la transacción
$transaction = json_decode(file_get_contents(__DIR__  . '/../storage/webpayplus.json'), true);
$transaction['response'] = $response;

// Comprueba que el pago se haya efectuado correctamente
if ($response->detailOutput->responseCode == 0) {
    // Transacción exitos
    $transaction['status'] = 'success';
} else {
    // Transacción fallida
    $transaction['status'] = 'failed';
}
$plus->acknowledgeTransaction();

// Agregamos los datos del response a la transacción y lo guardamos
file_put_contents(__DIR__  . '/../storage/webpayplus.json', json_encode($transaction));

// Redirecciona al cliente a Webpay para recibir el Voucher
echo RedirectorHelper::redirectBackNormal($response->urlRedirection);
