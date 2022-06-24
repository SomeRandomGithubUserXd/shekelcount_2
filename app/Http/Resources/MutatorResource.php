<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Mutator */
class MutatorResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->mutatorOption->name,
            'transform_visualisation' => $this->getTransformVisualisation(),
            'additional_info' => $this->getAdditionalInfo()
        ];
    }
}
