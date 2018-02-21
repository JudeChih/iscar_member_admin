<?php

namespace App\Services;

define('KOT_USER_NAME', config('global.kot_user_name'));
define('KOT_PASSWORD', config('global.kot_password'));
define('KOT_API_URL', config('global.kot_api_url'));

class SmsKotService {

    /**
     * 發送簡訊
     * @param type $mobile 手機號碼
     * @param type $smsMessage 發送訊息
     * @return boolean
     */
    public static function sendSMS($mobile, $smsMessage) {
        try {
            //return true;
            // 簡訊王帳號
            $username = KOT_USER_NAME;
            //簡訊王密碼
            $password = KOT_PASSWORD;
            //接收手機號碼
            $dstaddr = $mobile;
            //訊息內容，需先轉為「BIG5」再使用「urlencode」
            $smbody = urlencode(mb_convert_encoding($smsMessage, "BIG5"));
            //簡訊預約發送時間，建議設定為"0"即時發送
            $dlvtime = '0';
            //發送簡訊的有效期限，設定為0秒時有效時間將依簡訊中心流量設定值配送約4~24小時，不宣告此參數時，有效期限為預設值8小時
            $vldtime = '1800';
            /*
              //發送簡訊的有效期限，設定為0秒時有效時間將依簡訊中心流量設定值配送約4~24小時，不宣告此參數時，有效期限為預設值8小時
              $vldtime = '0';
              //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報
              $response = '';
             */
            $smsApiUrl = KOT_API_URL . "?username={$username}&password={$password}&dstaddr={$dstaddr}&smbody={$smbody}&dlvtime={$dlvtime}"; //&vldtime={$vldtime}&response={$response}";

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $smsApiUrl);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            $strResponse = curl_exec($curl);
            curl_close($curl);

            $tmparray  =  explode ( 'kmsgid' , $strResponse );
            if ( count ( $tmparray )>1){
                return  true;
            }  else {
                return  false;
            }
        } catch (\Exception $ex) {
            return false;
        }
    }
}
