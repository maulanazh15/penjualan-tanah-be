<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandResource extends JsonResource
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
            'judul' => $this->judul,
            'harga' => intval($this->harga),
            'luas' => intval($this->luas),
            'foto_tanah' => $this->foto_tanah,
            'prov_id' => intval($this->prov_id) ?? '',
            'city_id' => intval($this->city_id) ?? '',
            'dis_id' => intval($this->dis_id) ?? '',
            'subdis_id' => intval($this->subdis_id) ?? '',
            'alamat' => $this->alamat,
            'keterangan' => $this->keterangan,
            'user' => $this->user,

        ];
    }
}
