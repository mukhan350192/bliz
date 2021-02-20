<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StorageResource extends JsonResource
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
            'properties' => StorageMinProperties::collection(DB::table('storage_properties')->where('storage_id',$this->id)->get()),
            'images' => StorageMinImage::collection(DB::table('storage_images')->where('storage_id',$this->id)->limit(1)->get()),
            'updated_at' => date('d.m.Y H:i',strtotime($this->updated_at)),
        ];
    }
}
