<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = DB::table('currency')->where('id',$this->price_type)->first();
        $paymentType = DB::table('payment_type')->where('id',$this->payment_type)->first();
        return [
            'price' => $this->price . ' '. $currency->name,
            'payment_type' => $paymentType->name,
        ];
    }
}
