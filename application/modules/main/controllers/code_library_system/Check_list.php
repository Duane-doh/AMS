<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_list extends Main_Controller {
	private $module = MODULE_SYSTEM_CL_CHECKLISTS;

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
			$resources['datatable'][]	= array('table_id' => 'check_list_table', 'path' => 'main/code_library_system/check_list/get_check_list', 'advanced_filter' => TRUE);
			$resources['load_modal']	= array(
				'modal_check_list'		=> array(
					'controller'		=> 'code_library_system/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_check_list',
					'multiple'			=> true,
					'height'			=> '350px',
					'size'				=> 'sm',
					'title'				=> 'Checklist'
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

		$this->load->view('code_library/tabs/check_list', $data);
		$this->load_resources->get_resource($resources);
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
				
				$row[] = strtoupper($aRow['check_list_code']);
				$row[] = strtoupper($aRow['check_list_description']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

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
			$valid_data                        	= $this->_validate_data_check_list($params);
			
			
			// SET FIELDS VALUE PARAM CHECKLIST
			$fields								= array();
			$fields['check_list_code']        	= $valid_data['check_list_name'];
			$fields['check_list_description']	= $valid_data['check_list_description'];
			$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";
			
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

				//MESSAGE ALERT
				$message 						= $this->lang->line('data_updated');
				
				$audit_table[]					= $this->cl->tbl_param_checklist_docs;
				$audit_schema[]					= Base_Model::$schema_core;
				$prev_detail[]         			= array();
				$curr_detail[]          		= $current_detail;
				$audit_action[]        			= AUDIT_UPDATE;
				
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
			"path"				=> PROJECT_MAIN . '/code_library_system/check_list/get_check_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
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

}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */