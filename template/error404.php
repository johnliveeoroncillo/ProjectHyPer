<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <base href="<?=BASE_URL;?>/" />
    <title><?=APP_NAME;?> - ERROR</title>
    <link href="assets/img/logo-default.png" rel="icon" />
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
  </head>
  <body>
    <div class="absolute top-0 left-0 z-20 bg-white w-screen h-screen flex flex-col items-center justify-center">

      <h1 class="text-3xl bg-gray-600 text-white px-5 py-3 rounded">
        <?=$message;?>
      </h1>

      <a href="<?=(!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');?>" class="font-semibold my-5 text-xl">Go Back</a>
    </div>
  </body>
</html>
