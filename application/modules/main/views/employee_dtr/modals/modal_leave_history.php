<form id="leave_history_form">
	<div class="form-float-label">
		<div class="row b-n p-t-lg p-l-lg ">
			<span class="font-xl font-spacing-15"><?php echo isset($leave_type)? $leave_type:""; ?></span>
		</div>
		<br>
		<div class="row m-n b-t-n">
			<div class="col s12">
				<div class="pre-datatable filter-left"></div>
				<div>
					<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_leave_history">
						<thead>
							<tr>
								<th>Transaction Date</th>
								<th>Effective Date</th>
								<th>Transaction Type</th>
								<th width="15%">Number of Days</th>
								<th width="10%">Actions</th>
							</tr>
							<tr class="table-filters">
								<td><input name="transaction_date" class="form-filter"></td>
								<td><input name="effective_date" class="form-filter"></td>
								<td><input name="B-leave_transaction_type_name" class="form-filter"></td>
								<td><input name="A-leave_earned_used" class="form-filter"></td>
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
		</div>
	</div>
</form>