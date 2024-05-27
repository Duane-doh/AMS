<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eligibility extends Main_Controller {
	private $module = MODULE_HR_CL_ELIGIBILITY;

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
			$resources['load_css'][] 		= CSS_DATATABLE;
			$resources['load_js'][] 		= JS_DATATABLE;
			$resources['datatable'][]		= array('table_id' => 'eligibility_table', 'path' => 'main/code_library_hr/' . __CLASS__ .'/get_eligibility_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 		= array(
				'modal_eligibility_type' 	=> array(
					'controller'			=> 'code_library_hr/'.__CLASS__,
					'module'				=> PROJECT_MAIN,
					'method'				=> 'modal_eligibility_type',
					'multiple'				=> true,
					'height'				=> '330px',
					'size'					=> 'sm',
					'title'					=> 'Eligibility'
				)
			);
			$resources['load_delete'] 		= array(
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

		$this->load->view('code_library/tabs/eligibility', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_eligibility_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("eligibility_type_code", "eligibility_type_name", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_eligibility_types;
			$where						= array();
			$eligibility 				= $this->cl->get_eligibility_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(eligibility_type_id)) AS count"), $this->cl->tbl_param_eligibility_types, NULL, false);
			$iFilteredTotal 			= $this->cl->eligibility_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
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
	
				$row[] = strtoupper($aRow['eligibility_type_code']);
				$row[] = strtoupper($aRow['eligibility_type_name']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

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

	public function modal_eligibility_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 							= array();
			$resources['load_css']				= array(CSS_SELECTIZE);
			$resources['load_js']  				= array(JS_SELECTIZE);

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

				$resources['single']		= array(
					'eligibility_type_flag' => $eligibility_info['eligibility_type_flag'],
					'eligibility_level_name' => $eligibility_info['eligibility_level_id']
				);
			}

			$fields 				 		= array('sys_param_name', 'sys_param_value');
			$table 					 		= $this->cl->DB_CORE.'.'.$this->cl->tbl_sys_param;
			$where					 		= array();
			$where['sys_param_type'] 		= 'ELIGIBILITY_TYPE_FLAG';
			$data['eligibility_type_flag'] 	= $this->cl->get_code_library_data($fields, $table, $where, TRUE);

			$field 								= array("*") ;
			$table								= $this->cl->tbl_param_eligibility_levels;
			$where								= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;	
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['eligibility_level_id'] 	= array($eligibility_info['eligibility_level_id'], array("=", ")"));				
			}
			$data['eligibility_level_name'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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
			$valid_data 					 = $this->_validate_data_eligibility($params);

			//SET FIELDS VALUE
			$fields['eligibility_type_code'] = $valid_data['eligibility_type_code'];
			$fields['eligibility_type_name'] = $valid_data['eligibility_type_name'];
			$fields['eligibility_type_flag'] = !EMPTY($valid_data['eligibility_type_flag']) ? $valid_data['eligibility_type_flag'] : NULL;
			$fields['eligibility_level_id']  = $valid_data['eligibility_level_name'];
			$fields['active_flag']   		 = isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 						 	= $this->cl->tbl_param_eligibility_types;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				 = $this->cl->tbl_param_eligibility_types;
				$audit_schema[]				 = DB_MAIN;
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
				$audit_schema[]	= DB_MAIN;
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
			$msg = $this->get_user_message($e);	
			Main_Model::rollback();
		
			//$msg = $this->rlog_error($e, TRUE);
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
			"path"				=> PROJECT_MAIN . '/code_library_hr/eligibility/get_eligibility_list/',
			"advanced_filter"	=> true
		);
	
		echo json_encode($info);
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
					// 'max_len' 						=> 50
					//marvin
					'max_len' 						=> 100	
			);
			$validation ['eligibility_type_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'CS Eligibility Type',
					'max_len' 						=> 4 
			);
			$validation ['eligibility_level_name'] 	= array (
				'data_type' 					=> 'string',
				'name' 							=> 'CS Eligibility Level',
				'max_len' 						=> 4 
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

/* End of file Eligibility.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Eligibility.php */