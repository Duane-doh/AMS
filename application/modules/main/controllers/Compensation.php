<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compensation extends Main_Controller {

	private $log_user_id		=  '';
	private $log_user_roles		= array();
	//private $module = MODULE_COMPENSATION;

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('compensation_model', 'compensation');
		$this->load->model('payroll_model', 'payroll');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}


	public function index()
	{

		try
		{

			$data 			= array();
			$resources 		= array();
			$data['page'] 	= $page;
			
			$resources['load_css'] 	= array(CSS_SELECTIZE, CSS_DATATABLE);
			$resources['load_js'] 	= array(JS_SELECTIZE, JS_DATATABLE);

			$data['action']			= ACTION_VIEW;
			$data['id']				= DEFAULT_ID;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/Compensation";
			$key					= "Compensations"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/Compensation";
			set_breadcrumbs($breadcrumbs, TRUE);
			
			$fields = array('A.office_id','B.name AS office_name');
			$tables = array(
				'main' => array(
					'table' => $this->compensation->tbl_param_offices,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->compensation->db_core . '.' . $this->compensation->tbl_organizations,
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
			
			$user_office_scope = explode(',',$user_scopes['compensation']);
			$where['A.office_id'] = array($user_office_scope, array('IN'));
			//end
			
			$data['office_list'] = $this->compensation->get_compensation_data($fields, $tables, $where);
			
			$this->template->load('compensation/compensation', $data, $resources);

		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
		
		
	}

	public function get_tab($form)
	{
	
		try
		{
			$data 					= array();
			$resources['load_css'] 	= array(CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_LABELAUTY);
			

			switch ($form)
			{
				case 'employee_list':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$resources['datatable'][]	= array('table_id' => 'table_compensation_employee_list', 'path' => 'main/compensation/get_employee_list', 'advanced_filter' => TRUE);
					break;

				case 'compensation_type_list':
					$resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE);
					$resources['load_js'] 		= array(JS_DATATABLE,JS_SELECTIZE);
					$resources['load_modal'] = array(
					'modal_benefits_filters_by' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_benefits_filters_by',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'lg',
						'title'			=> 'Benefits Details'
					)		

				);
				$resources['datatable'][]	= array('table_id' => 'table_benefit_type_list', 'path' => 'main/compensation/get_benefit_list', 'advanced_filter' => TRUE);
				break;

	
			}
	
	
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}

		$this->load->view('compensation/'.$form, $data);
		$this->load_resources->get_resource($resources);
	}


	public function get_employee_tab($form, $action, $employee_id, $token, $salt, $module, $has_permission=FALSE)
	{
	
		try
		{
			$data 					= array();

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['has_permission']	= $has_permission;
			

			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				 throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				 throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			

		 	$post_data = array(
		 		'employee_id' => $employee_id,
			 	'module' => $module
		 	);
	
			switch ($form)
			{
				case 'employee_salary':

					$resources['load_js'] = array('jquery.number.min');
					
					$field                = array("B.position_name", "A.employ_salary_grade"," A.employ_salary_step", "A.employ_monthly_salary", "A.employ_start_date", "A.employ_end_date");
					$tables = array(
						'main'	=> array(
							'table'		=> $this->compensation->tbl_employee_work_experiences,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->compensation->tbl_param_positions,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'B.position_id = A.employ_position_id',
						),
						
					);
					$where  						= array();
					$key    						= $this->get_hash_key('employee_id');
					$where[$key]					= $employee_id;
					$where['employ_end_date']	    = 'IS NULL';
					$data['employee_salary_info'] 	= $this->compensation->get_compensation_data($field, $tables, $where, FALSE);
					
				
					$view_form = $form;
					break;
					
				// ====================== jendaigo : start : add employee bank account, responsibility code encoding ============= //
				case 'employee_bank':
					
					$resources['datatable'][]	= array(
						'table_id' => 'table_employee_bank', 
						'path' => 'main/compensation/get_employee_bank_acc', 
						'advanced_filter' => TRUE, 
						'post_data' => json_encode($post_data)
					);
					
					$resources['load_modal'] = array(
						'modal_employee_bank_acc' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_employee_bank_acc',
							'multiple'		=> true,
							'height'		=> '400px',
							'size'			=> 'sm',
							'title'			=> 'Bank Account Details'
						)	
					);
	
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_employee_bank_acc',
						PROJECT_MAIN
					);
					
					$view_form = $form;
					break;
					
				case 'employee_responsibility_code':
					
					$resources['datatable'][]	= array(
						'table_id' => 'table_employee_responsibility_code', 
						'path' => 'main/compensation/get_employee_responsibility_code', 
						'advanced_filter' => TRUE, 
						'post_data' => json_encode($post_data)
					);
					
					$resources['load_modal'] = array(
						'modal_employee_responsibility_code' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_employee_responsibility_code',
							'multiple'		=> true,
							'height'		=> '400px',
							'size'			=> 'sm',
							'title'			=> 'Responsibility Code Details'
						)	
					);
	
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_employee_responsibility_code',
						PROJECT_MAIN
					);
					
					$view_form = $form;
					break;	
					
				// ====================== jendaigo : end : add employee bank account and responsibility code encoding ============= //
				
				case 'employee_benefits':
					
					$field 	= array("B.compensation_name", "A.start_date"," A.end_date", "A.employee_compensation_id", "A.compensation_id") ;
					$tables = array(
						'main'	=> array(
							'table'		=> $this->compensation->tbl_employee_compensations,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->compensation->tbl_param_compensations,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.compensation_id = A.compensation_id',
						)
						
					);
					$where                                     = array();
					$key                                       = $this->get_hash_key('employee_id');
					$where[$key]                               = $employee_id;
					
					$where['ifnull(A.end_date, current_date)'] = array(date('Y-m-d'), array('>='));
					$employee_benefits                         = $this->compensation->get_compensation_data($field, $tables, $where);
					
					$ids = array();
					foreach ($employee_benefits as $value) 
					{
						$ids[] = $value['compensation_id'];
					}
					// GET UNAVAILED COMPENSATIONS
					$where = array();
					if(!EMPTY($ids))
					{
						$where['compensation_id']       	   = array($ids, array("NOT IN"));
					}
					$where['employee_flag']         = YES;
					$where['active_flag']           = YES;
					$data['compensation_type_list'] = $this->compensation->get_compensation_data(array('*'), $this->compensation->tbl_param_compensations, $where);
					
					$data['employee_benefits']      = $employee_benefits;

					// RE-PRODUCE NEW SECURITY PARAMS
					$salt                = gen_salt();
					$token               = in_salt($employee_id  . '/' . $action  . '/' . $module, $salt);
					
					$data['salt']        = $salt;
					$data['token']       = $token;
					$data['employee_id'] = $employee_id;
					$data['module']      = $module;
					$data['action']      = $action;
					
					$view_form = $form;
					break;

					case 'request_certificate':

					//GET PERVEIOUS RECORD
					$field 							= array("*") ;
					$table							= $this->compensation->tbl_param_request_sub_types;
					$where							= array();
					$where['request_type_id']	    = array(array(REQUEST_CERTIFICATE_EMPLOYMENT, REQUEST_SERVICE_RECORD), array('IN'));;
					$data['certificate_type_list'] 	= $this->compensation->get_compensation_data($field, $table, $where, TRUE);
				
					$view_form = $form;
					break;

					case 'compensation_history':
					$resources['datatable'][]	= array('table_id' => 'table_compensation_history', 'path' => 'main/compensation/get_compensation_history_list', 'advanced_filter' => TRUE, 'post_data' => json_encode($post_data));
					
					$view_form = $form;
					break;

					case 'payslip_history':

					$resources['datatable'][]	= array('table_id' => 'table_payslip_history', 'path' => 'main/compensation/get_employee_payslip_history_list', 'advanced_filter' => TRUE, 'post_data' => json_encode($post_data));

						$resources['load_modal'] = array(
						
						'modal_employee_payslip_history' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_employee_payslip_history',
							'multiple'		=> true,
							'height'		=> '400px',
							'size'			=> 'md',
							'title'			=> 'Payslip History Details'
						)	
					);

					$view_form = $form;
					break;

					
			}

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_DATATABLE, CSS_SELECTIZE);
			$resources['load_js']   = array(JS_DATETIMEPICKER,JS_DATATABLE, JS_SELECTIZE);
	
			$this->load->view('compensation/tabs/'.$view_form, $data);
			$this->load_resources->get_resource($resources);
	
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	}


	public function get_employee_list()
	{

		try
		{
			
			$params         = get_params();
			$module         = MODULE_HR_COMPENSATION;
			// $aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname", "ifnull(B.employ_office_name,E.name) office_name", "D.employment_status_name", "B.employ_office_id");
			// $bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name,' ',LEFT(A.middle_name,1), '.')", "E.name", "D.employment_status_name");
			
			// ====================== jendaigo : start : change name format ============= //
			$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name))) as fullname", "ifnull(B.employ_office_name,E.name) office_name", "D.employment_status_name", "B.employ_office_id");
			$bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name)))", "E.name", "D.employment_status_name");
			// ====================== jendaigo : end : change name format ============= //
			
			$employee_list  = $this->compensation->get_employee_list($aColumns, $bColumns, $params, $module);
			$iFilteredTotal = $this->compensation->filtered_length($aColumns, $bColumns, $params, $module);
			$iTotal         = $this->compensation->total_length();

			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
	
			
			$permission_view 	= $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit 	= $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete 	= $this->permission->check_permission($module, ACTION_DELETE);
		
			
			$user_offices = $this->session->userdata('user_offices');
			
			foreach ($employee_list as $aRow):
				
				$row = array();
				
				$employee_id 	= $this->hash($aRow['employee_id']);
				
				$salt			= gen_salt();
				$token_view	 	= in_salt($employee_id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($employee_id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$employee_id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$employee_id ."/".$token_edit."/".$salt."/".$module;



				$row[]      = $aRow['agency_employee_id'];
				$row[]      = strtoupper($aRow['fullname']);
				$row[]      = strtoupper($aRow['office_name']);
				$row[]      = $aRow['employment_status_name'];

				
				$action = "<div class='table-actions'>";
			
				$action 	.= "<a href='javascript:;' data-tooltip='view' class='view tooltipped' onclick=\"content_form('compensation/employee_tabs/".$url_view ."', '".PROJECT_MAIN."')\"></a>";
				
				$office_list = explode(',', $user_offices[$module]);

				if(in_array($aRow['employ_office_id'],$office_list ))
				$action 	.= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped' onclick=\"content_form('compensation/employee_tabs/".$url_edit ."', '".PROJECT_MAIN."')\"></a>";
						
				$action .= "</div>";
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage);
		}

		echo json_encode( $output );
	}


	public function get_benefit_employee_list($action, $compensation_id, $token, $salt, $module)
	{
		try
		{
			$params                = get_params();
			
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY);
			$resources             = $this->load_resources->get_resource($resources, TRUE);
			
			// $aColumns              = array("EC.compensation_id", "PI.agency_employee_id", "CONCAT(PI.last_name,if(PI.ext_name='','',CONCAT(' ',PI.ext_name)),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.') as fullname",  "WE.employ_office_name", "EC.start_date", "EC.end_date");
			// $bColumns              = array("PI.agency_employee_id", "CONCAT(PI.last_name, if(PI.ext_name='','',CONCAT(' ',PI.ext_name)),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.')", "WE.employ_office_name", "EC.start_date",  "EC.end_date");
			
			// ====================== jendaigo : start : change name format ============= //
			$aColumns              = array("EC.compensation_id", "PI.agency_employee_id", "CONCAT(PI.last_name, ', ', PI.first_name, IF(PI.ext_name='', '', CONCAT(' ', PI.ext_name)), IF((PI.middle_name='NA' OR PI.middle_name='N/A' OR PI.middle_name='-' OR PI.middle_name='/'), '', CONCAT(' ', LEFT(PI.middle_name, 1), '. '))) as fullname",  "WE.employ_office_name", "EC.start_date", "EC.end_date");
			$bColumns              = array("PI.agency_employee_id", "CONCAT(PI.last_name, ', ', PI.first_name, IF(PI.ext_name='', '', CONCAT(' ', PI.ext_name)), IF((PI.middle_name='NA' OR PI.middle_name='N/A' OR PI.middle_name='-' OR PI.middle_name='/'), '', CONCAT(' ', LEFT(PI.middle_name, 1), '. ')))", "WE.employ_office_name", "EC.start_date",  "EC.end_date");
			// ====================== jendaigo : end : change name format ============= //
			
			$employee_benefits     = $this->compensation->get_benefit_employee_list($aColumns, $bColumns, $params);			
			$iTotal                = $this->compensation->employees_total_length($params['compensation_id']);
			$iFilteredTotal        = $this->compensation->employees_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST["sEcho"]),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module = MODULE_HR_COMPENSATION;

			foreach ($employee_benefits as $aRow):
				$cnt++;
				$row                = array();
				
				$row[]              = $aRow['agency_employee_id'];
				$row[]              = ucwords($aRow['fullname']);
				$row[]              = strtoupper($aRow['employ_office_name']);
				$row[]              = '<center>' . format_date($aRow['start_date']) . '</center>';
				$row[]              = '<center>' . format_date($aRow['end_date']) . '</center>';
				$row[]              = '';				
				
				
				$output['aaData'][] = $row;
			endforeach;
		
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}

		echo json_encode( $output );
	}

	public function get_personnel_list()
	{
		try
		{
			$params        = get_params();
			
			$status        = 0;
			$employee_list = $this->compensation->get_personnel_list($params);
			
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
			"counter"       => count($employee_list)
		);
	
		echo json_encode($info);
	}

	public function get_specific_personnel()
	{
		try
		{
			$params				= get_params();
			$list 				= array();
			$append_personnnel_list = "";
			$region_code 			= $params['region_code'];

			
			$employee_list       		= $this->compensation->get_specific_personnel_list($params);
			
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
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}

		$info = array(
				"list" => $list,
				"append_personnnel_list" => $append_personnnel_list
		);
	
		echo json_encode($info);
	}

	/**************************************************EMPLOYEE TAB***************************************************/

	public function get_employee_compensation_list()
	{

		try
		{
			$params 		= get_params();


			$aColumns		= array("EC.employee_id", "EC.employee_compensation_id", "PB.compensation_name", "EC.start_date", "EC.end_date");
			$bColumns		= array("PB.compensation_name", "EC.start_date",  "EC.end_date");
			$table 	  		= $this->compensation->tbl_employee_compensations;
			
			$where			= array();
			$benefit 		= $this->compensation->get_employee_compensation_list($aColumns, $bColumns, $params);
			$iTotal   		= $this->compensation->get_employee_compensation_list(array("COUNT(DISTINCT(EC.compensation_id)) as count"), $bColumns, $params, $table, $where, TRUE);

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["count"],
				"iTotalDisplayRecords" => count($benefit),
				"aaData"               => array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_COMPENSATION, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_COMPENSATION, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_COMPENSATION, ACTION_DELETE);

			//$module = MODULE_HR_COMPENSATION;
			$cnt = 0;

			$employee_id = $params['employee_id'];
			$module = $params['module'];

			foreach ($benefit as $aRow):
				$cnt++;
				$row = array();
				$action = "";

				$id 			= $this->hash($aRow['employee_compensation_id']);

				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete   = in_salt($id  . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$employee_id ."/".$token_view."/".$salt."/".$module."/".$id;
				$url_edit 		= ACTION_EDIT."/".$employee_id ."/".$token_edit."/".$salt."/".$module."/".$id;
				$url_delete 	= ACTION_DELETE."/".$employee_id ."/".$token_delete."/".$salt."/".$module."/".$id;
				$delete_action	= 'content_delete("Employee Compensation", "'.$url_delete.'")';
					
				$row[] = $aRow['compensation_name'];
				$row[] = '<center>' . $aRow['start_date'] . '</center>';
				$row[] = '<center>' . $aRow['end_date'] . '</center>';

				$action = "<div class='table-actions'>";
				
				 
					
					$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_employee_benefits' data-tooltip='view' data-position='bottom' data-delay='50' onclick=\"modal_employee_benefits_init('".$url_view."')\"></a>";
				
				 if($module != MODULE_PERSONNEL_PORTAL):

					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_employee_benefits' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employee_benefits_init('".$url_edit."')\"></a>";
					$action .= "<a href='javascript:;' onclick='" . $delete_action . "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				 endif;

				$action .= "</div>";
				if($cnt == count($benefit)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS,JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}

		echo json_encode( $output );

	}

	public function delete_employee_benefit()
	{
		try
		{

			$params				= get_params();

				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table                             = $this->compensation->tbl_employee_compensations;
			
			$where                             = array();
			$where['employee_compensation_id'] = $params['employee_compensation_id'];
			
			$audit_action[]                    = AUDIT_DELETE;
			$audit_table[]                     = $table;
			$audit_schema[]                    = DB_MAIN;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[] = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);
			
			$this->compensation->delete_compensation($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			//$curr_detail[] 		 = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, 'Employee\'s Benefit');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				MODULE_HR_COMPENSATION, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
				
			Main_Model::commit();
			$flag = 1;
				
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg = $this->rlog_error($e, TRUE);
			RLog::error($msg);
		}
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg
		);
	
		echo json_encode($info);
	}


	public function get_compensation_type_list()
	{

		try
		{
			$params 		= get_params();

			$aColumns		= array("EC.employee_id", "PB.compensation_name", "EC.start_date",  "PF.frequency_name", "PB.taxable_flag");
			$bColumns		= array("PB.compensation_name", "EC.start_date", "PF.frequency_name", "PB.taxable_flag");
			$table 	  		= $this->compensation->tbl_employee_compensations;
			
			$where			= array();
			$benefit 		= $this->compensation->get_compensation_type_list($aColumns, $bColumns, $params);
			$iTotal   		= $this->compensation->get_compensation_type_list(array("COUNT(DISTINCT(EC.compensation_id)) as count"), $bColumns, $params, $table, $where, TRUE);
		
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["count"],
				"iTotalDisplayRecords" => count($benefit),
				"aaData" => array()
			);
			$cnt = 0;

			$module = MODULE_HR_COMPENSATION;

			$employee_id = $params['employee_id'];

			foreach ($benefit as $aRow):
				$cnt++;
				$row = array();
				$action = "";
 
				$action = "<div class='table-actions'>";
	
				$id 			= $this->hash($aRow['compensation_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete 	= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action	= 'content_delete("Employee Compensation", "'.$url_delete.'")';

					
				$row[] = $aRow['compensation_name'];
				$row[] = $aRow['start_date']; 
				$row[] = $aRow['frequency_name'];
				$row[] = ($aRow['taxable_flag'] == "Y") ? "YES":"No";

				if($aRow['compensation_type_flag'] == 'F') { 

					$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_fixed_benefits' data-tooltip='view' data-position='bottom' data-delay='50' onclick=\"modal_fixed_benefits_init('".$url_view."')\"></a>";
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_fixed_benefits' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_fixed_benefits_init('".$url_edit."')\"></a>";
					$action .= "<a h ref='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
					
				} if($aRow['compensation_type_flag'] == 'V') {
					$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_variable_benefits' data-tooltip='view' data-position='bottom' data-delay='50' onclick=\"modal_variable_benefits_init('".$url_view."')\"></a>";
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_variable_benefits' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_variable_benefits_init('".$url_edit."')\"></a>";
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				// if($this->permission->check_permission(MODULE_USER, ACTION_DELETE))
					
				
				$action .= "</div>";
				if($cnt == count($benefit)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS,JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}

		echo json_encode( $output );

	}
	

	public function get_compensation_history_list()
	{
	
		try
		{
			$params = get_params();

			// $aColumns		= array("A.amount", "DATE_FORMAT(A.effective_date, '%Y/%m/%d') as effective_date", "C.compensation_name");
			$aColumns		= array("A.amount", "DATE_FORMAT(A.effective_date, '%Y/%m/%d') as effective_date", "C.compensation_name", "C.report_short_code", "B.payroll_summary_id"); //jendaigo : get report_short_code and payout_summary_id
			$bColumns		= array("C.compensation_name", "DATE_FORMAT(A.effective_date, '%Y/%m/%d')", "A.amount");
			$benefit 		= $this->compensation->get_compensation_history_list($aColumns, $bColumns, $params);
// 			$iTotal   		= $this->compensation->get_compensation_history_list(array("COUNT(A.compensation_id) as count" ), $bColumns, $params, FALSE);
			$iTotal			= $this->compensation->compensation_history_total_length();
			$iFilteredTotal  = $this->compensation->compensation_history_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal['cnt'],
				"iTotalDisplayRecords" => $iFilteredTotal['cnt'],
				"aaData"               => array()
			);

			$cnt = 0;

			foreach ($benefit as $aRow):
				$cnt++;
				$row = array();
				$action = "";

				// $row[] = strtoupper($aRow['compensation_name']);

				// ====================== jendaigo : start : modify compensation label for BS ============= //
				if($aRow['report_short_code'] == 'BS')
					$compensation_name	= $this->compensation->get_employee_compensations($params, $aRow);
				else
					$compensation_name = strtoupper($aRow['compensation_name']);
				
				$row[] = 'NET SALARY ('.$compensation_name.')';
				// ====================== jendaigo : end : modify compensation label for BS ============= //

				$row[] = '<center>' . format_date($aRow['effective_date']) . '</center>';
				$row[] = '<p class="m-n right">&#8369; ' . number_format($aRow['amount'],2) . '</p>'; 

				if($cnt == count($benefit)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS,JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}
					
				$output['aaData'][] = $row;
			endforeach;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	
		echo json_encode( $output );
	}

// ====================== jendaigo : start : include employee bank account encoding ============= //
	public function get_employee_bank_acc()
	{
		try
		{
			$params         = get_params();

			$aColumns       = array("A.employee_identification_id","A.employee_id","B.identification_type_name", "A.identification_value", "B.identification_type_id", "B.format", "DATE_FORMAT(C.start_date,'%Y/%m/%d') as start_date", "IFNULL(DATE_FORMAT(C.end_date, '%Y/%m/%d'), 'PRESENT') as end_date", "C.remarks");
			$bColumns       = array("C.start_date", "C.end_date", "A.identification_value", "C.remarks");

			$identification = $this->compensation->get_bank_acc_list($aColumns, $bColumns, $params);

			$iTotal         = $this->compensation->bank_acc_total_length();
			$iFilteredTotal = $this->compensation->bank_acc_filtered_length($aColumns, $bColumns, $params);
			
			$output 					= array(
				"sEcho"                	=> intval($_POST['sEcho']),
				"iTotalRecords"        	=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData"               	=> array()
			);

			$employee_id 	= $params['employee_id'];
			$module    		= $params['module'];
			$id_action 		= $this->session->userdata("pds_action");

			$permission_edit 	= $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete 	= $this->permission->check_permission($module, ACTION_DELETE);
			$permission_view 	= $this->permission->check_permission($module, ACTION_VIEW);


			$cnt        = 0;
			foreach ($identification as $aRow):
				$cnt++;
				$row          = array();
				$action       = "";
				
				$id           = $this->hash($aRow['employee_identification_id']);
				
				$salt         = gen_salt();
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				$token_view	  = in_salt($id . '/' . ACTION_VIEW  . '/' . $module, $salt);

				$url_edit     = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete   = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;
				$url_view 	  = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;

				$row[]        = $aRow['start_date'];
				$row[]        = $aRow['end_date'];
				$row[]        = strtoupper(format_identifications($aRow['identification_value'],$aRow['format']));
				$row[]        = strtoupper($aRow['remarks']);
				
				$action       = "<div class='table-actions'>";

				if($id_action != ACTION_VIEW)
				{
					if($permission_view)
						$action	.= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_employee_bank_acc' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_employee_bank_acc_init('".$url_view."')\"></a>";
					
					if($cnt == $iFilteredTotal["cnt"])
					{
						if($permission_edit)
							$action	.= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_employee_bank_acc' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employee_bank_acc_init('".$url_edit."')\" ></a>";
						if($permission_delete)
							$action	.= "<a href='javascript:;' onclick='".'content_delete("Account Number", "'.$url_delete.'")'."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50' ></a>";
					}
				}
				$action .= "</div>";

				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		}
		catch (Exception $e)
		{
			$output 					= array(
				"sEcho"               	=> intval($_POST['sEcho']),
				"iTotalRecords"       	=> 0,
				"iTotalDisplayRecords"	=> 0,
				"aaData"              	=> array()
			);
		}

		echo json_encode( $output );
	}
	
	public function modal_employee_bank_acc($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field              = array("*");
				$table 				= array(
					'main' 			=> array(
						'table' 	=> $this->compensation->tbl_employee_identifications,
						'alias' 	=> 'A'
					),
					't1' 			=> array(
						'table'		=> $this->compensation->tbl_employee_identification_details,
						'alias'		=> 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employee_identification_id = A.employee_identification_id'
					)
				);
				
				$where                  = array();
				$key                    = $this->get_hash_key('A.employee_identification_id');
				$where[$key]            = $id;

				$identification         = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
				$data['identification'] = $identification;

			}

			//GET IDENTIFICATION TYPE FOR BANK ACCOUNT
			$field 							 = array("identification_type_id", "format") ;
			$table							 = $this->compensation->tbl_param_identification_types;
			$where							 = array();
			$where['builtin_flag']			 = NO;
			$where['active_flag'] 		 	 = YES;		
			$where['identification_type_id'] = BANKACCT_TYPE_ID ;					
			$data['identification_type'] 	 = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			
			$this->load_resources->get_resource($resources);
			$this->load->view('compensation/modals/modal_employee_bank_acc', $data);

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
	
	public function process_employee_bank_acc()
	{

		try
		{
			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) 
			{
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ))
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) 
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}
			
			// GETTING THE REAL VALUE OF AN IDENTIFICATION FROM THE VALUE WITH FORMATTING
			$value_arr                        = explode('|', $params['identification_type_id']);
			$concat                           = explode('x', $value_arr[1]);
			$separator                        = $concat[count($concat)-1];
			$params['identification_value']   = str_replace($separator, '', $params['identification_value']);
			$params['identification_type_id'] = $value_arr[0];

			//GET UNHASHED EMPLOYEE_ID
			$field 			= array("employee_id", "CONCAT(first_name, IF((middle_name='NA' OR middle_name='N/A' OR middle_name='-' OR middle_name='/'), '', CONCAT(' ', LEFT(middle_name, 1), '.')), ' ', last_name, IF(ext_name='', '', CONCAT(' ', ext_name))) as fullname ");
			$table 		 	= $this->compensation->tbl_employee_personal_info;
			$where  		= array();
			$key    		= $this->get_hash_key('employee_id');
			$where[$key]	= $params['employee_id'];

			$employee_id_unhashed 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$employee_id 			= $employee_id_unhashed['employee_id'];
			$employee_name 			= strtoupper($employee_id_unhashed['fullname']);
			
			// SERVER VALIDATION
			$valid_data = $this->_validate_add_bank_acc($params);
			
			// INPUT VALIDATIONS
			$previous_data = $this->_addtl_validate_input_bank_accs($params, $valid_data);

			//SET FIELDS VALUE
			//MAIN
			$fields['employee_id']       		= $employee_id;
		 	$fields['identification_type_id']   = $params['identification_type_id'];
			$fields['identification_value']     = $valid_data['identification_value'];
		
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->compensation->tbl_employee_identifications;
			$table_dtls 	= $this->compensation->tbl_employee_identification_details;
			
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;

			if($params['id'] == DEFAULT_ID) //DEFAULT_ID
			{
				//------- INSERT MAIN -------//

					//SET AUDIT TRAIL DETAILS
					$audit_action[]	= AUDIT_INSERT;
					
					$prev_detail[]	= array();

					//INSERT DATA
					$employee_identification_id = $this->compensation->insert_compensation($table, $fields, TRUE);

					//WHERE VALUES
					$where 	 			= array();
					$where['employee_identification_id']	= $employee_identification_id;
					
					// GET THE DETAIL AFTER INSERTING THE RECORD
					$curr_detail[] = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);

				//------- INSERT DETAILS -------//
					$audit_table[]	= $table_dtls;
					$audit_schema[]	= Base_Model::$schema_core;
					
					//SET FIELDS VALUE
					//DETAILS
					$field_dtls['employee_identification_id']	= $employee_identification_id;
					$field_dtls['start_date']  					= $valid_data['effective_date'];
					$field_dtls['end_date']   	  				= NULL;
					$field_dtls['remarks']         				= $valid_data['remarks'];
					
					//SET AUDIT TRAIL DETAILS
					$audit_action[]	= AUDIT_INSERT;
					
					$prev_detail[]	= array();

					//INSERT DATA
					$employee_identification_detail_id = $this->compensation->insert_compensation($table_dtls, $field_dtls, TRUE);

					//MESSAGE ALERT
					$message 		 = $this->lang->line('data_saved');
					
					//WHERE VALUES
					$where 	 			= array();
					$where['employee_identification_detail_id']	= $employee_identification_detail_id;
					
					// GET THE DETAIL AFTER INSERTING THE RECORD
					$curr_detail[] = $this->compensation->get_compensation_data(array("*"), $table_dtls, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s %s %s has been added";
			}
			else
			{
				//WHERE VALUES
				$where 	 								= array();
				$where['employee_identification_id']	= $previous_data['prev_detail_data']['employee_identification_id'];
				
				//------- UPDATE MAIN -------// 
				
					$audit_action[]	= AUDIT_UPDATE;
					
					// DETAILS BEFORE UPDATING THE RECORD
					$prev_detail[]  	= array($previous_data['prev_detail_data']);

					//UPDATE DATA
					$this->compensation->update_compensation($table, $fields, $where);
					
					// GET THE DETAIL AFTER UPDATING THE RECORD
					$curr_detail[]  = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);

				//-------UPDATE DETAILS-------//
					$audit_table[]	= $table_dtls;
					$audit_schema[]	= Base_Model::$schema_core;

					//SET DETAILS FIELDS VALUE
					$field_dtls['start_date']  					= $valid_data['effective_date'];
					$field_dtls['remarks']         				= $valid_data['remarks'];
					
					//SET AUDIT TRAIL DETAILS
					$audit_action[]	= AUDIT_UPDATE;
					
					$prev_detail[]	= array($previous_data['prev_detail_data']);

					//UPDATE DATA
					$this->compensation->update_compensation($table_dtls, $field_dtls, $where);
				
					// GET THE DETAIL AFTER UPDATING THE RECORD
					$curr_detail[] = $this->compensation->get_compensation_data(array("*"), $table_dtls, $where, TRUE);

				//MESSAGE ALERT
				$message = $this->lang->line('data_updated');
					
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s %s %s has been updated";
				
			}
			
			//-------UPDATE CLOSE PREVIOUS ENDDATE-------//
			if($previous_data['prev_data_entry'])
			{
				$audit_table[]	= $table_dtls;
				$audit_schema[]	= Base_Model::$schema_core;
				
				//SET DETAILS FIELDS VALUE
				$field_prev_dtls['end_date'] = date('Y-m-d', strtotime($valid_data['effective_date'] . ' -1 day'));
				
				//SET AUDIT TRAIL DETAILS
				$audit_action[]	= AUDIT_UPDATE;
				
				$prev_detail[]	= array($previous_data['prev_data_entry']);

				//WHERE VALUES
				$where 	 			= array();
				$where['employee_identification_detail_id']	= $previous_data['prev_data_entry']['employee_identification_detail_id'];

				//UPDATE DATA
				$this->compensation->update_compensation($table_dtls, $field_prev_dtls, $where);
			
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = $this->compensation->get_compensation_data(array("end_date"), $table_dtls, $where, TRUE);
			}

			$activity = sprintf($activity, $employee_name, 'Bank Account', $valid_data['identification_value']);

			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$params ['module'], 
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

			RLog::error($message);
		}
		$data['msg'] = $message;
		$data['status'] = $status;
		echo json_encode( $data );

	}
	
	private function _validate_input_identification($params)
	{
		try
		{			
			$validation['identification_value'] 	= array(
					'data_type' 					=> 'string',
					'name'							=> 'Identification Number',
					'max_len'						=> 50
			);
			$validation['identification_type_id'] 	= array(
					'data_type' 					=> 'digit',
					'name'							=> 'Identification Type'
			);	
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	public function delete_employee_bank_acc()
	{
		try
		{
			$flag 			= 0;
			$params			= get_params();
			$url 			= $params['param_1'];
			$url_explode	= explode('/',$url);
			$action 		= $url_explode[0];
			$id				= $url_explode[1];
			$token 			= $url_explode[2];
			$salt 			= $url_explode[3];
			$module			= $url_explode[4];
			$employee_id	= $url_explode[5];

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
			$prev_data					= array() ;
			/*GET PREVIOUS DATA*/
			
			$field 						= array("*") ;
			$where						= array();
			$key 						= $this->get_hash_key('employee_identification_id');
			$where[$key]				= $id;
			
			$table_hdr					= $this->compensation->tbl_employee_identifications;
			$table_dtls					= $this->compensation->tbl_employee_identification_details;
			$table_employee_info		= $this->compensation->tbl_employee_personal_info;
			
			$identification_hdr 		= $this->compensation->get_compensation_data($field, $table_hdr, $where, FALSE);
			$identification_dtls 		= $this->compensation->get_compensation_data($field, $table_dtls, $where, FALSE);
			
			//DELETE DATA
			$this->compensation->delete_compensation($table_hdr,$where);
			$this->compensation->delete_compensation($table_dtls,$where);
			
			//UPDATE PREVIOUS ENTRY - END DATE
			$fields					= array("A.employee_identification_id", "B.end_date", "CONCAT(C.first_name, IF((C.middle_name='NA' OR C.middle_name='N/A' OR C.middle_name='-' OR C.middle_name='/'), '', CONCAT(' ', LEFT(C.middle_name, 1), '.')), ' ',C.last_name, IF(C.ext_name='', '', CONCAT(' ', C.ext_name))) as fullname ") ;
			$tables 				= array(
					'main' 			=> array(
						'table' 	=> $table_hdr,
						'alias' 	=> 'A'
					),
					't1' 			=> array(
						'table'		=> $table_dtls,
						'alias'		=> 'B',
						'type'      => 'JOIN',
						'condition' => 'B.employee_identification_id = A.employee_identification_id'
					),
					't2' 			=> array(
						'table'		=> $table_employee_info,
						'alias'		=> 'C',
						'type'      => 'JOIN',
						'condition' => 'C.employee_id = A.employee_id'
					)
				);
			$where								= array();
			$where['A.employee_id']				= $identification_hdr['employee_id'];
			$where['A.identification_type_id']	= BANKACCT_TYPE_ID ;
			$order_by 							= array('A.employee_identification_id' => 'DESC');

			$prev_identification_entry		 	= $this->compensation->get_compensation_data($fields, $tables, $where, FALSE, $order_by);
			
			$employee_name 						= strtoupper($prev_identification_entry['fullname']);
			
			$fields									= array();
			$fields['end_date']						= NULL;
			
			$where									= array();
			$where['employee_identification_id']	= $prev_identification_entry['employee_identification_id'];
			
			$this->compensation->update_compensation($table_dtls, $fields, $where);
			$prev_identification_entry_updtd		= $this->compensation->get_compensation_data($field, $table_dtls, $where, FALSE);

			//AUDIT TRAIL
			//DETAILS
			$audit_table[]				= $table_dtls;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($identification_dtls);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			
			//HEADER
			$audit_table[]				= $table_hdr;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($identification_hdr);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			
			//PREVIOUS ENTRY DETAILS
			$audit_table[]				= $table_dtls;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array(array('end_date' => $prev_identification_entry['end_date']));
			$curr_detail[]				= array(array('end_date' => $prev_identification_entry_updtd['end_date']));
			$audit_action[] 			= AUDIT_UPDATE;

			$activity 					= "%s Account number %s has been deleted";
			$audit_activity 			= sprintf($activity, $employee_name, $identification_hdr['identification_value']);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 						= $this->lang->line('data_deleted');
			$flag 						= 1;
		}		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$post_data = array(
			'employee_id' => $employee_id,
			'module' => $module
		);

		$response 					= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'table_employee_bank',
			"path"					=> PROJECT_MAIN . '/compensation/get_employee_bank_acc',
			"advanced_filter" 		=> true,
			'post_data' 			=> json_encode($post_data)
			);

		echo json_encode($response);
	}
	
// ====================== jendaigo : end   : include employee bank account encoding ============= //

// ====================== jendaigo : start : include employee responsibility code encoding ============= //
	public function get_employee_responsibility_code()
	{
		try
		{
			$params         = get_params();

			$aColumns       = array("A.employee_responsibility_code_id","A.employee_id", "A.responsibility_center_code", "B.responsibility_center_desc", "DATE_FORMAT(A.start_date,'%Y/%m/%d') as start_date", "IFNULL(DATE_FORMAT(A.end_date, '%Y/%m/%d'), 'PRESENT') as end_date", "A.remarks");
			$bColumns       = array("start_date", "end_date", "A.responsibility_center_code", "responsibility_center_desc", "remarks");

			$responsibility_codes = $this->compensation->get_responsibility_code_list($aColumns, $bColumns, $params);

			$iTotal         = $this->compensation->responsibility_code_total_length();
			$iFilteredTotal = $this->compensation->responsibility_code_filtered_length($aColumns, $bColumns, $params);
			
			$output 					= array(
				"sEcho"                	=> intval($_POST['sEcho']),
				"iTotalRecords"        	=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData"               	=> array()
			);

			$employee_id 	= $params['employee_id'];
			$module    		= $params['module'];
			$id_action 		= $this->session->userdata("pds_action");

			$permission_edit 	= $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete 	= $this->permission->check_permission($module, ACTION_DELETE);
			$permission_view 	= $this->permission->check_permission($module, ACTION_VIEW);

			$cnt        = 0;
			foreach ($responsibility_codes as $aRow):
				$cnt++;
				$row          = array();
				$action       = "";
				
				$id           = $this->hash($aRow['employee_responsibility_code_id']);
				
				$salt         = gen_salt();
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				$token_view	  = in_salt($id . '/' . ACTION_VIEW  . '/' . $module, $salt);

				$url_edit     = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete   = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;
				$url_view 	  = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;

				$row[]        = $aRow['start_date'];
				$row[]        = $aRow['end_date'];
				$row[]        = strtoupper($aRow['responsibility_center_code']);
				$row[]        = strtoupper($aRow['responsibility_center_desc']);
				$row[]        = strtoupper($aRow['remarks']);
				
				$action       = "<div class='table-actions'>";

				// ====================== jendaigo : start : remove id_action ============= //
				// if($id_action != ACTION_VIEW)
				// {
				// ====================== jendaigo : end : remove id_action ============= //
					if($permission_view)
						$action	.= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_employee_responsibility_code' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_employee_responsibility_code_init('".$url_view."')\"></a>";
					
					if($cnt == $iFilteredTotal["cnt"])
					{
						if($permission_edit)
							$action	.= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_employee_responsibility_code' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employee_responsibility_code_init('".$url_edit."')\" ></a>";
						if($permission_delete)
							$action	.= "<a href='javascript:;' onclick='".'content_delete("Account Number", "'.$url_delete.'")'."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50' ></a>";
					}
				// } //jendaigo : remove id_action
				$action .= "</div>";

				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		}
		catch (Exception $e)
		{
			$output 					= array(
				"sEcho"               	=> intval($_POST['sEcho']),
				"iTotalRecords"       	=> 0,
				"iTotalDisplayRecords"	=> 0,
				"aaData"              	=> array()
			);
		}

		echo json_encode( $output );
	}

	public function modal_employee_responsibility_code($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			/*GET RESPONSIBILITY CODE LIST*/
			$field             		= array("*");
			$table 					= $this->compensation->tbl_param_responsibility_centers;
			$where              	= array();
			$where['active_flag']	= YES;

			$data['responsibility_codes'] = $this->compensation->get_compensation_data($field, $table, $where, TRUE);

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field              = array("*");
				$table 				= $this->compensation->tbl_employee_responsibility_codes;
				
				$where              = array();
				$key                = $this->get_hash_key('employee_responsibility_code_id');
				$where[$key]        = $id;

				$data['employee_responsibility_code'] = $this->compensation->get_compensation_data($field, $table, $where, FALSE);

			}
			
			$this->load_resources->get_resource($resources);
			$this->load->view('compensation/modals/modal_employee_responsibility_code', $data);

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
	
	public function process_employee_responsibility_code()
	{

		try
		{
			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) 
			{
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ))
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) 
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}

			//GET UNHASHED EMPLOYEE_ID
			$field 			= array("employee_id", "CONCAT(first_name, IF((middle_name='NA' OR middle_name='N/A' OR middle_name='-' OR middle_name='/'), '', CONCAT(' ', LEFT(middle_name, 1), '.')), ' ', last_name, IF(ext_name='', '', CONCAT(' ', ext_name))) as fullname ");
			$table 		 	= $this->compensation->tbl_employee_personal_info;
			$where  		= array();
			$key    		= $this->get_hash_key('employee_id');
			$where[$key]	= $params['employee_id'];

			$employee_id_unhashed 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$employee_id 			= $employee_id_unhashed['employee_id'];
			$employee_name 			= strtoupper($employee_id_unhashed['fullname']);
			
			// SERVER VALIDATION
			$valid_data = $this->_validate_responsibility_code($params);

			// INPUT VALIDATIONS
			$previous_data = $this->_addtl_validate_input_responsibility_code($params, $valid_data);

			//SET FIELDS VALUE
			//MAIN
			$fields['employee_id']       			= $employee_id;
		 	$fields['responsibility_center_code']   = $valid_data['responsibility_code'];
			$fields['start_date']  					= $valid_data['effective_date'];
			$fields['end_date']  					= NULL;
			$fields['remarks']  					= $valid_data['remarks'];

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->compensation->tbl_employee_responsibility_codes;
			
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;

			if($params['id'] == DEFAULT_ID) //DEFAULT_ID
			{
				//------- INSERT MAIN -------//

					//SET AUDIT TRAIL DETAILS
					$audit_action[]	= AUDIT_INSERT;
					
					$prev_detail[]	= array();

					//INSERT DATA
					$employee_responsibility_code_id = $this->compensation->insert_compensation($table, $fields, TRUE);

					//WHERE VALUES
					$where 	 			= array();
					$where['employee_responsibility_code_id']	= $employee_responsibility_code_id;
					
					// GET THE DETAIL AFTER INSERTING THE RECORD
					$curr_detail[] = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s %s %s has been added";
			}
			else
			{
				//WHERE VALUES
				$where 	 								= array();
				$where['employee_responsibility_code_id']	= $previous_data['prev_detail_data']['employee_responsibility_code_id'];
				
				//------- UPDATE MAIN -------// 
				
					$audit_action[]	= AUDIT_UPDATE;
					
					// DETAILS BEFORE UPDATING THE RECORD
					$prev_detail[]  	= array($previous_data['prev_detail_data']);

					//UPDATE DATA
					$this->compensation->update_compensation($table, $fields, $where);
					
					// GET THE DETAIL AFTER UPDATING THE RECORD
					$curr_detail[]  = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);

				//MESSAGE ALERT
				$message = $this->lang->line('data_updated');
					
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s %s %s has been updated";
				
			}

			//-------UPDATE CLOSE PREVIOUS ENDDATE-------//
			if($previous_data['prev_data_entry'])
			{
				$audit_table[]	= $table;
				$audit_schema[]	= Base_Model::$schema_core;
				
				//SET DETAILS FIELDS VALUE
				$field_prev['end_date'] = date('Y-m-d', strtotime($valid_data['effective_date'] . ' -1 day'));
				
				//SET AUDIT TRAIL DETAILS
				$audit_action[]	= AUDIT_UPDATE;
				
				$prev_detail[]	= array($previous_data['prev_data_entry']);

				//WHERE VALUES
				$where 	 			= array();
				$where['employee_responsibility_code_id']	= $previous_data['prev_data_entry']['employee_responsibility_code_id'];

				//UPDATE DATA
				$this->compensation->update_compensation($table, $field_prev, $where);
			
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = $this->compensation->get_compensation_data(array("end_date"), $table, $where, TRUE);
			}

			$activity = sprintf($activity, $employee_name, 'Responsibility Code', $valid_data['responsibility_code']);

			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$params ['module'], 
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

			RLog::error($message);
		}
		$data['msg'] = $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}
	
	public function delete_employee_responsibility_code()
	{
		try
		{
			$flag 			= 0;
			$params			= get_params();
			$url 			= $params['param_1'];
			$url_explode	= explode('/',$url);
			$action 		= $url_explode[0];
			$id				= $url_explode[1];
			$token 			= $url_explode[2];
			$salt 			= $url_explode[3];
			$module			= $url_explode[4];
			$employee_id	= $url_explode[5];

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
			$prev_data					= array() ;
			/*GET PREVIOUS DATA*/
			
			$field 						= array("*") ;
			$where						= array();
			$key 						= $this->get_hash_key('employee_responsibility_code_id');
			$where[$key]				= $id;
			
			$table_hdr					= $this->compensation->tbl_employee_responsibility_codes;
			$table_employee_info		= $this->compensation->tbl_employee_personal_info;
			
			$responsibility_code 		= $this->compensation->get_compensation_data($field, $table_hdr, $where, FALSE);
			$identification_hdr 		= $this->compensation->get_compensation_data($field, $table_hdr, $where, FALSE);

			//DELETE DATA
			$this->compensation->delete_compensation($table_hdr,$where);
			
			//UPDATE PREVIOUS ENTRY - END DATE
			$fields					= array("A.employee_responsibility_code_id", "A.end_date", "CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '.')), ' ',B.last_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name))) as fullname ") ;
			$tables 				= array(
					'main' 			=> array(
						'table' 	=> $table_hdr,
						'alias' 	=> 'A'
					),
					't2' 			=> array(
						'table'		=> $table_employee_info,
						'alias'		=> 'B',
						'type'      => 'JOIN',
						'condition' => 'B.employee_id = A.employee_id'
					)
				);
			$where								= array();
			$where['A.employee_id']				= $identification_hdr['employee_id'];
			$order_by 							= array('A.employee_responsibility_code_id' => 'DESC');

			$prev_responsibility_code_entry		= $this->compensation->get_compensation_data($fields, $tables, $where, FALSE, $order_by);

			$employee_name 						= strtoupper($prev_responsibility_code_entry['fullname']);
			
			$fields								= array();
			$fields['end_date']					= NULL;

			$where										= array();
			$where['employee_responsibility_code_id']	= $prev_responsibility_code_entry['employee_responsibility_code_id'];

			$this->compensation->update_compensation($table_hdr, $fields, $where);

			$prev_responsibility_code_entry_updtd		= $this->compensation->get_compensation_data($field, $tables, $where, FALSE);
			
			//AUDIT TRAIL
			
			//HEADER
			$audit_table[]				= $table_hdr;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($identification_hdr);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			
			//PREVIOUS ENTRY DETAILS
			$audit_table[]				= $table_hdr;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array(array('end_date' => $prev_responsibility_code_entry['end_date']));
			$curr_detail[]				= array(array('end_date' => $prev_responsibility_code_entry_updtd['end_date']));
			$audit_action[] 			= AUDIT_UPDATE;

			$activity 					= "%s Responsibility Code %s has been deleted";
			$audit_activity 			= sprintf($activity, $employee_name, $identification_hdr['identification_value']);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 						= $this->lang->line('data_deleted');
			$flag 						= 1;
		}		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$post_data = array(
			'employee_id' => $employee_id,
			'module' => $module
		);

		$response 					= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'table_employee_responsibility_code',
			"path"					=> PROJECT_MAIN . '/compensation/get_employee_responsibility_code',
			"advanced_filter" 		=> true,
			'post_data' 			=> json_encode($post_data)
			);

		echo json_encode($response);
	}
	
