    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Employee Attendnace </h5>
                <ol class="breadcrumb m-n p-b-sm">
                   <?php get_breadcrumbs();?>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        
        <!--start container-->
        <div class="container">
          <div class="section panel p-sm">
      <!--start section-->

      		<ul class="collapsible panel m-t-lg" data-collapsible="expandable">
			  <li>
				<div class="collapsible-header lighten-1">Attendance</div>

				<div class="collapsible-body" style="display: block !important; ">
				  <form id="attendance_form" name="user_form" class="form-vertical form-styled  m-t-lg" autocomplete="off">
				    <div class="form-basic">
					  <div class="row m-b-n">
					    <div class="col s11 m-l-lg">
					     <div class="row m-b-lg">
							<div class="col s2">
								<input type="radio" class="labelauty" name="filter_type"  value="1" data-labelauty="All Employees" checked/>
							</div>	
							 <div class="col s3">
								<input type="radio" class="labelauty" name="filter_type"  value="2" data-labelauty="Filter by Criteria"/>
							 </div>	
							  <div class="col s7">
								<button type="button" class="btn btn-success pull-right btn-medium" id = "add_more_btn"><i class="flaticon-add175"></i>Add Filter</button>
							 </div>	
						 </div>
					   	 <div class="row m-b-n" id="filter_criteria">
					   	 	<div class="col s6">
			 				  <div class="input-field">
			 				  	<label for="criteria_selected" class="active">Filter By</label>
			 				  	<select id="criteria_selected" name="criteria_selected" class="selectize" placeholder="Select Criteria" required>
									<option value="">Select Criteria</option>
									<option value="1">Employee No</option>
									<option value="2">Employee Name</option>
									<!-- <option value="3">Office</option>
									<option value="4">Position</option> -->
								 </select>
							  </div>
						    </div>
						    <div class="col s6">
							  <div class="input-field">
							  	<input type="text" name="criteria_value" id="criteria_value" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" />
							  	<label for="criteria_value" class="active">Filter value</label>
							  </div>
						    </div>
						  </div>
						  <div class="row m-b-lg">
					   	 	<div class="col s6">
			 				  <div class="input-field">
							    <input type="text" name="date_from" id="date_from" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="" class="datepicker" />
							    <label for="date_from">Period From</label>
							  </div>
						    </div>
						    <div class="col s6">
							  <div class="input-field">
							    <input type="text" name="date_to" id="date_to" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="" class="datepicker" />
							    <label for="date_to">Period To</label>
							  </div>
						    </div>
						  </div>
						   <div class="col s12 right-align p-r-n">
					    	<button class="btn btn-success  pull-right btn-medium" id="search_attendance" type="button"><i class="flaticon-search95"></i>Search</button>
					    </div>
					    </div>
					    <div class="col s1">&nbsp;</div>

					  </div>
					  <div class="row m-b-n m-l-lg">
					  	
					  </div>
					</div>
				  </form>
				</div>
			  </li>
			</ul>

      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->
<script type="text/javascript">
$(function(){	
	$("#search_attendance").on("click", function(){
		$("#attendance_div").removeClass("hide");
	});
	
    $('input[name="filter_type"]').off('change');
	$('input[name="filter_type"]').on('change', function(e) {
		var selected = $('input[name="filter_type"]:checked').val();
	    if(selected === "2"){
	    	$('#filter_criteria').removeClass('none');
	    	$('#add_more_btn').removeClass('none');
	    }
	    else{
	    	$('#filter_criteria').addClass('none');
	    	$('#add_more_btn').addClass('none');
	    }
	});

	$("#search_attendance").on('click', function (){
		// window.location = $base_url + 'main/daily_time_record/attendance/';
		$('#attendance_form').trigger('submit');
	});

	$('#attendance_form').parsley();
	$('#attendance_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('search_attendance', 1);
		  	window.location = $base_url + 'main/daily_time_record/attendance/?'+ data;
		 //  	var option = {
			// 		url  : $base_url + 'main/daily_time_record/attendance/',
			// 		data : data,
			// 		success : function(result){
			// 			if(result.status)
			// 			{
							
			// 			}
			// 			else
			// 			{
			// 				notification_msg("<?php echo ERROR ?>", result.msg);
			// 			}	
						
			// 		},
					
			// 		complete : function(jqXHR){
			// 			button_loader('search_attendance', 0);
			// 		}
			// };

			// // General.ajax(option);    
	  //   }
  	}
  	});
});
</script>
