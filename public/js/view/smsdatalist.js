$(function(){

// datetimepicker的賦予
$('#query_time_from').datetimepicker({
	format: 'yyyy-mm-dd',
	language: 'zh-TW',
	weekStart: 7,
	todayBtn: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 3,
	minView: 2,
	forceParse: 0
});
$('#query_time_to').datetimepicker({
	format: 'yyyy-mm-dd',
	language: 'zh-TW',
	weekStart: 7,
	todayBtn: 1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 3,
	minView: 2,
	forceParse: 0
});

// 查詢按鈕
$('.sdl_search').on('click',function(){
	var isFormValid = true;
	if(isFormValid){
		if($('input[name=query_time_from]').val() != '' && $('input[name=query_time_to]').val() != ''){
			isFormValid = checkDate();
	   	if(!isFormValid){
	   		alert("結束日不能早於開始日")
	   		$('input[name=query_time_from]').val('');
				$('input[name=query_time_to]').val('');
	   	}
		}
  }
  if(isFormValid){
  	$('.search_form').submit();
  }
})

// 選擇一筆資料的highlight
$('.panel_form form').on('click', function() {
  if ($(this).hasClass('select_case')) {
    $(this).removeClass('select_case')
  } else {
    $(this).addClass('select_case');
  }
})

$('.prompt_body_b .btn_yes').on('click',function(){
	$('.prompt_body_b').fadeOut(400);
})

$('.prompt_body_b .btn_no').on('click',function(){
	$('.prompt_body_b').fadeOut(400);
})

$('.prompt_body .btn_yes').on('click',function(){
	$('.prompt_body').fadeOut(400);
	$('.prompt_body').remove();
})

// 檢查起始日是否小於結束日
function checkDate(){
	var isFormValid = true;
	// 以下s開頭代表start、e開頭代表end
	var s = $('input[name=query_time_from]').val();
	var e = $('input[name=query_time_to]').val();

	// 開始日期切割
	s_date = s.split("-");

	// 結束日切割
	e_date = e.split("-");

	// 開始日年月日時分秒
	s_year = s_date[0];
	s_month = s_date[1];
	s_day = s_date[2];
	// 結束日年月日時分秒
	e_year = e_date[0];
	e_month = e_date[1];
	e_day = e_date[2];
	// 開始判斷
	if(s_year > e_year){
		isFormValid = false;
	}else if(s_year == e_year && s_month > e_month){
		console.log(123456789);
		isFormValid = false;
	}else if(s_year == e_year && s_month == e_month && s_day > e_day){
		isFormValid = false;
	}
	return isFormValid;
}
})