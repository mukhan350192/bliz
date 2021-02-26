<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $from = CityResource::collection(City::where('id',$this->from)->get());
        $to = CityResource::collection(City::where('id',$this->to)->get());
        $array = [
            'from' =>$this->from,
            'to' => $this->to,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
        if (isset($this->volume)){
            $array['volume'] = $this->volume;
        }
        if (isset($this->net)){
            $array['net'] = $this->net;
        }
        if (isset($this->middle)){
            $array['middle'] = $this->middle;
        }
        if (isset($this->distance)){
            $array['distance'] = $this->distance;
        }
        if (isset($this->duration)){
            $array['duration'] = $this->duration;
        }
        return $array;
    }
}
