<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;

class ModuleDataController extends Controller {

    /**
     * 導到[簡訊管理]頁面
     */
    public function moduledata() {
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        $searchdata = Request::all();
        $query_module_name = null;
        $query_functiontype = null;
        $sort = null;
        $order = null;
        try {
            if(isset($searchdata['sort'])){
                $sort = $searchdata['sort'];
            }
            if(isset($searchdata['order'])){
                $order = $searchdata['order'];
            }
            if(isset($searchdata['query_module_name'])){
                $query_module_name = $searchdata['query_module_name'];
            }
            if(isset($searchdata['query_functiontype'])){
                if($searchdata['query_functiontype'] != 0){
                    $query_functiontype = $searchdata['query_functiontype'];
                }
            }
            // 獲取簡訊資料，一個頁面顯示10個
            $moduledata = $map_r->getTenData($query_module_name,$query_functiontype,$sort,$order);
            // 無業務就不傳值
            if(count($moduledata) == 0){
                return View::make('member/moduledatalist');
            }
            return View::make('member/moduledatalist',compact('moduledata','query_module_name','query_functiontype','sort','order'));
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 更改模組狀態
     * @param array  $isflag  [要做變更的模組代碼以及模組狀態的陣列]
     */
    public function changeIsflag(){
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        $moduledata = Request::all();
        $arraydata = $moduledata['isflag'];
        try {
            DB::beginTransaction();
            foreach ($arraydata as $data) {
                if($data['isflag'] == 1){
                    $result = $map_r->delete($data['mapr_serno'],0);
                }elseif($data['isflag'] == 0){
                    $result = $map_r->delete($data['mapr_serno'],1);
                }
                if(!$result){
                    DB::rollback();
                    return '修改失敗';
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

    /**
     * 進入模組修改頁面
     * @param   sting $mapr_serno [模組代碼]
     */
    public function modify(){
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        $data = Request::all();
        try {
            $moduledata = $map_r->getDataBySerno($data['mapr_serno']);
            if(count($moduledata) > 1 || count($moduledata) == 0){
                return redirect('/member/moduledatalist')->withErrors(['error' => '模組資料有誤！']);
            }
            if(count($moduledata) == 1){
                $moduledata = $moduledata[0];
            }
            return View::make('member/moduledatalist_modify',compact('moduledata'));
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 送出模組修改的資料並進行存檔
     * @return array [模組要修改的資料]
     */
    public function save(){
        $map_r = new \App\Repositories\ModuleAccPass_rRepository;
        $data = Request::all();
        try {
            if(!$result = $map_r->UpdateData($data)){
                return redirect('/member/moduledata')->withErrors(['error' => '修改失敗！']);
            }

            return redirect('/member/moduledata')->withErrors(['error' => '修改成功！']);

        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
