<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'http_code',
        'extras'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $guarded = ['application_id'];
}
