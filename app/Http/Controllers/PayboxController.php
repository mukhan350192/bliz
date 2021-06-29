<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayboxController extends Controller
{
    public function makePayment(Request $request)
    {
        info('Paybox');

        $rules = array(
            'amount' => 'required',
            'user_id' => 'required'
        );
        $messages = [
            'amount.required' => 'Требуется ввести сумму',
            'user_id.required' => 'Пользователь не найден',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $amount = $request->input('amount');
        $user_id = $request->input('user_id');
        $success_url = 'bliz.kz';
        $failure_url = 'bliz.kz';
        $merchant_id = 517822;

        $description = 'Погашение займа';

        $url = 'https://api.paybox.money/payment.php';

        $data = [
            'extra_user_id' => $user_id,
            'pg_merchant_id' => $merchant_id,//our id in Paybox, will be gived on contract
            'pg_amount' => $amount, //amount of payment
            'pg_salt' => "Salt", //amount of payment
            'pg_order_id' => $user_id, //id of purchase, strictly unique
            'pg_description' => $description, //will be shown to client in process of payment, required
            'pg_result_url' => route('payment-result'),//route('payment-result')
            'pg_success_url' => $success_url,
        ];

        ksort($data);
        array_unshift($data, 'payment.php');
        array_push($data, 'BAN87lbNd3pfrwrC');

        $data['pg_sig'] = md5(implode(';', $data));

        unset($data[0], $data[1]);

        $query = http_build_query($data);
        $arr = [$url, $query];
        return $arr;

    }

    public function paymentResult(Request $request)
    {
        if ($request->pg_result) {
           // DB::beginTransaction();
            $user_id = (int)$request->extra_user_id;
            $amount = $request->pg_amount;

            $user = DB::table('balance')->where('user_id', $user_id)->first();
            if (!$user) {
                $update = DB::table('balance')->insertGetId([
                    'user_id' => $user_id,
                    'amount' => $amount,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $balance = DB::table('balance_history')->insertGetId([
                    'user_id' => $user_id,
                    'type' => 'Пополнение',
                    'amount' => $amount,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                if (!$update){
                //    DB::rollBack();
                    return response()->json([
                        'message' => 'Попробуйте позже',
                    ]);
                }
                return response()->json([
                    'success' => true,
                ]);
            }
            $update = DB::table('balance')->where('user_id',$user_id)->update([
                'amount' => $user->amount+$amount,
            ]);
            if (!$update){
             //   DB::rollBack();
                return response()->json([
                    'message' => 'Попробуйте позже',
                ]);
            }
            $balance = DB::table('balance_history')->insertGetId([
                'user_id' => $user_id,
                'type' => 'Пополнение',
                'amount' => $amount,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
            ]);

            //DB::commit();
        }
        return response()->json([
            'message' => 'fail in life',
            'success' => false
        ])->setStatusCode(400);

    }
}
