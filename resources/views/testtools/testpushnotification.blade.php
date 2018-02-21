<?php
$title = "測試推播";
?>

@extends('index')
@section("content_tip")
<script type="text/javascript" src="/js/view/testpushnotification.js"></script>
  <div class="prompt_body_b">
    <div class="prompt_box_b panel-primary">
      <div class="panel-heading">
        <h3>提示框</h3>
      </div>
      <h2></h2>
      <div>
        <a href="/member/moduledata" class="btn btn-success btn_yes">確認</a>
      </div>
    </div>
  </div>
@endsection
@section("content_body")
<div class="panel_detail col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
  <form action="/testtools/testpushnotification" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis form-horizontal" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="testtype" value="1">
    <div class="form-group">
      <label for="push_type" class="col-md-4 col-sm-4 col-xs-4 control-label">推播類型</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        <select class="form-control" data-toggle="tooltip" id='push_type' name="push_type" title="推播類型">
          <option value="1">GCM</option>
          <option value="2">APNS</option>
      </select>
      </div>
    </div>
    <div class="form-group">
      <label for="push_id" class="col-md-4 col-sm-4 col-xs-4 control-label">推播ID</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        {{-- <input type="text" name="push_id" class="form-control" data-toggle="tooltip" title="請填入GCM_id或是APNS_id"> --}}
        <input type="text" name="push_id" class="form-control" data-toggle="tooltip" title="請填入GCM_id或是APNS_id" value="">
      </div>
    </div>
    {{-- <div class="form-group">
      <label for="push_user" class="col-md-4 col-sm-4 col-xs-4 control-label">目標手機</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        <select class="form-control" data-toggle="tooltip" title="模組類型" name="push_user">
        <option value="0" data-value="1">Alen</option>
        <option value="1" data-value="1">Felix</option>
        <option value="2" data-value="1">小黑</option>
        <option value="3" data-value="1">阿猴</option>
        <option value="4" data-value="1">阿志</option>
        <option value="5" data-value="2">小白</option>
        <option value="6" data-value="2">阿駿</option>
        <option value="7" data-value="2">Tina</option>
      </select>
      </div>
    </div> --}}
    <div class="form-group push_data">
      <label for="push_data" class="col-md-4 col-sm-4 col-xs-4 control-label">Data</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        <textarea class="form-control" rows="5" name="push_data" data-toggle="tooltip" title="自定義參數，格式為json">
{
"message":"我是推播內容。",
"title":"我是推播標題",
"iscar_push":{"id_1":"123","id_2":"456"},
"sound":"lock"
}
        </textarea>
      </div>
    </div>
    {{-- <div class="form-group push_message dis_none">
      <label for="push_message" class="col-md-4 col-sm-4 col-xs-4 control-label">Message</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        <input type="text" name="push_message" class="form-control" data-toggle="tooltip" title="請填入推播內容，限字串">
      </div>
    </div> --}}
    <div class="form-group">
      <div class="col-sm-offset-4 col-sm-6">
        <button type="submit" class="btn btn-primary">測試</button>
      </div>
    </div>
  </form>
</div>
@endsection
@section("content_footer")
@endsection