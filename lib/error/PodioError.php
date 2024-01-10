<?php

namespace Podio;

use Exception;

class PodioError extends Exception
{
    public $body;
    public $status;
    public $url;
    protected $request;

    public function __construct($body, $status, $url)
    {
        $this->body = json_decode($body, true);
        $this->status = $status;
        $this->url = $url;
        $this->request = $this->body['request'];
        parent::__construct($this->generateMessage(), 1, null);
    }
    
    protected function generateMessage(): string
    {
        $str = "";
        if (!empty($this->body['error_description'])) {
            $str .= '"'.$this->body['error_description'].'"';
        }
        if (array_key_exists("url", $this->request)) {
            $str .= "\nRequest URL: ".$this->request['url'];
        }
        if (!empty($this->request['query_string'])) {
            $str .= '?'.$this->request['query_string'];
        }
        if (!empty($this->request['body'])) {
            $str .= "\nRequest Body: ".json_encode($this->request['body']);
        }
        return $str;
    }
}
