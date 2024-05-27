<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personnel_movement extends Main_Controller {
	private $module = MODULE_HR_CL_PERSONNEL_MOVEMOVENT;

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
			$resources['datatable'][]		= array('table_id' => 'personnel_movement_table', 'path' => 'main/code_library_hr/personnel_movement/get_personnel_movement_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 		= array(
				'modal_personnel_movement' 	=> array(
					'controller'			=> 'code_library_hr/'.__CLASS__,
					'module'				=> PROJECT_MAIN,
					'method'				=> 'modal_personnel_movement',
					'multiple'				=> true,
					'height'				=> '300px',
					'size'					=> 'sm',
					'title'					=> 'Personnel Movement'
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

		$this->load->view('code_library/tabs/personnel_movement', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_personnel_movement_list()
	{

		try
		{
			$params 					= get_params();
				
			$aColumns					= array("*");
			$bColumns					= array(
										"personnel_movement_name",
										"IF(needs_appointment = 'Y', 'Yes', 'No')",
										"IF(needs_office_order = 'Y', 'Yes', 'No')",
										"IF(active_flag = 'Y', 'Active', 'Inactive')"
										);
			$table 	  					= $this->cl->tbl_param_personnel_movements;
			$where						= array();
			$personnel_movement 		= $this->cl->get_personnel_movement_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(personnel_movement_id)) AS count"), $this->cl->tbl_param_personnel_movements, NULL, false);
			$iFilteredTotal 			= $this->cl->personnel_movt_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_DELETE);

			$cnt = 0;
			foreach ($personnel_movement as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$personnel_movement_id 	= $aRow["personnel_movement_id"];
				$id 					= $this->hash ($personnel_movement_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("personnel movement", "'.$url_delete.'")';
				
				$row[] = strtoupper($aRow['personnel_movement_name']);
				$row[] = strtoupper(($aRow['needs_appointment'] == "Y") ? 'YES':'NO');
				$row[] = strtoupper(($aRow['needs_office_order'] == "Y") ? 'YES':'NO');
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_personnel_movement' onclick=\"modal_personnel_movement_init('".$view_action."')\"></a>";
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_personnel_movement' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_personnel_movement_init('".$edit_action."')\"></a>";
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

	public function modal_personnel_movement($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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

			$resources 							= array();
			$data['action_id'] 					= $action;
			$data['nav_page']					= CODE_LIBRARY_PERSONNEL_MOVEMENT;
			$data ['action'] 					= $action;
			$data ['salt'] 						= $salt;
			$data ['token'] 					= $token;
			$data ['id'] 						= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table                           = $this->cl->tbl_param_personnel_movements;
				$where                           = array();
				$key                             = $this->get_hash_key('personnel_movement_id');
				$where[$key]                     = $id;
				$personnel_movement_info         = $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);		
				
				$data['personnel_movement_info'] = $personnel_movement_info;
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
		
		$this->load->view('code_library/modals/modal_personnel_movement', $data);
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
			$valid_data 						= $this->_validate_data_personnel_movement($params);

			//SET FIELDS VALUE
			$fields['personnel_movement_name'] 	= $valid_data['personnel_movement_name'];
			$fields['needs_appointment'] 		= isset($valid_data['needs_appointment']) ? "Y" : "N";
			$fields['needs_office_order'] 		= isset($valid_data['needs_office_order']) ? "Y" : "N";
			$fields['replacement_reason'] 		= $valid_data['replacement_reason'];
			$fields['active_flag']				= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_personnel_movements;
				
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]					= $this->cl->tbl_param_personnel_movements;
				$audit_schema[]					= DB_MAIN;
				$prev_detail[]  				= array();
				$curr_detail[]  				= array($fields);
				$audit_action[] 				= AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 						= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been added";
			}
			else
			{
				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('personnel_movement_id');
				$where[$key]	= $params['id'];
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_personnel_movements;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;


				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
								
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['personnel_movement_id']);
	
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
			$table 				= $this->cl->tbl_param_personnel_movements;
			$where				= array();
			$key 				= $this->get_hash_key('personnel_movement_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['personnel_movement_id']);
	
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
			"flag"           	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'personnel_movement_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/personnel_movement/get_personnel_movement_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_personnel_movement($params)
	{
		$fields                 			= array();
		$fields['personnel_movement_name']  = "Personnel Movement Name";
		$fields['replacement_reason']  		= "Replacement Reason";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_personnel_movement_input ($params);
	}

	private function _validate_personnel_movement_input($params) 
	{
		try {
			
			$validation ['personnel_movement_name'] = array (
					'data_type' 					=> 'string',
					'name' 							=> 'Personnel Movement Name',
					'max_len' 						=> 100 
			);
			$validation ['needs_appointment'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Appointment',
					'max_len' 						=> 1
			);
			$validation ['needs_office_order'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Office Order',
					'max_len' 						=> 1
			);
			$validation ['replacement_reason'] 		= array (
					'data_type'						=> 'string',
					'name' 							=> 'Replacement Reason',
					'max_len' 						=> 255 
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

/* End of file Personnel_movement.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Personnel_movement.php */