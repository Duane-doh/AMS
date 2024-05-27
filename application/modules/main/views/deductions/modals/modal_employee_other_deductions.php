<form id="form_other_deductions" class="m-b-md">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="has_permission" id="has_permission" value="<?php echo !EMPTY($has_permission) OR $action == ACTION_ADD ? 1 : NULL?>">
	<div class="form-float-label p-b-lg" id="other_deductions_div">
		<div class="row">
		  	<div class="col s12">
				<div class="input-field">
					<label class="active" for="deduction_id">Deduction Type<span class="required">&nbsp;*</span></label>
					<select required="" id="deduction_id" name="deduction_id" class="selectize field" placeholder="Select Deduction" <?php echo $action == ACTION_VIEW ? 'disabled' : ''?>>
					 <option value="">Select Deductions</option>
					 <?php if (!EMPTY($deduction_types)): ?>
						<?php foreach ($deduction_types as $deduction): ?>
							<option value="<?php echo $deduction['deduction_id'] . '|' . $deduction['deduction_type_flag'] ?>" <?php echo ($deduction['deduction_id'] == $deduction_info['deduction_id'] ? ' selected' : '')?> ><?php echo strtoupper($deduction['deduction_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row" id="extras">
		  	<div class="col s6">
				<div class="input-field">
					<label for="start_date" class="active">Start Date<span class="required">&nbsp;*</span></label>
					<input required="" id="start_date" name="start_date" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'start_date')" type="text" class="validate datepicker" value="<?php echo isset($deduction_info['start_date']) ? format_date($deduction_info['start_date']) : NULL; ?>">
				</div>
			</div>
			<?php 
				// if($deduction_info['deduction_type_flag'] != 'S') : 
				
				// ====================== jendaigo : start : exclude EWT, GMP & 8% Income Tax deduction IDs ============= //
				$excude_deduc = array(DEDUC_BIR_EWT,DEDUC_BIR_VAT,DEDUC_BIR_EIGHT);
				if($deduction_info['deduction_type_flag'] != 'S' AND !in_array($deduction_info['deduction_id'], $excude_deduc)) : 
				// ====================== jendaigo : end : exclude EWT, GMP & 8% Income Tax deduction IDs ============= //
			?>
				<div class="col s6 add_fields">
					<div class="input-field">
						<label for="payment_count" id="payment_label" class="active">Number of Payments<span class="required">&nbsp;*</span></label>
						<input required="" id="payment_count" name="payment_count" type="text" class="validate" value="<?php echo isset($deduction_info['payment_count']) ? $deduction_info['payment_count'] : NULL; ?>">
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php
		if($action != ACTION_ADD && !EMPTY($deduction_other_details_info)) {
			for($i=0; $i < count($deduction_other_details_info); $i++) {
				if($deduction_other_details_info[$i]['other_detail_type'] == 'YN') {
					echo  '<div class="row add_fields">'
						 . 	'<div class="col s12">'
						 .  	'<div class="switch p-md">'
						 .			 $deduction_other_details_info[$i]['other_detail_name'] . '<br><br>'
						 .   		'<label>'
						 .	    	'No'
						 .	        '<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_value'] . '" name="other_deduction_details[]" type="checkbox" value="N">' 
						 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_id'] . '" name="other_deduction_details[]" type="hidden">'
						 .	        '<span class="lever"></span>'
						 .	        'Yes'
						 .    		'</label>'
						 .		'</div>'
						 .	'</div>'
						 . '</div>';
				}
				
				//DISPLAY DROPDOWN
				if($deduction_other_details_info[$i]['other_detail_type'] == 'DR') {
					echo  '<div class="row add_fields">' 
						 . 	'<div class="col s12 additional_fields">'
				    	 .		'<div class="input-field">'
				    	 .			'<label for="' . $deduction_other_details_info[$i]['other_detail_name'] . '" class="active">'
				    	 . 				$deduction_other_details_info[$i]['other_detail_name']
				    	 .			'</label>'
				   		 .		'</div>'
				      	 .	'</div>'
				      	 . '</div>';

				}

				//DISPLAY CHARACTER
				if($deduction_other_details_info[$i]['other_detail_type'] == 'C') { 
					/*
					echo  '<div class="row add_fields">'	
						 . 	'<div class="col s12 additional_fields">'
						 .    	'<div class="input-field">'
						 .       	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_value'] . '" class="validate" ' . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? ' required="" ' : '') . ' type="text" name="other_deduction_details[]" value="">'
						 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_id'] . '" name="other_deduction_details[]" type="hidden">'
						 .       	'<label for="' . $deduction_other_details_info[$i]['other_detail_name'] . '" class="active">' . $deduction_other_details_info[$i]['other_detail_name'] . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') . '</label>'
						 .      '</div>'
						 .	'</div>'
						 . '</div>';
					*/
					// ====================== jendaigo : start : include ID type format ============= //
					if($employee_identification[strtoupper($deduction_other_details_info[$i]['other_detail_name'])] OR $deduction_other_details_info[$i]['deduction_id'] == DEDUC_HMDF2_JO)
					{
						$other_deduction_detail_id 	= $deduction_other_details_info[$i]['other_deduction_detail_id'];
						$identification_format 		= ($deduction_other_details_info[$i]['deduction_id'] == DEDUC_HMDF2_JO ? $mp2_sys['sys_param_value'] : $employee_identification[strtoupper($deduction_other_details_info[$i]['other_detail_name'])]['format']);
						$view_only 					= ($deduction_other_details_info[$i]['deduction_id'] == DEDUC_HMDF2_JO ? '' : 'readOnly');
						
						echo  '<div class="row add_fields">'	
								 . 	'<div class="col s12 additional_fields">'
								 .    	'<div class="input-field">'
								 .       	'<input id="' . $other_deduction_detail_id . '" value="' . format_identifications($deduction_other_details_info[$i]['other_deduction_detail_value'], $identification_format) . '" class="validate" ' . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? ' required="" ' : '') . ' type="text" name="other_deduction_details[]" '. $view_only .' onkeypress="format_identifications(\''. $identification_format .'\',this.value,event,\''. $other_deduction_detail_id .'\')" onkeydown="return (event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95 <event.keyCode && event.keyCode<106) || (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode 44 && event.charCode > 39))">'
								 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_id'] . '" name="other_deduction_details[]" type="hidden">'
								 .       	'<input value="' . $other_deduction_detail_id . '|' . $identification_format . '" name="identification_type_id" type="hidden">'
								 .       	'<label for="' . $deduction_other_details_info[$i]['other_detail_name'] . '" class="active">' . $deduction_other_details_info[$i]['other_detail_name'] . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') . '</label>'
								 .      '</div>'
								 .	'</div>'
								 . '</div>';
					}
					else
					{
						echo  '<div class="row add_fields">'	
							 . 	'<div class="col s12 additional_fields">'
							 .    	'<div class="input-field">'
							 .       	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_value'] . '" class="validate" ' . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? ' required="" ' : '') . ' type="text" name="other_deduction_details[]" value="">'
							 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_id'] . '" name="other_deduction_details[]" type="hidden">'
							 .       	'<label for="' . $deduction_other_details_info[$i]['other_detail_name'] . '" class="active">' . $deduction_other_details_info[$i]['other_detail_name'] . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') . '</label>'
							 .      '</div>'
							 .	'</div>'
							 . '</div>';
					}
					// ====================== jendaigo : end : include ID type format ============= //
				}

				//DISPLAY NUMBER
				if($deduction_other_details_info[$i]['other_detail_type'] == 'N') {
					echo  '<div class="row add_fields">'	
						 . 	'<div class="col s12">'
						 .    	'<div class="input-field">'
						 .       	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_value'] . '" class="validate number" ' . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? ' required="" ' : '') . ' type="text" name="other_deduction_details[]" id="' . $deduction_other_details_info[$i]['other_detail_name'] . '" value="">'
						 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_id'] . '" name="other_deduction_details[]" type="hidden">'
						 .       	'<label for="' . $deduction_other_details_info[$i]['other_detail_name'] . '" class="active">' . $deduction_other_details_info[$i]['other_detail_name'] .  ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') . '</label>'
						 .     '</div>'
						 . 	'</div>'
						 . '</div>';
				} 

				//DISPLAY DATE
				if($deduction_other_details_info[$i]['other_detail_type'] == 'D') {
					echo  '<div class="row add_fields">'	
						 .	'<div class="col s12">'
						 .  	'<div class="input-field">'
						 .      	'<label for="' . $deduction_other_details_info[$i]['other_detail_name'] . '" class="active">' . $deduction_other_details_info[$i]['other_detail_name'] . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') . '</label>'
						 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_value'] . '" class="validate datepicker" ' . ($deduction_other_details_info[$i]['required_flag'] == 'Y' ? ' required="" ' : '') . ' id="' . $deduction_other_details_info[$i]['other_detail_name'] . '" name="other_deduction_details[]" type="text" >'
						 .      	'<input value="' . $deduction_other_details_info[$i]['other_deduction_detail_id'] . '" name="other_deduction_details[]" type="hidden">'
						 .   	'</div>'
						 . 	'</div>'
						 .'</div>';
				}
			}
		}

		if($action != ACTION_ADD) { 
			if($deduction_info['deduction_type_flag'] == 'S') {
				if($action != ACTION_VIEW)
				echo 	'<div class="row add_fields" id="add_payments_btn">'
				.			'<div class="p-r-sm">'	
				.					'<a class="btn btn-success right" id="add_payments"><i class="flaticon-add175"></i> ADD SCHEDULE</a>'
				. 			'</div>'
				.		'</div>';
				
				for($i=0; $i < count($deduction_details_info); $i++) {
					// echo	'<div id="table_row_' . $i . '" class="row add_fields">'
				// .				'<div class="col s5">'
				// .					'<div class="input-field">'
				// .						'<input value="' . $deduction_details_info[$i]['payment_count'] . '" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl_' . $i . '" style="text-align: right"/>'
				// .						'<label class="active" for="payment_count_dtl_' . $i . '">Number of Payment<span class="required">&nbsp;*</span></label>'
				// .					'</div>'
				// .				'</div>'
				// .				'<div class="col s5">'
				// .					'<div class="input-field">'
				// .						'<input value="' . $deduction_details_info[$i]['amount'] . '" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount_' . $i . '" style="text-align: right"/>'
				// .						'<label class="active" for="amount_' . $i . '">Amount<span class="required">&nbsp;*</span></label>'
				// .					'</div>'
				// .				'</div>';
				
				// ===================== jendaigo : start : change overpay details fields ============= //
						echo	'<div id="table_row_' . $i . '" class="row add_fields">';
						
				$not_editable 			= ($deduction_details_info[$i]['payment_count'] == $deduction_details_info[$i]['paid_count']) ? 'cursor: default !important; color: lightgray !important; pointer-events:none;' : '';
				$fields_not_editable 	= ($deduction_details_info[$i]['paid_count'] != 0) ? 'cursor: default !important; color: lightgray !important; pointer-events:none;' : '';
				if($deduction_info['deduction_id'] == DEDUC_OVERPAY_JO)
				{ 
					echo		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input ' . $deduction_details_info[$i]['payment_count'] . ' value="' . $deduction_details_info[$i]['payment_count'] . '" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl_' . $i . '" style="text-align: right; ' . $not_editable . ' "/>'
						.				'<label class="active" for="payment_count_dtl_' . $i . '">No. of Payment<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input value="' . $deduction_details_info[$i]['paid_count'] . '" type="text" class="validate" required="" aria-required="true" name="paid_count_dtl[]" id="paid_count_dtl_' . $i . '" style="text-align: right; color: lightgray; cursor: default;" readOnly />'
						.				'<label class="active" for="paid_count_dtl_' . $i . '">Paid count<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col ' . ($action == ACTION_VIEW ? 's2' : 's1') . '">'
						.			'<div class="input-field">'
						.				'<input value="' . $deduction_details_info[$i]['amount'] . '" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount_' . $i . '" style="text-align: right; ' . $fields_not_editable . ' " />'
						.				'<label class="active" for="amount_' . $i . '">Amount<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input value="' . format_date($deduction_details_info[$i]['start_date']) . '" onkeypress="format_identifications(\''. DATE_FORMAT .'\',this.value,event,\'detail_start_date_' . $i . '\')" type="text" class="validate datepicker" required="" aria-required="true" name="detail_start_date[]" id="detail_start_date_' . $i . '" style="' . $fields_not_editable . ' " />'
						.				'<label class="active" for="detail_start_date_' . $i . '">Start of Deduction<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<select required="" id="deduction_detail_type_id_' . $i . '" name="deduction_detail_type_id[]" class="selectize field" placeholder="Select Type"' . (($action == ACTION_VIEW OR ($fields_not_editable)) ? 'disabled' : '') . '>'
						.				 	'<option value="">Select Type</option>';
											foreach ($deduction_detail_types as $deduction_detail_type):
					echo					'<option value="' . $deduction_detail_type['deduction_detail_type_id'] . '" ' . ($deduction_detail_type['deduction_detail_type_id'] == $deduction_details_info[$i]['deduction_detail_type_id'] ? ' selected' : '') . ' > ' . strtoupper($deduction_detail_type['deduction_detail_type_name']) . '</option>';
											endforeach;
					echo				'</select>'
						.				'<label class="active" for="deduction_detail_type_id_' . $i . '">Type<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input value="' . $deduction_details_info[$i]['remarks'] . '" type="text" class="validate" required="" aria-required="true" name="remarks[]" id="remarks_' . $i . '" style="' . $not_editable . ' " />'
						.				'<label class="active" for="remarks_' . $i . '">Remarks<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>';
				}
				else
				{
					echo		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input ' . $deduction_details_info[$i]['payment_count'] . ' value="' . $deduction_details_info[$i]['payment_count'] . '" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl_' . $i . '" style="text-align: right; ' . $not_editable . ' "/>'
						.				'<label class="active" for="payment_count_dtl_' . $i . '">No. of Payment<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input value="' . $deduction_details_info[$i]['paid_count'] . '" type="text" class="validate" required="" aria-required="true" name="paid_count_dtl[]" id="paid_count_dtl_' . $i . '" style="text-align: right; color: lightgray; cursor: default;" readOnly />'
						.				'<label class="active" for="paid_count_dtl_' . $i . '">Paid count<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input value="' . $deduction_details_info[$i]['amount'] . '" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount_' . $i . '" style="text-align: right; ' . $fields_not_editable . ' " />'
						.				'<label class="active" for="amount_' . $i . '">Amount<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s2">'
						.			'<div class="input-field">'
						.				'<input value="' . format_date($deduction_details_info[$i]['start_date']) . '" onkeypress="format_identifications(\''. DATE_FORMAT .'\',this.value,event,\'detail_start_date_' . $i . '\')" type="text" class="validate datepicker" required="" aria-required="true" name="detail_start_date[]" id="detail_start_date_' . $i . '" style="' . $fields_not_editable . ' " />'
						.				'<label class="active" for="detail_start_date_' . $i . '">Start of Deduction<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>'
						.		'<div class="col s3">'
						.			'<div class="input-field">'
						.				'<input value="' . $deduction_details_info[$i]['remarks'] . '" type="text" class="validate" required="" aria-required="true" name="remarks[]" id="remarks_' . $i . '" style="' . $not_editable . ' " />'
						.				'<label class="active" for="remarks_' . $i . '">Remarks<span class="required">&nbsp;*</span></label>'
						.			'</div>'
						.		'</div>';
				}
				// ===================== jendaigo : end : change overpay details fields ============= //
				
				if($action != ACTION_VIEW) {
					echo			'<div class="col s1">' //jendaigo: change width
						.				'<div class="input-field">'
						.				'<a class="m-n" id="remove_table_' . $i . '" onclick="remove_table(' . $i . ')" style="color:grey !important; ' . $fields_not_editable . '" ><i class="flaticon-recycle69"></i></a>'
						.			'</div>'
						.		'</div>';
				}
				echo		'</div>';
				}
			}
		}
	?>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW) : ?>
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel">Cancel</a>
	   	<button class="btn" id="save_other_deduction" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php endif;?>
	</div>
