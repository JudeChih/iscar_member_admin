<?php

namespace App\Repositories;

use App\Models\icr_SmsNumberCode;

class SmsNumberCodeRepository {

    /**
     * 取得10筆資料
     * @return type
     */
    public function getTenData($query_time_from,$query_time_to,$query_phone,$sort,$order){
        try {
            if(is_null($sort) && is_null($order)){
                $sort = 'snc_destination';
                $order = 'DESC';
            }
            if(is_null($query_phone)){
                $query_phone = '';
            }
            $sms_string = icr_SmsNumberCode::where('snc_targetphone','like', '%'.$query_phone.'%');
            if(!is_null($query_time_from) && !is_null($query_time_to)){
                $sms_string->where('create_date','>',$query_time_from)->where('create_date','<',$query_time_to);
            }
            return $sms_string->orderBy($sort,$order)->paginate(10);
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 抓取時間區間內的簡訊總發送量
     * @param  [type] $query_time_from [起始時間]
     * @param  [type] $query_time_to   [結束時間]
     * @param  [type] $query_phone     [發送手機]
     */
    public function getTotalSMS($query_time_from,$query_time_to,$query_phone){
        try {
            if(is_null($query_phone)){
                $query_phone = '';
            }
            $sms_string = icr_SmsNumberCode::where('snc_targetphone','like', '%'.$query_phone.'%');
            if(!is_null($query_time_from) && !is_null($query_time_to)){
                $sms_string->where('create_date','>',$query_time_from)->where('create_date','<',$query_time_to);
            }
            return $sms_string->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 抓取時間區間內的簡訊發送成功量
     * @param  [type] $query_time_from [起始時間]
     * @param  [type] $query_time_to   [結束時間]
     * @param  [type] $query_phone     [發送手機]
     */
    public function getSuccessSMS($query_time_from,$query_time_to,$query_phone){
        try {
            if(is_null($query_phone)){
                $query_phone = '';
            }
            $sms_string = icr_SmsNumberCode::where('snc_targetphone','like', '%'.$query_phone.'%');
            if(!is_null($query_time_from) && !is_null($query_time_to)){
                $sms_string->where('create_date','>',$query_time_from)->where('create_date','<',$query_time_to);
            }
            return $sms_string->where('snc_sendresult',1)->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    /**
     * 抓取時間區間內的簡訊發送失敗量
     * @param  [type] $query_time_from [起始時間]
     * @param  [type] $query_time_to   [結束時間]
     * @param  [type] $query_phone     [發送手機]
     */
    public function getFailSMS($query_time_from,$query_time_to,$query_phone){
        try {
            if(is_null($query_phone)){
                $query_phone = '';
            }
            $sms_string = icr_SmsNumberCode::where('snc_targetphone','like', '%'.$query_phone.'%');
            if(!is_null($query_time_from) && !is_null($query_time_to)){
                $sms_string->where('create_date','>',$query_time_from)->where('create_date','<',$query_time_to);
            }
            return $sms_string->where('snc_sendresult',0)->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    public function getDataBySendresult($snc_sendresult,$first,$last){
        try {
            $string = icr_SmsNumberCode::where('create_date','>',$first)->where('create_date','<',$last);
            if(!is_null($snc_sendresult)){
                $string->where('snc_sendresult',$snc_sendresult);
            }
            return $string->get();
        } catch (Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }

    // public function getDataBySendresult_LastMonth($snc_sendresult){
    //     try {
    //         if(is_null($snc_sendresult)){
    //             return icr_SmsNumberCode::get();
    //         }
    //         return icr_SmsNumberCode::where('snc_sendresult',$snc_sendresult)->get();
    //     } catch (Exception $e) {
    //         \App\Library\CommonTools::writeErrorLogByException($e);
    //         return false;
    //     }
    // }

    /**
     * 取得所有資料
     * @return type
     */
    public function getAllData() {
        return icr_SmsNumberCode::get();
    }

    /**
     * 使用「$primarykey」查詢資料表的主鍵值
     * @param type $primarykey 要查詢的值
     * @return type
     */
    public function getData($primarykey) {
        return icr_SmsNumberCode::find($primarykey);
    }

    /**
     * 使用「簡訊發送手機號碼」查詢資料表的「snc_targetphone」
     * @param type $snc_targetphone 簡訊發送手機號碼
     * @return type
     */
    public function getDataBySnc_TargetPhone($snc_targetphone) {
        return icr_SmsNumberCode::where('snc_targetphone', '=', $snc_targetphone)
                        ->where('snc_sendresult', '=', '1')
                        ->where('snc_verifyresult', '=', '0')
                        ->orderBy('snc_serno','DESC')
                        ->get();
    }

    /**
     * 建立一筆新的資料
     * @param array $arraydata 要新增的資料
     * @return type
     */
    public function create(array $arraydata) {
        try {
            $result = $this->createGetId($arraydata);
            if (!isset($result) || $result == null) {
                return false;
            }
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 建立一筆新的資料並取得自動新增的PriamryKey
     * @param array $arraydata 要異動的資料
     * @return type 自動新增的PriamryKey
     */
    public function createGetId(array $arraydata) {
        try {
            //檢查必填欄位
            if (!isset($arraydata['snc_destination']) || !isset($arraydata['snc_targetphone']) || !isset($arraydata['snc_code'])//
                    || !isset($arraydata['snc_sendresult']) || !isset($arraydata['snc_verifyresult'])) {
                return null;
            }
            //將資料填入對應的欄位
            $savedata['snc_destination'] = $arraydata['snc_destination'];
            $savedata['snc_targetphone'] = $arraydata['snc_targetphone'];
            $savedata['snc_code'] = $arraydata['snc_code'];
            $savedata['snc_sendresult'] = $arraydata['snc_sendresult'];
            $savedata['snc_verifyresult'] = $arraydata['snc_verifyresult'];


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
            //執行新增資料，並取得自動新增的欄位

            return icr_SmsNumberCode::insertGetId($savedata);
        } catch (\Exception $ex) {
            return null;
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
     * 將該「$snc_targetphone」舊的且「snc_verifyresult」為〈０：未驗證〉的資料設為〈２：失效〉
     * @param type $snc_targetphone 發送手機號碼
     * @return boolean 修改結果
     */
    public function updateOldToInvalidByTargetPhone($snc_targetphone) {
        try {
            icr_SmsNumberCode::where('snc_targetphone', '=', $snc_targetphone)
                    ->where('snc_sendresult', '=', '1')
                    ->where('snc_verifyresult', '=', '0')
                    ->update(array('snc_verifyresult' => '2'));

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 刪除該「$primarykey」的資料
     * @param type $primarykey 主鍵值
     */
    public function delete($primarykey) {
        return null;
    }

}
