<?php

session_start();
require_once 'config/config.php';
require_once 'core/Router.php';

$url = isset($_GET['r']) ? explode('/', rtrim($_GET['r'], '/')) : [];
Router::route($url);

// $url = array('dashboard', 'update');