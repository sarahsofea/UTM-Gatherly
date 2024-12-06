<?php
// require_once 'config/config.php';

class Router
{
    public static function route($url) 
    {
        $controllerName = isset($url[0]) ? ucfirst($url[0]) . 'Controller' : 'SiteController';
        $action = isset($url[1]) ? $url[1] : 'index';

        $controllerPath = CONTROLLER_PATH . $controllerName . ".php";

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName();

            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                echo "Action $action not found.";
            }
        } else {
            echo "Controller $controllerName not found.";
        }
    }
}


