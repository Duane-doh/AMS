<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requests extends Main_Controller {
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('requests_model', 'requests');		
		$this->load->model('daily_time_record_model', 'dtr');
		$this->load->model('pds_model', 'pds');
		$this->log_user_id    = $this->session->userdata('user_id');
		$this->log_user_roles = $this->session->userdata('user_roles');
	}

	public function index($date_start = null, $date_end = null)
	{
		$data =  array();
		$resources = array();
		
		// $resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE);
		// $resources['load_js'] 		= array(JS_DATATABLE, JS_SELECTIZE);

		// marvin : include date range filter : start
		$resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE, CSS_DATETIMEPICKER);
		$resources['load_js'] 		= array(JS_DATATABLE, JS_SELECTIZE, JS_DATETIMEPICKER);

		if(!empty($date_start) AND !empty($date_end))
		{
			// decode parameter
			$date_start = base64_decode(urldecode($date_start));
			$date_end = base64_decode(urldecode($date_end));

			// display in filter
			$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
			$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');

			// paramter in get_request
			$date_start = format_date($date_start,'Y-m-d');
			$date_end   = format_date($date_end,'Y-m-d');

			$resources['datatable'][]	= array('table_id' => 'table_requests', 'path' => 'main/requests/get_requests/'.$date_start.'/'.$date_end.'', 'advanced_filter' => true);
		}
		else
		{
			// default 1 month range
			$date_end  = date('Y/m/d');
			$date_start = date('Y/m/d', strtotime('-1 months', strtotime($date_end)));

			// default display in filter
			$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
			$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');

			$resources['datatable'][]	= array('table_id' => 'table_requests', 'path' => 'main/requests/get_requests', 'advanced_filter' => true);
		}
		// marvin : include date range filter : end

		// $resources['datatable'][]	= array('table_id' => 'table_requests', 'path' => 'main/requests/get_requests', 'advanced_filter' => true);
		
		// $fields = array('A.office_id','B.name AS office_name');
		// $tables = array(
			// 'main' => array(
				// 'table' => $this->requests->tbl_param_offices,
				// 'alias' => 'A'
			// ),
			// 't1'   => array(
				// 'table' => $this->requests->db_core . '.' . $this->requests->tbl_organizations,
				// 'alias' => 'B',
				// 'type'  => 'JOIN',
				// 'condition' => 'A.org_code = B.org_code'
 			// )
		// );
		// $where = array('A.active_flag' => 'Y');
		// $data['office_list'] = $this->requests->get_general_data($fields, $tables, $where);
		
		//======================================================================marvin======================================================================
		//specify office/user scopes
		$user_scopes['human_resources'] 		= isset($_SESSION['user_offices'][9]) ? $_SESSION['user_offices'][9] : '';
		$user_scopes['personal_data_sheets'] 	= isset($_SESSION['user_offices'][10]) ? $_SESSION['user_offices'][10] : '';
		$user_scopes['performance_evaluation'] 	= isset($_SESSION['user_offices'][11]) ? $_SESSION['user_offices'][11] : '';
		$user_scopes['time_and_attendance'] 	= isset($_SESSION['user_offices'][49]) ? $_SESSION['user_offices'][49] : '';
		$user_scopes['attendance_logs'] 		= isset($_SESSION['user_offices'][50]) ? $_SESSION['user_offices'][50] : '';
		$user_scopes['daily_time_record'] 		= isset($_SESSION['user_offices'][51]) ? $_SESSION['user_offices'][51] : '';
		$user_scopes['leaves'] 					= isset($_SESSION['user_offices'][53]) ? $_SESSION['user_offices'][53] : '';
		$user_scopes['payroll'] 				= isset($_SESSION['user_offices'][61]) ? $_SESSION['user_offices'][61] : '';
		$user_scopes['general_payroll'] 		= isset($_SESSION['user_offices'][63]) ? $_SESSION['user_offices'][63] : '';
		$user_scopes['special_payroll'] 		= isset($_SESSION['user_offices'][64]) ? $_SESSION['user_offices'][64] : '';
		$user_scopes['voucher'] 				= isset($_SESSION['user_offices'][65]) ? $_SESSION['user_offices'][65] : '';
		$user_scopes['remittance'] 				= isset($_SESSION['user_offices'][66]) ? $_SESSION['user_offices'][66] : '';
		$user_scopes['compensation'] 			= isset($_SESSION['user_offices'][12]) ? $_SESSION['user_offices'][12] : '';
		$user_scopes['deductions'] 				= isset($_SESSION['user_offices'][13]) ? $_SESSION['user_offices'][13] : '';
		$user_scopes['request_approvals'] 		= isset($_SESSION['user_offices'][125]) ? $_SESSION['user_offices'][125] : '';
		
		//change query base on scopes
		if(in_array('AO', $_SESSION['user_roles']) OR in_array('IMMSUP', $_SESSION['user_roles']) OR in_array('LVAPPOFF', $_SESSION['user_roles']))
		{
			$fields = array('A.user_id', 'A.lname', 'A.fname', 'A.mname');
			$tables = array(
				'main' => array(
					'table' => $this->requests->db_core . '.' . $this->requests->tbl_users,
					'alias' => 'A'
				)
			);
			$where = array('A.status_id' => 1);
			$user_office_scope = explode(',',$user_scopes['request_approvals']);
			$where['A.user_id'] = array($user_office_scope, array('IN'));
			$data['user_list'] = $this->requests->get_general_data($fields, $tables, $where);
		}
		else
		{
			$fields = array('A.office_id', 'B.name AS office_name');
			$tables = array(
				'main' => array(
					'table' => $this->requests->tbl_param_offices,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->requests->db_core . '.' . $this->requests->tbl_organizations,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.org_code = B.org_code'
				)
			);
			$where = array('A.active_flag' => 'Y');
			$user_office_scope = explode(',',$user_scopes['request_approvals']);
			$where['A.office_id'] = array($user_office_scope, array('IN'));
			$data['office_list'] = $this->requests->get_general_data($fields, $tables, $where);
		}
		//======================================================================marvin======================================================================

		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "System"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/requests";
		$key					= "Request Approvals"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/requests";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('requests', $data, $resources);

	}
	// public function get_requests() ---- disabled 01-23-2023
	// marvin : include date range filter : start
	public function get_requests($date_start = null, $date_end = null)
	// marvin : include date range filter : end
	{

		try
		{
			$params = get_params();
			
			//marvin
			$params['sSortDir_0'] = 'desc';
		/*
			$aColumns 	= array("A.request_id", "A.request_code", "CONCAT(B.first_name,' ',B.last_name) as full_name", "B.agency_employee_id", "C.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_requested", "D.request_status_name");
			
			//asiagate
			// $bColumns 	= array("A.request_code", "CONCAT(B.first_name,' ',B.last_name)", "B.agency_employee_id", "C.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y')", "D.request_status_name");
			
			//marvin
			$bColumns 	= array("A.date_requested", "CONCAT(B.first_name,' ',B.last_name)", "B.agency_employee_id", "C.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y')", "D.request_status_name");
		*/
			// ====================== jendaigo : start : change name format ============= //
			$aColumns 	= array("A.request_id", "A.request_code", "CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', B.middle_name))) as full_name", "B.agency_employee_id", "C.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_requested", "D.request_status_name", "A.request_type_id");
			$bColumns 	= array("A.date_requested", "CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', B.middle_name)))", "B.agency_employee_id", "C.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y')", "D.request_status_name");
			// ====================== jendaigo : end : change name format ============= //
			
			// marvin : include date range fitler : start
			if(EMPTY($date_start) OR EMPTY($date_end) OR $date_start > $date_end)
			{
				$date_end  = date('Y/m/d');
				$date_start = date('Y/m/d', strtotime('-1 months', strtotime($date_end)));
			}
			$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
			$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');
			// $requests       = $this->requests->get_requests_list($aColumns, $bColumns, $params);
			$requests       = $this->requests->get_requests_list($aColumns, $bColumns, $params, $date_start, $date_end);
			// marvin : include date range fitler : end

			$iTotal         = $this->requests->total_length();
			// $iFilteredTotal = $this->requests->filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				// "iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"iTotalDisplayRecords" => $requests["cnt"]['cnt'],
				"aaData"               => array()
			);
			$module            = MODULE_REQUESTS_APPROVALS;
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			
			// foreach ($requests as $aRow):
			foreach ($requests['res'] as $aRow):
				$row = array();
				$action = "";
				$table = "requests_sub";
				$where = array();
				$where['request_id'] = $aRow['request_id'];
				$_request_sub_id = $this->requests->get_general_data("request_sub_id", $table, $where, TRUE);
				if($aRow['request_type_id'] == REQUEST_MANUAL_ADJUSTMENT)
				{	
					foreach($_request_sub_id as $_row)
					{
						$table = "requests_employee_attendance";
						$where = array();
						$where['request_sub_id'] = $_row['request_sub_id'];
						$requested_dates = $this->requests->get_general_data("DATE_FORMAT(attendance_date,'%M %d, %Y') as attendance_date", $table, $where, FALSE);
						if(!empty($requested_dates)){break;}
					}
					
				}
				elseif($aRow['request_type_id'] == REQUEST_LEAVE_APPLICATION)
				{
					foreach($_request_sub_id as $_row)
					{
						$table = "requests_leaves";
						$where = array();
						$where['request_sub_id'] = $_row['request_sub_id'];
						$requested_dates = $this->requests->get_general_data(array("DATE_FORMAT(date_from,'%M %d, %Y') as date_from", "DATE_FORMAT(date_to,'%M %d, %Y') as date_to"), $table, $where, FALSE);
						if(!empty($requested_dates)){break;}
					}
				}

				$id 			= $aRow['request_id'];
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;


				$row[] =  $aRow['request_code'];
				$row[] =  $aRow['full_name'];
				$row[] =  $aRow['agency_employee_id'];
				$row[] =  $aRow['request_type_name'];
				$row[] =  $aRow['date_requested'];
				if($aRow['request_type_id'] == REQUEST_MANUAL_ADJUSTMENT)
				{	
					$row[] = strtoupper($requested_dates['attendance_date']);
				}elseif($aRow['request_type_id'] == REQUEST_LEAVE_APPLICATION)
				{
					$row[] = strtoupper($requested_dates['date_from']. " - " .$requested_dates['date_to'] );
				}
				else
				{
					$row[] = " ";
				}
				// $row[] =  $aRow['request_status_name'];
				
				//marvin
				$reqStat = $aRow['request_status_name'];
				$row[] =  ($reqStat == 'New' ? '<span class="new">'.$reqStat.'</span><script>$(".new").parent().css({"background-color":"#F8F9FA", "color":"#000"});</script>' : 
							($reqStat == 'Pending' ? '<span class="pending">'.$reqStat.'</span><script>$(".pending").parent().css({"background-color":"#858E96", "color":"#fff"});</script>' : 
								($reqStat == 'Ongoing' ? '<span class="ongoing">'.$reqStat.'</span><script>$(".ongoing").parent().css({"background-color":"#00A2BA", "color":"#fff"});</script>' : 
									($reqStat == 'Cancelled' ? '<span class="cancelled">'.$reqStat.'</span><script>$(".cancelled").parent().css({"background-color":"#FFBE00", "color":"#fff"});</script>' : 
										($reqStat == 'Approved' ?  '<span class="approved">'.$reqStat.'</span><script>$(".approved").parent().css({"background-color":"#00A441", "color":"#fff"});</script>' : 
											'<span class="rejected">'.$reqStat.'</span><script>$(".rejected").parent().css({"background-color":"#E63A40", "color":"#fff"});</script>')))));

				$action = "<div class='table-actions'>";
				if($permission_view)
				$action .= "<a href='#' class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"content_form('requests/open_request/".$url_view."','main')\"></a>";
				
				// marvin : include user scope : start
				if($permission_edit)
				{
					if($aRow['request_type_name'] == 'Leave Application')
					{
						// if(in_array('IMMSUP', $_SESSION['user_roles']) || in_array('TASTAFF', $_SESSION['user_roles']) || in_array('LVAPPOFF', $_SESSION['user_roles']))
						// {
							$action .= "<a href='#' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"content_form('requests/open_request/".$url_edit."','main')\"></a>";
						// }
					}
					else
					{
						$action .= "<a href='#' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"content_form('requests/open_request/".$url_edit."','main')\"></a>";
					}
				}
				// marvin : include user scope : end	
				
				$action .= "</div>";

				$row[] = $action;

				$output['aaData'][] = $row;
			endforeach;


		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}

		echo json_encode( $output );
	}
	public function open_request($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data                 =  array();
			$resources            = array();

			$breadcrumbs       = array();
			$key               = "Request Details"; 
			$breadcrumbs[$key] = PROJECT_MAIN."/requests/".$data['url_security'];
			set_breadcrumbs($breadcrumbs, FALSE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATATABLE);	

			/*CHECK IF ACTIVE USER HAS PERMISSION TO EDIT*/
			$request_info 	= $this->requests->get_request_details($id);

			$table = "requests_sub";
			$where = array();
			$where['request_id'] = $id;
			$_request_sub_id = $this->requests->get_general_data("request_sub_id", $table, $where, FALSE);
			if($request_info['request_type_id'] == REQUEST_MANUAL_ADJUSTMENT)
			{	
				
				$table = "requests_employee_attendance";
				$where = array();
				$where['request_sub_id'] = $_request_sub_id['request_sub_id'];
				$requested_dates = $this->requests->get_general_data("DATE_FORMAT(attendance_date,'%M %d, %Y') as attendance_date", $table, $where, FALSE);
				$request_info['requested_date'] = $requested_dates['attendance_date'];
			}elseif($request_info['request_type_id'] == REQUEST_LEAVE_APPLICATION)
			{
				$table = "requests_leaves";
				$where = array();
				$where['request_sub_id'] = $_request_sub_id['request_sub_id'];
				$requested_dates = $this->requests->get_general_data(array("DATE_FORMAT(date_from,'%M %d, %Y') as date_from", "DATE_FORMAT(date_to,'%M %d, %Y') as date_to"), $table, $where, FALSE);
				$request_info['requested_date'] = $requested_dates['date_from']. " - " . $requested_dates['date_to'];
			}
			$data['request_info'] = $request_info;
			switch ($request_info['request_type_id']) {
				case REQUEST_DEDUCTION_RECORD_CHANGES:
					$request_module = MODULE_TA_DAILY_TIME_RECORD;
					break;
				case REQUEST_CERTIFICATE_CONTRIBUTION:
					$request_module = MODULE_PAYROLL;
					break;
				case REQUEST_CERTIFICATE_EMPLOYMENT:
					$request_module = MODULE_HUMAN_RESOURCES;
					break;
				case REQUEST_PAYSLIP:
					$request_module = MODULE_PAYROLL;
					break;
				case REQUEST_MANUAL_ADJUSTMENT:
					$request_module = MODULE_TA_DAILY_TIME_RECORD;
					break;
				case REQUEST_LEAVE_APPLICATION:
					$request_module = MODULE_TA_LEAVES;
					
					// marvin : include user scope : start
					$leave_user_scope = explode(',', $_SESSION['user_offices'][53]);
					if(!in_array($request_info['user_id'], $leave_user_scope) AND $request_info['user_id'] != $_SESSION['user_id'] AND in_array('IMMSUP', $_SESSION['user_roles']) AND $action == ACTION_EDIT)
					{
						throw new Exception($this->lang->line('err_approved_leave'));
					}
					// marvin : include user scope : end
					break;
				case REQUEST_PDS_RECORD_CHANGES:
					$request_module = MODULE_HR_PERSONAL_DATA_SHEET;					
					break;	
				case REQUEST_SERVICE_RECORD:
					$request_module = MODULE_HUMAN_RESOURCES;					
					break;	
			}			
			
			// $office_permission = $this->permission->check_office_permission($request_info['employ_office_id'], null,false,true,$request_module);
			
			//=============================================marvin=============================================
			//add userid in permission
			$office_permission = $this->permission->check_office_permission($request_info['employ_office_id'] OR $request_info['user_id'], null,false,true,$request_module);
			//=============================================marvin=============================================
			/*
				IF ACTIVE USER HAS NO PERMISSION:
					CHANGE ACTION TO ACTION_VIEW
			*/
			if($office_permission == false OR $request_info['request_status_id'] == REQUEST_CANCELLED OR $request_info['request_status_id'] == REQUEST_REJECTED)
			{
				$action = ACTION_VIEW;
				$salt   = gen_salt();
				$token  = in_salt($id . '/' . $action . '/' . $module , $salt);
			}

			$data['action']       = $action;
			$data['id']           = $id;
			$data['salt']         = $salt;
			$data['token']        = $token;
			$data['module']       = $module;
			$data['url_security'] = $action."/".$id."/".$token."/".$salt."/".$module;

			$post_data = array(	
								'request_id'		=> $id,
								'request_action' 	=> $action,
								'request_module' 	=> $module
				);
			$resources['datatable'][] = array('table_id' => 'table_request_workflow', 'path' => 'main/requests/get_request_tasks', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			$resources['load_modal']  = array(
					'modal_process_task' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_process_task',
							'multiple'   => true,
							'height'     => '460px',
							'size'       => 'xl',
							'title'      => 'Process Task'
					),
					'modal_request_changes' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_request_changes',
							'multiple'   => true,
							'height'     => '460px',
							'size'       => 'md',
							'title'      => 'Request Details'
					),
					'modal_supporting_documents' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_supporting_documents',
							'multiple'   => true,
							'height'     => '460px',
							'size'       => 'md',
							'title'      => 'Supporting Documents'
					)
			);

			$this->template->load('request_workflow', $data, $resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			// echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			// echo $message;

			// marvin : include user scope : start
			switch($request_module)
			{
				// leave application
				case 53:
					$data['message'] = $message;
					$this->template->load('requests', $data, $resources);
					break;
				}
			// marvin : include user scope : end
		}
	}
	public function get_request_tasks()
	{
	
		try
		{
			$params = get_params();
			
			// davcorrea : 10/25/2023 improve query speed : START
			// $aColumns 	= array("A.*","CONCAT(C.fname,' ',C.lname) as full_name", "DATE_FORMAT(A.assigned_date,'%Y/%m/%d %l:%i %p') as assigned_date", "DATE_FORMAT(A.processed_date,'%Y/%m/%d %l:%i %p') as processed_date", "B.task_status_name");
			// $bColumns 	= array("A.task_detail", "CONCAT(C.fname,' ',C.lname)", "DATE_FORMAT(A.assigned_date,'%Y/%m/%d %l:%i %p')", "DATE_FORMAT(A.processed_date,'%Y/%m/%d %l:%i %p')", "B.task_status_name"); 
			$aColumns 	= array("A.*","CONCAT(C.fname,' ',C.lname) as full_name", "DATE_FORMAT(A.assigned_date,'%Y/%m/%d %l:%i %p') as assigned_date", "DATE_FORMAT(A.processed_date,'%Y/%m/%d %l:%i %p') as processed_date", "(SELECT B.task_status_name FROM doh_ptis_module.param_task_status B WHERE B.task_status_id = A.task_status_id) AS task_status_name");
			$bColumns 	= array("A.task_detail", "CONCAT(C.fname,' ',C.lname)", "DATE_FORMAT(A.assigned_date,'%Y/%m/%d %l:%i %p')", "DATE_FORMAT(A.processed_date,'%Y/%m/%d %l:%i %p')", "task_status_name");
			// END
		
			$tasks          = $this->requests->get_task_list($aColumns, $bColumns, $params);
			// davcorrea : optimized get request task : START
			// $iTotal         = $this->requests->task_total_length();
			// $iFilteredTotal = $this->requests->task_filtered_length($aColumns, $bColumns, $params);
			// davcorrea : END
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				// davcorrea START : disabled to optimize speed
				//"iTotalRecords"        => $iTotal["cnt"],
				// "iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"iTotalRecords"        => count($tasks),
				"iTotalDisplayRecords" => count($tasks),
				"aaData"               => array()
				// END
			);
			$module            = $params['request_module'];
			$request_action    = $params['request_action'];
			
			$permission_view   = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit   = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
		
	
			foreach ($tasks as $aRow):

				$roles 	= $this->requests->get_task_roles($aRow['process_id'], $aRow['process_stage_id']);
				$task_roles = array();
				if(!empty($roles)):
					foreach($roles as $role):
						$task_roles[] = $role['role_code'];
					endforeach;	
				endif;
				$row           = array();
				$id            = $aRow['request_task_id'];
				$salt          = gen_salt();
				
				$token_view    = in_salt($id  . '/' . ACTION_VIEW . '/' . $module, $salt);
				$token_edit    = in_salt($id  . '/' . ACTION_EDIT . '/' . $module, $salt);
				$token_process = in_salt($id  . '/' . ACTION_PROCESS . '/' . $module, $salt);
				
				$url_view      = ACTION_VIEW ."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_get       = ACTION_EDIT ."/".$id ."/".$token_edit."/".$salt."/".$module;				
				$url_process   = ACTION_PROCESS ."/".$id ."/".$token_process."/".$salt."/".$module;

				$row[] =  $aRow['task_detail'];
				$row[] =  !EMPTY($aRow['full_name']) ? $aRow['full_name'] : '--:--';
				$row[] =  !EMPTY($aRow['assigned_date']) ? $aRow['assigned_date'] : '--:--';
				$row[] =  !EMPTY($aRow['processed_date']) ? $aRow['processed_date'] : '--:--';
				$row[] =  $aRow['task_status_name'];
		
				$action = "<div class='table-actions'>";
				
				if($permission_view == true)
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-modal='modal_process_task' data-position='bottom' data-delay='50' onclick=\"modal_process_task_init('".$url_view."')\"></a>";
				
				$role_intersect = array_intersect($task_roles,$this->log_user_roles);
				// if($permission_edit == true AND $params['request_action'] != ACTION_VIEW AND $module != MODULE_PORTAL_MY_REQUESTS AND $aRow['task_status_id'] == TASK_NOT_YET_STARTED AND !EMPTY($role_intersect))
				// $action .= "<a href='#' class='get tooltipped' data-tooltip='Get This Task' data-position='bottom' data-delay='50' onclick=\"get_task('".$url_get."')\"></a>";
				if($permission_edit == true AND $params['request_action'] != ACTION_VIEW AND $module != MODULE_PORTAL_MY_REQUESTS AND !EMPTY($role_intersect))
				{
					if($this->log_user_id == $aRow['assigned_to'] AND $aRow['task_status_id'] == TASK_ONGOING)
					{
						$action .= "<a href='#' class='process tooltipped md-trigger' data-tooltip='Process' data-modal='modal_process_task' data-position='bottom' data-delay='50' onclick=\"modal_process_task_init('".$url_process."')\"></a>";
					}
					if($aRow['task_status_id'] == TASK_NOT_YET_STARTED)
					{
						$action .= "<a href='#' class='process tooltipped md-trigger' data-tooltip='Process' data-modal='modal_process_task' data-position='bottom' data-delay='50' onclick=\"modal_process_task_init('".$url_process."')\"></a>";
					}

				}

					
				$action .= "</div>";
				$row[] = $action;
		
				$output['aaData'][] = $row;
			endforeach;
	
	
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
	
		echo json_encode( $output );
	}
	public function modal_process_task($action, $id, $token, $salt, $module)
	{
		try
		{
			$data = array();
			$resources      = array();
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_css']	= array(CSS_SELECTIZE,CSS_DATATABLE);
			$resources['load_js'] 	= array(JS_SELECTIZE,JS_NUMBER,JS_DATATABLE);

			$data['actions'] 	= $this->requests->get_task_actions($id);
			$data['task_info'] 	= $this->requests->view_task_details($id);
			$data['request_id'] = $data['task_info']['request_id'];
			$request_id 		= $data['task_info']['request_id'];
			$field                          = array('*');
				$where                          = array();
				$key                            = $this->get_hash_key('request_id');
				$where['request_id']            = $request_id;
				$where["request_sub_status_id"] = SUB_REQUEST_NEW;
				$table                          = $this->requests->tbl_requests_sub;
				$sub_requests_status            = $this->requests->get_general_data($field, $table, $where, FALSE);
				if(!EMPTY($sub_requests_status))
				{
					$fields                          = array() ;
					$fields['request_sub_status_id'] = SUB_REQUEST_APPROVED;
					
					$where                           = array();
					$key                             = $this->get_hash_key('request_id');
					$where['request_id']             = $request_id;
					$table                           = $this->requests->tbl_requests_sub;
					$this->requests->update_general_data($table,$fields,$where);
				}

			if($data['task_info']['request_type_id'] == REQUEST_LEAVE_APPLICATION)
			{
				$field                 = array('C.*');
				$where                 = array();
				$where["A.request_id"] = $data['task_info']['request_id'];
				$tables = array(
					'main'	=> array(
						'table'		=> $this->requests->tbl_requests_sub,
						'alias'		=> 'A',
					),
					't1'	=> array(
						'table'		=> $this->requests->tbl_requests_sub,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'A.request_id = B.request_id',
					),
					't2'	=> array(
						'table'		=> $this->requests->tbl_requests_leaves,
						'alias'		=> 'C',
						'type'		=> 'join',
						'condition'	=> 'B.request_sub_id = C.request_sub_id',
					)
				);
				$data['leave_details']   = $this->requests->get_general_data($field, $tables, $where, FALSE);
			}
			$req_details_id            = $data['task_info']['request_id'];
			$post_data = array(	
				'request_id'		=> $req_details_id,
				'request_action' 	=> $action,
				'request_module' 	=> $module
			);
			$resources['datatable'][] = array('table_id' => 'table_request_changes', 'path' => 'main/requests/get_request_changes_list', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			
			// $resources['datatable'][] = array('table_id' => 'table_request_workflow', 'path' => 'main/requests/get_request_tasks', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			$this->load->view('modals/modal_process_task', $data);
			$this->load_resources->get_resource($resources);
				
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	
	}
	public function modal_request_changes($action, $id, $token, $salt, $module)
	{
		try
		{
			$data           = array();			
			$resources      = array();

			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATATABLE);

			$resources['load_modal']  = array(
					'modal_change_details' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_change_details',
							'multiple'   => true,
							'height'     => '460px',
							'size'       => 'sm',
							'title'      => 'Request Details'
					)
			);
			 $resources['loaded_init']  = array( 
                'ModalEffects.re_init();'
            );

			 if($module == MODULE_REQUESTS_APPROVALS)
			 {
			 
				$field                          = array('*');
				$where                          = array();
				$key                            = $this->get_hash_key('request_id');
				$where[$key]                    = $id;
				$where["request_sub_status_id"] = SUB_REQUEST_NEW;
				$table                          = $this->requests->tbl_requests_sub;
				$sub_requests_status            = $this->requests->get_general_data($field, $table, $where, FALSE);
				if(!EMPTY($sub_requests_status))
				{
					$fields                          = array() ;
					$fields['request_sub_status_id'] = SUB_REQUEST_APPROVED;
					
					$where                           = array();
					$key                             = $this->get_hash_key('request_id');
					$where[$key]                     = $id;
					$table                           = $this->requests->tbl_requests_sub;
					$this->requests->update_general_data($table,$fields,$where);
				}
			 }
			//$data['sub_requests'] = $this->requests->get_sub_request_list($id);
			$post_data = array(	
								'request_id'		=> $id,
								'request_action' 	=> $action,
								'request_module' 	=> $module
				);
			$resources['datatable'][] = array('table_id' => 'table_request_changes', 'path' => 'main/requests/get_request_changes_list', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			
			$this->load->view('modals/modal_record_changes', $data);
			$this->load_resources->get_resource($resources);
	
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	
	}
	public function modal_supporting_documents($action, $id, $token, $salt, $module)
	{
		try
		{
			$data           = array();			
			$resources      = array();

			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATATABLE);

			$resources['load_modal']  = array(
					'modal_add_supporting_document' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_add_supporting_document',
							'multiple'   => true,
							'height'     => '300px',
							'size'       => 'sm',
							'title'      => 'Supporting Document'
					)
			);
			 $resources['loaded_init']  = array( 
                'ModalEffects.re_init();'
            );
			 $resources['load_delete'] 	= array(
								__CLASS__,
								'delete_supporting_document',
								PROJECT_MAIN
							);
			 if($module == MODULE_REQUESTS_APPROVALS)
			 {
			 
				$field                          = array('*');
				$where                          = array();
				$key                            = $this->get_hash_key('request_id');
				$where[$key]                    = $id;
				$where["request_sub_status_id"] = SUB_REQUEST_NEW;
				$table                          = $this->requests->tbl_requests_sub;
				$sub_requests_status            = $this->requests->get_general_data($field, $table, $where, FALSE);
				if(!EMPTY($sub_requests_status))
				{
					$fields                          = array() ;
					$fields['request_sub_status_id'] = SUB_REQUEST_APPROVED;
					
					$where                           = array();
					$key                             = $this->get_hash_key('request_id');
					$where[$key]                     = $id;
					$table                           = $this->requests->tbl_requests_sub;
					$this->requests->update_general_data($table,$fields,$where);
				}
			 }
			//$data['sub_requests'] = $this->requests->get_sub_request_list($id);
			$post_data = array(	
								'request_id'		=> $id,
								'request_action' 	=> $action,
								'request_module' 	=> $module
				);
			$resources['datatable'][] = array('table_id' => 'table_request_supporting_documents', 'path' => 'main/requests/get_supporting_documents_list', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			
			$this->load->view('modals/modal_request_supporting_documents', $data);
			$this->load_resources->get_resource($resources);
	
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	
	}
	public function modal_add_supporting_document($action, $id, $token, $salt, $module, $request_id)
	{
		try
		{
			$data           = array();			
			$resources      = array();

			$data['action']     = $action;
			$data['id']         = $id;
			$data['salt']       = $salt;
			$data['token']      = $token;
			$data['module']     = $module;
			$data['request_id'] = $request_id;

			$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATETIMEPICKER);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($request_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module . '/' . $request_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$field       = array('E.*');
			$where       = array();
			$key         = $this->get_hash_key('A.request_id');
			$where[$key] = $request_id;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->requests->tbl_requests_sub,
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> $this->requests->tbl_param_request_sub_types,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.request_sub_type_id = B.request_sub_type_id',
				),
				't2'	=> array(
					'table'		=> $this->requests->tbl_param_checklists,
					'alias'		=> 'C',
					'type'		=> 'join',
					'condition'	=> 'B.check_list_id= C.check_list_id',
				),
				't3'	=> array(
					'table'		=> $this->requests->tbl_param_checklist_docs,
					'alias'		=> 'D',
					'type'		=> 'join',
					'condition'	=> 'C.check_list_id = D.check_list_id',
				),
				't4'	=> array(
					'table'		=> $this->requests->tbl_param_supporting_document_types,
					'alias'		=> 'E',
					'type'		=> 'join',
					'condition'	=> 'E.supp_doc_type_id = D.supp_doc_type_id',
				)
			);
			$data['document_types']        = $this->requests->get_general_data($field, $tables, $where, true);
			 if($action != ACTION_ADD)
			 {
			 
				$field               = array('*');
				$where               = array();
				$key                 = $this->get_hash_key('request_supporting_doc_id');
				$where[$key]         = $id;
				$table               = $this->requests->tbl_employee_supporting_docs;
				$data['document']  = $this->requests->get_general_data($field, $table, $where, FALSE);

				$resources['single']	= array('document_type_id' => $data['document']['supp_doc_type_id']);

			 }
			
			
			$this->load->view('modals/modal_add_supporting_document', $data);
			$this->load_resources->get_resource($resources);
	
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	
	}
	public function get_request_changes_list()
	{
	
		try
		{
			$params = get_params();
			$aColumns 	= array('C.request_sub_type_name', 'D.name', 'E.request_sub_status_name', 'B.request_sub_id','B.request_sub_status_id');
			$bColumns 	= array('E.request_sub_status_name','C.request_sub_type_name', 'D.name');
		
			$tasks          = $this->requests->get_sub_request_list($aColumns, $bColumns, $params);
			if($tasks[0]['request_sub_type_name'] == "Attendance Manual Adjustment Official Business")
			{
				$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				// "iTotalRecords"        => $iTotal["cnt"],
				// "iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"iTotalRecords"        => 1,
				"iTotalDisplayRecords" => 1,
				"aaData"               => array()
				);
			}
			else
			{
				$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				// "iTotalRecords"        => $iTotal["cnt"],
				// "iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"iTotalRecords"        => count($tasks),
				"iTotalDisplayRecords" => count($tasks),
				"aaData"               => array()
				);	
			}
			
			// davcorrea : END
			$module            = $params['request_module'];
			$permission_view   = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit   = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			
	
			foreach ($tasks as $aRow)
			{
			
				// MARVIN
				if($aRow['request_sub_type_name'] == 'Attendance Manual Adjustment')
				{
					$ta_results = $this->requests->get_ta_requests($aRow['request_sub_id']);
					// $this->requests->validate_ta_raw($ta_results);
					
					if(empty($ta_results['time_log']) AND empty($ta_results['remarks']))
					{
					}
					else
					{
						$row = array();
					
						$request_sub_id = $aRow['request_sub_id'];
						$salt           = gen_salt();
						$token          = in_salt($request_sub_id  . '/' . $params['request_action'] . '/' . $module, $salt);
						$url            = $params['request_action'] ."/".$request_sub_id ."/".$token."/".$salt."/".$module;
						$token_edit     = in_salt($request_sub_id  . '/' . ACTION_EDIT . '/' . $module, $salt);

						$status_color = "";
						if($module == MODULE_PORTAL_MY_REQUESTS OR $params['request_action'] == ACTION_VIEW)
						{
							if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
							{
									$flaticon ="flaticon-verify8";
							}
							else{
									$flaticon ="flaticon-cross95";						
							}
							$row[] = "<span><i class='".$flaticon."'></i></span>";
							
						}
						else
						{
							if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
							{
									$checked =" checked ";
							}
							else{
									$checked ="";						
							}
							$row[] = "<input  type='checkbox' id='check_".$aRow['request_sub_id']."' value='".$aRow['request_sub_id']."'  class='ind_checkbox filled-in' ".$checked." onchange=\"include_sub_request('".ACTION_EDIT."','".$request_sub_id."','".$token_edit."','".$salt."','".$module."','".$aRow['request_sub_id']."',this)\"/>
										  <label for='check_".$aRow['request_sub_id']."'></label>";
						}
						
						$row[] =  $aRow['request_sub_type_name'];
						$row[] =  $aRow['name'];
						//$row[] =  "<label class='font-md p-xs white-text ".$status_color."'>".$aRow['request_sub_status_name']."</label>";

						// $action = "<div class='table-actions'>";
						
						// // if($aRow['task_status_id'] != TASK_NOT_YET_STARTED)
						// $action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-tooltip='View' data-modal='modal_change_details' data-position='bottom' data-delay='50' onclick=\"modal_change_details_init('".$url."')\"></a>";
					
						// // MARVIN
						// // $action .= "<a href='javascript:;' class='delete' title='Delete'></a>";
						
						// $action .= "</div>";
						// $row[] = $action;
						$table = array(
							'main'	=> array(
								'table'		=> $this->requests->tbl_requests_sub,
								'alias'		=> 'A',
							),
							't1'	=> array(
								'table'		=> $this->requests->tbl_requests,
								'alias'		=> 'B',
								'type'		=> 'join',
								'condition'	=> 'A.request_id = B.request_id',
							)
						);			
						$field                   = array("*") ;
						$where                   = array();
						$where['A.request_sub_id'] = $aRow['request_sub_id'];
						$sub_request             = $this->requests->get_general_data($field, $table, $where, FALSE);

						$data['request_id']      = $this->hash($sub_request['request_id']);
			
			if($sub_request)
			{
				$type = $sub_request['request_sub_type_id'];
			}
			else
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			$change_details = $this->_get_requests_sub_data($type,$aRow['request_sub_id']);
						$req_details =  "<table>
						<th>Field</th>
						<th>Value</th>";
						foreach ($change_details as $field_detail => $value_detail)
						{
							$req_details .= "<tr>
							<td>".$field_detail."</td>
							<td>".$value_detail."</td>
							</tr>";
						}
						
						  $req_details .= "</table>";
						  $row[] = $req_details;
						$output['aaData'][] = $row;
					}
				}
				elseif($aRow['request_sub_type_name'] == 'Attendance Manual Adjustment Official Business')
				{
					$ta_results = $this->requests->get_ta_requests($aRow['request_sub_id']);
					// $this->requests->validate_ta_raw($ta_results);
					if(empty($ta_results['time_log']) AND empty($ta_results['remarks']))
					{
					}
					else
					{
						$row = array();
					
						$request_sub_id = $aRow['request_sub_id'];
						$salt           = gen_salt();
						$token          = in_salt($request_sub_id  . '/' . $params['request_action'] . '/' . $module, $salt);
						$url            = $params['request_action'] ."/".$request_sub_id ."/".$token."/".$salt."/".$module;
						$token_edit     = in_salt($request_sub_id  . '/' . ACTION_EDIT . '/' . $module, $salt);

						$status_color = "";
						if($module == MODULE_PORTAL_MY_REQUESTS OR $params['request_action'] == ACTION_VIEW)
						{
							if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
							{
									$flaticon ="flaticon-verify8";
							}
							else{
									$flaticon ="flaticon-cross95";						
							}
							$row[] = "<span><i class='".$flaticon."'></i></span>";
							
						}
						else
						{
							if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
							{
									$checked =" checked ";
							}
							else{
									$checked ="";						
							}
							$row[] = "<input  type='checkbox' id='check_".$aRow['request_sub_id']."' value='".$aRow['request_sub_id']."'  class='ind_checkbox filled-in' ".$checked." onchange=\"include_sub_request('".ACTION_EDIT."','".$request_sub_id."','".$token_edit."','".$salt."','".$module."','".$aRow['request_sub_id']."',this)\"/>
										  <label for='check_".$aRow['request_sub_id']."'></label>";
						}
						
						$row[] =  $aRow['request_sub_type_name'];
						$row[] =  $aRow['name'];
						$req_details =  "<table>
							<th>Field</th>
							<th>Value</th>";
						$change_details = array();
						$change_details['attendance_date']	= $ta_results['attendance_date'];
						$change_details['remarks']	= $ta_results['remarks'];
						foreach ($change_details as $field_detail => $value_detail)
						{
							$req_details .= "<tr>
							<td>".$field_detail."</td>
							<td>".$value_detail."</td>
							</tr>";
						}
			
						$req_details .= "</table>";
						$row[] = $req_details;
						//$row[] =  $ta_results['remarks'] . "</br>Attendance Date : ". $ta_results['attendance_date'] ;
						//$row[] =  "<label class='font-md p-xs white-text ".$status_color."'>".$aRow['request_sub_status_name']."</label>";

						// $action = "<div></div>";
						// $row[] = $action;
				
						$output['aaData'][] = $row;
					}
				break;
				}
				else
				{
					$row = array();
					
					$request_sub_id = $aRow['request_sub_id'];
					$salt           = gen_salt();
					$token          = in_salt($request_sub_id  . '/' . $params['request_action'] . '/' . $module, $salt);
					$url            = $params['request_action'] ."/".$request_sub_id ."/".$token."/".$salt."/".$module;
					$token_edit     = in_salt($request_sub_id  . '/' . ACTION_EDIT . '/' . $module, $salt);

					$status_color = "";
					if($module == MODULE_PORTAL_MY_REQUESTS OR $params['request_action'] == ACTION_VIEW)
					{
						if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
						{
								$flaticon ="flaticon-verify8";
						}
						else{
								$flaticon ="flaticon-cross95";						
						}
						$row[] = "<span><i class='".$flaticon."'></i></span>";
						
					}
					else
					{
						if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
						{
								$checked =" checked ";
						}
						else{
								$checked ="";						
						}
						$row[] = "<input  type='checkbox' id='check_".$aRow['request_sub_id']."' value='".$aRow['request_sub_id']."'  class='ind_checkbox filled-in' ".$checked." onchange=\"include_sub_request('".ACTION_EDIT."','".$request_sub_id."','".$token_edit."','".$salt."','".$module."','".$aRow['request_sub_id']."',this)\"/>
									  <label for='check_".$aRow['request_sub_id']."'></label>";
					}
					
					$row[] =  $aRow['request_sub_type_name'];
					$row[] =  $aRow['name'];
					//$row[] =  "<label class='font-md p-xs white-text ".$status_color."'>".$aRow['request_sub_status_name']."</label>";

					// $action = "<div class='table-actions'>";
					
					// // if($aRow['task_status_id'] != TASK_NOT_YET_STARTED)
					// $action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-tooltip='View' data-modal='modal_change_details' data-position='bottom' data-delay='50' onclick=\"modal_change_details_init('".$url."')\"></a>";
				
					// // MARVIN
					// // $action .= "<a href='javascript:;' class='delete' title='Delete'></a>";
					
					// $action .= "</div>";
					// $row[] = $action;
					$table = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_sub,
							'alias'		=> 'A',
						),
						't1'	=> array(
							'table'		=> $this->requests->tbl_requests,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.request_id = B.request_id',
						)
					);			
					$field                   = array("*") ;
					$where                   = array();
					$where['A.request_sub_id'] = $aRow['request_sub_id'];
					$sub_request             = $this->requests->get_general_data($field, $table, $where, FALSE);

					$data['request_id']      = $this->hash($sub_request['request_id']);
		
		if($sub_request)
		{
			$type = $sub_request['request_sub_type_id'];
		}
		else
		{
			throw new Exception($this->lang->line('invalid_action'));
		}
		$change_details = $this->_get_requests_sub_data($type,$aRow['request_sub_id']);
		$pds_flag = FALSE;
		if($sub_request['request_type_id'] == REQUEST_PDS_RECORD_CHANGES)
		{
			
			$total_count = count($change_details);
			$half_count = $total_count  / 2;

			$slice_old = array_slice($change_details,$half_count);
			$slice_new = array_slice($change_details,0,$half_count);

			$change_details = array();
			$x= 0;
			foreach ($slice_new as $key => $value) {
				$x++;
				$change_details[$x]['field_name'] = $key;
				$change_details[$x]['new_value'] = $value;					
			}
			$x= 0;
			foreach ($slice_old as $value) {
				$x++;
				$change_details[$x]['old_value'] = $value;					
			}

			$pds_flag = TRUE;
		}
		if($pds_flag)
		{
			$req_details =  "<table>
			<th>Field</th>
			<th>Old Value</th>
			<th>New Value</th>";
			foreach ($change_details as $change_detail)
			{
				$req_details .= "<tr>";
				foreach($change_detail as $detail => $detail_value)
				{
					$req_details .= "<td>".$detail_value."</td>";
				}
				$req_details .= "</tr>";
			}
			$req_details .= "</table>";
			$row[] = $req_details;
		}else
		{
			$req_details =  "<table>
			<th>Field</th>
			<th>Value</th>";
			foreach ($change_details as $field_detail => $value_detail)
			{
				$req_details .= "<tr>
				<td>".$field_detail."</td>
				<td>".$value_detail."</td>
				</tr>";
			}
			
			$req_details .= "</table>";
			$row[] = $req_details;
		}
					
		$output['aaData'][] = $row;
		}

				// $row = array();
				

				// $request_sub_id = $aRow['request_sub_id'];
				// $salt           = gen_salt();
				// $token          = in_salt($request_sub_id  . '/' . $params['request_action'] . '/' . $module, $salt);
				// $url            = $params['request_action'] ."/".$request_sub_id ."/".$token."/".$salt."/".$module;
				// $token_edit     = in_salt($request_sub_id  . '/' . ACTION_EDIT . '/' . $module, $salt);

				// $status_color = "";
				// if($module == MODULE_PORTAL_MY_REQUESTS OR $params['request_action'] == ACTION_VIEW)
				// {
					// if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
					// {
							// $flaticon ="flaticon-verify8";
					// }
					// else{
							// $flaticon ="flaticon-cross95";						
					// }
					// $row[] = "<span><i class='".$flaticon."'></i></span>";
					
				// }
				// else
				// {
					// if($aRow['request_sub_status_id'] == SUB_REQUEST_APPROVED)
					// {
							// $checked =" checked ";
					// }
					// else{
							// $checked ="";						
					// }
					// $row[] = "<input  type='checkbox' id='check_".$aRow['request_sub_id']."' value='".$aRow['request_sub_id']."'  class='ind_checkbox filled-in' ".$checked." onchange=\"include_sub_request('".ACTION_EDIT."','".$request_sub_id."','".$token_edit."','".$salt."','".$module."','".$aRow['request_sub_id']."',this)\"/>
								  // <label for='check_".$aRow['request_sub_id']."'></label>";
				// }
				
				// $row[] =  $aRow['request_sub_type_name'];
				// $row[] =  $aRow['name'];
				//$row[] =  "<label class='font-md p-xs white-text ".$status_color."'>".$aRow['request_sub_status_name']."</label>";

				// $action = "<div class='table-actions'>";
				
				// if($aRow['task_status_id'] != TASK_NOT_YET_STARTED)
				// $action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-tooltip='View' data-modal='modal_change_details' data-position='bottom' data-delay='50' onclick=\"modal_change_details_init('".$url."')\"></a>";
				
				// $action .= "</div>";
				// $row[] = $action;
		
				// $output['aaData'][] = $row;
					}
	
	
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		echo json_encode( $output );
	}
	public function get_supporting_documents_list()
	{
	
		try
		{
			$params = get_params();

			$aColumns 	= array('A.request_supporting_doc_id', 'A.date_received','B.supp_doc_type_name','A.remarks');
			$bColumns 	= array('A.date_received','B.supp_doc_type_name','A.remarks');
		
			$tasks          = $this->requests->get_supporting_documents_list($aColumns, $bColumns, $params);
			$iTotal         = $this->requests->sup_doc_total_length($params['request_id']);
			$iFilteredTotal = $this->requests->sup_doc_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module            = $params['request_module'];
			$request_id        = $params['request_id'];
			$permission_view   = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit   = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			
	
			foreach ($tasks as $aRow):

				$row = array();
				

				$id         = $this->hash($aRow['request_supporting_doc_id']);
				$salt       = gen_salt();
				
				$token_view = in_salt($id  . '/' . ACTION_VIEW . '/' . $module. '/' . $request_id, $salt);
				$token_edit = in_salt($id  . '/' . ACTION_EDIT . '/' . $module. '/' . $request_id, $salt);
				$token_delete = in_salt($id  . '/' . ACTION_DELETE . '/' . $module. '/' . $request_id, $salt);
				
				$url_view   = ACTION_VIEW ."/".$id ."/".$token_view."/".$salt."/".$module. '/' . $request_id;
				$url_edit   = ACTION_EDIT ."/".$id ."/".$token_edit."/".$salt."/".$module. '/' . $request_id;
				$url_delete   = ACTION_DELETE ."/".$id ."/".$token_delete."/".$salt."/".$module. '/' . $request_id;

				
				$row[] =  $aRow['date_received'];
				$row[] =  $aRow['supp_doc_type_name'];
				$row[] =  $aRow['remarks'];

				$action = "<div class='table-actions'>";
				
				// if($aRow['task_status_id'] != TASK_NOT_YET_STARTED)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-tooltip='View' data-modal='modal_add_supporting_document' data-position='bottom' data-delay='50' onclick=\"modal_add_supporting_document_init('".$url_view."')\"></a>";
				if($module != MODULE_PORTAL_MY_REQUESTS AND $params['request_action'] != ACTION_VIEW)
				{
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-tooltip='Edit' data-modal='modal_add_supporting_document' data-position='bottom' data-delay='50' onclick=\"modal_add_supporting_document_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("document", "'.$url_delete.'")';
					// if($permission_delete)

					$action        .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				$row[] = $action;
		
				$output['aaData'][] = $row;
			endforeach;
	
	
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
	
		echo json_encode( $output );
	}
	public function modal_change_details($action, $id, $token, $salt, $module)
	{
		try
		{
			$data                  = array();
			
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE);
			
			$data['action']        = $action;
			$data['id']            = $id;
			$data['salt']          = $salt;
			$data['token']         = $token;
			$data['module']        = $module;
			$data['pds_flag']      = FALSE;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$table = array(
				'main'	=> array(
					'table'		=> $this->requests->tbl_requests_sub,
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> $this->requests->tbl_requests,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.request_id = B.request_id',
				)
			);			
			$field                   = array("*") ;
			$where                   = array();
			$where['A.request_sub_id'] = $id;
			$sub_request             = $this->requests->get_general_data($field, $table, $where, FALSE);
			
			$data['request_id']      = $this->hash($sub_request['request_id']);
			
			if($sub_request)
			{
				$type = $sub_request['request_sub_type_id'];
			}
			else
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			$data['change_details'] = $this->_get_requests_sub_data($type,$id);

			if($sub_request['request_type_id'] == REQUEST_PDS_RECORD_CHANGES)
			{
				
				$total_count = count($data['change_details']);
				$half_count = $total_count  / 2;

				$slice_old = array_slice($data['change_details'],$half_count);
				$slice_new = array_slice($data['change_details'],0,$half_count);

				$change_details = array();
				$x= 0;
				foreach ($slice_new as $key => $value) {
					$x++;
					$change_details[$x]['field_name'] = $key;
					$change_details[$x]['new_value'] = $value;					
				}
				$x= 0;
				foreach ($slice_old as $value) {
					$x++;
					$change_details[$x]['old_value'] = $value;					
				}

				$data['change_details'] = $change_details;
				$data['pds_flag'] = TRUE;
			}

			$this->load->view('modals/modal_change_details', $data);
			$this->load_resources->get_resource($resources);
				
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	
	}
	public function process_subrequest()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			Main_Model::beginTransaction();

			$fields                          = array() ;
			$fields['request_sub_status_id'] = ($params['process_action'] == "approve") ? SUB_REQUEST_APPROVED : SUB_REQUEST_REJECTED;
			
			$where                           = array();
			$where['request_sub_id']         = $id;
			$table                           = $this->requests->tbl_requests_sub;
			$this->requests->update_general_data($table,$fields,$where);

			$audit_table[]			= $this->requests->tbl_requests_sub;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_UPDATE;	
				
			$activity 				= "Sub request %s has been updated.";
			$audit_activity 		= sprintf($activity, "status");

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			
			$field                   = array("request_id") ;
			$tables                  =$this->requests->tbl_requests_sub;
			$where                   = array();
			$where['request_sub_id'] = $id;
			$sub_detail               = $this->requests->get_general_data($field, $tables, $where, FALSE);
		
			// $field                          = array("count(*) as count") ;
			// $tables                         =$this->requests->tbl_requests_sub;
			// $where                          = array();
			// $where['request_id']            = $sub_detail['request_id'];
			// $where['request_sub_status_id'] = SUB_REQUEST_APPROVED;
			// $check_sub                      = $this->requests->get_general_data($field, $tables, $where, FALSE);
			// if($check_sub['count'] < 1)
			// {
			// 	throw new Exception("Your current action is invalid. Atleast one record is required.");
			// }				
			Main_Model::commit();

			$status = true;
			$message = $this->lang->line('data_updated');
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}
	public function get_task($action,$id,$token,$salt,$module)
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			Main_Model::beginTransaction();
			
			
			$fields                  = array() ;
			$fields['assigned_to']   = $this->log_user_id;
			$fields['assigned_date'] = date("Y-m-d H:i:s");
			$fields['task_status_id'] = TASK_ONGOING;
			
			$where                   = array();
			$key                     = $this->get_hash_key("request_task_id");
			$where['request_task_id']             = $id;
			$table                   = $this->requests->tbl_requests_tasks;
			$this->requests->update_general_data($table,$fields,$where);

			$audit_table[]			= $this->requests->tbl_requests_tasks;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_UPDATE;	
				
			$audit_activity 		= "Task has been assigned.";

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();

			$status = true;
			$message = "Task has been assigned to you.";
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		// $data					= array();
		// $data['status']			= $status;
		// $data['message']		= $message;
	
		// echo json_encode($data);
	}

	public function unget_task()
	{
		try
		{
			$params = get_params();
			$id = $params['id'];
			$module = $params['module'];
			$status 		= FALSE;
			$message		= "";
			
			Main_Model::beginTransaction();
			
			
			$fields                  = array() ;
			$fields['assigned_to']   = NULL;
			$fields['assigned_date'] = NULL; 
			$fields['task_status_id'] = TASK_NOT_YET_STARTED;
			
			$where                   = array();
			$where['request_id']             = $id;
			$table                   = $this->requests->tbl_requests_tasks;
			$this->requests->update_general_data($table,$fields,$where);

			$audit_table[]			= $this->requests->tbl_requests_tasks;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_UPDATE;	
				
			$audit_activity 		= "Task has been reopened.";

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();

			$status = true;
			$message = "Task has been reopened.";
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$info 		= array(
			"flag"  => $status,
			"msg" 	=> $message,
		);
	
		echo json_encode($info);
	}
	public function process_task()
	{
		try
		{
			$data        = array();
			$status      = FALSE;
			$params      = get_params();
			$action      = $params['action'];
			$token       = $params['token'];
			$salt        = $params['salt'];
			$id          = $params['id'];
			$module      = $params['module'];
			$task_action = $params['task_action'];
			$rejected_flag = FALSE;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$this->get_task($action,$id,$token,$salt,$module);
			if(empty($task_action ))
			throw new Exception("<b>Action</b> is required.");

			Main_Model::beginTransaction();
			$field 							= array('*');
			$where							= array();
			// $key 							= $this->get_hash_key('request_task_id');
			$where['request_task_id']		= $id;
			$table 							= $this->requests->tbl_requests_tasks;
			$task_prev						= $this->requests->get_general_data($field, $table, $where, FALSE);
			$process_id 					= $task_prev["process_id"];
			$process_step_id 				= $task_prev["process_step_id"];
			$process_stage_id 				= $task_prev["process_stage_id"];

			$request_id 					= $task_prev["request_id"];

			$field                          = array('*');
			$where                          = array();
			$where["request_id"]            = $request_id;
			// $where["request_sub_status_id"] = SUB_REQUEST_NEW;
			$table                          = $this->requests->tbl_requests_sub;
			$sub_requests_status            = $this->requests->get_general_data($field, $table, $where, TRUE);
			if($task_action == '1')
			{
				$has_sub_approved = "FALSE";
				foreach($sub_requests_status as $row)
				{
					if($row['request_sub_status_id'] == SUB_REQUEST_APPROVED )
					{
						$has_sub_approved = "TRUE";
					}
				}		
				if($has_sub_approved == "FALSE")
				{
					throw new Exception("Unable to Approve Request. Please Approved Atleast One Sub Request first to proceed.");
				}

			}

			if($task_action == '2')
			{
				$fields                          = array() ;
					$fields['request_sub_status_id'] = SUB_REQUEST_REJECTED;
					
					$where                           = array();
					$where['request_id']             = $request_id;
					$table                           = $this->requests->tbl_requests_sub;
					$this->requests->update_general_data($table,$fields,$where);

			}

			$fields 						= array() ;
			$fields['task_status_id']		= TASK_DONE;
			$fields['processed_date']		= date("Y-m-d H:i:s");
			$fields['remarks']				= $params['task_remarks'];
			
			$where							= array();
			// $key 							= $this->get_hash_key('request_task_id');
			$where['request_task_id']					= $id;
			$table 							= $this->requests->tbl_requests_tasks;
			$this->requests->update_general_data($table,$fields,$where);
				

			$field 							= array('*');
			$where							= array();
			$where["process_action_id"]		= $task_action;
			$where["process_id"]			= $process_id;
			$where["process_step_id"]		= $process_step_id;
			$table 							= $this->requests->db_core.".".$this->requests->tbl_process_actions;
			$action_data					= $this->requests->get_general_data($field, $table, $where, FALSE);

			$proceeding_step_id 			= $action_data['proceeding_step'];
			$update_db_flag 				= $action_data['update_db_flag'];

			$field                          = array('*');
			$where                          = array();
			$where["A.request_id"]            = $request_id;
			$tables = array(
					'main'	=> array(
						'table'		=> $this->requests->tbl_requests,
						'alias'		=> 'A',
					),
					't1'	=> array(
						'table'		=> $this->requests->tbl_requests_sub,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.request_id = B.request_id',
					),
					't2'	=> array(
						'table'		=> $this->requests->tbl_requests_leaves,
						'alias'		=> 'C',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'B.request_sub_id = C.request_sub_id',
					)
				);
			$request_detail           		= $this->requests->get_general_data($field, $tables, $where, FALSE);

			if(is_null($request_detail ))
			{
				throw new Exception("Error Processing Request");				
			}
			if($request_detail['request_type_id'] == REQUEST_LEAVE_APPLICATION AND $request_detail['commutation_flag'] == "N")
			{

				$fields                   = array();
				$fields['no_of_days']     = $params['with_pay'];
				$fields['no_of_days_wop'] = $params['without_pay'];
				
				$where                    = array();
				$where['request_sub_id']  = $request_detail['request_sub_id'];
				$table                    = $this->requests->tbl_requests_leaves;
				$this->requests->update_general_data($table,$fields,$where);
			}
			if(!EMPTY($proceeding_step_id))
			{
				$next_task						= $this->requests->get_next_task($process_id, $proceeding_step_id);
		
				$fields 					= array() ;
				$fields['request_id']		= $request_id;
				$fields['task_detail']		= $next_task['name'];
				$fields['process_id']		= $next_task['process_id'];
				$fields['process_stage_id']	= $next_task['process_stage_id'];
				$fields['process_step_id']	= $next_task['process_step_id'];
				$fields['task_status_id']	= TASK_NOT_YET_STARTED;

				$table 						= $this->requests->tbl_requests_tasks;
				$request_task_id 			= $this->requests->insert_general_data($table,$fields,TRUE);

				 /*INSERT NOTIFICATION*/
				// $request_notifications = modules::load('main/request_notifications');
				// $request_notifications->insert_request_notification($request_id);
			}
			else
			{
				if($update_db_flag)
				{
					if($request_detail)
					{

						switch ($request_detail['request_type_id']){
							case REQUEST_DEDUCTION_RECORD_CHANGES:
								//$this->_process_deduction_record_changes();
							break;
							case REQUEST_CERTIFICATE_CONTRIBUTION:
								//$this->_process_certificate_contribution();
							break;
							case REQUEST_CERTIFICATE_EMPLOYMENT:
								//$this->_process_certificate_employment($request_id);
							break;
							case REQUEST_PAYSLIP:
								//$this->_process_payslip();
							break;
							case REQUEST_MANUAL_ADJUSTMENT:
								$this->_process_manual_adjustment($request_id);
							break;
							case REQUEST_LEAVE_APPLICATION:
								$this->_process_leave_application($request_id);
							break;
							case REQUEST_PDS_RECORD_CHANGES:
								$this->_process_pds_record_changes($request_id);
							break;
						}
					}

					/*INSERT NOTIFICATION - APPROVED*/
					// $request_notifications = modules::load('main/request_notifications');
					// $request_notifications->insert_request_final_notification($id,TRUE);
				}
				else
				{
					if($request_detail['request_type_id'] == REQUEST_LEAVE_APPLICATION)
					{
						$fields                          = array() ;
						$fields['request_sub_status_id'] = SUB_REQUEST_REJECTED;
						$fields['remarks']               = $params['task_remarks'];
						
						$where                           = array();
						$where['request_id']             =  $request_id;

						$table                           = $this->requests->tbl_requests_sub;
						$this->requests->update_general_data($table,$fields,$where);
						
						$this->_process_leave_application($request_id);
					}

					$rejected_flag = TRUE;

					/*INSERT NOTIFICATION - REJECTED*/
					// $request_notifications = modules::load('main/request_notifications');
					// $request_notifications->insert_request_final_notification($id,FALSE);
				}
				
			}
			/*START UPDATING ISSUE STATUS*/
			$field 							= array('status_id');
			$where							= array();
			$where["process_stage_id"]		= $process_stage_id;
			$where["process_id"]			= $process_id;
			$where["process_step_id"]		= $process_step_id;
			$table 							= $this->requests->db_core.".".$this->requests->tbl_process_steps;
			$request_status					= $this->requests->get_general_data($field, $table, $where, FALSE);
			
			$fields 						= array() ;
		
			if($rejected_flag == TRUE)
			{
				$fields['request_status_id'] = REQUEST_REJECTED;
				$fields['date_processed']    = date("Y-m-d H:i:s");
				$where                       = array();
				$where['request_id']         = $request_id;
				$table                       = $this->requests->tbl_requests;
				$this->requests->update_general_data($table,$fields,$where);
			}
			else
			{
				if(!empty($request_status['status_id']))
				{
					$fields['request_status_id']	= $request_status['status_id'];
					$where							= array();
					$where['request_id']			= $request_id;
					$table 							= $this->requests->tbl_requests;
					$this->requests->update_general_data($table,$fields,$where);
				}
			}		
			
			
			Main_Model::commit();
			
			$status 		= TRUE;
			$message 		= $action_data['message'];
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			RLog::error($message);
			$message = $this->lang->line('err_internal_server');
			Main_Model::rollback();
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			Main_Model::rollback();
		}

		$data				= array();
		$data['status']		= $status;
		$data['message']	= $message;

		echo json_encode($data);
	}
	public function _process_pds_record_changes($request_id)
	{
		try
		{
			
							
			$field                          = array('*');
			$where                          = array();
			$where["request_id"]            = $request_id;
			//$where["request_sub_status_id"] = SUB_REQUEST_NEW;
			$table                          = $this->requests->tbl_requests_sub;
			$sub_requests 			        = $this->requests->get_general_data($field, $table, $where, TRUE);
		
			if($sub_requests)
			{
				foreach($sub_requests as $sub)
				{
					switch ($sub['request_sub_type_id']){
					case TYPE_REQUEST_PDS_PERSONAL_INFO:
						$request_table = $this->requests->tbl_requests_employee_personal_info;
						$pds_table     = $this->requests->tbl_employee_personal_info;
						$id_field      = "employee_id";
					break;
					case TYPE_REQUEST_PDS_IDENTIFICATION:
						$request_table = $this->requests->tbl_requests_employee_identifications;
						$pds_table     = $this->requests->tbl_employee_identifications;
						$id_field      = "employee_identification_id";
					break;
					case TYPE_REQUEST_PDS_ADDRESS_INFO:
						$request_table = $this->requests->tbl_requests_employee_addresses;
						$pds_table     = $this->requests->tbl_employee_addresses;
						$id_field      = "employee_address_id";
					break;
					case TYPE_REQUEST_PDS_CONTACT_INFO:
						$request_table = $this->requests->tbl_requests_employee_contacts;
						$pds_table     = $this->requests->tbl_employee_contacts;
						$id_field      = "employee_contact_id";
					break;
					case TYPE_REQUEST_PDS_FAMILY_INFO:
						$request_table = $this->requests->tbl_requests_employee_relations;
						$pds_table     = $this->requests->tbl_employee_relations;
						$id_field      = "employee_relation_id";
					break;
					case TYPE_REQUEST_PDS_EDUCATION:
						$request_table = $this->requests->tbl_requests_employee_educations;
						$pds_table     = $this->requests->tbl_employee_educations;
						$id_field      = "employee_education_id";
					break;
					case TYPE_REQUEST_PDS_ELIGIBILITY:
						$request_table = $this->requests->tbl_requests_employee_eligibility;
						$pds_table     = $this->requests->tbl_employee_eligibility;
						$id_field      = "employee_eligibility_id";
					break;
					case TYPE_REQUEST_PDS_WORK_EXPERIENCE:
						$request_table = $this->requests->tbl_requests_employee_work_experiences;
						$pds_table     = $this->requests->tbl_employee_work_experiences;
						$id_field      = "employee_work_experience_id";
					break;
					case TYPE_REQUEST_PDS_VOLUNTARY_WORK:
						$request_table = $this->requests->tbl_requests_employee_voluntary_works;
						$pds_table     = $this->requests->tbl_employee_voluntary_works;
						$id_field      = "employee_voluntary_work_id";
					break;
					case TYPE_REQUEST_PDS_TRAININGS:
						$request_table = $this->requests->tbl_requests_employee_trainings;
						$pds_table     = $this->requests->tbl_employee_trainings;
						$id_field      = "employee_training_id";
					break;
					case TYPE_REQUEST_PDS_OTHER_INFO:
						$request_table = $this->requests->tbl_requests_employee_other_info;
						$pds_table     = $this->requests->tbl_employee_other_info;
						$id_field      = "employee_other_info_id";
					break;
					case TYPE_REQUEST_PDS_QUESTIONNAIRE:

						if($sub['request_sub_status_id'] == SUB_REQUEST_APPROVED)
						{
							
							$request_table = $this->requests->tbl_requests_employee_questions;
							$pds_table     = $this->requests->tbl_employee_questions;

							$where                          = array();
							$where["request_sub_id"] 		= $sub['request_sub_id'];
							$questions 			        	= $this->requests->get_general_data(array('*'), $request_table, $where, TRUE);

							$where							= array();
							$where['employee_id']			= $sub['employee_id'];
							$this->requests->delete_general_data($pds_table,$where);
				
							foreach($questions as $question)
							{
								$slice_new = array_slice($question,1,4);
								$this->requests->insert_general_data($pds_table,$slice_new,TRUE);
							}
						}
					break;
					case TYPE_REQUEST_PDS_REFERENCES:
						$request_table = $this->requests->tbl_requests_employee_references;
						$pds_table     = $this->requests->tbl_employee_references;
						$id_field      = "employee_reference_id";
					break;
					case TYPE_REQUEST_PDS_DECLARATION:
						$request_table = $this->requests->tbl_requests_employee_declaration;
						$pds_table     = $this->requests->tbl_employee_declaration;
						$id_field      = "employee_id";
					break;
					}
					if($sub['request_sub_status_id'] == SUB_REQUEST_APPROVED)
					{
						if($sub['request_sub_type_id'] != TYPE_REQUEST_PDS_QUESTIONNAIRE)
						$this->_update_pds($request_table, $pds_table, $sub, $id_field);

					}
				}
			}
			return true;
				
		}
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function _update_pds($request_table = null,$pds_table = null,$sub_details = array(),$field_name)
	{
		try
		{
			$field                          = array('*');
			$where                          = array();
			$where["request_sub_id"] 		= $sub_details['request_sub_id'];
			$sub_requests 			        = $this->requests->get_general_data($field, $request_table, $where, FALSE);

			if($sub_details['action'] == ACTION_ADD)
			{
				
				$total_count = count($sub_requests);
				$half_count = ($total_count - 3) / 2;

				$slice_new = array_slice($sub_requests,2,$half_count + 1);

				$this->requests->insert_general_data($pds_table,$slice_new,TRUE);
			}
			else if($sub_details['action'] == ACTION_EDIT)
			{				
				$not_included = ($sub_details['request_sub_type_id'] == TYPE_REQUEST_PDS_PERSONAL_INFO) ? 2 : 3;
				$where							= array();
				$where[$field_name]				= $sub_requests[$field_name];

				$total_count = count($sub_requests);
				$half_count = ($total_count - $not_included) / 2;

				$start_slice = $not_included;
				$slice_new = array_slice($sub_requests,$start_slice,$half_count);

				$this->requests->update_general_data($pds_table,$slice_new,$where);
			}
			else if($sub_details['action'] == ACTION_DELETE)
			{
				$where							= array();
				$where[$field_name]				= $sub_requests[$field_name];

				$this->requests->delete_general_data($pds_table,$where);
			}

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($sub_requests['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
				
		}
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function _process_leave_application($request_id)
	{
		try
		{			
							
			$field               = array('*');
			$where               = array();
			$where["A.request_id"] = $request_id;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->requests->tbl_requests_sub,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->requests->tbl_requests_leaves,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.request_sub_id = B.request_sub_id',
				),
				't3'	=> array(
					'table'		=> $this->requests->tbl_param_leave_types,
					'alias'		=> 'C',
					'type'		=> 'join',
					'condition'	=> 'B.leave_type_id = C.leave_type_id',
				)
			);
			$leave_details        = $this->requests->get_general_data($field, $tables, $where, false);
			if(isset($leave_details))
			{
				$field                = array("*") ;
				$table                = $this->requests->tbl_employee_leave_balances;
				$where                = array();
				$where["employee_id"] = $leave_details['employee_id'];
				$leave_balances       = $this->requests->get_general_data($field, $table, $where, TRUE);
				if($leave_balances)
				{
					foreach ($leave_balances as $value) {
						if($value['leave_type_id'] == LEAVE_TYPE_SICK)
						{
							$sick_balance = $value['leave_balance'];
						}
						if($value['leave_type_id'] == LEAVE_TYPE_VACATION)
						{
							$vac_balance = $value['leave_balance'];
						}
					}

					$leave_last_date      = $this->requests->get_leave_last_trans_date($leave_details['employee_id']);


					$fields                     = array();
					if($sick_balance)
					$fields['sick_balance']     = $sick_balance;
					if($vac_balance)
					$fields['vacation_balance'] = $vac_balance;
					$fields['date_processed']   = isset($leave_last_date['last_trans_date']) ? $leave_last_date['last_trans_date'] : date('Y-m-d');
					
					$table                      = $this->requests->tbl_requests_leaves;
					$where                      = array();
					$where["request_sub_id"]    = $leave_details['request_sub_id'];
					$this->requests->update_general_data($table,$fields,$where);
				}
			}

			if(isset($leave_details) AND $leave_details['request_sub_status_id'] == SUB_REQUEST_APPROVED)
			{

				
				$leave_type = (!EMPTY($leave_details['deduct_bal_leave_type_id'])) ? $leave_details['deduct_bal_leave_type_id'] : $leave_details['leave_type_id'];

				$field                  = array('*');
				$where                  = array();
				$where["employee_id"]   = $leave_details['employee_id'];
				$where["leave_type_id"] = $leave_type;
				$tables                 = $this->requests->tbl_employee_leave_balances;
				$check_balance          = $this->requests->get_general_data($field, $tables, $where, false);

		

				/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
				if($check_balance['leave_balance'] >= $leave_details['no_of_days'])
				{
					/*
					IF ENOUGH BALANCE:
						SET NORMAL LEAVE WITH OR WITH OUT PAY
					*/
					$new_leave_balance = $check_balance['leave_balance'] - $leave_details['no_of_days'];
					$leave_with_pay    = $leave_details['no_of_days'];
					$leave_without_pay = $leave_details['no_of_days_wop'];
				}
				else
				{
					/*
					IF NOT ENOUGH BALANCE:
						-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
						-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
					*/
					if($leave_type == LEAVE_TYPE_SICK)	
					{
						$field                  = array('*');
						$where                  = array();
						$where["employee_id"]   = $leave_details['employee_id'];
						$where["leave_type_id"] = LEAVE_TYPE_VACATION;
						$tables                 = $this->requests->tbl_employee_leave_balances;
						$check_vl_balance       = $this->requests->get_general_data($field, $tables, $where, false);
					}

					if($leave_type == LEAVE_TYPE_SICK AND $check_vl_balance['leave_balance'] > 0)	
					{

						/*
							if LEAVE TYPE IS SICK LEAVE AND BALANCE IS NOT ENOUGH :
							-AUTO DEDUCT REMAINING LWOP TO VACATION LEAVE

							$leave_with_pay    = $check_balance['leave_balance'];
						$leave_without_pay = ($leave_details['no_of_days'] - $check_balance['leave_balance']) + $leave_details['no_of_days_wop'];
						*/	
						/*
						davcorrea 10/02/2023
						adjusted leave deduction computation
						
						*/
						// =================================START========================
						$leave_with_pay    = floor($check_balance['leave_balance']);
						$bal = $check_balance['leave_balance'] - $leave_with_pay;
						if($bal > 0.5)
						{
							$leave_with_pay = $leave_with_pay + 0.5;
						}
						$leave_without_pay = ($leave_details['no_of_days'] - $leave_with_pay) + $leave_details['no_of_days_wop'];
						// ================================END==============================
						if($check_vl_balance['leave_balance'] >= $leave_without_pay)
						{
							// $new_leave_balance = 0;
							// davdavcorrea 10/02/2023
							// adjusted leave deduction computation
							// ====================START=================
							$new_leave_balance = $check_balance['leave_balance'] -  $leave_with_pay;
							// =====================END===================
							$vl_new_balance    = $check_vl_balance['leave_balance'] - $leave_without_pay;
							$vl_leave_with_pay = $leave_without_pay;

							$leave_without_pay = 0;

						}
						else
						{

							$vl_net_balance = $check_vl_balance['leave_balance'] % 1;
							if($net_balance > 0.5)
							{
								$vl_new_balance = $vl_net_balance - 0.5;
								$check_vl_balance['leave_balance'] = floor($check_vl_balance['leave_balance']) + 0.5;
							}
							else
							{
								$vl_new_balance = $vl_net_balance;
								$check_vl_balance['leave_balance'] = floor($check_vl_balance['leave_balance']);
							}
							$leave_without_pay = ($leave_without_pay - $check_vl_balance['leave_balance']) + $leave_details['no_of_days_wop'];
							$vl_leave_with_pay = $check_vl_balance['leave_balance'];
						}
						
						$table                   = $this->requests->tbl_employee_leave_balances;
						$fields                  = array();
						$fields['leave_balance'] = $vl_new_balance;					
						
						$where                   = array();
						$where['employee_id']    = $leave_details['employee_id'];
						$where['leave_type_id']  = LEAVE_TYPE_VACATION;
						$this->requests->update_general_data($table,$fields,$where);

						$fields                              = array();
						$fields['employee_id']               = $leave_details['employee_id'];
						$fields['leave_type_id']             = LEAVE_TYPE_VACATION;
						$fields['orig_leave_type_id']        = NULL;
						$fields['effective_date']            = date('Y-m-d');
						$fields['leave_transaction_date']    = date('Y-m-d');
						$fields['leave_earned_used']         = $vl_leave_with_pay;
						$fields['leave_wop']                 = 0;
						$fields['leave_transaction_type_id'] = LEAVE_FILE_LEAVE;
						// Added remarks SL Charged to VL  davcorrea 10/02/2023 
						//===================START===========================
						$fields['remarks']                   = "SL Charged to VL";
						//  ==========END=======================					

						if($leave_details['request_sub_type_id'] == TYPE_REQUEST_LA_COMMUTATION_REQUESTED)
						{						
							$fields['commutation_flag'] = YES;
						}
						elseif($leave_details['request_sub_type_id'] == TYPE_REQUEST_LA_MONETIZATION)
						{						
							$fields['monetize_flag'] = YES;
						}
						else
						{
							$fields['leave_start_date'] = $leave_details['date_from'];
							$fields['leave_end_date']   = $leave_details['date_to'];
						}
						$table  = $this->requests->tbl_employee_leave_details;
						$this->requests->insert_general_data($table,$fields,TRUE);
							
						
					}
					else
					{

						$net_balance = $check_balance['leave_balance'] % 1;
						if($net_balance > 0.5)
						{
							$new_leave_balance = $net_balance - 0.5;
							$check_balance['leave_balance'] = floor($check_balance['leave_balance']) + 0.5;
						}
						else
						{
							$new_leave_balance = $net_balance;
							$check_balance['leave_balance'] = floor($check_balance['leave_balance']);
						}
						
						$leave_with_pay    = $check_balance['leave_balance'];
						$leave_without_pay = ($leave_details['no_of_days'] - $check_balance['leave_balance']) + $leave_details['no_of_days_wop'];
					}
					
				}
				
				
				$table                   = $this->requests->tbl_employee_leave_balances;
				$fields                  = array();
				$fields['leave_balance'] = $new_leave_balance;					
				
				$where                   = array();
				$where['employee_id']    = $leave_details['employee_id'];
				$where['leave_type_id']  = $leave_type;
				$this->requests->update_general_data($table,$fields,$where);


				if($leave_details['leave_type_id'] == LEAVE_TYPE_FORCED)
				{
					$field                   = array('*');
					$where                   = array();
					$where["employee_id"]    = $leave_details['employee_id'];
					$where["leave_type_id"]  = LEAVE_TYPE_FORCED;
					$tables                  = $this->requests->tbl_employee_leave_balances;
					$_check_balance          = $this->requests->get_general_data($field, $tables, $where, false);
					$_new_leave_balance		 = $_check_balance['leave_balance'] - $leave_details['no_of_days'];

					$table                   = $this->requests->tbl_employee_leave_balances;
					$fields                  = array();
					$fields['leave_balance'] = $_new_leave_balance;					
					
					$where                   = array();
					$where['employee_id']    = $leave_details['employee_id'];
					$where['leave_type_id']  =  LEAVE_TYPE_FORCED;
					$this->requests->update_general_data($table,$fields,$where);
										
				}
				$fields                              = array();
				$fields['employee_id']               = $leave_details['employee_id'];
				$fields['leave_type_id']             = $leave_details['leave_type_id'];
				$fields['orig_leave_type_id']        = (!EMPTY($leave_details['deduct_bal_leave_type_id'])) ? $leave_details['leave_type_id'] : NULL;
				$fields['effective_date']           = date('Y-m-d');
				$fields['leave_transaction_date']    = date('Y-m-d');
				$fields['leave_earned_used']         = $leave_with_pay;
				$fields['leave_wop']                 = $leave_without_pay;
				$fields['leave_transaction_type_id'] = LEAVE_FILE_LEAVE;		
				
				/* marvin : start : include study_type_id */
				if($leave_details['leave_type_id'] == LEAVE_TYPE_STUDY)
				{
					$fields['study_type_id'] = $leave_details['study_type_id'];					
				}
				/* marvin : end : include study_type_id */

				if($leave_details['request_sub_type_id'] == TYPE_REQUEST_LA_COMMUTATION_REQUESTED)
				{						
					$fields['commutation_flag'] = YES;
				}
				elseif($leave_details['request_sub_type_id'] == TYPE_REQUEST_LA_MONETIZATION)
				{						
					$fields['monetize_flag'] = YES;
				}
				else
				{
					$fields['leave_start_date'] = $leave_details['date_from'];
					$fields['leave_end_date']   = $leave_details['date_to'];
				}
				$table  = $this->requests->tbl_employee_leave_details;
				$this->requests->insert_general_data($table,$fields,TRUE);
				/*if(!EMPTY($leave_details['deduct_bal_leave_type_id']))
				{

					$fields['leave_type_id'] = $leave_details['deduct_bal_leave_type_id'];
					$table                   = $this->requests->tbl_employee_leave_details;
					$this->requests->insert_general_data($table,$fields,TRUE);
				}*/

			}
			else
			{
				if($leave_details['leave_type_id'] == LEAVE_TYPE_FORCED)
				{
					$fields                           = array();
					$fields['employee_id']            = $leave_details['employee_id'];
					$fields['leave_type_id']          = $leave_details['leave_type_id'];
					$fields['leave_transaction_date'] = date('Y-m-d');
					$fields['no_of_days']             = (isset($leave_details['no_of_days']) ? $leave_details['no_of_days'] : 0)  + (isset($leave_details['no_of_days_wop']) ? $leave_details['no_of_days_wop'] : 0);
					$fields['leave_start_date']       = $leave_details['date_from'];
					$fields['leave_end_date']         = $leave_details['date_to'];
					$fields['remarks']                = isset($leave_details['remarks']) ? $leave_details['remarks'] : '';
					
					$table = $this->requests->tbl_rejected_forced_leave;
					$this->requests->insert_general_data($table,$fields,TRUE);
				}
			}
			return true;
				
		}
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function _process_manual_adjustment($request_id)
	{
		try
		{			
							
			$field                          = array("*");			
			$tables = array(
				'main'	=> array(
					'table'		=> $this->requests->tbl_requests_sub,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->requests->tbl_requests_employee_attendance,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.request_sub_id = B.request_sub_id',
				)
			);
			$where                            = array();
			$where["A.request_id"]            = $request_id;
			$where["A.request_sub_status_id"] = SUB_REQUEST_APPROVED;
			
			$sub_requests                     = $this->requests->get_general_data($field, $tables, $where, true);
			$affected_dates = array();
			if($sub_requests)
			{
				foreach ($sub_requests as $key => $time_log) {

					$affected_dates[] = $time_log["attendance_date"];
					$employee_id      = $time_log["employee_id"];

					$table                    = $this->requests->tbl_employee_attendance;
					$where                    = array();
					$where["employee_id"]     = $time_log["employee_id"];
					$where["attendance_date"] = $time_log["attendance_date"];
					$where["time_flag"]       = $time_log["time_flag"];
					$prev_att                 = $this->requests->get_general_data(array('*'), $table, $where, false);
					if($prev_att)
					{
						$fields                   = array();
						if($time_log['request_sub_type_id'] == TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS)
						{
							$fields['attendance_status_id']     = OFFICIAL_BUSINESS;
						}
						$fields['time_log']       = $time_log['time_log'];
						$fields['remarks']        = $time_log['remarks'];
						$fields['edited_flag']    = YES;
						
						$where                    = array();
						$where["employee_id"]     = $time_log["employee_id"];
						$where["attendance_date"] = $time_log["attendance_date"];
						$where["time_flag"]       = $time_log["time_flag"];
						$this->requests->update_general_data($table,$fields,$where);
					}
					else
					{
						$fields                    = array();
						if($time_log['request_sub_type_id'] == TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS)
						{
							$fields['attendance_status_id']     = OFFICIAL_BUSINESS;
						}
						$fields['employee_id']     = $time_log["employee_id"];
						$fields['attendance_date'] = $time_log["attendance_date"];
						$fields['time_flag']       =  $time_log["time_flag"];
						$fields['time_log']        = $time_log['time_log'];
						$fields['source_flag']     = 'R';
						$fields['edited_flag']     = YES;
						$fields['remarks']         = $time_log['remarks'];
						$this->requests->insert_general_data($table,$fields,FALSE);
					}
				}
				
				$this->_update_attendance_period_dtl($employee_id,$affected_dates,NULL);
				
			}
			return true;
				
		}
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	private function _update_attendance_period_dtl($employee_id,$affected_dates,$affected_ids)
	{
		try
		{	

			$time_logs             = $this->dtr->get_affected_attendance_time_logs($employee_id,$affected_dates,$affected_ids);
			$new_time_logs         = array();
			if($time_logs)
			{
				$late_undertime = modules::load('main/attendance_late_undertime');
				
				foreach ($time_logs as $time_log) {
					$result              = $late_undertime->check_late_undertime($time_log);
					$fields                  = array();
					$fields['tardiness']     = ($result['tardiness'] > 0) ? $result['tardiness'] : 0;
					$fields['tardiness_hr']  = ($result['tardiness_hr'] > 0) ? $result['tardiness_hr'] : 0;
					$fields['tardiness_min'] = ($result['tardiness_min'] > 0) ? $result['tardiness_min'] : 0;
					$fields['undertime']     = ($result['undertime'] > 0) ? $result['undertime'] : 0;
					$fields['undertime_hr']  = ($result['undertime_hr'] > 0) ? $result['undertime_hr'] : 0;
					$fields['undertime_min'] = ($result['undertime_min'] > 0) ? $result['undertime_min'] : 0;
					$fields['working_hours'] = ($result['working_hours'] > 0) ? $result['working_hours'] : 0;
					$fields['remarks']       = $time_log['remarks'];

					$table                    = $this->dtr->tbl_attendance_period_dtl;
					$where                    = array();
					$where['employee_id']     = $time_log['employee_id'];
					$where['attendance_date'] = $time_log['attendance_date'];
					$this->dtr->update_general_data($table,$fields,$where);
				}
			}
			
			return true;		
		}
		catch(Exception $e)
		{
			throw $e;			
			$message = $e->getMessage();
			RLog::error($message);
		}
	}

	
	private function _get_requests_sub_data($type,$request_sub_id)
	{
		try
		{
			
			$data = array();

			switch ($type){
				case TYPE_REQUEST_DRC_BIR:
				case TYPE_REQUEST_DRC_PHILHEALTH:
				case TYPE_REQUEST_DRC_PAGIBIG:
				case TYPE_REQUEST_DRC_GSIS:
				case TYPE_REQUEST_COC_PHILHEALTH:
				case TYPE_REQUEST_COC_PAGIBIG:
				case TYPE_REQUEST_COC_GSIS:
				case TYPE_REQUEST_COC_TAX_WITH_HELD:
				case TYPE_REQUEST_COE_WITH_BENEFITS_LIST:
				case TYPE_REQUEST_COE_WITHOUT_BENEFITS_LIST:
				case TYPE_REQUEST_COE_SERVICE_RECORD:
					$field                          = array("B.request_sub_type_name as certificate_type","A.specific_details");			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_certifications,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_request_sub_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.certfication_type_id = B.request_sub_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);

				break;
				case TYPE_REQUEST_MANUAL_ADJUSTMENT:
				
					$field                     = array("DATE_FORMAT(attendance_date,'%M %d, %Y') as attendance_date","DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p') as time_log","time_flag","remarks");			
					$tables                    =$this->requests->tbl_requests_employee_attendance;
					$where                     = array();
					$where["request_sub_id"] = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_LA_COMMUTATION_REQUESTED:
				case TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED:
				case TYPE_REQUEST_LA_MONETIZATION:
					$field                          = array("B.leave_type_name as leave_type","A.no_of_days","DATE_FORMAT(date_from,'%M %d, %Y') as date_from","DATE_FORMAT(date_to,'%M %d, %Y') as date_to","A.reason_text as reason");			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_leaves,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_leave_types,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.leave_type_id = B.leave_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);

				break;
				case TYPE_REQUEST_PDS_PERSONAL_INFO:
					$field                          = array(
											"A.last_name",
											"A.first_name",
											"A.middle_name",
											"A.ext_name",
											"DATE_FORMAT(birth_date,'%M %d, %Y') as birth_date",
											"A.gender_code as gender",
											"B.civil_status_name as civil_status",
											"C.citizenship_name as citizenship",
											"CASE 
												WHEN A.citizenship_basis_id = '".CITIZENSHIP_BASIS_BIRTH."' THEN 'By Birth'
												WHEN A.citizenship_basis_id = '".CITIZENSHIP_BASIS_NATURALIZATION."' THEN 'By Naturalization'
											END citizenship_basis",
											"A.height",
											"A.weight",
											"D.blood_type_name as blood_type",
											"A.agency_employee_id as employee_no",
											"A.biometric_pin",
											"A.last_name_old",
											"A.first_name_old",
											"A.middle_name_old",
											"A.ext_name_old",
											"DATE_FORMAT(birth_date_old,'%M %d, %Y') as birth_date_old",
											"A.gender_code_old as gender_old",
											"E.civil_status_name as civil_status_old",
											"F.citizenship_name as citizenship_old",
											"CASE 
												WHEN A.citizenship_basis_id_old = '".CITIZENSHIP_BASIS_BIRTH."' THEN 'By Birth'
												WHEN A.citizenship_basis_id_old = '".CITIZENSHIP_BASIS_NATURALIZATION."' THEN 'By Naturalization'
											END citizenship_basis_old",
											"A.height_old",
											"A.weight_old",
											"G.blood_type_name as blood_type_old",
											"A.agency_employee_id as employee_no_old",
											"A.biometric_pin_old"											
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_personal_info,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_civil_status,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.civil_status_id = B.civil_status_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_citizenships,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.citizenship_id = C.citizenship_id',
						),
						't4'	=> array(
							'table'		=> $this->requests->tbl_param_blood_type,
							'alias'		=> 'D',
							'type'		=> 'left join',
							'condition'	=> 'A.blood_type_id = D.blood_type_id',
						),
						't5'	=> array(
							'table'		=> $this->requests->tbl_param_civil_status,
							'alias'		=> 'E',
							'type'		=> 'left join',
							'condition'	=> 'A.civil_status_id_old = E.civil_status_id',
						),
						't6'	=> array(
							'table'		=> $this->requests->tbl_param_citizenships,
							'alias'		=> 'F',
							'type'		=> 'left join',
							'condition'	=> 'A.citizenship_id_old = F.citizenship_id',
						),
						't7'	=> array(
							'table'		=> $this->requests->tbl_param_blood_type,
							'alias'		=> 'G',
							'type'		=> 'left join',
							'condition'	=> 'A.blood_type_id_old = G.blood_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_IDENTIFICATION:
					$field     = array(
											"B.identification_type_name as identification_type",
											"A.identification_value as id_number",
											"C.identification_type_name as identification_type_old",
											"A.identification_value_old as id_number_old"										
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_identifications,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_identification_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.identification_type_id = B.identification_type_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_identification_types,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.identification_type_id_old = C.identification_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_ADDRESS_INFO:
					$field     = array(
											"B.address_type_name as address_type",
											"A.address_value as street",
											"UPPER(concat_ws(', ', D.barangay_name, E.municity_name, F.province_name, G.region_name))  as address",
											"C.address_type_name as address_type_old",
											"A.address_value_old as street_old",
											"UPPER(concat_ws(', ', J.barangay_name, K.municity_name, L.province_name, M.region_name))  as address_old"									
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_addresses,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_address_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.address_type_id = B.address_type_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_address_types,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.address_type_id_old = C.address_type_id',
						),
						't4'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_barangays,
							'alias'		=> 'D',
							'type'		=> 'left join',
							'condition'	=> 'A.barangay_code = D.barangay_code',
						),
						't5'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_municities,
							'alias'		=> 'E',
							'type'		=> 'left join',
							'condition'	=> 'A.municity_code = E.municity_code',
						),
						't6'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_provinces,
							'alias'		=> 'F',
							'type'		=> 'left join',
							'condition'	=> 'A.province_code = F.province_code',
						),
						't7'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_regions,
							'alias'		=> 'G',
							'type'		=> 'left join',
							'condition'	=> 'A.region_code = G.region_code',
						),
						't8'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_barangays,
							'alias'		=> 'J',
							'type'		=> 'left join',
							'condition'	=> 'A.barangay_code_old = J.barangay_code',
						),
						't9'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_municities,
							'alias'		=> 'K',
							'type'		=> 'left join',
							'condition'	=> 'A.municity_code_old = K.municity_code',
						),
						't10'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_provinces,
							'alias'		=> 'L',
							'type'		=> 'left join',
							'condition'	=> 'A.province_code_old = L.province_code',
						),
						't11'	=> array(
							'table'		=> $this->requests->db_core.'.'.$this->requests->tbl_param_regions,
							'alias'		=> 'M',
							'type'		=> 'left join',
							'condition'	=> 'A.region_code_old = M.region_code',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_CONTACT_INFO:
					$field     = array(
											"B.contact_type_name as contact_type",
											"A.contact_value as contact",
											"C.contact_type_name as contact_type_old",
											"A.contact_value_old as contact_old"										
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_contacts,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_contact_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.contact_type_id = B.contact_type_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_contact_types,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.contact_type_id_old = C.contact_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_FAMILY_INFO:
					$field     = array(
											"B.relation_type_name as relation_type",
											"A.relation_first_name as first_name",
											"A.relation_middle_name as middle_name",
											"A.relation_last_name as last_name",
											"A.relation_ext_name as ext_name",
											"A.relation_gender_code as gender",
											"A.relation_occupation as occupation",
											"A.relation_company as company",
											"A.relation_contact_num as contact_no",
											"DATE_FORMAT(A.relation_birth_date,'%M %d, %Y') as birth_date",
											"C.civil_status_name as civil_status",
											"D.relation_employment_status_name as employment_status",
											"E.relation_type_name as relation_type_old",
											"A.relation_first_name as first_name_old",
											"A.relation_middle_name as middle_name_old",
											"A.relation_last_name_old as last_name_old",
											"A.relation_ext_name_old as ext_name_old",
											"A.relation_gender_code_old as gender_old",
											"A.relation_occupation_old as occupation_old",
											"A.relation_company_old as company_old",
											"A.relation_contact_num_old as contact_no_old",
											"DATE_FORMAT(A.relation_birth_date_old,'%M %d, %Y') as birth_date_old",
											"F.civil_status_name as civil_status_old",
											"G.relation_employment_status_name as employment_status_old"						
									);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_relations,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_relation_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.relation_type_id = B.relation_type_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_civil_status,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.relation_civil_status_id = C.civil_status_id',
						),
						't4'	=> array(
							'table'		=> $this->requests->tbl_param_relation_employment_status,
							'alias'		=> 'D',
							'type'		=> 'left join',
							'condition'	=> 'A.relation_employment_status_id = D.relation_employment_status_id',
						),
						't5'	=> array(
							'table'		=> $this->requests->tbl_param_relation_types,
							'alias'		=> 'E',
							'type'		=> 'left join',
							'condition'	=> 'A.relation_type_id_old = E.relation_type_id',
						),
						't6'	=> array(
							'table'		=> $this->requests->tbl_param_civil_status,
							'alias'		=> 'F',
							'type'		=> 'left join',
							'condition'	=> 'A.relation_civil_status_id_old = F.civil_status_id',
						),
						't7'	=> array(
							'table'		=> $this->requests->tbl_param_relation_employment_status,
							'alias'		=> 'G',
							'type'		=> 'left join',
							'condition'	=> 'A.relation_employment_status_id_old = G.relation_employment_status_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_EDUCATION:
					$field     = array(
											"B.educ_level_name as educational_level",
											"C.school_name as school_name",
											"D.degree_name as degree",
											"A.highest_level",
											"A.academic_honor",
											"A.start_year",
											"A.end_year",
											"A.year_graduated_flag",
											"A.relevance_flag",
											"E.educ_level_name as educational_level_old",
											"F.school_name as school_name_old",
											"G.degree_name as degree_old",
											"A.highest_level_old",
											"A.academic_honor_old",
											"A.start_year_old",
											"A.end_year_old",
											"A.year_graduated_flag_old",
											"A.relevance_flag_old",				
									);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_educations,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_educational_levels,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.educational_level_id = B.educ_level_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_schools,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.school_id = C.school_id',
						),
						't4'	=> array(
							'table'		=> $this->requests->tbl_param_education_degrees,
							'alias'		=> 'D',
							'type'		=> 'left join',
							'condition'	=> 'A.education_degree_id = D.degree_id',
						),
						't5'	=> array(
							'table'		=> $this->requests->tbl_param_educational_levels,
							'alias'		=> 'E',
							'type'		=> 'left join',
							'condition'	=> 'A.educational_level_id_old = E.educ_level_id',
						),
						't6'	=> array(
							'table'		=> $this->requests->tbl_param_schools,
							'alias'		=> 'F',
							'type'		=> 'left join',
							'condition'	=> 'A.school_id_old = F.school_id',
						),
						't7'	=> array(
							'table'		=> $this->requests->tbl_param_education_degrees,
							'alias'		=> 'G',
							'type'		=> 'left join',
							'condition'	=> 'A.education_degree_id_old = G.degree_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_ELIGIBILITY:
					$field     = array(
											"B.eligibility_type_name as eligibility_type",
											"A.rating",
											"DATE_FORMAT(A.exam_date,'%M %d, %Y') as exam_date",
											"A.exam_place",
											"A.license_no",
											"DATE_FORMAT(A.release_date,'%M %d, %Y') as validity_date",
											"A.relevance_flag as relevance_flag",
											"C.eligibility_type_name as eligibility_type_old",
											"A.rating_old",
											"DATE_FORMAT(A.exam_date_old,'%M %d, %Y') as exam_date_old",
											"A.exam_place_old",
											"A.license_no_old",
											"DATE_FORMAT(A.release_date_old,'%M %d, %Y') as validity_date_old",
											"A.relevance_flag_old as relevance_flag_old"
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_eligibility,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_eligibility_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.eligibility_type_id = B.eligibility_type_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_eligibility_types,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.eligibility_type_id_old = C.eligibility_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_WORK_EXPERIENCE:
					$field     = array(
											"DATE_FORMAT(A.employ_start_date,'%M %d, %Y') as start_date",
											"DATE_FORMAT(A.employ_end_date,'%M %d, %Y') as end_date",
											"A.employ_position_name as position",
											"A.employ_office_name as company_name",
											"A.employ_monthly_salary as monthly_salary",
											"B.employment_status_name as employment_status",
											"A.relevance_flag as relevance_flag",
											"A.remarks as remarks",
											"DATE_FORMAT(A.employ_start_date_old,'%M %d, %Y') as start_date_old",
											"DATE_FORMAT(A.employ_end_date_old,'%M %d, %Y') as end_date_old",
											"A.employ_position_name_old as position_old",
											"A.employ_office_name_old as company_name_old",
											"A.employ_monthly_salary_old as monthly_salary_old",
											"C.employment_status_name as employment_status_old",	
											"A.relevance_flag_old as relevance_flag_old",		
											"A.remarks_old as remarks_old"
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_work_experiences,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_employment_status,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.employment_status_id = B.employment_status_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_employment_status,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.employment_status_id_old = C.employment_status_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_VOLUNTARY_WORK:
					$field                     = array(
													"volunteer_org_name as org_name",
													"volunteer_org_address as org_address",
													"DATE_FORMAT(volunteer_start_date,'%M %d, %Y') as start_date",
													"DATE_FORMAT(volunteer_end_date,'%M %d, %Y') as end_date",
													"volunteer_hour_count as hour_count",
													"volunteer_position as position",
													"volunteer_org_name_old",
													"volunteer_org_address_old",
													"DATE_FORMAT(volunteer_start_date_old,'%M %d, %Y') as volunteer_start_date_old",
													"DATE_FORMAT(volunteer_end_date_old,'%M %d, %Y') as volunteer_end_date_old",
													"volunteer_hour_count_old",
													"volunteer_position_old"
													);			
					$tables                    =$this->requests->tbl_requests_employee_voluntary_works;
					$where                     = array();
					$where["request_sub_id"] = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_TRAININGS:
					$field                     = array(
													"training_name",
													"DATE_FORMAT(training_start_date,'%M %d, %Y') as start_date",
													"DATE_FORMAT(training_end_date,'%M %d, %Y') as end_date",
													"training_hour_count as hour_count",
													"training_type",
													"training_conducted_by as conducted_by",
													"relevance_flag as relevance_flag",
													"training_name_old",
													"DATE_FORMAT(training_start_date_old,'%M %d, %Y') as training_start_date_old",
													"DATE_FORMAT(training_end_date_old,'%M %d, %Y') as training_end_date_old",
													"training_hour_count_old",
													"training_type_old",	
													"training_conducted_by_old",
													"relevance_flag_old as relevance_flag_old"
													);			
					$tables                    =$this->requests->tbl_requests_employee_trainings;
					$where                     = array();
					$where["request_sub_id"] = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_OTHER_INFO:
					$field     = array(
											"B.other_info_type_name as info_type",
											"A.others_value as value",
											"C.other_info_type_name",
											"A.others_value_old"										
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_other_info,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_other_info_types,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.other_info_type_id = B.other_info_type_id',
						),
						't3'	=> array(
							'table'		=> $this->requests->tbl_param_other_info_types,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.other_info_type_id_old = C.other_info_type_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_QUESTIONNAIRE:
					$field     = array(
											'A.question_answer_flag',
											'A.question_answer_flag_old',
											"B.question_txt",
											"A.question_answer_txt",
											"A.question_answer_txt_old"
											);			
					$tables = array(
						'main'	=> array(
							'table'		=> $this->requests->tbl_requests_employee_questions,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->requests->tbl_param_questions,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.question_id = B.question_id',
						)
					);
					$where                            = array();
					$where["A.request_sub_id"]        = $request_sub_id;
					
					$results = $this->requests->get_general_data($field, $tables, $where, TRUE);
					$data1 = array();
					$data2 = array();
					foreach ($results as $key => $result) {
						$data1[$result['question_txt']] = ($result['question_answer_flag'] == YES) ? '<b>YES<br></b>'.$result['question_answer_txt']: '<b>NO</b>';
						$data2['question_'.$key] = (!EMPTY($result['question_answer_flag_old'])) ? (($result['question_answer_flag_old'] == YES) ? '<b>YES<br></b>'.$result['question_answer_txt_old']: '<b>NO</b>'): '';
					}
					$data = array_merge($data1,$data2);
				break;
				case TYPE_REQUEST_PDS_REFERENCES:
					$field                     = array(
													"reference_full_name as name",
													"reference_address as address",
													"reference_contact_info as contact_info",
													"reference_full_name_old",
													"reference_address_old",
													"reference_contact_info_old"
													);			
					$tables                    =$this->requests->tbl_requests_employee_references;
					$where                     = array();
					$where["request_sub_id"] = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				case TYPE_REQUEST_PDS_DECLARATION:
					$field                     = array(
													"govt_issued_id",
													"ctc_no",
													"issued_place",
													"DATE_FORMAT(issued_date,'%M %d, %Y') as issued_date",
													"govt_issued_id_old",
													"ctc_no_old",
													"issued_place_old",
													"DATE_FORMAT(issued_date_old,'%M %d, %Y') as issued_date_old"
													);			
					$tables                    =$this->requests->tbl_requests_employee_declaration;
					$where                     = array();
					$where["request_sub_id"] = $request_sub_id;
					
					$data = $this->requests->get_general_data($field, $tables, $where, false);
				break;
				default:
					throw new Exception($this->lang->line('invalid_action'));
				break;
			}
			return $data;
				
		}
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function process_supporting_document()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params     = get_params();
			$action     = $params['action'];
			$token      = $params['token'];
			$salt       = $params['salt'];
			$id         = $params['id'];
			$module     = $params['module'];
			$request_id = $params['request_id'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($request_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module . '/' . $request_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_document($params);
			
			Main_Model::beginTransaction();


			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->requests->tbl_requests_sub;
			$where							= array();
			$key 							= $this->get_hash_key('request_id');
			$where[$key]					= $request_id;
			$request_sub_info				= $this->requests->get_general_data($field, $table, $where, FALSE);

			

			$fields                     = array() ;
			$fields['request_id']       = $request_sub_info["request_id"];
			$fields['supp_doc_type_id'] = $valid_data["document_type_id"];
			$fields['date_received']    = $valid_data["date_received"];
			$fields['remarks']          = $valid_data["remarks"];

			if($action == ACTION_ADD)
			{	
				
				$table                = $this->requests->tbl_employee_supporting_docs;
				$employee_training_id = $this->requests->insert_general_data($table,$fields,TRUE);
				
				
				$audit_table[]        = $this->requests->tbl_employee_supporting_docs;
				$audit_schema[]       = DB_MAIN;
				$prev_detail[]        = array();
				$curr_detail[]        = array($fields);
				$audit_action[]       = AUDIT_INSERT;	

				$audit_activity 				= "Supporting document has been added.";

				$status = true;
				$message = $this->lang->line('data_saved');


			}
			else
			{
				$where       = array();
				$key         = $this->get_hash_key('request_supporting_doc_id');
				$where[$key] = $id;
				$table       = $this->requests->tbl_employee_supporting_docs;
				$this->requests->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->requests->tbl_employee_supporting_docs;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($declaration);
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$audit_activity 				= "Supporting document has been updated.";
				$status = true;
				$message = $this->lang->line('data_updated');
			}
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}
	private function _validate_document($params)
	{
		try
		{
						
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();

			$fields['document_type_id'] = "Document Type";
			$fields['date_received']    = "Date Received";
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_document($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_document($params)
	{
		try
		{
			$validation['document_type_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Document Type',
					'max_len'	=> 11
			);
			$validation['date_received'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Received'
			);
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks',
					'max_len'	=> 300
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function delete_supporting_document()
	{
		try
		{
			$flag = 0;
			$params			= get_params();
			$url 			= $params['param_1'];
			$url_explode	= explode('/',$url);
			$action 		= $url_explode[0];
			$id				= $url_explode[1];
			$token 			= $url_explode[2];
			$salt 			= $url_explode[3];
			$module			= $url_explode[4];
			$request_id		= $url_explode[5];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($request_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $request_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			Main_Model::beginTransaction();
			//GET PREVIOUS DATA
			$prev_data				= array() ;
			/*GET PREVIOUS DATA*/
			$field 						= array("*") ;
			$table						= $this->requests->tbl_employee_supporting_docs;
			$where						= array();
			$key 						= $this->get_hash_key('request_supporting_doc_id');
			$where[$key]				= $id;
			$supporting_doc 			= $this->requests->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where					= array();
			$key 					= $this->get_hash_key('request_supporting_doc_id');
			$where[$key]			= $id;
			$table 					= $this->requests->tbl_employee_supporting_docs;
			
			$this->requests->delete_general_data($table,$where);
			
			$audit_table[]				= $this->requests->tbl_employee_supporting_docs;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($supporting_doc);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$audit_activity 			= "Supporting document has been deleted.";
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 					= $this->lang->line('data_deleted');
			$flag 					= 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		$post_data = array(	
								'request_id'		=> $request_id,
								'request_action' 	=> $action,
								'request_module' 	=> $module
				);
		$response 					= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'table_request_supporting_documents',
			"path"					=> PROJECT_MAIN . '/requests/get_supporting_documents_list/',
			"advanced_filter" 		=> true,
			'post_data' 			=> json_encode($post_data)
			);
		echo json_encode($response);
	}
}


/* End of file Employee_requests.php */
/* Location: ./application/modules/main/controllers/Employee_requests.php */