</form>
<!--
<div id="table_row" class="row" style="display:none"> 
	<div class="col s5">
		<div class="input-field">
			<input style="text-align:right" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl" />
			<label class="active" for="payment_count_dtl">Number of Payment<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s5">
		<div class="input-field">
			<input style="text-align:right" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount" />
			<label class="active" for="amount">Amount<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<a class="m-n" id="remove_table" href='javascript:;' onclick='' style="color:grey !important" ><i class="flaticon-recycle69"></i></a>
		</div>
	</div>
</div>
-->
<!-- ===================== jendaigo : start : modify scheduled type deductions fields ============= -->
<div id="table_row" class="row" style="display:none"> 
	<div class="col s2">
		<div class="input-field">
			<input style="text-align:right" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl" />
			<label class="active" for="payment_count_dtl">No. of Payment<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<input style="text-align:right; color: lightgray;" type="text" class="validate" required="" aria-required="true" name="paid_count_dtl[]" id="paid_count_dtl" value="0" readOnly />
			<label class="active" for="paid_count_dtl">Paid count<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<input style="text-align:right" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount" />
			<label class="active" for="amount">Amount<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<input type="text" class="validate datepicker" required="" aria-required="true" name="detail_start_date[]" id="detail_start_date" />
			<label class="active" for="detail_start_date">Start of Deduction<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s3">
		<div class="input-field">
			<input type="text" class="validate" required="" aria-required="true" name="remarks[]" id="remarks"/>
			<label class="active" for="remarks">Remarks<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s1">
		<div class="input-field">
			<a class="m-n" id="remove_table" href='javascript:;' onclick='' style="color:grey !important" ><i class="flaticon-recycle69"></i></a>
		</div>
	</div>
