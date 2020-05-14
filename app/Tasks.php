<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    /**
     * 属性に対するモデルのデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'status' => 0,
        'order' => 0,
    ];
}
