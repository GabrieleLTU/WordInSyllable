<?php

namespace WordInSyllable\IO_Classes;

class WorkWithAPI
{
    const TABLE_NAME = 1;

    public function execute()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $string = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $urlData = explode("/", $string);

        $temp = ucfirst(strtolower($urlData[self::TABLE_NAME])) . "Controller";
        $controllerName = "\WordInSyllable\Controllers\\$temp";

        $controller = new $controllerName($urlData);
        echo json_encode($controller->$method());
    }
}
