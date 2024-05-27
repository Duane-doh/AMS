<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php 
			$salt			= gen_salt();
			$token_add		= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
			$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
		?>
	 <a class="btn btn-success  md-trigger" data-modal="modal_government_exam" onclick="modal_government_exam_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add Eligibility</a>
	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="pds_government_exam_table">
	  	<thead>
			<tr>
			  <th width="20%">Eligibility</th>
			  <th width="20%">Rating</th>
			  <th width="20%">Date of Examination/Conferment</th>
			  <th width="20%">Place of Examination/ Conferment</th>
			  <?php if($module != MODULE_PERSONNEL_PORTAL) : ?>
			  <th width="10%">Relevance</th>
			  <?php endif; ?>
			  <th width="10%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="B-eligibility_type_name" class="form-filter"></td>
				<td><input name="A-rating" class="form-filter"></td>
				<td><input name="exam_date" class="form-filter"></td>
				<td><input name="A-exam_place" class="form-filter"></td>
				<?php if($module != MODULE_PERSONNEL_PORTAL) : ?>
				<td ></td>
				<?php endif; ?>
				<td class="table-actions">
					<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
					<a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
				</td>
			</tr>
	  	</thead>
	</table>
</div>
<script>
function update_relevance(id){	
		var $params 	= {employee_eligibility_id 		 : id};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_government_exam_info/update_relevance"?>",$params, function(result) {
				if(result.flag){	
					load_datatable('pds_government_exam_table', '<?php echo PROJECT_MAIN ?>/pds_government_exam_info/get_government_exam_list/<?php echo $employee_id; ?>',false,0,0,true);	
					notification_msg("<?php echo SUCCESS ?>", result.msg);
				} else {
					notification_msg("<?php echo ERROR ?>", result.msg);
				}
			}, 'json');
	};
</script>