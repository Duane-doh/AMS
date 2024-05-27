<section id="content" class="p-t-n m-t-n ">
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
      <div class="row">
        <div class="col s6 m6 l6">
          <h5 class="breadcrumbs-title">Request Approvals</h5>
          <ol class="breadcrumb m-n p-b-sm">
            <?php get_breadcrumbs();?>
          </ol>
        </div>
        <div class="col s6 m6 l6 right-align">
            <div class="row form-vertical form-styled form-basic">
            <div class="input-field col s4">
          <label class="label position-left active">You are currently viewing: </label>
            </div>
            <div class="col s8">
              <!--<select name="H-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
                <option></option>-->
                <?php
					if(isset($office_list))
					{
						echo '<select name="H-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">';
						echo '<option></option>';
						foreach($office_list as $key => $value)
						{
							echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
						}						
					}
					else
					{
						echo '<select name="L-user_id" class="selectize form-filter" id="office_filter" placeholder="All employees..." onchange="office_filtering()">';
						echo '<option></option>';
						foreach($user_list as $key => $value)
						{
							echo '<option value="' . $value['user_id'] . '">' . $value['lname'] . ', ' . $value['fname'] . ' ' . $value['mname'] . '</option>';
						}	
					}
                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="section panel p-lg">

      <!-- marvin : include date range filter : start -->
      <div class="form-basic">
        <div class="row"> 
          <div class="col s7">
            <div class="input-field m-t-sm"></div>
          </div>                   
          <div class="col s2 p-r-n">
            <div class="input-field">
              <label for="fltr_dtr_start" class="active">Date Requested From</label>
              <input type="text" class="datepicker_start" id="fltr_dtr_start" value="<?php echo $fltr_dtr_start?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_start')"/>
            </div>
          </div>               
          <div class="col s2 p-l-n">
            <div class="input-field">
              <label for="fltr_dtr_end" class="active">Date Requested To</label>
              <input type="text" class="datepicker_end" id="fltr_dtr_end" value="<?php echo $fltr_dtr_end?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_end')"/>
            </div>
          </div>
          <div class="col s1 p-n">
            <div class="input-field p-t-xs">
              <a href="javascript:;" onclick="filter_attendance()" class="btn p-l-sm p-r-xs"><i class="flaticon-search95 "></i></a>
            </div>
          </div>
        </div>
      </div>
      <!-- marvin : include date range filter : end -->

      <div class="pre-datatable filter-left"> </div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_requests">
          <thead>
            <tr>
              <th width="10%">Request Number</th>
              <th width="15%">Employee Name</th>
              <th width="10%">Employee Number</th>
              <th width="10%">Request Type</th>
              <th width="10%">Date Requested</th>
              <th width="25%">Requested Date/s For Manual Adjustment/Filed Leave</th>
              <th width="10%">Status</th>
              <th width="10%">Actions</th>
            </tr>
            <tr class="table-filters">
              <td><input name="A-request_code" class="form-filter"></td>
              <td><input name="full_name" class="form-filter"></td>
              <td><input name="B-agency_employee_id" class="form-filter"></td>
              <td><input name="C-request_type_name" class="form-filter"></td>
              <td><input name="date_requested" class="form-filter"></td>
              <td><input disabled class="form-filter"></td>
              <td><input name="D-request_status_name" class="form-filter"></td>
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
</section>

<script type="text/javascript">
  $(document).ready(function(){

    // marvin : include user scope : start
    if("<?php echo $message; ?>")
    {
      notification_msg("<?php echo ERROR ?>", "<?php echo $message; ?>");
    }
    // marvin : include user scope : end
  });

  // marvin : include date range filter : start
  function filter_attendance() {
    var date_from = $('#fltr_dtr_start').val();
    var date_to   = $('#fltr_dtr_end').val(); 
    
    if(date_from != "" && date_to != "")
    {
      window.location.href = "<?php echo base_url() . PROJECT_MAIN . '/requests/index/'; ?>" + encodeURIComponent(btoa(date_from)) + "/" + encodeURIComponent(btoa(date_to));
    }
  }
  // marvin : include date range filter : end
</script>
