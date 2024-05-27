<section id="content" class="p-t-n m-t-n ">
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s6 m6 l6">
					<h5 class="breadcrumbs-title"> <?php echo ucwords(SUB_MENU_PDS); ?> </h5>
					<ol class="breadcrumb m-n">
						<?php get_breadcrumbs();?>
					</ol>
				</div>
				<div class="col s6 m6 l6 right-align">
                    <div class="row form-vertical form-styled form-basic">
                		<div class="input-field col s4">
						      <label class="label position-left active">You are currently viewing: </label>
                		</div>
                		<div class="col s8">
                			<select name="C-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
                				<option></option>
                				<?php 
                					foreach ($office_list as $key => $value) {
                						echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
                					}
                				?>
                			</select>
                		</div>
                	</div>
                </div>
			</div>
		</div>
		<div class="container p-t-n">
			<div class="section panel p-lg p-t-n">
				<div class="col l6 m6 s6 right-align m-b-n-lg">
					<div class="input-field inline p-l-md">
						<a href="javascript:;" class='btn btn-success md-trigger' data-modal='modal_pds_upload_ptis_format' data-position='bottom' data-delay='50' onclick='modal_pds_upload_ptis_format_init()'><i class="flaticon-upload109"></i> Upload PDS (PTIS Format)</a>
					</div>
					<div class="input-field inline p-l-md">
						<a href="javascript:;" class='btn btn-success md-trigger' data-modal='modal_upload_pds' data-position='bottom' data-delay='50' onclick='modal_upload_pds_init()'><i class="flaticon-upload109"></i> Upload PDS</a>
					</div>
					<div class="input-field inline p-l-md">
						<?php 
						$salt      = gen_salt();
						$module    = MODULE_USER;
						$token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
						$url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
						?>
						<a href="<?php echo base_url() . PROJECT_MAIN .'/pds/display_pds_info/' . $url_add ?>" class='btn btn-success' data-tooltip='Add' data-position='bottom' data-delay='50' onclick=''><i class="flaticon-add176"></i> Add PDS</a>
					</div>
				</div>
				<div class="pre-datatable filter-left"></div>
				<div style="padding-top: 50px">
					<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_pds_list">
						<thead>
							<tr>
								<th width="15%">Employee Number</th>
								<th width="25%">Employee Name</th>
								<th width="25%">Office</th>
								<th width="15%">Employment Status</th>
								<!-- MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : START -->
								<th width="10%">Last Entry</th>
								<!-- MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : END -->
								<th width="10%">Actions</th>
							</tr>
							<tr class="table-filters">
								<td><input name="A-agency_employee_id" class="form-filter"></td>
								<td><input name="fullname" class="form-filter"></td>
								<td><input name="E-name" class="form-filter"></td>
								<td><input name="D-employment_status_name" class="form-filter"></td>
								<!-- MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : START -->
								<td><input name="B-employ_start_date" class="form-filter"></td>
								<!-- MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : END -->
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
</section>
