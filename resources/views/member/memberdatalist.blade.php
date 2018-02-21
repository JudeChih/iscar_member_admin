<?php
$title = "會員管理";
?>

@extends('index')

@section("content_tip")
<script type="text/javascript" src="/js/view/memberdatalist.js"></script>
	<div class="prompt_body_b">
    <div class="prompt_box_b panel-primary">
  		<div class="panel-heading">
      	<h3>提示框</h3>
      </div>
      <h2></h2>
      <div>
      	<a href="/member/memberdata" class="btn btn-success btn_yes">確認</a>
      </div>
    </div>
  </div>
@endsection
@section("content_body")

<div class="btn_group col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<form action="/member/memberdata" class="search_form col-md-10 col-sm-10 col-xs-12 p_l_r_dis" method="get">
		@if(isset($client_type))
			<input type="hidden" name="client_type" value="{{ $client_type }}">
		@else
			<input type="hidden" name="client_type" value="-1">
		@endif
		@if(isset($search_type))
			<input type="hidden" name="search_type" value="{{ $search_type }}">
		@else
			<input type="hidden" name="search_type" value="md_cname">
		@endif
		@if(isset($isflag))
			<input type="hidden" name="isflag" value="{{ $isflag }}">
		@else
			<input type="hidden" name="isflag" value="2">
		@endif
		@if(isset($sort))
			<input type="hidden" name="sort" value="{{ $sort }}">
			<input type="hidden" name="order" value="{{ $order }}">
		@else
			<input type="hidden" name="sort" value="md_cname">
			<input type="hidden" name="order" value="DESC">
		@endif
    <div class="col-md-8 col-sm-8 col-xs-8 p_l_r_dis md_search">
    	<div class="input-group">
    		<div class="input-group-btn">
	        <select class="form-control br_t_b_l select_width">
		    		<option value="md_cname">名稱</option>
		    		<option value="md_account">帳號</option>
		    		<option value="md_contactmail">信箱</option>
		    		<option value="md_mobile">手機</option>
		    	</select>
	      </div>
		    @if(isset($query_name))
		    	<input type="text" name="query_name" class="form-control" placeholder="查詢使用者名稱" value="{{ $query_name }}">
				@else
					<input type="text" name="query_name" class="form-control" placeholder="查詢使用者名稱">
				@endif
			</div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis client_type">
    	<select class="form-control">
    		<option value="-1">用戶全部類別</option>
    		<option value="0">一般會員</option>
    		<option value="1">特約商</option>
    		<option value="3">品牌商</option>
    		<option value="99">宮廟管理員</option>
    		<option value="100">業務</option>
    	</select>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis isflag">
    	<select class="form-control">
    		<option value="2">全部</option>
    		<option value="0">停用中</option>
    		<option value="1">啟用中</option>
    	</select>
    </div>
    <div class="radio col-md-8 col-sm-8 col-xs-8 p_l_r_dis">
    	@if(isset($query_status))
    		<input type="hidden" name="query_status" value="{{$query_status}}">
    		<input type="hidden" name="query_day" value="{{ $query_day }}">
    	@else
				<input type="hidden" name="query_status" value="0">
				<input type="hidden" name="query_day" value="0">
    	@endif
    	<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
			  <label>
			    <input type="radio" value="0" name='mdl'>
			    查詢全部
			  </label>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
			  <label>
			    <input type="radio" value="1" name='mdl'>
			    未修改密碼
			  </label>
			  @if(isset($query_status))
			  	@if($query_status == 1)
						<label>
					  	<input type="text" class="form-control" placeholder="請輸入天數" value="{{ $query_day }}">
					  </label>
					@else
						<label>
					  	<input type="text" class="form-control" placeholder="請輸入天數" value="">
					  </label>
					@endif
				@else
					<label>
				  	<input type="text" class="form-control" placeholder="請輸入天數">
				  </label>
			  @endif
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
			  <label>
			    <input type="radio" value="2" name='mdl'>
			    未登入帳號
			  </label>
			  @if(isset($query_status))
			  	@if($query_status == 2)
					  <label>
					  	<input type="text" class="form-control" placeholder="請輸入天數" value="{{ $query_day }}">
					  </label>
					@else
						<label>
					  	<input type="text" class="form-control" placeholder="請輸入天數">
					  </label>
					@endif
				@else
					<label>
				  	<input type="text" class="form-control" placeholder="請輸入天數">
				  </label>
				@endif
			</div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">
    	<button type="button" class="btn btn-default mdl_search">查詢</button>
    </div>
  </form>
  <div class="col-md-2 col-sm-2 col-xs-12 p_l_r_dis btn_m_t">
		<button type="button" class="btn btn-info pull-right mdl_flag">停 / 啟用</button>
	</div>
</div>
<div class="panel_subtitle col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="md_cname">名稱 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-3 col-sm-3 col-xs-3 p_l_r_dis" data-val="md_account">帳號 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-3 col-sm-3 col-xs-3 p_l_r_dis" data-val="md_contactmail">信箱 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="md_mobile">手機 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="create_date">加入時間 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="md_logintype">登入方式 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="md_clienttype">用戶類別 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="isflag">狀態 <i class="dis_none fa" aria-hidden="true"></i></div>
</div>
<div class="panel_form col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
@if(isset($memberdata))
	@if(count($memberdata)!=0)
		@foreach ($memberdata as $data)
			<form action="/member/memberdata" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis" method="post">
				{!! csrf_field() !!}
				<input type="hidden" name="md_id" value="{{ $data->md_id }}">
				<input type="hidden" name="isflag" value="{{ $data->isflag }}">
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis mdl_replace">{{ $data->md_cname }}</div>
				<div class="col-md-3 col-sm-3 col-xs-3 p_l_r_dis mdl_replace">{{ $data->md_account }}</div>
				<div class="col-md-3 col-sm-3 col-xs-3 p_l_r_dis mdl_replace">{{ $data->md_contactmail }}</div>
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis mdl_replace">{{ $data->md_mobile }}</div>
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis ">{{ $data->create_date }}</div>
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis">
					@if($data->md_logintype == 0)
						FB
					@elseif($data->md_logintype == 1)
						Google
					@elseif($data->md_logintype == 2)
						快速註冊
					@elseif($data->md_logintype == 3)
						帳密登入
					@elseif($data->md_logintype == 4)
						WeChat
					@endif
				</div>
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis">{{ $data->md_clienttype }}</div>
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis">
					@if($data->isflag == 0)
						停用中
					@elseif($data->isflag == 1)
						啟用中
					@endif
				</div>
			</form>
  	@endforeach
  @else
		<p>查無資料</p>
  @endif
@else
	<p>目前暫無會員</p>
@endif
</div>
@endsection
@section("content_footer")
<div class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
@if(isset($memberdata))
	@if(isset($sort))
		{{ $memberdata->appends(['sort' => $sort,'order' => $order,'isflag' => $isflag,'query_name' => $query_name,'query_status' => $query_status,'query_day' => $query_day,'search_type' => $search_type,'client_type' => $client_type]) }}
	@else
		{{ $memberdata }}
	@endif
@endif
</div>
@endsection