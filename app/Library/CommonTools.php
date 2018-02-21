<?php

namespace App\Library;

use Illuminate\Http\Request;

define('AndroidApiKey', config('global.android_api_key'));

class CommonTools {
    /**
     * Facebook App ID
     * @var type
     */
    private static $FB_AppID = '875839542533172';

    /**
     * Facebook App Secret
     * @var type
     */
    private static $FB_AppSecret = 'e5def9309b844b03c4e7698be49d93d3';

    /**
    * 管理者md_id(推播，錯誤訊息)
    * @var type
    */
    public static $Managers_MdId = [];

    /**
     * 參數解碼，並轉為陣列格式
     * @param type $parameter base64_encode和urlencode後的參數
     * @return boolean
     */
    public static function decodeAndConvertToArray($parameter) {
        try {
            if (!isset($parameter) || mb_strlen($parameter) == 0) {
                return false;
            }
            $paraDecode = urldecode($parameter);
            $paraDecode = base64_decode($paraDecode);
            $paraArray = json_decode($paraDecode, true);

            return $paraArray;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 檢查參數值
     * @param type $paraArray 參數陣列
     * @return boolean 檢查結果
     */
    public static function checkParameter($paraArray) {
        try {
            if (!is_array($paraArray)) {
                return false;
            }
            if (!array_key_exists('mur', $paraArray) || !array_key_exists('modacc', $paraArray) || !array_key_exists('modvrf', $paraArray) || !array_key_exists('redirect_uri', $paraArray)) {
                return false;
            }
            //檢查 mur 是否存在
            $murRepo = new \App\Repositories\MobileUnitRecRepository();
            $murData = $murRepo->getDataByMurID($paraArray['mur']);
            if (!isset($murData) || count($murData) != 1) {
                return false;
            }
            // 檢查 modacc 是否存在
            $mapr_r = new \App\Repositories\ModuleAccPass_rRepository;
            $maprData = $mapr_r->getDataByAccount($paraArray['modacc']);
            if(count($maprData) == 0 || is_null($maprData)){
                return false;
            }
            if(count($maprData) == 1 ){
                $maprData = $maprData[0];
            }
            // 檢查 modvrf 是否正確
            $str = $maprData['mapr_moduleaccount'].$maprData['mapr_modulepassword'];
            $vry = hash('sha256',$str);
            if($vry != $paraArray['modvrf']){
                return false;
            }
            //檢查 redirect_uri 是否正確
            if ($paraArray['redirect_uri'] != $maprData['mapr_redirect_uri']) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 參數解碼並檢查值
     * @param type $parameter
     * @return boolean
     */
    public static function decodeAndCheckParameter($parameter) {
        try{
            //轉換格式，並檢查回傳是否為陣列格式
            if ((!$paraArray = CommonTools::decodeAndConvertToArray($parameter)) || !is_array($paraArray)) {
                return false;
            }

            //檢查陣列內的值是否符合條件
            if (!CommonTools::checkParameter($paraArray)) {
                return false;
            }

            return $paraArray;
        } catch (\Exception $e){
            CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 檢查值的格式是否正確
     * @param type $value 要檢查的值
     * @param type $maxLength 限制長度，若為「０」則不限制
     * @param type $canEmpty 可否〔不填值〕或〔空值〕
     * @param type $canSpace 可否包含〔空白〕
     * @return boolean 檢查結果
     */
    public static function checkValueFormat($value, $maxLength, $canEmpty, $canSpace) {
        try {
            if (mb_strlen($value) == 0 && $canempty) {
                return true;
            }
            if (mb_strlen($value) == 0) {
                return false;
            }

            if ($maxLength != 0 && mb_strlen($value) > $maxLength) {
                //長度太長
                return false;
            }
            if (!$canSpace && preg_match('/\s/', $value)) {
                //檢查是否可包含空白
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 取得隨機GUID字串, 依「$havedash」決定是否包含Dash
     * @param type $havedash 是否包含Dash
     * @return type GUID字串
     */
    public static function generateGUID($havedash = true) {

        if ($havedash) {
            $formatstring = '%04x%04x-%04x-%04x-%04x-%04x%04x%04x';
        } else {
            $formatstring = '%04x%04x%04x%04x%04x%04x%04x%04x';
        }

        return sprintf($formatstring,
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * 產生「密碼重置」用的Hash Code
     * @param type $rpv_serno 重設密碼驗證碼序號
     * @param type $rpv_hash 雜湊值
     * @return type
     */
    public static function encodeResetPwdHashParameter($rpv_serno, $rpv_hash) {
        return $rpv_hash . hash('md5', 'sunwai') . $rpv_serno;
    }

    /**
     * 分解「密碼重置」Hash Code成陣列格式
     * @param type $hashcode 「密碼重置」用的Hash Code
     * @return boolean
     */
    public static function decodeResetPwdHashParameter($hashcode) {
        try {
            $arr = explode(hash('md5', 'sunwai'), $hashcode);

            if (count($arr) !== 2) {
                return false;
            }
            $return['rpv_serno'] = $arr[1];
            $return['rpv_hash'] = $arr[0];
            return $return;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 在網址上加上「Query String」
     * @param type $uri 網址
     * @param type $queryString
     * @return string
     */
    public static function appendQueryStringInUri($uri, $queryString) {

        $parsedUrl = parse_url($uri);
        if (!isset($parsedUrl['path']) || $parsedUrl['path'] == null) {
            $uri .= '/';
        }
        $separator = (!isset($parsedUrl['query'])) ? '?' : '&';
        $uri .= $separator . $queryString;

        return $uri;
    }

    /**
     * 建立「ServiceAccessToken」、「MemberMobileLink」
     * @param type $md_id 會員代碼
     * @param type $mur_id 設備代碼
     * @return boolean
     */
    public static function createServiceAccessToken($md_id, $mur_id) {
        try {
            //資料庫 開始交易
            \Illuminate\Support\Facades\DB::beginTransaction();

            $satRepo = new \App\Repositories\ServiceAccessTokenRepository();
            $mmlRepo = new \App\Repositories\MemberMobileLinkRepository();
            //把同「md_id」、「mur_id」的SAT刪除
            if (!$satRepo->updateIsFlagToZeroByMdIDMurID($md_id, $mur_id)) {
                //資料庫 回復交易
                \Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            //把同「md_id」的MML刪除
            if (!$mmlRepo->updateIsFlagToZeroByMdID($md_id)) {
                //資料庫 回復交易
                \Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            //建立SAT
            $satData['sat_apptype'] = '0';
            $satData['md_id'] = $md_id;
            $satData['mur_id'] = $mur_id;

            $jwtSvc = new \App\Services\JWTService();
            $satData['sat_token'] = $jwtSvc->generateJWT(['md_id' => $md_id,'mur_id' => $mur_id]);
            //$satData['sat_token'] = CommonTools::generateGUID(false);
            $satData['sat_expiredate'] = \Carbon\Carbon::now()->addMonths(1);
            $sat_serno = $satRepo->createGetId($satData);
            if (!isset($sat_serno)) {
                //資料庫 回復交易
                //\Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            //建立MML
            $mmlData['mml_apptype'] = '0';
            $mmlData['md_id'] = $md_id;
            $mmlData['mur_id'] = $mur_id;
            $mml_serno = $mmlRepo->createGetId($mmlData);

            if (!isset($mml_serno)) {
                //資料庫 回復交易
                \Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            $satData = $satRepo->getData($sat_serno);
            if (!isset($satData) || count($satData) != 1) {
                //資料庫 回復交易
                \Illuminate\Support\Facades\DB::rollback();
                return false;
            }
            //資料庫 認可交易
            \Illuminate\Support\Facades\DB::commit();
            return $satData->sat_token;
        } catch (\Exception $ex) {
            //資料庫 回復交易
            \Illuminate\Support\Facades\DB::rollback();

            CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 產生亂數數字字串
     * @param type $length 字串長度
     * @return string
     */
    public static function generateRandomNumberString($length) {
        $characters = '0123456789';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

    /**
     * 建立「Exception」記錄
     * @param type $ex
     * @return boolean 執行結果
     */
    public static function writeErrorLogByException($ex) {
        try {
            $arraydata['log_type'] = '2';
            $arraydata['log_code'] = $ex->getCode();
            $arraydata['log_message'] = $ex->getMessage();
            $arraydata['log_previous'] = $ex->getPrevious();
            $arraydata['log_file'] = $ex->getFile();
            $arraydata['log_line'] = $ex->getLine();
            //$arraydata['log_trace'] = $ex->getTrace();
            $arraydata['log_traceasstring'] = $ex->getTraceAsString();

            $errRepo = new \App\Repositories\ErrorLogRepository();
            return $errRepo->create($arraydata);
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 建立「Message」記錄
     * @param type $message 訊息
     * @param type $code 代碼
     * @param type $file 檔案
     * @param type $line 行數
     * @return boolean 執行結果
     */
    public static function writeErrorLogByMessage($message, $code = null, $file = null, $line = null) {
        try {
            $arraydata['log_type'] = '1';
            $arraydata['log_message'] = $message;
            if (isset($code)) {
                $arraydata['log_code'] = $code;
            }
            if (isset($file)) {
                $arraydata['log_file'] = $file;
            }
            if (isset($line)) {
                $arraydata['log_line'] = $line;
            }

            $errRepo = new \App\Repositories\ErrorLogRepository();
            return $errRepo->create($arraydata);
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 建立會員帳號異動記錄
     * @param type $md_id 會員代碼
     * @param type $mur_id 設備代碼
     * @param type $operationType 異動類別 １：登入、２：註冊、３：變更密碼、
     * @param type $accountType 異動類別 １：isCar帳號、２：FaceBook、３：Google、４：WeChat、
     * @param type $sso_serno 第三方認證記錄編號
     * @param type $snc_serno 簡訊註冊代碼
     * @return boolean 執行結果
     */
    public static function writeAccountModifyRecode($md_id, $mur_id, $operationType, $accountType, $sso_serno = null, $snc_serno = null) {
        try {
            if (!isset($md_id) || !isset($mur_id) || !isset($operationType) || !isset($accountType)) {
                return false;
            }

            $arrayData['md_id'] = $md_id;
            $arrayData['uamr_operationtype'] = $operationType . $accountType;
            $arrayData['mur_id'] = $mur_id;
            if (isset($sso_serno)) {
                $arrayData['sso_serno'] = $sso_serno;
            }
            if (isset($snc_serno)) {
                $arrayData['snc_serno'] = $snc_serno;
            }

            $uamrRepo = new \App\Repositories\UserAccountModifyRecordRepository();
            if (!$uamrRepo->create($arrayData)) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }
/////阿志新增2017/05/23////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 檢查SAT的狀態
     * @param  [string] $moduleaccount [模組帳號]
     * @param  [string] $moduleverify  [模組驗證碼]
     * @return [boolean] true or false
     */
    public static function checkModuleAccount($moduleaccount,$moduleverify = null){
        $moduleaccpass = new \App\Repositories\ModuleAccPass_rRepository;
        try {
            $moduledata = $moduleaccpass->getDataByAccount($moduleaccount);
            if(is_null($moduledata) || count($moduledata) == 0){
                return false;
            }
            if(count($moduledata) != 0){
                $moduledata = $moduledata[0];
            }

            if(is_null($moduleverify)){
                return true;
            }
            $moduleverify = urldecode($moduleverify);
            $moduleverify = base64_decode($moduleverify);
            $moduleverify = explode("_",$moduleverify);
            if(count($moduleverify) != 2){
                return false;
            }
            $str = CommonTools::generateModuleVerify($moduledata['mapr_moduleaccount'],$moduledata['mapr_modulepassword'],$moduleverify[0]);
            if(!$str){
                return false;
            }
            if($str != $moduleverify[1]){
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 產生「模組驗證碼」
     * @param  [string]  $moduleaccount  [模組帳號]
     * @param  [string]  $modulepassword [模組密碼]
     * @param  [boolean] $sno      [是否有使用SALT]
     * @return [type]                 [description]
     */
    public static function generateModuleVerify($moduleaccount,$modulepassword,$sno){
        $str = "";
        $passwordsalt = new \App\Repositories\PasswordSalt_rRepository;
        try {
            $data = $passwordsalt->GetSaltBySerno($sno);
            if(count($data) != 0){
                $data = $data[0];
                $psr_salt = $data['psr_salt'];
                $str = $moduleaccount.$modulepassword.$psr_salt;
            }else{
                return false;
            }

            $str = hash('sha256',$str);
            if(mb_strlen($str) != 64){
                return false;
            }
            return $str;
        } catch (\Exception $ex) {
            CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 檢查SAT的狀態
     * @param [string] $sat [會員授權憑證]
     * @return [boolean] true or false
     */
    public static function CheckServiceAccessToken($sat){
        $jwt = new \App\Services\JWTService;
        $sat_r = new \App\Repositories\ServiceAccessTokenRepository;
        try {
            $data = $jwt->decodeToken($sat);
            if(!isset($data['md_id'])){
                return false;
            }
            $servicetokendata = $sat_r->getDataByMdIDMurID($data['md_id'],$data['mur_id']);
            if(is_null($servicetokendata) || count($servicetokendata) == 0){
                return false;
            }
            if(count($servicetokendata) != 0){
              $servicetokendata = $servicetokendata[0];
            }
            if($servicetokendata['sat_token'] != $sat){
                return false;
            }
            if($servicetokendata['sat_effective'] == 2 || $servicetokendata['sat_effective'] == 3){
                return false;
            }
            return $servicetokendata;
        } catch (\Exception $ex) {
            CommonTools::writeErrorLogByException($ex);
            return false;
        }

    }

    /**
     * 進行推播
     * @param type $md_id
     * @return boolean
     */
    public static function pushnotification($md_id,$message) {
        return true;
        // $sat_r = new \App\Repositories\ServiceAccessTokenRepository;
        // $mur_r = new \App\Repositories\MobileUnitRecRepository;
        // try {
        //     $satdata = $sat_r->getNewestDataByMdID($md_id);
        //     $murdata = $mur_r->GetDataByMUR_ID($satdata[0]['mur_id']);
        //     if ($murdata[0]['mur_systemtype'] == 0) {
        //       Commontools::Push_Notification_GCM($murdata[0]['mur_gcmid'], $message);
        //     }
        //     if ($murdata[0]['mur_systemtype'] == 1) {
        //         if (!Commontools::Push_Notification_APNS($murdata[0]['mur_gcmid'], $message)) {
        //            return false;
        //         }
        //     }
        //     return true;
        // } catch (\Exception $e) {
        //     CommonTools::writeErrorLogByException($e);
        //     return false;
        // }
    }

    /**
     * 檢查陣列中指定Key是否存在，並檢查Vaule格式是否正確
     * @param  [array]   $arrayData [資料陣列]
     * @param  [string]  $key       [要檢查的key]
     * @param  [int]     $maxLength [限制長度，若為0則不限制]
     * @param  [boolean] $canEmpty  [可否〔不填值〕或〔空值〕]
     * @param  [boolean] $canSpace  [可否包含〔空白〕]
     * @return [boolean]            [檢查結果]
     */
    public static function checkArrayValueFormat($arrayData,$key,$maxLength=0,$canEmpty=false,$canSpace=false){
        if(!is_array($arrayData)){
            return false;
        }
        if(array_key_exists($key,$arrayData) && is_null($arrayData[$key])){
            if(!$canEmpty){
                return false;
            }else{
                return true;
            }
        }
        if($maxLength != 0){
            if(strlen($arrayData[$key]) > $maxLength){
                return false;
            }
        }
        if(!$canSpace){
            if(preg_replace('/\s(?=)/', '', $arrayData[$key]) != $arrayData[$key]){
                return false;
            }
        }
        return true;
    }

/////阿志2017/08/10新增////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 同步親屬資料
     * @param  [string] $md_id [會員代號]
     */
    public static function synchronizeTempleRelative($md_id){
        $mb_r = new \App\Repositories\MbTplRelativeRepository;
        $md_r = new \App\Repositories\MemberDataRepository;
        try {
            // 抓取自己的會員資料
            if(!$memberdata = $md_r->getDataByMdId($md_id)){
                return false;
            }
            if(count($memberdata) >1 || count($memberdata) ==0){
                return false;
            }
            if(count($memberdata) ==1){
                $memberdata = $memberdata[0];
            }
            // 檢查此會員是否已經將自己新建為親屬資料
            if(!$data = $mb_r->getDataByMdIdTprTitle($md_id,99)){
                return false;
            }
            if(count($data) > 1){
                return false;
            }
            // 將自己的會員資料新增到親屬資料
            if(count($data) == 0){
                $arraydata['md_id'] = $memberdata['md_id'];
                $arraydata['tpr_title'] = 99;
                if(is_null($memberdata['md_lastname']) || is_null($memberdata['md_firstname'])){
                    $arraydata['tpr_name'] = "";
                }else{
                    $arraydata['tpr_name'] = $memberdata['md_lastname'].$memberdata['md_firstname'];
                }
                $arraydata['tpr_birthday'] = $memberdata['md_birthday'];
                $arraydata['tpr_birthdaytime'] = 0;
                if(is_null($memberdata['md_city'])){
                    $arraydata['tpr_country'] = "";
                }else{
                    $arraydata['tpr_country'] = $memberdata['md_city'];
                }
                if(is_null($memberdata['md_country'])){
                    $arraydata['tpr_area'] = "";
                }else{
                    $arraydata['tpr_area'] = $memberdata['md_country'];
                }
                if(is_null($memberdata['md_addr'])){
                    $arraydata['tpr_address'] = "";
                }else{
                    $arraydata['tpr_address'] = $memberdata['md_addr'];
                }
                if(!$result = $mb_r->createByMemberdata($arraydata)){
                    return false;
                }
            }
            // 將自己的會員資料同步到親屬資料
            if(count($data) == 1){
                $arraydata['md_id'] = $memberdata['md_id'];
                if(is_null($memberdata['md_lastname']) || is_null($memberdata['md_firstname'])){
                    $arraydata['tpr_name'] = "";
                }else{
                    $arraydata['tpr_name'] = $memberdata['md_lastname'].$memberdata['md_firstname'];
                }
                $arraydata['tpr_birthday'] = $memberdata['md_birthday'];
                if(is_null($memberdata['md_city'])){
                    $arraydata['tpr_country'] = "";
                }else{
                    $arraydata['tpr_country'] = $memberdata['md_city'];
                }
                if(is_null($memberdata['md_country'])){
                    $arraydata['tpr_area'] = "";
                }else{
                    $arraydata['tpr_area'] = $memberdata['md_country'];
                }
                if(is_null($memberdata['md_addr'])){
                    $arraydata['tpr_address'] = "";
                }else{
                    $arraydata['tpr_address'] = $memberdata['md_addr'];
                }
                if(!$result = $mb_r->updateByMemberdata($arraydata)){
                    return false;
                }
            }
            return true;
        } catch (Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

/////阿志2017/08/16新增////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 驗證用戶修改權限
     * @param [string] $md_id        [會員代碼]
     * @param [string] $moc_id       [車輛記錄編號]
     * @param [string] $messageCode  [錯誤代碼]
     */
    public static function CheckMemberOwnCar($md_id, $moc_id, &$messageCode) {
        $oc_r = new \App\Repositories\MemberOwnCarRepository;
        try {
            $querydata = $oc_r->QueryMemberCarDetails($md_id, $moc_id);
            if (is_null($querydata) || count($querydata) == 0 ) {
                $messageCode = '011300001';
                return false;
            }else if ( $querydata[0]['isflag'] == 0 ) {
                $messageCode = '011300002';
                return false;
            }else if (count($querydata) > 1 ) {
                //推波錯誤訊息給管理者，(要有md_id)
                $messageCode = '999999986';
                $message = "MemberCar::CheckMemberOwnCar，Carerrormessage:$messageCode，md_id:$md_id";
                //Commontools::PushNotificationToManagers($message);
                return false;
            }
            return $querydata;
        } catch(\Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return null;
        }
    }

    /**
     * 抓取會員車輛資訊
     * @param [string] $md_id        [會員代碼]
     * @param [string] $messageCode  [錯誤代碼]
     */
    public static function QueryMemberOwnCars($md_id, &$messageCode) {
        $oc_r = new \App\Repositories\MemberOwnCarRepository;
        try {
           $querydata = $oc_r->QueryMemberCar($md_id, null);
           if (is_null($querydata) || count($querydata) == 0 ) {
             $messageCode = '000000003';
             return false;
           }
           return $querydata;
        } catch(\Exception $e) {
          CommonTools::writeErrorLogByException($e);
          return false;
        }
    }

/////阿志2017/08/23新增////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 將md_clienttype從代碼轉成文字
     * @param  [string] $clienttype [用戶類別]
     */
    public static function clienttype_numToWord($clienttype){
        try {
            if($clienttype == 0){
                return '一般用戶';
            }
            if($clienttype == 1){
                return '特約商';
            }
            if($clienttype == 2){
                return '二手車商';
            }
            if($clienttype == 3){
                return '品牌商';
            }
            if($clienttype == 99){
                return '宮廟管理員';
            }
            if($clienttype == 100){
                return '業務';
            }
            return '類別未歸類';
        } catch (Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

/////阿志2017/10/30新增////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 判斷字串裡是否有包含特殊符號
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function strIsSpecial($data){
        $l2 = "&,',\",<,>,!,%,#,$,@,=,?,/,(,),[,],{,},.,+,*,_";
        $I2 = explode(',', $l2);
        $I2[] = ",";

        foreach ($I2 as $v) {
           if (strpos($data, $v) !== false) {
               return false;
           }
        }
        return true;
    }







/////阿駿的commonTools/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 將Json格式的字串 轉換為 PHP Array
     * @param type $inputstring WebAPI接收到的「JSON」格式字串
     * @return type PHP陣列
     */
    public static function ConvertStringToArray($inputstring) {
        try {
            $input = str_replace("'", '"', $inputstring);

            if (is_array($input)) {
                $inputjson = json_decode($input[0], true);
            } else {
                $inputjson = json_decode($input, true);
            }
            return $inputjson;
        } catch (\Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return null;
        }
    }

    /**
     * 建立回傳訊息
     * @param type $ProcessCode 訊息代碼
     * @param type $ArrayData 回傳資料
     * @return type Array
     */
    public static function ResultProcess($ProcessCode, $ArrayData) {
        $processstatus = new \App\Repositories\ProcessStatusRepository;
        $value = $processstatus->getDataFirstByPK($ProcessCode);
        //$value = IsCarProcessStatus::find($ProcessCode);
        $content01 = "";
        $content02 = "";
        $content03 = "";

        if ($value != null && $value->count() != 0) {
            $content01 = $value->content01;
            $content02 = $value->content02;
            $content03 = $value->content03;
        }

        //$data = array("message_no" => $ProcessCode, "content01" => rawurlencode($content01), "content02" => rawurlencode($content02), "content03" => rawurlencode($content03));
        $data = array("message_no" => $ProcessCode, "content01" => ($content01), "content02" => ($content02), "content03" => ($content03));
        if ($ArrayData != null) {
            $data = array_merge($data, $ArrayData);
        }

        //$result = urlencode($data);
        //$result = array_map('urlencode', $data);

        return CommonTools::UrlEncodeArray($data);
        //return $data;
        //return (json_encode($data));
    }

    /**
     * 對〔$data〕陣列中所有值作「rawurlencode」
     * @param type $data 陣列值
     * @return type 「rawurlencode」後的資料
     */
    private static function UrlEncodeArray($data) {

        //若不為〔陣列〕則直接作「rawurlencode」後回傳
        if (!is_array($data)) {
            //return $data;
            return rawurlencode($data);
        }
        //迴圈：「rawurlencode」所有$value
        foreach ($data as $name => $value) {
            //遞迴：呼叫原本 Function 以跑遍所有「陣列」中的「陣列」
            $data[$name] = CommonTools::UrlEncodeArray($value);
        }

        return $data;
    }

    /**
     * 建立「WebAPI 執行記錄」到資料庫中
     * @param type $functionname 執行的功能名稱
     * @param type $input 接收到的值
     * @param type $result 回傳的值
     * @param type $messagecode 訊息代碼
     * @return boolean 執行結果
     */
    public static function WriteExecuteLog($functionname, $input, $result, $messagecode) {
        $jsoniorec = new \App\Repositories\JsonIORecRepository;
        $arraydata = array("jio_receive" => json_encode($input), "jio_return" => $result, "jio_wcffunction" => $functionname, "ps_id" => $messagecode);
        if ($jsoniorec->InsertData($arraydata)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 檢查「$keyname」是否存在於「$arraydata」中，並檢查其他條件
     * @param type $arraydata   要檢查的陣列
     * @param type $keyname    要檢查的參數名稱
     * @param type $maxlength 最大長度限制，若輸入「0」則為不限制
     * @param type $canempty 是否可為「空值」
     * @param type $canspace 是否可包含「空白」
     * @return boolean 是否符合條件
     */
    public static function CheckRequestArrayValue($arraydata, $keyname, $maxlength, $canempty, $canspace) {
        try {

            if (array_key_exists($keyname, $arraydata)) {
                $QQ = $arraydata[$keyname];
                if (is_array($QQ)) {
                    $QQ = implode(" ", $QQ);
                }
            } else {
                $QQ = null;
            }

            if ((!array_key_exists($keyname, $arraydata) || ( mb_strlen($QQ) == 0)) && $canempty) {
                return true;
            }
            if (!array_key_exists($keyname, $arraydata) || ( mb_strlen($QQ) == 0)) {
                //不存在
                return false;
            }

            if ($maxlength != 0 && mb_strlen($QQ) > $maxlength) {
                //長度太長
                return false;
            }
            if (!$canspace) {
                //檢查是否可包含空白
                if (preg_match('/\s/', $QQ) === 1) {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 依AccessToken 取得FaceBook Account ID
     * @param type $accessToken
     * @return type
     */
    public static function GetFacebookAccountID($accessToken) {
        try {
            //初始化 Facebook 元件
            $facebook = CommonTools::GetFacebook();
            if ($facebook == null) {
                return null;
            }
            //使用「$accountToken」取得使用者資料
            $response = $facebook->get('/me?fields=id,name', $accessToken);

            $user = $response->getGraphUser();

            return $user['id'];
        } catch (\Exception $e) {
            return null;
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            return null;
        }
    }

    /**
     * 取得並初始化FaceBook 元件
     * @return \Facebook\Facebook Facebook元件
     */
    private static function GetFacebook() {
        try {
            $facebook = new \Facebook\Facebook([ 'app_id' => CommonTools::$FB_AppID, 'app_secret' => CommonTools::$FB_AppSecret,]);

            return $facebook;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 驗證「Token」和「UserDeviceCode」，若正確回傳「Token」對應的「MD_ID會員代碼」
     * @param type $token
     * @param type $devicecode
     * @param type $md_id
     * @param string $messageCode
     * @return boolean
     */
    public static function CheckAccessTokenDeviceCode($token, $devicecode, &$md_id, &$messageCode) {

        //return   Commontools::CheckServiceAccessTokenOlder($token, $md_id, $messageCode);
        if (!CommonTools::CheckServiceAccessTokenOlderOlder($token, $md_id, $messageCode)) {
            return false;
        }

        if (!CommonTools::CheckUserDeviceCode($token, $devicecode)) {
            $messageCode = '999999994';
            return false;
        }

        return true;
    }

    /**
     * 檢查「使用者登入憑證記錄﹙IsCarServiceAccessToken﹚」
     * @param type $token 使用者登入憑證
     * @param type $md_id 回傳「使用者代碼」
     * @param type $messageCode 回傳「訊息代碼」
     * @return boolean 執行是否成功
     */
    public static function CheckServiceAccessTokenOlder($token, &$md_id, &$messageCode) {
        $accesstoken = new \App\Repositories\ServiceAccessTokenRepository;
        try {
            if (!CommonTools::CheckCountServiceToken($token, $querydata, $messageCode)) {
                return false;
            }
            $datenow = new \DateTime('now');
            $expiredate = new \DateTime($querydata[0]['sat_expiredate']);

            if ($datenow > $expiredate) {
                //已過期
                $isexpired = false;
                //伺服端連線憑證逾期失效，請重新登入
                $messageCode = "999999993";
                //更新，已過期
                $querydata[0]['sat_effective'] = "2";
            } else {
                //未過期
                $isexpired = true;
                //更新，延長期限
                $date = new \DateTime('now');
                $querydata[0]['sat_expiredate'] = $date->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s');
                $md_id = $querydata[0]['md_id'];
            }
            if (!CommonTools::CheckCountServiceToken($token, $checkquery, $messageCode)) {
                return false;
            }
            if (!$accesstoken->UpdateData($querydata[0])) {
                $messageCode = "999999999";
                return false;
            }
            return $isexpired;
        } catch (\Exception $e) {
            $messageCode = "9999999999";
            return false;
        }
    }

    /**
     * 檢查 使用者 辨識碼
     * @param type $token
     * @param type $devicecode
     * @return boolean
     * 92ae013293e04ad6a8939867b890aee4*/
    public static function CheckUserDeviceCode($token, $devicecode) {
        $accesstoken = new \App\Repositories\ServiceAccessTokenRepository;
        try {
            $querydata = $accesstoken->GetDataBySat_Token($token);

            if ($querydata == null || count($querydata) == 0) {
                return false;
            }

            $origenalstring = $querydata[0]['md_id'] . $querydata[0]['mur_id'];

            $hashstring = base64_encode(hash('sha256', $origenalstring, True));
            //檢查 輸入「使用者辨識碼」 與 運算出的值是否相符
            if ($hashstring != $devicecode) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function CheckCountServiceToken($token, &$querydata, &$messageCode) {
        $accesstoken = new \App\Repositories\ServiceAccessTokenRepository;
        $querydata = $accesstoken->GetDataBySat_Token($token);
        if ($querydata == null || count($querydata) == 0) {
            //無效的伺服端連線憑證，請重新登入
            $messageCode = "999999992";
            return false;
        }
        return true;
    }

    public static function WriteExecuteLogGetId($functionname, $input, $result, $messagecode, &$jio_id) {
        $jsoniorec = new \App\Repositories\JsonIORecRepository;
        $arraydata = array("jio_receive" => json_encode($input), "jio_return" => $result, "jio_wcffunction" => $functionname, "ps_id" => $messagecode);
        if ($jsoniorec->InsertDataGetId($arraydata, $jio_id)) {
            return true;
        } else {
            return false;
        }
        return true;
    }

    /**
     * 進行推播給管理者
     * @param type $md_id
     * @return boolean
     */
   public static function PushNotificationToManagers($message) {
    $memberdata = new \App\Repositories\MemberDataRepository;
     try {
           foreach (CommonTools::$Managers_MdId as $value) {
              $queryData =  $memberdata->GetData_ByMDID($value);
              if ($queryData[0]['mur_systemtype'] == 0) {
                  CommonTools::Push_Notification_GCM($queryData[0]['mur_gcmid'], $message);
              }
              if ($queryData[0]['mur_systemtype'] == 1) {
                  if (!CommonTools::Push_Notification_APNS($queryData[0]['mur_gcmid'], $message)) {
                  return false;
                  }
              }
           }
          return true;
     } catch (\Exception $e) {
          CommonTools::writeErrorLogByException($e);
          return false;
     }
   }

     /**
     * 使用Apple Push Notification Service 推播IOS
     * @param type $deviceToken 推播id
     * @param type $arraydata 推播內容
     */
    public static function Push_Notification_APNS($deviceToken, $arraydata) {
        try {
            $passphrase = '1qaz@WSX#EDC' ;
            $ctx = stream_context_create();
            // 正式為 /ios-apns/Apns-Production.pem  測試為 /ios-apns/Apns-Development.pem
            stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__.'/ios-apns/Apns-Development.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            // 正式為 ssl://gateway.push.apple.com:2195  測試為 ssl://gateway.sandbox.push.apple.com:2195
            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,
                                        $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$fp) {
              return false;
            }
            $body['aps'] = array('alert' => array('title' => $arraydata['title'],'body' => $arraydata['message']),'sound' => $arraydata['sound'],$arraydata['iscar_push']);

            $payload = json_encode($body);
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));
            if (!$result) {
               throw new Exception("Message not delivered");
            }
            fclose($fp);
            return true;
        } catch (Exception $e) {
           CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 使用Google API 推播Android
     * @param type $ids 推播id
     * @param type $arraydata 推播內容
     */
    public static function Push_Notification_GCM ($target,$arraydata) {
        try {
            //FCM API end-point
            // $url = 'https://fcm.googleapis.com/fcm/send';
            //GCM API end-point
            $url = 'https://gcm-http.googleapis.com/gcm/send';
            //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key = AndroidApiKey;
            $fields = array();
            // FCM
            // $fields['notification'] = $arraydata;
            //GCM
            $fields['data'] = $arraydata;


            if(is_array($target)){
            $fields['registration_ids'] = $target;
            }else{
            $fields['to'] = $target;
            }
            //header with content_type api key
            $headers = array(
            'Content-Type:application/json',
                'Authorization:key='.$server_key
            );
            //CURL request to route notification to FCM connection server (provided by Google)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
              die('Oops! FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            return true;
        } catch (Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return false;
        }

    }

    /**
     * 檢查「keyname」是否存在於「arraydata」中，並檢查是否有填值
     * @param type $arraydata
     * @param type $keyname
     * @return boolean
     */
    public static function CheckArrayValue($arraydata, $keyname) {
        try {
            /*
              if(!array_key_exists($keyname, $arraydata)) {
              return 1;
              }
              if( ($arraydata[$keyname] === null)){
              return 2;
              }
              if(strlen($arraydata[$keyname]) === 0){
              return 3;
              }
             */
            if (
                    !array_key_exists($keyname, $arraydata) || is_null($arraydata[$keyname]) || mb_strlen($arraydata[$keyname]) == 0
            ) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 取得隨機GUID字串不包含Dash
     * @return type GUID字串﹙不包含Dash﹚
     */
    public static function NewGUIDWithoutDash() {
        return CommonTools::CreateGUID(false);
    }

    /**
     * 取得隨機GUID字串, 依「$havedash」決定是否包含Dash
     * @param type $havedash 是否包含Dash
     * @return type GUID字串
     */
    private static function CreateGUID($havedash) {

        if ($havedash) {
            $formatstring = '%04x%04x-%04x-%04x-%04x-%04x%04x%04x';
        } else {
            $formatstring = '%04x%04x%04x%04x%04x%04x%04x%04x';
        }

        return sprintf($formatstring,
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /** (點數專用)
     * 異動庫存資料並建立庫存異動檔
     * @param type $pmr_modify 異動型態。1：IAP/IAB購買、2：消費點數購買權限、3：事件執行紅利給與、4：使用活動券給予廠商紅利、5：索取活動券扣除廠商紅利
     * @param type $pmr_type 異動點數類別。 0：代幣點數 1：APP禮點 2：活動券紅利
     * @param type $md_id 會員代碼
     * @param type $relation_id 關聯代碼。1：「IAP/IAB購買代幣」填〔IsCarIAPRecord_m.Irm_serno〕、2.「權限購買」時填〔IsCarCouponmenugrade_ex.cmge_serno〕、3.「紅利增加」填〔iscarusersharelog.sel_serno〕或〔iscarcommentboard.cb_serno〕或尚未定義之可給與紅利事件執行記錄編號
     * @param type $modify 異動的點數
     * @param type $before 異動前的點數
     * @param type $afterstock 回傳 異動後的點數
     * @return boolean 執行結果
     */
    public static function UpdateStockAndModifyRecord($pmr_modify, $pmr_type, $md_id, $relation_id, $modify, $before) {

      try {
        $modifystatus = '';
        if ($pmr_modify == '1')         {//1：IAP/IAB購買
            $modifystatus = 'add';
            $after = $before + $modify;
        } else if ($pmr_modify == '2')  {//2：事件執行紅利給與
            $modifystatus = 'add';
            $after = $before + $modify;
        } else if ($pmr_modify == '3')  {//3：消費點數購買權限
            $modifystatus = 'use';
            $after = $before - $modify;
        } else if ($pmr_modify == '4')  {//4：使用活動券給予廠商紅利
            $modifystatus = 'use';
            $after = $before - $modify;
        } else if ($pmr_modify == '5')  {//5：索取活動券扣除廠商紅利
            $modifystatus = 'use';
            $after = $before - $modify;
        }  else if ($pmr_modify == '6') {//6：完成活動券評論給與iscar禮點
            $modifystatus = 'add';
            $after = $before + $modify;
        } else if ($pmr_modify == '7')  {//7：完成合作社活動用後評論給與iscar禮點
            $modifystatus = 'add';
            $after = $before + $modify;
        } else if ($pmr_modify == '8')  {//8：使用儲值點數刊登售車資訊
            $modifystatus = 'use';
            $after = $before - $modify;
        } else if ($pmr_modify == '9')  {//9：寶箱模組消費異動
            $modifystatus = 'use';
            $after = $before - $modify;
        } else if ($pmr_modify == '10')  {
            $modifystatus = 'add';
            $after = $before + $modify;
        }  else if ($pmr_modify == '11') {// 11 : 特約商推播
            $modifystatus = 'use';
            $after = $before - $modify;
        } else {
            return false;
        }
        //建立庫存異動資料
        $prepaidmodify = [
                             'pmr_date'         => date('Y/m/d H:i:s')
                           , 'pmr_type'         => $pmr_type
                           , 'pmr_modify'       => $pmr_modify
                           , 'pmr_relation_id'  => $relation_id
                           , 'md_id'            => $md_id
                           , 'pmr_point_before' => $before
                           , 'pmr_point'        => $modify
                           , 'pmr_point_after'  => $after
                        ];
        $pmr_serno = '';
        if (!\App\models\IsCarPrepaidModifyRecord::InsertData($prepaidmodify, $pmr_serno)) {
            return false;
        }
        //異動點數庫存
        $afterstock = 0;
        if (!\App\models\IsCarCoinStock::UpdateStock($md_id, $pmr_type, $modifystatus, $modify, $afterstock)) {
           return false;
        }
        return true;

      } catch(\Exception $e){
        CommonTools::writeErrorLogByException($e);
        return false;
      }
    }

}
