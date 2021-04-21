<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EquipmentMinDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = DB::table('currency')->where('id',$this->currency)->first();
        $price_type = DB::table('equipment_rent')->where('id',$this->price_type)->first();
        return [
            'name' => $this->name,
            'price' => $this->price . $currency->name. '/'. $price_type->name,
            'city' => $this->city_id,
            'address' => $this->address,
            'net' => $this->net,
      //      'power' => $this->power,
        ];
    }
}
