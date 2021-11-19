<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PostWithoutSubscription extends JsonResource
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
            'details' => DetailsResource::collection(DB::table('details')->where('post_id', $this->id)->get()),
            'additional' => PostAdditional::collection(DB::table('post_additional')->where('post_id',$this->id)->get()),
            'price' => PriceResource::collection(DB::table('post_price')->where('post_id',$this->id)->get()),
            'updated_at' => $this->updated_at,
        ];
        if (isset($this->priority) && $this->priority == 2){
            $array['top'] = true;
        }

        return $array;
    }
}
