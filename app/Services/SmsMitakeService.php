<?php

namespace App\Services;

define('MITAKE_USER_NAME', config('global.mitake_user_name'));
define('MITAKE_PASSWORD', config('global.mitake_password'));
define('MITAKE_API_URL', config('global.mitake_api_url'));

class SmsMitakeService {

    /**
     * 發送簡訊
     * @param type $countryCode 發送區域：０測試假值、１台灣、２大陸
     * @param type $mobile 手機號碼
     * @param type $smsMessage 發送訊息
     * @return boolean
     */
    public static function sendSMS($mobile, $smsMessage) {
        try {
            //return true;
            // 三竹帳號
            $username = MITAKE_USER_NAME;
            //三竹密碼
            $password = urlencode(MITAKE_PASSWORD);
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
            $smsApiUrl = MITAKE_API_URL . "?username={$username}&password={$password}&dstaddr={$dstaddr}&smbody={$smbody}&dlvtime={$dlvtime}"; //&vldtime={$vldtime}&response={$response}";

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $smsApiUrl);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            $strResponse = curl_exec($curl);
            curl_close($curl);

            $position = strpos($strResponse, 'msgid');
            if ($position != false) {
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
