<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyEmployee extends Model
{
    public $timestamp = false;

    protected $table = 'employee_family';

    protected $fillable = [
        'id',
        'employee_id',
        'name',
        'indentifier',
        'job',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'is_life',
        'is_divorced',
        'relation_status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $cast = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'name' => 'string',
        'indentifier' => 'string',
        'job' => 'string',
        'place_of_birth' => 'string',
        'date_of_birth' => 'date:Y-m-d',
        'religion' => 'string',
        'is_life' => 'bool',
        'is_divorced' => 'bool',
        'relation_status' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];
}
