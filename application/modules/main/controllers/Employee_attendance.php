<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_attendance extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('daily_time_record_model', 'dtr');
		$this->load->model('pds_model', 'pds');
	}
	public function display_attendance($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)	
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
			
			$resources['load_modal'] = array(
				'modal_add_employee_attendance' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_attendance',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> 'Employee Attendance'
				),
				'modal_print_employee_dtr' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_print_employee_dtr',
							'multiple'   => true,
							'height'     => '150px',
							'size'       => 'sm',
							'title'      => 'Print DTR'
					),
				'modal_attendance_remarks' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_attendance_remarks',
						'multiple'		=> true,
						'height'		=> '200px',
						'size'			=> 'sm',
						'title'			=> 'Remarks'
				)
			);
			$employee_ids	   = $this->dtr->get_general_data(('employee_id'), $this->dtr->tbl_employee_personal_info);
			foreach($employee_ids as $employee_id)
			{
				$prep_string = "%$".$employee_id['employee_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $id)
				{
					$id = $employee_id['employee_id'];
					break;
				}
			}
			$data['personal_info']    = $this->pds->get_employee_info($id);
			
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
		
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= $data['personal_info']['fullname']; 
		$breadcrumbs[$key]		= "";
		set_breadcrumbs($breadcrumbs, FALSE);
		$this->template->load('employee_attendance/employee_attendance', $data, $resources);
		
	}
	public function get_dtr_list($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
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
			$resources['datatable'][] = array('table_id' => 'employee_logs_table', 'path' => 'main/daily_time_record/get_employee_log/', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));

			$resources['load_modal'] = array(
				'modal_attendance_breakdown' => array(
						'controller'	=> 'daily_time_record',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_attendance_breakdown',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'lg',
						'title'			=> 'Attendance Breakdown'
				),
				'modal_add_employee_attendance' => array(
						'controller'	=> 'daily_time_record',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_attendance',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> 'Employee Attendance'
				)
			);
			$this->load->view('employee_attendance/tabs/employee_dtr', $data);
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

	public function get_time_logs($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module,$date_start = '',$date_end= '')
	{

		try
		{
			$data 					= array();
			$resources 				= array();

			$resources['load_css']    = array(CSS_DATETIMEPICKER,CSS_MODAL_COMPONENT);
			$resources['load_js']     = array(JS_DATETIMEPICKER,JS_MODAL_CLASSIE,JS_MODAL_EFFECTS);

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
			if(EMPTY($date_start) OR EMPTY($date_end) OR $date_start > $date_end)
			{
				$date_end  = date('Y/m/d');
				$date_start = date('Y/m/d', strtotime('-1 months', strtotime($date_end)));
			}
			$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
			$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');


			$employee_ids	   = $this->dtr->get_general_data(('employee_id'), $this->dtr->tbl_employee_personal_info);
			foreach($employee_ids as $employee_id)
			{
				$prep_string = "%$".$employee_id['employee_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $id)
				{
					$id = $employee_id['employee_id'];
					break;
				}
			}
			$data['personal_info'] = $this->pds->get_employee_info($id);
			$time_logs             = $this->dtr->get_attendance_time_logs($id,$date_start,$date_end);
		
			$new_time_logs         = array();
			$date_array         = array();
			
			//marvin
			$new_date_array = array();
			
			if($time_logs)
			{
				$late_undertime = modules::load('main/attendance_late_undertime');

				foreach ($time_logs as $time_log) {
					
					$result              = $late_undertime->check_late_undertime($time_log);
	
					if($time_log['attendance_status_id'] == OFFICIAL_BUSINESS)
					{
						$time_log['time_in'] = "OFFICIAL BUSINESS";
						$time_log['break_out'] = "OFFICIAL BUSINESS";
						$time_log['break_in'] = "OFFICIAL BUSINESS";
						$time_log['time_out'] = "OFFICIAL BUSINESS";
						$result['tardiness'] = 0;
						$result['undertime'] = 0;
						$result['tardiness_hour'] = 0;
						$result['tardiness_min'] = 0;
						$result['undertime_hour'] = 0;
						$result['undertime_min'] = 0;
						$result['working_hours'] = 8;
					}
					$new_time_logs[]= array_merge($time_log,$result);
					if($time_log['attendance_period_flag'] != 1)
					{
						$date_array[]= $time_log['attendance_date'];
					}
					//marvin
					//store the date tomorrow for validation of time_out
					// $new_date_array[] = date('Y-m-d', strtotime($time_log['attendance_date'] . '+1 day'));
				}
				//marvin
				//validate undertime and undertime_hour if tomorrow date is existing in time_logs for 12, 16 and 24 hours duty
				// if($result['type_of_duty'] > 8)
				// {
					// $arr_counter = count($date_array);
					// for($i=0;$i<$arr_counter;$i++)
					// {
						// if(in_array($new_date_array[$i], $date_array))
						// {
							// $new_time_logs[$i]['undertime'] = 0;
							// $new_time_logs[$i]['undertime_hour'] = 0;
						// }
					// }
				// }
			}
			$data['last_date'] = max($date_array);
			$data['time_logs'] = $new_time_logs;
		
			$this->load->view('employee_attendance/tabs/employee_time_logs', $data);
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

	public function get_dtr_tabs($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
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
			$resources['datatable'][] = array('table_id' => 'employee_logs_table', 'path' => 'main/daily_time_record/get_employee_log/', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));

			$resources['load_modal'] = array(
				'modal_attendance_breakdown' => array(
						'controller'	=> 'daily_time_record',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_attendance_breakdown',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'lg',
						'title'			=> 'Attendance Breakdown'
				),
				'modal_add_employee_attendance' => array(
						'controller'	=> 'daily_time_record',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_attendance',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> 'Employee Attendance'
				)
			);
			$this->load->view('employee_attendance/tabs/dtr_tabs', $data);
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
	public function get_leave($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{

		try
		{
			$data 					= array();
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

			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			$post_data 					= array(	
												'employee_id' => $id,
												'action'   => $action
										);
			$resources['datatable'][]    = array('table_id' => 'table_employee_leave_list', 'path' => 'main/leaves/get_employee_leave_list','advanced_filter'=>true,'post_data' => json_encode($post_data));
			$resources['load_modal']		= array(
				'modal_employee_leave_adjustment'		=> array(
						'controller'	=> 'leaves',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_employee_leave_adjustment',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'sm',
						'title'			=> "Leave Adjustment" 
				),
				'modal_leave_history'		=> array(
						'controller'	=> 'leaves',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_history',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'lg',
						'title'			=> "Leave History"
				),
				'modal_add_employee_leave_type'		=> array(
						'controller'	=> 'leaves',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_leave_type',
						'multiple'		=> true,
						'height'		=> '400px',
						'size'			=> 'sm',
						'title'			=> "Leave Type"
				)
			);	

			$this->load->view('employee_attendance/tabs/employee_leave', $data);
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

	public function process_time_logs()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_time_logs($params);
			Main_Model::beginTransaction();
			$field       = array("*") ;
			$table       = $this->dtr->tbl_employee_personal_info;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$emp_info    = $this->dtr->get_general_data($field, $table, $where, FALSE);

			$affected_ids = array();
			$affected_dates = array();

			//davcorrea added date validation
			//==========START=================
			$time_in_count = -1;
			foreach ($valid_data['time_in'] as $key => $time_log) {					
				$time_in_count ++;
				$time_out_count = -1;
				foreach ($valid_data['time_out'] as $outKey => $outTime_log) 
				{
					$time_out_count ++;
					if($time_in_count == $time_out_count)
					{
						if(!EMPTY($time_log) && !EMPTY($outTime_log))
						{
							if($time_log >= $outTime_log)
							{
								throw new Exception("Time In should not be the same or later than Time Out");
							}
						}						
						
					}
				}
				//==========END===================
				if(is_int($key))
				{

					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];
					$timeInIndex = array_search($key , $valid_data['time_in']);







					if($prev_att['time_log'] != $time_log)
					{

						
						//davcorrea added date validation
						//==========START=================
						$timelogdate = explode(" ", $time_log);						
						if(!EMPTY($timelogdate[0]))
						{						
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Time In Date: Date should not be earlier or later than attendance date");
							}
						}			
						
						
						//==========END===================
						if(EMPTY($valid_data['remarks'][$attendance_date]))
							throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
							
						$affected_ids[]        = $key;
						$fields                = array();
						$fields['time_log']    = !EMPTY($time_log) ? $time_log : NULL;
						$fields['remarks']     = $valid_data['remarks'][$attendance_date];
						$fields['edited_flag'] = YES;
						$table                           = $this->dtr->tbl_employee_attendance;
						$where                           = array();
						$where['employee_attendance_id'] = $key;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;
						$this->dtr->update_general_data($table,$fields,$where);
					}
				}	
				else
				{
					$affected_dates[] = $key;

					$table                    = $this->dtr->tbl_employee_attendance;
					$where                    = array();
					$where["employee_id"]     = $emp_info['employee_id'];
					$where["attendance_date"] = $key;
					$where["time_flag"]       = FLAG_TIME_IN;
					$prev_att                 = $this->dtr->get_general_data(array('*'), $table, $where, false);

					if($prev_att)
					{
						if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
						$fields                = array();
						$fields['time_log']    = $time_log;
						$fields['remarks']     = $valid_data['remarks'][$key];
						$fields['edited_flag'] = YES;
						
						$where                    = array();
						$where["employee_id"]     = $emp_info['employee_id'];
						$where["attendance_date"] = $key;
						$where["time_flag"]       = FLAG_TIME_IN;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
					else
					{
						if($time_log)
						{
							//davcorrea added date validation
							//==========START=================
							$timelogdate = explode(" ", $time_log);			
							if($key != $timelogdate[0]){
							throw new Exception("Invalid Time In Date: Date should not be earlier or later than attendance date");
							}
							
						//==========END===================
							if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
							$fields = array(
									'employee_id'     => $emp_info['employee_id'],
									'attendance_date' => $key,
									'time_flag'       => FLAG_TIME_IN,
									'time_log'        => $time_log,
									'remarks'         => $valid_data['remarks'][$key],
									'source_flag'     => 'M',
									'edited_flag'     => YES
								);
							$table 	= $this->dtr->tbl_employee_attendance;
							$audit_table[]				= $table;
							$audit_schema[]				= DB_MAIN;
							$prev_detail[] 				= array();
							$curr_detail[]				= array($fields);
							$audit_action[] 			= AUDIT_INSERT;

							$this->dtr->insert_general_data($table,$fields,false);
						}
					}
					
					
				}					
				
			}
			foreach ($valid_data['break_out'] as $key => $time_log) {				
				
				if(is_int($key))
				{
					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];

					if($prev_att['time_log'] != $time_log)
					{
						//davcorrea added date validation
						//==========START=================
						$timelogdate = explode(" ", $time_log);			
						if(!EMPTY($timelogdate[0]))
						{						
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Time Out Date: Date should not be earlier or later than attendance date");
							}
						}			
							
						//==========END===================
						
						if(EMPTY($valid_data['remarks'][$attendance_date]))
							throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
						$affected_ids[]        = $key;
						$fields                = array();
						$fields['time_log']    = !EMPTY($time_log) ? $time_log : NULL;
						$fields['remarks']     = $valid_data['remarks'][$attendance_date];
						$fields['edited_flag'] = YES;

						$table                           = $this->dtr->tbl_employee_attendance;
						$where                           = array();
						$where['employee_attendance_id'] = $key;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
				}	
				else
				{
					$affected_dates[] = $key;

					$table                    = $this->dtr->tbl_employee_attendance;
					$where                    = array();
					$where["employee_id"]     = $emp_info['employee_id'];
					$where["attendance_date"] = $key;
					$where["time_flag"]       = FLAG_BREAK_OUT;
					$prev_att                 = $this->dtr->get_general_data(array('*'), $table, $where, false);

					if($prev_att)
					{
						if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
						$fields                   = array();
						$fields['time_log']       = $time_log;
						$fields['remarks']        = $valid_data['remarks'][$key];
						$fields['edited_flag'] = YES;
						
						$where                    = array();
						$where["employee_id"]     = $emp_info['employee_id'];
						$where["attendance_date"] = $key;
						$where["time_flag"]       = FLAG_BREAK_OUT;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
					else
					{
						if($time_log)
						{
							//davcorrea added date validation
							//==========START=================
							$timelogdate = explode(" ", $time_log);					
							if($key != $timelogdate[0]){
							throw new Exception("Invalid Break Out Date: Date should not be earlier or later than attendance date");
							}
							
						//==========END===================
							if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
							$fields = array(
									'employee_id'     => $emp_info['employee_id'],
									'attendance_date' => $key,
									'time_flag'       => FLAG_BREAK_OUT,
									'time_log'        => $time_log,
									'remarks'         => $valid_data['remarks'][$key],
									'source_flag'     => 'M',
									'edited_flag'     => YES
								);
							$table 	= $this->dtr->tbl_employee_attendance;
							$audit_table[]				= $table;
							$audit_schema[]				= DB_MAIN;
							$prev_detail[] 				= array();
							$curr_detail[]				= array($fields);
							$audit_action[] 			= AUDIT_INSERT;

							$this->dtr->insert_general_data($table,$fields,false);
						}
					}
				}		
			}	
			//davcorrea added date validation
			//==========START=================
			$time_in_count = -1;
			foreach ($valid_data['break_in'] as $key => $time_log) {				
				$time_in_count ++;
				$time_out_count = -1;
				foreach ($valid_data['break_out'] as $outKey => $outTime_log) 
				{
					$time_out_count ++;
					if($time_in_count == $time_out_count)
					{
						if(!EMPTY($time_log) && !EMPTY($outTime_log))
						{
							// if($time_log >= $outTime_log)
							// {
							// 	throw new Exception("Break In should not be the same or later than Break Out");
							// }
							// davcorrea: resolved break in and break out validation: 11/06/2023 : START
							if($time_log <= $outTime_log)
							{
								throw new Exception("Break In cannot be the same or earlier than Break Out");
							}
							// END
						}						
						
					}
				}
				//==========END===================
				if(is_int($key))
				{
					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];

					if($prev_att['time_log'] != $time_log)
					{
						//davcorrea added date validation
						//==========START=================
						$timelogdate = explode(" ", $time_log);					
						if(!EMPTY($timelogdate[0]))
						{						
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Time Out Date: Date should not be earlier or later than attendance date");
							}
						}			
							
						//==========END===================
						if(EMPTY($valid_data['remarks'][$attendance_date]))
							throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
						$affected_ids[] = $key;
						$fields             = array();
						$fields['time_log'] = !EMPTY($time_log) ? $time_log : NULL;
						$fields['remarks']  = $valid_data['remarks'][$attendance_date];
						$fields['edited_flag'] = YES;

						$table                           = $this->dtr->tbl_employee_attendance;
						$where                           = array();
						$where['employee_attendance_id'] = $key;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
				}	
				else
				{
					$affected_dates[] = $key;

					$table                    = $this->dtr->tbl_employee_attendance;
					$where                    = array();
					$where["employee_id"]     = $emp_info['employee_id'];
					$where["attendance_date"] = $key;
					$where["time_flag"]       = FLAG_BREAK_IN;
					$prev_att                 = $this->dtr->get_general_data(array('*'), $table, $where, false);

					if($prev_att)
					{
						if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
						$fields                   = array();
						$fields['time_log']       = $time_log;
						$fields['remarks']        = $valid_data['remarks'][$key];
						$fields['edited_flag'] = YES;
						
						$where                    = array();
						$where["employee_id"]     = $emp_info['employee_id'];
						$where["attendance_date"] = $key;
						$where["time_flag"]       = FLAG_BREAK_IN;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
					else
					{
						if($time_log)
						{	
							//davcorrea added date validation
							//==========START=================
							$timelogdate = explode(" ", $time_log);					
								if($key != $timelogdate[0]){
									throw new Exception("Invalid Break In Date: Date should not be earlier or later than attendance date");
							}
							
							//==========END===================
							if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
							$fields = array(
									'employee_id'     => $emp_info['employee_id'],
									'attendance_date' => $key,
									'time_flag'       => FLAG_BREAK_IN,
									'time_log'        => $time_log,
									'remarks'         => $valid_data['remarks'][$key],
									'source_flag'     => 'M',
									'edited_flag'     => YES
								);
							$table 	= $this->dtr->tbl_employee_attendance;
							$audit_table[]				= $table;
							$audit_schema[]				= DB_MAIN;
							$prev_detail[] 				= array();
							$curr_detail[]				= array($fields);
							$audit_action[] 			= AUDIT_INSERT;

							$this->dtr->insert_general_data($table,$fields,false);
						}
					}
				}		
			}		
			foreach ($valid_data['time_out'] as $key => $time_log) {	
				
				if(is_int($key))
				{
					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];
					if($prev_att['time_log'] != $time_log)
					{						
						//davcorrea added date validation
						//==========START=================
						$timelogdate = explode(" ", $time_log);
	
						if(!EMPTY($timelogdate[0]))
						{						
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Time Out Date: Date should not be earlier or later than attendance date");
							}
						}			
						//==========END===================

						if(EMPTY($valid_data['remarks'][$attendance_date]))
							throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
						$affected_ids[]     = $key;
						$fields             = array();
						$fields['time_log'] = !EMPTY($time_log) ? $time_log : NULL;
						$fields['remarks']  = $valid_data['remarks'][$attendance_date];
						$fields['edited_flag'] = YES;
						
						$table                           = $this->dtr->tbl_employee_attendance;
						$where                           = array();
						$where['employee_attendance_id'] = $key;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
				}	
				else
				{
					$affected_dates[] = $key;
					
					$table                    = $this->dtr->tbl_employee_attendance;
					$where                    = array();
					$where["employee_id"]     = $emp_info['employee_id'];
					$where["attendance_date"] = $key;
					$where["time_flag"]       = FLAG_TIME_OUT;
					$prev_att                 = $this->dtr->get_general_data(array('*'), $table, $where, false);
					
					if($prev_att)
					{
						if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
						$fields                   = array();
						$fields['time_log']       = $time_log;
						$fields['remarks']        = $valid_data['remarks'][$key];
						$fields['edited_flag'] = YES;
						
						$where                    = array();
						$where["employee_id"]     = $emp_info['employee_id'];
						$where["attendance_date"] = $key;
						$where["time_flag"]       = FLAG_TIME_OUT;
						$audit_table[]				= $table;
						$audit_schema[]				= DB_MAIN;
						$prev_det 					= $this->dtr->get_general_data(array('*'),$table,$where);
						$prev_detail[] 				= $prev_det;
						$curr_detail[]				= array($fields);
						$audit_action[] 			= AUDIT_UPDATE;

						$this->dtr->update_general_data($table,$fields,$where);
					}
					else
					{
						
						if($time_log)
						{
							//davcorrea added date validation
						//==========START=================
						$timelogdate = explode(" ", $time_log);					
						if($key != $timelogdate[0]){
							throw new Exception("Invalid Time Out Date: Date should not be earlier or later than attendance date");
						}
							
						//==========END===================
							if(EMPTY($valid_data['remarks'][$key]))
							throw new Exception("<b>Remarks</b> is required for <b>".$key);
							$fields = array(
									'employee_id'     => $emp_info['employee_id'],
									'attendance_date' => $key,
									'time_flag'       => FLAG_TIME_OUT,
									'time_log'        => $time_log,
									'remarks'         => $valid_data['remarks'][$key],
									'source_flag'     => 'M',
									'edited_flag'     => YES
								);
							$table 	= $this->dtr->tbl_employee_attendance;
							$audit_table[]				= $table;
							$audit_schema[]				= DB_MAIN;
							$prev_detail[] 				= array();
							$curr_detail[]				= array($fields);
							$audit_action[] 			= AUDIT_INSERT;

							$this->dtr->insert_general_data($table,$fields,false);
						}
					}
				}		
			}	
			$this->_update_attendance_period_dtl($emp_info['employee_id'],$affected_dates,$affected_ids);
			$audit_activity 			= "Attendance Manual Adjustment(Edit attendance) for " . $emp_info['first_name'] . " ". $emp_info['last_name'] ;
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_saved');
		}
		catch(PDOException $e){
			 Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _update_attendance_period_dtl($employee_id,$affected_dates,$affected_ids)
	{
		try
		{	

			$time_logs             = $this->dtr->get_affected_attendance_time_logs($employee_id,$affected_dates,$affected_ids);
			$new_time_logs         = array();
			if($time_logs)
			{
				$late_undertime = modules::load('main/attendance_late_undertime');
				
				foreach ($time_logs as $time_log) {
					$result                   = $late_undertime->check_late_undertime($time_log);
					$fields                   = array();
					$fields['tardiness']      = ($result['tardiness'] > 0) ? $result['tardiness'] : 0;
					$fields['undertime']      = ($result['undertime'] > 0) ? $result['undertime'] : 0;
					$fields['working_hours']  = ($result['working_hours'] > 0) ? $result['working_hours'] : 0;
					$fields['remarks']        = $time_log['remarks'];
					
					$table                    = $this->dtr->tbl_attendance_period_dtl;
					$where                    = array();
					$where['employee_id']     = $time_log['employee_id'];
					$where['attendance_date'] = $time_log['attendance_date'];
					
					// $this->dtr->update_general_data($table,$fields,$where);
					
					/*======= marvin : start : include insert if no existing data =======*/
					$existing_attendance_period_dtl = $this->dtr->get_general_data('*',$this->dtr->tbl_attendance_period_dtl,$where);
					
					if(!empty($existing_attendance_period_dtl))
					{
						$this->dtr->update_general_data($table,$fields,$where);
					}
					else
					{
						$existing_employee_attendance 	= $this->dtr->get_general_data('*',$this->dtr->tbl_employee_attendance,$where);
						
						$fields['employee_id'] 			= $existing_employee_attendance[0]['employee_id'];
						$fields['attendance_date'] 		= $existing_employee_attendance[0]['attendance_date'];
						$fields['attendance_status_id'] = $existing_employee_attendance[0]['attendance_status_id'];
						
						$this->dtr->insert_general_data($table,$fields);
					}
					/*======= marvin : end : include insert if no existing data =======*/
				}
			}
			
			return true;		
		}
		catch(Exception $e)
		{
			throw $e;			
			$message = $e->getMessage();
			RLog::error($message);
		}
	}
	private function _validate_time_logs($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields            = array();
			//$fields['remarks'] = "Remarks";
			
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_time_logs($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_time_logs($params)
	{
		try
		{
			
			$validation['time_in'] = array(
					'data_type' => 'date',
					'name'		=> 'Time In'
			);	
			$validation['break_out'] = array(
					'data_type' => 'date',
					'name'		=> 'Break Out'
			);	
			$validation['break_in'] = array(
					'data_type' => 'date',
					'name'		=> 'Break In'
			);	
			$validation['time_out'] = array(
					'data_type' => 'date',
					'name'		=> 'Time Out'
			);	
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks'
			);	
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	

	public function modal_add_employee_attendance($action, $id, $token, $salt, $module, $fltr_dtr_start, $fltr_dtr_end)	
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
			
			//marvin
			$data['fltr_dtr_start']	= $fltr_dtr_start;
			$data['fltr_dtr_end']	= $fltr_dtr_end;
			
			//start adding disabled date list
			$personal_info			= $this->pds->get_employee_info($id);
			$time_logs              = $this->dtr->custom_get_attendance_time_logs($id,$fltr_dtr_start,$fltr_dtr_end);
			
			$data['dates'] 	= array();
			
			foreach($time_logs as $dates)
			{
				$data['dates'][] = $dates['attendance_date'];
			}
			//end
			
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE);
			$resources['load_js']	= array(JS_DATETIMEPICKER,JS_SELECTIZE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$field                     = array("attendance_status_id","attendance_status_name") ;
			$table                     = $this->dtr->tbl_param_attendance_status;
			$where                     = array();
			$where['dtr_adjust_flag']  = YES;
			$where['active_flag']  	   = YES;
			$data['attendance_status'] = $this->dtr->get_general_data($field, $table, $where, TRUE);

			$this->load->view('daily_time_record/modals/modal_add_employee_attendance', $data);
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
	public function process_add_attendance()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			
			$valid_data = $this->_validate_attendance($params);

			// davcorrea: validate if break time logs does not overlap : START :11/13/2023
			$timeInLogtime = explode(" ", $params['time_in']);	
			$breakOutLogtime = explode(" ", $params['break_out']);
			$breakInLogtime = explode(" ", $params['break_in']);	
			$timeOutLogtime = explode(" ", $params['time_out']);
			if(!empty($params['time_in']))
			{
				if(strlen($timeInLogtime[1]) != '5')
				{
					throw new Exception("Invalid Time in Format and Length");
				}
			}
			if(!empty($params['break_out']))
			{
				if(strlen($breakOutLogtime[1]) != '5')
				{
					throw new Exception("Invalid Break Out Format and Length");
				}
			}
			if(!empty($params['break_in']))
			{
				if(strlen($breakInLogtime[1]) != '5')
				{
					throw new Exception("Invalid Break In Format and Length");
				}
			}
			if(!empty($params['time_out']))
			{
				if(strlen($timeOutLogtime[1]) != '5')
				{
					throw new Exception("Invalid Time Out Format and Length");
				}
			}
			$timeInLogtime_24_format = date("H:i", strtotime($timeInLogtime[1]." ".$timeInLogtime[2]));
			$breakOutLogtime_24_format = date("H:i", strtotime($breakOutLogtime[1]." ".$breakOutLogtime[2]));
			$breakInLogtime_24_format = date("H:i", strtotime($breakInLogtime[1]." ".$breakInLogtime[2]));
			$timeOutLogtime_24_format = date("H:i", strtotime($timeOutLogtime[1]." ".$timeOutLogtime[2]));
			if(!empty($params['break_in']) && !empty($params['break_out']))		
			{
				if($breakOutLogtime_24_format >= $breakInLogtime_24_format)
				{
					throw new Exception("Break In cannot be the same or earlier than Break Out");
				}
			}

			if(!empty($params['time_in']) && !empty($params['time_out']))		
			{
				if($timeInLogtime_24_format >= $timeOutLogtime_24_format)
				{
					throw new Exception("Time In cannot be the same or earlier than Time Out");
				}
			}
			if(!empty($params['time_in']) && !empty($params['break_out']))		
			{
				if($timeInLogtime_24_format >= $breakOutLogtime_24_format)
				{
					throw new Exception("Time In cannot be the same or earlier than Break Out");
				}
			}
			
			if(!empty($params['break_in']) && !empty($params['time_out']))		
			{
				if($breakInLogtime_24_format >= $timeOutLogtime_24_format)
				{
					throw new Exception("Break In cannot be the same or earlier than Time Out");
				}
			}
				
			// END
			
			// marvin : check work schedule : start
			$field       = array("employee_id") ;
			$table       = $this->dtr->tbl_employee_personal_info;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$emp_info    = $this->dtr->get_general_data($field, $table, $where, FALSE);
			
			$work_schedule = $this->dtr->get_employee_work_schedule($emp_info['employee_id'], $valid_data['attendance_date']);
			$day = strtolower(date('D',strtotime($date)));
			// marvin : check work schedule : end
			
			//marvin : validation dtr new entry : start
			if($work_schedule[$day.'_type_of_duty'] == 8)
			{
				if($work_schedule['break_hours'] > 0)
				{
					if(!empty($valid_data['time_in']))
					{
						if(empty($valid_data['break_out']))
						{
							throw new Exception("<b>Break Out</b> is Required");
						}
					}

					if(!empty($valid_data['time_out']))
					{
						if(empty($valid_data['break_in']))
						{
							throw new Exception("<b>Break In</b> is Required");
						}
					}

				}
				if(!empty($valid_data['time_in']))
				{
					if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['time_in'], 0, 10))
					{
						throw new Exception("<b>Attendance Date</b> doesn't match the <b>Time in</b> Date");
					}
					if(empty($valid_data['break_out']))
					{
						if(empty($valid_data['time_out']))
						{
							throw new Exception("<b>Time Out</b> is Required");
						}
					}
				}
				if(!empty($valid_data['break_out']))
				{
					if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['break_out'], 0, 10))
					{
						throw new Exception("<b>Attendance Date</b> doesn't match the <b>Break out</b> Date");
					}
				}
				if(!empty($valid_data['break_in']))
				{
					if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['break_in'], 0, 10))
					{
						throw new Exception("<b>Attendance Date</b> doesn't match the <b>Break in</b> Date");
					}
				}
				if(!empty($valid_data['time_out']))
				{
					if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['time_out'], 0, 10))
					{
						throw new Exception("<b>Attendance Date</b> doesn't match the <b>Time out</b> Date");
					}
					if(empty($valid_data['break_in']))
					{
						if(empty($valid_data['time_in']))
						{
							throw new Exception("<b>Time In</b> is Required");
						}
					}	
				}			
			}
			else
			{
				// switch($work_schedule[$day.'_type_of_duty'])
				// {
					// case 12:
						// break;
						
					// case 16:
						// break;
						
					// case 24:
						if(date('Y-m-d', strtotime($valid_data['attendance_date'])) != date('Y-m-d', strtotime($valid_data['time_in'])))
						{
							unset($valid_data['time_in']);
						}
						if(date('Y-m-d', strtotime($valid_data['attendance_date'])) != date('Y-m-d', strtotime($valid_data['break_out'])))
						{
							unset($valid_data['break_out']);
						}
						if(date('Y-m-d', strtotime($valid_data['attendance_date'])) != date('Y-m-d', strtotime($valid_data['break_in'])))
						{
							unset($valid_data['break_in']);
						}
						if(date('Y-m-d', strtotime($valid_data['attendance_date'])) != date('Y-m-d', strtotime($valid_data['time_out'])))
						{
							unset($valid_data['time_out']);
						}
						// break;
				// }
			}
			//marvin : validation dtr new entry : end
			//davcorrea: validate if attendance date already has pending request : START
			$field       = array('*') ;
			$table       = $this->dtr->tbl_requests_employee_attendance;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$where['attendance_date'] = $params['attendance_date'];
			$emp_valid_requests    = $this->dtr->get_general_data($field, $table, $where, TRUE);
			
			if(!EMPTY($emp_valid_requests)){
				$req_sub_id = end($emp_valid_requests);
				$field       = array('*') ;
				$table       = $this->dtr->tbl_requests_sub;
				$where       = array();
				$where['request_sub_id'] = $req_sub_id['request_sub_id'];
				$emp_valid_requests_sub    = $this->dtr->get_general_data($field, $table, $where, FALSE);
				$field       = array('*') ;
				$table       = $this->dtr->tbl_requests;
				$where       = array();
				$where['request_id'] = $emp_valid_requests_sub['request_id'];
				$emp_valid_requests_main    = $this->dtr->get_general_data($field, $table, $where, FALSE);
				if($emp_valid_requests_main['request_status_id'] == REQUEST_NEW || $emp_valid_requests_main['request_status_id'] == REQUEST_PENDING || $emp_valid_requests_main['request_status_id'] == REQUEST_ONGOING)
				{
					throw new Exception("<b>Attendance Date</b> Already has pending request");
				}
			}

			$field       = array('*') ;
			$table       = $this->dtr->tbl_employee_attendance;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$where['attendance_date'] = $params['attendance_date'];
			$emp_valid_dtr    = $this->dtr->get_general_data($field, $table, $where, FALSE);
			if(!EMPTY($emp_valid_dtr)){
				throw new Exception("<b>Attendance Date</b> Already exists");
			}

			//END
			Main_Model::beginTransaction();
			$field       = array("*") ;
			$table       = $this->dtr->tbl_employee_personal_info;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$emp_info    = $this->dtr->get_general_data($field, $table, $where, FALSE);
			
			$affected_ids     = array();
			$affected_dates   = array();
			$affected_dates[] = $valid_data['attendance_date'];

			$field                    = array("group_concat(time_flag) as time_flag") ;
			$table                    = $this->dtr->tbl_employee_attendance;
			$where                    = array();
			$where['employee_id']     = $emp_info['employee_id'];
			$where['attendance_date'] = $valid_data['attendance_date'];
			$prev_att                 = $this->dtr->get_general_data($field, $table, $where, FALSE);

			$time_flags = explode(',', $prev_att['time_flag']);

			if(in_array(FLAG_TIME_IN, $time_flags))
			{
				if($valid_data['time_in'])
				{
					$fields                         = array();
					$fields['time_log']             = $valid_data['time_in'];
					$fields['remarks']              = $valid_data['remarks'];
					$fields['edited_flag']          = YES;
					$fields['attendance_status_id'] = $valid_data['attendance_status_id'];
					
					$table                          = $this->dtr->tbl_employee_attendance;
					$where                          = array();
					$where['attendance_date']       = $valid_data['attendance_date'];
					$where['time_flag']             = FLAG_TIME_IN;
					$where['employee_id']           = $emp_info['employee_id'];
					$this->dtr->update_general_data($table,$fields,$where);
				}
				
			}	
			else
			{
				if($valid_data['time_in'])
				{
					$fields = array(
							'employee_id'          => $emp_info['employee_id'],
							'attendance_date'      => $valid_data['attendance_date'],
							'time_flag'            => FLAG_TIME_IN,
							'time_log'             => $valid_data['time_in'],
							'remarks'              => $valid_data['remarks'],
							'source_flag'          => 'M',
							'edited_flag'          => YES,
							'attendance_status_id' => $valid_data['attendance_status_id']
						);
					$table 	= $this->dtr->tbl_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
					$audit_table[]			= $this->dtr->tbl_employee_attendance;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
				}
				
			}	
			if(in_array(FLAG_BREAK_OUT, $time_flags))
			{
				if($valid_data['break_out'])
				{
					$fields                         = array();
					$fields['time_log']             = $valid_data['break_out'];
					$fields['remarks']              = $valid_data['remarks'];
					$fields['edited_flag']          = YES;
					$fields['attendance_status_id'] = $valid_data['attendance_status_id'];
					
					$table                          = $this->dtr->tbl_employee_attendance;
					$where                          = array();
					$where['attendance_date']       = $valid_data['attendance_date'];
					$where['time_flag']             = FLAG_BREAK_OUT;
					$where['employee_id']           = $emp_info['employee_id'];
					$this->dtr->update_general_data($table,$fields,$where);
				}
				
			}	
			else
			{
				if($valid_data['break_out'])
				{
					$fields = array(
							'employee_id'          => $emp_info['employee_id'],
							'attendance_date'      => $valid_data['attendance_date'],
							'time_flag'            => FLAG_BREAK_OUT,
							'time_log'             => $valid_data['break_out'],
							'remarks'              => $valid_data['remarks'],
							'source_flag'          => 'M',
							'edited_flag'          => YES,
							'attendance_status_id' => $valid_data['attendance_status_id']
						);
					$table 	= $this->dtr->tbl_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
					$audit_table[]			= $this->dtr->tbl_employee_attendance;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
				}
				
			}					
			
			if(in_array(FLAG_BREAK_IN, $time_flags))
			{
				if($valid_data['break_in'])
				{
					$fields                         = array();
					$fields['time_log']             = $valid_data['break_in'];
					$fields['remarks']              = $valid_data['remarks'];
					$fields['edited_flag']          = YES;
					$fields['attendance_status_id'] = $valid_data['attendance_status_id'];
					
					$table                          = $this->dtr->tbl_employee_attendance;
					$where                          = array();
					$where['attendance_date']       = $valid_data['attendance_date'];
					$where['time_flag']             = FLAG_BREAK_IN;
					$where['employee_id']           = $emp_info['employee_id'];
					$this->dtr->update_general_data($table,$fields,$where);
					
				}
				
			}	
			else
			{
				if($valid_data['break_in'])
				{
					$fields = array(
							'employee_id'          => $emp_info['employee_id'],
							'attendance_date'      => $valid_data['attendance_date'],
							'time_flag'            => FLAG_BREAK_IN,
							'time_log'             => $valid_data['break_in'],
							'remarks'              => $valid_data['remarks'],
							'source_flag'          => 'M',
							'edited_flag'          => YES,
							'attendance_status_id' => $valid_data['attendance_status_id']
						);
					$table 	= $this->dtr->tbl_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
					$audit_table[]			= $this->dtr->tbl_employee_attendance;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
				}
				
			}

			if(in_array(FLAG_TIME_OUT, $time_flags))
			{
				if($valid_data['time_out'])
				{
					$fields                         = array();
					$fields['time_log']             = $valid_data['time_out'];
					$fields['remarks']              = $valid_data['remarks'];
					$fields['edited_flag']          = YES;
					$fields['attendance_status_id'] = $valid_data['attendance_status_id'];
					
					$table                          = $this->dtr->tbl_employee_attendance;
					$where                          = array();
					$where['attendance_date']       = $valid_data['attendance_date'];
					$where['time_flag']             = FLAG_TIME_OUT;
					$where['employee_id']           = $emp_info['employee_id'];
					$this->dtr->update_general_data($table,$fields,$where);
				}
				
			}	
			else
			{
				if($valid_data['time_out'])
				{
					$fields = array(
							'employee_id'          => $emp_info['employee_id'],
							'attendance_date'      => $valid_data['attendance_date'],
							'time_flag'            => FLAG_TIME_OUT,
							'time_log'             => $valid_data['time_out'],
							'remarks'              => $valid_data['remarks'],
							'source_flag'          => 'M',
							'edited_flag'          => YES,
							'attendance_status_id' => $valid_data['attendance_status_id']
						);
					$table 	= $this->dtr->tbl_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
					$audit_table[]			= $this->dtr->tbl_employee_attendance;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
				}
				
			}	
			$this->_update_attendance_period_dtl($emp_info['employee_id'],$affected_dates,$affected_ids);

			$audit_activity 		= "Attendance Manual Adjustment (Add attendance) for " . $emp_info['first_name'] . " " . $emp_info['last_name'];
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_saved');
		}
		catch(PDOException $e){
			 Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}
	private function _validate_attendance($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields                         = array();
			$fields['attendance_date']      = "Attendace Date";
			$fields['remarks']              = "Remarks";
			$fields['attendance_status_id'] = "Type";
			
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_attendance($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_attendance($params)
	{
		try
		{
			
			$validation['attendance_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Attendance Date'
			);
			$validation['time_in'] = array(
					'data_type' => 'date',
					'name'		=> 'Time In'
			);	
			$validation['break_out'] = array(
					'data_type' => 'date',
					'name'		=> 'Break Out'
			);	
			$validation['break_in'] = array(
					'data_type' => 'date',
					'name'		=> 'Break In'
			);	
			$validation['time_out'] = array(
					'data_type' => 'date',
					'name'		=> 'Time Out'
			);	
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks'
			);	
			$validation['attendance_status_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Type'
			);	
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	

	public function modal_attendance_remarks($employee_id, $attendance_date, $time_flag, $edit_flag = 0)	
	{
		try
		{
			$data                    = array();
			$resources               = array();
			$data['employee_id']     = $employee_id;
			$data['attendance_date'] = $attendance_date;
			$data['time_flag']       = $time_flag;
			$data['edit_flag']       = $edit_flag;
			$resources['load_css'] 	= array(CSS_SELECTIZE);
			$resources['load_js']	= array(JS_SELECTIZE);
			if(EMPTY($employee_id) OR EMPTY($attendance_date) OR EMPTY($time_flag))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}

			$field                    = array("remarks","attendance_status_id") ;
			$table                    = $this->dtr->tbl_employee_attendance;
			$where                    = array();
			$key                      = $this->get_hash_key('employee_id');
			$where[$key]              = $employee_id;
			$where['attendance_date'] = $attendance_date;
			$where['time_flag']       = $time_flag;
			$data['attendance_info']  = $this->dtr->get_general_data($field, $table, $where, FALSE);

			$field                     = array("attendance_status_id","attendance_status_name") ;
			$table                     = $this->dtr->tbl_param_attendance_status;
			$where                     = array();
			$where['dtr_adjust_flag']  = YES;
			$data['attendance_status'] = $this->dtr->get_general_data($field, $table, $where, TRUE);

			$this->load->view('employee_attendance/modals/modal_attendance_remarks', $data);
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

	public function save_attendance_remarks()
	{
		try
		{
			
			$status          = FALSE;
			$message         = "";
			
			$params          = get_params();
			$employee_id     = $params['employee_id'];
			$attendance_date = $params['attendance_date'];
			$time_flag       = $params['time_flag'];

			if(EMPTY($employee_id) OR EMPTY($attendance_date) OR EMPTY($time_flag))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			
			Main_Model::beginTransaction();		

			
			if(!EMPTY($params['remarks']))
			{
				if(EMPTY($params['attendance_status_id']))
				{
					throw new Exception('<b>Type</b> is required.');
				}
				$fields                         = array();
				$fields['remarks']              = $params['remarks'];
				$fields['attendance_status_id'] = $params['attendance_status_id'];
				
				$table                    = $this->dtr->tbl_employee_attendance;
				$where                    = array();
				$where['attendance_date'] = $attendance_date;
				$where['time_flag']       = $time_flag;
				$key                      = $this->get_hash_key('employee_id');
				$where[$key]              = $employee_id;
				$this->dtr->update_general_data($table,$fields,$where);
				
				
			}	
			else
			{
				throw new Exception('<b>Remarks</b> is required.');
			}
			
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
		}
		catch(PDOException $e){
			 Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	public function modal_print_employee_dtr($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
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

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER);
			$resources['load_js']	= array(JS_DATETIMEPICKER);

			$this->load->view('employee_attendance/modals/modal_print_employee_dtr', $data);
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
	public function print_employee_dtr($employee_id,$date_range_from,$date_range_to)
	{
		try
		{
			
			$data                  = array();		
			
			
			$table                     = $this->dtr->tbl_employee_personal_info;
			$where                     = array();
			$key                       = $this->get_hash_key('employee_id');
			$where[$key]               = $employee_id;
			$emp_id                    = $this->dtr->get_general_data(array("employee_id"), $table, $where, FALSE);
			
			$params['employee_filtered']        = $emp_id['employee_id'];
			$params['date_range_from'] = $date_range_from;
			$params['date_range_to']   = $date_range_to;

			$generate_report = modules::load('main/reports_ta/ta_daily_time_record');
			$data            = $generate_report->generate_report_data($params);
			//add work schedule in dtr
			$this->load->model('daily_time_record_model', 'dtr');
			//Added validation if employee has multiple work schedule - davcorrea
			//=================START=============================
			$multipleSchedule = $this->dtr->if_employee_has_multiple_work_schedule($emp_id['employee_id'],$date_range_from,$date_range_to);
			if($multipleSchedule)
			{
				$data['work_schedule'][0] = $this->dtr->get_employee_work_schedule($emp_id['employee_id'],$date_range_from,$date_range_to);
				$data['work_schedule'][0]['work_schedule_name'] = "With Multiple Work Schedule";
			}
			else
			{
				$data['work_schedule'][0] = $this->dtr->get_employee_work_schedule($emp_id['employee_id'],$date_range_to);
			}
			//=================END=====================
			// $data['work_schedule'][0] = $this->dtr->get_employee_work_schedule($emp_id['employee_id'],$date_range_to);
			$this->load->helper('html');
			ini_set('memory_limit', '512M'); // boost the memory limit if it's low
			$this->load->library('pdf');
			//Legal Size Paper
			$pdf 	= $this->pdf->load('utf-8', array(210,297));
			
			$html 	= $this->load->view('forms/reports/'.REPORTS_TA_DAILY_TIME_RECORD, $data, TRUE);
			$pdf->WriteHTML($html);
			$pdf->Output();
			
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		
	}
	
	public function remove_time_log($employee_id, $attendance_date)
	{
		try
		{
			$status 	= FALSE;
			$message	= "";

			$params		= get_params();
			
			Main_Model::beginTransaction();
			$where = array();
			$where['employee_id'] = $params['employee_id'];
			$emp_det = $this->dtr->get_general_data(('*'),$this->dtr->tbl_employee_personal_info,$where,FALSE);
			$where = array();
			$where = array(
				'employee_id' => $params['employee_id'],
				'attendance_date' => $params['attendance_date']
			);
			$table = $this->dtr->tbl_employee_attendance;
			$prev_det = $this->dtr->get_general_data(('*'),$table,$where,TRUE);
			$audit_table[]				= $table;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= $prev_det;
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$module 					= MODULE_TA_DAILY_TIME_RECORD;
			$this->dtr->delete_general_data($table,$where);

			$audit_activity 			="Attendance of " .$emp_det['first_name'] ." " .$emp_det['last_name'] ." for ". $prev_det[0]['attendance_date'] . " deleted";
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			Main_Model::commit();
			
			$status = true;
			$message = "Time log <b>" . format_date($params['attendance_date'], 'F j, Y') . "</b> has been successfully deleted.";
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	public function get_leave_breakdown()
	{
		try
		{	
			$total_days  = "";
			$params      = get_params();
			


			$tables      = $this->dtr->tbl_employee_leave_details;
			$where       = array();
			$where['leave_detail_id'] = $params['id'];
			$details    = $this->dtr->get_general_data(array("*"), $tables, $where, FALSE);
			

			$employee_id = $details['employee_id'];
			$date_from   = $details['leave_start_date'];
			$date_to 	 = $details['leave_end_date'];

			$columns = '<div id="leave_columns_break_down">
							<div class="form-float-label" >';

			$date_availability = modules::load('main/attendance_late_undertime');
			
			$result = $date_availability->check_working_days($employee_id,$date_from,$date_to);							
			
			// echo"<pre>";
			// print_r($employee_id);
			// print_r($details);
			// print_r($date_to);
			// die();
			$cnt = 0;
			foreach($result as $aRow)
			{
				$tables     				 = $this->dtr->tbl_employee_returned_leaves;
				$where      				 = array();
				$where['leave_detail_id'] 	 = $params['id'];
				$where['leave_date_returned']= $aRow;
				$already_returned 			 = $this->dtr->get_general_data(array("*"), $tables, $where, FALSE);
				if($already_returned)
				{
					continue;
				}
				$cnt++;
				$columns .= '<div class="row m-n b-t b-light-gray">				
						<div class="col s8">
						<div class="input-field">
						<label for="leave_date" class="active">Date <span class="required"> * </span></label>
								<input id="leave_date" name="leave_date[]" type="text"  class="validate" value="'.$aRow.'" readonly/>
							</div>
						</div>

						<div class="col s4">
							<div class="input-field m-t-lg">
							<input id="returned_dates_'.$cnt.'" name="returned_dates[]" type="checkbox"  class="validate" value="'.$aRow.'" readonly/>
							<label for="returned_dates_'.$cnt.'" class="active">Return Date <span class="required"> * </span></label>
							</div>
						</div>
					</div>
					
					';
			}
			
			$columns .= '</div></div>';	
			$total_days = count($result);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		$data              = array();
		$data['breakdown'] = $columns;
		$data['total_days']  = $total_days;
	
		echo json_encode($data);
	}

}
/* End of file Employee_attendance.php */
/* Location: ./application/modules/main/controllers/Employee_attendance.php */