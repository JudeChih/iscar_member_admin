<?php
	$title = "登入";
?>
@extends('login/__login_index')
@section('content')
	<div class="container login_page">
		<form class="form_signin" action="/login" method="post">
			{!! csrf_field() !!}
			<h1 class="form_signin_heading">isCar_Member 後台</h1>
			<label for="ud_account" class="sr-only">Username</label>
			<input type="text" id="ud_account" class="form-control" name="ud_account" placeholder="使用者名稱" required="" autofocus="">
			<label for="ud_pwd" class="sr-only">Userpwd</label>
			<input type="password" id="ud_pwd" class="form-control" name="ud_pwd" placeholder="使用者密碼" required="" autofocus="">
			<div class="error_box">
			@if($errors->any())
				<p>{{$errors->first()}}</p>
			@endif
			</div>
			<button class="btn btn-block btn_login" type="submit">登入</button>
		</form>
	</div>

@endsection