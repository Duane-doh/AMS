<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s7">
                <h5 class="breadcrumbs-title">Daily Time Record</h5>
                <ol class="breadcrumb m-n p-b-sm">
                    <?php get_breadcrumbs();?>
                </ol>   
              </div>
               <div class="col l5 m5 s5 p-t-sm user-avatar">
                    <div class="row m-n">
                        <?php 
                            /* GET USER AVATAR */
                            $emp_avatar_src = base_url() . PATH_USER_UPLOADS . $personal_info['photo'];
                            $emp_avatar_src = @getimagesize($emp_avatar_src) ? $emp_avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
                          ?>
                        <img class="circle" width="65" height="65" src="<?php echo  $emp_avatar_src?>"/> 
                        <label class="dark font-xl"><?php echo $personal_info['fullname']; ?></label><br>
                        <label class="font-lg"><?php echo $personal_info['agency_employee_id']; ?></label><br>
                        <label class="font-md"><?php echo $personal_info['name']; ?></label>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="container  p-t-n">
          <div class="section panel p-lg ">
            <div class="form-basic">
                <form id="time_log_form" name="time_log_form" class=" m-t-n-sm" autocomplete="off">
				<!--
                <input type="hidden" name="id" id="user_id" value="<?php //echo $id ?>">
                <input type="hidden" name="salt" value="<?php //echo $salt ?>">
                <input type="hidden" name="token" value="<?php //echo $token ?>">
                <input type="hidden" name="action" id="action" value="<?php //echo $action ?>"/>
                <input type="hidden" name="module" id="module" value="<?php //echo $module ?>"/>
				-->
				<!--marvin-->
				<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                <input type="hidden" name="salt" id="salt" value="<?php echo $salt ?>">
                <input type="hidden" name="token" id="token" value="<?php echo $token ?>">
                <input type="hidden" name="action" id="action" value="<?php echo $action ?>"/>
                <input type="hidden" name="module" id="module" value="<?php echo $module ?>"/>
				<!--marvin-->
                <div class="row"> 
                  <div class="col s7">
                      <div class="input-field m-t-sm">
                        <button type="button" class="btn btn-success md-trigger p-l-sm" data-modal="modal_leave_application" onclick="modal_leave_application_init()"><i class="flaticon-up146 "></i> Apply Leave</button>
                        <button type="button" class="btn btn-success md-trigger p-l-sm" data-modal="modal_print_employee_dtr" onclick="modal_print_employee_dtr_init()"><i class="flaticon-printing23 "></i> Print DTR</button>
						<!-- davcorrea 10/04/2023 added button approving officer list : START -->
                        <button type="button" class="btn btn-success md-trigger p-l-sm" data-modal="modal_view_approving_officer" onclick="modal_view_approving_officer_init()"><i class="flaticon-verify8 "></i> View Approving Officer</button>
                        <!-- END -->
                      </div>
                  </div>                   
                  <div class="col s2 p-r-n">
                     <div class="input-field">
                          <label for="fltr_dtr_start" class="active">Date From</label>
                          <input type="text" class="date" id="fltr_dtr_start" value="<?php echo $fltr_dtr_start?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_start')"/>
                    </div>
                  </div>               
                  <div class="col s2 p-l-n">
                     <div class="input-field">
                          <label for="fltr_dtr_end" class="active">Date To</label>
                          <input type="text" class="date" id="fltr_dtr_end" value="<?php echo $fltr_dtr_end?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_end')"/>
                    </div>
                </div>
                 <div class="col s1 p-n">
                    <div class="input-field p-t-xs">
                          <a href="javascript:;" onclick="filter_attendance()" class="btn p-l-sm p-r-xs"><i class="flaticon-search95 "></i></a>
                    </div>
                  </div>
              </div>
              <div class="row"> 
                <div class="col s12">
                  <div class="card-panel teal white-text valign-middle">
                    <span class="font-md font-bold valign-middle white-text">Attendance Time Logs</span>
                      <a class="btn pull-right m-t-n-sm p-l-sm p-r-xs" onclick="collapseAll()"> <i class="flaticon-collapse7"></i></a>
                      <a class="btn pull-right m-t-n-sm p-l-sm p-r-xs m-r-md" onclick="expandAll()"> <i class="flaticon-expand36"></i></a>
                   <?php 
                        $salt      = gen_salt();
                        $token_add = in_salt($id . '/' . ACTION_ADD  . '/' . $module, $salt);
                        // $url_add   = ACTION_ADD."/".$id ."/".$token_add."/".$salt."/".$module;
						
						//marvin
                       $url_add   = ACTION_ADD."/".$id ."/".$token_add."/".$salt."/".$module."/".format_date($fltr_dtr_start,'Y-m-d')."/".format_date($fltr_dtr_end,'Y-m-d');
                    ?>  
                  <a class="btn pull-right m-t-n-sm m-r-sm md-trigger" data-modal="modal_add_employee_attendance" onclick="modal_add_employee_attendance_init('<?php echo $url_add; ?>')"> Add New </a>
				  <!--marvin
				  add print raw time logs-->
				  <a class="btn pull-right m-t-n-sm m-r-sm " onclick="print_raw_attendance()"><i class="flaticon-printing23 "></i> Raw Time Logs</a>
				  <!--marvin-->
                </div>
                </div>
              </div>              
              <div class="row" id="employee_dtr_div"> 
              <?php 
                if($time_logs[0]['attendance_date']): 
                  $counter = 0;
                  foreach ($time_logs as $log):     
                    $time_input = '';
                    if($log['attendance_period_flag'] != 1) 
                    {
                      $time_input = ' time_input ';
                    }  
                    // if($log['has_four_log_flag'] == TRUE)
                    // {
                      // $time_input = '';
                    // }
                            ?>      
              <div class="col s12 m6 l4">
                <ul class="collapsible panel" data-collapsible="expandable">
                    <li class="attendance_card">
                     <!-- <div class="collapsible-header grey darken-1 white-text p-l-n"><i class="flaticon-arrows98"></i><span class="font-md font-bold valign-middle p-l-sm m-r-n-xxl"><?php //echo strtoupper(format_date($log['attendance_date'],'F d, Y'))?></span> -->
					 
					 <!--marvin-->
                      <div class="collapsible-header grey darken-1 white-text p-l-n"><i class="flaticon-arrows98"></i><span class="font-md font-bold valign-middle p-l-sm m-r-n-xxl"><?php echo strtoupper(format_date($log['attendance_date'],'F d, Y'))?> <?php echo $log['has_pending_request'] ? '(Pending Request)' : ''; ?></span> 

                           <?php if($log['undertime'] > 0):?>
                           <div class="chip orange lighten-1 pull-right font-xxs white-text m-t-xs tooltipped" data-tooltip="Undertime">
                             <img  class="m-r-xxs" src="<?php echo base_url() . PATH_IMAGES . 'letterU.jpg';?>" alt="U">
                            <?php echo ($log['undertime_hour'] > 0) ? $log['undertime_hour'] .' hr':''; ?>
                            <?php echo ($log['undertime_min'] > 0) ? $log['undertime_min'] .' min':''; ?>
                            
                          </div><?php endif;?>
                           <?php if($log['tardiness'] > 0):?>
                           <div class="chip orange lighten-1 pull-right font-xxs white-text m-t-xs tooltipped" data-tooltip="Late">
                             <img class="m-r-xxs" src="<?php echo base_url() . PATH_IMAGES . 'letterL.jpg';?>" alt="L">
                            <?php echo ($log['tardiness_hour'] > 0) ? $log['tardiness_hour'] .' hr':''; ?>
                            <?php echo ($log['tardiness_min'] > 0) ? $log['tardiness_min'] .' min':''; ?>
                          </div><?php endif;?>

                           
                      </div>
                        <div class="collapsible-body p-l-md p-t-md p-r-n grey lighten-3">              
                            <div class="row"> 
                                <?php 
                                if(!EMPTY($time_input))
                                $key = $counter++;
                              ?>           
                            <div class="col s9">
                              <div class="input-field">
                                    <label for="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="active">Time in</label>
									
									<!-- ASIAGATE 
                                    <input type="text" name="<?php //echo (!EMPTY($time_input)) ? 'time_in':'view_input' ?>[<?php //echo !empty($log['time_in_id']) ? $log['time_in_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php //echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php //echo $time_input; ?>" value="<?php //echo isset($log['time_in']) ? $log['time_in']:'' ?>" <?php //echo (!EMPTY($time_input)) ? '':' disabled ' ?>/> -->
									
									<!-- MARVIN -->
									<input type="text" name="<?php echo (!EMPTY($time_input)) ? 'time_in':'view_input' ?>[<?php echo !empty($log['time_in_id']) ? $log['time_in_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php echo $time_input; ?>" value="<?php echo isset($log['time_in']) ? $log['time_in']:'' ?>" <?php echo (EMPTY($log['time_in']) AND !EMPTY($time_input) AND $log['has_pending_request'] == 0) ? '' : 'disabled' ?> <?php if($log['attendance_status_id'] == OFFICIAL_BUSINESS){echo'disabled';} ?> onclick="getTimeLogs(this.id, <?php echo "'".format_date($log['attendance_date'],'Y/m/d')."'"; ?>)" />
                              </div>
                            </div>          
                            <div class="col s3 ">
                              <div class="input-field p-t-xs">
                                  <div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">
                                      <a class="btn-floating btn-small teal">
                                        <i class="material-icons">menu</i>
                                      </a>
                                      <ul>
									  
										<!-- ASIAGATE
										<li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('<?php //echo $id ?>'+'/'+'<?php //echo $log['attendance_date']?>'+'/'+'<?php //echo FLAG_TIME_IN ?>'+'/0')"><i class="flaticon-information66"></i></a></li> -->

                                        <?php if (!EMPTY($time_input)):?>
										
										<!--ASIAGATE
                                        <li><a class="btn-floating teal" onclick="set_down_count(<?php //echo $key?>)"><i class="flaticon-arrow620"></i></a></li>-->
										
										<!--MARVIN-->
										<li><a class="btn-floating teal" data-tooltip='Move Down' onclick="move_down(<?php echo $key?>)"><i class="flaticon-arrow620"></i></a></li>
										
                                        <li><a class="btn-floating teal" data-tooltip='Move Up' onclick="move_up(<?php echo $key?>)"><i class="flaticon-arrow620" style="transform:rotate(180deg);"></i></a></li>
                                      <?php endif;?>
                                      </ul>
                                  </div>
                              </div>
                            </div>
                            </div>
                            <div class="row"> 
                              <?php 
                                if(!EMPTY($time_input))
                                $key = $counter++;
                              ?>           
                            <div class="col s9">
                              <div class="input-field">
                                    <label for="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="active">Break out</label>
									
									<!-- ASIAGATE
                                    <input type="text" name="<?php //echo (!EMPTY($time_input)) ? 'break_out':'view_input' ?>[<?php //echo !empty($log['break_out_id']) ? $log['break_out_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php //echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php //echo $time_input; ?>" value="<?php //echo isset($log['break_out']) ? $log['break_out']:'' ?>" <?php //echo (!EMPTY($time_input)) ? '':' disabled ' ?>/>-->
									
									<!-- MARVIN -->
									<input type="text" name="<?php echo (!EMPTY($time_input)) ? 'break_out':'view_input' ?>[<?php echo !empty($log['break_out_id']) ? $log['break_out_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php echo $time_input; ?>" value="<?php echo isset($log['break_out']) ? $log['break_out']:'' ?>" <?php echo (EMPTY($log['break_out']) AND !EMPTY($time_input)AND $log['has_pending_request'] == 0) ? '' : 'disabled' ?> <?php if($log['attendance_status_id'] == OFFICIAL_BUSINESS){echo'disabled';} ?>  onclick="getTimeLogs(this.id, <?php echo "'".format_date($log['attendance_date'],'Y/m/d')."'"; ?>)" />
                              </div>
                            </div>          
                              <div class="col s3 ">
                              <div class="input-field p-t-xs">
                                  <div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">
                                      <a class="btn-floating btn-small teal">
                                        <i class="material-icons">menu</i>
                                      </a>
                                      <ul>
									  
									  <!-- ASIAGATE
									  <li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('<?php //echo $id ?>'+'/'+'<?php //echo $log['attendance_date']?>'+'/'+'<?php //echo FLAG_BREAK_OUT ?>'+'/0')"><i class="flaticon-information66"></i></a></li>-->
									  
                                        <?php if (!EMPTY($time_input)):?>
										
										<!--ASIAGATE
                                        <li><a class="btn-floating teal" onclick="set_down_count(<?php //echo $key?>)"><i class="flaticon-arrow620"></i></a></li>-->
										
										<!--MARVIN-->
										<li><a class="btn-floating teal" data-tooltip='Move Down' onclick="move_down(<?php echo $key?>)"><i class="flaticon-arrow620"></i></a></li>
										
                                        <li><a class="btn-floating teal" data-tooltip='Move Up' onclick="move_up(<?php echo $key?>)"><i class="flaticon-arrow620" style="transform:rotate(180deg);"></i></a></li>
                                      <?php endif;?>
                                      </ul>
                                  </div>
                              </div>
                            </div>
                            </div>
                            <div class="row">
                              <?php 
                                if(!EMPTY($time_input))
                                $key = $counter++;
                              ?>              
                            <div class="col s9">
                              <div class="input-field">
                                    <label for="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="active">Break in</label>
									
									<!-- ASIAGATE
                                    <input type="text" name="<?php //echo (!EMPTY($time_input)) ? 'break_in':'view_input' ?>[<?php //echo !empty($log['break_in_id']) ? $log['break_in_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php //echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php //echo $time_input; ?>" value="<?php //echo isset($log['break_in']) ? $log['break_in']:'' ?>" <?php //echo (!EMPTY($time_input)) ? '':' disabled ' ?>/>-->
									
									<!-- MARVIN -->
									<input type="text" name="<?php echo (!EMPTY($time_input)) ? 'break_in':'view_input' ?>[<?php echo !empty($log['break_in_id']) ? $log['break_in_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php echo $time_input; ?>" value="<?php echo isset($log['break_in']) ? $log['break_in']:'' ?>" <?php echo (EMPTY($log['break_in']) AND !EMPTY($time_input) AND $log['has_pending_request'] == 0) ? '' : 'disabled' ?> <?php if($log['attendance_status_id'] == OFFICIAL_BUSINESS){echo'disabled';} ?>  onclick="getTimeLogs(this.id, <?php echo "'".format_date($log['attendance_date'],'Y/m/d')."'"; ?>)" />
                              </div>
                            </div>          
                            <div class="col s3 ">
                              <div class="input-field p-t-xs">
                                  <div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">
                                      <a class="btn-floating btn-small teal">
                                        <i class="material-icons">menu</i>
                                      </a>
                                      <ul>
									  
									  <!-- ASIAGATE
									  <li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('<?php //echo $id ?>'+'/'+'<?php //echo $log['attendance_date']?>'+'/'+'<?php //echo FLAG_BREAK_IN ?>'+'/0')"><i class="flaticon-information66"></i></a></li>-->

                                        <?php if (!EMPTY($time_input)):?>
										
										<!-- ASIAGATE
                                        <li><a class="btn-floating teal" onclick="set_down_count(<?php //echo $key?>)"><i class="flaticon-arrow620"></i></a></li>-->
										
										<!--MARVIN-->
										<li><a class="btn-floating teal" data-tooltip='Move Down' onclick="move_down(<?php echo $key?>)"><i class="flaticon-arrow620"></i></a></li>
										
                                        <li><a class="btn-floating teal" data-tooltip='Move Up' onclick="move_up(<?php echo $key?>)"><i class="flaticon-arrow620" style="transform:rotate(180deg);"></i></a></li>
                                      <?php endif;?>
                                      </ul>
                                  </div>
                              </div>
                            </div>
                            </div>
                            <div class="row"> 
                              <?php 
                                if(!EMPTY($time_input))
                                $key = $counter++;
                              ?>           
                            <div class="col s9">
                              <div class="input-field">
                                    <label for="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="active">Time Out</label>
									
									<!-- ASIAGATE
                                    <input type="text" name="<?php //echo (!EMPTY($time_input)) ? 'time_out':'view_input' ?>[<?php //echo !empty($log['time_out_id']) ? $log['time_out_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php //echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php //echo $time_input; ?>" value="<?php //echo isset($log['time_out']) ? $log['time_out']:'' ?>" <?php //echo (!EMPTY($time_input)) ? '':' disabled ' ?>/>-->
									
									<!-- MARVIN -->
									<input type="text" name="<?php echo (!EMPTY($time_input)) ? 'time_out':'view_input' ?>[<?php echo !empty($log['time_out_id']) ? $log['time_out_id'] : $log['attendance_date'] ?>]" id="attendance_time_<?php echo (!EMPTY($time_input)) ? $key : ''?>" class="datetimepicker <?php echo $time_input; ?>" value="<?php echo isset($log['time_out']) ? $log['time_out']:'' ?>" <?php echo (EMPTY($log['time_out']) AND !EMPTY($time_input) AND $log['has_pending_request'] == 0) ? '' : 'disabled' ?> <?php if($log['attendance_status_id'] == OFFICIAL_BUSINESS){echo'disabled';} ?>  onclick="getTimeLogs(this.id, <?php echo "'".format_date($log['attendance_date'],'Y/m/d')."'"; ?>)" />
                              </div>
                            </div>          
                            <div class="col s3 ">
                              <div class="input-field p-t-xs">
                                  <div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">
                                      <a class="btn-floating btn-small teal">
                                        <i class="material-icons">menu</i>
                                      </a>
                                      <ul>
									  
									  <!-- ASIAGATE
									  <li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('<?php //echo $id ?>'+'/'+'<?php //echo $log['attendance_date']?>'+'/'+'<?php //echo FLAG_TIME_OUT ?>'+'/0')"><i class="flaticon-information66"></i></a></li>-->
									  
                                        <?php if (!EMPTY($time_input)):?>
										
										<!-- ASIAGATE
                                        <li><a class="btn-floating teal" onclick="set_down_count(<?php //echo $key?>)"><i class="flaticon-arrow620"></i></a></li> -->
										
										<!-- MARVIN -->
										<li><a class="btn-floating teal" data-tooltip='Move Down' onclick="move_down(<?php echo $key?>)"><i class="flaticon-arrow620"></i></a></li>
										
                                        <li><a class="btn-floating teal" data-tooltip='Move Up' onclick="move_up(<?php echo $key?>)"><i class="flaticon-arrow620" style="transform:rotate(180deg);"></i></a></li>
                                      <?php endif;?>
                                      </ul>
                                  </div>
                              </div>
                            </div>
                            </div>
                            <div class="row"> 
                              <div class="col s11">
                                <div class="input-field">
                                      <!--<label for="attendance_time_<?php //echo $log['attendance_date']?>" class="active">Remarks</label>-->
									  
									  <!--marvin-->
                                      <label for="attendance_time_<?php echo $log['attendance_date']?>" class="active">Remarks <span class="required">*</span></label>
									  
                                      <input type="text" name="<?php echo (!EMPTY($time_input)) ? 'remarks':'view_input' ?>[<?php echo $log['attendance_date']?>]" id="attendance_time_<?php echo $log['attendance_date']?>" value="<?php echo isset($log['remarks']) ? $log['remarks']:'' ?>" <?php echo (!EMPTY($time_input) AND $log['has_pending_request'] == 0) ? '':' disabled ' ?> <?php if($log['attendance_status_id'] == OFFICIAL_BUSINESS){echo'disabled';} ?>  />
                                </div>
                              </div>      
                            </div>
                      </div>
                    </li>
                  </ul>
              </div>
              <?php
                endforeach;
               else: 

                ?>
              <div class="col s12">
                <div class="card-panel grey lighten-5 valign-middle p-lg">
                      <center><span class="font-lg valign-middle blue-grey-text">No data available.</span></center>
                </div>
              </div>
              <?php endif;?> 
              </div>
               <?php if($time_logs[0]['attendance_date'] AND $action != ACTION_VIEW): ?>
              <div class="panel-footer">
                <div class="row">
                 <!--  <div class="col s9">
                    <div class="input-field">
                      <label for="remarks">Remarks <span class="required"> * </span></label>
                      <textarea id="remarks" name="remarks" required class="materialize-textarea"></textarea>
                    </div>
                  </div> -->
                   <div class="col s12">
                      <button class="btn" id="save_time_logs" name="save_time_logs" type="submit" value="Save">Save</button>
                      <button class="btn btn-secondary" onclick="restore_time_logs()" type="button" value="Save">Refresh Values</button>
                  </div>
                </div>
                
              </div>
              <?php endif;?>
            </form>     
          </div>
        </div>
    </section>

