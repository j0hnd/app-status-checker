<?php

namespace App\Models;

use App\Scopes\DeletedAtScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndpointParam extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'key',
        'value'
    ];

    protected $guarded = ['application_id'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

//    protected $with = [
//        'applications'
//    ];


//    public function applications()
//    {
//        return $this->belongsTo(Application::class, 'id', 'application_id');
//    }
}
