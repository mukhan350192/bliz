<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DetailsMinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'from' => $this->from,
            'to' => $this->to,
            'volume' => $this->volume,
            'net' => $this->net,
            'title' => $this->title,
        ];
        $type = DB::table('type_transport')->where('id',$this->type_transport)->first();
        if (isset($type)){
            $array['type'] = $type->name;
        }
        $price = DB::table('post_price')->where('post_id',$this->post_id)->get();
        foreach ($price as $p){
            $price_type =  $p->price_type;
            $array['price_value'] = $p->price;
        }
        if (isset($price_type)){
            $currency = DB::table('currency')->where('id',$price_type)->first();
            if (isset($currency)){
                $array['price_currency'] = $currency->name;
            }
        }

        if ($this->from_string){
            $array['from_string'] = $this->from_string;
        }
        if ($this->to_string){
            $array['to_string'] = $this->to_string;
        }
        if ($this->distance){
            $array['distance'] = $this->distance;
        }
        if ($this->duration){
            $array['duration'] = $this->duration;
        }

        return $array;

    }
}
