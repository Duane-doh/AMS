<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//RLog::info('LINE 2209 =>'.json_encode($fields));
class Code_library_system extends Main_Controller {
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
			$resources['load_css'] 	= array(CSS_LABELAUTY, CSS_SELECTIZE,CSS_DATETIMEPICKER, CSS_CALENDAR);
			$resources['load_js'] 	= array(JS_LABELAUTY,JS_SELECTIZE, JS_DATETIMEPICKER, JS_CALENDAR, JS_CALENDAR_MOMENT);
			$data['action_id']		= $action_id;
			switch ($form) 
			{
				/*----- START GET TAB SYSTEM -----*/
				case 'sys_param':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'sys_param_table', 'path' => 'main/code_library_system/get_sys_param_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_sys_param' 		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_sys_param',
							'multiple'			=> true,
							'height'			=> '250px',
							'size'				=> 'sm',
							'title'				=> 'System Parameter'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_sys_param',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'check_list':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'check_list_table', 'path' => 'main/code_library_system/get_check_list', 'advanced_filter' => TRUE);
					$resources['load_modal']	= array(
						'modal_check_list'		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_check_list',
							'multiple'			=> true,
							'height'			=> '300px',
							'size'				=> 'sm',
							'title'				=> 'Checklist'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_check_list',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'dropdown':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'dropdown_table', 'path' => 'main/code_library_system/get_dropdown_list', 'advanced_filter' => TRUE);
					$resources['load_modal']	= array(
						'modal_dropdown'		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_dropdown',
							'multiple'			=> true,
							'height'			=> '380px',
							'size'				=> 'md',
							'title'				=> 'Dropdown'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_dropdown',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'supp_doc_type':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'supp_doc_type_table', 'path' => 'main/code_library_system/get_supp_doc_type_list', 'advanced_filter' => TRUE);
					$resources['load_modal']	= array(
						'modal_supp_doc_type'	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_supp_doc_type',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'Supporting Document Type'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_dropdown',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				/*----- END GET TAB SYSTEM -----*/

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

	/*----- START PROCESS SYSTEM -----*/
	public function process_sys_param()
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
			$valid_data 					= $this->_validate_data_sys_param($params);

			//SET FIELDS VALUE
			$fields['sys_param_type'] 		= $valid_data['sys_param_type'];
			$fields['sys_param_name'] 		= $valid_data['sys_param_name'];
			$fields['sys_param_value'] 		= $valid_data['sys_param_value'];

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->db_core.".".$this->cl->tbl_sys_param;
			$audit_table[]	= $this->cl->tbl_sys_param;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS

				$audit_action[]				= AUDIT_INSERT;
				
				$prev_detail[]				= array();

				$fields['built_in_flag'] 	= '2';

				//INSERT DATA
				$sys_param_id				= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 					= array();
				$where['sys_param_id']		= $sys_param_id;

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
				$key 			= $this->get_hash_key('sys_param_id');
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
			
			$activity = sprintf($activity, $params['sys_param_name']);
	
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

	public function process_check_list()
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
			$valid_data                        	= $this->_validate_data_check_list($params);
			
			
			// SET FIELDS VALUE PARAM CHECKLIST
			$fields								= array();
			$fields['check_list_code']        	= $valid_data['check_list_name'];
			$fields['check_list_description']	= $valid_data['check_list_description'];
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			
			if(EMPTY($params['id']))
			{
				//INSERT DATA PARAM CHECK LIST 
				$table 							= $this->cl->tbl_param_checklists;
				$check_list_type          		= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]					= $this->cl->tbl_param_checklists;
				$audit_schema[]					= Base_Model::$schema_core;
				$prev_detail[]            		= array();
				$curr_detail[]            		= array($fields);
				$audit_action[]           		= AUDIT_INSERT;
				
				//INSERT DATA PARAM CHECK LIST DOCS
				$current_detail 				= array();

				foreach ($valid_data['check_list_type'] as $value) {
					$table 						= $this->cl->tbl_param_checklist_docs;	

					$fields						= array();
					$fields['supp_doc_type_id'] = $value;
					$fields['check_list_id'] 	= $check_list_type['check_list_id'];

					$this->cl->insert_code_library($table, $fields, FALSE);

					$current_detail[] 			= $fields;
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]					= $this->cl->tbl_param_checklist_docs;
				$audit_schema[]					= Base_Model::$schema_core;
				$prev_detail[]         			= array();
				$curr_detail[]          		= $current_detail;
				$audit_action[]        			= AUDIT_INSERT;
				
				//MESSAGE ALERT
				$message                		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity               		= "%s has been added";
			}
			else
			{
				//WHERE 
				$where							= array();
				$key							= $this->get_hash_key('check_list_id');
				$where[$key]					= $params['id'];	

				$table 							= $this->cl->tbl_param_checklists;	
				$previous 						= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				$this->cl->update_code_library($table, $fields, $where);	

				$audit_table[]					= $this->cl->tbl_param_checklists;
				$audit_schema[]					= DB_MAIN;
				$prev_detail[]         			= array($previous);
				$curr_detail[]         			= array($fields);
				$audit_action[]         		= AUDIT_UPDATE;	

				//WHERE 
				$where							= array();
				$key							= $this->get_hash_key('check_list_id');
				$where[$key]					= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_checklist_docs, $where);

				foreach ($valid_data['check_list_type'] as $value) {
					$table 						= $this->cl->tbl_param_checklist_docs;	

					$fields						= array();
					$fields['supp_doc_type_id'] = $value;
					$fields['check_list_id'] 	= $previous['check_list_id'];

					$this->cl->insert_code_library($table, $fields, FALSE);

					$current_detail[] 			= $fields;
				}

				$audit_table[]					= $this->cl->tbl_param_checklist_docs;
				$audit_schema[]					= Base_Model::$schema_core;
				$prev_detail[]         			= array();
				$curr_detail[]          		= $current_detail;
				$audit_action[]        			= AUDIT_UPDATE;
				
				//MESSAGE ALERT
				$message 						= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['check_list_name']);
	
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

	public function process_dropdown()
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
			$valid_data             			= $this->_validate_data_dropdown($params);
			//SET FIELDS VALUE
			$fields                  			= array();
			$fields['dropdown_name'] 			= $valid_data['dropdown_name'];
			$fields['columns']       			= html_entity_decode($valid_data['columns'], ENT_QUOTES);
			$fields['table_name']   			= $valid_data['table'];
			

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_dropdown;
			$audit_table[]						= $table;
			$audit_schema[]						= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]					= AUDIT_INSERT;
				
				$prev_detail[]					= array();

				$fields['created_by']    		= $this->session->userdata('user_id');
				$fields['created_date']  		= date('Y-m-d');
				//INSERT DATA
				$dropdown_id 					= $this->cl->insert_code_library($table, $fields, TRUE);
				
				$table 							= $this->cl->tbl_param_dropdown_conditions;
				//INSERT DATA INTO OTHER DEDUCTION DETAILS
				for ($i = 0; $i < count($params['where_condition']); $i++ ) {

					$fields                    	= array();
					$fields['dropdown_id']     	= $dropdown_id;
					$fields['where_condition'] 	= $params['where_condition'][$i];
					$fields['where_field']     	= $params['where_field'][$i];
					$fields['where_operator']  	= $params['where_operator'][$i];
					$fields['where_value']     	= $params['where_value'][$i];

					$this->cl->insert_code_library($table, $fields);
				}
				
				//MESSAGE ALERT
				$message 						= $this->lang->line('data_saved');

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[]  				= array($fields);//$this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where							= array();
				$key 							= $this->get_hash_key('dropdown_id');
				$where[$key]					= $params['id'];
				
				$audit_action[]					= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  				= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//EDIT
				$where							= array();
				$key 							= $this->get_hash_key('dropdown_id');
				$where[$key]					= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_dropdown_conditions, $where);

