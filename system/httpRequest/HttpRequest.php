<?php

namespace System\httpRequest;

class HttpRequest
{
    private $url;
    private $method;
    private $headers;
    private $data;
    private $formData;

    public function __construct($url, $method = 'GET', $headers = array(), $data = array(), $formData = array())
    {
        $this->url = $url;
        $this->method = strtoupper($method);
        $this->headers = $headers;
        $this->data = $data;
        $this->formData = $formData;
    }

    public function send(): bool|string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);

        if ($this->method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($this->formData)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->formData);
            } elseif (!empty($this->data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->data));
            }
        }

        if (!empty($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}