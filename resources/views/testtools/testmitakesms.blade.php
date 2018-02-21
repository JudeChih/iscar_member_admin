<?php
$title = "測試三竹";
?>

@extends('index')
@section("content_tip")
<script type="text/javascript" src="/js/view/testmitakesms.js"></script>
  <div class="prompt_body_b">
    <div class="prompt_box_b panel-primary">
      <div class="panel-heading">
        <h3>提示框</h3>
      </div>
      <h2></h2>
      <div>
        <button class="btn btn-primary takesms_yes">確認</button>
      </div>
    </div>
  </div>
@endsection
@section("content_body")
<div class="panel_detail col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
  <form action="/testtools/testmitakesms" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis form-horizontal" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="testtype" value="3">
    <div class="form-group">
      <label for="mobile" class="col-md-4 col-sm-4 col-xs-4 control-label">收件人電話</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        <input type="text" name="mobile" class="form-control" data-toggle="tooltip" title="請填入10碼手機號碼" maxlength="10" value="">
      </div>
    </div>
    <div class="form-group sms_data">
      <label for="sms_data" class="col-md-4 col-sm-4 col-xs-4 control-label">簡訊內容</label>
      <div class="col-md-6 col-sm-6 col-xs-8">
        <input type="text" name="sms_data" class="form-control" data-toggle="tooltip" title="簡訊內容" value="">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-4 col-sm-6">
        <button type="button" class="btn btn-primary send">發送</button>
      </div>
    </div>
  </form>
</div>
@endsection
@section("content_footer")
@endsection