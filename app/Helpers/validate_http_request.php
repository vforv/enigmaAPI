<?php

use Illuminate\Http\Request;

function ValidateHttpRequest($rules, $request, $object = false)
{
    $validator = $object ? Validator::make((array)$request, $rules) : Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return ["success" => false, "errors" => $validator->errors(), "status" => 400];
    }
}
