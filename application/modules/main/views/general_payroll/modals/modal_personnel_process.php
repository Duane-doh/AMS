<form id="payroll_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="id2" id="id2" value="<?php echo !EMPTY($id2) ? $id2 : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="gp_flag" id="gp_flag" value="<?php echo !EMPTY($gp_flag) ? $gp_flag : 'N'?>">

	<div class="form-float-label">
		<div class="row p-t-md p-l-md">
			<div class="col s4"><span class="font-lg font-spacing-15">Name : </span></div>
			<div class="col s8"><span class=" p-l-md font-lg font-spacing-15"> <b><?php echo $payout_header['employee_name'];?></b></span></div>
		</div>
		<div class="row p-t-md p-l-md" style="border: 0px !important">
			<div class="col s4"><span class="font-lg font-spacing-15" id="amount_label">Net Pay : </span></div>
			<div class="col s8"><span class=" p-l-md font-lg font-spacing-15"> <b id="total_amount_label">&#8369; <?php echo $total_amount;?></b></span></div>
		</div>
		<div class="row b-n m-b-n-lg p-t-md p-l-md">
			
		</div>
		<div class='col s3 switch new_record p-t-xl form-basic'>
			 <select id="dtl_type_flag" name="dtl_type_flag" class="selectize" placeholder="Compensation/Deduction">
			 	<option></option>
		 		<option value="C">Compensations</option>
				<option value="D">Deductions</option>
		 	 </select>
		</div>
		<div class='col s3 switch new_record p-t-xl form-basic'>
			 <select id="dtl_effective_date" class="selectize" placeholder="Effectivity Date">
			 	<option></option>
			 	<?php
			 		foreach($effective_date AS $e) {
			 			echo '<option value="' . $e . '">' . format_date($e,'F d, Y') . '</option>';
			 		}
			 	?>
		 	 </select>
		</div>
		<div class="p-sm p-t-md">
			<div class="p-r-sm p-t-xl">
				<?php if($action != ACTION_VIEW) : ?>
					<a class="btn btn-success right hide" id="add_details"><i class="flaticon-add175"></i> ADD COMPENSATION/DEDUCTION</a>
				<?php endif;?>
			</div>
			<div class="col s12 p-t-sm form-basic">
				<table class="striped table-default" id="details_table">
					<thead class="teal white-text">
						<tr>
							<td width = "15%" class="font-semibold">Type</td>
							<td width = "15%" class="font-semibold">Name</td>
							<td width = "15%" class="font-semibold">Payout Date <span class="required">*</span></td>
							<td width = "10%" class="font-semibold">Original Amount</td>
							<td width = "10%" class="font-semibold">Adjust<br/>Amount<span class="required">*</span></td>
							<td width = "10%" class="font-semibold">Total Amount</td>
							<td width = "20%" class="font-semibold">Remarks</td>
							<td width = "5%" class="font-semibold"></td>
						</tr>
					</thead>
					<tbody id="div_rows">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default p-t-sm">
		<?php if($action != ACTION_VIEW):?>
			<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_payout_details">Cancel</a>
			<button class="btn btn-success hide" id="save_payout_details" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php endif; ?>
	</div>
</form>

<table>
	<tr id="table_row" style="display:none!important">
		<td><p style="text-align: left" id="var_dtl_flag"><?php echo ''; ?> </p></td>
		<td class="form-basic"><select class="validate" required="" aria-required="true" name="new_payout_dtl_ids[]" id="new_payout_dtl_ids" placeholder="Select Compensation / Deduction Name"></select></td>
		<td class="form-basic">
			<select class="effective_date" name="new_payout_dates[]" id="new_payout_dates">
			 	<?php
			 		foreach($effective_date AS $e) {
			 			echo '<option value="' . $e . '">' . $e . '</option>';
			 		}
			 	?>
		 	 </select>
		</td>
		<td><input style="text-align: right" type="text" class="validate number orig_amount" required="" aria-required="true" name="new_orig_amounts[]" id="new_orig_amounts"></td>
		<td><input style="text-align: right" type="text" class="validate number less_amount" required="" aria-required="true" name="new_less_amounts[]" id="new_less_amounts"></td>
		<td><p class="right p-n">&nbsp;</p></td>
		<td><input style="text-align: right" type="text" name="new_remarks[]" id="new_remarks"></td>
		<td><div class="table-actions p-t-sm"><a class="delete tooltipped" data-tooltip="Delete" data-position="bottom" data-delay="50"></a></div></td>
		<input type="hidden" name="new_payout_dtl_flags[]" value='C'>
	</tr>
