<?php
/* GET SYSTEM LOGO */
$system_logo_src        = base_url() . PATH_SETTINGS_UPLOADS . get_setting(GENERAL, "system_logo");
$system_logo_src        = @getimagesize($system_logo_src) ? $system_logo_src : base_url() . PATH_IMAGES . "logo_white.png";

/* GET SYSTEM FAVICON */
$system_favicon_src     = base_url() . PATH_SETTINGS_UPLOADS . get_setting(GENERAL, "system_favicon");
$system_favicon_src     = @getimagesize($system_favicon_src) ? $system_favicon_src : base_url() . PATH_IMAGES . "template_doh_images/favicon-32x32.png";

/* GET USER AVATAR */
$avatar_src = base_url() . PATH_USER_UPLOADS . $this->session->photo;
$avatar_src = @getimagesize($avatar_src) ? $avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";


$pass_data              = array();

$pass_data['resources'] = $resources;
$pass_data['initial']   = TRUE;
?>

<!DOCTYPE html>
<html>
<head>
  <title>PTIS<?php //echo get_setting(GENERAL, "system_title") ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <link rel="shortcut icon" href="<?php echo $system_favicon_src; ?>" id="favico_logo"/>
  <link rel="stylesheet" media="screen" href="<?php echo base_url().PATH_CSS ?>style.css" />
  <link rel="stylesheet" media="screen" href="<?php echo base_url().PATH_CSS ?>custom.css" />
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>parsley.css" type="text/css" />
 <!--  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>skins/skin_<?php echo get_setting(THEME, "skins") ?>.css"> -->
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>materialize.css"  media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>flaticon.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>material_icons.css">
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>jquery.jscrollpane.css" rel="stylesheet" media="all" />
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>component.css" rel="stylesheet" media="all" />
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>popModal.css" rel="stylesheet" media="all" />

  <!-- DOH TEMPLATE CSS-->
  <!--<link href="<?php //echo base_url().PATH_CSS ?>template_doh_css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">-->
  <link href="<?php echo base_url().PATH_CSS ?>template_doh_css/template.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- DOH CUSTOM CSS-->    
  <link href="<?php echo base_url().PATH_CSS ?>template_doh_css/template_custom_style.css" type="text/css" rel="stylesheet" media="screen,projection">    
  <!-- CSS style Horizontal Nav (Layout 03)-->    
  <link href="<?php echo base_url().PATH_CSS ?>template_doh_css/template_style_horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">

  <!-- ALWAYS ON TOP (nodejs) -->
  <!--<script src="<?php //echo NODEJS_SERVER ?>socket.io/socket.io.js"></script>-->
  <script src="<?php echo base_url().PATH_JS ?>less.min.js" type="text/javascript"></script>
  <!-- PAGE LOADER SCRIPT -->
  <script src="<?php echo base_url().PATH_JS ?>pace.js" type="text/javascript"></script>

  <?php
  if(!EMPTY($resources["load_css"])){
    foreach($resources["load_css"] as $css):
      echo '<link href="'. base_url() . PATH_CSS . $css .'.css" rel="stylesheet" type="text/css">';
    endforeach;
  }
  ?>

  <!-- JQUERY 2.1.1+ IS REQUIRED BY MATERIALIZE TO FUNCTION -->
  <script src="<?php echo base_url().PATH_JS ?>jquery-2.1.1.min.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>jquery-ui.min.js" type="text/javascript"></script>

