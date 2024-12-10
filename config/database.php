<?php

/**
 * DATABASE CONFIGURATION
 */
$config['DB_HOST'] = $env->get('DB_HOST', 'localhost');
$config['DB_USERNAME'] = $env->get('DB_USERNAME', 'root');
$config['DB_PASSWORD'] = $env->get('DB_PASSWORD', 'Swmis2011!');
$config['DB_NAME'] = $env->get('DB_NAME', 'phpjl');
$config['DB_PREFIX'] = $env->get('DB_PREFIX', '');