</table>

<script>
var payout_details_cnt     = <?php echo json_encode($payout_details) ?>;
var row_index              = payout_details_cnt.length;
var deleted_payout_details = [];

$(document).ready(function(){
	var payout_details = <?php echo json_encode($payout_details) ?>;
	var result = ''
	var action = <?php echo $action ?>;
	
	get_payout_details('','');
});
$('#dtl_type_flag, #dtl_effective_date').on('change', function(){
	var dtl_type_flag = $('#dtl_type_flag').val();
	var dtl_effective_date = $('#dtl_effective_date').val();
	
	get_payout_details(dtl_type_flag, dtl_effective_date);
	
});

$('#add_details').on('click', function()
{
	var clonerow = $("#table_row");
	
	// clone the row
	clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").appendTo("#div_rows");
	
	var newrow = $("#table_row_" + row_index);
	
	// assign id and name to selectize of newly created row 
	newrow.find('select').attr({
		id : 'dtl_type_id_' + row_index
	});
	newrow.find('p').attr({
		id : 'var_dtl_flag_' + row_index
	});

	newrow.find('input.less_amount').attr({
		id : 'less_amount_' + row_index,
		onchange: 'validate_less_amount(' + row_index + ')'
	});
	newrow.find('input.orig_amount').attr({
		id : 'orig_amount_' + row_index
	});
	var option = '';
	var dtl_type_flag = $('#dtl_type_flag').val();

	if(dtl_type_flag == 'C')
	{
		option = <?php echo json_encode($option_compensation)?>;
		$('#var_dtl_flag_' + row_index).text('Compensation');
	}
	else
	{
		option = <?php echo json_encode($option_deductions)?>;
		$('#var_dtl_flag_' + row_index).text('Deduction');
	}
	// $('#compensation_name_'+row_index).selectize.destroy();
	$('#dtl_type_id_'+row_index).html(option).selectize();
	
	newrow.find('a').attr({
		id: "remove_table_" + row_index ,
		onclick: "remove_table("+row_index+")"
	});
	newrow.find('select.effective_date').selectize();
	newrow.find('.number').number(true, 2);
	
	row_index++; 
});


function remove_table(row_index)
{
	$("#table_row_" + row_index).remove();

}

$(function (){
	$('#payroll_form').parsley();
	$('#payroll_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_payout_details', 1);
		  	var option = {
					url  : $base_url + 'main/payroll_general_tab/save_payout_details',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_payout_details").trigger('click');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_payout_details', 0);
					}
			};

			General.ajax(option);
	    }
  	});


  	
  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})

