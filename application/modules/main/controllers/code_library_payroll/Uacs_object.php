<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uacs_object extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_UACS_OBJECT;
	
	//PERMISSIONS
	private $permission_view;
	private $permission_edit;
	private $permission_delete;
	private $permission_add;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
		
		//PERMISSIONS
		$this->permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_UACS_OBJECT, ACTION_VIEW);
		$this->permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_UACS_OBJECT, ACTION_EDIT);
		$this->permission_delete 		= $this->permission->check_permission(MODULE_PAYROLL_CL_UACS_OBJECT, ACTION_DELETE);
		$this->permission_add 			= $this->permission->check_permission(MODULE_PAYROLL_CL_UACS_OBJECT, ACTION_ADD);
	}

	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			
			$data = array();
			$resources = array();
			$data['action_id'] = $action_id;
			$resources['load_css'][] = CSS_DATATABLE;
			$resources['load_js'][] = JS_DATATABLE;
			$resources['datatable'][] = array('table_id' => 'uacs_code_table', 'path' => 'main/code_library_payroll/uacs_object/get_uacs_code_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] = array(
				'modal_uacs_code' 	=> array(
					'controller' 		=> 'code_library_payroll/'.__CLASS__,
					'module' 			=> PROJECT_MAIN,
					'method' 			=> 'modal_uacs_code',
					'multiple' 			=> TRUE,
					'height' 			=> '400px',
					'size' 				=> 'sm',
					'title' 			=> 'UACS Object Code'
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
		$this->load->view('code_library/tabs/uacs_code', $data);
		$this->load_resources->get_resource($resources);
		
	}

	public function get_uacs_code_list()
	{

		try
		{
			if (EMPTY($this->permission_view)) {
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			}
			$params 					=get_params();

			$aColumns					= array("A.uacs_object_code", "A.account_title", "B.uacs_object_type", "A.active_flag");
			$bColumns					= array("uacs_object_code", "account_title", "uacs_object_type", "IF(A.active_flag = 'Y', 'Active', 'Inactive')");
			$uacs_code					= $this->cl->get_uacs_code_list($aColumns, $bColumns, $params, array());
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(uacs_object_code)) AS count"), $this->cl->tbl_param_uacs_object_codes, NULL, false);
			
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($uacs_code),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
		
			$permission_view 	= $this->permission_view;
			$permission_edit	= $this->permission_edit;
			$permission_delete	= $this->permission_delete;
		    $permission_add		= $this->permission_add;	
			
			$cnt = 0;
			foreach ($uacs_code as $aRow):
			$cnt++;
			$row 						= array();
			$action 					= "<div class='table-actions'>";
				
			$uacs_object_code_id	= $aRow["uacs_object_code"];
			$id 						= $this->hash ($uacs_object_code_id);
			$salt 						= gen_salt();
			$token_view 				= in_salt($id . '/' . ACTION_VIEW, $salt);
			$token_edit 				= in_salt($id . '/' . ACTION_EDIT, $salt);
			$token_delete 				= in_salt($id . '/' . ACTION_DELETE, $salt);
			$view_action 				= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
			$edit_action 				= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;
			$url_delete 				= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
			$delete_action				= 'content_delete("UACS Object Code", "'.$url_delete.'")';

			$row[] = strtoupper($aRow['account_title']);
			$row[] = strtoupper($aRow['uacs_object_code']);
			$row[] = strtoupper($aRow['uacs_object_type']);
			$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

			if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_uacs_code' onclick=\"modal_uacs_code_init('".$view_action."')\"></a>";
			if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_uacs_code' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_uacs_code_init('".$edit_action."')\"></a>";
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

	public function modal_uacs_code($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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

			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE, JS_NUMBER);
			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_UACS_OBJECT;
			
			
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              		= $this->cl->tbl_param_uacs_object_codes;
				$where              		= array();
				$key                		= $this->get_hash_key('uacs_object_code');
				$where[$key]        		= $id;
				$uacs_code_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);
				$data['uacs_code_info'] 	= $uacs_code_info;
			}
			
			$table              			= $this->cl->tbl_param_uacs_object_types;
			$data['uacs_object_type_info'] 	= $this->cl->get_code_library_data(array("*"), $table, array(), TRUE);
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

		$this->load->view('code_library/modals/modal_uacs_code', $data);
		$this->load_resources->get_resource($resources);
	}

	public function process()
	{
		try
		{
			if (EMPTY($this->permission_add)) {
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			}
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
			$valid_data						= $this->_validate_data_uacs_code($params);

			//SET FIELDS VALUE
			$fields['account_title']		= $valid_data['account_title'];
			$fields['uacs_object_code']		= $valid_data['uacs_object_code'];
			$fields['uacs_object_type_id']	= $valid_data['uacs_object_type'];
			$fields['active_flag']			= isset($valid_data['active_flag']) ? "Y" : "N";
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_uacs_object_codes;

			if(EMPTY($params['id']))
			{
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]			= $table;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[]  		= array();
				$curr_detail[]  		= array($fields);
				$audit_action[] 		= AUDIT_INSERT;
				
				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//WHERE
				$where			= array();
				$key 			= $this->get_hash_key('uacs_object_code');
				$where[$key]	= $params['id'];

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $table;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";

			}

			$activity = sprintf($activity, $params['account_title']);

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
			
			$where				= array();
			$key 				= $this->get_hash_key('uacs_object_code');
			$where[$key]		= $id;

			//****MAIN TABLE****
			$table	 	= $this->cl->tbl_param_uacs_object_codes;
			
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$previous			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');

			$current	 		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

			//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $table;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($current);
				$audit_action[] = AUDIT_DELETE;

			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $previous['account_title']);

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
			"flag"            	=> $flag,
			"msg"            	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'uacs_code_table',
			"path"            	=> PROJECT_MAIN . '/code_library_payroll/uacs_object/get_uacs_code_list/',
			"advanced_filter" 	=> true
		);

		echo json_encode($info);
	}

	private function _validate_data_uacs_code($params)
	{
		$fields                 	= array();
		$fields['account_title']  	= "Account Title";
		$fields['uacs_object_code']	= "UACS Object Code";
		$fields['uacs_object_type']	= "UACS Object Type";

		$this->check_required_fields($params, $fields);

		return $this->_validate_uacs_code_input ($params);
	}

	private function _validate_uacs_code_input($params)
	{
		try {
				
			$validation ['account_title'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Account Title',
					'max_len' 					=> 255
			);
			$validation ['uacs_object_code']	= array(
					'data_type'					=> 'string',
					'name'						=> 'UACS Object Code',
					'max_len'					=> 50
			);
			$validation ['uacs_object_type']	= array(
					'data_type'					=> 'string',
					'name'						=> 'UACS Object Type',
					'max_len'					=> 50
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'enum',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1
			);
				
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_payroll.php */