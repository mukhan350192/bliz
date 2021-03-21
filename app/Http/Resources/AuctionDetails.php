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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $price = DB::table('auction_orders')
            ->join('currency', 'auction_orders.currency', '=', 'currency.id')
            ->select('currency.name', 'auction_orders.price', 'auction_orders.user_id', 'auction_orders.created_at')
            ->where('auction_orders.auction_id', '=', $this->id)
            ->orderBy('price', 'asc')->get();
        $priceDetail = [];
        $index = 0;
        $user_id = [];
        foreach ($price as $p) {
            $priceDetail[$index]['user'] = UserForAuction::collection(User::where('id', $p->user_id)->get());
            $priceDetail[$index]['price'] = $p->price;
            $priceDetail[$index]['currency'] = $p->name;
            $priceDetail[$index]['created'] = date('d.m.Y H:i', strtotime($p->created_at));
            $user_id[] = ['user_id' => $p->user_id];
            $index++;
        }


        return [
            'id' => $this->id,
            'details' => AuctionProperties::collection(DB::table('auction_details')->where('auction_id', $this->id)->get()),
            'user_id' => $user_id,
            'price_details' => $priceDetail,
            'user' => UserForAuction::collection(User::where('id', $this->user_id)->get()),
            'updated_at' => date('d.m.Y H:i:s', strtotime($this->updated_at)),
        ];
    }
}
