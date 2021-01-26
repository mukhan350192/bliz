<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function registration(Request $request)
    {
        $fullName = $request->input('fullName');
        $email = $request->input('email');
        $password = $request->input('password');
        $phone = $request->input('phone');

        $result['success'] = false;

        do {
            if (!$fullName){
                $result['message'] = 'Не передан ФИО';
                break;
            }
            if (!$email) {
                $result['message'] = 'Не передан эмейл';
                break;
            }
            if (!$password) {
                $result['message'] = 'Не передан пароль';
                break;
            }
            if (!$phone) {
                $result['message'] = 'Не передан телефон';
                break;
            }

            $user = User::where('email', $email)->first();
            if ($user) {
                $result['message'] = 'Этот емейл уже регистирован';
                break;
            }

            $token = Str::random(60);
            $token = sha1($token.time());
            $types = DB::table('user_types')->get();
            $typesData = [];
            foreach ($types as $type){
                $typesData[] = [
                    $type->id => $type->name,
                ];
            }
            DB::beginTransaction();
            $user = User::create([
                'fullName' => $fullName,
                'email' => $email,
                'password' => bcrypt($password),
                'phone' => $phone,
                'token' => $token,
                'user_type' => 1,
            ]);
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Упс попробуйте позже',
                ]);
            } else {
                DB::commit();
                $userType = $typesData[1];
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'fullName' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'user_type' => $userType,
                    'user_type_id' => 1,
                ]);
            }
        } while (false);
        return response()->json($result);
    }

    public function entityRegistration(Request $request){
        $companyType = $request->input('companyType');
        $companyName = $request->input('companyName');
        $bin = $request->input('bin');
        $fullName = $request->input('fullName');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $password = $request->input('password');

        $result['success'] = false;

        do{
            if (!$companyType){
                $result['message'] = 'Не передан тип компании';
                break;
            }
            if (!$companyName){
                $result['message'] = 'Не передан название компании';
                break;
            }
            if (!$fullName){
                $result['message'] = 'Не передан фио контактный лицо';
                break;
            }
            if (!$phone){
                $result['message'] = 'Не передан телефон';
                break;
            }
            if (!$email){
                $result['message'] = 'Не передан почта';
                break;
            }
            if (!$password){
                $result['message'] = 'Не передан пароль';
                break;
            }
            $user = User::where('email',$email)->first();
            if(isset($user)) {
                $result['message'] = 'Этот email уже зарегистрирован!';
                break;
            }
            $token = str::random(60);
            $token = sha1($token.time());

            DB::beginTransaction();
            $user = User::insertGetId([
                'phone' => $phone,
                'email' => $email,
                'fullName' => $fullName,
                'password' => bcrypt('password'),
                'user_type' => 2,
                'token' => $token,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$user){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так. Попробуйте позже';
                break;
            }
            $company = DB::table('company_details')->insertGetId([
                'name' => $companyName,
                'types' => $companyType,
                'user_id' => $user,
                'bin' => $bin,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$company){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так. Попробуйте позже';
                break;
            }
            DB::commit();
            $result['success'] = true;
            $result['token'] = $token;
            $result['email'] = $email;
            $result['phone'] = $phone;
            $result['companyName'] = $companyName;
            $result['companyType'] = $companyType;
            $result['fullName'] = $fullName;
            if (isset($bin)){
                $result['bin'] = $bin;
            }
        }while(false);

        return response()->json($result);
    }

    public function login(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');
        $result['success'] = false;
        do {
            if (!$phone) {
                $result['message'] = 'Не передан почта';
                break;
            }
            if (!$password) {
                $result['message'] = 'Не передан пароль';
                break;
            }
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                $result['message'] = 'Такой пользователь не существует';
                break;
            }
            $res = Hash::check($password, $user->password);
            if (!$res) {
                $result['message'] = 'Неправильный логин или пароль';
                break;
            }

            $token = Str::random(60);
            $token = sha1($token);
            $user->token = $token;
            $user->save();
            $result['success'] = true;
            if (isset($user->image)){
                $result['image'] = $user->image;
            }
            $result['fullName'] = $user->fullName;
            $result['phone'] = $user->phone;
            if ($user->type == 1){
                $result['url'] = 'http://test.money-men.kz/public/images/avatars/';
            }else if ($user->type == 2){
                $company = DB::table('company_details')->where('user_id',$user->id)->first();
                if (isset($company)){
                    $companyType = DB::table('company_types')->where('id',$company->types)->first();
                    $result['companyName'] = $company->name;
                    $result['companyType'] = $companyType->name;
                    if (isset($company->bin)){
                        $result['bin'] = $company->bin;
                    }
                    if (isset($company->registration)){
                        $result['registration'] = $company->registration;
                    }
                    if (isset($company->license)){
                        $result['license'] = $company->license;
                    }
                }
                $result['url'] = 'http://test.money-men.kz/public/images/company/';

            }
            $result['email'] = $user->email;
            $result['token'] = $token;
        } while (false);
        return response()->json($result);
    }

    public function logout(Request $request)
    {
        $email = $request->input('email');
        $result['success'] = false;

        do {
            if (!$email) {
                $result['message'] = 'Не передан эмейл';
                break;
            }
            $user = User::where('email', $email)->first();
            if (!$user) {
                $result['message'] = 'Не существует такой логин';
                break;
            }
            $user->token = '';
            $user->save;
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function getCountry()
    {
        $result = Country::all();
        return response()->json($result);
    }

    public function getCity(Request $request)
    {
        $countryID = $request->input('countryID');
        $city = City::where('country_id', $countryID)->get();
        return response()->json($city);
    }

    public function setImage(Request $request)
    {
        $image = $request->file('image');
        $token = $request->input('token');
        $result['success'] = false;
        do {
            if (!$image) {
                $result['message'] = 'Не передан изображение';
                break;
            }
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден токен';
                break;
            }

            $name = $image->getClientOriginalName();
            $name = sha1(time() . $name) . '.' . $request->file('image')->extension();;

            $destinationPath = public_path('/images/avatars');
            $image->move($destinationPath, $name);
            $user->image = $name;
            $user->save();
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function displayImage()
    {
        $path = public_path('/images/5c6de787-c652-40f6-bb42-4cbfeb327a2d.jpg');
        return $path;

    }

    public function getProfile(Request $request)
    {
        $token = $request->input('token');
        $result['success'] = false;

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
            $result['fullName'] = $user->fullName;
            $result['email'] = $user->email;
            $result['phone'] = $user->phone;
            $cityName = City::find($user->city);
            if (isset($cityName)){
                $result['cityId'] = $cityName->id;
                $result['cityName'] = $cityName->name;
                $country = Country::find($cityName->country_id);
                $result['country'] = $country->name;
                $result['address'] = $user->address;
            }

            if (isset($user->image)) {
                $result['image'] = 'http://test.money-men.kz/public/images/avatars/' . $user->image;
            }
            if($user->user_type == 2){
                $companyDetails = DB::table('company_details')->where('user_id',$user->id)->first();
                $result['companyName'] = $companyDetails->name;
                if (isset($companyDetails->bin)){
                    $result['bin'] = $companyDetails->bin;
                }
                if (isset($companyDetails->license)){
                    $result['license'] = "https://test.money-men.kz/images/company/$companyDetails->license";
                }
                if (isset($companyDetails->registration)){
                    $result['registration'] = "https://test.money-men.kz/images/company/$companyDetails->registration";
                }
                $type = DB::table('company_types')->where('id',$companyDetails->types)->first();
                $result['companyType'] = $type->name;
            }
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function updateProfile(Request $request)
    {
       // $country = $request->input('country');
        $fullName = $request->input('fullName');
        $city = $request->input('city');
        $address = $request->input('address');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $token = $request->input('token');
        $result['success'] = false;

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
            $user->fullName = $fullName;
          //  $user->country = $country;
            $user->city = $city;
            $user->address = $address;
            $user->email = $email;
            $user->phone = $phone;
            $user->save();
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function deleteAvatar(Request $request)
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
            $user = User::where('token', $token)->update(['image' => '']);
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function addFavourites(Request $request)
    {
        $token = $request->input('token');
        $post_id = $request->input('post_id');
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$post_id) {
                $result['message'] = 'Не передан номер объявление';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $post = Post::find($post_id);
            if (!$post){
                $result['message'] = 'Не найден объявление';
                break;
            }
            DB::beginTransaction();
            $favourites = DB::table('favourites')->insert([
                'user_id' => $user->id,
                'post_id' => $post_id,
            ]);
            if (!$favourites) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }
            $result['success'] = true;
            DB::commit();
        } while (false);
        return response()->json($result);
    }

    public function deleteFavourites(Request $request)
    {
        $token = $request->input('token');
        $id = $request->input('id');
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$id) {
                $result['message'] = 'Не передан номер избранного объявление';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            DB::beginTransaction();
            $favourites = DB::table('favourites')->where('id', $id)->delete();
            if (!$favourites) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }
            $result['success'] = true;
            DB::commit();
        } while (false);
        return response()->json($result);
    }

    public function getCompanyTypes(){
        $company = DB::table('company_types')->get();
        return response()->json($company);
    }

    public function updateBin(Request $request){
        $token = $request->input('token');
        $bin = $request->input('bin');
        $result['success'] = false;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$bin){
                $result['message'] = 'Не передан бин';
                break;
            }
            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $update = DB::table('company_details')
                ->where('user_id',$user->id)
                ->update(['bin' => $bin]);
            $result['success'] = true;
        }while(false);

        return response()->json($result);
    }

    public function updateRegistration(Request $request){
        $token = $request->input('token');
        $register = $request->file('register');
        $result['success'] = false;
        do {
            if (!$register) {
                $result['message'] = 'Не передан изображение';
                break;
            }
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден токен';
                break;
            }

            $name = $register->getClientOriginalName();
            $name = sha1(time() . $name) . '.' . $request->file('register')->extension();;

            $destinationPath = public_path('/images/company/');
            $register->move($destinationPath, $name);
            DB::table('company_details')->where('user_id',$user->id)->update(['registration' => $name]);
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function updateLicense(Request $request){
        $token = $request->input('token');
        $license = $request->file('license');
        $result['success'] = false;
        do {
            if (!$license) {
                $result['message'] = 'Не передан изображение';
                break;
            }
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден токен';
                break;
            }

            $name = $license->getClientOriginalName();
            $name = sha1(time() . $name) . '.' . $request->file('license')->extension();;

            $destinationPath = public_path('/images/company/');
            $license->move($destinationPath, $name);
            DB::table('company_details')->where('user_id',$user->id)->update(['license' => $name]);
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function changePassword(Request $request){
        $password = $request->input('password');
        $repeat = $request->input('password');
        $token = $request->input('token');
        $result['success'] = false;

        do{
            if (!$password){
                $result['message'] = 'Не передан пароль';
                break;
            }
            if (!$repeat){
                $result['message'] = 'Не передан подтверждение пароля';
                break;
            }
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден токен';
                break;
            }
            $user->password = bcrypt($password);
            $user->save();
            $result['success'] = true;
        }while(false);

        return response()->json($result);
    }

    public function deleteAccount(Request $request){
        $token = $request->input('token');
        $password = $request->input('password');
        $result['success'] = false;

        do {
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$password){
                $result['message'] = 'Не передан пароль';
                break;
            }
            $user = User::where('token',$token)->first();

            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            if (!Hash::check($password,$user->password)){
                $result['message'] = 'Не совпадают пароль';
                break;
            }

            DB::beginTransaction();
            if ($user->type == 2){
                $company = DB::table('company_details')->where('user_id',$user->id)->delete();
            }
            $posts = Post::where('user_id',$user->id)->delete();

            $favourites = DB::table('favourites')->where('user_id',$user->id)->delete();
            var_dump($posts);
            var_dump($favourites);
            if (!$favourites || !$posts){
               $result['message'] = 'Упс что то произошло. Попробуйте позже';
               break;
            }
            User::find($user->id)->delete();
            DB::commit();
            $result['success'] = true;
        }while(false);
        return response()->json($result);
    }
}
