<?php
/**
 * Created by PhpStorm.
 * User: cyberistanbul
 * Date: 2019-01-20
 * Time: 14:44
 */
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, Authorization');
date_default_timezone_set("Europe/Istanbul");
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/core/Controller.php';
require __DIR__ . '/core/Model.php';
require __DIR__ . '/core/Route.php';
include __DIR__ . '/config/routes.php';

foreach ($routes as $method => $mRoutes) {
    foreach ($mRoutes as $key => $item) {
        $item = explode('/', $item);
        Route::routing('/' . $key, ucfirst($item[0]) . "@" . $item[1], $method);
    }
}