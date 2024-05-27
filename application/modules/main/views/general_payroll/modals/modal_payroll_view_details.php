<!--
Not being used
-->

<?php $data_id = 'modal_deductions/' . ACTION_VIEW; ?>
<?php $data_id2 = 'modal_benefits/' . ACTION_VIEW; ?>
<h3 class="md-header"><?php echo SUB_MENU_LEAVE ?></h3>
<form id="role_form">
		<div class="form-float-label">
					<div class="row">
				<div class="col s6">
					<div class="input-field">
						<input id="name" name="name" value="Sample"  disabled="" type="text" class="validate">
	    				<label class="active" for="name">name</label>
	    			</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input id="designation" name="designation" value="Sample"  disabled="" type="text" class="validate">
	    				<label class="active" for="designation">Designation</label>
	    			</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<input id="salary_grade" name="salary_grade" value="Sample"  disabled="" type="text" class="validate">
	    				<label class="active" for="salary_grade">Salary grade</label>
	    			</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input id="basic_salary" name="basic_salary" value="Sample"  disabled=""  type="text" class="validate">
	    				<label class="active" for="basic_salary">Basic salary</label>
	    			</div>
				</div>
			</div>
			<div class="row">
				<div class="right-align p-l-xl p-b-xs">
				  <div class="input-field inline">
				 		<button class="btn btn-success  md-trigger" data-modal="modal_default" onclick="modal_init('<?php echo $data_id; ?>')"><i class="flaticon-add175"></i> Add <?php echo SUB_MENU_DEDUCTIONS; ?></button>
				  </div>
				</div>
			</div>
			<div class="row">
				  <table cellpadding="0" cellspacing="0" class="table table-default" id="programs_table">
					  <thead>
						<tr>
						  <th width="25%">Deduction</th>
						  <th width="25%">Type</th>
						  <th width="40%">Amount</th>
						</tr>
					  </thead>
					  <tbody id="programs_tbody">
					  	 <tr>
					  	 	<td>sample</td>
					  	 	<td>sample</td>
					  	 	<td>sample</td>
					  	 </tr>
					  </tbody>
				  </table>
			</div>
			<div class="row">
				<div class="right-align p-l-xl p-b-xs">
				  <div class="input-field inline">
				 		<button class="btn btn-success  md-trigger" data-modal="modal_default" onclick="modal_init('<?php echo $data_id2; ?>')"><i class="flaticon-add175"></i> Add Benefits</button>
				  </div>
				</div>
			</div>
			<div class="row">
			  <table cellpadding="0" cellspacing="0" class="table table-default" id="programs_table">
				  <thead>
					<tr>
					  <th width="25%">Benefits</th>
					  <th width="25%">Tax</th>
					  <th width="40%">Amount</th>
					</tr>
				  </thead>
				  <tbody id="programs_tbody">
				  	 <tr>
				  	 	<td>sample</td>
				  	 	<td>sample</td>
				  	 	<td>sample</td>
				  	 </tr>
				  </tbody>
			  </table>
			</div>
		</div>
	</div>
</form>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat" id="cancel_service_record">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success " id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
<script>
var modalObj = new handleModal({ controller : 'payroll', modal_id: 'modal_default', module: '<?php echo PROJECT_MAIN ?>' });
$(function (){
	$("#cancel_service_record").on("click", function(){
		modalObj.closeModal();
	});
})
</script>