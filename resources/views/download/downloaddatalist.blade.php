<?php
$title = "下載管理";
?>

@extends('index')

@section("content_tip")
<script type="text/javascript" src="/js/view/moduledatalist.js"></script>
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

{{-- <div class="btn_group col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<form action="/member/moduledata" class="search_form col-md-6 col-sm-6 col-xs-12 p_l_r_dis" method="get">

		@if(isset($query_functiontype))
			<input type="hidden" name="query_functiontype" value="{{ $query_functiontype }}">
		@else
			<input type="hidden" name="query_functiontype" value="">
		@endif
		@if(isset($sort))
			<input type="hidden" name="sort" value="{{ $sort }}">
			<input type="hidden" name="order" value="{{ $order }}">
		@else
			<input type="hidden" name="sort" value="mapr_moduleaccount">
			<input type="hidden" name="order" value="DESC">
		@endif
		@if(isset($query_module_name))
			<div class="input-group col-md-5 col-sm-5 col-xs-5 p_l_r_dis">
			  <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_module_name' placeholder="請輸入模組名稱" data-toggle="tooltip" title="模組名稱" value="{{$query_module_name}}">
			</div>
		@else
			<div class="input-group col-md-5 col-sm-5 col-xs-5 p_l_r_dis">
			  <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_module_name' placeholder="請輸入模組名稱" data-toggle="tooltip" title="模組名稱">
			</div>
		@endif
		<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis functiontype">
    	<select class="form-control" data-toggle="tooltip" title="模組類型">
    		<option value="0">全部</option>
    		<option value="1">前台</option>
    		<option value="2">後台</option>
    	</select>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">
    	<button type="button" class="btn btn-default mdl_search pull-left">查詢</button>
    </div>
  </form>
  <div class="col-md-6 col-sm-6 col-xs-12 p_l_r_dis btn_m_t">
  	<button type="button" class="btn btn-info pull-right mdl_flag">停 / 啟用</button>
		<button type="button" class="btn btn-warning pull-right mdl_modify">修改</button>
	</div>
</div> --}}
<div class="panel_subtitle col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	{{-- <div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="mapr_moduleaccount">模組帳號 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="mapr_modulename">模組名稱 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-5 col-sm-5 col-xs-5 p_l_r_dis" data-val="mapr_redirect_uri">模組呼叫回傳頁 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-1 col-sm-1 col-xs-1 p_l_r_dis" data-val="mapr_functiontype">模組類型 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="isflag">模組狀態 <i class="dis_none fa" aria-hidden="true"></i></div> --}}
</div>
<div class="panel_form col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
@if(isset($moduledata))
	@if(count($moduledata)!=0)
		@foreach ($moduledata as $data)
			<form action="/member/moduledatamodify" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis" method="get">
				{!! csrf_field() !!}
				<input type="hidden" name="mapr_serno" value="{{ $data->mapr_serno }}">
				<input type="hidden" name="isflag" value="{{ $data->isflag }}">
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">{{ $data->mapr_moduleaccount }}</div>
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">{{ $data->mapr_modulename }}</div>
				<div class="col-md-5 col-sm-5 col-xs-5 p_l_r_dis">{{ $data->mapr_redirect_uri }}</div>
				<div class="col-md-1 col-sm-1 col-xs-1 p_l_r_dis">
					@if($data->mapr_functiontype == 1)
						前台
					@elseif($data->mapr_functiontype == 2)
						後台
					@endif
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">
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
	<p>暫無下載資料</p>
@endif
</div>
@endsection
@section("content_footer")
<div class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
{{-- @if(isset($moduledata))
	@if(isset($sort))
		{{ $moduledata->appends(['sort' => $sort,'order' => $order,'query_module_name' => $query_module_name,'query_functiontype' => $query_functiontype]) }}
	@else
		{{ $moduledata }}
	@endif
@endif --}}
</div>
@endsection