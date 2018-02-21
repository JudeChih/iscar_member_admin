<?php
$title = '使用者列表';
?>

@extends('index')
@section("content_body")
<script type="text/javascript" src="/js/view/userdatalist.js"></script>
<div class="btn_group  col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<form action="/login/user-list" class="search_form col-md-6 col-sm-6 col-xs-10 p_l_r_dis" method="get">
{{-- 		@if(isset($sort))
			<input type="hidden" name="sort" value="{{ $sort }}">
		@else
			<input type="hidden" name="sort" value="11">
		@endif --}}
    <div class="col-md-8 col-sm-8 col-xs-8 p_l_r_dis">
	    @if(isset($query_name))
	    	<input type="text" name="query_name" class="form-control" placeholder="查詢使用者名稱" value="{{ $query_name }}">
			@else
				<input type="text" name="query_name" class="form-control" placeholder="查詢使用者名稱">
			@endif
    </div>
    <div class="radio col-md-8 col-sm-8 col-xs-8 p_l_r_dis">
    	@if(isset($query_status))
    		<input type="hidden" name="query_status" value="{{$query_status}}">
    	@else
				<input type="hidden" name="query_status" value="2">
    	@endif
    	<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
			  <label>
			    <input type="radio" value="2" name="radio">
			    全部
			  </label>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
			  <label>
			    <input type="radio" value="0" name="radio">
			    停用
			  </label>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
			  <label>
			    <input type="radio" value="1" name="radio">
			    正常
			  </label>
			</div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
    	<button type="submit" class="btn btn-default udl_search">查詢</button>
    </div>
  </form>
  <div class="col-md-6 col-sm-6 col-xs-12 p_l_r_dis">
  	<button type="button" class="btn btn-danger udl_delete pull-right">刪除</button>
		<button type="button" class="btn btn-warning udl_edit pull-right">修改</button>
		<a href="/login/user-list-create" class="btn btn-success pull-right">新增</a>
	</div>
</div>
<div class="panel_subtitle col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<div class="sort_check col-md-4 col-sm-4 col-xs-4 p_l_r_dis" data-val="1">使用者名稱{{--  <i class="dis_none fa" aria-hidden="true"></i> --}}</div>
	<div class="sort_check col-md-4 col-sm-4 col-xs-4 p_l_r_dis" data-val="2">使用者狀態{{--  <i class="dis_none fa" aria-hidden="true"></i> --}}</div>
	<div class="sort_check col-md-4 col-sm-4 col-xs-4 p_l_r_dis" data-val="3">登入帳號{{--  <i class="dis_none fa" aria-hidden="true"></i> --}}</div>
</div>
<div class="panel_form col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	@if(count($userdata)!=0)
		@foreach ($userdata as $data)
			<form action="/login/user-list-modify" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis" method="get">
				{!! csrf_field() !!}
				<input type="hidden" name="usd_serno" value="{{ $data->usd_serno }}">
				<div class="col-md-4 col-sm-4 col-xs-4 text_left">{{ $data->usd_name }}</div>
				<div class="col-md-4 col-sm-4 col-xs-4 text_left">
					@if($data->usd_status == 0)
						停用
					@elseif($data->usd_status == 1)
						正常
					@endif
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4 text_left">{{ $data->usd_account }}</div>
			</form>
  	@endforeach
  @else
		<p>查無資料</p>
  @endif
</div>
@endsection
@section("content_footer")
<div class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	@if(isset($userdata))
		{{ $userdata }}
	@endif
</div>
@endsection