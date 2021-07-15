<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_id',
        'application_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function webhooks()
    {
        return $this->belongsTo(Webhook::class, 'webhook_id', 'id');
    }

    public function applications()
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }
}
