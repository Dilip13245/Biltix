<?php

namespace App\Services;

class ApiResponse
{
    public $success;
    public $message;
    public $data;
    public $code;

    public function __construct($success, $message, $data = [], $code = 200)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function isError()
    {
        return !$this->success;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData($key = null)
    {
        if ($key) {
            return $this->data[$key] ?? null;
        }
        return $this->data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function toArray()
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'code' => $this->code
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}