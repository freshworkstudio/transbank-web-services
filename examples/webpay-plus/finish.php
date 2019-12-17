<?php

include '../vendor/autoload.php';

// Obtenemos los datos de la transacción
$transaction = json_decode(file_get_contents(__DIR__  . '/../storage/webpayplus.json'), true);

if (isset($_POST['TBK_TOKEN'])) {
    die('La transacción ha sido anulada por el usuario. <a href="../">Volver al home</a>');
}
if (!isset($_POST['token_ws'])) {
    die('No se recibió el token de la transacción. <a href="../">Volver al home</a>');
}

if ($transaction['token'] != $_POST['token_ws']) {
    die('No se tiene registro de la transacción recibida. <a href="../">Volver al home</a>');
}

// Comprueba que el pago se haya efectuado correctamente
if ($transaction['status'] == 'success') {
    // Transacción exitos
    echo 'Transacción exitosa';
    echo '<pre>' . print_r($transaction['response'], true) . '</pre>';
    echo '<p><a href="../">Volver al home</a></p>';
    die;
}

echo 'Transaccion fallida. <a href="../">Volver al home</a>';
