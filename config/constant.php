<?php
    define('APP_NAME', 'PHPJL');
    
    define('FOLDER', (empty($_ENV['FOLDER']) ? '/' : $_ENV['FOLDER']));
    define('BASE_URL', '//'.$_SERVER['HTTP_HOST'].FOLDER);
    define('IS_DEVELOP', strpos($_SERVER['HTTP_HOST'], 'local') !== false);
    define('CURRENT_URL', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

    define('SMTP_HOST', (empty($_ENV['SMTP_HOST']) ? '' : $_ENV['SMTP_HOST']));
    define('SMTP_USER', (empty($_ENV['SMTP_USER']) ? '' : $_ENV['SMTP_USER']));
    define('SMTP_PASS', (empty($_ENV['SMTP_PASS']) ? '' : $_ENV['SMTP_PASS']));
    define('SMTP_PORT', (empty($_ENV['SMTP_PORT']) ? '' : $_ENV['SMTP_PORT']));

    define('HOME_FILE', (empty($_ENV['HOME_FILE']) ? 'index' : $_ENV['HOME_FILE']));
    define('LOGIN_URL', (empty($_ENV['LOGIN_URL']) ? '' : $_ENV['LOGIN_URL']));
    define('PROTECTED_URL', (empty($_ENV['PROTECTED_URL']) ? 'home' : $_ENV['PROTECTED_URL']));
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
?>
