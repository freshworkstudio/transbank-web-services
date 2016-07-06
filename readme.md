#Transbank WebServices SDK
Librería para la integración de Webpay Plus, Webpay OneClick y Webpay Patpass

# Installation
```bash
composer require freshwork/transbank
```

#QuickStart

## Webpay OneClick on Integration Environment
Este ejemplo ejecuta el método `initInscription` de WebPayOneClick.
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
### In production
Just change this line

Para que funcione en producción en vez de integración, solo debes cambiar esta línea
``` php
$certificationBag = CertificationBagFactory::production('/path/to/private.key', '/path/to/certificate.crt');

//OR
$certificationBag = new CertificationBag('/path/to/private.key', '/path/to/certificate.crt', null, CertificationBag::PRODUCTION);
```
If the `CertificationBag` is setted on `CertificationBag::PRODUCTION`, the underlying classes (`WebpayNormal`, `WebpayOneClick`, etc) uses the production url endpoints of the WebService automatically.

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

#One Click
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
...
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;


// $response: Freshwork\Transbank\WebpayOneClick\oneClickInscriptionOutput
$response = $oneClick->initInscription('username', 'user@company.cl', 'http://misitio.cl/webpayresponse');

//Devuelve el html un formulario <form> con los datos y un <script> que envia el formulario automáticamente.
//It returns the html of a <form> and a <script> that submits the form immediately.
echo RedirectorHelper::redirectHTML($response->urlWebpay, $response->token);

exit; //el usuario es enviado a webpay para aprobar la inscripción de su tarjeta
```
`var_dump($response): `

![sp dev test](https://cloud.githubusercontent.com/assets/1103494/16324748/404d6e12-3985-11e6-859b-6ae3f049f4c9.png)

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

#PatPass
Coming soon. It's technically possible to implement with this package right now using `WebpayPatPass` class, but it still need some adjustments to be easier to implement.


## Advanced Usage
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

### CertificationBag

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