				$dropdown  						= $this->cl->get_code_library_data(array("dropdown_id"), $table, $where, FALSE);
				//INSERT DATA INTO CONDITION DETAILS
				for ($i = 0; $i < count($params['where_condition']); $i++ ) {

					$fields                    	= array();
					$fields['dropdown_id']     	= $dropdown['dropdown_id'];
					$fields['where_condition'] 	= $params['where_condition'][$i];
					$fields['where_field']     	= $params['where_field'][$i];
					$fields['where_operator']  	= $params['where_operator'][$i];
					$fields['where_value']     	= $params['where_value'][$i];

					$this->cl->insert_code_library($this->cl->tbl_param_dropdown_conditions, $fields);
				}

				//MESSAGE ALERT
				$message 						= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  				= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['dropdown_name']);
	
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

	public function process_supp_doc_type()
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
			$valid_data 					= $this->_validate_data_supp_doc_type($params);

			//SET FIELDS VALUE
			$fields 						= array();
			$fields['supp_doc_type_name'] 	= $valid_data['supp_doc_type_name'];
			$fields['active_flag'] 			= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_supporting_document_types;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]				= AUDIT_INSERT;
				
				$prev_detail[]				= array();

				//INSERT DATA
				$supp_doc_type_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_saved');

				//WHERE VALUE
				$where 	 					= array();
				$where['supp_doc_type_id']	= $supp_doc_type_id;

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
				$key 			= $this->get_hash_key('supp_doc_type_id');
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
			
			$activity = sprintf($activity, $params['supp_doc_type_name']);
	
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

	/*----- END PROCESS SYSTEM -----*/

	/*------------------------SYSTEMS VALIDATE DATA START ----------------------------*/

	private function _validate_data_sys_param($params)
	{
		$fields                 	= array();
		$fields['sys_param_type']  	= "System Parameter Type";
		$fields['sys_param_name']  	= "System Parameter Name";
		$fields['sys_param_value']  = "System Parameter Value";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_sys_param_input ($params);
	}

	private function _validate_sys_param_input($params) 
	{
		try {
			
			$validation ['sys_param_type'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'System Parameter Name',
					'max_len' 				=> 45 
			);
			$validation ['sys_param_name'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'System Parameter Type',
					'max_len' 				=> 100 
			);
			$validation ['sys_param_value'] = array (
					'data_type' 			=> 'string',
					'name' 					=> 'System Parameter Value',
					'max_len' 				=> 45 
			);
			$validation ['built_in_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Built In Flag',
					'max_len' 				=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_check_list($params)
	{
		$fields                 			= array();
		$fields['check_list_name']  		= "Checklist Name";
		$fields['check_list_type']  		= "Checklist Type";
		$fields['check_list_description'] 	= "Checklist Description";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_check_list_input ($params);
	}

	private function _validate_check_list_input($params) 
	{
		try {
			
			$validation ['check_list_name'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Checklist Name',
					'max_len' 						=> 45 
			);
			$validation ['check_list_type'] 		= array (
					'data_type'						=> 'string',
					'name' 							=> 'Checklist Type',
					'max_len' 						=> 45 
			);
			$validation ['check_list_description'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Checklist Description',
					'max_len' 						=> 255 
			);
			$validation ['active_flag']				= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_supp_doc_type($params)
	{
		$fields                  		= array();
		$fields['supp_doc_type_name']  	= "Supporting Document Type Name";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_supp_doc_type_input ($params);
	}

	private function _validate_supp_doc_type_input($params) 
	{
		try {
			
			$validation ['supp_doc_type_name'] 	= array (
					'data_type'					=> 'string',
					'name' 						=> 'Supporting Document Type Name',
					'max_len' 					=> 255
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

	private function _validate_data_dropdown($params)
	{
		$fields                  = array();
		$fields['dropdown_name'] = "Dropdown Name";
		$fields['columns']       = "Dropdown Columns";
		$fields['table']         = "Dropdown Table";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_dropdown_input ($params);
	}

	private function _validate_dropdown_input($params) 
	{
		try {
			
			$validation ['dropdown_name'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Dropdown Name',
					'max_len' 					=> 50 
			);
			$validation ['columns'] 			= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Dropdown Columns',
					'max_len' 					=> 100
			);
			$validation ['table'] 				= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Dropdown Table',
					'max_len' 					=> 50
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	/*------------------------SYSTEMS VALIDATE DATA END ----------------------------*/

	/*----- START GET LIST SYSTEM -----*/
	public function get_sys_param_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("sys_param_type", "sys_param_name", "sys_param_value", "built_in_flag");
			$where						= array();
			$sys_param 					= $this->cl->get_sys_param_list($aColumns, $bColumns, $params, $table, $where);
			$table 	  					= $this->cl->db_core.'.'.$this->cl->tbl_sys_param;
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(sys_param_id)) as count" ), $table, array(), FALSE );
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords"			=> count($sys_param),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData"				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_SYSTEM_CL_SYSTEM_PARAMETER, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_SYSTEM_CL_SYSTEM_PARAMETER, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_SYSTEM_CL_SYSTEM_PARAMETER, ACTION_DELETE);

			$cnt = 0;
			foreach ($sys_param as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$sys_param_id 			= $aRow["sys_param_id"];
				$id 					= $this->hash ($sys_param_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("system parameter", "'.$url_delete.'")';
				
				$row[] = $aRow['sys_param_type'];
				$row[] = $aRow['sys_param_name'];
				$row[] = $aRow['sys_param_value'];
				$row[] = ($aRow['built_in_flag'] == "1") ? 'Built In':'User Defined';
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_sys_param' onclick=\"modal_sys_param_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_sys_param' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_sys_param_init('".$edit_action."')\"></a>";
				
				if ($aRow['built_in_flag'] != 1) 
				{
					if($permission_delete)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
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

	public function get_check_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array("check_list_code","check_list_description", "active_flag");
			$checklist 					= $this->cl->get_check_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(check_list_id)) AS count"), $this->cl->tbl_param_checklists, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($checklist),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_SYSTEM_CL_CHECKLISTS, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_SYSTEM_CL_CHECKLISTS, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_SYSTEM_CL_CHECKLISTS, ACTION_DELETE);

			$cnt = 0;
			foreach ($checklist as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$check_list_id 			= $aRow["check_list_id"];
				$id 					= $this->hash($check_list_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("checklist", "'.$url_delete.'")';
				
				$row[] = $aRow['check_list_code'];
				$row[] = $aRow['check_list_description'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_check_list' onclick=\"modal_check_list_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_check_list' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_check_list_init('".$edit_action."')\"></a>";
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

	public function get_supp_doc_type_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array("supp_doc_type_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_supporting_document_types;
			$where						= array();
			$supp_doc_type 				= $this->cl->get_supp_doc_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(supp_doc_type_name)) AS count"), $this->cl->tbl_param_supporting_document_types, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($supp_doc_type),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);

			$permission_view 			= $this->permission->check_permission(MODULE_SYSTEM_CL_SUPPORTING_DOCUMENT, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_SYSTEM_CL_SUPPORTING_DOCUMENT, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_SYSTEM_CL_SUPPORTING_DOCUMENT, ACTION_DELETE);

			$cnt = 0;
			foreach ($supp_doc_type as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$supp_doc_type_id 		= $aRow["supp_doc_type_id"];
				$id 					= $this->hash ($supp_doc_type_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("supporting document type", "'.$url_delete.'")';
				
				$row[] = $aRow['supp_doc_type_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_supp_doc_type' onclick=\"modal_supp_doc_type_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_supp_doc_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_supp_doc_type_init('".$edit_action."')\"></a>";
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

	public function get_dropdown_list()
	{

		try
		{
			$message        			= "";
			$params          			= get_params();

			$output 					= array(
				"sEcho"				 	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> 0,
				"iTotalDisplayRecords" 	=> 0,
				"aaData" 				=> array()
			);

			$table 			 			= '';
			$aColumns        			= array("A.*,B.dtl_no");
			$bColumns        			= array("A.dropdown_name", "A.table_name", "A.created_date");
			$result          			= $this->cl->get_dropdown_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(dropdown_id)) AS count"), $this->cl->tbl_param_dropdown, NULL, false);
			
			$output 					= array(
				"sEcho"				 	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($result),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			
			if(!EMPTY($result))
			{
				//PERMISSIONS
				$permission_view 		= $this->permission->check_permission(MODULE_SYSTEM_CL_DROPDOWN, ACTION_VIEW);
				$permission_edit 		= $this->permission->check_permission(MODULE_SYSTEM_CL_DROPDOWN, ACTION_EDIT);
				$permission_delete 		= $this->permission->check_permission(MODULE_SYSTEM_CL_DROPDOWN, ACTION_DELETE);

				foreach ($result as $aRow):
					$row 				= array();

					$action 			= "<div class='table-actions'>";
				
					$dropdown_id 		= $aRow["dropdown_id"];
					$id 				= $this->hash ($dropdown_id);
					$salt 				= gen_salt();
					$token_view 		= in_salt($id . '/' . ACTION_VIEW,  	$salt);
					$token_edit 		= in_salt($id . '/' . ACTION_EDIT, 		$salt);
					$token_delete 		= in_salt($id . '/' . ACTION_DELETE, 	$salt);
					$view_action 		= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
					$edit_action 		= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
					$url_delete 		= ACTION_DELETE."/". $id . "/" . $salt  . "/" . $token_delete;
					$delete_action		= 'content_delete("dropdown", "'.$url_delete.'")';
					
					$row[] = $aRow['dropdown_name'];
					$row[] = $aRow['table_name'];
					$row[] = '<center>' . format_date($aRow['created_date']) . '</center>';

					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_dropdown' onclick=\"modal_dropdown_init('".$view_action."')\"></a>";
					if($permission_edit && EMPTY($aRow['dtl_no']))
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_dropdown' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_dropdown_init('".$edit_action."')\"></a>";
					if($permission_delete && EMPTY($aRow['dtl_no']))
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
					
					$action .= "</div>";

					$row[] = $action;
					
					$output['aaData'][] = $row;
				endforeach;
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

	/*----- END GET LIST SYSTEM -----*/

	/*----- START DELETE SYSTEM -----*/
	public function delete_sys_param()
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
			$table 				= $this->cl->tbl_sys_param;
			$where				= array();
			$key 				= $this->get_hash_key('sys_param_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['sys_param_name']);
	
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
			"table_id" 			=> 'sys_param_table',
			"path"				=> PROJECT_MAIN . '/code_library_system/get_sys_param_list/',
			"advanced_filter" 	=> true
		);	
	
		echo json_encode($info);
	}

	public function delete_check_list()
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
			$table 				= $this->cl->tbl_param_checklist_docs;
			$table2				= $this->cl->tbl_param_checklists;

			$where				= array();
			$key 				= $this->get_hash_key('check_list_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$this->cl->delete_code_library($table2, $where);

			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['check_list_name']);
	
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
			"table_id" 			=> 'check_list_table',
			"path"				=> PROJECT_MAIN . '/code_library_system/get_check_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_dropdown()
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
			$table 				= $this->cl->tbl_param_dropdown;

			$where				= array();
			$key 				= $this->get_hash_key('dropdown_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['dropdown_name']);
	
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
			"table_id" 			=> 'dropdown_table',
			"path"				=> PROJECT_MAIN . '/code_library_system/get_dropdown_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_supp_doc_type()
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
			$table 				= $this->cl->tbl_param_supporting_document_types;
			$where				= array();
			$key 				= $this->get_hash_key('supp_doc_type_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['supp_doc_type_name']);
	
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
			"msg"            	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'supp_doc_type_table',
			"path"            	=> PROJECT_MAIN . '/code_library_system/get_supp_doc_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}
	/*----- END DELETE SYSTEM -----*/

	public function modal($modal = NULL, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			
			$data['action_id'] 		  = $action_id;
			switch ($modal) 
			{
				case 'sys_param':
					$data['nav_page'] = CODE_LIBRARY_SYSTEM_PARAMETER;
				break;
				case 'check_list':
					$data['nav_page'] = CODE_LIBRARY_DOCUMENT_CHECKLIST;
				break;
				case 'supp_doc_type':
					$data['nav_page'] = CODE_LIBRARY_SUPP_DOC_TYPE;
				break;
				case 'dropdown':
					$data['nav_page'] = CODE_LIBRARY_DROPDOWN;
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

	/*----- START MODAL SYSTEM -----*/
	public function modal_sys_param($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE);

			$field 							= array("*") ;
			$table							= $this->cl->db_core.".".$this->cl->tbl_sys_param_type;
			$where							= array();	
			$data['sys_param_type'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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
			$data['nav_page']				= CODE_LIBRARY_SYSTEM_PARAMETER;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
				
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->db_core.".".$this->cl->tbl_sys_param;
				$where						= array();
				$key 						= $this->get_hash_key('sys_param_id');
				$where[$key]				= $id;
				$sys_param_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['sys_param_info']		= $sys_param_info;

				$resources['single']		= array(
					'sys_param_type' 		=> $data['sys_param_info']['sys_param_type']
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
		
		$this->load->view('code_library/modals/modal_sys_param', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_check_list($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources                           	 = array();
			$resources['load_css']                	 = array(CSS_SELECTIZE);
			$resources['load_js']                	 = array(JS_SELECTIZE);

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
			$data ['nav_page']						= CODE_LIBRARY_DOCUMENT_CHECKLIST;
			$data ['action'] 						= $action;
			$data ['salt'] 							= $salt;
			$data ['token'] 						= $token;
			$data ['id'] 							= $id;

			//LIST THE DEDUCTION TYPE
			$field                                 	= array("*") ;
			$table                               	= $this->cl->tbl_param_supporting_document_types;
			$where                                 	= array();
			$supporting_document_type_name         	= $this->cl->get_code_library_data($field, $table, $where, TRUE);
			$data['supporting_document_type_name'] 	= $supporting_document_type_name;
			
			if(!EMPTY($id))
			{
				//EDIT

				$select_fields = array("*");
				$tables = $this->cl->tbl_param_checklists;

				$field       						= array("*") ;
				$where       						= array();
				$key         						= $this->get_hash_key('check_list_id');
				$where[$key] 						= $id;
				$check_list_info   					= $this->cl->get_code_library_data($field, $tables, $where, FALSE);
				$data['check_list_info']			= $check_list_info;
				$check_list   						= $this->cl->get_checklist_supp_doc_list($check_list_info['check_list_id']);

				$doc_type 							= array();

				foreach ($check_list as $value) {
					$doc_type[] 					= $value['supp_doc_type_id'];
				}

				$resources['multiple']				= array(
					'check_list_type' 				=> $doc_type
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
		
		$this->load->view('code_library/modals/modal_check_list', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_supp_doc_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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
			$data['nav_page']					= CODE_LIBRARY_SUPP_DOC_TYPE;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              			= $this->cl->tbl_param_supporting_document_types;
				$where              			= array();
				$key                			= $this->get_hash_key('supp_doc_type_id');
				$where[$key]        			= $id;
				$supp_doc_type_info    			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['supp_doc_type_info'] 	= $supp_doc_type_info;
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
		
		$this->load->view('code_library/modals/modal_supp_doc_type', $data);
	}

	public function modal_dropdown($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE);


			$status		= FALSE;
			$message	= "";
			$data		= array();

			if($action != ACTION_ADD)
			{
				if(EMPTY($action) OR EMPTY($id) OR EMPTY($salt) OR EMPTY($token)){
					throw new Exception($this->lang->line('err_unauthorized_access'));

				}
				if($token != in_salt($id . '/' . $action, $salt)) {
					throw new Exception($this->lang->line('err_invalid_request'));
				}
			}

			$tables			= $this->cl->get_code_library_data(array("*"),$this->cl->tbl_data_dictionary,NULL);

			$data['tables']	= $tables;

			$display_view	= '';
			$cond_counter	= 1;
			$dd_where 		= FALSE;
			$sel_option		= '';

			if(!EMPTY($id))
			{

				$where 					= array();
				$key					= $this->get_hash_key("dropdown_id");
				$where[$key]			= $id;
				$table 					= $this->cl->tbl_param_dropdown;
				$drop_down				= $this->cl->get_code_library_data(array("*"),$table,$where,FALSE);

				if(EMPTY($drop_down))
					throw new Exception($this->lang->line('err_invalid_request'));
				$table 					= $this->cl->tbl_param_dropdown_conditions;
				$dropdown_where			= $this->cl->get_code_library_data(array("*"),$table,$where,TRUE);

				$table			= $this->cl->tbl_data_dictionary;
				$where			= array();
				$key			= 'table_name';
				$where[$key]	= $drop_down['table_name'];
				$fields			= $this->cl->get_code_library_data(array("column_name"),$table,$where,TRUE);
				$sel_option		.= '<option value="" selected></option>';

				if(!EMPTY($fields))
				{
					foreach ($fields as $col)
					{
						if($col['column_name']!='line_no')
							$sel_option .= '<option value="'.$col['column_name'].'">'.$col['column_name'].'</option>';
					}
				}
				if(!EMPTY($dropdown_where))
				{
					$disabled		= $action == ACTION_VIEW ? "disabled" : "";
					$dd_where 		= TRUE;

					$operators 		= array("=","!=","<=",">=","LIKE");

					foreach ($dropdown_where as $value)
					{
						if($value['where_condition'] == 'WHERE')
						{
							$display_view .= '
							<div class="list-group-item" id="where_div">
								<div class = "row">
									<label class="col s2 p-t-sm center">WHERE</label>
									<input type="hidden" name="where_condition[]" '.$disabled.' value="'.$value['where_condition'].'" id="where_condition_1">
									<div class="col s3">
										<select name="where_field[]" id="where_field_1" '.$disabled.' placeholder="Select Fields" type="text" class="selectize validate where_field">';
											foreach ($fields as $col)
											{
												$selected = '';
												if($value['where_field'] == $col['column_name'])
													$selected = 'selected';
												$display_view .= '<option value="'.$col['column_name'].'" '.$selected.'>'.$col['column_name'].'</option>';
											}
										$display_view .= '</select>
									</div>
									<div class="col s2">
										<select name="where_operator[]" '.$disabled.' id="where_operator" placeholder="Operator" type="text" class="selectize validate">
											<option value=""></option>';
											foreach ($operators as $operator)
											{
												$selected = '';
												if($value['where_operator'] == $operator)
													$selected = 'selected';
												$display_view .= '<option value="'.$operator.'" '.$selected.'>'.$operator.'</option>';
											}
										$display_view .='</select>
									</div>
									<div class="col s2">
										<input type="text" '.$disabled.' name="where_value[]" id="where_value" value="'.$value['where_value'].'"  placeholder="Enter where value" value="" class="browser-default left validate input-sm" >
									</div>';
									if($action == ACTION_EDIT)
									{
										$display_view .=
										'<div class="col s1">
											<a class="btn btn-success left" name="add_table" id="add_table" onclick="add_where()"><i class="flaticon-add175"></i>Where</a>
										</div>';
									}
							$display_view .='</div>';
						}
						else
						{
							$display_view .=
							'<div class = "row" id="where_condition_div_'.$cond_counter.'">
								<div class="col s2">
									<select name="where_condition[]" '.$disabled.' placeholder="Condition" type="text" class="selectize validate" required="true">
										<option value=""></option>';
										if($value['where_condition'] == 'AND')
										{
											$selected_and = 'selected';
											$selected_or = '';
										}
										else
										{
											$selected_and = '';
											$selected_or = 'selected';
										}
										$display_view .= '<option value="AND" '.$selected_and.'>AND</option>
										<option value="OR" '.$selected_or.'>OR</option>
									</select>
								</div>
								<div class="col s3">
									<select name="where_field[]" '.$disabled.' placeholder="Select Fields" type="text" class="selectize validate where_field" required="true">
										<option value=""></option>';
										foreach ($fields as $col)
										{
											$selected = '';
											if($value['where_field'] == $col['column_name'])
												$selected = 'selected';
											$display_view .= '<option value="'.$col['column_name'].'" '.$selected.'>'.$col['column_name'].'</option>';
										}
									$display_view .= '</select>
								</div>
								<div class="col s2">
									<select name="where_operator[]" '.$disabled.' placeholder="Operator" type="text" class="selectize validate" required="true" data-parsley-trigger="change">
										<option value=""></option>';
										foreach ($operators as $operator)
										{
											$selected = '';
											if($value['where_operator'] == $operator)
												$selected = 'selected';
											$display_view .= '<option value="'.$operator.'" '.$selected.'>'.$operator.'</option>';
										}
									$display_view .='</select>
								</div>
								<div class="col s2">
									<input type="text" name="where_value[]" '.$disabled.' value="'.$value['where_value'].'" placeholder="Enter where value" class="form-control input-sm" required="true">
								</div>';
								if($action == ACTION_EDIT)
								{
									$display_view .=
									'<div class="col s1">
										<a onclick="delete_condition(this)" id="delete_condition_'.$cond_counter.'" title="Delete Condition" class="btn btn-xs default"><i class="Small flaticon-minus102"></i></a>
									</div>';
								}
							$display_view .='</div>';
						}

						$cond_counter++;
					}
					$display_view .= '</div>';

				}

				$data['sel_option']		= $sel_option;
				$data['drop_down']		= $drop_down;
				$data['dd_where']		= $dd_where;
				$data['display_view']	= $display_view;
				$data['cond_counter']	= $cond_counter;
				$data['salt']			= $salt;
				$data['token'] 			= $token;
				$data['id']				= $id;

			}

			$data['action_id']		= $action;
			$status 				= TRUE;
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
		
		$this->load->view('code_library/modals/modal_dropdown', $data);
		$this->load_resources->get_resource($resources);
	}
	/*----- END MODAL SYSTEM -----*/
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_system.php */