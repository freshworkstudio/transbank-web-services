# Transbank WebServices SDK
Librería para la integración de Webpay Plus, Webpay OneClick y Webpay Patpass. Esta librería es mantenida por Gonzalo De Spirito de [freshworkstudio.com](http://freshworkstudio.com) y [simplepay.cl](http://simplepay.cl).

![Freshwork Studio's Transbank SDK](https://cloud.githubusercontent.com/assets/1103494/16623124/b0082046-436a-11e6-870a-2e5f6dbd9ef8.jpg)
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>


# Installation
```bash
composer require freshwork/transbank
```

# Index

[Transacción Normal](#webservice-normal) | [Webpay OneClick](#one-click)  | [Webpay PatPass](#patpass) | [Logs](#logs)  | [CertificationBag](#certificationbag) | [Test Data / Datos de prueba](#test-data) 

# QuickStart

### UPDATE: Video tutorial | Implementar webpay plus
[![image](https://user-images.githubusercontent.com/1103494/46308744-d6880100-c590-11e8-99d5-08cd67971d77.png)](https://www.youtube.com/watch?v=VavxN-a9SIk)
[Ver screencast](https://www.youtube.com/watch?v=VavxN-a9SIk)



## Webpay OneClick Demo Store
Acá hay una tienda de prueba desarrollada en Laravel que ocupa OneClick. 
Laravel Demo Store using Webpay OneClick

[https://github.com/freshworkstudio/demo-store](https://github.com/freshworkstudio/demo-store)

## WebService Normal
Transacción normal con Webpay. (Pago tarjeta de crédito y débito)

Webpay Normal transaction. (Debit and credit card)
``` php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

//Get a certificationBag with certificates and private key of WebpayNormal for integration environment.
$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

//For normal transactions, you can just add one TransactionDetail
//Para transacciones normales, solo se puede añadir una linea de detalle de transacción.
$plus->addTransactionDetail(10000, 'Orden824201'); //Amount and BuyOrder

$response = $plus->initTransaction('http://test.dev/response', 'http://test.dev/thanks');

echo RedirectorHelper::redirectHTML($response->url, $response->token);
```

### When the user arrives at test.dev/response
```php
$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

$response = $plus->getTransactionResult();
//If everything goes well (check stock, check amount, etc) you can call acknowledgeTransaction to accept the payment. Otherwise, the transaction is reverted in 30 seconds.
//Si todo está bien, peudes llamar a acknowledgeTransaction. Si no se llama a este método, la transaccion se reversará en 30 segundos.
$plus->acknowledgeTransaction();

//Redirect back to Webpay Flow and then to the thanks page
return RedirectorHelper::redirectBackNormal($response->urlRedirection);
```

# One Click
"La modalidad de pago Oneclick permite al tarjetahabiente realizar pagos en el comercio sin la necesidad de ingresar cada vez información de la tarjeta de crédito al momento de realizar la compra. El modelo de pago contempla un proceso previo de inscripción o enrolamiento del tarjetahabiente, a través del comercio, que desee utilizar el servicio. Este tipo de pago facilita la venta, disminuye el tiempo de la transacción y reduce los riesgos de ingreso erróneo de los datos del medio de pago."

## Background
El webservice de Webpay One Click contempla los siguientes métodos:

This webservice implements this methods:

 - initInscription
 - finishInscription
 - authorize
 - codeReverseOneClick
 - removeUser

### initInscription
Permite asociar una tarjeta de crédito a un usuario en tu aplicación.
Este método inicia el proceso de inscripción de tarjeta. Transbank devolverá un token y una URL donde redirigir al usuario para que realice este proceso.

This method starts the credit card inscription. It returns a token and an URL to redirect the user.
Allows you to associate a credit card with a user of your application.

```php
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

$certificationBag = CertificationBagFactory::integrationOneClick();

//OneClick Instance
$oneClick = TransbankServiceFactory::oneclick($certificationBag);


// $response: Freshwork\Transbank\WebpayOneClick\oneClickInscriptionOutput
$response = $oneClick->initInscription('username', 'user@company.cl', 'http://misitio.cl/webpayresponse');

//Devuelve el html un formulario <form> con los datos y un <script> que envia el formulario automáticamente.
//It returns the html of a <form> and a <script> that submits the form immediately.
echo RedirectorHelper::redirectHTML($response->urlWebpay, $response->token);

exit; //el usuario es enviado a webpay para aprobar la inscripción de su tarjeta
```
`var_dump($response): `

![sp dev test](https://cloud.githubusercontent.com/assets/1103494/16324748/404d6e12-3985-11e6-859b-6ae3f049f4c9.png)

![pago seguro webpay](https://cloud.githubusercontent.com/assets/1103494/16889963/7272ee14-4ab8-11e6-8638-9a0d8cbcf502.png)

### finishInscription
El usuario, tras finalizar el proceso en Webpay, será redirigido a http://misitio.cl/webpayresponse con un token enviado por POST.

Once the user completes the credit card inscription, Transbank redirected the user back to http://misitio.cl/webpayresponse with a token provided through POST data.

```php
...
$token = $_POST['TBK_TOKEN'];

$response = $oneClick->finishInscription($token);

var_dump($response);
exit;
```
`var_dump($response): `

![var_dump](https://cloud.githubusercontent.com/assets/1103494/16324732/085d7100-3985-11e6-8081-27b15f020200.png)

Ahora, deberás asociar el tbkUser (token) al usuario dentro de tu base de datos para después usar ese código para realizar cargos en la tarjeta del usuario.

Now, you have to store the token with the user data in the database, so you can charge the credit card of the user afterwards.

### authorize
Realizar un cargo en la TC del usuario
Charge the credit card of the user


```php

// Identificador único de la compra generado por el comercio. Debe ser timestamp [yyyymmddhhMMss] + un correlativo de tres dígitos.
// Ej: Para la tercera transacción realizada el día 15 de julio de 2011 a las 11:55:50 la orden de compra sería: 20110715115550003.
$buyOrder = date('ymdhis') . str_pad(1, 3, '0', STR_PAD_LEFT);

//This comes from the database. The token retrieved in the finishInscription process saved with the user data in the database.
$authToken = '9bf43307-6fa0-4b3b-888d-f36b6d040162'; //$user->tbkToken;

$response = $oneClick->authorize(1000, $buyOrder, 'username', $authToken);

var_dump($response);
exit;
```
`var_dump($response): `

![sp dev authorize](https://cloud.githubusercontent.com/assets/1103494/16324927/158d7f80-3987-11e6-9966-c32bc55663fb.png)

### codeReverseOneClick
``` php
...
$response = $oneClick->codeReverseOneClick($buyOrder);
// $response = $oneClick->codeReverseOneClick('20110715115550003');
```
### removeUser
``` php
...
$response = $oneClick->removeUser($userToken, $username);
```

## Webpay OneClick
Este ejemplo ejecuta el método `initInscription` de WebPayOneClick en el ambiente de integración.
La idea de este método es asociar la tarjeta de crédito del usuario y dejarla guardada para poder cobrarle en el futuro
sin necesidad de pasar por el flujo de pago. Una vez autorizada la asociación de la tarjeta, solo se debe ejecutar el método
`authorize` en cualquier moment y el cobro se hará efectivo, sin necesidad de intervención del usuario.

This method executes `initInscription` for WebpayOneClick
```php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

//You always need to create a certificationBag where you put your private_key and certificate. This lines uses the integration certificates for OneClick that comes bundled into the package.
//Siempre necesitas un certificationBag, que es como una bolsa donde colocas los certificados necesarios (private key, certificado comercio, certificado tbk y defines el ambiente)
$certificationBag = CertificationBagFactory::integrationOneClick();

//OneClick Instance
$oneClick = TransbankServiceFactory::oneclick($certificationBag);

//Response from Transbank (token & url to redirect the user)
//Si todo sale bien, respuesta de transbank trae token y url a donde dirigir al usuario.
$response = $oneClick->initInscription('username', 'gonzalo@freshworkstudio.com', 'http://test.dev/tbkresponse');

//Generates a HTML Form and a script tag that sends the form immediately. You need to pass the url and the token.
//Esta clase toma el token y la url, y genera el html de un formulario POST que se envía inmediatamente por javascript. Puedes hacerlo tu, pero aquí lo tienes listo.
echo RedirectorHelper::redirectHTML($response->urlWebpay, $response->token);
```
Este proceso redirige al usuario a webpay para autorizar la asociación de la tarjeta de crédito. Cuando el flujo termine, 
la respuesta llegará a `http://test.dev/tbkresponse`. Es en esa página en donde se debe continuar el flujo y guardar el token 
que se genera y guardarlo en base de datos (o donde sea) asociado al usuario. Ese token es necesario posteriormente para ejecutar el metodo `authorize` y poder realizar cobros en el futuro. 

### Procesar respuesta de la asociación
```php

$response = $oneclick->finishInscription();

if($response->responseCode != 0)
{
    //La tarjeta ha sido rechazada
    //return redirect()->route('checkout');
    //Retornar al checkoout
    exit;
}

//Si todo sale bien editar el usuario en base de datos y asociar el token y últimos digitos de la Tarjeta
$user = $auth->user();
$user->tbkToken = $response->tbkUser;
$user->cc_final_numbers = $response->last4CardDigits;
$user->save();
flash()->success('Su tarjeta se ha inscrito satisfactoriamente');

//Redirigir al checkout nuevamente. Esta vez la página de checkout revisará los datos del usuario y sabrá que solo debe cobrar y no autorizar la tarjeta. 
return redirect()->route('checkout');
}

```

### Autorizar cobro 
Una vez que el usuario tiene el token asociado, cobrar es tan simple como:
```
$total = 1000; //$1.000 pesos
$buyOrder = rand(1,1000); // Numero de orden de compra. Normalmente el ID de la transacción que creamos en nuestro sistema
$user = getConnectedUser(); //Nuestro usuario logeado
$email = $user->email; //Email del usuario. Debe ser el mismo que configuramos cuando asociamos al usuario en el paso anterior
$token = $user->token; //Token que viene desde la base de datos asociado al usuario y que se generó en el paso anterior
 
try {
    $response = $this->oneclick->authorize($total, $buyOrder, $email, $token);
}catch (\Exception $e) {
    flash()->error('La transacción fue rechazada. Intenta asociar otra tarjeta. (' . $e->getMessage() . ')');
    return redirect()->route('checkout.failed', ['txid' => $transaction->id]);
}

echo 'La transacción fue aceptada'
``` 

### In production
Just change this line

Para que funcione en producción en vez de integración, solo debes cambiar esta línea
``` php
$certificationBag = CertificationBagFactory::production('/path/to/private.key', '/path/to/certificate.crt');

//OR
$certificationBag = new CertificationBag('/path/to/private.key', '/path/to/certificate.crt', null, CertificationBag::PRODUCTION);
```
If the `CertificationBag` is setted on `CertificationBag::PRODUCTION`, the underlying classes (`WebpayNormal`, `WebpayOneClick`, etc) uses the production url endpoints of the WebService automatically.


# PatPass
Una transacción de autorización de PatPass by Webpay corresponde a una solicitud de inscripción de pago recurrente con tarjetas de crédito, en donde el primer pago se resuelve al instante, y los subsiguientes quedan programados para ser ejecutados mes a mes. PatPass by Webpay cuenta con fecha de caducidad o termino, la cual debe ser proporcionada junto a otros datos para esta transacción. La transacción puede ser realizada en Dólares y Pesos, para este último caso es posible enviar el monto en UF y Webpay realizará la conversión a pesos al momento de realizar el cargo al tarjetahabiente.


El proceso de Patpass es el mismo que en una transacción Normal o Mall, pero aca se debe completar la información de la inscripción `addInscriptionInfo`  antes de llamar al método `init`
Notar que esta clase se recomienda el uso del metodo `init` y no `initInscription` .

This process is similar to the normal transaction and mall transaction flow, but you have to call `addInscriptionInfo` before executing `init` call.

### init
```php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

$bag = CertificationBagFactory::integrationPatPass();

$patpass = TransbankServiceFactory::patpass($bag);

$patpass->addTransactionDetail(1000, '50'); //Amount & BuyOrder

//Id del servicio a contratar, Rut cliente, Nombre, Apellido, Segundo apellido, Email cliente, Celular Cliente, Fecha termino contrato, Email comercio
$patpass->addInscriptionInfo('serviceID', '11.111.111-5', 'Gonzalo', 'De Spirito', 'Zúñiga', 'gonzalo@email.com',
    '987654321', '2017-12-01', 'contacto@comercio.cl');

$response = $patpass->init('http://test.dev/response', 'http://test.dev/thanks');
echo RedirectorHelper::redirectHTML($response->url, $response->token);
```

### response
```php

$response = $patpass->getTransactionResult();
//If everything goes well (check stock, check amount, etc) you can call acknowledgeTransaction to accept the payment. Otherwise, the transaction is reverted in 30 seconds.
//Si todo está bien, peudes llamar a acknowledgeTransaction. Si no se llama a este método, la transaccion se reversará en 30 segundos.
$plus->acknowledgeTransaction();

//Redirect back to Webpay Flow and then to the thanks page
return RedirectorHelper::redirectBackNormal($response->urlRedirection);
```
## Logs
Para el proceso de certificación con Transbank, muchas veces se solicita los logs de las transacciones realizadas.
Por defecto, el sistema usa `Freshwork\Transbank\Log\VoidLogger` que no guarda ni imprime los logs que emite el paquete. No hace nada :)
Puedes cambiar la configuración para usar `TransbankCertificactionLogger` o crear tu propia implementación de la
interfaz `Freshwork\Transbank\Log\LoggerInterface`. 

Transbank asks for your logs to certificate your project. By default, the package uses an useless 
`Freshwork\Transbank\Log\VoidLogger`, but you can use `TransbankCertificactionLogger` or create your own
implementation of `Freshwork\Transbank\Log\LoggerInterface` to generate some useful logs you can send. 
```php
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;
use Freshwork\Transbank\Log\LoggerFactory;
use Freshwork\Transbank\Log\TransbankCertificationLogger;

//To use TransbankCertificationLogger, but you can pass any LoggerInterface implementation. Even your own.
//After this line, any LogHandler::log(..) call will use this implementation. 
LoggerFactory::setLogger(new TransbankCertificationLogger('/dir/to/save/logs'));

$certificationBag = CertificationBagFactory::integrationOneClick();

//OneClick Instance
$oneClick = TransbankServiceFactory::oneclick($certificationBag);


// $response: Freshwork\Transbank\WebpayOneClick\oneClickInscriptionOutput
$response = $oneClick->initInscription('username', 'user@company.cl', 'http://misitio.cl/webpayresponse');

//Devuelve el html un formulario <form> con los datos y un <script> que envia el formulario automáticamente.
//It returns the html of a <form> and a <script> that submits the form immediately.
echo RedirectorHelper::redirectHTML($response->urlWebpay, $response->token);

exit; //el usuario es enviado a webpay para aprobar la inscripción de su tarjeta

```
Internamente, se generan varios logs, entre ellos un log con los datos de entrada al llamar aun método de soap, el xml generado, el xml recibido, el objeto recibido y errores por si falla la validación del certificado. 
Si necesitas logear más información, puedes usar: 

Internally, with every response/request generated, the clases generates a log. 
If you need to log anything else, you can use `LogHandler::log`: 

`LogHandler::log($data, $level = LoggerInterface::LEVEL_INFO, $type = null)`
```php
use Freshwork\Transbank\Log\LogHandler;
use Freshwork\Transbank\Log\LoggerInterface;

LogHandler::log('Comenzando proceso de pago', LoggerInterface::LEVEL_INFO); 
LogHandler::log('Error!!', LoggerInterface::LEVEL_ERROR, 'mensajes_internos'); 
LogHandler::log(['datos' => 'más datos', 'otros_datos']); 
```

## CertificationBag

```php
use Freshwork\Transbank\CertificationBag;

//Para desarrollo
$bag = new CertificationBag(
	'path/to/cert/597020000000.key',
	'path/to/cert/597020000000.crt',
	null,
	CertificationBag::INTEGRATION
);

//Producción
$bag = new CertificationBag(
	'path/to/cert/597020000001.key',
	'path/to/cert/597020000001.crt',
	null,
	CertificationBag::PRODUCTION
);
```
Ya teniendo el `CertificationBag`,  se puede instanciar la clase  `WebpayOneClickWebService`

Once you have a  CertificationBag` instance, you can create a `WebpayOneClickWebService`

```php
use Freshwork\Transbank\CertificationBag;
use Freshwork\Transbank\CertificationBag;

$bag = new CertificationBag(
    '/path/to/597020000000.key',
	'/path/to/597020000000.crt'
);
$bag->setEnvironment(CertificationBag::INTEGRATION);

$oneClickService = new WebpayOneClickWebService($bag);
```

## Test Data
Estos son los datos de tarjetas para que puedas probar en el ambiente de integración. 

Just for the integration environment: 

![image](https://cloud.githubusercontent.com/assets/1103494/16890030/f125835c-4ab8-11e6-8bf9-847c847085a7.png)

##### VISA CREDIT CARD (WILL BE APPROVED / SERÁ APROBADA)
Number: 4051885600446623
CVV: 123
Year: any / cualquiera
Month: any / cualquiera

##### MASTERCARD CREDIT CARD (WILL BE DENIED / SERÁ DENEGADA)
Number: 5186059559590568
CVV: 123
Year: any / cualquiera
Month: any / cualquiera

##### DEBIT CARD
CardNumber: 12345678

#### BANK VIEW
RUT: 11.111.111-1
Password: 123

![captura de pantalla 2016-07-15 a las 6 28 41 p m](https://cloud.githubusercontent.com/assets/1103494/16890148/fdcf065e-4ab9-11e6-8d1a-83b9f8537c5c.png)


# Advanced Usage of the package
Este paquete ofrece una clase `WebpayOneClick` (recomendado) ó  `WebpayOneClickWebService` para interactuar con el Web Service.

This package offers a  `WebpayOneClick` class (recommended) or a `WebpayOneClickWebService` class to implement the web service easily.

### `WebpayOneClick` (más simple / simpler)
Puedes crear un objeto `WebpayOneClick` usando `TransbankServiceFactory`
You can create a `WebpayOneClick` object with `TransbankServiceFactory`

```php
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\CertificationBag;

/* TransbankServiceFactory::createOneClickWith(
	$private_key,
	$certificate,
	$is_production = false)
*/

 $oneClick = TransbankServiceFactory::createOneClickWith(
	'/path/to/597020000000.key',
	'/path/to/597020000000.crt',
	false
);

// ó / OR

$bag = new CertificationBag(
    '/path/to/597020000000.key',
	'/path/to/597020000000.crt'
);
$bag->setEnvironment(CertificationBag::INTEGRATION);

$oneClick = TransbankServiceFactory::createOneClick($bag);

//$oneclick->initTransaction(...);
//$oneclick->finishTransaction(...);
//$oneclick->authorize(...);
//...
```

### `WebpayOneClickWebService` (más completo)
El otro método para interactuar con el webservice es la clase `WebpayOneClickWebService` .  `WebpayOneClick` implementa todos estos métodos internamente.

Para poder instanciarla es necesario configurar al ambiente y certificados del comercio con el que se va a trabajar. Para simplificar esto, implementamos una clase llamada `CertificationBag`. Piensa esta clase como una bolsa donde colocas tu certificado, tu llave privada y defines el ambiente (integración o producción). Recuerda que necesitas deferentes certificados en cada ambiente.

You have another way: `WebpayOneClickWebService`. Actually, `WebpayOneClick` implements this class internally.

To instantiate this class, you have to set the  environment and certificates on which your are going to work. To simplify this process, we implemented a `CertificationBag` class. Imagine this class, as a bag where you put the private_key, the certificate and where you define your environment (integration, production). Remember that you need different certificates based on your environment.


#### example
As you can see it's a little bit more complicated, but but it's more OOP.
```
use Freshwork\Transbank\CertificationBag;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;
use Freshwork\Transbank\WebpayOneClick\oneClickPayInput;

$bag = new CertificationBag(
	'/path/to/597020000000.key',
	'/path/to/597020000000.crt'
);

$buyOrder = date('ymdhis') . str_pad(1, 3, '0', STR_PAD_LEFT);

$oneClickService = new WebpayOneClickWebService($bag);

$oneClickPayInput = new oneClickPayInput();
$oneClickPayInput->amount = 1000;
$oneClickPayInput->buyOrder = $buyOrder;
$oneClickPayInput->tbkUser = '9bf43307-6fa0-4b3b-888d-f36b6d040162';
$oneClickPayInput->username = 'gonzunigad@gmail.com';

$oneClickauthorizeResponse = $oneClickService->authorize($oneClickPayInput);

$oneClickPayOutput = $oneClickauthorizeResponse->return;
var_dump($oneClickPayOutput);
```
Librería desarrollada por [Simplepay](https://simplepay.cl)
Powered by [Freshwork Studio](https://freshworkstudio.com)

## License

Freshwork Transbank Package is licensed under [The MIT License (MIT)](LICENSE).
