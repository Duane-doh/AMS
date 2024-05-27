<?php
	
	$present_date = "";
	$fetch_data = $sss;
	$active = "";

	// SECURITY VARIABLES
	$id = ($encoded_date_id != NULL)? $encoded_date_id:NULL;
	if(IS_NULL($fetch_data))
	{
		$display_save = '<tr class="sss_input_box">
							<td><input type="text" class="validate number right-align" required min="0" name="salary_range_from[]" id="salary_range_from_1" value="" onkeyup="get_salary_range_from(1)"></td>
							<td><input type="text" class="validate number right-align" required min="0" name="salary_range_to[]" id="salary_range_to_1" value="" onkeyup="get_salary_range_to(1)"></td>
							<td><input type="text" class="validate number right-align" required min="0" name="salary_credit[]" data-parsley-range="[6,10]" id="salary_credit_1" value=""></td>
							<td><input type="text" class="validate number right-align employer_share_1" required min="0" name="employer_share[]" id="employer_share" value="" onkeyup="get_employer_share(1)"></td>
							<td><input type="text" class="validate number right-align employee_share_1" required min="0" name="employee_share[]" id="employee_share" value="" onkeyup="get_employee_share(1)"></td>
							<td class="right-align totalNumber_1"></td>
						</tr>';
		
		$save_btn = '<button class="btn btn-success " id="save_sss" value="'.BTN_SAVE.'">'.BTN_SAVE.'</button>';
		$inputs = "";
		$delete_btn = "";
		$add_btn = "<button type='button' class='btn' id='add_sss'><i class='flaticon-add176'></i>Add</button>";
		$display_stat = "checked";
	}
	else
	{
		$display_save = '';
		$present_date = $fetch_data[0]['effectivity_date'];
		$active ="active";
		
		if($action != ACTION_EDIT)
		{
			$save_btn = '';
			$inputs = "disabled";
			$delete_btn = "";
			$add_btn = "";
			$display_stat = ($fetch_data[0]['active_flag'] == "Y")? "checked":"";
		}
		else
		{
						
			$save_btn = '<button class="btn btn-success " id="save_sss" value="'.BTN_SAVE.'">'.BTN_SAVE.'</button>';
			$inputs = "";
			$delete_btn = "<a href='javascript:;' onclick='delete_row(this)' class='delete delete_row'></a>";
			$add_btn = "<button type='button' class='btn' id='add_sss'><i class='flaticon-add176'></i>Add</button>";
			$display_stat = "";
			$display_stat = ($fetch_data[0]['active_flag'] == "Y")? "checked":"";
			
		}
	}
?>

<form id="sss_form">
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo $salt;?>">
	<input type="hidden" name="token" id="token" value="<?php echo $token;?>">
	<input type="hidden" name="action" id="action" value="<?php echo $action;?>">

	<div class="form-float-label">
		<div class="row b-b b-light-gray">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker" required name="effectivity_date" id="effectivity_date" 
				   		   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effectivity_date')"
						   value="<?php echo format_date($present_date); ?>" <?php echo $inputs;?> />
			   		<label class="" for="effectivity_date">Effectivity Date<span class="required">*</span></label>
				</div>
			</div>
			<div class='col s6 switch p-t-lg'>
			    <label>
			        Inactive
			        <input name='active_flag' type='checkbox' value='1' <?php echo $display_stat?> <?php echo $inputs ?> id = "active_flag" > 
			        <span class='lever'></span>Active
			    </label>
			</div>
		</div>

		<div class="row b-t-n p-t-sm">
			<div class="col s2 pull-right">
	  			<?php echo $add_btn?>
			</div>
		</div>
		
		<div class="row b-t-n m-sm">
			<div class="form-basic">
				<table class="table-default striped">
					<thead class="teal">
						<tr>
							<th width="18%" class="white-text">Salary Range From</th>
							<th width="18%" class="white-text">Salary Range To</th>
							<th width="18%" class="white-text">Salary Credit</th>
							<th width="18%" class="white-text">Employer Share</th>
							<th width="18%" class="white-text">Employee Share</th>
							<th width="18%" class="white-text">Total</th>
							<th width="18%" class="white-text">Action</th>
						</tr>
					</thead>
					
					<tbody id="table_body">
						<?php echo $display_save;?>
						<?php $count = 0;?>
						<?php foreach($sss as $result_sss): ?>
						<?php $total_value = $result_sss['employer_share'] + $result_sss['employee_share'];?>
						<tr class="sss_input_box">
							<td><input type="text" class="validate number right-align" required min="0" name="salary_range_from[<?php echo $count;?>]" id="salary_range_from" value="<?php echo $result_sss['salary_range_from']; echo "dsa";?>" onkeyup="get_salary_range_from()" <?php echo $inputs ?> ></td>
							<td><input type="text" class="validate number right-align" required min="0" name="salary_range_to[<?php echo $count?>]" id="salary_range_to" value="<?php echo $result_sss['salary_range_to'];?>" onkeyup="get_salary_range_to()" <?php echo $inputs ?> ></td>
							<td><input type="text" class="validate number right-align" required min="0" name="salary_credit[<?php echo $count?>]" id="salary_credit" value="<?php echo $result_sss['salary_base'];?>" <?php echo $inputs ?>></td>
							<td><input type="text" class="validate number right-align" required min="0" name="employer_share[<?php echo $count?>]" id="employer_share" value="<?php echo $result_sss['employer_share'];?>" <?php echo $inputs ?>></td>
							<td><input type="text" class="validate number right-align" required min="0" name="employee_share[<?php echo $count?>]" id="employee_share" value="<?php echo $result_sss['employee_share'];?>" <?php echo $inputs ?>></td>
							<td class="right-align"><?php echo $total_value;?></td>
							<td>
								<div class="table-actions">
									<?php echo $delete_btn;?>
								</div>
							</td>
						</tr>
						<?php ++$count;?>
						<?php endforeach;?>
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
	
	<div class="md-footer default">
  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
  		<?php echo $save_btn?>
	</div>

