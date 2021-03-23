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

        $array = [
            'user' => UserForAuction::collection(User::where('id',$this->customer)->get()),
            'details' => PostMinResource::collection(Post::where('id',$this->post_id)->get()),
            'status' => $status->name,
            'executor' => UserForAuction::collection(User::where('id',$this->executor)->get()),
            'price' => [
                'price' => $this->price,
                'currency' => $this->currency,
            ],
        ];
        return $array;
    }
}
