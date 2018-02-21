<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta id="token" name="token" content="{{ csrf_token() }}">
	<title>{{ isset($title) ? $title.' | ' : '首頁 | ' }}isCar_Admin</title>

	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/css/sass/stylesheets/style.css">

	<script type="text/javascript" src="/js/plugin/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="/js/plugin/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/plugin/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="/js/plugin/bootstrap-datetimepicker.zh-TW.js"></script>
	<script type="text/javascript" src="/js/plugin/select2.min.js"></script>
	<script type="text/javascript" src="/js/general.js"></script>
</head>
<body>

	@yield('content_tip')
	<nav class="navbar navbar-default" role="navigation">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="/index"><img alt="Brand" src="/images/iscar_icon1.png" style="width:50px;"></a>
	    </div>
	    <div class="collapse navbar-collapse a_href" id="bs-example-navbar-collapse-1">
	    	<ul class="nav navbar-nav nav_menu">
	    		<li><a href="/member/memberdata">會員管理</a></li>
	    	</ul>
	    	<ul class="nav navbar-nav nav_menu">
	    		<li><a href="/member/smsdata">簡訊管理</a></li>
	    	</ul>
	    	<ul class="nav navbar-nav nav_menu">
	    		<li><a href="/member/moduledata">模組管理</a></li>
	    	</ul>
	    	{{-- <ul class="nav navbar-nav nav_menu">
	    		<li><a href="/member/downloaddata">下載專區</a></li>
	    	</ul> --}}
	    	<ul class="nav navbar-nav nav_menu">
	    		<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">測試工具 <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="/testtools/testpushnotification">測試推播</a></li>
                <li><a href="/testtools/testkotsms">測試簡訊王</a></li>
                <li><a href="/testtools/testmitakesms">測試三竹</a></li>
            </ul>
        	</li>
	    	</ul>
	    	<ul class="nav navbar-nav nav_menu">
	    		<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">後台管理 <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="/login/user-modify">修改資料</a></li>
                @if(!empty(Session::get('admin')))
                	@if(Session::get('admin') == 1)
                		<li><a href="/login/user-list">使用者列表</a></li>
                	@endif
                @endif
            </ul>
        	</li>
	    	</ul>
	      <ul class="nav navbar-nav navbar-right">
					<li><a href="/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> 登出</a></li>
	      </ul>
	      @if(!empty(Session::get('user_name')))
		      <ul class="nav navbar-nav navbar-right">
		        <li><a><i class="fa fa-user" aria-hidden="true"></i> {{ Session::get('user_name') }}</a></li>
		      </ul>
		    @endif
	    </div>
	  </div>
	</nav>
	<div class="container-fluid">
    <div class="row">
			<div class="panel panel-primary col-md-10 col-sm-12 col-md-offset-1 col-xs-12 p_l_r_dis">
			  <div class="panel-heading col-md-12 col-sm-12 col-xs-12">
			    <h1><?= isset($title) ? $title : '' ?></h1>
			  </div>
			  <div class="panel-body p_all_dis col-md-12 col-sm-12 col-xs-12">
			    @yield('content_body')
			  </div>
  			<div class="panel-footer panel-primary col-md-12 col-sm-12 col-xs-12">
  				@yield('content_footer')
  			</div>
			</div>
    </div>
	</div>
	<footer>
		<div>Copyright © 2017 就是行國際科技有限公司</div>
	</footer>
@if($errors->any())
  <div class="prompt_body">
    <div class="prompt_box panel-primary">
  		<div class="panel-heading">
      	<h3>提示框</h3>
      </div>
      <h2>{{$errors->first()}}</h2>
      <button type="button" class="btn btn-primary btn_yes">確認</button>
    </div>
  </div>
@endif
</body>
</html>