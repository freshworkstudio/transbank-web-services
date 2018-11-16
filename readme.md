# Transbank WebServices SDK
Librería para la integración de Webpay Plus, Webpay OneClick y Webpay Patpass. Esta librería es mantenida por Gonzalo De Spirito de [freshworkstudio.com](http://freshworkstudio.com) y [simplepay.cl](http://simplepay.cl).

*Leelo en inglés: [English](README.en.md)*

![Freshwork Studio's Transbank SDK](https://cloud.githubusercontent.com/assets/1103494/16623124/b0082046-436a-11e6-870a-2e5f6dbd9ef8.jpg)
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>


# Instalación
```bash
composer require freshwork/transbank
```


# Índice

* [Inicio rápido](#inicio-rapido)
  * [Video Tutorial | Implementar Webpay Plus Normal](#video-tutorial--implementar-webpay-plus-normal)
  * [Tienda de ejemplo usando Webpay OneClick](#tienda-de-ejemplo-webpay-oneclick)
* 


# Inicio rápido
### Video tutorial | Implementar Webpay Plus Normal
[![image](https://user-images.githubusercontent.com/1103494/46308744-d6880100-c590-11e8-99d5-08cd67971d77.png)](https://www.youtube.com/watch?v=VavxN-a9SIk)
[Ver screencast](https://www.youtube.com/watch?v=VavxN-a9SIk)

### Tienda de ejemplo Webpay OneClick
Tienda de prueba desarrollada en Laravel que ocupa OneClick. 
[https://github.com/freshworkstudio/demo-store](https://github.com/freshworkstudio/demo-store)


# Implemetación de distintos servicios
Transbank cuenta con distintos productos para implementar pagos en comercios y otras aplicaciones.
En esta documentación podrás encontrar detalles sobre:

* Webservices Webpay Plus con Autorización y Captura Simultánea (Normal)
* Webservices Webpay Plus con Autorización y Captura diferida
* Webpay OneClick
* PatPass


## Webpay Plus Normal
Transacción normal con Webpay. (Pago tarjeta de crédito y débito)

### Inicio de la transacción
Para comenzar con el flujo de pago, debes informar a Transbank cuanto y qué pagará tu cliente, así como también la URL de retorno intermedia que se utilizará para que puedas validar que el pago se hizo correctamente y la URL final, que se utilizará para que puedas mostrar los detalles del pago y orden.

Luego de informarle a Transbank de esta transacción, te entregará un **TOKEN** y una **URL**. Con estos datos deberás redireccionar al usuario a dicha URL (Webpay) a través de una petición **HTTP POST** (Es obligación que sea a través de POST) lo cual puedes hacer a través de un formulario HTML.

``` php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay Normal.
$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

// Para transacciones normales, solo puedes añadir una linea de detalle de transacción.
$plus->addTransactionDetail(10000, 'Orden824201'); // Monto e identificador de la orden

// Debes además, registrar las URLs a las cuales volverá el cliente durante y después del flujo de Webpay
$response = $plus->initTransaction('http://test.dev/response', 'http://test.dev/thanks');

// Utilidad para generar formulario y realizar redirección POST
echo RedirectorHelper::redirectHTML($response->url, $response->token);
```

### Retorno a URL intermedia
Luego de que el usuario haya pagado, Webpay redireccionará al cliente a tu página brevemente, para que tu puedas validar la transacción.
Para ello, te enviará un **TOKEN** en el cuerpo de una petición **HTTP POST**, bajo la llave **token_ws** (`$_POST['token_ws']`), sin embargo no deberás preocuparte de esto, ya que el método `getTransactionResult` se encarga internamente de capturar este valor.

Al ejecutar dicho método (`getTransactionResult`) pediremos a Transbank información sobre el pago y su estado. Deberemos comprobar este último y además validar que la orden no haya sido pagada anteriormente.

Si todo ha funcionado correctamente, deberás hacerle saber a Transbank que has comprobado la Transacción (o de lo contrario se reversará el pago) haciendo uso de `acknowledgeTransaction` y luego redireccionar al cliente nuevamente a Webpay de la misma forma anterior, para que este pueda recibir su voucher.

```php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

$bag = CertificationBagFactory::integrationWebpayNormal();

$plus = TransbankServiceFactory::normal($bag);

$response = $plus->getTransactionResult();

// Comprueba que el pago se haya efectuado correctamente
if (
    $response->detailOutput->responseCode == 0 && 
    /* Comprueba que la orden no haya sido pagada */
) {
    $plus->acknowledgeTransaction();
}

// Redirecciona al cliente a Webpay para recibir el Voucher
return RedirectorHelper::redirectBackNormal($response->urlRedirection);
```

### Retorno a URL final
En esta página deberás mostrar la mayor cantidad de información que fue obtenida en el paso anterior al invocar `getTransactionResult` y además detalles de la orden.


## OneClick

Permite al tarjetahabiente realizar pagos en el comercio sin la necesidad de ingresar cada vez información de la tarjeta de crédito al momento de realizar la compra.

### Inscripción del cliente
Para poder hacer uso de OneClick, el usuario debe inscribir su tarjeta de crédito y para ello, se le informará a Transbank el nombre de usuario (o identificador único) del cliente, su correo electrónico y además una URL de retorno (a la cual volverá el usuario luego de finalizar su inscripción) usando el método `initInscription` y este devolverá un **TOKEN** y una **URL**.

El usuario deberá ser redireccionado a dicha URL usando una petición **HTTP POST**, usando como dato el token que se nos proporcionó.

```php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\RedirectorHelper;

include 'vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay OneClick.
$certificationBag = CertificationBagFactory::integrationOneClick();
$oneClick = TransbankServiceFactory::oneclick($certificationBag);


// Informamos a Transbank que se iniciará un proceso de inscripción para un usuario
$response = $oneClick->initInscription('username', 'user@company.cl', 'http://misitio.cl/webpayresponse');

// Utilidad para generar formulario y realizar redirección POST
echo RedirectorHelper::redirectHTML($response->urlWebpay, $response->token);

```
![pago seguro webpay](https://cloud.githubusercontent.com/assets/1103494/16889963/7272ee14-4ab8-11e6-8638-9a0d8cbcf502.png)

### Finalizar la inscripción
El usuario, tras finalizar el proceso en Webpay, será redireccionado a la URL de retorno que informamos anteriormente.

En dicha redirección se te enviará un **TOKEN** en el cuerpo de una petición **HTTP POST** (redirección), bajo la llave **TBK_TOKEN** (`$_POST['TBK_TOKEN']`), sin embargo no deberás preocuparte de esto, ya que el método `finishInscription` se encarga internamente de capturar este valor.

Con este método (`finishInscription`) pediremos a Transbank información sobre la inscripción y el identificador del usuario para realizar los cobros posteriores, pero también deberemos validar si la inscripción se hizo de forma éxitosa.

```php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;

include 'vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay OneClick.
$certificationBag = CertificationBagFactory::integrationOneClick();
$oneClick = TransbankServiceFactory::oneclick($certificationBag);

$response = $oneClick->finishInscription($token);

if($response->responseCode == 0)
    // Inscripción exitosa
}

var_dump($response);
```
![var_dump](https://cloud.githubusercontent.com/assets/1103494/16324732/085d7100-3985-11e6-8081-27b15f020200.png)

Es de suma importancia que asocies el token provisto por Transbank (**tbkUser**) a tu cliente, porque este servirá para realizar los cobros posteriores.

### Realizar cargo a la tarjeta de crédito
Para realizar un cargo a la tarjeta de crédito previamente inscrita, deberás hacer uso del método `autorize` con el token asociado a tu cliente provisto por Tranbank en el paso anterior, el nombre de usuario o identificador único de tu cliente, monto y número de orden (en un formato especial).


```php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;

include 'vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay OneClick.
$certificationBag = CertificationBagFactory::integrationOneClick();
$oneClick = TransbankServiceFactory::oneclick($certificationBag);

// Identificador único de la compra generado por el comercio. Debe ser timestamp [yyyymmddhhMMss] + un correlativo de tres dígitos.
// Ej: Para la tercera transacción realizada el día 15 de julio de 2011 a las 11:55:50 la orden de compra sería: 20110715115550003.
$buyOrder = date('ymdhis') . str_pad(1, 3, '0', STR_PAD_LEFT);

// Token provisto por Transbank para identificar a tu cliente
$authToken = '9bf43307-6fa0-4b3b-888d-f36b6d040162'; //$user->tbkToken;

try {
	$response = $oneClick->authorize(1000, $buyOrder, 'username', $authToken);
} catch (\Exception $e) {
    // No se pudo realizar el cargo
}

var_dump($response);
```
![sp dev authorize](https://cloud.githubusercontent.com/assets/1103494/16324927/158d7f80-3987-11e6-9966-c32bc55663fb.png)

### Reversar un cargo
En ciertos casos, vamos a necesitar reversar un cargo y para ello deberemos utilizar el método `codeReverseOneClick` junto con el número de la orden. 

``` php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;

include 'vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay OneClick.
$certificationBag = CertificationBagFactory::integrationOneClick();
$oneClick = TransbankServiceFactory::oneclick($certificationBag);

// Ej: $response = $oneClick->codeReverseOneClick('20110715115550003');
$response = $oneClick->codeReverseOneClick($buyOrder);
```
### Desuscribir tarjeta
Si tu cliente desea desuscribir su tarjeta, deberás informar a Transbank haciendo uso del método `removeUser` con el token asociado a él y su nombre de usuario o identificador único.

``` php
<?php

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;

include 'vendor/autoload.php';

// Obtenemos los certificados y llaves para utilizar el ambiente de integración de Webpay OneClick.
$certificationBag = CertificationBagFactory::integrationOneClick();
$oneClick = TransbankServiceFactory::oneclick($certificationBag);

$response = $oneClick->removeUser($userToken, $username);
```


## PatPass
Una transacción de autorización de PatPass por Webpay, corresponde a una solicitud de inscripción de pago recurrente con tarjetas de crédito en donde el primer pago se resuelve al instante, y los subsiguientes quedan programados para ser ejecutados mes a mes. PatPass cuenta con fecha de caducidad o termino, la cual debe ser proporcionada junto a otros datos para esta transacción. La transacción puede ser realizada en Dólares y Pesos, para este último caso es posible enviar el monto en UF y Webpay realizará la conversión a pesos al momento de realizar el cargo al tarjetahabiente.

El proceso de Patpass es el mismo que en una transacción Normal o Mall, pero aca se debe completar la información de la inscripción `addInscriptionInfo`  antes de llamar al método `init`
Notar que esta clase se recomienda el uso del metodo `init` y no `initInscription` .

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
# Logs
Para el proceso de certificación con Transbank, muchas veces se solicitan los registros de las transacciones realizadas y esta biblioteca provee una utilidad para facilitar dicho proceso.

Haciendo uso de `TransbankCertificationLogger` debes especificar la ruta donde almacenar dichos registros y establecer su instancia en `LoggerFactory`

Otra opción es crear tu propia implementación haciendo uso de la interfaz `LoggerInterface`.


```php
<?php
    
use Freshwork\Transbank\Log\LoggerFactory;
use Freshwork\Transbank\Log\TransbankCertificationLogger;

include 'vendor/autoload.php';

LoggerFactory::setLogger(new TransbankCertificationLogger('/dir/to/save/logs'));

// ...

```
Internamente se generan varios registros, entre ellos:

* Los datos de entrada al llamar a un método de SOAP

* El XML generado y firmado

* El XML recibido

* El objeto recibido

* Errores como por ejemplo, la falla de validación del certificado. 

Si necesitas logear más información, puedes usar:

`LogHandler::log($data, $level = LoggerInterface::LEVEL_INFO, $type = null)`

```php
<?php

use Freshwork\Transbank\Log\LogHandler;
use Freshwork\Transbank\Log\LoggerInterface;

LogHandler::log('Comenzando proceso de pago', LoggerInterface::LEVEL_INFO); 
LogHandler::log('Error!!', LoggerInterface::LEVEL_ERROR, 'mensajes_internos'); 
LogHandler::log(['datos' => 'más datos', 'otros_datos']); 
```

# CertificationBag
Es una clase utilizada envolver certificados y llaves privadas con la finalidad de facilitar el manejo de los mismos a través de la biblioteca.

Es obligatorio y necesario el uso de `CertificationBag` ya que contiene todos los datos necesarios para cifrar la comunicación con Transbank y validar las respuestas de este.

```php
<?php

use Freshwork\Transbank\CertificationBag;

// Integración / Desarrollo
$bag = new CertificationBag(
	'path/to/cert/597020000000.key',
	'path/to/cert/597020000000.crt',
	null,
	CertificationBag::INTEGRATION
);

// Producción
$bag = new CertificationBag(
	'path/to/cert/597020000001.key',
	'path/to/cert/597020000001.crt',
	null,
	CertificationBag::PRODUCTION
);
```
Teniendo una `CertificationBag`,  se puede crear una instancia de los servicios de Webpay, por ejemplo:   `WebpayOneClickWebService`

```php
<?php

use Freshwork\Transbank\CertificationBag;
use Freshwork\Transbank\WebpayOneClickWebService;

$bag = new CertificationBag(
    '/path/to/597020000000.key',
	'/path/to/597020000000.crt'
);
$bag->setEnvironment(CertificationBag::INTEGRATION);

$oneClickService = new WebpayOneClickWebService($bag);
```

# Datos de prueba
Estos son los datos de tarjetas para que puedas probar en el ambiente de integración. 

![image](https://cloud.githubusercontent.com/assets/1103494/16890030/f125835c-4ab8-11e6-8bf9-847c847085a7.png)

##### TARJETA DE CRÉDITO VISA (SERÁ APROBADA)
**Número:** 4051885600446623
**CVV:** 123
**Fecha de expiración:** cualquiera

##### TARJETA DE CRÉDITO MASTERCARD (SERÁ RECHAZADA)
**Número:** 5186059559590568
**CVV:** 123
**Fecha de expiración:** cualquiera

##### TARJETA DE DÉBITO
**Número**: cualquiera

#### CREDENCIALES DEL BANCO
**RUT:** 11.111.111-1
**Contraseña:** 123

![captura de pantalla 2016-07-15 a las 6 28 41 p m](https://cloud.githubusercontent.com/assets/1103494/16890148/fdcf065e-4ab9-11e6-8d1a-83b9f8537c5c.png)


Biblioteca desarrollada por [Simplepay](https://simplepay.cl)
Powered by [Freshwork Studio](https://freshworkstudio.com)

# License
Freshwork Transbank está licenciada bajo [La licencia MIT (MIT)](LICENSE).
