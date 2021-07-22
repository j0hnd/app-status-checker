<?php

namespace App\Repositories;

use App\Interfaces\HealthLogRepositoryInterface;
use App\Models\HealthLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HealthLogRepository extends  BaseRepository implements HealthLogRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(HealthLog $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    public function getRecentApplicationLogs($application_id): Collection
    {
        return HealthLog::select('id', 'application_id', 'extras', 'created_at')
            ->where('application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getApplicationLogs($application_id): Collection
    {
        return HealthLog::select('id', 'application_id', 'extras', 'created_at')
            ->where('application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRecentApplicationLog($application_id): Model
    {
        return HealthLog::select('id', 'application_id', 'http_code', 'extras', 'created_at')
            ->where('application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
