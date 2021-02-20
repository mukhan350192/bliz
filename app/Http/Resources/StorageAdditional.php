<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StorageAdditional extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [];

        $fireSystem = [];
        if (!empty($this->fire_system)){
            $length = strlen($this->fire_system);
            if ($length == 1){
                $s =DB::table('storage_fire_system')->where('id',$this->fire_system)->first();
                $docsArray[] = $s->name;
            }else{
                $docs = explode(',',$this->fire_system);
                $docsArray = [];
                foreach ($docs as $doc){
                    $s =DB::table('storage_fire_system')->where('id',$doc)->first();
                    $docsArray[] = $s->name;
                }
            }
            $array['fire_system'] = $docsArray;
        }

        if (!empty($this->ventilation)){
            $length = strlen($this->ventilation);
            var_dump($length);
          if ($length == 1){
                echo 'yes';
                $s =DB::table('storage_ventilation')->where('id',$this->ventilation)->first();
                $ventArray[]=$s->name;
            }else{
                $docs = explode(',',$this->ventilation);
                $docsArray = [];
                foreach ($docs as $doc){
                    $s =DB::table('storage_ventilation')->where('id',$doc)->first();
                    $ventArray[] = $s->name;
                }
            }
            $array['ventilation'] = $ventArray;
        }

        if (isset($this->fire_alarm) && $this->fire_alarm == 1){
            $array['fire_alarm'] = true;
        }
        if (isset($this->security_alarm) && $this->security_alarm == 1){
            $array['security_alarm'] = true;
        }
        if (isset($this->security_area_transport) && $this->security_area_transport == 1){
            $array['security_area_transport'] = true;
        }
        if (isset($this->inline_blocks) && $this->inline_blocks == 1){
            $array['inline_blocks'] = true;
        }
        if (isset($this->rack) && $this->rack == 1){
            $array['rack'] = true;
        }
        if (isset($this->ramp) && $this->ramp == 1){
            $array['ramp'] = true;
        }


/*        $array = [
            'ventilation' => $this->ventilation,
            'fire_alarm' => $this->fire_alarm,
            'security_alarm' => $this->security_alarm,
            'security_area_transport' => $this->security_area_transport,
            'inline_blocks' => $this->inline_blocks,
            'rack' => $this->rack,
            'ramp' => $this->ramp,
        ];*/
        return $array;
    }
}