// ====================== jendaigo : end   : include employee responsibility code encoding ============= //

	public function get_employee_payslip_history_list()
	{
	
		try
		{
			$params = get_params();

			$aColumns			= array("B.payroll_hdr_id", "B.date_month_year", "SUM(A.total_income) total_income_sum", "SUM(A.total_deductions) total_deductions_sum", "SUM(A.net_pay) net_pay_sum");
			$bColumns			= array("B.date_month_year", "SUM(A.total_income)", "SUM(A.total_deductions)", "SUM(A.net_pay)");

			$payslip_history 	= $this->compensation->employee_payslip_history_list($aColumns, $bColumns, $params);
			$iTotal          	= $this->compensation->payslip_history_total_length();
			$iFilteredTotal  	= $this->compensation->payslip_history_filtered_length($aColumns, $bColumns, $params);
			
			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

			$cnt         = 0;
			$employee_id = $params['employee_id'];
			
			foreach ($payslip_history as $aRow):
				$cnt++;
				$row    = array();
				$action = "";		
				
				$payroll_hdr_id = $aRow["payroll_hdr_id"];
				$id              = $this->hash ($payroll_hdr_id);
				$salt            = gen_salt();
				$token_view      = in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_delete    = in_salt($id . '/' . ACTION_DELETE, $salt);
				$url_view        = ACTION_VIEW . "/" . $id . "/" . $salt  . "/" . $token_view;			
				$url_delete      = ACTION_DELETE . "/" . $id . "/" . $salt . "/" . $token_delete;
				$delete_action   = 'content_delete("benefit", "'.$url_delete.'")';
				
				if($aRow["date_month_year"])
				{
					$row[]           = '<center>' . $aRow['date_month_year'] . '</center>';
					$row[]           = '<p class="m-n right">&#8369; ' . number_format($aRow['total_income_sum'],2) . '</p>';
					$row[]           = '<p class="m-n right">&#8369; ' . number_format($aRow['total_deductions_sum'],2) . '</p>';
					$row[]           = '<p class="m-n right">&#8369; ' . number_format($aRow['net_pay_sum'],2) . '</p>';
				

				if($cnt == count($payslip_history)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS,JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}

				$action             = "<div class='table-actions'>";
				
				$action            .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_employee_payslip_history' data-tooltip='view' data-position='bottom' data-delay='50' onclick=\"modal_employee_payslip_history_init('".$url_view."')\"></a>";
				
				$action            .= "</div>";
				
				$row[]              = $action;
				$output['aaData'][] = $row;
				}
			endforeach;
				
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	
		echo json_encode( $output );
	}

	public function get_employee_payslip_history_details()
	{
	
		try
		{
			$params = get_params();
						
			if(ISSET($params['deduction']))
			{
				$aColumns			= array("SUM(B.amount) amount, SUM(B.employer_amount) employer_amount, SUM(B.paid_count) paid_count,D.deduction_name");
				$bColumns			= array("D.deduction_name", "D.amount", "D.employer_amount");
				$group_by			= "GROUP BY B.deduction_id";
			}
			if(ISSET($params['compensation']))
			{
				$aColumns			= array("SUM(B.amount) amount,D.compensation_name");
				$bColumns			= array("D.compensation_name", "D.amount");
				$group_by			= "GROUP BY B.compensation_id";
			}
			$payslip_history 	= $this->compensation->employee_payslip_history_details($aColumns, $bColumns, $params, $group_by);

			$iTotal   			= $this->compensation->employee_payslip_history_details(array("COUNT(DISTINCT(B.payroll_dtl_id)) as count" ), $bColumns, $params, "");

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["count"],
				"iTotalDisplayRecords" => count($payslip_history),
				"aaData"               => array()
			);
			$cnt         = 0;
			// $employee_id = $params['employee_id'];
			foreach ($payslip_history as $aRow):
				$cnt++;
				$row    = array();
				$action = "";
			
				// $compensation_id = $aRow["compensation_id"];
				// $id              = $this->hash ($compensation_id);
				// $salt            = gen_salt();
				// $token_edit      = in_salt($id . '/' . ACTION_VIEW, $salt);
				// $token_delete    = in_salt($id . '/' . ACTION_DELETE, $salt);
				// $view_action     = ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_edit;			
				// $url_delete      = ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				// $delete_action   = 'content_delete("benefit", "'.$url_delete.'")';
				
				if(ISSET($params['deduction'])) {
					$row[] = $aRow['deduction_name'];
					$row[] = '<p class="p-n right">&#8369; ' . number_format($aRow['amount'],2) . '</p>';
					$row[] = '<p class="p-n right">&#8369; ' . number_format($aRow['employer_amount'],2) . '</p>';
					$row[] = '<p class="p-n right">' . $aRow['paid_count'] . '</p>';
				}
				if(ISSET($params['compensation'])) {
					$row[] = $aRow['compensation_name'];
					$row[] = '<p class="p-n right">&#8369; ' . number_format($aRow['amount'],2) . '</p>';
				}

				if($cnt == count($payslip_history)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS, JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}

				// $action = "<div class='table-actions'>";

				// $action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_employee_payslip_history' data-tooltip='view' data-position='bottom' data-delay='50' onclick=\"modal_employee_payslip_history_init('".$url_view."')\"></a>";

				// $action .= "</div>";
				
				// $row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
				
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	
		echo json_encode( $output );
	}

	//EMPLOYEE LIST TAB
	public function get_benefit_list()
	{
	
		try
		{

			$params = get_params();	


			$aColumns = array("*");
			$bColumns = array("compensation_name", "active_flag");
			
			$benefits 		 = $this->compensation->get_benefit_list($aColumns, $bColumns, $params);
			
			$iFilteredTotal  = $this->compensation->filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);

			$module = MODULE_USER;

			foreach ($benefits as $aRow):
		
				$row = array();

				$action = "";

				$compensation_id 	= $aRow['compensation_id'];
				$id 				= $this->hash ($compensation_id);

				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;			
				


				$row[] = strtoupper($aRow['compensation_name']);
				$row[] = ($aRow['active_flag'] == 'Y') ? 'ACTIVE' : 'INACTIVE';

				$action = "<div class='table-actions'>";

				$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_benefits_filters_by' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_benefits_filters_by_init('".$url_view."')\"></a>";
				// $action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_benefits_filters_by' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_benefits_filters_by_init('".$url_edit."')\"></a>";
				// ====================== jendaigo : start : disable editing if status is inactive ============= //
				if($aRow['active_flag'] == 'Y'){
					if($aRow['compensation_id'] != DEDUC_UNDERPAY_JO) //jendaigo : remove edit button for compensation underpay
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_benefits_filters_by' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_benefits_filters_by_init('".$url_edit."')\"></a>";
				}
				// ====================== jendaigo : end   : disable editing if status is inactive ============= //
				
				$action .= "</div>";
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
				
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	
		echo json_encode( $output );
	}

	public function employee_benefits_tabs($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
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




			//$data['employee_salary_info']   = $this->compensation->get_employee_salary_info($field, $table, $where, FALSE);

			$data['employee_salary_info']   = '';

			//var_dump($employee_salary_info);exit;
 			
			// if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			// {
			// 	throw new Exception($this->lang->line('err_invalid_request'));
			// }
			// if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			// {
			// 	throw new Exception($this->lang->line('err_unauthorized_access'));
			// }
		
			
			$this->template->load('compensation/employee_benefits_details', $data, $resources);
		
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	}
	
	public function employee_tabs($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
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
			$data['personal_info']    = $this->compensation->get_employee_info($employee_id);

			$data['employee_salary_info']  = '';

 			
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
				$key					= "My Portal";
				$breadcrumbs[$key]		= PROJECT_MAIN."/Compensation";
				$key					= "Compensations"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/Compensation";
				set_breadcrumbs($breadcrumbs, TRUE);
			} else {
				/*BREADCRUMBS*/
				$breadcrumbs 			= array();
				$key					= "Payroll"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/Compensation";
				$key					= "Compensations"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/Compensation";
				$key					= "Employee Compensations"; 
				$breadcrumbs[$key]		= PROJECT_MAIN."/compensation/employee_tabs/".$url_security;
				set_breadcrumbs($breadcrumbs, TRUE);
			}
			
			// GET CURRENT USER'S GRANTED OFFICES
			$user_offices = $this->session->userdata('user_offices');
			$user_offices = $user_offices[$module];
			// COMPARE IF THE SELECTED EMPLOYEE'S OFFICE IS IN THE LIST
			$data['has_permission'] = (in_array($data['personal_info']['employ_office_id'], explode(',', $user_offices)));
			

			$this->template->load('compensation/compensation_details', $data, $resources);
		
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
	}

	public function tabs($form, $action, $id, $token, $salt, $module)
	{

		try
		{
			$data 					= array();
			$resources['load_css'] 	= array();
			$resources['load_js'  ] = array();

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$this->template->load('compensation', $data, $resources);
		
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}

	

	public function modal_request_certificate($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);


			$field 							= array("*") ;
			$table							= $this->compensation->tbl_param_compensations;
			$where							= array();
			$data['benefit_types'] 	    	= $this->compensation->get_compensation_data($field, $table, $where, TRUE);
		

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('compensation/modals/modal_request_certificate', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
	}


	/*****************************************Compensation Tab************************************************/

	public function modal_benefits_filters_by($action, $id, $token, $salt, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_DATATABLE);
			
			
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
				'compensation_id' => $id);

			$resources['datatable'][]	= array('table_id' => 'table_benefit_employee_list', 'path' => 'main/compensation/get_benefit_employee_list', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));
				
				$resources['load_modal'] = array(
					'modal_add_personnel_to_benefits' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_personnel_to_benefits',
						'multiple'      => true,
						'height'        => '500px',
						'size'          => 'xl',
						'title'			=> 'Employee'
					)
				);	


			$field 					= array("*") ;
			$table					= $this->compensation->tbl_param_compensations;
			$where					= array();
			$key    				= $this->get_hash_key('compensation_id');
			$where[$key]			= $id;
			$data['benefit_info'] 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);

			// ====================== jendaigo : start : check if superuser role ============= //
			$data['has_permission'] = (in_array('SUPER_USER',$this->session->userdata('user_roles'))) ? TRUE : FALSE;
			// ====================== jendaigo : end : check if superuser role ============= //

			$this->load->view('compensation/modals/modal_benefits_filters_by', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
	}

	public function modal_add_personnel_to_benefits($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_NUMBER);

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

			$field 							= array("employment_type_code", "employment_type_name") ;
			$table							= $this->compensation->tbl_param_employment_types;
			$where							= array();
			$where['employment_type_code']	=array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
			$data['employ_type_flag'] 		= $this->compensation->get_compensation_data($field, $table, $where, TRUE);

			$field 					= array("*") ;
			$table					= $this->compensation->tbl_param_compensations;
			$where					= array();
			$key    				= $this->get_hash_key('compensation_id');
			$where[$key]			= $id;
			$data['benefit_info'] 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);


			$field                     = array("*") ;
			$table                     = $this->compensation->tbl_param_positions;
			$where                     = array();
			$where['active_flag'] 	   = YES;
			$data['positions']         = $this->compensation->get_compensation_data($field, $table, $where, TRUE);
			
			$data['salary_grade']      = $this->compensation->get_salary_grade();

			$field  = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->compensation->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->compensation->db_core.".".$this->compensation->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				),
				
			);
			$where                       = array();
			$where['A.active_flag'] 	 = YES;
			$data['offices']             = $this->compensation->get_compensation_data($field, $tables, $where, TRUE);

			$where                       = array();
			$where['other_info_type_id'] = 4;
			$data['designation']         = $this->compensation->get_compensation_data(array('*'), $this->compensation->tbl_employee_other_info, $where);
			
			$data['performance_ratings'] = $this->compensation->get_compensation_data(array('*'), $this->compensation->tbl_param_performance_rating, array('active_flag' => 'Y'));
			
			switch ($action) 
			{
				case ACTION_ADD:
					$tables = array(
						'main'	=> array(
							'table'		=> $this->compensation->tbl_employee_personal_info,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->compensation->tbl_employee_work_experiences,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = B.employee_id',
						),
						't3'	=> array(
							'table'		=> $this->compensation->tbl_employee_performance_evaluations,
							'alias'		=> 'C',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = C.employee_id',
						),
						't4'	=> array(
							'table'		=> $this->compensation->tbl_employee_other_info,
							'alias'		=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = D.employee_id',
						),
						't5'	=> array(
							'table'		=> $this->compensation->tbl_employee_deductions,
							'alias'		=> 'E',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = E.employee_id',
						)
						
					);
					$where = array();
					$compensation_id = $this->get_hash_key('compensation_id');
					$where[$compensation_id] = $id;

					$compensation_id = $this->compensation->get_compensation_data(array('GROUP_CONCAT(employee_id) employee_id'),$this->compensation->tbl_employee_compensations, $where, FALSE);
					
					$where                       = array();
					$where['B.active_flag']      = YES;
					$where['B.employ_type_flag'] = array(array('AP','WP','JO'), array('IN'));
					if(!empty($compensation_id['employee_id']))
					$where['A.employee_id']      = array(explode(',', $compensation_id['employee_id']), array('NOT IN'));
					
					$group_by            	= array('A.employee_id, B.employ_office_id, B.employ_position_id, B.employ_salary_grade, fullname, C.rating, designation, union_member');
					// $order_by 				= array('CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\')' => 'ASC');
					// $fields 				= array('A.employee_id, B.employ_office_id, B.employ_position_id, B.employ_salary_grade,CONCAT(A.last_name, if(A.ext_name="","",CONCAT(" ", A.ext_name)),", ",A.first_name," ",LEFT(A.middle_name,1), ".") as fullname, C.rating, (SELECT others_value FROM employee_other_info WHERE other_info_type_id = 4 AND employee_id = A.employee_id) AS designation, if(E.deduction_id=69,"Y","N") AS union_member, B.employ_type_flag');
					
					// ====================== jendaigo : start : change name format ============= //
					$order_by 				= array('CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \')))' => 'ASC');
					$fields 				= array('A.employee_id, B.employ_office_id, B.employ_position_id, B.employ_salary_grade,CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) as fullname, C.rating, (SELECT others_value FROM employee_other_info WHERE other_info_type_id = 4 AND employee_id = A.employee_id) AS designation, if(E.deduction_id=69,"Y","N") AS union_member, B.employ_type_flag');
					// ====================== jendaigo : end : change name format ============= //
					
					$data['employee_list']  = $this->compensation->get_compensation_data($fields, $tables, $where, TRUE, $order_by, $group_by);

					break;
				case ACTION_EDIT:
				case ACTION_DELETE:

					$tables = array(
						'main'	=> array(
							'table'		=> $this->compensation->tbl_employee_personal_info,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->compensation->tbl_employee_compensations,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = B.employee_id',
						),
						't3'	=> array(
							'table'		=> $this->compensation->tbl_employee_performance_evaluations,
							'alias'		=> 'C',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = C.employee_id',
						),
						't4'	=> array(
							'table'		=> $this->compensation->tbl_employee_other_info,
							'alias'		=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = D.employee_id',
						),
						't5'	=> array(
							'table'		=> $this->compensation->tbl_employee_work_experiences,
							'alias'		=> 'E',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = E.employee_id',
						),
						't6'	=> array(
							'table'		=> $this->compensation->tbl_employee_deductions,
							'alias'		=> 'F',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.employee_id = F.employee_id  AND F.deduction_id = 15',
						)
						
					);
					
					$where                   = array();
					$compensation_id         = $this->get_hash_key('B.compensation_id');
					$where[$compensation_id] = $id;
					$where['E.active_flag']  = YES; //jendaigo : include active_flag validation
					$group_by                = array('A.employee_id, E.employ_office_id, E.employ_position_id, E.employ_salary_grade, B.employee_id, fullname, C.rating, designation');
					// $order_by                = array('CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\')' => 'ASC');
					// $fields                  = array('B.employee_id, E.employ_office_id, E.employ_position_id, E.employ_salary_grade,CONCAT(A.last_name," ",ifnull(A.ext_name,\'\'),", ",A.first_name," ",LEFT(A.middle_name,1), \'.\') as fullname, C.rating, (SELECT others_value FROM employee_other_info WHERE other_info_type_id = 4 AND employee_id = A.employee_id) AS designation, if(F.deduction_id=53,"Y","N") AS union_member, E.employ_type_flag');
					
					// ====================== jendaigo : start : change name format ============= //
					$order_by                = array('CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \')))' => 'ASC');
					$fields                  = array('B.employee_id, E.employ_office_id, E.employ_position_id, E.employ_salary_grade,CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) as fullname, C.rating, (SELECT others_value FROM employee_other_info WHERE other_info_type_id = 4 AND employee_id = A.employee_id) AS designation, if(F.deduction_id=53,"Y","N") AS union_member, E.employ_type_flag');
					// ====================== jendaigo : end : change name format ============= //
					
					$data['employee_list']   = $this->compensation->get_compensation_data($fields, $tables, $where, TRUE, $order_by, $group_by);

					break;
				
			}

			$this->load->view('compensation/modals/modal_add_personnel_to_benefits', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
	}

	public function modal_edit_personnel_to_benefits($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);

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

			$field 					= array("*") ;
			$table					= $this->compensation->tbl_param_compensations;
			$where					= array();
			$key    				= $this->get_hash_key('compensation_id');
			$where[$key]			= $id;
			$data['benefit_info'] 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			
			
			$field                     = array("*") ;
			$table                     = $this->compensation->tbl_param_positions;
			$where                     = array();
			$where['active_flag'] 	   = YES;
			$data['positions']         = $this->compensation->get_compensation_data($field, $table, $where, TRUE);
			
			$data['salary_grade']      = $this->compensation->get_salary_grade();


			$field                     = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->compensation->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->compensation->db_core.".".$this->compensation->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
			$where                     = array();
			$where['A.active_flag']    = YES;
			$data['offices']           = $this->compensation->get_compensation_data($field, $tables, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->compensation->tbl_param_compensations;
			$where                     = array();
			$key                       = $this->get_hash_key('compensation_id');
			$where[$key]               = $id;
			$compensations_type        = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$data['compensation_type'] = $compensations_type['compensation_name'];

			$this->load->view('compensation/modals/modal_edit_personnel_to_benefits', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}		
	}

	public function modal_delete_personnel_to_benefits($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);

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
			
			
			$field                     = array("*") ;
			$table                     = $this->compensation->tbl_param_positions;
			$where                     = array();
			$where['active_flag'] 	   = YES;
			$data['positions']         = $this->compensation->get_compensation_data($field, $table, $where, TRUE);
			
			$data['salary_grade']      = $this->compensation->get_salary_grade();


			$field                     = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->compensation->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->compensation->db_core.".".$this->compensation->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
			$where                  = array();
			$where['A.active_flag'] = YES;
			$data['offices'] 		= $this->compensation->get_compensation_data($field, $tables, $where, TRUE);

			$field              = array("*") ;
			$table              = $this->compensation->tbl_param_compensations;
			$where              = array();
			$key                = $this->get_hash_key('compensation_id');
			$where[$key]        = $id;
			$compensations_type         = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$data['compensations_type'] = $compensations_type['compensation_name'];

			$this->load->view('compensation/modals/modal_delete_personnel_to_benefits', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}		
	}

	public function modal_employee_payslip_history($action = NULL, $id = NULL, $salt = NULL, $token = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($salt) OR EMPTY($token))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$resources['load_css'] = array(CSS_DATATABLE, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATATABLE, JS_SELECTIZE);
			
			
			
			$data['action']        = $action;
			$data['id']            = $id;
			$data['salt']          = $salt;
			$data['token']         = $token;
			
			$table = array(
					'main' => array(
							'table' => $this->payroll->tbl_payout_header,
							'alias' => 'A'
					),
					't1' => array(
							'table' => $this->payroll->tbl_payout_summary_dates,
							'alias' => 'B',
							'type' => 'LEFT JOIN',
							'condition' => 'A.payroll_summary_id=B.payout_summary_id'
					)
			);
			$where = array();
			$key = $this->get_hash_key('payroll_hdr_id');
			$where[$key] = $id;
			$payout_header = $this->payroll->get_payroll_data(array('A.employee_name', 'GROUP_CONCAT(DISTINCT DATE_FORMAT(B.effective_date, \'%Y/%m/%d\')) AS effective_date'), $table, $where, FALSE, array(), array('A.payroll_summary_id', 'A.employee_name', 'B.effective_date'));
				
			$data['effective_date'] = explode(',', $payout_header['effective_date']);
			
			$fields                = array("effective_date","sum(if(compensation_id IS NOT NULL, amount,0)) -  sum(if(deduction_id IS NOT NULL, amount,0)) AS net_pay");
			$table                 = $this->compensation->tbl_payout_details;
			$where                 = array();
			$key                   = $this->get_hash_key('payroll_hdr_id');
			$where[$key]           = $id;
			$data['payroll_info']  = $this->compensation->get_compensation_data($fields, $table, $where, FALSE, array(), array('effective_date'));

			
			$resources['datatable'][]	= array('table_id' => 'table_payslip_history_details_compensations', 'path' => 'main/compensation/get_employee_payslip_history_details', 'advanced_filter' => TRUE, 'post_data' => json_encode(array('compensation' => true, 'payroll_id' => $id)));

			$resources['datatable'][]	= array('table_id' => 'table_payslip_history_details_deductions', 'path' => 'main/compensation/get_employee_payslip_history_details', 'advanced_filter' => TRUE, 'post_data' => json_encode(array('deduction' => true, 'payroll_id' => $id)));

			$this->load->view('compensation/modals/modal_employee_payslip_history', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
	}

	public function process_add_personnel_to_benefits()
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
			$compensation_id	= $params['id'];
			$module				= $params['module'];
			

			if(EMPTY($action) OR EMPTY($compensation_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($compensation_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			

			if(EMPTY($params['employee_list']))
				throw new Exception($this->lang->line('invalid_action'));
			/*CHECK DATA VALIDITY*/
			$valid_data = $this->_validate_add_personel($params);
			
			Main_Model::beginTransaction();

			$field             = array("*") ;
			$table             = $this->compensation->tbl_param_compensations;
			
			$where             = array();
			$key               = $this->get_hash_key('compensation_id');
			$where[$key]       = $compensation_id;
			$compensation_type = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			
			switch ($action) 
			{
				case ACTION_ADD:
					$audit_activity = "Employees were added to Benefit Type ".$compensation_type['compensation_name'];

					break;

				case ACTION_EDIT:
					$audit_activity = "Employees in Benefit Type ".$compensation_type['compensation_name'] . " were updated";
					$this->compensation->delete_compensation($this->compensation->tbl_employee_compensations, $where);
					
					break;

				case ACTION_DELETE:
					$audit_activity = "Employees were deleted from Benefit Type ".$compensation_type['compensation_name'];
					$audit_action[] = AUDIT_DELETE;	
					$where['employee_id'] = array($params['employee_list'], array('IN'));
					$this->compensation->delete_compensation($this->compensation->tbl_employee_compensations, $where);
					$prev_detail[]  = array($params['employee_list']);
					$curr_detail[]  = array();

					break;
			}

			if($action != ACTION_DELETE)
			{
				$fields            = array();
				foreach($params['employee_list'] as $i => $employee_id)
				{
				
					$fields[$i]['employee_id']          = $employee_id;
					$fields[$i]['compensation_id']      = $compensation_type["compensation_id"];
					$fields[$i]['start_date']           = $valid_data["start_date"];
					$fields[$i]['end_date'] 			= !EMPTY($valid_data["end_date"]) ? $valid_data["end_date"] : NULL;

				}
				$table          = $this->compensation->tbl_employee_compensations;
				$this->compensation->insert_compensation($table, $fields, FALSE);
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
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	public function process_edit_personnel_to_benefits()
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
			$compensation_id 	= $params['id'];
			$module			 	= $params['module'];
			
			if(EMPTY($action) OR EMPTY($compensation_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($compensation_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if(EMPTY($params['employee_list']))
				throw new Exception("No personnel found in list.");
			/*CHECK DATA VALIDITY*/
			
			
			Main_Model::beginTransaction();
			foreach($params['employee_list'] as $employee_id)
			{
				$field 						= array("*") ;
				$tables = array(
					'main'	=> array(
						'table'		=> $this->compensation->tbl_param_compensations,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->compensation->tbl_employee_compensations,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'A.compensation_id = B.compensation_id',
					)
				);
				// $where          = array();
				// $key            = $this->get_hash_key('B.compensation_id');
				// $where[$key]    = $compensation_id;
				// $key            = $this->get_hash_key('B.employee_id');
				// $where[$key]    = $employee_id;
				// $leave_balances = $this->compensation->get_compensation_data($field, $tables, $where, FALSE);
				
				$where          = array();
				$tables         = $this->compensation->tbl_employee_personal_info;
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $employee_id;
				$personal_info  = $this->compensation->get_compensation_data(array('*'), $tables, $where, FALSE);
				
				$where          = array();
				$tables         = $this->compensation->tbl_param_compensations;
				$key            = $this->get_hash_key('compensation_id');
				$where[$key]    = $compensation_id;
				$compensation_type     = $this->compensation->get_compensation_data(array('*'), $tables, $where, FALSE);
			}

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	public function get_remove_personnel_list()
	{
		try
		{
			$params				= get_params();
			$list 				= array();
			$append_personnnel_list = "";

			
			$employee_list       		= $this->compensation->get_remove_personnel_list($params);
			
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
				  					<td class="table-actions"><a herf="javascript:;" class="delete cursor-pointer" id="remove_personnel"></a></td>
			  					</tr>';
				endforeach;

				
			}
		}
		catch(Exception $e)
		{

			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}
		
		
		
		$info = array(
				"list" => $list,
				"append_personnnel_list" => $append_personnnel_list
		);
	
		echo json_encode($info);
	}
	public function process_remove_personnel()
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
			$compensation_id	= $params['id'];
			$module			= $params['module'];
			$employee_id	= $params['employee_id'];
			
			if(EMPTY($action) OR EMPTY($compensation_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($compensation_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if(EMPTY($params['employee_list']))
				throw new Exception($this->lang->line('invalid_action'));
			
			Main_Model::beginTransaction();

			
			foreach($params['employee_list'] as $employee_id)
			{
			
				
								

				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $employee_id;
				$key            = $this->get_hash_key('compensation_id');
				$where[$key]    = $compensation_id;

				$table 						= $this->compensation->tbl_employee_compensations;
				$this->compensation->delete_compensation($table,$where);

				$audit_table[]			= $this->compensation->tbl_employee_compensations;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($prev);
				$curr_detail[]			= array();
				$audit_action[] 		= AUDIT_DELETE;	
			}
			

			$audit_activity 		= "Personnels were deleted from compensation ".$compensation_type['comppensation_name'];


			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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

	

	public function modal_benefits($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);


			$field 							= array("*") ;
			$table							= $this->compensation->tbl_param_compensations;
			$where							= array();
			$data['benefit_types'] 	    	= $this->compensation->get_compensation_data($field, $table, $where, TRUE);
		

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('compensation/modals/modal_benefits', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
	}


	public function modal_supporting_documents()
	{
		try
		{
			$data = array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);


			$this->load->view('compensation/modals/modal_supporting_documents', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}

	public function modal_employee_benefits($action = NULL, $employee_id = NULL,  $token = NULL, $salt = NULL, $module = NULL, $id = NULL)
	{
		try 
		{
			$data = array();

			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data['id'] 			= $id;
			$data['action']			= $action;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;

			$field                           = array("*") ;
			$table                           = $this->compensation->tbl_param_compensations;
			$where                           = array();
			$where['compensation_type_flag'] = array('S', array("!="));
			$where['active_flag']            = 'Y';
			$data['benefit_types']           = $this->compensation->get_compensation_data($field, $table, $where, TRUE);

			$field 							= array("*") ;
			$table							= $this->compensation->tbl_param_multipliers;
			$where							= array();
			$data['multipliers'] 	    	= $this->compensation->get_compensation_data($field, $table, $where, TRUE);


			// get frequency
			$field 							 = array("*") ;
			$table							= $this->compensation->tbl_param_frequencies;
			$where							= array();
			$data['frequency'] 	    		= $this->compensation->get_compensation_data($field, $table, $where, TRUE);

		

			if(!EMPTY($id))
			{

				//EDIT
				$field 						= array("*") ;
				//$table 						= $this->compensation->tbl_employee_compensations;
				$tables = array(
										'main'	=> array(
											'table'		=> $this->compensation->tbl_employee_compensations,
											'alias'		=> 'A',
										),
										't2'	=> array(
											'table'		=> $this->compensation->tbl_param_compensations,
											'alias'		=> 'B',
											'type'		=> 'join',
											'condition'	=> 'B.compensation_id = A.compensation_id',
										),
										
									);

				$where						= array();
				$key 						= $this->get_hash_key('employee_compensation_id');
				$where[$key]				= $id;
				$key 						= $this->get_hash_key('employee_id');
				$where[$key]				= $employee_id;
				$compensation_info 			= $this->compensation->get_compensation_data(array("*"), $tables, $where, FALSE);	
				$data ['benefit_info']		= $compensation_info;

				$field 							 = array("*") ;
				$table							 = $this->compensation->tbl_param_compensations;
				$where							 = array();
				$where['compensation_type_flag'] = array('S', array("!="));
				$where['active_flag'] = 'Y';
				$data['benefit_types'] 	    	 = $this->compensation->get_compensation_data($field, $table, $where, TRUE);

				$resources['single'] 		= array(
					'compensation_id'		=> $data['benefit_info']['compensation_id'],
					'multiplier' 			=> $data['benefit_info']['multiplier_id'],
					'frequency' 			=> $data['benefit_info']['frequency_id']
				);
			}

			$this->load_resources->get_resource($resources);
			$this->load->view('compensation/modals/modal_employee_benefits', $data);
			
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		
	}

	function get_benefits_data($compensation_id = 0) 
	{

		try 
		{

			
			$field 	= array("*") ;
			$table	= $this->compensation->tbl_param_compensations;
			$where	= array('compensation_id' => $compensation_id);
			$data 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);


		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw new Exception($message);
		}

		echo json_encode($data);
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
			$process_id = REQUEST_WORKFLOW_CERTIFICATE_EMPLOYMENT;
			
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

			$table						= $this->compensation->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $user_pds_id;
			$pds_data 					= $this->compensation->get_compensation_data(array("employee_id"), $table, $where, FALSE);
			
			/*############################ END : GET EMPLOYEE DATA #############################*/

			/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
			$cert_type = REQUEST_CERTIFICATE_EMPLOYMENT;

			if($valid_data["cert_type"] == TYPE_REQUEST_COE_SERVICE_RECORD)
			{
				$cert_type = REQUEST_SERVICE_RECORD;
				$process_id = REQUEST_WORKFLOW_SERVICE_RECORD;
			}
			$fields 						= array();
			$fields['employee_id']			= $pds_data["employee_id"];
			$fields['request_type_id']		= $cert_type;
			$fields['request_status_id']	= 1;
			$fields['date_requested']		= date("Y-m-d H:i:s");

			$table 							= $this->compensation->tbl_requests;
			$request_id						= $this->compensation->insert_compensation($table,$fields,TRUE);

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
			$table 				= $this->compensation->tbl_requests;

			$this->compensation->update_compensation($table,$fields,$where);

			/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

			/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
			$fields 							= array();
			$fields['employee_id']				= $pds_data["employee_id"];
			$fields['request_id']				= $request_id;
			$fields['request_sub_type_id']		= $valid_data["cert_type"];
			$fields['request_sub_status_id']	= SUB_REQUEST_NEW;
			$fields['action']					= ACTION_PROCESS;

			$table 								= $this->compensation->tbl_requests_sub;
			$request_sub_id						= $this->compensation->insert_compensation($table,$fields,TRUE);
			/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

			/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			$fields 						= array();
			$fields['request_sub_id']		= $request_sub_id;
			$fields['specific_details']		= $valid_data["purpose"];
			$fields['certfication_type_id']	= $valid_data["cert_type"];

			$table 							= $this->compensation->tbl_requests_certifications;
			$this->compensation->insert_compensation($table,$fields,FALSE);

			/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			

			/*SET UNHASED REQUEST ID TO $final_request_id */
			$final_request_id = $request_id;


			/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
			
			$workflow 		= $this->compensation->get_initial_task($process_id);
			

			$fields 					= array() ;
			$fields['request_id']		= $final_request_id;
			$fields['task_detail']		= $workflow['name'];
			$fields['process_id']		= $workflow['process_id'];
			$fields['process_stage_id']	= $workflow['process_stage_id'];
			$fields['process_step_id']	= $workflow['process_step_id'];
			$fields['task_status_id']	= 1;
                                         
			$table 						= $this->compensation->tbl_requests_tasks;
			$this->compensation->insert_compensation($table,$fields,FALSE);

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

	public function process_employee_benefit()
	{

		try
		{
			$params = get_params();
			$action = $params['action'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			$module = $params['module'];
			$emp_id = $params['employee_id'];

			

			
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) 
			{
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['employee_id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) 
				{
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['employee_id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) 
				{
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			// SERVER VALIDATION
			$valid_data = array();
			$prev_compensation_id = array();
			foreach ($params['compensation'] as $key => $value) 
			{
				$valid_data[]                = $this->_validate_data($value);
				$prev_compensation_id[]      = $value['compensation_id'];
			}

			//GET UNHASHED EMPLOYEE_ID
			$field                     = array("employee_id");
			$table                     = $this->compensation->tbl_employee_personal_info;
			$where                     = array();
			$key                       = $this->get_hash_key('employee_id');
			$where[$key]               = $emp_id;	
			
			$employee_id_unhashed      = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$employee_id               = $employee_id_unhashed['employee_id'];


			$module = MODULE_HR_COMPENSATION;
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->compensation->tbl_employee_compensations;
			$audit_table[]	= $table;
			$audit_schema[]	= DB_MAIN;
			$audit_action[]	= AUDIT_UPDATE;
			$prev_detail[]  = $this->compensation->get_compensation_data(array("*"), $table, $where);

			$where                    = array();
			$where['employee_id']     = $employee_id;
			$where['compensation_id'] = array($prev_compensation_id, array("IN"));

			$this->compensation->delete_compensation($table, $where);
			
			
			$fields = array();
			foreach ($valid_data as $key => $value) 
			{	
				RLog::error('VALID_DATA :'.json_encode($value));
				$fields[$key]['end_date'] = NULL;
				if(!EMPTY($value['end_date']))
				{

					// if(strtotime($value['start_date']) > strtotime($value['end_date']))
					// {
					// 	throw new Exception('End date should be later than the start date. Line #' . ($key+1));
					// }
					$fields[$key]['end_date'] = $value['end_date'];
				}
				$fields[$key]['compensation_id'] = $value['compensation_id'];
				$fields[$key]['employee_id']     = $employee_id;
				$fields[$key]['start_date']      = $value['start_date'];
			}
				RLog::error('FIELDS :'.json_encode($fields));

			$this->compensation->insert_compensation($table, $fields);


			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[]  = $fields;
			//MESSAGE ALERT
			$message = $this->lang->line('data_updated');
			
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been updated";
				
			
			
			$activity = sprintf($activity, 'Employee\'s Benefits');
	
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
			RLog::error($message);
		}
		$data['msg'] = $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}
	
	public function process_compensation()
	{

		try
		{

			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) 
			{
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ))
				{
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) 
				{

					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data = $this->_validate_data($params);

			//GET UNHASHED EMPLOYEE_ID
			$field 			= array("employee_id");
			$table 		 	= $this->compensation->tbl_employee_personal_info;
			$where  		= array();
			$key    		= $this->get_hash_key('employee_id');
			$where[$key]	= $params['employee_id'];

			$employee_id_unhashed 	= $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$employee_id 			= $employee_id_unhashed['employee_id'];

			//SET FIELDS VALUE
			
			$fields['compensation_id']   = $valid_data['compensation_id'];
			$fields['employee_id']       = $employee_id;
		 	$fields['start_date']        = $valid_data['start_date'];
			$fields['end_date']          = $valid_data['end_date'];
			$fields['payout_schedule']   = $valid_data['payout_schedule'];
			$fields['amount']   	  	 = $valid_data['amount'];
			$fields['frequency']         = $valid_data['frequency'];

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->compensation->tbl_employee_compensation_hdr;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]	= AUDIT_INSERT;
				
				$prev_detail[]	= array();

				//INSERT DATA
				$compensation_id = $this->compensation->insert_compensation($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 		 = $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 			= array();
				$where['compensation_hdr_id']	= $compensation_id;
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('compensation_hdr_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);

				
				//UPDATE DATA
				$this->cl->update_compensation($table, $fields, $where);


				//MESSAGE ALERT
				$message = $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->compensation->get_compensation_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, 'Benefits');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
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
			RLog::error($message);
		}
		$data['msg'] = $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}


	private function _validate_data($params)
	{
		if(EMPTY($params['compensation_id']))
			throw new Exception('
				Benefit Type is required.');	

		if(EMPTY($params['start_date']))
			throw new Exception('
				Start Date is required.');

		return $this->_validate_input ($params);
	}

	private function _validate_add_personel($params)
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
			
			$validation ['compensation_id'] = array (
					'data_type' => 'string',
					'name' => 'benefit id',
					'max_len' => 11 
			);
			$validation ['start_date'] = array (
					'data_type' => 'date',
					'name' => 'start date'
			);
			$validation ['end_date'] = array (
					'data_type' => 'date',
					'name' => 'end date'
			);
			

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			
			throw $e;
		}
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

	// ====================== jendaigo : start : include validations for employee bank account encoding ============= //
	private function _validate_add_bank_acc($params)
	{
		if(EMPTY($params['identification_value']))
			throw new Exception('
				Account Number is required.');	

		if(EMPTY($params['effective_date']))
			throw new Exception('
				Effectivity Date is required.');
				
		if(EMPTY($params['remarks']))
			throw new Exception('
				Remarks is required.');

		return $this->_validate_input_bank_accs ($params);
	}
	
	private function _validate_input_bank_accs($params) {
		
		try {
			
			$validation ['identification_value'] = array (
					'data_type' => 'string',
					'name' => 'account number',
					'max_len' => 50 
			);
			
			$validation ['effective_date'] = array (
					'data_type' => 'date',
					'name' => 'effectivity date'
			);
			$validation ['remarks'] = array (
					'data_type' => 'string',
					'name' => 'remarks',
					'max_len' => 255 
			);

			return $this->validate_inputs($params, $validation);

		} 
		catch ( Exception $e ) {
			
			throw $e;
		}
	}
	
	private function _addtl_validate_input_bank_accs($params, $valid_data) {
	
		//EFECTIVITY DATE
		$valid_data['effective_date'] = date("Y-m-d", strtotime($valid_data['effective_date']));
				
		$field 			= array("A.employee_id", "A.identification_type_id", "A.employee_identification_id", "B.employee_identification_detail_id", "A.identification_value", "B.start_date", "B.end_date", "B.remarks", "CONCAT(C.first_name, IF((C.middle_name='NA' OR C.middle_name='N/A' OR C.middle_name='-' OR C.middle_name='/'), '', CONCAT(' ', LEFT(C.middle_name, 1), '. ')), C.last_name, IF(C.ext_name='', '', CONCAT(' ', C.ext_name))) as fullname");
		$tables 		= array(
			'main' 			=> array(
				'table' 	=> $this->compensation->tbl_employee_identifications,
				'alias' 	=> 'A'
			),
			't1' 			=> array(
				'table'		=> $this->compensation->tbl_employee_identification_details,
				'alias'		=> 'B',
				'type'      => 'JOIN',
				'condition' => 'B.employee_identification_id = A.employee_identification_id'
			),
			't2' 			=> array(
				'table'		=> $this->compensation->tbl_employee_personal_info,
				'alias'		=> 'C',
				'type'      => 'JOIN',
				'condition' => 'C.employee_id = A.employee_id'
			)
		);
	
		// GET PREVIOUS ENTRY DATA
		$where								= array();
		$key    							= $this->get_hash_key('A.employee_id');
		$where[$key]						= $params['employee_id'];
		$where['A.identification_type_id'] 	= $params['identification_type_id'];
		$order_by 							= array('A.employee_identification_id' => 'DESC');
		
		if ($params ['action'] == ACTION_EDIT)
		{
			$group_by 					= array();
			$limit  					= 'LIMIT 1, 1';
			$data['prev_data_entry']  	= $this->compensation->get_compensation_data($field, $tables, $where, FALSE, $order_by, $group_by, $limit);

			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$where				= array();
			$key 				= $this->get_hash_key('A.employee_identification_id');
			$where[$key]		= $params['id'];
			
			$data['prev_detail_data']   = $this->compensation->get_compensation_data($field, $tables, $where, FALSE);
		}
		else
		{
			$data['prev_data_entry']  	= $this->compensation->get_compensation_data($field, $tables, $where, FALSE, $order_by);
		}
		
		// GET DUPLICATE ACCOUNT NUMBER
		$where							= array();
		$where['identification_value']	= $valid_data['identification_value'];
		$dumplicate_employee 			= $this->compensation->get_compensation_data($field, $tables, $where, FALSE);
			
		if(!EMPTY($dumplicate_employee))
		{
			if( ($params ['action'] == ACTION_ADD) OR ( ($params ['action'] == ACTION_EDIT) AND
				($data['prev_detail_data']['employee_identification_id'] != $dumplicate_employee['employee_identification_id'])) )
			throw new Exception('Account number already exist to employee '.$dumplicate_employee['fullname'].'.');
		}


		if(strlen($valid_data['identification_value']) < 10)
				throw new Exception('Account number should be 10 characters long.');

		if(!EMPTY($data['prev_data_entry']))
		{
			if($valid_data['effective_date'] < $data['prev_data_entry']['start_date'])
				throw new Exception('Effectivity Date should not be earlier than previous entry start date.');
				
			if($valid_data['effective_date'] == $data['prev_data_entry']['start_date'])
				throw new Exception('Effectivity Date should not be equal to previous entry start date.');
		}
		
		return $data;
	}
	// ====================== jendaigo : start : include validations for employee bank account encoding ============= //
	
	// ====================== jendaigo : start : include validations for employee responsibility code encoding ============= //
	private function _validate_responsibility_code($params)
	{
		if(EMPTY($params['responsibility_code']))
			throw new Exception('
				Responsibility Code is required.');	

		if(EMPTY($params['effective_date']))
			throw new Exception('
				Effectivity Date is required.');
				
		if(EMPTY($params['remarks']))
			throw new Exception('
				Remarks is required.');

		return $this->_validate_input_responsibility_code ($params);
	}
	
	private function _validate_input_responsibility_code($params) {
		
		try {
			
			$validation ['responsibility_code'] = array (
					'data_type' => 'string',
					'name' => 'responsibility code',
					'max_len' => 50 
			);
			
			$validation ['effective_date'] = array (
					'data_type' => 'date',
					'name' => 'effectivity date'
			);
			$validation ['remarks'] = array (
					'data_type' => 'string',
					'name' => 'remarks',
					'max_len' => 255 
			);

			return $this->validate_inputs($params, $validation);

		} 
		catch ( Exception $e ) {
			
			throw $e;
		}
	}
		
	private function _addtl_validate_input_responsibility_code($params, $valid_data) {

		//EFECTIVITY DATE
		$valid_data['effective_date'] = date("Y-m-d", strtotime($valid_data['effective_date']));
		
		$field	= array("*");
		$table	= $this->compensation->tbl_employee_responsibility_codes;
	
		// GET PREVIOUS ENTRY DATA
		$where								= array();
		$key    							= $this->get_hash_key('employee_id');
		$where[$key]						= $params['employee_id'];
		$order_by 							= array('employee_responsibility_code_id' => 'DESC');
		
		if ($params ['action'] == ACTION_EDIT)
		{
			$group_by 					= array();
			$limit  					= 'LIMIT 1, 1';
			$data['prev_data_entry']  	= $this->compensation->get_compensation_data($field, $table, $where, FALSE, $order_by, $group_by, $limit);
		
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$where				= array();
			$key 				= $this->get_hash_key('employee_responsibility_code_id');
			$where[$key]		= $params['id'];
			
			$data['prev_detail_data']   = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
		}
		else
		{
			$data['prev_data_entry']  	= $this->compensation->get_compensation_data($field, $table, $where, FALSE, $order_by);
		}

		if(!EMPTY($data['prev_data_entry']))
		{
			if($valid_data['effective_date'] < $data['prev_data_entry']['start_date'])
				throw new Exception('Effectivity Date should not be earlier than previous entry start date.');
				
			if($valid_data['effective_date'] == $data['prev_data_entry']['start_date'])
				throw new Exception('Effectivity Date should not be equal to previous entry start date.');
		}
		
		return $data;
	
	}
	// ====================== jendaigo : end : include validations for employee responsibility code encoding ============= //
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */