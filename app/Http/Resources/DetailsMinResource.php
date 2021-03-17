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
        $type = DB::table('type_transport')->where('id',$this->type_transport)->first();
        $type = $type->name;
        $price = DB::table('post_price')->where('post_id',$this->id)->limit(1)->first();
        var_dump($price);
        //echo $price[0];
        echo $price->price_type;
        $currency = DB::table('currency')->where('id',$price->price_type)->first();
        $price = $price->price.' '.$currency->name;

        $array = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'from' => $this->from,
            'to' => $this->to,
            'volume' => $this->volume,
            'net' => $this->net,
            'type_transport' => $type,
            'title' => $this->title,
            'price' => $price,
        ];

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
