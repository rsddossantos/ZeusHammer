$(function () {
	$('[data-toggle="tooltip"]').tooltip();
	$('.dropdown').hover(function(){
		$(this).addClass('fundomenu');
			}, function(){
			$(this).removeClass('fundomenu');
			});	
	$('.panel-body').hover(function(){
		$(this).addClass('fundopanel');
			}, function(){
			$(this).removeClass('fundopanel');
			});
	if ($('.active,table').is(':visible')) {
		$('html, body').animate({scrollTop: $('#5').offset().top}, 1000);
	}
	
	$('.tabs').bind('click',function(){
			$('html, body').animate({scrollTop: $('#5').offset().top}, 1000);
		});			
});
function voltar() {
	window.history.back()
}