function get_payout_details(dtl_type_flag, dtl_effective_date)
{
	var payout_details = <?php echo json_encode($payout_details) ?>;
	var payout_dates = <?php echo json_encode($effective_date) ?>;
	var result = ''
	var action = <?php echo $action ?>;
	var total_amount = 0;
	if (dtl_type_flag == '')
	{
		$('#add_details').addClass('hide');
		$('#save_payout_details').addClass('hide');
	}
	else
	{
		if(dtl_type_flag == 'C') $('#add_details').html('ADD COMPENSATION');
		else if(dtl_type_flag == 'D') $('#add_details').html('ADD DEDUCTION');
		
		$('#add_details').removeClass('hide');
		$('#save_payout_details').removeClass('hide');
	}
	
	for(var i=0 ; i < payout_details.length; i++)
	{
		if(dtl_type_flag == '' && dtl_effective_date == '')
		{
			var included = true;
			$.each(deleted_payout_details, function(index, value) {
				   if(payout_details[i]['payroll_dtl_id'] == value)
				   included = false;
			});
			if(included)
			{
				result+= '<tr id="table_row_' + i + '"></tr>';
				result+='<td><p class="p-n">' + (payout_details[i]['dtl_flag'] == 'C' ? 'Compensation' : 'Deduction' ) + '</p></td>';
				result+='<td><p class="p-n">' + payout_details[i]['payout_dtl_name'] + '</p></td>';
				result+='<td><p class="center p-n">' + payout_details[i]['payout_date'] + '</p></td>';
				result+='<td><p class="right p-n">' + number_format(payout_details[i]['orig_amount'],2) + '</p></td>';
				result+='<td><p class="right p-n">' + number_format(payout_details[i]['less_amount'],2) + '</p></td>';
				result+='<td><p class="right p-n">' + number_format(payout_details[i]['amount'],2) + '</p></td>';
				result+='<td><p class="p-n">' + payout_details[i]['remarks'] + '</p></td>';
				result+='<input type="hidden" name="payout_dtl_ids[]" value="' + payout_details[i]['payout_dtl_id'] + '">';
				result+='<input type="hidden" name="payout_dtl_flags[]" value="' + payout_details[i]['dtl_flag'] + '">';
				result+='</tr>';
				if(payout_details[i]['dtl_flag'] == 'C') total_amount += +payout_details[i]['amount'];
				else if(payout_details[i]['dtl_flag'] == 'D') total_amount -= +payout_details[i]['amount'];
			}
		}
		else
		{
			var included = true;
			if(dtl_type_flag != '') {
				if(payout_details[i]['dtl_flag'] == dtl_type_flag) included = true;
				else included = false;
			}
			if(dtl_effective_date != '' && included) {
				if(payout_details[i]['payout_date'] == dtl_effective_date) included = true;
				else included = false;
			}
			if(included)
			{
				$.each(deleted_payout_details, function(index, value) {
				   if(payout_details[i]['payroll_dtl_id'] == value)
				   included = false;
				});
			}
			if(included)
			{

				var selectize_payout = '<select class="effective_date" name="payout_dates[]" id="payout_dates">'
			 	for(var j=0; j<payout_dates.length; j++) {
			 		var selected = '';
			 		if(payout_details[i]['payout_date'] == payout_dates[j]) {
			 			selected = 'selected';
			 		}
		 			selectize_payout += '<option value="' + payout_dates[j] + '" ' + selected + '>' + payout_dates[j] + '</option>';
			 	}
		 	 	selectize_payout += '</select>';

				result+= '<tr id="table_row_' + i + '"></tr>';
				result+='<td><p class="p-n">' + (payout_details[i]['dtl_flag'] == 'C' ? 'Compensation' : 'Deduction' ) + '</p></td>';
				result+='<td><p class="p-n">' + payout_details[i]['payout_dtl_name'] + '</p></td>';
				result+='<td>' + ( action == <?php echo ACTION_VIEW ?> || dtl_type_flag == '' ? ('<p class="center p-n">' + payout_details[i]['payout_date']+'</p>') : (selectize_payout)) + '</td>';
				result+='<td><p class="right p-n">'+number_format(payout_details[i]['orig_amount'],2)+'</p><input style="text-align: right" type="hidden" class="validate" required="" aria-required="true" name="orig_amounts[]" id="orig_amount_'+payout_details[i]['payout_dtl_id']+'" value="'+payout_details[i]['orig_amount']+'"></td>';
				result+='<td>' + ( action == <?php echo ACTION_VIEW ?> || dtl_type_flag == '' ? ('<p class="right p-n">'+number_format(payout_details[i]['less_amount'],2)+'</p>') : ('<input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="less_amounts[]" id="less_amount_'+payout_details[i]['payout_dtl_id']+'"  value="'+payout_details[i]['less_amount']+'" onchange="validate_less_amount(\''+payout_details[i]['payout_dtl_id']+'\')">')) + '</td>';
				result+='<td><p class="right p-n">'+number_format(payout_details[i]['amount'],2)+'</p><input style="text-align: right" type="hidden" class="validate" required="" aria-required="true" name="total_amounts[]" id="total_amounts_'+payout_details[i]['payout_dtl_id']+'" value="'+payout_details[i]['orig_amount']+'"></td>';
				if (payout_details[i]['dtl_flag'] == 'C')
					result+='<td>' + ( action == <?php echo ACTION_VIEW ?> || dtl_type_flag == '' ? ('<p class="p-n">' + payout_details[i]['remarks']+'</p>') : ('<input style="text-left: right" type="text" aria-required="true" name="remarks[]" value="'+payout_details[i]['remarks_compensation']+'">')) + '</td>';
				else
					result+='<td>' + ( action == <?php echo ACTION_VIEW ?> || dtl_type_flag == '' ? ('<p class="p-n">' + payout_details[i]['remarks']+'</p>') :('<input style="text-left: right" type="text" aria-required="true" name="remarks[]" value="'+payout_details[i]['remarks_deduction']+'">')) + '</td>';
				if (payout_details[i]['sys_flag'] == 'N')
					result+='<td><div class="table-actions p-t-sm"><a class="delete tooltipped" data-tooltip="Delete" data-position="bottom" data-delay="50" onclick="delete_saved_record('+payout_details[i]['payroll_dtl_id']+',this)"></a></div></td>';
				result+='<input type="hidden" name="payout_dtl_ids[]" value="' + payout_details[i]['payout_dtl_id'] + '">';
				result+='<input type="hidden" name="payout_dtl_flags[]" value="' + payout_details[i]['dtl_flag'] + '">';
				result+='</tr>';
				if(dtl_type_flag == '') {
					if(payout_details[i]['dtl_flag'] == 'C') total_amount += +payout_details[i]['amount'];
					else if(payout_details[i]['dtl_flag'] == 'D') total_amount -= +payout_details[i]['amount'];
				} else {
					total_amount += +payout_details[i]['amount'];
				}


				row_index++;
			}

		}
		if(dtl_type_flag == '') $('#amount_label').html('Net Pay: ');
		else {
			if(dtl_type_flag == 'C') $('#amount_label').html('Total Compensations: ');
			else if(dtl_type_flag == 'D') $('#amount_label').html('Total Deductions: ');
		} 
		$('#total_amount_label').html('&#8369; ' + number_format(total_amount,2));
		$('.tooltipped').tooltip({delay: 50});
	}
	
	$('#div_rows').html(result);
	if(dtl_type_flag != '') {
		$('#div_rows select.effective_date').selectize();
		$('input.number').number(true,2);
	}
	
}
function delete_saved_record(payroll_dtl_id,that)
{	
	var dtl_type_flag = $('#dtl_type_flag').val();
	var alert_text = 'Deduction';
	if(dtl_type_flag == 'C')
	{
		alert_text = 'Compensation';
	}
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			var option = {
					url  : $base_url + 'main/payroll_general_tab/delete_payout_details',
					data : {'payroll_dtl_id':payroll_dtl_id},
					success : function(result){
						if(result.status)
						{
							deleted_payout_details.push(payroll_dtl_id);
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$(that).closest('tr').remove();
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
					}
			};

			General.ajax(option);
			
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Are you sure you want to delete this ' + alert_text + '?');	
			$('.confirmModal_content p').html('This action will permanently delete this record from the database and cannot be undone.');
		},
		onClose : function() {}
	});
}
// function validate_less_amount($index) {
// 	var orig_amount = $('#orig_amount_'+$index).val();
// 	var less_amount = $('#less_amount_'+$index).val();

// 	if(parseFloat(less_amount) > parseFloat(orig_amount)) $('#less_amount_'+$index+'').val(orig_amount);
// }

</script>