<script type="text/javascript">

var data_map          = {};
var orig_time_logs    = {};
var number_of_rows    = 0;
var input_total_count = 0;
var last_date         = '<?php echo $last_date;?>';

initialize_orig_time_logs();

function initialize_orig_time_logs (){
  var input_indx = 0;
  $(".time_input").each(function() {    
      orig_time_logs[input_indx] = $(this).val();
      input_indx ++;
  });
  input_total_count = input_indx - 1;
}
function restore_time_logs()
{ 
  $('#confirm_modal').confirmModal({
    topOffset : 0,
    onOkBut : function() {
      $('.input-field label').addClass('active');
        for (i = 0; i <= $(".time_input").length; i++) {
          $("#attendance_time_" + i).val(orig_time_logs[i]);      
        }
    },
    onCancelBut : function() {},
    onLoad : function() {
      $('.confirmModal_content h4').html('Are you sure you want to refresh values?'); 
      $('.confirmModal_content p').html('This action will restore log changes.');
    },
    onClose : function() {}
  });
}

function initialize_data (){
  var input_indx = 0;
  $(".time_input").each(function() {    
      data_map[input_indx] = $(this).val();
      input_indx ++;
  });
}

//ASIAGATE
// function move_up(input_count)
// {  
  // $('#confirm_modal').confirmModal({
    // topOffset : 0,
    // onOkBut : function() {
      // initialize_data();
      // for (i = input_count; i <= $(".time_input").length; i++) {
        // var concat_count = i + 1;
        // if(i < $(".time_input").length)
        // {
          // $("#attendance_time_" + i).val(data_map[concat_count]);
        // }
        // else
        // {
          // $("#attendance_time_" + i).val('');
        // }
          
      // }
    // },
    // onCancelBut : function() {},
    // onLoad : function() {
      // $('.confirmModal_content h4').html('Are you sure you want to remove this time log?'); 
      // $('.confirmModal_content p').html('This action will remove this time log and adjust all logs below moving up.');
    // },
    // onClose : function() {}
  // });
