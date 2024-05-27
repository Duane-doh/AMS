<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//RLog::info('LINE 2209 =>'.json_encode($fields));
class Code_library_ta extends Main_Controller {
	private $module = MODULE_ROLE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function get_tab($form, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data 					= array();
			$resources['load_css'] 	= array(CSS_LABELAUTY, CSS_SELECTIZE,CSS_DATETIMEPICKER, CSS_CALENDAR);
			$resources['load_js'] 	= array(JS_LABELAUTY,JS_SELECTIZE, JS_DATETIMEPICKER, JS_CALENDAR, JS_CALENDAR_MOMENT);
			$data['action_id']		= $action_id;
			switch ($form) 
			{
				/*----- START GET TAB TIME & ATTENDANCE -----*/
				case 'leave_type':
					$resources['load_css'] 		= array(CSS_DATATABLE, CSS_CALENDAR);
					$resources['load_js']		= array(JS_DATATABLE, JS_CALENDAR, JS_CALENDAR_MOMENT, JS_COLORPICKER, JS_COLORGROUP);
					//$resources['datatable'][]	= array('table_id' => 'leave_type_table', 'path' => 'main/code_library_ta/get_leave_type_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_leave_type' 		=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_leave_type',
							'multiple'			=> true,
							'height'			=> '150px',
							'size'				=> 'sm',
							'title'				=> 'Leave Type'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_leave_type',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'holiday_type':
					$resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE, CSS_COLORPICKER);
					$resources['load_js'] 		= array(JS_DATATABLE, JS_SELECTIZE);
					$resources['datatable'][]	= array('table_id' => 'holiday_type_table', 'path' => 'main/code_library_ta/get_holiday_type_list', 'advanced_filter' => TRUE);
					$resources['load_modal']    = array(
						'modal_holiday_type' 	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_holiday_type',
							'multiple'			=> true,
							'height'			=> '280px',
							'size'				=> 'sm',
							'title'				=> 'Holiday Type'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_holiday_type',
						PROJECT_MAIN
					);
					$view_form = $form;	
				break;

				case 'work_calendar':
					$resources['load_css']		= array(CSS_DATATABLE, CSS_CALENDAR);
					$resources['load_js']		= array(JS_DATATABLE, JS_MODAL_EFFECTS);
					$resources['datatable'][]	= array('table_id' => 'work_calendar_table', 'path' => 'main/code_library_ta/get_work_calendar_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
						'modal_work_calendar' 	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_work_calendar',
							'multiple'			=> true,
							'height'			=> '280px',
							'size'				=> 'sm',
							'title'				=> 'Work Calendar'
						)
					);
					$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_work_calendar',
						PROJECT_MAIN
					);
					$view_form = $form;	
				break;
				/*----- END GET TAB TIME & ATTENDANCE -----*/

			}

			$data['action_id'] = $action_id;
			$this->load->view('code_library/tabs/'.$view_form, $data);
			$this->load_resources->get_resource($resources);
		
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
	}

	/*----- START PROCESS TIME & ATTENDANCE -----*/

	public function process_leave_type()
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
			$valid_data 				= $this->_validate_data_leave_type($params);

			//SET FIELDS VALUE
			$fields['leave_type_name'] 	= $valid_data['leave_type_name'];
			$fields['active_flag']     	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_leave_types;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]			= AUDIT_INSERT;
				
				$prev_detail[]			= array();

				//INSERT DATA
				$leave_type_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 				= array();
				$where['leave_type_id']	= $leave_type_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('leave_type_id');
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
			
			$activity = sprintf($activity, $params['leave_type_name']);
	
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

	public function process_holiday_type()
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

	public function process_work_calendar()
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
			$valid_data 				= $this->_validate_data_work_calendar($params);

			//SET FIELDS VALUE
			$fields['holiday_type_id'] 	= $valid_data['holiday_type'];
			$fields['title']          	= $valid_data['title'];
			$fields['description']    	= $valid_data['description'];
			$fields['holiday_date']    	= $valid_data['holiday_date'];
			$fields['start_time']    	= $valid_data['start_time'];
			$fields['end_time']    		= $valid_data['end_time'];

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_work_calendar;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]				= AUDIT_INSERT;
				
				$prev_detail[]				= array();

				//INSERT DATA
				$work_calendar_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				//MESSAGE ALERT
				$message 					= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 					= array();
				$where['work_calendar_id']	= $work_calendar_id;

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
				$key 			= $this->get_hash_key('work_calendar_id');
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
				$activity 	= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['work_calendar_id']);
	
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
	/*----- END PROCESS TIME & ATTENDANCE -----*/

	/*------------------------TIME & ATTENDANCE VALIDATE DATA START ----------------------------*/
	
	private function _validate_data_leave_type($params)
	{
		$fields                 	= array();
		$fields['leave_type_name']  = "Leave Type Name";

		$this->check_required_fields($params, $fields);	
		
		return $this->_validate_leave_type_input ($params);
	}

	private function _validate_leave_type_input($params) 
	{
		try {
			
			$validation ['leave_type_name'] = array (
					'data_type' 			=> 'string',
					'name' 					=> 'Leave Type Name',
					'max_len'				=> 50 
			);
			$validation ['active_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Active Flag',
					'max_len' 				=> 1 
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
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

	private function _validate_data_work_calendar($params)
	{
		$fields                 	= array();
		$fields['holiday_type']  	= "Holiday Type";
		$fields['title']  			= "Title";
		$fields['description']  	= "Description";
		$fields['holiday_date']  	= "Date";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_work_calendar_input ($params);
	}

	private function _validate_work_calendar_input($params) 
	{
		try {
			
			$validation ['holiday_type']	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Holiday Type Name'
			);
			$validation ['title'] 			= array (
					'data_type'				=> 'string',
					'name' 					=> 'Title',
					'max_len' 				=> 50 
			);
			$validation ['description'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Description',
					'max_len' 				=> 50
			);
			$validation ['holiday_date'] 	= array (
					'data_type' 			=> 'date',
					'name' 					=> 'Holiday Date',
					'max_len' 				=> 50
			);
			$validation ['start_time'] 		= array (
					'data_type' 			=> 'time',
					'name' 					=> 'Start Time'
			);
			$validation ['end_time'] 		= array (
					'data_type' 			=> 'time',
					'name' 					=> 'End Time'
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	/*------------------------TIME & ATTENDANCE VALIDATE DATA END ----------------------------*/

	/*----- START GET LIST TIME & ATTENDANCE -----*/
	public function get_leave_type_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("leave_type_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_leave_types;
			$where						= array();
			$leave_types 				= $this->cl->get_leave_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(leave_type_id)) AS count"), $this->cl->tbl_param_leave_types, NULL, false);
	
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($leave_types),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_DELETE);

			$cnt = 0;
			foreach ($leave_types as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$leave_type_id 			= $aRow["leave_type_id"];
				$id 					= $this->hash ($leave_type_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("leave type", "'.$url_delete.'")';
				

				$row[] = $aRow['leave_type_name'];
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_leave_type' onclick=\"modal_leave_type_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_leave_type' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_leave_type_init('".$edit_action."')\"></a>";
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

	public function get_holiday_type_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("holiday_type_name", "color_code", "active_flag");
			$table 	  					= $this->cl->tbl_param_holiday_types;
			$where						= array();
			$holiday_type				= $this->cl->get_holiday_type_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(holiday_type_id)) AS count"), $this->cl->tbl_param_holiday_types, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($holiday_type),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
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
		
				$row[] = $aRow['holiday_type_name'];
				$row[] = "<div style='height: 25px; background-color: ".$aRow['color_code']."'></div>";;
				$row[] = ($aRow['active_flag'] == "Y") ? Y:N;

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

	public function get_work_calendar_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("title", "desciption", "holiday_date");
			$table 	  					= $this->cl->tbl_param_work_calendar;
			$where						= array();
			$work_calendar				= $this->cl->get_work_calendar_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(work_calendar_id)) AS count"), $this->cl->tbl_param_work_calendar, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($work_calendar),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_WORK_CALENDAR, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_WORK_CALENDAR, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_WORK_CALENDAR, ACTION_DELETE);

			$cnt = 0;
			foreach ($work_calendar as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "";

				$action 				= "<div class='table-actions'>";
			
				$work_calendar_id 		= $aRow["work_calendar_id"];
				$id 					= $this->hash ($work_calendar_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("work calendar", "'.$url_delete.'")';

				$row[] = $aRow['title'];
				$row[] = $aRow['description'];
				$row[] = $aRow['holiday_date'];

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_work_calendar' onclick=\"modal_work_calendar_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_work_calendar' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_work_calendar_init('".$edit_action."')\"></a>";
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

	public function get_work_calendar_info()
	{
		$data = array();
		try
		{		
			$holidays = $this->cl->get_work_calendar_info();
			$events	  = array();

			foreach($holidays as $sched)
			{
				$events[] 	= array(
					'id' 	=> $sched['work_calendar_id'], 
					'title' => $sched['title'],
					'start' => $sched['holiday_date'],
					'color' => $sched['color_code']
				);
			}

			echo json_encode($events);
			return;
		
		}
			catch(PDOException $e){
				$message = $e->getMessage();
				RLog::error($message);
				$message = $this->lang->line('err_internal_server');
			}
			catch(Exception $e){
				$message = $e->getMessage();
				RLog::error($message);
			}

			echo json_encode($data);
	}
	/*----- END GET LIST TIME & ATTENDANCE -----*/

	/*----- START DELETE TIME & ATTENDANCE -----*/
	public function delete_leave_type()
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
			$table 				= $this->cl->tbl_param_leave_types;
			$where				= array();
			$key 				= $this->get_hash_key('leave_type_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['leave_type_name']);
	
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
			"table_id" 			=> 'leave_type_table',
			"path"				=> PROJECT_MAIN . '/code_library/get_leave_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_holiday_type()
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
			"table_id" 			=> 'holiday_type_table',
			"path"				=> PROJECT_MAIN . '/code_library/get_holiday_type_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	public function delete_work_calendar()
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
			$table 				= $this->cl->tbl_param_work_calendar;
			$where				= array();
			$key 				= $this->get_hash_key('work_calendar_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['title']);
	
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
			"table_id" 			=> 'work_calendar_table',
			"path"				=> PROJECT_MAIN . '/code_library/get_work_calendar_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}
	/*----- END DELETE TIME & ATTENDANCE -----*/


	public function modal($modal = NULL, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			
			$data['action_id'] 		  = $action_id;
			switch ($modal) 
			{
				case 'leave_type':
					$data['nav_page'] = CODE_LIBRARY_LEAVE_TYPE;
				break;
				case 'holiday_type':
					$data['nav_page'] = CODE_LIBRARY_HOLIDAY_TYPE;
				break;
				case 'work_calendar':
					$data['nav_page'] = CODE_LIBRARY_WORK_CALENDAR;
				break;
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
		
		$this->load->view('code_library/modals/modal_code_library', $data);
		$this->load_resources->get_resource($resources);
	}

	/*----- START MODAL TIME & ATTENDANCE -----*/
	public function modal_leave_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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

			$resources          			= array();
			$data ['action_id'] 			= $action;
			$data ['nav_page']  			= CODE_LIBRARY_LEAVE_TYPE;
			$data ['action']    			= $action;
			$data ['salt']      			= $salt;
			$data ['token']    				= $token;
			$data ['id']       			 	= $id;
			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_leave_types;
				$where						= array();
				$key 						= $this->get_hash_key('leave_type_id');
				$where[$key]				= $id;
				$leave_type_info 			= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['leave_type_info']	= $leave_type_info;
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
		
		$this->load->view('code_library/modals/modal_leave_type', $data);
	}

	public function modal_holiday_type($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_SELECTIZE);

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_attendance_status;
			$where							= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['attendance_status'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);

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

	public function modal_work_calendar($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 			   			= array();	
			$resources['load_css']          = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']			= array(JS_DATETIMEPICKER, JS_SELECTIZE);
			//GET HOLIDAY TYPE 
			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_holiday_types;
			$where							= array();
			if($action != ACTION_VIEW) {
				$where['active_flag']		= YES;
			}
			$data['holiday_type_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);
			
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					//throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					//throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_WORK_CALENDAR;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;

			if(!EMPTY($id))
			{
				//EDIT
				$table 						= $this->cl->tbl_param_work_calendar;
				$where						= array();
				$key 						= $this->get_hash_key('work_calendar_id');
				$where[$key]				= $id;
				$work_calendar_info  		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				
				$data['work_calendar_info']	= $work_calendar_info;

				$resources['single'] 		= array(
					'holiday_type' 			=> $data['work_calendar_info']['holiday_type_id']
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
		
		$this->load->view('code_library/modals/modal_work_calendar', $data);
		$this->load_resources->get_resource($resources);
	}
	/*----- END MODAL TIME & ATTENDANCE -----*/

}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_ta.php */