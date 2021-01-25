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
        var_dump($fullName);
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
                return response()->json([
                    'success' => true,
                    'token' => $token,
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
            DB::beginTransaction();
            $user = User::insertGetId([
                'phone' => $phone,
                'email' => $email,
                'fullName' => $fullName,
                'password' => bcrypt('password'),
                'user_type' => 2,
                'token' => sha1($token.time()),
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
                'bin' => $bin,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'user_id' => $user,
            ]);
            if (!$company){
                DB::rollBack();
                $result['message'] = 'Что то произошло не так. Попробуйте позже';
                break;
            }
            $result['success'] = true;
            $result['token'] = $token;
        }while(false);

        return response()->json($result);
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $result['success'] = false;
        do {
            if (!$email) {
                $result['message'] = 'Не передан почта';
                break;
            }
            if (!$password) {
                $result['message'] = 'Не передан пароль';
                break;
            }
            $user = User::where('email', $email)->first();
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
            $result['image'] = $user->image;
            $result['name'] = $user->name;
            $result['phone'] = $user->phone;
            $result['url'] = 'http://test.money-men.kz/public/images/avatars';
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
            $result['name'] = $user->name;
            $result['secondName'] = $user->secondName;
            if (isset($user->lastName)) {
                $result['lastName'] = $user->lastName;
            }
            $result['email'] = $user->email;
            $result['phone'] = $user->phone;
            $result['birthDay'] = $user->birthDay;
            $cityName = City::find($user->city);
            if (isset($cityName)){
                $result['cityId'] = $cityName->id;
                $result['cityName'] = $cityName->name;
                $country = Country::find($cityName->country_id);
                $result['country'] = $country->name;
            }

            if (isset($user->image)) {
                $result['image'] = 'http://test.money-men.kz/public/images/avatars/' . $user->image;
            }

            $result['type'] = $user->type;
            $result['userType'] = $user->user_type;
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function updateProfile(Request $request)
    {
        $name = $request->input('name');
        $secondName = $request->input('secondName');
        $lastName = $request->input('lastName');
        $birthDay = $request->input('birthDay');
       // $country = $request->input('country');
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
            $user->name = $name;
            $user->secondName = $secondName;
            $user->lastName = $lastName;
            $user->birthDay = $birthDay;
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
}
