<?php
$sel_compensation_id 	= (isset($val['compensation_id']) ? $val['compensation_id'] : NULL);
$sel_bank_id 			= (isset($val['bank_id']) ? $val['bank_id'] : NULL);
$sel_certified_by 		= (isset($val['certified_by']) ? $val['certified_by'] : NULL);
$sel_approved_by 		= (isset($val['approved_by']) ? $val['approved_by'] : NULL);
$sel_certified_cash_by 	= (isset($val['certified_cash_by']) ? $val['certified_cash_by'] : NULL);
$sel_effective_date		= (isset($val['effective_date']) ? $val['effective_date'] : NULL);
$from_dte_yr = date('Y');
$to_dte_yr = date('Y', strtotime("last month"));
$sel_tenure_period_start_date	= (isset($val['tenure_period_start_date']) ? $val['tenure_period_start_date'] : ($from_dte_yr > $to_dte_yr ? date($to_dte_yr.'/01/01') : date('Y/01/01')));
$last_month = ((int)date('m')) - 1;
$last_month_date = 
$sel_tenure_period_end_date		= (isset($val['tenure_period_end_date']) ? $val['tenure_period_end_date'] : date($to_dte_yr.'/m/t'));
$sel_rating_period_start_date	= (isset($val['rating_period_start_date']) ? $val['rating_period_start_date'] : NULL);
$sel_rating_period_end_date		= (isset($val['rating_period_end_date']) ? $val['rating_period_end_date'] : NULL);

