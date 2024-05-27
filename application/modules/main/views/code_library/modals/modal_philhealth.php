<?php $end_count = $end_result['salary_bracket'] + 1; ?>
<form id="philhealth_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row b-b b-light-gray">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker" required name="effectivity_date" id="effectivity_date" 
				   		   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effectivity_date')"
						   value="<?php echo isset($philhealth_info[0]['effectivity_date']) ? format_date($philhealth_info[0]['effectivity_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="effectivity_date">Effectivity Date<span class="required">*</span></label>
				</div>
			</div>
			<div class='col s6 switch p-t-lg'>
			    <label>
			        Inactive
			        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($philhealth_info[0]	['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
			        <span class='lever'></span>Active
			    </label>
			</div>
		</div>

		<div class="row b-t-n p-t-sm">
			<div class="col s2 pull-right">
				<?php if($action != ACTION_VIEW):?>
		  			<button type="button" class="btn" id="add_philhealth"><i class="flaticon-add176"></i>Add</button>
		  		<?php endif; ?>
			</div>
		</div>
		<!-- EDIT/VIEW OTHER PHILHEALTH -->
		<div class="row b-t-n m-sm">
			<div class="form-basic">
				<table class="table-default striped">
					<thead class="teal">
						<tr>
							<th width="10%" class="white-text">Salary Bracket</th>
							<th width="10%" class="white-text">Salary Range From</th>
							<th width="10%" class="white-text">Salary Range To</th>
							<th width="10%" class="white-text">Salary Base</th>
							<th width="10%" class="white-text">Total Monthly Premium</th>
							<th width="10%" class="white-text">Employee Share</th>
							<th width="10%" class="white-text">Employer Share</th>
							<th width="7%" class="white-text">Action</th>
						</tr>
					</thead>
					<tbody id="table_body">
						<?php if ($action == ACTION_ADD):?>
							<tr>
								<td><input type="text" required name="salary_bracket[]" id="salary_bracket" value="1"></td>
								<td><input type="text" class="validate number right-align" required min="0" name="salary_range_from[]" id="salary_range_from_1" value="" onkeyup="salary(1)"></td>
								<td><input type="text" class="validate number right-align" required min="0" name="salary_range_to[]" id="salary_range_to_1" value="" onkeyup="salary(1)"></td>
								<td><input type="text" class="validate number right-align" required min="0" name="salary_base[]" id="salary_base_1" value=""></td>
								<td><input type="text" class="validate number right-align" required min="0" name="total_monthly_premium[]" id="total_monthly_premium_1" value=""></td>
								<td><input type="text" class="validate number right-align" required min="0" name="employee_share[]" id="employee_share_1" value=""></td>
								<td><input type="text" class="validate number right-align" required min="0" name="employer_share[]" id="employer_share_1" value=""></td>
								<td></td>
							</tr>
						<?php endif;?>
						<?php if ($action != ACTION_ADD):
								foreach ($philhealth_info as $philhealth):
							?>
								<tr>
									<td><input type="text" required name="salary_bracket[]" id="salary_bracket" value="<?php echo isset($philhealth['salary_bracket']) ? $philhealth['salary_bracket'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<td><input type="text" class="validate number right-align" required min="0" name="salary_range_from[]" id="salary_range_from" value="<?php echo isset($philhealth['salary_range_from']) ? $philhealth['salary_range_from'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<td><input type="text" class="validate number right-align" required min="0" name="salary_range_to[]" id="salary_range_to" value="<?php echo isset($philhealth['salary_range_to']) ? $philhealth['salary_range_to'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<td><input type="text" class="validate number right-align" required min="0" name="salary_base[]" id="salary_base" value="<?php echo isset($philhealth['salary_base']) ? $philhealth['salary_base'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<td><input type="text" class="validate number right-align" required min="0" name="total_monthly_premium[]" id="total_monthly_premium" value="<?php echo isset($philhealth['total_monthly_premium']) ? $philhealth['total_monthly_premium'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<td><input type="text" class="validate number right-align" required min="0" name="employee_share[]" id="employee_share" value="<?php echo isset($philhealth['employee_share']) ? $philhealth['employee_share'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<td><input type="text" class="validate number right-align" required min="0" name="employer_share[]" id="employer_share" value="<?php echo isset($philhealth['employer_share']) ? $philhealth['employer_share'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></td>
									<?php if($action != ACTION_VIEW):?>
										<td class="table-actions">
											<a href="javascript:;" class="delete tooltipped delete_row" onclick="delete_row(this)" data-tooltip="Delete" data-position="bottom" data-delay="50"></a>
										</td>
									<?php endif; ?>
								</tr>
							<?php
							endforeach;
							 endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_philhealth" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>

</form>

<script>

var count = 2;
<?php if($action == ACTION_EDIT):?>
	var count = '<?php echo $end_count ?>';
<?php endif; ?>
$('#add_philhealth').on( "click", function() {
	var add_philhealth =	'<tr>' +
								'<td>' +
									'<input type="text" required name="salary_bracket[]" id="salary_bracket" value="'+ count +'"/>' +
								'</td>' +
								'<td>' +
									'<input type="text" class="validate number right-align" required min="0" name="salary_range_from[]" id="salary_range_from_'+ count +'" value=""/>' +
								'</td>' +
								'<td>' +
									'<input type="text" class="validate number right-align" required min="0" name="salary_range_to[]" id="salary_range_to_'+ count +'" value=""/>' +
								'</td>' +
								'<td>' +
									'<input type="text" class="validate number right-align" required min="0" name="salary_base[]" id="salary_base_'+ count +'" value=""/>' +
								'</td>' +
								'<td>' +
									'<input type="text" class="validate number right-align" required min="0" name="total_monthly_premium[]" id="total_monthly_premium_'+ count +'" value=""/>' +
								'</td>' +
								'<td>' +
									'<input type="text" class="validate number right-align" required min="0" name="employee_share[]" id="employee_share_'+ count +'" value=""/>' +
								'</td>' +
								'<td>' +
									'<input type="text" class="validate number right-align" required min="0" name="employer_share[]" id="employer_share_'+ count +'" value=""/>' +
								'</td>' +
								'<td class="table-actions">' +
									'<a href="javascript:;" class="delete delete_row" onclick="delete_row(this)"></a>' +
								'</td>' +
							'</tr>';
		$('#table_body').append(add_philhealth);
		$('.number').number(true,2);
		count ++;
		selectize_init();
  	});

$(function (){
	$('#philhealth_form').parsley();
	$('#philhealth_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_philhealth', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/philhealth/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_philhealth.closeModal();
							load_datatable('philhealth_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/philhealth/get_philhealth_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_philhealth', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})
function delete_row(delete_row){
	delete_row.closest('tr').remove();
}
</script>