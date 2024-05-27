<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_BANK_BRANCH;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     = array();
			$resources                = array();
			$data['action_id']        = $action_id;
			$resources['load_css'][]  = CSS_DATATABLE;
			$resources['load_js'][]   = JS_DATATABLE;
			$resources['datatable'][] = array('table_id' => 'bank_table', 'path' => 'main/code_library_payroll/bank/get_bank_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_bank' 			=> array(
					'controller'		=> 'code_library_payroll/bank',
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_bank',
					'multiple'			=> true,
					'height'			=> '400px',
					'size'				=> 'sm',
					'title'				=> 'Bank'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_payroll/bank',
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

		$this->load->view('code_library/tabs/bank', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_bank_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("A.*", "B.fund_source_name");
			$bColumns					= array("A.bank_name", "A.branch_code", "A.account_no", "B.fund_source_name", "IF(A.active_flag = 'Y', 'Active', 'Inactive')");
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

				$row[] = strtoupper($aRow['bank_name']);
				$row[] = strtoupper($aRow['branch_code']);
				$row[] = strtoupper($aRow['account_no']);
				$row[] = strtoupper($aRow['fund_source_name']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

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

	public function modal_bank($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_fund_sources;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['fund_source_id']  	= array($bank_info['fund_source_id'], array("=", ")"));				
			}
			$data['fund_source_name'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);
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
			// print_r($params);
			// die();
			// SERVER VALIDATION
			$valid_data 				= $this->_validate_data_bank($params);
			
			//SET FIELDS VALUE
			$fields['bank_name']  		= $valid_data['bank_name'];
			$fields['branch_code']  	= $valid_data['branch_code'];
			$fields['account_no']  		= $valid_data['account_no'];
			$fields['fund_source_id']  	= $valid_data['fund_source'];
			$fields['active_flag']  	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_banks;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_banks;
				$audit_schema[]	= DB_MAIN;
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
				$key 			= $this->get_hash_key('bank_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_personnel_movements;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
								
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
			"path"				=> PROJECT_MAIN . '/code_library_payroll/bank/get_bank_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_bank($params)
	{
		$fields                 = array();
		$fields['bank_name']  	= "Bank Name";
		$fields['branch_code'] 	= "Branch Code";
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
			$validation ['branch_code'] = array (
					'data_type' 		=> 'string',
					'name'				=> 'Branch Code',
					'max_len' 			=> 10 
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
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */