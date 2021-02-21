<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EquipmentResources extends JsonResource
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
            'images' => EquipmentImages::collection(DB::table('equipment_images')->where('equipment_id',$this->id)->get()),
            'details' => EquipmentDetails::collection(DB::table('equipment_details')->where('equipment_id',$this->id)->get()),
            'updated_at' => date('d.m.Y H:i',strtotime($this->updated_at)),
        ];
    }
}
