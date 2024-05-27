<div class="row">
	<div class="col l12 m12 s12 right-align p-r-n">
		<div class="input-field inline p-l-md z-input m-t-n m-b-md">
			<?php if($this->permission->check_permission(MODULE_PAYROLL_CL_BIR_TABLE, ACTION_ADD)) :?>
				<button class="btn btn-success  md-trigger" data-modal="modal_bir" onclick="modal_bir_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_BIR_TABLE; ?></button>
			<?php endif; ?>
		</div>
	</div>
	<div class="pre-datatable filter-left"></div>
	<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="bir_table_dt">
	  <thead>
		<tr>
			<th width="20%">Effective Date</th>
			<th width="20%">Tax Table Flag</th>
			<th width="15%">Status</th>
			<th width="5%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="effective_date" class="form-filter"></td>
			<td><input name="tax_table_flag" class="form-filter"></td>
			<td><input name="active_flag" class="form-filter"></td>
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
</div>
<!-- <div class="col s5 p-t-md">
		<form id="form_reports">
		    <div class="input-field col s3">
		      <label class="label position-left">Tax Table</label>
		    </div>
		    <div class="col s8"> -->
		      <!-- DEFAULT SELECT STRUCTURE -->
		     <!--  <select id="tax_table_type" class="selectize" id="reports" name="reports" placeholder="Select Type...">
		        <option value="">Select Type...</option>
		        <option value="daily">Daily</option>
		        <option value="weekly">Weekly</option>
		        <option value="semi-monthly">Semi-Monthly</option>
		        <option value="monthly">Monthly</option>
		      </select> -->
		      <!-- END STRUCTURE -->
		 <!--    </div>
		</form>
	</div>
<div class="row p-l-lg">
	<div id="dependent" class="hide">
	  <div class="col s3">
	  		<input type="radio" name="dependent_type" value="wo_dependent" id="wo_dependent" >
			<label for="wo_dependent">Employees w/o qualified dependent</label> <br>
		</div>
		<div class="col s4">
			<input type="radio" name="dependent_type" value="w_dependent" id="w_dependent">
			<label for="w_dependent">Single/Married employee w/ qualified dependent child(ren)</label>
		</div>
	</div>
</div>
<div class="panel p-sm">
	<div class="hide" id="table">
		<table cellpadding="0" cellspacing="0" class="table table-default table-layout-auto" id="bir_table">
				  <thead>
					<tr>
					  <th width="20%">
					  	<span id="daily" class="hide">Daily</span>
					  	<span id="weekly" class="hide">Weekly</span>
					  	<span id="semi-monthly" class="hide">Semi-Monthly</span>
					  	<span id="monthly" class="hide">Monthly</span>
					  </th>
					  <th width="20%">1</th>
					  <th width="20%">2</th>
					  <th width="20%">3</th>
					  <th width="20%">4</th>
					  <th width="20%">5</th>
					  <th width="20%">6</th>
					  <th width="20%">7</th>
					  <th width="20%">8</th>
					  <th width="2%">Action</th>
					</tr>
				  </thead>
					  <tbody>
					  </tbody>
		</table>
	</div>
	<div class="hide" id="table2">
		<table class="table table-default table-layout-auto">
			<tr>
				<th colspan="11">
					<span class="hide" id="w_dependent_type">A. Table for employees without qualified dependent</span>
					<span class="hide" id="wo_dependent_type">B. Table for single/married employee with qualified dependent child(ren)</span>
				</th>
			</tr>
			<tr>
				<td>1. Z</td>
				<td>0.0</td>
				<td>1</td>
				<td>0</td>
				<td>33</td>
				<td>99</td>
				<td>231</td>
				<td>462</td>
				<td>825</td>
				<td>1,650</td>
				<td>
					<div class='table-actions'>
						<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_default' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_init('modal_identification/".ACTION_EDIT."')\"></a></td>
					</div>	
			</tr>
		</table>
	</div>
</div> -->
<script type="text/javascript">
$(function (){
	$('#tax_table_type').on('change',function(){
		if($(this).val() == 'daily')
		{
			$('#daily').removeClass('hide');
			$('#weekly').addClass('hide');
			$('#semi-monthly').addClass('hide');
			$('#monthly').addClass('hide');
			$('#dependent').removeClass('hide');
			$('#table').removeClass('hide');
		}
		else if($(this).val() == 'weekly')
		{
			$('#daily').addClass('hide');
			$('#weekly').removeClass('hide');
			$('#semi-monthly').addClass('hide');
			$('#monthly').addClass('hide');
			$('#dependent').removeClass('hide');
			$('#table').removeClass('hide');
		}
		else if($(this).val() == 'semi-monthly')
		{
			$('#daily').addClass('hide');
			$('#weekly').addClass('hide');
			$('#semi-monthly').removeClass('hide');
			$('#monthly').addClass('hide');
			$('#dependent').removeClass('hide');
			$('#table').removeClass('hide');
		}
		else if($(this).val() == 'monthly')
		{
			$('#daily').addClass('hide');
			$('#weekly').addClass('hide');
			$('#semi-monthly').addClass('hide');
			$('#monthly').removeClass('hide');
			$('#dependent').removeClass('hide');
			$('#table').removeClass('hide');
		}
		else
		{
			$('#daily').addClass('hide');
			$('#weekly').addClass('hide');
			$('#semi-monthly').addClass('hide');
			$('#monthly').addClass('hide');
			$('#dependent').addClass('hide');
			$('#table').addClass('hide');
		}
	});
	
	$('#w_dependent').on('click',function(){
		if($(this).val() == 'w_dependent')
		{
			$('#table2').removeClass('hide');
			$('#w_dependent_type').removeClass('hide');
			$('#wo_dependent_type').addClass('hide');
		}
	});
	$('#wo_dependent').on('click',function(){
		if($(this).val() == 'wo_dependent')
		{
			$('#table2').removeClass('hide');
			$('#w_dependent_type').addClass('hide');
			$('#wo_dependent_type').removeClass('hide');	
		}
	});
})
</script>		