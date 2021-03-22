<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function selectDriver(Request $request)
    {
        $token = $request->input('token');
        $order_id = $request->input('order_id');
        $result['success'] = false;

        do{
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

        }while(false);

        return response()->json($result);
    }
}
