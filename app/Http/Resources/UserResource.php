<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
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
            'fullName' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
        if ($this->city){
            $array['city'] = $this->city;
        }
        if ($this->address){
            $array['address'] = $this->address;
        }
        if (!$this->image){
            $array['address'] = $this->image;
        }
        if ($this->user_type == 2) {
            $array['companyDetails'] = CompanyResource::collection(DB::table('company_details')->where('user_id',$this->id)->get());
        }

        /*return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'user_type' => $this->user_type,
          //  'city' => CityResource::collection($this->city),
            'address' => $this->address,
            'image' => $this->image,
            'token' => $this->token,
        ];*/
        return $array;
    }
}
