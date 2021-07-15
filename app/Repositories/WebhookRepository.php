<?php

namespace App\Repositories;

use App\Interfaces\WebhookRepositoryInterface;
use App\Models\Webhook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WebhookRepository extends  BaseRepository implements WebhookRepositoryInterface
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
    public function __construct(Webhook $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return Webhook::orderBy('created_at', 'desc')->get();
    }

    public function findByWebhookCode($code): Model
    {
        return Webhook::where('webhook_code', $code)->firstOrFail();
    }

    public function getActiveWebhooks(): Collection
    {
        return Webhook::active()->get();
    }

    public function delete(Webhook $webhook)
    {
        return $webhook->delete();
    }
}
