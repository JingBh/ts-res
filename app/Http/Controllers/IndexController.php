<?php

namespace App\Http\Controllers;

use App\Services\GetData;

class IndexController
{
    public function index()
    {
        $data = GetData::latest();
        return view("index", [
            "data" => $data
        ]);
    }
}
