<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StorageMinImage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $count = DB::table('storage_images')->where('storage_id',$this->storage_id)->count();
        $first = DB::table('storage_images')->where('storage_id',$this->storage_id)->select('name')->first();
        $array = [];
        if (isset($first)){
            $array = [
                'count' => $count,
                'image' => $first->name,
            ];
        }


        return $array;
    }
}
