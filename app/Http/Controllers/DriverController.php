<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostMinResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function loginDriver(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');
        $result['success'] = false;
        do {
            if (!$phone) {
                $result['message'] = 'Не передан телефон';
                break;
            }
            if (!$password) {
                $result['message'] = 'Не передан пароль';
                break;
            }

            $employee = DB::table('user_employee')
                ->where('phone', $phone)
                ->first();
            if (!$employee) {
                $result['message'] = 'Неправильный логин или пароль';
                break;
            }
            $check = Hash::check($password, $employee->password);
            if (!$check) {
                $result['message'] = 'Неправильный логин или пароль';
                break;
            }
            $user_id = DB::table('user_employee')->where('employee_id', $employee->id)->first();
            $result['user_id'] = $user_id->user_id;
            $result['employee_id'] = $employee->id;
            $result['success'] = true;

        } while (false);
        return response()->json($result);
    }

    public function getDriverOrders(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $user_id = $request->input('user_id');
        $result['success'] = false;

        do {
            if (!$employee_id) {
                $result['message'] = 'Не передан айди водителя';
                break;
            }
            if (!$user_id) {
                $result['message'] = 'Не передан айди пользователя';
                break;
            }
            $orders = DB::table('orders')
                ->where('employee_id', $employee_id)
                ->where('executor', $user_id)
                ->where('status',3)
                ->get();
            if (!$orders) {
                $result['success'] = true;
                $result['message'] = 'Пока у вас нету заказов';
                break;
            }
            $posts = [];
            foreach ($orders as $o) {
                array_push($posts, $o->post_id);
            }
            $data = PostMinResource::collection(Post::whereIn('id', $posts)->get());

            $result['success'] = true;

            $result['data'] = $data;

        } while (false);

        return response()->json($result);
    }

    public function completeOrder(Request $request){
        $employee_id = $request->input('employee_id');
        $user_id = $request->input('user_id');
        $post_id = $request->input('post_id');
        $result['success'] = false;
        do{
            if (!$employee_id) {
                $result['message'] = 'Не передан айди водителя';
                break;
            }
            if (!$user_id) {
                $result['message'] = 'Не передан айди пользователя';
                break;
            }
            if (!$post_id){
                $result['message'] = 'Не передан айди объявление';
                break;
            }
            $orders = DB::table('orders')
                ->where('employee_id', $employee_id)
                ->where('executor', $user_id)
                ->where('status',3)
                ->get();
            if (!$orders) {
                $result['success'] = true;
                $result['message'] = 'Пока у вас нету заказов';
                break;
            }
            DB::table('orders')->where('id',$orders->id)->update([
                'status' => 4,
            ]);
            $result['success'] = true;
        }while(false);
        return response()->json($result);
    }
}
