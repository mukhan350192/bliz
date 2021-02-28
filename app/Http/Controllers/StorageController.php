<?php

namespace App\Http\Controllers;

use App\Http\Resources\StorageDetailResource;
use App\Http\Resources\StorageResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Storage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageController extends Controller
{
    public function updateStorage(Request $request)
    {
        $name = $request->input('name');
        $area = $request->input('area');
        $total_area = $request->input('total_area');
        $class = $request->input('class');
        $type = $request->input('type_storage');
        $year = $request->input('year');
        $city_id = $request->input('city_id');
        $address = $request->input('address');
        $floor = $request->input('floor');
        $floor_type = $request->input('floor_type');
        $warning = $request->input('warning');
        $warning_area = $request->input('warning_area');
        $storage_id = $request->input('storage_id');
        $price = $request->input('price');
        $rentTypeID = $request->input('rentTypeID');
        $token = $request->input('token');
        $result['success'] = true;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$name) {
                $result['message'] = 'Не передан имя';
                break;
            }

            if (!$city_id) {
                $result['message'] = 'Не передан город';
                break;
            }

            if (!$address) {
                $result['message'] = 'Не передан адрес';
                break;
            }

            if (!$storage_id) {
                $result['message'] = 'Не передан склад';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $storage = Storage::find($storage_id);
            if (!$storage) {
                $result['message'] = 'Не найден склад';
                break;
            }
            DB::beginTransaction();
            $update = DB::table('storage_properties')->where('id', $storage->id)
                ->update([
                    'area' => $area,
                    'total_area' => $total_area,
                    'class' => $class,
                    'type_storage' => $type,
                    'year' => $year,
                    'city_id' => $city_id,
                    'address' => $address,
                    'floor' => $floor,
                    'floor_type' => $floor_type,
                    'warning' => $warning,
                    'warning_area' => $warning_area,
                    'price' => $price,
                    'rentTypeID' => $rentTypeID,
                    'updated_at' => Carbon::now(),
                ]);
            if (!$update) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            DB::commit();
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function getAllOwnStorage(Request $request)
    {
        $token = $request->input('token');

        $result['success'] = true;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }

            $storages = DB::table('storage')
                ->join('storage_properties', 'storage.property_id', '=', 'storage_properties.id')
                ->where('user_id', $user->id)
                ->get();
            $data = [];
            $cities = [];
            $city = City::all();
            foreach ($city as $c) {
                $cities[$c->id] = $c->name;
            }


            $typeRent = DB::table('type_rent')->get();
            $rentCollection = [];
            foreach ($typeRent as $ty) {
                $rentCollection[$ty->id] = $ty->name;
            }
            $imagesList = [];
            $images = DB::table('storage_images')->select('storage_id', 'name')->get()->unique('storage_id');
            foreach ($images as $image) {
                $imagesList[$image->storage_id] = $image->name;
            }
            $storageID = [];
            $index = 0;
            foreach ($storages as $storage) {

                $data[$index]['city'] = $cities[$storage->city_id];
                $data[$index]['address'] = $storage->address;
                $data[$index]['price'] = $storage->price . 'тг / ' . $rentCollection[$storage->rentTypeID];
                $data[$index]['area'] = $storage->area;
                $data[$index]['total_area'] = $storage->total_area;
                if (isset($storage->year)) {
                    $data[$index]['year'] = $storage->year . ' г.';
                }
                if (isset($storage->class)) {
                    $data[$index]['class'] = $storage->class;
                }
                if (isset($storage->type_storage)) {
                    $data[$index]['type_storage'] = $storage->type_storage;
                }
                if (isset($storage->floor)) {
                    $data[$index]['floor'] = $storage->floor . ' этаж';
                }
                if (isset($storage->floor_type)) {
                    $data[$index]['floor_type'] = $storage->floor_type . ' мест';
                }
                if (isset($storage->warning)) {
                    $data[$index]['warning'] = $storage->warning . ' мест';
                }
                if (isset($storage->warning_area)) {
                    $data[$index]['warning_area'] = $storage->warning_area . ' м';
                }
                if (isset($imagesList[$storage->id])) {
                    $data[$index]['image'] = 'https://test.money-men.kz/images/storage/' . $imagesList[$storage->id];
                }

                $index = $index + 1;
            }
            $result['data'] = $data;
        } while (false);
        return response()->json($result);
    }

    public function getRentType(Request $request)
    {
        $rent = DB::table('type_rent')->select('id', 'name')->get();
        return response()->json($rent);
    }

    public function addImageToStorage(Request $request)
    {
        $image = $request->file('image');
        $storageID = $request->input('storage_id');
        $token = $request->input('token');
        $result['success'] = true;

        do {
            if (!$image) {
                $result['message'] = 'Не передан файл';
                break;
            }
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }

            $storage = Storage::find($storageID);
            if (!$storage) {
                $result['message'] = 'Не найден склад';
                break;
            }

            DB::beginTransaction();
            $storageImage = $image->getClientOriginalName();
            $storageImage = sha1(time() . $storageImage) . '.' . $request->file('image')->extension();

            $destinationPath = public_path('/images/storage/');
            $image->move($destinationPath, $storageImage);
            $imageID = DB::table('storage_images')->insertGetId([
                'name' => $storageImage,
                'storage_id' => $storageID,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$imageID) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            DB::commit();
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function addStorage(Request $request)
    {
        $token = $request->input('token');
        $image = $request->file('image');

        $area = $request->input('area');
        $totalArea = $request->input('totalArea');
        $class = $request->input('class');
        $type = $request->input('type');
        $year = $request->input('year');
        $city_id = $request->input('city_id');
        $address = $request->input('address');
        $floor = $request->input('floor');
        $parking_car = $request->input('parking_car');
        $parking_cargo = $request->input('parking_cargo');
        $floor_type = $request->input('floor_type');
        $floor_load = $request->input('floor_load');
        $fire_system = $request->input('fire_system');
        $ventilation = $request->input('ventilation');
        $fire_alarm = $request->input('fire_alarm');
        $security_alarm = $request->input('security_alarm');
        $storage_transport_area = $request->input('storage_transport_area');
        $inline_block = $request->input('inline_block');
        $infrastructure = $request->input('infrastructure');
        $price = $request->input('price');
        $price_type = $request->input('price_type');
        $currency = $request->input('currency');
        $rack = $request->input('rack');
        $ramp = $request->input('ramp');
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$area) {
                $result['message'] = 'Не передан площадь';
                break;
            }
            if (!$totalArea) {
                $result['message'] = 'Не передан общая площадь';
                break;
            }
            if (!$class) {
                $result['message'] = 'Не передан класс склада';
                break;
            }
            if (!$type) {
                $result['message'] = 'Не передан тип склада';
                break;
            }
            if (!$city_id) {
                $result['message'] = 'Не передан город';
                break;
            }
            if (!$address) {
                $result['message'] = 'Не передан адрес склада';
                break;
            }
            if (!$price) {
                $result['message'] = 'Не передан цена';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }

            DB::beginTransaction();

            $storage_id = DB::table('storage')->insertGetId([
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$storage_id) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }

            $storage_property = DB::table('storage_properties')->insertGetId([
                'storage_id' => $storage_id,
                'price' => $price,
                'price_type' => $price_type,
                'currency' => $currency,
                'area' => $area,
                'total_area' => $totalArea,
                'class' => $class,
                'type_storage' => $type,
                'year' => $year,
                'city_id' => $city_id,
                'address' => $address,
                'floor' => $floor,
                'floor_type' => $floor_type,
                'parking_car' => $parking_car,
                'parking_cargo' => $parking_cargo,
                'floor_load' => $floor_load,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$storage_property) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }
            $fire = '';
            foreach ($fire_system as $fs) {
                $fire .= ',' . $fs;
            }
            if (!empty($fire)) {
                $fire = ltrim($fire, $fire[0]);
            }

            $ventArray = '';
            foreach ($ventilation as $vent) {
                $ventArray .= ',' . $vent;
            }
            if (!empty($ventArray)) {
                $ventArray = ltrim($ventArray, $ventArray[0]);
            }

            $storage_additional = DB::table('storage_additional')->insertGetId([
                'storage_id' => $storage_id,
                'fire_system' => $fire,
                'ventilation' => $ventArray,
                'fire_alarm' => $fire_alarm,
                'security_alarm' => $security_alarm,
                'security_area_transport' => $storage_transport_area,
                'inline_blocks' => $inline_block,
                'rack' => $rack,
                'ramp' => $ramp,
                'infrastructure' => $infrastructure,
            ]);

            if (!$storage_additional) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }

            if (isset($image)){
                $allowedfileExtension = ['jpeg', 'jpg', 'png'];
                foreach ($request->file('image') as $file) {

                    $extension = $file->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if (!$check) {
                        $result['message'] = 'Пожалуйста, загружайте только jpeg,jpg,png';
                        break;
                    }

                    $path = $file->store('public/images/storage/');

                    $name = $file->getClientOriginalName();
                    $name = sha1(time() . $name) . '.' . $file->extension();
                    $file->move($path, $name);
                    $imageID = DB::table('storage_images')->insertGetId([
                        'storage_id' => $storage_id,
                        'name' => $name,
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
        } while (false);

        return response()->json($result);
    }

    public function getAllStorage(Request $request)
    {
        $page = intval($request->input('page'));
        $result['success'] = true;
        if (!$page || $page == 1) {
            $page = 1;
            $skip = 0;
            $take = 10;
        } else {
            echo "yes";
            $skip = ($page - 1) * 10;
            $take = ($page - 1) * 10;
        }
        $count = Storage::all();
        $count = $count->count();
        $result['all'] = $count;
        $result['current_page'] = $page;
        $result['max_page'] = ceil($count / 10);
        $result['data'] = StorageResource::collection(Storage::skip($skip)->take($take)->get());


        return response()->json($result);
    }

    public function getStorageById(Request $request)
    {
        $storage_id = $request->input('storage_id');
        $result['success'] = false;

        do {
            if (!$storage_id) {
                $result['message'] = 'Не передан айди';
                break;
            }
            $storage = Storage::find($storage_id)->first();
            if (!$storage) {
                $result['message'] = 'Не найден склад';
                break;
            }
            $result['success'] = true;
            $result['data'] = StorageDetailResource::collection(Storage::where('id', $storage_id)->get());
        } while (false);

        return response()->json($result);
    }

    public function getFireSystem()
    {
        $data = DB::table('storage_fire_system')->select('id', 'name')->get();
        return response()->json($data);
    }

    public function getVentilation()
    {
        $data = DB::table('storage_ventilation')->select('id', 'name')->get();
        return response()->json($data);
    }
}
