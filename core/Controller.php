<?php
/**
 * Created by PhpStorm.
 * User: cyberistanbul
 * Date: 2019-01-20
 * Time: 14:45
 */

include __DIR__ . '/../config/config.php';

class Controller
{

    protected $model;
    protected $library;

    protected function __construct()
    {
        global $config;
        if ($config['isApi']) {
            if (!isset($_FILES['file'])) {
                if (!isset($_POST['postman'])) {
                    $_POST = json_decode(file_get_contents('php://input'), true);
                }
            }
            if (!($config['apikey'] == @$_SERVER['REDIRECT_HTTP_AUTHORIZATION'] || $config['apikey'] == @$_SERVER['REDIRECT_REDIRECT_HTTP_AUTHORIZATION'] || $config['apikey'] == @$_SERVER['HTTP_AUTHORIZATION'])) {
                exit(json_encode(["error" => "Api Key Kontrolü Geçilemedi"]));
            }
        }
    }

    protected function array_equal($a, $b)
    {
        return (
            is_array($a)
            && is_array($b)
            && count($a) == count($b)
            && array_diff($a, $b) === array_diff($b, $a)
        );
    }

    protected function post($name, $xss = true)
    {
        if (isset($name) && isset($_POST[$name]) && !empty($_POST[$name])) {
            if ($xss) {
                if (is_array($_POST[$name])) {
                    $return_data = [];
                    foreach ($_POST[$name] as $key => $value) {
                        $return_data[$key] = addslashes(strip_tags(trim($value)));
                    }
                } else {
                    return addslashes(strip_tags(trim($_POST[$name])));
                }
            } else {
                return $_POST[$name];
            }
        }
        return null;
    }

    protected function get($name, $xss = true)
    {
        if (isset($name) && isset($_GET[$name]) && !empty($_GET[$name])) {
            if ($xss) {
                if (is_array($_GET[$name])) {
                    $return_data = [];
                    foreach ($_GET[$name] as $key => $value) {
                        $return_data[$key] = addslashes(strip_tags(trim($value)));
                    }
                } else {
                    return addslashes(strip_tags(trim($_GET[$name])));
                }
            } else {
                return $_GET[$name];
            }
        }

        return null;
    }

    protected function load_model($modelName)
    {
        if (is_array($modelName)) {
            $modelNames = $modelName;
            foreach ($modelNames as $modelName) {
                $temp = $modelName;
                $modelName = ucfirst(mb_strtolower($modelName, "utf8")) . "Model";
                $modelFile = __DIR__ . '/../models/' . $modelName . '.php';
                if (file_exists($modelFile)) {
                    require $modelFile;

                    $models = array(mb_strtolower($temp, 'utf8') . '_model' => new $modelName());
                    $this->model = (object)array_merge((array)$this->model, $models);
                }
            }
        } else {
            $temp = $modelName;
            $modelName = ucfirst(mb_strtolower($modelName, 'utf8')) . "Model";
            $modelFile = __DIR__ . '/../models/' . $modelName . '.php';
            if (file_exists($modelFile)) {
                require $modelFile;

                $models = array(mb_strtolower($temp, 'utf8') . '_model' => new $modelName());
                $this->model = (object)array_merge((array)$this->model, $models);
            }
        }
    }

    protected function base_url($link = "")
    {
        global $config;
        return $config['base_url'] . $link;
    }

    protected function get_config($name)
    {
        global $config;
        return $config[$name];
    }

    protected function load_library($libraryName)
    {
        if (is_array($libraryName)) {
            $libraryNames = $libraryName;
            foreach ($libraryNames as $libraryName) {
                $libraryName = ucfirst($libraryName);
                $libraryFile = __DIR__ . '/libraries/' . $libraryName . '.php';
                if (file_exists($libraryFile)) {
                    require $libraryFile;

                    $library = array(mb_strtolower($libraryName, "utf8") => new $libraryName());
                    $this->library = (object)array_merge((array)$this->library, $library);
                } else {
                    $libraryFile = __DIR__ . '/../libraries' . $libraryName . '.php';
                    if (file_exists($libraryFile)) {
                        require $libraryFile;

                        $library = array(mb_strtolower($libraryName, "utf8") => new $libraryName());
                        $this->library = (object)array_merge((array)$this->library, $library);
                    }
                }
            }
        } else {
            $libraryName = ucfirst($libraryName);
            $libraryFile = __DIR__ . '/libraries/' . $libraryName . '.php';
            if (file_exists($libraryFile)) {
                require $libraryFile;

                $library = array(mb_strtolower($libraryName, "utf8") => new $libraryName());
                $this->library = (object)array_merge((array)$this->library, $library);
            } else {
                $libraryFile = __DIR__ . '/../libraries' . $libraryName . '.php';
                if (file_exists($libraryFile)) {
                    require $libraryFile;

                    $library = array(mb_strtolower($libraryName, "utf8") => new $libraryName());
                    $this->library = (object)array_merge((array)$this->library, $library);
                }
            }
        }
    }
}