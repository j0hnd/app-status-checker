<?php

namespace App\Repositories;

use App\Models\Application;
use App\Interfaces\ApplicationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ApplicationRepository extends  BaseRepository implements ApplicationRepositoryInterface
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
    public function __construct(Application $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function all(): Collection
    {
        return Application::select('id', 'name', 'application_code', 'application_url', 'application_type', 'is_monitored')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findApplicationsForMonitoring(): Collection
    {
        return Application::with('health_logs')
            ->select('id', 'name', 'application_code', 'application_url', 'application_type', 'is_monitored', 'frequency')
            ->where('is_monitored', 1)
            ->get();
    }

    public function findApplicationForMonitoring($code): Collection
    {
        return Application::with('health_logs')
            ->select('id', 'name', 'application_code', 'application_url', 'application_type', 'is_monitored', 'frequency')
            ->where('application_code', $code)
            ->where('is_monitored', 1)
            ->get();
    }

    public function findByApplicationCode($code): Model
    {
        return Application::where('application_code', $code)->firstOrFail();
    }

    public function delete(Application $application)
    {
        return $application->delete();
    }
}
