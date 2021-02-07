<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PostAdditional extends JsonResource
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
        if (!empty($this->documents)){
            $docs = explode(',',$this->documents);
            $docsArray = [];
            foreach ($docs as $doc){
                $s =DB::table('post_document')->where('id',$doc)->first();
                $docsArray[] = $s->name;
            }
            $array['docs'] = $docsArray;
        }
        if (!empty($this->loading)){
            $docs = explode(',',$this->loading);
            $docsArray = [];
            foreach ($docs as $doc){
                $s =DB::table('post_loading')->where('id',$doc)->first();
                $docsArray[] = $s->name;
            }
            $array['loading'] = $docsArray;
        }
        if (!empty($this->condition)){
            var_dump($this->condition);
            $docs = explode(',',$this->condition);
            $docsArray = [];
            foreach ($docs as $doc){
                $s =DB::table('post_condition')->where('id',$doc)->first();
                $docsArray[] = $s->name;
            }
            $array['condition'] = $docsArray;
        }
        if (!empty($this->addition)){
            $docs = explode(',',$this->addition);
            $docsArray = [];
            foreach ($docs as $doc){
                $s =DB::table('post_addition')->where('id',$doc)->first();
                $docsArray[] = $s->name;
            }
            $array['addition'] = $docsArray;
        }
        return $array;
    }
}
