<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends Main_Controller {
	private $module = MODULE_PAYROLL_VOUCHER;

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
			$resources['datatable'][]	= array('table_id' => 'voucher_table', 'path' => 'main/code_library_payroll/voucher/get_voucher_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_voucher' 		=> array(
					'controller'		=> 'code_library_payroll/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_voucher',
					'multiple'			=> true,
					'height'			=> '150px',
					'size'				=> 'sm',
					'title'				=> 'Voucher'
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

		$this->load->view('code_library/tabs/voucher', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_voucher_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("voucher_name", "IF(active_flag = 'Y', 'Active', 'Inactive')");
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
			$valid_data 			= $this->_validate_data_voucher($params);

			//SET FIELDS VALUE
			$fields['voucher_name'] = $valid_data['voucher_name'];
			$fields['active_flag']	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 					= $this->cl->tbl_param_voucher;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);	

				//SET AUDIT TRAIL DETAILS
				$audit_table[]		= $this->cl->tbl_param_voucher;
				$audit_schema[]		= DB_MAIN;
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
				$key 			= $this->get_hash_key('voucher_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_voucher;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
								
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
			"path"				=> PROJECT_MAIN . '/code_library_payroll/voucher/get_voucher_list/',
			"advanced_filter"	=> true
		);
	
		echo json_encode($info);
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
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */