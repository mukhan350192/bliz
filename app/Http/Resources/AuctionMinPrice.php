<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AuctionMinPrice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $price = DB::table('auction_orders')->select('id',DB::raw('MIN(price) as price'))->groupBy('id')->where('auction_id',$this->auction_id)->get();
        var_dump($price);
        $count = DB::table('auction_orders')->where('auction_id',$this->auction_id)->count();
        $array = [];

        $price = json_decode($price);
        $min = 0;
        if (isset($this->price)){
            foreach ($price as $p){
                $min = $p->price;
                $array['price'] = $p->price;
                $currency = DB::table('currency')->select('name')->where('id',$p->currency)->first();
                $array['currency'] = $currency->name;
                if ($p->price < $min){
                    $array['price'] = $p->price;
                    $currency = DB::table('currency')->select('name')->where('id',$p->currency)->first();
                    $array['currency'] = $currency->name;
                }
            }
            $array['count'] = $count;
        }
        return $array;
    }
}
