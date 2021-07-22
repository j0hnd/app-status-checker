<?php

namespace App\Interfaces;

use App\Models\HealthLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface HealthLogRepositoryInterface
{
    public function all(): Collection;

    public function getRecentApplicationLogs($application_id): Collection;

    public function getApplicationLogs($application_id): Collection;

    public function getRecentApplicationLog($application_id): Model;
}
