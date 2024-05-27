<?php
	switch ($nav_page) {
		case CODE_LIBRARY_POSITION:
			$icon = 'supervisor_account';
		break;
		case CODE_LIBRARY_OFFICE:
			$icon = 'account_balance';
		break;
		case CODE_LIBRARY_LEAVE_TYPE:
			$icon = 'bookmark_border';
		break;
		case CODE_LIBRARY_DEDUCTION_TYPE:
			$icon = 'money_off';
		break;
		case CODE_LIBRARY_APPOINTMENT_TYPE:
			$icon = 'event_available';
		break;
		case CODE_LIBRARY_REPLACEMENT_REASON:
			$icon = 'receipt';
		break;
		case CODE_LIBRARY_APPOINTMENT_STATUS:
			$icon = 'assignment';
		break;
		case CODE_LIBRARY_NATURE_EMPLOYMENT:
			$icon = 'work';
		break;
		case CODE_LIBRARY_ELIGIBILITY_TITLE:
			$icon = 'done_all';
		break;
		case CODE_LIBRARY_BIR_TABLE:
			$icon = 'library_books';
		break;
		case CODE_LIBRARY_GSIS_TABLE:
			$icon = 'library_books';
		break;
		case CODE_LIBRARY_PAGIBIG_TABLE:
			$icon = 'library_books';
		break;
		case CODE_LIBRARY_PHILHEALTH_TABLE:
			$icon = 'library_books';
		break;
		case CODE_LIBRARY_BANK:
			$icon = ICON_BANK;
		break;
		case CODE_LIBRARY_VOUCHER:
			$icon = 'library_books';
		break;
	}
?>
<form id="role_form">
	<div class="form-float-label">
		  <div class="col s12">
			<div class="input-field m-l-md">
					 <i class="material-icons prefix grey-text"><?php echo $icon; ?></i>
				<label for="leave_type" ><?php echo $nav_page ?> Name</label>
				<input type="text" id="leave_type" name="name">
			</div>
		  </div>
	</div>
</form>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
    <button class="btn btn-success  green" id="save_attendance_correction" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
  <?php //endif; ?>
</div>
