<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//RLog::info('LINE 2209 =>'.json_encode($fields));
class Code_library_payroll extends Main_Controller {
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
			$resources['load_css'] 	= array(CSS_CALENDAR);
			$resources['load_js'] 	= array(JS_CALENDAR, JS_CALENDAR_MOMENT);
			$data['action_id']		= $action_id;
			switch ($form) 
			{
				/*----- START GET TAB PAYROLL -----*/
				case 'bank':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'bank_table', 'path' => 'main/code_library_payroll/get_bank_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_bank' 			=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_bank',
							'multiple'			=> true,
							'height'			=> '300px',
							'size'				=> 'sm',
							'title'				=> 'Bank'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_bank',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'bir':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'bir_table_dt', 'path' => 'main/code_library_payroll/get_bir_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_bir'				=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_bir',
							'multiple'			=> true,
							'height'			=> '300px',
							'size'				=> 'md',
							'title'				=> 'BIR'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_bir',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				
				case 'pagibig':
					$resources['load_css'][]	= CSS_DATATABLE;
					$resources['load_js'][]		= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'pagibig_table', 'path' => 'main/code_library_payroll/get_pagibig_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_pagibig' 		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_pagibig',
							'multiple'			=> true,
							'height'			=> '350px',
							'size'				=> 'md',
							'title'				=> 'Pagibig'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_pagibig',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				
				case 'philhealth':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'philhealth_table', 'path' => 'main/code_library_payroll/get_philhealth_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_philhealth'		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_philhealth',
							'multiple'			=> true,
							'height'			=> '400px',
							'size'				=> 'lg',
							'title'				=> 'Philhealth'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_philhealth',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				
				case 'gsis':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'gsis_table', 'path' => 'main/code_library_payroll/get_gsis_list', 'advanced_filter' => TRUE);
					$resources['load_modal']	= array(
						'modal_gsis' 			=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_gsis',
							'multiple'			=> true,
							'height'			=> '350px',
							'size'				=> 'md',
							'title'				=> 'GSIS'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_gsis',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'voucher':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'voucher_table', 'path' => 'main/code_library_payroll/get_voucher_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_voucher' 		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_voucher',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'Voucher'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_voucher',
						PROJECT_MAIN
					);
					$view_form = $form;	
				break;

				case 'fund_source':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'fund_source_table', 'path' => 'main/code_library_payroll/get_fund_source_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_fund_source' 	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_fund_source',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'Fund Source'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_fund_source',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;	

				case 'remittance_type':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'remittance_type_table', 'path' => 'main/code_library_payroll/get_remittance_type_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_remittance_type' => array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_remittance_type',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'Remittance Type'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_remittance_type',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				/*----- END GET TAB PAYROLL -----*/
				
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

	/*----- START PROCESS PAYROLL -----*/
	public function process_bank()
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
			// print_r($params);
			// die();
			// SERVER VALIDATION
			$valid_data 				= $this->_validate_data_bank($params);
			
