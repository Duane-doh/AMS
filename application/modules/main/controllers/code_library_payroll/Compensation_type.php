<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compensation_type extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_COMPENSATION_TYPE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     		= array();
			$resources                		= array();
			$data['action_id']        		= $action_id;
			$resources['load_css'][]  		= CSS_DATATABLE;
			$resources['load_js'][]   		= JS_DATATABLE;
			$resources['datatable'][] 		= array('table_id' => 'compensation_type_table', 'path' => 'main/code_library_payroll/compensation_type/get_compensation_type_list', 'advanced_filter' => TRUE);
			$resources['load_modal']  		= array(
				'modal_compensation_type' 	=> array(
					'controller'			=> 'code_library_payroll/compensation_type',
					'module'				=> PROJECT_MAIN,
					'method'				=> 'modal_compensation_type',
					'multiple'				=> true,
					'height'				=> '400px',
					'size'					=> 'md',
					'title'					=> 'Compensation Type'
				),
				'modal_compensation_type_payroll' 	=> array(
						'controller'			=> 'code_library_payroll/compensation_type_payroll',
						'module'				=> PROJECT_MAIN,
						'method'				=> 'modal_compensation_type_payroll',
						'multiple'				=> true,
						'height'				=> '550px',
						'size'					=> 'sm',
						'title'					=> 'Payroll Payout'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_payroll/compensation_type',
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

		$this->load->view('code_library/tabs/compensation_type', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_compensation_type_list()
	{
		try
		{
			$params 					= get_params();
			
			$aColumns					= array("compensation_id", "compensation_name", "compensation_code", "IF(active_flag = 'Y', 'Active', 'Inactive') as active_flag", "compensation_type_flag");
			$bColumns					= array("compensation_name", "compensation_code", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$compensation 				= $this->cl->get_compensation_list($aColumns, $bColumns, $params);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(compensation_id)) AS count"), $this->cl->tbl_param_compensations, NULL, false);
			$iFilteredTotal 			= $this->cl->compensation_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);

			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_COMPENSATION_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_COMPENSATION_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_COMPENSATION_TYPE, ACTION_DELETE);
			$permission_assign 			= $this->permission->check_permission(MODULE_PAYROLL_CL_COMPENSATION_TYPE, ACTION_ASSIGN);

			$cnt = 0;
			foreach ($compensation as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
				
				$compensation_id 		= $aRow["compensation_id"];
				$id 					= $this->hash($compensation_id);
				$salt 					= gen_salt();
				$token_assign 			= in_salt($id . '/' . ACTION_ASSIGN, $salt);
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$assign_action 			= ACTION_ASSIGN . "/". $id . "/" . $salt  . "/" . $token_assign;
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("compensation type", "'.$url_delete.'")';
			
				$row[]	= strtoupper($aRow['compensation_name']);
				$row[]	= strtoupper($aRow['compensation_code']);
				$row[] 	= strtoupper($aRow['active_flag']);

				if ($aRow['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_FIXED) 
				{ 
					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_compensation_type' onclick=\"modal_compensation_type_init('".$view_action."')\"></a>";
					if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_compensation_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_init('".$edit_action."')\"></a>";
					if($permission_assign)
					$action .= "<a href='#!' class='apply tooltipped md-trigger' data-modal='modal_compensation_type_payroll' data-tooltip='Assign Payroll Payout' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_payroll_init('".$assign_action."')\"></a>";
					if($permission_delete)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				} 

				if ($aRow['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE) 
				{
					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_compensation_type' onclick=\"modal_compensation_type_init('".$view_action."')\"></a>";
					if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_compensation_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_init('".$edit_action."')\"></a>";
					if($permission_assign)
					$action .= "<a href='#!' class='apply tooltipped md-trigger' data-modal='modal_compensation_type_payroll' data-tooltip='Assign Payroll Payout' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_payroll_init('".$assign_action."')\"></a>";
					if($permission_delete)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				} 

				if ($aRow['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_SYSTEM) 
				{
					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_compensation_type' onclick=\"modal_compensation_type_init('".$view_action."')\"></a>";
					if($permission_assign)
					$action .= "<a href='#!' class='apply tooltipped md-trigger' data-modal='modal_compensation_type_payroll' data-tooltip='Assign Payroll Payout' data-position='bottom' data-delay='50' onclick=\"modal_compensation_type_payroll_init('".$assign_action."')\"></a>";
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

	public function modal_compensation_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
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

			$data['action_id'] 			= $action;
			$data['nav_page']			= CODE_LIBRARY_COMPENSATION;
			$data['action'] 			= $action;
			$data['salt'] 				= $salt;
			$data['token'] 				= $token;
			$data['id'] 				= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_compensations;
				$where						= array();
				$key 						= $this->get_hash_key('compensation_id');
				$where[$key]				= $id;
				$compensation_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				$data['compensation_info']	= $compensation_info;

				$resources['single'] 		 = array(
					'multiplier' 			 => $data['compensation_info']['multiplier_id'],
					'frequency' 			 => $data['compensation_info']['frequency_id'],
					'parent_compensation' 	 => $data['compensation_info']['parent_compensation_id'],
					'inherit_parent_id_flag' => $data['compensation_info']['inherit_parent_id_flag']
				);
			}

			
			$where_multiplier             	= '';
			if($action == ACTION_ADD)
			{
				$where_multiplier			= "AND active_flag = 'Y'";			
			}
			else
			{
				if(!EMPTY($compensation_info['multiplier_id']))
				$where_multiplier			= "AND (active_flag = 'Y' OR multiplier_id = " . $compensation_info['multiplier_id'] . ")";
			}	
			$data['multiplier_name'] 		= $this->cl->get_multiplier_list($where_multiplier);

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_frequencies;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				if(!EMPTY($compensation_info['frequency_id']))
				{
					$where['active_flag'] 		= array(YES, array("=", "OR", "("));
			 		$where['frequency_id']  	= array($compensation_info['frequency_id'], array("=", ")"));	
				}			
			}	
			$where['compensation_flag']		= YES;
			$data['frequency_name'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_compensations;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['compensation_id']  	= array($compensation_info['compensation_id'], array("=", ")"));				
			}	
			$where['parent_compensation_id']= 'IS NULL';
			$where['basic_salary_flag']		= NO;
			$data['parent_compensation'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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
			$valid_data 						= $this->_validate_data_compensation_type($params);

			//SET FIELDS VALUE
			$fields 							= array();
			$fields['compensation_name']		= $valid_data['compensation_name'];
			$fields['compensation_code'] 		= $valid_data['compensation_code'];			
			$fields['tenure_rqmt_flag'] 		= $valid_data['tenure_rqmt_flag'];
			$fields['report_short_code'] 		= !EMPTY($valid_data['report_short_code']) ? $valid_data['report_short_code'] : NULL;
			$fields['employ_type_flag'] 		= ISSET($valid_data['employ_type_flag'])? $valid_data['employ_type_flag'] : PAYROLL_TYPE_FLAG_ALL;
			$fields['inherit_parent_id_flag'] 	= EMPTY($valid_data['inherit_parent_id_flag']) ? NOT_APPLICABLE : $valid_data['inherit_parent_id_flag'];
			$fields['compensation_type_flag']  	= isset($valid_data['compensation_type_flag']) ? COMPENSATION_TYPE_FLAG_VARIABLE : COMPENSATION_TYPE_FLAG_FIXED;
			$fields['deminimis_flag'] 			= isset($valid_data['deminimis_flag']) ? "Y" : "N";
			$fields['general_payroll_flag'] 	= $valid_data['general_payroll_flag'];
			$fields['taxable_flag'] 			= isset($valid_data['taxable_flag']) ? "Y" : "N";
			$fields['basic_salary_flag'] 		= isset($valid_data['basic_salary_flag']) ? "Y" : "N";
			$fields['monetization_flag'] 		= isset($valid_data['monetization_flag']) ? "Y" : "N";
			$fields['taxable_flag'] 			= isset($valid_data['taxable_flag']) ? "Y" : "N";
			$fields['special_payroll_flag'] 	= $valid_data['special_payroll_flag'];
			$fields['less_absence_flag'] 		= isset($valid_data['less_absence_flag']) ? "Y" : "N";
			$fields['active_flag']  			= isset($valid_data['active_flag']) ? "Y" : "N";
			$fields['employee_flag']  			= isset($valid_data['employee_flag']) ? "Y" : "N";
			$fields['taxable_amount'] 			= isset($valid_data['taxable_amount']) ? $valid_data['taxable_amount'] : "0.00";
			$fields['parent_compensation_id'] 	= EMPTY($valid_data['parent_compensation']) ? NULL : $valid_data['parent_compensation'];
			$fields['tenure_rqmt_val'] 			= $valid_data['tenure_rqmt_val'];
			$fields['multiplier_id'] 			= ($fields['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE) ? $valid_data['multiplier'] : NULL;
			$fields['rate'] 					= ($fields['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE) ? $valid_data['rate'] : NULL;
			$fields['amount'] 					= ($fields['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_FIXED) ? $valid_data['amount'] : NULL;
			$fields['frequency_id'] 			= ($fields['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_FIXED) ? $valid_data['frequency'] : NULL;
			//$fields['other_salary_flag'] 		= isset($valid_data['other_salary_flag']) ? "Y" : "N";
			
			if(($valid_data['special_payroll_flag']  == 'N') AND ($valid_data['general_payroll_flag'] == 'N'))
			{
				throw new Exception("General Payroll flag and Special Payroll flag can't be both <b>No</b>");
			}

			// SET FIELDS TO AUDIT TRAIL
			$audit_fields 							= array();
			$audit_fields['compensation_name']		= $valid_data['compensation_name'];
			$audit_fields['compensation_code'] 		= $valid_data['compensation_code'];			
			$audit_fields['tenure_rqmt_flag'] 		= $valid_data['tenure_rqmt_flag'];
			$audit_fields['inherit_parent_id_flag'] = $valid_data['inherit_parent_id_flag'];
			$audit_fields['report_short_code'] 		= !EMPTY($valid_data['report_short_code']) ? $valid_data['report_short_code'] : ' ';
			$audit_fields['employ_type_flag'] 		= ISSET($valid_data['employ_type_flag'])? $valid_data['employ_type_flag'] : PAYROLL_TYPE_FLAG_ALL;
			$audit_fields['compensation_type_flag'] = isset($valid_data['compensation_type_flag']) ? COMPENSATION_TYPE_FLAG_VARIABLE : COMPENSATION_TYPE_FLAG_FIXED;
			$audit_fields['deminimis_flag'] 		= isset($valid_data['deminimis_flag']) ? "Y" : "N";
			$audit_fields['general_payroll_flag'] 	= $valid_data['general_payroll_flag'];
			$audit_fields['taxable_flag'] 			= isset($valid_data['taxable_flag']) ? "Y" : "N";
			$audit_fields['basic_salary_flag'] 		= isset($valid_data['basic_salary_flag']) ? "Y" : "N";
			$audit_fields['monetization_flag'] 		= isset($valid_data['monetization_flag']) ? "Y" : "N";
			$audit_fields['taxable_flag'] 			= isset($valid_data['taxable_flag']) ? "Y" : "N";
			$audit_fields['special_payroll_flag'] 	= $valid_data['special_payroll_flag'];
			$audit_fields['less_absence_flag'] 		= isset($valid_data['less_absence_flag']) ? "Y" : "N";
			$audit_fields['active_flag']  			= isset($valid_data['active_flag']) ? "Y" : "N";
			$audit_fields['employee_flag']  		= isset($valid_data['employee_flag']) ? "Y" : "N";
			$audit_fields['taxable_amount'] 		= isset($valid_data['taxable_amount']) ? $valid_data['taxable_amount'] : "0.00";
			$audit_fields['parent_compensation_id'] = EMPTY($valid_data['parent_compensation']) ? NULL : $valid_data['parent_compensation'];
			$audit_fields['tenure_rqmt_val'] 		= $valid_data['tenure_rqmt_val'];
			$audit_fields['multiplier_id'] 			= $valid_data['multiplier'];
			$audit_fields['rate'] 					= $valid_data['rate'];
			$audit_fields['amount'] 				= $valid_data['amount'];
			$audit_fields['frequency_id'] 			= $valid_data['frequency'];

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			 	= $this->cl->tbl_param_compensations;	

			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_compensations;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($audit_fields);
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
				$curr_detail[]  = array($audit_fields);
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
			"path"				=> PROJECT_MAIN . '/code_library_payroll/compensation_type/get_compensation_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_compensation_type($params)
	{
		$fields                  			= array();
		$fields['compensation_name']  		= "Compensation Name";
		$fields['compensation_code']  		= "Compensation Code";
		$fields['report_short_code']  		= "Report Short Code";

		if($params['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_FIXED)
		{			
			$fields['amount']  				= "Amount";
			$fields['frequency_id']  		= "Frequency";
		}
		if($params['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE)
		{			
			$fields['multiplier']  			= "Multiplier";
			$fields['rate']  				= "Rate";
		}
		if ($params['tenure_rqmt_flag'] != TENURE_RQMT_NA)
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
			$validation ['report_short_code'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Report Short Code',
					'max_len' 					=> 5
			);
			$validation ['amount'] 					= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Amount',
					'max_len' 						=> 45 
			);
			$validation ['taxable_amount'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Taxable Amount',
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
					'max_len' 						=> 11 
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
			$validation ['employ_type_flag'] = array (
					'data_type' 				=> 'string',
					'name' 						=> 'Payroll Type',
					'max_len' 					=> 3
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
			// $validation ['other_salary_flag'] 		= array (
			// 		'data_type' 					=> 'string',
			// 		'name' 							=> 'Other Salary Flag',
			// 		'max_len' 						=> 1 
			// );
			$validation ['monetization_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Monetization Flag',
					'max_len' 						=> 1
			);
			$validation ['taxable_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Taxable Flag',
					'max_len' 						=> 1
			);
			$validation ['less_absence_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Less Absence Flag',
					'max_len' 						=> 1
			);
			$validation ['special_payroll_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Special Payroll Flag',
					'max_len' 						=> 1
			);
			$validation ['tenure_rqmt_val'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Tenure Requirement Value',
					'max_len' 						=> 11
			);
			$validation ['pro_rated_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Pro Rated Flag',
					'max_len' 						=> 2
			);
			$validation ['inherit_parent_id_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Inherit Parent Code Flag',
					'max_len' 						=> 2
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Compensation_type.php */
/* Location: ./application/modules/main/controllers/code_library_payroll/Compensation_type.php */