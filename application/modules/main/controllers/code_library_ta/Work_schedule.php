<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_schedule extends Main_Controller {
	private $module = MODULE_TA_WORK_SCHEDULE;

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
			$resources['load_css'] 		= array(CSS_DATATABLE, CSS_CALENDAR, CSS_COLORPICKER);
			$resources['load_js']		= array(JS_DATATABLE, JS_CALENDAR, JS_CALENDAR_MOMENT, JS_COLORPICKER, JS_COLORGROUP);
			$resources['datatable'][]	= array('table_id' => 'work_schedule_table', 'path' => 'main/code_library_ta/work_schedule/get_work_schedule_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_work_schedule' 		=> array(
					'controller'		=> 'code_library_ta/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_work_schedule',
					'multiple'			=> true,
					'height'			=> '700px',
					'size'				=> 'sm',
					'title'				=> 'Work Schedule'
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

		$this->load->view('code_library/tabs/work_schedule', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_work_schedule_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("work_schedule_id","work_schedule_name","active_flag");
			$bColumns					= array("work_schedule_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_work_schedules;
			$where						= array();
			$work_schedules 			= $this->cl->get_work_schedule_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(work_schedule_id)) AS count"), $this->cl->tbl_param_work_schedules, NULL, FALSE);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($work_schedules),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_WORK_SCHEDULE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_WORK_SCHEDULE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_WORK_SCHEDULE, ACTION_DELETE);

			$cnt = 0;
			foreach ($work_schedules as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$id 					= $this->hash ($aRow["work_schedule_id"]);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("work schedule", "'.$url_delete.'")';
				

				$row[] = strtoupper($aRow['work_schedule_name']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_work_schedule' onclick=\"modal_work_schedule_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_work_schedule' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_work_schedule_init('".$edit_action."')\"></a>";
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

	public function modal_work_schedule($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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

			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data ['action_id']    = $action;
			$data ['action']       = $action;
			$data ['salt']         = $salt;
			$data ['token']        = $token;
			$data ['id']           = $id;
			if(!EMPTY($id))
			{
				$fields = array(
					"work_schedule_name",
					"break_hours",
					"break_time",
					"active_flag",
					"time_format(mon_earliest_in,'%h:%i %p') mon_earliest_in",
					"time_format(mon_latest_in,'%h:%i %p') mon_latest_in",
					
					//marvin
					"mon_type_of_duty",
					
					"time_format(tue_earliest_in,'%h:%i %p') tue_earliest_in",
					"time_format(tue_latest_in,'%h:%i %p') tue_latest_in" ,
					
					//marvin
					"tue_type_of_duty" ,
					
					"time_format(wed_earliest_in,'%h:%i %p') wed_earliest_in",
					"time_format(wed_latest_in,'%h:%i %p') wed_latest_in" ,
					
					//marvin
					"wed_type_of_duty" ,
					
					"time_format(thu_earliest_in,'%h:%i %p') thu_earliest_in",
					"time_format(thu_latest_in,'%h:%i %p') thu_latest_in" ,
					
					//marvin
					"thu_type_of_duty" ,
					
					"time_format(fri_earliest_in,'%h:%i %p') fri_earliest_in",
					"time_format(fri_latest_in,'%h:%i %p') fri_latest_in" ,
					
					//marvin
					"fri_type_of_duty" ,
					
					"time_format(sat_earliest_in,'%h:%i %p') sat_earliest_in",
					"time_format(sat_latest_in,'%h:%i %p') sat_latest_in" ,
					
					//marvin
					"sat_type_of_duty" ,
					
					"time_format(sun_earliest_in,'%h:%i %p') sun_earliest_in",
					"time_format(sun_latest_in,'%h:%i %p') sun_latest_in",

					//marvin
					"sun_type_of_duty" 
					);
				$table 						= $this->cl->tbl_param_work_schedules;
				$where						= array();
				$key 						= $this->get_hash_key('work_schedule_id');
				$where[$key]				= $id;
				$data['work_schedule_info'] 		= $this->cl->get_code_library_data($fields, $table, $where, FALSE);	
				
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
		$this->load->view('code_library/modals/modal_work_schedule', $data);
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
			$valid_data 				= $this->_validate_data_work_schedule($params);

			//SET FIELDS VALUE
			$fields                       = array();
			$fields['work_schedule_name'] = $valid_data['work_schedule_name'];
			$fields['break_hours']        = $valid_data['break_hours'];
			$fields['break_time']         = $valid_data['break_time'];
			$fields['mon_earliest_in']    = !EMPTY($valid_data['mon_earliest_in']) 	? $valid_data['mon_earliest_in'] 	: NULL;
			$fields['mon_latest_in']      = !EMPTY($valid_data['mon_latest_in']) 	? $valid_data['mon_latest_in'] 		: NULL;
			
			//marvin
			$fields['mon_type_of_duty']      = !EMPTY($valid_data['mon_type_of_duty']) 	? $valid_data['mon_type_of_duty'] 		: NULL;
			
			$fields['tue_earliest_in']    = !EMPTY($valid_data['tue_earliest_in']) 	? $valid_data['tue_earliest_in'] 	: NULL;
			$fields['tue_latest_in']      = !EMPTY($valid_data['tue_latest_in']) 	? $valid_data['tue_latest_in'] 		: NULL;
			
			//marvin
			$fields['tue_type_of_duty']      = !EMPTY($valid_data['tue_type_of_duty']) 	? $valid_data['tue_type_of_duty'] 		: NULL;
			
			$fields['wed_earliest_in']    = !EMPTY($valid_data['wed_earliest_in']) 	? $valid_data['wed_earliest_in'] 	: NULL;
			$fields['wed_latest_in']      = !EMPTY($valid_data['wed_latest_in']) 	? $valid_data['wed_latest_in'] 		: NULL;
			
			//marvin
			$fields['wed_type_of_duty']      = !EMPTY($valid_data['wed_type_of_duty']) 	? $valid_data['wed_type_of_duty'] 		: NULL;
			
			$fields['thu_earliest_in']    = !EMPTY($valid_data['thu_earliest_in']) 	? $valid_data['thu_earliest_in'] 	: NULL;
			$fields['thu_latest_in']      = !EMPTY($valid_data['thu_latest_in']) 	? $valid_data['thu_latest_in'] 		: NULL;
			
			//marvin
			$fields['thu_type_of_duty']      = !EMPTY($valid_data['thu_type_of_duty']) 	? $valid_data['thu_type_of_duty'] 		: NULL;
			
			$fields['fri_earliest_in']    = !EMPTY($valid_data['fri_earliest_in']) 	? $valid_data['fri_earliest_in'] 	: NULL;
			$fields['fri_latest_in']      = !EMPTY($valid_data['fri_latest_in']) 	? $valid_data['fri_latest_in'] 		: NULL;
			
			//marvin
			$fields['fri_type_of_duty']      = !EMPTY($valid_data['fri_type_of_duty']) 	? $valid_data['fri_type_of_duty'] 		: NULL;
			
			$fields['sat_earliest_in']    = !EMPTY($valid_data['sat_earliest_in']) 	? $valid_data['sat_earliest_in'] 	: NULL;
			$fields['sat_latest_in']      = !EMPTY($valid_data['sat_latest_in']) 	? $valid_data['sat_latest_in'] 		: NULL;
			
			//marvin
			$fields['sat_type_of_duty']      = !EMPTY($valid_data['sat_type_of_duty']) 	? $valid_data['sat_type_of_duty'] 		: NULL;
			
			$fields['sun_earliest_in']    = !EMPTY($valid_data['sun_earliest_in']) 	? $valid_data['sun_earliest_in'] 	: NULL;
			$fields['sun_latest_in']      = !EMPTY($valid_data['sun_latest_in']) 	? $valid_data['sun_latest_in'] 		: NULL;
			
			//marvin
			$fields['sun_type_of_duty']      = !EMPTY($valid_data['sun_type_of_duty']) 	? $valid_data['sun_type_of_duty'] 		: NULL;
			
			$fields['active_flag']        = isset($valid_data['active_flag']) 		? "Y" : "N";

			$audit_fields                       = array();
			$audit_fields['work_schedule_name'] = $valid_data['work_schedule_name'];
			$audit_fields['break_hours']        = $valid_data['break_hours'];
			$audit_fields['break_time']         = $valid_data['break_time'];
			$audit_fields['mon_earliest_in']    = $valid_data['mon_earliest_in'];
			$audit_fields['mon_latest_in']      = $valid_data['mon_latest_in'];
			
			//marvin
			$audit_fields['mon_type_of_duty']      = $valid_data['mon_type_of_duty'];
			
			$audit_fields['tue_earliest_in']    = $valid_data['tue_earliest_in'];
			$audit_fields['tue_latest_in']      = $valid_data['tue_latest_in'];
			
			//marvin
			$audit_fields['tue_type_of_duty']      = $valid_data['tue_type_of_duty'];
			
			$audit_fields['wed_earliest_in']    = $valid_data['wed_earliest_in'];
			$audit_fields['wed_latest_in']      = $valid_data['wed_latest_in'];
			
			//marvin
			$audit_fields['wed_type_of_duty']      = $valid_data['wed_type_of_duty'];
			
			$audit_fields['thu_earliest_in']    = $valid_data['thu_earliest_in'];
			$audit_fields['thu_latest_in']      = $valid_data['thu_latest_in'];
			
			//marvin
			$audit_fields['thu_type_of_duty']      = $valid_data['thu_type_of_duty'];
			
			$audit_fields['fri_earliest_in']    = $valid_data['fri_earliest_in'];
			$audit_fields['fri_latest_in']      = $valid_data['fri_latest_in'];
			
			//marvin
			$audit_fields['fri_type_of_duty']      = $valid_data['fri_type_of_duty'];
			
			$audit_fields['sat_earliest_in']    = $valid_data['sat_earliest_in'];
			$audit_fields['sat_latest_in']      = $valid_data['sat_latest_in'];
			
			//marvin
			$audit_fields['sat_type_of_duty']      = $valid_data['sat_type_of_duty'];
			
			$audit_fields['sun_earliest_in']    = $valid_data['sun_earliest_in'];
			$audit_fields['sun_latest_in']      = $valid_data['sun_latest_in'];
			
			//marvin
			$audit_fields['sun_type_of_duty']      = $valid_data['sun_type_of_duty'];
			
			$audit_fields['active_flag']        = isset($valid_data['active_flag'])? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_work_schedules;
			$audit_table[]	= $table;
			$audit_schema[]	= DB_MAIN;
			$curr_detail[] 	= array($audit_fields);	
				
			if(EMPTY($params['id']))
			{
				
				$work_schedule_id = $this->cl->insert_code_library($table, $fields, TRUE);
				
				$message          = $this->lang->line('data_saved');
				
				$audit_action[]   = AUDIT_INSERT;				
				$prev_detail[]    = array();
				$activity         = "%s has been added";		

			}
			else
			{
				
				$where			= array();
				$key 			= $this->get_hash_key('work_schedule_id');
				$where[$key]	= $params['id'];
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				$audit_action[]	= AUDIT_UPDATE;
				$activity 		= "%s has been updated";

				
				$this->cl->update_code_library($table, $fields, $where);
				$message 		= $this->lang->line('data_updated');				
			}
			
			$activity = sprintf($activity, $params['work_schedule_name']);
	
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
				
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_work_schedules;
			$where				= array();
			$key 				= $this->get_hash_key('work_schedule_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
	

			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
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
			"table_id" 			=> 'work_schedule_table',
			"path"				=> PROJECT_MAIN . '/code_library_ta/work_schedule/get_work_schedule_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_work_schedule($params)
	{
		$fields                       = array();
		$fields['work_schedule_name'] = "Work Schedule Name";
		$fields['break_hours']        = "Break Hours";

		$this->check_required_fields($params, $fields);	
		
		return $this->_validate_work_schedule_input ($params);			
	}

	private function _validate_work_schedule_input($params) 
	{
		try {
			
			$validation ['work_schedule_name'] = array (
					'data_type' 			=> 'string',
					'name' 					=> 'Work Schedule Name',
					'max_len'				=> 50 
			);
			$validation ['break_hours'] = array (
					'data_type' 			=> 'amount',
					'name' 					=> 'Break Hours',
					'decimal' 				=> 2
			);
			
			//marvin
			$validation ['break_time'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Break Time'
			);
			
			$validation ['mon_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Monday Earliest In'
			);
			$validation ['mon_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Monday Latest In'
			);
			
			//marvin
			$validation ['mon_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Monday Duty'
			);
			
			$validation ['tue_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Tuesday Earliest In'
			);
			$validation ['tue_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Tuesday Latest In'
			);
			
			//marvin
			$validation ['tue_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Tuesday Duty'
			);
			
			$validation ['wed_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Wednesday Earliest In'
			);
			$validation ['wed_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Wednesday Latest In'
			);
			
			//marvin
			$validation ['wed_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Wednesday Duty'
			);
			
			$validation ['thu_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Thursday Earliest In'
			);
			$validation ['thu_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Thursday Latest In'
			);
			
			//marvin
			$validation ['thu_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Thursday Duty'
			);
			
			$validation ['fri_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Friday Earliest In'
			);
			$validation ['fri_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Friday Latest In'
			);
			
			//marvin
			$validation ['fri_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Friday Duty'
			);
			
			$validation ['sat_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Saturday Earliest In'
			);
			$validation ['sat_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Saturday Latest In'
			);
			
			//marvin
			$validation ['sat_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Saturday Duty'
			);
			
			$validation ['sun_earliest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Sunday Earliest In'
			);
			$validation ['sun_latest_in'] = array (
					'data_type' 			=> 'time',
					'name' 					=> 'Sunday Latest In'
			);
			
			//marvin
			$validation ['sun_type_of_duty'] = array (
					'data_type' 			=> 'tinyint',
					'name' 					=> 'Sunday Duty'
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
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Work_schedule.php */