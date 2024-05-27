<form id="leave_history_form">
 	<div class="row b-n  p-md m-n">

 		<span class="font-md font-playfair-display font-spacing-15"><?php echo isset($leave_history['leave_type_name'])? $leave_history['leave_type_name']:""; ?></span>
 	</div>
	
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Transaction Type</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['leave_transaction_type_name'])? $leave_history['leave_transaction_type_name']:""; ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Transaction Date</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['leave_transaction_date'])? format_date($leave_history['leave_transaction_date'], 'F d, Y'):""; ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Effective Date</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['effective_date'])? format_date($leave_history['effective_date'], 'F d, Y'):""; ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Number of Days</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['leave_earned_used'])? $leave_history['leave_earned_used']:""; ?></span>
		</div>
	</div>
	<?php if($leave_history['leave_wop'] > 0):?>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Number of Days w/o Pay</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['leave_wop'])? $leave_history['leave_wop']:""; ?></span>
		</div>
	</div>
	<?php endif;?>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Leave Start Date</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['leave_start_date'])? format_date($leave_history['leave_start_date'], 'F d, Y'):""; ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Leave End Date</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['leave_end_date'])? format_date($leave_history['leave_end_date'], 'F d, Y'):""; ?></span>
		</div>
	</div>
	<!-- marvin : start : include study type id -->
	<?php if($leave_history['leave_type_id'] == LEAVE_TYPE_STUDY): ?>
	<div class="row">
		<div class="col s4">
			<span class="font-sm">In case of Study Leave</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['study_type_id']) ? ($leave_history['study_type_id'] == 'M' ? 'Completion of Master\'s Degree' : 'BAR/Board Examination Review') : ''; ?></span>
		</div>
	</div>
	<?php endif; ?>
	<!-- marvin : end : include study type id -->
	<div class="row">
		<div class="col s4">
			<span class="font-sm">Remarks</span>
		</div>
		<div class="col s8">
			<span class="font-sm font-spacing-15"><?php echo isset($leave_history['remarks'])? $leave_history['remarks']:""; ?></span>
		</div>
	</div>
</form>