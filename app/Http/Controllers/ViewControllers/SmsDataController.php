<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;

class SmsDataController extends Controller {

    /**
     * 導到[簡訊管理]頁面
     */
    public function smsdata() {
        $sd_r = new \App\Repositories\SmsNumberCodeRepository;
        $searchdata = Request::all();
        $query_time_from = null;
        $query_time_to = null;
        $query_phone = null;
        $sort = null;
        $order = null;
        try {
            if(isset($searchdata['sort'])){
                $sort = $searchdata['sort'];
            }
            if(isset($searchdata['order'])){
                $order = $searchdata['order'];
            }
            if(isset($searchdata['query_time_from'])){
                $query_time_from = $searchdata['query_time_from'];
            }
            if(isset($searchdata['query_time_to'])){
                $query_time_to = $searchdata['query_time_to'];
            }
            if(isset($searchdata['query_phone'])){
                $query_phone = $searchdata['query_phone'];
            }
            if(isset($query_time_from) && isset($query_phone) && isset($query_phone)){
                // 獲取簡訊資料，一個頁面顯示10個
                $smsdata = $sd_r->getTenData($query_time_from,$query_time_to,$query_phone,$sort,$order);
                // 無業務就不傳值
                if(count($smsdata) == 0){
                    return View::make('member/smsdatalist');
                }
                // 獲取時間區間的發送總筆數
                $total_result = $sd_r->getTotalSMS($query_time_from,$query_time_to,$query_phone);
                // 獲取時間區間的發送成功筆數
                $success_result = $sd_r->getSuccessSMS($query_time_from,$query_time_to,$query_phone);
                // 獲取時間區間的發送失敗筆數
                $fail_result = $sd_r->getFailSMS($query_time_from,$query_time_to,$query_phone);
                $sms_total = count($total_result);
                $sms_success = count($success_result);
                $sms_fail = count($fail_result);
                return View::make('member/smsdatalist',compact('smsdata','query_time_from','query_time_to','query_phone','sort','order','sms_total','sms_success','sms_fail'));
            }
            return View::make('member/smsdatalist');
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
