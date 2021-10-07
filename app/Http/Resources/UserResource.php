<?php

namespace App\Http\Resources;

use App\Models\Country;
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
        if ($this->country_id){
            $country_name = Country::find($this->country_id);
            $array['country_name'] = $country_name->name;
            $array['country_id'] = $this->country_id;
            $array['short_code'] = $country_name->short_code;
        }

        if ($this->city){
            $array['city'] = $this->city;
        }
        if ($this->city_string){
            $array['city_string'] = $this->city_string;
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
        $additional_phone = DB::table('user_phones')->where('user_id',$this->id)->get();
        if (isset($additional_phone)){
            foreach ($additional_phone as $add){
                $array['additional_phones'] = $add->phone;
            }
        }
        $sub = DB::table('subscription')->where('user_id',$this->id)->first();
        if (isset($sub)){
            $array['subscription'] = true;
            $array['end_subscription_date'] = date('d.m.Y',strtotime($sub->end));
        }
        return $array;
    }
}
