<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/login', function () {
  	return \Illuminate\Support\Facades\View::make('login/login');
});
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', [
    'as' => 'logout',
    'uses' => 'Auth\LoginController@logout'
]);
//////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/', function () {
    return redirect('/login');
});
/////////////////////////////////////////////Sunwai///////////////////////////////////////////////
Route::group(['middleware' => 'userdata'],function(){
		////////////////////////////////////////////////////////////////////////////////////////////////
		// Route::get('/index', function () {
		//     return view('index');
		// });

		// index頁面
		Route::get('/index','ViewControllers\IndexController@indexdata');

		// 會員管理頁面
		Route::get('/member/memberdata','ViewControllers\MemberDataController@memberdata');
		Route::post('/member/memberdata','ViewControllers\MemberDataController@changeIsflag');
		// 簡訊管理頁面
		Route::get('/member/smsdata','ViewControllers\SmsDataController@smsdata');
		// 模組管理頁面
		Route::get('/member/moduledata','ViewControllers\ModuleDataController@moduledata');
		Route::post('/member/moduledata','ViewControllers\ModuleDataController@changeIsflag');
		Route::get('/member/moduledatamodify','ViewControllers\ModuleDataController@modify');
		Route::post('/member/moduledatamodify','ViewControllers\ModuleDataController@save');
		// 下載專區頁面
		Route::get('/member/downloaddata','ViewControllers\DownloadDataController@downloaddata');
		Route::post('/member/downloaddata','ViewControllers\DownloadDataController@save');
		// 後台管理頁面
		Route::get('/login/user-modify','ViewControllers\UserController@userData');
		Route::post('/login/user-modify','ViewControllers\UserController@save');
		Route::get('/login/user-list','ViewControllers\UserController@userList');
		Route::get('/login/user-list-modify','ViewControllers\UserController@userListModify');
		Route::post('/login/user-list-modify','ViewControllers\UserController@save');
		Route::get('/login/user-list-delete','ViewControllers\UserController@userListDelete');
		Route::get('/login/user-list-create','ViewControllers\UserController@userListCreate');
		Route::post('/login/user-list-create','ViewControllers\UserController@save');
		// 測試工具
		Route::get('/testtools/testpushnotification','ViewControllers\TestToolsController@testPushNotification');
		Route::post('/testtools/testpushnotification','ViewControllers\TestToolsController@startPush');



		// 簡訊王
		Route::get('/testtools/testkotsms','ViewControllers\TestToolsController@testKotsms');
		Route::post('/testtools/testkotsms','ViewControllers\TestToolsController@startPush');
		// 三竹
		Route::get('/testtools/testmitakesms','ViewControllers\TestToolsController@testMitakesms');
		Route::post('/testtools/testmitakesms','ViewControllers\TestToolsController@startPush');


});