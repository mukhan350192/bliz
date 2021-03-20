<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuctionMinDetails;
use App\Http\Resources\EquipmentMin;
use App\Http\Resources\OrderMinExecutePosts;
use App\Http\Resources\PostAdditionResource;
use App\Http\Resources\PostConditionResource;
use App\Http\Resources\PostDocumentResource;
use App\Http\Resources\PostLoadingResource;
use App\Http\Resources\PostMinResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Detail;
use App\Models\Order;
use App\Models\Post;
use App\Models\SpecialEquipment;
use App\Models\Storage;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\b;

class PostController extends Controller
{

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

            if (!$token) {
                $result['message'] = 'Не передан токен';
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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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

    public function getPost(Request $request)
    {

        $page = intval($request->input('page'));
        $sub_id = $request->input('sub_id');
        $category_id = $request->input('category_id');
        $result['success'] = false;
        $skip = 0;
        $take = 0;
        if (!$page || $page == 1) {
            $page = 1;
            $skip = 0;
            $take = 10;
        } else {
            $skip = ($page - 1) * 10;
            $take = ($page - 1) * 10;
        }
        $count = Post::all();
        $count = $count->count();
        $city = City::all();
        if (!$category_id) {
            die('Не передан категория айди');
        }
        if (!$sub_id) {
            $post = DB::table('posts')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->join('details', 'posts.id', '=', 'details.post_id')
                ->select('posts.id', 'posts.sub_id', 'posts.title', 'posts.volume',
                    'posts.net', 'posts.start_date', 'posts.end_date', 'users.fullName',
                    'users.phone', 'users.email', 'details.from', 'details.to', 'users.user_type',
                    'posts.price', 'posts.created_at', 'posts.updated_at')
                ->where('posts.category_id', $category_id)
                ->skip($skip)
                ->take($take)
                ->get();
            $count = Post::where('category_id', $category_id)->count();
        } else {
            $post = DB::table('posts')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->join('details', 'posts.id', '=', 'details.post_id')
                ->select('posts.id', 'posts.sub_id', 'posts.title', 'posts.volume', 'posts.net',
                    'posts.start_date', 'posts.end_date', 'users.fullName', 'users.phone', 'users.email',
                    'details.from', 'details.to', 'users.user_type', 'posts.price', 'posts.created_at', 'posts.updated_at')
                ->where('posts.sub_id', '=', $sub_id)
                ->where('posts.category_id', '=', $category_id)
                ->skip($skip)
                ->take($take)
                ->get();
            $count = Post::where('category_id', $category_id)->where('sub_id', $sub_id)->count();
        }

        $sub = SubCategory::all();
        foreach ($post as $posts) {
            foreach ($city as $c) {
                if ($c->id == $posts->from) {
                    $posts->from = $c->name;
                }
                if ($c->id == $posts->to) {
                    $posts->to = $c->name;
                }
            }
            foreach ($sub as $s) {
                if ($s->id == $posts->sub_id) {
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
                'max_page' => ceil($count / 10),
            ],
            'data' => $post,
        ];

        return response()->json($data);
    }

    public function getSubCategories()
    {
        $subCategory = SubCategory::all();
        return response()->json($subCategory);
    }

    public function getCategory()
    {
        $category = Category::all();
        return response()->json($category);
    }

    public function sendRequest(Request $request)
    {
        $token = $request->input('token');
        $postID = $request->input('post_id');
        $price = $request->input('price');
        $currency = $request->input('currency');

        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$postID) {
                $result['message'] = 'Не передан номер заявки';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }

            $post = Post::find($postID);
            if (!$post) {
                $result['message'] = 'Не найден такое объявление';
                break;
            }

            if (isset($post) && $post->status != 1) {
                $result['message'] = 'К сожалению этот объяление уже неактивна';
                break;
            }
            $orders = Order::where('post_id', $postID)->where('executor', $user->id)->first();
            if ($orders) {
                $result['message'] = 'Вы уже отправили заявку';
                break;
            }

            DB::beginTransaction();
            $orders = Order::insertGetId([
                'post_id' => $postID,
                'customer' => $post->user_id,
                'executor' => $user->id,
                'status' => 1,
                'currency' => $currency,
                'price' => $price,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$orders) {
                DB::rollBack();
                $result['message'] = 'Что то пошло не так';
                break;
            }
            $result['success'] = true;
            DB::commit();

            $result['message'] = 'Ваша заявка отправлена';
        } while (false);

        return response()->json($result);
    }

    public function getOwnPosts(Request $request)
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
            $posts = Post::where('user_id', $user->id)->get();
            if (!$posts) {
                $result['message'] = 'У вас пока нету объявлений';
                break;
            }
            $sub = SubCategory::all();

            foreach ($posts as $post) {
                $post->start_date = strtotime($post->start_date);
                $post->start_date = date('d.m.Y', $post->start_date);
                $post->end_date = strtotime($post->end_date);
                $post->end_date = date('d.m.Y', $post->end_date);
                foreach ($sub as $s) {
                    if ($s->id == $post->sub_id) {
                        $post->sub_id = $s->name;
                        $category = Category::find($s->category_id);
                        $post->category_id = $category->name;
                    }
                }
                if ($post->status == 1) {
                    $post->status = 'Активная объявление';
                }
                if ($post->status == 2) {
                    $post->status = 'Завершенная объявление';
                }
            }
            $result['data'] = $posts;
        } while (false);

