<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Storage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageController extends Controller
{
    public function createStorage(Request $request){
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
        $image = $request->file('image');
        $price = $request->input('price');
        $rentTypeID = $request->input('rentTypeID');
        $token = $request->input('token');
        $result['success'] = true;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$name){
                $result['message'] = 'Не передан имя';
                break;
            }

            if (!$city_id){
                $result['message'] = 'Не передан город';
                break;
            }

            if (!$address) {
                $result['message'] = 'Не передан адрес';
                break;
            }

            if (!$price){
                $result['message'] = 'Не передан стоимость';
                break;
            }

            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            DB::beginTransaction();
            $propertyID = DB::table('storage_properties')->insertGetId([
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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$propertyID){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }

            $storageID = Storage::create([
                'name' => $name,
                'user_id' => $user->id,
                'property_id' => $propertyID,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$storageID){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            $storageImage = $image->getClientOriginalName();
            $storageImage = sha1(time() . $storageImage) . '.' . $request->file('image')->extension();

            $destinationPath = public_path('/images/storage/');
            $image->move($destinationPath, $storageImage);
            $imageID = DB::table('storage_images')->insertGetId([
                'name' => $storageImage,
                'storage_id' => $storageID->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$imageID){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            DB::commit();
            $result['success'] = true;
        }while(false);

        return response()->json($result);
    }

    public function updateStorage(Request $request){
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

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$name){
                $result['message'] = 'Не передан имя';
                break;
            }

            if (!$city_id){
                $result['message'] = 'Не передан город';
                break;
            }

            if (!$address) {
                $result['message'] = 'Не передан адрес';
                break;
            }

            if (!$storage_id){
                $result['message'] = 'Не передан склад';
                break;
            }

            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $storage = Storage::find($storage_id);
            if (!$storage){
                $result['message'] = 'Не найден склад';
                break;
            }
            DB::beginTransaction();
            $update = DB::table('storage_properties')->where('id',$storage->id)
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
            if (!$update){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            DB::commit();
            $result['success'] = true;
        }while(false);

        return response()->json($result);
    }

    public function getAllOwnStorage(Request $request){
        $token = $request->input('token');

        $result['success'] = true;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            $storages = DB::table('storage')
                        ->join('storage_properties','storage.property_id','=','storage_properties.id')
                        ->where('user_id',$user->id)
                        ->get();
            $data = [];
            $cities = [];
            $city = City::all();
            foreach ($city as $c){
                $cities .= [
                    $c->id => $c->name,
                ];
            }
            $typeRent = DB::table('type_rent')->get();
            $rentCollection = [];
            foreach ($typeRent as $ty){
                $rentCollection[] = [
                    $ty->id => $ty->name,
                ];
            }
            $storageID = [];
            foreach ($storages as $storage){
                $storageID[] = [$storage->id];
                $data[] = [
                    'name' => $storage->name,
                    'city' => $cities[$storage->city],
                    'address' => $storage->address,
                ];
                if (isset($storage->area)){
                    $data[] = ['area' => $storage->area];
                }
                if (isset($storage->total_area)){
                    $data[] = ['area' => $storage->area];
                }
                if (isset($storage->class)){
                    $data[] = ['class' => $storage->class];
                }
                if (isset($storage->type_storage)){
                    $data[] = ['type_storage' => $storage->type_storage];
                }
                if (isset($storage->year)){
                    $data[] = ['year' => $storage->year];
                }
                if (isset($storage->floor)){
                    $data[] = ['floor' => $storage->floor];
                }
                if (isset($storage->floor_type)){
                    $data[] = ['floor_type' => $storage->floor_type];
                }
                if (isset($storage->warning)){
                    $data[] = ['warning' => $storage->warning];
                }
                if (isset($storage->warning_area)){
                    $data[] = ['warning_area' => $storage->warning_area];
                }
            }
            $result = $data;
        }while(false);
        return response()->json($result);
    }

    public function getRentType(Request $request){
        $rent = DB::table('type_rent')->select('id','name')->get();
        return response()->json($rent);
    }

    public function addImageToStorage(Request $request){
        $image = $request->file('image');
        $storageID =$request->input('storage_id');
        $token = $request->input('token');
        $result['success'] = true;

        do{
            if (!$image){
                $result['message'] = 'Не передан файл';
                break;
            }
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            $storage = Storage::find($storageID);
            if (!$storage){
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
            if (!$imageID){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            DB::commit();
            $result['success'] = true;
        }while(false);
        return response()->json($result);
    }
}