$hdn_sel_compensation_id	= NULL;
?>
<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Prepare Special Payroll</h5>
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
          <div class="section panel p-lg">			
				<form id="sp_payroll_form" name="sp_payroll_form" class="form-vertical form-styled  m-t-lg" autocomplete="off">	
				  	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
					<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
					<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
					<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
					<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">		  
				    <div class="form-basic">				    	
					  	<div class="row">
							<div class="col s4">
								<div class="input-field">
								 <label for="compensation_type_id" class="active">Compensation Type<span class="required">*</span></label>
								 <select id="compensation_type_id" name="compensation_type_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Compensation Type">
								 	<option value="">Select Compensation Type</option>
									<?php if (!EMPTY($compensation_types)): ?>
										<?php foreach ($compensation_types as $type): 
											$selected = ($type['compensation_id'] == $sel_compensation_id ? ' selected ' : '');
											if ( ! EMPTY($selected))
												$hdn_sel_compensation_id = $type['compensation_id'] . '|' . $type['tenure_rqmt_flag'] . '|' . $type['monetization_flag'];;
										?>
											<option value="<?php echo $type['compensation_id'] . '|' . $type['tenure_rqmt_flag'] . '|' . $type['monetization_flag']; ?>" <?php echo $selected; ?>><?php echo $type['compensation_name']; ?></option>
										<?php endforeach;?>
									<?php endif;?>
							 	 </select>
							 	 <input type="hidden" name="sp_type_id" id="sp_type_id" value="<?php echo $hdn_sel_compensation_id?>">
								</div>
							</div>
							<div class="col s4">
								<div class="input-field">
								 <label for="bank_id" class="active">Bank<span class="required">*</span></label>
								 <select id="bank_id" name="bank_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Bank">
								 	<option value="">Select Bank</option>
									<?php if (!EMPTY($banks)): ?>
										<?php foreach ($banks as $bank):
											$selected = ($bank['bank_id'] == $sel_bank_id ? ' selected ' : '');
										?>
											<option value="<?php echo $bank['bank_id'] ?>" <?php echo $selected; ?>><?php echo $bank['bank_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
							 	 </select>
								</div>
							</div>
							<div class="col s2">
								<div class="input-field">
									<label for="payout_date" class="active">Payout Date<span class="required">*</span></label>
									<input id="payout_date" name="payout_date" type="text" class="validate datepicker" placeholder="yyyy/mm/dd" value="<?php echo $sel_effective_date; ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,this.id)">
								</div>
							</div>
						</div>
								
						
						<div class="row" id="row_tenure_period">
							<div class="col s2">
								<div class="input-field">
									<label for="tenure_period_from" class="active">Covered Period From<span class="required">*</span></label>
									<input id="tenure_period_from" name="tenure_period_from" type="text" class="validate datepicker_start" placeholder="yyyy/mm/dd" value="<?php echo $sel_tenure_period_start_date; ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,this.id)">
								</div>
							</div>
							<div class="col s2">
								<div class="input-field">
									<label for="tenure_period_to" class="active">Covered Period To<span class="required">*</span></label>
									<input id="tenure_period_to" name="tenure_period_to" type="text" class="validate datepicker_end" placeholder="yyyy/mm/dd" value="<?php echo $sel_tenure_period_end_date; ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,this.id)">
								</div>
							</div>

							<div class="col s2" style="visibility:hidden;">
								<div class="input-field">
									<label for="rating_period_from" class="active">Performance Rating Period From</label>
									<input id="rating_period_from" name="rating_period_from" type="text" class="validate" placeholder="yyyy/mm/dd" value="<?php echo $sel_rating_period_start_date; ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,this.id)">
								</div>
							</div>
							<div class="col s2" style="visibility:hidden;">
								<div class="input-field">
									<label for="rating_period_to" class="active">Performance Rating Period To</label>
									<input id="rating_period_to" name="rating_period_to" type="text" class="validate" placeholder="yyyy/mm/dd" value="<?php echo $sel_rating_period_end_date; ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,this.id)">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col s4">
								<div class="input-field">
								 <label for="certified_correct_by_id" class="active">Certified Correct By</label>
								 <select id="certified_correct_by_id" name="certified_correct_by_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Certified By">
									<option value="">Select Certified By</option>
									<?php if (!EMPTY($personnel_certify)): ?>
										<?php foreach ($personnel_certify as $personnel): 
											$selected = ($personnel['employee_id'] == $sel_certified_by ? ' selected ' : '');
										?>
											<option value="<?php echo $personnel['employee_id'] ?>" <?php echo $selected; ?>><?php echo $personnel['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
							 	 </select>
								</div>
							</div>
							<div class="col s4">
								<div class="input-field">
								 <label for="approved_by_id" class="active">Approved By</label>
								 <select id="approved_by_id" name="approved_by_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Approved By">
								 	<option value="">Select Approved By</option>
									<?php if (!EMPTY($personnel_approvers)): ?>
										<?php foreach ($personnel_approvers as $personnel): 
											$selected = ($personnel['employee_id'] == $sel_approved_by ? ' selected ' : '');
										?>
											<option value="<?php echo $personnel['employee_id'] ?>" <?php echo $selected; ?>><?php echo $personnel['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
							 	 </select>
								</div>
							</div>
							<div class="col s4">
								<div class="input-field">
								 <label for="certified_cash_by_id" class="active">Certified Cash Available By</label>
								 <select id="certified_cash_by_id" name="certified_cash_by_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Certified Cash Available By">
									<option value="">Select Certified Cash Available By</option>
									<?php if (!EMPTY($personnel_ca_certify)): ?>
										<?php foreach ($personnel_ca_certify as $personnel): 
											$selected = ($personnel['employee_id'] == $sel_certified_cash_by ? ' selected ' : '');
										?>
											<option value="<?php echo $personnel['employee_id'] ?>" <?php echo $selected; ?>><?php echo $personnel['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
							 	 </select>
								</div>
							</div>
						</div>			  
				    </div>
				    <div class="panel-footer right-align">
					  <a href="<?php echo base_url() . PROJECT_MAIN ?>/special_payroll" class="waves-effect waves-teal btn-flat">Cancel</a>
					  <?php if($action != ACTION_VIEW AND $this->permission->check_permission( (!EMPTY($module) ? $module : NULL),  (!EMPTY($action) ? $action : NULL)) ):?>
					    <button id="sp_payroll_report" type="button"class="btn btn-success " value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
					  <?php endif; ?>
					</div>
				  </form>     
				  <hr>
				  
				  <form id="payroll_employee_form" name="payroll_employee_form" class="form-vertical form-styled  m-t-lg" autocomplete="off">
				    <!--start container-->
				    
	                <div class="col s6 m6 l6 right-align">
	                	<div class="row form-vertical form-styled form-basic">
	                		<div class="col s2">
	                		<?php if($action == ACTION_ADD) 
	                		{?>
	                			&nbsp;
	                		<?php } else { ?>
	                		
							      <label class="label position-left active">Number of Included Employees: </label>
							      <label id="display_employee_count" class="label position-center font-xl">0</label>
	                		
	                		<?php } ?>
	                		</div>
	                		<div class="col s2 right-align">
							      <label class="label position-left">You are currently viewing: </label>
	                		</div>
	                		<div class="col s8">
	                			<select name="e-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
	                				<option></option>
	                				<?php 
	                					foreach ($office_list as $key => $value) {
	                						echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
	                					}
	                				?>
	                			</select>
	                		</div>
	                	</div>
	                </div>				    
				    
					<div class="pre-datatable filter-left"></div>
					<div class="p-t-xl">
						<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="payroll_employee_list">
							<thead>
								<tr>
									<th width="5%">
										<input type="checkbox" name="check_all" id="check_all" class="filled-in" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
									  	<label for="check_all"></label>							
									</th>
									<th width="15%">Employee Number</th>
									<th width="20%">Employee Name</th>
									<th width="25%">Office</th>
									<th width="15%">Employment Status</th>
									<th width="10%">Action</th>
								</tr>
								<tr class="table-filters">
									<td></td>
									<td><input name="b-agency_employee_id" class="form-filter"></td>
									<td><input name="employee_name" class="form-filter"></td>
									<td><input name="f-name" class="form-filter"></td> <!-- office name -->
									<td><input name="g-employment_status_name" class="form-filter"></td>
									<td class="table-actions">
										<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
										<a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
									</td>
								</tr>
							</thead>
							<tbody>
		
							</tbody>
						</table>
					</div>
				    <!--end container-->				  
				</form>
          </div>
        </div>
        <!--end container-->

    </section>

	
<script>
$(function (){
	load_payroll_employees();
	
	$('#sp_payroll_form').parsley();
	$("#sp_payroll_report").on('off');
	$("#sp_payroll_report").on('click', function (){
		$("#sp_payroll_form").submit();
		
	});
	$("#sp_payroll_form").off('submit');
	$("#sp_payroll_form").on('submit', function (e){
		e.preventDefault();
		if($(this).parsley().isValid()) {
			var data = $(this).serialize();
			var data = $('#sp_payroll_form,#payroll_employee_form').serialize();
			$('#confirm_modal').confirmModal({
	    		topOffset 	: 0,
	    		onOkBut 	: function() {
	    			button_loader('sp_payroll_report', 1);
    				$.ajax({
    						url  : $base_url + 'main/special_payroll/process_special_payroll',
    						data : data,
   	 						dataType:'json',
    						success : function(result){
    							if(result.status) {
    								notification_msg("<?php echo SUCCESS ?>", result.msg);
    								if(result.reload_url) {
    									window.location = $base_url + 'main/special_payroll/prepare_special_payroll/' + result.reload_url;
    								}
    							}
    							else {
    								notification_msg("<?php echo ERROR ?>", result.msg);
    							}	
    							
    						},
    						
    						complete : function(jqXHR){
    							button_loader('sp_payroll_report', 0);
    						}
    				});
	
	    		},
	    		onCancelBut : function() {},
	    		onLoad 		: function() {
	    			$('.confirmModal_content h4').html('Are you sure you want to save this Payroll?');
	    		},
	    		onClose 	: function() {}
	    	});
		}
	});

	$('#compensation_type_id').off('change');
	$('#compensation_type_id').on('change', function(e){
		var data = $('#sp_payroll_form, #payroll_employee_form').serialize();
		$.post($base_url + 'main/special_payroll/set_compensation_type_change/', data, function(result)
		{
			if (result.success == 1)
			{
				load_payroll_employees();
			}
		}, 'json');		
	});	

	function load_payroll_employees()
	{
		var data = {
						payroll_id : $('#id').val(),
						office_id : $('#office_filter').val()
					};
		
		$.post($base_url + 'main/special_payroll/get_included_employee_count/', data, function(result)
		{
			$('#display_employee_count').html(result.count);
			
		}, 'json');
		var post_data = $("#sp_payroll_form, #payroll_employee_form").serializeArray();
		var no_sort_cols = [0, -1]; // do not sort first (checkbox) and last columns (action buttons)
		load_datatable('payroll_employee_list', '<?php echo "main/special_payroll/get_payroll_employee_list/$action/$module/"?>',false,0,1,true,post_data,no_sort_cols);
		set_select_all_check();
	}
	
	//ab : check_all button checked if all items is selected else Uncheck
	function set_select_all_check()
	{
		var data_module = { module : <?php echo $module?>};
		$.post($base_url + 'main/special_payroll/is_selected_all/', data_module, function(result)
		{
			if(result.is_select_all == true) 
			{
				$("#check_all").prop('checked', 'checked');
			} else {
				$("#check_all").prop('checked', '');
			}
		}, 'json'); 
	}
	
	$(document).on('click', '.ind_checkbox', function(){
		var checked    = $(this).prop('checked');
		// START: set selected employee to a session var
		var data = $('#sp_payroll_form, #payroll_employee_form').serialize();
		var param = $(this).val() + '/' + (checked ? 1 : 0);
		$.post($base_url + 'main/special_payroll/set_selected_employee/' + param, data, function(result)
		{
			set_select_all_check();	
		}, 'json');
		// END: set selected employee to a session var
			
	});

	$('.filter-submit, .filter-cancel').on('click', function(event) {
		load_payroll_employees();
		event.stopPropagation();
	});

	$('#check_all').on('click', function() {
		var checked = 1;
	    if($(this).is(':checked')) {
	    	$('#payroll_employee_list > tbody .ind_checkbox').prop('checked',true);
	    	checked = 1;
	    }
	    else {
	    	$('#payroll_employee_list > tbody .ind_checkbox').prop('checked',false);
	    	checked = 0;
	    }

	    var data = $('#sp_payroll_form, #payroll_employee_form').serialize();
		var office = $('#office_filter').val();
		var param = 'all/' + checked + '/' + office;
		$.post($base_url + 'main/special_payroll/set_selected_employee/' + param, data, function(result)
		{

		}, 'json');
	});
})

</script>