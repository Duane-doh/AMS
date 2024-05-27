<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_time_record extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('daily_time_record_model', 'dtr');
		$this->load->model('pds_model', 'pds');
	}
	//  davcorrea : START : date and status filter
	// public function index()
	public function index($date_start = null, $date_end = null, $status_filter = null)
	// davcorrea : END 
	{

		try 
		{			
			// $data                     = array();
			// $resources                = array();
			// $resources['load_css']    = array(CSS_SELECTIZE, CSS_DATATABLE);
			// $resources['load_js']     = array(JS_SELECTIZE, JS_DATATABLE);
			// $resources['datatable'][] = array('table_id' => 'attendance_table_list', 'path' => 'main/daily_time_record/get_employee_list/', 'advanced_filter' => TRUE);
			

			// $fields = array('A.office_id','B.name AS office_name');
			// $tables = array(
				// 'main' => array(
					// 'table' => $this->pds->tbl_param_offices,
					// 'alias' => 'A'
				// ),
				// 't1'   => array(
					// 'table' => $this->pds->db_core . '.' . $this->pds->tbl_organizations,
					// 'alias' => 'B',
					// 'type'  => 'JOIN',
					// 'condition' => 'A.org_code = B.org_code'
	 			// )
			// );
			// $where = array('A.active_flag' => 'Y');
			
			//marvin
			//add filter office scope of user
			// $user_scopes['human_resources'] 		= isset($_SESSION['user_offices'][9]) ? $_SESSION['user_offices'][9] : '';
			// $user_scopes['personal_data_sheets'] 	= isset($_SESSION['user_offices'][10]) ? $_SESSION['user_offices'][10] : '';
			// $user_scopes['performance_evaluation'] 	= isset($_SESSION['user_offices'][11]) ? $_SESSION['user_offices'][11] : '';
			// $user_scopes['time_and_attendance'] 	= isset($_SESSION['user_offices'][49]) ? $_SESSION['user_offices'][49] : '';
			// $user_scopes['attendance_logs'] 		= isset($_SESSION['user_offices'][50]) ? $_SESSION['user_offices'][50] : '';
			// $user_scopes['daily_time_record'] 		= isset($_SESSION['user_offices'][51]) ? $_SESSION['user_offices'][51] : '';
			// $user_scopes['leaves'] 					= isset($_SESSION['user_offices'][53]) ? $_SESSION['user_offices'][53] : '';
			// $user_scopes['payroll'] 				= isset($_SESSION['user_offices'][61]) ? $_SESSION['user_offices'][61] : '';
			// $user_scopes['general_payroll'] 		= isset($_SESSION['user_offices'][63]) ? $_SESSION['user_offices'][63] : '';
			// $user_scopes['special_payroll'] 		= isset($_SESSION['user_offices'][64]) ? $_SESSION['user_offices'][64] : '';
			// $user_scopes['voucher'] 				= isset($_SESSION['user_offices'][65]) ? $_SESSION['user_offices'][65] : '';
			// $user_scopes['remittance'] 				= isset($_SESSION['user_offices'][66]) ? $_SESSION['user_offices'][66] : '';
			// $user_scopes['compensation'] 			= isset($_SESSION['user_offices'][12]) ? $_SESSION['user_offices'][12] : '';
			// $user_scopes['deductions'] 				= isset($_SESSION['user_offices'][13]) ? $_SESSION['user_offices'][13] : '';
			
			// $user_office_scope = explode(',',$user_scopes['daily_time_record']);
			// $where['A.office_id'] = array($user_office_scope, array('IN'));
			//end
			
			// $data['office_list'] = $this->pds->get_general_data($fields, $tables, $where);

			/*BREADCRUMBS*/
			// $breadcrumbs 			= array();
			// $key					= "Time & Attendance"; 
			// $breadcrumbs[$key]		= PROJECT_MAIN."/daily_time_record";
			// $key					= "Employee Attendance"; 
			// $breadcrumbs[$key]		= PROJECT_MAIN."/daily_time_record";
			// set_breadcrumbs($breadcrumbs, TRUE);
			// $this->template->load('daily_time_record/attendance', $data, $resources);
			
			
			
			
			//=============================================================marvin=============================================================
			//change filter base on roles
			$data                     = array();
			$resources                = array();
			// $resources['load_css']    = array(CSS_SELECTIZE, CSS_DATATABLE);
			// $resources['load_js']     = array(JS_SELECTIZE, JS_DATATABLE);
			// davcorrea : START : 10/13/2023 include date range filter
			$resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE, CSS_DATETIMEPICKER);
		$resources['load_js'] 		= array(JS_DATATABLE, JS_SELECTIZE, JS_DATETIMEPICKER);
			if(!empty($date_start) AND !empty($date_end))
			{
				// decode parameter
				$date_start = base64_decode(urldecode($date_start));
				$date_end = base64_decode(urldecode($date_end));
				$status_filter =  base64_decode(urldecode($status_filter));
				// display in filter
				$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
				$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');
				$data['status_filter']   = $status_filter;
	
				// paramter in get_request
				$date_start = format_date($date_start,'Y-m-d');
				$date_end   = format_date($date_end,'Y-m-d');

				$resources['datatable'][] = array('table_id' => 'attendance_table_list', 'path' => 'main/daily_time_record/get_employee_list/'.$date_start.'/'.$date_end.'/'.$status_filter.'', 'advanced_filter' => TRUE);
			}
			else
			{
				// default 1 month range
				$date_end  = date('Y/m/d');
				$date_start = date('Y/m/d', strtotime('-1 months', strtotime($date_end)));
	
				// default display in filter
				$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
				$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');
	
				$resources['datatable'][] = array('table_id' => 'attendance_table_list', 'path' => 'main/daily_time_record/get_employee_list/', 'advanced_filter' => TRUE);
			}
			// davcorrea : END
			// $resources['datatable'][] = array('table_id' => 'attendance_table_list', 'path' => 'main/daily_time_record/get_employee_list/', 'advanced_filter' => TRUE);
			
			if(in_array('AO', $_SESSION['user_roles']) OR in_array('IMMSUP', $_SESSION['user_roles']) OR in_array('LVAPPOFF', $_SESSION['user_roles']))
			{
				$fields = array('A.user_id','A.lname','A.fname','A.mname');
				$tables = array(
					'main' => array(
						'table' => $this->pds->db_core . '.' . $this->pds->tbl_users,
						'alias' => 'A'
					)
				);
				$where = array('A.status_id' => 1);
			}
			else
			{
				$fields = array('A.office_id','B.name AS office_name');
				$tables = array(
					'main' => array(
						'table' => $this->pds->tbl_param_offices,
						'alias' => 'A'
					),
					't1'   => array(
						'table' => $this->pds->db_core . '.' . $this->pds->tbl_organizations,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.org_code = B.org_code'
					)
				);
				$where = array('A.active_flag' => 'Y');
			}
			
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
			
			if(in_array('AO', $_SESSION['user_roles']) OR in_array('IMMSUP', $_SESSION['user_roles']) OR in_array('LVAPPOFF', $_SESSION['user_roles']))
			{
				$user_scope = explode(',',$user_scopes['daily_time_record']);
				$where['A.user_id'] = array($user_scope, array('IN'));
				$data['user_list'] = $this->pds->get_general_data($fields, $tables, $where);				
			}
			else
			{
				$user_office_scope = explode(',',$user_scopes['daily_time_record']);
				$where['A.office_id'] = array($user_office_scope, array('IN'));
				$data['office_list'] = $this->pds->get_general_data($fields, $tables, $where);				
			}
			
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Time & Attendance"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/daily_time_record";
			$key					= "Employee Attendance"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/daily_time_record";
			set_breadcrumbs($breadcrumbs, TRUE);
			$this->template->load('daily_time_record/attendance', $data, $resources);
			//=============================================================marvin=============================================================
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
	}
	public function display_attendance($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)	
	{
		try
		{
			$data 					= array();
			$resources 				= array();

			$resources['load_css']    = array(CSS_DATATABLE);
			$resources['load_js']     = array(JS_DATATABLE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}			
			
			$post_data                = array('employee_id' => $id);
			$resources['datatable'][] = array('table_id' => 'employee_logs_table', 'path' => 'main/daily_time_record/get_employee_log/', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));

			$resources['load_modal'] = array(
				'modal_attendance_breakdown' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_attendance_breakdown',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'lg',
						'title'			=> 'Attendance Breakdown'
				),
				'modal_add_employee_attendance' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_attendance',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> 'Employee Attendance'
				)
			);

			$data['personal_info']    = $this->pds->get_employee_info($id);
			
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
		
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Employee Attendance"; 
		$breadcrumbs[$key]		= "";
		set_breadcrumbs($breadcrumbs, FALSE);
		$this->template->load('daily_time_record/employee_attendance', $data, $resources);
		
	}

	public function modal_attendance_breakdown($action, $id, $token, $salt, $module)	
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			$resources['load_css'] 	= array(CSS_DATATABLE);
			$resources['load_js']	= array(JS_DATATABLE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$post_data                = array('employee_attendance_id' => $id);
			$resources['datatable'][] = array('table_id' => 'table_attendance_breakdown', 'path' => 'main/daily_time_record/get_employee_attendance_breakdown/', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));

			$resources['load_modal']	= array(
				'modal_update_attendance_breakdown' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_update_attendance_breakdown',
						'multiple'		=> true,
						'height'		=> '250px',
						'size'			=> 'sm',
						'title'			=> 'Update Attendance'
				)
			);
			
			 $resources['load_delete'] 	= array(
								__CLASS__,
								'delete_attendance',
								PROJECT_MAIN
							);
			$this->load->view('daily_time_record/modals/modal_attendance_breakdown', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}
	public function modal_add_employee_attendance($action, $id, $token, $salt, $module)	
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE);
			$resources['load_js']	= array(JS_DATETIMEPICKER,JS_SELECTIZE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$tables                 = $this->dtr->tbl_param_leave_intervals;
			$where['active_flag'] = "Y"; 
			$where                  = array();
			$data['duration_types'] = $this->dtr->get_general_data(array("*"), $tables, $where, TRUE);


			$tables = array(
				'main'	=> array(
					'table'		=> $this->dtr->tbl_param_leave_types,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->dtr->tbl_employee_leave_balances,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.leave_type_id = B.leave_type_id',
				)
			);
			$where               = array();
			$key                 = $this->get_hash_key('B.employee_id');
			$where[$key]         = $id;
			$data['leave_types'] = $this->dtr->get_general_data(array("*"), $tables, $where, TRUE);
			
			$this->load->view('daily_time_record/modals/modal_add_employee_attendance', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
		
	}
	public function modal_update_attendance_breakdown($action, $id, $token, $salt, $module)	
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER);
			$resources['load_js']	= array(JS_DATETIMEPICKER);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$table              = $this->dtr->tbl_employee_attendance;
			$where              = array();
			$key                = $this->get_hash_key('employee_attendance_id');
			$where[$key]        = $id;
			$data['attendance'] = $this->dtr->get_general_data(array("TIME_FORMAT(time_in,'%h:%i %p') as time_in", "TIME_FORMAT(time_out,'%h:%i %p') as time_out", "TIME_FORMAT(break_in,'%h:%i %p') as break_in", "TIME_FORMAT(break_out,'%h:%i %p') as break_out"), $table, $where, FALSE);
			
			$this->load->view('daily_time_record/modals/modal_update_attendance_breakdown', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}	

	// public function get_employee_list()
	// davcorrea: 10/13/2023 : include date range filter : start
	public function get_employee_list($date_start, $date_end, $status_filter)
	// davcorrea : include date range filter : end
	{

		try
		{
			$module = MODULE_TA_DAILY_TIME_RECORD;
			$params         = get_params();
			//davcorrea : sort table from latest employees first : START
			if($params['sSortDir_0'] == 'asc')
			{
				$params['sSortDir_0'] = "desc";
			}else
			{
				$params['sSortDir_0'] = "asc";
			}
			//davcorrea : sort table from latest employees first : END
			/*
			//=============================================================marvin=============================================================
			//change column parameter base on roles
			if(isset($params['C-office_id']))
			{
				$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name,', ', A.first_name, ' ',A.middle_name) as fullname", "", "E.name", "D.employment_status_name", "B.employ_office_id", "C.office_id");
			}
			else
			{
				$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name,', ', A.first_name, ' ',A.middle_name) as fullname", "", "E.name", "D.employment_status_name", "B.employ_office_id", "F.user_id");
			}
			//=============================================================marvin=============================================================
			*/
			
			// ====================== jendaigo : start : change name format ============= //
			if(isset($params['C-office_id']))
				$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', A.middle_name))) as fullname", "", "E.name", "D.employment_status_name", "B.employ_office_id", "C.office_id");
			else
				$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', A.middle_name))) as fullname", "", "E.name", "D.employment_status_name", "B.employ_office_id", "F.user_id");
			// ====================== jendaigo : end : change name format ============= //
			
			$bColumns       = array("A.agency_employee_id", "fullname", "E.name", "D.employment_status_name");
		
			// davcorrea: 10/13/2023 : include date range fitler : start
			$params['date_start'] = $date_start;
			$params['date_end'] = $date_end;
			$params['status_filter'] = $status_filter;
			if(empty($params['date_start']) && empty($params['date_end']))
			{
				$params['date_end'] = date("Y-m-d");
				$params['date_start'] = date("Y-m-d", strtotime("-1 months"));
			}

			if(empty($params['status_filter']))
			{
				$params['status_filter'] = "Y";
			}

			$employees       = $this->dtr->get_employee_list($aColumns, $bColumns, $params);
			// davcorrea: 10/13/2023 : include date range fitler : end
			$iTotal         = $this->dtr->total_length();
			$iFilteredTotal = $this->dtr->filtered_length($aColumns, $bColumns, $params,$module);
			
			// DEFAULT PARAMETERS TO BE PASSED 
			$output = array(

				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal['cnt'],
				"iTotalDisplayRecords" => $iFilteredTotal['cnt'],
				"aaData"               => array()

			);
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$user_offices    = $this->session->userdata('user_offices');
			$office_list     = explode(',', $user_offices[$module]);

			foreach ($employees as $aRow):
				
				$row = array();
				$action = "";
				

				$id 			= $this->hash($aRow['employee_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;

				$row[]      = $aRow['agency_employee_id'];
				$row[]      = $aRow['fullname'];
				$row[]      = $aRow['name'];
				$row[]      = $aRow['employment_status_name'];

				$action = "<div class='table-actions'>";

				if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped'  data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"content_form('employee_attendance/display_attendance/".$url_view."', '".PROJECT_MAIN."')\"></a>";
				
				// if($permission_edit == true AND in_array($aRow['employ_office_id'],$office_list ))
				
				//=============================================================marvin=============================================================
				//check permission and change parameter base on roles
				if(isset($aRow['office_id']))
				{
					if($permission_edit == true AND in_array($aRow['office_id'],$office_list ));
				}
				else
				{
					if($permission_edit == true AND in_array($aRow['user_id'],$office_list ));			
				}
				//=============================================================marvin=============================================================
				// if($permission_edit == true AND in_array($aRow['user_id'],$office_list ))
				$action .= "<a href='javascript:;' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"content_form('employee_attendance/display_attendance/".$url_edit."', '".PROJECT_MAIN."')\"></a>";
				
				$action .= '</div>';
					
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

	public function get_employee_log()
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("B.employee_attendance_id","DATE_FORMAT(B.attendance_date,'%M %d, %Y') as attendance_date","DATE_FORMAT(B.attendance_date,'%W') as day_name", "min(TIME_FORMAT(B.time_in,'%h:%i %p')) as time_in", "max(TIME_FORMAT(B.time_out,'%h:%i %p')) as time_out", "min(TIME_FORMAT(B.break_in,'%h:%i %p')) as break_in", "max(TIME_FORMAT(B.break_out,'%h:%i %p')) as break_out");
			$bColumns       = array("DATE_FORMAT(B.attendance_date,'%M %d, %Y')","DATE_FORMAT(B.attendance_date,'%W')", "min(TIME_FORMAT(B.time_in,'%h:%i %p'))", "max(TIME_FORMAT(B.time_out,'%h:%i %p'))", "min(TIME_FORMAT(B.break_in,'%h:%i %p'))", "max(TIME_FORMAT(B.break_out,'%h:%i %p'))");
			
			$attendance     = $this->dtr->get_attendance_list($aColumns, $bColumns, $params);
			$iTotal         = $this->dtr->attendance_total_length($params['employee_id']);
			$iFilteredTotal = $this->dtr->attendance_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module = MODULE_TA_DAILY_TIME_RECORD;
			/*
			$permission_view = $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			*/
			$cnt = 0;
			foreach ($attendance as $aRow):
				$cnt++;
				$row = array();
				$action = "";
				

				$id 			= $this->hash($aRow['employee_attendance_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;

				$row[] =  $aRow['attendance_date'];
				$row[] =  !EMPTY($aRow['day_name']) ? $aRow['day_name']:"--:--";	
				$row[] =  !EMPTY($aRow['time_in']) ? $aRow['time_in']:"--:--";	
				$row[] =  !EMPTY($aRow['break_out']) ? $aRow['break_out']:"--:--";
				$row[] =  !EMPTY($aRow['break_in']) ? $aRow['break_in']:"--:--";	
				$row[] =  !EMPTY($aRow['time_out']) ? $aRow['time_out']:"--:--";

				$action = "<div class='table-actions'>";

				
				// if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_attendance_breakdown' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_attendance_breakdown_init('".$url_view."')\"></a>";
				
				$action .= '</div>';
				
				
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
	public function get_employee_attendance_breakdown()
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.employee_attendance_id","TIME_FORMAT(B.time_in,'%r') as time_in", "TIME_FORMAT(B.time_out,'%r') as time_out", "TIME_FORMAT(B.break_in,'%r') as break_in", "TIME_FORMAT(B.break_out,'%r') as break_out","B.source_flag");
			$bColumns       = array("TIME_FORMAT(B.time_in,'%r')", "TIME_FORMAT(B.time_out,'%r')", "TIME_FORMAT(B.break_in,'%r')", "TIME_FORMAT(B.break_out,'%r')","B.source_flag");
			
			$attendance     = $this->dtr->get_attendance_breakdown_list($aColumns, $bColumns, $params);
			$iTotal         = $this->dtr->attendance_breakdown_total_length($params['employee_attendance_id']);
			$iFilteredTotal = $this->dtr->attendance_breakdown_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module = MODULE_TA_DAILY_TIME_RECORD;
			
			/*$permission_view = $this->permission->check_permission($module, ACTION_VIEW);*/
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			

			foreach ($attendance as $aRow):
				
				$row = array();
				$action = "";
				

				$id            = $this->hash($aRow['employee_attendance_id']);
				$salt          = gen_salt();
				$token_edit    = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete  = in_salt($id . '/' . ACTION_DELETE . '/' . $module, $salt);
				
				$url_edit      = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete    = ACTION_DELETE."/".$id."/".$token_delete."/".$salt."/".$module;
				
				$delete_action = 'content_delete("attendance", "'.$url_delete.'")';
			
				if($aRow['source_flag'] == "B")
				{
					$row[] =  "Biometric";
				}
				else if($aRow['source_flag'] == "M")
				{
					$row[] =  "Manually Encoded";
				}
				else
				{
					$row[] =  "Request";
				}
				
				$row[] =  !EMPTY($aRow['time_in']) ? $aRow['time_in']:"--:--";	
				$row[] =  !EMPTY($aRow['break_out']) ? $aRow['break_out']:"--:--";
				$row[] =  !EMPTY($aRow['break_in']) ? $aRow['break_in']:"--:--";	
				$row[] =  !EMPTY($aRow['time_out']) ? $aRow['time_out']:"--:--";

				$action = "<div class='table-actions'>";

				if($aRow['source_flag'] == "M")
				{
					if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_update_attendance_breakdown' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_update_attendance_breakdown_init('".$url_edit."')\"></a>";
					if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= '</div>';
				
				
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
	public function get_duration_time()
	{
		try
		{	
			$total_days = "";
			$params = get_params();

			$tables                = $this->dtr->tbl_param_leave_intervals;
			$where                 = array();
			$where['leave_interval_id'] = $params['duration_type'];
			$intervals               = $this->dtr->get_general_data(array("TIME_FORMAT(time_in,'%h:%i %p') as time_in","TIME_FORMAT(break_out,'%h:%i %p') as break_out","TIME_FORMAT(break_in,'%h:%i %p') as break_in","TIME_FORMAT(time_out,'%h:%i %p') as time_out"), $tables, $where, FALSE);
			
		}
		catch(Exception $e)
		{
			throw $e;
		}
		$data              = array();
		$data['breakdown'] = $columns;
		$data['total_days']  = $total_days;
	
		echo json_encode($intervals);
	}
	public function _process_leave($valid_data)
	{
		try
		{			
			
		
			$field                  = array('*');
			$where                  = array();
			$where["employee_id"]   = $valid_data['employee_id'];
			$where["leave_type_id"] = $valid_data['leave_type'];
			$tables                 = $this->dtr->tbl_employee_leave_balances;
			$check_balance          = $this->dtr->get_general_data($field, $tables, $where, false);

			$table                = $this->dtr->tbl_param_leave_intervals;
			$where                = array();
			$where["leave_interval_id"] = $valid_data['duration_type'];
			$interval             = $this->dtr->get_general_data(array("*"), $table, $where, FALSE);

			/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
			if($check_balance['leave_balance'] >= $interval['leave_duration'])
			{
				
				$fields                    = array();
				$fields['time_in']         = !EMPTY($valid_data['time_in']) ? $valid_data['time_in'] : NULL;
				$fields['break_out']       = !EMPTY($valid_data['break_out']) ? $valid_data['break_out'] : NULL;
				$fields['break_in']        = !EMPTY($valid_data['break_in']) ? $valid_data['break_in'] : NULL;
				$fields['time_out']        = !EMPTY($valid_data['time_out']) ? $valid_data['time_out'] : NULL;
				$fields['employee_id']     = $valid_data['employee_id'];
				$fields['attendance_date'] = $valid_data['attendance_date'];
				$fields['source_flag']     = 'M';

				$table  = $this->dtr->tbl_employee_attendance;
				$this->dtr->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->dtr->tbl_employee_attendance;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	
					
				$new_leave_balance = $check_balance['leave_balance'] - $interval['leave_duration'];
				
				$table  = $this->dtr->tbl_employee_leave_balances;
				$fields                  = array();
				$fields['leave_balance'] = $new_leave_balance;					
				
				$where                   = array();
				$where['employee_id']    = $valid_data['employee_id'];
				$where['leave_type_id']  = $valid_data['leave_type'];
				$this->dtr->update_general_data($table,$fields,$where);

				$fields                              = array();
				$fields['employee_id']               = $valid_data['employee_id'];
				$fields['leave_type_id']             = $valid_data['leave_type'];
				$fields['effective_date']           = date('Y-m-d');
				$fields['leave_transaction_date']    = date('Y-m-d');
				$fields['leave_earned_used']         = $interval['leave_duration'];
				$fields['leave_transaction_type_id'] = LEAVE_FILE_LEAVE;
				$fields['leave_start_date']          = $valid_data['attendance_date'];
				$fields['leave_end_date']            = $valid_data['attendance_date'];

				$table  = $this->dtr->tbl_employee_leave_details;
				$this->dtr->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->dtr->tbl_employee_attendance;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "%s has been added.";
				$audit_activity 		= sprintf($activity, "Attendance Manual encoding ");

				$this->audit_trail->log_audit_trail($audit_activity, MODULE_TA_DAILY_TIME_RECORD, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			}
			else
			{
				throw new Exception("Requested action is invalid. Employee Leave balance is insufficient. ");
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
	public function process_add_attendance()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";

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
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_attendance($params);
			
			Main_Model::beginTransaction();

			$table						= $this->dtr->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $id;
			$pds_data 					= $this->dtr->get_general_data(array("employee_id"), $table, $where, FALSE);

			$valid_data['employee_id'] = $pds_data['employee_id'];

			if($valid_data['leave_flag'] == "Y")
			{
				
				$this->_process_leave($valid_data);
			}
			else
			{
				$fields                    = array();
				$fields['time_in']         = !EMPTY($valid_data['time_in']) ? $valid_data['time_in'] : NULL;
				$fields['break_out']       = !EMPTY($valid_data['break_out']) ? $valid_data['break_out'] : NULL;
				$fields['break_in']        = !EMPTY($valid_data['break_in']) ? $valid_data['break_in'] : NULL;
				$fields['time_out']        = !EMPTY($valid_data['time_out']) ? $valid_data['time_out'] : NULL;
				$fields['employee_id']     = $valid_data['employee_id'];
				$fields['attendance_date'] = $valid_data['attendance_date'];
				$fields['source_flag']     = 'M';

				$table  = $this->dtr->tbl_employee_attendance;
				$this->dtr->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->dtr->tbl_employee_attendance;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "%s has been added.";
				$audit_activity 		= sprintf($activity, "Attendance Manual encoding ");

				$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			}
			
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_saved');
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

	private function _validate_attendance($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields                    = array();
			$fields['duration_type']   = "Duration Type";
			$fields['attendance_date'] = "Attendance Date";			
			if($params['leave_flag']   == "Y")
			{
				$fields['leave_type']  = "Leave type";
			}
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_attendance($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_attendance($params)
	{
		try
		{
			
			$validation['leave_flag'] = array(
					'data_type' => 'enum',
					'name'		=> 'Switch',
					'allowed_values'	=> array('Y','N')
			);	
			$validation['leave_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Leave Type',
					'max_len'	=> 3
			);	
			$validation['duration_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Duration Type',
					'max_len'	=> 3
			);	
			$validation['attendance_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Attendance Date'
			);	
			$validation['time_in'] = array(
					'data_type' => 'time',
					'name'		=> 'Time In'
			);	
			$validation['break_out'] = array(
					'data_type' => 'time',
					'name'		=> 'Break Out'
			);	
			$validation['break_in'] = array(
					'data_type' => 'time',
					'name'		=> 'Break In'
			);	
			$validation['time_out'] = array(
					'data_type' => 'time',
					'name'		=> 'Time Out'
			);	
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	

	public function process_update_attendance()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";

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
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_update_attendance($params);
			
			Main_Model::beginTransaction();
			$table           = $this->dtr->tbl_employee_attendance;
			$where           = array();
			$key             = $this->get_hash_key('employee_attendance_id');
			$where[$key]     = $id;
			$prev_attendance = $this->dtr->get_general_data(array("*"), $table, $where, FALSE);

			$fields              = array();
			$fields['time_in']   = !EMPTY($valid_data['time_in']) ? $valid_data['time_in'] : NULL;
			$fields['break_out'] = !EMPTY($valid_data['break_out']) ? $valid_data['break_out'] : NULL;
			$fields['break_in']  = !EMPTY($valid_data['break_in']) ? $valid_data['break_in'] : NULL;
			$fields['time_out']  = !EMPTY($valid_data['time_out']) ? $valid_data['time_out'] : NULL;

			$table  = $this->dtr->tbl_employee_attendance;
			$where           = array();
			$key             = $this->get_hash_key('employee_attendance_id');
			$where[$key]     = $id;
			$this->dtr->update_general_data($table,$fields,$where);

			$audit_table[]  = $this->dtr->tbl_employee_attendance;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($prev_attendance);
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;	
			
			$activity       = "%s has been updated.";
			$audit_activity = sprintf($activity, "Attendance Manual encoding ");

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
		
			
			
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

	private function _validate_update_attendance($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields            = array();
			$fields['time_in'] = "Time in";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_update_attendance($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_update_attendance($params)
	{
		try
		{
			$validation['time_in'] = array(
					'data_type' => 'time',
					'name'		=> 'Time In'
			);	
			$validation['break_out'] = array(
					'data_type' => 'time',
					'name'		=> 'Break Out'
			);	
			$validation['break_in'] = array(
					'data_type' => 'time',
					'name'		=> 'Break In'
			);	
			$validation['time_out'] = array(
					'data_type' => 'time',
					'name'		=> 'Time Out'
			);	
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	
	public function delete_attendance()
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

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			Main_Model::beginTransaction();
			//GET PREVIOUS DATA
			$prev_data				= array() ;
			/*GET PREVIOUS DATA*/
			$field               = array("*") ;
			$table               = $this->dtr->tbl_employee_attendance;
			$where               = array();
			$key                 = $this->get_hash_key('employee_attendance_id');
			$where[$key]         = $id;
			$employee_attendance = $this->dtr->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where					= array();
			$key 					= $this->get_hash_key('employee_attendance_id');
			$where[$key]			= $id;
			$table 					= $this->dtr->tbl_employee_attendance;
			
			$this->dtr->delete_general_data($table,$where);
			
			$audit_table[]				= $this->dtr->tbl_employee_attendance;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($employee_attendance);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$audit_activity 			= "Employee attendance has been deleted.";
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 					= $this->lang->line('data_deleted');
			$flag 					= 1;

			$field                    = array("*") ;
			$table                    = $this->dtr->tbl_employee_attendance;
			$where                    = array();
			$where['employee_id']     = $employee_attendance['employee_id'];
			$where['attendance_date'] = $employee_attendance['attendance_date'];
			$new_employee_attendance      = $this->dtr->get_general_data($field, $table, $where, FALSE);
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		$id        = $this->hash($new_employee_attendance['employee_attendance_id']);
		$post_data = array('employee_attendance_id' => $id);
		$response 					= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'table_attendance_breakdown',
			"path"					=> PROJECT_MAIN . '/daily_time_record/get_employee_attendance_breakdown/',
			"advanced_filter" 		=> true,
			'post_data' 			=> json_encode($post_data)
			);
		echo json_encode($response);
	}
}
/* End of file Daily_time_record.php */
/* Location: ./application/modules/main/controllers/Daily_time_record.php */