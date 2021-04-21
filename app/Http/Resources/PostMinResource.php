<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PostMinResource extends JsonResource
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
            'updated_at' =>  $this->updated_at,
            'details' => DetailsMinResource::collection(DB::table('details')->where('post_id',$this->id)->get()),
        ];
        if ($this->priority == 2){
            $array['top'] = true;
        }
        return $array;
    }
}
