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
        $price = DB::table('post_price')->where('post_id',$this->id)->first();
        $currency = DB::table('currency')->where('id',$price->price_type)->first();
        $price = $price->price.' '.$currency->name;

        return [
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

    }
}
