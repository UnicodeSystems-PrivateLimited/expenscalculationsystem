<?php

namespace App\Helpers;
use Exception;

class CURLRequest {

    public static function send($url, array $headers = [], $method = 'GET', $data = '') {
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method
        ];
        if (!empty($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        if ($method === 'POST') {
            $options[CURLOPT_POSTFIELDS] = $data;
        }
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            throw new Exception("cURL Error #:" . $err);
        }
        return $response;
    }

}
