<?php

namespace Simplepushio;

/**
 * Class Api
 * @package Simplepushio
 */
class Api {

    /**
     * Simplepush API URL
     */
    const API_URL = 'https://api.simplepush.io/send';

    /**
     * User agent
     */
    const USERAGENT = 'Simplepush-io PHP/1.0';

    /**
     * Simplepush.io Key
     *
     * @var string
     */
    private $key = '';

    /**
     * CURL info container
     *
     * @var mixed
     */
    private $curl_info;

    /**
     * CURL errors container
     *
     * @var string
     */
    private $curl_error = '';

    /**
     * CURL response container
     *
     * @var string
     */
    private $curl_response = '';

    /**
     * Api constructor.
     *
     * @param string $key
     * @throws \Exception
     */
    public function __construct($key = '') {
        if ($key != '') {
            $this->key = $key;
        } else {
            throw new \Exception('API key is required');
        }
    }

    /**
     * Build API HTTP Post data
     *
     * @param array $array
     * @return string
     */
    private function buildPostData($array = []) {
        $array['key'] = $this->key;
        return http_build_query($array);
    }

    /**
     * Send request to simplepush
     *
     * @param array $fields
     * @return bool
     * @throws \Exception
     */
    public function sendRequest($fields = []) {
        if (count($fields) > 0) {
            $request = curl_init();
            curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($request, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($request, CURLOPT_USERAGENT, self::USERAGENT);
            curl_setopt($request, CURLOPT_URL, self::API_URL);
            curl_setopt($request, CURLOPT_POST, TRUE);
            curl_setopt($request, CURLOPT_POSTFIELDS, $this->buildPostData($fields));
            $response = curl_exec($request);
            $this->curl_info = curl_getinfo($request);
            $this->curl_error = curl_error($request);
            $this->curl_response = (string)$response;
            if ($this->curl_info['http_code'] == '200') {
                $decoded_response = json_decode($this->curl_response);
                if ($decoded_response->status == 'OK') {
                    return TRUE;
                } else {
                    throw new \Exception("Error!");
                }
            } else {
                throw new \Exception("Error: HTTP Code {$this->curl_info['http_code']}");
            }
        } else {
            throw new \Exception("Empty parameters list");
        }
    }
}