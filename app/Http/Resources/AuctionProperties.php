<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AuctionProperties extends JsonResource
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
            'date_finish' => date('d.m.Y',strtotime($this->date_finish)),
            'date_start' => date('d.m.Y',strtotime($this->date_start)),
            'date_end' => date('d.m.Y',strtotime($this->date_end)),
            'from_city' => $this->from_city,
            'to_city' => $this->to_city,

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
        if (isset($this->type_transport)){
            $array['type_transport'] = $this->type_transport;
        }
        if (isset($this->title)){
            $array['title'] = $this->title;
        }
        if (isset($this->quantity)){
            $array['quantity'] = $this->quantity;
        }
        if (isset($this->net)){
            $array['net'] = $this->net;
        }
        if (isset($this->volume)){
            $array['volume'] = $this->volume;
        }
        if (isset($this->width)){
            $array['width'] = $this->width;
        }
        if (isset($this->length)){
            $array['length'] = $this->length;
        }
        if (isset($this->height)){
            $array['height'] = $this->height;
        }
    /*    if (isset($this->price)){
            $array['price'] = $this->price;
            $currency = DB::table('currency')->select('name')->where('id',$this->currency)->get();
            $array['currency'] = $currency->name;
        }
   /    if (isset($this->payment_type)){
            $payment = DB::table('payment_type')->select('name')->where('id',$this->payment_type)->get();
            $array['payment_type'] = $payment->name;
        }
*/
        return $array;
    }
}
