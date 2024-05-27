<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_type extends Main_Controller {
	private $module = MODULE_TA_HOLIDAY_TYPE;

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
			$resources['load_css'] 		= array(CSS_DATATABLE, CSS_COLORPICKER);
			$resources['load_js']		= array(JS_DATATABLE, JS_COLORPICKER, JS_COLORGROUP);
			$resources['datatable'][] = array('table_id' => 'holiday_type_table', 'path' => 'main/code_library_ta/holiday_type/get_holiday_type_list', 'advanced_filter' => TRUE);
			$resources['load_modal']    = array(
				'modal_holiday_type' 	=> array(
					'controller'		=> 'code_library_ta/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_holiday_type',
					'multiple'			=> true,
					'height'			=> '280px',
					'size'				=> 'sm',
					'title'				=> 'Holiday Type'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_ta/'.__CLASS__,
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

		$this->load->view('code_library/tabs/holiday_type', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_holiday_type_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("holiday_type_name", "color_code", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_holiday_types;
			$where						= array();
			$holiday_type				= $this->cl->get_holiday_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(holiday_type_id)) AS count"), $this->cl->tbl_param_holiday_types, NULL, false);
			$iFilteredTotal 			= $this->cl->holiday_type_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_HOLIDAY_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_HOLIDAY_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_HOLIDAY_TYPE, ACTION_DELETE);

			$cnt = 0;
			foreach ($holiday_type as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "";

				$action 				= "<div class='table-actions'>";
			
				$holiday_type_id 		= $aRow["holiday_type_id"];
				$id 					= $this->hash ($holiday_type_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("holiday type", "'.$url_delete.'")';
		
				$row[] = strtoupper($aRow['holiday_type_name']);
				$row[] = "<div style='height: 25px; background-color: #".$aRow['color_code']."'></div>";;
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_holiday_type' onclick=\"modal_holiday_type_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_holiday_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_holiday_type_init('".$edit_action."')\"></a>";
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

	public function modal_holiday_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_SELECTIZE);

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			
			$data['nav_page']				= CODE_LIBRARY_HOLIDAY_TYPE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_holiday_types;
				$where						= array();
				$key 						= $this->get_hash_key('holiday_type_id');
				$where[$key]				= $id;
				$holiday_type_info  		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['holiday_type_info']	= $holiday_type_info;	

				$resources['single']		= array(
					'attendance_status' 	=> $holiday_type_info['attendance_status_id']
				);	
			}

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_attendance_status;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;			
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['attendance_status_id'] 	= array($holiday_type_info['attendance_status_id'], array("=", ")"));				
			}
			$data['attendance_status'] 			= $this->cl->get_code_library_data($field, $table, $where, TRUE);
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
		
		$this->load->view('code_library/modals/modal_holiday_type', $data);
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
			$valid_data 					= $this->_validate_data_holiday_type($params);

			//SET FIELDS VALUE
			$fields['holiday_type_name'] 	= $valid_data['holiday_type_name'];
			$fields['attendance_status_id'] = $valid_data['attendance_status'];
			$fields['color_code']       	= $valid_data['group_color'];
			$fields['active_flag']   		= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_holiday_types;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]				= AUDIT_INSERT;
				
				$prev_detail[]				= array();

				//INSERT DATA
				$holiday_type_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 					= array();
				$where['holiday_type_id']	= $holiday_type_id;

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
				$key 			= $this->get_hash_key('holiday_type_id');
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
			
			$activity = sprintf($activity, $params['holiday_type_name']);
	
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
			$table 				= $this->cl->tbl_param_holiday_types;
			$where				= array();
			$key 				= $this->get_hash_key('holiday_type_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['holiday_type_name']);
	
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
			"table_id" 			=> 'holiday_type_table',
			"path"				=> PROJECT_MAIN . '/code_library_ta/holiday_type/get_holiday_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_holiday_type($params)
	{
		$fields                 		= array();
		$fields['holiday_type_name']  	= "Holiday Type Name";
		$fields['attendance_status']  	= "Attendance Status";
		$fields['group_color']  		= "Color Code";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_holiday_type_input ($params);
	}

	private function _validate_holiday_type_input($params) 
	{
		try {
			
			$validation ['holiday_type_name'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Holiday Type Name',
					'max_len' 					=> 50 
			);
			$validation ['attendance_status'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Attendance Status',
					'max_len' 					=> 3
			);
			$validation ['group_color'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Color Code',
					'max_len' 					=> 7 
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