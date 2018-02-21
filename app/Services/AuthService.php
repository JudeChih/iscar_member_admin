<?php
namespace App\Services;

use Session;
use Request;

class AuthService {


    private static $sessionToken = 'jwttoken';
    private static $sessionUserData = 'userdata';
    private static $sessionUserName = 'user_name';
    private static $sessionUserSerno = 'serno';
    private static $sessionUserAdmin = 'admin';

    /**
     * 儲存「JWT Token」
     * @param type $token
     */
    public static function saveToken($token, $userdata) {

        if (!isset($token) || !isset($userdata)) {
            return \Illuminate\Support\Facades\Redirect::route('logout');
        }
        // 存各式資料
        Session::put(AuthService::$sessionToken, $token);
        Session::put(AuthService::$sessionUserData, $userdata);
        Session::put(AuthService::$sessionUserName, $userdata->usd_name);
        Session::put(AuthService::$sessionUserAdmin, $userdata->usd_admin);
        Session::put(AuthService::$sessionUserSerno, $userdata->usd_serno);

    }

    /**
     * 清除「JWT Token」
     */
    public static function clearToken() {
        Session::flush();
    }

    /**
     * 取得「JWT Token」
     * @return type
     */
    public static function token() {
        return Session::get(AuthService::$sessionToken);
    }

    /**
     * 使用者資料
     * @return type
     */
    public static function userData() {
        return Session::get(AuthService::$sessionUserData);
    }

    /**
     * 使用者名稱
     * @return type
     */
    public static function userName() {
        return Session::get(AuthService::$sessionUserName);
    }

    /**
     * 使用者狀態
     * @return type
     */
    public static function userAdmin() {
        return Session::get(AuthService::$sessionUserAdmin);
    }

    /**
     * 使用者身分
     * @return type
     */
    public static function userSerno() {
        return Session::get(AuthService::$sessionUserSerno);
    }

}
