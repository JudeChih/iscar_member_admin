<?php

namespace App\Repositories;

use App\Models\isCarMemberData;
use DB;
use Session;

class MemberDataRepository {

    /**
     * 抓取時間段新增的會員數量
     * @param  [type] $first [起始時間]
     * @param  [type] $last  [終止時間]
     */
    public function getDataByTimes($first,$last){
        try {
            $string = isCarMemberData::where('create_date','>',$first)->where('create_date','<',$last)->get();;
            return $string;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 取得十筆資料
     * @return type
     */
    public function getTenData($query_name,$query_status,$query_day,$isflag,$sort,$order,$search_type,$client_type) {
        try {
            if(is_null($sort) && is_null($order)){
                $sort = 'md_cname';
                $order = 'DESC';
            }
            if(is_null($query_name)){
                $query_name = '';
            }
            if(is_null($search_type)){
                $search_type = 'md_cname';
            }
            $adm_string = isCarMemberData::where($search_type,'like', '%'.$query_name.'%');
            if(!is_null($isflag)){
                $adm_string->where('isflag',$isflag);
            }
            if(!is_null($query_status) && !is_null($query_day)){
                $now = \Carbon\Carbon::now();
                $now = $now->subDays($query_day);
                $now = $now->toDateTimeString();
                if($query_status == 1){
                    $adm_string->where('md_pwd_changedate','<',$now);
                }elseif($query_status == 2){
                    $adm_string->where('md_last_login','<',$now);
                }
            }
            if(!is_null($client_type)){
                $adm_string->where('md_clienttype',$client_type);
            }

            return $adm_string->orderBy($sort,$order)->paginate(10);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 透過登入類型抓資料
     * @param string $logintype 登入類型
     */
    public function getDataByMdLoginType($logintype){
        try {
            if(is_null($logintype)){
                return isCarMemberData::get();
            }
            return isCarMemberData::where('md_logintype',$logintype)->get();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 藉由md_id取得會員資料
     * @param  [type] $md_id [會員代號]
     */
    public function getDataByMdId($md_id){
        try {
            if ($md_id == null || strlen($md_id) == 0) {
                return false;
            }
            return isCarMemberData::where('md_id',$md_id)->where('isflag',1)->get();
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 藉由md_mobile、md_contactmail抓取會員資料
     * @param  [type] $md_mobile      [會員電話]
     * @param  [type] $md_contactmail [會員信箱]
     */
    public function getDataByMobileContactmail($md_mobile,$md_contactmail){
        try {
            if ($md_mobile == null || strlen($md_mobile) == 0 || $md_contactmail == null || strlen($md_contactmail) == 0) {
                return false;
            }
            return isCarMemberData::where('md_mobile', '=', $md_mobile)->where('md_contactmail','=',$md_contactmail)->get();

        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 新增資料
     * @param   $arraydata
     * @return  Boolean
     */
    public function InsertData($arraydata, &$md_id) {

        //取得新的代碼
        $md_id = \App\Library\CommonTools::NewGUIDWithoutDash();

        $savedata['md_id'] = $md_id;

        //$inputdata = json_encode( $inputdata );

        if (
                !\App\Library\CommonTools::CheckArrayValue($arraydata, "md_logintype") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_onlinestatus")//1
                || !\App\Library\CommonTools::CheckArrayValue($arraydata, "md_rightstatus")//0
                || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accountid") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accesstoken") || !\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_longtermtoken")
        ) {
            return false;
        }


        $savedata['md_logintype'] = $arraydata['md_logintype'];
        $savedata['ssd_onlinestatus'] = $arraydata['ssd_onlinestatus'];
        $savedata['md_rightstatus'] = $arraydata['md_rightstatus'];
        $savedata['ssd_accountid'] = $arraydata['ssd_accountid'];
        $savedata['ssd_accesstoken'] = $arraydata['ssd_accesstoken'];
        $savedata['ssd_longtermtoken'] = $arraydata['ssd_longtermtoken'];


        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accountmail")) {
            $savedata['ssd_accountmail'] = $arraydata['ssd_accountmail'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accountname")) {
            $savedata['ssd_accountname'] = $arraydata['ssd_accountname'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fbfirstname")) {
            $savedata['ssd_fbfirstname'] = $arraydata['ssd_fbfirstname'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fblastname")) {
            $savedata['ssd_fblastname'] = $arraydata['ssd_fblastname'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fblocallanguage")) {
            $savedata['ssd_fblocallanguage'] = $arraydata['ssd_fblocallanguage'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fbgender")) {
            $savedata['ssd_fbgender'] = $arraydata['ssd_fbgender'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_birthday")) {
            $savedata['ssd_birthday'] = $arraydata['ssd_birthday'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_timezone")) {
            $savedata['ssd_timezone'] = $arraydata['ssd_timezone'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_picturepath")) {
            $savedata['ssd_picturepath'] = $arraydata['ssd_picturepath'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "rl_sn")) {
            $savedata['rl_sn'] = $arraydata['rl_sn'];
        } else {
            $savedata['rl_sn'] = "1";
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_cname")) {
            $savedata['md_cname'] = $arraydata['md_cname'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_ename")) {
            $savedata['md_ename'] = $arraydata['md_ename'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_tel")) {
            $savedata['md_tel'] = $arraydata['md_tel'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_mobile")) {
            $savedata['md_mobile'] = $arraydata['md_mobile'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_addr")) {
            $savedata['md_addr'] = $arraydata['md_addr'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_contactmail")) {
            $savedata['md_contactmail'] = $arraydata['md_contactmail'];
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_first_login")) {
            $savedata['md_first_login'] = $arraydata['md_first_login'];
        } else {
            $savedata['md_first_login'] = date('Y-m-d H:i:s');
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_last_login")) {
            $savedata['md_last_login'] = $arraydata['md_last_login'];
        } else {
            $savedata['md_last_login'] = date('Y-m-d H:i:s');
        }


        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = $arraydata['isflag'];
        } else {
            $savedata['isflag'] = '1';
        }

        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = $arraydata['create_user'];
        } else {
            $savedata['create_user'] = 'webapi';
        }
        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        } else {
            $savedata['last_update_user'] = 'webapi';
        }
        if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_clubjoinstatus")) {
            $savedata['md_clubjoinstatus'] = $arraydata['md_clubjoinstatus'];
        }

        if (isCarMemberData::insert($savedata)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改資料
     * @param   $mur_id
     * @param   $arraydata
     * @return  Boolean
     */
    public function UpdateData(array $arraydata) {

        try {
            if (!\App\Library\CommonTools::CheckArrayValue($arraydata, "md_id")) {
                return false;
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_logintype")) {
                $savedata['md_logintype'] = $arraydata['md_logintype'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_onlinestatus")) {
                $savedata['ssd_onlinestatus'] = $arraydata['ssd_onlinestatus'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_rightstatus")) {
                $savedata['md_rightstatus'] = $arraydata['md_rightstatus'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accountid")) {
                $savedata['ssd_accountid'] = $arraydata['ssd_accountid'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accesstoken")) {
                $savedata['ssd_accesstoken'] = $arraydata['ssd_accesstoken'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_longtermtoken")) {
                $savedata['ssd_longtermtoken'] = $arraydata['ssd_longtermtoken'];
            }


            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accountmail")) {
                $savedata['ssd_accountmail'] = $arraydata['ssd_accountmail'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_accountname")) {
                $savedata['ssd_accountname'] = $arraydata['ssd_accountname'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fbfirstname")) {
                $savedata['ssd_fbfirstname'] = $arraydata['ssd_fbfirstname'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fblastname")) {
                $savedata['ssd_fblastname'] = $arraydata['ssd_fblastname'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fblocallanguage")) {
                $savedata['ssd_fblocallanguage'] = $arraydata['ssd_fblocallanguage'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_fbgender")) {
                $savedata['ssd_fbgender'] = $arraydata['ssd_fbgender'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_birthday")) {
                $savedata['ssd_birthday'] = $arraydata['ssd_birthday'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_timezone")) {
                $savedata['ssd_timezone'] = $arraydata['ssd_timezone'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "ssd_picturepath")) {
                $savedata['ssd_picturepath'] = $arraydata['ssd_picturepath'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "rl_sn")) {
                $savedata['rl_sn'] = $arraydata['rl_sn'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_cname")) {
                $savedata['md_cname'] = $arraydata['md_cname'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_ename")) {
                $savedata['md_ename'] = $arraydata['md_ename'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_tel")) {
                $savedata['md_tel'] = $arraydata['md_tel'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_mobile")) {
                $savedata['md_mobile'] = $arraydata['md_mobile'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_addr")) {
                $savedata['md_addr'] = $arraydata['md_addr'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_contactmail")) {
                $savedata['md_contactmail'] = $arraydata['md_contactmail'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_first_login")) {
                $savedata['md_first_login'] = $arraydata['md_first_login'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_last_login")) {
                $savedata['md_last_login'] = $arraydata['md_last_login'];
            }

            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_clienttype")) {
                $savedata['md_clienttype'] = $arraydata['md_clienttype'];
            }
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_clubjoinstatus")) {
                $savedata['md_clubjoinstatus'] = $arraydata['md_clubjoinstatus'];
            }

            // 修改密碼
            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "md_password")) {
                $savedata['md_password'] = $arraydata['md_password'];
            }


            if (\App\Library\CommonTools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');
            isCarMemberData::where('md_id', '=', $arraydata['md_id'])
                           ->update($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 更新 用戶端運行類別
     * @param type $md_id
     * @param type $md_clienttype
     * @return boolean
     */
    public function UpdateData_ClientType($md_id, $md_clienttype) {
        try {
            if ($md_id == null || strlen($md_id) == 0 || $md_clienttype == null || strlen($md_clienttype) == 0) {
                return null;
            }
            isCarMemberData::where('md_id', '=', $md_id)
                    ->update(array('md_clienttype' => $md_clienttype));

            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     *
     * @param type $md_id
     * @return boolean
     */
    public function CreateOriginalMember(&$md_id) {

        try {
            //取得新的代碼
            $md_id = \App\Library\CommonTools::NewGUIDWithoutDash();
            $qqq = $md_id;
            $savedata['md_id'] = $md_id;
            $savedata['md_logintype'] = '2';
            $savedata['md_cname'] = mb_substr($qqq, 0, 8, 'utf8') . '@iscar';

            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            isCarMemberData::insert($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

     /**
     *
     * @param type $md_id
     * @return boolean
     */
    public function CreateMemberResgister(&$md_id) {

        try {
            //取得新的代碼
            $md_id = \App\Library\CommonTools::NewGUIDWithoutDash();
            $qqq = $md_id;
            $savedata['md_id'] = $md_id;
            $savedata['md_logintype'] = '2';
            $savedata['md_cname'] = mb_substr($qqq, 0, 8, 'utf8') . '@iscar';

            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            isCarMemberData::insert($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 取得資料，依「sso_acountid」取得
     * @param  type $sso_acountid
     */
    public function getDataBySso_AcountID($sso_accountid){
        return isCarMemberData::where('isflag',1)->where('sso_accountid',$sso_accountid)->get();
    }


    /**
     * 取得資料，依「MD_ID」取得
     * @param type $md_id
     * @return type
     */
    public function GetMemberData($md_id) {
        if ($md_id == null || strlen($md_id) == 0) {
            return null;
        }

        $results = isCarMemberData::where('isflag', '1')
                ->where('md_id', $md_id)
                ->get();
        return $results;
    }

    /**
     * 取得資料，依「MD_ID」取得
     * @param  type $md_id
     */
    public function GetMemberLevelData($md_id){
        return isCarMemberData::join('icr_memberclublevel_set','iscarmemberdata.mcls_serno','icr_memberclublevel_set.mcls_serno')->where('iscarmemberdata.md_id',$md_id)->where('iscarmemberdata.isflag',1)->get();
    }

    /**
     * 依「$accountid」取得資料
     * @param type $accountid
     * @return type
     */
    public function GetDataByAccountID($accountid) {
        if ($accountid == null || strlen($accountid) == 0) {
            return null;
        }

        $results = isCarMemberData::where('isflag', '1')
                ->where('ssd_accountid', $accountid)
                ->get()
                ->toArray();

        return $results;
    }

    /**
     * 透過md_mobile抓取會員資料
     * @param  [string] $md_mobile [手機號碼]
     */
    public function getDataByMd_Mobile($md_mobile){
        try {
            return isCarMemberData::where('md_mobile', '=', $md_mobile)
                            ->where('isflag', '=', '1')
                            ->get();
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
        }
    }

    /**
     * 依「$mur_id」取得資料
     * @param type $mur_id
     * @return type
     */
    public function GetDataByMUR_ID($mur_id) {
        if (is_null($mur_id) || strlen($mur_id) == 0) {
            return null;
        }

        $results = isCarMemberData::leftJoin('iscarmembermobilelink', 'iscarmemberdata.md_id', '=', 'iscarmembermobilelink.md_id')
                ->where('iscarmembermobilelink.isflag', '=', '1')
                ->where('iscarmemberdata.isflag', '1')
                ->where('iscarmembermobilelink.mur_id', '=', $mur_id)
                ->where('iscarmemberdata.md_logintype', '2')
                ->select('iscarmemberdata.md_id'
                        , 'iscarmemberdata.md_logintype'
                        , 'iscarmemberdata.ssd_onlinestatus'
                        , 'iscarmemberdata.md_rightstatus'
                        , 'iscarmemberdata.ssd_accountid'
                        , 'iscarmemberdata.ssd_accountmail'
                        , 'iscarmemberdata.ssd_accountname'
                        , 'iscarmemberdata.ssd_fbfirstname'
                        , 'iscarmemberdata.ssd_fblastname'
                        , 'iscarmemberdata.ssd_fblocallanguage'
                        , 'iscarmemberdata.ssd_fbgender'
                        , 'iscarmemberdata.ssd_birthday'
                        , 'iscarmemberdata.ssd_timezone'
                        , 'iscarmemberdata.ssd_picturepath'
                        , 'iscarmemberdata.ssd_accesstoken'
                        , 'iscarmemberdata.ssd_longtermtoken'
                        , 'iscarmemberdata.rl_sn'
                        , 'iscarmemberdata.md_cname'
                        , 'iscarmemberdata.md_ename'
                        , 'iscarmemberdata.md_tel'
                        , 'iscarmemberdata.md_mobile'
                        , 'iscarmemberdata.md_addr'
                        , 'iscarmemberdata.md_contactmail'
                        , 'iscarmemberdata.md_first_login'
                        , 'iscarmemberdata.md_last_login'
                        , 'iscarmemberdata.isflag'
                        , 'iscarmemberdata.create_user'
                        , 'iscarmemberdata.create_date'
                        , 'iscarmemberdata.last_update_user'
                        , 'iscarmemberdata.last_update_date')
                ->get()
                ->toArray();

        return $results;
    }

    public function GetData_ByMDID($md_id) {
      try {
           if (is_null($md_id) || strlen($md_id) == 0) {
            return null;
          }
            $query  = isCarMemberData::where('iscarmemberdata.isflag','=', '1')
                ->where('iscarmemberdata.md_id', '=', $md_id)
                ->leftJoin('iscarserviceaccesstoken','iscarmemberdata.md_id','=','iscarserviceaccesstoken.md_id')
                ->leftJoin('iscarmobileunitrec','iscarserviceaccesstoken.mur_id','=','iscarmobileunitrec.mur_id')
                ->orderBy('iscarserviceaccesstoken.last_update_date', 'desc');

            $results = $query->select('iscarmobileunitrec.mur_gcmid'
                                  ,'iscarmobileunitrec.mur_systemtype'
                                 )->get()->toArray();
        return $results;

      } catch (Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

     public function QueryMemberClubJoinSataus($md_id) {
        try {
          $query = isCarMemberData::where('iscarmemberdata.md_id','=',$md_id)
                            ->leftJoin('icr_clubmemberrecord', function($leftJoin)
                              {
                                 $leftJoin->on('icr_clubmemberrecord.md_id','=','iscarmemberdata.md_id')
                                           ->where('icr_clubmemberrecord.isflag', '=','1')
                                           ->where('icr_clubmemberrecord.cmr_joinstatus','=','1');
                              })
                              ->leftJoin('icr_carclubdata', function($leftJoin)
                              {
                                 $leftJoin->on('icr_carclubdata.ccd_id','=','icr_clubmemberrecord.ccd_id')
                                           ->where('icr_carclubdata.isflag', '=','1')
                                           ->where('icr_carclubdata.ccd_dismiss_tag','=','0');
                              })
                              ->leftJoin('icr_memberclublevel_set as Club_mcls', function($leftJoin)
                              {
                                 $leftJoin->on('Club_mcls.mcls_serno','=','icr_carclubdata.mcls_serno')
                                           ->where('Club_mcls.isflag', '=','1');
                              })
                              ->leftJoin('icr_memberclublevel_set as mem_mcls', function($leftJoin)
                              {
                                 $leftJoin->on('mem_mcls.mcls_serno','=', 'iscarmemberdata.mcls_serno')
                                           ->where('mem_mcls.isflag', '=','1');
                              });
          $result = $query->select( 'iscarmemberdata.md_id'
                                   ,'iscarmemberdata.md_logintype'
                                   ,'iscarmemberdata.mcls_serno'
                                   ,'iscarmemberdata.md_clubjoinstatus'
                                   ,'icr_clubmemberrecord.cmr_id'
                                   ,'icr_clubmemberrecord.ccd_id'
                                   ,'icr_clubmemberrecord.cmr_joinstatus'
                                   ,'icr_clubmemberrecord.cmr_membergrade'
                                   ,'icr_carclubdata.ccd_id'
                                   ,'icr_carclubdata.ccd_clubname'
                                   ,'icr_carclubdata.ccd_clubbadge'
                                   ,'icr_carclubdata.ccd_public_tag'
                                   ,'icr_carclubdata.mcls_serno'
                                   ,'icr_carclubdata.ccd_dismiss_tag'
                                   ,'mem_mcls.mcls_levelweight'
                                   ,'mem_mcls.mcls_nextlevelexp'
                                   ,'Club_mcls.mcls_gradename as Club_mcls_gradename'
                                   ,'Club_mcls.mcls_gradeicon as Club_mcls_gradeicon'
                                   ,'mem_mcls.mcls_gradename as mem_mcls_gradename'
                                   ,'mem_mcls.mcls_gradeicon as mem_mcls_gradeicon'
                                   ,'Club_mcls.mcls_functioncontent'
                                  )
                                  ->get()->toArray();
          return $result;
        } catch(\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
     }

     public function GetPushMd_id($fbgender, $agemin, $agemax, $citysArray) {
         try {
           $query = isCarMemberData::where('iscarmemberdata.isflag','1')
                            ->leftJoin('iscarmembermobilelink', function($leftJoin)
                              {
                                 $leftJoin->on('iscarmembermobilelink.md_id','=','iscarmemberdata.md_id')
                                           ->where('iscarmembermobilelink.mml_apptype', '=','0')
                                           ->where('iscarmembermobilelink.isflag','=','1 ');
                              })
                            ->whereIn("iscarmemberdata.md_logintype", array(0,1));
           if(!is_null($fbgender) && mb_strlen($fbgender) != 0 ) {
               $query->where('iscarmemberdata.ssd_fbgender','=',$fbgender);
           }
           if (!is_null($agemin) && !is_null($agemax) && mb_strlen($agemin) != 0 && mb_strlen($agemax) != 0) {
               $query->whereRaw("year(from_days(DATEDIFF(now(),iscarmemberdata.ssd_birthday))) between $agemin and $agemax");
           }
           if (!is_null($citysArray) && mb_strlen($citysArray) != 0 ) {
               $arrayCitys =  explode(",",$citysArray);
               $query->whereRaw("iscarmemberdata.rl_sn in (select rl.rl_serno from iscarregionlist as rl where rl.rl_city in ("."'".implode("','",$arrayCitys)."'"."))");
           }
           $result = $query->select('iscarmemberdata.md_id')->distinct()->get()->toArray();

           return $result;

         } catch(\Exception $ex) {
           \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
         }
     }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        try {
            return isCarMemberData::get();
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
        }
    }

    /**
     * 使用「$primarykey」查詢資料表的主鍵值
     * @param type $primarykey 要查詢的值
     * @return type
     */
    public function getData($primarykey) {
        try {
            return isCarMemberData::find($primarykey);
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
        }
    }

    /**
     * 使用「會員帳號」查詢資料表的「md_account」
     * @param type $account 會員帳號
     * @return type
     */
    public function getDataByMd_Account($md_account) {
        try {
            return isCarMemberData::where('md_account', '=', $md_account)
                            ->where('isflag', '=', '1')
                            ->get();
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return null;
        }
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        try {
            DB::beginTransaction();
            //檢查必填欄位
            if (!isset($arraydata['md_logintype']) || !isset($arraydata['md_ssobind_status']) || !isset($arraydata['md_ssobind_type'])//
                    || !isset($arraydata['md_countrycode']) || !isset($arraydata['md_mobile']) || !isset($arraydata['md_clienttype'])//
                    || !isset($arraydata['mcls_serno']) || !isset($arraydata['md_clubjoinstatus'])) {
                return false;
            }
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['md_logintype'] = $arraydata['md_logintype'];
            $savedata['md_ssobind_status'] = $arraydata['md_ssobind_status'];
            $savedata['md_ssobind_type'] = $arraydata['md_ssobind_type'];
            $savedata['md_countrycode'] = $arraydata['md_countrycode'];
            $savedata['md_mobile'] = $arraydata['md_mobile'];
            $savedata['md_clienttype'] = $arraydata['md_clienttype'];
            $savedata['mcls_serno'] = $arraydata['mcls_serno'];
            $savedata['md_clubjoinstatus'] = $arraydata['md_clubjoinstatus'];

            if (isset($arraydata['sso_accountid'])) {
                $savedata['sso_accountid'] = $arraydata['sso_accountid'];
            }
            if (isset($arraydata['md_account'])) {
                $savedata['md_account'] = $arraydata['md_account'];
            }
            if (isset($arraydata['md_password'])) {
                $savedata['md_password'] = $arraydata['md_password'];
            }
            if (isset($arraydata['rl_sn'])) {
                $savedata['rl_sn'] = $arraydata['rl_sn'];
            }
            if (isset($arraydata['md_cname'])) {
                $savedata['md_cname'] = $arraydata['md_cname'];
            }
            if (isset($arraydata['md_ename'])) {
                $savedata['md_ename'] = $arraydata['md_ename'];
            }
            if (isset($arraydata['md_tel'])) {
                $savedata['md_tel'] = $arraydata['md_tel'];
            }
            if (isset($arraydata['md_addr'])) {
                $savedata['md_addr'] = $arraydata['md_addr'];
            }
            if (isset($arraydata['md_contactmail'])) {
                $savedata['md_contactmail'] = $arraydata['md_contactmail'];
            }
            if (isset($arraydata['md_first_login'])) {
                $savedata['md_first_login'] = $arraydata['md_first_login'];
            }
            if (isset($arraydata['md_last_login'])) {
                $savedata['md_last_login'] = $arraydata['md_last_login'];
            }
            if (isset($arraydata['md_picturepath'])) {
                $savedata['md_picturepath'] = $arraydata['md_picturepath'];
            }
            if (isset($arraydata['md_firstname'])) {
                $savedata['md_firstname'] = $arraydata['md_firstname'];
            }
            if (isset($arraydata['md_lastname'])) {
                $savedata['md_lastname'] = $arraydata['md_lastname'];
            }
            if (isset($arraydata['md_fbgender'])) {
                $savedata['md_fbgender'] = $arraydata['md_fbgender'];
            }
            if (isset($arraydata['md_birthday'])) {
                $savedata['md_birthday'] = $arraydata['md_birthday'];
            }
            if (isset($arraydata['md_locallanguage'])) {
                $savedata['md_locallanguage'] = $arraydata['md_locallanguage'];
            }

            if (isset($arraydata['isflag'])) {
                $savedata['isflag'] = $arraydata['isflag'];
            }
            if (isset($arraydata['create_user'])) {
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if (isset($arraydata['create_date'])) {
                $savedata['create_date'] = $arraydata['create_date'];
            }
            if (isset($arraydata['last_update_user'])) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            }
            if (isset($arraydata['last_update_date'])) {
                $savedata['last_update_date'] = $arraydata['last_update_date'];
            }
            $result = isCarMemberData::insert($savedata);
            if($result){
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            DB::rollback();
            return false;
        }
    }

    public function updateAdvanceData(Array $arraydata){
        try {
            DB::beginTransaction();
            //檢查必填欄位
            if (!isset($arraydata['md_id']) || !isset($arraydata['md_cname']) || !isset($arraydata['md_lastname']) //
                || !isset($arraydata['md_firstname']) || !isset($arraydata['md_mobile']) || !isset($arraydata['md_contactmail']) //
                || !isset($arraydata['md_birthday']) || !isset($arraydata['md_addr']) || !isset($arraydata['md_city']) //
                || !isset($arraydata['md_country']) || !isset($arraydata['md_tel'])) {
                // || !isset($arraydata['md_picturepath'])
                return false;
            }
            $savedata['md_cname'] = $arraydata['md_cname'];
            $savedata['md_lastname'] = $arraydata['md_lastname'];
            $savedata['md_firstname'] = $arraydata['md_firstname'];
            $savedata['md_mobile'] = $arraydata['md_mobile'];
            $savedata['md_contactmail'] = $arraydata['md_contactmail'];
            $savedata['md_birthday'] = $arraydata['md_birthday'];
            $savedata['md_addr'] = $arraydata['md_addr'];
            $savedata['md_city'] = $arraydata['md_city'];
            $savedata['md_country'] = $arraydata['md_country'];
            $savedata['md_tel'] = $arraydata['md_tel'];

            $result = isCarMemberData::where('md_id',$arraydata['md_id'])->update($savedata);

            DB::commit();
            return true;

        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            DB::rollback();
            return false;
        }
    }

    /**
     *
     */
    public function updateMemberData(Array $arraydata){
        try {
            // 寫死必填欄位
            // $savedata['md_logintype'] = 0;
            $savedata['md_rightstatus'] = 0;
            $savedata['isflag'] = 1;
            $savedata['md_clienttype'] = 0;
            $savedata['mcls_serno'] = 0;
            $savedata['md_clubjoinstatus'] = 0;

            if(isset($arraydata['md_logintype'])){
                $savedate['md_logintype'] = $arraydata['md_logintype'];
            }
            if(isset($arraydata['md_rightstatus'])){
                $savedate['md_rightstatus'] = $arraydata['md_rightstatus'];
            }
            if(isset($arraydata['sso_accountid'])){
                $savedate['sso_accountid'] = $arraydata['sso_accountid'];
            }
            if(isset($arraydata['md_firstname'])){
                $savedate['md_firstname'] = $arraydata['md_firstname'];
            }
            if(isset($arraydata['md_lastname'])){
                $savedate['md_lastname'] = $arraydata['md_lastname'];
            }
            if(isset($arraydata['md_locallanguage'])){
                $savedate['md_locallanguage'] = $arraydata['md_locallanguage'];
            }
            if(isset($arraydata['md_fbgender'])){
                $savedate['md_fbgender'] = $arraydata['md_fbgender'];
            }
            if(isset($arraydata['md_birthday'])){
                $savedate['md_birthday'] = $arraydata['md_birthday'];
            }
            if(isset($arraydata['md_cname'])){
                $savedate['md_cname'] = $arraydata['md_cname'];
            }
            if(isset($arraydata['md_ename'])){
                $savedate['md_ename'] = $arraydata['md_ename'];
            }
            if(isset($arraydata['md_tel'])){
                $savedate['md_tel'] = $arraydata['md_tel'];
            }
            if(isset($arraydata['md_mobile'])){
                $savedate['md_mobile'] = $arraydata['md_mobile'];
            }
            if(isset($arraydata['md_addr'])){
                $savedate['md_addr'] = $arraydata['md_addr'];
            }
            if(isset($arraydata['md_contactmail'])){
                $savedate['md_contactmail'] = $arraydata['md_contactmail'];
            }
            if(isset($arraydata['md_first_login'])){
                $savedate['md_first_login'] = $arraydata['md_first_login'];
            }
            if(isset($arraydata['md_last_login'])){
                $savedate['md_last_login'] = $arraydata['md_last_login'];
            }
            if(isset($arraydata['create_user'])){
                $savedate['create_user'] = $arraydata['create_user'];
            }
            if(isset($arraydata['create_date'])){
                $savedate['create_date'] = $arraydata['create_date'];
            }
            if(isset($arraydata['last_update_user'])){
                $savedate['last_update_user'] = $arraydata['last_update_user'];
            }
            if(isset($arraydata['last_update_date'])){
                $savedate['last_update_date'] = $arraydata['last_update_date'];
            }
            if(isset($arraydata['md_clienttype'])){
                $savedate['md_clienttype'] = $arraydata['md_clienttype'];
            }
            if(isset($arraydata['mcls_serno'])){
                $savedate['mcls_serno'] = $arraydata['mcls_serno'];
            }
            if(isset($arraydata['md_clubjoinstatus'])){
                $savedate['md_clubjoinstatus'] = $arraydata['md_clubjoinstatus'];
            }
            if(isset($arraydata['md_picturepath'])){
                $savedate['md_picturepath'] = $arraydata['md_picturepath'];
            }
            if(isset($arraydata['md_ssobind_status'])){
                $savedate['md_ssobind_status'] = $arraydata['md_ssobind_status'];
            }
            if(isset($arraydata['md_ssobind_type'])){
                $savedate['md_ssobind_type'] = $arraydata['md_ssobind_type'];
            }
            if(isset($arraydata['md_account'])){
                $savedate['md_account'] = $arraydata['md_account'];
            }
            if(isset($arraydata['md_password'])){
                $savedate['md_password'] = $arraydata['md_password'];
            }
            if(isset($arraydata['md_city'])){
                $savedate['md_city'] = $arraydata['md_city'];
            }
            if(isset($arraydata['md_province'])){
                $savedate['md_province'] = $arraydata['md_province'];
            }
            if(isset($arraydata['md_country'])){
                $savedate['md_country'] = $arraydata['md_country'];
            }
            if(isset($arraydata['md_countrycode'])){
                $savedate['md_countrycode'] = $arraydata['md_countrycode'];
            }
            if(isset($arraydata['md_pwd_changedate'])){
                $savedate['md_pwd_changedate'] = $arraydata['md_pwd_changedate'];
            }
            isCarMemberData::where('md_id',$arraydata['md_id'])->update($savedate);
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
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
     * 更新會員密碼
     * @param type $md_id 會員代碼
     * @param type $newpassword 新密碼
     * @return boolean
     */
    public function updatePassword($md_id, $newpassword) {
        try {
            $affectedRows = isCarMemberData::where('md_id', '=', $md_id)->update(array('md_password' => $newpassword));

            if ($affectedRows != 1) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            \App\Library\CommonTools::writeErrorLogByException($ex);
            return false;
        }
    }

    /**
     * 停用或啟用該「$md_id」的資料
     * @param string $md_id  會員代碼
     * @param string $isflag 停用或啟用
      */
    public function delete($md_id,$isflag) {
        try {
            $savedata['isflag'] = $isflag;
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            if(!empty(Session::get('user_name'))){
                $savedata['last_update_user'] = Session::get('user_name');
            }
            isCarMemberData::where('md_id',$md_id)->update($savedata);
            return true;
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

}
