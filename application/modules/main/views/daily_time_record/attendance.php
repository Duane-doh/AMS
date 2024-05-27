<?php

	if(isset($office_list))
	{
		$scopes = array('filter' => 'offices', 'office_list' => $office_list);
	}
	else
	{
		$scopes = array('filter' => 'users', 'user_list' => $user_list);
	}
	

?>

<section id="content" class="p-t-n m-t-n ">
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s6 m6 l6">
            <h5 class="breadcrumbs-title"> Employee Attendance </h5>
            <ol class="breadcrumb m-n p-b-sm">
               <?php get_breadcrumbs(); ?>
            </ol>
          </div>
			<div class="col s6 m6 l6 right-align">
               <div class="row form-vertical form-styled form-basic">
            		<div class="input-field col s4">
					      <label class="label position-left active">You are currently viewing: </label>
            		</div>
            		<div class="col s8">
            			<!--<select name="F-user_id" class="selectize form-filter" id="office_filter" placeholder="All offices / employees..." onchange="office_filtering()">
            				<option></option>-->
            				<?php 
            					// foreach ($office_list as $key => $value) {
            						// echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
            					// }
								
								switch($scopes['filter'])
								{
									case 'offices':
										echo '<select name="C-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">';
										echo '<option></option>';
										foreach ($office_list as $key => $value)
										{
											echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
										}
										break;
										
									case 'users':
										echo '<select name="F-user_id" class="selectize form-filter" id="office_filter" placeholder="All employees..." onchange="office_filtering()">';
										echo '<option></option>';
										foreach ($scopes['user_list'] as $key => $value)
										{
											echo '<option value="' . $value['user_id'] . '">' . $value['lname'] . ', ' . $value['fname'] . ' ' . $value['mname'] . '</option>';
										}
										break;
								}
            				?>
            			</select>
            		</div>
            	</div>
            </div>

        </div>
      </div>
    </div>
	<!-- davcorrea: START : Date and status filter -->
    <div class="container">
      <div class="section panel p-lg">

	  <div class="form-basic">
        <div class="row"> 
          <div class="col s5">
            <div class="input-field m-t-sm"></div>
          </div>                   
          <div class="col s2 p-r-n">
            <div class="input-field">
              <label for="fltr_dtr_start" class="active">Employee Start Date From</label>
              <input type="text" class="datepicker_start" id="fltr_dtr_start" value="<?php echo $fltr_dtr_start?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_start')"/>
            </div>
          </div>               
          <div class="col s2 p-l-n">
            <div class="input-field">
              <label for="fltr_dtr_end" class="active">Employee Start Date To</label>
              <input type="text" class="datepicker_end" id="fltr_dtr_end" value="<?php echo $fltr_dtr_end?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_end')"/>
            </div>
          </div>
		  <div class="col s2 p-l-n">
            <div class="input-field">
			<select name="C-office_id" class="selectize form-filter"  id="status_filter" >
								<option <?php if($status_filter == 'Y'){ echo "selected";}  ?>  value="Y">ACTIVE</option>
								<option <?php if($status_filter == 'N'){ echo "selected";}  ?> value="N">INACTIVE</option>

								
			</select>
			</div>
          </div>
          <div class="col s1 p-n">
            <div class="input-field p-t-xs">
              <a href="javascript:;" onclick="filter_attendance()" class="btn p-l-sm p-r-xs"><i class="flaticon-search95 "></i></a>
            </div>
          </div>
        </div>
      </div>
	  <!-- davcorrea: date and status filter 10/17/2023 : END -->
      	<div class="pre-datatable filter-left"></div>
			<div>
			  	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="attendance_table_list">
				  	<thead>
					<tr>
						<th width="20%">Employee Number</th>
						<th width="20%">Employee Name</th>
						<th width="35%">Office</th>
						<th width="15%">Employment Status</th>
						<th width="10%">Actions</th>
					</tr>
					<tr class="table-filters">
						<td><input name="A-agency_employee_id" class="form-filter"></td>
						<td><input name="fullname" class="form-filter"></td>
						
						<!--marvin
						change name of input-->
						<td><input name="E-name" class="form-filter"></td>
						
						<!--<td><input name="C-office_name" class="form-filter"></td>-->
						<td><input name="D-employment_status_name" class="form-filter"></td>
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
</section>
<script type="text/javascript">
  // davcorrea : include date range filter : start
  function filter_attendance() {
    var date_from = $('#fltr_dtr_start').val();
    var date_to   = $('#fltr_dtr_end').val(); 
	var status_filter   = $('#status_filter').val()
    
    if(date_from != "" && date_to != "")
    {
      window.location.href = "<?php echo base_url() . PROJECT_MAIN . '/daily_time_record/index/'; ?>" + encodeURIComponent(btoa(date_from)) + "/" + encodeURIComponent(btoa(date_to)) + "/" + encodeURIComponent(btoa(status_filter));
    }
  }

  // davcorrea : include date range filter : end
</script>