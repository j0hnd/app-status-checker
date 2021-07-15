<?php

namespace App\SystemChecks;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class Webhook
{
    public function send($url, $logs): Response
    {
        return Http::withHeaders(['Content-Type: application/json'])->post($url, $logs);
    }
}
