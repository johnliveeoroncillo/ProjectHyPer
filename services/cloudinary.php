<?php
require ROOT . FOLDER . '/vendor/autoload.php';

\Cloudinary::config([ 
    "cloud_name" => (empty($_ENV['CL_NAME']) ? 'dh9xwk7fi' : $_ENV['CL_NAME']), 
    "api_key" => (empty($_ENV['CL_API_KEY']) ? '112416633534731' : $_ENV['CL_API_KEY']), 
    "api_secret" => (empty($_ENV['CL_SECRET_KEY']) ? 'jr5Jiqt1BLGJW2qzNjsJZVrPW78' : $_ENV['CL_SECRET_KEY']), 
    "secure" => true]);