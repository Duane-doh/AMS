<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_calendar extends Main_Controller {
	private $module = MODULE_TA_WORK_CALENDAR;

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
			$resources['load_css']		= array(CSS_DATATABLE, CSS_CALENDAR, CSS_COLORPICKER);
			$resources['load_js']		= array(JS_DATATABLE, JS_COLORPICKER, JS_COLORGROUP, JS_CALENDAR, JS_CALENDAR_MOMENT);
			$resources['datatable'][]	= array('table_id' => 'work_calendar_table', 'path' => 'main/code_library_ta/work_calendar/get_work_calendar_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_work_calendar' 	=> array(
					'controller'		=> 'code_library_ta/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_work_calendar',
					'multiple'			=> true,
					'height'			=> '280px',
					'size'				=> 'sm',
					'title'				=> 'Work Calendar'
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
		$this->load_resources->get_resource($resources);		
		$this->load->view('code_library/tabs/work_calendar', $data);
	}

	public function get_work_calendar_list()
	{
		try
		{
			$params 					= get_params();
			
			$aColumns					= array("title", "description", "DATE_FORMAT(holiday_date, '%Y/%m/%d') as holiday_date", 'work_calendar_id', 'holiday_type_id', 'active_flag', 'start_time', 'end_time');
			$bColumns					= array("title", "description", "DATE_FORMAT(holiday_date, '%Y/%m/%d')");
			$table 	  					= $this->cl->tbl_param_work_calendar;
			$where						= array();
			$work_calendar				= $this->cl->get_work_calendar_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(work_calendar_id)) AS count"), $this->cl->tbl_param_work_calendar, NULL, false);
			$iFilteredTotal 			= $this->cl->work_calendar_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
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

				$row[] = strtoupper($aRow['title']);
				$row[] = strtoupper($aRow['description']);
				$row[] = '<center>' . format_date($aRow['holiday_date']) . '</center>';

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
	
	public function modal_work_calendar($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 			   			= array();	
			$resources['load_css']          = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']			= array(JS_DATETIMEPICKER, JS_SELECTIZE);
			
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
				$fields 					= array('title', 'description', 'DATE_FORMAT(holiday_date, "%Y/%m/%d") as holiday_date', 'work_calendar_id', 'holiday_type_id', 'active_flag', 'TIME_FORMAT(start_time, "%h:%i %p") AS start_time', 'TIME_FORMAT(end_time, "%h:%i %p") AS end_time');
				$table 						= $this->cl->tbl_param_work_calendar;
				$where						= array();
				$key 						= $this->get_hash_key('work_calendar_id');
				$where[$key]				= $id;
				$work_calendar_info  		= $this->cl->get_code_library_data($fields, $table, $where, FALSE);	
				$data['work_calendar_info']	= $work_calendar_info;

				$resources['single'] 		= array(
					'holiday_type' 			=> $work_calendar_info['holiday_type_id']
				);
			}

			$field 							= array("*") ;
			$table							= $this->cl->tbl_param_holiday_types;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['holiday_type_id'] 	= array($work_calendar_info['holiday_type_id'], array("=", ")"));				
			}
			$data['holiday_type_name'] 		= $this->cl->get_code_library_data($field, $table, $where, TRUE);
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
			"path"				=> PROJECT_MAIN . '/code_library_ta/work_calendar/get_work_calendar_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
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
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */