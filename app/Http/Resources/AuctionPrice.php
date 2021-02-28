<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AuctionPrice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $auctionDetails = DB::table('auction_orders')
            ->join('currency','auction_orders.currency','=','currency.id')
            ->select('currency.name','auction_orders.price','auction_orders.user_id')
            ->orderBy('price','asc')->get();
        $count = $auctionDetails->count();
        foreach ($auctionDetails as $auction){
            $array['user_id'] = $auction->user_id;
            $array['price'] = $auction->price;
            $array['currency'] = $auction->currency;
        }
        return $array;
    }
}
