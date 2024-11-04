<?php

// Determine the protocol (http or https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Define the root directory dynamically
define('ROOT_PATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);

// Define paths for key directories based on ROOT_PATH
define('VIEW_PATH', ROOT_PATH . 'views' . DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH', ROOT_PATH . 'controllers' . DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH . 'core' . DIRECTORY_SEPARATOR);
define('LAYOUT_PATH', VIEW_PATH . 'layout' . DIRECTORY_SEPARATOR);
// Define the base URL for the application root
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/UTM-Gatherly/');
// Define the asset URL to point to your assets folder
define('ASSET_URL', BASE_URL . 'plugins/');
define('IMAGE_URL', BASE_URL . 'images/');