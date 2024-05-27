<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//RLog::info('LINE 2209 =>'.json_encode($fields));
class Code_library_hr extends Main_Controller {
	private $module = MODULE_ROLE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function get_tab($form, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data 					= array();
			// $resources['load_css'] 	= array(CSS_LABELAUTY, CSS_SELECTIZE,CSS_DATETIMEPICKER, CSS_CALENDAR);
			// $resources['load_js'] 	= array(JS_LABELAUTY,JS_SELECTIZE, JS_DATETIMEPICKER, JS_CALENDAR, JS_CALENDAR_MOMENT);
			$data['action_id']		= $action_id;

			switch ($form) 
			{
				/*----- START GET TAB HR -----*/
				case 'compensation_type':
					$resources['load_css'][] 		= CSS_DATATABLE;
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['datatable'][]		= array('table_id' => 'compensation_type_table', 'path' => 'main/code_library_hr/get_compensation_type_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 		= array(
						'modal_compensation_type' 	=> array(
							'controller'			=> __CLASS__,
							'module'				=> PROJECT_MAIN,
							'method'				=> 'modal_compensation_type',
							'multiple'				=> true,
							'height'				=> '400px',
							'size'					=> 'md',
							'title'					=> 'Compensation Type'
						)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_compensation_type',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'deduction_type':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'deduction_type_table', 'path' => 'main/code_library_hr/get_deduction_type_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_deduction_type'	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_deduction_type',
							'multiple'			=> true,
							'height'			=> '420px',
							'size'				=> 'lg',
							'title'				=> 'Deduction Type'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_deduction_type',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'education_degree':
					$resources['load_css'][] 		= CSS_DATATABLE;
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['datatable'][]		= array('table_id' => 'education_degree_table', 'path' => 'main/code_library_hr/get_education_degree_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 		= array(
						'modal_education_degree' 	=> array(
							'controller'			=> __CLASS__,
							'module'				=> PROJECT_MAIN,
							'method'				=> 'modal_education_degree',
							'multiple'				=> true,
							'height'				=> '150px',
							'size'					=> 'sm',
							'title'					=> 'Education Degree'
						)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_education_degree',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;	

				case 'educational_level':
					$resources['load_css'][] 		= CSS_DATATABLE;
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['datatable'][]		= array('table_id' => 'educational_level_table', 'path' => 'main/code_library_hr/get_educational_level_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 		= array(
						'modal_educational_level' 	=> array(
							'controller'			=> __CLASS__,
							'module'				=> PROJECT_MAIN,
							'method'				=> 'modal_educational_level',
							'multiple'				=> true,
							'height'				=> '150px',
							'size'					=> 'sm',
							'title'					=> 'Educational Level'
						)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_educational_level',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;	

				case 'eligibility':
					$resources['load_css'][] 		= CSS_DATATABLE;
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['datatable'][]		= array('table_id' => 'eligibility_table', 'path' => 'main/code_library_hr/get_eligibility_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 		= array(
						'modal_eligibility_type' 	=> array(
							'controller'			=> __CLASS__,
							'module'				=> PROJECT_MAIN,
							'method'				=> 'modal_eligibility_type',
							'multiple'				=> true,
							'height'				=> '200px',
							'size'					=> 'sm',
							'title'					=> 'Eligibility'
						)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_eligibility',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'employment_status':
					$resources['load_css'][] 		= CSS_DATATABLE;
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['datatable'][]		= array('table_id' => 'employment_status_table', 'path' => 'main/code_library_hr/get_employment_status_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 		= array(
						'modal_employment_status' 	=> array(
							'controller'			=> __CLASS__,
							'module'				=> PROJECT_MAIN,
							'method'				=> 'modal_employment_status',
							'multiple'				=> true,
							'height'				=> '150px',
							'size'					=> 'sm',
							'title'					=> 'Employment Status'
						)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_employment_status',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;	

				case 'branch':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'branch_table', 'path' => 'main/code_library_hr/get_branch_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_branch' 			=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_branch',
							'multiple'			=> true,
							'height'			=> '200px',
							'size'				=> 'sm',
							'title'				=> 'Government Branch'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_branch',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;	

				case 'separation_mode':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'separation_mode_table', 'path' => 'main/code_library_hr/get_separation_mode_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_separation_mode' => array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_separation_mode',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'Mode of Separation'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_separation_mode',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'personnel_movement':
					$resources['load_css'][] 		= CSS_DATATABLE;
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['datatable'][]		= array('table_id' => 'personnel_movement_table', 'path' => 'main/code_library_hr/get_personnel_movement_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 		= array(
						'modal_personnel_movement' 	=> array(
							'controller'			=> __CLASS__,
							'module'				=> PROJECT_MAIN,
							'method'				=> 'modal_personnel_movement',
							'multiple'				=> true,
							'height'				=> '300px',
							'size'					=> 'sm',
							'title'					=> 'Personnel Movement'
						)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_personnel_movement',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'plantilla':
					$resources['load_css'][]   	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'table_plantilla', 'path' => 'main/code_library_hr/get_plantilla_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_plantilla' 		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_plantilla',
							'multiple'			=> true,
							'height'			=> '300px',
							'size'				=> 'md',
							'title'				=> CODE_LIBRARY_PLANTILLA
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_plantilla',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'position':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'position_table', 'path' => 'main/code_library_hr/get_position_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_position' 		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_position',
							'multiple'			=> true,
							'height'			=> '350px',
							'size'				=> 'sm',
							'title'				=> 'Position'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_position',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'salary_schedule':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'salary_schedule_table', 'path' => 'main/code_library_hr/get_salary_schedule_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_salary_schedule' => array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_salary_schedule',
							'multiple'			=> true,
							'height'			=> '450px',
							'size'				=> 'xl',
							'title'				=> 'Salary Schedule'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_salary_schedule',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				
				case 'school':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'school_table', 'path' => 'main/code_library_hr/get_school_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_school' 			=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_school',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'School'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_school',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				/*----- END GET TAB HR -----*/
			}

			$data['action_id'] = $action_id;
			$this->load->view('code_library/tabs/'.$view_form, $data);
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

	/*----- START PROCESS HR -----*/
	public function process_compensation_type()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					//throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					//throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 						= $this->_validate_data_compensation_type($params);

			//SET FIELDS VALUE
			$fields 							= array();
			$fields['compensation_name']		= $valid_data['compensation_name'];
			$fields['compensation_code'] 		= $valid_data['compensation_code'];
			$fields['frequency_id'] 			= $valid_data['frequency'];
			$fields['parent_compensation_id'] 	= $valid_data['parent_compensation'];
			$fields['tenure_rqmt_val'] 			= $valid_data['tenure_rqmt_val'];
			$fields['tenure_rqmt_flag'] 		= $valid_data['tenure_rqmt_flag'];
			$fields['compensation_type_flag']  	= isset($valid_data['compensation_type_flag']) ? "V" : "F";
			$fields['deminimis_flag'] 			= isset($valid_data['deminimis_flag']) ? "Y" : "N";
			$fields['general_payroll_flag'] 	= isset($valid_data['general_payroll_flag']) ? "Y" : "N";
			$fields['taxable_flag'] 			= isset($valid_data['taxable_flag']) ? "Y" : "N";
			$fields['basic_salary_flag'] 		= isset($valid_data['basic_salary_flag']) ? "Y" : "N";
			$fields['other_salary_flag'] 		= isset($valid_data['other_salary_flag']) ? "Y" : "N";
			$fields['monetization_flag'] 		= isset($valid_data['monetization_flag']) ? "Y" : "N";
			$fields['active_flag']  			= isset($valid_data['active_flag']) ? "Y" : "N";

			if($valid_data['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE)
			{
				$fields['multiplier_id'] 		= $valid_data['multiplier'];
				$fields['rate'] 				= $valid_data['rate'];
			}
			if($valid_data['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_FIXED)
			{
				$fields['amount'] 				= $valid_data['amount'];
			}

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			 	= $this->cl->tbl_param_compensations;	

			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$compensation_id = $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_compensations;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;				

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved'); 
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('compensation_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_compensations;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
			}
			
			$activity = sprintf($activity, $params['compensation_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_deduction_type()
	{
		try
		{
			$status = 0;
			$params	= get_params();
			
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION 
			$valid_data 						= $this->_validate_data_deduction_type($params);

			//SET FIELDS VALUE
			$fields 							= array();
			$fields['deduction_name'] 			= $valid_data['deduction_name'];
			$fields['deduction_code'] 			= $valid_data['deduction_code'];
			$fields['deduction_type_flag'] 		= $valid_data['deduction_type_flag'];
			$fields['frequency_id'] 			= $valid_data['frequency'];
			$fields['employee_flag']    		= $valid_data['employee_flag'];
			$fields['remittance_type_id']    	= $valid_data['remittance_type'];
			$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";

			if($valid_data['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_FIXED)
			{
				$fields['amount'] 				= $valid_data['amount'];
			}
			else if($valid_data['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_VARIABLE)
			{
				$fields['multiplier_id'] 		= $valid_data['multiplier'];
				$fields['rate'] 				= $valid_data['rate'];
			}

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_deductions;	
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$deduction_id 	= $this->cl->insert_code_library($table, $fields, TRUE);
				
				//INSERT DATA INTO OTHER DEDUCTION DETAILS
				for ($x = 0; $x<count($valid_data['other_detail_name']); $x++ ) {

					$fields 							= array();
					$fields['deduction_id']    			= $deduction_id ;
					$fields['other_detail_name']    	= $valid_data['other_detail_name'][$x];
					$fields['other_detail_type']    	= $valid_data['other_detail_type'][$x];
					$fields['dropdown_flag']    		= $valid_data['dropdown_flag'][$x];
					$fields['pk_flag']    				= isset($valid_data['pk_flag'][$x]) ? "Y" : "N";
					$fields['required_flag']    		= isset($valid_data['required_flag'][$x]) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_other_deduction_details, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_deductions;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;		
				
				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('deduction_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				// DELETE OTHER DEDUCTION DETAILS
				$where						= array();
				$key 						= $this->get_hash_key('deduction_id');
				$where[$key]				= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_other_deduction_details, $where);

				//EDIT DATA INTO OTHER DEDUCTION DETAILS
				for ($x = 0; $x<count($valid_data['other_detail_name']); $x++ ) {

					$fields 							= array();
					$fields['deduction_id']    			= $deduction_info['deduction_id'];
					$fields['other_detail_name']    	= $valid_data['other_detail_name'][$x];
					$fields['other_detail_type']    	= $valid_data['other_detail_type'][$x];
					$fields['dropdown_flag']    		= $valid_data['dropdown_flag'][$x];
					$fields['pk_flag']    				= isset($valid_data['pk_flag'][$x]) ? "Y" : "N";
					$fields['required_flag']    		= isset($valid_data['required_flag'][$x]) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_other_deduction_details, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]					= $this->cl->tbl_param_deductions;
				$audit_schema[]					= Base_Model::$schema_core;
				$prev_detail[]  				= array($previous);
				$curr_detail[]  				= array($fields);
				$audit_action[]        			= AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['deduction_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_education_degree()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 			= $this->_validate_data_education_degree($params);

			//SET FIELDS VALUE
			$fields 				= array();
			$fields['degree_name'] 	= $valid_data['degree_name'];
			$fields['active_flag'] 	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_education_degrees;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$degree_id 		= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_education_degrees;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('degree_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_education_degrees;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['degree_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_educational_level()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 				= $this->_validate_data_educational_level($params);

			//SET FIELDS VALUE
			$fields['educ_level_name'] 	= $valid_data['educ_level_name'];
			$fields['active_flag'] 		= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_educational_levels;

			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$educ_level_id 	= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_educational_levels;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('educ_level_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_educational_levels;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['educ_level_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_eligibility()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					//throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					//throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 					 = $this->_validate_data_eligibility($params);

			//SET FIELDS VALUE
			$fields['eligibility_type_code'] = $valid_data['eligibility_type_code'];
			$fields['eligibility_type_name'] = $valid_data['eligibility_type_name'];
			$fields['active_flag']   		 = isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 						 	= $this->cl->tbl_param_eligibility_types;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$eligibility_type_id 		 = $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				 = $this->cl->tbl_param_eligibility_types;
				$audit_schema[]				 = Base_Model::$schema_core;
				$prev_detail[]  			 = array();
				$curr_detail[]  			 = array($fields);
				$audit_action[] 			 = AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 					 = $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 					 = "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('eligibility_type_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);				

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_eligibility_types;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['eligibility_type_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_employment_status()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 						= $this->_validate_data_employment_status($params);

			//SET FIELDS VALUE
			$fields['employment_status_name'] 	= $valid_data['employment_status_name'];
			$fields['active_flag'] 				= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_employment_status;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$employment_status_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				 	= $this->cl->tbl_param_employment_status;
				$audit_schema[]				 	= Base_Model::$schema_core;
				$prev_detail[]  			 	= array();
				$curr_detail[]  			 	= array($fields);
				$audit_action[] 			 	= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 					 	= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 					 	= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('employment_status_id');
				$where[$key]	= $params['id'];
								
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_employment_status;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['employment_status_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_branch()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 			= $this->_validate_data_branch($params);

			//SET FIELDS VALUE
			$fields['branch_name'] 	= $valid_data['branch_name'];
			$fields['branch_code'] 	= $valid_data['branch_code'];
			$fields['active_flag'] 	= isset($valid_data['active_flag']) ? "Y" : "N";			

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 					= $this->cl->tbl_param_government_branches;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$branch_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]		= $this->cl->tbl_param_government_branches;
				$audit_schema[]		= Base_Model::$schema_core;
				$prev_detail[]  	= array();
				$curr_detail[]  	= array($fields);
				$audit_action[] 	= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 			= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 			= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('branch_id');
				$where[$key]	= $params['id'];

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_government_branches;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['branch_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_separation_mode()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 					= $this->_validate_data_separation_mode($params);

			//SET FIELDS VALUE
			$fields['separation_mode_name'] = $valid_data['separation_mode_name'];
			$fields['active_flag']			= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 							= $this->cl->tbl_param_separation_modes;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$separation_mode_id 		= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				= $this->cl->tbl_param_separation_modes;
				$audit_schema[]				= Base_Model::$schema_core;
				$prev_detail[]  			= array();
				$curr_detail[]  			= array($fields);
				$audit_action[] 			= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_saved');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 					= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('separation_mode_id');
				$where[$key]	= $params['id'];
								
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_separation_modes;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['separation_mode_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_personnel_movement()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 						= $this->_validate_data_personnel_movement($params);

			//SET FIELDS VALUE
			$fields['personnel_movement_name'] 	= $valid_data['personnel_movement_name'];
			$fields['needs_appointment'] 		= isset($valid_data['needs_appointment']) ? "Y" : "N";
			$fields['needs_office_order'] 		= isset($valid_data['needs_office_order']) ? "Y" : "N";
			$fields['replacement_reason'] 		= $valid_data['replacement_reason'];
			$fields['active_flag']				= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_personnel_movements;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$personnel_movement_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]					= $this->cl->tbl_param_personnel_movements;
				$audit_schema[]					= Base_Model::$schema_core;
				$prev_detail[]  				= array();
				$curr_detail[]  				= array($fields);
				$audit_action[] 				= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 						= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('personnel_movement_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_personnel_movements;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;


				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
								
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['personnel_movement_id']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_plantilla()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					//throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					//throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data                      = $this->_validate_data_plantilla($params);
			
			//SET FIELDS VALUE
			$fields                          = array();
			$fields['plantilla_code']        = $valid_data['plantilla_code'];
			$fields['position_id']           = $valid_data['position_id'];
			$fields['office_id'] 			 = $valid_data['office'];
			$fields['division_id']   	     = !EMPTY($valid_data['division']) ? $valid_data['division']:NULL;//ncocampo
			$fields['parent_plantilla_id']   = !EMPTY($valid_data['parent_plantilla_id']) ? $valid_data['parent_plantilla_id']:NULL;
			$fields['active_flag']			 = isset($valid_data['active_flag']) ? "Y" : "N";			
			$fields['rata']			 		 = isset($valid_data['rata']) ? "Y" : "N";	
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table                           = $this->cl->tbl_param_plantilla_items;
	
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$plantilla_id          		 = $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				 = $this->cl->tbl_param_plantilla_items;
				$audit_schema[]				 = Base_Model::$schema_core;
				$prev_detail[]  			 = array();
				$curr_detail[]  			 = array($fields);
				$audit_action[] 			 = AUDIT_INSERT;		
				
				//MESSAGE ALERT
				$message              		 = $this->lang->line('data_saved');
									
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity             		 = "%s has been added";
			}
			else
			{
				//WHERE 
				$where          = array();
				$key            = $this->get_hash_key('plantilla_id');
				$where[$key]    = $params['id'];
								
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_plantilla_items;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;
				
				//MESSAGE ALERT
				$message        = $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity       = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['plantilla_name']);
	
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
		$data['msg']    = $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_position()
	{
		try
		{
			$status = 0;
			$params	= get_params();
			
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 					= $this->_validate_data_position($params);

			//SET FIELDS VALUE
			$fields['position_name']  		= $valid_data['position_name'];
			$fields['position_level_id'] 	= $valid_data['position_level'];
			$fields['position_class_id'] 	= $valid_data['position_class'];
			$fields['salary_grade']   		= $valid_data['salary_grade'];
			$fields['salary_step']    		= $valid_data['salary_step'];
			$fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 							= $this->cl->tbl_param_positions;
				
			if(EMPTY($params['id']))
			{				
				//INSERT DATA
				$position_id          		= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				= $this->cl->tbl_param_positions;
				$audit_schema[]				= Base_Model::$schema_core;
				$prev_detail[]  			= array();
				$curr_detail[]  			= array($fields);
				$audit_action[] 			= AUDIT_INSERT;						
				
				//MESSAGE ALERT
				$message             		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity            		= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where          = array();
				$key            = $this->get_hash_key('position_id');
				$where[$key]    = $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_positions;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;
				
				//MESSAGE ALERT
				$message        = $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity       = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['position_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_salary_schedule()
	{
		try
		{

			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 		= $this->_validate_data_salary_schedule($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();

			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$amt_cnt 		= count($params['amount']);

				foreach ($params['amount'] as $grade => $salary_steps) {

					foreach ($salary_steps as $step => $amount) {
						
						$fields 							= array();
						$fields['effectivity_date']			= $valid_data['effectivity_date'];
						$fields['budget_circular_number']	= $valid_data['budget_circular_number'];
						$fields['budget_circular_date']		= $valid_data['budget_circular_date'];
						$fields['executive_order_number']	= $valid_data['executive_order_number'];
						$fields['execute_order_date']		= $valid_data['execute_order_date'];
						$fields['salary_grade']				= $grade;
						$fields['salary_step']				= $step;
						$fields['amount'] 					= $amount;	
						$fields['other_fund_flag']    		= isset($valid_data['other_fund_flag']) ? "Y" : "N";   
						$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";

						$this->cl->insert_code_library($this->cl->tbl_param_salary_schedule, $fields);
					}
				}
				
				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_salary_schedule;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{	
				//WHERE 
				$where						= array();
				$key 						= $this->get_hash_key('effectivity_date');
				$where[$key]				= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  					= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//DELETE DATA
				$where 						= array();
				$where['effectivity_date'] 	= $valid_data['prev_date'];

				$this->cl->delete_code_library($this->cl->tbl_param_salary_schedule, $where);

				$amt_cnt 					= count($params['amount']);

				foreach ($params['amount'] as $grade => $salary_steps) {

					foreach ($salary_steps as $step => $amount) {
						
						$fields 							= array();
						$fields['effectivity_date']			= $valid_data['effectivity_date'];
						$fields['effectivity_date']			= $valid_data['effectivity_date'];
						$fields['budget_circular_number']	= $valid_data['budget_circular_number'];
						$fields['budget_circular_date']		= $valid_data['budget_circular_date'];
						$fields['executive_order_number']	= $valid_data['executive_order_number'];
						$fields['execute_order_date']		= $valid_data['execute_order_date'];
						$fields['salary_grade']				= $grade;
						$fields['salary_step']				= $step;
						$fields['amount'] 					= $amount;	   
						$fields['other_fund_flag']    		= isset($valid_data['other_fund_flag']) ? "Y" : "N";  
						$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";

						$this->cl->insert_code_library($this->cl->tbl_param_salary_schedule, $fields);
					}
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_salary_schedule;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}

			$activity = sprintf($activity, $params['effectivity_date']);
	
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
		$data['message'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_school()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 			= $this->_validate_data_school($params);

			//SET FIELDS VALUE
			$fields['school_name'] 	= $valid_data['school_name'];
			$fields['active_flag']	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 					= $this->cl->tbl_param_schools;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$school_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]		= $this->cl->tbl_param_schools;
				$audit_schema[]		= Base_Model::$schema_core;
				$prev_detail[]  	= array();
				$curr_detail[]  	= array($fields);
				$audit_action[] 	= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 			= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 			= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('school_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_schools;
				$audit_schema[]	= Base_Model::$schema_core;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
								
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['school_name']);
	
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
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}
	/*----- END PROCESS HR -----*/

	
	/*------------------------HUMAN RESOURCES VALIDATE DATA START ----------------------------*/
	private function _validate_data_compensation_type($params)
	{
		$fields                  			= array();
		$fields['compensation_name']  		= "Compensation Name";
		$fields['compensation_code']  		= "Compensation Code";

		if ($params['tenure_rqmt_flag'] == TENURE_RQMT_NA)
		$fields['tenure_rqmt_val']  		= "Tenure Requirement Value";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_compensation_input_type ($params);
	}

	private function _validate_compensation_input_type($params) 
	{
		try {
			
			$validation ['compensation_name'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Compensation Name',
					'max_len' 						=> 50 
			);
			$validation ['compensation_code'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'compensation Code',
					'max_len' 						=> 45 
			);
			$validation ['parent_compensation'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Parent Compensation',
					'max_len' 						=> 45 
			);
			$validation ['compensation_type_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Compensation Type',
					'max_len' 						=> 1
			);
			$validation ['amount'] 					= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Amount',
					'max_len' 						=> 45 
			);
			$validation ['multiplier'] 				= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Base Multiplier',
					'max_len' 						=> 100
			);
			$validation ['frequency'] 				= array (
					'data_type'						=> 'string',
					'name' 							=> 'Frequency',
					'max_len' 						=> 45 
			);
			$validation ['rate'] 					= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Rate',
					'max_len' 						=> 45 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			$validation ['taxable_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Taxable Flag',
					'max_len' 						=> 1 
			);
			$validation ['employee_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employee Flag',
					'max_len' 						=> 1 
			);
			$validation ['deminimis_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Deminimis Flag',
					'max_len' 						=> 1 
			);
			$validation ['general_payroll_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'General Payroll Flag',
					'max_len' 						=> 1 
			);
			$validation ['tenure_rqmt_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Tenure Requirement Flag',
					'max_len' 						=> 2
			);
			$validation ['basic_salary_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Basic Salary Flag',
					'max_len' 						=> 1 
			);
			$validation ['other_salary_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Other Salary Flag',
					'max_len' 						=> 1 
			);
			$validation ['monetization_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Monetization Flag',
					'max_len' 						=> 1
			);
			$validation ['tenure_rqmt_val'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Tenure Requirement Value',
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_deduction_type($params)
	{
		$fields                  		= array();
		$fields['deduction_name']  		= "Deduction Name";
		$fields['deduction_code']  		= "Deduction Code";
		$fields['deduction_type_flag'] 	= "Deduction Type";
		$fields['frequency']  			= "Frequency";
		$fields['remittance_type']  	= "Remittance Type";
		$fields['employee_flag'] 		= "Employee Flag";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_deduction_type_input ($params);
	}

	private function _validate_deduction_type_input($params) 
	{
		try {
			
			$validation ['deduction_name'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Deduction Name',
					'max_len' 					=> 50 
			);
			$validation ['deduction_code'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Deduction Code',
					'max_len' 					=> 45
			);
			$validation ['deduction_type_flag'] = array (
					'data_type' 				=> 'string',
					'name' 						=> 'Deduction Type',
					'max_len' 					=> 2
			);
			$validation ['frequency'] 			= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Frequency',
					'max_len' 					=> 50 
			);
			$validation ['remittance_type'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Remittance Type',
					'max_len' 					=> 45
			);
			$validation ['employee_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Employee Flag',
					'max_len' 					=> 1
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1 
			);	
			$validation ['other_detail_name'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Other Detail Name',
					'max_len' 					=> 50
			);	
			$validation ['other_detail_type'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Other Detail Type',
					'max_len' 					=> 2
			);	
			$validation ['dropdown_flag'] 		= array (
					'data_type'					=> 'string',
					'name' 						=> 'Dropdown Flag',
					'max_len' 					=> 4
			);	
			$validation ['pk_flag'] 			= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Primary Flag',
					'max_len' 					=> 1
			);	
			$validation ['required_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Required Flag',
					'max_len' 					=> 1
			);	
			$validation ['multiplier'] 			= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Base Multiplier',
					'max_len' 					=> 100
			);
			$validation ['rate'] 				= array (
					'data_type' 				=> 'amount',
					'name' 						=> 'Rate'
			);
			$validation ['amount'] 				= array (
					'data_type' 				=> 'amount',
					'name' 						=> 'Amount'
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_education_degree($params)
	{
		$fields                 	= array();
		$fields['degree_name']  	= "Education Degree Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_education_degree_input ($params);
	}

		private function _validate_education_degree_input($params) 
	{
		try {
			
			$validation ['degree_name'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Education Degree Name',
					'max_len' 			=> 100
			);
			$validation ['active_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Active Flag',
					'max_len' 			=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_educational_level($params)
	{	
		$fields                  		= array();
		$fields['educ_level_name']  	= "Educational Level Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_educational_level_input ($params);
	}

	private function _validate_educational_level_input($params) 
	{
		try {
			
			$validation ['educ_level_name'] = array (
					'data_type' 			=> 'string',
					'name' 					=> 'Educational Level Name',
					'max_len' 				=> 100
			);
			$validation ['active_flag'] 	= array (
					'data_type'				=> 'string',
					'name' 					=> 'Active Flag',
					'max_len' 				=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_eligibility($params)
	{	
		$fields                  			= array();
		$fields['eligibility_type_code']  	= "CS Eligibility Code";
		$fields['eligibility_type_name']  	= "CS Eligibility Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_eligibility_input ($params);
	}

	private function _validate_eligibility_input($params) 
	{
		try {
			$validation ['eligibility_type_code'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'CS Eligibility Code',
					'max_len' 						=> 50 
			);
			$validation ['eligibility_type_name'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'CS Eligibility Name',
					'max_len' 						=> 50 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_employment_status($params)
	{
		$fields                  			= array();
		$fields['employment_status_name']  	= "Employment Status Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_employement_status_input ($params);
	}

	private function _validate_employement_status_input($params) 
	{
		try {
			
			$validation ['employment_status_name'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employment Status Name',
					'max_len' 						=> 50 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_branch($params)
	{
		$fields                 = array();
		$fields['branch_code']  = "Branch Code";
		$fields['branch_name']  = "Branch Name";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_branch_input ($params);
	}

	private function _validate_branch_input($params) 
	{
		try {
			$validation ['branch_code'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Branch Code',
					'max_len' 			=> 100
			);			
			$validation ['branch_name'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Branch Name',
					'max_len' 			=> 100
			);
			$validation ['active_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Active Flag',
					'max_len' 			=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_separation_mode($params)
	{
		$fields                 			= array();
		$fields['separation_mode_name']  	= "Mode of Separation Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_separation_mode_input ($params);
	}

	private function _validate_separation_mode_input($params) 
	{
		try {
			
			$validation ['separation_mode_name'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Mode of Separation Name',
					'max_len' 						=> 45 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_personnel_movement($params)
	{
		$fields                 			= array();
		$fields['personnel_movement_name']  = "Personnel Movement Name";
		$fields['replacement_reason']  		= "Replacement Reason";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_personnel_movement_input ($params);
	}

	private function _validate_personnel_movement_input($params) 
	{
		try {
			
			$validation ['personnel_movement_name'] = array (
					'data_type' 					=> 'string',
					'name' 							=> 'Personnel Movement Name',
					'max_len' 						=> 100 
			);
			$validation ['needs_appointment'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Appointment',
					'max_len' 						=> 1
			);
			$validation ['needs_office_order'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Office Order',
					'max_len' 						=> 1
			);
			$validation ['replacement_reason'] 		= array (
					'data_type'						=> 'string',
					'name' 							=> 'Replacement Reason',
					'max_len' 						=> 255 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_plantilla($params)
	{
		$fields                 			= array();
		$fields['plantilla_code']  			= "Item number";
		$fields['office']  					= "Office";
		$fields['position_id']  			= "Position";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_plantilla_input ($params);
	}

	private function _validate_plantilla_input($params) 
	{
		try 
		{
			$validation ['plantilla_code'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Item Number',
					'max_len' 					=> 50 
			);
			$validation ['parent_plantilla_id'] = array (
					'data_type' 				=> 'digit',
					'name' 						=> 'Parent Plantilla',
					'max_len' 					=> 11
			);
			$validation ['office'] 				= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Office',
					'max_len' 					=> 11 
			);
			$validation ['position_id'] 		= array (
					'data_type'					=> 'string',
					'name' 						=> 'Position',
					'max_len' 					=> 11 
			);
			$validation ['active_flag']			= array (
					'data_type' 				=> 'string',
					'name'      				=> 'Active Flag',
					'max_len'  					=> 1 
			);
			$validation ['rata']			= array (
				'data_type' 				=> 'string',
				'name'      				=> 'Rata',
				'max_len'  					=> 1 
		);

			return $this->validate_inputs($params, $validation );
		
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_position($params)
	{
		//SPECIFY HERE INPUTS FROM USER
		$fields                   	= array();
		$fields['position_name']  	= "Position Name";
		$fields['position_level']	= "Level";
		$fields['position_class']	= "Class";
		$fields['salary_grade']  	= "Salary Grade";
		$fields['salary_step']   	= "Salary Step";

		$this->check_required_fields($params, $fields);
			
		return $this->_validate_position_input($params);
	}

	private function _validate_position_input($params) 
	{
		try {
			$validation ['position_name'] 	= array (
				'data_type' 				=> 'string',
				'name'     					=> 'Position Name',
				'max_len'   				=> 100
			);
			$validation ['active_flag']		= array (
				'data_type' 				=> 'string',
				'name'      				=> 'Active Flag',
				'max_len'  					=> 1 
			);
			$validation ['position_level'] 	= array (
				'data_type' 				=> 'string',
				'name'     					=> 'Level',
				'max_len'   				=> 100
			);
			$validation ['position_class'] 	= array (
				'data_type' 				=> 'string',
				'name'      				=> 'Class',
				'max_len'   				=> 100
			);
			$validation ['salary_grade'] 	= array (
				'data_type' 				=> 'digit',
				'name'      				=> 'Salary Grade',
				'max_len'   				=> 2 
			);
			$validation ['salary_step'] 	= array (
				'data_type'					=> 'string',
				'name'      				=> 'Salary Step',
				'max_len'   				=> 2	
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_salary_schedule($params)
	{
		//SPECIFY HERE INPUTS FROM USER
			$fields 							= array();
			$fields['effectivity_date']			= "Effectivity Date";
			$fields['budget_circular_number']	= "Budget Circular Number";
			$fields['budget_circular_date']		= "Budget Circular Date";
			$fields['executive_order_number']	= "Executive Order Number";
			$fields['execute_order_date']		= "Executive Order Date";
	
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_data_salary_schedule_input($params);
	}

	private function _validate_data_salary_schedule_input($params) 
	{
		try {
			
			$validation ['effectivity_date'] 		= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Effectivity Date',
			);
			$validation ['prev_date'] 				= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Previous Date',
			);
			$validation ['budget_circular_number'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Budget Circular Name',
					'max_len' 						=> 45 
			);
			$validation ['budget_circular_date'] 	= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Budget Circular Date',
			);
			$validation ['executive_order_number'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Executive Order Name',
					'max_len' 						=> 45 
			);
			$validation ['execute_order_date'] 		= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Executive Order Date',
			);
			$validation ['other_fund_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Fund Flag',
					'max_len' 						=> 1 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_school($params)
	{
		$fields                 = array();
		$fields['school_name']  = "School Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_school_input ($params);
	}

	private function _validate_school_input($params) 
	{
		try {
			
			$validation ['school_name'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'School Name',
					'max_len' 			=> 100
			);
			$validation ['active_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Active Flag',
					'max_len' 			=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	/*------------------------HUMAN RESOURCES VALIDATE DATA END ----------------------------*/


	/*----- START GET LIST HR -----*/
	public function get_compensation_type_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("A.*");
			$bColumns					= array("A.compensation_name", "A.compensation_code", "A.active_flag");
			$compensation 				= $this->cl->get_compensation_list($aColumns, $bColumns, $params);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(compensation_id)) AS count"), $this->cl->tbl_param_compensations, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($compensation),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_COMPENSATION_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_COMPENSATION_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_COMPENSATION_TYPE, ACTION_DELETE);

			$cnt = 0;
			foreach ($compensation as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "";

				$action 				= "<div class='table-actions'>";
				
				$compensation_id 		= $aRow["compensation_id"];
				$id 					= $this->hash ($compensation_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("compensation type", "'.$url_delete.'")';
			
				$row[]	= $aRow['compensation_name'];
				$row[]	= $aRow['compensation_code'];
				$row[] 	= ($aRow['active_flag'] == "Y") ? Y:N;

				if ($aRow['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_FIXED) 
				{ 
					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_compensation_type' onclick=\"modal_compensation_type_init('".$view_action."')\"></a>";
					if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_compensation_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_init('".$edit_action."')\"></a>";
					if($permission_delete)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				} 

				if ($aRow['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE) 
				{
					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_compensation_type' onclick=\"modal_compensation_type_init('".$view_action."')\"></a>";
					if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_compensation_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_init('".$edit_action."')\"></a>";
					if($permission_delete)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				} 

				if ($aRow['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_SYSTEM) 
				{
					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_compensation_type' onclick=\"modal_compensation_type_init('".$view_action."')\"></a>";
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

	public function get_deduction_type_list()
	{

		try
		{
			$params 				 	= get_params();
				
			$aColumns		 			= array("A.*", "B.multiplier_name", "C.frequency_name", "D.remittance_type_name");
			$bColumns		 			= array("A.deduction_code", "A.deduction_name", "A.active_flag");
			$deduction_types 			= $this->cl->get_deduction_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(deduction_id)) AS count"), $this->cl->tbl_param_deductions, NULL, false);
		
			$output 					= array(
				"sEcho"				 	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($deduction_types),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_DEDUCTION_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_DEDUCTION_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_DEDUCTION_TYPE, ACTION_DELETE);

			$cnt = 0;
			foreach ($deduction_types as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$deduction_id 			= $aRow["deduction_id"];
				$id 					= $this->hash ($deduction_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("deduction type", "'.$url_delete.'")';
				
				$row[] = $aRow['deduction_code'];
				$row[] = $aRow['deduction_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_deduction_type' onclick=\"modal_deduction_type_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_deduction_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_deduction_type_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_education_degree_list()
	{
		try
		{
			$params 					= get_params();
						
			$aColumns					= array("*");
			$bColumns					= array("degree_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_education_degrees;
			$where						= array();
			$degree 					= $this->cl->get_education_degree_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(degree_id)) AS count"), $this->cl->tbl_param_education_degrees, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($degree),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_EDUCATIONAL_DEGREE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_EDUCATIONAL_DEGREE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_EDUCATIONAL_DEGREE, ACTION_DELETE);

			$cnt = 0;
			foreach ($degree as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$degree_id 				= $aRow["degree_id"];
				$id 					= $this->hash ($degree_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("education degree", "'.$url_delete.'")';
				
				$row[] = $aRow['degree_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_education_degree' onclick=\"modal_education_degree_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_education_degree' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_education_degree_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_educational_level_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array("educ_level_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_educational_levels;
			$where						= array();
			$educational_level			= $this->cl->get_educational_level_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(educ_level_id)) AS count"), $this->cl->tbl_param_educational_levels, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($educational_level),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_EDUCATIONAL_LEVEL, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_EDUCATIONAL_LEVEL, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_EDUCATIONAL_LEVEL, ACTION_DELETE);

			$cnt = 0;
			foreach ($educational_level as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$educ_level_id 			= $aRow["educ_level_id"];
				$id 					= $this->hash ($educ_level_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("educational level", "'.$url_delete.'")';
				
				$row[] = $aRow['educ_level_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_educational_level' onclick=\"modal_educational_level_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_educational_level' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_educational_level_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_eligibility_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("eligibility_type_code", "eligibility_type_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_eligibility_types;
			$where						= array();
			$eligibility 				= $this->cl->get_eligibility_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(eligibility_type_id)) AS count"), $this->cl->tbl_param_eligibility_types, NULL, false);
			
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($eligibility),
				"iTotalDisplayRecords"	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_ELIGIBILITY, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_ELIGIBILITY, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_ELIGIBILITY, ACTION_DELETE);

			$cnt = 0;
			foreach ($eligibility as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "";

				$action 				= "<div class='table-actions'>";
			
				$eligibility_id 		= $aRow["eligibility_type_id"];
				$id 					= $this->hash ($eligibility_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("eligibility", "'.$url_delete.'")';
	
				$row[] = $aRow['eligibility_type_code'];
				$row[] = $aRow['eligibility_type_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_eligibility_type' onclick=\"modal_eligibility_type_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_eligibility_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_eligibility_type_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_employment_status_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("employment_status_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_employment_status;
			$where						= array();
			$employment_status			= $this->cl->get_employment_status_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(employment_status_id)) AS count"), $this->cl->tbl_param_employment_status, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($employment_status),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_EMPLOYMENT_STATUS, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_EMPLOYMENT_STATUS, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_EMPLOYMENT_STATUS, ACTION_DELETE);

			$cnt = 0;
			foreach ($employment_status as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["employment_status_id"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("employment status", "'.$url_delete.'")';

				$row[] = $aRow['employment_status_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_employment_status' onclick=\"modal_employment_status_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_employment_status' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employment_status_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_branch_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("branch_code", "branch_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_government_branches;
			$where						= array();
			$branch 					= $this->cl->get_branch_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(branch_id)) AS count"), $this->cl->tbl_param_government_branches, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($branch),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_GOVERNMENT_BRANCH, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_GOVERNMENT_BRANCH, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_GOVERNMENT_BRANCH, ACTION_DELETE);

			$cnt = 0;
			foreach ($branch as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["branch_id"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("branch", "'.$url_delete.'")';

				$row[] = $aRow['branch_code'];
				$row[] = $aRow['branch_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_branch' onclick=\"modal_branch_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_branch' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_branch_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_separation_mode_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("separation_mode_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_separation_modes;
			$where						= array();
			$separation_mode 			= $this->cl->get_separation_mode_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(separation_mode_id)) AS count"), $this->cl->tbl_param_separation_modes, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords"			=> count($separation_mode),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData"				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_MODE_SEPARATION, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_MODE_SEPARATION, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_MODE_SEPARATION, ACTION_DELETE);

			$cnt = 0;
			foreach ($separation_mode as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$separation_mode_id 	= $aRow["separation_mode_id"];
				$id 					= $this->hash ($separation_mode_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("mode of separation", "'.$url_delete.'")';
				
				$row[] = $aRow['separation_mode_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_separation_mode' onclick=\"modal_separation_mode_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_separation_mode' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_separation_mode_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_personnel_movement_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array("personnel_movement_name", "needs_appointment", "needs_office_order", "active_flag");
			$table 	  					= $this->cl->tbl_param_personnel_movements;
			$where						= array();
			$personnel_movement 		= $this->cl->get_personnel_movement_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(personnel_movement_id)) AS count"), $this->cl->tbl_param_personnel_movements, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($personnel_movement),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_DELETE);

			$cnt = 0;
			foreach ($personnel_movement as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$personnel_movement_id 	= $aRow["personnel_movement_id"];
				$id 					= $this->hash ($personnel_movement_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("personnel movement", "'.$url_delete.'")';
				
				$row[] = $aRow['personnel_movement_name'];
				$row[] = ($aRow['needs_appointment'] == "Y") ? "Yes":"No";
				$row[] = ($aRow['needs_office_order'] == "Y") ? "Yes":"No";
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_personnel_movement' onclick=\"modal_personnel_movement_init('".$view_action."')\"></a>";
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_personnel_movement' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_personnel_movement_init('".$edit_action."')\"></a>";
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_plantilla_list()
	{
		try
		{
			
			$params         			= get_params();
			
			$aColumns     				= array("A.plantilla_id", "A.plantilla_code", "A.parent_plantilla_id", "B.position_name", "B.position_name AS plantilla_parent_name", "D.name", "A.active_flag");
			$bColumns      				= array('A.plantilla_code', 'B.position_name', 'D.name', 'A.active_flag');
			$table 	  					= $this->cl->tbl_param_plantilla_items;
			$where						= array();
			$plantilla 					= $this->cl->get_plantilla_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(plantilla_id)) AS count"), $this->cl->tbl_param_plantilla_items, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($plantilla),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_PLANTILLA, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_PLANTILLA, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_PLANTILLA, ACTION_DELETE);

			$cnt = 0;
			foreach ($plantilla as $aRow):
				$cnt++;
				$row     				= array();
				$action   				= "";
				
				$action   				= "<div class='table-actions'>";
				
				$plantilla_id     		= $aRow["plantilla_id"];
				$id                		= $this->hash($plantilla_id);
				$salt              		= gen_salt();
				$token_view         	= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit         	= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete      		= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action        	= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action        	= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete         	= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action      	= 'content_delete("plantilla", "'.$url_delete.'")';
				
				$row[]    		= $aRow['plantilla_code'];
				$row[]          = $aRow['position_name']; 
				$row[]          = $aRow['name']; 
				$row[]			= ($aRow['active_flag'] == "Y") ? "Active":"Inactive";
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_plantilla' onclick=\"modal_plantilla_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_plantilla' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_plantilla_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_position_list()
	{

		try
		{
			$params   					= get_params();
			$date 	  					= date('Y-m-d');

			$aColumns 					= array("A.*", "B.position_level_name", "C.position_class_level_name");
			$bColumns 					= array("A.position_name", "B.position_level_name", "C.position_class_level_name", "A.active_flag");
			$table 	 					= $this->cl->tbl_param_positions;
			$where						= array();
			/*$tables   				= "param_positions A left join param_salary_schedule B ON (A.salary_grade = B.salary_grade AND A.salary_step = B.salary_step)";
			$where   					= "Where B.effectivity_date = (select MAX(B.effectivity_date) AS date from param_salary_schedule B where B.effectivity_date <= '$date')";*/
			$position 					= $this->cl->get_position_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(position_id)) AS count"), $this->cl->tbl_param_positions, NULL, false);
		
			$output 					= array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => count($position),
				"iTotalDisplayRecords" => $iTotal["count"],
				"aaData"               => array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_POSITION, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_POSITION, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_POSITION, ACTION_DELETE);

			$cnt = 0;
			foreach ($position as $aRow):
				$cnt++;
				$row           			= array();

				$action        			= "<div class='table-actions'>";
				
				$position_id   			= $aRow["position_id"];
				$id            			= $this->hash ($position_id);
				$salt          			= gen_salt();
				$token_view    			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit    			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete  			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action   			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action   			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete    			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action 			= 'content_delete("position", "'.$url_delete.'")';
				
				/*$annual_salary = $aRow['amount'] * 12;*/
				$row[] = $aRow['position_name'];
				$row[] = $aRow['position_level_name'];
				$row[] = $aRow['position_class_level_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_position' onclick=\"modal_position_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_position' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_position_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_salary_schedule_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("effectivity_date", "active_flag");
			$table 	  					= $this->cl->tbl_param_salary_schedule;
			$where						= array();
			$salary_schedule			= $this->cl->get_salary_grade_steps_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effectivity_date)) AS count"), $this->cl->tbl_param_salary_schedule, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($salary_schedule),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_SALARY_SCHEDULE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_SALARY_SCHEDULE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_SALARY_SCHEDULE, ACTION_DELETE);

			$cnt = 0;
			foreach ($salary_schedule as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "<div class='table-actions'>";
			
				$effectivity_date		= $aRow["effectivity_date"];
				$id 					= $this->hash ($effectivity_date);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("salary schedule", "'.$url_delete.'")';
				
				$row[] = '<center>' . format_date($aRow['effectivity_date']) . '</center>';
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_salary_schedule' onclick=\"modal_salary_schedule_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_salary_schedule' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_salary_schedule_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function get_school_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array("school_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_schools;
			$where						= array();
			$school 					= $this->cl->get_school_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(school_id)) AS count"), $this->cl->tbl_param_schools, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($school),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_SCHOOL, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_SCHOOL, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_SCHOOL, ACTION_DELETE);

			$cnt = 0;
			foreach ($school as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$school_id 				= $aRow["school_id"];
				$id 					= $this->hash ($school_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("school", "'.$url_delete.'")';
				
				$row[] = $aRow['school_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_school' onclick=\"modal_school_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_school' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_school_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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
	/*----- END GET LIST HR -----*/

	
	/*----- START DELETE HR -----*/
	public function delete_compensation_type()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_compensations;
			$where				= array();
			$key 				= $this->get_hash_key('compensation_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['compensation_name']);
	
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
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'compensation_type_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_compensation_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_deduction_type()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_deductions;
			$where				= array();
			$key 				= $this->get_hash_key('deduction_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['deduction_name']);
	
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
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'deduction_type_table',
			"path"				=> PROJECT_MAIN . '/code_library/get_deduction_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_education_degree()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_education_degrees;
			$where				= array();
			$key 				= $this->get_hash_key('degree_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['degree_name']);
	
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
	
		$info 					= array(
			"flag"           	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'education_degree_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/get_education_degree_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_educational_level()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_educational_levels;
			$where				= array();
			$key 				= $this->get_hash_key('educ_level_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['degree_name']);
	
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
	
		$info 					= array(
			"flag"            	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'educational_level_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/get_educational_level_list/',
			"advanced_filter" 	=> true

		);
	
		echo json_encode($info);
	}

	public function delete_eligibility()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_eligibility_types;
			$where				= array();
			$key 				= $this->get_hash_key('eligibility_type_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['eligibility_type_name']);
	
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
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'eligibility_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_eligibility_list/',
			"advanced_filter"	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_employment_status()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_employment_status;
			$where				= array();
			$key 				= $this->get_hash_key('employment_status_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['employment_status_name']);
	
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
			$flag = 1;
				
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$msg = $this->lang->line('parent_delete_error');
			$this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'employment_status_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_employment_status_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_branch()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_government_branches;
			$where				= array();
			$key 				= $this->get_hash_key('branch_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['branch_name']);
	
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
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'branch_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_branch_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_separation_mode()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_separation_modes;
			$where				= array();
			$key 				= $this->get_hash_key('separation_mode_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['separation_mode_name']);
	
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
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'separation_mode_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_separation_mode_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_personnel_movement()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_personnel_movements;
			$where				= array();
			$key 				= $this->get_hash_key('personnel_movement_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['personnel_movement_id']);
	
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
	
		$info 					= array(
			"flag"           	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'personnel_movement_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/get_personnel_movement_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_plantilla()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_plantilla_items;
			$where				= array();
			$key 				= $this->get_hash_key('plantilla_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['plantilla_name']);
	
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
			$flag = 1;
				
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
		
			$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'table_plantilla',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_plantilla_list/',
			"advanced_filter" 	=> true
		);

		echo json_encode($info);
	}

	public function delete_position()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_positions;
			$where				= array();
			$key 				= $this->get_hash_key('position_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['position_name']);
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
				$prev_detai=l, 
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
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'position_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/get_position_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_salary_schedule()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_salary_schedule;
			$where				= array();
			$key 				= $this->get_hash_key('effectivity_date');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['effectivity_date']);
	
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
	
		$info 					= array(
			"flag"            	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'salary_schedule_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/get_salary_schedule_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_school()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_schools;
			$where				= array();
			$key 				= $this->get_hash_key('school_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['school_name']);
	
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
	
		$info 					= array(
			"flag"            	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'school_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/get_school_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}
	/*----- END DELETE HR -----*/
	

	public function modal($modal = NULL, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			
			$data['action_id'] 		  = $action_id;
			switch ($modal) 
			{
				case 'compensation':
					$data['nav_page'] = CODE_LIBRARY_COMPENSATION;
				break;
				case 'deduction_type':
					$data['nav_page'] = CODE_LIBRARY_DEDUCTION_TYPE;
				break;
				case 'education_degree':
					$data['nav_page'] = CODE_LIBRARY_EDUCATION_DEGREE;
				break;
				case 'educational_level':
					$data['nav_page'] = CODE_LIBRARY_EDUCATIONAL_LEVEL;
				break;
				case 'eligibility':
					$data['nav_page'] = CODE_LIBRARY_ELIGIBILITY_TITLE;
				break;
				case 'employment_status':
					$data['nav_page'] = CODE_LIBRARY_EMPLOYMENT_STATUS;
				break;
				case 'branch':
					$data['nav_page'] = CODE_LIBRARY_BRANCH;
				break;
				case 'separation_mode':
					$data['nav_page'] = CODE_LIBRARY_SEPARATION_MODE;
				break;
				case 'personnel_movement':
					$data['nav_page'] = CODE_LIBRARY_PERSONNEL_MOVEMENT;
				break;
				case 'plantilla':
					$data['nav_page'] = CODE_LIBRARY_PLANTILLA;
				break;
				case 'position':
					$data['nav_page'] = CODE_LIBRARY_POSITION;
				break;
				case 'salary_schedule':
					$data['nav_page'] = CODE_LIBRARY_SALARY_SCHEDULE;
				break;
				case 'school':
					$data['nav_page'] = CODE_LIBRARY_SCHOOL;
				break;
				
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
		
		$this->load->view('code_library/modals/modal_code_library', $data);
		$this->load_resources->get_resource($resources);
	}

	/*----- START MODAL HR -----*/
	public function modal_compensation_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE, 'jquery.number.min');

			//GET LIST FOR DROPDOWN
			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_multipliers;
			$where							= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['multiplier_name'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_frequencies;
			$where							= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['frequency_name'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_compensations;
			$where							= array();
			if($action != ACTION_VIEW) {
				$where['employee_flag']		= YES;
			}
			$data['parent_compensation'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);



			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 			= $action;
			$data ['nav_page']				= CODE_LIBRARY_COMPENSATION;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_compensations;
				$where						= array();
				$key 						= $this->get_hash_key('compensation_id');
				$where[$key]				= $id;
				$compensation_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				$data ['compensation_info']	= $compensation_info;

				$resources['single'] 		= array(
					'multiplier' 			=> $data['compensation_info']['multiplier_id'],
					'frequency' 			=> $data['compensation_info']['frequency_id'],
					'parent_compensation' 	=> $data['compensation_info']['parent_compensation_id']
				);
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
		
		$this->load->view('code_library/modals/modal_compensation_type', $data);
		$this->load_resources->get_resource($resources);

	}

	public function modal_deduction_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE, 'jquery.number.min');

			//DROPDOWN
			$field                       	= array("*") ;
			$table                       	= $this->cl->tbl_param_multipliers;
			$where                       	= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['multiplier_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field                      	= array("*") ;
			$table                      	= $this->cl->tbl_param_frequencies;
			$where                      	= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['frequency_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field                       	= array("*") ;
			$table                       	= $this->cl->tbl_param_remittance_types;
			$where                       	= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['remittance_type_name'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 			= $action;
			$data ['nav_page']				= CODE_LIBRARY_DEDUCTION_TYPE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_deductions;
				$where						= array();
				$key 						= $this->get_hash_key('deduction_id');
				$where[$key]				= $id;
				$deduction_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				$data ['deduction_info']	= $deduction_info;

				$resources['single']		= array(
					'multiplier'    		=> $data['deduction_info']['multiplier_id'],
					'frequency' 			=> $data['deduction_info']['frequency_id'],
					'remittance_type' 		=> $data['deduction_info']['remittance_type_id']
				);

				//EDIT
				$table 								= $this->cl->tbl_param_other_deduction_details;
				$where								= array();
				$key 								= $this->get_hash_key('deduction_id');
				$where[$key]						= $id;
				$data ['other_deduction_details'] 	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
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
		
		$this->load->view('code_library/modals/modal_deduction_type', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_education_degree($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 					= array();
			$data['action_id'] 			= $action;
			$data['nav_page']			= CODE_LIBRARY_EDUCATION_DEGREE;
			$data ['action'] 			= $action;
			$data ['salt'] 				= $salt;
			$data ['token'] 			= $token;
			$data ['id'] 				= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              	= $this->cl->tbl_param_education_degrees;
				$where              	= array();
				$key                	= $this->get_hash_key('degree_id');
				$where[$key]        	= $id;
				$degree_info    		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['degree_info'] 	= $degree_info;
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
		
		$this->load->view('code_library/modals/modal_education_degree', $data);
	}

	public function modal_educational_level($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 							= array();
			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_EDUCATIONAL_LEVEL;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              			= $this->cl->tbl_param_educational_levels;
				$where              			= array();
				$key                			= $this->get_hash_key('educ_level_id');
				$where[$key]        			= $id;
				$educational_level_info 		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['educational_level_info'] = $educational_level_info;
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
		
		$this->load->view('code_library/modals/modal_educational_level', $data);
	}

	public function modal_eligibility_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 						= array();
			$data ['action_id'] 			= $action;
			$data ['nav_page']				= CODE_LIBRARY_ELIGIBILITY_TITLE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_eligibility_types;
				$where						= array();
				$key 						= $this->get_hash_key('eligibility_type_id');
				$where[$key]				= $id;
				$eligibility_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['eligibility_info']	= $eligibility_info;
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
		
		$this->load->view('code_library/modals/modal_eligibility_type', $data);
	}

	public function modal_employment_status($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 							= array();
			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_EMPLOYMENT_STATUS;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			if(!EMPTY($id))
			{
				//EDIT
				$table 				    		= $this->cl->tbl_param_employment_status;
				$where				    		= array();
				$key 				    		= $this->get_hash_key('employment_status_id');
				$where[$key]		    		= $id;
				$employment_status_info			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['employment_status_info']	= $employment_status_info;
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
		
		$this->load->view('code_library/modals/modal_employment_status', $data);
	}

	public function modal_branch($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 					= array();
			$data['action_id'] 			= $action;
			$data['nav_page']			= CODE_LIBRARY_BRANCH;
			$data ['action'] 			= $action;
			$data ['salt'] 				= $salt;
			$data ['token'] 			= $token;
			$data ['id'] 				= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 					= $this->cl->tbl_param_government_branches;
				$where					= array();
				$key 					= $this->get_hash_key('branch_id');
				$where[$key]			= $id;
				$branch_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['branch_info']	= $branch_info;
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
		
		$this->load->view('code_library/modals/modal_branch', $data);
	}

	public function modal_separation_mode($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 							= array();
			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_SEPARATION_MODE;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 							= $this->cl->tbl_param_separation_modes;
				$where							= array();
				$key 							= $this->get_hash_key('separation_mode_id');
				$where[$key]					= $id;
				$separation_mode_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['separation_mode_info']	= $separation_mode_info;
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
		
		$this->load->view('code_library/modals/modal_separation_mode', $data);
	}

	public function modal_personnel_movement($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 							= array();
			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_PERSONNEL_MOVEMENT;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table                           = $this->cl->tbl_param_personnel_movements;
				$where                           = array();
				$key                             = $this->get_hash_key('personnel_movement_id');
				$where[$key]                     = $id;
				$personnel_movement_info         = $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['personnel_movement_info'] = $personnel_movement_info;
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
		
		$this->load->view('code_library/modals/modal_personnel_movement', $data);
	}

	public function modal_plantilla($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$date				   			= date('Y-m-d');
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_SELECTIZE, 'jquery.number.min');

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			//GET PLANTILLA 
			$field                      	= array("*") ;
			$table                     		= $this->cl->tbl_param_positions;
			$where                      	= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['positions']          	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field                      	= array("*") ;
			$table                     		= $this->cl->db_core.'.'.$this->cl->tbl_organizations;
			$where                      	= array();
			$data['offices']          		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$data['parents']      			= $this->cl->get_parent_plantilla_name();

			$data['offices']      			= $this->cl->get_offices();
			$data['divisions']      		= $this->cl->get_divisions();
			
			$data ['action']   				= $action;
			$data ['salt']     				= $salt;
			$data ['token']    				= $token;
			$data ['id']      				= $id;

			if($action != ACTION_ADD)
			{
				$data['parents']      			= $this->cl->get_parent_plantilla_name($id);

				$field                  	= array("*") ;
				$table                  	= $this->cl->tbl_param_plantilla_items;
				$key                    	= $this->get_hash_key('plantilla_id');
				$where                 		= array();
				$where[$key]           	 	= $id;
				$plantilla_info         	= $this->cl->get_code_library_data($field, $table, $where, FALSE);
				$data['plantilla_info'] 	= $plantilla_info;
				
				$resources['single']   		= array(
					'office'          		=> $plantilla_info['office_id'],
					'parent_plantilla_id'  	=> $plantilla_info['parent_plantilla_id'],
					'position_id'           => $plantilla_info['position_id']
				);            
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
		
		$this->load->view('code_library/modals/modal_plantilla', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_position($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 							= array();
			$resources['load_css']				= array(CSS_SELECTIZE);
			$resources['load_js']  				= array(JS_SELECTIZE);

			$field 								= array("*") ;
			$table								= $this->cl->tbl_param_position_levels;
			$where								= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']			= YES;
			}
			$data['position_level_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field 								= array("*") ;
			$table								= $this->cl->tbl_param_position_class_levels;
			$where								= array();
			if($action != ACTION_VIEW) 
			{
				$where['active_flag']			= YES;
			}
			$data['position_class_level_name'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$data['salary_grade']      			= $this->cl->get_salary_grade();

			$data['salary_step']      			= $this->cl->get_salary_step();

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 				= $action;
			$data ['nav_page']					= CODE_LIBRARY_POSITION;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table         					= $this->cl->tbl_param_positions;
				$where         					= array();
				$key           					= $this->get_hash_key('position_id');
				$where[$key]   					= $id;
				$position_info 					= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['position_info'] 			= $position_info;

				$resources['single']			= array(
					'position_level' 			=> $data['position_info']['position_level_id'],
					'position_class' 			=> $data['position_info']['position_class_id'],
					'salary_grade' 				=> $data['position_info']['salary_grade'],
					'salary_step' 				=> $data['position_info']['salary_step']
				);
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
		
		$this->load->view('code_library/modals/modal_position', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_salary_schedule($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 					= $action;
			$data ['action'] 						= $action;
			$data ['salt'] 							= $salt;
			$data ['token'] 						= $token;
			$data ['id'] 							= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table               				= $this->cl->tbl_param_salary_schedule;
				$where              				= array();
				$key                 				= $this->get_hash_key('effectivity_date');
				$where[$key]         				= $id;
				$data['salary']         			= $this->cl->get_code_library_data(array("max(salary_grade) AS grade", "max(salary_step) AS step", "effectivity_date", "other_fund_flag", "active_flag", "budget_circular_number", "budget_circular_date", "executive_order_number", "execute_order_date"), $table, $where, FALSE);		
				
				//EDIT
				$table               				= $this->cl->tbl_param_salary_schedule;
				$where              				= array();
				$key                 				= $this->get_hash_key('effectivity_date');
				$where[$key]         				= $id;
				$amount 				      	  	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				$data['amount']						= array();

				foreach ($amount as $value) {
					$grade 							= $value['salary_grade'];
					$step 							= $value['salary_step'];
					$data['amount'][$grade][$step] 	= $value['amount'];
				}	
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
		
		$this->load->view('code_library/modals/modal_salary_schedule', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_school($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 				= array();
			$data['action_id'] 		= $action;
			$data['nav_page']		= CODE_LIBRARY_SCHOOL;
			$data ['action'] 		= $action;
			$data ['salt'] 			= $salt;
			$data ['token'] 		= $token;
			$data ['id'] 			= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table               = $this->cl->tbl_param_schools;
				$where               = array();
				$key                 = $this->get_hash_key('school_id');
				$where[$key]         = $id;
				$school_info         = $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['school_info'] = $school_info;
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
		
		$this->load->view('code_library/modals/modal_school', $data);
	}
	/*----- END MODAL HR -----*/

	
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */