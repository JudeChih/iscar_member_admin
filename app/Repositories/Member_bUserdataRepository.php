<?php

namespace App\Repositories;

use App\Models\md_userdata;
use DB;

class Member_bUserdataRepository {

    /**
     * 取得十筆資料
     * @return type
     */
    public function getTenData($query_name,$query_status) {
        try {
            if(is_null($query_name)){
                $query_name = '';
            }
            $adm_string = md_userdata::where('usd_name','like', '%'.$query_name.'%');
            if($query_status != 2 && !is_null($query_status)){
                $adm_string->where('usd_status',$query_status);
            }
            return $adm_string->where('isflag',1)->paginate(10);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 使用「$usd_account,$usd_pwd」查詢資料
     * @param string $usd_account
     * @param string $usd_pwd
     */
    public function getDataByAccountPwd($usd_account,$usd_pwd) {
        return md_userdata::where('usd_status',1)->where('usd_account',$usd_account)->where('usd_pwd',$usd_pwd)->where('isflag',1)->get();
    }

    /**
     * 使用「$usd_serno」查詢資料
     * @param string $usd_name
     */
    public function getDataBySerno($usd_serno) {
        return md_userdata::where('usd_serno',$usd_serno)->where('isflag',1)->get();
    }

    /**
     * 使用「$usd_name」查詢資料
     * @param string $usd_name
     */
    public function getDataByName($usd_name) {
        return md_userdata::where('usd_status',1)->where('usd_admin',1)->where('usd_name',$usd_name)->where('isflag',1)->get();
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        try {
            if(!isset($arraydata['usd_account']) || !isset($arraydata['usd_name']) || !isset($arraydata['usd_status']) || !isset($arraydata['usd_pwd'])){
                return false;
            }
            $data = md_userdata::where('usd_account',$arraydata['usd_account'])->where('isflag',1)->get();
            if(count($data) > 0){
                return false;
            }
            DB::beginTransaction();
            $savedata['usd_account'] = $arraydata['usd_account'];
            $savedata['usd_name'] = $arraydata['usd_name'];
            $savedata['usd_status'] = $arraydata['usd_status'];
            $savedata['usd_pwd'] = $arraydata['usd_pwd'];
            $savedata['usd_admin'] = 0;

            if(isset($arraydata['isflag'])){
                $savedata['isflag'] = $arraydata['isflag'];
            }else{
                $savedata['isflag'] = 1;
            }
            if(isset($arraydata['create_user'])){
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if(isset($arraydata['last_update_user'])){
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            $savedata['create_date'] = \Carbon\Carbon::now();
            $savedata['last_update_date'] = \Carbon\Carbon::now();

            $result = md_userdata::insert($savedata);
            if($result){
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (Exception $e) {
            DB::rollback();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 更新該「$usd」的資料
     * @param array $arraydata 要更新的資料
     * @param type $primarykey
     * @return type
     */
    public function update(array $arraydata) {
        try {
            if(!isset($arraydata['serno'])){
                return false;
            }
            DB::beginTransaction();
            if(isset($arraydata['usd_name'])){
                $savedata['usd_name'] = $arraydata['usd_name'];
            }
            if(isset($arraydata['usd_status'])){
                $savedata['usd_status'] = $arraydata['usd_status'];
            }
            if(isset($arraydata['usd_admin'])){
                $savedata['usd_admin'] = $arraydata['usd_admin'];
            }
            if(isset($arraydata['usd_account'])){
                $savedata['usd_account'] = $arraydata['usd_account'];
            }
            if(isset($arraydata['usd_pwd'])){
                $savedata['usd_pwd'] = $arraydata['usd_pwd'];
            }
            if(isset($arraydata['last_update_user'])){
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            $savedata['last_update_date'] = \Carbon\Carbon::now();

            $result = md_userdata::where('usd_serno',$arraydata['serno'])->update($savedata);
            if($result){
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (Exception $e) {
            DB::rollback();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 刪除該「$primarykey」的資料
     * @param type $primarykey 主鍵值
     */
    public function delete($usd_serno) {
        try {
            $savedata['isflag'] = 0;
            $savedata['last_update_date'] = \Carbon\Carbon::now();

            $result = md_userdata::where('usd_serno',$usd_serno)->update($savedata);
            if($result){
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (Exception $e) {
            DB::rollback();
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

}
