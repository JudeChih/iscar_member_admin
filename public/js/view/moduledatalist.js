$(function(){

// 設定查詢框的值
var is = $('.search_form').find('input[name=query_functiontype]').val();
$('.functiontype select option').each(function(){
	if($(this).val() == is){
		$(this).prop('selected', true);
	}
})

// 查詢按鈕
$('.mdl_search').on('click',function(){
	$('.functiontype select option').each(function(){
		if($(this).prop('selected')){
			$('.search_form').find('input[name=query_functiontype]').val($(this).val());
		}
	});
	// 查詢欄為空白或是沒輸入，都給它空字串
	if($.trim($('.search_form').find('input[name=query_module_name]').val()).length === 0){
		$('.search_form').find('input[name=query_module_name]').val('');
	}
	$('.search_form').submit();
})

// 修改的按鈕
$('.mdl_modify').on('click', function() {
	var num = 0;
	var boolean = true;
	$('.panel_form form.select_case').each(function(){
		num = num + 1;
	});
	if(num > 1){
		boolean = false;
	}
  if ($('.panel_form form').hasClass('select_case') && boolean) {
    $('.panel_form form.select_case').submit();
  } else if(num > 1){
  	alert('只能選擇一筆資料進行修改');
  }else {
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

// 停用啟用的按鈕
$('.mdl_flag').on('click',function(){
	if ($('.panel_form form').hasClass('select_case')) {
		var flag = [];
		$('.panel_form form').each(function(){
			if($(this).hasClass('select_case')){
				var mapr_serno = $(this).find("input[name=mapr_serno]").val();
				var isflag = $(this).find("input[name=isflag]").val();
				var json = {};
				json['mapr_serno'] = mapr_serno;
				json['isflag'] = isflag;
				flag.push(json);
			}
		});
		var token = $("input[name='_token']").val();
		$.ajax({
			url: '/member/moduledata',
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