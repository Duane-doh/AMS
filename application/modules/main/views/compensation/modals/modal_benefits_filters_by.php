<form id="add_employee_benefits_form">

	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="active" for="compensation_name">Benefit Name</label>
					<input  disabled type="text" id="compensation_id" class="validate" value="<?php echo isset($benefit_info['compensation_name']) ? $benefit_info['compensation_name'] : NULL; ?> " />
				</div>
			</div>
		</div>
	</div>
	 <div class="section panel p-md">
	 	<?php if($action != ACTION_VIEW) : ?>

	  <div class="col l12 m12 s12 center-align m-b-n-l-lg p-l-n p-b-xl">
	    <div class="col s4 m-r-n-md p-n input-field m-t-n">
	      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_benefits" onclick="modal_add_personnel_to_benefits_init('<?php echo ACTION_ADD."/".$id."/".$token_add."/".$salt."/".$module ?>')">Add Employee</button>
	    </div>
	    <div class="col s4 m-r-n-md p-n input-field m-t-n">
	      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_benefits" onclick="modal_add_personnel_to_benefits_init('<?php echo ACTION_EDIT."/".$id."/".$token_edit."/".$salt."/".$module ?>')">Edit Employee</button>
	    </div>
		<!-- 
	     <div class="col s4 m-r-n-md p-n input-field m-t-n">
	      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_benefits" onclick="modal_add_personnel_to_benefits_init('<?php echo ACTION_DELETE."/".$id."/".$token_delete."/".$salt."/".$module ?>')">Delete Employee</button>
	    </div>
		-->
		<!-- ===================== jendaigo : start : limit viewing to superuser only ============= -->
		<?php if($has_permission) : ?>
	     <div class="col s4 m-r-n-md p-n input-field m-t-n">
	      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_benefits" onclick="modal_add_personnel_to_benefits_init('<?php echo ACTION_DELETE."/".$id."/".$token_delete."/".$salt."/".$module ?>')">Delete Employee</button>
	    </div>
		<?php endif; ?>
		<!-- ===================== jendaigo : end : limit viewing to superuser only ============= -->
	    </div>
	<?php endif; ?>
      <!--start section-->
      <div class="pre-datatable filter-left"></div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_benefit_employee_list">
          <thead>
            <tr>
            	<th width="10%">Employee Number</th>
             	<th width="25%">Employee Name</th>
             	<th width="25%">Office</th>
             	<th width="15%">Start Date</th>
             	<th width="15%">End Date</th>
            </tr>
            <tr class="table-filters">
	            <td><input name="PI-agency_employee_id" class="form-filter"></td>
	            <td><input name="full_name" class="form-filter"></td>
	            <td><input name="WE-employ_office_name" class="form-filter"></td>
	            <td><input name="EC-start_date" class="form-filter"></td>
	            <td>
	            	<input name="EC-end_date" class="form-filter">
	          		<a hidden="true" href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
	            	<a hidden="true" href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
	            </td>
            </tr>
          </thead>
        </table>
      </div>

  <!--end section-->              
      </div>
	</div>
	<div class="md-footer default">
	</div>
</form>
<script>
$(function (){

	$('#add_employee_benefits_form').parsley();
	$('#add_employee_benefits_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
			//var id = $('#emp_id').val();

		  	button_loader('save_compensation', 1);
		  	var option = {
				url  : $base_url + 'main/compensation/process_compensation/',
				data : data,
				success : function(result){
					if(result.status)
					{
						notification_msg("<?php echo SUCCESS ?>", result.msg);
						$("#cancel_compensation").trigger('click');
						load_datatable('table_employe_benefit_list', '<?php echo PROJECT_MAIN ?>/compensation/get_compensation_type_list');
					}
					else
					{
						notification_msg("<?php echo ERROR ?>", result.msg);
					}	
					
				},
				
				complete : function(jqXHR){
					button_loader('save_compensation', 0);
				}
			};

			General.ajax(option);    
	    }
  	});
})

</script>