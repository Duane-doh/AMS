<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_work_schedule extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_work_schedule_model', 'work_schedule');
	}
	public function get_schedule($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data 					= array();
			$resources 				= array();

			$resources['load_css']    = array(CSS_DATATABLE);
			$resources['load_js']     = array(JS_DATATABLE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}			
			
			$post_data                = array('employee_id' => $id);
			$resources['datatable'][] = array('table_id' => 'employee_work_schedule_table', 'path' => 'main/employee_work_schedule/get_work_schedule/', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));

			$resources['load_modal'] = array(
				'modal_employee_work_schedule' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_employee_work_schedule',
						'multiple'		=> true,
						'height'		=> '250px',
						'size'			=> 'sm',
						'title'			=> 'Employee Work Schedule'
				)
			);
			$resources['load_delete'] 		= array(
				__CLASS__,
				'delete_employee_work_schedule',
				PROJECT_MAIN
			);
			$this->load->view('employee_attendance/tabs/employee_work_schedule', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}
	public function get_work_schedule()
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.employee_work_schedule_id","A.start_date","A.end_date","B.work_schedule_name");
			$bColumns       = array("A.start_date","A.end_date","B.work_schedule_name");
			
			$work_schedules = $this->work_schedule->get_work_schedule_list($aColumns, $bColumns, $params);
			$iTotal         = $this->work_schedule->work_schedule_total_length($params['employee_id']);
			$iFilteredTotal = $this->work_schedule->work_schedule_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module = MODULE_TA_DAILY_TIME_RECORD;
			$permission_view   = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit   = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			$cnt = 0;
			foreach ($work_schedules as $aRow):
				$cnt++;
				$row = array();
				$action = "";				
				$employee_id 	= $params['employee_id'];
				$id            = $this->hash($aRow['employee_work_schedule_id']);
				$salt          = gen_salt();
				$token_view    = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module. '/' . $employee_id, $salt);
				$token_edit    = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module. '/' . $employee_id, $salt);
				$token_delete  = in_salt($id  . '/' . ACTION_DELETE  . '/' . $module. '/' . $employee_id, $salt);
				
				$url_view      = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module. '/' . $employee_id;
				$url_edit      = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module. '/' . $employee_id;
				$url_delete    = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module. '/' . $employee_id;
				
				$delete_action = 'content_delete("work schedule", "'.$url_delete.'")';

				$row[] =  $aRow['start_date'];
				$row[] =  isset($aRow['end_date']) ? $aRow['end_date']:'PRESENT' ;
				$row[] =  $aRow['work_schedule_name'];

				
				$action = "<div class='table-actions'>";

				
				if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_employee_work_schedule' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_employee_work_schedule_init('".$url_view."')\"></a>";
				
				if($permission_edit AND EMPTY($aRow['end_date']))
				$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_employee_work_schedule' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_employee_work_schedule_init('".$url_edit."')\"></a>";
				
				if($permission_delete AND EMPTY($aRow['end_date']))
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				$action .= '</div>';
				
				
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

	public function modal_employee_work_schedule($action, $id, $token, $salt, $module, $employee_id)	
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;

			$resources['load_css'] 	= array(CSS_SELECTIZE,CSS_DATETIMEPICKER);
			$resources['load_js']	= array(JS_SELECTIZE,JS_DATETIMEPICKER);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module. '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			if($action != ACTION_ADD)
			{
				$table              = $this->work_schedule->tbl_employee_work_schedules;
				$where              = array();
				$key                = $this->get_hash_key('employee_work_schedule_id');
				$where[$key]        = $id;
				$data['sched_info'] = $this->work_schedule->get_general_data(array("*"), $table, $where, FALSE);

				$resources['single'] 		= array(
					'schedule_type'		=> $data['sched_info']['work_schedule_id']
				);
			
			}
			
			$table                  = $this->work_schedule->tbl_param_work_schedules;
			$where                  = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['work_schedule_id']   = array($data['sched_info']['work_schedule_id'], array("=", ")"));				
			}	
			$data['work_schedules'] = $this->work_schedule->get_general_data(array("work_schedule_id","work_schedule_name"), $table, $where, TRUE);
			
			$this->load->view('employee_attendance/modals/modal_employee_work_schedule', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}	

	public function process_work_schedule()
	{
		try
		{
			$status = 0;
			$params	= get_params();

			$action      = $params['action'];
			$token       = $params['token'];
			$salt        = $params['salt'];
			$id          = $params['id'];
			$module      = $params['module'];
			$employee_id = $params['employee_id'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module. '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			
			$valid_data 				= $this->_validate_data_work_schedule($params);

			Main_Model::beginTransaction();
			$effective_date = date('Y-m-d',strtotime($valid_data['effective_date']));

			
			if($action == ACTION_ADD)
			{
				$where			= array();
				$key 			= $this->get_hash_key('employee_id');
				$where[$key]	= $employee_id;
				$min_date  		= $this->work_schedule->get_general_data(array("max(start_date) min_date","employee_work_schedule_id"), $this->work_schedule->tbl_employee_work_schedules, $where, FALSE,NULL,$group_by = array('employee_id','employee_work_schedule_id'));

				if($min_date['min_date'] >= $effective_date)
				{
					$allowed_date = date('M d, Y', strtotime($min_date['min_date']));
					throw new Exception('Effectivity date must be after <b>' . $allowed_date .'.</b>' );
				}
				$where			= array();
				$key 			= $this->get_hash_key('employee_id');
				$where[$key]	= $employee_id;
				$emp_info  = $this->work_schedule->get_general_data(array("employee_id"), $this->work_schedule->tbl_employee_personal_info, $where, FALSE);

				$fields                     = array();
				$fields['employee_id']      = $emp_info['employee_id'];
				$fields['work_schedule_id'] = $valid_data['schedule_type'];
				$fields['start_date']       = $valid_data['effective_date'];
			
				$table          = $this->work_schedule->tbl_employee_work_schedules;
				$employee_work_schedule_id = $this->work_schedule->insert_general_data($table, $fields, TRUE);
				
				$message          = $this->lang->line('data_saved');

				$audit_table[]  = $this->work_schedule->tbl_employee_work_schedules;
				$audit_schema[] = DB_MAIN;
				$curr_detail[]  = array($fields);
				$audit_action[]   = AUDIT_INSERT;				
				$prev_detail[]    = array();
				$activity         = "%s has been added";	

				$where             = array();
				$key               = $this->get_hash_key('employee_id');
				$where[$key]       = $employee_id;
				$where['end_date'] = "IS NULL";
				$where['employee_work_schedule_id'] = array($employee_work_schedule_id, array("!="));

				$end_date =  date('Y-m-d', strtotime('-1 day', strtotime($valid_data['effective_date'])));

				$fields             = array();
				$fields['end_date'] = $end_date;
				$this->work_schedule->update_general_data($table, $fields, $where);	

			}
			else
			{
				$where			= array();
				$key 			= $this->get_hash_key('employee_id');
				$where[$key]	= $employee_id;
				$key 			= $this->get_hash_key('employee_work_schedule_id');
				$where[$key]	= array($params['id'], array("!="));
				$min_date  		= $this->work_schedule->get_general_data(array("max(start_date) min_date"), $this->work_schedule->tbl_employee_work_schedules, $where, FALSE);
				
				$fields                     = array();
				$fields['work_schedule_id'] = $valid_data['schedule_type'];
				$fields['start_date']       = $valid_data['effective_date'];
				
				$table          = $this->work_schedule->tbl_employee_work_schedules;
				$where			= array();
				$key 			= $this->get_hash_key('employee_work_schedule_id');
				$where[$key]	= $params['id'];
				$prev_schedule  = $this->work_schedule->get_general_data(array("*"), $table, $where, FALSE);
				
				
				if($min_date['min_date'] >= $effective_date)
				{
					$allowed_date = date('M d, Y', strtotime($min_date['min_date']));
					throw new Exception('Effectivity date must be after <b>' . $allowed_date .'.</b>' );
				}
								
				$this->work_schedule->update_general_data($table, $fields, $where);
				$message 		= $this->lang->line('data_updated');	

				$audit_table[]  = $this->work_schedule->tbl_employee_work_schedules;
				$audit_schema[] = DB_MAIN;
				$curr_detail[]  = array($fields);
				$audit_action[]	= AUDIT_UPDATE;
				$activity 		= "%s has been updated";
				$prev_detail[] = array($prev_schedule);			

				/*UPDATE THE END DATE OF THE PREVIOUS RECORD*/
				$end_date =  date('Y-m-d', strtotime('-1 day', strtotime($prev_schedule['start_date'])));

				$where                              = array();
				$key                                = $this->get_hash_key('employee_id');
				$where[$key]                        = $employee_id;
				$where['end_date']                  = $end_date;
				$where['employee_work_schedule_id'] = array($employee_work_schedule_id, array("!="));

				
				$end_date =  date('Y-m-d', strtotime('-1 day', strtotime($valid_data['effective_date'])));
				
				$fields             = array();
				$fields['end_date'] = $end_date;
				$this->work_schedule->update_general_data($table, $fields, $where);	
			}
			
			$activity = sprintf($activity, 'Employee work schedule');
	
			$this->audit_trail->log_audit_trail(
				$activity, 
				$module, 
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

	public function delete_employee_work_schedule()
	{
		try
		{
			$params        = get_params();
			$flag          = 0;
			
			$security_data = explode("/", $params['param_1']);
			$action        = $security_data[0];
			$id            = $security_data[1];
			$token         = $security_data[2];
			$salt          = $security_data[3];
			$module        = $security_data[4];
			$employee_id   = $security_data[5];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module. '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}			
				
			Main_Model::beginTransaction();
			$table 				= $this->work_schedule->tbl_employee_work_schedules;
			$where				= array();
			$key 				= $this->get_hash_key('employee_work_schedule_id');
			$where[$key]		= $id;
			$work_schedule		=  $this->work_schedule->get_general_data(array("*"), $table, $where, FALSE);

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
			$prev_detail[] 		= array($work_schedule);
			$curr_detail[] 		= array();
	

			
			
			$this->work_schedule->delete_general_data($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, 'Employee work schedule');
	
			$end_date =  date('Y-m-d', strtotime('-1 day', strtotime($work_schedule['start_date'])));
			$where             = array();
			$key               = $this->get_hash_key('employee_id');
			$where[$key]       = $employee_id;
			$where['end_date'] = $end_date;

			

			$fields             = array();
			$fields['end_date'] = NULL;
			$this->work_schedule->update_general_data($table, $fields, $where);	


			$this->audit_trail->log_audit_trail(
				$activity, 
				$module, 
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
		$post_data = array('employee_id' => $employee_id);
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'employee_work_schedule_table',
			"path"				=> PROJECT_MAIN . '/employee_work_schedule/get_work_schedule/',
			"advanced_filter" 	=> true,
			'post_data' 		=> json_encode($post_data)
		);
	
		echo json_encode($info);
	}

	private function _validate_data_work_schedule($params)
	{
		$fields                   = array();
		$fields['schedule_type']  = "Work Schedule";
		$fields['effective_date'] = "Effectivity Date";

		$this->check_required_fields($params, $fields);	
		
		return $this->_validate_work_schedule_input ($params);			
	}

	private function _validate_work_schedule_input($params) 
	{
		try {
			
			$validation ['schedule_type'] = array (
					'data_type' 			=> 'digit',
					'name' 					=> 'Work Schedule',
					'max_len'				=> 11 
			);
			$validation ['effective_date'] 	= array (
					'data_type' 			=> 'date',
					'name' 					=> 'Effectivity Date'
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}
/* End of file Employee_work_schedule.php */
/* Location: ./application/modules/main/controllers/Employee_work_schedule.php */