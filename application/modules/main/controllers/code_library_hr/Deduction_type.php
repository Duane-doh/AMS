<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deduction_type extends Main_Controller {
	private $module = MODULE_HR_CL_DEDUCTION_TYPE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                       = array();
			$resources                  = array();
			$data['action_id']          = $action_id;
			$resources['load_css'][] 	= CSS_DATATABLE;
			$resources['load_js'][] 	= JS_DATATABLE;
			$resources['datatable'][]	= array('table_id' => 'deduction_type_table', 'path' => 'main/code_library_hr/' . __CLASS__ .'/get_deduction_type_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_deduction_type'	=> array(
					'controller'		=> 'code_library_hr/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_deduction_type',
					'multiple'			=> true,
					'height'			=> '420px',
					'size'				=> 'lg',
					'title'				=> 'Deduction Type'
				)
			);
			$resources['load_delete'] 	= array(
				'code_library_hr/'.__CLASS__,
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

		$this->load->view('code_library/tabs/deduction_type', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function get_deduction_type_list()
	{

		try
		{
			$params 				 	= get_params();
			$aColumns		 			= array("A.deduction_id", "A.deduction_code", "A.deduction_name", "IF(A.active_flag = 'Y', 'Active', 'Inactive') as active_flag", "B.multiplier_name", "C.frequency_name", "D.remittance_type_name");
			$bColumns		 			= array("deduction_code", "deduction_name", "IF(A.active_flag = 'Y', 'Active', 'Inactive')");
			$deduction_types 			= $this->cl->get_deduction_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(deduction_id)) AS count"), $this->cl->tbl_param_deductions, NULL, false);
			$iFilteredTotal 			= $this->cl->deduction_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho"				 	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
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
				
				$id 					= $this->hash($deduction_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("deduction type", "'.$url_delete.'")';
				
				$row[] = strtoupper($aRow['deduction_code']);
				$row[] = strtoupper($aRow['deduction_name']);
				$row[] = strtoupper($aRow['active_flag']);

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

	public function modal_deduction_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE, JS_NUMBER);
			
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

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_deductions;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['deduction_id']  	= array($deduction_info['deduction_id'], array("=", ")"));				
			}	
			$where['parent_deduction_id'] 	= 'IS NULL';
			$data['parent_deduction'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);
			
			//DROPDOWN
			$where_multiplier             	= '';
			if($action == ACTION_ADD)
			{
				$where_multiplier			= "AND active_flag = 'Y'";			
			}
			else
			{
				if(!EMPTY($deduction_info['multiplier_id']))
				$where_multiplier			= "AND (active_flag = 'Y' OR multiplier_id = " . $deduction_info['multiplier_id'] . ")";
			}	
			$data['multiplier_name'] 		= $this->cl->get_multiplier_list($where_multiplier);

			$field                      	= array("*") ;
			$table                      	= $this->cl->tbl_param_frequencies;
			$where                      	= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['frequency_id']  	= array($deduction_info['frequency_id'], array("=", ")"));				
			}	
			$where['deduction_flag']		= YES;
			$data['frequency_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);
			
			$field                       	= array("*") ;
			$table                       	= $this->cl->tbl_param_remittance_types;
			$where                       	= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['remittance_type_id']= array($deduction_info['remittance_type_id'], array("=", ")"));				
			}	
			$data['remittance_type_name'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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

	public function process()
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
			$fields['employ_type_flag'] 		= ISSET($valid_data['employ_type_flag'])? $valid_data['employ_type_flag'] : PAYROLL_TYPE_FLAG_ALL;
			$fields['frequency_id'] 			= $valid_data['frequency'];
			$fields['month_pay_num'] 			= ISSET($valid_data['deduction_month'])? $valid_data['deduction_month'] : 0;
			$fields['employee_flag']    		= isset($valid_data['employee_flag']) ? "Y" : "N";
			$fields['remittance_type_id']    	= $valid_data['remittance_type'];
			$fields['priority_num']    			= !EMPTY($valid_data['priority_num']) ? $valid_data['priority_num'] : 0;
			$fields['statutory_flag']    		= isset($valid_data['statutory_flag']) ? "Y" : "N";
			$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";
			$fields['employer_share_flag']    	= isset($valid_data['employer_share_flag']) ? "Y" : "N";
			$fields['amount'] 					= ($valid_data['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_FIXED) ? $valid_data['amount'] : NULL;
			$fields['rate'] 					= ($valid_data['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_VARIABLE) ? $valid_data['rate'] : NULL;
			$fields['multiplier_id'] 			= ($valid_data['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_VARIABLE) ? $valid_data['multiplier'] : NULL;
			
			
			// SET FIELDS TO AUDIT TRAIL
			$audit_fields 							= array();
			$audit_fields['deduction_name'] 		= $valid_data['deduction_name'];
			$audit_fields['deduction_code'] 		= $valid_data['deduction_code'];
			$audit_fields['deduction_type_flag'] 	= $valid_data['deduction_type_flag'];
			$audit_fields['employ_type_flag'] 		= ISSET($valid_data['employ_type_flag'])? $valid_data['employ_type_flag'] : PAYROLL_TYPE_FLAG_ALL;
			$audit_fields['frequency_id'] 			= $valid_data['frequency'];
			$audit_fields['month_pay_num'] 			= ISSET($valid_data['deduction_month'])? $valid_data['deduction_month'] : 0;
			$audit_fields['employee_flag']    		= isset($valid_data['employee_flag']) ? "Y" : "N";
			$audit_fields['remittance_type_id']    	= $valid_data['remittance_type'];
			$audit_fields['priority_num']    		= $valid_data['priority_num'];
			$audit_fields['statutory_flag']    		= isset($valid_data['statutory_flag']) ? "Y" : "N";
			$audit_fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";
			$audit_fields['employer_share_flag']    = isset($valid_data['employer_share_flag']) ? "Y" : "N";
			$audit_fields['amount'] 				= $valid_data['amount'];
			$audit_fields['rate'] 					= $valid_data['rate'];
			$audit_fields['multiplier_id'] 			= $valid_data['multiplier'];

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_deductions;	
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$deduction_id 	= $this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_deductions;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($audit_fields);
				$audit_action[] = AUDIT_INSERT;	
				
				//INSERT DATA INTO OTHER DEDUCTION DETAILS
				for ($x = 0; $x<count($valid_data['other_detail_name']); $x++ ) {

					$fields 							= array();
					$fields['deduction_id']    			= $deduction_id ;
					$fields['other_detail_name']    	= $valid_data['other_detail_name'][$x];
					$fields['other_detail_type']    	= $valid_data['other_detail_type'][$x];
					$fields['dropdown_flag']    		= !EMPTY($valid_data['dropdown_flag'][$x]) ? $valid_data['dropdown_flag'][$x] : NULL;
					$fields['pk_flag']    				= isset($valid_data['pk_flag'][$x]) ? "Y" : "N";
					$fields['required_flag']    		= isset($valid_data['required_flag'][$x]) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_other_deduction_details, $fields);
				}
				
				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{
				$table 			= $this->cl->tbl_param_deductions;
				$where			= array();
				$key 			= $this->get_hash_key('deduction_id');
				$where[$key]	= $params['id'];
				$deduction_info = $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('deduction_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous	    = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_deductions;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($audit_fields);
				$audit_action[] = AUDIT_UPDATE;

				// DELETE OTHER DEDUCTION DETAILS
				$where			= array();
				$key 			= $this->get_hash_key('deduction_id');
				$where[$key]	= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_other_deduction_details, $where);

				//EDIT DATA INTO OTHER DEDUCTION DETAILS
				for ($x = 0; $x<count($valid_data['other_detail_name']); $x++ ) {

					$fields 							= array();
					$fields['deduction_id']    			= $deduction_info['deduction_id'];
					$fields['other_detail_name']    	= $valid_data['other_detail_name'][$x];
					$fields['other_detail_type']    	= $valid_data['other_detail_type'][$x];
					$fields['dropdown_flag']    		= !EMPTY($valid_data['dropdown_flag'][$x]) ? $valid_data['dropdown_flag'][$x] : NULL;
					$fields['pk_flag']    				= isset($valid_data['pk_flag'][$x]) ? "Y" : "N";
					$fields['required_flag']    		= isset($valid_data['required_flag'][$x]) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_other_deduction_details, $fields);
				}

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
			"path"				=> PROJECT_MAIN . '/code_library_hr/deduction_type/get_deduction_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_deduction_type($params)
	{
		$fields                  		= array();
		$fields['deduction_name']  		= "Deduction Name";
		$fields['deduction_code']  		= "Deduction Code";
		$fields['deduction_type_flag'] 	= "Deduction Type";
		$fields['employ_type_flag'] 	= "Payroll Type";
		$fields['frequency']  			= "Frequency";
		if($params['has_ded_mon'] == 1)
		{
			$fields['deduction_month']  	= "Deduction Month";
		}
		$fields['remittance_type']  	= "Remittance Type";

		if($params['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_FIXED)
			$fields['amount']  		= "Amount";

		if($params['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_VARIABLE)
		{
			$fields['multiplier']  	= "Multiplier";
			$fields['rate']  		= "Rate";

		}
		$this->check_required_fields($params, $fields);	

		return $this->_validate_deduction_type_input($params);
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
			$validation ['employ_type_flag'] = array (
					'data_type' 				=> 'string',
					'name' 						=> 'Payroll Type',
					'max_len' 					=> 3
			);
			$validation ['frequency'] 			= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Frequency',
					'max_len' 					=> 50 
			);
			if($params['has_ded_mon'] == 1)
			{
				$validation ['deduction_month'] 	= array (
						'data_type' 				=> 'string',
						'name' 						=> 'Deduction Month',
						'max_len' 					=> 50
				);
			}
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
			$validation ['statutory_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Statutory Flag',
					'max_len' 					=> 1
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1 
			);	
			$validation ['employer_share_flag']	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Employer Share Flag',
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
					'max_len' 					=> 3
			);
			$validation ['rate'] 				= array (
					'data_type' 				=> 'amount',
					'name' 						=> 'Rate'
			);
			$validation ['amount'] 				= array (
					'data_type' 				=> 'amount',
					'name' 						=> 'Amount'
			);		
			$validation ['priority_num'] 		= array (
					'data_type' 				=> 'digit',
					'name' 						=> 'Priority Number',
					'max_len' 					=> 11
			);
			

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	public function array_frequency_id_limit($frequency)
	{
		$new_array = array();
			foreach($frequency as $freq)
			{
				$new_array[$freq['frequency_id']] = $freq['limit_month_num']; 
			}
		
		return $new_array;
	}
	
	public function get_months_limit()
	{
		try
		{
			$flag 			= 0;
			$msg			= "";
			$options 		= array();
			$params 		= get_params();
				
			$field                      = array('limit_month_num') ;
			$table                      = $this->cl->tbl_param_frequencies;
			$where                      = array();
			$where['frequency_id']		= $params['frequency_id'];
			$result					 	= $this->cl->get_code_library_data($field, $table, $where, FALSE);
			$limit						= $result['limit_month_num'];
			
			if(!EMPTY($limit))
			{
				$months = array(
						1  =>'January',
						2  =>'February',
						3  =>'March',
						4  =>'April',
						5  =>'May',
						6  =>'June',
						7  =>'July',
						8  =>'August',
						9  =>'September',
						10 =>'October',
						11 =>'November',
						12 =>'December'
				);
				
				$options[] = array(
						'id' => "",
						'name' => "Select Pay Month",
				);
				$ctr = 0;
				foreach($months as $key=>$mon)
				{
					$options[] = array(
							'id' => $key,
							'name' => $mon,
					);
					if($key == $limit) break;
				}
			}else{
				$options[] = array(
						'id' => "",
						'name' => "",
				);
			}
			
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
		
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
	
		echo json_encode( $info );
	}

}

/* End of file Deduction_type.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Deduction_type.php */