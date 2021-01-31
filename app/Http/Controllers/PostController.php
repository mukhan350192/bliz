<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Detail;
use App\Models\Order;
use App\Models\Post;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    public function getDistance($cityOne, $cityTwo)
    {
//        $cityOne = $request->input('from');
//        $cityTwo = $request->input('to');
        $city1 = City::find($cityOne);
        $city2 = City::find($cityTwo);
        return $this->distance(44.8325386, 63.2229718, 51.147862, 71.3393068, "K");

    }

    public function addPost(Request $request)
    {
        $title = $request->input('title');
        $sub_id = $request->input('sub_id');
        $volume = $request->input('volume');
        $net = $request->input('net');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $from = $request->input('from');
        $to = $request->input('to');
        $token = $request->input('token');
        $categoryID = $request->input('category_id');
        $price = $request->input('price');
        $result['success'] = false;

        do {
            if (!$title) {
                $result['message'] = 'Не передан title';
                break;
            }
            if (!$sub_id) {
                $result['message'] = 'Не передан категория';
                break;
            }
            if (!$volume) {
                $result['message'] = 'Не передан объем';
                break;
            }
            if (!$net) {
                $result['message'] = 'Не передан масса';
                break;
            }
            if (!$start_date) {
                $result['message'] = 'Не передан дата погрузки';
                break;
            }
            if (!$end_date) {
                $result['message'] = 'Не передан дата погрузки';
                break;
            }
            if (!$from) {
                $result['message'] = 'Не передан откуда увезти';
                break;
            }
            if (!$to) {
                $result['message'] = 'Не передан куда довезти';
                break;
            }

            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$price){
                $result['message'] = 'Не передан цена';
                break;
            }


            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            DB::beginTransaction();
            $postID = Post::insertGetId([
                'title' => $title,
                'sub_id' => $sub_id,
                'volume' => $volume,
                'net' => $net,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'priority' => 1,
                'user_id' => $user->id,
                'category_id' => $categoryID,
                'price' => $price,
            ]);
            if (!$postID) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так. Попробуйте позже.';
                break;
            }
            $distance = $this->getDistance($from, $to);
            $detailID = Detail::insertGetId([
                'post_id' => $postID,
                'distance' => $distance,
                'from' => $from,
                'to' => $to,
            ]);
            if (!$detailID) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так. Попробуйте позже.';
                break;
            }
            $result['success'] = true;
            DB::commit();
        } while (false);

        return response()->json($result);
    }

    public function getPost(Request $request){

        $page = intval($request->input('page'));
        $sub_id = $request->input('sub_id');
        $category_id = $request->input('category_id');
        $result['success'] = false;
        $skip = 0;
        $take = 0;
        if (!$page || $page==1){
            $page = 1;
            $skip = 0;
            $take = 10;
        }else{
            $skip = ($page-1)*10;
            $take = ($page-1)*10;
        }
        $count = Post::all();
        $count = $count->count();
        $city = City::all();
        if (!$category_id){
            die('Не передан категория айди');
        }
        if (!$sub_id){
            $post = DB::table('posts')
                ->join('users','posts.user_id','=','users.id')
                ->join('details','posts.id','=','details.post_id')
                ->select('posts.id','posts.sub_id','posts.title','posts.volume','posts.net','posts.start_date','posts.end_date','users.fullName','users.phone','users.email','details.from','details.to','users.user_type','posts.price')
                ->where('posts.category_id',$category_id)
                ->skip($skip)
                ->take($take)
                ->get();
        }else{
            $post = DB::table('posts')
                ->join('users','posts.user_id','=','users.id')
                ->join('details','posts.id','=','details.post_id')
                ->select('posts.id','posts.sub_id','posts.title','posts.volume','posts.net','posts.start_date','posts.end_date','users.name','users.phone','users.email','details.from','details.to','users.user_type','posts.price')
                ->where('posts.sub_id','=',$sub_id)
                ->where('posts.category_id','=',$category_id)
                ->skip($skip)
                ->take($take)
                ->get();
        }

        $sub = SubCategory::all();
        foreach ($post as $posts){
            foreach ($city as $c){
                if ($c->id == $posts->from){
                    $posts->from = $c->name;
                }
                if ($c->id == $posts->to){
                    $posts->to = $c->name;
                }
            }
            foreach ($sub as $s){
                if ($s->id == $posts->sub_id){
                    $posts->sub_id = $s->name;
                }
            }
        }

        $data = [
            'success' => true,
            'pagination' => [
                'page' => $page,
                'per_page' => 10,
                'total' => $count,
                'max_page' => ceil($count/10),
            ],
            'data' => $post,
        ];

        return response()->json($data);
    }

    public function getSubCategories(){
        $subCategory = SubCategory::all();
        return response()->json($subCategory);
    }

    public function getCategory(){
        $category = Category::all();
        return response()->json($category);
    }

    public function sendRequest(Request $request){
        $token = $request->input('token');
        $postID = $request->input('post_id');
        $result['success'] = false;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$postID){
                $result['message'] = 'Не передан номер заявки';
                break;
            }

            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

            $post = Post::find($postID);
            if (!$post){
                $result['message'] = 'Не найден такое объявление';
                break;
            }

            if (isset($post) && $post->status == 2){
                $result['message'] = 'К сожалению этот объяление уже неактивна';
                break;
            }
            $orders = Order::where('post_id',$postID)->where('user_id',$user->id)->first();
            if ($orders){
                $result['message'] = 'Вы уже отправили заявку';
                break;
            }

            DB::beginTransaction();
            $orders = Order::insertGetId([
                'post_id' => $postID,
                'from_id' => $post->user_id,
                'to_id' => $user->id,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$orders){
                DB::rollBack();
                $result['message'] = 'Что то пошло не так';
                break;
            }
            $result['success'] = true;
            DB::commit();

            $result['message'] = 'Ваша заявка отправлена';
        }while(false);

        return response()->json($result);
    }

    public function getOwnPosts(Request $request){
        $token = $request->input('token');
        $result['success'] = false;

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
            $posts = Post::where('user_id',$user->id)->get();
            if (!$posts){
                $result['message'] = 'У вас пока нету объявлений';
                break;
            }
            $sub = SubCategory::all();

            foreach ($posts as $post){
                $post->start_date = strtotime($post->start_date);
                $post->start_date = date('d.m.Y',$post->start_date);
                $post->end_date = strtotime($post->end_date);
                $post->end_date = date('d.m.Y',$post->end_date);
                foreach ($sub as $s){
                    if ($s->id == $post->sub_id){
                        $post->sub_id = $s->name;
                        $category = Category::find($s->category_id);
                        $post->category_id = $category->name;
                    }
                }
                if ($post->status == 1){
                    $post->status = 'Активная объявление';
                }
                if ($post->status == 2){
                    $post->status = 'Завершенная объявление';
                }
            }
            $result['data'] = $posts;
        }while(false);

        return response()->json($result);
    }

    public function getAllPostsByCategory(Request $request){
        $token = $request->input('token');
        $result['success'] = false;

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
            $category = DB::table('categories')->limit(3)->get();
            foreach ($category as $cat){
                $data = Post::where('category_id',$cat->id)->where('status',1)->where('user_id',$user->id)->count();
                $all[] = [
                    'name' => $cat->name,
                    'count' => $data,
                ];

            }
//            print_r($all);
            $result['data'] = $all;
        }while(false);
        return response()->json($result);
    }

    public function acceptPost(Request $request){
        $token = $request->input('token');
        $orderID = $request->input('order_id');
        $result['success'] = true;

        do {
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$orderID){
                $result['message'] = 'Не передан номер заказа';
                break;
            }
            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }

        }while(false);
        return response()->json($result);
    }




}
