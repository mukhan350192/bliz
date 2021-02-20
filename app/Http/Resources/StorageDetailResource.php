<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StorageDetailResource extends JsonResource
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
            'id' => $this->id,
            'user' => UserResource::collection(User::where('id',$this->user_id)->get()),
            'properties' => StorageProperties::collection(DB::table('storage_properties')->where('storage_id',$this->id)->get()),
            'images' => StorageImages::collection(DB::table('storage_images')->where('storage_id',$this->id)->get()),
            'additional' => StorageAdditional::collection(DB::table('storage_additional')->where('storage_id',$this->id)->get()),
            'updated_at' => date('d.m.Y H:i',strtotime($this->updated_at)),
        ];
    }
}
