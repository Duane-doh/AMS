
var Calendar 	= function() 
{
	
	var full_cal = function()
	{
		$('.fullcalendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			defaultDate: '2016-05-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
				{
					title: 'All Day Event',
					start: '2016-05-01'
				},
				{
					title: 'Long Event',
					start: '2016-05-07',
					end: '2016-05-10'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2016-05-09T16:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2016-05-16T16:00:00'
				},
				{
					title: 'Conference',
					start: '2016-05-11',
					end: '2016-05-13'
				},
				{
					title: 'Meeting',
					start: '2016-05-12T10:30:00',
					end: '2016-05-12T12:30:00'
				},
				{
					title: 'Lunch',
					start: '2016-05-12T12:00:00'
				},
				{
					title: 'Meeting',
					start: '2016-05-12T14:30:00'
				},
				{
					title: 'Happy Hour',
					start: '2016-05-12T17:30:00'
				},
				{
					title: 'Dinner',
					start: '2016-05-12T20:00:00'
				},
				{
					title: 'Birthday Party',
					start: '2016-05-13T07:00:00'
				},
				{
					title: 'Click for Google',
					url: 'http://google.com/',
					start: '2016-05-28'
				}
			]		
		});
	}

	var cal_list  = function()
	{
		$("#div_list_view").hide();
		$("button[name='view']").on("click", function(){
		  var other_one = $(this).attr("id") == "cal_view" ? "list_view" : "cal_view";
			if(!$(this).hasClass("grey"))
			{
				$("#" + other_one).removeClass("grey");
				$("#div_" + other_one).hide();
				$(this).addClass("grey");
				$("#div_" + $(this).attr("id")).show();
			}
		});
	}

	var upload 	= function()
	{

		var options 	= {
			url 			: $base_url + "upload/",
			fileName 		: "file",
			allowedTypes 	: "jpeg,jpg,png,gif",
			acceptFiles 	: "*",
			dragDrop 		: false,
    		multiple		: false,
    		maxFileCount  	: 1,
    		allowDuplicates : true,
			duplicateStrict : false,
			showDone 		: false,
			showAbort 		: false,
			showProgress    : false,
			showPreview     : false,
			returnType      : "json",
			formData        : { "dir": PATH_USER_UPLOADS },
			uploadFolder    : $base_url + PATH_USER_UPLOADS,
			onSelect 		: function(files) {
 			 	$(".avatar-wrapper .ajax-file-upload").hide();
			},
			onSuccess 		: function(files,data,xhr) {
			  var avatar = $base_url + PATH_USER_UPLOADS + data;
			  $("#profile_img").attr("src", avatar);

			  $('#user_image').val(data);
			  $('.avatar-wrapper .ajax-file-upload-progress').hide();
			  $(".avatar-wrapper .ajax-file-upload-red").html("<i class='flaticon-recycle69'></i>");
			},
			showDelete      : true,
			deleteCallback  : function(data,pd)
			{
				  for(var i=0;i<data.length;i++)
				  {
				    	$.post($base_url + "upload/delete/",{ op: "delete", name:data[i], dir:PATH_USER_UPLOADS },

					    function(resp, textStatus, jqXHR)
					    {
						      $(".avatar-wrapper .ajax-file-upload-error").fadeOut();
						      $('#user_image').val('');

						      var avatar = $base_url + PATH_IMAGES + "avatar.jpg";
						      $("#profile_img").attr("src", avatar);
					    });
			   	  }

				  pd.statusbar.hide();

				  $(".avatar-wrapper .ajax-file-upload").css("display","");

			},
			onLoad           : function(obj)
			{
			  	$.ajax({
				    cache 	 	: true,
				    url 		: $base_url + "upload/existing_files/",
				    dataType 	: "json",
				    data 		: { dir: PATH_USER_UPLOADS, file: $('#user_image').val() } ,
				    success 	: function(data)
				    {
						for(var i=0;i<data.length;i++)
						{
							obj.createProgress(data[i]);
						}

				      	if(data.length > 0){
					        $(".avatar-wrapper .ajax-file-upload").hide();
					        $('.avatar-wrapper .ajax-file-upload-progress').hide();
					        $(".avatar-wrapper .ajax-file-upload-red").html("<i class='flaticon-recycle69'></i>");
				      	}else{
					        var avatar = $base_url + PATH_IMAGES + "avatar.jpg";
					        $("#profile_img").attr("src", avatar);
				      	}
				    }
			  	});
			}
		};

		var uploadObj 	= $( "#profile_photo" ).uploadFile( options );
	}

	return {

		full_cal : function()
		{
			full_cal();
		},
		cal_list : function()
		{
			cal_list();
		},
		upload    : function()
		{
			upload();
		}

	}

}();
