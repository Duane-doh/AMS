<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Position extends Main_Controller {
	private $module = MODULE_HR_CL_POSITION;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     	= array();
			$resources                	= array();
			$data['action_id']        	= $action_id;
			$resources['load_css'][]	= CSS_DATATABLE;
			$resources['load_js']		= array(JS_DATATABLE, JS_EDITOR);
			$resources['datatable'][]	= array('table_id' => 'position_table', 'path' => 'main/code_library_hr/position/get_position_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_position' 		=> array(
					'controller'		=> 'code_library_hr/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_position',
					'multiple'			=> true,
					'height'			=> '400px',
					'size'				=> 'lg',
					'title'				=> 'Position'
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

		$this->load->view('code_library/tabs/position', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_position_list()
	{

		try
		{
			$params   					= get_params();
			$date 	  					= date('Y-m-d');

			$aColumns 					= array("A.*", "B.position_level_name", "C.position_class_level_name");
			$bColumns 					= array("A.position_name", "B.position_level_name", "C.position_class_level_name", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	 					= $this->cl->tbl_param_positions;
			$where						= array();
			/*$tables   				= "param_positions A left join param_salary_schedule B ON (A.salary_grade = B.salary_grade AND A.salary_step = B.salary_step)";
			$where   					= "Where B.effectivity_date = (select MAX(B.effectivity_date) AS date from param_salary_schedule B where B.effectivity_date <= '$date')";*/
			$position 					= $this->cl->get_position_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(position_id)) AS count"), $this->cl->tbl_param_positions, NULL, false);
			$iFilteredTotal 			= $this->cl->position_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho"                	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData"               	=> array()
			);

			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_POSITION, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_POSITION, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_POSITION, ACTION_DELETE);

			$cnt = 0;
			foreach ($position as $aRow):
				$cnt++;
				$row           			= array();

				$action        			= "<div class='table-actions'>";
				
				$position_id   			= $aRow["position_id"];
				$id            			= $this->hash ($position_id);
				$salt          			= gen_salt();
				$token_view    			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit    			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete  			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action   			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action   			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete    			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action 			= 'content_delete("position", "'.$url_delete.'")';
				
				$row[] = strtoupper($aRow['position_name']);
				$row[] = strtoupper($aRow['position_level_name']);
				$row[] = strtoupper($aRow['position_class_level_name']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_position' onclick=\"modal_position_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_position' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_position_init('".$edit_action."')\"></a>";
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

	public function modal_position($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 							= array();
			$resources['load_css']				= array(CSS_SELECTIZE);
			$resources['load_js']  				= array(JS_SELECTIZE, JS_EDITOR);

			$data['salary_grade']      			= $this->cl->get_salary_grade();

			$data['salary_step']      			= $this->cl->get_salary_step();

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 				= $action;
			$data ['nav_page']					= CODE_LIBRARY_POSITION;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table         						= $this->cl->tbl_param_positions;
				$where         						= array();
				$key           						= $this->get_hash_key('position_id');
				$where[$key]   						= $id;
				$position_info 						= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				$position_duties_info				= $this->process_param_position_duties($id, NULL, NULL, 3);
				$position_info['position_duty_id']	= $position_duties_info['position_duty_id'];
				$position_info['duties']			= $position_duties_info['duties'];
				$data['position_info'] 				= $position_info;

				$resources['single']			= array(
					'position_level' 			=> $data['position_info']['position_level_id'],
					'position_class' 			=> $data['position_info']['position_class_id'],
					'salary_grade' 				=> $data['position_info']['salary_grade'],
					'salary_step' 				=> $data['position_info']['salary_step']
				);
			}

			$field 								= array("*") ;
			$table								= $this->cl->tbl_param_position_levels;
			$where								= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;			
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['position_level_id'] 	= array($position_info['position_level_id'], array("=", ")"));				
			}
			$data['position_level_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			$field 								= array("*") ;
			$table								= $this->cl->tbl_param_position_class_levels;
			$where								= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;			
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['position_class_level_id'] 	= array($position_info['position_class_id'], array("=", ")"));				
			}
			$data['position_class_level_name'] 	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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
		
		$this->load->view('code_library/modals/modal_position', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function process()
	{
		try
		{
			$status = 0;
			$params	= get_params();
			$duties = $_POST['duties'];
			
			
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
			$valid_data 					= $this->_validate_data_position($params);
// 			$this->_validate_duties($duties);

			//SET FIELDS VALUE
			$fields['position_name']  		= $valid_data['position_name'];
			$fields['position_level_id'] 	= $valid_data['position_level'];
			$fields['position_class_id'] 	= $valid_data['position_class'];
			$fields['salary_grade']   		= $valid_data['salary_grade'];
			$fields['salary_step']    		= $valid_data['salary_step'];
			$fields['general_function']    	= $valid_data['general_function'];
			$fields['education']			= $valid_data['education'];
			$fields['experience']			= $valid_data['experience'];
			$fields['eligibility']			= $valid_data['eligibility'];
			$fields['training']			= $valid_data['training'];
			$fields['active_flag']    		= isset($valid_data['active_flag']) ? "Y" : "N";
			

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 							= $this->cl->tbl_param_positions;
				
			if(EMPTY($params['id']))
			{				
				//INSERT DATA
				$new_id = $this->cl->insert_code_library($table, $fields, TRUE);
				
				//INSERT DATA INTO param_duties
				$this->process_param_position_duties($new_id, NULL, $duties, 1);
				
				//SET AUDIT TRAIL DETAILS
				$audit_table[]				= $this->cl->tbl_param_positions;
				$audit_schema[]				= DB_MAIN;
				$prev_detail[]  			= array();
				$curr_detail[]  			= array($fields);
				$audit_action[] 			= AUDIT_INSERT;						
				
				//MESSAGE ALERT
				$message             		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity            		= "%s has been added";
			}
			else
			{
				//WHERE 
				$where          = array();
				$key            = $this->get_hash_key('position_id');
				$where[$key]    = $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);
				
				//UPDATE DATA param_position_duties
				$this->process_param_position_duties($params['id'], $params['position_duty_id'], $duties, 2);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_positions;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;
				
				//MESSAGE ALERT
				$message        = $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity       = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['position_name']);
	
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
			$table 				= $this->cl->tbl_param_positions;
			$where				= array();
			$key 				= $this->get_hash_key('position_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			
			$this->cl->delete_code_library($this->cl->tbl_param_position_duties, $where);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['position_name']);
			
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
			"table_id" 			=> 'position_table',
			"path"				=> PROJECT_MAIN . '/code_library_hr/position/get_position_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_position($params)
	{
		//SPECIFY HERE INPUTS FROM USER
		$fields                   	= array();
		$fields['position_name']  	= "Position Name";
		$fields['position_level']	= "Level";
		$fields['position_class']	= "Class";
		// $fields['salary_grade']  	= "Salary Grade";
		// $fields['salary_step']   	= "Salary Step";
// 		$fields['general_function'] = "General Function";
		$fields['education']		= "Education";
		$fields['experience']		= "Experience";
		$fields['eligibility']		= "Eligibility";
		$fields['training']		= "Training";
		
		$this->check_required_fields($params, $fields);
			
		return $this->_validate_position_input($params);
	}

	private function _validate_position_input($params) 
	{
		try {
			$validation ['position_name'] 	= array (
				'data_type' 				=> 'string',
				'name'     					=> 'Position Name',
				'max_len'   				=> 100
			);
			$validation ['active_flag']		= array (
				'data_type' 				=> 'string',
				'name'      				=> 'Active Flag',
				'max_len'  					=> 1 
			);
			$validation ['position_level'] 	= array (
				'data_type' 				=> 'string',
				'name'     					=> 'Level',
				'max_len'   				=> 100
			);
			$validation ['position_class'] 	= array (
				'data_type' 				=> 'string',
				'name'      				=> 'Class',
				'max_len'   				=> 100
			);
			$validation ['salary_grade'] 	= array (
				'data_type' 				=> 'digit',
				'name'      				=> 'Salary Grade',
				'max_len'   				=> 2 
			);
			$validation ['salary_step'] 	= array (
				'data_type'					=> 'digit',
				'name'      				=> 'Salary Step',
				'max_len'   				=> 2	
			);
			$validation ['general_function'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'General Function',
			);
			$validation ['duties'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'Duties',
			);
			$validation ['education'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'Education',
					// 'max_len'   				=> 100
					'max_len'   				=> 110
			);
			$validation ['experience'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'Experience',
					'max_len'   				=> 100
			);
			$validation ['eligibility'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'Eligibility',
					'max_len'   				=> 100
			);
			$validation ['training'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'Training',
					'max_len'   				=> 100
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	public function _validate_duties($duties)
	{
		try 
		{
			if(EMPTY($duties))
				throw new Exception("Duties is required.");
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	public function process_param_position_duties($id, $duty_id = NULL, $duties = NULL, $process)
	{
		$table	= $this->cl->tbl_param_position_duties;
		
		switch($process)
		{
			// 1 = INSERT
			case 1:
				$fields['position_id'] 	= $id;
				$fields['duties']		= $duties;
				$this->cl->insert_code_library($table, $fields, TRUE);
				break;
				
			// 2 = UPDATE
			case 2:
				$where						= array();
				$where['position_duty_id']	= $duty_id;
				$key           				= $this->get_hash_key('position_id');
				$where[$key]   				= $id;
				
				$fields['duties'] 			= $duties;
				$this->cl->update_code_library($table, $fields, $where);
				break;
				
			// 3 = GET
			case 3:
				$where					= array();
				$key           			= $this->get_hash_key('position_id');
				$where[$key]   			= $id;
				return $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);
				break;
			
		}
		
	}
}

/* End of file Position.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Position.php */