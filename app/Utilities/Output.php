<?php

namespace App\Utilities;

class Output{
    public static function success($message = "", $data = []){
        return Output::output(200, $message, $data);
    }

    public static function error($message = "Something went wrong"){
        return Output::output(400, $message);
    }

    public static function unauthorize(){
        return Output::output(401, "Please Login To Continue.");
    }

    public static function not_found(){
        return Output::output(404, 'Sorry, data not found.', []);
    }

    public static function output($status = 200, $message = "", $data = []){
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data
        ]);
    }
}