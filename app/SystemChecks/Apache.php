<?php

namespace App\SystemChecks;

use Illuminate\Support\Facades\Log;


class Apache
{
    public function resolve($url, $options = null): array
    {
        try {
            // set GET URL with parameters if exist
            if ($options['method'] == 'get' and $options['field_type'] == "params" and (! empty($options['fields']) or ! is_null($options['fields']))) {
                $url = "{$url}?" . http_build_query($options['fields']);
            }

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT,10);

            // set POST fields
            if ($options['method'] == "post" and $options['field_type'] == "body" and (! empty($options['fields']) or ! is_null($options['fields']))) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['fields']));
            }

            // set the headers that we want our cURL client to use.
            if (isset($options['bearer_token']) or ! is_null($options['bearer_token'])) {
                $headers = [
                    'Authorization: Bearer ' . $options['bearer_token']
                ];

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);


            $output = curl_exec($ch);

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
        } catch (\Exception $exception) {
            Log::error("Error on line number " . $exception->getLine() . " - " . $exception->getMessage());
        }

        return [
            "http_code" => $http_code,
            "extras" => serialize($output)
        ];
    }
}
