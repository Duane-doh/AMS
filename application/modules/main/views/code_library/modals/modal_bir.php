<form id="bir_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row b-b b-light-gray">
		  <div class="col s4">
			<div class="input-field">
				<input type="text" class="validate datepicker" required name="effective_date" id="effective_date" 
				   	   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effective_date')"
					   value="<?php echo isset($bir_info[0]['effective_date']) ? format_date($bir_info[0]['effective_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="effective_date">Effectivity Date <?php echo $action != ACTION_VIEW ? '<span class="required">*</span>' : '' ?></label>
			</div>
		  </div>
		  <div class='col s4 '>
		    <div class="input-field">
				<label for="tax_table_flag" class="active">Tax Table Flag <span class="required">&nbsp;*</span></label>
				<select id="tax_table_flag" name="tax_table_flag" required class="selectize validate" placeholder="Select Tax Table Flag...">
					<option></option>
					<?php foreach($bir_tax_table AS $tax_table): ?>
						<option <?php  if($tax_table['sys_param_value'] == $bir_info[0]['tax_table_flag']){echo selected; }?> value="<?php echo $tax_table['sys_param_value']?>"><?php echo strtoupper(ISSET($tax_table['sys_param_value'])) ? strtoupper($tax_table['sys_param_name']) : "" ?></option>
					<?php endforeach; ?>
			 	</select>
			</div>
		  </div>
		  <div class='col s4 switch p-t-lg'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($bir_info[0]['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		  </div>
		</div>
		
		<div>
			<div class="p-r-sm p-t-md">
				<?php if($action != ACTION_VIEW) : ?>
				<a class="btn btn-success right" id="add_details"><i class="flaticon-add176"></i>Add</a>
			 	<?php endif;?>
			</div>
			<div class="col s12 p-t-sm form-basic">
				<table class="striped table-default" id="details_table">
					<thead class="teal white-text">
						<tr>
							<td width="20%" style="font-size: 12px;"><b>MINIMUM AMOUNT</b></td>
							<td width="20%" style="font-size: 12px;"><b>MAXIMUM AMOUNT</b></td>
							<td width="20%" style="font-size: 12px;"><b>TAX AMOUNT</b></td>
							<td width="20%" style="font-size: 12px;"><b>TAX RATE</b></td>
							<td class="none status_code" id="" width="20%" style="font-size: 12px;"><b>EXEMPT STATUS CODE</b></td>
							<td class="none flags" id="" width="20%" style="font-size: 12px;"><b>PROFESSIONAL</b></td>
							<td class="none flags" id="" width="20%" style="font-size: 12px;"><b>NON-PROFESSIONAL</b></td>
							<td class="none flags" id="" width="20%" style="font-size: 12px;"><b>VAT</b></td>
							<td width="10%" style="font-size: 12px;"><b>ACTION</b></td>
						</tr>
					</thead>
					<tbody id="div_rows">
					<?php if($action == ACTION_ADD) { ?>
						<tr id="table_row_0">
							<td><input style="text-align: right" type="text" class="validate right number" required="" aria-required="true" name="min_amount[]"></td>
							<td><input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="max_amount[]"></td>
							<td><input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="tax_amount[]"></td>
							<td><input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="tax_rate[]"></td>
							<td class="none status_code"><input style="text-align: right" type="text" class="validate"  name="exempt_status_code[]" id="exempt_status_code"></td>
							<td class="none flags">								
								<div class="switch">
									<label>
										No
										<input name="professional_flag[0]" type="checkbox"  value="Y">
										<span class='lever'></span>Yes
									</label>
								</div>
							</td>
							<td class="none flags">						
								<div class="switch">									
									<label>
										No
										<input name="non_professional_flag[0]" type="checkbox"  value="Y">
										<span class='lever'></span>Yes
									</label>
								</div>
							</td>
							<td class="none flags">						
								<div class="switch">
									<label>
										No
										<input name="vat_flag[0]" type="checkbox"  value="Y">
										<span class='lever'></span>Yes
									</label>
								</div>
							</td>
							<td></td>
						</tr>
					<?php 
					}
					else
					{
						foreach($bir_info AS $key => $bir)
						{
							$p_flag  = '';
							$np_flag = '';
							$v_flag  = '';
							$p_flag  = ($bir["professional_flag"] == "Y") ? " checked " : "";
							$np_flag = ($bir["non_professional_flag"] == "Y") ? " checked " : "";
							$v_flag  = ($bir["vat_flag"] == "Y") ? " checked " : "";

							echo '<tr id="table_row_' . $key . '">'
								. '<td>' . ($action == ACTION_VIEW ? '<p class="p-n right">&#8369; ' . $bir['min_amount'] . '</p>' : '<input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="min_amount[]" value="' . $bir['min_amount'] . '"> ') . '</td>'
								. '<td>' . ($action == ACTION_VIEW ? '<p  class="p-n right">&#8369; ' . $bir['max_amount'] . '</p>' : '<input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="max_amount[]" value="' . $bir['max_amount'] . '"> ') . '</td>'
								. '<td>' . ($action == ACTION_VIEW ? '<p  class="p-n right">&#8369; ' . $bir['tax_amount'] . '</p>' : '<input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="tax_amount[]" value="' . $bir['tax_amount'] . '"> ') . '</td>'
								. '<td>' . ($action == ACTION_VIEW ? '<p class="p-n right">' . $bir['tax_rate'] . '</p>' : '<input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="tax_rate[]" value="' . $bir['tax_rate'] . '"> ') . '</td>'
								. '<td class="none status_code">' . ($action == ACTION_VIEW ? '<p class="p-n right">' . $bir['exempt_status_code'] . '</p>' : '<input style="text-align: right" type="text" class="validate"  name="exempt_status_code[]" id="exempt_status_code" value="' . $bir['exempt_status_code'] . '"> ') . '</td>'
								. '<td class="none flags">'								
									. '<div class="switch p-md">'							
											. '<label>'
												. 'No'
												. '<input class="professional_flag" name="professional_flag['.$key.']" type="checkbox"  value="Y"' . $p_flag . '>'
												. '<span class="lever"></span>Yes'
											. '</label>'
									. '</div>'
								. '</td>'
								. '<td class="none flags">'						
									. '<div class="switch p-md">'							
									. '<label>'
										. 'No'										
										. '<input class="non_professional_flag" name="non_professional_flag['.$key.']" type="checkbox"  value="Y"' . $np_flag . '>'
										. '<span class="lever"></span>Yes'
									. '</label>'
									. '</div>'
								. '</td>'
								. '<td class="none flags">'						
									. '<div class="switch p-md">'							
									. '<label>'
										. 'No'
										. '<input class="vat_flag" name="vat_flag['.$key.']" type="checkbox"  value="Y"' . $v_flag . '>'
										. '<span class="lever"></span>Yes'
									. '</label>'
									. '</div>'
								. '</td>'			
								. '<td> ' . ($action == ACTION_VIEW ? '' : '<div class="table-actions p-t-sm"><a id="remove_table_' . $key .'" href="javascript:;" onclick="remove_table(' . $key . ')" class="delete tooltipped" data-tooltip="Delete" data-position="bottom" data-delay="50"></a></div>') . '</td></tr>';
						}

					} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default">
	  	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  	<?php if($action != ACTION_VIEW):?>
		    <button class="btn btn-success " id="save_bir" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<table>
	<tr id="table_row" style="display:none!important">
		<td><input style="text-align: right" type="text" class="validate number" required name="min_amount[]" id="min_amount"></td>
		<td><input style="text-align: right" type="text" class="validate number" required name="max_amount[]" id="max_amount"></td>
		<td><input style="text-align: right" type="text" class="validate number" required name="tax_amount[]" id="tax_amount"></td>
		<td><input style="text-align: right" type="text" class="validate number" required name="tax_rate[]" id="tax_rate"></td>
		<td class="none status_code"><input style="text-align: right" type="text" class="validate"  name="exempt_status_code[]" id="exempt_status_code"></td>
		<td class="none flags">								
		<div class="switch">
				<label>
					No
					<input class="professional_flag" name="professional_flag" type="checkbox"  value="Y">
					<span class='lever'></span>Yes
				</label>
			</div>
		</td>
		<td class="none flags">						
			<div class="switch">									
				<label>
					No
					<input class="non_professional_flag" name="non_professional_flag" type="checkbox"  value="Y">
					<span class='lever'></span>Yes
				</label>
			</div>
		</td>
		<td class="none flags">						
			<div class="switch">
				<label>
					No
					<input class="vat_flag" name="vat_flag" type="checkbox"  value="Y">
					<span class='lever'></span>Yes
				</label>
			</div>
		</td>
		<td><div class="table-actions p-t-sm"><a id="remove_table" href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div></td>
	</tr>
</table>

<script>
var row_index = $('#details_table tr').length - 1;

$('#add_details').on('click', function()
{
	var clonerow = $("#table_row");
	
	
	// clone the row
	clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").appendTo("#div_rows");
	// handleSelect2();
	
	var newrow = $("#table_row_" + row_index);
	$('.number').number(true,2);
	// assign id and name to selectize of newly created row 
	newrow.find('input.vat_flag').attr({
		name: 'vat_flag[' + row_index + ']'
	});

	newrow.find('input.professional_flag').attr({
		name: 'professional_flag[' + row_index + ']'
	});
	newrow.find('input.non_professional_flag').attr({
		name: 'non_professional_flag[' + row_index + ']'
	});
	newrow.find('a').attr({
		id: "remove_table_" + row_index ,
		onclick: "remove_table("+row_index+")"
	});
	
	row_index++; 
});

$('#tax_table_flag').on('change', function()
{
	var selected = $(this).val();
		// ====================== jendaigo : start : include 'selected' w/ str value 'MONTHLY-2307' ============= //
		if(selected.indexOf('MONTHLY-2307') >= 0)
		{
			selected = 'MONTHLY-2307';
		}
		// ====================== jendaigo : end : include 'selected' w/ str value 'MONTHLY-2307' ============= //
		
		if(selected === 'ANNUAL')
		{
			$('.status_code').addClass('none');
			$('#exempt_status_code').attr("required", false);
			$('.flags').addClass('none');
		}
		else if(selected === 'MONTHLY-2307')
		{
			$('.status_code').removeClass('none');
			$('.flags').removeClass('none');
			$('#exempt_status_code').attr("required", false);
		}
		else
		{
			$('.status_code').removeClass('none');
			$('.flags').addClass('none');
			$('#exempt_status_code').attr("required", true);
		}
	
});


function remove_table(row_index)
{
	$("#table_row_" + row_index).remove();
}

$(function (){
	$('#bir_form').parsley();
	$('#bir_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_bir', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/bir/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_bir.closeModal();
							load_datatable('bir_table_dt', '<?php echo PROJECT_MAIN ?>/code_library_payroll/bir/get_bir_list',false,false,false,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_bir', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	<?php if($action == ACTION_VIEW): ?>
		$('.professional_flag').attr("disabled", true);
		$('.non_professional_flag').attr("disabled", true);
		$('.vat_flag').attr("disabled", true);
  	<?php endif; ?>

  	<?php if($action != ACTION_ADD): ?>
		$('.input-field label').addClass('active');
		<?php if ($bir_info[0]['tax_table_flag'] == "ANNUAL"):?>			
			$('.status_code').addClass('none');
			$('#exempt_status_code').attr("required", false);
			$('.flags').addClass('none');
		<?php elseif ($bir_info[0]['tax_table_flag'] == 'MONTHLY-2307'):?>
			$('.status_code').removeClass('none');
			$('.flags').removeClass('none');
			$('#exempt_status_code').attr("required", false);
		// ====================== jendaigo : start : include checking of info w/ str value 'MONTHLY-2307' ============= //	
		<?php elseif(strpos($bir_info[0]['tax_table_flag'], 'MONTHLY-2307') !== false):?>
			$('.status_code').removeClass('none');
			$('.flags').removeClass('none');
			$('#exempt_status_code').attr("required", false);
		// ====================== jendaigo : end : include checking of info w/ str value 'MONTHLY-2307' ============= //
		<?php else: ?>
			$('.status_code').removeClass('none');
			$('.flags').addClass('none');
			$('#exempt_status_code').attr("required", true);
		<?php endif;?>
  	<?php endif; ?>
})
</script>