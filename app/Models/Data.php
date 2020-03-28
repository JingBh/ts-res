<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * 存储各个 task 每次执行结果
 *
 * @property integer $id
 * @property string $task
 * @property array $data
 * @property Carbon|string|null $created_at
 * @property Carbon|string|null $updated_at
 */
class Data extends Model
{
    protected $table = "data";

    protected $casts = [
        "data" => "json"
    ];

    /**
     * 获取 tasks 列表
     *
     * @return Collection
     */
    public static function tasks() {
        return self::groupBy("task")->pluck("task");
    }
}
