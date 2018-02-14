<?php

namespace App\Helpers;

class Core {


    /**
     * @param mixed $var
     */
    public static function pr($var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        exit;
    }

    public static function enableErrors() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        ini_set('error_reporting', E_ALL);
    }

    public static function getUploadDirectoryPath(string $path): string {
        return storage_path($path);
    }

    public static function getUploadDirectoryURL(string $path): string {
        return url(self::STORAGE . $path);
    }

    public static function getUploadDirectoryURLEmailTemplate(string $path): string {
        return url($path);
    }

    public static function getUploadDirectoryPathEmailTemplate(string $path): string {
        return url($path);
    }

    public static function makeDirectory(string $path) {
        if (!file_exists($path) and ! is_dir($path)) {
            mkdir($path, 0777, TRUE);
            chmod($path, 0777);
        }
    }

    /**
     * @param mixed $var
     */
    public static function prettyPrintR($var) {
        print_r(json_decode(json_encode($var)));
    }
    
    public static function updateVatData($contents, $file) {
        $custom_path = 'resources/json/'.$file;
        file_put_contents($custom_path, $contents);
    }

}
