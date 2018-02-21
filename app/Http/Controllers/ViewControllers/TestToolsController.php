<?php
namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;

class TestToolsController extends Controller {

	/**
	 * 導到[測試推撥]頁面
	 */
	public function testPushNotification(){
		try {
			return View::make('testtools/testpushnotification');
		} catch (Exception $e) {
			\App\Library\CommonTools::writeErrorLogByException($e);
            return false;
		}
	}

	/**
	 * 開始測試
	 * @param  string  $message  [推撥內容]
	 * @param  json    $data     [自定義鍵值對]
	 */
	public function startPush(){
		$pushdata = Request::all();
		try {
			if($pushdata['testtype'] == 1){//推波測試
				if($pushdata['push_id'] == null || $pushdata['push_id'] == ''){
					return redirect('/testtools/testpushnotification')->withErrors(['error' => '請填寫推播ID']);
				}
				if(!\App\Library\CommonTools::strIsSpecial($pushdata['push_id'])){
					return redirect('/testtools/testpushnotification')->withErrors(['error' => '裡面有包含特殊符號']);
				}
				$data = $pushdata['push_data'];
				$data = json_decode($data, TRUE);
				if(strlen($data['message']) > 50){
					$message = mb_substr( $data['message'],0,50,"utf-8");
	        		$data['message'] = $message."...";
		      	}
				$push_id = $pushdata['push_id'];
				if($pushdata['push_type'] == 1){
					\App\Library\CommonTools::Push_Notification_GCM($push_id,$data);
				}
				if($pushdata['push_type'] == 2){
					\App\Library\CommonTools::Push_Notification_APNS($push_id,$data);
				}

				return View::make('testtools/testpushnotification');

			}elseif($pushdata['testtype'] == 2){//簡訊王測試
				$sk_s = new \App\Services\SmsKotService;
				if($pushdata['mobile'] == null || $pushdata['mobile'] == ''){
					return redirect('/testtools/testkotsms')->withErrors(['error' => '請填寫收件人電話號碼']);
				}
				if(!$sk_s->sendSMS($pushdata['mobile'],$pushdata['sms_data'])){
					return redirect('/testtools/testkotsms')->withErrors(['error' => '發送失敗，請重新輸入']);
				}
				return redirect('/testtools/testkotsms')->withErrors(['error' => '發送成功']);

			}elseif($pushdata['testtype'] == 3){//三竹測試
				$sk_s = new \App\Services\SmsMitakeService;
				if($pushdata['mobile'] == null || $pushdata['mobile'] == ''){
					return redirect('/testtools/testmitakesms')->withErrors(['error' => '請填寫收件人電話號碼']);
				}
				if(!$sk_s->sendSMS($pushdata['mobile'],$pushdata['sms_data'])){
					return redirect('/testtools/testmitakesms')->withErrors(['error' => '發送失敗，請重新輸入']);
				}
				return redirect('/testtools/testmitakesms')->withErrors(['error' => '發送成功']);

			}

		} catch (Exception $e) {
			\App\Library\CommonTools::writeErrorLogByException($e);
            return false;
		}
	}

	/**
	 * 導到[測試簡訊王]頁面 簡訊發送測試
	 */
	public function testKotsms(){
		try {
			return View::make('testtools/testkotsms');
		} catch (Exception $e) {
			\App\Library\CommonTools::writeErrorLogByException($e);
            return false;
		}
	}

	/**
	 * 導到[測試三竹]頁面 簡訊發送測試
	 */
	public function testMitakesms(){
		try {
			return View::make('testtools/testmitakesms');
		} catch (Exception $e) {
			\App\Library\CommonTools::writeErrorLogByException($e);
            return false;
		}
	}

}