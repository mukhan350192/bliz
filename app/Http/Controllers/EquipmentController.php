<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentMin;
use App\Http\Resources\EquipmentResources;
use App\Models\Equipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    //  получить категории спецтехники
    public function getEquipmentCategory(){
        $data = DB::table('equipment_category')->select('id','name')->get();
        return response()->json($data);
    }

    // получить тип аренды спецтехники
    public function getEquipmentRent(){
        $data = DB::table('equipment_rent')->select('id','name')->get();
        return response()->json($data);
    }

    //получить тип спецтехники
    public function getEquipmentType(){
        $data = DB::table('type_equipment')->select('id','name')->get();
        return response()->json($data);
    }
    public function addEquipment(Request $request){
        $category_id = $request->input('category_id');
        $type_equipment = $request->input('type_equipment');
        $name = $request->input('name');
        $city_id = $request->input('city_id');
        $address = $request->input('address');
        $net = intval($request->input('net'));
        $year = intval($request->input('year'));
        $type_blade = $request->input('type_blade');
        $power = $request->input('power');
        $height = $request->input('height');
        $width = $request->input('width');
        $rise = $request->input('rise');
        $deep = $request->input('deep');
        $description = $request->input('description');
        $price = $request->input('price');
        $currency = $request->input('currency');
        $equipment_rent = $request->input('equipment_rent');
        $token = $request->input('token');
        $image = $request->file('image');
        $result['success'] = false;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$category_id){
                $result['message'] = 'Не передан категория айди';
                break;
            }

            if (!$type_equipment){
                $result['message'] = 'Не передан тип спецтехники';
                break;
            }

            if (!$name){
                $result['message'] = 'Не передан модель спецтехники';
                break;
            }

            if (!$city_id){
                $result['message'] = 'Не передан айди города';
                break;
            }

            if (!$address){
                $result['message'] = 'Не передан адрес';
                break;
            }

            if (!$price){
                $result['message'] = 'Не передан цена';
                break;
            }

            if (!$currency){
                $result['message'] = 'Не передан валюта';
                break;
            }

            if (!$equipment_rent){
                $result['message'] = 'Не передан тип аренды';
                break;
            }

            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            DB::beginTransaction();

            $equipmentID = DB::table('special_equipment')->insertGetId([
                'user_id' => $user->id,
                'category_id' => $category_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$equipmentID){
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }

            $equipmentDetails = DB::table('equipment_details')->insertGetId([
               'equipment_id' => $equipmentID,
                'type_equipment' => $type_equipment,
                'name' => $name,
                'city_id' => $city_id,
                'address' => $address,
                'net' => $net,
                'year' => $year,
                'type_blade' => $type_blade,
                'power' => $power,
                'height' => $height,
                'width' => $width,
                'rise' => $rise,
                'deep' => $deep,
                'description' => $description,
                'price' => $price,
                'price_type' => $equipment_rent,
                'currency' => $currency,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$equipmentDetails){
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }
            if (isset($image)) {
                $allowedfileExtension = ['jpeg', 'jpg', 'png'];
                foreach ($request->file('image') as $file) {

                    $extension = $file->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if (!$check) {
                        $result['message'] = 'Пожалуйста, загружайте только jpeg,jpg,png';
                        break;
                    }

                    $path = $file->store('public/images/equipment/');

                    $fileName = $file->getClientOriginalName();
                    $fileName = sha1(time() . $fileName) . '.' . $file->extension();
                    $file->move($path, $fileName);
                    $imageID = DB::table('equipment_images')->insertGetId([
                        'equipment_id' => $equipmentID,
                        'name' => $fileName,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    if (!$imageID) {
                        DB::rollBack();
                        $result['message'] = 'Попробуйте позже';
                        break;
                    }
                }
            }

            DB::commit();
            $result['success'] = true;
        }while(false);
        return response()->json($result);
    }

    public function getAllEquipment(Request $request){
        $page = intval($request->input('page'));
        $category_id = $request->input('category_id');
        $take = 10;
        if (!$page || $page == 1){
            $page = 1;
            $skip = 0;

        }else{
            $skip = ($page-1)*10;
        }
        if (!$category_id){
            $count = DB::table('special_equipment')->count();
            $data = EquipmentMin::collection(DB::table('special_equipment')->get());
        }else{
            $count = DB::table('special_equipment')->where('category_id',$category_id)->count();
        }
        $result['success'] = true;
        $result['current_page'] = $page;
        $result['max_page'] = ceil($count/$page);
        $result['count'] = $count;
        $result['data'] = $data;
        return response()->json($result);
    }

    public function getEquipmentByID(Request $request){
        $equipment_id = $request->input('equipment_id');
        $result['success'] = false;
        do{
            if (!$equipment_id){
                $result['message'] = 'Не передан айди';
                break;
            }
            $equipment = DB::table('special_equipment')->where('id',$equipment_id)->first();
            if (!$equipment){
                $result['message'] = 'Не найден спецтехника';
                break;
            }
            $data = EquipmentResources::collection(DB::table('special_equipment')->where('id',$equipment_id)->get());
            $result['success'] = true;
            $result['data'] = $data;
        }while(false);
        return response()->json($result);
    }
}
