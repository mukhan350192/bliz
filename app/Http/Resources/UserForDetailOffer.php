<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserForDetailOffer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $user = User::find($this->executor);
        if ($user->user_type == 1){
            $array['price'] = $this->price;
            $array['currency'] = $this->currency;
            $array['fullName'] = $user->fullName;
            $array['type'] = 'Частное лицо';
            $array['user_id'] = $user->id;
        }
        if ($user->user_type == 2){
            $data = DB::table('company_details')
                ->join('company_types','company_details.types','=','company_types.id')
                ->select('company_details.name','company_types.name as companyName')
                ->where('company_details.user_id','=',$user->id)
                ->get();;
            $array = [
                'price' => $this->price,
                'currency' => $this->currency,
                'fullName' => $data[0]->companyName. ' '. $data[0]->name,
                'user_id' => $user->id,
            ];
        }

        return $array;
    }
}
