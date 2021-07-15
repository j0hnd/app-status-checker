<?php

namespace App\Models;

use App\Scopes\DeletedAtScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EndpointDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'method',
        'field_type',
        'current_token',
        'token_url',
        'content_type',
        'authorization_type',
        'app_key',
        'app_secret',
        'username',
        'password'
    ];

    protected $guarded = ['application_id'];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

//    protected $with = [
//        'application'
//    ];


    protected static function booted()
    {
        static::addGlobalScope(new DeletedAtScope());
    }


//    public function application()
//    {
//        return $this->belongsTo(Application::class,  'application_id', 'id');
//    }
}
