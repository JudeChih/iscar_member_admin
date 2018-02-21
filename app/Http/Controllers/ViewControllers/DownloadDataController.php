<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;

class DownloadDataController extends Controller {

    /**
     * 導到[下載管理]頁面
     */
    public function downloaddata() {
        $data = Request::all();
        try {
            return View::make('download/downloaddatalist',compact('moduledata','query_module_name','query_functiontype','sort','order'));
        } catch (\Exception $e) {
            \App\Library\CommonTools::writeErrorLogByException($e);
            return false;
        }
    }
}