// }

//MARVIN
function move_up(input_count)
{  
	initialize_data();
	var prev_time = input_count - 1;
	if($("#attendance_time_" + input_count).val() != '')
	{
		if($("#attendance_time_" + prev_time).val() == '')
		{
			$("#attendance_time_" + prev_time).val(data_map[input_count]).attr('disabled', true).parent().find("label").attr('class', 'active');
			$("#attendance_time_" + input_count).val('').attr('disabled', false).parent().find("label").attr('class', '');		
		}
	}
}

//ASIAGATE
// function move_down(input_count,row_cnt)
// {
  // initialize_data();
  // /*SET/MOVE VALUES DOWN*/
  // for (i = input_count; i <= $(".time_input").length; i++) {
    // var concat_count = i + parseInt(row_cnt);
      // $("#attendance_time_" + concat_count).val(data_map[i]);
  // }
  // /*CLEAR MOVED VALUES*/
  // for (i = 0; i < row_cnt; i++) {
      // var concat_count = i + parseInt(input_count);
      // $("#attendance_time_" + concat_count).val('');
  // }
// }

//MARVIN
function move_down(input_count)
{
	initialize_data();
	var next_time = input_count + 1;
	if($("#attendance_time_" + input_count).val() != '')
	{
		if($("#attendance_time_" + next_time).val() == '')
		{
			$("#attendance_time_" + next_time).val(data_map[input_count]).attr('disabled', true).parent().find("label").attr('class', 'active');
			$("#attendance_time_" + input_count).val('').attr('disabled', false).parent().find("label").attr('class', '');
		}
	}
}

