<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Performance_evaluation extends Main_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('performance_evaluation_model', 'performance_evaluation');
		$this->load->model('pds_model', 'pds');
	}

	public function index()
	{
		$data = $resources = array();

		$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATATABLE);
		$resources['datatable'][] = array('table_id' => 'table_employee_details', 'path' => 'main/performance_evaluation/get_employee_list', 'advanced_filter' => true);

		if($module != MODULE_PERSONNEL_PORTAL)
		{
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Human Resources"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/performance_evaluation";
			$key					= "Performance Evaluations"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/performance_evaluation";
			set_breadcrumbs($breadcrumbs, TRUE);
		}
		
		$fields = array('A.office_id','B.name AS office_name');
		$tables = array(
			'main' => array(
				'table' => $this->performance_evaluation->tbl_param_offices,
				'alias' => 'A'
			),
			't1'   => array(
				'table' => $this->performance_evaluation->db_core . '.' . $this->performance_evaluation->tbl_organizations,
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
		
		$user_office_scope = explode(',',$user_scopes['performance_evaluation']);
		$where['A.office_id'] = array($user_office_scope, array('IN'));
		//end
		
		$data['office_list'] = $this->performance_evaluation->get_general_data($fields, $tables, $where);
		
		$this->template->load('performance_evaluation/performance_evaluation', $data, $resources);
	}

	//GET ALL LIST OF EMPLOYEE
	public function get_employee_list()
	{
		try
		{
			$params         = get_params();
			
			$module = MODULE_HR_PERFORMANCE_EVALUATION;
			
			/*
			$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname", "E.name", "D.employment_status_name");
			$bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',A.middle_name)", "E.name", "D.employment_status_name");
			*/
			// ====================== jendaigo : start : change name format ============= //
			$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name))) as fullname", "E.name", "D.employment_status_name");
			$bColumns       = array("A.agency_employee_id", "CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name))))", "E.name", "D.employment_status_name");
			// ====================== jendaigo : end : change name format ============= //
			
			$pds_records    = $this->performance_evaluation->get_employee_list($aColumns, $bColumns, $params, $module);
			$iTotal         = $this->performance_evaluation->total_length();
			$iFilteredTotal = $this->performance_evaluation->filtered_length($aColumns, $bColumns, $params, $module);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$cnt = 0;
			foreach ($pds_records as $aRow):
				$cnt++;
				$row    = array();
				$action = "";
				

				$id           = $this->hash($aRow['employee_id']);
				
				$salt         = gen_salt();
				$token_view   = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view     = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit     = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				
				$row[] = $aRow['agency_employee_id'];
				$row[] = $aRow['fullname'];
				$row[] = $aRow['name'];
				$row[] = $aRow['employment_status_name'];
				
				$action       = "<div class='table-actions'>";

				
				// if($permission_view)
				$action 	.= "<a href='".base_url() . PROJECT_MAIN ."/performance_evaluation/employee_performance_evaluation_list/".$url_edit."' class='process tooltipped' data-tooltip='Process' data-position='bottom' data-delay='50' onclick=''></a>";
				
				$action 	.= '</div>';
					
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
	
	//GET THE LIST PERFORMANCE EVALUATION OF THE SPECIFIC EMPLOYEE
	public function employee_performance_evaluation_list($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{

		// $data['personal_info']    = $this->pds->get_employee_info($id);
		// ====================== jendaigo : start : modify model ============= //
		$data['personal_info']    = $this->performance_evaluation->get_employee_info($id);
		// ====================== jendaigo : end : modify model ============= //
		
		$data['employee_id']      = $id;
		$data['module']           = $module;
		
		// START: CHECK IF USER HAS ACCESS TO THE OFFICE
		$allow_office = $this->permission->check_office_permission(NULL, $id, TRUE, FALSE, $module);
		// END: CHECK IF USER HAS ACCESS TO THE OFFICE

		$data['allow_office']	  = $allow_office;
		
		// $resources                = array();
		
		$resources                = array();
		$resources['load_css']    = array('jquery-labelauty', 'jquery.dataTables');
		$resources['load_js']     = array('jquery-labelauty', 'jquery.dataTables.min');
		$resources['datatable'][] = array('table_id' => 'table_performance_evaluation', 'path' => 'main/performance_evaluation/get_employee_performance_evaluation_list/'.$id.'/'.$module, 'advanced_filter' => true);
		
		$resources['load_modal']		= array(
					'modal_performance_evaluation'		=> array(
							'controller'	=> strtolower(__CLASS__),
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_performance_evaluation',
							'multiple'		=> true,
							'height'		=> '320px',
							'size'			=> 'sm',
							'title'			=> 'Performance Evaluation'
					)
		);

		$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_performance_evaluation',
						PROJECT_MAIN
					);

		if($module == MODULE_PERSONNEL_PORTAL)
		{
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "My Portal"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/performance_evaluation/employee_performance_evaluation_list/".$action."/".$id."/".$token."/".$salt."/".$module;
			$key					= "Performance Evaluation"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/employee_performance_evaluation_list/".$action."/".$id."/".$token."/".$salt."/".$module;
			set_breadcrumbs($breadcrumbs, TRUE);
		}
		else
		{
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Performance Evaluations"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/performance_evaluation";
			$key					= "Performance Evaluation"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/employee_performance_evaluation_list/".$action."/".$id."/".$token."/".$salt."/".$module;
			
			set_breadcrumbs($breadcrumbs, FALSE);
		}

		$this->template->load('performance_evaluation/employee_performance_evaluation_list', $data, $resources);
	}

	//GET THE DETAILED INFORMATION OF SPECIFIC SERVICE RECORD
	public function get_employee_performance_evaluation_list($id, $module)
	{
		try
		{

			$params          = get_params();
			
			$aColumns        = array("A.employee_perf_eval_id", "DATE_FORMAT(A.evaluation_start_date, '%Y/%m/%d') as evaluation_start_date", "DATE_FORMAT(A.evaluation_end_date, '%Y/%m/%d') as evaluation_end_date", "A.rating", "A.rating_description", "A.remarks", "B.classification_field_name");
			$bColumns        = array("DATE_FORMAT(A.evaluation_start_date, '%Y/%m/%d')", "DATE_FORMAT(A.evaluation_end_date, '%Y/%m/%d')", "A.rating", "A.rating_description", "A.remarks", "B.classification_field_name");
			
			//GET ALL THE LIST OF SERVICE RECORD OF THE EMPLOYEE
			$evaluation_list = $this->performance_evaluation->get_employee_performance_evaluation_list($aColumns, $bColumns, $params, $id);
			$iTotal          = $this->performance_evaluation->evaluation_total_length($id);
			$iFilteredTotal  = $this->performance_evaluation->evaluation_filtered_length($aColumns, $bColumns, $params, $id);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

			$cnt         = 0;
			$employee_id = $id;
			
			// START: CHECK IF USER HAS ACCESS TO THE OFFICE
			$allow_office = $this->permission->check_office_permission(NULL, $employee_id, TRUE, FALSE, $module);
			// END: CHECK IF USER HAS ACCESS TO THE OFFICE

			foreach ($evaluation_list as $aRow):
				
				$cnt++;
				$row          = array();
				$action       = "";
				
				$id           = $this->hash($aRow['employee_perf_eval_id']);
				$salt         = gen_salt();
				$token_view   = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view     = ACTION_VIEW."/".$id."/".$token_view."/".$salt."/".$module."/".$employee_id;
				$url_edit     = ACTION_EDIT."/".$id."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete   = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;
				
				$row[]        = '<center>' . $aRow['evaluation_start_date']  . '</center>';
				$row[]        = '<center>' . $aRow['evaluation_end_date'] . '</center>';
				$row[]        = '<p class="m-n right">' . $aRow['rating'] . '</p>';
				$row[]        = strtoupper($aRow['rating_description']);
				$row[]        = strtoupper($aRow['remarks']);
				$row[]        = $aRow['classification_field_name'];
				
				$action        = "<div class='table-actions'>";
				$action        .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_performance_evaluation' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_performance_evaluation_init('".$url_view."')\"></a>";

				if($module != MODULE_PERSONNEL_PORTAL && $allow_office):
				$action        .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_performance_evaluation' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_performance_evaluation_init('".$url_edit."')\"></a>";
				$delete_action = 'content_delete("Performance Evaluation", "'.$url_delete.'")';
				$action        .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				endif;
				
				$action        .= "</div>";

				$row[]              = $action;
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

	//MODAL FOR ADDING OTHER SERVICE (OUTSIDE DOH)
	public function modal_performance_evaluation($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		try
		{
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_NUMBER, JS_SELECTIZE);
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$data ['module']      = $module;
			$data ['action']      = $action;
			$data ['salt']        = $salt;
			$data ['token']       = $token;
			$data ['id']          = $id;
			$data ['employee_id'] = $employee_id;

			if($action != ACTION_ADD) {
				$field                                   = array("*") ;
				$table                                   = $this->performance_evaluation->tbl_employee_performance_evaluations;
				$where                                   = array();
				$key                                     = $this->get_hash_key('employee_perf_eval_id');
				$where[$key]                             = $id;
				$employee_performance_evaluation         = $this->performance_evaluation->get_general_data($field, $table, $where, FALSE);
				$data['employee_performance_evaluation'] = $employee_performance_evaluation; 

				$resources['single'] 		   = array(
					'classification_field_id'  => $employee_performance_evaluation['classification_field_id']
				);
			}

			$field 							= array("*");
			$table							= $this->performance_evaluation->tbl_param_perf_eval_classification_fields;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['classification_field_id']   = array($employee_performance_evaluation['classification_field_id'], array("=", ")"));				
			}				
			$data['classifications'] 		= $this->performance_evaluation->get_general_data($field, $table, $where, TRUE);

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
		
		$this->load->view('performance_evaluation/modal/modal_performance_evaluation', $data);
		$this->load_resources->get_resource($resources);
	}

	//PROCESS FOR ADDING AND UPDATING PERFORMANCE EVALUATION
	public function process_employee_performance_evaluation()
	{
		try
		{
			$status      = FALSE;
			$message     = "";
			$reload_url  = "";
			
			$params      = get_params();

			$action      = $params['action'];
			$token       = $params['token'];
			$salt        = $params['salt'];
			$id          = $params['id'];
			$module      = $params['module'];
			$employee_id = $params['employee_id'];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			// CHECK DATA VALIDATION
			$valid_data = $this->_validate_performance_evaluation_record($params);
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			
			$fields = array();
			
			$fields['evaluation_start_date'] = $valid_data["evaluation_start_date"];
			$fields['evaluation_end_date']   = $valid_data["evaluation_end_date"];
			$fields['rating']                = $valid_data["rating"];
			$fields['rating_description']    = $valid_data["description"];
			$fields['remarks']               = $valid_data["remarks"];
			$fields['classification_field_id']= $valid_data["classification_field_id"];

			if($valid_data['evaluation_end_date'] < $valid_data['evaluation_start_date'])
			{
				throw new Exception('<b>Date Ended</b> should not be earlier than <b>Date Started</b>.');
			}

			if($action == ACTION_ADD)
			{
				
				//UNHASHED EMPLOYEE ID 
				$table                           = $this->performance_evaluation->tbl_employee_personal_info;
				$field                           = array("employee_id") ;
				$where                           = array();
				$key                             = $this->get_hash_key('employee_id');
				$where[$key]                     = $employee_id;
				$employee_performance_evaluation = $this->performance_evaluation->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_id']           = $employee_performance_evaluation['employee_id'];
				
				//VALIDATION IN INSERTING PERFORMANCE EVALUATION
				$table 							 = $this->performance_evaluation->tbl_employee_performance_evaluations;
				$field                           = array("evaluation_start_date, evaluation_end_date");
				$where                           = array();
				$where['employee_id']            = $employee_performance_evaluation['employee_id'];
				$order_by						 = array("evaluation_end_date" => "desc");
				$evaluation_date                 = $this->performance_evaluation->get_general_data($field, $table, $where, TRUE, $order_by);
				

				$exist          = FALSE;
				$new_start_date = format_date($valid_data['evaluation_start_date'],'Y-m-d');
				$new_end_date   = format_date($valid_data['evaluation_end_date'],'Y-m-d');
				if(!EMPTY($evaluation_date)) {
					foreach ($evaluation_date as $key => $date) {
						if($date['evaluation_start_date'] <= $new_start_date && $date['evaluation_end_date'] >= $new_start_date ) $exist = TRUE;
						if($date['evaluation_start_date'] <= $new_end_date && $date['evaluation_end_date'] >= $new_end_date ) $exist = TRUE;
						if($date['evaluation_start_date'] >= $new_start_date && $date['evaluation_end_date'] <= $new_end_date ) $exist = TRUE;
					}
				}
				
				if(EMPTY($exist))
				{
					$table              = $this->performance_evaluation->tbl_employee_performance_evaluations;
					$employee_system_id = $this->performance_evaluation->insert_general_data($table,$fields,TRUE);
					$audit_activity     = "Performance Evaluation has been added to employee records.";
					$status             = TRUE;
					$message            = $this->lang->line('data_saved');
				}
				else
				{
					$status             = FALSE;
					$message            = $this->lang->line('invalid_evaluation_date');
					$audit_activity     = "Invalid Performance Evaluation Date.";
				}
			

				$audit_table[]      = $this->performance_evaluation->tbl_employee_performance_evaluations;;
				$audit_schema[]     = DB_MAIN;
				$prev_detail[]      = array();
				$curr_detail[]      = array($fields);
				$audit_action[]     = AUDIT_INSERT;	


			}
			else
			{
				//USE FOR AUDIT TRAIL
				$field                          = array("*") ;
				$table                          = $this->performance_evaluation->tbl_employee_performance_evaluations;
				$where                          = array();
				$key                            = $this->get_hash_key('employee_id');
				$where[$key]                    = $employee_id;
				$personal_info                  = $this->performance_evaluation->get_general_data($field, $table, $where, FALSE);
				
				//VALIDATION FOR UPDATING THE PERFORMANCE EVALUATION
				$table 							 = $this->performance_evaluation->tbl_employee_performance_evaluations;
				$field                           = array("evaluation_start_date, evaluation_end_date");
				$where                           = array();
				$where['employee_id']            = $personal_info['employee_id'];
				$key                             = $this->get_hash_key('employee_perf_eval_id');
				$where[$key]                     = array($id, array('!='));
				$order_by						 = array("evaluation_end_date" => "desc");
				$evaluation_date                 = $this->performance_evaluation->get_general_data($field, $table, $where, TRUE, $order_by);
				

				$exist          = FALSE;
				$new_start_date = format_date($valid_data['evaluation_start_date'],'Y-m-d');
				$new_end_date   = format_date($valid_data['evaluation_end_date'],'Y-m-d');
				if(!EMPTY($evaluation_date)) {
					foreach ($evaluation_date as $key => $date) {
						if($date['evaluation_start_date'] <= $new_start_date && $date['evaluation_end_date'] >= $new_start_date ) $exist = TRUE;
						if($date['evaluation_start_date'] <= $new_end_date && $date['evaluation_end_date'] >= $new_end_date ) $exist = TRUE;
						if($date['evaluation_start_date'] >= $new_start_date && $date['evaluation_end_date'] <= $new_end_date ) $exist = TRUE;
					}
				}
				
				if(EMPTY($exist))
				{
					$where                          = array();
					$key                            = $this->get_hash_key('employee_perf_eval_id');
					$where[$key]                    = $id;
					$table                          = $this->performance_evaluation->tbl_employee_performance_evaluations;
					$this->performance_evaluation->update_general_data($table,$fields,$where);
				
					$audit_activity = "Performace Evaluation has been Updated.";
					$status         = TRUE;
					$message        = $this->lang->line('data_updated');
				}
				else
				{	
					$audit_activity = "Performace Evaluation has not been Updated, Invalid Evaluation Date.";
					$status         = FALSE;
					$message        = $this->lang->line('invalid_evaluation_date');
				}


				$audit_table[]  = $this->performance_evaluation->tbl_employee_performance_evaluations;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($personal_info);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				
			}
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}

		$data            = array();
		$data['message'] = $message;
		$data['status']  = $status;
		echo json_encode($data);
	}

	//VALIDATE ALL REQUIRED FIELDS IN OTHER SERVICE MODAL (OUTSIDE DOH)
	private function _validate_performance_evaluation_record($params)
	{
		try
		{
			//SPECIFY HERE INPUT NAME FROM USER
			//ALL REQUIRED FIELDS
			$fields                           = array();
			$fields['evaluation_start_date']  = "Start Date";
			$fields['evaluation_end_date']    = "End Date";
			$fields['rating']                 = "Rating";
			$fields['classification_field_id']= "Classification";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_performance_evaluation_record($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	//VALIDATE ALL INPUT DATA IN OTHER SERVICE MODAL (OUTSIDE DOH)
	private function _validate_input_performance_evaluation_record($params)
	{
		try
		{
			$validation ['evaluation_start_date'] = array(
				'data_type' => 'date',
				'name'      => 'Start Date'
			);
			$validation ['evaluation_end_date'] = array (
				'data_type' => 'date',
				'name'      => 'End Date' 
			);
			$validation ['rating'] = array (
				'data_type' => 'amount',
				'name'      => 'Rating',
				'max'       => 100 
			);
			$validation ['description'] = array (
				'data_type' => 'string',
				'name'      => 'Rating Description',
				'max'       => 100 
			);
			$validation ['remarks'] = array (
				'data_type' => 'string',
				'name'      => 'Remarks',
				'max_len'   => 100 
			);
			$validation ['classification_field_id'] = array (
				'data_type' => 'string',
				'name'      => 'Classification'
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	//DELETE THE SPECIFIC SERVICE RECORD
	public function delete_performance_evaluation()
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
			$employee_id = $url_explode[5];


			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			Main_Model::beginTransaction();
			
			//GET PREVIOUS DATA
			$prev_data              = array() ;
			/*GET PREVIOUS DATA*/
			$field                  = array("*") ;
			$table                  = $this->performance_evaluation->tbl_employee_performance_evaluations;
			$where                  = array();
			$key                    = $this->get_hash_key('employee_perf_eval_id');
			$where[$key]            = $id;
			$performance_evaluation = $this->performance_evaluation->get_general_data($field, $table, $where, FALSE);
			
			//DELETE DATA
			$where                  = array();
			$key                    = $this->get_hash_key('employee_perf_eval_id');
			$where[$key]            = $id;
			$table                  = $this->performance_evaluation->tbl_employee_performance_evaluations;
			
			$this->performance_evaluation->delete_general_data($table,$where);
			
			$audit_table[]          = $this->performance_evaluation->tbl_employee_performance_evaluations;
			$audit_schema[]         = DB_MAIN;
			$prev_detail[]          = array($performance_evaluation);
			$curr_detail[]          = array();
			$audit_action[]         = AUDIT_DELETE;
			$audit_activity         = "Performance Evaluation has been deleted.";
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
			"table_id"        => 'table_performance_evaluation',
			"path"            => PROJECT_MAIN . '/performance_evaluation/get_employee_performance_evaluation_list/'.$employee_id.'/'.$module,
			"advanced_filter" => true
		);

		echo json_encode($response);
	}

	public function get_rating_description() {
		
		$status                    = 0;
		$result                    = array();
		
		$params                    = get_params();
		$table                     = $this->performance_evaluation->tbl_param_performance_rating;
		$where                     = array();
		$where['rating_min_value'] = array($params['rating'], array('<='));
		$where['rating_max_value'] = array($params['rating'], array('>='));
		$description               = $this->performance_evaluation->get_general_data(array("rating_description"), $table, $where, FALSE);
		$status                    = 1;
		
		$result['status']          = $status;
		$result['description']     = $description['rating_description'];

		echo json_encode($result);

	}
}


/* End of file performance_evaluation.php*/
/* Location: ./application/modules/main/controllers/Performance_evaluation.php */
