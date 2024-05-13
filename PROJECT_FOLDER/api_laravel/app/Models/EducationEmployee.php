<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationEmployee extends Model
{
    public $timestamp = false;

    protected $table = 'education';

    protected $fillable = [
        'id',
        'employee_id',
        'name',
        'level',
        'description',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $cast = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'name' => 'string',
        'level' => 'string',
        'description' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];
}
