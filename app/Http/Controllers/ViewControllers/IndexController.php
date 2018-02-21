<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;

class IndexController extends Controller {

    /**
     * 導到[index]頁面
     */
    public function indexdata() {
        $md_r = new \App\Repositories\MemberDataRepository;
        $sn_r = new \App\Repositories\SmsNumberCodeRepository;
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        $searchdata = Request::all();
        try {
            // 抓出當月第一天跟當月最後一天
            $end_this = new \Carbon\Carbon('last day of this month');
            $end_this->second(0);
            $end_this->minute(0);
            $end_this->hour(0);
            $end_this->addDay();
            $first_this = new \Carbon\Carbon('first day of this month');
            $first_this->second(59);
            $first_this->minute(59);
            $first_this->hour(23);
            $first_this->subDay();
            // 抓上月第一天跟上月第一天
            $end_last = new \Carbon\Carbon('last day of last month');
            $end_last->second(0);
            $end_last->minute(0);
            $end_last->hour(0);
            $end_last->addDay();
            $first_last = new \Carbon\Carbon('first day of last month');
            $first_last->second(59);
            $first_last->minute(59);
            $first_last->hour(23);
            $first_last->subDay();
            // 抓會員總數
            $mem_num = $md_r->getDataByMdLoginType(null);
            $indexData['mem_num'] = count($mem_num);
            // 抓FB會員數
            $mem_fb = $md_r->getDataByMdLoginType(0);
            $indexData['mem_fb'] = count($mem_fb);
            // 抓一般會員數
            $mem_iscar = $md_r->getDataByMdLoginType(3);
            $indexData['mem_iscar'] = count($mem_iscar);
            // 抓當月新增會員數
            $mem_num_this = $md_r->getDataByTimes($first_this,$end_this);
            $indexData['mem_num_this'] = count($mem_num_this);
            // 抓上月新增會員數
            $mem_num_last = $md_r->getDataByTimes($first_last,$end_last);
            $indexData['mem_num_last'] = count($mem_num_last);
            // 抓當月簡訊發送總數
            $sms_num_this = $sn_r->getDataBySendresult(null,$first_this,$end_this);
            $indexData['sms_num_this'] = count($sms_num_this);
            // 抓當月簡訊發送成功數
            $sms_success_this = $sn_r->getDataBySendresult(1,$first_this,$end_this);
            $indexData['sms_success_this'] = count($sms_success_this);
            // 抓當月簡訊發送失敗數
            $sms_fail_this = $sn_r->getDataBySendresult(0,$first_this,$end_this);
            $indexData['sms_fail_this'] = count($sms_fail_this);
            // 抓上月簡訊發送總數
            $sms_num_last = $sn_r->getDataBySendresult(null,$first_last,$end_last);
            $indexData['sms_num_last'] = count($sms_num_last);
            // 抓上月簡訊發送成功數
            $sms_success_last = $sn_r->getDataBySendresult(1,$first_last,$end_last);
            $indexData['sms_success_last'] = count($sms_success_last);
            // 抓上月簡訊發送失敗數
            $sms_fail_last = $sn_r->getDataBySendresult(0,$first_last,$end_last);
            $indexData['sms_fail_last'] = count($sms_fail_last);
            // 抓簡訊總發送量
            $sms_total = $sn_r->getAllData();
            $indexData['sms_total'] = count($sms_total);
            // 抓模組總數
            $mod_num = $map_r->getDataByFunctionType(null);
            $indexData['mod_num'] = count($mod_num);
            // 抓前台模組數
            $mod_front = $map_r->getDataByFunctionType(1);
            $indexData['mod_front'] = count($mod_front);
            // 抓後台模組數
            $mod_back = $map_r->getDataByFunctionType(2);
            $indexData['mod_back'] = count($mod_back);

            return View::make('member/indextotal',compact('indexData'));
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 更改會員狀態
     * @param array  $isflag  [要做變更的會員代碼以及會員狀態的陣列]
     */
    public function changeIsflag(){
        $md_r = new \App\Repositories\MemberDataRepository;
        $memberdata = Request::all();
        $arraydata = $memberdata['isflag'];
        try {
            DB::beginTransaction();
            foreach ($arraydata as $data) {
                if($data['isflag'] == 1){
                    $result = $md_r->delete($data['md_id'],0);
                }elseif($data['isflag'] == 0){
                    $result = $md_r->delete($data['md_id'],1);
                }
                if(!$result){
                    DB::rollback();
                    return false;
                }
            }
            DB::commit();
            return '修改成功';
        } catch (Exception $e) {
            DB::rollback();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return '修改失敗';
        }
    }
}
