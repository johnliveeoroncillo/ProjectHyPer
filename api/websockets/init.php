<?php
  require __DIR__ . '/../../vendor/autoload.php';

  $options = array(
    'cluster' => 'ap1',
    'useTLS' => false
  );
  $pusher = new Pusher\Pusher(
    '30b0ef207f4183b9936d',
    '2279d8a7b2758c4cde48',
    '1667564',
    $options
  );

?>