			//SET FIELDS VALUE
			$fields['bank_name']  		= $valid_data['bank_name'];
			$fields['account_no']  		= $valid_data['account_no'];
			$fields['fund_source_id']  	= $valid_data['fund_source'];
			$fields['active_flag']  	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_banks;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]		= AUDIT_INSERT;
				
				$prev_detail[]		= array();

				//INSERT DATA
				$bank_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 			= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 			= array();
				$where['bank_id']	= $bank_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 			= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('bank_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['bank_name']);
	
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

	/*----- START PROCESS PAYROLL -----*/
	public function process_bir()
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
			$valid_data 				= $this->_validate_data_bir($params);
			
			//SET FIELDS VALUE
			$fields['effective_date']  	= $valid_data['effective_date'];

			//$fields['account_number']  	= $valid_data['account_number'];
			$fields['active_flag']  	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_bir;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
			
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]		= AUDIT_INSERT;
				
				$prev_detail[]		= array();

				//INSERT DATA
				$bir_id 			= $this->cl->insert_code_library($table, $fields, TRUE);
				$valid_data_details = array();
				foreach ($params['min_amount'] as $key => $value) 
				{	
					$bir_details               = array();
					$bir_details['min_amount'] = $params['min_amount'][$key];
					$bir_details['max_amount'] = $params['max_amount'][$key];
					$bir_details['tax_amount'] = $params['tax_amount'][$key];
					$bir_details['tax_rate']   = $params['tax_rate'][$key];

					if($params['min_amount'][$key] > $params['max_amount'][$key])
					{
						throw new Exception('Maximum must be greater than the minimum amount. [ROW - ' . $key+1 . ']');
					}

					$valid_data_details[$key]  = $this->_validate_data_bir_details($bir_details);
					$valid_data_details[$key]['bir_id'] = $bir_id;
				}
				$this->cl->insert_code_library($this->cl->tbl_param_bir_details, $valid_data_details);
				//MESSAGE ALERT
				$message 			= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 			= array();
				$where['bir_id']	= $bir_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 			= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('bir_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);
				$this->cl->delete_code_library($this->cl->tbl_param_bir_details, $where);
				$valid_data_details = array();
				foreach ($params['min_amount'] as $key => $value) 
				{	
					$bir_details               = array();
					$bir_details['min_amount'] = $params['min_amount'][$key];
					$bir_details['max_amount'] = $params['max_amount'][$key];
					$bir_details['tax_amount'] = $params['tax_amount'][$key];
					$bir_details['tax_rate']   = $params['tax_rate'][$key];

					$valid_data_details[$key]  = $this->_validate_data_bir_details($bir_details);
					$valid_data_details[$key]['bir_id'] = $prev_detail[0][0]['bir_id'];
				}
				$this->cl->insert_code_library($this->cl->tbl_param_bir_details, $valid_data_details);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, 'BIR Table');
	
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


	public function process_voucher()
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
			$valid_data 				= $this->_validate_data_voucher($params);

			//SET FIELDS VALUE
			$fields['voucher_name'] 	= $valid_data['voucher_name'];
			$fields['active_flag']		= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_voucher;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]			= AUDIT_INSERT;
				
				$prev_detail[]			= array();

				//INSERT DATA
				$voucher_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 				= array();
				$where['voucher_id']	= $voucher_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('voucher_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['voucher_name']);
	
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

	public function process_philhealth()
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
			$valid_data 						= $this->_validate_data_philhealth($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_philhealth;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]			= AUDIT_INSERT;
				
				$prev_detail[]			= array();

				//INSERT DATA
				for ($x = 0; $x<count($valid_data['salary_bracket']); $x++ ) {

					$fields 							= array();
					$fields['effectivity_date'] 		= $valid_data['effectivity_date'];
					$fields['salary_bracket'] 			= $valid_data['salary_bracket'][$x];
					$fields['salary_range_from'] 		= $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] 			= $valid_data['salary_range_to'][$x];
					$fields['employee_share'] 			= $valid_data['employee_share'][$x];
					$fields['employer_share'] 			= $valid_data['employer_share'][$x];
					$fields['salary_base'] 				= $valid_data['salary_base'][$x];
					$fields['total_monthly_premium'] 	= $valid_data['total_monthly_premium'][$x];
					$fields['active_flag']				= isset($valid_data['active_flag']) ? "Y" : "N";

					if($params['salary_range_from'][$x] > $params['salary_range_to'][$x])
					{
						$x++; //Used for row count
						throw new Exception('Salary Range To must be greater than the Salary Range From. [ROW - ' . $x . ']');
					}

					$this->cl->insert_code_library($this->cl->tbl_param_philhealth, $fields);
				}

				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//UPDATE 
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//WHERE 
				$where						= array();
				$key 						= $this->get_hash_key('effectivity_date');
				$where[$key]				= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_philhealth, $where);

				//EDIT DATA 
				for ($x = 0; $x<count($valid_data['salary_bracket']); $x++ ) {

					$fields 							= array();
					$fields['effectivity_date'] 		= $valid_data['effectivity_date'];
					$fields['salary_bracket'] 			= $valid_data['salary_bracket'][$x];
					$fields['salary_range_from'] 		= $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] 			= $valid_data['salary_range_to'][$x];
					$fields['employee_share'] 			= $valid_data['employee_share'][$x];
					$fields['employer_share'] 			= $valid_data['employer_share'][$x];
					$fields['salary_base'] 				= $valid_data['salary_base'][$x];
					$fields['total_monthly_premium'] 	= $valid_data['total_monthly_premium'][$x];
					$fields['active_flag']				= isset($valid_data['active_flag']) ? "Y" : "N";

					if($params['salary_range_from'][$x] > $params['salary_range_to'][$x])
					{
						$x++; //Used for row count
						throw new Exception('Salary Range To must be greater than the Salary Range From. [ROW - ' . $x . ']');
					}

					$this->cl->insert_code_library($this->cl->tbl_param_philhealth, $fields);
				}
				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['philhealth_id']);
	
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

	public function process_pagibig()
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
			$valid_data 						= $this->_validate_data_pagibig($params);

			//SET FIELDS VALUE
			$fields['effectivity_date'] 		= $valid_data['effectivity_date'];
			$fields['salary_range_from'] 		= $valid_data['salary_range_from'];
			$fields['salary_range_to'] 			= $valid_data['salary_range_to'];
			$fields['employee_rate'] 			= $valid_data['employee_rate'];
			$fields['employer_rate'] 			= $valid_data['employer_rate'];
			$fields['active_flag']				= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_pagibig;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]			= AUDIT_INSERT;
				
				$prev_detail[]			= array();

				//INSERT DATA
				$pagibig_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 				= array();
				$where['pagibig_id']	= $pagibig_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[]			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('pagibig_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['pagibig_id']);
	
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

	public function process_gsis()
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
			$valid_data 						= $this->_validate_data_gsis($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_gsis;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]	= AUDIT_INSERT;
				
				$prev_detail[]	= array();

				for ($x = 0; $x<count($valid_data['personal_share']); $x++ ) {

					$fields 							= array();
					$fields['personal_share']    		= $valid_data['personal_share'][$x];
					$fields['government_share']    		= $valid_data['government_share'][$x];
					$fields['max_government_share']    	= $valid_data['max_government_share'][$x];
					$fields['effective_date']    		= $valid_data['effective_date'];
					$fields['insurance_type_flag']    	= $valid_data['insurance_type_flag'][$x];
					$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_gsis, $fields);
				}
				
				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{
				//UPDATE 
				$audit_action[]	= AUDIT_UPDATE;

				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//EDIT
				$where						= array();
				$key 						= $this->get_hash_key('effective_date');
				$where[$key]				= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_gsis, $where);

				for ($x = 0; $x<count($valid_data['personal_share']); $x++ ) {

					$fields 							= array();
					$fields['personal_share']    		= $valid_data['personal_share'][$x];
					$fields['government_share']    		= $valid_data['government_share'][$x];
					$fields['max_government_share']    	= $valid_data['max_government_share'][$x];
					$fields['effective_date']    		= $valid_data['effective_date'];
					$fields['insurance_type_flag']    	= $valid_data['insurance_type_flag'][$x];
					$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_gsis, $fields);
				}

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['gsis_id']);
	
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

	public function process_remittance_type()
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
			$valid_data 					= $this->_validate_data_remittance_type($params);

			//SET FIELDS VALUE
			$fields['remittance_type_name'] = $valid_data['remittance_type_name'];
			$fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_remittance_types;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]					= AUDIT_INSERT;
				
				$prev_detail[]					= array();

				//INSERT DATA
				$remittance_type_id  			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 						= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 						= array();
				$where['remittance_type_id']	= $remittance_type_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 					= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('remittance_type_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['remittance_type_name']);
	
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

	public function process_fund_source()
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
			$valid_data 					= $this->_validate_data_fund_source($params);

			//SET FIELDS VALUE
			$fields['fund_source_name'] 	= $valid_data['fund_source_name'];
			$fields['active_flag'] 			= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_fund_sources;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]				= AUDIT_INSERT;
				
				$prev_detail[]				= array();

				//INSERT DATA
				$fund_source_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_saved');

				//WHERE VALUE
				$where 	 					= array();
				$where['fund_source_id'] 	= $fund_source_id;
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 				= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 					= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('fund_source_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['fund_source_name']);
	
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

	/*------------------------PAYROLL VALIDATE DATA START ----------------------------*/
	private function _validate_data_bank($params)
	{
		$fields                 = array();
		$fields['bank_name']  	= "Bank Name";
		$fields['account_no']  	= "Account No.";
		$fields['fund_source']  = "Fund Souce Name";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_bank_input ($params);
	}

	private function _validate_bank_input($params) 
	{
		try {
			
			$validation ['bank_name'] 	= array (
					'data_type' 		=> 'string',
					'name'				=> 'Bank Name',
					'max_len' 			=> 50 
			);
			$validation ['account_no'] 	= array (
					'data_type' 		=> 'string',
					'name'				=> 'Account No.',
					'max_len' 			=> 50 
			);
			$validation ['fund_source'] = array (
					'data_type' 		=> 'string',
					'name'				=> 'Fund Source',
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

	private function _validate_data_bir($params)
	{
		$fields                   = array();
		$fields['effective_date'] = "Effectivity Date";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_bir_input ($params);
	}

	private function _validate_bir_input($params) 
	{
		try {

			$validation ['effective_date'] 	= array (
					'data_type' 			=> 'date',
					'name'					=> 'Effectivity Date',
					'max_len' 				=> 50 
			);

			$validation ['active_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Active Flag',
					'max_len' 				=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_bir_details($params)
	{
		$fields               = array();
		$fields['min_amount'] = "Minimum Amount";
		$fields['max_amount'] = "Maximum Amount";
		$fields['tax_amount'] = "Tax Amount";
		$fields['tax_rate']   = "Tax Rate";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_bir_details_input ($params);
	}

	private function _validate_bir_details_input($params) 
	{
		try {

			$validation ['min_amount'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Minimum Amount',
					'max_len' 			=> 10 
			);

			$validation ['max_amount'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Maximum Amount',
					'max_len' 			=> 10 
			);

			$validation ['tax_amount'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Tax Amount',
					'max_len' 			=> 10 
			);

			$validation ['tax_rate'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Tax Rate',
					'max_len' 			=> 5 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_voucher($params)
	{
		$fields                 	= array();
		$fields['voucher_name']  	= "Voucher Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_voucher_input ($params);
	}

	private function _validate_voucher_input($params) 
	{
		try {
			
			$validation ['voucher_name'] 	= array (
					'data_type' 			=> 'string',
					'name'					=> 'Voucher Name',
					'max_len' 				=> 45 
			);
			$validation ['active_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Active Flag',
					'max_len' 				=> 1 
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_philhealth($params)
	{
		$fields                 			= array();
		$fields['salary_bracket']  			= "Salary Bracket";
		$fields['effectivity_date']  		= "Effectivity Date";/*
		$fields['salary_range_from']  		= "Salary Range From";
		$fields['salary_range_to']  		= "Salary Range To";*/
		$fields['employee_share']  			= "Employee Share";
		$fields['employer_share']  			= "Employer Share";
		$fields['salary_base']  			= "Salary Base";
		$fields['total_monthly_premium']  	= "Total Monthly Premium";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_philhealth_input ($params);
	}

	private function _validate_philhealth_input($params) 
	{
		try {
			
			$validation ['salary_bracket'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Salary Bracket',
					'max_len' 						=> 45 
			);
			$validation ['effectivity_date'] 		= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Effectivity Date',
			);
			$validation ['salary_range_from'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Salary Range From',
					'max_len' 						=> 45 
			);	
			$validation ['salary_range_to'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Salary Range To',
					'max_len' 						=> 45 
			);
			$validation ['employee_share'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employee Share',
					'max_len' 						=> 45 
			);
			$validation ['employer_share'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employer Share',
					'max_len' 						=> 45 
			);
			$validation ['salary_base'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Salary Base',
					'max_len' 						=> 45 
			);
			$validation ['total_monthly_premium'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Total Monthly Premium',
					'max_len' 						=> 45 
			);
			$validation ['active_flag'] 			= array (
					'data_type'						=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_pagibig($params)
	{
		$fields                 		= array();
		$fields['effectivity_date']  	= "Effectivity Date";
		$fields['salary_range_from']  	= "Salary Range From";
		$fields['salary_range_to']  	= "Salary Range To";
		$fields['employee_rate']  		= "Employee Rate";
		$fields['employer_rate']  		= "Employer Rate";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_pagibig_input ($params);
	}

	private function _validate_pagibig_input($params) 
	{
		try {
			
			$validation ['effectivity_date'] 	= array (
					'data_type' 				=> 'date',
					'name' 						=> 'Effectivity Date',
			);
			$validation ['salary_range_from'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Salary Range From',
					'max_len' 					=> 45 
			);
			$validation ['salary_range_to'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Salary Range To',
					'max_len' 					=> 45 
			);
			$validation ['employee_rate'] 		= array (
					'data_type'					=> 'string',
					'name' 						=> 'Employee Rate',
					'max_len' 					=> 45 
			);
			$validation ['employer_rate'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Employer Rate',
					'max_len' 					=> 45 
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1 
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_gsis($params)
	{
		$fields 							= array();
		$fields['personal_share']    		= "Personal Share";
		$fields['government_share']    		= "Government Share";
		$fields['max_government_share']    	= "Maximum Government Share";
		$fields['effective_date']    		= "Effectivity Date";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_gsis_input ($params);
	}

	private function _validate_gsis_input($params) 
	{
		try {
			
			$validation ['effective_date'] 			= array (
					'data_type' 					=> 'date',
					'name'							=> 'Effectivity Date',
			);
			$validation ['personal_share'] 			= array (
					'data_type' 					=> 'decimal',
					'name'							=> 'Personal Share'
			);
			$validation ['government_share'] 		= array (
					'data_type' 					=> 'string',
					'name'							=> 'Government Share'
			);
			$validation ['max_government_share'] 	= array (
					'data_type' 					=> 'string',
					'name'							=> 'Maximum Government Share'
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			$validation ['insurance_type_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Insurance Type Flag',
					'max_len' 						=> 3
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_fund_source($params)
	{
		$fields                 		= array();
		$fields['fund_source_name']  	= "Fund Source Name";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_fund_source_input ($params);
	}

	private function _validate_fund_source_input($params) 
	{
		try {
			
			$validation ['fund_source_name'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Fund Source Name',
					'max_len' 					=> 100
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_remittance_type($params)
	{
		$fields                 			= array();
		$fields['remittance_type_name']  	= "Remittance Type Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_remittance_type_input($params);
	}

	private function _validate_remittance_type_input($params) 
	{
		try {
			
			$validation ['remittance_type_name'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Remittance Type',
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

	/*------------------------PAYROLL VALIDATE DATA END ----------------------------*/
	/*----- START GET LIST PAYROLL -----*/
	public function get_bank_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("A.*", "B.fund_source_name");
			$bColumns					= array("A.bank_name", "A.account_no", "B.fund_source_name", "A.active_flag");
			$table 	  					= $this->cl->tbl_param_banks;
			$where						= array();
			$banks 						= $this->cl->get_bank_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(bank_id)) AS count"), $this->cl->tbl_param_banks, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($banks),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_DELETE);

			$cnt = 0; 
			foreach ($banks as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$bank_id 				= $aRow["bank_id"];
				$id 					= $this->hash ($bank_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("bank", "'.$url_delete.'")';

				$row[] = $aRow['bank_name'];
				$row[] = $aRow['account_no'];
				$row[] = $aRow['fund_source_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_bank' onclick=\"modal_bank_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_bank' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_bank_init('".$edit_action."')\"></a>";
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

	public function get_bir_list()
	{
		try
		{
			$params 					= get_params();
			
			$aColumns					= array("bir_id", "DATE_FORMAT(effective_date, '%Y/%m/%d')", "tax_table_flag", "active_flag");
			$bColumns					= array("DATE_FORMAT(effective_date, '%Y/%m/%d')", "active_flag");
			$table 	  					= $this->cl->tbl_param_bir;
			$where						= array();
			$bir 						= $this->cl->get_bir_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(bir_id)) AS count"), $this->cl->tbl_param_bir, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($bir),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_DELETE);

			$cnt = 0; 
			foreach ($bir as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$bir_id 				= $aRow["bir_id"];
				$id 					= $this->hash ($bir_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("BIR Table", "'.$url_delete.'")';

				$row[] = '<center>' . $aRow['effective_date'] . '</center>';
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_bir' onclick=\"modal_bir_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_bir' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_bir_init('".$edit_action."')\"></a>";
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

	public function get_voucher_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("voucher_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_voucher;
			$where						= array();
			$voucher 					= $this->cl->get_voucher_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(voucher_id)) AS count"), $this->cl->tbl_param_voucher, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($voucher),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);

			$cnt = 0;
			foreach ($voucher as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["voucher_id"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("voucher", "'.$url_delete.'")';

				$row[] = $aRow['voucher_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_voucher' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_voucher_init('".$edit_action."')\"></a>";
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

	public function get_philhealth_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$bColumns					= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$table 	  					= $this->cl->tbl_param_philhealth;
			$where						= array();
			$philhealth					= $this->cl->get_philhealth_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effectivity_date)) AS count"), $this->cl->tbl_param_philhealth, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($philhealth),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PHILHEALTH, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PHILHEALTH, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PHILHEALTH, ACTION_DELETE);

			$cnt = 0;
			foreach ($philhealth as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["effectivity_date"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("Philhealth", "'.$url_delete.'")';

				$row[] = '<center>' . $aRow['effectivity_date'] . '</center>';
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_philhealth' onclick=\"modal_philhealth_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_philhealth' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_philhealth_init('".$edit_action."')\"></a>";
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

	public function get_pagibig_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$bColumns					= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$table 	  					= $this->cl->tbl_param_pagibig;
			$where						= array();
			$pagibig					= $this->cl->get_pagibig_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(pagibig_id)) AS count"), $this->cl->tbl_param_pagibig, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($pagibig),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PAGIBIG, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PAGIBIG, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PAGIBIG, ACTION_DELETE);

			$cnt = 0;
			foreach ($pagibig as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["pagibig_id"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("Pagibig", "'.$url_delete.'")';

				$row[] = $aRow['effectivity_date'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_pagibig' onclick=\"modal_pagibig_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_pagibig' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_pagibig_init('".$edit_action."')\"></a>";
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
	public function get_gsis_list()
	{

		try
		{
			$padrams 					= get_params();
			
			$aColumns					= array("DATE_FORMAT(effective_date, '%Y/%m/%d')", "tax_table_flag", "active_flag");
			$bColumns					= array("DATE_FORMAT(effective_date, '%Y/%m/%d')", "active_flag");
			$table 	  					= $this->cl->tbl_param_gsis;	
			$where						= array();
			$gsis 						= $this->cl->get_gsis_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effective_date)) AS count"), $this->cl->tbl_param_gsis, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($gsis),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_GSIS, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_GSIS, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_GSIS, ACTION_DELETE);

			$cnt = 0;
			foreach ($gsis as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["effective_date"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("GSIS", "'.$url_delete.'")';

				$row[] = '<center>' . format_date($aRow['effective_date']) . '</center>';
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_gsis' onclick=\"modal_gsis_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_gsis' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_gsis_init('".$edit_action."')\"></a>";
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

	public function get_fund_source_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array("fund_source_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_fund_sources;
			$where						= array();
			$fund_source 				= $this->cl->get_fund_source_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(fund_source_id)) AS count"), $this->cl->tbl_param_fund_sources, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($fund_source),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_FUND_SOURCE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_FUND_SOURCE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_FUND_SOURCE, ACTION_DELETE);
			
			$cnt = 0;
			foreach ($fund_source as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "<div class='table-actions'>";
			
				$fund_source_id 		= $aRow["fund_source_id"];
				$id 					= $this->hash ($fund_source_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("fund source", "'.$url_delete.'")';
				
				$row[] = $aRow['fund_source_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_fund_source' onclick=\"modal_fund_source_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_fund_source' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_fund_source_init('".$edit_action."')\"></a>";
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

	public function get_remittance_type_list()
	{

		try
		{
			$params             		= get_params();
			
			$aColumns           		= array("*");
			$bColumns           		= array("remittance_type_name", "active_flag");
			$table              		= $this->cl->tbl_param_remittance_types;
			$where             			= array();
			$remittance_type 			= $this->cl->get_remittance_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(remittance_type_id)) AS count"), $this->cl->tbl_param_remittance_types, NULL, false);
			
			$output 					= array(
				"sEcho"               	=> intval($_POST['sEcho']),
				"iTotalRecords"       	=> count($remittance_type),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData"               	=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_REMITTANCE_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_REMITTANCE_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_REMITTANCE_TYPE, ACTION_DELETE);

			$cnt = 0;
			foreach ($remittance_type as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$remittance_type_id    	= $aRow["remittance_type_id"];
				$id                    	= $this->hash($remittance_type_id);
				$salt                  	= gen_salt();
				$token_view            	= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit            	= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete          	= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action           	= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action           	= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;		
				$url_delete            	= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action         	= 'content_delete("remittance type", "'.$url_delete.'")';
				
				$row[] = $aRow['remittance_type_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_remittance_type' onclick=\"modal_remittance_type_init('".$view_action."')\"></a>";
				// if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_remittance_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_remittance_type_init('".$edit_action."')\"></a>";
				// if($this->permission->check_permission(MODULE_USER, ACTION_DELETE))
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

	/*----- END GET LIST PAYROLL -----*/
	
	
	/*----- START DELETE PAYROLL -----*/
	public function delete_bank()
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
			$table 				= $this->cl->tbl_param_banks;
			$where				= array();
			$key 				= $this->get_hash_key('bank_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['bank_name']);
	
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"table_id" 			=> 'bank_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_bank_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_bir()
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
			$table 				= $this->cl->tbl_param_bir;
			$where				= array();
			$key 				= $this->get_hash_key('bir_id');
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
			$activity 			= sprintf($activity, 'BIR table');
	
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
		
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"table_id" 			=> 'bir_table_dt',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_bir_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_voucher()
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
			$table 				= $this->cl->tbl_param_voucher;
			$where				= array();
			$key 				= $this->get_hash_key('voucher_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['voucher_name']);
	
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_voucher_list/',
			"advanced_filter"	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_philhealth()
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
			$table 				= $this->cl->tbl_param_philhealth;
			$where				= array();
			$key 				= $this->get_hash_key('effectivity_date');
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"table_id" 			=> 'philhealth_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_philhealth_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_pagibig()
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
			$table 				= $this->cl->tbl_param_pagibig;
			$where				= array();
			$key 				= $this->get_hash_key('pagibig_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['pagibig_id']);
	
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"table_id" 			=> 'pagibig_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_pagibig_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_gsis()
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
			$table 				= $this->cl->tbl_param_gsis;

			$where				= array();
			$key 				= $this->get_hash_key('effective_date');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['effective_date']);
	
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"table_id" 			=> 'gsis_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_gsis_list/',
			"advanced_filter"	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_fund_source()
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
			$table 				= $this->cl->tbl_param_fund_sources;
			$where				= array();
			$key 				= $this->get_hash_key('fund_source_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['fund_source_name']);
	
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info 					= array(
			"flag"            	=> $flag,
			"msg"            	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'fund_source_table',
			"path"            	=> PROJECT_MAIN . '/code_library_payroll/get_fund_source_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_remittance_type()
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
			$table 				= $this->cl->tbl_param_remittance_types;
			$where				= array();
			$key 				= $this->get_hash_key('remittance_type_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['remittance_type_name']);
	
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
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
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
			"table_id" 			=> 'replacement_reason_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/get_replacement_reason_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	/*----- END DELETE PAYROLL -----*/

	

	public function modal($modal = NULL, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			
			$data['action_id'] 		  = $action_id;
			switch ($modal) 
			{
				case 'bank':
					$data['nav_page'] = CODE_LIBRARY_BANK;
				break;
				case 'bir':
					$data['nav_page'] = CODE_LIBRARY_BIR_TABLE;
				break;
				case 'fund_source':
					$data['nav_page'] = CODE_LIBRARY_FUND_SOURCE;
				break;
				case 'gsis':
					$data['nav_page'] = CODE_LIBRARY_GSIS_TABLE;
				break;
				case 'pagibig':
					$data['nav_page'] = CODE_LIBRARY_PAGIBIG_TABLE;
				break;
				case 'philhealth':
					$data['nav_page'] = CODE_LIBRARY_PHILHEALTH_TABLE;
				break;
				case 'remittance_type':
					$data['nav_page'] = CODE_LIBRARY_REMITTANCE_TYPE;
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

	/*----- START MODAL PAYROLL -----*/
	public function modal_bank($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE);

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_fund_sources;
			$where							= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['fund_source_name'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_BANK;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_banks;
				$where						= array();
				$key 						= $this->get_hash_key('bank_id');
				$where[$key]				= $id;
				$bank_info 					= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['bank_info']			= $bank_info;

				$resources['single']		= array(
					'fund_source' 			=> $data['bank_info']['fund_source_id']
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
		
		$this->load->view('code_library/modals/modal_bank', $data);
		$this->load_resources->get_resource($resources);
	}

	/*----- START MODAL PAYROLL -----*/
	public function modal_bir($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 					= array();
			$resources['load_css']		= array(CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_DATETIMEPICKER, 'jquery.number.min');


			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 				= $action;
			$data['nav_page']				= MODULE_PAYROLL_CL_BIR_TABLE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;

			if(!EMPTY($id))
			{

				$tables = array(

					'main'      => array(
					'table'     => $this->cl->tbl_param_bir,
					'alias'     => 'A',
					),
					't2'        => array(
					'table'     => $this->cl->tbl_param_bir_details,
					'alias'     => 'B',
					'type'      => 'LEFT JOIN',
					'condition' => 'A.bir_id = B.bir_id',
				 	)
				);
				$select_fields = array("A.*, B.*");
				//EDIT
				$table 						= $this->cl->tbl_param_bir;
				$where						= array();
				$key 						= $this->get_hash_key('A.bir_id');
				$where[$key]				= $id;
				$bir_info 					= $this->cl->get_code_library_details($select_fields, $tables, $where);	
				
				$data['bir_info']			= $bir_info;

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
		
		$this->load->view('code_library/modals/modal_bir', $data);
		$this->load_resources->get_resource($resources);
	}
	public function modal_voucher($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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
			$data['nav_page']			= CODE_LIBRARY_VOUCHER;
			$data ['action'] 			= $action;
			$data ['salt'] 				= $salt;
			$data ['token'] 			= $token;
			$data ['id'] 				= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 					= $this->cl->tbl_param_voucher;
				$where					= array();
				$key 					= $this->get_hash_key('voucher_id');
				$where[$key]			= $id;
				$voucher_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['voucher_info']	= $voucher_info;
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
		
		$this->load->view('code_library/modals/modal_voucher', $data);
	}

	public function modal_philhealth($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_DATETIMEPICKER);
			$resources['load_js'] 			= array(JS_DATETIMEPICKER, 'jquery.number.min');

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_PHILHEALTH_TABLE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_philhealth;
				$where						= array();
				$key 						= $this->get_hash_key('effectivity_date');
				$where[$key]				= $id;
				$data['philhealth_info']	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
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
		
		$this->load->view('code_library/modals/modal_philhealth', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_pagibig($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 					= array();
			$resources['load_css']		= array(CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_DATETIMEPICKER, 'jquery.number.min');

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 			= $action;
			$data['nav_page']			= CODE_LIBRARY_PAGIBIG_TABLE;
			$data ['action'] 			= $action;
			$data ['salt'] 				= $salt;
			$data ['token'] 			= $token;
			$data ['id'] 				= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 					= $this->cl->tbl_param_pagibig;
				$where					= array();
				$key 					= $this->get_hash_key('pagibig_id');
				$where[$key]			= $id;
				$pagibig_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['pagibig_info']	= $pagibig_info;
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
		
		$this->load->view('code_library/modals/modal_pagibig', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_gsis($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 							= array();
			$resources['load_css'] 				= array(CSS_DATETIMEPICKER);
			$resources['load_js']  				= array(JS_DATETIMEPICKER, 'jquery.number.min');

			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_GSIS_TABLE;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              			= $this->cl->tbl_param_gsis;
				$where							= array();
				$key 							= $this->get_hash_key('effective_date');
				$where[$key]					= $id;
				$gsis_info 						= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				$data['gsis_info']				= $gsis_info;

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
		
		$this->load->view('code_library/modals/modal_gsis', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_fund_source($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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
			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_FUND_SOURCE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              		= $this->cl->tbl_param_fund_sources;
				$where              		= array();
				$key                		= $this->get_hash_key('fund_source_id');
				$where[$key]        		= $id;
				$fund_source_info    		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['fund_source_info'] 	= $fund_source_info;
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
		
		$this->load->view('code_library/modals/modal_fund_source', $data);
	}

	public function modal_remittance_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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
			$data['action_id']				 	= $action;
			$data['nav_page']					= CODE_LIBRARY_REMITTANCE_TYPE;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			if(!EMPTY($id))
			{
				//EDIT
				$table 							= $this->cl->tbl_param_remittance_types;
				$where							= array();
				$key 							= $this->get_hash_key('remittance_type_id');
				$where[$key]					= $id;
				$remittance_type_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				$data['remittance_type_info']	= $remittance_type_info;
				
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
		
		$this->load->view('code_library/modals/modal_remittance_type', $data);
	}
	/*----- END MODAL PAYROLL -----*/
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_payroll.php */