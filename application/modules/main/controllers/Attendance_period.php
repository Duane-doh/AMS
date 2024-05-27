<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_period extends Main_Controller {
	private $log_user_id    =  '';
	private $log_user_roles = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('attendance_period_model', 'attendance');
		$this->load->model('leaves_model', 'leaves');
		$this->log_user_id    = $this->session->userdata('user_id');
		$this->log_user_roles = $this->session->userdata('user_roles');
	}
	 
	public function index()
	{
		try
		{
			$data      = array();
			$resources = array();
		
			$resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE);
			$resources['load_js'] 		= array(JS_DATATABLE,  JS_SELECTIZE);
			$resources['datatable'][]	= array('table_id' => 'table_attendance_periods', 'path' => 'main/attendance_period/get_attendance_period_list', 'advanced_filter' => true);

			$resources['load_modal']			= array(
					'modal_attendance_period'	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_attendance_period',
							'multiple'			=> true,
							'height'			=> '500px',
							'size'				=> 'sm',
							'title'				=> "Attendance Period"
					)
			);	
			$resources['load_delete'] 	= array(
								__CLASS__,
								'delete_attendance_period',
								PROJECT_MAIN
							);
			/*BREADCRUMBS*/
			$breadcrumbs       = array();
			$key               = "Time & Attendance"; 
			$breadcrumbs[$key] = PROJECT_MAIN."/attendance_period";
			$key               = "Attendance Period"; 
			$breadcrumbs[$key] = PROJECT_MAIN."/attendance_period";
			set_breadcrumbs($breadcrumbs, TRUE);
			
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

		$this->template->load('attendance_period/attendance_period', $data, $resources);
	}

	public function get_attendance_period_list()
	{

		try
		{
			$params         = get_params();
			
			// $aColumns       = array("A.attendance_period_hdr_id","A.period_status_id","B.payroll_type_name","DATE_FORMAT(A.date_from,'%M %d, %Y') as date_from","DATE_FORMAT(A.date_to,'%M %d, %Y') as date_to","C.period_status_name ");
			// marvin : include remarks for batching : start
			$aColumns       = array("A.attendance_period_hdr_id","A.period_status_id","B.payroll_type_name","DATE_FORMAT(A.date_from,'%M %d, %Y') as date_from","DATE_FORMAT(A.date_to,'%M %d, %Y') as date_to","C.period_status_name","A.remarks");
			// marvin : include remarks for batching : end
			$bColumns       = array("B.payroll_type_name","DATE_FORMAT(A.date_from,'%M %d, %Y')","DATE_FORMAT(A.date_to,'%M %d, %Y')","C.period_status_name ");
			
			$attendance     = $this->attendance->get_attendance_period_list($aColumns, $bColumns, $params);

			$iTotal         = $this->attendance->attendance_period_total_length();
			$iFilteredTotal = $this->attendance->attendance_period_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module             = MODULE_TA_ATTENDANCE_PERIOD;
			
			$permission_view    = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit    = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_process = $this->permission->check_permission($module, ACTION_PROCESS);
			$permission_delete  = $this->permission->check_permission($module, ACTION_DELETE);
			foreach ($attendance as $aRow):
				
				$row           = array();
				$id            = $this->hash($aRow['attendance_period_hdr_id']);
				$salt          = gen_salt();
				$token_view    = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);	
				$token_process = in_salt($id  . '/' . ACTION_PROCESS  . '/' . $module, $salt);		
				$token_delete  = in_salt($id  . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view      = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit      = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete    = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

				$row[] =  strtoupper($aRow['payroll_type_name']);
				$row[] =  '<center>' . $aRow['date_from'] . '</center>';
				$row[] =  '<center>' . $aRow['date_to'] . '</center>';
				$row[] =  strtoupper($aRow['period_status_name']);

				// marvin : include remarks for batching : start
				$row[] = strtoupper($aRow['remarks']);
				// marvin : include remarks for batching : end

				$action = "<div class='table-actions'>";
				if($permission_view)
				$action .= "<a href='#' class='process tooltipped' data-tooltip='Process' data-position='bottom' onclick=\"content_form('attendance_period/open_period_detail/".$url_view."','main')\" data-delay='50'></a>";
				
				if($permission_edit  == true AND $aRow['period_status_id'] == ATTENDANCE_PERIOD_PROCESSING)
				$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-modal='modal_attendance_period' data-position='bottom' data-delay='50' onclick=\"modal_attendance_period_init('".$url_edit."')\"></a>";
				
				/*if($permission_process  == true AND $aRow['period_status_id'] == ATTENDANCE_PERIOD_PROCESSING)
				$action .= "<a href='javascript:;' class='process tooltipped' data-tooltip='Process' data-position='bottom'  onclick=\"process_period('".ACTION_PROCESS."','".$id."','".$token_process."','".$salt."','".$module."')\" data-delay='50'></a>";
				*/
				$delete_action = 'content_delete("record", "'.$url_delete.'")';
				if($permission_delete  == true AND $aRow['period_status_id'] == ATTENDANCE_PERIOD_PROCESSING)
				$action        .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";

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

	public function open_period_detail($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data                 =  array();
			$resources            = array();
			
			$data['action']       = $action;
			$data['id']           = $id;
			$data['salt']         = $salt;
			$data['token']        = $token;
			$data['module']       = $module;
			$data['url_security'] = $action."/".$id."/".$token."/".$salt."/".$module;


			$breadcrumbs 			= array();
			$key					= "Attendance Period Details"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/attendance_period/open_period_detail/".$data['url_security'];
			set_breadcrumbs($breadcrumbs, FALSE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATATABLE);

			$post_data = array(	
								'attendance_period_hdr_id'		=> $id
				);
			$resources['datatable'][] = array('table_id' => 'table_attendance_period_employees', 'path' => 'main/attendance_period/get_attendance_period_employees', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			$resources['load_modal']  = array(
					'modal_employee_attendance_period' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_employee_attendance_period',
							'multiple'   => true,
							'height'     => '500px',
							'size'       => 'md',
							'title'      => 'Employee Attendance Period Details'
					),
					'modal_employee_mra' => array(
							'controller' => 'Attendance_mra',
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_employee_mra',
							'multiple'   => true,
							'height'     => '150px',
							'size'       => 'sm',
							'title'      => 'Late/Undertime w/o Pay'
					),
					'modal_employee_daily_mra' => array(
							'controller' => 'Attendance_mra',
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_employee_daily_mra',
							'multiple'   => true,
							'height'     => '450px',
							'size'       => 'lg',
							'title'      => 'Employee Daily MRA'
					)
			);

			$fields 			= array('A.office_id','B.name AS office_name');
			$tables 			= array(
				'main' 			=> array(
					'table' 	=> $this->attendance->tbl_param_offices,
					'alias' 	=> 'A'
				),
				't1'   			=> array(
					'table' 	=> $this->attendance->db_core . '.' . $this->attendance->tbl_organizations,
					'alias' 	=> 'B',
					'type'  	=> 'JOIN',
					'condition' => 'A.org_code = B.org_code'
	 			)
			);

			$where = array('A.active_flag' => 'Y');
			
			//marvin
			//add filter office scope of user
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
			
			$user_office_scope = explode(',',$user_scopes['attendance_logs']);
			$where['A.office_id'] = array($user_office_scope, array('IN'));
			//end
			
			$data['office_list'] = $this->attendance->get_general_data($fields, $tables, $where, TRUE);

			$this->template->load('attendance_period/attendance_period_employee_list', $data, $resources);
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
	public function get_attendance_period_employees()
	{
			try
		{
			$params         = get_params();
			
			// $aColumns       = array("A.employee_id", "A.agency_employee_id", "CONCAT(A.first_name,' ',A.last_name) as fullname", "E.name", "C.employment_status_name", "D.office_id");
			// ====================== jendaigo : start : change name format ============= //
			$aColumns       = array("A.employee_id", "A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name=''  OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', A.middle_name))) as fullname", "E.name", "C.employment_status_name", "D.office_id");
			// ====================== jendaigo : end : change name format ============= //
			
			$bColumns       = array("E.agency_employee_id", "E.fullname", "E.name", "E.employment_status_name");
			
			$employee_list  = $this->attendance->get_attendance_employee_list($aColumns, $bColumns, $params);
			$iTotal         = $this->attendance->employee_total_length($params['attendance_period_hdr_id']);
			$iFilteredTotal = $this->attendance->employee_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module          = MODULE_TA_ATTENDANCE_PERIOD;			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$user_offices    = $this->session->userdata('user_offices');
			$office_list     = explode(',', $user_offices[$module]);

			foreach ($employee_list as $aRow):
				$row        = array();
				
				$id         = $aRow['employee_id'];				
				$salt       = gen_salt();
				$token_view = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module . '/' . $params['attendance_period_hdr_id'], $salt);
				$url_view   = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$params['attendance_period_hdr_id'];

				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module . '/' . $params['attendance_period_hdr_id'], $salt);
				$url_edit   = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$params['attendance_period_hdr_id'];

				$row[] = $aRow['agency_employee_id'];
				$row[] = $aRow['fullname'];
				$row[] = $aRow['name'];
				$row[] = $aRow['employment_status_name'];

				
				$action = "<div class='table-actions'>";
			
				if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_employee_attendance_period' onclick=\"modal_employee_attendance_period_init('".$url_view."')\" data-delay='50'></a>";
				if($permission_edit)
				$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-tooltip='Edit Late/Undertime w/o Pay Summary' data-position='bottom' data-modal='modal_employee_mra' onclick=\"modal_employee_mra_init('".$url_edit."')\" data-delay='50'></a>";
				if($permission_edit)
				$action .= "<a href='javascript:;' class='activity tooltipped md-trigger' data-tooltip='Edit MRA' data-position='bottom' data-modal='modal_employee_daily_mra' onclick=\"modal_employee_daily_mra_init('".$url_edit."')\" data-delay='50'></a>";
				
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
	public function modal_employee_attendance_period($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module, $attendance_period_hdr_id)
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

			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATATABLE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($attendance_period_hdr_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $attendance_period_hdr_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$post_data = array('employee_id'		=> $id,'attendance_period_hdr_id' => $attendance_period_hdr_id);
			$resources['datatable'][] = array('table_id' => 'table_employee_attendance', 'path' => 'main/attendance_period/get_employees_attendance', 'advanced_filter' => true, 'post_data' => json_encode($post_data));
			

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
		$this->load->view('attendance_period/modals/modal_employee_attendance_period', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_employees_attendance()
	{
			try
		{
			$params         = get_params();
			
			$aColumns       = array("DATE_FORMAT(A.attendance_date,'%M %d, %Y') as attendance", "A.basic_hours", "A.working_hours", "B.attendance_status_name");
			$bColumns       = array("DATE_FORMAT(A.attendance_date,'%M %d, %Y')", "A.basic_hours", "A.working_hours", "B.attendance_status_name");
			
			$employee_list  = $this->attendance->get_employee_attendance($aColumns, $bColumns, $params);
			$iTotal         = $this->attendance->employee_attendance_total_length($params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => count($employee_list),
				"iTotalDisplayRecords" => $iTotal["cnt"],
				"aaData"               => array()
			);
			
			$module          = MODULE_TA_ATTENDANCE_PERIOD;			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);			
			
			foreach ($employee_list as $aRow):
				$row    = array();
				
				$row[]  = $aRow['attendance'];
				$row[]  = $aRow['basic_hours'];
				$row[]  = $aRow['working_hours'];
				$row[]  = $aRow['attendance_status_name'];
				
				
				$action = "<div class='table-actions'>";
				
				$action .= "</div>";
				
				$row[]  = $action;
					
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
	public function modal_attendance_period($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			$resources['load_css'] = array(CSS_DATETIMEPICKER,CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER,JS_SELECTIZE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			if($action != ACTION_ADD)
			{
				$field                 = array("*") ;
				$table                 = $this->attendance->tbl_attendance_period_hdr;
				$where                 = array();
				$key                   = $this->get_hash_key('attendance_period_hdr_id');
				$where[$key]           = $id;
				$data['period_detail'] = $this->attendance->get_general_data($field, $table, $where, FALSE);
				$payroll_type          = $data['period_detail']['payroll_type_id'];
				$resources['single']   = array(
											'payroll_type'  => $payroll_type
										);
			}

			$field                 = array("*");
			$table                 = $this->attendance->tbl_param_payroll_types;
			$where                 = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['payroll_type_id']   = array($payroll_type, array("=", ")"));				
			}	
			$data['payroll_types'] = $this->attendance->get_general_data($field, $table, $where, TRUE);

			//GET USER ACCOUNTS
			$field 				= array('B.employee_id','username');
			$where 				= array();
			$where['status_id'] = 1;
			$tables = array(
				'main' 	=> array(
					'table' => 'doh_ptis_core.users',
					'alias' => 'A',
				),
				't1' => array(
					'table' 	=> 'doh_ptis_module.associated_accounts',
					'alias' 	=> 'B',
					'type' 		=> 'join',
					'condition' => 'A.user_id = B.user_id'
				)
			);
			$order_by 		= array('A.username'=>'asc');
			$data['users'] 	= $this->attendance->get_general_data($field, $tables, $where, TRUE, $order_by);
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

		$this->load->view('attendance_period/modals/modal_attendance_period', $data);
		$this->load_resources->get_resource($resources);
	}
	public function process_attendance_period()
	{
		//MARVIN
		ini_set('memory_limit', '-1');
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
			
			//MARVIN : EMPLOYEE LIST TO EXCLUDE
			// $employee_list = isset($params['employee_list']) ? $params['employee_list'] : NULL;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_attendance_period($params);

			// marvin : check existing attendance period : start
			$field       		= array('*') ;
			$table       		= $this->attendance->tbl_attendance_period_hdr;
			$where       		= array();
			$where['date_from'] = $valid_data['period_from'];
			$where['date_to'] 	= $valid_data['period_to'];
			$attendance_exist  	= $this->attendance->get_general_data($field, $table, $where, TRUE);
			// marvin : check existing attendance period : end
			
			Main_Model::beginTransaction();


			$fields                     = array() ;
			$fields['payroll_type_id']  = $valid_data["payroll_type"];
			$fields['date_from']        = $valid_data["period_from"];
			$fields['date_to']          = $valid_data["period_to"];
			$fields['period_status_id'] = ATTENDANCE_PERIOD_PROCESSING;
			//marvin : batch if exist : start
			if($attendance_exist)
			{
				$count = count($attendance_exist) + 1;
				$fields['remarks'] = 'Batch ' . $count;
			}
			//marvin : batch if exist : end
			
			///MARVIN : INCLUDE EMPLOYEE EXCLUDED
			// $fields['excluded_employee'] = implode(',', $employee_list);

			if($action == ACTION_ADD)
			{	
				
				$table                    = $this->attendance->tbl_attendance_period_hdr;
				$attendance_period_hdr_id = $this->attendance->insert_general_data($table,$fields,TRUE);

				$audit_table[]  = $this->attendance->tbl_attendance_period_hdr;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	

				$audit_activity       = "New Attendance Period has been added.";
				
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field       = array("*") ;
				$table       = $this->attendance->tbl_attendance_period_hdr;
				$where       = array();
				$key         = $this->get_hash_key('attendance_period_hdr_id');
				$where[$key] = $id;
				$attendance  = $this->attendance->get_general_data($field, $table, $where, FALSE);

				$attendance_period_hdr_id = $attendance['attendance_period_hdr_id'];
				

				$where       = array();
				$key         = $this->get_hash_key('attendance_period_hdr_id');
				$where[$key] = $id;
				$table       = $this->attendance->tbl_attendance_period_hdr;
				$this->attendance->update_general_data($table,$fields,$where);

				$audit_table[]  = $this->attendance->tbl_attendance_period_hdr;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($attendance);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	

				$fields                             = array() ;
				$fields['attendance_period_hdr_id'] = NULL;

				$where       = array();
				$key         = $this->get_hash_key('attendance_period_hdr_id');
				$where[$key] = $id;
				$table       = $this->attendance->tbl_attendance_period_dtl;
				$this->attendance->update_general_data($table,$fields,$where);

					
				$audit_activity = "Attendance Period has been updated.";

				$message = $this->lang->line('data_updated');
			}
			// echo"<pre>";
			// print_r($attendance_period_hdr_id);
			// print_r($employee_list);
			// die();
			/*UPDATE ATTENDANCE PERIOD DETAIL TABLE*/	
			$this->attendance->update_attendance_period_dtl($attendance_period_hdr_id, $employee_list);

			/*UPDATE ATTENDANCE PERIOD STATUS*/
			$this->_update_attendance_dtl_status($attendance_period_hdr_id);
			
			//MARVIN : IGNORE UPDATE IF EDIT AND INCLUDE EMPLOYEE EXCLUDED
			// $this->_update_attendance_dtl_status($attendance_period_hdr_id, $action, $employee_list);

			/*INSERT ATTENDANCE PERIOD SUMMARY*/
			$this->update_attendance_period_summary($attendance_period_hdr_id);

			// Update employee_work_experiences service_lwop
			$this->_update_employee_work_experiences_lwop($attendance_period_hdr_id);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			$message = $this->lang->line('data_not_saved');
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

	private function _update_employee_work_experiences_lwop($attendance_period_hdr_id)
	{
		try
		{
			// Get lwop per attendance_status_id in attendance_period_dtl
			$dtl_lwop = $this->attendance->get_attendance_period_dtl_lwop($attendance_period_hdr_id);

			// Get lwop attendance_period_summary
			$summary_lwop = $this->attendance->get_attendance_period_summary_lwop($attendance_period_hdr_id);

			// Polulate dtl_lwop
			$dtl_arr = array();
			if ( ! empty( $dtl_lwop ) )
			{
				foreach ($dtl_lwop as $dtl)
					$dtl_arr[$dtl['employee_id']] = $dtl['total_hrs'];
			}

			if ( $summary_lwop )
			{
				$tbl_employee_work_experiences = $this->attendance->tbl_employee_work_experiences;

				foreach ($summary_lwop as $key => $summary) 
				{
					$employee_id  = ! empty($summary['employee_id']) ? $summary['employee_id'] : 0;
					// Get dtl lwop hours using employee_id index
					$dtl_lwop_hrs = ! empty($dtl_arr[$employee_id]) ? $dtl_arr[$employee_id] : 0;
					// Add summary and dtl lwop values
					$lwop_hrs   = $summary['lwop_ut_hr'] + $dtl_lwop_hrs;
					$lwop_min   = $summary['lwop_ut_min'];
					// Convert hours and mins in days			
					$total_hour = floor( $lwop_min / 60 ) + $lwop_hrs;
					$total_min  = ( $lwop_min % 60 );
					$total_days = ( $total_hour + ( $total_min / 60 ) ) / 8;
					// Set fields
					$fields 				   = array();
					$fields['service_lwop']    = $total_days;
					// Set where
					$where 					   = array();
					$where['employee_id'] 	   = $employee_id;
					$where['active_flag'] 	   = YES;
					// Update
					$this->attendance->update_general_data($tbl_employee_work_experiences, $fields, $where);
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function update_attendance_period_summary($attendance_period_hdr_id)
	{
		try
		{
			/*DELETE EXISTING RECORD*/
			$where       = array();
			$where['attendance_period_hdr_id'] = $attendance_period_hdr_id;
			
			$table       = $this->attendance->tbl_attendance_period_summary;
			$this->attendance->delete_general_data($table,$where);

			/*INSERT NEW RECORDS*/
			$this->attendance->insert_process_attendance_period_summary($attendance_period_hdr_id);

			/*INSERT ATTENDANCE PERIOD SUMMARY*/
			$this->update_leavecard_lwop($attendance_period_hdr_id);

			return TRUE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function update_leavecard_lwop($attendance_period_hdr_id)
	{
		try
		{
			
			$field                             = array("*") ;
			$table                             = $this->attendance->tbl_attendance_period_hdr;
			$where                             = array();
			$where['attendance_period_hdr_id'] = $attendance_period_hdr_id;
			$attendance_hdr                    = $this->attendance->get_general_data($field, $table, $where, FALSE);

			

			$start_month = $attendance_hdr["date_from"];
			$end_month   = $attendance_hdr["date_to"];
			$month_year  = date('mY',strtotime ( $start_month ));	
			$leave_type_id = $this->hash(LEAVE_TYPE_VACATION);
			$leave_credits         = $this->leaves->get_leave_monthly_credit_employess($leave_type_id,$month_year);
			
			if($leave_credits)
			{
				foreach ($leave_credits as $value) {
					$lates      = $this->leaves->get_employee_late($value['employee_id'],$month_year);
					$total_late = ($lates['total_late'] > 0) ? round($lates['total_late'],3):0;
					$total_und  = ($lates['total_und'] > 0) ? round($lates['total_und'],3):0;


					$x = 1.25;
					$y = $x / 30;
					$n = $value['total_lwop'];/*in days*/

					$credit_less_lwop = round($x-($y*$n),3);


					/*a*/$leave_balace   = $value["leave_balance"];
					/*b*/$leave_earned   = $x;
					/*c*/$lwop_deduction = round($y*$n,3);
					/*d*/$late_deduction = $total_late + $total_und;

					if(($leave_balace + $leave_earned) <= ($lwop_deduction + $late_deduction))
					{
						$new_card_lwop     = ($lwop_deduction + $late_deduction) - ($leave_balace + $leave_earned);
						if($new_card_lwop > 0)
						{
							$sixty_minutes   = 60;
							$deduction_munites = ($new_card_lwop * 8) * $sixty_minutes;
							
							$tardiness_hour = floor($deduction_munites/$sixty_minutes);
							$tardiness_min  = round($deduction_munites)%$sixty_minutes;

							$fields                             = array() ;
							$fields['lwop_ut_hr'] = ($tardiness_hour > 0) ? $tardiness_hour : 0;
							$fields['lwop_ut_min'] = ($tardiness_min > 0) ? $tardiness_min : 0;

							$table                             = $this->attendance->tbl_attendance_period_summary;

							$where                             = array();
							$where['attendance_period_hdr_id'] = $attendance_period_hdr_id;
							$where['employee_id']              = $value['employee_id'];
							$this->attendance->update_general_data($table,$fields,$where);
						}
						
					}
				}
			}
				
			
			
			return TRUE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	//MARVIN : IGNORE EDIT AND INCLUDE EMPLOYEE EXCLUDED
	// private function _update_attendance_dtl_status($attendance_period_hdr_id, $action, $employee_list = NULL)
	private function _update_attendance_dtl_status($attendance_period_hdr_id)
	{
		try
		{
			/*GET PREVIOUS DATA*/
			$field                             = array("*") ;
			$table                             = $this->attendance->tbl_attendance_period_dtl;
			$where                             = array();
			$where['attendance_period_hdr_id'] = $attendance_period_hdr_id;
			$attendance                        = $this->attendance->get_general_data($field, $table, $where, TRUE);

			//MARVIN : CONDITION FOR IGNORE EDIT AND INCLUDE EMPLOYEE EXCLUDED
			// if($action == ACTION_ADD)
			// {
				if($attendance)
				{
					foreach ($attendance as $key => $value) {

						$biometric_logs = modules::load('main/biometric_logs');
						$result         = $biometric_logs->check_attendance($value['employee_id'],$value['attendance_date']);
						if($value['attendance_status_id'] == OFFICIAL_BUSINESS)
						{
							$result['tardiness'] = 0;
							$result['undertime'] = 0;
							$result['tardiness_hour'] = 0;
							$result['tardiness_min'] = 0;
							$result['undertime_hour'] = 0;
							$result['undertime_min'] = 0;
							$result['working_hours'] = 8;
						}
						// $fields = array(
											// "working_hours"        => ($result['working_hours'] > 0 AND $result['working_hours'] < 100) ? round($result['working_hours'],3) : NULL,
											// "tardiness"            => ($result['tardiness'] > 0) ? $result['tardiness'] : NULL,
											// "tardiness_hr"         => ($result['tardiness_hour'] > 0) ? $result['tardiness_hour'] : NULL,
											// "tardiness_min"        => ($result['tardiness_min'] > 0) ? $result['tardiness_min'] : NULL,
											// "undertime"            => ($result['undertime'] > 0) ? $result['undertime'] : NULL,
											// "undertime_hr"         => ($result['undertime_hour'] > 0) ? $result['undertime_hour'] : NULL,
											// "undertime_min"        => ($result['undertime_min'] > 0) ? $result['undertime_min'] : NULL,
											// "attendance_status_id" => $result['status']
										// );
										
						$fields = array(
											"working_hours"        => ($result['working_hours'] > 0 AND $result['working_hours'] < 100) ? round($result['working_hours'],3) : $result['working_hours'],
											"tardiness"            => ($result['tardiness'] > 0) ? $result['tardiness'] : NULL,
											"tardiness_hr"         => ($result['tardiness_hour'] > 0) ? $result['tardiness_hour'] : NULL,
											"tardiness_min"        => ($result['tardiness_min'] > 0) ? $result['tardiness_min'] : NULL,
											"undertime"            => ($result['undertime'] > 0) ? $result['undertime'] : NULL,
											"undertime_hr"         => ($result['undertime_hour'] > 0) ? $result['undertime_hour'] : NULL,
											"undertime_min"        => ($result['undertime_min'] > 0) ? $result['undertime_min'] : NULL,
											"attendance_status_id" => $result['status']
										);
						
						$where                             = array();
						$where['attendance_period_dtl_id'] = $value['attendance_period_dtl_id'];
						$table                             = $this->attendance->tbl_attendance_period_dtl;
						$this->attendance->update_general_data($table,$fields,$where);
					}
				}
			// }
			// else
			// {
			// 	if(!is_null($employee_list))
			// 	{
			// 		foreach($attendance as $key => $value)
			// 		{
			// 			if(in_array($value['employee_id'], $employee_list))
			// 			{
			// 				$biometric_logs = modules::load('main/biometric_logs');
			// 				$result 		= $biometric_logs->check_attendance($value['employee_id'],$value['attendance_date']);
			// 				$fields = array(
			// 								"working_hours"        => ($result['working_hours'] > 0 AND $result['working_hours'] < 100) ? round($result['working_hours'],3) : $result['working_hours'],
			// 								"tardiness"            => ($result['tardiness'] > 0) ? $result['tardiness'] : NULL,
			// 								"tardiness_hr"         => ($result['tardiness_hour'] > 0) ? $result['tardiness_hour'] : NULL,
			// 								"tardiness_min"        => ($result['tardiness_min'] > 0) ? $result['tardiness_min'] : NULL,
			// 								"undertime"            => ($result['undertime'] > 0) ? $result['undertime'] : NULL,
			// 								"undertime_hr"         => ($result['undertime_hour'] > 0) ? $result['undertime_hour'] : NULL,
			// 								"undertime_min"        => ($result['undertime_min'] > 0) ? $result['undertime_min'] : NULL,
			// 								"attendance_status_id" => $result['status']
			// 							);
			// 				$where                             = array();
			// 				$where['attendance_period_dtl_id'] = $value['attendance_period_dtl_id'];
			// 				$table                             = $this->attendance->tbl_attendance_period_dtl;
			// 				$this->attendance->update_general_data($table,$fields,$where);
			// 			}
			// 		}
			// 	}
			// }

			return TRUE;
		}
		// catch(Exception $e)
		// {
			// throw $e;
		// }
		/* ============= MARVIN : START : ADD NEW ERROR ============= */
		catch(Exception $e)
		{
			$fields = array('*');
			$table = 'employee_personal_info';
			$where = array();
			$where['employee_id'] = $value['employee_id'];
			
			$rs = $this->attendance->get_general_data($fields, $table, $where);
			
			throw new Exception('An error occurred while saving the record. Please check the <b>Daily Time Record</b> of <b>' . $rs[0]['last_name'] . ', ' . $rs[0]['first_name'] . ' ' . $rs[0]['middle_name'] . '</b> dated <b>' . date('F d, Y', strtotime($value['attendance_date'])) . '</b>');
		}
		/* ============= MARVIN : END : ADD NEW ERROR ============= */
	}
	private function _validate_attendance_period($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields                 = array();
			
			$fields['payroll_type'] = "Payroll Type";
			$fields['period_from']  = "Period From";
			$fields['period_to']    = "Period To";
			$fields['period_to']    = "Period To";
			
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_attendance_period($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_attendance_period($params)
	{
		try
		{
			$validation['payroll_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Payroll Type',
					'max_len'	=> 3
			);
			$validation['period_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Period From'
			);
			$validation['period_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Period To'
			);
			//include excluded employee
			$validation['employee_list'] = array(
					'data_type' => 'string',
					'name'		=> 'Excluded Employee'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_attendance_period()
	{
		try
		{
			$flag        = 0;
			$params      = get_params();
			$url         = $params['param_1'];
			$url_explode = explode('/',$url);
			$action      = $url_explode[0];
			$id          = $url_explode[1];
			$token       = $url_explode[2];
			$salt        = $url_explode[3];
			$module      = $url_explode[4];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			Main_Model::beginTransaction();
			//GET PREVIOUS DATA
			$prev_data				= array() ;
			/*GET PREVIOUS DATA*/
			$field             = array("*") ;
			$table             = $this->attendance->tbl_attendance_period_hdr;
			$where             = array();
			$key               = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key]       = $id;
			$attendance_period = $this->attendance->get_general_data($field, $table, $where, FALSE);
			
			$fields                             = array() ;
			$fields['attendance_period_hdr_id'] = NULL;

			$where       = array();
			$key         = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key] = $id;
			$table       = $this->attendance->tbl_attendance_period_dtl;
			$this->attendance->update_general_data($table,$fields,$where);
			//DELETE DATA
			$where       = array();
			$key         = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key] = $id;
			
			$table       = $this->attendance->tbl_attendance_period_summary;
			$this->attendance->delete_general_data($table,$where);

			$table = $this->attendance->tbl_attendance_period_hdr;			
			$this->attendance->delete_general_data($table,$where);
			
			$audit_table[]  = $this->attendance->tbl_attendance_period_hdr;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($attendance_period);
			$curr_detail[]  = array();
			$audit_action[] = AUDIT_DELETE;
			$audit_activity = "Attendance Period has been deleted.";

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg  = $this->lang->line('data_deleted');
			$flag = 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$response = array(
			"flag"            => $flag,
			"msg"             => $msg,
			"reload"          => 'datatable',
			"table_id"        => 'table_attendance_periods',
			"path"            => PROJECT_MAIN . '/attendance_period/get_attendance_period_list/',
			"advanced_filter" => true
			);
		echo json_encode($response);
	}
	public function process_period_update()
	{
		try
		{
			
			$status     = FALSE;
			$message    = "";
			$reload_url = "";
			
			$params     = get_params();
			$action     = $params['action'];
			$token      = $params['token'];
			$salt       = $params['salt'];
			$id         = $params['id'];
			$module     = $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			Main_Model::beginTransaction();
			/*GET PREVIOUS DATA*/
			$field       = array("*") ;
			$table       = $this->attendance->tbl_attendance_period_hdr;
			$where       = array();
			$key         = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key] = $id;
			$attendance  = $this->attendance->get_general_data($field, $table, $where, FALSE);


			$this->attendance->get_process_tenure($id);
			
			$fields                     = array() ;
			$fields['period_status_id'] = ATTENDANCE_PERIOD_COMPLETED;

				
			$where       = array();
			$key         = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key] = $id;
			$table       = $this->attendance->tbl_attendance_period_hdr;
			$this->attendance->update_general_data($table,$fields,$where);

			$audit_table[]  = $this->attendance->tbl_attendance_period_hdr;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($attendance);
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;	

				
			$audit_activity = "Attendance Period has been processed.";
			$status         = true;
			$message        = $this->lang->line('data_updated');
			
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
	
		$data            = array();
		$data['status']  = $status;
		$data['message'] = $message;
	
		echo json_encode($data);
	}
	public function check_weekends($date_from = NULL,$date_to =NULL)
	{
		try
		{	
			$dates       = array();
			$date_from   = date('Y-m-d',strtotime ($date_from));
			$date_to     = date('Y-m-d',strtotime ($date_to));
			
			$active_date = $date_from;

			if($date_from <= $date_to )
			{
				while($active_date <= $date_to )
				{
					$day    = date('N',strtotime($active_date));

					if($day == '6' OR $day == '7')
					{
						$dates[] = $active_date;
					}
					$active_date = date('Y-m-d',strtotime('+1 day' , strtotime ( $active_date ) ) );					
				}				
			}
			

			return $dates;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function check_late($employee_id,$date)
	{
		try
		{	

			$day    = date('N',strtotime($date));

			if($day == '6' OR $day == '7')
			{
				return false;
			}
			else
			{
				$active_date    = date('Y-m-d',strtotime($date));


				$tables = array(
					'main'	=> array(
						'table'		=> $this->attendance->tbl_param_work_calendar,
						'alias'		=> 'A',
					),
					't1'	=> array(
						'table'		=> $this->attendance->tbl_param_holiday_types,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
					)
				);
				$where                 = array();
				$where['A.holiday_date'] = $active_date;
				$holiday               = $this->attendance->get_general_data(array("*"), $tables, $where, FALSE);
				if($holiday)
				{
					return $holiday;
				}
				else
				{
					return true;
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function check_date($date)
	{
		try
		{	

			$day    = date('N',strtotime($date));

			if($day == '6' OR $day == '7')
			{
				return false;
			}
			else
			{
				$active_date    = date('Y-m-d',strtotime($date));


				$tables = array(
					'main'	=> array(
						'table'		=> $this->attendance->tbl_param_work_calendar,
						'alias'		=> 'A',
					),
					't1'	=> array(
						'table'		=> $this->attendance->tbl_param_holiday_types,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
					)
				);
				$where                 = array();
				$where['A.holiday_date'] = $active_date;
				$holiday               = $this->attendance->get_general_data(array("*"), $tables, $where, FALSE);
				if($holiday)
				{
					return $holiday;
				}
				else
				{
					return true;
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}


/* End of file Attendance_period.php */
/* Location: ./application/modules/main/controllers/Attendance_period.php */