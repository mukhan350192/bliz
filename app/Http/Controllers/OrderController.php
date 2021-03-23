<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function selectDriver(Request $request)
    {
        $token = $request->input('token');
        $order_id = $request->input('order_id');
        $type = 1;
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$order_id) {
                $result['message'] = 'Не передан айди заказа';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $driver = DB::table('user_employee')
                ->join('employee', 'user_employee.employee_id', '=', 'employee.id')
                ->where('user_employee.user_id', $user->id)
                ->where('employee.type', $type)
                ->get();
            if (!$driver) {
                $result['message'] = 'Пока у вас нету водителей';
                break;
            }
            $data = [];
            foreach ($driver as $d) {
                $data[] = [
                    'fio' => $d->fio,
                    'phone' => $d->phone,
                ];
                if (isset($d->email)) {
                    $data[] = [
                        'email' => $d->email,
                    ];
                }
                if (isset($d->image)) {
                    $data[] = [
                        'image' => $d->image,
                    ];
                }
            }
            $result['success'] = true;
            $result['data'] = $data;

        } while (false);

        return response()->json($result);
    }

    public function giveOrderForDriver(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $post_id = $request->input('post_id');
        $token = $request->input('token');
        $result['success'] = false;

        do {
            if (!$employee_id) {
                $result['message'] = 'Не передан айди водителя';
                break;
            }
            if (!$post_id) {
                $result['message'] = 'Не передан айди заказа';
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
            $orders = Order::where('post_id', $post_id)->where('executor', $user->id)->first();
            if (!$orders) {
                $result['message'] = 'Не найден заказ';
                break;
            }
            Order::where('post_id', $post_id)->where('executor', $user->id)->update([
                'status' => 3,
                'updated_at' => Carbon::now(),
            ]);
            $result['success'] = true;
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
}
