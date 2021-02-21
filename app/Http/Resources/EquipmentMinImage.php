<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EquipmentMinImage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $count = DB::table('equipment_images')->where('equipment_id',$this->equipment_id)->count();
        $first = DB::table('equipment_images')->where('equipment_id',$this->equipment_id)->select('name')->first();
        $array = [];
        if (isset($first)){
            $array = [
                'count' => $count,
                'image' => $first->name,
            ];
        }


        return $array;
    }
}
