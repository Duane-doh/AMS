<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
			<?php 
				$salt			= gen_salt();
				$token_add		= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
				$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
			?>
			<a class="btn btn-success  md-trigger" data-modal="modal_trainings" onclick="modal_trainings_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add <?php echo 'Training'; ?></a>
	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="pds_trainings_table">
		<thead>
			<tr>
			  	<th width="20%">Training Title</th>
			 	<th width="15%">Date Started</th>
			  	<th width="15%">Date Ended</th>
			  	<th width="15%">Number of Hours</th>
			  	<th width="20%">Conducted / Sponsored By</th>
			  	<th width="20%">Type of LD</th>
				<?php if($module != MODULE_PERSONNEL_PORTAL) : ?>
			  	<th width="10%">Relevance</th>
				<?php endif; ?>
			  	<th width="10%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="A-training_name" class="form-filter"></td>
				<td><input name="training_start_date" class="form-filter"></td>
				<td><input name="training_end_date" class="form-filter"></td>
				<td><input name="A-training_hour_count" class="form-filter"></td>
				<td><input name="A-training_conducted_by" class="form-filter"></td>
				<td><input name="A-training_type" class="form-filter"></td>
				<?php if($module != MODULE_PERSONNEL_PORTAL) : ?>
				<td ></td>
				<?php endif; ?>
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
<script>
function update_relevance(id){	
		var $params 	= {employee_training_id 		 : id};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_trainings_info/update_relevance"?>",$params, function(result) {
				if(result.flag){	
					load_datatable('pds_trainings_table', '<?php echo PROJECT_MAIN ?>/pds_trainings_info/get_trainings_list/<?php echo $employee_id; ?>',false,0,0,true);	
					notification_msg("<?php echo SUCCESS ?>", result.msg);
				} else {
					notification_msg("<?php echo ERROR ?>", result.msg);
				}
			}, 'json');
	};
</script>