function set_down_count(input_count)
{
  $('#confirm_modal').confirmModal({
    topOffset : 0,
    onOkBut : function() {
      if(number_of_rows > 0)
      {
        var four_log = 4;/*In every time card, there are 4 logs, TI,BO,BI,TO*/

        /*Compute number of timecards to append.*/
        var datecard_loop_cnt = number_of_rows / four_log;

        if(datecard_loop_cnt < 1)
        {
          append_dtr();
        }
        else
        {

          for (var i = 1; i <= datecard_loop_cnt; i++) 
          {
            append_dtr();
          };
          if(datecard_loop_cnt%1 >0)
          {
             append_dtr();
          }
        }
        

        move_down(input_count,number_of_rows);
      }
    },
    onCancelBut : function() {},
    onLoad : function() {
      $('.confirmModal_content h4').html('Enter number of rows to be inserted.'); 
      $('.confirmModal_content p').html('This action will adjust all logs below moving down.<br><br><input id="number_of_rows" name="number_of_rows" type="text" value="" onkeyup="set_number_of_rows($(this).val())"/>');
    },
    onClose : function() {}
  });
}

function set_number_of_rows(user_input)
{
  if(!isNaN(user_input))
  {
    number_of_rows = user_input;
  }
  
}

function append_dtr (){
  last_date = moment(last_date).add(1,'days');
  var dtr_date = moment(last_date).format('YYYY-MM-DD');;
  var date_view = moment(last_date).format('LL');
  var id = $('#id').val();
 
  var time_in_cnt   = input_total_count + 1;
  var break_out_cnt = input_total_count + 2;
  var break_in_cnt  = input_total_count + 3;
  var time_out_cnt  = input_total_count + 4;

  input_total_count = input_total_count + 4;
  var str ='<div class="col s12 m6 l4">'
            +'<ul class="collapsible panel" data-collapsible="expandable">'
                +'<li class="attendance_card">'
                    +'<div class="collapsible-header grey darken-1 white-text p-l-n"><i class="flaticon-arrows98"></i><span class="font-md font-bold valign-middle p-l-sm m-r-n-xxl">'+date_view.toUpperCase()+'</span></div>'
                    +'<div class="collapsible-body p-l-md p-t-md p-r-n grey lighten-3">'              
                        +'<div class="row">'                                       
                          +'<div class="col s9">'
                            +'<div class="input-field">'
                                  +'<label for="attendance_time_'+time_in_cnt+'" class="active">Time in</label>'
                                  +'<input type="text" name="time_in['+dtr_date+']" id="attendance_time_'+time_in_cnt+'" class="datetimepicker time_input" value=""/>'
                            +'</div>'
                          +'</div>'  
                          +'<div class="col s3 ">'
                            +'<div class="input-field p-t-xs">'
                                +'<div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">'
                                    +'<a class="btn-floating btn-small teal">'
                                      +'<i class="material-icons">menu</i>'
                                    +'</a>'
                                   +'<ul>'
                                      +'<li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('+id+'/'+dtr_date+'/TI/0)"><i class="flaticon-information66"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="set_down_count('+time_in_cnt+')"><i class="flaticon-arrow620"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="move_up('+time_in_cnt+')"><i class="flaticon-cross95 "></i></a></li>'
                                    +'</ul>'
                                +'</div>'
                            +'</div>'
                          +'</div>'
                          +'</div>'
                          +'<div class="row">'
                          +'<div class="col s9">'
                            +'<div class="input-field">'
                                  +'<label for="attendance_time_'+break_out_cnt+'" class="active">Break out</label>'
                                  +'<input type="text" name="break_out['+dtr_date+']" id="attendance_time_'+break_out_cnt+'" class="datetimepicker time_input" value=""/>'
                            +'</div>'
                          +'</div>'        
                           +'<div class="col s3 ">'
                            +'<div class="input-field p-t-xs">'
                                +'<div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">'
                                    +'<a class="btn-floating btn-small teal">'
                                      +'<i class="material-icons">menu</i>'
                                    +'</a>'
                                   +'<ul>'
                                      +'<li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('+id+'/'+dtr_date+'/BO/0)"><i class="flaticon-information66"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="set_down_count('+time_in_cnt+')"><i class="flaticon-arrow620"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="move_up('+time_in_cnt+')"><i class="flaticon-cross95 "></i></a></li>'
                                    +'</ul>'
                                +'</div>'
                            +'</div>'
                          +'</div>'
                          +'</div>'
                          +'<div class="row">'
                          +'<div class="col s9">'
                            +'<div class="input-field">'
                                  +'<label for="attendance_time_'+break_in_cnt+'" class="active">Break in</label>'
                                  +'<input type="text" name="break_in['+dtr_date+']" id="attendance_time_'+break_in_cnt+'" class="datetimepicker time_input" value=""/>'
                            +'</div>'
                          +'</div>'        
                           +'<div class="col s3 ">'
                            +'<div class="input-field p-t-xs">'
                                +'<div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">'
                                    +'<a class="btn-floating btn-small teal">'
                                      +'<i class="material-icons">menu</i>'
                                    +'</a>'
                                   +'<ul>'
                                      +'<li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('+id+'/'+dtr_date+'/BI/0)"><i class="flaticon-information66"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="set_down_count('+time_in_cnt+')"><i class="flaticon-arrow620"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="move_up('+time_in_cnt+')"><i class="flaticon-cross95 "></i></a></li>'
                                    +'</ul>'
                                +'</div>'
                            +'</div>'
                          +'</div>'
                          +'</div>'
                          +'<div class="row">'
                          +'<div class="col s9">'
                            +'<div class="input-field">'
                                  +'<label for="attendance_time_'+time_out_cnt+'" class="active">Time Out</label>'
                                  +'<input type="text" name="time_out['+dtr_date+']" id="attendance_time_'+time_out_cnt+'" class="datetimepicker time_input" value="" />'
                            +'</div>'
                          +'</div> '         
                           +'<div class="col s3 ">'
                            +'<div class="input-field p-t-xs">'
                                +'<div class="fixed-action-btn dtr-action-btn horizontal click-to-toggle">'
                                    +'<a class="btn-floating btn-small teal">'
                                      +'<i class="material-icons">menu</i>'
                                    +'</a>'
                                   +'<ul>'
                                      +'<li><a class="btn-floating teal md-trigger" data-modal="modal_attendance_remarks" onclick="modal_attendance_remarks_init('+id+'/'+dtr_date+'/TO/0)"><i class="flaticon-information66"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="set_down_count('+time_in_cnt+')"><i class="flaticon-arrow620"></i></a></li>'
                                      +'<li><a class="btn-floating teal" onclick="move_up('+time_in_cnt+')"><i class="flaticon-cross95 "></i></a></li>'
                                    +'</ul>'
                                +'</div>'
                            +'</div>'
                          +'</div>'
                        +'</div>'
                        +'<div class="row">' 
                              +'<div class="col s11">'
                                +'<div class="input-field">'
                                      +'<label for="attendance_time_'+dtr_date+'" class="active">Remarks</label>'
                                      +'<input type="text" name="remarks['+dtr_date+']" id="attendance_time_'+dtr_date+'" />'
                                +'</div>'
                              +'</div>'      
                        +'</div>'
                    +'</div>'
                +'</li>'
            +'</ul>'
        +'</div>';
        $('#employee_dtr_div').append(str);
        expandAll();
        datepicker_init();
}
function filter_attendance (){
    var date_from = $('#fltr_dtr_start').val();
    var date_to = $('#fltr_dtr_end').val();   
    if(date_from != "" && date_to != "")
    {
      window.location = ($base_url + 'main/employee_dtr/load_employee_dtr/' + dateFormat(date_from, 'yyyy-m-d') + '/' + dateFormat(date_to, 'yyyy-m-d'));       
    }
    else{
      if(date_from == "" && date_to != "")
      {
        notification_msg("<?php echo ERROR ?>", "<b>Date From</b> is required.");
      }
      else if(date_to == "" && date_from != "")
      {
        notification_msg("<?php echo ERROR ?>", "<b>Date To</b> is required.");
      }
      else
      {
        notification_msg("<?php echo ERROR ?>", "<b>Date From</b> and <b>Date To</b> is required.");
      }
      
    }
}
function expandAll(){
  $(".collapsible-header").addClass("active");
  $(".collapsible").collapsible({accordion: false});
}

