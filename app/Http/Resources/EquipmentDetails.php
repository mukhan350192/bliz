<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EquipmentDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $city = City::find($this->city_id)->first();
        $currency = DB::table('currency')->where('id',$this->currency)->first();
        $equipmentRent = DB::table('equipment_rent')->where('id',$this->price_type)->first();
        $array = [
            'type' => $this->type_equipment,
            'name' => $this->name,
            'city' => $city->name,
            'address' => $this->address,
            'price' => $this->price,
            'currency' => $currency->name,
            'equipment_rent' => $equipmentRent->name,
        ];
        if (isset($this->net)){
            $array['net'] = $this->net;
        }
        if (isset($this->year)){
            $array['year'] = $this->year;
        }
        if (isset($this->type_blade)){
            $array['type_blade'] = $this->type_blade;
        }
        if (isset($this->power)){
            $array['power'] = $this->power;
        }
        if (isset($this->height)){
            $array['height'] = $this->height;
        }
        if (isset($this->width)){
            $array['width'] = $this->width;
        }
        if (isset($this->rise)){
            $array['rise'] = $this->rise;
        }
        if (isset($this->deep)){
            $array['deep'] = $this->deep;
        }
        if (isset($this->description)){
            $array['description'] = $this->description;
        }

        return $array;
    }
}
