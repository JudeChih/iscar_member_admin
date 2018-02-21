<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;

class MemberDataController extends Controller {

    /**
     * 導到[會員管理]頁面
     */
    public function memberdata() {
        $md_r = new \App\Repositories\MemberDataRepository;
        $searchdata = Request::all();
        $isflag = null;
        $search_type = null;
        $client_type = null;
        $query_status = null;
        $query_day = null;
        $query_name = null;
        $sort = null;
        $order = null;
        try {
            if(isset($searchdata['sort'])){
                $sort = $searchdata['sort'];
            }
            if(isset($searchdata['order'])){
                $order = $searchdata['order'];
            }
            if(isset($searchdata['isflag'])){
                if($searchdata['isflag'] != 2){
                    $isflag = $searchdata['isflag'];
                }
            }
            if(isset($searchdata['client_type'])){
                if($searchdata['client_type'] != '-1'){
                    $client_type = $searchdata['client_type'];
                }
            }
            if(isset($searchdata['search_type'])){
                $search_type = $searchdata['search_type'];
            }
            if(isset($searchdata['query_status'])){
                $query_status = $searchdata['query_status'];
            }
            if(isset($searchdata['query_day'])){
                $query_day = $searchdata['query_day'];
            }
            if(isset($searchdata['query_name'])){
                $query_name = $searchdata['query_name'];
            }
            // 獲取會員資料，一個頁面顯示10個
            $memberdata = $md_r->getTenData($query_name,$query_status,$query_day,$isflag,$sort,$order,$search_type,$client_type);
            // 無會員就不傳值
            if(count($memberdata) == 0){
                return View::make('member/memberdatalist');
            }
            // 將會員類別由代碼轉文字
            foreach ($memberdata as $data) {
                $data['md_clienttype'] = \App\Library\CommonTools::clienttype_numToWord($data['md_clienttype']);
            }
            return View::make('member/memberdatalist',compact('memberdata','query_name','query_status','query_day','isflag','sort','order','search_type','client_type'));
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
