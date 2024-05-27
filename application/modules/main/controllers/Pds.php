<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds extends Main_Controller {

	private $log_user_id       =  '';
	private $log_user_roles    = array();
	
	private $permission_view   = FALSE;
	private $permission_edit   = FALSE;
	private $permission_delete = FALSE;
	
	private $permission_module = MODULE_; // TBD 

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_model', 'pds');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');

		//$this->permission_view   = $this->permission->check_permission($this->permission_module, ACTION_VIEW);
		//$this->permission_edit   = $this->permission->check_permission($this->permission_module, ACTION_EDIT);
		//$this->permission_delete = $this->permission->check_permission($this->permission_module, ACTION_DELETE);
	}
	
	public function index()
	{
		if($this->permission->check_permission(MODULE_HR_PERSONAL_DATA_SHEET, ACTION_VIEW))
		{
			$data      = array();
			$resources = array();
			
			$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATATABLE);
			$resources['datatable'][]	= array(
				'table_id' 			=> 'table_pds_list', 
				'path' 				=> 'main/pds/get_pds_list', 
				'advanced_filter' 	=> true
			);

			$resources['load_modal']    = array(
				'modal_upload_pds'  => array(
					'controller'	=> "Pds_upload",
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal_upload_pds',
					'multiple'		=> true,
					'height'		=> '350px',
					'size'			=> 'sm',
					'title'			=> "Upload PDS"
				),
				'modal_pds_upload_ptis_format'  => array(
					'controller'				=> "Pds_upload_ptis_format",
					'module'					=> PROJECT_MAIN,
					'method'					=> 'modal_pds_upload_ptis_format',
					'multiple'					=> true,
					'height'					=> '350px',
					'size'						=> 'sm',
					'title'						=> "Upload PDS (PTIS Format)"
				)
			);

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
			
			$user_office_scope = explode(',',$user_scopes['personal_data_sheets']);
			$where['A.office_id'] = array($user_office_scope, array('IN'));
			//end
			
			$data['office_list'] = $this->pds->get_general_data($fields, $tables, $where);

			if($module != MODULE_PERSONNEL_PORTAL)
			{
				/*BREADCRUMBS*/
				$breadcrumbs 			= array();
				$key					= "Human Resources"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/pds";
				$key					= "Personal Data Sheet"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/pds";
				set_breadcrumbs($breadcrumbs, TRUE);
			}

			$this->template->load('pds/pds', $data, $resources);
		}
		else
		{
			redirect(base_url() . 'unauthorized' , 'location');
		}
	}

	public function get_pds_list()
	{

		try
		{
			$params         = get_params();

			$module         = MODULE_HR_PERSONAL_DATA_SHEET;
			
			// $aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname", "", "E.name", "D.employment_status_name", "ifnull(B.employ_office_id,0) AS employ_office_id");
			// $bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',A.middle_name)", "E.name", "D.employment_status_name");
			
			/*
			//==== MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : START
			$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname", "", "E.name", "D.employment_status_name", "ifnull(B.employ_office_id,0) AS employ_office_id", "B.employ_start_date");
			$bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',A.middle_name)", "E.name", "D.employment_status_name", "B.employ_start_date");
			//==== MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : END
			*/
			// ====================== jendaigo : start : change name format ============= //
			$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'  OR A.middle_name IS NULL), '', CONCAT(' ', A.middle_name))) as fullname", "", "E.name", "D.employment_status_name", "ifnull(B.employ_office_id,0) AS employ_office_id", "B.employ_start_date");
			$bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', A.middle_name)))", "E.name", "D.employment_status_name", "B.employ_start_date");
			// ====================== jendaigo : end : change name format ============= //
			
			$pds_records    = $this->pds->get_employee_list($aColumns, $bColumns, $params, $module);
			$iTotal         = $this->pds->total_length();
			

			$iFilteredTotal = $this->pds->filtered_length($aColumns, $bColumns, $params, $module);
			

			// DEFAULT PARAMETERS TO BE PASSED 
			$output = array(

				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()

			);
			
			/*
			$permission_view = $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			*/
			$user_offices = $this->session->userdata('user_offices');

			// CHECKS IF EMPLOYEE HAS PENDING REQUESTS
			//ASIAGATE DISABLED
			// $employees_with_request = $this->pds->get_employees_with_request_list();
			// $employees_with_request_list   = explode(',', $employees_with_request['employee_id']);
		
			foreach ($pds_records as $aRow):
				$row        = array();
				
				$id         = $this->hash($aRow['employee_id']);			
				
				$salt       = gen_salt();
				$token_view = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view   = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit   = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				
				$row[]      = $aRow['agency_employee_id'];
				$row[]      = $aRow['fullname'];
				$row[]      = $aRow['name'];
				$row[]      = $aRow['employment_status_name'];
				
				//==== MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : START
				$row[]      = $aRow['employ_start_date'];
				//==== MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : END

				
				$action        = "<div class='table-actions'>";

				// if($permission_view)
				$action        .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped' onclick=\"content_form('pds/display_pds_info/".$url_view."', '".PROJECT_MAIN."')\"></a>";
				
				// if($permission_edit)

				$office_list   = explode(',', $user_offices[$module]);
				$office_list[] = 0;
				//ASIAGATE DISABLED
				// if(in_array($aRow['employ_office_id'],$office_list ))
				// {							
					// if(in_array($aRow['employee_id'],$employees_with_request_list )){
						// $action .= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped' onclick=\"notification_msg('error','Employee is currently editting his/her PDS.')\"></a>";
					// }
					// else
					// {
						// $action .= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped' onclick=\"content_form('pds/display_pds_info/".$url_edit."', '".PROJECT_MAIN."')\"></a>";
					// }
				// }
				
				//================= MARVIN : START : FIX EMPLOYEE WITH PENDIN REQUEST ===============================//
				if(in_array($aRow['employ_office_id'],$office_list ))
				{		
					$employee_with_request = $this->pds->get_employee_request($aRow['employee_id']);
					if($employee_with_request){
						$action .= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped' onclick=\"notification_msg('error','Employee is currently editting his/her PDS.')\"></a>";
					}
					else
					{
						$action .= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped' onclick=\"content_form('pds/display_pds_info/".$url_edit."', '".PROJECT_MAIN."')\"></a>";
					}
				}
				//================= MARVIN : END : FIX EMPLOYEE WITH PENDIN REQUEST ===============================//
				
				// $delete_action = 'content_delete("record", "'.$url_delete.'")';
				// if($permission_delete)

				// $action        .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";

				$action        .= "</div>";
				
				$row[]         = $action;
					
				$output['aaData'][] = $row;
			endforeach;	
		}
		catch (Exception $e)
		{
			$output = array(

				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()

			);
		}

		echo json_encode( $output );
	}

	public function display_pds_info($action, $id, $token, $salt, $module)
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
			$resources['load_css'] 	= array(CSS_LABELAUTY);
			$resources['load_js'  ] = array(JS_LABELAUTY);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if($module == MODULE_PERSONNEL_PORTAL)
			{
				
				$requests 				= $this->pds->check_request($id);
				if($requests )
				{
					$salt		= gen_salt();
					$token	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);

					$data['action']			= ACTION_VIEW;
					$data['id']				= $id;
					$data['salt']			= $salt;
					$data['token']			= $token;
					$data['module']			= $module;
				}
			}
			if($action != ACTION_ADD)
			{
				//GET CITIZENSHIPS
				// $field                   = array("*") ;
				// $table                   = $this->pds->tbl_employee_personal_info;
				// $where                   = array();
				// $key                     = $this->get_hash_key('employee_id');
				// $where[$key]             = $id;
				// $personal_info           = $this->pds->get_general_data($field, $table, $where, FALSE);
				// //$data['personal_info'] = $personal_info;
				$employee_ids	   = $this->pds->get_general_data(('employee_id'), $this->pds->tbl_employee_personal_info);
				foreach($employee_ids as $employee_id)
				{
					$prep_string = "%$".$employee_id['employee_id']."%$";
					$hashed_id = md5($prep_string);
					if($hashed_id == $id)
					{
						$employee_id = $employee_id['employee_id'];
						break;
					}
				}
				$data['personal_info']    = $this->pds->get_employee_info($employee_id);

				if($personal_info['job_order_flag'] == 'Y')
				$data['job_order_flag'] 	= $personal_info['job_order_flag'];
			}


			if($module == MODULE_PERSONNEL_PORTAL)
			{
				/*BREADCRUMBS*/
				$breadcrumbs 			= array();
				$key					= "My Portal"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/pds/display_pds_info/".$action."/".$id."/".$token."/".$salt."/".$module;
				$key					= "Personal Information"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/display_pds_info/".$action."/".$id."/".$token."/".$salt."/".$module;
				set_breadcrumbs($breadcrumbs, TRUE);
			}
			else
			{
				/*BREADCRUMBS*/
				$breadcrumbs 			= array();
				$key					= "Personal Information"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/display_pds_info/".$action."/".$id."/".$token."/".$salt."/".$module;
				set_breadcrumbs($breadcrumbs, FALSE);
			}

			$this->template->load('pds/display_pds_info', $data, $resources);
		}
		catch (PDOException $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
	}

	public function get_tab($form, $action, $id, $token, $salt, $module)
	{

		try
		{
			$data 					= array();

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$this->session->set_userdata("pds_employee_id", $id);
			$this->session->set_userdata("pds_module", $module);
			$this->session->set_userdata("pds_action", $action);

			switch ($form) 
			{

				case PDS_RECORD_CHANGES:
					//GET PERVEIOUS RECORD

					$requests = $this->pds->check_request($id);
					if($requests)
					{
						$data['requests'] = $this->pds->count_pending_requests($id, $requests['request_id']);
					}
					else
					{
						$data['requests'] = $this->pds->count_pending_requests($id,NULL);
					}
					
					$data['nav_page'] = PDS_RECORD_CHANGES;
					$view_form        = $form;
				break;

				case PDS_CHECK_LIST:
					
					
					$field                       = array("*") ;
					$table                       = $this->pds->tbl_param_check_list;
					$where                       = array();
					$where['check_list_type_id'] = CHECKLIST_PDS;
					$where['active_flag']        = YES;
					$data['check_list']          = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                       = array("*") ;
					$table                       = $this->pds->tbl_employee_pds_checklist;
					$where                       = array();
					$key                         = $this->get_hash_key('employee_id');
					$where[$key]                 = $id;
					$emp_list                    = $this->pds->get_general_data($field, $table, $where, TRUE);
					$data['emp_checklist']       = array();
					
					if($emp_list)
					{
						foreach($emp_list as $list)
						{
							$data['emp_checklist'][] = $list['check_list_id'];
						}
					}
					
					$data['nav_page']			= PDS_CHECK_LIST;
					$view_form = $form;
				break;
			}
			
			
			$this->load->view('pds/tabs/'.$view_form, $data);
			$this->load_resources->get_resource($resources);
		
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
	
	
	// public function modal_skill_info()
	// {
	// 	try
	// 	{
	// 		$data = array();
			
	// 		$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
	// 		$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

	// 		$this->load->view('pds/modals/modal_skill_info', $data);
	// 		$this->load_resources->get_resource($resources);
			
	// 	}
	// 	catch (PDOException $e)
	// 	{
	// 		echo $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// 	catch (Exception $e)
	// 	{
	// 		echo $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// }
	// public function modal_recognition_info()
	// {
	// 	try
	// 	{
	// 		$data = array();
			
	// 		$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
	// 		$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

	// 		$this->load->view('pds/modals/modal_recognition_info', $data);
	// 		$this->load_resources->get_resource($resources);
			
	// 	}
	// 	catch (PDOException $e)
	// 	{
	// 		echo $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// 	catch (Exception $e)
	// 	{
	// 		echo $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// }
	// public function modal_membership_info()
	// {
	// 	try
	// 	{
	// 		$data = array();
			
	// 		$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
	// 		$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

	// 		$this->load->view('pds/modals/modal_membership_info', $data);
	// 		$this->load_resources->get_resource($resources);
			
	// 	}
	// 	catch (PDOException $e)
	// 	{
	// 		echo $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// 	catch (Exception $e)
	// 	{
	// 		echo $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// }
	
	/*PROCESS PDS CHECKLIST*/
	public function process_checklist()
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
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_checklist($params);
			
			Main_Model::beginTransaction();

			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_personal_info;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $id;
			$personal_info 					= $this->pds->get_general_data($field, $table, $where, FALSE);

			$field                       = array("*") ;
			$table                       = $this->pds->tbl_employee_pds_checklist;
			$where                       = array();
			$key                         = $this->get_hash_key('employee_id');
			$where[$key]                 = $id;
			$previous            		= $this->pds->get_general_data($field, $table, $where, TRUE);

		
			//DELETE OLD DATA
			$where					= array();
			$key 					= $this->get_hash_key('employee_id');
			$where[$key]			= $id;
			$table 					= $this->pds->tbl_employee_pds_checklist;
			
			$this->pds->delete_general_data($table,$where);

			$fields	= array();
			$where	= array();
			if($valid_data['checklist'])
			{
				foreach($valid_data['checklist'] as $checklist)
				{
					
					$fields[]	= array(
									'employee_id'			=> $personal_info["employee_id"],
									'check_list_id'			=> $checklist
									);
				}
				$table	= $this->pds->tbl_employee_pds_checklist;
				$this->pds->insert_general_data($table,$fields,FALSE);
			}
			
			$audit_table[]			= $this->pds->tbl_employee_pds_checklist;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= $previous;
			$curr_detail[]			= $fields;
			$audit_action[] 		= AUDIT_UPDATE;	

			$activity 				= "Employee PDS checklist %s has been updated.";
			$audit_activity 		= sprintf($activity, "");

			$status = true;
			$message = $this->lang->line('data_updated');

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			//$message = json_encode($declaration);
			Main_Model::commit();
			
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
	private function _validate_checklist($params)
	{
		try
		{
			$validation['checklist'] = array(
					'data_type' => 'digit',
					'name'		=> 'Checklist',
					'max_len'	=> 11
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function modal_pds_upload()
	{
		try
		{
			$data = array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$this->load->view('pds/modals/modal_pds_upload', $data);
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
	
	public function print_pds($action, $id, $token, $salt, $module, $name= 'Print PDS')
	{
		try
		{
			
			$data = array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			ini_set('memory_limit', '512M');

			// GET CITIZENSHIP BASIS
			$field                       	= array("sys_param_name", "sys_param_value");
			$table					 		= $this->pds->db_core.".".$this->pds->tbl_sys_param;
			$where                       	= array();
			$where['sys_param_type'] 		= 'CITIZENSHIP_BASIS';
			$citizenship_basis      		= $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['citizenship_basis'] 		= $citizenship_basis;

			$data['personal_info']	= $this->pds->get_pds_personal_info($id);

			$field                               = array("*");
			$table                               = $this->pds->db_core.'.'.$this->pds->tbl_param_genders;
			$where                               = array();
			$data['per_params']['gender']        = $this->pds->get_general_data($field, $table, $where, TRUE);

			$field                               = array("*") ;
			$table                               = $this->pds->tbl_param_civil_status;
			$where                               = array();
			$civil_status  				 		 = $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['civil_status']  				 = $civil_status;

			//GET MUNICIPALITY PROVINCE AND REGION 
			$tables_address = array(
				'main'	=> array(
					'table'		=> $this->pds->tbl_employee_addresses,
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_barangays,
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.barangay_code = B.barangay_code',
				),
				't2'	=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_municities,
					'alias'		=> 'C',
					'type'		=> 'left join',
					'condition'	=> 'A.municity_code = C.municity_code',
				),
				't3'	=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_provinces,
					'alias'		=> 'D',
					'type'		=> 'left join',
					'condition'	=> 'A.province_code = D.province_code',
				),
				't4'	=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_regions,
					'alias'		=> 'E',
					'type'		=> 'left join',
					'condition'	=> 'A.region_code = E.region_code',
				)
			);
			$field = array (
					"A.address_type_id, A.postal_number, A.address_value, B.barangay_name, C.municity_name, D.province_name, E.region_name, E.region_code, D.province_code" 
			);
			$where                       	  = array();
			$key                         	  = $this->get_hash_key('A.employee_id');
			$where[$key]                 	  = $id;
			$where['address_type_id'] 	 	  = RESIDENTIAL_ADDRESS;
			$residential_address_info         = $this->pds->get_general_data($field, $tables_address, $where, FALSE);
			if($residential_address_info['region_code'] == '13')
			{
				$residential_address_info['province_name'] = "METRO MANILA";
			}
			if($residential_address_info['province_code'] == '13806')
			{
				$residential_address_info['municity_name'] = $residential_address_info['municity_name'] . ",CITY OF MANILA";
			}
			
			$data['residential_address_info'] = $residential_address_info;
			
			$residential_address_value 		  = $residential_address_info['address_value'];
			$address_value_parts 			  = explode('|', $residential_address_value);
			$data['residential_house_no'] 	  = $address_value_parts[0]; 
			$data['residential_street'] 	  = $address_value_parts[1]; 
			$data['residential_subdivision']  = $address_value_parts[2]; 

			$where                       	  = array();
			$key                         	  = $this->get_hash_key('A.employee_id');
			$where[$key]                 	  = $id;
			$where['address_type_id'] 	 	  = PERMANENT_ADDRESS;
			$permanent_address_info           = $this->pds->get_general_data($field, $tables_address, $where, FALSE);

			if($permanent_address_info['region_code'] == '13')
			{
				$permanent_address_info['province_name'] = "METRO MANILA";
			}
			if($permanent_address_info['province_code'] == '13806')
			{
				$permanent_address_info['municity_name'] = $permanent_address_info['municity_name'] . ",CITY OF MANILA";
			}
			$data['permanent_address_info']   = $permanent_address_info;
			$permanent_address_value 		  = $permanent_address_info['address_value'];
			$permanent_value_parts 			  = explode('|', $permanent_address_value);
			$data['permanent_house_no'] 	  = $permanent_value_parts[0]; 
			$data['permanent_street'] 	  	  = $permanent_value_parts[1]; 
			$data['permanent_subdivision']    = $permanent_value_parts[2]; 
			// GET IDENTIFICATION TYPE FORMAT
			$field                       	= array("identification_type_id, format") ;
			$table                       	= $this->pds->tbl_param_identification_types;
			$where                       	= array();
			$where['builtin_flag']     		= 'Y';
			$identification_format      	= $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['identification_format'] 	= $identification_format;

			//GET IDENTIFICATIONS
			$table = array(
				'main' => array(
					'table' => $this->pds->tbl_employee_identifications,
					'alias' => 'A'
				),
				't2' => array(
					'table' => $this->pds->tbl_param_identification_types,
					'alias' => 'B',
					'type' => 'JOIN',
					'condition' => 'B.identification_type_id = A.identification_type_id'
				)
			);
			$where                               = array();
			$key                                 = $this->get_hash_key('A.employee_id');
			$where[$key]                         = $id;
			$where['B.builtin_flag']	         = 'Y';
			$order_by 							 = array('B.identification_type_id' => 'ASC');
			$identification_info                 = $this->pds->get_general_data(array("*"), $table, $where, TRUE, $order_by);

			$data['identification_info']         = $identification_info;			

			//GET CONTACTS
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_contacts;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$contact_info                        = $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['contact_info']                = $contact_info;
				
			/*FAMILY BACKGROUND DATA*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_SPOUSE;
			$data['spouse']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_FATHER;
			$data['father']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_MOTHER;
			$data['mother']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			
			$field                               = array("*","CONCAT(relation_first_name, ' ',relation_last_name) as name", "DATE_FORMAT(relation_birth_date, '%m/%d/%Y') AS relation_birth_date") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_CHILD;
			$data['child']                       = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//EDUCATIONAL BACKGROUND
			$data['educ_details']                = $this->pds->get_pds_education($id);
			
			$where                               = array();
			$where['active_flag']                = 'Y';
			$data['educ_list']                   = $this->pds->get_general_data(array('*'), $this->pds->tbl_param_educational_levels);
			
			$data['govt_exam']                   = $this->pds->get_pds_eligibility($id);
			$data['work_exp']                    = $this->pds->get_pds_work_experience($id);
			
			$field                               = array("*", "DATE_FORMAT(volunteer_start_date, '%m/%d/%Y') AS volunteer_start_date", "DATE_FORMAT(volunteer_end_date, '%m/%d/%Y') AS volunteer_end_date") ;
			$table                               = $this->pds->tbl_employee_voluntary_works;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['vol_details']                 = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			// $field                               = array("*", "DATE_FORMAT(training_start_date, '%m/%d/%Y') AS training_start_date", "DATE_FORMAT(training_end_date, '%m/%d/%Y') AS training_end_date") ;
			// ====================== jendaigo : start : include reference for sorting ============= //
			$field                               = array("*", "training_start_date AS sort_start_date", "DATE_FORMAT(training_start_date, '%m/%d/%Y') AS training_start_date", "DATE_FORMAT(training_end_date, '%m/%d/%Y') AS training_end_date") ;
			// ====================== jendaigo : end : include reference for sorting ============= //
			
			$table                               = $this->pds->tbl_employee_trainings;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			
			// $data['train_details']               = $this->pds->get_general_data($field, $table, $where, TRUE);
			// ====================== jendaigo : start : sort based on straining_start_date ============= //
			$order_by 				   			 = array('sort_start_date' => 'DESC');
			$data['train_details']               = $this->pds->get_general_data($field, $table, $where, TRUE, $order_by);
			// ====================== jendaigo : end : sort based on straining_start_date ============= //
			
			/*OTHER INFORMATION DATA*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_other_info;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['other_info_type_id']         = OTHER_SKILLS;
			$data['other_params']['skills_list'] = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_other_info;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['other_info_type_id']         = OTHER_RECOGNITION;
			$data['other_params']['recog_list']  = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_other_info;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['other_info_type_id']         = OTHER_ASSOCIATION;
			$data['other_params']['member_list'] = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			
			$data['questions']                   = $this->pds->get_pds_questions($id);
			/*START QUESTIONS*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_param_questions;
			$where                               = array();
			$where['parent_question_id']         = "IS NULL";
			$data['parent_questions']            = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_param_questions;
			$where                               = array();
			$where['parent_question_flag']       = "N";
			$data['child_questions']             = $this->pds->get_general_data($field, $table, $where, TRUE);
			/*GET EMPLOYEE PREVIOUS ANSWERS*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_questions;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['answers']                     = $this->pds->get_general_data($field, $table, $where, TRUE);
			/*END QUESTIONS*/
			
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_references;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['refn_details']                = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*", "DATE_FORMAT(issued_date, '%m/%d/%Y') issued_date") ;
			$table                               = $this->pds->tbl_employee_declaration;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['declaration']                 = $this->pds->get_general_data($field, $table, $where, FALSE);

			$this->load->library('pdf');

			$pdf = $this->pdf->load('utf-8', array(216,330), 10, 10, 7, 7, 10, 10);
			$html = $this->load->view('forms/reports/pds_report', $data, TRUE);
			
			$pdf->WriteHTML($html);
			$pdf->Output();
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
}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */