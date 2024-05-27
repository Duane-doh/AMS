<?php if($action != ACTION_VIEW) : 
	$employee_id = $id;
	$salt      = gen_salt();
	$token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
	$url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module."/".$employee_id;
?>
<div class="row p-r-sm">
<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
			<!-- davcorrea changed button label -->
			<a class="btn btn-success md-trigger" data-modal="modal_work_experience_non_doh" onclick="modal_work_experience_non_doh_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add Non DOH Work Experience</a>


			<!-- Edited Button label -->
			<!-- <a class="btn btn-success md-trigger" data-modal="modal_work_experience_non_doh" onclick="modal_work_experience_non_doh_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add Non DOH Work Experience</a> -->
		<?php else: ?>
			<!-- <?php //if($if_separated):?>
				<a class="btn" id="generate_form_2316"><i class="flaticon-arrows97"></i> Generate Form 2316</a>				
				<input type="hidden" name="employee_id" id="employee_id" value="<?php //echo $if_separated['employee_id'] ?>"/>
				<input type="hidden" name="employ_end_date" id="employ_end_date" value="<?php //echo $if_separated['employ_end_date'] ?>"/>
			<?php //endif;?> -->
			<a class="btn btn-success md-trigger" data-modal="modal_work_experience_non_doh" onclick="modal_work_experience_non_doh_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add Non DOH</a>
			<a class="btn btn-success md-trigger" data-modal="modal_work_experience_doh" onclick="modal_work_experience_doh_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add DOH</a>
		<?php endif;?>
		</div>
	</div>	
</div>
<?php endif; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="pds_work_experience_table">
		<thead>
			<tr>
				<th width="10%">Start Date</th>
				<th width="10%">End Date</th>	
				<th width="15%">Position Title </th>
				<th width="25%">Office</th>
				<th width="10%">Monthly Salary</th>
				<th width="10%">Employment Status</th>	
				<th width="10%">Relevance</th>	
				<th width="10%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="employ_start_date" class="form-filter"></td>
				<td><input name="employ_end_date" class="form-filter"></td>
				<td><input name="position" class="form-filter"></td>
				<td><input name="office" class="form-filter"></td>
				<td><input name="A-employ_monthly_salary" class="form-filter"></td>
				<td><input name="B-employment_status_name" class="form-filter"></td>
				<td ></td>
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
<script>

	$('#generate_form_2316').on('click', function() {	

	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			var emp_id 		= $('#employee_id').val();
			var end_date 	= $('#employ_end_date').val();	

			var $params 	= {id 		 : emp_id,
					  		   date      : end_date};

			$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/generate_form_2316"?>",$params, function(result) {
				if(result.flag){				
					notification_msg("<?php echo SUCCESS ?>", result.msg);
				} else {
					notification_msg("<?php echo ERROR ?>", result.msg);
				}
			}, 'json');

		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Do you want to proceed?');	
			$('.confirmModal_content p').html('This action cannot be reverted.');
		},
		onClose : function() {}
	});

		
	});
	function update_relevance(id){	
		var $params 	= {employee_work_experience_id 		 : id};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/update_relevance"?>",$params, function(result) {
				if(result.flag){	
					load_datatable('pds_work_experience_table', '<?php echo PROJECT_MAIN ?>/pds_work_experience_info/get_work_experience_list/<?php echo $employee_id; ?>',false,0,0,true);	
					notification_msg("<?php echo SUCCESS ?>", result.msg);
				} else {
					notification_msg("<?php echo ERROR ?>", result.msg);
				}
			}, 'json');
	};
</script>

