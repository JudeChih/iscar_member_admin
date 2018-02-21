<?php
$title = "統計列表";
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

<div class="btn_group col-md-6 col-sm-6 col-xs-12">
	<div class="jumbotron member_total">
	  <h2>isCar會員數</h2>
	  <ul>
	  	<li>總會員數：<span>{{$indexData['mem_num']}}</span>位
				<ul>
					<li>ＦＢ會員：<span>{{$indexData['mem_fb']}}</span>位</li>
	  			<li>一般會員：<span>{{$indexData['mem_iscar']}}</span>位</li>
				</ul>
	  	</li>
	  	<li>本月新增會員：<span>{{$indexData['mem_num_this']}}</span>位</li>
	  	<li>上月新增會員：<span>{{$indexData['mem_num_last']}}</span>位</li>
	  </ul>
	  <p><a href="/member/memberdata" class="btn btn-primary">了解更多</a></p>
	</div>
</div>
<div class="btn_group col-md-6 col-sm-6 col-xs-12 p_l_r_dis">
	<div class="jumbotron sms_total">
	  <h2>isCar模組</h2>
	  <ul>
	  	<li>總數：<span>{{$indexData['mod_num']}}</span>個</li>
	  	<li>前台：<span>{{$indexData['mod_front']}}</span>個</li>
	  	<li>後台：<span>{{$indexData['mod_back']}}</span>個</li>
	  </ul>
	  <p><a href="/member/moduledata" class="btn btn-primary">了解更多</a></p>
	</div>
</div>
<div class="btn_group col-md-6 col-sm-6 col-xs-12 p_l_r_dis">
	<div class="jumbotron sms_total">
	  <h2>簡訊管理</h2>
	  <ul>
	  	<li>總發送量：<span>{{$indexData['sms_total']}}</span>封</li>
	  	<li>當月發送量：<span>{{$indexData['sms_num_this']}}</span>封
				<ul>
					<li>成功發送<span>{{$indexData['sms_success_this']}}</span>封</li>
					<li>失敗發送<span>{{$indexData['sms_fail_this']}}</span>封</li>
				</ul>
	  	</li>
	  	<li>上月發送量：<span>{{$indexData['sms_num_last']}}</span>封
				<ul>
					<li>成功發送<span>{{$indexData['sms_success_last']}}</span>封</li>
					<li>失敗發送<span>{{$indexData['sms_fail_last']}}</span>封</li>
				</ul>
	  	</li>
	  </ul>
	  <p><a href="/member/smsdata" class="btn btn-primary">了解更多</a></p>
	</div>
</div>
@endsection
@section("content_footer")
<div class="col-md-12 col-sm-12 col-xs-12 p_l_r_dis">

</div>
@endsection