<form id="deduction_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
  <?php if($this->permission->check_permission($module,ACTION_EDIT) AND $action != ACTION_VIEW):?>
	<div class="section panel p-md m-b-lg">
    <?php if(EMPTY($leave_dtls['deduct_bal_leave_type_id'])):?>
    <div class="col s3 p-n">
      <button class="btn btn-success md-trigger pull-right" type="button" data-modal="modal_add_monthly_leave_credit" onclick="modal_add_monthly_leave_credit_init('<?php echo $action."/".$id."/".$token."/".$salt."/".$module ?>')">Add Monthly Credits</button>
    </div>
    <?php endif;?>
    <div class="col s3 p-n">
      <button class="btn btn-success md-trigger pull-right" type="button" data-modal="modal_add_personnel" onclick="modal_add_personnel_init('<?php echo $action."/".$id."/".$token."/".$salt."/".$module ?>')">Add Employee</button>
    </div>
    <div class="col s3 p-n  m-l-md m-r-n-md">
      <button class="btn btn-success md-trigger pull-right" type="button" data-modal="modal_remove_personnel" onclick="modal_remove_personnel_init('<?php echo $action."/".$id."/".$token."/".$salt."/".$module ?>')">Remove Employee</button>
    </div>
    
    <div class="col s3 p-n">
      <button class="btn btn-success md-trigger pull-right" type="button" data-modal="modal_adjust_leave" onclick="modal_adjust_leave_init('<?php echo $action."/".$id."/".$token."/".$salt."/".$module ?>')">Adjust Leave</button>
    </div>
  
	</div>
	  <?php endif;?>
	 <div class="section panel p-md">
      <div class="row">
        <div class="col s4 m4 l4">
         <br>
        </div>
        <div class="col s8 m8 l8 right-align">
          <div class="row form-vertical form-styled form-basic">
          <div class="input-field col s4">
        <label class="label position-left active">You are currently viewing: </label>
          </div>
          <div class="col s8">
            <!--<select name="H-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
              <option></option>-->
              <?php 
                // foreach ($office_list as $key => $value) {
                  // echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
                // }
				/*********************************************************marvin*********************************************************/
				/*show user and office list base on roles*/
				if(isset($office_list))
				{
					echo '<select name="H-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">';
					echo '<option></option>';
					foreach ($office_list as $key => $value)
					{
						echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
					}
				}
				else
				{
					echo '<select name="K-user_id" class="selectize form-filter" id="office_filter" placeholder="All employees..." onchange="office_filtering()">';
					echo '<option></option>';
					foreach ($user_list as $key => $value)
					{
						echo '<option value="' . $value['user_id'] . '">' . $value['lname'] . ', ' . $value['fname'] . ' ' . $value['mname'] . '</option>';
					}
				}
				/*********************************************************marvin*********************************************************/
              ?>
            </select>
          </div>
        </div>
      </div>
      </div>
      <div class="pre-datatable filter-left"></div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_leave_type_adjustment">
          <thead>
            <tr>
            	<th width="15%">Employee Number</th>
              <th width="20%">Employee Name</th>
              <th width="20%">Office</th>
             	<th width="10%">Leave Balance</th>
             	<th width="10%">Pending Leave</th>
              	<th width="10%">Actions</th>
            </tr>
            <!-- For Advanced Filters -->
            <tr class="table-filters">
              <td><input name="A-agency_employee_id" class="form-filter"></td>
              <td><input name="fullname" class="form-filter"></td>
              <td><input name="J-name" class="form-filter"></td>
              <td><input name="B-leave_balance" class="form-filter"></td>
              <td><input name="pending_leave" class="form-filter"></td>
              <td class="table-actions">
                <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
                <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a> 
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