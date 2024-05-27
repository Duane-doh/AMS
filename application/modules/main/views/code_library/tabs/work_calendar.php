<div class="col s12 m-b-md">
	<?php if($this->permission->check_permission(MODULE_TA_WORK_CALENDAR, ACTION_ADD)) :?>
	  	<button class="btn btn-success  md-trigger green pull-right" data-modal="modal_work_calendar" onclick="modal_work_calendar_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add Event</a></button>
	<?php endif; ?>
</div>
<div class="panel col l12 s12">
	<div class="section panel p-sm p-t-n">
		<!--start section-->
		<div id="article_tab_teal">
			<div class=" row teal">				
				<div class="col s12 ">
					<ul class="tabs teal">
						<li class="tab col s4 active"><a data-mode="calendar" data-toggle="tab" href="#calendar">CALENDAR VIEW</a></li>
						<li class="tab col s5"><a data-mode="list" data-toggle="tab" href="#calendar_list" >LIST VIEW</a></li>
					</ul>
				</div>
				

				<div id="tab_content2" class="panel col s12 p-b-xs p-t-lg">
					<div id='calendar'></div>
					<div id='calendar_list'>
						<div class="col l12 m5 s7 right-align m-b-n-l-lg">
						<div class="pre-datatable filter-left"></div>
						<div>
							<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="work_calendar_table">
								<thead>
									<tr>
										<th width="25%">Event Title</th>
										<th width="25%">Description</th>
										<th width="25%">Date</th>
										<th width="2%">Actions</th>
									</tr>
									<tr class="table-filters">
										<td><input name="title" class="form-filter"></td>
										<td><input name="description" class="form-filter"></td>
										<td><input name="holiday_date" class="form-filter"></td>
										<td class="table-actions">
											<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
											<a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
										</td>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end section-->              
	</div>
</div>




<script>
$(document).ready(function() {
	/*$.ajax({
		 method: "GET",
		 url: "<?php echo base_url(); ?>main/code_library/get_work_calendar_info",
		 dataType: "json",
		 async: false,
		 success: function(response) {
		 	events_data = response
		 }
	});*/

	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '<?php echo date('Y-m-d') ?>',
			selectable: false,
			selectHelper: true,
			select: function(start, end) {
				var title = prompt('Event Title:');
				var eventData;
					if (title) {
						eventData = {
							title: title,
							start: start,
							end: end
						};
						$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
					}
					$('#calendar').fullCalendar('unselect');
				},
		editable: false,
		eventDrop: function(event, delta, revertFunc) {
			console.log(event)
			$.ajax({
				 method: "POST",
				 url: "<?php echo base_url(); ?>main/code_library_ta/work_calendar/update_work_calendar_info",
				 dataType: "json",
				 data: {
				 	id: event.id,
				 	title: event.title,
				 	holiday_date: event.start.format()
				 }
			});

	    },
		eventLimit: true, // allow "more" link when too many events
		events: "<?php echo base_url(); ?>main/code_library_ta/work_calendar/get_work_calendar_info"
	});

	//Bind tabs
	$('#article_tab_teal').find('.tabs > li > a').click(function(event){
		event.preventDefault();
		var mode = $(this).data('mode');

		if (mode == 'calendar') {
			//re-fetch calendar
			$('#calendar').fullCalendar('refetchEvents');
		} else {
			//load list work sched
			load_datatable('work_calendar_table', '<?php echo PROJECT_MAIN ?>/code_library_ta/work_calendar/get_work_calendar_list',false,0,0,true);
		}
		$('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');
		$('.tabs > li > a').removeClass('active');
		$(this).addClass('active');
	}); 


});

</script>