function collapseAll(){
  $(".collapsible-header").removeClass(function(){
    return "active";
  });
  $(".collapsible").collapsible({accordion: true});
  $(".collapsible").collapsible({accordion: false});
}
$(function (){
	
	// MARVIN
	initialize_data();
	
	
  if( $( '.tooltipped' ).length !== 0 )
  {
    $('.tooltipped').tooltip({delay: 50});
  }
  jQuery(document).off('submit', '#time_log_form');
  jQuery(document).on('submit', '#time_log_form', function(e){
      e.preventDefault();
      
	 // MARVIN
	$(".time_input").attr('disabled', false);
	
	
    var data = $('#time_log_form').serialize();
	
      button_loader('save_time_logs', 1);
      var option = {
        url  : $base_url + '<?php echo PROJECT_MAIN ?>/employee_dtr/process_manual_adjustment',
        data : data,
        success : function(result){
          if(result.status)           
          {
            notification_msg("<?php echo SUCCESS ?>", result.message);
			
			// MARVIN
			for(i=0;i<$(".time_input").length;i++)
			{
				if(data_map[i] == "" && $("#attendance_time_"+i).val() == "")
				{
					$("#attendance_time_"+i).attr("disabled", false);
				}
				else if(data_map[i] == "" && $("#attendance_time_"+i).val() != "")
				{
					$("#attendance_time_"+i).attr("disabled", false);
				}
				else
				{
					$("#attendance_time_"+i).attr("disabled", true);
				}
			}
          }
          else
          {
			console.log(data_map);
            notification_msg("<?php echo ERROR ?>", result.message);
			
			// MARVIN
			for(i=0;i<$(".time_input").length;i++)
			{
				if(data_map[i] == "" && $("#attendance_time_"+i).val() == "")
				{
					$("#attendance_time_"+i).attr("disabled", false);
				}
				else if(data_map[i] == "" && $("#attendance_time_"+i).val() != "")
				{
					$("#attendance_time_"+i).attr("disabled", false);
				}
				else
				{
					$("#attendance_time_"+i).attr("disabled", true);
				}
			}
          } 
			//marvin
			location.reload();
        },
        
        complete : function(jqXHR){
          button_loader('save_time_logs', 0);
        }
    };

    General.ajax(option); 

      
    });
  $('#fltr_dtr_start').datetimepicker({
    format:'Y/m/d',
    formatDate:'Y/m/d',
    scrollInput: false,
    onShow:function( ct ){
      this.setOptions({
      maxDate:jQuery('#fltr_dtr_end').val()?jQuery('#fltr_dtr_end').val():false
      })
    },
    timepicker:false
  });
    
  $('#fltr_dtr_end').datetimepicker({
    format:'Y/m/d',
    formatDate:'Y/m/d',
    scrollInput: false,
    onShow:function( ct ){
      this.setOptions({
        minDate:jQuery('#fltr_dtr_start').val()?jQuery('#fltr_dtr_start').val():false
      })
    },
    timepicker:false
  });
  
})

