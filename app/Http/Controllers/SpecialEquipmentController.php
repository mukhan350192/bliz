<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentMin;
use App\Models\Equipment;
use App\Models\SpecialEquipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialEquipmentController extends Controller
{
    public function getEquipmentCategory(){
        $equipment = DB::table('equipment_category')->select('id','name')->get();
        return response()->json($equipment);
    }

    public function filterEquipment(Request $request){
        $category_id = $request->input('category_id');
        $city_id = $request->input('city_id');
        $net_start = $request->input('net_start');
        $net_end = $request->input('net_end');
        $year_start = $request->input('year_start');
        $year_end = $request->input('year_end');
        $price_start = $request->input('price_start');
        $price_end = $request->input('price_end');
        $page = $request->input('page');



        $sql = "SELECT * FROM special_equipment AS s JOIN equipment_details AS ed ON s.id = ed.equipment_id WHERE s.id>0";
        if (isset($category_id)){
            $sql .= " AND s.category_id = $category_id";
        }
        if (isset($city_id)){
            $sql .= " AND ed.city_id = '$city_id'";
        }
        if (isset($net_start)){
            $sql .= " AND ed.net>=$net_start";
        }
        if (isset($net_end)){
            $sql .= " AND ed.net <=$net_end";
        }
        if (isset($price_start)){
            $sql .= " AND ed.price>=$price_start";
        }
        if (isset($price_end)){
            $sql .= " AND ed.price <=$price_end";
        }
        if (isset($year_start)){
            $sql .= " AND ed.year>=$year_start";
        }
        if (isset($year_end)){
            $sql .= " AND ed.year <=$year_end";
        }
        $skip = 0;
        $take = 10;
        if (!$page || $page == 1) {
            $page = 1;
            $skip = 0;
        } else {
            $skip = ($page - 1) * 10;
        }
        $results = DB::select($sql);
        $count = count($results);
        $ids = [];
        foreach ($results as $r){
            array_push($ids,$r->equipment_id);
        }
        $ids = implode(",",$ids);
        var_dump($ids);
        $ss = DB::table('special_equipment')->whereIn('id',[$ids])->skip($skip)->take($take)->orderByDesc('updated_at')->get();
        var_dump($ss);
        $data = EquipmentMin::collection(DB::table('special_equipment')->whereIn('id',[$ids])->skip($skip)->take($take)->orderByDesc('updated_at')->get());
        $result = [
            'success' => true,
            'count' => $count,
            'data' => $data,
            'page' => $page,
            'max_page' => ceil($count/10),
        ];

        return response()->json($result);
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

    public function getEquipment(Request $request){
        $category_id = $request->input('category_id');
        $page = intval($request->input('page'));
        $result['success'] = false;
        if (!$page || $page == 1) {
            $page = 1;
            $skip = 0;
            $take = 10;
        } else {
            $skip = ($page - 1) * 10;
            $take = ($page - 1) * 10;
        }
        if (!$category_id){
            $equipment = SpecialEquipment::skip($skip)->take($take)->get();
            $count = SpecialEquipment::count();
        }else{
            $equipment = SpecialEquipment::where('category_id',$category_id)->skip($skip)->take($take)->get();
            $count = SpecialEquipment::where('category_id',$category_id)->count();
        }
        foreach ($equipment as $eq){
            $user = User::find($eq->user_id);
            if ($user->user_type == 2){

            }
        }
    }
}
