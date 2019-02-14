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

    public function __construct()
    {
        include __DIR__ . '/../config/database.php';
        $host = $database['hostname'];
        $dbname = $database['database'];
        $uname = $database['username'];
        $pass = $database['password'];
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $uname, $pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}