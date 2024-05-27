<?php
	$data_id = ACTION_ADD;
?>
<div class="page-title m-b-lg">
  <ul id="breadcrumbs">
	<li><a href="#">Home</a></li>
	<li><a href="#" class="active"><?php echo SUB_MENU_LOANS; ?></a></li>
  </ul>  
  <div class="row m-b-n">
	<div class="col s6 p-r-n">
		<h5>
			<?php echo SUB_MENU_LOANS; ?>
		</h5>
	</div>
	<div class="col s6 right-align">
		  <div class="btn-group"></div>
		  <div class="input-field inline">
	 		<button class="btn  md-trigger" data-modal="modal_loan" onclick="modal_loan_init()"></i> Add Loan</a></button>
		  </div>
		</div>
  </div>
</div>

<div class="pre-datatable"></div>
<div>
  <table cellpadding="0" cellspacing="0" class="table table-default table-layout-auto" id="loans_list">
  <thead>
	<tr>
	  <th width="20%">Loan Date</th>
	  <th width="20%">Loan</th>
	  <th width="20%">Type</th>
	  <th width="15%">Employee</th>
	  <th width="15%">Status</th>
	  <th width="10%">Actions</th>
	</tr>
  </thead>
	  <tbody>
	  
	  </tbody>
  </table>
</div>