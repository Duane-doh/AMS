<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_status extends Main_Controller {
	private $module = MODULE_HR_CL_EMPLOYMENT_STATUS;

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
			$resources['datatable'][]		= array('table_id' => 'employment_status_table', 'path' => 'main/code_library_hr/employment_status/get_employment_status_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 		= array(
				'modal_employment_status' 	=> array(
					'controller'			=> 'code_library_hr/'.__CLASS__,
					'module'				=> PROJECT_MAIN,
					'method'				=> 'modal_employment_status',
					'multiple'				=> true,
					'height'				=> '250px',
					'size'					=> 'sm',
					'title'					=> 'Employment Status'
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

		$this->load->view('code_library/tabs/employment_status', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function get_employment_status_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("employment_status_name", "employment_status_code", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_employment_status;
			$where						= array();
			$employment_status			= $this->cl->get_employment_status_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(employment_status_id)) AS count"), $this->cl->tbl_param_employment_status, NULL, false);
			$iFilteredTotal 			= $this->cl->emp_status_filtered_length($aColumns, $bColumns, $params, $table);
			
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_EMPLOYMENT_STATUS, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_EMPLOYMENT_STATUS, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_EMPLOYMENT_STATUS, ACTION_DELETE);

			$cnt = 0;
			foreach ($employment_status as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["employment_status_id"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("employment status", "'.$url_delete.'")';
				
// 				$emp_type = array();
// 				if($aRow['ap_flag'] === 'Y')
// 					array_push($emp_type,'Appointment'); 
// 				if($aRow['jo_flag'] === 'Y')
// 					array_push($emp_type, 'Job Order');
// 				if($aRow['og_flag'] === 'Y')
// 					array_push($emp_type, 'Other Government Agency');
// 				if($aRow['pr_flag'] === 'Y')
// 					array_push($emp_type, 'Private');
// 				if($aRow['wp_flag'] === 'Y')
// 					array_push($emp_type, 'With Promotion');
// 				$emp_types = implode(', ', $emp_type);
				
				$row[] = strtoupper($aRow['employment_status_name']);
				$row[] = strtoupper($aRow['employment_status_code']);
// 				$row[] = strtoupper($emp_types);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_employment_status' onclick=\"modal_employment_status_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_employment_status' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employment_status_init('".$edit_action."')\"></a>";
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

	public function modal_employment_status($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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

			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_EMPLOYMENT_STATUS;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table 				    		= $this->cl->tbl_param_employment_status;
				$where				    		= array();
				$key 				    		= $this->get_hash_key('employment_status_id');
				$where[$key]		    		= $id;
				$data['employment_status_info']	= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				$employ_type_flag  = array();
				if($data['employment_status_info']['pr_flag'] == YES)
				{
					$employ_type_flag[] = PRIVATE_WORK;
				}
				if($data['employment_status_info']['og_flag'] == YES)
				{
					$employ_type_flag[] = NON_DOH_GOV;
				}
				if($data['employment_status_info']['ap_flag'] == YES)
				{
					$employ_type_flag[] = DOH_GOV_APPT;
				}
				if($data['employment_status_info']['wp_flag'] == YES)
				{
					$employ_type_flag[] = DOH_GOV_NON_APPT;
				}
				if($data['employment_status_info']['jo_flag'] == YES)
				{
					$employ_type_flag[] = DOH_JO;
				}

				$resources['multiple'] 		 	= array(
					'employment_type' 			=> $employ_type_flag
				);
			}

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_employment_types;
			$where							= array();
			$where['active_flag'] 			= YES;		
			$data['employment_type'] 	  	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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
		
		$this->load->view('code_library/modals/modal_employment_status', $data);
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
			$valid_data 						= $this->_validate_data_employment_status($params);

			//SET FIELDS VALUE
			$fields['employment_status_name'] 	= $valid_data['employment_status_name'];
			$fields['employment_status_code'] 	= $valid_data['employment_status_code'];
			$fields['active_flag'] 				= isset($valid_data['active_flag']) ? "Y" : "N";

			$pr_flag = '';
			$og_flag = '';
			$ap_flag = '';
			$wp_flag = '';
			$jo_flag = '';
			foreach($valid_data['employment_type'] as $value) {
				if($value == PRIVATE_WORK){
					$pr_flag = PRIVATE_WORK;
				}
				if($value == NON_DOH_GOV){
					$og_flag = NON_DOH_GOV;
				}
				if($value == DOH_GOV_APPT){
					$ap_flag = DOH_GOV_APPT;
				}
				if($value == DOH_GOV_NON_APPT){
					$wp_flag = DOH_GOV_NON_APPT;
				}
				if($value == DOH_JO){
					$jo_flag = DOH_JO;
				}
			}

			$fields['pr_flag'] = !EMPTY($pr_flag) ? YES : NO;
			$fields['og_flag'] = !EMPTY($og_flag) ? YES : NO;
			$fields['ap_flag'] = !EMPTY($ap_flag) ? YES : NO;
			$fields['wp_flag'] = !EMPTY($wp_flag) ? YES : NO;
			$fields['jo_flag'] = !EMPTY($jo_flag) ? YES : NO;

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_employment_status;
				
			if(EMPTY($params['id']))
			{	
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				 	= $this->cl->tbl_param_employment_status;
				$audit_schema[]					= DB_MAIN;
				$prev_detail[]  			 	= array();
				$curr_detail[]  			 	= array($fields);
				$audit_action[] 			 	= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 					 	= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 					 	= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('employment_status_id');
				$where[$key]	= $params['id'];
								
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_employment_status;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['employment_status_name']);
	
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
			$table 				= $this->cl->tbl_param_employment_status;
			$where				= array();
			$key 				= $this->get_hash_key('employment_status_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['employment_status_name']);
	
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
			//$msg = $this->lang->line('parent_delete_error');
			//$this->rlog_error($e, TRUE);
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
			"table_id" 			=> 'employment_status_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/employment_status/get_employment_status_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_employment_status($params)
	{
		$fields                  			= array();
		$fields['employment_type']  		= "Employment Status Type";
		$fields['employment_status_name']  	= "Employment Status Name";
		$fields['employment_status_code']  	= "Employment Status Code";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_employement_status_input ($params);
	}

	private function _validate_employement_status_input($params) 
	{
		try {
			
			$validation ['employment_type'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employment Status Type',
					'max_len' 						=> 3
			);
			$validation ['employment_status_name'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employment Status Name',
					'max_len' 						=> 50 
			);
			$validation ['employment_status_code'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employment Status Code',
					'max_len' 						=> 5
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

/* End of file Employment_status.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Employment_status.php */