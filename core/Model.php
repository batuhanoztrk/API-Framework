<?php
/**
 * Created by PhpStorm.
 * User: cyberistanbul
 * Date: 2019-01-20
 * Time: 14:46
 */

class Model
{
    protected $db;

    protected function __construct()
    {
        include __DIR__ . '/../config/database.php';
        include __DIR__ . '/libraries/BasicDb.php';
        $host = $database['hostname'];
        $dbname = $database['database'];
        $uname = $database['username'];
        $pass = $database['password'];
        $this->db = new BasicDb($host, $dbname, $uname, $pass);
    }
}