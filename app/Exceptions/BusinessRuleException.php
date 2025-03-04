<?php

namespace App\Exceptions;

class BusinessRuleException extends \Exception
{
    protected $message;
    protected $data;
    protected $errors;
    protected $status;

    public static function invoke(string $message = '', mixed $data = [], mixed $errors = [], $status = 400)
    {
        $instance = new static('Business rule validation error.');
        $instance->message = $message;
        $instance->data = $data;
        $instance->errors = $errors;
        $instance->status = $status;

        return $instance;
    }

    /*
    public function context()
    {
        return [
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ];
    }
    */

    public function render()
    {
        $response = ['message' => $this->message];

        if ($this->data) {
            data_set($response, 'data', $this->data);
        }

        if ($this->errors) {
            data_set($response, 'errors', $this->errors);
        }

        return response()->json($response, $this->status);
    }
}
