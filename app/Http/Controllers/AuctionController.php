<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuctionDetails;
use App\Http\Resources\AuctionMinDetails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    public function addAuction(Request $request)
    {
        $token = $request->input('token');
        $date_finish = $request->input('date_finish');
        $from_city = $request->input('from_city');
        $to_city = $request->input('to_city');

        $middle_city = $request->input('middle_city');
        $from_string = $request->input('from_string');
        $to_string = $request->input('to_string');
        $distance = $request->input('distance');
        $duration = $request->input('duration');

        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $title = $request->input('title');
        $type_transport = $request->input('type_transport');

        $quantity = $request->input('quantity');
        $volume = $request->input('volume');
        $net = $request->input('net');
        $width = $request->input('width');
        $length = $request->input('length');
        $height = $request->input('height');
        $price = $request->input('price');
        $currency = $request->input('currency');
        $paymnent_type = $request->input('paymnent_type');

        $documents = $request->input('documents');
        $loading = $request->input('loading');
        $condition = $request->input('condition');
        $addition = $request->input('addition');

        $negotiable_price = $request->input('negotiable_price');
        $nds = $request->input('nds');
        $when_loading = $request->input('when_loading');
        $at_unloading = $request->input('at_unloading');
        $prepayment = $request->input('prepayment');
        $bargain = $request->input('bargain');
        $price_request = $request->input('price_request');

        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }

            if (!$date_finish) {
                $result['message'] = 'Не передан когда закончится аукцион';
                break;
            }

            if (!$from_city) {
                $result['message'] = 'Не передан откуда увезти груз';
                break;
            }
            if (!$to_city) {
                $result['message'] = 'Не передан куда перевести груз';
                break;
            }
            if (!$date_start) {
                $result['message'] = 'Не передан дата погрузки';
                break;
            }
            if (!$date_end) {
                $result['message'] = 'Не передан дата выгрузки';
                break;
            }
            if (!$title) {
                $result['message'] = 'Не передан характеристика груза';
                break;
            }
            if (!$type_transport) {
                $result['message'] = 'Не передан тип транспорта';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }

            DB::beginTransaction();

            $auctionID = DB::table('auction')->insertGetId([
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$auctionID) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }
            $date_finish = date('Y-m-d H:i', strtotime($date_finish));
            $date_start = date('Y-m-d', strtotime($date_start));
            $date_end = date('Y-m-d', strtotime($date_end));

            $detailsID = DB::table('auction_details')->insertGetId([
                'auction_id' => $auctionID,
                'date_finish' => $date_finish,
                'from_city' => $from_city,
                'to_city' => $to_city,
                'middle_city' => $middle_city,
                'from_string' => $from_string,
                'to_string' => $to_string,
                'distance' => $distance,
                'duration' => $duration,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'title' => $title,
                'type_transport' => $type_transport,
                'quantity' => $quantity,
                'net' => $net,
                'volume' => $volume,
                'width' => $width,
                'length' => $length,
                'height' => $height,
                'price' => $price,
                'currency' => $currency,
                'payment_type' => $paymnent_type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$detailsID) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
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
            if (!empty($add)) {
                $add = ltrim($add, $add[0]);
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


            $auctionAdditional = DB::table('auction_additional')->insertGetId([
                'auction_id' => $auctionID,
                'documents' => $docs,
                'loading' => $load,
                'condition' => $con,
                'addition' => $add,
                'negotiable_price' => $negotiable_price,
                'nds' => $nds,
                'when_loading' => $when_loading,
                'at_unloading' => $at_unloading,
                'prepayment' => $prepayment,
                'bargain' => $bargain,
                'price_request' => $price_request,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if (!$auctionAdditional) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }


            DB::commit();
            $result['success'] = true;
        } while (false);

        return response()->json($result);
    }

    public function getAllAuction(Request $request)
    {
        $page = $request->input('page');
        $take = 10;
        if (!isset($page) || $page == 1) {
            $page = 1;
            $skip = 0;
        } else {
            $skip = ($page - 1) * 10;
        }
        $count = DB::table('auction')->count();
        $data = AuctionMinDetails::collection(DB::table('auction')->skip($skip)->take($take)->get());
        $result['current_page'] = $page;
        $result['max_page'] = ceil($count / 10);
        $result['per_page'] = 10;
        $result['total'] = $count;
        $result['data'] = $data;
        return response()->json($result);
    }

    public function getAuctionById(Request $request)
    {
        $auction_id = $request->input('auction_id');
        $result['success'] = false;
        do {
            if (!$auction_id) {
                $result['message'] = 'Не передан аукцион айди';
                break;
            }
            $auction = DB::table('auction')->where('id', $auction_id)->get();
            if (!$auction) {
                $result['message'] = 'Не найден аукцион';
                break;
            }
            $data = AuctionDetails::collection($auction);
            $result['data'] = $data;
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function sendAuctionRequest(Request $request)
    {
        $token = $request->input('token');
        $price = $request->input('price');
        $auction_id = $request->input('auction_id');
        $currency = $request->input('currency');
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$price) {
                $result['message'] = 'Не передан цена';
                break;
            }
            if (!$currency) {
                $result['message'] = 'Не передан валюта';
                break;
            }
            if (!$auction_id) {
                $result['message'] = 'Не передан аукцион айди';
                break;
            }

            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $auction = DB::table('auction_orders')->where('user_id',$user->id)->where('auction_id',$auction_id)->first();
            if (isset($auction)){
                $result['message'] = 'Вы уже отправили заявку';
                break;
            }

            DB::beginTransaction();
            $auctionOrders = DB::table('auction_orders')->insertGetId([
                'auction_id' => $auction_id,
                'user_id' => $user->id,
                'price' => $price,
                'currency' => $currency,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if (!$auctionOrders) {
                DB::rollBack();
                $result['message'] = 'Попробуйте позже';
                break;
            }
            DB::commit();
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function cancelAuctionOrder(Request $request){
        $token = $request->input('token');
        $auction_id = $request->input('auction_id');
        $result['success'] = false;

        do{
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$auction_id) {
                $result['message'] = 'Не передан аукцион айди';
                break;
            }
            $user = User::where('token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $auction = DB::table('auction_orders')->where('user_id',$user->id)->where('auction_id',$auction_id)->first();
            if (!isset($auction)){
                $result['message'] = 'Не найден аукцион';
                break;
            }
            $auction->delete();
            $auction->save();

            $result['success'] = true;

        }while(false);

        return response()->json($result);

    }

}
