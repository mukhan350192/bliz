<?php

namespace App\Http\Resources;

use App\Models\Detail;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'user' => UserResource::collection(User::where('id', $this->user_id)->get()),
            'details' => DetailsResource::collection(DB::table('details')->where('post_id', $this->id)->get()),
            'additional' => PostAdditional::collection(DB::table('post_additional')->where('post_id',$this->id)->get()),
            'price' => PriceResource::collection(DB::table('post_price')->where('post_id',$this->id)->get()),
            'updated_at' => $this->updated_at,
        ];

        return $array;
    }
}