//marvin
function getTimeLogs(input_id, timeLogs){

	// console.log(input_id, timeLogs);
	$("#"+input_id+"").datetimepicker({
		startDate : timeLogs,
		minDate : timeLogs,
		maxDate : timeLogs,
		formatDate:'Y/m/d'
	});
}

//marvin
function print_raw_attendance (){

	//temporary disabled
	// notification_msg("<?php echo ERROR ?>", "Not yet implemented");
	
	
	
  var date_from = $('#fltr_dtr_start').val();
  var date_to   = $('#fltr_dtr_end').val();   
  var action    = $('#action').val();   
  var id        = $('#id').val();   
  var token     = $('#token').val();   
  var salt      = $('#salt').val();   
  var module    = $('#module').val();   

    if(date_from != "" && date_to != "")
    {
      var data_pass = {
        'date_from' : dateFormat(date_from, 'yyyy-m-d'),
        'date_to'   : dateFormat(date_to, 'yyyy-m-d'),
        'action'    : action,
        'id'        : id,
        'token'     : token,
        'salt'      : salt,
        'module'    : module
      };
      var data = 'date_from='+dateFormat(date_from, 'yyyy-m-d')+'&date_to='+dateFormat(date_to, 'yyyy-m-d')+'&action='+action+'&id='+id+'&token='+token+'&salt='+salt+'&module='+module;
      window.open($base_url + 'main/reports_ta/print_raw_bio_logs/print_employee_dtr/?' + data, '_blank');
        
    }
    else{
      if(date_from == "" && date_to != "")
      {
        notification_msg("<?php echo ERROR ?>", "<b>Date From</b> is required.");
      }
      else if(date_to == "" && date_from != "")
      {
        notification_msg("<?php echo ERROR ?>", "<b>Date To</b> is required.");
      }
      else
      {
        notification_msg("<?php echo ERROR ?>", "<b>Date From</b> and <b>Date To</b> is required.");
      }
      
    }
}
</script>
