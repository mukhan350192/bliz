<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AuctionDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $price = DB::table('auction_orders')
            ->join('currency','auction_orders.currency','=','currency.id')
            ->select('currency.name','auction_orders.price','auction_orders.user_id')
            ->where('auction_orders.auction_id','=',$this->id)
            ->orderBy('price','asc')->get();
        $priceDetail = [];
        $index = 0;
        foreach ($price as $p){
            $priceDetail[$index]['user'] = UserForAuction::collection(User::where('id',$p->user_id)->get());
            $priceDetail[$index]['price'] = $p->price;
            $priceDetail[$index]['currency'] = $p->name;
            $index++;
        }

        return [
            'id' => $this->id,
            'details' => AuctionProperties::collection(DB::table('auction_details')->where('auction_id',$this->id)->get()),
            'price_details' => $priceDetail,
            'updated_at' => strtotime('d.m.Y',strtotime($this->updated_at)),
        ];
    }
}
