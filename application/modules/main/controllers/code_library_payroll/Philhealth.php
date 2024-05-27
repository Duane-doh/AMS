<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Philhealth extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_PHILHEALTH;

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
			$resources['datatable'][]	= array('table_id' => 'philhealth_table', 'path' => 'main/code_library_payroll/philhealth/get_philhealth_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_philhealth'		=> array(
					'controller'		=> 'code_library_payroll/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_philhealth',
					'multiple'			=> true,
					'height'			=> '400px',
					'size'				=> 'lg',
					'title'				=> 'Philhealth'
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

		$this->load->view('code_library/tabs/philhealth', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_philhealth_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("effectivity_date", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_philhealth;
			$where						= array();
			$philhealth					= $this->cl->get_philhealth_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effectivity_date)) AS count"), $this->cl->tbl_param_philhealth, NULL, false);
		
			$output 					= array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($philhealth),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PHILHEALTH, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PHILHEALTH, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_PHILHEALTH, ACTION_DELETE);

			$cnt = 0;
			foreach ($philhealth as $aRow):
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
				$delete_action			= 'content_delete("Philhealth", "'.$url_delete.'")';

				$row[] = '<center>' . format_date($aRow['effectivity_date']) . '</center>';
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_philhealth' onclick=\"modal_philhealth_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_philhealth' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_philhealth_init('".$edit_action."')\"></a>";
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

	public function modal_philhealth($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_DATETIMEPICKER);
			$resources['load_js'] 			= array(JS_DATETIMEPICKER, 'jquery.number.min');

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
			$data['nav_page']				= CODE_LIBRARY_PHILHEALTH_TABLE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_philhealth;
				$where						= array();
				$key 						= $this->get_hash_key('effectivity_date');
				$where[$key]				= $id;
				$order_by 					= array('salary_bracket' => 'ASC');
				$data['philhealth_info']	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE, $order_by);

				$data['end_result'] 		= end($data['philhealth_info']);
				
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
		
		$this->load->view('code_library/modals/modal_philhealth', $data);
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
			$valid_data 							 = $this->_validate_data_philhealth($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 									 = $this->cl->tbl_param_philhealth;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				for ($x = 0; $x<count($valid_data['salary_bracket']); $x++ ) {

					$fields 						 = array();
					$fields['effectivity_date'] 	 = $valid_data['effectivity_date'];
					$fields['salary_bracket'] 		 = $valid_data['salary_bracket'][$x];
					$fields['salary_range_from'] 	 = $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] 		 = $valid_data['salary_range_to'][$x];
					$fields['employee_share'] 		 = $valid_data['employee_share'][$x];
					$fields['employer_share'] 		 = $valid_data['employer_share'][$x];
					$fields['salary_base'] 			 = $valid_data['salary_base'][$x];
					$fields['total_monthly_premium'] = $valid_data['total_monthly_premium'][$x];
					$fields['active_flag']			 = isset($valid_data['active_flag']) ? "Y" : "N";

					if($params['salary_range_from'][$x] > $params['salary_range_to'][$x])
					{
						$x++; //ROW COUNTER
						throw new Exception('Salary Range To must be greater than the Salary Range From. [ROW - ' . $x . ']');
					}

					$this->cl->insert_code_library($this->cl->tbl_param_philhealth, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_philhealth;
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

				$this->cl->delete_code_library($this->cl->tbl_param_philhealth, $where);

				//EDIT DATA 
				for ($x = 0; $x<count($valid_data['salary_bracket']); $x++ ) {

					$fields 							= array();
					$fields['effectivity_date'] 		= $valid_data['effectivity_date'];
					$fields['salary_bracket'] 			= $valid_data['salary_bracket'][$x];
					$fields['salary_range_from'] 		= $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] 			= $valid_data['salary_range_to'][$x];
					$fields['employee_share'] 			= $valid_data['employee_share'][$x];
					$fields['employer_share'] 			= $valid_data['employer_share'][$x];
					$fields['salary_base'] 				= $valid_data['salary_base'][$x];
					$fields['total_monthly_premium'] 	= $valid_data['total_monthly_premium'][$x];
					$fields['active_flag']				= isset($valid_data['active_flag']) ? "Y" : "N";

					if($params['salary_range_from'][$x] > $params['salary_range_to'][$x])
					{
						$x++; //ROW COUNTER
						throw new Exception('Salary Range To must be greater than the Salary Range From. [ROW - ' . $x . ']');
					}

					$this->cl->insert_code_library($this->cl->tbl_param_philhealth, $fields);
				}

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_philhealth;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['philhealth_id']);
	
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
			$table 				= $this->cl->tbl_param_philhealth;
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
			"table_id" 			=> 'philhealth_table',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/philhealth/get_philhealth_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_philhealth($params)
	{
		$fields                 			= array();
		$fields['salary_bracket']  			= "Salary Bracket";
		$fields['effectivity_date']  		= "Effectivity Date";/*
		$fields['salary_range_from']  		= "Salary Range From";
		$fields['salary_range_to']  		= "Salary Range To";*/
		// $fields['employee_share']  			= "Employee Share";
		// $fields['employer_share']  			= "Employer Share";
		// $fields['salary_base']  			= "Salary Base";
		// $fields['total_monthly_premium']  	= "Total Monthly Premium";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_philhealth_input ($params);
	}

	private function _validate_philhealth_input($params) 
	{
		try {
			
			$validation ['salary_bracket'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Salary Bracket',
					'max_len' 						=> 45 
			);
			$validation ['effectivity_date'] 		= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Effectivity Date',
			);
			$validation ['salary_range_from'] 		= array (
					'data_type' 					=> 'amount',
					'name' 							=> 'Salary Range From',
					'max_len' 						=> 45 
			);	
			$validation ['salary_range_to'] 		= array (
					'data_type' 					=> 'amount',
					'name' 							=> 'Salary Range To',
					'max_len' 						=> 45 
			);
			$validation ['employee_share'] 			= array (
					'data_type' 					=> 'amount',
					'name' 							=> 'Employee Share',
					'max_len' 						=> 45 
			);
			$validation ['employer_share'] 			= array (
					'data_type' 					=> 'amount',
					'name' 							=> 'Employer Share',
					'max_len' 						=> 45 
			);
			$validation ['salary_base'] 			= array (
					'data_type' 					=> 'amount',
					'name' 							=> 'Salary Base',
					'max_len' 						=> 45 
			);
			$validation ['total_monthly_premium'] 	= array (
					'data_type' 					=> 'amount',
					'name' 							=> 'Total Monthly Premium',
					'max_len' 						=> 45 
			);
			$validation ['active_flag'] 			= array (
					'data_type'						=> 'string',
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