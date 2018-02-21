<?php
$title = '使用者資料';
?>

@extends('index')
@section("content_body")
<script type="text/javascript" src="/js/view/user_modify.js"></script>
<div class="btn_group  col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<button type="button" class="btn btn-primary um_save pull-right">存檔</button>
</div>
<div class="panel_detail col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<form action="/login/user-modify" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis form-horizontal" method="post">
		{!! csrf_field() !!}
		<input type="hidden" name="modifytype" value="userModify">
		<input type="hidden" name="serno" value="{{Session::get('serno')}}">
		<div class="form-group">
			<label for="usd_account" class="col-md-4 col-sm-4 col-xs-4 control-label">登入帳號</label>
			<div class="col-md-6 col-sm-6 col-xs-8">
				<input type="text" class="form-control check_unit" value="{{ $userdata['usd_account'] }}" readonly>
			</div>
		</div>
		<div class="form-group">
			<label for="usd_name" class="col-md-4 col-sm-4 col-xs-4 control-label">使用者名稱</label>
			<div class="col-md-6 col-sm-6 col-xs-8">
				<input type="text" class="form-control check_unit" name="usd_name" data-toggle="tooltip" title="名稱不能為空" value="{{ $userdata['usd_name'] }}">
			</div>
		</div>
		<div class="form-group">
			<label for="usd_pwd" class="col-md-4 col-sm-4 col-xs-4 control-label">登入密碼</label>
			<div class="col-md-6 col-sm-6 col-xs-8">
				<input type="password" class="form-control check_unit" name="usd_pwd" data-toggle="tooltip" title="密碼不能為空" value="{{ $userdata['usd_pwd'] }}">
			</div>
		</div>
		<div class="form-group">
			<label for="usd_pwd_confirm" class="col-md-4 col-sm-4 col-xs-4 control-label">確認密碼</label>
			<div class="col-md-6 col-sm-6 col-xs-8">
				<input type="password" class="form-control check_unit" id="usd_pwd_confirm" data-toggle="tooltip" title="請再次確認密碼">
			</div>
		</div>
	</form>
</div>

@endsection
@section("content_footer")
@endsection