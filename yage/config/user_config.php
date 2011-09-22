<?php

// Directory Information
$CONFIG['DOCUMENT_ROOT'] = 'path_to_yage';
$CONFIG['YAGE_ROOT'] = '/yage';

// Path Information
$CONFIG['PATH_ROOT'] = 'http://localhost/yage';

// Game Information
$CONFIG['APP_TITLE'] = 'Application Title';

// Template Information
$CONFIG['DEFAULT_LAYOUT'] = 'mylayout';

// Database Information
$CONFIG['DB_HOST'] = 'localhost';
$CONFIG['DB_PORT'] = '';
$CONFIG['DB_NAME'] = 'yage';
$CONFIG['DB_USER'] = 'username';
$CONFIG['DB_PASS'] = 'password';

/**
 *  Load Environment Config
 *  Set to a string value.
 *
 *  e.g.
 *  $CONFIG['ENV'] = 'dev';  // tries to load user_config_dev.php
 *  $CONFIG['ENV'] = 'prod'; // tries to load user_config_prod.php
 */
$CONFIG['ENV'] = false;