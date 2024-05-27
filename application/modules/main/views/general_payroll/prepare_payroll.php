<?php 
	$payout_count = empty($payout_count) ? 0 : $payout_count;
	$hidden_payout_fields = isset($hidden_payout_fields) ? $hidden_payout_fields : '';
	
	$hidden_payout_fields = '';
	
	for($pd=1, $cnt=0; $pd<=$payout_count; $pd++, $cnt++)
	{
		$hdn_id = "hdn_payout_date_" . $pd;
		$hidden_payout_fields .= '<input type="hidden" name="'.$hdn_id.'" id="'.$hdn_id.'" value="'.$payout_dates[$pd].'">';
	}
?>
<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Prepare General Payroll</h5>
                <ol class="breadcrumb m-n p-b-sm">
                    <?php get_breadcrumbs();?>
                </ol>
              </div>
            </div>
          </div>
          
          
	        <!--start container-->
	        <div class="container" id="page_content">
	          <div class="section panel p-lg">			
					<form id="payroll_form" name="payroll_form" class="form-vertical form-styled  m-t-lg" autocomplete="off">	
					  	<input type="hidden" name="action" id="action" value="<?php echo $action; ?> ">
						<input type="hidden" name="id" id="payroll_id"value="<?php echo $id; ?>">
						<input type="hidden" name="salt" value="<?php echo $salt; ?>">
						<input type="hidden" name="token" value="<?php echo $token; ?>">
						<input type="hidden" name="module" id="module" value="<?php echo $module; ?>">
						<input type="hidden" name="payout_count" id="payout_count" value="<?php echo $payout_count; ?>">
						<?php echo $hidden_payout_fields;?>
					    <div class="form-basic">				    	
						  	<div class="row">
								<div class="col s4">
									<div class="input-field">
										<label for="payroll_type" class="active">Payroll Type <span class="required">*</span></label>
									 	<select id="payroll_type" name="payroll_type" class="selectize" placeholder="Select Payroll Type" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
											<option value=""></option>
											<?php foreach($payroll_types as $type): 
												$selected = ( (isset($val['payroll_type_id']) AND $type['payroll_type_id'] == $val['payroll_type_id']) ? ' selected ' : '');
											?>
												<option value="<?php echo $type['payroll_type_id'];?>" <?php echo $selected; ?>>	<?php echo $type['payroll_type_name']; ?></option>
											<?php endforeach; ?>
									 	</select>
									</div>
								</div>
								<div class="col s4">
									<div class="input-field">
									 <label for="payroll_period" class="active">Attendance Period<span class="required">*</span></label>
									 <select id="payroll_period" class="selectize" name="payroll_period" placeholder="Select Payroll Period" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
											<option value=""></option>
											<?php foreach($payroll_periods as $period):
												$selected = ($period['value'] == $val['attendance_period_hdr_id'] ? ' selected ' : '');
											?>
												<option value="<?php echo $period['value'];?>" <?php echo $selected; ?>> <?php echo $period['text']; ?></option>
											<?php endforeach; ?>
								 	 </select>
									</div>
								</div>
								<div class="col s4">
									<div class="input-field">
									 <label for="bank_id" class="active">Bank<span class="required">*</span></label>
									 <select id="bank_id" class="selectize" name="bank_id" placeholder="Select Bank" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
										<option value="">Select Bank</option>
										<?php foreach($banks as $bank):
											$selected = ( (isset($val['bank_id']) AND $bank['bank_id'] == $val['bank_id']) ? ' selected ' : '');
										?>
											<option value="<?php echo $bank['bank_id'];?>" <?php echo $selected; ?>><?php echo $bank['bank_name'];?></option>
										<?php endforeach; ?>					
								 	 </select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s4">
									<div class="input-field">
									 <label for="certified_correct_by_id" class="active">Certified By</label>
									 <select  id="certified_correct_by_id" name="certified_correct_by_id" class="selectize" placeholder="Select Certified By" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
										<option value="">Select Certified By</option>
										<?php if (!EMPTY($personnel_certify)): ?>
											<?php foreach ($personnel_certify as $personnel): 
												$selected = ( (isset($val['certified_by']) AND $personnel['employee_id'] == $val['certified_by']) ? ' selected ' : '');
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
									 <select id="approved_by_id" name="approved_by_id" class="selectize" placeholder="Select Approved By" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
									 	<option value="">Select Approved By</option>
										<?php if (!EMPTY($personnel_approvers)): ?>
											<?php foreach ($personnel_approvers as $personnel): 
												$selected = ( (isset($val['approved_by']) AND $personnel['employee_id'] == $val['approved_by']) ? ' selected ' : '');
											?>
												<option value="<?php echo $personnel['employee_id'] ?>" <?php echo $selected; ?>><?php echo $personnel['employee_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								 	 </select>
									</div>
								</div>
								<div class="col s4">
									<div class="input-field">
									 <label for="certified_cash_by_id" class="active">NCA Available By</label>
									 <select id="certified_cash_by_id" name="certified_cash_by_id" class="selectize" placeholder="Select NCA Available By" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
										<option value="">Select Certified Cash By</option>
										<?php if (!EMPTY($personnel_ca_certify)): ?>
											<?php foreach ($personnel_ca_certify as $personnel): 
												$selected = ( (isset($val['certified_cash_by']) AND $personnel['employee_id'] == $val['certified_cash_by']) ? ' selected ' : '');
											?>
												<option value="<?php echo $personnel['employee_id'] ?>" <?php echo $selected; ?>><?php echo $personnel['employee_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								 	 </select>
									</div>
								</div>
							</div>
							<div class="form-basic">
								<div class="row m-b-n">
									<div id="div_payout_date" class="col s12">
									
									<!-- payout dates here -->
									
									</div>
									
									
								<?php if ($action!=ACTION_ADD) { ?>
									<div class="col s3">
										<div class="input-field">
											 <label for="status_id" class="active">Status</label>
											 <div class="col s6">
											 <label><?php echo $val['payout_status_name']; ?></label>
											 </div>
										</div>
									</div>
								<?php } ?>
								</div>
							</div>					  
					    </div>
					    <div class="panel-footer right-align">
						  <a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll" class="waves-effect waves-teal btn-flat">Cancel</a>
						  <?php if ($action!=ACTION_VIEW) { ?>
						    	<button id="btn_payroll_save" class="btn btn-success " value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
						    	<?php if($action!=ACTION_ADD AND $this->permission->check_permission(MODULE_PAYROLL_GENERAL_PAYROLL, ACTION_PROCESS_ALL)) :?>
						    		<button id="btn_payroll_process" class="btn btn-success " value="<?php echo BTN_PROCESS ?>"><?php echo BTN_PROCESS ?></button>
						    	<?php endif; ?>
							<?php } ?>
						</div>
					  </form>     
					  <hr>
					  
					  <form id="payroll_employee_form" name="payroll_employee_form" class="form-vertical form-styled  m-t-lg" autocomplete="off">
					    <!--start container-->
					    
		                <div class="col s6 m6 l6 ">
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
		                		<div class="col s8 right-align">
		                			<select name="c-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
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
											<input type="checkbox" name="check_all" id="check_all" class="filled-in" checked="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
										  	<label for="check_all"></label>								
										</th>
										<th width="15%">Employee Number</th>
										<th width="20%">Employee Name</th>
										<th width="25%">Office</th>
										<th width="15%">Employment Status</th>
										<th width="15%">Action</th>
									</tr>
									<tr class="table-filters">
										<td></td>
										<td><input name="a-agency_employee_id" class="form-filter"></td>
										<td><input name="employee_name" class="form-filter"></td>
										<td><input name="e-name" class="form-filter"></td> <!-- office name -->
										<td><input name="f-employment_status_name" class="form-filter"></td>
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
	        
			<div class="col s3" id="div_payout_0" style="display:none!important">
				<div class="input-field">
				 <label for="payout_date_0" class="active">Payout Date<span class="required">*</span></label>
				 <input type="text" id="payout_date_0" name="payout_date_0" class="validate datepicker" placeholder="yyyy/mm/dd" 
				 	value="" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>
				 	onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,this.id)">
				</div>
			</div>          
          
          
          
        </div>
        <!--breadcrumbs end-->

        

    </section>

	
<script>
$(function (){
	load_payroll_employees();
	if (<?php echo $payout_count;?> > 0)
	{
		load_payout_dates(<?php echo $payout_count;?>);
	}

	var submit_button;
	$("#btn_payroll_save, #btn_payroll_process").click(function() {
		submit_button = $(this).attr('id');
  	});

	$('#payroll_form').parsley();
	$("#payroll_form").on('off');
	$("#payroll_form").on('submit', function (e){
		e.preventDefault();
		if($(this).parsley().isValid())
		{
			var submit_url = $base_url + 'main/payroll/save_payroll';
			var submit_type = 'save';
			
			if (submit_button == 'btn_payroll_process') {
				submit_url = $base_url + 'main/payroll/process_payroll';
				submit_type = 'process';
			}
    		var data = $('#payroll_form,#payroll_employee_form').serialize();

			$('#confirm_modal').confirmModal({
	    		topOffset 	: 0,
	    		onOkBut 	: function() {
	    			button_loader(submit_button, 1);
    				$.ajax({
    						url  : submit_url,
    						data : data,
   	 						dataType:'json',
    						success : function(result){
    							if(result.status) {
    								notification_msg("<?php echo SUCCESS ?>", result.msg);
    								if(result.reload_url) {
    									window.location = $base_url + 'main/payroll/prepare_payroll/' + result.reload_url;
    								}
    							}
    							else {
    								// notification_msg("<?php echo ERROR ?>", result.msg);
									scroll_notification_msg("<?php echo ERROR ?>", result.msg); //jendaigo : change notification css
    							}	
    							
    						},
    						
    						complete : function(jqXHR){
    							button_loader(submit_button, 0);
    						}
    				});
	    		},
	    		onCancelBut : function() {},
	    		onLoad 		: function() {
	    			$('.confirmModal_content h4').html('Are you sure you want to '+submit_type+' this Payroll?');
	    		},
	    		onClose 	: function() {}
	    	});
		}
	});

	$('#payroll_type').off('change');
	$('#payroll_type').on('change', function(e){
		var payroll_period = $("#payroll_period")[0].selectize;

		var data = $('#payroll_form').serialize();
		
		payroll_period.disable();
		$.post($base_url + 'main/payroll/get_payroll_period_dropdown/', data, function(result)
		{
			
			payroll_period.clear();
			payroll_period.clearOptions();

			payroll_period.load(function(callback) {
				callback(result.list);
			});
			payroll_period.enable();

			if(result.payout_count !=="")
			{
				load_payout_dates(result.payout_count);
			}
		 				  
		}, 'json');
	});

	$('#payroll_period').on('change', function(e){
		var data = $('#payroll_form').serialize();
		$.post($base_url + 'main/payroll/set_payroll_period_change/', data, function(result)
		{
			if (result.success == 1)
			{
				load_payroll_employees();
			}
		}, 'json');
		
	});	

	function load_payout_dates(payout_count)
	{
		if (payout_count < 1) return;
		
		$("#payout_count").val(payout_count);
		
		$('#div_payout_date').html('');
		
		for (idx = 1; idx <= payout_count; idx++) {
			var clone_div = $("#div_payout_0");

			clone_div.clone().attr("id", "div_payout_"+idx).removeAttr("style").appendTo("#div_payout_date");
			
			var new_div = $("#div_payout_" + idx);

			var id_payout_date = "payout_date_" + idx; 

			new_div.find('input').attr({
				id : id_payout_date,
				name : id_payout_date,
				value: $('#hdn_payout_date_' + idx).val()
			});

			var label_id = "label_payout_date_" + idx;
			new_div.find('label').attr({
				'for' : "payout_date_" + idx,
				id : label_id
			});
			$('#' + label_id).html('Payout Date ' + idx + ' <span class="required">*</span>');
		}

		datepicker_init();
	}		

	
	function load_payroll_employees()
	{

		set_select_all_check();
		var data = {
						payroll_id : $('#payroll_id').val(),
						office_id : $('#office_filter').val()
					};

		$.post($base_url + 'main/payroll/get_included_employee_count/', data, function(result)
		{
			$('#display_employee_count').html(result.count);
			
		}, 'json');
		var post_data = $('#payroll_form, #payroll_employee_form').serializeArray();
		var no_sort_cols = [0, -1]; // do not sort first (checkbox) and last columns (action buttons)
		var id_data = ''; 
		if ($('#payroll_period').val() > 0)
			id_data = $('#payroll_type').val() + '/' + $('#payroll_period').val();
		
		load_datatable('payroll_employee_list', '<?php echo "main/payroll/get_payroll_employee_list/$action/$module/"?>' + id_data,false,0,1,true,post_data,no_sort_cols);
		set_select_all_check();
	}	

	//ab : check_all button checked if all items is selected else Uncheck
	function set_select_all_check()
	{
		var data_module = { module : <?php echo $module?>};
		$.post($base_url + 'main/payroll/is_selected_all/', data_module, function(result)
		{
			if(result.is_select_all) 
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
		var data = $('#payroll_form, #payroll_employee_form').serialize();
		var param = $(this).val() + '/' + (checked ? 1 : 0);

		$.post($base_url + 'main/payroll/set_selected_employee/' + param, data, function(result)
		{
			if (result.success == 1)
			{
				set_select_all_check();
				if (checked)
					$('#'+result.proc_employee_id).css("visibility", "visible");
				else
					$('#'+result.proc_employee_id).css("visibility", "hidden");
			}
			if (result.success == -1)
			{
				if (checked)
				{
					console.log(checked);
					console.log(result.success);
					console.log($(this).attr("id"));
					
					//$(this).prop('checked', false);
				}
				notification_msg("<?php echo ERROR ?>", result.msg);
			}
		}, 'json');
		// END: set selected employee to a session var		
	});

	$('.filter-submit, .filter-cancel').on('click', function(event) {
		load_payroll_employees();
		event.stopPropagation();
	});
})

function process_employee(e_id)
{
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			$('#page_content').isLoading();
			
			var post_data = $('#payroll_form').serialize();
						
			$.post($base_url + 'main/payroll/process_selected_employee/'+e_id, post_data, function(result)
			{
				if(result.status)
				{
					notification_msg("<?php echo SUCCESS ?>", result.msg);
				}
				else
				{
					notification_msg("<?php echo ERROR ?>", result.msg);
				}

				$('#page_content').isLoading("hide");
			}, 'json');	
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Are you sure you want to re-compute his/her payout ?');	
		},
		onClose : function() {}
	});
}
$('#check_all').click(function() {
	var checked = 1;
    if($(this).is(':checked')) {
    	$('#payroll_employee_list > tbody .ind_checkbox').prop('checked',true);
    	var checked = 1;
    }
    else {
    	$('#payroll_employee_list > tbody .ind_checkbox').prop('checked',false);
    	var checked = 0;
    }
	// START: set selected employee to a session var
	var data = $('#payroll_form, #payroll_employee_form').serialize();
	var param = 'all/' + (checked ? 1 : 0) + '/1';

	$.post($base_url + 'main/payroll/set_selected_employee/' + param, data, function(result)
	{
		if (result.success == 1)
		{
			if (checked)
				$('#'+result.proc_employee_id).css("visibility", "visible");
			else
				$('#'+result.proc_employee_id).css("visibility", "hidden");
		}
		if (result.success == -1)
		{
			if (checked)
			{
				console.log(checked);
				console.log(result.success);
				console.log($(this).attr("id"));
				
				//$(this).prop('checked', false);
			}
			notification_msg("<?php echo ERROR ?>", result.msg);
		}
	}, 'json');
	// END: set selected employee to a session var		
});

// ====================== jendaigo : start : format scrollable notification ============= //
function scroll_notification_msg(type, msg){
	$(".notify." + type + " p").html(msg);

	$(".notify." + type + " p").css({
		"width": "300px",
		"height": "200px",
		"overflow": "auto"
	});

	$(".notify." + type).notifyModal({
		duration : -1
	});
}
// ====================== jendaigo : end : format scrollable notification ============= //

</script>