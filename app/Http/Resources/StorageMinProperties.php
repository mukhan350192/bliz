<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StorageMinProperties extends JsonResource
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
        $price_type = DB::table('type_rent')->where('id',$this->price_type)->first();
        $array =[
            'area' => $this->area,
            'total_area' => $this->total_area,
            'price' => $this->price.' '.$currency->name. '/'.$price_type->name,

        ];
        if (isset($this->class)){
            $array['class'] =  $this->class;
        }
        if (isset($this->type_storage)){
            $array['type'] = $this->type_storage;
        }
        return $array;
    }
}
