<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JWT extends JsonResource
{
    public function toArray($request)
    {
        return [
            'access_token' => $this->resource,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }
}
