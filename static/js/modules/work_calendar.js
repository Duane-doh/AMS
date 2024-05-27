$(document).ready(function(){	
	var base_url = $('#base_url').val();


		
		var cal_defaultDate = "";
		var cal_editable = "";
		var cal_events = new Array();
		if($( ".fc-agendaWeek-button" ).hasClass('fc-state-active'))
		{
			var data	= $('#courses_form').serializeArray();
			//data		= JSON.stringify(data);
			var link	= base_url + "tis/schedules/get_calendar_week_data";
			$.post(link, data, function(result){
				
				
				result	= JSON.parse(result);
				cal_defaultDate	= JSON.stringify(result.cal_data.cal_defaultDate);
				cal_editable	= JSON.stringify(result.cal_data.cal_editable);
				cal_events	= result.cal_data.cal_events;
				$('.fullcalendar').fullCalendar('removeEvents');
				$('.fullcalendar').fullCalendar('addEventSource',cal_events);
				$('.fullcalendar').fullCalendar({
					eventClick: function(cal_events,jsEvent, view) {
						var event_id = cal_events.id;
						var val_arr				= event_id.split('+');
						
						 var employee			= val_arr[0];
						 var id_modal			= val_arr[1];
						 var id_page			= val_arr[2];
							
						if(employee == 'EMPLOYEE')
						{
							get_page("tis/courses/tabs/" + id_page)
						}
						else
						{
							modal_init('modal_schedule','schedules','tis', id_modal);
							$("#modal_schedule_btn").click();
						}
				    },
				   
				   
				});
			});
		}
		if($( ".fc-month-button" ).hasClass('fc-state-active'))
		{
			var data	= $('#courses_form').serializeArray();
			//data		= JSON.stringify(data);
			var link	= base_url + "tis/schedules/get_calendar_month_data";
			$.post(link, data, function(result){
				
				
				result	= JSON.parse(result);
				cal_defaultDate	= JSON.stringify(result.cal_data.cal_defaultDate);
				cal_editable	= JSON.stringify(result.cal_data.cal_editable);
				cal_events	= result.cal_data.cal_events;
				
				$('.fullcalendar').fullCalendar('removeEvents');
				$('.fullcalendar').fullCalendar('addEventSource',cal_events);
				$('.fullcalendar').fullCalendar({
					eventClick: function(cal_events,jsEvent, view) {
						var event_id = cal_events.id;
						var val_arr				= event_id.split('+');
						
						 var employee			= val_arr[0];
						 var id_modal			= val_arr[1];
						 var id_page			= val_arr[2];
							
						if(employee == 'EMPLOYEE')
						{
							get_page("tis/courses/tabs/" + id_page)
						}
						else
						{
							modal_init('modal_schedule','schedules','tis', id_modal);
							$("#modal_schedule_btn").click();
						}
				    },
				   
				   
				});
			});
		}
	
	//START: CODES FOR CALENDAR
	var cal_defaultDate = "";
	var cal_editable = "";
	var cal_events = new Array();
	var base_url = $('#base_url').val();
	jQuery(document).off('click', '.fc-month-button');
	jQuery(document).on('click', '.fc-month-button', function(e){	
		var data	= $('#courses_form').serializeArray();
		//data		= JSON.stringify(data);
		var link	= base_url + "tis/schedules/get_calendar_month_data";
		$.post(link, data, function(result){
			
			
			result	= JSON.parse(result);
			cal_defaultDate	= JSON.stringify(result.cal_data.cal_defaultDate);
			cal_editable	= JSON.stringify(result.cal_data.cal_editable);
			cal_events	= result.cal_data.cal_events;
			
			$('.fullcalendar').fullCalendar('removeEvents');
			$('.fullcalendar').fullCalendar('addEventSource',cal_events);
			$('.fullcalendar').fullCalendar({
				eventClick: function(cal_events,jsEvent, view) {
					var event_id = cal_events.id;
					var val_arr				= event_id.split('+');
					
					 var employee			= val_arr[0];
					 var id_modal			= val_arr[1];
					 var id_page			= val_arr[2];
						
					if(employee == 'EMPLOYEE')
					{
						get_page("tis/courses/tabs/" + id_page)
					}
					else
					{
						modal_init('modal_schedule','schedules','tis', id_modal);
						$("#modal_schedule_btn").click();
					}
			    },
			   
			   
			});
		});
		
	});
});