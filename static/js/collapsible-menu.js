/* CUSTOMIZED ACCORDION MENU FUNCTION
** Used accordion menu using materialize collapsible function
*/

// FUNCTION FOR COLLAPSIBLE / SLIDING MENU
var wrapper = $("#wrapper");
if(wrapper.hasClass("slide-nav")){
	
	$(".slide-nav .side-nav").hover(function(){
	  
		if($("#wrapper").hasClass("has-sub-nav")){ // If page has two-column menu
			$(".slide-nav").css("padding-left","240px"); // add 200
		}else{
			$(".slide-nav").css("padding-left","240px");
		}
		$(".slide-nav").css("transition","1s");

		if($(".menu li").hasClass("active")){
			$(".menu .active").find(".collapsible-body").css('display','block');
		}

		$("#content header").css("width", "calc(100% - 240px)").css("transition","1s");
		collapsible_arrow();
	}, function() {
	  
		if($("#wrapper").hasClass("has-sub-nav")){
			$(".slide-nav").css("padding-left","90px"); // add 200
		}else{
			$(".slide-nav").css("padding-left","90px");
		}
		$(".slide-nav").css("transition","0s");				
		$("#content header").css("width", "calc(100% - 90px)").css("transition","0s");
		$(".menu").find(".collapsible-body").css('display','none');
		$(".menu li").find(".collapsible-header").removeClass("flaticon-down95 flaticon-arrow621");
	});
	
}else{
	collapsible_arrow();
}

function collapsible_arrow(){
  $(".menu li").each(function() {
	var elem = $(this);

	if(elem.find('.collapsible-body').length){
		var active_arrow = elem.hasClass("active") ? "flaticon-down95" : "flaticon-arrow621";
		var prev_arrow = elem.hasClass("active") ? "flaticon-arrow621" : "flaticon-down95";
	
		elem.find(".collapsible-header").addClass(active_arrow).removeClass(prev_arrow);
	}
  });
}

// END COLLAPSIBLE / SLIDING MENU