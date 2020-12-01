<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function registration(Request $request){
        $name = $request->input('name');
        $secondName = $request->input('secondName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $password = $request->input('password');
        $phone = $request->input('phone');
        $birthDay = $request->input('birthDay');
        $country = $request->input('country');
        $city = $request->input('city');
        $address = $request->input('address');
        $type = $request->input('type');
        $image = $request->input('image');
        $result['success'] = false;
        do{
            if (!$name){
                $result['message'] = 'Не передан имя';
                break;
            }
            if (!$secondName){
                $result['message'] = 'Не передан фамилия';
                break;
            }
            if (!$email){
                $result['message'] = 'Не передан эмейл';
                break;
            }
            if (!$password){
                $result['message'] = 'Не передан пароль';
                break;
            }
            if (!$phone){
                $result['message'] = 'Не передан телефон';
                break;
            }
            if(!$birthDay){
                $result['message'] = 'Не передан день рождение';
                break;
            }
            if (!$country){
                $result['message'] = 'Не передан страна';
                break;
            }
            if (!$city){
                $result['message'] = 'Не передан город';
                break;
            }
            if (!$address){
                $result['message'] = 'Не передан адрес';
                break;
            }
            if (!$type){
                $result['message'] = 'Не передан тип пользователья';
                break;
            }

            $user = User::where('email',$email)->first();
            if ($user){
                $result['message'] = 'Этот емейл уже регистирован';
                break;
            }

            $token = Str::random(60);
            $token = sha1($token);

            DB::beginTransaction();
            $user = User::create([
                'name' => $name,
                'secondName' => $secondName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => bcrypt($password),
                'phone' => $phone,
                'birthDay' => $birthDay,
                'country' => $country,
                'city' => $city,
                'address' => $address,
                'status' => '1',
                'token' => $token,
                'type' => $type,
            ]);
            if(!$user){
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Упс попробуйте позже',
                ]);
            }else {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'token' => $token,
                ]);
            }
        }while(false);
        return response()->json($result);
    }

    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $result['success'] = false;
        do {
            if (!$email){
                $result['message'] = 'Не передан почта';
                break;
            }
            if (!$password){
                $result['message'] = 'Не передан пароль';
                break;
            }
            $user = User::where('email',$email)->first();
            if (!$user){
                $result['message'] = 'Такой пользователь не существует';
                break;
            }
            $res = Hash::check($password,$user->password);
            if (!$res){
                $result['message'] = 'Неправильный логин или пароль';
                break;
            }

            $token = Str::random(60);
            $token = sha1($token);
            $user->token = $token;
            $user->save();
            $result['success'] = true;
            $result['token'] = $token;
        }while(false);
        return response()->json($result);
    }

    public function logout(Request $request){
        $email = $request->input('email');
        $result['success'] = false;

        do{
            if (!$email){
                $result['message'] = 'Не передан эмейл';
                break;
            }
            $user = User::where('email',$email)->first();
            if (!$user){
                $result['message'] = 'Не существует такой логин';
                break;
            }
            $user->token = '';
            $user->save;
            $result['success'] = true;
        }while(false);
        return response()->json($result);
    }
}
