<?php
$title = "簡訊管理";
?>

@extends('index')

@section("content_tip")
<script type="text/javascript" src="/js/view/smsdatalist.js"></script>
	<div class="prompt_body_b">
    <div class="prompt_box_b panel-primary">
  		<div class="panel-heading">
      	<h3>提示框</h3>
      </div>
      <h2></h2>
      <div>
      	<a href="/member/smsdata" class="btn btn-success btn_yes">確認</a>
      </div>
    </div>
  </div>
@endsection
@section("content_body")

<div class="btn_group col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<form action="/member/smsdata" class="search_form col-md-12 col-sm-12 col-xs-12 p_l_r_dis" method="get">
		@if(isset($sort))
			<input type="hidden" name="sort" value="{{ $sort }}">
			<input type="hidden" name="order" value="{{ $order }}">
		@else
			<input type="hidden" name="sort" value="snc_destination">
			<input type="hidden" name="order" value="DESC">
		@endif
		@if(isset($query_time_from))
			<div class="input-group col-md-3 col-sm-3 col-xs-12 p_l_r_dis">
			  <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_time_from' id="query_time_from" aria-describedby="sizing-addon1" placeholder="請選擇起始日" data-toggle="tooltip" title="起始日" value="{{$query_time_from}}">
			</div>
		@else
			<div class="input-group col-md-3 col-sm-3 col-xs-12 p_l_r_dis">
			  <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_time_from' id="query_time_from" aria-describedby="sizing-addon1" placeholder="請選擇起始日" data-toggle="tooltip" title="起始日">
			</div>
		@endif
		@if(isset($query_time_to))
			<div class="input-group col-md-3 col-sm-3 col-xs-12 p_l_r_dis">
			  <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-calendar" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_time_to' id="query_time_to" aria-describedby="sizing-addon2" placeholder="請選擇結束日" data-toggle="tooltip" title="結束日" value="{{$query_time_to}}">
			</div>
		@else
			<div class="input-group col-md-3 col-sm-3 col-xs-12 p_l_r_dis">
			  <span class="input-group-addon" id="sizing-addon2"><i class="fa fa-calendar" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_time_to' id="query_time_to" aria-describedby="sizing-addon2" placeholder="請選擇結束日" data-toggle="tooltip" title="結束日">
			</div>
		@endif
		@if(isset($query_phone))
	    <div class="input-group col-md-3 col-sm-3 col-xs-12 p_l_r_dis">
			  <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_phone' id="query_phone" placeholder="查詢使用者電話" value="{{ $query_phone }}" data-toggle="tooltip" title="手機號碼">
			</div>
		@else
			<div class="input-group col-md-3 col-sm-3 col-xs-12 p_l_r_dis">
			  <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name='query_phone' id="query_phone" placeholder="查詢使用者電話" data-toggle="tooltip" title="手機號碼">
			</div>
		@endif
    <div class="col-md-2 col-sm-2 col-xs-12 p_l_r_dis">
    	<button type="button" class="btn btn-default sdl_search pull-left">查詢</button>
    </div>
  </form>
</div>
@if(isset($sms_total) && isset($sms_success) && isset($sms_fail))
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="alert alert-info alert_total" role="alert">總共發送<span>{{$sms_total}}</span>封簡訊，成功<span>{{$sms_success}}</span>封，失敗<span>{{$sms_fail}}</span>封</div>
</div>
@endif
<div class="panel_subtitle col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
	<div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="snc_destination">發送地區 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-4 col-sm-4 col-xs-4 p_l_r_dis" data-val="snc_targetphone">發送手機 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="snc_code">驗證碼 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="snc_sendresult">發送結果 <i class="dis_none fa" aria-hidden="true"></i></div>
	<div class="sort_check col-md-2 col-sm-2 col-xs-2 p_l_r_dis" data-val="snc_verifyresult">驗證結果 <i class="dis_none fa" aria-hidden="true"></i></div>
</div>
<div class="panel_form col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
@if(isset($smsdata))
	@if(count($smsdata)!=0)
		@foreach ($smsdata as $data)
			<form action="/member/smsdata" class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis" method="post">
				{!! csrf_field() !!}
				<input type="hidden" name="isflag" value="{{ $data->isflag }}">
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">
					@if($data->snc_destination == 0)
						測試假值
					@elseif($data->snc_destination == 1)
						台灣
					@elseif($data->snc_destination == 2)
						大陸
					@endif
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4 p_l_r_dis">{{ $data->snc_targetphone }}</div>
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">{{ $data->snc_code }}</div>
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">
					@if($data->snc_sendresult == 0)
						失敗
					@elseif($data->snc_sendresult == 1)
						成功
					@endif
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2 p_l_r_dis">
					@if($data->snc_verifyresult == 0)
						未驗證
					@elseif($data->snc_verifyresult == 1)
						已驗證
					@elseif($data->snc_verifyresult == 2)
						逾時失效
					@endif
				</div>
			</form>
  	@endforeach
  @else
		<p>查無資料</p>
  @endif
@else
	<p>目前暫無資料</p>
@endif
</div>
@endsection
@section("content_footer")
<div class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis">
@if(isset($smsdata))
	@if(isset($sort))
		{{ $smsdata->appends(['sort' => $sort,'order' => $order,'query_time_from' => $query_time_from,'query_phone' => $query_phone,'query_time_to' => $query_time_to]) }}
	@else
		{{ $smsdata }}
	@endif
@endif
</div>
@endsection