        return response()->json($result);
    }

    public function getAllPostsByCategory(Request $request)
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
            $category = DB::table('categories')->limit(3)->get();
            foreach ($category as $cat) {
                $data = Post::where('category_id', $cat->id)->where('status', 1)->where('user_id', $user->id)->count();
                $all[] = [
                    'name' => $cat->name,
                    'count' => $data,
                ];

            }
//            print_r($all);
            $result['data'] = $all;
        } while (false);
        return response()->json($result);
    }

    public function acceptPost(Request $request)
    {
        $token = $request->input('token');
        $orderID = $request->input('order_id');
        $result['success'] = true;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$orderID) {
                $result['message'] = 'Не передан номер заказа';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $order = Order::find($orderID);
            if (!$order) {
                $result['message'] = 'Не найден заказ';
                break;
            }
            $order->status = 2;
            $order->save();
            $post = Post::find($order->post_id);
            $post->status = 2;
            $post->save();
            $result['success'] = true;


        } while (false);
        return response()->json($result);
    }

    public function getPerformerOrders(Request $request)
    {
        $token = $request->input('token');
        $result['success'] = false;

        $statuses = [
            1 => 'Ждет погрузку',
            2 => 'Доставлен',
            3 => 'В пути',
            4 => 'Завершен',
        ];
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
            $orders = DB::table('orders')
                ->join('posts', 'posts.id', '=', 'orders.post_id')
                ->join('details', 'details.post_id', '=', 'posts.id')
                ->where('orders.status', '!=', 4)
                ->get();
            $data = [];
            $index = 0;
            $cities = City::all();
            $city = [];
            foreach ($cities as $c) {
                $city[$c->id] = $c->name;
            }
            foreach ($orders as $order) {
                $data[$index]['status'] = $statuses[$order->status];
                if (isset($city[$order->from])) {
                    $data[$index]['from'] = $city[$order->from];
                }
                if (isset($city[$order->to])) {
                    $data[$index]['to'] = $city[$order->to];
                }
                $data[$index]['price'] = $order->price;
                $data[$index]['volume'] = $order->volume;
                $data[$index]['net'] = $order->net;
                $data[$index]['title'] = $order->title;
                $data[$index]['start_date'] = $order->start_date;
                $data[$index]['end_date'] = $order->end_date;
                $index++;
            }
            $result['data'] = $data;
        } while (false);
        return response()->json($result);
    }

    public function getPostDocuments()
    {
        return PostDocumentResource::collection(DB::table('post_document')->get());
    }

    public function getPostLoading()
    {
        return PostLoadingResource::collection(DB::table('post_loading')->get());
    }

    public function getPostCondition()
    {
        return PostConditionResource::collection(DB::table('post_condition')->get());
    }

    public function getPostAddition()
    {
        return PostAdditionResource::collection(DB::table('post_addition')->get());
    }

    public function newAddPost(Request $request)
    {
        $token = $request->input('token');
        $category_id = $request->input('category_id');
        $sub_id = $request->input('sub_id');
        $title = $request->input('title');
        $from = $request->input('from');
        $to = $request->input('to');
        $volume = $request->input('volume');
        $net = $request->input('net');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $quantity = $request->input('quantity');
        $width = $request->input('width');
        $height = $request->input('height');
        $length = $request->input('length');
        $documents = $request->input('documents');
        $loading = $request->input('loading');
        $condition = $request->input('condition');
        $addition = $request->input('addition');
        $price = $request->input('price');
        $price_type = $request->input('price_type');
        $payment_type = $request->input('payment_type');
        $type_transport = $request->input('type_transport');
        $distance = $request->input('distance');
        $duration = $request->input('duration');
        $from_string = $request->input('from_string');
        $to_string = $request->input('to_string');
        $result['success'] = false;
        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$category_id) {
                $result['message'] = 'Не передан категория';
                break;
            }
            if (!$sub_id) {
                $result['message'] = 'Не передан субкатегория';
                break;
            }
            if (!$title) {
                $result['message'] = 'Не передан содержание товара';
                break;
            }
            if (!$from) {
                $result['message'] = 'Не передан откуда увезти груз';
                break;
            }
            if (!$to) {
                $result['message'] = 'Не передан куда отвезти груз';
                break;
            }
            if (!$price) {
                $result['message'] = 'Не передан цена за доставку';
                break;
            }
            if (!$price_type) {
                $result['message'] = 'Не передан валюта';
                break;
            }
            if (!$payment_type) {
                $result['message'] = 'Не передан способ оплаты';
                break;
            }
            if (!$type_transport) {
                $result['message'] = 'Не передан тип транспорта';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не передан токен';
                break;
            }
            DB::beginTransaction();
            $postID = Post::insertGetId([
                'sub_id' => $sub_id,
                'category_id' => $category_id,
                'priority' => 1,
                'user_id' => $user->id,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$postID) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }

            $detailsID = DB::table('details')->insertGetId([
                'title' => $title,
                'post_id' => $postID,
                'from' => $from,
                'to' => $to,
                'volume' => $volume,
                'net' => $net,
                'type_transport' => $type_transport,
                'distance' => $distance,
                'duration' => $duration,
                'from_string' => $from_string,
                'to_string' => $to_string,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'quantity' => $quantity,
                'width' => $width,
                'height' => $height,
                'length' => $length,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$detailsID) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            $docs = '';
            if (isset($documents)) {
                foreach ($documents as $doc) {
                    $docs .= ',' . $doc;
                }
            }

            $load = '';
            if (isset($loading)) {
                foreach ($loading as $l) {
                    $load .= ',' . $l;
                }
            }

            $con = '';
            if (isset($condition)) {
                foreach ($condition as $c) {
                    $con .= ',' . $con;
                }
            }

            $add = '';
            if (isset($addition)) {
                foreach ($addition as $a) {
                    $add .= ',' . $a;
                }
            }
            if (!empty($docs)) {
                $docs = ltrim($docs, $docs[0]);
            }
            if (!empty($load)) {
                $load = ltrim($load, $load[0]);
            }
            if (!empty($con)) {
                $con = ltrim($con, $con[0]);
            }
            if (!empty($add)) {
                $add = ltrim($add, $add[0]);
            }

            $postAdditional = DB::table('post_additional')->insertGetId([
                'post_id' => $postID,
                'documents' => $docs,
                'loading' => $load,
                'condition' => $con,
                'addition' => $add,
            ]);
            if (!$postAdditional) {
                DB::rollBack();
                $result['message'] = 'Что то произошло не так';
                break;
            }
            $price = DB::table('post_price')->insertGetId([
                'post_id' => $postID,
                'price' => $price,
                'price_type' => $price_type,
                'payment_type' => $payment_type,
            ]);

            DB::commit();
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function newGetPost(Request $request)
    {
        $page = intval($request->input('page'));
        $sub_id = $request->input('sub_id');
        $category_id = $request->input('category_id');
        $result['success'] = false;
        $skip = 0;
        $take = 0;
        if (!$page || $page == 1) {
            $page = 1;
            $skip = 0;
            $take = 10;
        } else {
            $skip = ($page - 1) * 10;
            $take = ($page - 1) * 10;
        }
        $count = Post::where('category_id', $category_id)->count();

        $city = City::all();
        if (!$category_id) {
            die('Не передан категория айди');
        }
        if (!$sub_id) {
            $data = PostMinResource::collection(Post::where('category_id', $category_id)->skip($skip)->take($take)->get());
        } else {
            $data = PostMinResource::collection(Post::where('category_id', $category_id)->where('sub_id', $sub_id)->skip($skip)->take($take)->get());
            $count = Post::where('category_id', $category_id)->where('sub_id', $sub_id)->count();
        }
        $result['data'] = $data;
        $result['pagination'] = [
            'total' => $count,
            'page' => $page,
            'max_page' => ceil($count / 10),
        ];
        $result['success'] = true;
        return response()->json($result);
    }

    public function getCurrency()
    {
        $currency = DB::table('currency')->select('id', 'name')->get();
        return response()->json($currency);
    }

    public function getPaymentType()
    {
        $payment = DB::table('payment_type')->select('id', 'name')->get();
        return response()->json($payment);
    }

    public function getPostByID($id)
    {
        //$post_id = $request->input('post_id');
        $result['success'] = false;
        do {
            if (!$id) {
                $result['message'] = 'Не передан пост айди';
                break;
            }
            $post = Post::where('id', $id)->get();
            $data = PostResource::collection($post);
            $result['data'] = $data;
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }


    public function customerOrdersInWork(Request $request)
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
            $count = DB::table('orders')
                ->where('executor', $user->id)
                ->whereIn('status', [2,3,4])
                ->count();
            $data = OrderMinExecutePosts::collection(
                DB::table('orders')
                    ->where('executor', $user->id)
                    ->whereIn('status', [2,3,4])
                    ->get());
            $result['count'] = $count;
            $result['data'] = $data;
            $result['success'] = true;

        } while (false);

        return response()->json($result);
    }

    public function executorOrdersInWork(Request $request)
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
            $count = DB::table('orders')
                ->where('customer', $user->id)
                ->whereIn('status', [2,3,4])
                ->count();
            $data = OrderMinExecutePosts::collection(
                DB::table('orders')
                    ->where('customer', $user->id)
                    ->whereIn('status', [2,3,4])
                    ->get());
            $result['count'] = $count;
            $result['data'] = $data;
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function customerOrdersInHold(Request $request)
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
            $count = DB::table('orders')
                ->where('executor', $user->id)
                ->whereIn('status', [1,5,6])
                ->count();
            $data = OrderMinExecutePosts::collection(
                DB::table('orders')
                    ->where('executor', $user->id)
                    ->whereIn('status', [1,5,6])
                    ->get());
            $result['count'] = $count;
            $result['data'] = $data;
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function executorOrdersInHold(Request $request)
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
            $count = DB::table('orders')
                ->where('customer', $user->id)
                ->whereIn('status', [1,5,6])
                ->count();
            $data = OrderMinExecutePosts::collection(
                DB::table('orders')
                    ->where('customer', $user->id)
                    ->whereIn('status', [1,5,6])
                    ->get());
            $result['count'] = $count;
            $result['data'] = $data;
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function getDistance(Request $request){
        $from = $request->input('from');
        $to = $request->input('to');
        $result['success'] = false;
        $key = 'AIzaSyAplKiP9AOLuUbWdH655ApFMl1nnfXwcwk';
        do{
            if (!$from){
                $result['message'] = 'Не передан откуда';
                break;
            }
            if (!$to){
                $result['message'] = 'Не передан куда';
                break;
            }
            $url = "https://maps.googleapis.com/maps/api/directions/json?language=ru-RU&origin=place_id:$from&destination=place_id:$to&key=$key";
            $s = file_get_contents($url);
            $s = json_decode($s,true);
            $distance = $s['routes'][0]['legs'][0]['distance']['text'];
            $duration = $s['routes'][0]['legs'][0]['duration']['text'];
            $routes =$s['routes'][0]['legs'][0]['steps'];
            $result = [
                'success' => true,
                'distance' => $distance,
                'duration' => $duration,
                'routes' => $routes,
            ];
        }while(false);
        return response()->json($result)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    public function currency(Request $request){
        $url = 'https://www.nationalbank.kz/rss/rates_all.xml';
        $s = file_get_contents($url);
        //$result['data'] = $s;
        //var_dump($s);
        return $s;
    }


    public function addPostFavourites(Request $request){
        $token = $request->input('token');
        $post_id = $request->input('post_id');
        $category_id = $request->input('category_id');
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
            $post = Post::where('id',$post_id)->where('category_id',1)->get();
            if (!$post) {
                $result['message'] = 'Не найден объявление';
                break;
            }
            DB::beginTransaction();
            $favourites = DB::table('favourites')->insert([
                'user_id' => $user->id,
                'post_id' => $post_id,
                'category_id' => $category_id,
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

    public function addStorageFavourites(Request $request){
        $token = $request->input('token');
        $storage_id = $request->input('storage_id');
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$storage_id) {
                $result['message'] = 'Не передан айди склада';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $post = Storage::find($storage_id);
            if (!$post) {
                $result['message'] = 'Не найден объявление';
                break;
            }
            DB::beginTransaction();
            $favourites = DB::table('storage_favourites')->insert([
                'user_id' => $user->id,
                'storage_id' => $storage_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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

    public function addSpecialFavourites(Request $request){
        $token = $request->input('token');
        $special_id = $request->input('special_id');
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$special_id) {
                $result['message'] = 'Не передан айди спецтехника';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $post = SpecialEquipment::find($special_id);
            if (!$post) {
                $result['message'] = 'Не найден объявление';
                break;
            }
            DB::beginTransaction();
            $favourites = DB::table('special_favourites')->insert([
                'user_id' => $user->id,
                'special_id' => $special_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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

    public function getAllFavourites(Request $request){
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

            $cargo = DB::table('favourites')->where('user_id',$user->id)->where('category_id',1)->count();
            $post = DB::table('favourites')->where('user_id',$user->id)->where('category_id',2)->count();
            $auction = DB::table('favourites')->where('user_id',$user->id)->where('category_id',3)->count();

            $storage = DB::table('storage_favourites')->where('user_id',$user->id)->count();

            $special = DB::table('special_favourites')->where('user_id',$user->id)->count();
            $result['success'] = true;
            $result['data'] = [
                'cargo' => $cargo,
                'post' => $post,
                'auction' => $auction,
                'storage' => $storage,
                'special' => $special,
            ];
        }while(false);

        return response()->json($result);

    }

    public function getListCargoFavourites(Request $request){
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
            $data = PostMinResource::collection(Post::where('category_id', 1)->where('user_id',$user->id)->get());


            $result['success'] = true;
            $result['data'] = $data;
        }while(false);

        return response()->json($result);
    }

    public function getListPostFavourites(Request $request){
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
            $data = PostMinResource::collection(Post::where('category_id', 2)->where('user_id',$user->id)->get());


            $result['success'] = true;
            $result['data'] = $data;
        }while(false);

        return response()->json($result);
    }

    public function getListAuctionFavourites(Request $request){
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

            $data = AuctionMinDetails::collection(DB::table('auction')->where('user_id',$user->id)->get());

            $result['success'] = true;
            $result['data'] = $data;
        }while(false);

        return response()->json($result);
    }

    public function getListSpecialFavourites(Request $request){
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

            $data = EquipmentMin::collection(DB::table('special_equipment')->where('user_id',$user->id)->get());

            $result['success'] = true;
            $result['data'] = $data;
        }while(false);

        return response()->json($result);
    }

    public function cancelOrder(Request $request){
        $token = $request->input('token');
        $order_id = $request->input('order_id');
        $result['success'] = false;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$order_id){
                $result['message'] = 'Не передан заказ айди';
                break;
            }
            $user = User::where('token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $order = Order::find($order_id);
            if (!$order){
                $result['message'] = 'Не найден заказ';
                break;
            }
            $order->status = 5;
            $order->save();
            $result['success'] = true;
        }while(false);

        return response()->json($result);
    }

}