</form>

<script>
	// START_APPEND_FORM
	$("#active_flag").on("change", function(){
		if($(this).val() == 1)
		{
			$("#active_flag").val("0");
		}
		else
		{
			$("#active_flag").val("1");
		}
	});
	var count = 2;
	var counter = 1;
	$('#add_sss').on('click',function(){
		var addform = '<tr class="sss_input_box_'+count+'">'+
						'<td><input type="text" class="validate number right-align" required min="0" name="salary_range_from['+counter+']" id="salary_range_from_'+count+'" value="" onkeyup="get_salary_range_from('+count+')"></td>'+
						'<td><input type="text" class="validate number right-align" required min="0" name="salary_range_to['+counter+']" id="salary_range_to_'+count+'" value="" onkeyup="get_salary_range_to('+count+')"></td>'+
						'<td><input type="text" class="validate number right-align" required min="0" name="salary_credit['+counter+']" id="salary_credit_'+count+'" value="" data-parsley-range="[6,10]" ></td>'+
						'<td><input type="text" class="validate number right-align employer_share_'+count+'" required min="0" name="employer_share['+counter+']" id="employer_share" value="" onkeyup="get_employer_share('+count+')"></td>'+
						'<td><input type="text" class="validate number right-align employee_share_'+count+'" required min="0" name="employee_share['+counter+']" id="employee_share" value="" onkeyup="get_employee_share('+count+')"></td>'+
						'<td class="right-align totalNumber_'+count+'"></td>'+
						'<td><div class="table-actions"><a href="javascript:;" onclick="delete_row(this)" class="delete tooltipped delete_row" data-tooltip="Delete" data-position="bottom" data-delay="50"></a></div></td>'+
					  '</tr>';
		$('input[type=text]').addClass('validate number');
// 		$('#salary_range_from_'+count+).attr('class', )
		$("#table_body").append(addform);
		$('.number').number(true,2);
		counter++;
		count++;
	});
	//------------END---------------

	<?php if(!IS_NULL($fetch_data)){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

  	// START_SALARY_RANGE_SCRIPT
			
		function get_salary_range_from(count){
			var range_from = Number($('#salary_range_from_'+count).val());
			var range_to  = Number($('#salary_range_to_'+count).val());
			$('#salary_credit_'+count).attr('data-parsley-range','['+range_from+', '+range_to+']');
		}

		function get_salary_range_to(count){
			var range_from = Number($('#salary_range_from_'+count).val());
			var range_to  = Number($('#salary_range_to_'+count).val());

			$('#salary_credit_'+count).attr('data-parsley-range','['+range_from+', '+range_to+']');
		}
		//---------------END--------------

		// START_GET_TOTAL_SHARE_SCRIPT
		function get_employer_share(count){
			var totalnum = Number($('.employer_share_'+count).val()) + Number($('.employee_share_'+count).val());
			$('.totalNumber_'+count).html(totalnum);
		}

		function get_employee_share(count){
			var totalnum = Number($('.employer_share_'+count).val()) + Number($('.employee_share_'+count).val());
			$('.totalNumber_'+count).html(totalnum);
		}
			
		//-------------END-----------------
		
		function delete_row(delete_row){
			delete_row.closest('tr').remove();
		}
	
</script>