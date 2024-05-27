
<div class="col l12 m12 s12 right-align p-r-n">
      <div class="input-field inline p-l-md z-input m-t-n-md m-b-xs">
          <button class="btn btn-success md-trigger m-r-sm " type="button" data-modal="modal_add_employee_leave_type" onclick="modal_add_employee_leave_type_init('<?php echo $action."/".$id."/".$token."/".$salt."/".$module ?>')">Add Leave</button>
      </div>
  </div>
<div class="pre-datatable filter-left"></div>
<div>
  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_leave_list">
      <thead>
      <tr>
        <th width="40%">Leave Type</th>
        <th width="20%">Balance</th>
        <th width="20%">Pending</th>
        <th width="20">Actions</th>
      </tr><tr class="table-filters">
      <td><input name="A-leave_type_name" class="form-filter"></td>
      <td><input name="B-leave_balance" class="form-filter"></td>
      <td><input name="pending_leave" class="form-filter"></td>
      <td class="table-actions">
        <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
        <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
      </td>
    </tr>
      </thead>
        <tbody>
          
        </tbody>
      </table>
</div>    
