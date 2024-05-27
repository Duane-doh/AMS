<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deductions extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();


		
		$this->load->model('deduction_model', 'deductions');


		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	
	public function index()
	{
		$data =  array();
		$resources = array();


		$data['action']			= ACTION_VIEW;
		$data['id']				= DEFAULT_ID;
		$data['salt']			= $salt;
		$data['token']			= $token; 
		$data['module']			= $module;


		$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATATABLE);

		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/Deductions";
		$key					= "Deductions"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/Deductions";
		set_breadcrumbs($breadcrumbs, TRUE);
		
		$fields = array('A.office_id','B.name AS office_name');
		$tables = array(
			'main' => array(
				'table' => $this->deductions->tbl_param_offices,
				'alias' => 'A'
			),
			't1'   => array(
				'table' => $this->deductions->db_core . '.' . $this->deductions->tbl_organizations,
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
		
		$user_office_scope = explode(',',$user_scopes['deductions']);
		$where['A.office_id'] = array($user_office_scope, array('IN'));
		//end
		
		$data['office_list'] = $this->deductions->get_deduction_data($fields, $tables, $where);

		$this->template->load('deductions/deductions_tabs', $data, $resources);
		
	}

	public function get_tab($form)
	{

		try
		{
			$data 					= array();
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			

			switch ($form)
			{
				case 'tab_deduction_employee_list':
					$resources['datatable'][]	= array('table_id' => 'table_deduction_employee', 'path' => 'main/deductions/get_deduction_employee_list', 'advanced_filter' => true);
				break;

				case 'tab_deduction_type_list':
					$resources['datatable'][]	= array('table_id' => 'table_deduction_type', 'path' => 'main/deductions/get_deduction_type_list', 'advanced_filter' => true);
					$resources['load_modal'] = array(
					'modal_deduction_type' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_deduction_type',
							'multiple'		=> true,
							'height'		=> '460px',
							'size'			=> 'md',
							'title'			=> 'Deduction Type'
						)
					);
				break;
	
			}

			$this->load->view('deductions/tabs/'.$form, $data);
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

	
	
	public function get_statutory_info($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{	
		try
		{

			$data 					= array();
			$resources['load_css'] 	= array();
			$resources['load_js'  ] = array();

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$url_security 			= $action."/".$employee_id."/".$token."/".$salt."/".$module;


			//GET PDS INFORMATION
			$data['personal_info']    = $this->deductions->get_employee_info($employee_id);

			
			
			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			/*BREADCRUMBS*/
		
			if ($module == MODULE_PERSONNEL_PORTAL) 
			{
				$breadcrumbs 			= array();
				$key				= "My Portal";
				$breadcrumbs[$key]		= PROJECT_MAIN."/Deductions";
				$key					= "Deductions"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/Deductions";
				set_breadcrumbs($breadcrumbs, TRUE);
			} else {
				/*BREADCRUMBS*/
				$breadcrumbs 			= array();
				$key					= "Employee Deductions"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/deductions/get_statutory_info/".$url_security;
				set_breadcrumbs($breadcrumbs, FALSE);
			}
			// GET CURRENT USER'S GRANTED OFFICES
			$user_offices = $this->session->userdata('user_offices');
			$user_offices = $user_offices[$module];
			// COMPARE IF THE SELECTED EMPLOYEE'S OFFICE IS IN THE LIST
			$data['has_permission'] = (in_array($data['personal_info']['employ_office_id'], explode(',', $user_offices)));

			$this->template->load('deductions/display_statutory_info', $data, $resources);		
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

	public function get_tab_employee_deductions($form, $action = NULL, $employee_id = NULL,  $token = NULL, $salt = NULL, $module = NULL, $has_permission = FALSE)
	{

		try
		{
			$data 					= array();
			$resources['load_css']	= array(CSS_DATATABLE, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATATABLE, JS_SELECTIZE);

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['has_permission'] = $has_permission;

			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$post_data = array(
					'employee_id'    => $employee_id,
					'module'         => $module,
					'action_id'      => $action,
					'has_permission' => $has_permission
		 	);

			switch ($form)
			{
				case 'statutory_details':
					
					$resources['datatable'][] = array('table_id' => 'table_statutory_details', 'path' => 'main/deductions/get_statutory_details', 'advanced_filter' => TRUE, 'post_data' => json_encode($post_data));
					
					$resources['load_modal'] = array(
						
							'modal_employee_statutory' => array(
								'controller' => __CLASS__,
								'module'     => PROJECT_MAIN,
								'method'     => 'modal_employee_statutory',
								'multiple'   => true,
								'height'     => '400px',
								'size'       => 'sm',
								'title'      => 'Statutory Deduction'
							)
					);

					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_employee_statutory',
						PROJECT_MAIN
					);
					

					$view_form = $form;
					break;

				case 'other_deductions':
					
					$resources['datatable'][] = array('table_id' => 'table_other_deduction', 'path' => 'main/deductions/get_other_deduction_list', 'advanced_filter' => TRUE, 'post_data' => json_encode($post_data));
					$resources['load_modal'] = array(
		
							'modal_employee_other_deductions' => array(
								'controller' => __CLASS__,
								'module'     => PROJECT_MAIN,
								'method'     => 'modal_employee_other_deductions',
								'multiple'   => true,
								// 'height'     => '400px',
								// 'size'       => 'sm',
								// ====================== jendaigo : start : change height and width ============= //
								'height'     => '500px',
								'size'       => 'xl',
								// ====================== jendaigo : start : change height and width ============= //
								'title'      => 'Other Deduction'
							)
					);


					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_employee_other_deductions',
						PROJECT_MAIN
					);
					

					$view_form = $form;
				break;
	
				case 'request_certificate':

					//GET PERVEIOUS RECORD
					$field                         = array("*") ;
					$table                         = $this->deductions->tbl_param_request_sub_types;
					$where                         = array();
					$where['request_type_id']      = REQUEST_CERTIFICATE_CONTRIBUTION;
					$data['certificate_type_list'] = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
				
					
					$view_form = $form;
					break;

				case 'supporting_documents':
					$resources['load_css']    = array(CSS_DATATABLE);
					$resources['load_js']     = array(JS_DATATABLE);
					$resources['datatable'][] = array('table_id' => 'table_supporting_documents', 'path' => 'main/deductions/get_supporting_documents_list', 'advanced_filter' => TRUE);
					
					$view_form = $form;
				break;

				case 'deduction_history':
					$resources['load_css']    = array(CSS_DATATABLE);
					$resources['load_js']     = array(JS_DATATABLE);
					$resources['datatable'][] = array('table_id' => 'table_deduction_history', 'path' => 'main/deductions/get_deduction_history_list', 'advanced_filter' => TRUE, 'post_data' => json_encode($post_data));
				
				$view_form = $form;
				break;
	
			}
	
			$this->load->view('deductions/tabs/'.$form, $data);
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



	//DEDUCTION LIST 
	public function get_deduction_type_list()
	{

		try
		{
			$params   = get_params();

			$aColumns = array("deduction_id","deduction_name");
			$bColumns = array("deduction_name");
		
			$deduction_types = $this->deductions->get_deduction_list($aColumns, $bColumns, $params);
// 			$iTotal          = $this->deductions->total_length($params);
			$iFilteredTotal  = $this->deductions->filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

			$module = MODULE_HR_DEDUCTIONS;

			foreach ($deduction_types as $aRow):
				$row = array();
				
				$id         = $this->hash($aRow['deduction_id']);
				$salt       = gen_salt();
				$token_view = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);

				$url_view   = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit   = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;

				$row[] = strtoupper($aRow['deduction_name']);
				
				$action = "<div class='table-actions'>";
				// if($this->permission ->check_permission(MODULE_USER, ACTION_EDIT))
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_deduction_type' onclick=\"modal_deduction_type_init('".$url_view."')\" data-delay='50'></a>";
				
				if($aRow['deduction_id'] != DEDUC_OVERPAY_JO) //jendaigo : remove edit button for deduction overpay
				$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_deduction_type' onclick=\"modal_deduction_type_init('".$url_edit."')\" data-delay='50'></a>";
				
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


	public function get_deduction_personnel_list($action, $deduction_id, $token, $salt, $module)
	{
		try
		{
			$params                = get_params();

			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY);

			$resources = $this->load_resources->get_resource($resources, TRUE);
			
			// $aColumns = array("ED.deduction_id", "PI.agency_employee_id", "CONCAT(PI.last_name,\" \",ifnull(PI.ext_name,''),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.') as fullname", "WE.employ_office_name", "ED.start_date");
			// $bColumns = array("PI.agency_employee_id", "CONCAT(PI.last_name,\" \",ifnull(PI.ext_name,''),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.')", "WE.employ_office_name",  "ED.start_date");
			
			// ====================== jendaigo : start : change name format ============= //
			$aColumns = array("ED.deduction_id", "PI.agency_employee_id", "CONCAT(PI.last_name, ', ', PI.first_name, IF(PI.ext_name='', '', CONCAT(' ', PI.ext_name)), IF((PI.middle_name='NA' OR PI.middle_name='N/A' OR PI.middle_name='-' OR PI.middle_name='/'), '', CONCAT(' ', LEFT(PI.middle_name, 1), '. '))) as fullname", "WE.employ_office_name", "ED.start_date");
			$bColumns = array("PI.agency_employee_id", "CONCAT(PI.last_name, ', ', PI.first_name, IF(PI.ext_name='', '', CONCAT(' ', PI.ext_name)), IF((PI.middle_name='NA' OR PI.middle_name='N/A' OR PI.middle_name='-' OR PI.middle_name='/'), '', CONCAT(' ', LEFT(PI.middle_name, 1), '. ')))", "WE.employ_office_name",  "ED.start_date");
			// ====================== jendaigo : end : change name format ============= //
			
			$personnel_list = $this->deductions->get_deduction_personnel_list($aColumns, $bColumns, $params);
			$iTotal         = $this->deductions->total_length();
			$iFilteredTotal = $this->deductions->filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module = MODULE_HR_DEDUCTIONS;



			foreach ($personnel_list as $aRow):
				$cnt++;
				$row   = array();
				
				$row[] = $aRow['agency_employee_id'];
				$row[] = ucwords($aRow['fullname']);
				$row[] = strtoupper($aRow['employ_office_name']);
				$row[] = '<center>' . format_date($aRow['start_date']) . '</center>';	
				$row[] = '';
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


	

	public function get_deduction_history_list()
	{
	
		try
		{
			$params = get_params();
			$aColumns          = array("PC.deduction_name, DATE_FORMAT(PD.effective_date, '%Y/%m/%d') as effective_date, PD.amount, PD.deduction_id");
			$bColumns          = array("PC.deduction_name", "DATE_FORMAT(PD.effective_date, '%Y/%m/%d')", "PD.amount");
			$table             = $this->deductions->tbl_payout_header;
			$where             = array();
			$deduction_history = $this->deductions->get_deduction_history_list($aColumns, $bColumns, $params, $table, $where);
			// $iTotal   			= $this->deductions->get_deduction_history_list(array("COUNT(DISTINCT(PC.deduction_id)) as count" ), $bColumns, $params, $table, $where, false);
			/*$table = array(
				'main' => array(
					'table' => $this->deductions->tbl_employee_deductions,
					'alias' => 'A'
				),
				't1' => array(
					'table'     => $this->deductions->tbl_param_deductions,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.deduction_id=B.deduction_id'
				)
			);
			$iTotal         = $this->deductions->total_length($table, array($this->get_hash_key('A.employee_id') => $params['employee_id']);*/
			$iTotal         = $this->deductions->total_length();
			$iFilteredTotal = $this->deductions->filtered_length($aColumns, $bColumns, $params);
				
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal['cnt'],
				"iTotalDisplayRecords" => count($iFilteredTotal['cnt']),
				"aaData"               => array()
			);
			$cnt = 0;

			foreach ($deduction_history as $aRow):
				$cnt++;
				$row = array();
				$action = "";

			
			
				$deduction_id  = $aRow["deduction_id"];
				$id            = $this->hash ($deduction_id);
				$salt          = gen_salt();
				$token_edit    = in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_delete  = in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action   = ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete    = ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action = 'content_delete("deduction", "'.$url_delete.'")';
					
				$row[] = strtoupper($aRow['deduction_name']);
				$row[] = $aRow['effective_date'];	
				$row[] = '<p class="m-n right">&#8369; ' . number_format($aRow['amount'],2) . '</p>';


				if($cnt == count($deduction_history)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS,JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}

				$action = "<div class='table-actions'>";
				
				
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


	public function get_employee_deduction_type($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{

			$data 					= array();
			$resources['load_css'] 	= array(CSS_SELECTIZE);
			$resources['load_js'  ] = array(JS_SELECTIZE);

			$data['action']      = $action;
			$data['employee_id'] = $employee_id;
			$data['salt']        = $salt;
			$data['token']       = $token;
			$data['module']      = $module;


			//GET PDS INFORMATION
			$field                   = array("*") ;
			$table                   = $this->deductions->tbl_employee_personal_info;
			$key                     = $this->get_hash_key('employee_id');
			$where                   = array($key => $employee_id);			
			$data['pds_information'] = $this->deductions->get_deductions_data($field, $table, $where, FALSE);

			
			
			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$this->template->load('deductions/deduction_type_employees', $data, $resources);
		
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




	public function get_statutory_details()
	{
	
		try
		{		

			$params         = get_params();
			$has_permission = $params['has_permission'];
			$aColumns       = array("ED.employee_deduction_id, ED.deduction_id, PD.deduction_name, DATE_FORMAT(ED.start_date, '%Y/%m/%d') as start_date, PF.frequency_name");
			$bColumns       = array("PD.deduction_name", "DATE_FORMAT(ED.start_date, '%Y/%m/%d')", "PF.frequency_name");
		
			$statutory_list = $this->deductions->get_statutory_list($aColumns, $bColumns, $params);
			$table = array(
				'main' => array(
					'table' => $this->deductions->tbl_employee_deductions,
					'alias' => 'A'
				),
				't1' => array(
					'table'     => $this->deductions->tbl_param_deductions,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.deduction_id=B.deduction_id'
				)
			);
			$iFilteredTotal = $this->deductions->filtered_length($aColumns, $bColumns, $params);
			$iTotal         = $this->deductions->total_length($table, array($this->get_hash_key('A.employee_id') => $params['employee_id'], 'B.statutory_flag' => 'Y'));

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"iTotalRecords"        => $iTotal["cnt"],
				"aaData"               => array()
			);

			$module      = $params['module'];
			$employee_id = $params['employee_id'];

			foreach ($statutory_list as $aRow):
				$cnt++;
				$row = array();
				
				$row[] =  strtoupper($aRow['deduction_name']);	
				$row[] =  '<center>' . format_date($aRow['start_date']) . '</center>';	
				$row[] =  strtoupper($aRow['frequency_name']);

				$id           = $this->hash($aRow['employee_deduction_id']);
				$deduction_id = $this->hash($aRow['deduction_id']);

				$salt			= gen_salt();
				
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete 	= in_salt($id  . '/' . ACTION_DELETE. '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$employee_id ."/".$token_view."/".$salt."/".$module."/".$id."/".$deduction_id;
				$url_edit 		= ACTION_EDIT."/".$employee_id ."/".$token_edit."/".$salt."/".$module."/".$id."/".$deduction_id."/".$has_permission;
				$url_delete 	= ACTION_DELETE."/".$employee_id ."/".$token_delete."/".$salt."/".$module."/".$id."/".$deduction_id."/".$has_permission;
				$delete_action	= 'content_delete("Employee Statutory Deductions", "'.$url_delete.'")';

				$action = "<div class='table-actions'>";

				$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_employee_statutory' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_employee_statutory_init('".$url_view."')\"></a>";
				
				if($module == MODULE_HR_DEDUCTIONS && $params['action_id'] != ACTION_VIEW && $params['has_permission']):
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_employee_statutory' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employee_statutory_init('".$url_edit ."')\"></a>";
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				endif;
				$action .= "</div>";

				if($cnt == $total)
				
				$action .= $resources;
				
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

	
	public function get_other_deduction_list()
	{
	
		try
		{	
			$params         = get_params();	

			$has_permission = $params['has_permission'];

			$aColumns = array("ED.employee_deduction_id, PD.deduction_name, DATE_FORMAT(ED.start_date, '%Y/%m/%d') as start_date, PD.deduction_type_flag, PF.frequency_name");
			$bColumns = array("PD.deduction_name", "DATE_FORMAT(ED.start_date, '%Y/%m/%d')",  "PD.deduction_type_flag", "PF.frequency_name");
		
			$statutory_list = $this->deductions->get_other_deduction_list($aColumns, $bColumns, $params);
			$iTotal         = $this->deductions->total_length();
			$iFilteredTotal = $this->deductions->filtered_length($aColumns, $bColumns, $params);

			$cnt = 0;

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module      = $params['module'];
			$employee_id = $params['employee_id'];

			foreach ($statutory_list as $aRow):
				$cnt++;
				$row = array();
				

				$row[] =  strtoupper($aRow['deduction_name']);	
				$row[] =  '<center>' . format_date($aRow['start_date']) . '</center>';		
				$row[] =  strtoupper($aRow['deduction_type_flag']);
				$row[] =  strtoupper($aRow['frequency_name']);

				$id           = $this->hash($aRow['employee_deduction_id']);
				
				$salt         = gen_salt();
				$token_view   = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id  . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view      = ACTION_VIEW."/".$employee_id ."/".$token_view."/".$salt."/".$module."/".$id;
				$url_edit      = ACTION_EDIT."/".$employee_id ."/".$token_edit."/".$salt."/".$module."/".$id."/".$has_permission;
				$url_delete    = ACTION_DELETE."/".$employee_id ."/".$token_delete."/".$salt."/".$module."/".$id."/".$has_permission;
				$delete_action = 'content_delete("Employee Other Deductions", "'.$url_delete.'")';

				$action = "<div class='table-actions'>";

				$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_employee_other_deductions' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_employee_other_deductions_init('".$url_view."')\"></a>";

				if($module == MODULE_HR_DEDUCTIONS && $params['action_id'] != ACTION_VIEW && $params['has_permission']):
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_employee_other_deductions' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employee_other_deductions_init('".$url_edit."')\"></a>";
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				endif;
				
				$delete_action	= 'content_delete("deduction", "'.$url_delete.'")';
			
		
				$action .= "</div>";

				if($cnt == $total)
					$action .= $resources;
				
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

	function get_deductions_data($deduction_id = 0) 
	{

		try {

			$field 	= array("*") ;
			//$table	= $this->deductions->tbl_param_deductions;
			 $tables = array(
				'main' => array(
					'table'		=> $this->deductions->tbl_param_deductions,
					'alias'		=> 'A',
				),
				't2' => array(
					'table'		=> $this->deductions->tbl_param_other_deduction_details,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'B.deduction_id = A.deduction_id',
				),
				
			);
			$where	= array('A.deduction_id' => $deduction_id);
			$data['other_deductions_details'] 	= $this->deductions->get_deduction_data($field, $tables, $where);


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

		echo json_encode($data);
	}
	
	public function modal_deduction_type($action, $id, $token, $salt, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATATABLE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) )
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['id']           = $id;
			$data['salt']         = $salt;
			$data['token_add']    = in_salt($id  . '/' . ACTION_ADD  . '/' . $module, $salt);
			$data['token_edit']   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
			$data['token_delete'] = in_salt($id  . '/' . ACTION_DELETE  . '/' . $module, $salt);
			$data['module']       = $module;
			$data['action']       = $action;

			$post_data = array(
				'deduction_id' => $id);
			$resources['datatable'][]	= array('table_id' => 'table_deduction_personnel_list', 'path' => 'main/deductions/get_deduction_personnel_list', 'advanced_filter' => TRUE, 'post_data' => json_encode($post_data));

			$resources['load_modal'] = array(
					'modal_add_personnel_to_deductions' => array(
						'controller' => __CLASS__,
						'module'     => PROJECT_MAIN,
						'method'     => 'modal_add_personnel_to_deductions',
						'multiple'   => true,
						'height'     => '500px',
						'size'       => 'xl',
						'title'      => 'Employee'
					),
					'modal_edit_personnel_to_deductions' => array(
						'controller' => __CLASS__,
						'module'     => PROJECT_MAIN,
						'method'     => 'modal_edit_personnel_to_deductions',
						'multiple'   => true,
						'height'     => '500px',
						'size'       => 'xl',
						'title'      => "Edit Personnel"
					),
					'modal_delete_personnel_to_deductions' => array(
						'controller' => __CLASS__,
						'module'     => PROJECT_MAIN,
						'method'     => 'modal_delete_personnel_to_deductions',
						'multiple'   => true,
						'height'     => '500px',
						'size'       => 'xl',
						'title'      => "Delete Personnel"
					)
				);	

			$field 					= array("*") ; 
			$table					= $this->deductions->tbl_param_deductions;
			$where					= array();
			$key    				= $this->get_hash_key('deduction_id');
			$where[$key]			= $id;
			$data['deduction_info'] = $this->deductions->get_deduction_data($field, $table, $where, FALSE);

			// ====================== jendaigo : start : check if superuser role ============= //
			$data['has_permission'] = (in_array('SUPER_USER',$this->session->userdata('user_roles'))) ? TRUE : FALSE;
			// ====================== jendaigo : end : check if superuser role ============= //

			$this->load->view('deductions/modals/modal_deduction_type', $data);
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

	public function get_deduction_employee_list()

	{

		try
		{
		
			$params = get_params();
			$module = MODULE_HR_DEDUCTIONS;

			// $aColumns = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname", "", "ifnull(B.employ_office_name,E.name) AS office_name", "D.employment_status_name", "B.employ_office_id");
			// $bColumns = array("A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name,' ',LEFT(A.middle_name,1), '.')", "E.name", "D.employment_status_name");
			
			// ====================== jendaigo : start : change name format ============= //
			$aColumns = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name))) as fullname", "", "ifnull(B.employ_office_name,E.name) AS office_name", "D.employment_status_name", "B.employ_office_id");
			$bColumns = array("A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name)))", "E.name", "D.employment_status_name");
			// ====================== jendaigo : end : change name format ============= //
			
			$employee_list 	= $this->deductions->get_employee_list($aColumns, $bColumns, $params, $module);
			// $iTotal			= $this->deductions->total_length();
			$iFilteredTotal = $this->deductions->filtered_length($aColumns, $bColumns, $params, $module);
			
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

			foreach ($employee_list as $aRow):

				$row = array();
				$id  = $this->hash($aRow['employee_id']);
				
				$salt       = gen_salt();
				$token_view = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;


				$row[] = $aRow['agency_employee_id'];
				$row[] = strtoupper($aRow['fullname']);
				$row[] = strtoupper($aRow['office_name']);
				$row[] = $aRow['employment_status_name'];

				
				$action = "<div class='table-actions'>";
			
				// if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				$action 	.= "<a href='javascript:;' data-tooltip='View' class='view tooltipped' onclick=\"content_form('deductions/get_statutory_info/".$url_view ."', '".PROJECT_MAIN."')\"></a>";
				$office_list = explode(',', $user_offices[$module]);

				if(in_array($aRow['employ_office_id'],$office_list ))
				$action .= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped' onclick=\"content_form('deductions/get_statutory_info/".$url_edit ."', '".PROJECT_MAIN."')\"></a>";
				
					
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

	public function get_personnel_list()
	{
		try
		{
			$params        = get_params();

			$status        = 0;
			$employee_list = $this->deductions->get_personnel_list($params);
			
			$status        = 1;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		
		$info = array(
			"status"        => $status,
			"employee_list" => $employee_list,
			"counter" 		=> count($employee_list)
		);
	
		echo json_encode($info);
	}

	public function get_specific_personnel()
	{
		$params				= get_params();
		$list 				= array();
		$append_personnnel_list = "";
		$region_code 			= $params['region_code'];

		
		$employee_list       		= $this->deductions->get_specific_personnel_list($params);
		
		if($employee_list)
		{
			foreach ($employee_list as $aRow):		

				$id 			= $this->hash($aRow['employee_id']);
				
				$list[] = array(
							"value" => $id,
							"text" => ucwords($aRow["fullname"])
					); 
				$append_personnnel_list .= '<tr class="employee_div">
				  					<td><input type="hidden" name="employee_list[]" value="'.$id.'">'.$aRow["agency_employee_id"].'</td>
			  					<td>'.ucwords($aRow["fullname"]).'</td>
			  					<td class="table-actions"><a href="javascript:;" class="delete cursor-pointer" id="remove_personnel"></a></td>
		  					</tr>';
			endforeach; 
		}
		
		
		$info = array(
				"list" => $list,
				"append_personnnel_list" => $append_personnnel_list
		);
	
		echo json_encode($info);
	}

	public function modal_add_personnel_to_deductions($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_NUMBER);

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


			$field 							= array("employment_type_code", "employment_type_name") ;
			$table							= $this->deductions->tbl_param_employment_types;
			$where							= array();
			$where['employment_type_code']	= array(array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
			$data['employ_type_flag'] 		= $this->deductions->get_deduction_data($field, $table, $where, TRUE);

			$field                  = array("*") ;
			$table                  = $this->deductions->tbl_param_deductions;
			$where                  = array();
			$key                    = $this->get_hash_key('deduction_id');
			$where[$key]            = $id;
			$data['deduction_info'] = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			

			$field                = array("*") ;
			$table                = $this->deductions->tbl_param_positions;
			$where                = array();
			$where['active_flag'] = YES;
			$data['positions']    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			$data['salary_grade'] = $this->deductions->get_salary_grade();

			$field = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->deductions->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->deductions->db_core.".".$this->deductions->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				),
				
			);
			$where                     	 = array();
			$where['A.active_flag'] 	 = YES;
			$data['offices'] 			 = $this->deductions->get_deduction_data($field, $tables, $where, TRUE);

			$where                     	 = array();
			$where['other_info_type_id'] = 4;
			$data['designation']         = $this->deductions->get_deduction_data(array('*'), $this->deductions->tbl_employee_other_info, $where);
			
			$data['performance_ratings'] = $this->deductions->get_deduction_data(array('*'), $this->deductions->tbl_param_performance_rating, array('active_flag' => 'Y'));
			
			switch ($action) 
			{
				case ACTION_ADD:
					$tables = array(
						'main'	=> array(
							'table'		=> $this->deductions->tbl_employee_personal_info,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->deductions->tbl_employee_work_experiences,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = B.employee_id',
						),
						't3'	=> array(
							'table'		=> $this->deductions->tbl_employee_performance_evaluations,
							'alias'		=> 'C',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = C.employee_id',
						),
						't4'	=> array(
							'table'		=> $this->deductions->tbl_employee_other_info,
							'alias'		=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = D.employee_id',
						)
						
					);
					$where                = array();
					$deduction_id         = $this->get_hash_key('deduction_id');
					$where[$deduction_id] = $id;

					$deduction_id = $this->deductions->get_deduction_data(array('GROUP_CONCAT(employee_id) employee_id'),$this->deductions->tbl_employee_deductions, $where, FALSE);

					$where                       = array();
					$where['B.active_flag']      = YES;

					switch ($data['deduction_info']['employ_type_flag']) {
						case PAYROLL_TYPE_FLAG_ALL:
							$where['B.employ_type_flag'] = array(array(DOH_GOV_APPT,DOH_GOV_NON_APPT, DOH_JO), array('IN'));
							break;
						
						case PAYROLL_TYPE_FLAG_REG:
							$where['B.employ_type_flag'] = array(array(DOH_GOV_APPT,DOH_GOV_NON_APPT), array('IN'));
							break;

						case PAYROLL_TYPE_FLAG_JO:
							$where['B.employ_type_flag'] = array(array(DOH_JO), array('IN'));
							break;
					}
					if(!empty($deduction_id['employee_id']))
					$where['A.employee_id'] = array(explode(',', $deduction_id['employee_id']), array('NOT IN'));
					
					$group_by 				= array('A.employee_id, B.employ_office_id, B.employ_position_id, B.employ_salary_grade, fullname, C.rating, designation');
					// $order_by 				= array('CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\')' => 'ASC');
					// $fields  				= array('A.employee_id, B.employ_office_id, B.employ_position_id, B.employ_salary_grade, CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\') as fullname, C.rating, (SELECT others_value FROM employee_other_info WHERE other_info_type_id = 4 AND employee_id = A.employee_id) AS designation, B.employ_type_flag');
					
					// ====================== jendaigo : start : change name format ============= //
					$order_by 				= array('CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \')))' => 'ASC');
					$fields  				= array('A.employee_id, B.employ_office_id, B.employ_position_id, B.employ_salary_grade, CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) as fullname, C.rating, (SELECT others_value FROM employee_other_info WHERE other_info_type_id = 4 AND employee_id = A.employee_id) AS designation, B.employ_type_flag');
					// ====================== jendaigo : end : change name format ============= //
					
					$data['employee_list']  = $this->deductions->get_deduction_data($fields, $tables, $where, TRUE, $order_by, $group_by);
					
					break;
				case ACTION_EDIT:
				case ACTION_DELETE:

					$tables = array(
						'main'	=> array(
							'table'		=> $this->deductions->tbl_employee_personal_info,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->deductions->tbl_employee_deductions,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = B.employee_id',
						),
						't3'	=> array(
							'table'		=> $this->deductions->tbl_employee_performance_evaluations,
							'alias'		=> 'C',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = C.employee_id',
						),
						't4'	=> array(
							'table'		=> $this->deductions->tbl_employee_other_info,
							'alias'		=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = D.employee_id',
						),
						't5'	=> array(
							'table'		=> $this->deductions->tbl_employee_work_experiences,
							'alias'		=> 'E',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = E.employee_id',
						)
						
					);
					
					$where                 = array();
					$deduction_id          = $this->get_hash_key('B.deduction_id');
					$where[$deduction_id]  = $id;
					$where['E.active_flag']      = YES; //jendaigo : include active_flag validation
					$group_by              = array('A.employee_id, E.employ_office_id, E.employ_position_id, E.employ_salary_grade, B.employee_id, fullname, C.rating, D.other_info_type_id');
					
					// $order_by              = array('CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\')' => 'ASC');
					// $fields                = array('B.employee_id, E.employ_office_id, E.employ_position_id, E.employ_salary_grade, CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\') as fullname, C.rating, D.other_info_type_id, E.employ_type_flag');
					// ====================== jendaigo : start : change name format ============= //
					$order_by              = array('CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \')))' => 'ASC');
					$fields                = array('B.employee_id, E.employ_office_id, E.employ_position_id, E.employ_salary_grade, CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) as fullname, C.rating, D.other_info_type_id, E.employ_type_flag');
					// ====================== jendaigo : end : change name format ============= //
					
					$data['employee_list'] = $this->deductions->get_deduction_data($fields, $tables, $where, TRUE, $order_by, $group_by);

					break;
				
			}


			$this->load->view('deductions/modals/modal_add_personnel_to_deductions', $data);
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

	public function modal_edit_personnel_to_deductions($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);
			
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

			$field                = array("*") ;
			$table                = $this->deductions->tbl_param_deductions;
			$where                = array();
			$key                  = $this->get_hash_key('deduction_id');
			$where[$key]          = $id;
			$data['benefit_info'] = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			
			
			$field                = array("*") ;
			$table                = $this->deductions->tbl_param_positions;
			$where                = array();
			$where['active_flag'] = YES;
			$data['positions']    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			$data['salary_grade'] = $this->deductions->get_salary_grade();


			$field = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->deductions->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->deductions->db_core.".".$this->deductions->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
			$where                  = array();
			$where['A.active_flag'] = YES;
			$data['offices']        = $this->deductions->get_deduction_data($field, $tables, $where, TRUE);

			$field                  = array("*") ;
			$table                  = $this->deductions->tbl_param_deductions;
			$where                  = array();
			$key                    = $this->get_hash_key('deduction_id');
			$where[$key]            = $id;
			$deductions_type        = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			$data['deduction_type'] = $deductions_type['deduction_name'];

			$this->load->view('deductions/modals/modal_edit_personnel_to_deductions', $data);
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

	public function modal_delete_personnel_to_deductions($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);
			
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module)) {
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt)) {
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$field                = array("*") ;
			$table                = $this->deductions->tbl_param_positions;
			$where                = array();
			$where['active_flag'] = YES;
			$data['positions']    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			$data['salary_grade'] = $this->deductions->get_salary_grade();


			$field  = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table' 	=> $this->deductions->tbl_param_offices,
					'alias' 	=> 'A',
				),
				't2'	=> array(
					'table'     => $this->deductions->db_core.".".$this->deductions->tbl_organizations,
					'alias'     => 'B',
					'type'      => 'join',
					'condition' => 'A.org_code = B.org_code',
				)
			);
			$where                   = array();
			$where['A.active_flag']  = YES;
			$data['offices']         = $this->deductions->get_deduction_data($field, $tables, $where, TRUE);
			
			$field                   = array("*") ;
			$table                   = $this->deductions->tbl_param_deductions;
			$where                   = array();
			$key                     = $this->get_hash_key('deduction_id');
			$where[$key]             = $id;
			$deductions_type         = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			$data['deductions_type'] = $deductions_type['deduction_name'];

			$this->load->view('deductions/modals/modal_delete_personnel_to_deductions', $data);
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



	public function modal_employee_other_deductions($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL, $id = NULL, $has_permission = FALSE)
	{
		try
		{
			$data = array();

			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');

			$data['id'] 			= $id;
			$data['action']			= $action;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;
			$data['has_permission']	= $has_permission;

			$field                     = array("*") ;
			$data['deduction_details'] = $this->deductions->get_deduction_data($field, $this->deductions->tbl_param_other_deduction_details, array('active_flag' => 'Y'));

			// ====================== jendaigo : start : get pds identifications ============= //	
			$field                         = array("*") ;
			$tables  = array(
				'main'	=> array(
					'table'		=> $this->deductions->tbl_employee_identifications,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->deductions->tbl_param_identification_types,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.identification_type_id = A.identification_type_id',
				),
				
			);
			
			$where                         = array();
			$key                   		   = $this->get_hash_key('employee_id');
			$where[$key]           		   = $employee_id;
			$employee_identification = $this->deductions->get_deduction_data($field, $tables, $where, TRUE);
			
			foreach ($employee_identification as $key=>$eidentification):
				$data['employee_identification'][strtoupper($eidentification['identification_type_name'])] = $eidentification;
				$data['employee_identification'][strtoupper($eidentification['identification_type_name'])]['format_identification_value'] = $this->format_identification_value($eidentification['identification_value'], $eidentification['format']);;
			endforeach;
			// ====================== jendaigo : end : get pds identifications ============= //	

			$field               = array("*") ;
			$table               = $this->deductions->tbl_param_multipliers;
			$where               = array();
			$data['multipliers'] = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			// get frequency
			$field             = array("*") ;
			$table             = $this->deductions->tbl_param_frequencies;
			$where             = array();
			$data['frequency'] = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			// ====================== jendaigo : start : get MP2 system parameter details ============= //
			$data['mp2_sys']			= $this->deductions->get_deduction_data(array('*'), DB_CORE.'.'.$this->deductions->tbl_sys_param, array('sys_param_type' => PARAM_FORMAT_MP2), FALSE);
			// ====================== jendaigo : end : get MP2 system parameter details ============= //

			if(!EMPTY($id)) { 
				//EDIT
				$field   = array("*") ;

				$tables  = array(
					'main'	=> array(
						'table'		=> $this->deductions->tbl_employee_deductions,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->deductions->tbl_param_deductions,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'B.deduction_id = A.deduction_id',
					),
					
				);
				// GET EMPLOYEE DEDUCTION
				$where                  = array();
				$key                    = $this->get_hash_key('employee_deduction_id');
				$where[$key]            = $id;
				$key                    = $this->get_hash_key('employee_id');
				$where[$key]            = $employee_id;
				$deduction_info         = $this->deductions->get_deduction_data(array("*"), $tables, $where, FALSE);	
				$data['deduction_info'] = $deduction_info;

				// GET EMPLOYEE DEDUCTION DETAILS
				$field                          = array("*") ;
				// $table                          = $this->deductions->tbl_employee_deduction_details;
				// ====================== jendaigo : start : include deduction detail details ============= //
				$table  = array(
					'main'	=> array(
						'table'		=> $this->deductions->tbl_employee_deduction_details,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->deductions->tbl_employee_deduction_detail_details,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'B.employee_deduction_detail_id = A.employee_deduction_detail_id',
					),
					
				);
				// ====================== jendaigo : end : include deduction detail details ============= //
				
				$where                          = array();
				$where['employee_deduction_id'] = $deduction_info['employee_deduction_id'];
				$data['deduction_details_info'] = $this->deductions->get_deduction_data($field, $table, $where);

				// GET EMPLOYEE DEDUCTION OTHER DETAILS
				/*
				$tables = array(
					'main'	=> array(
						'table'		=> $this->deductions->tbl_employee_deduction_other_details,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->deductions->tbl_param_other_deduction_details,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'A.other_deduction_detail_id = B.other_deduction_detail_id',
					)
				);
				
				$where                                = array();
				$where['employee_deduction_id']       = $deduction_info['employee_deduction_id'];
				*/
				// ====================== jendaigo : start : include blank value parameters ============= //
				$tables = array(
					'main'	=> array(
						'table'		=> $this->deductions->tbl_param_other_deduction_details,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->deductions->tbl_employee_deduction_other_details,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'B.other_deduction_detail_id = A.other_deduction_detail_id AND
										B.employee_deduction_id = "'.$deduction_info['employee_deduction_id'].'" ',
					)
				);
				
				$where                                = array();
				$where['A.deduction_id']       		  = $deduction_info['deduction_id'];
				$data['deduction_other_details_info'] = $this->deductions->get_deduction_data(array("*"), $tables, $where);
				// ====================== jendaigo : end : include blank value parameters ============= //

				/*
				$tables = array(
						'main' => array(
							'table' => $this->deductions->tbl_param_deductions,
							'alias' => 'A',
						),
						't2'   => array(
							'table'     => $this->deductions->tbl_param_identification_types,
							'alias'     => 'B',
							'type'      => 'LEFT JOIN',
							'condition' => 'B.identification_type_code = A.deduction_code'
						)
				);
					
				$where = array();
				$where['A.deduction_id'] = $data['deduction_other_details_info'][0]['deduction_id'];
				
				$result	= $this->deductions->get_deduction_data(array("B.format"), $tables, $where, FALSE);
				
				$data['deduction_other_details_info'][0]['other_deduction_detail_value'] = $this->format_identification_value($data['deduction_other_details_info'][0]['other_deduction_detail_value'], $result['format']);
				*/

				$resources['single'] = array(
					'deduction_id' => $data['deduction_info']['deduction_id'] . '|' . $data['deduction_info']['deduction_type_flag']
				);

				$deduction_par = $this->deductions->get_deduction_data(array('deduction_id'),$this->deductions->tbl_employee_deductions, array($this->get_hash_key('employee_id') => $employee_id, 'deduction_id' => array($deduction_info['deduction_id'] , array('!='))));
				
				foreach($deduction_par AS $r) {
					$ids[] = $r['deduction_id'];
				}
			}
			else {
				$deduction_par = $this->deductions->get_deduction_data(array('deduction_id'),$this->deductions->tbl_employee_deductions, array($this->get_hash_key('employee_id') => $employee_id));
				

				foreach($deduction_par AS $r) {
					$ids[] = $r['deduction_id'];
				}
			}
				
			$employee_info = $this->deductions->get_deduction_data(array('*'), $this->deductions->tbl_employee_work_experiences, array($this->get_hash_key('employee_id') => $employee_id, 'active_flag' => 'Y'), FALSE);
			
			$employ_type_flag = array();
			switch ($employee_info['employ_type_flag']) {
				case DOH_GOV_APPT:
				case DOH_GOV_NON_APPT:
					$employ_type_flag[] = 'REG';
					break;
				case DOH_JO:
					$employ_type_flag[] = $employee_info['employ_type_flag'];
					break;
			}
			$employ_type_flag[] = 'ALL';
			$table                        = $this->deductions->tbl_param_deductions;
			
			$where                        = array();
			$where['statutory_flag'] 	  = 'N';
			$where['active_flag']         = 'Y';
			$where['employee_flag']       = 'Y';
			$where['employ_type_flag']        = array($employ_type_flag, array("IN"));
			if(!EMPTY($ids)) {
				$where['deduction_id']        = array($ids, array("NOT IN"));
			}
			$data['deduction_types']      = $this->deductions->get_deduction_data($field, $table, $where, TRUE);

			// ====================== jendaigo : start : get deduction detail types ============= //
			//GET SYSTEM PARAMETER VALUES
			$compensation_prem_id_sys		= $this->deductions->get_deduction_data(array('sys_param_value'), DB_CORE.'.'.$this->deductions->tbl_sys_param, array('sys_param_type' => PARAM_COMPENSATION_ID_PREMIUM), FALSE);
			$compensation_bs_code_sys		= $this->deductions->get_deduction_data(array('sys_param_value'), DB_CORE.'.'.$this->deductions->tbl_sys_param, array('sys_param_type' => PARAM_COMPENSATION_BASIC_SALARY), FALSE);
			$compensation_bsp_code_sys		= $this->deductions->get_deduction_data(array('sys_param_value'), DB_CORE.'.'.$this->deductions->tbl_sys_param, array('sys_param_type' => PARAM_COMPENSATION_BASIC_SALARY_PREMIUM), FALSE);

			//CHECK IF EMPLOYEE IS WITH PREMIUM COMPENSATION
			$employee_compensations			= array();
			$field							= array("compensation_id");
			$table                      	= $this->deductions->tbl_employee_compensations;
			$where                      	= array();
			$key                    		= $this->get_hash_key('employee_id');
			$where[$key]            		= $employee_id;
			$where['compensation_id']   	= $compensation_prem_id_sys['sys_param_value'];
			$employee_prem_compensations	= $this->deductions->get_deduction_data($field, $table, $where, TRUE);

			//GET DEDUCTION DETAIL TPES
			$field							= array("*");
			$table                       	= $this->deductions->tbl_param_deduction_detail_types;
			$where                       	= array();
			$where['active_flag']        	= 'Y';
			$where['employ_type_flag']      = array($employ_type_flag, array("IN"));
			
			$deduction_detail_type_codes 	= ($employee_prem_compensations) ? array($compensation_bs_code_sys['sys_param_value']) : array($compensation_bsp_code_sys['sys_param_value']);
			$where['deduction_detail_type_code']	= array($deduction_detail_type_codes, array("NOT IN"));
			
			$data['deduction_detail_types']     	= $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			// ====================== jendaigo : start : get deduction detail types ============= //
			
			$data['module'] = MODULE_HR_DEDUCTIONS;
			$this->load_resources->get_resource($resources);
			$this->load->view('deductions/modals/modal_employee_other_deductions', $data);
			
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

	public function get_personnel_dependents_list()
	{

		try
		{
			$params         = get_params(); 
			
			$employee_id    = $params['employee_id'];
			$aColumns       = array("*,CONCAT(relation_last_name, ifnull(CONCAT(' ',relation_ext_name),''), ', ', relation_first_name, ' ', LEFT(relation_middle_name,1), '.') AS name");
			$bColumns       = array("CONCAT(relation_last_name, if(relation_ext_name='','',CONCAT(' ',relation_ext_name)), ', ', relation_first_name, ' ', LEFT(relation_middle_name,1), '.')", "relation_birth_date");
			
			 $statutory_list = $this->deductions->get_personnel_dependents_list($aColumns, $bColumns, $params);
			// $iTotal         = $this->deductions->total_length();
			// $iFilteredTotal = $this->deductions->filtered_length($aColumns, $bColumns, $params);
			
			// $output = array(
			// 	"sEcho"                => intval($_POST['sEcho']),
			// 	"iTotalRecords"        => $iTotal["cnt"],
			// 	"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
			// 	"aaData"               => array()
			// );

			foreach ($statutory_list as $key => $aRow):
				$row = array();
				
				
				$switch = '<center><input type="checkbox" name="relation[' . $key . '][' . strtolower($params['flag']) .']" class="filled-in" id="filled-in-box_' . $key .'" ' . ($aRow[strtolower($params['flag'])] == 'Y' ? 'checked="checked"' : '') . " " . $params['disabled_str'] . '/>'
						. '<label for="filled-in-box_' . $key .'"></label><center>';
 

				$row[] =  ucwords($aRow['name']) . '<input type="hidden" name="relation[' . $key . '][employee_relation_id]" value="' . $aRow['employee_relation_id'] . '"/>';	
				$row[] =  '<center>' . format_date($aRow['relation_birth_date']) . '</center>';	
				$row[] =  $switch;	
			
					
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


	public function modal_employee_statutory($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL, $id = NULL, $deduction_id = NULL, $has_permission = FALSE)
	{
		try
		{
			$data = array();

			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_NUMBER);

			$data['id'] 			= $id;
			$data['action']			= $action;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;
			$data['has_permission']	= $has_permission;

			
			$field               = array("*") ;
			$table               = $this->deductions->tbl_param_multipliers;
			$where               = array();
			$data['multipliers'] = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			
			// get frequency
			$field             = array("*") ;
			$table             = $this->deductions->tbl_param_frequencies;
			$where             = array();
			$data['frequency'] = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
							
			//get address list
			$field                      = array("*") ;
			$table                      = $this->deductions->tbl_employee_addresses;
			$where                      = array();
			$key                        = $this->get_hash_key('employee_id');
			$where[$key]                = $employee_id;
			$data['employee_addresses'] = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			$ids = array();

			if(!EMPTY($id)) { 
				//EDIT
				$field  = array("*") ;
				$tables = array(
					'main'	=> array(
						'table'		=> $this->deductions->tbl_employee_deductions,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->deductions->tbl_param_deductions,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'B.deduction_id = A.deduction_id',
					),
					
				);

				$where                        = array();
				$key                          = $this->get_hash_key('employee_deduction_id');
				$where[$key]                  = $id;
				$key                          = $this->get_hash_key('employee_id');
				$where[$key]                  = $employee_id;
				$deduction_info               = $this->deductions->get_deduction_data(array("*"), $tables, $where, FALSE);	
				$data['deduction_info']       = $deduction_info;
				
				// GET EMPLOYEE DEDUCTION DETAILS
				$field                                = array("*") ;
				$table                                = $this->deductions->tbl_employee_deduction_details;
				$where                                = array();
				$where['employee_deduction_id']       = $deduction_info['employee_deduction_id'];
				$data['deduction_details_info']       = $this->deductions->get_deduction_data($field, $table, $where);

				// GET EMPLOYEE DEDUCTION OTHER DETAILS

				$tables = array(
					'main'	=> array(
						'table'		=> $this->deductions->tbl_employee_deduction_other_details,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->deductions->tbl_param_other_deduction_details,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'A.other_deduction_detail_id = B.other_deduction_detail_id',
					)
				);
				$where                                = array();
				$where['employee_deduction_id']       = $deduction_info['employee_deduction_id'];
				$data['deduction_other_details_info'] = $this->deductions->get_deduction_data(array("*"), $tables, $where);

				$deduction_par = $this->deductions->get_deduction_data(array('deduction_id'),$this->deductions->tbl_employee_deductions, array($this->get_hash_key('employee_id') => $employee_id, 'deduction_id' => array($deduction_info['deduction_id'] , array('!='))));
				

				foreach($deduction_par AS $r) {
					$ids[] = $r['deduction_id'];
				}

				$resources['single'] = array(
					'deduction_id'            => $data['deduction_info']['deduction_id']
				);
			}
			else {
				$deduction_par = $this->deductions->get_deduction_data(array('deduction_id'),$this->deductions->tbl_employee_deductions, array($this->get_hash_key('employee_id') => $employee_id)); 
				foreach($deduction_par AS $r) {
					$ids[] = $r['deduction_id'];
				} 
			}

			$where                = array();
			$key                  = $this->get_hash_key('employee_id');
			$where[$key]          = $employee_id;
			$where['active_flag'] = YES;
			$employment_flag      = $this->deductions->get_deduction_data(array('employ_type_flag'), $this->deductions->tbl_employee_work_experiences, $where, FALSE);
			
			$employ_type_flag   = array();
			$employ_type_flag[] = PAYROLL_TYPE_FLAG_ALL;
			switch ($employment_flag['employ_type_flag']) 
			{
				case DOH_GOV_APPT:
				case DOH_GOV_NON_APPT:
					$employ_type_flag[] = PAYROLL_TYPE_FLAG_REG;
					break;
				
				case DOH_JO:
					$employ_type_flag[] = PAYROLL_TYPE_FLAG_JO;
					break;
			}

			$field = array("*");

			$tables = array(
				'main'	=> array(
					'table'		=> $this->deductions->tbl_param_deductions,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->deductions->tbl_param_identification_types,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.identification_type_code = A.deduction_code',
				),
				't3'	=> array(
					'table'		=> $this->deductions->tbl_employee_identifications,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'C.identification_type_id = B.identification_type_id'
				)
				
			);
			$where = array();
			$where['A.statutory_flag']   = 'Y';
			$where['A.active_flag']      = 'Y';
			$where['A.employee_flag']    = 'Y';
			$where['A.employ_type_flag'] = array($employ_type_flag, array('IN'));

			if(!EMPTY($ids)) $where['A.deduction_id'] = array($ids, array("NOT IN"));
			
			$data['deduction_types'] = $this->deductions->get_deduction_data($field, $tables, $where); 

			$where = array();
			if(ISSET($deduction_id)) {
				$key         = $this->get_hash_key('deduction_id');
				$where[$key] = $deduction_id;
			}
			$where['active_flag']      = 'Y';
			$data['deduction_details'] = $this->deductions->get_deduction_data($field, $this->deductions->tbl_param_other_deduction_details, $where); 
			$data['module']            = MODULE_HR_DEDUCTIONS;
			
			$this->load->view('deductions/modals/modal_employee_statutory', $data);
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
	
	public function get_identification_number() 
	{
		try 
		{			
			$flag   	  = 0;
			$msg    	  = ERROR;
			$params 	  = get_params();
			$deduction_id = $params['select_id'];
			$emp_id 	  = $params['id'];

			if($deduction_id == DEDUC_PHIC_QTR)
			{
				$deduction_id = DEDUC_PHILHEALTH;
			}
			if($deduction_id == DEDUC_BIR_EWT OR $deduction_id == DEDUC_BIR_VAT)
			{
				$deduction_id = DEDUC_BIR;
			}

			$tables 			= array(
				'main'			=> array(
					'table'		=> $this->deductions->tbl_param_deductions,
					'alias'		=> 'A',
				),
				't2'			=> array(
					'table'		=> $this->deductions->tbl_param_identification_types,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.identification_type_code = A.deduction_code'
				),
				't3'			=> array(
					'table'		=> $this->deductions->tbl_employee_identifications,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'C.identification_type_id = B.identification_type_id'
				)
			);

			$where                     	= array();
			$key    				   	= $this->get_hash_key('C.employee_id');
			$where[$key]    		   	= $emp_id;
			$where['A.deduction_id']  	= $deduction_id;
			$data['identification_no']	= $this->deductions->get_deduction_data(array("C.identification_value", "B.format"), $tables, $where, FALSE);
			
			$identification_value		= $data['identification_no']['identification_value'];
			
			$format					   	= $data['identification_no']['format'];
			$id 						= $this->format_identification_value($identification_value, $format);
			
			$flag 	= 1;
			$msg  	= SUCCESS;

		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		 = array(
			"flag"   => $flag,
			"msg" 	 => $msg,
			"id"	 => $id,
			"format" => $format
		);

		echo json_encode($info);
	}	

	public function format_identification_value($identification_value, $format)
	{
		$identification_value		= str_replace( chr( 194 ) . chr( 160 ), ' ', $identification_value);
		$identification_value		= str_replace(" ", "", $identification_value);
		$identification_value		= str_replace("-", "", $identification_value);
		
		return format_identifications($identification_value, $format);
	}
	
	
	public function process_add_personnel_to_deductions()
	{
		try
		{
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params				= get_params();
			$action				= $params['action'];
			$token				= $params['token'];
			$salt				= $params['salt'];
			$deductions_id		= $params['id'];
			$module				= $params['module'];

			$params['employee_list'] = array_unique($params['employee_list']); //jendaigo: remove duplicate employee

			if(EMPTY($action) OR EMPTY($deductions_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module)) {
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($deductions_id . '/' . $action  . '/' . $module , $salt)) 	{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if(EMPTY($params['employee_list']))
				throw new Exception($this->lang->line('invalid_action'));
			
			/*CHECK DATA VALIDITY*/
			$valid_data = $this->_validate_add_personnel($params);
			
			Main_Model::beginTransaction();

			$field 						= array("*") ;
			$tables = $this->deductions->tbl_param_deductions;
				
			$where          	= array();
			$key            	= $this->get_hash_key('deduction_id');
			$where[$key]    	= $deductions_id;
			$deductions_type 	= $this->deductions->get_deduction_data($field, $tables, $where, FALSE);

			switch ($action) 
			{
				case ACTION_ADD:
					$audit_activity = "Employees were added to Deduction Type ".$deductions_type['deduction_name'];

					break;

				case ACTION_EDIT:

					$this->validation_edit_delete_personnel_to_deductions($params['employee_list'], $deductions_type['deduction_name'], $where, $action); //jendaigo: validations

					$audit_activity = "Employees in Deduction Type ".$deductions_type['deduction_name'] . " were updated";
					
					$where['employee_id'] = array($params['employee_list'], array('IN')); //jendaigo: include employee list
					$this->deductions->delete_deduction($this->deductions->tbl_employee_deductions, $where);
					
					break;

				case ACTION_DELETE:

					$this->validation_edit_delete_personnel_to_deductions($params['employee_list'], $deductions_type['deduction_name'], $where, $action); //jendaigo: validations

					$audit_activity = "Employees were deleted from Deduction Type ".$deductions_type['deduction_name'];
					$audit_action[] = AUDIT_DELETE;	
					
					$where['employee_id'] = array($params['employee_list'], array('IN'));
					$this->deductions->delete_deduction($this->deductions->tbl_employee_deductions, $where);
					
					$prev_detail[]  = array($params['employee_list']);
					$curr_detail[]  = array();

					break;
			}

			if($action != ACTION_DELETE)
			{
				// ====================== jendaigo : start : hmdf init ============= //
				if($deductions_type['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
				{
					switch($deductions_type['deduction_id']) {
						case DEDUC_HMDF1_JO:
							$ded_payment_count 	= 120;
							$ded_amount 		= 100;
						break;
						case DEDUC_HMDF2_JO:
							$ded_payment_count 	= 60;
							$ded_amount 		= 500;
						break;
					}
				}
				// ====================== jendaigo : start : hmdf init ============= //

				$fields            = array();
				foreach($params['employee_list'] as $i => $employee_id)
				{
					$fields[$i]['employee_id']          = $employee_id;
					$fields[$i]['deduction_id']         = $deductions_type["deduction_id"];
					$fields[$i]['start_date']           = $valid_data["start_date"];

					// ====================== jendaigo : start : additional condition for employee deduction ============= //
					if($deductions_type['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
						$fields[$i]['payment_count'] = $ded_payment_count;
					// ====================== jendaigo : start : additional condition for employee deduction ============= //
				}	

				$table          = $this->deductions->tbl_employee_deductions;
				$this->deductions->insert_deduction($table, $fields, FALSE);
				
				// ====================== jendaigo : start : additional condition for deduction details ============= //
				//GET DEDUCTION OTHER DETAILS
				$deductions_other			= array();
				$tables = array(
					'main' => array(
						'table' => $this->deductions->tbl_param_other_deduction_details,
						'alias' => 'A'
					),
					't1'   => array(
						'table' => $this->deductions->tbl_employee_deductions,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.deduction_id = B.deduction_id'
					)
				);
				
				$where          			= array();
				$where['A.deduction_id'] 	= $deductions_type['deduction_id'];
				$where['B.employee_id'] 	= array($params['employee_list'], array('IN'));
				$deductions_other			= $this->deductions->get_deduction_data($field, $tables, $where, TRUE);

				$field = array('GROUP_CONCAT(DISTINCT other_detail_name) as other_detail_names');
				$deductions_other_dtl		= $this->deductions->get_deduction_data($field, $tables, $where, FALSE);

				//GET IDENTIFICATION REFERENCE
				$fields					= array("B.identification_value", "CONCAT(C.last_name, ', ', C.first_name, ' ', LEFT(C.middle_name, (1)), '. ') full_name, D.employ_start_date");
				$tables = array(
					'main' => array(
						'table' => $this->deductions->tbl_param_identification_types,
						'alias' => 'A'
					),
					't1'   => array(
						'table' => $this->deductions->tbl_employee_identifications,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.identification_type_id = B.identification_type_id'
					),
					't2'   => array(
						'table' => $this->deductions->tbl_employee_personal_info,
						'alias' => 'C',
						'type'  => 'LEFT JOIN',
						'condition' => 'B.employee_id = C.employee_id'
					),
					't3'   => array(
						'table' => $this->deductions->tbl_employee_work_experiences,
						'alias' => 'D',
						'type'  => 'LEFT JOIN',
						'condition' => 'D.employee_id = C.employee_id'
					)
				);
			
				$where          						= array();
				$emp_other_detl_names 					= explode(',', $deductions_other_dtl['other_detail_names']);
				$employ_type_flags		   				= array('AP','JO','WP');

				$where['A.identification_type_name'] 	= array($emp_other_detl_names, array('IN'));
				$where['B.employee_id'] 				= array($params['employee_list'], array('IN'));
				$where['D.employ_type_flag'] 			= array($employ_type_flags, array('IN'));	
				$order_by 				   				= array('employ_start_date' => 'ASC');
				$group_by 				   				= array('B.employee_id');
				$identification_types					= $this->deductions->get_deduction_data($fields, $tables, $where, TRUE, $order_by, $group_by);

				$fields            			= array();
				$fields_ded_details         = array();
				$message_ded_details        = '';
				$err_msg            		= '';

				foreach($deductions_other as $key => $deduction_other)
				{
					$fields[$key]['employee_deduction_id']    		= $deduction_other["employee_deduction_id"];
					$fields[$key]['other_deduction_detail_id']    	= $deduction_other["other_deduction_detail_id"];
					$fields[$key]['other_deduction_detail_value'] 	= (!empty($identification_types) ? $identification_types[$key]['identification_value'] : '');

					//ID REF VALIDATION
					if($identification_types[$key]['identification_value'] == 'NA')
					{
						$err_msg['identification']['other_detail_name']	 = $deduction_other['other_detail_name'];
						$err_msg['identification']['employee_list']    	.= '<br>'.$identification_types[$key]['full_name'];
					}

					//ID START DATE VALIDATION
					if($identification_types[$key]['employ_start_date'] > $deduction_other['start_date'])
					{
						$err_msg['start_date']['other_detail_name']	 = $deduction_other['other_detail_name'];
						$err_msg['start_date']['employee_list']    	.= '<br>'.$identification_types[$key]['full_name'];
					}

					//PAYMENT COUNT DETAILS
					if($deductions_type['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
					{
						$fields_ded_details[$key]['employee_deduction_id']  = $deduction_other["employee_deduction_id"];
						$fields_ded_details[$key]['payment_count']    		= $ded_payment_count;
						$fields_ded_details[$key]['amount']    				= $ded_amount;
						
						$message_ded_details = '<br><br>Note: The tagged deduction is up to '.$ded_payment_count.' months only. For deduction of more/less than '.$ded_payment_count.' months kindly edit the details of deduction in employees list.';
					}
				}

				//ERROR MESSAGE VIEW				
				if($err_msg['identification'])
					throw new Exception('Please update employee ' . $err_msg['identification']['other_detail_name'] . ' from HR Module. <br>' . $err_msg['identification']['employee_list']);
				if($err_msg['start_date'])
					throw new Exception($err_msg['start_date']['other_detail_name'] . ' start date should not be earlier than work experience start date. <br>' . $err_msg['start_date']['employee_list']);

				//SAVE DEDUCTION DETAILS
				$table          = $this->deductions->tbl_employee_deduction_other_details;
				$this->deductions->insert_deduction($table, $fields, TRUE);

				if(!empty($fields_ded_details))
				{
					$fields_ded_dtl_dtls = array();
					foreach($fields_ded_details as $key => $fields_ded_detail)
					{
						$table          = $this->deductions->tbl_employee_deduction_details;
						$ded_detl_id 	= $this->deductions->insert_deduction($table, $fields_ded_detail, TRUE);

						$fields_ded_dtl_dtls['employee_deduction_detail_id']	= $ded_detl_id;
						$fields_ded_dtl_dtls['start_date']						= $valid_data["start_date"];
						$fields_ded_dtl_dtls['paid_count']						= 0;
						$fields_ded_dtl_dtls['remarks']  						= $valid_data["remarks"];

						$table          = $this->deductions->tbl_employee_deduction_detail_details;
						$this->deductions->insert_deduction($table, $fields_ded_dtl_dtls, FALSE);
					}
				}
				// ====================== jendaigo : end : additional condition for deduction details ============= //

				$audit_action[] = AUDIT_UPDATE;	
				$prev_detail[]  = array();
				$curr_detail[]  = $fields;
			}
			
			$audit_table[]  = $table;
			$audit_schema[] = DB_MAIN;

			$this->audit_trail->log_audit_trail(
				$audit_activity, 
				$module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table, 
				$audit_schema
			);
				
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

		// ====================== jendaigo : start : success message of HMDF deduction details  ============= //
		if(!empty($message_ded_details) AND empty($err_msg))
			$message .= $message_ded_details;
		// ====================== jendaigo : end : success message of HMDF deduction details  ============= //
			
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;

		echo json_encode($data);
	}

	public function process_statutory_deductions()
	{
		$status = FALSE;
		try
		{
			$params = get_params();
			$id     = $params['id'];
			$action = $params['action'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			$module = $params['module'];
			$emp_id = $params['employee_id'];
			
			
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'] , $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			$deduction_id                = explode('|', $params['deduction_id']);
			$fields['deduction_id']      = $deduction_id[0];
			$fields['employee_id']       = $emp_id;
			$fields['start_date']        = $params['start_date'];
			// $fields['payment_count']     = $params['payment_count'];
			// SERVER VALIDATION
			
			//GET UNHASHED EMPLOYEE_ID
			$field                       = array("employee_id");
			$table                       = $this->deductions->tbl_employee_personal_info;
			$where                       = array();
			$key                         = $this->get_hash_key('employee_id');
			$where[$key]                 = $emp_id;	
			
				
			$employee_id_unhashed        = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			$employee_id                 = $employee_id_unhashed['employee_id'];
			$valid_data                  = $this->_validate_data($fields);
			$valid_data['employee_id']   = $employee_id;
			
			$module = MODULE_HR_COMPENSATION;

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table          = $this->deductions->tbl_employee_deductions;
			$audit_table[]  = $table;
			$audit_schema[] = DB_MAIN;
			$curr_detail[]  = array($valid_data);
				
			if(EMPTY($params['id'])) {
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]	= AUDIT_INSERT;
				
				$prev_detail[]	= array();

				//INSERT DATA
				$deduction_id = $this->deductions->insert_deduction($table, $valid_data, TRUE);

				if(!EMPTY($params['relation'])) {
					$table = $this->deductions->tbl_employee_relations;
					foreach($params['relation'] AS $relation) {
						$where = array();
						$where['employee_relation_id'] = $relation['employee_relation_id'];
						$fields = array();
						$fields[strtolower($params['active_flag'])] = ISSET($relation[strtolower($params['active_flag'])]) ? 'Y' : 'N';
						$this->deductions->update_deduction($table, $fields, $where);
					}
				}
				if(!EMPTY($params['other_deduction_details_switch'])) {
					// INSERT ALL OTHER DEDUCTION DETAILS
					$new_par = array();
					for($i=0; $i < count($params['other_deduction_details_switch']); $i+=2) {
						if($params['other_deduction_details_switch'][$i] == 'on') {
							$new_par[$i]['employee_deduction_id']        = $deduction_id;
							$new_par[$i]['other_deduction_detail_value'] = $params['other_deduction_details_switch'][$i+1];
							$new_par[$i]['other_deduction_detail_id']    = $params['other_deduction_details_switch'][$i+2];
							++$i;
						}
					}
					if(!EMPTY($new_par)) $this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_other_details, $new_par);
				}

				if(!EMPTY($params['other_deduction_details'])) {
					// INSERT ALL OTHER DEDUCTION DETAILS
					$new_par = array();
					for($i=0; $i < count($params['other_deduction_details']); $i+=2) {
						$new_par[$i]['employee_deduction_id']        = $deduction_id;
						$new_par[$i]['other_deduction_detail_value'] = $params['other_deduction_details'][$i];
						$new_par[$i]['other_deduction_detail_id']    = $params['other_deduction_details'][$i+1];
					}

					$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_other_details, $new_par);
				}
				//MESSAGE ALERT
				$message 		 = $this->lang->line('data_saved');

		
				$activity = "%s has been added";
			}
			else {
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('employee_deduction_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->deductions->get_deduction_data(array("*"), $table, $where, TRUE);

				
				//UPDATE DATA
				$this->deductions->update_deduction($table, $valid_data, $where); 
				$this->deductions->delete_deduction($this->deductions->tbl_employee_deduction_other_details, $where);
				$this->deductions->delete_deduction($this->deductions->tbl_employee_deduction_details, $where);

				if(!EMPTY($params['relation'])) {
					$table = $this->deductions->tbl_employee_relations;
					foreach($params['relation'] AS $relation) {
						$where = array();
						$where['employee_relation_id'] = $relation['employee_relation_id'];
						$fields = array();
						$fields[strtolower($params['active_flag'])] = ISSET($relation[strtolower($params['active_flag'])]) ? 'Y' : 'N';
						$this->deductions->update_deduction($table, $fields, $where);
					}
				}

				if(!EMPTY($params['other_deduction_details_switch'])) {
					// INSERT ALL OTHER DEDUCTION DETAILS
					$new_par = array();
					for($i=0; $i < count($params['other_deduction_details_switch']); $i+=2) {
						if($params['other_deduction_details_switch'][$i] == 'on') {
							$new_par[$i]['employee_deduction_id']        = $prev_detail[0][0]['employee_deduction_id'];
							$new_par[$i]['other_deduction_detail_value'] = $params['other_deduction_details_switch'][$i+1];
							$new_par[$i]['other_deduction_detail_id']    = $params['other_deduction_details_switch'][$i+2];
							++$i;
						}
					}
					
					if(!EMPTY($new_par)) $this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_other_details, $new_par);
					
				}

				if(!EMPTY($params['other_deduction_details'])) {
					// INSERT ALL OTHER DEDUCTION DETAILS
					$new_par = array();
					for($i=0; $i < count($params['other_deduction_details']); $i+=2) {
						$new_par[$i]['employee_deduction_id']        = $prev_detail[0][0]['employee_deduction_id'];
						$new_par[$i]['other_deduction_detail_value'] = $params['other_deduction_details'][$i];
						$new_par[$i]['other_deduction_detail_id']    = $params['other_deduction_details'][$i+1];
					}
					$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_other_details, $new_par);
				}

				//MESSAGE ALERT
				$message = $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->deductions->get_deduction_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, 'Deduction');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
			
			Main_Model::commit();
			$status = TRUE;
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
		$data['msg'] = $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}
	
	public function process_other_deductions()
	{
	
		try
		{
			$params = get_params();

			$id     = $params['id'];
			$action = $params['action'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			$module = $params['module'];
			$emp_id = $params['employee_id'];

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'] , $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			$details                  = explode('|', $params['deduction_id']);
			
			$new_par                  = array();
			$new_par['deduction_id']  = $details[0];
			$new_par['start_date']    = $params['start_date'];
			$new_par['employee_id']   = $params['employee_id'];
			$new_par['payment_count'] = 0;

			// ====================== jendaigo : start : input validation ============= //
			foreach ($params['amount'] as $key => $ded_amount):
				if($new_par['deduction_id'] == DEDUC_HMDF1_JO)
				{
					if($ded_amount < 100)
						throw new Exception ('Minimum amount value is 100.');
					elseif($ded_amount%100 != 0)
						throw new Exception ('Amount value must be increment of 100.');
				}
				elseif($new_par['deduction_id'] == DEDUC_HMDF2_JO)
				{
					if($ded_amount < 500)
						throw new Exception ('Minimum amount value is 500.');
					elseif($ded_amount%100 != 0)
						throw new Exception ('Amount value must be increment of 100.');
				}
			endforeach;

			//GET EMPLOYEE_WORK_EXP
			$field                     = array("employ_start_date");
			$table                     = $this->deductions->tbl_employee_work_experiences;
			$where                     = array();
			$key                       = $this->get_hash_key('employee_id');
			$where[$key]               = $emp_id;
			$employ_type_flags		   = array('AP','JO','WP');
			$where['employ_type_flag'] = array($employ_type_flags, array('IN'));	
			$order_by 				   = array('employ_start_date' => 'ASC');
			
			$employee_work_experiences = $this->deductions->get_deduction_data($field, $table, $where, FALSE, $order_by);

			if($employee_work_experiences['employ_start_date'] > date("Y-m-d", strtotime($params['start_date'])))
				throw new Exception ('Start date should not be earlier than work experience start date.');

			// ====================== jendaigo : end : input validation  ============= //

			if(!ISSET($params['payment_count'])) {
				if(!EMPTY($params['payment_count_dtl'])) {
					foreach ($params['payment_count_dtl'] as $key => $value) {
						$new_par['payment_count'] += $value;
					}
				}
			}
			else {
				$new_par['payment_count'] = $params['payment_count'];
			}

			// SERVER VALIDATION
			$valid_data                = $this->_validate_data($new_par);

			//GET UNHASHED EMPLOYEE_ID
			$field                     = array("employee_id");
			$table                     = $this->deductions->tbl_employee_personal_info;
			$where                     = array();
			$key                       = $this->get_hash_key('employee_id');
			$where[$key]               = $emp_id;	
			
			$employee_id_unhashed      = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			$employee_id               = $employee_id_unhashed['employee_id'];

			// ====================== jendaigo : start : get deduction_type_flag ============= //
			//GET DEDUCTION_TYPE_FLAG
			$field                     = array("deduction_type_flag");
			$table                     = $this->deductions->tbl_param_deductions;
			$where                     = array();
			$where['deduction_id']     = $valid_data['deduction_id'];	
			
			$param_deductions      	   = $this->deductions->get_deduction_data($field, $table, $where, FALSE);
			// ====================== jendaigo : end : get deduction_type_flag ============= //

			//SET FIELDS VALUE
			$valid_data['employee_id'] = $employee_id;
			
			$module                    = MODULE_HR_DEDUCTIONS;

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table          = $this->deductions->tbl_employee_deductions;
			$audit_table[]  = $table;
			$audit_schema[] = DB_MAIN;
			$curr_detail[]  = array($valid_data);

			if(EMPTY($params['id'])) {
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[] = AUDIT_INSERT;
				
				$prev_detail[]  = array();
				
				//INSERT DATA
				$deduction_id   = $this->deductions->insert_deduction($table, $valid_data, TRUE);

				if(!EMPTY($params['other_deduction_details'])) {
					// INSERT ALL OTHER DEDUCTION DETAILS
					$new_par = array();
					for($i=0; $i < count($params['other_deduction_details']); $i+=2) {
						
						// ====================== jendaigo : start : include error if ref is NA ============= //
						if($params['other_deduction_details'][$i] == 'NA')
						{
							$field                      		= array("*");
							$table                     			= $this->deductions->tbl_param_other_deduction_details;
							$where                     			= array();
							$where['other_deduction_detail_id'] = $params['other_deduction_details'][$i+1];	
							
							$other_deduction_detail      		= $this->deductions->get_deduction_data($field, $table, $where, FALSE);
							
							throw new Exception('Please update ' . $other_deduction_detail['other_detail_name'] . ' from HR Module.');
						}
						// ====================== jendaigo : end : include error if ref is NA ============= //

						$formatted_value 	= $params['other_deduction_details'][$i]; //jendaigo : assigned variable for formatted reference ID

						$params 			= $this->remove_identification_format($params, $i); //jendaigo : remove format of ID input
						
						// ====================== jendaigo : start : validation for MP2 reference ID ============= //
						if($valid_data['deduction_id'] == DEDUC_HMDF2_JO)
							$this->check_hmdf_duplicate($params['other_deduction_details'][$i], $formatted_value);
						// ====================== jendaigo : end : validation for MP2 reference ID ============= //
						
						$new_par[$i]['employee_deduction_id']        = $deduction_id;
						$new_par[$i]['other_deduction_detail_value'] = $params['other_deduction_details'][$i];
						$new_par[$i]['other_deduction_detail_id']    = $params['other_deduction_details'][$i+1];
					}
					$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_other_details, $new_par);
				}
				
				if(!EMPTY($params['payment_count_dtl'])) {
					// INSERT ALL DEDUCTION DETAILS
					$new_par 			= array();

					for($i=0; $i < count($params['payment_count_dtl']); $i++) {
						$new_par[$i]['employee_deduction_id'] 				= $deduction_id;
						$new_par[$i]['payment_count']         				= $params['payment_count_dtl'][$i];
						$new_par[$i]['amount']                				= $params['amount'][$i];
					}

					$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_details, $new_par);
					
					// ====================== jendaigo : start : include deduction detail deatils ============= //
					if($param_deductions['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
						$this->insert_employee_deduction_detail_details($params, $new_par);
					// ====================== jendaigo : end : include deduction detail deatils ============= //
				}

				//MESSAGE ALERT
				$message 		 = $this->lang->line('data_saved'); 
				$activity = "%s has been added";
			}
			else {
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('employee_deduction_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->deductions->get_deduction_data(array("*"), $table, $where, TRUE);

				// ====================== jendaigo : start : get paid count details ============= //
				//GET DEDUCTION PAID COUNT DETAILS BEFORE UPDATING THE RECORD
				if($param_deductions['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
				{
					$fields = array("c.attendance_period_hdr_id, c.employee_deduction_paid_count_dtl_id");
					$tables = array(
							'main'	=> array(
								'table'		=> $this->deductions->tbl_employee_deduction_details,
								'alias'		=> 'a',
							),
							't1'	=> array(
								'table'		=> $this->deductions->tbl_employee_deduction_detail_details,
								'alias'		=> 'b',
								'type'		=> 'LEFT JOIN',
								'condition'	=> 'b.employee_deduction_detail_id = a.employee_deduction_detail_id',
							),
							't2'	=> array(
								'table'		=> $this->deductions->tbl_employee_deduction_paid_count_details,
								'alias'		=> 'c',
								'type'		=> 'JOIN',
								'condition'	=> 'c.employee_deduction_detail_detail_id = b.employee_deduction_detail_detail_id',
							)
						);
						
					$where_dtl                      		= array();
					$where_dtl['a.employee_deduction_id']	= $prev_detail[0][0]['employee_deduction_id'];
					$employee_paid_count_details 			= $this->deductions->get_deduction_data($fields, $tables, $where_dtl);
				}
				// ====================== jendaigo : end : get paid count details ============= //

				//UPDATE DATA
				$this->deductions->update_deduction($table, $valid_data, $where);

				$this->deductions->delete_deduction($this->deductions->tbl_employee_deduction_other_details, $where);
				$this->deductions->delete_deduction($this->deductions->tbl_employee_deduction_details, $where);
				
				if(!EMPTY($params['other_deduction_details'])) {
					// INSERT ALL OTHER DEDUCTION DETAILS
					$new_par = array();

					for($i=0; $i < count($params['other_deduction_details']); $i+=2) {
						
						$formatted_value 	= $params['other_deduction_details'][$i]; //jendaigo : assigned variable for formatted reference ID
						$params = $this->remove_identification_format($params, $i); //jendaigo : remove format of ID input				
						
						// ====================== jendaigo : start : validation for MP2 reference ID ============= //
						if($valid_data['deduction_id'] == DEDUC_HMDF2_JO)
							$this->check_hmdf_duplicate($params['other_deduction_details'][$i], $formatted_value);
						// ====================== jendaigo : end : validation for MP2 reference ID ============= //
						
						$new_par[$i]['employee_deduction_id']        = $prev_detail[0][0]['employee_deduction_id'];
						$new_par[$i]['other_deduction_detail_value'] = $params['other_deduction_details'][$i];
						$new_par[$i]['other_deduction_detail_id']    = $params['other_deduction_details'][$i+1];
					}

					$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_other_details, $new_par);
				}

				if(!EMPTY($params['payment_count_dtl'])) {

					// INSERT ALL DEDUCTION DETAILS
					$new_par = array();
					for($i=0; $i < count($params['payment_count_dtl']); $i++) {
						$new_par[$i]['employee_deduction_id'] = $prev_detail[0][0]['employee_deduction_id'];
						$new_par[$i]['payment_count']         = $params['payment_count_dtl'][$i];
						$new_par[$i]['amount']                = $params['amount'][$i];
					}
					$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_details, $new_par);
				}


				// ====================== jendaigo : start : include deduction detail deatils ============= //
				if($param_deductions['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
					$this->insert_employee_deduction_detail_details($params, $new_par, $employee_paid_count_details);
				// ====================== jendaigo : end : include deduction detail deatils ============= //

				//MESSAGE ALERT
				$message = $this->lang->line('data_updated'); 
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->deductions->get_deduction_data(array("*"), $table, $where, TRUE); 
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been updated"; 
			}

			$activity = sprintf($activity, 'Deduction');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
			
			Main_Model::commit();
			$status = TRUE;
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		$data['msg'] = $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}
	
	public function process_certificate_request()
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
			$process_id = REQUEST_WORKFLOW_CERTIFICATE_CONTRIBUTION;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				//throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				//throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_requests($params);
			
			Main_Model::beginTransaction();
			$user_pds_id	= $this->session->userdata("user_pds_id");

			
			/*############################ START : GET EMPLOYEE DATA #############################*/

			$table						= $this->deductions->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $user_pds_id;
			$pds_data 					= $this->deductions->get_deduction_data(array("employee_id"), $table, $where, FALSE);
			
			/*############################ END : GET EMPLOYEE DATA #############################*/

			/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
			$fields 						= array();
			$fields['employee_id']			= $pds_data["employee_id"];
			$fields['request_type_id']		= REQUEST_CERTIFICATE_CONTRIBUTION;
			//$fields['process_id']			= $process_id;
			$fields['request_status_id']	= 1;
			$fields['date_requested']		= date("Y-m-d H:i:s");

			$table 							= $this->deductions->tbl_requests;
			$request_id						= $this->deductions->insert_deduction($table,$fields,TRUE);

			/*############################ END : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/

			/*############################ START : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/
			$quotient = 100 / $request_id;
			$addedZeroes = "";
			if ($quotient > 10) {
				$addedZeroes = "00";
			}
			elseif ($quotient > 0) {
				$addedZeroes = "0";
			}
			else {
				$addedZeroes = "";
			}
			
		
			$fields 				= array() ;
			$fields['request_code']	= date("Ym").$addedZeroes.$request_id;
			$where					= array();
			$where['request_id']	= $request_id;
			$table 				= $this->deductions->tbl_requests;

			$this->deductions->update_deduction($table,$fields,$where);

			/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

			/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
			$fields 							= array();
			$fields['employee_id']				= $pds_data["employee_id"];
			$fields['request_id']				= $request_id;
			$fields['request_sub_type_id']		= $valid_data["cert_type"];
			$fields['request_sub_status_id']	= SUB_REQUEST_NEW;
			$fields['action']					= ACTION_PROCESS;

			$table 								= $this->deductions->tbl_requests_sub;
			$request_sub_id						= $this->deductions->insert_deduction($table,$fields,TRUE);
			/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

			/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			$fields 						= array();
			$fields['request_sub_id']		= $request_sub_id;
			$fields['specific_details']		= $valid_data["purpose"];
			$fields['certfication_type_id']	= $valid_data["cert_type"];

			$table 							= $this->deductions->tbl_requests_certifications;
			$this->deductions->insert_deduction($table,$fields,FALSE);

			/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			

			/*SET UNHASED REQUEST ID TO $final_request_id */
			$final_request_id = $request_id;


			/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
			
			$workflow 		= $this->deductions->get_initial_task($process_id);
			

			$fields 					= array() ;
			$fields['request_id']		= $final_request_id;
			$fields['task_detail']		= $workflow['name'];
			$fields['process_id']		= $workflow['process_id'];
			$fields['process_stage_id']	= $workflow['process_stage_id'];
			$fields['process_step_id']	= $workflow['process_step_id'];
			$fields['task_status_id']	= 1;
                                        
			$table 						= $this->deductions->tbl_requests_tasks;
			$this->deductions->insert_deduction($table,$fields,FALSE);

			/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/

			 /*INSERT NOTIFICATION*/
			$request_notifications = modules::load('main/request_notifications');
			$request_notifications->insert_request_notification($request_id);

			
			$status = true;
			$message = "Request has been successfully submitted.";

			/*
			$audit_table[]			= $this->requests->tbl_employee_relations;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "%s has been added.";
			$audit_activity 		= sprintf($activity, $valid_data["relation_first_name"] . " ".$valid_data["relation_last_name"]);


			$status = true;
			$message = $this->lang->line('data_saved');

			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				*/
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
			RLog::error($message);
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_data($params)
	{
		if(EMPTY($params['deduction_id']))
			throw new Exception('
				Deduction Type is required.');	

		if(EMPTY($params['start_date']))
			throw new Exception('
				Start Date is required.');

		if(EMPTY($params['employee_id']))
			throw new Exception('
				Employee ID is required.');

		// if(EMPTY($params['payment_count']))
		// 	throw new Exception('
		// 		Number of payments is required.');

		return $this->_validate_input ($params);
	}

	private function _validate_add_personnel($params)
	{	
		if($params['action'] != ACTION_DELETE)
		{
			if(EMPTY($params['start_date']))
				throw new Exception('Start Date is required.');

		}

		return $this->_validate_input ($params);
	}

	private function _validate_input($params) {
		try {

			$validation ['deduction_id'] = array (
					'data_type' => 'string',
					'name' => 'Deduction Type',
					'max_len' => 11 
			);
			
			$validation ['start_date'] = array (
					'data_type' => 'date',
					'name' => 'Start Date'
			);

			$validation ['employee_id'] = array (
					'data_type' => 'string',
					'name' => 'Employee ID',
					'max_len' => 50 
			);
			
			$validation ['payment_count'] = array (
					'data_type' => 'digit',
					'name' => 'Number of payments',
					'max_len' => 5 
			);
			
			// ====================== jendaigo : start : validation for remarks ============= //
			$validation ['remarks'] = array (
					'data_type' => 'string',
					'name' => 'Remarks',
					'max_len' => 255 
			);
			// ====================== jendaigo : start : validation for remarks ============= //

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			
			throw $e;
		}
	}


	public function delete_employee_statutory()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$employee_id  		= $security_data[1];
			$token   			= $security_data[2];
			$salt  				= $security_data[3];
			$module 			= $security_data[4];
			$id  				= $security_data[5];
			$has_permission     = $security_data[7];
			$flag 				= 0;


			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ) or EMPTY ( $module ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action.'/'.$module, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->deductions->tbl_employee_deductions;

			$where				= array();
			$key 				= $this->get_hash_key('employee_deduction_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		  = $this->deductions->get_deduction_data(array("*"), $table, $where, TRUE);
			
			$this->deductions->delete_deduction($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			//$curr_detail[] 		 = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['deduction_id']);
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
				
			Main_Model::commit();
			$flag = 1;
				
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
		
			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
		$post_data = array(
				'employee_id' => $employee_id,
				'module'      => $module,
				'action_id'   => $action,
				'has_permission' => $has_permission
	 	);
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'table_statutory_details',
			"path"				=> PROJECT_MAIN . '/deductions/get_statutory_details/',
			"advanced_filter" 	=> true,
			"post_data"       => $post_data
		);
	
		echo json_encode($info);
	}

	public function delete_employee_other_deductions()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$employee_id  		= $security_data[1];
			$token   			= $security_data[2];
			$salt  				= $security_data[3];
			$module 			= $security_data[4];
			$id 				= $security_data[5];
			// $has_permission     = $security_data[7];
			$has_permission     = $security_data[6]; // jendaigo : fix selection of permission
			$flag 				= 0;
			
			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ) or EMPTY ( $module ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action . '/' . $module, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );


			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			
				$table           = $this->deductions->tbl_employee_deductions;
				
				$where           = array();
				$key             = $this->get_hash_key('employee_deduction_id');
				$where[$key]     = $id;
				
				$audit_action[]  = AUDIT_DELETE;
				$audit_table[]   = $table;
				$audit_schema[]  = DB_MAIN;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]   = $this->deductions->get_deduction_data(array("*"), $table, $where, TRUE);

				// ====================== jendaigo : start : data validation ============= //
				//GET DEDUCTION DETAILS
				$employee_list          = array($prev_detail[0][0]['employee_id']);
				$where_dtl          	= array();
				$key            		= $this->get_hash_key('a.employee_deduction_id');
				$where_dtl[$key]    	= $id;

				$this->validation_edit_delete_personnel_to_deductions($employee_list, null, $where_dtl, $action, TRUE);
				// ====================== jendaigo : end : data validation ============= //
				
				$this->deductions->delete_deduction($table, $where);
				$msg             = $this->lang->line('data_deleted');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				//$curr_detail[] = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);
			
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity        = "%s has been deleted";
				$activity        = sprintf($activity, $prev_detail[0][0]['deduction_id']);
				
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
				
			Main_Model::commit();
			$flag = 1;
				
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
		
			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
		
		$post_data = array(
				'employee_id' => $employee_id,
				'module'      => $module,
				'action_id'   => $action,
				'has_permission' => $has_permission
	 	);

		$info = array(
			"flag"            => $flag,
			"msg"             => $msg,
			"reload"          => 'datatable',
			"table_id"        => 'table_other_deduction',
			"path"            => PROJECT_MAIN . '/deductions/get_other_deduction_list',
			"advanced_filter" => true,
			"post_data"       => $post_data
		);

		echo json_encode($info);
	}


	private function _validate_requests($params)
	{
		

		if(EMPTY($params['cert_type']))
			throw new Exception('Certificate Type is required.');

		if(EMPTY($params['purpose']))
			throw new Exception('Purpose is required.');


		return $this->_validate_input_requests ($params);
	}

	private function _validate_input_requests($params) {
		
		try {
			

			$validation ['cert_type'] = array (
					'data_type' => 'string',
					'name' => 'Cert type',
					'max_len' => 45 
			);
			$validation ['purpose'] = array (
					'data_type' => 'string',
					'name' => 'purpose id',
					'max_len' => 255 
			);


			return $this->validate_inputs($params, $validation );

		} 
		catch ( Exception $e ) {
			
			throw $e;
		}
	}	
	
	public function get_identification_format(){
		try
		{

			$flag   	  = 0;
			$msg    	  = ERROR;
			$params = get_params();
			$tables 			= array(
					'main'			=> array(
							'table'		=> $this->deductions->tbl_param_deductions,
							'alias'		=> 'A',
					),
					't2'			=> array(
							'table'		=> $this->deductions->tbl_param_identification_types,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.identification_type_code = A.deduction_code'
					)
			);
			
			$where = array();
			$where['A.deduction_id'] = $params['deduction_id'];
			
			$result	= $this->deductions->get_deduction_data(array("B.format"), $tables, $where, FALSE);
			
			$flag 	= 1;
			$msg  	= SUCCESS;
		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		 = array(
			"flag"   => $flag,
			"msg" 	 => $msg,
			"format" => $result['format']
		);

		echo json_encode($info);
		
	}

	// ====================== jendaigo : start : format ID input ============= //
	public function remove_identification_format($params, $index)
	{
		// GETTING THE INPUT REAL VALUE OF A FORMATTED IDENTIFICATION REFERENCE
		$value_arr                       		= explode('|', $params['identification_type_id']);
		$concat                          		= explode('x', $value_arr[1]);
		$separator                       		= $concat[count($concat)-1];
			
		if($params['other_deduction_details'][$index+1] == $value_arr[0])
		{
			$params['other_deduction_details'][$index]	= str_replace($separator, '', $params['other_deduction_details'][0]);
		
			$length 		= array_sum(array_filter(explode('x', $value_arr[1]), 'is_numeric'));
			$input_length 	= mb_strlen($params['other_deduction_details'][$index]);
		
			if($input_length != $length)
			{
				$error_details = (($input_length == 1) ? '(you are currently using ' . $input_length . ' character).' : '(you are currently using ' . $input_length . ' characters).');
				throw new Exception('Please lengthen ID reference character to ' . $length . ' ' . $error_details);
			}
		}
		
		return $params;
	}
	// ====================== jendaigo : end : format ID input ============= //

	// ====================== jendaigo : start : validate mp2 reference input ============= //
	public function check_hmdf_duplicate($other_deduction_detail_value, $formatted_value)
	{
		//CHECK OTHER DEDUCTION DETAILS
		$fields = array("A.deduction_id", "B.other_deduction_detail_value AS value", "CONCAT(C.last_name, ', ', C.first_name, ' ', LEFT(C.middle_name, (1)), '. ') full_name" );
		$tables = array(
				'main'	=> array(
					'table'		=> $this->deductions->tbl_employee_deductions,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->deductions->tbl_employee_deduction_other_details,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.employee_deduction_id = A.employee_deduction_id',
				),
				't3'	=> array(
					'table'		=> $this->deductions->tbl_employee_personal_info,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'C.employee_id = A.employee_id',
				)
			);
			
		$where                      				= array();
		$where['A.deduction_id'] 					= array(array(DEDUC_HMDF1_JO,DEDUC_HMDF2_JO), array('IN'));
		$where['B.other_deduction_detail_value'] 	= $other_deduction_detail_value;
		$other_deduction_details 					= $this->deductions->get_deduction_data($fields, $tables, $where);
		
		//CHECK EMPLOYEE IDENTIFICATIONS
		$fields = array("REPLACE(A.identification_type_id, 4, 3) AS deduction_id", "A.identification_value AS value", "CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, (1)), '. ') full_name" );
		$tables = array(
				'main'	=> array(
					'table'		=> $this->deductions->tbl_employee_identifications,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->deductions->tbl_employee_personal_info,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.employee_id = A.employee_id',
				)
			);
			
		$where                      				= array();
		$where['A.identification_type_id'] 			= 4; //hmdf
		$where['A.identification_value'] 			= $other_deduction_detail_value;
		$employee_identifications 					= $this->deductions->get_deduction_data($fields, $tables, $where);
		
		$param_duplicates	= array_unique(array_merge($other_deduction_details, $employee_identifications), SORT_REGULAR);
		
		foreach($param_duplicates as $param_duplicate):
			switch($param_duplicate['deduction_id']) {
				case DEDUC_HMDF1_JO:
				throw new Exception('Pag-ibig No. ' . $formatted_value . ' is already assigned to employee ' . $param_duplicate['full_name'] . '.');
			break;
			case DEDUC_HMDF2_JO:
				throw new Exception('MP2 Savings Account No. ' . $formatted_value . ' is already assigned to employee ' . $param_duplicate['full_name'] . '.');
				break;
			}
		endforeach;
	}
	// ====================== jendaigo : end : validate mp2 reference input ============= //
	
	// ====================== jendaigo : start : include deduction details conditions ============= //
	public function insert_employee_deduction_detail_details($params, $new_par, $employee_paid_count_details=array())
	{
		$field				= array("*");
		$table				= $this->deductions->tbl_employee_deduction_details;
		
		$paid_count_key			= 0;
		$paid_count_par_details	= array();

		foreach ($new_par as $key => $new_par):
		
			if($new_par['payment_count'] == 0)
				throw new Exception('No. of Payment should be greater than zero.');
			
			if($params['paid_count_dtl'][$key] > $new_par['payment_count'])
				throw new Exception('Row ' . ($key+1) . ': Paid count should not be greater than No. of Payment.');

			if($params['detail_start_date'][$key] < $params['start_date'])
				throw new Exception('Row ' . ($key+1) . ': Start of Deduction should not be lower than Deduction Start Date.');

			$where							= array();
			$where['employee_deduction_id'] = $new_par['employee_deduction_id'];	
			$employee_deduction_details     = $this->deductions->get_deduction_data($field, $table, $where, TRUE);

			$new_par_details	= array();
			$new_par_details[$key]['employee_deduction_detail_id']	= $employee_deduction_details[$key]['employee_deduction_detail_id'];
			$new_par_details[$key]['deduction_detail_type_id']		= $params['deduction_detail_type_id'][$key];
			$new_par_details[$key]['start_date']					= $params['detail_start_date'][$key];
			$new_par_details[$key]['paid_count']         			= $params['paid_count_dtl'][$key];
			$new_par_details[$key]['remarks']						= $params['remarks'][$key];
			
			$employee_deduction_detail_detail_id = $this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_detail_details, $new_par_details, TRUE);

			if($new_par_details[$key]['paid_count'] != 0)
			{
				for ($x = 1; $x <= $new_par_details[$key]['paid_count']; $x++)
				{
					$paid_count_par_details[$paid_count_key]['employee_deduction_paid_count_dtl_id'] 	= $employee_paid_count_details[$paid_count_key]['employee_deduction_paid_count_dtl_id'];
					$paid_count_par_details[$paid_count_key]['employee_deduction_detail_detail_id'] 	= $employee_deduction_detail_detail_id;
					$paid_count_par_details[$paid_count_key]['attendance_period_hdr_id'] 				= $employee_paid_count_details[$paid_count_key]['attendance_period_hdr_id'];
					$paid_count_key += 1;
				}
			}
		endforeach;

		if($paid_count_par_details)
		$this->deductions->insert_deduction($this->deductions->tbl_employee_deduction_paid_count_details, $paid_count_par_details);
	}
	// ====================== jendaigo : end : include deduction details conditions ============= //

	// ====================== jendaigo : start : validations for edit and delete personnel to deductions ============= //
	public function validation_edit_delete_personnel_to_deductions($employee_list, $deduction_name, $where, $action, $assign_type=FALSE)
	{
		//SET ACTION ACTIVITY
		if($action == ACTION_EDIT)
			$action_activity = 'edit';
		else
			$action_activity = 'delete';
		
		$fields	= array("CONCAT(b.last_name, ', ', b.first_name, ' ', LEFT(b.middle_name, (1)), '. ') full_name, a.payment_count, a.paid_count, count(c.employee_deduction_detail_id) as multi_row");
		$tables = array(
			'main' => array(
				'table' => $this->deductions->tbl_employee_deductions,
				'alias' => 'a'
			),
			't1'   => array(
				'table' => $this->deductions->tbl_employee_personal_info,
				'alias' => 'b',
				'type'  => 'JOIN',
				'condition' => 'b.employee_id = a.employee_id'
			),
			't2'   => array(
				'table' => $this->deductions->tbl_employee_deduction_details,
				'alias' => 'c',
				'type'  => 'LEFT JOIN',
				'condition' => 'c.employee_deduction_id = a.employee_deduction_id'
			)
		);
			
		$where          			= $where;
		$where['a.employee_id'] 	= array($employee_list, array('IN'));
		$order_by 				   	= array('full_name' => 'ASC');
		$group_by 				   	= array('full_name');
		$employee_deductions		= $this->deductions->get_deduction_data($fields, $tables, $where, TRUE, $order_by, $group_by);

		$err_msg            	= '';
		foreach($employee_deductions as $key => $employee_deduction)
		{
			//MULTI ROW VALIDATION
			if($employee_deduction['multi_row'] > 1)
			{
				$err_msg['multi_row']['deduction_name']	 = $deduction_name;
				$err_msg['multi_row']['employee_list']  .= '<br>'.$employee_deduction['full_name'];
			}
			
			//PAID COUNT VALIDATION
			if($employee_deduction['paid_count'] > 0)
			{
				$err_msg['paid_count']['deduction_name']	= $deduction_name;
				$err_msg['paid_count']['employee_list']    .= '<br>'.$employee_deduction['full_name'];
			}
		}

		//ERROR MESSAGE VIEW	
		if(!empty($err_msg['paid_count']))
		{
			if($assign_type==TRUE)
				throw new Exception('Unable to ' .$action_activity. ': Deduction is being used by a generated payroll.');
			else
				throw new Exception('Unable to ' .$action_activity. ' ' . $err_msg['paid_count']['deduction_name'] . ': Deduction is being used by a generated payroll. <br>' . $err_msg['paid_count']['employee_list']);
		}
		
		if(!empty($err_msg['multi_row']) AND ($assign_type==FALSE))
			throw new Exception('Unable to ' .$action_activity. ' ' . $err_msg['multi_row']['deduction_name'] . ': Please remove employee(s) with multiple deduction details. <br>' . $err_msg['multi_row']['employee_list']);
	}
	// ====================== jendaigo : end : validations for edit and delete personnel to deductions ============= //
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */