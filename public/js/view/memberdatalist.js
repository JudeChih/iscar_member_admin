$(function(){

// 遮蔽重要訊息
$('.mdl_replace').each(function(){
	var string = $(this).text();
	string = setCharAt(string,1,'*');
	$(this).text(string);
})

function setCharAt(str,index,chr) {
	var string = '';
  if(index > str.length-1) return str;
  if(str.length > 3){
  	for(var i=0;i<str.length-2;i++){
  		string = string + chr;
  	}
  	return str.substr(0,1) + string + str.substr(str.length-1);
  }
  return str.substr(0,index) + chr + str.substr(index+1);
}
// 查詢會員類別變更 ex 一般會員、特約商、品牌商...等等
$('.client_type select').change(function(){
	var aa = $(this).prop('selected',true).val();
	$('.search_form').find('input[name=client_type]').val(aa);
})

// 查詢選項變更 ex 名稱、信箱、電話、帳號
$('.md_search select').change(function(){
	var aa = $(this).prop('selected',true).val();
	$('.search_form').find('input[name=search_type]').val(aa);
	if(aa == 'md_cname'){
		$('.md_search input').prop('placeholder','查詢使用者名稱');
	}else if(aa == 'md_account'){
		$('.md_search input').prop('placeholder','查詢使用者帳號');
	}else if(aa == 'md_contactmail'){
		$('.md_search input').prop('placeholder','查詢使用者信箱');
	}else if(aa == 'md_mobile'){
		$('.md_search input').prop('placeholder','查詢使用者手機');
	}
})



// 設定查詢框的值
var type = $('.search_form').find('input[name=search_type]').val();
$('.md_search select option').each(function(){
	var aa = $(this).val();
	if(aa == type){
		$(this).prop('selected',true);
		if(type == 'md_cname'){
			$('.md_search input').prop('placeholder','查詢使用者名稱');
		}else if(aa == 'md_account'){
			$('.md_search input').prop('placeholder','查詢使用者帳號');
		}else if(aa == 'md_contactmail'){
			$('.md_search input').prop('placeholder','查詢使用者信箱');
		}else if(aa == 'md_mobile'){
			$('.md_search input').prop('placeholder','查詢使用者手機');
		}
	}
})
var client = $('.search_form').find('input[name=client_type]').val();
$('.client_type select option').each(function(){
	var aa = $(this).val();
	if(aa == client){
		$(this).prop('selected',true);
	}
})
var is = $('.search_form').find('input[name=isflag]').val();
$('.isflag select option').each(function(){
	if($(this).val() == is){
		$(this).prop('selected', true);
	}
})
var option = $('.radio').find('input[name=query_status]').val();
$('.radio div').eq(option).find('input[name=mdl]').prop('checked',true);

// 查詢按鈕
$('.mdl_search').on('click',function(){
	$('.isflag select option').each(function(){
		if($(this).prop('selected')){
			$('.search_form').find('input[name=isflag]').val($(this).val());
		}
	});

	$('.radio div').each(function(){
		if($(this).find('input[type=radio]').prop('checked')){
			var val = $(this).find('input[type=radio]').val();
			$('.radio').find('input[name=query_status]').val(val);
			var day = $(this).find('input[type=text]').val();
			$('.radio').find('input[name=query_day]').val(day);
		}
	})

	$('.search_form').submit();
})

// 停用啟用的按鈕
$('.mdl_flag').on('click',function(){
	if ($('.panel_form form').hasClass('select_case')) {
		var flag = [];
		$('.panel_form form').each(function(){
			if($(this).hasClass('select_case')){
				var md_id = $(this).find("input[name=md_id]").val();
				var isflag = $(this).find("input[name=isflag]").val();
				var json = {};
				json['md_id'] = md_id;
				json['isflag'] = isflag;
				flag.push(json);
			}
		});
		var token = $("input[name='_token']").val();
		$.ajax({
			url: '/member/memberdata',
			type:'POST',
			cache:false,
			datatype:'json',
			data: {_token: token,isflag: flag},
			beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
			success: function(data){
				$('.prompt_body_b h2').text(data);
				$('.prompt_body_b').fadeIn(400);
			},
			error: function(){
				console.log('error');
			}
		})
  } else {
    alert('請選擇任一筆資料');
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




})