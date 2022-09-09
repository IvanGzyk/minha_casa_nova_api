<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImovelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "identify"      => $this->uuid,
            "name"          => $this->name,
            'address'       => $this->address,
            'description'   => $this->description,
            'value'         => $this->value,
        ];
    }
}
