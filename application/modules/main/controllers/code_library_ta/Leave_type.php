<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leave_type extends Main_Controller {
	private $module = MODULE_TA_LEAVE_TYPE;
	private $sort_num_add;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
// 		$this->$sort_num_add=0;
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     = array();
			$resources                = array();
			$data['action_id']        = $action_id;
			$resources['load_css'] 		= array(CSS_DATATABLE,CSS_COLORPICKER);
			$resources['load_js']		= array(JS_DATATABLE, JS_CALENDAR, JS_CALENDAR_MOMENT, JS_COLORPICKER, JS_COLORGROUP);
			$resources['datatable'][]	= array('table_id' => 'leave_type_table', 'path' => 'main/code_library_ta/leave_type/get_leave_type_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_leave_type' 		=> array(
					'controller'		=> 'code_library_ta/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_leave_type',
					'multiple'			=> true,
					'height'			=> '420px',
					'size'				=> 'sm',
					'title'				=> 'Leave Type'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_ta/'.__CLASS__,
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

		$this->load->view('code_library/tabs/leave_type', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_leave_type_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("leave_type_name",
												"IF(deduct_bal_leave_type_id = '2', 'Vacation Leave', 'Not Applicable')",
												"IF(cert_flag = 'Y', 'Yes', 'No')",
												"IF(built_in_flag = 'Y', 'Yes', 'No')",
												"IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_leave_types;
			$where						= array();
			$leave_types 				= $this->cl->get_leave_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(leave_type_id)) AS count"), $this->cl->tbl_param_leave_types, NULL, false);
			$iFilteredTotal 			= $this->cl->leave_type_filtered_length($aColumns, $bColumns, $params, $table);
// 			$this->$sort_num_add 		= $iTotal["count"];
// 			print_r ( $iTotal );
// 			die ();
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_DELETE);

			$cnt = 0;
			foreach ($leave_types as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$leave_type_id 			= $aRow["leave_type_id"];
				$id 					= $this->hash ($leave_type_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view."/".$cnt ;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit."/".$cnt;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("leave type", "'.$url_delete.'")';
				

				$row[] = strtoupper($aRow['leave_type_name']);
				$row[] = ($aRow['deduct_bal_leave_type_id'] == 2) ? "VACATION LEAVE" : (($aRow['deduct_bal_leave_type_id'] == 1) ? "SICK LEAVE" : "NOT APPLICABLE");
				$row[] = strtoupper(($aRow['cert_flag'] == "Y") ? "YES":"NO");
				$row[] = strtoupper(($aRow['built_in_flag'] == "Y") ? "YES":"NO");
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_leave_type' onclick=\"modal_leave_type_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_leave_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_leave_type_init('".$edit_action."')\"></a>";
				if($permission_delete)
					if($aRow['built_in_flag'] == "N")
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

	public function modal_leave_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL, $sort_num = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_SELECTIZE);
			
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
			$data ['nav_page']  			= CODE_LIBRARY_LEAVE_TYPE;
			$data ['action']    			= $action;
			$data ['salt']      			= $salt;
			$data ['token']    				= $token;
			$data ['id']       			 	= $id;
			
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_leave_types;
				$where						= array();
				$key 						= $this->get_hash_key('leave_type_id');
				$where[$key]				= $id;
				$leave_type_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['leave_type_info']	= $leave_type_info;
				$data['deduct_leave_type']	= $leave_type_info['deduct_bal_leave_type_id'];
				$data['include_cert']		= $leave_type_info['cert_flag'];
				$data['built_in_flag']		= $leave_type_info['built_in_flag'];
				$data['active_flag']		= $leave_type_info['active_flag'];
				$data['leave_id']			= $leave_type_info['leave_type_id'];
				$data ['sort_num'] 			= $leave_type_info['sort_order'];
				
			}
			else 
			{
				$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(leave_type_id)) AS count"), $this->cl->tbl_param_leave_types, NULL, false);
				$data['sort_num'] 			= $iTotal['count'] + 1;
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
		
		$this->load->view('code_library/modals/modal_leave_type', $data);
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
			$valid_data 								= $this->_validate_data_leave_type($params);

			//SET FIELDS VALUE
			$fields['leave_type_name'] 					= $valid_data['leave_type_name'];
			$fields['sort_order'] 						= ! empty($valid_data['sort_num']) ? $valid_data['sort_num'] : 0;
			$fields['active_flag']     					= isset($valid_data['active_flag']) ? "Y" : "N";
			
			/*===== marvin : start : include nature_of_deduction =====*/
			$fields['nature_of_deduction']				= $valid_data['nature_of_deduction'];
			/*===== marvin : end : include nature_of_deduction =====*/
			
			if($valid_data['deduction_leave_type'] == 0)
			{
				$fields['deduct_bal_leave_type_id'] 	= "0";
			}
			else{
				$fields['deduct_bal_leave_type_id'] 	= $valid_data['deduction_leave_type'];
			}
			$fields['cert_flag'] 						= isset($valid_data['include_certification']) ? "Y" : "N";
			$fields['built_in_flag'] 					= isset($valid_data['built_in_flag']) ? "Y" : "N";
			
			$table 			= $this->cl->tbl_param_leave_types;
			$colname 		= 'leave_type_name';
			$colval 		= strtolower($valid_data['leave_type_name']);
			
			$info 			= $this->cl->get_duplicated_name($table,$colname,$colval);
			
			$input_name 	= strtolower($info['leave_type_name']);
			
			$lid 			= $valid_data ['leave_id'];
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_leave_types;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 
				
				if($input_name != $colval){
				
				//SET AUDIT TRAIL DETAILS
					$audit_action[]			= AUDIT_INSERT;
					
					$prev_detail[]			= array();
	
					//INSERT DATA
					$leave_type_id 			= $this->cl->insert_code_library($table, $fields, TRUE);
	
					//MESSAGE ALERT
					$message 				= $this->lang->line('data_saved');
					//WHERE VALUES
					$where 	 				= array();
					$where['leave_type_id']	= $leave_type_id;
	
					// GET THE DETAIL AFTER INSERTING THE RECORD
					$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
					
					// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
					$activity 				= "%s has been added";
					
					$status = TRUE;
				
				}
				else{
					
					$audit_action[]			= AUDIT_INSERT;
						
					$prev_detail[]			= array();
					
					$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
						
					$activity 				= "%s is already existed";
					
					$message = ucwords($info['leave_type_name'])." is already existed.";
				}
				
			}
			else
			{
				//UPDATE 
				if(($input_name == $colval && $lid == $info['leave_type_id']) || ($input_name == '' && $info['leave_type_id'] == '')){
				//WHERE 
					$where			= array();
					$key 			= $this->get_hash_key('leave_type_id');
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
					
					$status = TRUE;
				
				}
				else{
					$audit_action[]	= AUDIT_UPDATE;
					
					$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
					
					$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
						
					// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
					$activity 		= "%s is already existed";
					
					$message = ucwords($info['leave_type_name'])." is already existed.";
					
					
				}
				
			}
			
			$activity = sprintf($activity, $params['leave_type_name']);
	
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
			$table 				= $this->cl->tbl_param_leave_types;
			$where				= array();
			$key 				= $this->get_hash_key('leave_type_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['leave_type_name']);
	
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
			$msg = $this->get_user_message($e);	
			Main_Model::rollback();
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
			"table_id" 			=> 'leave_type_table',
			"path"				=> PROJECT_MAIN . '/code_library_ta/leave_type/get_leave_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_leave_type($params)
	{
		$fields                 				= array();
		$fields['leave_type_name']  			= "Leave Type Name";
		$fields['sort_num']  					= "Sort Number";

		$this->check_required_fields($params, $fields);	
		
		return $this->_validate_leave_type_input ($params);
	}

	private function _validate_leave_type_input($params) 
	{
		try {
			
			$validation ['leave_id'] = array (
					'data_type' 					=> 'string',
					'name' 							=> 'Leave Type ID',
					'max_len'						=> 11
			);
			
			$validation ['leave_type_name'] = array (
					'data_type' 					=> 'string',
					'name' 							=> 'Leave Type Name',
					'max_len'						=> 50 
			);
			
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			
			$validation ['deduction_leave_type'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Deduction Leave Type',
					'max_len' 						=> 3
			);
			
			$validation ['include_certification'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Include Certification',
					'max_len' 						=> 1
			);
			
			$validation ['built_in_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Built In Flag',
					'max_len' 						=> 1
			);
			
			/*===== marvin : start : include nature_of_deduction =====*/
			$validation ['nature_of_deduction'] 	= array (
					'data_type' 					=> 'tinyint',
					'name' 							=> 'Nature of Deduction',
					'max_len' 						=> 1
			);
			/*===== marvin : end : include nature_of_deduction =====*/
			
			$validation ['sort_num'] 				= array (
					'data_type' 					=> 'digit',
					'name' 							=> 'Sort Number'
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */