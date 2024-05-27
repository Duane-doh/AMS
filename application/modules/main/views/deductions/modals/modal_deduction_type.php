<form id="deduction_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<input  disabled type="text" class="validate" required="" aria-required="true" name="deduction_name" id="deduction_name" value="<?php echo isset($deduction_info['deduction_name']) ? $deduction_info['deduction_name'] : NULL; ?> " />
					<label class="active" for="deduction_name">Deduction Name</label>
				</div>
			</div>
		</div>
	</div>


	 <div class="section panel p-md">
      <!--start section-->

		<?php if($action != ACTION_VIEW) : ?>
	 	<div class="col l12 m12 s12 center-align m-b-n-l-lg p-l-n p-b-xl">
			<!--
		    <div class="col s4 m-r-n-md p-n input-field m-t-n">
		      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_deductions" onclick="modal_add_personnel_to_deductions_init('<?php echo ACTION_ADD."/".$id."/".$token_add."/".$salt."/".$module ?>')">Add Employee</button>
		    </div>
		    <div class="col s4 m-r-n-md p-n input-field m-t-n">
		      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_deductions" onclick="modal_add_personnel_to_deductions_init('<?php echo ACTION_EDIT."/".$id."/".$token_edit."/".$salt."/".$module ?>')">Edit Employee</button>
		    </div>
			-->
			
			<!-- ===================== jendaigo : start : exclude add & edit button view to MP2 Deduction ============= -->
			<?php if($deduction_info['deduction_id'] != DEDUC_HMDF2_JO) : ?>
			<div class="col s4 m-r-n-md p-n input-field m-t-n">
		      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_deductions" onclick="modal_add_personnel_to_deductions_init('<?php echo ACTION_ADD."/".$id."/".$token_add."/".$salt."/".$module ?>')">Add Employee</button>
		    </div>
			<div class="col s4 m-r-n-md p-n input-field m-t-n">
		      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_deductions" onclick="modal_add_personnel_to_deductions_init('<?php echo ACTION_EDIT."/".$id."/".$token_edit."/".$salt."/".$module ?>')">Edit Employee</button>
		    </div>
			<?php endif; ?>
			<!-- ===================== jendaigo : end : exclude edit button view to MP2 Deduction ============= -->
			<!-- 
		     <div class="col s4 m-r-n-md p-n input-field m-t-n">
		      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_deductions" onclick="modal_add_personnel_to_deductions_init('<?php echo ACTION_DELETE."/".$id."/".$token_delete."/".$salt."/".$module ?>')">Delete Employee</button>
		    </div>
			-->
			<!-- ===================== jendaigo : start : limit viewing to superuser only ============= -->
			<?php if($has_permission) : ?>
		     <div class="col s4 m-r-n-md p-n input-field m-t-n">
		      <button class="btn btn-success md-trigger" type="button" data-modal="modal_add_personnel_to_deductions" onclick="modal_add_personnel_to_deductions_init('<?php echo ACTION_DELETE."/".$id."/".$token_delete."/".$salt."/".$module ?>')">Delete Employee</button>
		    </div>
			<?php endif; ?>
			<!-- ===================== jendaigo : end : limit viewing to superuser only ============= -->

		</div>
		<?php endif; ?>
      <div class="pre-datatable filter-left"></div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_deduction_personnel_list">
          <thead>
            <tr>
            	<th width="25%">Employee Number</th>
             	<th width="25%">Employee Name</th>
             	<th width="25%">Office</th>
             	<th width="25%">Start Date</th>
            </tr>
            <!-- For Advanced Filters -->
             <tr class="table-filters">
                <td><input name="PI-agency_employee_id" class="form-filter"></td>
	            <td><input name="full_name" class="form-filter"></td>
	            <td><input name="WE-employ_office_name" class="form-filter"></td>
	            <td>
	          		<input name="ED-start_date" class="form-filter">
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