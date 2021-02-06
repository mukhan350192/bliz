<?php

namespace App\Http\Controllers;

use App\Models\SpecialEquipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialEquipmentController extends Controller
{
    public function getEquipment(){
        $equipment = DB::table('equipment_category')->select('id','name')->get();
        return response()->json($equipment);
    }

    public function addEquipment(Request $request){
        $token = $request->input('token');
        $name = $request->input('name');
        $category_id = $request->input('category_id');
        $net = $request->input('net');
        $mobility = $request->input('mobility');
        $volume = $request->input('volume');
        $image = $request->file('image');
        $price = $request->input('price');
        $price_type = $request->input('price_type');
        $city_id = $request->input('city_id');
        $address = $request->input('address');
        $result['success'] = false;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$name){
                $result['message'] = 'Не передан название техники';
                break;
            }
            if (!$category_id){
                $result['message'] = 'Не передан категория';
                break;
            }
            if (!$net){
                $result['message'] = 'Не передан масса техники';
                break;
            }
            if (!$mobility){
                $result['message'] = 'Не передан мобильность техники';
                break;
            }
            if (!$volume){
                $result['message'] = 'Не передан емкость ковша';
                break;
            }
            if (!$price){
                $result['message'] = 'Не передан цена';
                break;
            }
            if (!$price_type){
                $result['message'] = 'Не передан тип цены';
                break;
            }
            if (!$city_id){
                $result['message'] = 'Не передан номер города';
                break;
            }
            if (!$address){
                $result['message'] = 'Не передан адрес';
                break;
            }

            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            DB::beginTransaction();
            $equipment = SpecialEquipment::create([
                'name' => $name,
                'category_id' => $category_id,
                'user_id' => $user->id,
                'net' => $net,
                'volume' => $volume,
                'price' => $price,
                'price_type' => $price_type,
                'mobility' => $mobility,
                'city_id' => $city_id,
                'address' => $address,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$equipment){
                $result['message'] = 'Попробуйте позже';
                break;
            }
            if (isset($image)){
                $equipmentImage = $image->getClientOriginalName();
                $equipmentImage = sha1(time() . $equipmentImage) . '.' . $request->file('image')->extension();

                $destinationPath = public_path('/images/equipment/');
                $image->move($destinationPath, $equipmentImage);
                $equipment->image = $equipmentImage;
                $equipment->save();
            }
            $result['success'] = true;
            DB::commit();
        }while(false);

        return response()->json($result);
    }
}
