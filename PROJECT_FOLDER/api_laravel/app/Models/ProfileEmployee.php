<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileEmployee extends Model
{
    public $timestamp = false;

    protected $table = 'employee_profile';

    protected $fillable = [
        "id",
        "employee_id",
        "place_of_birth",
        "date_of_birth",
        "gender",
        "is_married",
        "prof_pict",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    protected $cast = [
        "id" => 'integer',
        "employee_id" => 'integer',
        "place_of_birth" => 'string',
        "date_of_birth" => 'date:Y-m-d',
        "gender" => 'string',
        "is_married" => 'bool',
        "prof_pict" => 'string',
        "created_by" => 'string',
        "updated_by" => 'string',
        "created_at" => 'date:Y-m-d',
        "updated_at" => 'date:Y-m-d',
    ];
}
