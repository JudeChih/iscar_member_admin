<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Auth\JWTController;
use App\Services\AuthService;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    use JWTController;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * 執行登入
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    public function login(\Illuminate\Http\Request$request) {
        try {
            //檢查帳號密碼是否有填寫
            if (!isset($request->ud_account) || !isset($request->ud_pwd)) {
                AuthService::clearToken();
                return redirect()->back()->withInput()->withErrors(['error' => '使用者名稱錯誤！！']);
            }
            //檢查是否有這個使用者資料
            $userdata = $this->checkUserStatus($request->ud_account,$request->ud_pwd);
            if (!isset($userdata)) {
                AuthService::clearToken();
                return redirect()->back()->withInput()->withErrors(['error' => '使用者名稱錯誤！！']);
            }

            //建立「JWT Token」
            $jwttoken = $this->generateJWTToken($userdata);
            if (!isset($jwttoken)) {
                AuthService::clearToken();
                return redirect()->back()->withInput()->withErrors(['error' => '使用者名稱錯誤！！']);
            }
            //儲存「Token」
            AuthService::saveToken($jwttoken, $userdata);

            return redirect('/index');
        } catch (Exception $ex) {
            AuthService::clearToken();
            return redirect()->back()->withInput()->withErrors(['error' => '使用者名稱錯誤！！']);
        }
    }

    /**
     * 執行登出
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    function logOut(\Illuminate\Http\Request$request) {
        //清除「Token」
        AuthService::clearToken();
        return redirect('/login');
    }

    /**
     * 檢查使用者帳號密碼，並取得使用者資料
     * @param type $userName 使用者帳號
     * @param type $userPassword 使用者密碼
     * @return type 使用者資料 [ usd_admin ,ud_name ,usd_serno ]
     */
    private function checkUserStatus($userName,$userPassword) {
        $mb_r = new \App\Repositories\Member_bUserdataRepository;
        $userdata = $mb_r->getDataByAccountPwd($userName,$userPassword);
        if (count($userdata) > 0) {
            // 做成Json格式回傳
            return json_decode(json_encode(['usd_name' => $userdata[0]->usd_name,'usd_admin' => $userdata[0]->usd_admin,'usd_serno' => $userdata[0]->usd_serno]));
        } else {
            return null;
        }
    }
}
