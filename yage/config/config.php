<?php

$CONFIG = array();
require_once "user_config.php";

// determine override configs
if(isset($CONFIG['ENV']) && $CONFIG['ENV']) {
    try {
        require_once "user_config_" . $CONFIG['ENV'] . ".php";
    } catch( $e ) {}
}

require_once "routes.php";


// YAGE Constants
define('C_YAGE_NAME', 'Yage');
define('C_YAGE_VERSION', '0.0.1');

// Default Constants
define('C_DEFAULT_LAYOUT', $CONFIG['DEFAULT_LAYOUT']);
define('C_DEFAULT_SRC_LAYOUT', 'yage');

// Server Document Root
define('C_DIR_DOCROOT', $CONFIG['DOCUMENT_ROOT']);
define('C_DIR_YAGE', C_DIR_DOCROOT . $CONFIG['YAGE_ROOT']);
define('C_DIR_APP', C_DIR_YAGE . '/app');

// Directory Constants
define('C_DIR_PUBLIC', C_DIR_YAGE . '/public');
define('C_DIR_CONTROLLER', C_DIR_APP . '/controller');
define('C_DIR_MODEL', C_DIR_APP . '/model');
define('C_DIR_VIEW', C_DIR_APP . '/view');
define('C_DIR_LAYOUT', C_DIR_VIEW . '/layout');

// Source Directory Constants
define('C_SRC_YAGE', C_DIR_YAGE . '/yage');
define('C_SRC_ROOT', C_SRC_YAGE . '/core');
define('C_SRC_PLUGIN', C_SRC_ROOT . '/plugin');
define('C_SRC_LIBS', C_SRC_ROOT . '/libs');
define('C_SRC_CONTROLLER', C_SRC_ROOT . '/controller');
define('C_SRC_MODEL', C_SRC_ROOT . '/model');
define('C_SRC_VIEW', C_SRC_ROOT . '/view');
define('C_SRC_LAYOUT', C_SRC_VIEW . '/layout');

// Path Constants
define('C_PATH_ROOT', $CONFIG['PATH_ROOT']);
define('C_PATH_SRC_ASSETS', C_PATH_ROOT . '/src_assets');

// Game Constants
define('C_APP_TITLE', $CONFIG['APP_TITLE']);

// Database Constants
define('C_DB_HOST', $CONFIG['DB_HOST']);
define('C_DB_USER', $CONFIG['DB_USER']);
define('C_DB_PASS', $CONFIG['DB_PASS']);
define('C_DB_NAME', $CONFIG['DB_NAME']);
define('C_DB_PORT', $CONFIG['DB_PORT']);

// Routes
define('C_ROUTE_DEFAULT', $CONFIG['ROUTE_DEFAULT']);