</div>

<div id="table_row2" class="row" style="display:none"> 
	<div class="col s2">
		<div class="input-field">
			<input style="text-align:right" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl" />
			<label class="active" for="payment_count_dtl">No. of Payment<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<input style="text-align:right; color: lightgray;" type="text" class="validate" required="" aria-required="true" name="paid_count_dtl[]" id="paid_count_dtl" value="0" readOnly />
			<label class="active" for="paid_count_dtl">Paid count<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s1">
		<div class="input-field">
			<input style="text-align:right" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount" />
			<label class="active" for="amount">Amount<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<input type="text" class="validate datepicker" required="" aria-required="true" name="detail_start_date[]" id="detail_start_date" />
			<label class="active" for="detail_start_date">Start of Deduction<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<select class="selectize field" required="" name="deduction_detail_type_id[]" placeholder="Select Type" id="deduction_detail_type_id">
			 <option value="">Select Type</option>
			 <?php if (!EMPTY($deduction_detail_types)): ?>
				<?php foreach ($deduction_detail_types as $deduction_detail_type): ?>
					<option value="<?php echo $deduction_detail_type['deduction_detail_type_id'] ?>"><?php echo strtoupper($deduction_detail_type['deduction_detail_type_name']) ?></option>
				<?php endforeach;?>
			<?php endif;?>
			</select>
			<label class="active" for="deduction_detail_type_id">Type<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s2">
		<div class="input-field">
			<input type="text" class="validate" required="" aria-required="true" name="remarks[]" id="remarks"/>
			<label class="active" for="remarks">Remarks<span class="required">&nbsp;*</span></label>
		</div>
	</div>
	<div class="col s1">
		<div class="input-field">
			<a class="m-n" id="remove_table" href='javascript:;' onclick='' style="color:grey !important" ><i class="flaticon-recycle69"></i></a>
		</div>
	</div>
