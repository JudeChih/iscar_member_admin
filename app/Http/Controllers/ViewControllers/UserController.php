<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use \Illuminate\Support\Facades\View;
use Session;

class UserController extends Controller
{
    /**
     * View [ UserData ] Route 使用者列表
     */
    public function userList(){
        $aud_r = new \App\Repositories\Member_bUserdataRepository;
        $data = Request::all();
        $query_name = null;
        $query_status = null;
        try {
            if(isset($data['query_name'])){
                $query_name = $data['query_name'];
            }
            if(isset($data['query_status'])){
                $query_status = $data['query_status'];
            }
            if(!$userdata = $aud_r->getTenData($query_name,$query_status)){
                return View::make('login/userdatalist');
            }

            return View::make('login/userdatalist',compact('userdata','query_name','query_status'));
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * View [ Userdatalist_modify ] 個別使用者資料
     */
    public function userData(){
        $aud_r = new \App\Repositories\Member_bUserdataRepository;
        try {
            $serno = Session::get('serno');
            if(!$userdata = $aud_r->getDataBySerno($serno)){
                return redirect('/index')->withInput()->withErrors(['查無會員資料！']);
            }
            if(count($userdata) ==0 || count($userdata) >1 ){
                return redirect('/index')->withInput()->withErrors(['查無會員資料！']);
            }
            if(count($userdata) ==1){
                $userdata = $userdata[0];
            }
            return View::make('login/user_modify',compact('userdata'));
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * View [ Userdatalist_modify ] 新增使用者資料
     */
    public function userListCreate(){   
        $modifytype = 'userListCreate';
        try {
            return View::make('login/userdatalist_modify',compact('modifytype'));
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * View [ User_modify ] 修改使用者資料
     */
    public function userListModify(){
        $modifytype = 'userListEdit';
        $aud_r = new \App\Repositories\Member_bUserdataRepository;
        $data = Request::all();
        try {
            if(!$userdata = $aud_r->getDataBySerno($data['usd_serno'])){
                return redirect('/login/user-list')->withErrors(['error' => '查無會員資料！']);
            }
            if(count($userdata) == 0 || count($userdata) >1){
                return redirect('/login/user-list')->withErrors(['error' => '查無會員資料！']);
            }
            if(count($userdata) == 1){
                $userdata = $userdata[0];
            }
            return View::make('login/userdatalist_modify',compact('modifytype','userdata'));
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 刪除使用者資料
     *
     * @param  [string] $ud_guid
     * @return [string] $prompt 回傳更新成功與否的訊息
     */
    public function userListDelete(){
        $aud_r = new \App\Repositories\Member_bUserdataRepository;
        $data = Request::all();
        try {
            if(!$result = $aud_r->delete($data['usd_serno'])){
                return redirect('/login/user-list')->withErrors(['error' => '刪除失敗！']);
            }
            return redirect('/login/user-list')->withErrors(['error' => '刪除成功！']);
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 儲存使用者異動資料
     *
     * @param  [string]  $request [使用者資料]
     * @return [boolean] $prompt  [回傳更新成功與否的訊息]
     */
    public function save(){
        $aud_r = new \App\Repositories\Member_bUserdataRepository;
        $data = Request::all();
        try {
            if($data['modifytype'] == 'userListCreate'){
                if(!$result = $aud_r->create($data)){ // 新增會員資料
                    return redirect('/login/user-list')->withErrors(['error' => '新增失敗！']);
                }
                return redirect('/login/user-list')->withErrors(['error' => '新增成功！']);
            }elseif($data['modifytype'] == 'userListEdit'){ // 修改會員資料
                if(!$result = $aud_r->update($data)){
                    return redirect('/login/user-list')->withErrors(['error' => '修改失敗！']);
                }
                return redirect('/login/user-list')->withErrors(['error' => '修改成功！']);
            }elseif($data['modifytype'] == 'userModify'){ // 個別會員自行修改
                if(!$result = $aud_r->update($data)){
                    return redirect('/login/user-modify')->withErrors(['error' => '修改失敗！']);
                }
                return redirect('/login/user-modify')->withErrors(['error' => '修改成功！']);
            }
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
