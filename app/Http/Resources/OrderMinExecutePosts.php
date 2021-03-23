<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class OrderMinExecutePosts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status = DB::table('order_status')->where('id',$this->status)->first();
        $count = DB::table('orders')
            ->where('customer',$this->customer)
            ->where('post_id',$this->post_id)
            ->where('status',1)
            ->count();
        $array = [
            'order_id' => $this->id,
            'user' => UserForAuction::collection(User::where('id',$this->customer)->get()),
            'details' => PostMinResource::collection(Post::where('id',$this->post_id)->get()),
            'status' => $status->name,
            'count' => $count,
            'price' => [
                'price' => $this->price,
                'currency' => $this->currency,
            ],
        ];
        return $array;
    }
}
