<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AuctionMinDetails extends JsonResource
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
            'id' => $this->id,
            'details' => AuctionMinProperties::collection(DB::table('auction_details')->where('auction_id',$this->id)->get()),
            'price_details' => AuctionMinPrice::collection(DB::table('auction_orders')->where('auction_id',$this->id)->get()),
            'updated_at' => date('d.m.Y',strtotime($this->updated_at)),
        ];

        return $array;
    }
}
