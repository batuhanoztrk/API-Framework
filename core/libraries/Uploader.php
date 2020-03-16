<?php
/**
 * Created by PhpStorm.
 * User: cyberistanbul
 * Date: 2019-02-10
 * Time: 03:55
 */
include __DIR__ . '/class.upload.php';

if (!function_exists('getimagesizefromstring')) {
    function getimagesizefromstring($data)
    {
        $uri = 'data://application/octet-stream;base64,' . base64_encode($data);
        return getimagesize($uri);
    }
}

class Uploader extends Controller
{
    // Verot.net
    public function upload($file, $path, $limit = 1, $allowedFormats = ["jpg", "png", "gif", "jpeg"])
    {

        $image = $_FILES[$file];


        $fileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedFormats)) {
            if ($image['size'] <= $limit * 1024 * 1024) {

                $foo = new \Verot\Upload\Upload($image);
                $name = md5(uniqid(mt_rand(), true));
                $foo->file_new_name_body = $name;
                $target = realpath(__DIR__ . '/../../' . $path) . '/';

                $foo->process($target);


                if ($foo->processed) {
                    return $this->base_url($path . "/" . $name);
                } else {
                    return 0;
                }
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }

    /*public function upload($file, $path, $limit = 1, $allowedFormats = ["jpg", "png", "gif", "jpeg"])
    {

        $image = $_FILES[$file];


        $fileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedFormats)) {
            if ($image['size'] <= $limit * 1024 * 1024) {
                $name = md5(uniqid(mt_rand(), true)) . "." . $fileType;

                $target = realpath(__DIR__ . '/../../' . $path) . '/' . $name;

                if (move_uploaded_file($image['tmp_name'], $target) != FALSE) {
                    return $this->base_url($path . "/" . $name);
                } else {
                    return 0;
                }
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }*/

    public function base64_upload($file, $path, $limit = 1, $allowedFormats = ["jpg", "png", "gif", "jpeg"])
    {
        /*$imgData = base64_decode($file);
        $f = finfo_open();

        $mime_type = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
        $mime_type = mb_strtolower($mime_type, "utf-8");

        if (in_array($mime_type, $allowedFormats)) {
            $image_size = getimagesizefromstring($file);

            if ($image_size <= $limit * 1024 * 1024) {
                $name = md5(uniqid(mt_rand(), true)) . "." . $mime_type;

                $target = realpath(__DIR__ . '/../../' . $path) . '/' . $name;

                if (file_put_contents($target, $imgData)) {
                    return $this->base_url($path . "/" . $name);
                } else {
                    return 0;
                }

            } else {
                return -1;
            }

        } else {
            return -2;
        }*/

        $name = md5(uniqid(mt_rand(), true)) . ".png";

        $target = realpath(__DIR__ . '/../../' . $path) . '/' . $name;

        if (file_put_contents($target, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file)))) {
            return $this->base_url($path . "/" . $name);
        } else {
            return 0;
        }

    }

}