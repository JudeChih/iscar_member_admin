<?php
namespace App\Services;

define('QueryMember', config('global.query_member'));
define('ModifyMember', config('global.modify_member'));
define('Modacc', config('global.modacc'));
define('Modvrf', config('global.modvrf'));

class MemberServer {

    /**
     * call member模組的API [QueryMember]
     * @param  [type] $md_mobile      [會員手機號碼]
     * @param  [type] $md_contactmail [會員電子信箱]
     * @global route  QueryMember     [member模組的API]
     * @global string Modacc          [模組帳號]
     * @global string Modvrf          [模組驗證碼]
     */
    public function callApiQueryMember($md_mobile,$md_contactmail){
        // 編輯要call API所需要傳入的值
        $arraydata['md_mobile'] = $md_mobile;
        $arraydata['md_contactmail'] = $md_contactmail;
        $arraydata['modacc'] = Modacc;
        $arraydata['modvrf'] = Modvrf;
        $url = QueryMember;
        // call API
        $request = \App\Library\CommonTools::curlModule($arraydata,$url);
        $request = $request['query_memberresult'];
        return $request;
    }

    /**
     * call member模組的API [ModifyMember]
     * @param  [type] $md_id          [會員代號]
     * @param  [type] $md_clienttype  [會員類型]
     * @global route  ModifyMember    [member模組的API]
     * @global string Modacc          [模組帳號]
     * @global string Modvrf          [模組驗證碼]
     */
    public function callApiModifyMember($md_id,$md_clienttype){
        // 編輯要call API所需要傳入的值
        $arraydata['md_id'] = $md_id;
        $arraydata['md_clienttype'] = $md_clienttype;
        $arraydata['modacc'] = Modacc;
        $arraydata['modvrf'] = Modvrf;
        $url = ModifyMember;
        // call API
        $request = \App\Library\CommonTools::curlModule($arraydata,$url);
        $request = $request['modify_memberresult'];
        return $request;
    }

}
