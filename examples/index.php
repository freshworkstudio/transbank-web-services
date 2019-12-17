<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Prueba del SDK de transbank</title>
  <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="h-full">
  <div class="flex align-center justify-center">
    <div class="w-1/2 mt-20 p-5 flex-col flex rounded-lg shadow-lg">
      <h2 class="text-2xl font-thin">Prueba de SDK de Transbank</h2>
      
      <div class="tests mt-10 mb-10">
        <div class="webpay-plus mb-4">
          <span class="font-bold w-40 inline-block">Webpay Plus</span>
          <span><a class="text-blue-600 hover:no-underline underline" href="webpay-plus/init.php">Iniciar transacción</a></span>
        </div>
        <div class="webpay-oneclick mb-4">
          <span class="font-bold w-40 inline-block">Webpay OneClick</span>
          <span><a class="text-blue-600 hover:no-underline underline" href="webpay-oneclick/init.php">Iniciar inscripción</a></span>
        </div>
      </div>

      <img class="w-20 ml-auto" src="https://avatars1.githubusercontent.com/u/876077?s=200&v=4" alt="Freshwork Studio">

    </div>
  </div>
  
</body>
</html>
