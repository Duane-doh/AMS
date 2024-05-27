
<div class="col l12 m12 s12 right-align p-r-n">
      <div class="input-field inline p-l-md z-input m-t-n-md m-b-xs">
          <?php 
              $salt      = gen_salt();
              $token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module  . '/' . $id, $salt);
              $url_add   = ACTION_ADD."/".DEFAULT_ID."/".$token_add."/".$salt."/".$module."/".$id;
          ?>
          <button class="btn btn-success md-trigger" data-modal="modal_employee_work_schedule" onclick="modal_employee_work_schedule_init('<?php echo $url_add; ?>')">Add New Schedule</button>   
      </div>
  </div>
  <div class="pre-datatable filter-left"></div>
<div>
  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="employee_work_schedule_table">
    <thead>
    <tr>
      <th width="20%">Start Date</th>
        <th width="20%">End Date</th>
        <th width="45%">Schedule Type</th>
        <th width="15%">Actions</th>
    </tr> 
    <tr class="table-filters">
      <td><input name="A-start_date" class="form-filter"></td>
      <td><input name="A-end_date" class="form-filter"></td>
      <td><input name="B-work_schedule_name" class="form-filter"></td>
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
