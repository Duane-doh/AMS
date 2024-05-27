<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsis extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_GSIS;

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
			$resources['datatable'][]	= array('table_id' => 'gsis_table', 'path' => 'main/code_library_payroll/gsis/get_gsis_list', 'advanced_filter' => TRUE);
			$resources['load_modal']	= array(
				'modal_gsis' 			=> array(
					'controller'		=> 'code_library_payroll/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_gsis',
					'multiple'			=> true,
					'height'			=> '350px',
					'size'				=> 'md',
					'title'				=> 'GSIS'
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

		$this->load->view('code_library/tabs/gsis', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_gsis_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("effective_date", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_gsis;	
			$where						= array();
			$gsis 						= $this->cl->get_gsis_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effective_date)) AS count"), $this->cl->tbl_param_gsis, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($gsis),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_GSIS, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_GSIS, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_GSIS, ACTION_DELETE);

			$cnt = 0;
			foreach ($gsis as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["effective_date"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("GSIS", "'.$url_delete.'")';

				$row[] = '<center>' . format_date($aRow['effective_date']) . '</center>';
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_gsis' onclick=\"modal_gsis_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_gsis' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_gsis_init('".$edit_action."')\"></a>";
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

	public function modal_gsis($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 							= array();
			$resources['load_css'] 				= array(CSS_SELECTIZE,CSS_DATETIMEPICKER);
			$resources['load_js']  				= array(JS_SELECTIZE, JS_DATETIMEPICKER, 'jquery.number.min');

			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;

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
			$data['nav_page']					= CODE_LIBRARY_GSIS_TABLE;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table              			= $this->cl->tbl_param_gsis;
				$where							= array();
				$key 							= $this->get_hash_key('effective_date');
				$where[$key]					= $id;
				$gsis_info 						= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				$data['gsis_info']				= $gsis_info;

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
		
		$this->load->view('code_library/modals/modal_gsis', $data);
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

			// PERSONAL SHARE 
			$personal_share 	= $params['personal_share'];			
			$pshare 			= array();

			foreach($personal_share AS $ps)
			{
				$pshare[] 					= $ps / 100;
				$params['personal_share'] 	= $pshare;
			}

			// GOVERNMENT SHARE
			$government_share 	= $params['government_share'];			
			$gshare 			= array();

			foreach($government_share AS $gs)
			{
				$gshare[] 					= $gs / 100;
				$params['government_share'] = $gshare;
			}

			// SERVER VALIDATION
			$valid_data 							= $this->_validate_data_gsis($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table									= $this->cl->tbl_param_gsis;
				
			if(EMPTY($params['id']))
			{
				for ($x = 0; $x<count($valid_data['personal_share']); $x++ ) {

					$fields 						= array();
					$fields['personal_share']    	= $valid_data['personal_share'][$x];
					$fields['government_share']    	= $valid_data['government_share'][$x];
					$fields['max_government_share'] = $valid_data['max_government_share'][$x];
					$fields['effective_date']    	= $valid_data['effective_date'];
					$fields['insurance_type_flag']  = $valid_data['insurance_type_flag'][$x];
					$fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_gsis, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_gsis;
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

				//EDIT
				$where			= array();
				$key 			= $this->get_hash_key('effective_date');
				$where[$key]	= $params['id'];

				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				$this->cl->delete_code_library($this->cl->tbl_param_gsis, $where);

				for ($x = 0; $x<count($valid_data['personal_share']); $x++ ) {

					$fields 						= array();
					$fields['personal_share']    	= $valid_data['personal_share'][$x];
					$fields['government_share']    	= $valid_data['government_share'][$x];
					$fields['max_government_share'] = $valid_data['max_government_share'][$x];
					$fields['effective_date']    	= $valid_data['effective_date'];
					$fields['insurance_type_flag']  = $valid_data['insurance_type_flag'][$x];
					$fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";

					$this->cl->insert_code_library($this->cl->tbl_param_gsis, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_gsis;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
								
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['gsis_id']);
	
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
			$table 				= $this->cl->tbl_param_gsis;

			$where				= array();
			$key 				= $this->get_hash_key('effective_date');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['effective_date']);
	
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
			"table_id" 			=> 'gsis_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/gsis/get_gsis_list/',
			"advanced_filter"	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_gsis($params)
	{
		$fields 							= array();
		// $fields['personal_share']    		= "Personal Share";
		// $fields['government_share']    		= "Government Share";
		// $fields['max_government_share']    	= "Maximum Government Share";
		$fields['effective_date']    		= "Effectivity Date";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_gsis_input ($params);
	}

	private function _validate_gsis_input($params) 
	{
		try {
			
			$validation ['effective_date'] 			= array (
					'data_type' 					=> 'date',
					'name'							=> 'Effectivity Date',
			);
			$validation ['personal_share'] 			= array (
					'data_type' 					=> 'decimal',
					'name'							=> 'Personal Share'
			);
			$validation ['government_share'] 		= array (
					'data_type' 					=> 'string',
					'name'							=> 'Government Share'
			);
			$validation ['max_government_share'] 	= array (
					'data_type' 					=> 'string',
					'name'							=> 'Maximum Government Share'
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);
			$validation ['insurance_type_flag'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Insurance Type Flag',
					'max_len' 						=> 3
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */