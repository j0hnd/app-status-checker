<?php

namespace App\Interfaces;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ApplicationRepositoryInterface
{
    public function create(array $attributes): Model;

    public function all(): Collection;

    public function findApplicationsForMonitoring(): Collection;

    public function findApplicationForMonitoring($code): Collection;

    public function findByApplicationCode($code): Model;

    public function findByGroup($group): Collection;

    public function getGroups(): Collection;

    public function delete(Application $application);
}
