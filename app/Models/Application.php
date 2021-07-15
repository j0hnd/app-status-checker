<?php

namespace App\Models;

use App\Scopes\DeletedAtScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use DB;

class Application extends Model
{
    const APPLICATION_IS_NOT_MONITORED = 0;
    const APPLICATION_IS_MONITORED = 1;

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'application_url',
        'application_type',
        'is_monitored',
        'frequency',
        'added_by'
    ];

    protected $guarded = [
        'application_code'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $with = [
        'webhooks',
        'endpoint_detail',
        'endpoint_params'
    ];

    protected static $endpoint_info = [];


    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        Application::creating(function (Application $application) {
            $application->application_code = Uuid::uuid4();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new DeletedAtScope());
    }

    public function webhooks()
    {
        return $this->hasMany(WebhookApplication::class, 'application_id', 'id');
    }

    public function endpoint_detail()
    {
        return $this->hasOne(EndpointDetail::class,  'application_id', 'id');
    }

    public function endpoint_params()
    {
        return $this->hasMany(EndpointParam::class, 'application_id', 'id');
    }

    public function save($options = [])
    {
        $success = false;

        try {
            DB::beginTransaction();

            $endpoint_info = self::get_endpoint_info();

            parent::save();

            if (! empty($endpoint_info)) {
                // save endpoint details
                if ($this->endpoint_detail) {
                    $this->endpoint_detail->method = $endpoint_info['details']['method'];
                    $this->endpoint_detail->field_type = $endpoint_info['details']['field_type'];
                    $this->endpoint_detail->token_url = $endpoint_info['details']['token_url'];
                    $this->endpoint_detail->authorization_type = $endpoint_info['details']['authorization_type'];

                    if ($endpoint_info['details']['authorization_type'] == "basic_auth") {
                        $this->endpoint_detail->username = $endpoint_info['details']['username'];
                        $this->endpoint_detail->password = $endpoint_info['details']['password'];
                    }

                    if ($endpoint_info['details']['authorization_type'] == "api_key_auth") {
                        $this->endpoint_detail->app_key = $endpoint_info['details']['app_key'];
                        $this->endpoint_detail->app_secret = $endpoint_info['details']['app_secret'];
                    }

                    $this->endpoint_detail()->save($this->endpoint_detail);
                } else {
                    $endpoint_info['details']['application_id'] = $this->id;
                    $endpoint_details = new EndpointDetail($endpoint_info['details']);
                    $this->endpoint_detail()->save($endpoint_details);
                }

                // save endpoint params
                if (! empty($endpoint_info['params'])) {
                    $this->endpoint_params()->delete();

                    foreach ($endpoint_info['params'] as  $param) {
                        foreach ($param as $index => $_param) {
                            if ($_param and $endpoint_info['params']['value']) {
                                $data['application_id'] = $this->id;
                                $data['key'] = $_param;
                                $data['value'] = $endpoint_info['params']['value'][$index];

                                $endpoint_params = new EndpointParam($data);
                                $this->endpoint_params()->save($endpoint_params);

                                unset($data);
                            }
                        }

                        break;
                    }

                }

                if (is_null($endpoint_info['params'])) {
                    $this->endpoint_params()->delete();
                }
            }

            DB::commit();
            $success = true;
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
        }

        return $success;
    }

    public static function set_endpoint_info($endpoint_info)
    {
        self::$endpoint_info = $endpoint_info;
    }

    public static function get_endpoint_info()
    {
        return self::$endpoint_info;
    }
}