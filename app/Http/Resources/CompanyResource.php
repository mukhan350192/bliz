<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $s = CompanyTypes::collection(DB::table('company_types')->where('id',$this->types)->get());
        $array = [
        //    'user_id' => $this->user_id,
            'companyName' => $s[0]->name." ".$this->name,
            //'companyType' => $s,
        ];
        if (!is_null($this->bin)){
            $array['bin'] = $this->bin;
        }
        if (!is_null($this->registration)){
            $array['registration'] = $this->registration;
        }
        if (!is_null($this->license)){
            $array['license'] = $this->license;
        }
        return $array;
    }
}
