<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StorageProperties extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = DB::table('currency')->where('id', $this->currency)->first();
        $price_type = DB::table('type_rent')->where('id', $this->price_type)->first();

        $array = [
            'area' => $this->area,
            'total_area' => $this->total_area,
            'price' => $this->price . ' ' . $currency->name . '/' . $price_type->name,

        ];

        if (isset($this->year)) {
            $array['year'] = $this->year;
        }
        if (isset($this->city_id)){
            $array['city'] = $this->city_id;
        }
        if (isset($this->address)) {
            $array['address'] = $this->address;
        }
        if (isset($this->floor)) {
            $array['floor'] = $this->floor;
        }
        if (isset($this->floor_type)) {
            $array['floor_type'] = $this->floor_type;
        }
        if (isset($parking_cargo)) {
            $array['parking_cargo'] = $this->parking_cargo;
        }
        if (isset($this->region)){
            $array['region'] = $this->region;
        }
        if (isset($parking_car)) {
            $array['parking_car'] = $this->parking_car;
        }
        if (isset($this->floor_load)) {
            $array['floor_load'] = $this->floor_load;
        }
        return $array;
    }
}
