$(function(){
// 修改、新增、刪除之後在頁面會跳出的小視窗，確認的按鈕
$('.btn_yes').on('click', function() {
  $('.prompt_body').fadeOut(400, function() {
    $('.prompt_body').remove();
  });
})

var status = $('.radio').find('[name=query_status]').val();
$('.radio').find('label').each(function(){
	var val = $(this).find('[name=radio]').val();
	if(status == val){
		$(this).find('[name=radio]').prop('checked',true);
	}
})

// 刪除按鈕
$('.udl_delete').on('click',function(){
  if ($('.panel_form form').hasClass('select_case')) {
  	$('.panel_form').find('form.select_case').prop('action', '/login/user-list-delete');
    $('.panel_form').find('form.select_case').submit();
  } else {
    alert('請選擇任一筆資料');
  }
})

// 查詢按鈕
$('.udl_search').on('click',function(){
	$('.search_form .radio').find('input[type=radio]').each(function(){
		if($(this).prop('checked')){
			$('input[name=query_status]').val($(this).val());
		}
	});
	$('.search_form').submit();
});

// 修改按鈕
$('.udl_edit').on('click',function(){
	if ($('.panel_form form').hasClass('select_case')) {
    $('.panel_form').find('form.select_case').submit();
  } else {
    alert('請選擇任一筆資料');
  }
})

// 選擇一筆資料的highlight
$('.panel_form form').on('click', function() {
  if ($(this).hasClass('select_case')) {
    $(this).removeClass('select_case')
  } else {
    $(this).siblings('form').removeClass('select_case');
    $(this).addClass('select_case');
  }
})
})