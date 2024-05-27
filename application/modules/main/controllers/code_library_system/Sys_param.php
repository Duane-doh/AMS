<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_param extends Main_Controller {
	private $module = MODULE_SYSTEM_CL_SYSTEM_PARAMETER;

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
			$resources['datatable'][]	= array('table_id' => 'sys_param_table', 'path' => 'main/code_library_system/sys_param/get_sys_param_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_sys_param' 		=> array(
					'controller'		=> 'code_library_system/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_sys_param',
					'multiple'			=> true,
					'height'			=> '250px',
					'size'				=> 'sm',
					'title'				=> 'System Parameter'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_system/'.__CLASS__,
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

		$this->load->view('code_library/tabs/sys_param', $data);
		$this->load_resources->get_resource($resources);
	}

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
				
				$row[] = strtoupper($aRow['sys_param_type']);
				$row[] = strtoupper($aRow['sys_param_name']);
				$row[] = strtoupper($aRow['sys_param_value']);
				$row[] = ($aRow['built_in_flag'] == "1") ? 'BUILT IN':'USER DEFINED';
				
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
			$table 				= $this->cl->db_core.'.'.$this->cl->tbl_sys_param;
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
			"path"				=> PROJECT_MAIN . '/code_library_system/sys_param/get_sys_param_list/',
			"advanced_filter" 	=> true
		);	
	
		echo json_encode($info);
	}

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

}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */