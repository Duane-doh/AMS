$(function(){
	var $base_url = $("#base_url").val();
	alert($base_url);
		$home_page = $("#home_page").val();
		$login = $("#login_form");
		$logout = $("#logout");		

	$login.submit(function(event){
		var data = $("#login_form").serialize();
		event.preventDefault();
	
		button_loader('submit_login', 1);
		$.post($base_url + "auth/sign_in/", data, function(result) {
			alert($base_url);
			if(result.flag == 0){			
				Materialize.toast(result.msg, 3000, '', function(){
					button_loader('submit_login', 0);
				});
			} else {
				window.location = $base_url + $home_page;
			}			  						
		}, 'json');	
	});
	
	$logout.on("click", function(){
		$.post($base_url + "auth/sign_out/", function(result) {
			if(result.flag == 0){
				Materialize.toast(result.msg, 3000);
			}
			else
			{
				window.location = $base_url;
			}	
		}, 'json');
	});
	
});