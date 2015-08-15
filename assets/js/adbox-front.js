jQuery(function($) {
    fixOnDesktop();
	
	$(window).resize(function(){
		fixOnDesktop();
	});

function fixOnDesktop() {
	var class_details_top = $("#ms_ad_box").offset().top;
	var winWidth = $(window).width();
	
	if(winWidth > 700 ) {
		$(window).scroll(function(){
			$(window).scrollTop()>=class_details_top?$("#ms_ad_box").css("position","fixed").css("top","50px"):$("#ms_ad_box").css("position","").css("top","");
		});
	} 
	if(winWidth <= 700 ) {
		$(window).scroll(function(){
			$("#ms_ad_box").css("position","").css("top","").css("width","");
		});
	}
}
});