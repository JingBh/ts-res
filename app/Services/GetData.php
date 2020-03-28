<?php

namespace App\Services;

use App\Models\Data;

class GetData
{
    public static function latest() {
        $result = [];
        $tasks = Data::tasks();
        foreach ($tasks as $task) {
            $data = Data::where("task", $task)->orderBy("created_at", "DESC")->value("data");
            $result[$task] = $data;
        }
        return $result;
    }
}
