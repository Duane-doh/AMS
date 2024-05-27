<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
    
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s7 m7 l7">
            <h5 class="breadcrumbs-title">Employee Attendance</h5>
            <ol class="breadcrumb m-n">
                <?php get_breadcrumbs();?>
            </ol>
          </div>
          <div class="col l5 m5 s5 p-t-sm user-avatar">
            <div class="row m-n">
              <?php 
                /* GET USER AVATAR */
                $avatar_src = base_url() . PATH_USER_UPLOADS . $personal_info['photo'];
                $avatar_src = @getimagesize($avatar_src) ? $avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
              ?>
                <img class="circle" width="65" height="65" src="<?php echo  $avatar_src?>"/> 
                <label class="dark font-xl"><?php echo $personal_info['fullname']; ?></label><br>
                <label class="font-lg"><?php echo $personal_info['agency_employee_id']; ?></label><br>
                <label class="font-md"><?php echo $personal_info['name']; ?></label>
            </div>
        </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="section panel p-lg p-t-sm">
      <div class="col l6 m6 s3 right-align m-b-n-lg">
            <div class="input-field inline p-l-md z-input">
                <?php 
                    $salt      = gen_salt();
                    $token_add = in_salt($id . '/' . ACTION_ADD  . '/' . $module, $salt);
                    $url_add   = ACTION_ADD."/".$id ."/".$token_add."/".$salt."/".$module;
                ?>
                <button class="btn btn-success md-trigger m-r-sm " data-modal="modal_add_employee_attendance" onclick="modal_add_employee_attendance_init('<?php echo $url_add; ?>')">Add Record</button>   
            </div>
        </div>
      <div class="pre-datatable filter-left"></div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="employee_logs_table">
          <thead>
          <tr>
            <th width="15%">Attendance Date</th>
              <th width="15%">Day</th>
              <th width="15%">Time In</th>
              <th width="10%">Break Out</th>
              <th width="10%">Break In</th>
              <th width="15%">Time Out</th>
              <th width="10%">Actions</th>
          </tr> 
          <tr class="table-filters">
            <td><input name="attendance_date" class="form-filter"></td>
            <td><input name="day_name" class="form-filter"></td>
            <td><input name="time_in" class="form-filter"></td>
            <td><input name="break_out" class="form-filter"></td>
            <td><input name="break_in" class="form-filter"></td>
            <td><input name="time_out" class="form-filter"></td>
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
