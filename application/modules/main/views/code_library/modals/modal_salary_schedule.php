 <form id="salary_schedule_form">
	<input type="hidden" name="id" 	   id="id" 	   value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt"   id="salt"   value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token"  id="token"  value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="prev_date" id="prev_date" value="<?php echo !EMPTY($salary['effectivity_date']) ?  $salary['effectivity_date'] : '' ?>">
	<input type="hidden" name="prev_inserted_flag" id="prev_inserted_flag" value="<?php echo !EMPTY($salary['inserted_flag']) ?  $salary['inserted_flag'] : '' ?>">

	<!-- <div class="scroll-pane"> -->
	<!-- marvin remove class -->
	<div class="">
	<!-- marvin remove class -->
		<div class="form-float-label">
			<div class="row m-n">
				<div class="col s3">
					<div class="input-field">
						<input id="effectivity_date" name="effectivity_date" required 
				   		       onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effectivity_date')"
							   value="<?php echo !EMPTY($salary['effectivity_date']) ?  format_date($salary['effectivity_date']) : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker" >
						<label for="effectivity_date">Effectivity Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<input id="grade_count" name="grade_count" required value="<?php echo !EMPTY($salary['grade']) ? $salary['grade'] : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
						<label for="grade_count">Salary Grade<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<input id="steps_count" name="steps_count" required value="<?php echo !EMPTY($salary['step']) ? $salary['step'] : '' ?>" type="text" class="validate" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<label for="steps_count">Step Increment<span class="required">*</span></label>
					</div>
				</div>
				<div class='col s3 switch b-b b-light-gray p-md'>
					Use As Other Fund?<br><br>
				    <label>
				        No
				        <input name='other_fund_flag' type='checkbox'   value='Y' <?php echo ($salary['other_fund_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="row m-n">
				<div class="col s3">
					<div class="input-field">
						<input id="budget_circular_number" name="budget_circular_number" required value="<?php echo !EMPTY($salary['budget_circular_number']) ? $salary['budget_circular_number'] : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
						<label for="budget_circular_number">Budget Circular Number<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<input id="budget_circular_date" name="budget_circular_date" required 
				   		       onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'budget_circular_date')"
							   value="<?php echo !EMPTY($salary['budget_circular_date']) ?  format_date($salary['budget_circular_date']) : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker" >
						<label for="budget_circular_date">Budget Circular Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<input id="executive_order_number" name="executive_order_number" required value="<?php echo !EMPTY($salary['executive_order_number']) ? $salary['executive_order_number'] : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
						<label for="executive_order_number">Executive Order Number<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<input id="execute_order_date" name="execute_order_date" required 
				   		       onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'execute_order_date')"
							   value="<?php echo !EMPTY($salary['execute_order_date']) ?  format_date($salary['execute_order_date']) : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker" >
						<label for="execute_order_date">Executive Order Date<span class="required">*</span></label>
					</div>
				</div>
			</div>
			<div class="row b-b-n">
				<div class='col s2 switch p-md b-r-n'>
					<br>
				    <label>
				        Inactive
				        <input name='active_flag' type='checkbox' value='Y' <?php echo ($salary['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Active
				    </label>
				</div>

				<!-- MARVIN : START : INCLUDE EMPLOYMENT TYPE -->
				<div class="col s7" id="col_effectivity_date_for" style="border-left: 1px solid #e5e5e5;">
					<?php if(isset($salary['effectivity_date_for'])): ?>
						<?php
							$effectivity_date_for = json_decode(urldecode($salary['effectivity_date_for']));
							$emp_status = json_decode(urldecode($salary['employment_status']));
						?>
						<?php foreach($effectivity_date_for as $k => $v): ?>
						<?php $row_counter = $k; ?>
						<div class="row" id="row_effectivity_date_for_<?php echo $k; ?>" style="border-bottom: 1px solid #e5e5e5;">
							<div class="col s3 input-field">
								<label>Effectivity Date for:</label>
								<input type="text" class="datepicker" name="effectivity_date_for[]" value="<?php echo $v; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' : ''; ?> />
							</div>
							<div class="col s7 input-field">
								<select name="employment_status[<?php echo $k; ?>][]" id="employment_status_<?php echo $k; ?>" class="selectize employmentstatus" placeholder="Select Employment Status" onchange="prevent_duplicate(id)" multiple <?php echo $action == ACTION_VIEW ? 'disabled' : ''; ?>>
									<?php foreach($employment_status as $emp): ?>
									<option value="<?php echo $emp['employment_status_id']; ?>" <?php echo in_array($emp['employment_status_id'], $emp_status[$k]) ? 'selected' : ''; ?>><?php echo $emp['employment_status_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col s2 input-field">
								<a href="javascript:;" id="<?php echo $k; ?>" <?php echo $action == ACTION_VIEW ? '' : 'onclick="delete_row_effectivity_date_for(id)"'; ?> class="btn p-r-md p-l-md <?php echo $action == ACTION_VIEW ? 'disabled' : ''; ?>">Remove</a>
							</div>
						</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<!-- MARVIN : END : INCLUDE EMPLOYMENT TYPE -->

				<div class="col s3 b-l-n p-t-md p-l-xl">
					<?php if($action != ACTION_VIEW):?>
						<button type="button" class="btn" id="generate_table">Generate Table</button>

						<!-- marvin : start : include button for adding effectivity_date -->
						<br>
						<br>
						<button type="button" class="btn" id="add_new_row">Add Effectivity Date</button>
						<!-- marvin : end : include button for adding effectivity_date -->
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- class="none" -->
	<div  id="matrix_div" class="form-basic m-r-md m-l-md p-t-md">
		<table id="matrix_tbl" class="table-default striped">
			<thead id="thead_matrix" class="teal white-text">
				<tr>
					<th width="5%" class="white-text">Salary Grade</th>
					<?php 
						for ($i=1; $i <= $salary['step']; $i++) { 
							echo  "<th class='white-text step_label_". $i ."' width='10%'>Step ".$i."</th>";
						}
					?>
				</tr>
			</thead>
			<tbody id="tbody_matrix">
			<?php 
				if($amount)
				{
					for ($i=1; $i <= $salary['grade']; $i++) { 
					echo "<tr class='grade_label_". $i ."'> <td>". $i ." </td>";
					for ($j=1; $j <= $salary['step']; $j++) { 
						// echo "<td class='step_label_".$j."'> <input class='number' type='text' style='text-align:right;' name='amount[".$i."][".$j."]' value='".$amount[$i][$j]."'></td>";
						//===== marvin : start : include disable class for viewing =====//
						echo "<td class='step_label_".$j."'> <input class='number' type='text' style='text-align:right;' name='amount[".$i."][".$j."]' value='".$amount[$i][$j]."' ".($action == ACTION_VIEW ? 'disabled' : '')."></td>";
						//===== marvin : end : include disable class for viewing =====//
					}
					echo "</tr>";
				}
				}
				
			?>
			</tbody>
		</table>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button type="button" class="btn btn-success " id="save_salary_schedule" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>

//===== marvin : start : create new effectivity date row =====//
//create row
var row_counter = "<?php echo $row_counter; ?>";
if(row_counter == "")
{
	row_counter = 0;
}
else
{
	Number(row_counter++);
}
$("#add_new_row").on("click", function(){
	
	$.ajax({
		url : "<?php echo base_url() . 'main/code_library_hr/salary_schedule/create_row_effectivity_date_for/'; ?>" + row_counter,
		type : "POST",
		success : function(result){

			$("#col_effectivity_date_for").append(result);
			row_counter++;
		}
	});
});

//delete row
function delete_row_effectivity_date_for(id){

	$("#row_effectivity_date_for_" + id).remove();
}

//prevent duplicate
function prevent_duplicate(id){

	// $(".selectize-dropdown-content .option[data-value='77']").remove();
}

//===== marvin : end : create new effectivity date row =====//

$(function (){

	var previous_grade = parseInt(<?php echo isset($salary['grade']) ?  $salary['grade']:0?>);
	var previous_step = parseInt(<?php echo isset($salary['step']) ?  $salary['step']:0?>);
	
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

  	$('#salary_schedule_form').parsley();
  	$("#save_salary_schedule").off("click");
	$("#save_salary_schedule").on("click",function(e){
		
		var selected = $('input[name="active_flag"]:checked').val();

		if (selected)
		{
			$('#confirm_modal').confirmModal({
			    topOffset : 0,
			    onOkBut : function() {
			      	$('#salary_schedule_form').trigger('submit');
			    },
			    onCancelBut : function() {},
			    onLoad : function() {
			      $('.confirmModal_content h4').html('Are you sure you want to proceed?'); 
			      $('.confirmModal_content p').html('This action will deactivate current active salary schedule and cannot be undone.');
			    },
			    onClose : function() {}
			});
		}
		else
		{
			$('#salary_schedule_form').trigger('submit');
		}
		
	});

	
	jQuery(document).off('submit', '#salary_schedule_form');
	jQuery(document).on('submit', '#salary_schedule_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_salary_schedule', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/salary_schedule/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_salary_schedule.closeModal();
							load_datatable('salary_schedule_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/salary_schedule/get_salary_schedule_list/',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_salary_schedule', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  	// CHARACTER PREVENTS TO INPUT NOT VALID DIGIT
	$(".input-field").on('keydown', '#grade_count, #steps_count', function(e) {
		-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()
	})

	$('#generate_table').on( "click", function() {
		var steps_val = $("#steps_count").val();
		var grade_val = $("#grade_count").val();

		if(steps_val > 10) 
		{
			$('#steps_count').val('9');
			steps_val = $("#steps_count").val();

			if(steps_val != "" && grade_val != "")
			{
				set_matrix(grade_val, steps_val);
			}
		}

		if(steps_val < 10)
		{
			if(steps_val != "" && grade_val != "")
			{
				set_matrix(grade_val, steps_val);
			}
		}
	});

	function set_matrix(grade_val, steps_val) 
	{
		var fields_steps_hdr = "";
		var fields_grade_count = "";
		var fields_step_count = "";
		var fields_new_count = "";
		var grade_result = 0;
		var steps_result = 0;

		grade_result = grade_val - previous_grade;
		steps_result =  steps_val - previous_step;
		//FOR FIRST INPUT
		if (previous_grade === 0 && previous_step === 0) 
		{
			//STEP HEADER
			for($a = 0; $a < steps_result; $a++)
			{
				var cnt = parseInt($a) + 1;
				fields_steps_hdr += "<th class='white-text step_label_"+ cnt +"' width='10%'>Step "+ cnt +"</th>";
			}
			$("#thead_matrix").find("th:last").after(fields_steps_hdr);
			//GRADE COUNT
				for($x = 1; $x <= grade_result; $x++) 
				{
					var cnt = $x + previous_step;
					fields_grade_count = "<tr class='grade_label_"+ cnt +"'> <td>"+ cnt  + " </td></tr>";	
					fields_step_count = "";
					//
					for($y = 1; $y <= steps_result; $y++) 
					{
						fields_step_count = fields_step_count + "<td class='step_label_"+ $y +"'> <input class='number' type='text' style='text-align:right;' name='amount["+ cnt +"]["+ $y +"]' /> </td>";
					}
					
					$("#tbody_matrix").append(fields_grade_count);
					$(".grade_label_" + cnt).append(fields_step_count);		
				}
			

			previous_grade = grade_val;	
			previous_step = steps_val;
		}

		else if (grade_result > 0 || steps_result > 0) 
		{

			for($a = 0; $a < steps_result; $a++)
			{
				var cnt = parseInt($a) + parseInt(previous_step) + 1;
				fields_steps_hdr += "<th class='white-text step_label_"+ cnt +"' width='10%'>Step "+ cnt +"</th>";
			}
			$("#thead_matrix").find("th:last").after(fields_steps_hdr);

			if (grade_result > 0)
			{
				for($x = 1; $x <= grade_result; $x++) 
				{
					fields_step_count = "";
					var cnt = parseInt($x) + parseInt(previous_grade);
					fields_grade_count = "<tr class='grade_label_"+ cnt +"'> <td>"+ cnt  + " </td></tr>";			
					
					$("#tbody_matrix").append(fields_grade_count);
					
					for($b = 1; $b <= previous_step; $b++) 
					{
						fields_step_count = fields_step_count + "<td class='step_label_"+ $b +"'> <input class='number' type='text' style='text-align:right;' name='amount["+ cnt +"]["+ $b +"]' /> </td>";
					}
					
					$(".grade_label_"+cnt).append(fields_step_count);				
				
				}
			}
			if (steps_result > 0)
			{
				for($x = 1; $x <= previous_grade; $x++) 
				{
					fields_new_count = "";
					for($c = 1; $c <= steps_result; $c++) 
					{
						var cnt = parseInt($c) + parseInt(previous_step);
						fields_new_count = fields_new_count + "<td class='step_label_"+ cnt +"'> <input class='number' type='text' style='text-align:right;' name='amount["+ $x +"]["+ cnt +"]' /> </td>";
					}
				
					$(".grade_label_"+ $x ).append(fields_new_count);
				}
			}
		
			previous_grade = grade_val;	
			previous_step = steps_val;

		}

		else if (grade_result < 0 || steps_result < 0) 
		{
			if(grade_result < 0)
			{
				for($d = parseInt(grade_val); $d <= previous_grade; $d++)
				{
					var cnt = parseInt($d) + 1;
					$(".grade_label_"+cnt).remove();
				}
			}
			if(steps_result < 0) 
			{
				for($d = steps_val; $d <= previous_step; $d++)
				{
					var cnt = parseInt($d) + 1;
					$(".step_label_"+cnt).remove();
				}
			}
			
			previous_grade = grade_val;	
			previous_step = steps_val;
		}
		$('.number').number(true,2);
	}
})
</script>



