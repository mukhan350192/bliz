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
        $price = DB::table('auction_orders')->select('currency',DB::raw('MIN(price) as price'))->groupBy('currency')->get();

        $count = DB::table('auction_orders')->count();
        $array = [];

        $price = json_decode($price);

        if (isset($this->price)){
            foreach ($price as $p){
                $array['price'] = $p->price;
                $currency = DB::table('currency')->select('name')->where('id',$p->currency)->first();
                $array['currency'] = $currency->name;
            }
            $array['count'] = $count;
        }
        return $array;
    }
}
