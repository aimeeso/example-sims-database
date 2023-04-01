<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "pack" => new PackResource($this->pack),
            "name"=> $this->name,
            "description"=> $this->description,
            "image_path"=> $this->image_path,
            "release_date"=> $this->release_date,
            "eu_release_date"=> $this->eu_release_date,
            "console_release_date"=> $this->console_release_date,
        ];
    }
}
