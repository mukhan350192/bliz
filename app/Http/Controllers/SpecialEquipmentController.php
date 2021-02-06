<?php

namespace App\Http\Controllers;

use App\Models\SpecialEquipment;
use Illuminate\Http\Request;

class SpecialEquipmentController extends Controller
{
    public function getEquipment(){
        $equipment = SpecialEquipment::all('id','name');
        return response()->json($equipment);
    }
}
