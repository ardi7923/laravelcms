<?php

namespace Ardi7923\Laravelcms\Services;


class ResponseService
{
    private $code = 500,
        $msg      = "",
        $errors   = [],
        $data     = "";

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setMsg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function get()
    {
        return response()->json([
            'data' => $this->data
        ],$this->code);
    }

    public function error()
    {
        return response()->json([
            'errors' => $this->errors
        ],$this->code);
    }

    public function success()
    {
        return response()->json([
            'msg'    => $this->msg
        ],$this->code);
    }
}
