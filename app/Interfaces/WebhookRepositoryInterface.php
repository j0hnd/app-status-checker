<?php

namespace App\Interfaces;

use App\Models\Webhook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface WebhookRepositoryInterface
{
    public function all(): Collection;

    public function findByWebhookCode($code): Model;

    public function getActiveWebhooks(): Collection;

    public function delete(Webhook $webhook);
}