</div>
<!-- ===================== jendaigo : end : modify scheduled type deductions fields ============= -->

<div class="col s6" style="display:none" id="extra_field">
	<div class="input-field">
		<label for="payment_count" id="payment_label" class="active">Number of Payments<span class="required">&nbsp;*</span></label>
		<input required="" id="payment_count" name="payment_count" type="text" class="validate" value="<?php echo isset($deduction_info['payment_count']) ? $deduction_info['payment_count'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
	</div>
</div>
<script>

var row_index = 1;
var action = <?php echo $action ?>;
var date_format = <?php echo json_encode(DATE_FORMAT) ?>; //jendaigo : get date format
var deduction_id = "";
// function payment_schedules()
function payment_schedules(deduction_id) //jendaigo : pass value of deduction id in the function
{
	$('#add_payments').on('click', function()
	{
		// var clonerow = $("#table_row");

		// ====================== jendaigo : start : modify scheduled type deductions fields ============= //
		$('#detail_start_date').datepicker('destroy');

		if(deduction_id == <?php echo json_encode(array(''.DEDUC_OVERPAY_JO.'')); ?>)
		{
			var clonerow = $("#table_row2");
			$('#deduction_detail_type_id')[0].selectize.destroy();
		}
		else
		{
			var clonerow = $("#table_row");
		}
		// ====================== jendaigo : end : modify scheduled type deductions fields ============= //
		
		// clone the row
		// clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").addClass('add_fields').appendTo("#other_deductions_div");
		clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").addClass('add_fields').insertAfter("#add_payments_btn"); //jendaigo: change cloned row position after add schedule button
		// handleSelect2();
		
		var newrow = $("#table_row_" + row_index);
		newrow.find('input.number').number(true, 2);
		
		// assign id and name to selectize of newly created row 
		newrow.find('a').attr({
			id: "remove_table_" + row_index ,
			onclick: "remove_table("+row_index+")"
		});
		
		// ====================== jendaigo : start : assign id and initialization of cloned row ============= //
		newrow.find('input[name="payment_count_dtl[]"]').attr("id", "payment_count_dtl_" + row_index);
		newrow.find('input[name="paid_count_dtl[]"]').attr("id", "paid_count_dtl_" + row_index);
		newrow.find('input[name="amount[]"]').attr("id", "amount_" + row_index);
		newrow.find('input[name="remarks[]"]').attr("id", "remarks_" + row_index);
		
		newrow.find('input[name="detail_start_date[]"]').attr({"id": ("detail_start_date_" + row_index), "autocomplete": "off", "onkeypress": "format_identifications('" + date_format + "',this.value,event,'detail_start_date_" + row_index + "')"}).datetimepicker({timepicker:false, format:'Y/m/d'});
		
		$('#detail_start_date').datepicker();

		if(deduction_id == <?php echo json_encode(array(''.DEDUC_OVERPAY_JO.'')); ?>)
		{
			newrow.find('select').attr("id", "deduction_detail_type_id_" + row_index).selectize();
			$('#deduction_detail_type_id').selectize();
		}
		// ====================== jendaigo : end : assign id and initialization of cloned row ============= //
		
		row_index++; 
	});	
}

