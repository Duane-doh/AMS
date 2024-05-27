<?php if($module == MODULE_HR_DEDUCTIONS):?>
<div class="col s12 p-r-sm right-align m-b-n-l-lg">
    <div class="input-field inline m-t-n p-l-md">
      <?php if($this->permission->check_permission($module, ACTION_ADD) && $action != ACTION_VIEW && $has_permission) :?>
      <button class="btn btn-success  md-trigger" type="button" data-modal="modal_employee_other_deductions" onclick="modal_employee_other_deductions_init('<?php echo ACTION_ADD."/".$employee_id ?>')"><i class="flaticon-add175"></i> Add Other Deduction</button>
      <?php endif; ?>
  </div>
 </div>
 <?php endif;?>
<div class="pre-datatable filter-left"></div>

  <div style="padding:50px 12px 0px 0px">
    
  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_other_deduction">
    <thead> 
    <tr>
      <th width="20%">Deduction</th>
      <th width="20%">Start Date</th>
      <th width="20%">Deduction Type</th>
      <th width="20%">Frequency</th>
      <th width="20%">Action</th>
    </tr>
    <tr class="table-filters">
      <td><input name="PD-deduction_name" class="form-filter"></td>
      <td><input name="ED-start_date" class="form-filter"></td>
      <td><input name="PD-deduction_type_flag" class="form-filter"></td>
      <td><input name="PF-frequency_name" class="form-filter"></td>
      <td class="table-actions">
        <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
        <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
      </td>
    </tr>
    </thead>
      <tbody>
      </tbody>
  </table>
</div>



<script>
  $('#hide_statutory_tab').hide();
</script>