<?php

namespace Helpers;


class FileManager {

    private $storage = '/img/upload/';

    public function __construct(string $storage = null) {
        if($storage != null)
            $this->storage = $this->storage . $storage;
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $this->storage))
            mkdir($_SERVER['DOCUMENT_ROOT'] . $this->storage);
    }

    public function upload($folder, $name) {
        $dir = $this->storage .'/'.$folder;
        $fileName = $_FILES['image']['name'];
        $array = explode('.', $fileName);
        $extension = trim(array_pop($array));
        $imagePath = $dir.'/'.$name.'.'.$extension;
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $dir))
            mkdir($_SERVER['DOCUMENT_ROOT'] . $dir);
        move_uploaded_file($_FILES['image']['tmp_name'],
            $_SERVER['DOCUMENT_ROOT'] . $imagePath);
        return $imagePath;
    }

    public function delDir($directory){
        if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'. $this->storage . '/' . $directory)){
            self::delTree($_SERVER['DOCUMENT_ROOT'] .'/'. $this->storage . '/' . $directory);
        }
    }

    private function delTree($dir){
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function delFile() {

    }

}