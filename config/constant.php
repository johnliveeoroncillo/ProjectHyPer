<?php
    define('APP_NAME', 'PHPJL');
    define('DB_HOST', (empty($_ENV['DB_HOST']) ? '127.0.0.1' : $_ENV['DB_HOST']));
    define('DB_USERNAME', (empty($_ENV['DB_USERNAME']) ? 'root' : $_ENV['DB_USERNAME']));
    define('DB_PASSWORD', (empty($_ENV['DB_PASSWORD']) ? 'Swmis2011!' : $_ENV['DB_PASSWORD']));
    define('DB_NAME', (empty($_ENV['DB_NAME']) ? 'phpjl' : $_ENV['DB_NAME']));
    define('DB_PREFIX', (empty($_ENV['DB_PREFIX']) ? '' : $_ENV['DB_PREFIX']));
    define('FOLDER', (empty($_ENV['FOLDER']) ? '' : $_ENV['FOLDER']));
    define('FOLDER_NAME', strpos($_SERVER['HTTP_HOST'], 'local') === false ? FOLDER : 'phpjl-mvc/');
    define('BASE_URL', '//'.$_SERVER['HTTP_HOST'].'/'.FOLDER_NAME);
    define('IS_DEVELOP', strpos($_SERVER['HTTP_HOST'], 'local') !== false);
    define('CURRENT_URL', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

    define('SMTP_HOST', (empty($_ENV['SMTP_HOST']) ? '' : $_ENV['SMTP_HOST']));
    define('SMTP_USER', (empty($_ENV['SMTP_USER']) ? '' : $_ENV['SMTP_USER']));
    define('SMTP_PASS', (empty($_ENV['SMTP_PASS']) ? '' : $_ENV['SMTP_PASS']));
    define('SMTP_PORT', (empty($_ENV['SMTP_PORT']) ? '' : $_ENV['SMTP_PORT']));

    define('HOME_FILE', (empty($_ENV['HOME_FILE']) ? 'index' : $_ENV['HOME_FILE']));
?>
