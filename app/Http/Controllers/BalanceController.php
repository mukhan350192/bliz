<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function getBalance(Request $request){
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
            $balance = DB::table('balance')->where('user_id',$user->id)->first();
            if (!$balance){
                $result['amount'] = 0;
                $result['success'] = true;
                break;
            }else{
                $result['amount'] = $balance->amount;
                $result['success'] = true;
                break;
            }
        }while(false);
        return response()->json($result);
    }
}
