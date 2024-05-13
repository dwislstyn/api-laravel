<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamp = false;

    protected $table = 'employee';

    protected $fillable = [
        'id',
        'nik',
        'name',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $cast = [
        'id' => 'integer',
        'nik' => 'string',
        'name' => 'string',
        'is_active' => 'bool',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];
}
