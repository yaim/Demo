<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginAttempt extends JsonResource
{
    public function toArray($request)
    {
        return [
            'attempted_at' => $this->attempted_at,
        ];
    }
}
