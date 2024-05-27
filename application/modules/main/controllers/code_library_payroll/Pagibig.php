<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagibig extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_PAGIBIG;

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
			$resources['datatable'][]	= array('table_id' => 'pagibig_table', 'path' => 'main/code_library_payroll/pagibig/get_pagibig_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_pagibig' 		=> array(
					'controller'		=> 'code_library_payroll/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_pagibig',
					'multiple'			=> true,
					'height'			=> '350px',
					'size'				=> 'md',
					'title'				=> 'Pagibig'
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

		$this->load->view('code_library/tabs/pagibig', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_pagibig_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("effectivity_date", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_pagibig;
			$where						= array();
			$pagibig					= $this->cl->get_pagibig_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effectivity_date)) AS count"), $this->cl->tbl_param_pagibig, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($pagibig),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PAGIBIG, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PAGIBIG, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PAGIBIG, ACTION_DELETE);

			$cnt = 0;
			foreach ($pagibig as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $aRow["effectivity_date"];
				$id 					= $this->hash ($id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("Pagibig", "'.$url_delete.'")';

				$row[] = '<center>' . format_date($aRow['effectivity_date']) . '</center>';
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_pagibig' onclick=\"modal_pagibig_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_pagibig' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_pagibig_init('".$edit_action."')\"></a>";
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

	public function modal_pagibig($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 					= array();
			$resources['load_css']		= array(CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_DATETIMEPICKER, 'jquery.number.min');

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
			$data['nav_page']			= CODE_LIBRARY_PAGIBIG_TABLE;
			$data ['action'] 			= $action;
			$data ['salt'] 				= $salt;
			$data ['token'] 			= $token;
			$data ['id'] 				= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 					= $this->cl->tbl_param_pagibig;
				$where					= array();
				$key 					= $this->get_hash_key('effectivity_date');
				$where[$key]			= $id;
				$data['pagibig_info']	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
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
		
		$this->load->view('code_library/modals/modal_pagibig', $data);
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
			// EMPLOYEE RATE
			$employee_rate 	= $params['employee_rate'];			
			$employee 		= array();

			foreach($employee_rate AS $emp)
			{
				$employee[] 				= $emp / 100;
				$params['employee_rate'] 	= $employee;
			}

			// EMPLOYER RATE
			$employer_rate 	= $params['employer_rate'];			
			$employer 		= array();

			foreach($employer_rate AS $empr)
			{
				$employer[] 				= $empr / 100;
				$params['employer_rate'] 	= $employer;
			}

			// SERVER VALIDATION
			$valid_data 						 = $this->_validate_data_pagibig($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								 = $this->cl->tbl_param_pagibig;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				for ($x = 0; $x<count($valid_data['salary_range_from']); $x++ ) {

					$fields 					 = array();
					$fields['effectivity_date']  = $valid_data['effectivity_date'];
					$fields['salary_range_from'] = $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] 	 = $valid_data['salary_range_to'][$x];
					$fields['max_salary_range']  = $valid_data['max_salary_range'][$x];
					$fields['employee_rate'] 	 = $valid_data['employee_rate'][$x];
					$fields['employer_rate'] 	 = $valid_data['employer_rate'][$x];
					$fields['active_flag']		 = isset($valid_data['active_flag']) ? "Y" : "N";

					if($params['salary_range_from'][$x] > $params['salary_range_to'][$x])
					{
						$x++; //ROW COUNTER
						throw new Exception('Salary Range To must be greater than the Salary Range From. [ROW - ' . $x . ']');
					}

					$this->cl->insert_code_library($this->cl->tbl_param_pagibig, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_pagibig;
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
				$key 			= $this->get_hash_key('effectivity_date');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				$this->cl->delete_code_library($this->cl->tbl_param_pagibig, $where);

				//EDIT DATA 
				for ($x = 0; $x<count($valid_data['salary_range_from']); $x++ ) {

					$fields 					 = array();
					$fields['effectivity_date']  = $valid_data['effectivity_date'];
					$fields['salary_range_from'] = $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] 	 = $valid_data['salary_range_to'][$x];
					$fields['max_salary_range']  = $valid_data['max_salary_range'][$x];
					$fields['employee_rate'] 	 = $valid_data['employee_rate'][$x];
					$fields['employer_rate'] 	 = $valid_data['employer_rate'][$x];
					$fields['active_flag']		 = isset($valid_data['active_flag']) ? "Y" : "N";

					if($params['salary_range_from'][$x] > $params['salary_range_to'][$x])
					{
						$x++; //ROW COUNTER
						throw new Exception('Salary Range To must be greater than the Salary Range From. [ROW - ' . $x . ']');
					}

					$this->cl->insert_code_library($this->cl->tbl_param_pagibig, $fields);
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
			
			$activity = sprintf($activity, $params['pagibig_id']);
	
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
			$table 				= $this->cl->tbl_param_pagibig;
			$where				= array();
			$key 				= $this->get_hash_key('effectivity_date');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['effectivity_date']);
	
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
			"table_id" 			=> 'pagibig_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/pagibig/get_pagibig_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_pagibig($params)
	{
		$fields                 		= array();
		$fields['effectivity_date']  	= "Effectivity Date";
		// $fields['salary_range_from']  	= "Salary Range From";
		// $fields['salary_range_to']  	= "Salary Range To";
		// $fields['max_salary_range']  	= "Maximum Salary Range";
		// $fields['employee_rate']  		= "Employee Rate";
		// $fields['employer_rate']  		= "Employer Rate";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_pagibig_input ($params);
	}

	private function _validate_pagibig_input($params) 
	{
		try {
			
			$validation ['effectivity_date'] 	= array (
					'data_type' 				=> 'date',
					'name' 						=> 'Effectivity Date',
			);
			$validation ['salary_range_from'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Salary Range From',
					'max_len' 					=> 45 
			);
			$validation ['salary_range_to'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Salary Range To',
					'max_len' 					=> 45 
			);
			$validation ['max_salary_range'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Salary Range To',
					'max_len' 					=> 45 
			);
			$validation ['employee_rate'] 		= array (
					'data_type'					=> 'string',
					'name' 						=> 'Employee Rate',
					'max_len' 					=> 45 
			);
			$validation ['employer_rate'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Employer Rate',
					'max_len' 					=> 45 
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'string',
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
/* Location: ./application/modules/main/controllers/Code_library_hr.php */