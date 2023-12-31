<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'photo_profile' => $this->photo_profile ?? '',
            'prov_id' => intval($this->prov_id) ?? '',
            'city_id' => intval($this->city_id) ?? '',
            'dis_id' => intval($this->dis_id) ?? '',
            'subdis_id' => intval($this->subdis_id) ?? '',

        ];
    }
}
