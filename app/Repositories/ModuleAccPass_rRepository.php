<?php

namespace App\Repositories;

use App\Models\icr_ModuleAccPass_r;
use DB;
use Session;

class ModuleAccPass_rRepository {

    /**
     * 獲取10筆符合條件的資料
     * @param  [string] $query_module_name  [模組名稱]
     * @param  [string] $query_functiontype [模組類型]
     * @param  [string] $sort               [排序依據]
     * @param  [string] $order              [排序方式]
     */
    public function getTenData($query_module_name,$query_functiontype,$sort,$order){
        try {
            if(is_null($sort) && is_null($order)){
                $sort = 'mapr_moduleaccount';
                $order = 'DESC';
            }
            if(is_null($query_module_name)){
                $query_module_name = '';
            }
            $mod_string = icr_ModuleAccPass_r::where('mapr_modulename','like', '%'.$query_module_name.'%');
            if(!is_null($query_functiontype)){
                $mod_string->where('mapr_functiontype',$query_functiontype);
            }
            return $mod_string->orderBy($sort,$order)->paginate(10);
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    public function getDataByFunctionType($functiontype){
        try {
            if(is_null($functiontype)){
                return icr_ModuleAccPass_r::get();
            }
            return icr_ModuleAccPass_r::where('mapr_functiontype',$functiontype)->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 透過mapr_serno抓取模組資料
     * @param  [string] $serno [模組代碼]
     */
    public function getDataBySerno($serno){
        try {
            return icr_ModuleAccPass_r::where('mapr_serno',$serno)->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * ██████████▍READ 讀取資料
     */
     public function GetModulUserData($account) {
        try {
             $query = icr_ModuleAccPass_r::where('icr_moduleaccpass_r.mapr_moduleaccount', '=', $account);

             $result = $query->select( 'icr_moduleaccpass_r.mapr_serno'
                                      ,'icr_moduleaccpass_r.mapr_moduleaccount'
                                      ,'icr_moduleaccpass_r.mapr_modulepassword'
                                     )
                                    ->get()->toArray();
            return $result;
        } catch(Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return null;
        }
     }

    /**
     * ██████████▍UPDATE 更新資料
     */
     public function UpdateData(array $arraydata) {


        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_serno")) {
                return false;
            }
            DB::beginTransaction();
            $savedata['mapr_serno'] = $arraydata['mapr_serno'];

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_moduleaccount")) {
                $savedata['mapr_moduleaccount'] = $arraydata['mapr_moduleaccount'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_modulepassword")) {
                $savedata['mapr_modulepassword'] = $arraydata['mapr_modulepassword'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_modulename")) {
                $savedata['mapr_modulename'] = $arraydata['mapr_modulename'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_modulefunction")) {
                $savedata['mapr_modulefunction'] = $arraydata['mapr_modulefunction'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_module_ip")) {
                $savedata['mapr_module_ip'] = $arraydata['mapr_module_ip'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_redirect_uri")) {
                $savedata['mapr_redirect_uri'] = $arraydata['mapr_redirect_uri'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_contactmail")) {
                $savedata['mapr_contactmail'] = $arraydata['mapr_contactmail'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_contactname")) {
                $savedata['mapr_contactname'] = $arraydata['mapr_contactname'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_contactmobile")) {
                $savedata['mapr_contactmobile'] = $arraydata['mapr_contactmobile'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "mapr_functiontype")) {
                $savedata['mapr_functiontype'] = $arraydata['mapr_functiontype'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }
            if(!empty(Session::get('user_name'))){
                $savedata['last_update_user'] = Session::get('user_name');
            }
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            icr_ModuleAccPass_r::where('mapr_serno', '=', $savedata['mapr_serno'])
                    ->update($savedata);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollBack();
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 使用「$moduleaccount」查詢資料表的mapr_moduleaccount欄位
     * @param type $moduleaccount 要查詢的值
     * @return type
     */
    public function getDataByAccount($moduleaccount) {
        return icr_ModuleAccPass_r::where('isflag',1)->where('mapr_moduleaccount',$moduleaccount)->get();
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        return null;
    }

    /**
     * 更新該「$primarykey」的資料
     * @param array $arraydata 要更新的資料
     * @param type $primarykey
     * @return type
     */
    public function update(array $arraydata, $primarykey) {
        return null;
    }

    /**
     * 停用或啟用該「$mapr_serno」的資料
     * @param string $mapr_serno  會員代碼
     * @param string $isflag 停用或啟用
      */
    public function delete($mapr_serno,$isflag) {
        try {
            $savedata['isflag'] = $isflag;
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            if(!empty(\App\Services\AuthService::userName())){
                $savedata['last_update_user'] = \App\Services\AuthService::userName();
            }
            icr_ModuleAccPass_r::where('mapr_serno',$mapr_serno)->update($savedata);
            return true;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

}