function remove_table(row_index)
{
	$("#table_row_" + row_index).remove();
}
$(function (){
	var $format = "";
	$('#form_other_deductions').parsley();
	$('#form_other_deductions').submit(function(e) {
		
		$(this).find('select').prop('disabled', false); //jendaigo : enable disabled select before form is submitted
		
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_other_deduction', 1);
		  	var option = {
					url  : $base_url + '<?php echo PROJECT_MAIN ?>/deductions/process_other_deductions',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_employee_other_deductions.closeModal();

							var employee_id = $('#employee_id').val();
							var module = $('#module').val();
							var action      = $('#action').val();
							var has_permission      = $('#has_permission').val();
							var post_data   = {'employee_id' : employee_id, 'module' : module, 'action_id' : action, 'has_permission' : has_permission};

							load_datatable('table_other_deduction', '<?php echo PROJECT_MAIN ?>/deductions/get_other_deduction_list',false,0,0,true, post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_other_deduction', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	deduction_id = $('#deduction_id').val();
	get_identification_format(deduction_id.charAt(0));

})

var deduction_name = $('#deduction_id').selectize();
var deduction_types_details = <?php echo json_encode($deduction_details) ?>;
var employee_identification = <?php echo json_encode($employee_identification) ?>; // jendaigo : get ID ref of employee
var mp2_sys = <?php echo json_encode($mp2_sys) ?>; // jendaigo : include MP2 format
var deduction_detail_types = <?php echo json_encode($deduction_detail_types) ?>; // jendaigo : include deduction detail types
var deduction_types = <?php echo json_encode($deduction_types) ?>;

deduction_name.selectize().on('change', function() {
	deduction_id = $('#deduction_id').val();
	get_identification_format(deduction_id.charAt(0));
	
	row_index = 1;
	$('.add_fields').remove();
	var value = deduction_name[0].selectize.getValue();
	var current = '';
	var val_arr = value.split('|');
	
	// ADDING FOR OTHER DEDUCTIONS DETAILS
	// START
	var html = '';
	for(var i=0; i < deduction_types_details.length; i++)
	{
		if(deduction_types_details[i]['deduction_id'] == val_arr[0])
		{
			if(deduction_types_details[i]['other_detail_type'] == 'YN')
			{

				html += '<div class="row add_fields">'
					 + 	  '<div class="col s12">'
					 +  	'<div class="switch p-md">'
					 +			 deduction_types_details[i]['other_detail_name'] + '<br><br>'
					 +   		'<label>'
					 +	    	'No'
					 +	        '<input class="switch_me" name="other_deduction_details_switch[]" type="checkbox">' 
					 +	        '<span class="lever"></span>'
					 +	        'Yes'
					 +    		'</label>'
					 +		'</div>'
					 +      '<input value="' + deduction_types_details[i]['other_detail_name'] + '" name="other_deduction_details_switch[]" type="hidden">'
					 +      '<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details_switch[]" type="hidden">'
					 +	'</div>'
					 + '</div>';
			}

			//DISPLAY DROPDOWN
			if(deduction_types_details[i]['other_detail_type'] == 'DR')
			{
				html += '<div class="row add_fields">'
					 +	  '<div class="col s12">'
			    	 +		'<div class="input-field">'
			    	 +			'<label for="' + deduction_types_details[i]['other_detail_name'] + '" class="active">'
			    	 + 				deduction_types_details[i]['other_detail_name']
			    	 +			'</label>'
			   		 +		'</div>'
			      	 +	'</div>'
			      	 + '</div>';

			}

			//DISPLAY CHARACTER
			if(deduction_types_details[i]['other_detail_type'] == 'C')
			{
				/*
				html +=	'<div class="row add_fields">'
					 +   '<div class="col s12">'
					 +    	'<div class="input-field">'
					 +       	'<input class="validate" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' type="text" name="other_deduction_details[]" value="">'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden">'
					 +       	'<label for="' + deduction_types_details[i]['other_detail_name'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') + '</label>'
					 +      '</div>'
					 +	'</div>'
					 + '</div>';
				*/
					 
				// ====================== jendaigo : start : autoreflect employee_identification value ============= //
				if((employee_identification[deduction_types_details[i]['other_detail_name']]) || (deduction_types_details[i]['deduction_id'] == <?php echo DEDUC_HMDF2_JO; ?>))
				{
					other_deduction_detail_id 	= deduction_types_details[i]['other_deduction_detail_id'];
					identification_format 		= (deduction_types_details[i]['deduction_id'] == <?php echo DEDUC_HMDF2_JO; ?> ? mp2_sys['sys_param_value'] : employee_identification[deduction_types_details[i]['other_detail_name'].toUpperCase()]['format']);
					view_only 					= (deduction_types_details[i]['deduction_id'] == <?php echo DEDUC_HMDF2_JO; ?> ? '' : 'readOnly');
					format_identification_value = (deduction_types_details[i]['deduction_id'] == <?php echo DEDUC_HMDF2_JO; ?> ? '' : employee_identification[deduction_types_details[i]['other_detail_name'].toUpperCase()]['format_identification_value']);
					
					html +=	'<div class="row add_fields">'
						 +   '<div class="col s12">'
						 +    	'<div class="input-field">'
						 +       	'<input id="' + other_deduction_detail_id + '" class="validate" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' type="text" name="other_deduction_details[]" value="' + format_identification_value + '" ' + view_only + ' onkeypress="format_identifications(\''+ identification_format +'\',this.value,event,\''+ other_deduction_detail_id +'\')" onkeydown="return (event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95 <event.keyCode && event.keyCode<106) || (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode 44 && event.charCode > 39))" >'
						 +       	'<input value="' + other_deduction_detail_id + '|' + identification_format + '" name="identification_type_id" type="hidden">'
						 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden">'
						 +       	'<label for="' + deduction_types_details[i]['other_detail_name'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') + '</label>'
						 +      '</div>'
						 +	'</div>'
						 + '</div>';
				}
				else
				{
					html +=	'<div class="row add_fields">'
						 +   '<div class="col s12">'
						 +    	'<div class="input-field">'
						 +       	'<input class="validate" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' type="text" name="other_deduction_details[]" value="">'
						 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden">'
						 +       	'<label for="' + deduction_types_details[i]['other_detail_name'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') + '</label>'
						 +      '</div>'
						 +	'</div>'
						 + '</div>';
				}
				// ====================== jendaigo : end : autoreflect employee_identification value ============= //
			}

			//DISPLAY NUMBER
			if(deduction_types_details[i]['other_detail_type'] == 'N')
			{
				html +=	'<div class="row add_fields">'
					 +   '<div class="col s12">'
					 +    	'<div class="input-field">'
					 +       	'<input class="validate number" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' type="text" name="other_deduction_details[]" id="' + deduction_types_details[i]['other_detail_name'] + '" value="">'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden">'
					 +       	'<label for="' + deduction_types_details[i]['other_detail_name'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] +  (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') + '</label>'
					 +     '</div>'
					 + 	'</div>'
					 + '</div>';
			} 

			//DISPLAY DATE
			if(deduction_types_details[i]['other_detail_type'] == 'D')
			{
				html +=	'<div class="row add_fields">'
					 +   '<div class="col s12">'
					 +  	'<div class="input-field">'
					 +      	'<label for="' + deduction_types_details[i]['other_detail_name'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">&nbsp;*</span>' : '') + '</label>'
					 +      	'<input class="validate datepicker" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' id="' + deduction_types_details[i]['other_detail_name'] + '" name="other_deduction_details[]" type="text">'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden">'
					 +   	'</div>'
					 + 	 '</div>'
					 +  '</div>';
			}
		}
	}
	$('#other_deductions_div').append(html);
	
	if(val_arr[1] == 'S')
		{
			/*
			var html = 	'<div class="row add_fields" id="add_payments_btn">'
			+				'<div class="p-r-sm">'	
			+					'<a class="btn btn-success right" id="add_payments"><i class="flaticon-add175"></i> ADD SCHEDULE</a>'
			+ 				'</div>'
			+			'</div>'
			+			'<div id="table_row_0" class="row add_fields">'
			+				'<div class="col s5">'
			+					'<div class="input-field">'
			+						'<input style="text-align:right" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl_0" />'
			+						'<label class="active" for="payment_count_dtl_0">Number of Payment<span class="required">&nbsp;*</span></label>'
			+					'</div>'
			+				'</div>'
			+				'<div class="col s5">'
			+					'<div class="input-field">'
			+						'<input style="text-align:right" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount_0" />'
			+						'<label class="active" for="amount_0">Amount<span class="required">&nbsp;*</span></label>'
			+					'</div>'
			+				'</div>'
			+				'<div class="col s2">'
			+					'<div class="input-field">'
			+						'<a class="m-n" id="remove_table_0" onclick="remove_table(0)" style="color:grey !important" ><i class="flaticon-recycle69"></i></a>'
			+					'</div>'
			+				'</div>'
			+			'</div>';
			*/
			
			// ====================== jendaigo : start : modify scheduled type deductions fields ============= //
			if(val_arr[0] == <?php echo json_encode(array(''.DEDUC_OVERPAY_JO.'')); ?>)
			{	
				var add_option_list = '<option value="">Select Type</option>';
				for(var i=0; i < deduction_detail_types.length; i++)
				{
					add_option_list += '<option value="' + deduction_detail_types[i]['deduction_detail_type_id'] + '">' + deduction_detail_types[i]['deduction_detail_type_name'] + '</option>'
				};
				
				var add_fields = 	'<div class="col s2">'
				+						'<div class="input-field">'
				+							'<input style="text-align:right" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl_0" />'
				+							'<label class="active" for="payment_count_dtl_0">No. of Payment<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+					'<div class="col s2">'
				+						'<div class="input-field">'
				+							'<input style="text-align:right; color: lightgray;" type="text" class="validate" required="" aria-required="true" name="paid_count_dtl[]" id="paid_count_dtl_0" value="0" readOnly />'
				+							'<label class="active" for="paid_count_dtl_0">Paid count<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+					'<div class="col s1">'
				+						'<div class="input-field">'
				+							'<input style="text-align:right" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount_0" />'
				+							'<label class="active" for="amount_0">Amount<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+   				'<div class="col s2">'
				+  						'<div class="input-field">'
				+							'<input type="text" class="validate datepicker" required="" aria-required="true" name="detail_start_date[]" id="detail_start_date_0" onkeypress="format_identifications(\''+ date_format +'\',this.value,event,\'detail_start_date_0\')"/>'
				+							'<label class="active" for="detail_start_date_0">Start of Deduction<span class="required">&nbsp;*</span></label>'
				+   					'</div>'
				+ 					 '</div>'
				+					'<div class="col s2">'
				+						'<div class="input-field">'
				+							'<select class="selectize field" required="" name="deduction_detail_type_id[]" id="deduction_detail_type_id_0" placeholder="Select Type">'
				+								add_option_list
				+							'</select>'
				+							'<label class="active" for="deduction_detail_type_id_0">Type<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+   				'<div class="col s2">'
				+  						'<div class="input-field">'
				+							'<input type="text" class="validate" required="" aria-required="true" name="remarks[]" id="remarks_0" />'
				+							'<label class="active" for="remarks_0">Remarks<span class="required">&nbsp;*</span></label>'
				+   					'</div>'
				+ 					 '</div>'
				+				'<div class="col s1">';
			}
			else
			{
				var add_fields = 	'<div class="col s2">'
				+						'<div class="input-field">'
				+							'<input style="text-align:right" type="text" class="validate" required="" aria-required="true" name="payment_count_dtl[]" id="payment_count_dtl_0" />'
				+							'<label class="active" for="payment_count_dtl_0">No. of Payment<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+					'<div class="col s2">'
				+						'<div class="input-field">'
				+							'<input style="text-align:right; color: lightgray;" type="text" class="validate" required="" aria-required="true" name="paid_count_dtl[]" id="paid_count_dtl_0" value="0" readOnly />'
				+							'<label class="active" for="paid_count_dtl_0">Paid count<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+					'<div class="col s2">'
				+						'<div class="input-field">'
				+							'<input style="text-align:right" type="text" class="validate number" required="" aria-required="true" name="amount[]" id="amount_0" />'
				+							'<label class="active" for="amount_0">Amount<span class="required">&nbsp;*</span></label>'
				+						'</div>'
				+					'</div>'
				+   				'<div class="col s2">'
				+  						'<div class="input-field">'
				+							'<input type="text" class="validate datepicker" required="" aria-required="true" name="detail_start_date[]" id="detail_start_date_0" onkeypress="format_identifications(\''+ date_format +'\',this.value,event,\'detail_start_date_0\')"/>'
				+							'<label class="active" for="detail_start_date_0">Start of Deduction<span class="required">&nbsp;*</span></label>'
				+   					'</div>'
				+ 					 '</div>'
				+   				'<div class="col s3">'
				+  						'<div class="input-field">'
				+							'<input type="text" class="validate" required="" aria-required="true" name="remarks[]" id="remarks_0" />'
				+							'<label class="active" for="remarks_0">Remarks<span class="required">&nbsp;*</span></label>'
				+   					'</div>'
				+ 					 '</div>'
				+				'<div class="col s1">';
			}
				
			var html = 	'<div class="row add_fields" id="add_payments_btn">'
				+				'<div class="p-r-sm">'	
				+					'<a class="btn btn-success right" id="add_payments"><i class="flaticon-add175"></i> ADD SCHEDULE</a>'
				+ 				'</div>'
				+			'</div>'
				+			'<div id="table_row_0" class="row add_fields">'
				+				add_fields
				+					'<div class="input-field">'
				+						'<a class="m-n" id="remove_table_0" onclick="remove_table(0)" style="color:grey !important" ><i class="flaticon-recycle69"></i></a>'
				+					'</div>'
				+				'</div>'
				+			'</div>';
			// ====================== jendaigo : start : modify scheduled type deductions fields ============= //
			
			$('#other_deductions_div').append(html);
			$("#deduction_detail_type_id_0").selectize(); //jendaigo : initialize select selectize
			
			// payment_schedules();
			payment_schedules(val_arr[0]); //jendaigo : pass value of deduction id in the function
		}
		else
		{
			/*
			$("#payment_label").html('Number of Payments<span class="required">&nbsp;*</span>');
			$('#payment_count').removeAttr('disabled');
			var clonerow = $("#extra_field");
			// clone the row
			clonerow.clone().removeAttr("style").addClass('add_fields').appendTo("#extras");
			*/
			
			// ====================== jendaigo : start : exclude EWT, GMP & 8% Income Tax deduction IDs ============= //
			var excude_deduc = <?php echo json_encode(array(''.DEDUC_BIR_EWT.'', ''.DEDUC_BIR_VAT.'', ''.DEDUC_BIR_EIGHT.'')); ?>;
			
			if( $.inArray(val_arr[0], excude_deduc) === -1 )
			{
				$("#payment_label").html('Number of Payments<span class="required">&nbsp;*</span>');
				$('#payment_count').removeAttr('disabled');
				var clonerow = $("#extra_field");
				// clone the row
				clonerow.clone().removeAttr("style").addClass('add_fields').appendTo("#extras");
			}	
			// ====================== jendaigo : end : exclude EWT, GMP & 8% Income Tax deduction IDs ============= //
		}
	// END
	$('input.number').number(true, 2);
	$('.datepicker').datetimepicker('destroy');
	// $('.datepicker').datetimepicker({
	$('.datepicker').attr("autocomplete", "off").datetimepicker({ //jendaigo: add attribute autocomple off
		timepicker:false,
		format:'Y/m/d'
	});

	
});

function get_identification_format(id){
	params     = {deduction_id : id};
	var option = {
			url  : $base_url + "<?php echo PROJECT_MAIN."/deductions/get_identification_format"?>",
			data : params,
			async : false,
			success : function(result){
				if(result.flag == "1"){	
					$format = result.format;	
				} else {
					//notification_msg("<?php echo ERROR ?>", result.msg);
				}
			},
	};
	
	General.ajax(option); 
}

<?php if($action == ACTION_VIEW){ ?>
		$('label .required').addClass('none');
		$('.validate').attr('disabled','');
<?php } ?>

<?php if($action != ACTION_ADD){ ?>
		var row_index = <?php echo $deduction_info['payment_count'] ?>;
		// payment_schedules();
		payment_schedules(<?php echo $deduction_info['deduction_id'] ?>); //jendaigo : pass value of deduction id in the function
		// $('#deduction_id').trigger('change');
<?php } ?>

// ====================== jendaigo : start : add attribute autocomple off ============= //
<?php if($action == ACTION_ADD){ ?>
		$('.datepicker').attr("autocomplete", "off");
<?php } ?>
// ====================== jendaigo : end : add attribute autocomple off ============= //

</script>
