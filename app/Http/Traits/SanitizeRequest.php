<?php

namespace App\Http\Traits;

trait SanitizeRequest
{
    private $clean = false;


    public function all($keys = null)
    {
        return $this->sanitize(parent::all());
    }

    protected function sanitize(Array $inputs)
    {
        if ($this->clean) {
            return $inputs;
        }

        foreach ($inputs as $i => $item) {
            if (is_array($item)) {
                continue;
            }

            $inputs[$i] = htmlspecialchars(trim($item), ENT_QUOTES);
        }

        $this->replace($inputs);
        $this->clean = true;

        return $inputs;
    }
}
