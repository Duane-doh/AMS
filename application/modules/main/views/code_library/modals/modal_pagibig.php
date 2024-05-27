<form id="pagibig_form">
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
						   value="<?php echo isset($pagibig_info[0]['effectivity_date']) ? format_date($pagibig_info[0]['effectivity_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="effectivity_date">Effectivity Date<span class="required">*</span></label>
				</div>
			</div>
			<div class='col s6 switch p-t-lg'>
			    <label>
			        Inactive
			        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($pagibig_info[0]['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
			        <span class='lever'></span>Active
			    </label>
			</div>
		</div>

		<div class="row b-t-n p-t-sm b-b-n">
			<div class="col s2 pull-right">
				<?php if($action != ACTION_VIEW):?>
		  			<button type="button" class="btn" id="add_pagibig"><i class="flaticon-add176"></i>Add</button>
			  	<?php endif; ?>
			</div>
		</div>

		<div class="row b-t-n m-sm">
			<div class="form-basic">
				<table class="table-default striped">
					<thead class="teal">
						<tr>
							<th class="white-text" style="font-size: 12px;" width="100">SALARY RANGE<br>FROM</th>
							<th class="white-text" style="font-size: 12px;" width="100">SALARY RANGE<br>TO</th>
							<th class="white-text" style="font-size: 12px;" width="100">MAXIMUM SALARY<br>RANGE</th>
							<th class="white-text" style="font-size: 12px;" width="100">EMPLOYER RATE</th>
							<th class="white-text" style="font-size: 12px;" width="100">EMPLOYEE RATE</th>
							<th class="white-text" width="65">ACTION</th>
						</tr>
					</thead>
					<tbody id="table_body">
						<?php if ($action == ACTION_ADD):?>
							<tr>
								<td><input name="salary_range_from[]" type="text" class="validate number right-align" required min="0" value=""></input></td>
								<td><input name="salary_range_to[]" type="text" class="validate number right-align" required min="0" value=""></input></td>
								<td><input name="max_salary_range[]" type="text" class="validate number right-align" required min="0" value=""></input></td>
								<td><input name="employer_rate[]" type="text" class="validate" required min="0" value=""></input></td>
								<td><input name="employee_rate[]" type="text" class="validate" required min="0" value=""></input></td>
								<td></td>
							</tr>
						<?php endif;?>
						<?php if ($action != ACTION_ADD):
								foreach ($pagibig_info as $pagibig):
							?>
								<tr>
									<td><input type="text" class="validate number right-align" required name="salary_range_from[]" id="salary_range_from" min="0" value="<?php echo isset($pagibig['salary_range_from']) ? $pagibig['salary_range_from'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
									<td><input type="text" class="validate number right-align" required name="salary_range_to[]" id="salary_range_to" min="0" value="<?php echo isset($pagibig['salary_range_to']) ? $pagibig['salary_range_to'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
									<td><input type="text" class="validate number right-align" required name="max_salary_range[]" id="max_salary_range" min="0" value="<?php echo isset($pagibig['max_salary_range']) ? $pagibig['max_salary_range'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
									<td><input type="text" class="validate" required name="employer_rate[]" id="employer_rate" min="0" value="<?php echo isset($pagibig['employer_rate']) ? $pagibig['employer_rate'] * 100: NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
									<td><input type="text" class="validate" required name="employee_rate[]" id="employee_rate" min="0" value="<?php echo isset($pagibig['employee_rate']) ? $pagibig['employee_rate'] * 100: NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
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
		    <button class="btn btn-success " id="save_pagibig" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
counter = 1;
$('#add_pagibig').on( "click", function() {
	var add_pagibig =	'<tr>' +
							'<td>' +
								'<input name="salary_range_from[]" id="salary_range_from_'+counter+'" type="text" min="0" class="validate number right-align" required value=""></input>' +
							'</td>' +
							'<td>' +
								'<input name="salary_range_to[]" id="salary_range_to_'+counter+'" type="text" min="0" class="validate number right-align" required value=""></input>' +
							'</td>' +
							'<td>' +
								'<input name="max_salary_range[]" id="max_salary_range_'+counter+'" type="text" min="0" class="validate number right-align" required value=""></input>' +
							'</td>' +
							'<td>' +
								'<input name="employer_rate[]" id="employer_rate_'+counter+'" type="text" min="0" class="validate" required value=""></input>' +
							'</td>' +
							'<td>' +
								'<input name="employee_rate[]" id="employee_rate_'+counter+'" type="text" min="0" class="validate" required value=""></input>' +
							'</td>' +
							'<td class="table-actions">' +
									'<a href="javascript:;" class="delete delete_row" onclick="delete_row(this)"></a>' +
							'</td>' +
						'</tr>';
	$('#table_body').append(add_pagibig);
	$('.number').number(true,2);
	counter++;
	selectize_init();
});
$(function (){
	$('#pagibig_form').parsley();
	$('#pagibig_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_pagibig', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/pagibig/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_pagibig.closeModal();
							load_datatable('pagibig_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/pagibig/get_pagibig_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_pagibig', 0);
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