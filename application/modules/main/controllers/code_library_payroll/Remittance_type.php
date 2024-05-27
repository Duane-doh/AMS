<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remittance_type extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_REMITTANCE_TYPE;

	private $view_permission;
	private $add_permission;
	private $edit_permission;
	private $delete_permission;
	private $assign_permission;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
		
		$this->view_permission 		= $this->permission->check_permission($this->module, ACTION_VIEW);
		$this->add_permission 		= $this->permission->check_permission($this->module, ACTION_ADD);
		$this->edit_permission 		= $this->permission->check_permission($this->module, ACTION_EDIT);
		$this->delete_permission 	= $this->permission->check_permission($this->module, ACTION_DELETE);
		$this->assign_permission 	= $this->permission->check_permission($this->module, ACTION_ASSIGN);
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			if( EMPTY($this->view_permission)) 
			{
				throw new Exception ( $this->lang->line( 'err_unauthorized_access' ) );
			}
			$data                    	= array();
			$resources                	= array();
			$data['action_id']        	= $action_id;
			$resources['load_css'][]  	= CSS_DATATABLE;
			$resources['load_js'][]   	= JS_DATATABLE;
			$resources['datatable'][]	= array('table_id' => 'remittance_type_table', 'path' => 'main/code_library_payroll/remittance_type/get_remittance_type_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_remittance_type' => array(
					'controller'		=> 'code_library_payroll/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_remittance_type',
					'multiple'			=> true,
					'height'			=> '280px',
					'size'				=> 'sm',
					'title'				=> 'Remittance Type'
				),
				'modal_remittance_type_deduction' => array(
					'controller'		=> 'code_library_payroll/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_remittance_type_deduction',
					'multiple'			=> true,
					'height'			=> '470px',
					'size'				=> 'sm',
					'title'				=> 'Remittance Type - Deductions'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_payroll/'.__CLASS__,
				'delete',
				PROJECT_MAIN
			);
			
			$data['action_id'] = $action_id;
			
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

		$this->load->view('code_library/tabs/remittance_type', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_remittance_type_list()
	{

		try
		{
			$params             		= get_params();
			
			$aColumns           		= array("*");
			$bColumns           		= array("remittance_type_name", "IF(active_flag = 'Y', 'Active', 'Inactive')");
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
				$token_assign 			= in_salt($id . '/' . ACTION_ASSIGN, $salt);
				$token_delete          	= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action           	= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action           	= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;
				$assign_action 			= ACTION_ASSIGN . "/". $id . "/" . $salt  . "/" . $token_assign;
				$url_delete            	= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action         	= 'content_delete("remittance type", "'.$url_delete.'")';
				
				$row[] 					= strtoupper($aRow['remittance_type_name']);
				$row[] 					= strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				if($this->view_permission)
				{
					$action 	.= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_remittance_type' onclick=\"modal_remittance_type_init('".$view_action."')\"></a>";
				}
				if($this->edit_permission) 
				{
					$action 	.= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_remittance_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_remittance_type_init('".$edit_action."')\"></a>";
				}
				if($this->assign_permission)
				{
					$action 	.= "<a href='#!' class='apply tooltipped md-trigger' data-modal='modal_remittance_type_deduction' data-tooltip='Assign Deduction' data-position='bottom' data-delay='50' onclick=\"modal_remittance_type_deduction_init('".$assign_action."')\"></a>";
				}
				if($this->delete_permission)
				{
					$action 	.= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action 			.= "</div>";
				
				$row[] 				= $action;
					
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

	public function modal_remittance_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE);

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id']				= $action;
			$data['nav_page']				= CODE_LIBRARY_REMITTANCE_TYPE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table 							= $this->cl->tbl_param_remittance_types;
				$where							= array();
				$key 							= $this->get_hash_key('remittance_type_id');
				$where[$key]					= $id;
				$data['remittance_type_info'] 	= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);
				$resources['single']			= array(
					'remittance_payee' 			=> $data['remittance_type_info']['remittance_payee_id']
				);
			}

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_remittance_payees;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;			
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['remittance_payee_id']	= array($data['remittance_type_info']['remittance_payee_id'], array("=", ")"));				
			}
			$data['remittance_payee'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);
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
		$this->load_resources->get_resource($resources);
	}
	public function modal_remittance_type_deduction($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE);
			
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			$data['action_id']				 	= $action;
			$data['nav_page']					= CODE_LIBRARY_REMITTANCE_TYPE;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			if(!EMPTY($id))
			{
				//EDIT
				$table 								= $this->cl->tbl_param_remittance_types;
				$where								= array();
				$key 								= $this->get_hash_key('remittance_type_id');
				$where[$key]						= $id;
				$data['remittance_type_info'] 		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);
				
			}
			$remittance_type_id					= $data['remittance_type_info']['remittance_type_id'];
			
			//get all active deduction types
			$table 								= $this->cl->tbl_param_deductions;
			$where								= array();
			$key 								= 'active_flag';
			$where[$key]						= 'Y';
			$fields								= array("deduction_id","deduction_name");
			$deduction_types 					= $this->cl->get_code_library_data($fields, $table, $where,TRUE);
			$counter = 0;
			foreach($deduction_types as $deduction_type) {
				$deduction_types[$counter]['deduction_id']	= $this->hash($deduction_type['deduction_id']);
				$counter++;
			}
			$data['deduction_types'] 				= $deduction_types;
			
			$val									= array(SYS_PARAM_TYPE_REMITTANCE_FILE_UPLOAD);
			$data['remittance_file_upload']			= $this->cl->get_remittance_file_uploads($val);
			
			//GET THE DEDUCTION TYPE ID OF REMITTANCE TYPE
			$val									= array($remittance_type_id);
			
			$remittance_deduction_types				= $this->cl->get_remittance_type_deduction($val);
			$data['remittance_type_deduction_file']	= $remittance_deduction_types[0]['file_name'];
			
			$deductions 							= array();
			
			foreach($remittance_deduction_types as $deduction) {
				$deduction_id    	= $deduction["deduction_id"];
				$deductions[]		= $this->hash($deduction_id);
			}
			$data['remittance_deductions']			= $deductions;
			$remittance_payee_id = $data['remittance_type_info']['remittance_payee_id'];
			if(!EMPTY($remittance_payee_id)) {
				$field 									= array("*") ;
				$table									= $this->cl->tbl_param_remittance_payees;
				$where									= array();
				$where['active_flag'] 					= array(YES, array("=", "AND", "("));
				$where['remittance_payee_id']			= array($remittance_payee_id, array("=", ")"));
				$data['remittance_payee'] 	  			= $this->cl->get_code_library_data($field, $table, $where, TRUE);
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
		
		$this->load->view('code_library/modals/modal_remittance_type_deduction', $data);
		$this->load_resources->get_resource($resources);
	}
	public function process()
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
// 			echo "<pre>";
// 			print_r($valid_data);
// 			die();
			//SET FIELDS VALUE
			$fields['remittance_type_name'] = $valid_data['remittance_type_name'];
			$fields['remittance_payee_id'] 	= !EMPTY($valid_data['remittance_payee']) ? $valid_data['remittance_payee'] : NULL;
			$fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 							= $this->cl->tbl_param_remittance_types;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				= $this->cl->tbl_param_remittance_types;
				$audit_schema[]				= DB_MAIN;
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
				//WHERE 
				$where						= array();
				$key 						= $this->get_hash_key('remittance_type_id');
				$where[$key]				= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  					= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);	

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				= $this->cl->tbl_param_remittance_types;
				$audit_schema[]				= DB_MAIN;
				$prev_detail[]  			= array($previous);
				$curr_detail[] 	 			= array($fields);
				$audit_action[] 			= AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 					= "%s has been updated";
				
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
	
	public function process_remittance_type_deduction()
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
			
			$table 							= $this->cl->tbl_param_remittance_types;
			$where							= array();
			$key 							= $this->get_hash_key('remittance_type_id');
			$where[$key]					= $params['id'];
			$remittance_types			 	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			
			$table 							= $this->cl->tbl_param_remittance_type_deductions;
			$where							= array();
			$key 							= $this->get_hash_key('remittance_type_id');
			$where[$key]					= $params['id'];
			
			// GET THE DETAIL FIRST BEFORE DELETING THE RECORD
			$prev_detail					= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			//delete remittance type deduction
			$this->cl->delete_code_library($table, $where);
			
			$counter						= 0;
			foreach($params['deduction_type'] as $deduction)
			{
				$fields							= array();
				$fields['remittance_type_id'] 	= $remittance_types[0]['remittance_type_id'];
				$fields['remittance_payee'] 	= !EMPTY($remittance_types[0]['remittance_payee_id']) ? $remittance_types[0]['remittance_payee_id']: NULL;
				$fields['file_name'] 			= $params['remittance_file_name'];
				$table 							= $this->cl->tbl_param_deductions;
				$where							= array();
				$key 							= $this->get_hash_key('deduction_id');
				$where[$key]					= $deduction;
				$deduction_id			 		= $this->cl->get_code_library_data(array("deduction_id"), $table, $where, FALSE);
				
				$fields['deduction_id']			= $deduction_id['deduction_id'];
				//INSERT DATA
				$table 							= $this->cl->tbl_param_remittance_type_deductions;
				$this->cl->insert_code_library($table, $fields, TRUE);
				
				//SET AUDIT TRAIL DETAILS
				$audit_table[]					= $this->cl->tbl_param_remittance_type_deductions;
				$audit_schema[]					= DB_MAIN;
				$prev_detail[]  				= array($prev_detail);
				$curr_detail[]  				= array($fields);
				$audit_action[] 				= AUDIT_INSERT;
				$couter++;
			}
			
			//MESSAGE ALERT
			$message 				= $this->lang->line('data_saved');
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 				= "%s has been added";
			
			$activity 				= sprintf($activity, $params['remittance_type_name']);
			
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
	public function delete()
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
	
		$info 	= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'remittance_type_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/remittance_type/get_remittance_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_remittance_type($params)
	{
		$fields                 			= array();
		$fields['remittance_type_name']  	= "Remittance Type Name";
// 		$fields['remittance_payee']  		= "Remittance Payee";

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

			$validation ['remittance_payee']	 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Remittance Payee',
					'max_len' 						=> 11 
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
	
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */