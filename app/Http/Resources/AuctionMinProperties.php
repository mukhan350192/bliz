<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuctionMinProperties extends JsonResource
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
            'date_finish' => $this->date_finish,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'from_city' => $this->from_city,
            'to_city' => $this->to_city,
            'title' => $this->title,
        ];
        if (isset($this->from_string)){
            $array['from_string'] = $this->from_string;
        }
        if (isset($this->to_string)){
            $array['to_string'] = $this->to_string;
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
