<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserForAuction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if (isset($this->image)){
            $array['image'] = $this->image;
        }
        if (isset($this->user_type) && $this->user_type == 1){
            $array['fullName'] = $this->fullName;
            $array['type'] = 'Частное лицо';
            $array['id'] = $this->id;
            $array['phone'] = $this->phone;
            $array['email'] = $this->email;
        }
        if (isset($this->user_type) && $this->user_type == 2){
            $data = DB::table('company_details')
                ->join('company_types','company_details.types','=','company_types.id')
                ->select('company_details.name','company_types.name as companyName')
                ->where('company_details.user_id','=',$this->id)
                ->get();

            $array = [
                'fullName' => $data[0]->companyName. ' '. $data[0]->name,
                'id' => $this->id,
                'type' => 'Юр лицо',
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ];
        }
        return $array;
    }
}
