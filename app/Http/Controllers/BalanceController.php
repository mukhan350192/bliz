<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function getBalance(Request $request)
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
            $balance = DB::table('balance')->where('user_id', $user->id)->first();
            if (!$balance) {
                $result['amount'] = 0;
                $result['success'] = true;
                break;
            } else {
                $result['amount'] = $balance->amount;
                $result['success'] = true;
                break;
            }
        } while (false);
        return response()->json($result);
    }

    public function addSub(Request $request)
    {
        $token = $request->input('token');
        $result['success'] = false;
        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            $user = DB::table('users')->where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Токен не найден';
                break;
            }
            DB::table('subscription')->insertGetId([
                'user_id' => $user->id,
                'start' => date('Y-m-d'),
                'end' => date('Y-m-d', strtotime("+30 days")),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }
}
