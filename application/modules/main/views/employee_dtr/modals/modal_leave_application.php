<form id="employee_leave_form">
	<div class="form-float-label">
	  <div class="row m-n p-t-md">
	    <div class="col s12">
	    	<div class="pre-datatable filter-left"></div>
	    	<diV>
			  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_leave_list">
			  <thead>
				<tr>
				  <th width="40%">Leave Type</th>
				  <th width="20%">Balance</th>
				  <th width="20%">Pending</th>
				  <th width="20">Actions</th>
				</tr>
				<tr class="table-filters">
					<td><input name="A-leave_type_name" class="form-filter"></td>
					<td><input name="B-leave_balance" class="form-filter"></td>
					<td><input name="pending_leave" class="form-filter"></td>
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
	    </div>
	  </div>
	</div>
	<table>
		<tbody>
		<tr>
			<td width="10%;"></td>
			<td><p><b class="red-text">Note: </b> For additional types of leave
				(e.g. Solo Parent Leave, VAWC Leave etc.) and the updating of leave balances,
					please coordinate with the Personnel Administration Division.</p>
			</strong></td>
			<td width="10%;"></td>
		</tr>
		<tr>
			<td width="10%;"></td>
			<td><button type="button" style="margin-top:15px;" class="btn btn-success md-trigger p-l-sm" data-position='bottom' data-modal='modal_leave_instructions' onclick="modal_leave_instructions_init()" data-delay='50'>Leave Instructions and Requirements</button></td>
			<td width="10%;"></td>
		</tr>
		</tbody>
	</table>

  </div>
	<div class="row">
		<div class="col s8">
		
		</div>

	</div>
</form>