</head>
<body>
  <input type="hidden" id="base_url" value="<?php echo base_url() ?>">
    <!-- (nodejs) -->
    <input type="hidden" id="nodejs_server" value="<?php echo NODEJS_SERVER ?>"/>
    <input type="hidden" id="user_id" value="<?php echo $this->session->user_id ?>"/>
    <?php $user_orgs = $this->session->userdata('user_offices'); 
      foreach ($user_orgs as $key => $orgs) {
        echo '<input type="hidden" id="org_code'.$key.'" value="'.$orgs.'"/>';
      }
    ?>
    <?php $user_roles = implode(",",$this->session->user_roles); ?>
    <input type="hidden" id="user_roles" value="<?php echo $user_roles ?>"/>
    <input type="hidden" id="notif_cnt_<?php echo $this->session->user_id ?>"/>
    <!-- (nodejs) -->

  <input type="hidden" id="path_user_uploads" value="<?php echo PATH_USER_UPLOADS ?>" />
  <input type="hidden" id="path_images" value="<?php echo PATH_IMAGES ?>" />
  <input type="hidden" id="path_settings_upload" value="<?php echo PATH_SETTINGS_UPLOADS ?>">
  <input type="hidden" id="path_file_uploads" value="<?php echo PATH_FILE_UPLOADS ?>">
    <script src="<?php echo base_url().PATH_JS ?>script.js" type="text/javascript"></script>

      <!-- HEADER SECTION -->
     <header id="header" class="page-topbar">
        <!-- start header nav-->
        <div class="navbar-fixed">
            <nav class="teal darken-2">
                <div class="nav-wrapper">
                    <div class="pull-left">
                      <ul>
                        <li>
                          <a href="<?php echo base_url() . HOME_PAGE ?>" class="brand-logo darken-1 p-l-sm bg-none">
                            <img src="<?php echo base_url().PATH_IMAGES ?>template_doh_images/dohlogo.png"/>
                            <!--<img src="<?php //echo base_url().PATH_IMAGES ?>template_doh_images/header_flag.png" class="flag"/>-->
                            <h6 id="header-text1">Republic of the Philippines</h6>
                            <h6 id="header-text2">DEPARTMENT OF HEALTH</h6>
                            <h6 id="header-text3">ATTENDANCE MANAGEMENT SYSTEM</h6>
                          </a>
                        </li>
                      </ul>
                    </div>  
                    <div class="pull-right h-menu p-r-sm ">
                      
                      <li class="dropdown hm-profile icon">
                          <a  class='dropdown-button' href='#' data-activates='dropdown-inbox'>
                                <i class="material-icons">notifications</i><span id="notif_cnt" class="notif-count"></span>
                          </a>
                         <div id="dropdown-inbox" class="dropdown-content" style="width:100%;">       
          
                            <div id="notification-title" class="dropdown-title">
                              <i class="flaticon-bell43"></i><span class="white-text"> Notifications</span>
                            </div>
                            <div style=" clear:both;"></div>
                            <div class="scroll-pane white" style="height:350px;">
                              <ul id="notification-content" class="collection">
                                <li class="white"><p class='center-align'>No new notification...</p></li>
                              </ul>
                            </div>
                        </div>
                      </li>
                      <li class="dropdown hm-profile hide-on-med-and-down">
                          <a  class='dropdown-button btn' href='#' data-activates='dropdown001'>
                                <img src="<?php echo $avatar_src?>" class="left" style="margin: 0px 15px 15px 0px;"/> 
                                 <small><?php echo $this->session->userdata("name"); ?></small>
                          </a>
                          <ul  id='dropdown001' class='dropdown-menu pull-right'>
                              <li>
                                  <a href="javascript:;" class="md-trigger" data-modal="modal_profile" onclick="profile_modal_init()"><i class="material-icons left p-r-sm m-n">face</i>View Profile</a>
                               </li>
                              <li class="hide">
                                  <a href=""><i class="material-icons left p-r-sm m-n">lock</i>Privacy Setting</a>
                              </li>
                              <li>
                                  <a href="javascript:;" class="logout"><i class="material-icons left p-r-sm m-n">exit_to_app</i>Log Out</a>
                              </li>
                          </ul>
                      </li>
                      <li class="no-hover"><a href="#" data-activates="slide-out" class="menu-sidebar-collapse hide-on-large-only"><i class="material-icons" >menu</i></a></li>
                      
                    </div>
                </div>
            </nav>
            <?php 
                $system_name = $this->session->userdata('system_name');
                $system_code = $this->session->userdata('system_code');
                $count       = count($this->session->userdata('system_code'));
            ?>
            <nav id="horizontal-nav" class="white hide-on-med-and-down">
                  <div class="nav-wrapper">                  
                    <ul id="nav-mobile" class="left hide-on-med-and-down nav m-b-n">
                        <?php if($this->permission->check_permission(MODULE_DASHBOARD, ACTION_VIEW)) :?>
                        <li>
                            <a href="<?php if($count !=1 ){
                                echo "#!" ;
                              }else
                              {
                                echo base_url() . PROJECT_MAIN . '/dashboard';
                                } ?>">
                                <i class="material-icons left">dashboard</i>
                                    <span class="p-r-md p-r-md">Dashboard</span>
                                <?php if($count != 1):?>
                                        <i class="material-icons right m-n p-r-xs">arrow_drop_down</i>
                                <?php endif;?>
                            </a>
                            <?php if($count != 1):?>
                                <ul class="dropdown-horizontal-list">
                                    <?php foreach ($system_name as $key => $value) { ?>
                                        <li>
                                            <a href="<?php echo base_url() . PROJECT_MAIN . "/dashboard/get_dashboard/".$system_code[$key] ?>"><?php echo $value ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                             <?php endif;?>
                        </li>
                      <?php endif; ?>
                        <?php if($this->session->userdata("user_pds_id")):

                          $user_pds_id = $this->session->userdata("user_pds_id");
                          $pds_module  = MODULE_PERSONNEL_PORTAL;
                          $pds_salt    = gen_salt();
                          $pds_token   = in_salt($user_pds_id  . '/' . ACTION_EDIT  . '/' . $pds_module, $pds_salt);
                          $pds_url     = ACTION_EDIT."/".$user_pds_id ."/".$pds_token."/".$pds_salt."/".$pds_module;
        
                        ?>
                         <?php if($this->permission->check_permission(MODULE_PERSONNEL_PORTAL, ACTION_VIEW)) :?>
                        <li>
                            <a href="#!">
                              <i class="material-icons left p-r-md m-n">person</i>
                              <span>My Portal</span><i class="material-icons right m-n">arrow_drop_down</i>
                            </a>
                            <ul class="dropdown-horizontal-list">
                              <?php if($this->permission->check_permission(MODULE_PORTAL_MY_REQUESTS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/employee_requests">Requests</a></li>
                             <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_PORTAL_PERSONAL_DATA_SHEET, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/pds/display_pds_info/<?php echo $pds_url;?>">Personal Data Sheet</a></li>                  
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PORTAL_PERFORMANCE_EVALUATION, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/performance_evaluation/employee_performance_evaluation_list/<?php echo $pds_url?>">Performance Evaluation</a></li> 
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PORTAL_COMPENSATION, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/compensation/employee_tabs/<?php echo $pds_url?>">Compensation</a></li>                   
                             <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PORTAL_DEDUCTIONS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/deductions/get_statutory_info/<?php echo $pds_url?>">Deductions</a></li>
                             <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_PORTAL_DAILY_TIME_RECORD, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/employee_dtr">Daily Time Records</a></li>
                            <?php endif; ?>
                            </ul>
                          </li>
                        <?php endif; ?>
                        <?php endif; ?>
                         <?php if($this->permission->check_permission(MODULE_HUMAN_RESOURCES, ACTION_VIEW)) :?>
                          <li>
                            <a href="#!">
                              <i class="material-icons left p-r-md m-n">people</i>
                              <span>Human Resources</span><i class="material-icons right m-n">arrow_drop_down</i>
                            </a>
                            <!-- HUMAN RESOURCES DROPDOWN-->
                            <ul class="dropdown-horizontal-list">
                              <?php if($this->permission->check_permission(MODULE_HR_PERSONAL_DATA_SHEET, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/pds">Personal Data Sheets</a></li>
                              <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_HR_PERFORMANCE_EVALUATION, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/performance_evaluation">Performance Evaluations</a></li>
                             <?php endif; ?>                           
                             <?php if($this->permission->check_permission(MODULE_HR_CODE_LIBRARY, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_HUMAN_RESOURCES?>">Code Library</a></li>
                              <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_HR_REPORTS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/reports_hr/reports_hr/">Reports</a></li>
                             <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if($this->permission->check_permission(MODULE_TIME_AND_ATTENDANCE, ACTION_VIEW)) :?>
                        <li>
                            <a href="#!">
                              <i class="material-icons left p-r-md m-n">event_note</i>
                              <span>Time & Attendance</span><i class="material-icons right m-n">arrow_drop_down</i>
                            </a>
                            <!-- TIME AND ATTENDANCE DROPDOWN-->
                            <ul class="dropdown-horizontal-list">
                              <?php if($this->permission->check_permission(MODULE_TA_ATTENDANCE_LOGS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/biometric_logs">Attendance Logs</a></li>
                               <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_TA_ATTENDANCE_PERIOD, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/attendance_period">Attendance Periods</a></li>
                               <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_TA_DAILY_TIME_RECORD, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/daily_time_record">Employee Attendance</a></li>
                               <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_TA_LEAVES, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/leaves">Leaves</a></li>                  
                               <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_TA_CODE_LIBRARY, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_ATTENDANCE?>">Code Library</a></li>
                               <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_TA_REPORTS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/reports_ta/reports_ta/">Reports</a></li>
                              <?php endif; ?>
                          </ul>
                        </li>
                        <?php endif; ?>
                        <?php if($this->permission->check_permission(MODULE_PAYROLL, ACTION_VIEW)) :?>
                        <li>
                            <a class=" p-l-lg" href="#!">
                              <i class="material-icons left ">assessment</i>
                              <span>Payroll</span><i class="material-icons right m-n p-r-xs">arrow_drop_down</i>
                            </a>
                             <!-- PAYROLL DROPDOWN -->
                            <ul class="dropdown-horizontal-list">
                             <?php if($this->permission->check_permission(MODULE_PAYROLL_GENERAL_PAYROLL, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll">General Payroll</a></li>
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PAYROLL_SPECIAL_PAYROLL, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/special_payroll">Special Payroll</a></li>
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PAYROLL_VOUCHER, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll_voucher">Employee Vouchers</a></li>
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PAYROLL_REMITTANCE, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll_remittance">Remittances</a></li>                  
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_HR_COMPENSATION, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/compensation/">Compensations</a></li>                  
                              <?php endif; ?>
                             <?php if($this->permission->check_permission(MODULE_HR_DEDUCTIONS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/deductions">Deductions</a></li>
                             <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PAYROLL_CODE_LIBRARY, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_PAYROLL?>">Code Library</a></li>
                              <?php endif; ?>
                              <?php if($this->permission->check_permission(MODULE_PAYROLL_REPORTS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/reports_payroll/reports_payroll/">Reports</a></li>
                            <?php endif; ?>
                              </ul>
                        </li>
                        <?php endif; ?>
                         <?php if($this->permission->check_permission(MODULE_SYSTEM, ACTION_VIEW)) :?>
                        <li>
                            <a class=" p-l-lg" href="#!">
                              <i class="material-icons left p-r-md m-n">settings</i>
                              <span>System</span><i class="material-icons right m-n p-r-xs">arrow_drop_down</i>
                            </a>
                            <!-- USER MANAGEMENT DROPDOWN -->
                            <ul class="dropdown-horizontal-list">
                                <?php if($this->permission->check_permission(MODULE_REQUESTS_APPROVALS, ACTION_VIEW)) :?>
                                            <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/requests">Request Approvals</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_WORKFTLOW, ACTION_VIEW)) :?>
                                            <li><a href="<?php echo base_url() . PROJECT_CORE ?>/manage_workflow">Request Workflows</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_FILE, ACTION_VIEW)) :?>
                                            <li><a href="<?php echo base_url() . PROJECT_CORE ?>/files">Files</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_ADHOC_REPORTS, ACTION_VIEW)) :?>
                                            <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/adhoc_reports/module/">Adhoc Reports</a></li>
                                <?php endif; ?>                               
                                <?php if($this->permission->check_permission(MODULE_CODE_LIBRARY, ACTION_VIEW)) :?>
                                            <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_SYSTEM?>">Code Library</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_SYSTEM_ACCOUNT_SETTINGS, ACTION_VIEW)) :?>
                                            <li><a href="<?php echo base_url() . PROJECT_CORE ?>/account_settings">Account Settings</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                         <?php endif; ?>
                         <?php if($this->permission->check_permission(MODULE_USER_MANAGEMENT, ACTION_VIEW)) :?>
                        <li class="pad29">
                            <a class=" p-l-md" href="#!">
                              <i class="material-icons left p-r-md m-n p-l-xs">person</i>
                              <span>User Management</span><i class="material-icons right m-n">arrow_drop_down</i>
                            </a>
                            <!-- USER MANAGEMENT DROPDOWN -->
                            <ul class="dropdown-horizontal-list">
                                <?php if($this->permission->check_permission(MODULE_USER, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_CORE ?>/users">Users</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_ROLE, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_CORE ?>/roles">Roles</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_PERMISSION, ACTION_VIEW)) :?>
                                         <li><a href="<?php echo base_url() . PROJECT_CORE ?>/permissions">Permissions</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_ORGANIZATION, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_CORE ?>/organizations">Organizations</a></li>
                                <?php endif; ?>
                                <?php if($this->permission->check_permission(MODULE_AUDI_TRAIL, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_CORE ?>/audit_log">Audit Trail</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                         <?php endif; ?>
                    </ul>
                  </div>
            </nav>

            <!-- HORIZONTL NAV END-->
        </div>
        <!-- end header nav-->
    </header>
    <!-- END HEADER -->

          <!-- START MAIN -->
  <div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper wrapper-bg hide-on-large-only">

      <!-- START LEFT SIDEBAR NAV-->
            <aside id="left-sidebar-nav">
                <ul id="slide-out" class="side-nav leftside-navigation ">
                    <li class="user-details cyan darken-2">
                        <div class="row">
                            <div class="col s4 m4 l4">
                                <img src="<?php echo $avatar_src?>" alt="" class="circle responsive-img valign profile-image">
                            </div>
                             <a class="btn-flat dropdown-button white-text profile-btn user-roal" href="#" data-activates="profile-dropdown"><?php echo $this->session->userdata("name"); ?><i class="material-icons right">arrow_drop_down</i></a>
                            <div>
                               <ul id="profile-dropdown" class="dropdown-content">
                                    <li><a href="javascript:;" class="md-trigger" data-modal="modal_profile" onclick="profile_modal_init()"><i class="material-icons">face</i>Profile</a>
                                    </li><!-- 
                                    <li><a href="#" data-activates="notification-out" class="chat-collapse"><i class="material-icons">notifications</i>Notification</a>
                                    </li> -->
                                    <li class="divider"></li>
                                    <li><a href="javascript:;" class="logout"><i class="material-icons">exit_to_app</i>Logout</a>
                                    </li>
                                </ul>
                               
                            </div>
                        </div>
                    </li>
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <?php if($this->permission->check_permission(MODULE_DASHBOARD, ACTION_VIEW)) :?>
                              <li class="bold">
                                  <a class=" collapsible-header" href="<?php if($count !=1 ){
                                      echo "#!" ;
                                    }else
                                    {
                                      echo base_url() . PROJECT_MAIN . '/dashboard';
                                      } ?>">
                                      <i class="material-icons left">dashboard</i>
                                          <span class="p-r-md p-r-md">Dashboard</span>
                                      <?php if($count != 1):?>
                                              <i class="material-icons right m-n p-r-xs">arrow_drop_down</i>
                                      <?php endif;?>
                                  </a>
                                  <?php if($count != 1):?>
                                  <div class="collapsible-body" style="display: none;">
                                      <ul>
                                          <?php foreach ($system_name as $key => $value) { ?>
                                              <li>
                                                  <a href="<?php echo base_url() . PROJECT_MAIN . "/dashboard/get_dashboard/".$system_code[$key] ?>"><?php echo $value ?></a>
                                              </li>
                                          <?php } ?>
                                      </ul>
                                  </div>
                                   <?php endif;?>
                              </li>
                            <?php endif; ?>
                            <?php if($this->session->userdata("user_pds_id")):

                              $user_pds_id = $this->session->userdata("user_pds_id");
                              $pds_module  = MODULE_PERSONNEL_PORTAL;
                              $pds_salt    = gen_salt();
                              $pds_token   = in_salt($user_pds_id  . '/' . ACTION_EDIT  . '/' . $pds_module, $pds_salt);
                              $pds_url     = ACTION_EDIT."/".$user_pds_id ."/".$pds_token."/".$pds_salt."/".$pds_module;
            
                            ?>
                         <?php if($this->permission->check_permission(MODULE_PERSONNEL_PORTAL, ACTION_VIEW)) :?>  
                            <li class="bold"><a class="collapsible-header"><i class="material-icons">person</i> My Portal<i class="material-icons right m-n">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php if($this->permission->check_permission(MODULE_PORTAL_MY_REQUESTS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/employee_requests">Requests</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PORTAL_PERSONAL_DATA_SHEET, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/pds/display_pds_info/<?php echo $pds_url;?>">Personal Data Sheet</a></li>                  
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PORTAL_PERFORMANCE_EVALUATION, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/performance_evaluation/employee_performance_evaluation_list/<?php echo $pds_url?>">Performance Evaluation</a></li> 
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PORTAL_COMPENSATION, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/compensation/employee_tabs/<?php echo $pds_url?>">Compensation</a></li>                   
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PORTAL_DEDUCTIONS, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/deductions/get_statutory_info/<?php echo $pds_url?>">Deductions</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PORTAL_DAILY_TIME_RECORD, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/employee_dtr">Daily Time Records</a></li>
                                      <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>
                         <?php if($this->permission->check_permission(MODULE_HUMAN_RESOURCES, ACTION_VIEW)) :?>
                            <li class="bold"><a class="collapsible-header"><i class="material-icons">people</i>Human Resources<i class="material-icons right m-n">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php if($this->permission->check_permission(MODULE_HR_PERSONAL_DATA_SHEET, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/pds">Personal Data Sheet</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_HR_PERFORMANCE_EVALUATION, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/performance_evaluation">Performance Evaluation</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_HR_CODE_LIBRARY, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_HUMAN_RESOURCES?>">Code Library</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_HR_REPORTS, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/reports_hr/reports_hr/">Reports</a></li>
                                       <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                             <?php endif; ?>
                        <?php if($this->permission->check_permission(MODULE_TIME_AND_ATTENDANCE, ACTION_VIEW)) :?>
                            <li class="bold"><a class="collapsible-header"><i class="material-icons">event_note</i>Time & Attendance<i class="material-icons right m-n">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php if($this->permission->check_permission(MODULE_TA_ATTENDANCE_LOGS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/biometric_logs">Attendance Logs</a></li>
                                        <?php endif; ?>                               
                                        <?php if($this->permission->check_permission(MODULE_TA_ATTENDANCE_PERIOD, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/attendance_period">Attendance Period</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_TA_DAILY_TIME_RECORD, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/daily_time_record">Employee Attendance</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_TA_LEAVES, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/leaves">Leaves</a></li>                  
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_TA_CODE_LIBRARY, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_ATTENDANCE?>">Code Library</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_TA_REPORTS, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/reports_ta/reports_ta/">Reports</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                             <?php endif; ?>
                        <?php if($this->permission->check_permission(MODULE_PAYROLL, ACTION_VIEW)) :?>
                            <li class="bold"><a class="collapsible-header">
                              <i class="material-icons left ">assessment</i>Payroll<i class="material-icons right m-n">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php if($this->permission->check_permission(MODULE_PAYROLL_GENERAL_PAYROLL, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll">General Payroll</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PAYROLL_SPECIAL_PAYROLL, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/special_payroll">Special Payroll</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PAYROLL_VOUCHER, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll_voucher">Employee Voucher</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PAYROLL_REMITTANCE, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/payroll_remittance">Remittance</a></li>                  
                                        <?php endif; ?>                              
                                        <?php if($this->permission->check_permission(MODULE_HR_COMPENSATION, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/compensation/">Compensation</a></li>                  
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_HR_DEDUCTIONS, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/deductions">Deductions</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PAYROLL_CODE_LIBRARY, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_PAYROLL?>">Code Library</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PAYROLL_REPORTS, ACTION_VIEW)) :?>
                                                  <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/reports_payroll/reports_payroll/">Reports</a></li>
                                      <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                            <?php endif; ?>
                         <?php if($this->permission->check_permission(MODULE_SYSTEM, ACTION_VIEW)) :?>
                             <li class="bold"><a class="collapsible-header"><i class="material-icons">settings</i>Systems<i class="material-icons right m-n">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php if($this->permission->check_permission(MODULE_REQUESTS_APPROVALS, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/requests">Request Approvals</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_WORKFTLOW, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_CORE ?>/manage_workflow">Request Workflows</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_FILE, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_CORE ?>/files">Files</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_ADHOC_REPORTS, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/adhoc_reports/module/">Adhoc Reports</a></li>
                                        <?php endif; ?>                               
                                        <?php if($this->permission->check_permission(MODULE_CODE_LIBRARY, ACTION_VIEW)) :?>
                                                    <li><a href="<?php echo base_url() . PROJECT_MAIN ?>/code_library/module/<?php echo CODE_LIBRARY_SYSTEM?>">Code Library</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                             <?php endif; ?>
                         <?php if($this->permission->check_permission(MODULE_USER_MANAGEMENT, ACTION_VIEW)) :?>
                             <li class="bold"><a class="collapsible-header"><i class="material-icons">person</i>User Management<i class="material-icons right m-n">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php if($this->permission->check_permission(MODULE_USER, ACTION_VIEW)) :?>
                                        <li><a href="<?php echo base_url() . PROJECT_CORE ?>/users">Users</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_ROLE, ACTION_VIEW)) :?>
                                                <li><a href="<?php echo base_url() . PROJECT_CORE ?>/roles">Roles</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_PERMISSION, ACTION_VIEW)) :?>
                                                 <li><a href="<?php echo base_url() . PROJECT_CORE ?>/permissions">Permissions</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_ORGANIZATION, ACTION_VIEW)) :?>
                                                <li><a href="<?php echo base_url() . PROJECT_CORE ?>/organizations">Organizations</a></li>
                                        <?php endif; ?>
                                        <?php if($this->permission->check_permission(MODULE_AUDI_TRAIL, ACTION_VIEW)) :?>
                                                <li><a href="<?php echo base_url() . PROJECT_CORE ?>/audit_log">Audit Trail</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
          </aside>
            <!-- END LEFT SIDEBAR NAV-->
         </div>
 
      <!-- //////////////////////////////////////////////////////////////////////////// -->
    <!-- MAIN CONTENT SECTION -->
    <main class="container p-n" id="container">
    <?php echo $contents ?>
    </main>

  <!-- //////////////////////////////////////////////////////////////////////////// -->

    <!-- START FOOTER -->
    <footer class="page-footer p-t-n p-l-md p-r-md m-t-n">
        <div class="footer-copyright">
            <span>Copyright © 2016 <a class="grey-text text-lighten-4" href="http://www.doh.gov.ph/" target="_blank">Department of Health</a> All rights reserved.</span>
            <!--<span class="right"> Powered by <a class="grey-text text-lighten-4" target="_blank" href="http://www.asiagate.com/">Asiagate Networks, Inc.</a></span>-->
            <span class="right"> Powered by <a class="grey-text text-lighten-4" href="#">Knowledge Management and Information Technology Service</a></span>
        </div>
    </footer>
    <!-- END FOOTER -->
    </div>

    <!-- NOTIFICATION SECTION -->
    <div class="notify success none"><div class="success"><h4><span>Success!</span></h4><p></p></div></div>
    <div class="notify error none"><div class="error"><h4><span>Warning!</span></h4><p></p></div></div>

    <!-- CONFIRMATION SECTION -->
    <div id="confirm_modal" style="display:none">
        <div class="confirmModal_content">
            <h4></h4>
            <p></p>
        </div>
        <div class="confirmModal_footer">
            <button type="button" value="Ok" id="confirm_modal_btn" class="btn bg-success" data-confirmmodal-but="ok">Ok</button>
            <button type="button" data-confirmmodal-but="cancel"><?php echo BTN_CANCEL ?></button>
        </div>
    </div>

    <div id="loading" class="none" align="center">
    <div class="p-lg center-align">
    <img src="<?php echo base_url() . PATH_IMAGES ?>loading40.gif" />
    </div>
    </div>

    <!-- Modal -->
    <div id="modal_profile" class="md-modal lg md-effect-<?php echo MODAL_EFFECT ?>">
        <div class="md-content">
            <a class="md-close icon">&times;</a>
            <div id="modal_profile_content"></div>
        </div>
    </div>

    <!-- DEFAULT SIZE -->
    <div id="modal_default" class="md-modal md-effect-<?php echo MODAL_EFFECT ?>">
        <div class="md-content">
            <a class="md-close icon">&times;</a>
            <div id="modal_default_content"></div>
        </div>
    </div>

    <!-- MEDIUM SIZE -->
    <div id="modal_medium" class="md-modal md-effect-<?php echo MODAL_EFFECT ?> md">
        <div class="md-content">
            <a class="md-close icon none">&times;</a>
            <div id="modal_medium_content"></div>
        </div>
    </div>

    <!-- LARGE SIZE -->
    <div id="modal_large" class="md-modal md-effect-<?php echo MODAL_EFFECT ?> lg">
        <div class="md-content">
            <a class="md-close icon">&times;</a>
            <div id="modal_large_content"></div>
        </div>
    </div>

  <?php echo $this->view('modal_initialization', $pass_data); ?>
  <!-- <div class="md-overlay"></div> -->
  
  <!-- ================================================
    START OF DOH TEMPLATE JS
    ================================================ -->

    <!--materialize js-->
    <!--<script type="text/javascript" src="<?php //echo base_url().PATH_JS ?>template_doh_js/materialize.js"></script>-->
    <!--scrollbar-->
    <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>template_doh_js/template_perfect_scrollbar.min.js"></script>
    <!-- chartist -->
   <!-- <script type="text/javascript" src="<?php //echo base_url().PATH_JS ?>template_doh_js/template_chartist.min.js"></script>-->
    
    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
   <!-- <script type="text/javascript" src="<?php //echo base_url().PATH_JS ?>template_doh_js/template_plugins.js"></script>-->

    <!-- ================================================
    END OF DOH TEMPLATE JS
    ================================================ -->
    <!-- MODAL/DELETE INITIALIZATION -->

  
  <!-- (nodejs) -->
  <!-- since $.get('nodejs/index.html') doesn't work, we need this dummy div - $('alerts_div').load('nodejs/index.html') works -->
  <div id="alerts_div" class="none"></div>
  <!-- (nodejs) -->

  <!-- PLATFORM SCRIPT -->
  <script src="<?php echo base_url().PATH_JS ?>materialize.js"></script>
  <!-- END PLATFORM SCRIPT -->

  <script src="<?php echo base_url().PATH_JS ?>auth.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>parsley.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().PATH_JS ?>collapsible-menu.js"></script>

  <!-- JSCROLLPANE SCRIPT -->
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>jquery.mousewheel.js"></script>
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>jquery.jscrollpane.js"></script>
  <!-- END JSCROLLPANE SCRIPT -->

  <!-- UPLOAD FILE -->
  <link href="<?php echo base_url() . PATH_CSS; ?>uploadfile.css" rel="stylesheet" type="text/css">
  <script src="<?php echo base_url() . PATH_JS ?>jquery.uploadfile.js" type="text/javascript"></script>
  <!-- UPLOAD FILE -->

  <!-- POPMODAL SCRIPT -->
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>popModal.min.js"></script>
  <!-- END POPMODAL SCRIPT -->

  <!-- MODAL -->
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>classie.js"></script>
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>modalEffects.js"></script>
  <!-- MODAL -->

  <!-- FULLSCREEN MODAL -->
  <link href="<?php echo base_url() . PATH_CSS; ?>animate.min.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>animatedModal.min.js"></script>
  <!-- FULLSCREEN MODAL -->

  <!-- SEARCH -->
  <script src="<?php echo base_url().PATH_JS ?>jquery.lookingfor.min.js"></script>
  <!-- SEARCH -->

  <!-- PAGE LOADER -->
  <script src="<?php echo base_url() . PATH_JS ?>jquery.isloading.js" type="text/javascript"></script>
  <!-- PAGE LOADER -->

  <?php
  if(!EMPTY($resources["load_js"])){
    foreach($resources["load_js"] as $js):
      echo '<script src="'. base_url() . PATH_JS . $js .'.js" type="text/javascript"></script>';
    endforeach;
  }
  ?>

  <!-- (nodejs) use for time and date, ex: 5 seconds ago, 2 days ago, etc. -->
  <script type="text/javascript" src="<?php echo base_url() . PATH_JS ?>moment.js"></script>
  <!-- (nodejs) -->

  <script src="<?php echo base_url().PATH_JS ?>common.js" type="text/javascript"></script>
  <script src="<?php echo base_url().PATH_JS ?>initializations.js" type="text/javascript"></script>
  <script src="<?php echo base_url().PATH_JS ?>general.js" type="text/javascript"></script>

  <?php 
    $this->view( 'initializations', $pass_data );
  ?>

  <script>
    setTimeout(function(){
    <?php IF(!EMPTY($nav_page)):?>
      $('.list-basic-sub-menu').find('ul').find('a[href*="<?php echo $nav_page; ?>"]').closest('li').addClass('active');
      <?php ENDIF; ?>
  }, 300);
  </script>
</body>
</html>
