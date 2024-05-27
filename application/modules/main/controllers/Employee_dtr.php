<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_dtr extends Main_Controller {
	
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_dtr_model', 'dtr');
		$this->load->model('leaves_model', 'leaves');
		$this->load->model('requests_model', 'requests');
		$this->load->model('pds_model', 'pds');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}	
	public function index()
	{
		
		$this->load_employee_dtr();
	}
	public function load_employee_dtr($date_start = '',$date_end= '')
	{
		try
		{
			$data                    =  array();
			$resources               = array();
			$params = get_params();
			$resources['load_css']   = array(CSS_DATETIMEPICKER);
			$resources['load_js']    = array(JS_DATETIMEPICKER);
			$resources['load_modal'] = array(
					
					'modal_leave_application' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_leave_application',
							'multiple'   => true,
							'height'     => '500px',
							'size'       => 'md',
							'title'      => 'Leave Application'
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
					'modal_add_employee_attendance' => array(
						'controller'	=> 'employee_attendance',
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_attendance',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> 'Add Attendance'
					)
					,'modal_attendance_remarks' => array(
							'controller'	=> 'employee_attendance',
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_attendance_remarks',
							'multiple'		=> true,
							'height'		=> '200px',
							'size'			=> 'sm',
							'title'			=> 'Remarks'
						),
						// davcorrea 10/04/2023 added modal approving officer list : START
						'modal_view_approving_officer' => array(
								'controller' => __CLASS__,
								'module'     => PROJECT_MAIN,
								'method'     => 'modal_view_approving_officer',
								'multiple'   => true,
								// davcorrea : START : change modal size
								'height'     => '500px',
								'size'       => 'md',
								// davcorrea: END
								// 'height'     => '650px',
								// 'size'       => 'md',
								'title'      => 'Approving Officer'
						)
						// END
			);
			$id             = $this->session->userdata("user_pds_id");
			$module         = MODULE_PORTAL_DAILY_TIME_RECORD;
			$salt           = gen_salt();
			$token          = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
			
			$data['action'] = ACTION_EDIT;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;

			if(EMPTY($date_start) OR EMPTY($date_end) OR $date_start > $date_end)
			{
				$date_end  = date('Y/m/d');
				$date_start = date('Y/m/d', strtotime('-1 months', strtotime($date_end)));
			}
			$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
			$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');


			//ADDED to lessen load to SQL server
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

			// $table                     = $this->requests->tbl_employee_personal_info;
			// $where                     = array();
			// $key                       = $this->get_hash_key('employee_id');
			// $where[$key]               = $id;
			// $emp_id                    = $this->dtr->get_general_data(array("employee_id"), $table, $where, FALSE);
			
			// $id = $emp_id['employee_id'];


			$data['personal_info'] = $this->pds->get_employee_info($id);
			$time_logs             = $this->dtr->get_attendance_time_logs($id,$date_start,$date_end);

			$new_time_logs         = array();
			$date_array         = array();
			
			//marvin
			$new_date_array = array();
			
			if($time_logs)
			{
				$late_undertime = modules::load('main/attendance_late_undertime');
				
				foreach($time_logs as $time_log){
					
					$result = $late_undertime->check_late_undertime($time_log);
					
					//marvin
					// if with new time_out, change time_in to time_out and remove time_in afterwards
					// if($result['new_time_out'])
					// {
						// $time_log['time_out'] = $result['new_time_out'];
						// $time_log['time_in'] = '';
						// unset($result['new_time_out']);	
					// }
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
					if(!EMPTY($time_log['time_in']) AND !EMPTY($time_log['time_out']) AND !EMPTY($time_log['break_in']) AND !EMPTY($time_log['break_out']))
					{
						$time_log['has_four_log_flag'] = TRUE;
					}
					
					/*============== MARVIN : START : DISABLE EDITING OF TIME LOG IF MANUAL ADJUSTMENT REQUEST EXISTS =============*/
					//get request_sub_id
					$field 						= array('request_sub_id');
					$table 						= 'requests_employee_attendance';
					$where 						= array();
					$where['employee_id'] 		= $time_log['employee_id'];
					$where['attendance_date'] 	= $time_log['attendance_date'];
					$requestsubid 				= $this->requests->get_general_data($field, $table, $where);
					if(!EMPTY($requestsubid))
					{
						//get request_id
						$field 			= array('request_id');
						$table 			= 'requests_sub';
						$where 			= array();
						$reqsubid_arr 	= array();
						foreach($requestsubid as $reqsubid)
						{
							$reqsubid_arr[] = $reqsubid['request_sub_id'];
						}
						$where['employee_id'] 			= $time_log['employee_id'];
						$where['request_sub_id'] 		= array($reqsubid_arr, array('IN'));
						// $where['request_sub_status_id'] = 1;
						$requestid_validate 			= $this->requests->get_general_data($field, $table, $where);
						if(!EMPTY($requestid_validate))
						{
							$field 			= array('request_status_id');
							$table 			= 'requests';
							$where 			= array();
							$reqsubid_arr 	= array();
							foreach($requestid_validate as $requestid_val)
							{
								$reqsubid_arr[] = $requestid_val['request_id'];
							}
							$where['request_id'] 		= array($reqsubid_arr, array('IN'));
							// $where['request_sub_status_id'] = 1;
							$request_status_id_validate 			= $this->requests->get_general_data($field, $table, $where);
							if(!empty($request_status_id_validate))
							{
								foreach($request_status_id_validate as $req_status_validate)
								{
									if($req_status_validate['request_status_id'] == REQUEST_NEW || $req_status_validate['request_status_id'] == REQUEST_PENDING || $req_status_validate['request_status_id'] == REQUEST_ONGOING)
								{
									$time_log['has_pending_request'] = TRUE;
								}
								}
								
							}
							
						}
					}
					else
					{
						$time_log['has_pending_request'] = FALSE;
					}
					/*============== MARVIN : END : DISABLE EDITING OF TIME LOG IF MANUAL ADJUSTMENT REQUEST EXISTS =============*/
					
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
		
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "My Portal"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/employee_dtr";
			$key					= "Daily Time Record"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/employee_dtr";
			set_breadcrumbs($breadcrumbs, TRUE);
			$this->template->load('employee_dtr/employee_dtr', $data, $resources);
		}
		catch(Exception $e)
		{			
			$message = $e->getMessage();
			RLog::error($message);
		}
	}	
	
	public function modal_print_employee_dtr()
	{
		try
		{
			$data 					= array();
			$resources 				= array();

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER);
			$resources['load_js']	= array(JS_DATETIMEPICKER);

			$this->load->view('employee_dtr/modals/modal_print_employee_dtr', $data);
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

	
	public function modal_leave_application()
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$resources['load_css'] 	= array(CSS_MODAL_COMPONENT);
			$resources['load_js']	= array(JS_MODAL_CLASSIE,JS_MODAL_EFFECTS);
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);

			$employee_id  = $this->session->userdata("user_pds_id");
			$post_data = array('employee_id' => $employee_id);
			$resources['datatable'][]	= array('table_id' => 'table_employee_leave_list', 'path' => 'main/employee_dtr/get_employee_leave_list','advanced_filter'=>true,'post_data' => json_encode($post_data));
			
			$resources['load_modal'] = array(
				'modal_apply_leave' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_apply_leave',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'md',
						'title'			=> 'Leave Application'
				),
				'modal_leave_instructions' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_instructions',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'xl',
						'title'			=> 'Leave Instructions'
				),
				'modal_leave_history' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_history',
						'multiple'		=> true,
						'height'		=> '450px',
						'size'			=> 'lg',
						'title'			=> 'Leave History'
				)
		);
			
			$this->load->view('employee_dtr/modals/modal_leave_application', $data);
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
	// davcorrea 10/04/2023 added modal approving officer list : START
	public function modal_view_approving_officer()
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$resources['load_css'] 	= array(CSS_MODAL_COMPONENT);
			$resources['load_js']	= array(JS_MODAL_CLASSIE,JS_MODAL_EFFECTS);
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);

			$employee_id  = $this->session->userdata("user_pds_id");
			$post_data = array('employee_id' => $employee_id);
			$resources['datatable'][]	= array('table_id' => 'table_approving_officer_list','path' => 'main/employee_dtr/get_employee_approving_officers', 'advanced_filter'=>false,'post_data' => json_encode($post_data));
			
			
			$this->load->view('employee_dtr/modals/modal_view_approving_officer', $data);
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
	
	public function get_employee_leave_list()
	{

		try
		{
			$params = get_params();

			$aColumns 	= array("A.leave_type_id", "A.leave_type_name", "B.leave_balance","SUM(IF(E.request_sub_status_id = 1 AND F.no_of_days IS NOT NULL, F.no_of_days,0)) as pending_leave");
			$bColumns 	= array("A.leave_type_name", "B.leave_balance","B.leave_balance","SUM(IF(E.request_sub_status_id = 1 AND F.no_of_days IS NOT NULL, F.no_of_days,0))");
		
			$leave_type_list = $this->leaves->employee_get_leave_type_list($aColumns, $bColumns, $params);
			$iTotal          = $this->leaves->employee_leave_type_total_length();
			$iFilteredTotal  = $this->leaves->employee_leave_type_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			$module = MODULE_USER;
			$employee_id = $params['employee_id'];
			foreach ($leave_type_list as $aRow):
				$row = array();
				
				$id 			= $this->hash($aRow['leave_type_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module. '/' . $employee_id, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module. '/' . $employee_id, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;

				$row[] = $aRow['leave_type_name'];
				$row[] = !EMPTY($aRow['leave_balance']) ? $aRow['leave_balance'] : '0';
				$row[] = !EMPTY($aRow['pending_leave']) ? $aRow['pending_leave'] : '0';
				
				$action = "<div class='table-actions'>";

				// if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_leave_history' onclick=\"modal_leave_history_init('".$url_view."')\" data-delay='50'></a>";
				$action .= "<a href='#' class='apply tooltipped md-trigger' data-tooltip='Apply Leave' data-position='bottom' data-modal='modal_apply_leave' onclick=\"modal_apply_leave_init('".$url_edit."')\" data-delay='50'></a>";
				
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
	// davcorrea 10/04/2023 added function approving officer list : START
	public function get_employee_approving_officers()
	{

		try
		{
			$params = get_params();
			$aColumns 	= array("D.employee_id", "D.first_name", "D.last_name" , "B.user_id", "(SELECT GROUP_CONCAT(t2.module_name)FROM doh_ptis_core.modules t2 WHERE  t2.module_id != '124' AND  t2.module_id != '125' AND t2.module_id IN (SELECT t3.module_id FROM doh_ptis_core.user_offices t3 WHERE t3.user_id = B.user_id AND t3.office_id = B.office_id)ORDER BY t2.module_id ASC) AS Module_names");
			$bColumns   = array("Module_names");

			$approving_officers = $this->dtr->employee_get_approving_officer_list($params, $aColumns, $bColumns);
			$iTotal = count($approving_officers);
			// $iFilteredTotal = count($this->dtr->employee_get_approving_officer_filtered_list($params, $aColumns));
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
			);
			$module = MODULE_USER;
			$employee_id = $params['employee_id'];
			foreach ($approving_officers as $aRow){

				$approving_roles = $this->dtr->get_approving_officer_roles($aRow['user_id']);
				$prepare_role = "";
				$check_roles_by_modules = explode(",",$aRow['Module_names']);
				$superUser = false;
				foreach($approving_roles as $approving_role)
				{
					$correct_role = "FALSE";
					if(strtoupper($approving_role['role_name']) == SUPER_USER || strtoupper($approving_role['role_name']) == SUPER_ADMIN)
					{
						$superUser = true;
						continue;
					}
					if(strtoupper($approving_role['role_name']) == 'ADMIN OFFICER')
					{
						foreach($check_roles_by_modules as $check_roles_by_module)
						{
							if($check_roles_by_module == "Daily Time Record")
							{
								$correct_role = "TRUE";
							}
						}
					}
					if(strtoupper($approving_role['role_name']) == 'LEAVE APPROVING OFFICER')
					{

						foreach($check_roles_by_modules as $check_roles_by_module)
						{
							if($check_roles_by_module == "Leaves")
							{
								$correct_role = "TRUE";
							}
						}
					}
					if(strtoupper($approving_role['role_name']) == 'IMMEDIATE SUPERVISOR')
					{
						foreach($check_roles_by_modules as $check_roles_by_module)
						{
							if($check_roles_by_module == "Leaves")
							{
								$correct_role = "TRUE";
							}
						}
					}
					if($correct_role == "FALSE"){continue;}
					if(EMPTY($prepare_role))
					{
						$prepare_role = $approving_role['role_name'];
					}
					else
					{
						$prepare_role = $prepare_role . ", " . $approving_role['role_name'];
					}
					
				}
				if($superUser)
				{
					continue;
				}
				$row = array();
				$id 			= $this->hash($aRow['employee_id']);
				$salt			= gen_salt();
				$row[] = $aRow['first_name'] ." " . $aRow['last_name'];
				$row[] = $aRow['Module_names'];
				$row[] = $prepare_role;
				$output['aaData'][] = $row;
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

		echo json_encode( $output );
	}

	// END
	public function modal_apply_leave($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE,CSS_LABELAUTY);
			$resources['load_js']	= array(JS_DATETIMEPICKER,JS_SELECTIZE,JS_LABELAUTY);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key] 		= $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			
			$data['leave_type'] = $leave_type['leave_type_id'];
			
			/*===== marvin : start : include nature of deduction =====*/
			$data['nature_of_deduction'] = $leave_type['nature_of_deduction'];
			/*===== marvin : end : include nature of deduction =====*/
			$this->load->view('employee_dtr/modals/modal_apply_leave', $data);
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
	public function modal_leave_instructions()
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE,CSS_LABELAUTY);
			$resources['load_js']	= array(JS_DATETIMEPICKER,JS_SELECTIZE,JS_LABELAUTY);
			$data['test'] = "test";
			$this->load->view('employee_dtr/modals/modal_leave_instructions', $data);
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
	public function modal_leave_history($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			$resources['load_modal']		= array(
				'modal_leave_history_detail'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_history_detail',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'sm',
						'title'			=> "Leave History Detail"
				)
			);	
			$post_data = array(
				'employee_id' => $employee_id,
				'leave_type_id' => $id

				);
			$resources['datatable'][]	= array('table_id' => 'table_leave_history', 'path' => 'main/leaves/get_employee_leave_history','advanced_filter'=>true,'post_data' => json_encode($post_data));
			
			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];

			$this->load->view('employee_dtr/modals/modal_leave_history', $data);
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
	public function modal_leave_history_detail($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			
			$field                 = array("*") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_employee_leave_details,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_types,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.leave_type_id = B.leave_type_id',
				),
				't3'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_transaction_types,
					'alias'		=> 'C',
					'type'		=> 'join',
					'condition'	=> 'A.leave_transaction_type_id = C.leave_transaction_type_id',
				)
			);
			$where                 = array();
			$key                   = $this->get_hash_key('A.leave_detail_id');
			$where[$key]           = $id;
			$data['leave_history'] = $this->leaves->get_general_data($field, $tables, $where, FALSE);

			$this->load->view('leaves/modals/modal_leave_history_detail', $data);
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
	public function process_leave_request()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			$employee_id	= $params['employee_id'];			
			$process_id     = REQUEST_WORKFLOW_LEAVE_APPLICATION;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_leave_request($params);
			$employee_ids_valid	   = $this->dtr->get_general_data(('employee_id'), $this->dtr->tbl_employee_personal_info);
			foreach($employee_ids_valid as $employee_id_valid)
			{
				$prep_string = "%$".$employee_id_valid['employee_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $employee_id)
				{
					$dehashed_emp_id = $employee_id_valid['employee_id'];
					break;
				}
			}
			// davcorrea : START : validate if user has pending leave request on that day
			$pending_leave_req = $this->validate_pending_leave_request($dehashed_emp_id,$params['date_from'], $params['date_to']);
			if($pending_leave_req)
			{
				// check if leave was returned
				$returned_leave = $this->validate_returned_leave_request($dehashed_emp_id,$params['date_from'], $params['date_to']);
				if($returned_leave == "FALSE")
				{
					throw new Exception("Leave request already exist on dates requested");
				}

			}

			$timeLogs_exist = $this->validate_exisiting_time_logs($dehashed_emp_id,$params['date_from'], $params['date_to']);
			if($timeLogs_exist)
			{
				throw new Exception("Selected Days has existing time logs");
			}
			// END
			Main_Model::beginTransaction();
			
			/*############################ START : GET EMPLOYEE DATA #############################*/

			$table						= $this->requests->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where['employee_id']				= $dehashed_emp_id;
			$pds_data 					= $this->requests->get_general_data(array("employee_id"), $table, $where, FALSE);
			
			/*############################ END : GET EMPLOYEE DATA #############################*/

			/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
			$fields 						= array();
			$fields['employee_id']			= $pds_data["employee_id"];
			$fields['request_type_id']		= REQUEST_LEAVE_APPLICATION;
			$fields['request_status_id']	= REQUEST_NEW;
			$fields['date_requested']		= date("Y-m-d H:i:s");

			$table 							= $this->requests->tbl_requests;
			$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

			$audit_table[]			= $this->requests->tbl_requests;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			/*############################ END : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/

			/*############################ START : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/
			$quotient = 100 / $request_id;
			$addedZeroes = "";
			if ($quotient > 10) {
				$addedZeroes = "00";
			}
			elseif ($quotient > 0) {
				$addedZeroes = "0";
			}
			else {
				$addedZeroes = "";
			}
			
		
			$fields 				= array() ;
			$fields['request_code']	= date("Ym").$addedZeroes.$request_id;
			$where					= array();
			$where['request_id']	= $request_id;
			$table 					= $this->requests->tbl_requests;

			$this->requests->update_general_data($table,$fields,$where);

			/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

			/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
			$fields 							= array();
			$fields['employee_id']				= $pds_data["employee_id"];
			$fields['request_id']				= $request_id;
			if(isset($valid_data['commutation_flag']))
			{
				$fields['request_sub_type_id']		= TYPE_REQUEST_LA_COMMUTATION_REQUESTED;
			}
			elseif(isset($valid_data['monetize_flag']))
			{
				$fields['request_sub_type_id']		= TYPE_REQUEST_LA_MONETIZATION;
			}
			else
			{
				$fields['request_sub_type_id']		= TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED;
			}
			$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
			$fields['action']                = ACTION_PROCESS;
			
			$table                           = $this->requests->tbl_requests_sub;
			$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

			$audit_table[]			= $this->requests->tbl_requests_sub;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	
			/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

			/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			$fields                     = array();
			$fields['request_sub_id']   = $request_sub_id;
			$fields['no_of_days']       = $valid_data["no_of_days"];
			$fields['leave_type_id']    = $valid_data["type"];

			
			if(EMPTY($valid_data['commutation_flag']) AND EMPTY($valid_data['monetize_flag']))
			{
				$fields['date_from']        = $valid_data["date_from"];
				$fields['date_to']          = $valid_data["date_to"];

				if($valid_data['type'] == LEAVE_TYPE_VACATION)
				{
					/* marvin : start : disabled */
					// $fields['reason_flag']      = $valid_data["reason"];
					// if($valid_data['reason'] == "O")
					// {

						// $fields['reason_text']      = $valid_data["vl_details"];
						$fields['leave_location']   = $valid_data["location"];

						// if($valid_data['location'] == "A")
						// {
							$fields['location_text']    = $valid_data["vl_location"];
						// }
					// }
					/* marvin : end : disabled */
				}
				else if($valid_data['type'] == LEAVE_TYPE_SICK)
				{
					$fields['leave_location']   = $valid_data["sick_leave"];
					$fields['location_text']    = $valid_data["sl_details"];
				}
				/* marvin : start : include study_type_id */
				else if($valid_data['type'] == LEAVE_TYPE_STUDY)
				{
					$fields['study_type_id']   = $valid_data["study_type_id"];
				}
				/* marvin : end : include study_type_id */
				
				/* marvin : start : include special privilege */
				else if($valid_data['type'] == LEAVE_TYPE_SPECIAL_PRIVILEGE)
				{
					$fields['leave_location']   = $valid_data["location"];
					$fields['location_text']    = $valid_data["spl_location"];
				}
				/* marvin : end : include special privilege */
				
				/* marvin : start : include special privilege */
				else if($valid_data['type'] == LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN)
				{
					$fields['location_text']    = $valid_data["sbl_details"];
				}
				/* marvin : end : include special privilege */
			}
			elseif(!EMPTY($valid_data['monetize_flag']))
			{
				$fields['monetize_flag'] = $valid_data["monetize_flag"];
			}
			else
			{
				$fields['commutation_flag'] = $valid_data["commutation_flag"];
			}
			
			$table 							= $this->requests->tbl_requests_leaves;
			$this->requests->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->requests->tbl_requests_leaves;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	
			if(EMPTY($valid_data['commutation_flag']) AND EMPTY($valid_data['monetize_flag']))
			{
				foreach ($valid_data["leave_day_count"] as $key => $leave_day_count) {
					$fields                      = array();
					$fields['request_sub_id']    = $request_sub_id;
					$fields['no_of_days']        = $valid_data["leave_interval"][$key];
					$fields['leave_date']        = $valid_data["leave_date"][$key];
					$fields['leave_interval_id'] = $leave_day_count;

					$table 							= $this->requests->tbl_requests_leave_details;
					$this->requests->insert_general_data($table,$fields,FALSE);
				}
				
			}
			/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/

			/*############################ START : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
			
			$workflow 		= $this->requests->get_initial_task($process_id);
			

			$fields 					= array() ;
			$fields['request_id']		= $request_id;
			$fields['task_detail']		= $workflow['name'];
			$fields['process_id']		= $workflow['process_id'];
			$fields['process_stage_id']	= $workflow['process_stage_id'];
			$fields['process_step_id']	= $workflow['process_step_id'];
			$fields['task_status_id']	= 1;

			$table 						= $this->requests->tbl_requests_tasks;
			$this->requests->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->requests->tbl_requests_tasks;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	
			/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
			
			/*INSERT NOTIFICATION*/
			$request_notifications = modules::load('main/request_notifications');
			$request_notifications->insert_request_notification($request_id);


			$status = true;
			$message = "Request has been successfully submitted.<br> You can view your leave application status in the <b>Requests</b> module.";
						
			$activity 				= "%s has been submitted.";
			$audit_activity 		= sprintf($activity, "Leave request ");
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			
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

	private function _validate_leave_request($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();
			$fields['no_of_days']  = "Number of Days";

			if(!$params['commutation_flag'] AND !$params['monetize_flag'])
			{
				$fields['date_from']       = "Start Date";
				$fields['date_to']         = "End Date";
				
				$fields['leave_date']      = "Date";
				$fields['leave_day_count'] = "Day";
				if($params['type'] == LEAVE_TYPE_VACATION)
				{
					/* marvin : start : disabled */
					// $fields['reason']     = "Reason";
					// if($params['reason'] == "O")
					// {
						// $fields['vl_details'] = "Specific Reason";
						$fields['location'] = "Location";

						// if($params['location'] == "A")
						// {
							// $fields['vl_location'] = "Specific Location";
							$fields['vl_location'] = "Specify Place";
						// }
					// }
					/* marvin : end : disabled */
				}
				else if($params['type'] == LEAVE_TYPE_SICK)
				{
					$fields['sick_leave']     = "Sick Leave Type";
					$fields['sl_details']     = "Specific Details";
				}
				
				/* marvin : start : include study leave */
				else if($params['type'] == LEAVE_TYPE_STUDY)
				{
					$fields['study_type_id'] = 'In case of Study Leave';
				}
				/* marvin : end : include study leave */
				
				/* marvin : start : include special privilege leave */
				else if($params['type'] == LEAVE_TYPE_SPECIAL_PRIVILEGE)
				{
					$fields['location'] = "Location";
					$fields['spl_location'] = 'Specify Place';
				}
				/* marvin : end : include special privilege leave */
				
				/* marvin : start : include special privilege leave */
				else if($params['type'] == LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN)
				{
					$fields['sbl_details'] = "Specify Illness";
				}
				/* marvin : end : include special privilege leave */
			}
			

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_leave_request($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_leave_request($params)
	{
		try
		{
			
			$validation['leave_interval'] = array(
					'data_type' => 'digit',
					'name'		=> 'Interval',
					'max_len'	=> 11
			);	
			$validation['leave_day_count'] = array(
					'data_type' => 'digit',
					'name'		=> 'Day',
					'max_len'	=> 11
			);	
			$validation['leave_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date'
			);	
			$validation['commutation_flag'] = array(
					'data_type' => 'enum',
					'name'		=> 'Commutation',
					'max_len'	=> 1,
					'allowed_values' => array('Y')
			);	
			$validation['monetize_flag'] = array(
					'data_type' => 'enum',
					'name'		=> 'Monetization',
					'max_len'	=> 1,
					'allowed_values' => array('Y')
			);	
			$validation['no_of_days'] = array(
					'data_type' => 'digit',
					'name'		=> 'Number of Days',
					'max_len'	=> 11
			);	
			$validation['date_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Start Date',
					'max_date'  => $params['date_to']
			);	
			$validation['date_to'] = array(
					'data_type' => 'date',
					'name'		=> 'End Date'
			);
			$validation['reason'] = array(
					'data_type' => 'enum',
					'name'		=> 'Reason',
					'max_len'	=> 1,
					'allowed_values' => array('S','O')
			);		
			$validation['vl_details'] = array(
					'data_type' => 'string',
					'name'		=> 'Specific Reason'
			);
			$validation['location'] = array(
					'data_type' => 'enum',
					'name'		=> 'Location',
					'max_len'	=> 1,
					'allowed_values' => array('P','A')
			);		
			$validation['vl_location'] = array(
					'data_type' => 'string',
					// 'name'		=> 'Specific Location'
					'name'		=> 'Specify Place'
			);
			$validation['sick_leave'] = array(
					'data_type' => 'enum',
					'name'		=> 'Sick Leave Type',
					'max_len'	=> 1,
					'allowed_values' => array('H','O')
			);	
			$validation['sl_details'] = array(
					'data_type' => 'string',
					'name'		=> 'Specific Details'
			);	
			$validation['type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Leave Type',
					'max_len'	=> 11
			);
			/* marvin : start : include study_leave_id */
			if($params['type'] == LEAVE_TYPE_STUDY)
			{
				$validation['study_type_id'] = array(
						'data_type' => 'char',
						'name'		=> 'In case of Study Leave',
						'max_len'	=> 1
				);
			}
			/* marvin : start : include study_leave_id */
			
			/* marvin : start : include special privilege leave */
			if($params['type'] == LEAVE_TYPE_SPECIAL_PRIVILEGE)
			{
				$validation['spl_location'] = array(
					'data_type' => 'string',
					'name'		=> 'Specify Place'
				);
			}
			/* marvin : start : include special privilege leave */
			
			/* marvin : start : include special leave benefits for women */
			if($params['type'] == LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN)
			{
				$validation['sbl_details'] = array(
						'data_type' => 'string',
						'name'		=> 'Specify Illness'
				);
			}
			/* marvin : start : include special leave benefits for women */
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	
	public function process_manual_adjustment()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			$process_id     = REQUEST_WORKFLOW_MANUAL_ADJUSTMENT;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_adjustment($params);
			Main_Model::beginTransaction();		
			
			
			/*############################ START : GET EMPLOYEE DATA #############################*/

			$table           = $this->requests->tbl_employee_personal_info;
			$where           = array();
			$key             = $this->get_hash_key('employee_id');
			$where[$key]     = $id;
			$emp_info = $this->requests->get_general_data(array("*"), $table, $where, FALSE);
			
			/*############################ END : GET EMPLOYEE DATA #############################*/

			/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
			$fields                         = array();
			$fields['employee_id']			= $emp_info["employee_id"];
			$fields['request_type_id']		= REQUEST_MANUAL_ADJUSTMENT;
			$fields['request_status_id']	= REQUEST_NEW;
			$fields['date_requested']		= date("Y-m-d H:i:s");

			$table 							= $this->requests->tbl_requests;
			$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

			$audit_table[]			= $this->requests->tbl_requests;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			/*############################ END : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/

			/*############################ START : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/
			$quotient = 100 / $request_id;
			$addedZeroes = "";
			if ($quotient > 10) {
				$addedZeroes = "00";
			}
			elseif ($quotient > 0) {
				$addedZeroes = "0";
			}
			else {
				$addedZeroes = "";
			}
			
		
			$fields 				= array() ;
			$fields['request_code']	= date("Ym").$addedZeroes.$request_id;
			$where					= array();
			$where['request_id']	= $request_id;
			$table 					= $this->requests->tbl_requests;

			$this->requests->update_general_data($table,$fields,$where);

			/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

			

			/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			$attendance_fields = array();
			
			// MARVIN
			$raw_attendance_fields = array();


			// foreach ($valid_data['time_in'] as $key => $time_log) {
				
			// 	$employee_attendance_id = NULL;
			// 	$include                = true;
						
			// 	if(is_int($key))
			// 	{
			// 		$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
			// 		$employee_attendance_id = $key;
			// 		$attendance_date        = $prev_att['attendance_date'];					
			// 		if($prev_att['time_log'] == $time_log)
			// 		{
			// 			$include = false;						
			// 		}
			// 	}	

			// ======== START -davcorrea- validation for my portal date should be same sa attendance date and time in not later than time out ===========
			foreach ($valid_data['time_in'] as $key => $time_log) {
				
				$employee_attendance_id = NULL;
				$include                = true;
				foreach($valid_data['time_out'] as $key1 => $timeout_log) 
				{	
					if(!empty($timeout_log) && !empty($time_log))
						{
							if($timeout_log <= $time_log)
							{
								throw new Exception("Time Out should not be the same or earlier than Time In");
							}
						}
					}	
				

				if(is_int($key))
				{
					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];
					$timelogdate = explode(" ", $time_log);	
						if($attendance_date !== $timelogdate[0])
						{
							throw new Exception("Invalid Time In Date: Date should not be earlier or later than attendance date");
						}	
								
						
					
					if($prev_att['time_log'] == $time_log)
					{
						$include = false;						
					}
				}	
				// ================ END ==========================
				else
				{
					$attendance_date = $key;
				}
				
				if($include == true)	
				{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					// if(EMPTY($valid_data['remarks'][$attendance_date]))
							// throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
				
					$attendance_fields[] = array(
									'request_sub_id'         => $request_sub_id,
									'employee_attendance_id' => $employee_attendance_id,
									'employee_id'            => $emp_info['employee_id'],
									'attendance_date'        => $attendance_date,
									'time_flag'              => FLAG_TIME_IN,
									'time_log'               => $time_log,
									'remarks'               => $valid_data['remarks'][$attendance_date]
								);

					// MARVIN
					if(is_int($key))
					{
						$raw_log_time = $this->dtr->get_specific_time_log_dtl($key);		
	
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $raw_log_time['employee_attendance_id'],
							'employee_id'            	=> $raw_log_time['employee_id'],
							'attendance_date'        	=> $raw_log_time['attendance_date'],
							'time_flag'              	=> $raw_log_time['time_flag'],
							'time_log'               	=> $raw_log_time['time_log'],
							'remarks'					=> $raw_log_time['remarks']
						);
					}
					else
					{
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $employee_attendance_id,
							'employee_id'            	=> $emp_info['employee_id'],
							'attendance_date'        	=> $attendance_date,
							'time_flag'              	=> FLAG_TIME_IN,
							'time_log'               	=> $valid_data[$attendance_date]['time_in'],
							'remarks'					=> $params['remarks'][$attendance_date]
						);
					}
					
				}
			}
			// foreach ($valid_data['break_out'] as $key => $time_log) {				
				
			// 	$employee_attendance_id = NULL;
			// 	$include                = true;
						
			// 	if(is_int($key))
			// 	{
			// 		$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
			// 		$employee_attendance_id = $key;
			// 		$attendance_date        = $prev_att['attendance_date'];
			// 		if($prev_att['time_log'] == $time_log)
			// 		{
			// 			$include = false;
			// 		}
			// 	}	
			// 	else
			// 	{
			// 		$attendance_date = $key;					
			// 	}
			// ======== START -davcorrea- validation for my portal date should be same sa attendance date and time in not later than time out ===========
			foreach ($valid_data['break_out'] as $key => $time_log) {				
			
				$employee_attendance_id = NULL;
				$include                = true;
						
				if(is_int($key))
				{
					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];
					$timelogdate = explode(" ", $time_log);	
						if(!empty($time_log))
						{
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Break Out Date: Date should not be earlier or later than attendance date");
							}	
						}

					
					if($prev_att['time_log'] == $time_log)
					{
						$include = false;
					}
				}	
				else
				{
					$attendance_date = $key;					
				}
				// ===================== END==============================================
				if($include == true)	
				{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					// if(EMPTY($valid_data['remarks'][$attendance_date]))
							// throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
				
					$attendance_fields[] = array(
									'request_sub_id'         => $request_sub_id,
									'employee_attendance_id' => $employee_attendance_id,
									'employee_id'            => $emp_info['employee_id'],
									'attendance_date'        => $attendance_date,
									'time_flag'              => FLAG_BREAK_OUT,
									'time_log'               => $time_log,
									'remarks'               => $valid_data['remarks'][$attendance_date]
								);

					// MARVIN
					if(is_int($key))
					{
						$raw_log_time = $this->dtr->get_specific_time_log_dtl($key);		
	
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $raw_log_time['employee_attendance_id'],
							'employee_id'            	=> $raw_log_time['employee_id'],
							'attendance_date'        	=> $raw_log_time['attendance_date'],
							'time_flag'              	=> $raw_log_time['time_flag'],
							'time_log'               	=> $raw_log_time['time_log'],
							'remarks'					=> $raw_log_time['remarks']
						);
					}
					else
					{
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $employee_attendance_id,
							'employee_id'            	=> $emp_info['employee_id'],
							'attendance_date'        	=> $attendance_date,
							'time_flag'              	=> FLAG_BREAK_OUT,
							'time_log'               	=> $valid_data[$attendance_date]['time_in'],
							'remarks'					=> $params['remarks'][$attendance_date]
						);
					}
				}	
			}	
			
			// foreach ($valid_data['break_in'] as $key => $time_log) {				
			
			// 	$employee_attendance_id = NULL;
			// 	$include                = true;
			// 	if(is_int($key))
			// 	{
			// 		$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
			// 		$employee_attendance_id = $key;
			// 		$attendance_date        = $prev_att['attendance_date'];
			// 		$timelogdate = explode(" ", $time_log);											
			// 		if($prev_att['time_log'] == $time_log)
			// 		{
			// 			$include = false;
			// 		}
			// 	}

			// ======== START -davcorrea- validation for my portal date should be same sa attendance date and time in not later than time out ===========
			foreach ($valid_data['break_in'] as $key => $time_log) {				
			
				$employee_attendance_id = NULL;
				$include                = true;
						
				foreach($valid_data['break_out'] as $key => $timeout_log) 
				{
					if(!EMPTY($timeout_log) &&  !EMPTY($time_log))
					{
						// if($timeout_log <= $time_log)
						// {
						// throw new Exception("Time Out should not be the same or earlier than Time In");
						// }
					
							// // davcorrea : START :corrected break IN/ Break Out validation
							if($timeout_log >= $time_log)
							{
								throw new Exception("Break In cannot be the same or earlier than Break Out");
							}
							// END
	

					}	
				}
				if(is_int($key))
				{
					$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
					$employee_attendance_id = $key;
					$attendance_date        = $prev_att['attendance_date'];
					$timelogdate = explode(" ", $time_log);	
					if(!empty($time_log))
						{										
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Break In Date: Date should not be earlier or later than attendance date");
							}	
						}
							
					if($prev_att['time_log'] == $time_log)
					{
						$include = false;
					}
				}	
				else
				{
					$attendance_date = $key;					
				}
				// ============================ END ==================================

				if($include == true)	
				{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					// if(EMPTY($valid_data['remarks'][$attendance_date]))
							// throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
				
					$attendance_fields[] = array(
									'request_sub_id'         => $request_sub_id,
									'employee_attendance_id' => $employee_attendance_id,
									'employee_id'            => $emp_info['employee_id'],
									'attendance_date'        => $attendance_date,
									'time_flag'              => FLAG_BREAK_IN,
									'time_log'               => $time_log,
									'remarks'               => $valid_data['remarks'][$attendance_date]
								);
								
					// MARVIN
					if(is_int($key))
					{
						$raw_log_time = $this->dtr->get_specific_time_log_dtl($key);		
	
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $raw_log_time['employee_attendance_id'],
							'employee_id'            	=> $raw_log_time['employee_id'],
							'attendance_date'        	=> $raw_log_time['attendance_date'],
							'time_flag'              	=> $raw_log_time['time_flag'],
							'time_log'               	=> $raw_log_time['time_log'],
							'remarks'					=> $raw_log_time['remarks']
						);
					}
					else
					{
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $employee_attendance_id,
							'employee_id'            	=> $emp_info['employee_id'],
							'attendance_date'        	=> $attendance_date,
							'time_flag'              	=> FLAG_BREAK_IN,
							'time_log'               	=> $valid_data[$attendance_date]['time_in'],
							'remarks'					=> $params['remarks'][$attendance_date]
						);
					}
				}	
			}		
			// foreach ($valid_data['time_out'] as $key => $time_log) {	

			// 	$employee_attendance_id = NULL;
			// 	$include                = true;

			// 	if(is_int($key))
			// 	{
			// 		$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
			// 		$employee_attendance_id = $key;
			// 		$attendance_date        = $prev_att['attendance_date'];	
			// 		if($prev_att['time_log'] == $time_log)
			// 		{
			// 			$include = false;
			// 		}
			// 	}	
			// 	else
			// 	{
			// 		$attendance_date = $key;					
			// 	}

				// ======== START -davcorrea- validation for my portal date should be same sa attendance date and time in not later than time out ===========
				foreach ($valid_data['time_out'] as $key => $time_log) {	

					$employee_attendance_id = NULL;
					$include                = true;
					if(!empty($key) && !empty($time_log))
					{
						$timelogdate = explode(" ", $time_log);	
															
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Time Out Date: Date should not be earlier or later than attendance date");
							}		
					}
					if(is_int($key))
					{
						$prev_att               = $this->dtr->get_specific_time_log_dtl($key);
						$employee_attendance_id = $key;
						$attendance_date        = $prev_att['attendance_date'];
						$timelogdate = explode(" ", $time_log);	
						if(!empty($time_log))
						{
							if($attendance_date !== $timelogdate[0])
							{
								throw new Exception("Invalid Time Out Date: Date should not be earlier or later than attendance date");
							}		
						}
							
						
						if($prev_att['time_log'] == $time_log)
						{
							$include = false;
						}
					}	
					else
					{
						$attendance_date = $key;					
					}
				// ====================================== END ===============================================
				if($include == true)	
				{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					// if(EMPTY($valid_data['remarks'][$attendance_date]))
							// throw new Exception("<b>Remarks</b> is required for <b>".$attendance_date);
				
					$attendance_fields[] = array(
									'request_sub_id'         => $request_sub_id,
									'employee_attendance_id' => $employee_attendance_id,
									'employee_id'            => $emp_info['employee_id'],
									'attendance_date'        => $attendance_date,
									'time_flag'              => FLAG_TIME_OUT,
									'time_log'               => $time_log,
									'remarks'               => $valid_data['remarks'][$attendance_date]
								);
					
					// MARVIN
					if(is_int($key))
					{
						$raw_log_time = $this->dtr->get_specific_time_log_dtl($key);		
	
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $raw_log_time['employee_attendance_id'],
							'employee_id'            	=> $raw_log_time['employee_id'],
							'attendance_date'        	=> $raw_log_time['attendance_date'],
							'time_flag'              	=> $raw_log_time['time_flag'],
							'time_log'               	=> $raw_log_time['time_log'],
							'remarks'					=> $raw_log_time['remarks']
						);
					}
					else
					{
						$raw_attendance_fields[] = array(
							'request_sub_id'         	=> $request_sub_id,
							'employee_attendance_id' 	=> $employee_attendance_id,
							'employee_id'            	=> $emp_info['employee_id'],
							'attendance_date'        	=> $attendance_date,
							'time_flag'              	=> FLAG_TIME_OUT,
							'time_log'               	=> $valid_data[$attendance_date]['time_in'],
							'remarks'					=> $params['remarks'][$attendance_date]
						);
					}
				}	
			}
			
			// MARVIN
			if($attendance_fields != $raw_attendance_fields)
			{
				$trash = array();

				for($i=0;$i<count($attendance_fields);$i++)
				{
					if($attendance_fields[$i] != $raw_attendance_fields[$i])
					{
						if(empty($attendance_fields[$i]['remarks']))
						{
							throw new Exception("<b>Remarks</b> is required for <b>" . $attendance_fields[$i]['attendance_date']);		
						}
					}
					else
					{
						$trash[$i] = $attendance_fields[$i];
					}
				}
				
				foreach($trash as $key => $bin)
				{
					unset($attendance_fields[$key]);
				}
				
				sort($attendance_fields);

				$table 	= $this->requests->tbl_requests_employee_attendance;

				for($i=0;$i<count($attendance_fields);$i++)
				{
					if(empty($attendance_fields[$i]['time_log']))
					{
						$attendance_fields[$i]['time_log'] = null;
					}
				}
				
				$this->requests->insert_general_data($table,$attendance_fields,FALSE);

				$audit_table[]			= $this->requests->tbl_requests_employee_attendance;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= $attendance_fields;
				$audit_action[] 		= AUDIT_INSERT;	
			}
			else
			{
				throw new Exception('No changes has been made.');
			}

			// ASIAGATE
			// if(!EMPTY($attendance_fields))
			// {
				// $table 	= $this->requests->tbl_requests_employee_attendance;

				// for($i=0;$i<count($attendance_fields);$i++)
				// {
					// if(empty($attendance_fields[$i]['time_log']))
					// {
						// $attendance_fields[$i]['time_log'] = null;
					// }
				// }
				
				// $this->requests->insert_general_data($table,$attendance_fields,FALSE);

				// $audit_table[]			= $this->requests->tbl_requests_employee_attendance;
				// $audit_schema[]			= DB_MAIN;
				// $prev_detail[] 			= array();
				// $curr_detail[]			= $attendance_fields;
				// $audit_action[] 		= AUDIT_INSERT;	
			// }
			// else
			// {
				// throw new Exception('No changes has been made.');
			// }
			
			/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
			


			/*############################ START : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
			
			$workflow 		= $this->requests->get_initial_task($process_id);
			RLog::info('LINE 1112 =>'.json_encode($workflow));

			$fields 					= array() ;
			$fields['request_id']		= $request_id;
			$fields['task_detail']		= $workflow['name'];
			$fields['process_id']		= $workflow['process_id'];
			$fields['process_stage_id']	= $workflow['process_stage_id'];
			$fields['process_step_id']	= $workflow['process_step_id'];
			$fields['task_status_id']	= 1;

			$table 						= $this->requests->tbl_requests_tasks;
			$this->requests->insert_general_data($table,$fields,FALSE);

			/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
			/*INSERT NOTIFICATION*/
			$request_notifications = modules::load('main/request_notifications');
			$request_notifications->insert_request_notification($request_id);
		
						
			$audit_activity 		= "Attendance Manual Adjustment Edit Request submitted for " . $emp_info['first_name'] . " " . $emp_info['last_name'];

			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			$status = true;
			$message = "Request has been successfully submitted and may take effect once approved.";
			Main_Model::commit();
		
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

	private function _validate_adjustment($params)
	{
		try
		{
			
				
			return $this->_validate_input_adjustment($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_adjustment($params)
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
	public function get_leave_breakdown()
	{
		try
		{	
			$total_days  = "";
			$params      = get_params();
			
			$user_pds_id = $this->session->userdata('user_pds_id');

			$key = $this->get_hash_key('employee_id');
			$tables      = $this->requests->tbl_employee_personal_info;
			$where       = array();
			$where[$key] = $user_pds_id;
			$emp_info    = $this->requests->get_general_data(array("employee_id"), $tables, $where, FALSE);


			$employee_id = $emp_info['employee_id'];

			$columns = '<div id="leave_columns_break_down">
							<div class="form-float-label" >';

			$date_availability = modules::load('main/attendance_late_undertime');
			
			
			// $result            = $date_availability->check_working_days($employee_id,$params['date_from'],$params['date_to']);	

			/*===== marvin : start : include nature_of_deduction =====*/
			if($params['nature_of_deduction'] == 1)
			{
				$result = $date_availability->check_working_days_with_nod($employee_id,$params['date_from'],$params['date_to']);					
			}
			else
			{
				$result = $date_availability->check_working_days($employee_id,$params['date_from'],$params['date_to']);							
			}
			/*===== marvin : end : include nature_of_deduction =====*/

			$tables            = $this->requests->tbl_param_leave_intervals;
			$where             = array();
			$where['active_flag'] = "Y"; 
			$intervals         = $this->requests->get_general_data(array("*"), $tables, $where, TRUE);

			$options = "";
			if($intervals)
			{
				foreach($intervals as $aRow)
				{
					$selected ="";
					if($aRow['leave_interval_id'] == LEAVE_WHOLE_DAY)
					$selected = " selected ";

					$options .= "<option value='".$aRow['leave_interval_id']."' ".$selected.">".$aRow['leave_interval_name']."</option>";
				}
			}
			foreach($result as $aRow)
			{
				$columns .= '<div class="row m-n b-t b-light-gray">				
						<div class="col s6">
							<div class="input-field">
								<input id="leave_date" name="leave_date[]" type="text"  class="validate" value="'.$aRow.'" readonly/>
								<label for="leave_date" class="active">Date <span class="required"> * </span></label>
							</div>
						</div>
						<div class="col s6 date_row">
							<div class="input-field">
								<label for="leave_day_count" class="active">Duration <span class="required"> * </span></label>
								<select name="leave_day_count[]" class="selectize leave_day_count" placeholder="Select Days" onchange ="update_leave_count(this)">
									<option value="">Select Days</option>
									'.$options.'					
								</select>
								<input name="leave_interval[]" type="hidden"  class="validate leave_interval" value="'.LEAVE_WHOLE_DAY.'"/>
							</div>
						</div>
					</div>';
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
	public function get_interval_value()
	{
		try
		{	
			$total_days = "";
			$params = get_params();

			$tables                     = $this->requests->tbl_param_leave_intervals;
			$where                      = array();
			$where['leave_interval_id'] = $params['interval'];
			$intervals                  = $this->requests->get_general_data(array("*"), $tables, $where, FALSE);
			
		}
		catch(Exception $e)
		{
			throw $e;
		}
		$data                   = array();
		$data['interval_value'] = $intervals['leave_duration'];
	
		echo json_encode($data);
	}
	public function print_employee_dtr($date_range_from,$date_range_to)
	{
		try
		{
			$data                  = array();		
			
			
			$table                     = $this->requests->tbl_employee_personal_info;
			$where                     = array();
			$key                       = $this->get_hash_key('employee_id');
			$where[$key]               = $this->session->userdata("user_pds_id");
			$emp_id                    = $this->requests->get_general_data(array("employee_id"), $table, $where, FALSE);
			
			// $params['employee']        = $emp_id['employee_id'];
			
			//marvin
			//fix the parameter
			$params['employee']        = $emp_id;
			
			$params['date_range_from'] = $date_range_from;
			$params['date_range_to']   = $date_range_to;
			
			$generate_report = modules::load('main/reports_ta/ta_daily_time_record');
			$data            = $generate_report->generate_report_data($params);
			
			//add work schedule in dtr
			$this->load->model('daily_time_record_model', 'dtr');
			// $data['work_schedule'][0] = $this->dtr->get_employee_work_schedule($emp_id['employee_id'],$date_range_to);

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
			$process_id     = REQUEST_WORKFLOW_MANUAL_ADJUSTMENT;
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
			$employee_ids	   = $this->dtr->get_general_data(('employee_id'), $this->dtr->tbl_employee_personal_info);
			foreach($employee_ids as $employee_id)
			{
				$prep_string = "%$".$employee_id['employee_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $id)
				{
					$dehashed_emp_id = $employee_id['employee_id'];
					break;
				}
			}
			// marvin : check work schedule : start
			// $field       = array("employee_id") ;
			// $table       = $this->dtr->tbl_employee_personal_info;
			// $where       = array();
			// // $key         = $this->get_hash_key('employee_id');
			// $where['employee_id'] = $dehashed_emp_id;
			// $emp_info    = $this->dtr->get_general_data($field, $table, $where, FALSE);
			
			$work_schedule = $this->dtr->get_employee_work_schedule($dehashed_emp_id, $valid_data['attendance_date']);
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
			
			//marvin
			//validation dtr new entry
			// if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['time_in'], 0, 10))
			// {
				// throw new Exception("<b>Attendance Date</b> doesn't match the <b>Time in</b> Date");
			// }
			// if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['break_out'], 0, 10))
			// {
				// throw new Exception("<b>Attendance Date</b> doesn't match the <b>Break out</b> Date");
			// }
			// if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['break_in'], 0, 10))
			// {
				// throw new Exception("<b>Attendance Date</b> doesn't match the <b>Break in</b> Date");
			// }
			// if(substr($valid_data['attendance_date'], 0, 10) != substr($valid_data['time_out'], 0, 10))
			// {
				// throw new Exception("<b>Attendance Date</b> doesn't match the <b>Time out</b> Date");
			// }
			//end
			//davcorrea: validate if attendance date already has pending request : START
			$field       = array('*') ;
			$table       = $this->requests->tbl_requests_employee_attendance;
			$where       = array();
			// $key         = $this->get_hash_key('employee_id');
			$where['employee_id'] = $dehashed_emp_id;
			$where['attendance_date'] = $params['attendance_date'];
			$emp_valid_requests    = $this->requests->get_general_data($field, $table, $where, TRUE);
			
			if(!EMPTY($emp_valid_requests)){
				$req_sub_id = end($emp_valid_requests);
				$field       = array('*') ;
				$table       = $this->requests->tbl_requests_sub;
				$where       = array();
				$where['request_sub_id'] = $req_sub_id['request_sub_id'];
				$emp_valid_requests_sub    = $this->requests->get_general_data($field, $table, $where, FALSE);

				$field       = array('*') ;
				$table       = $this->requests->tbl_requests;
				$where       = array();
				$where['request_id'] = $emp_valid_requests_sub['request_id'];
				$emp_valid_requests_main    = $this->requests->get_general_data($field, $table, $where, FALSE);
				if($emp_valid_requests_main['request_status_id'] == REQUEST_NEW || $emp_valid_requests_main['request_status_id'] == REQUEST_PENDING || $emp_valid_requests_main['request_status_id'] == REQUEST_ONGOING)
				{
					throw new Exception("<b>Attendance Date</b> Already has pending request");
				}
			}

			$field       = array('*') ;
			$table       = $this->requests->tbl_employee_attendance;
			$where       = array();
			// $key         = $this->get_hash_key('employee_id');
			$where['employee_id'] = $dehashed_emp_id;
			$where['attendance_date'] = $params['attendance_date'];
			$emp_valid_dtr    = $this->requests->get_general_data($field, $table, $where, FALSE);
			if(!EMPTY($emp_valid_dtr)){
				throw new Exception("<b>Attendance Date</b> Already exists");
			}

			//END
			Main_Model::beginTransaction();

			/*############################ START : GET EMPLOYEE DATA #############################*/

			$table           = $this->requests->tbl_employee_personal_info;
			$where           = array();
			// $key             = $this->get_hash_key('employee_id');
			$where['employee_id'] = $dehashed_emp_id;
			$emp_info = $this->requests->get_general_data(array("*"), $table, $where, FALSE);
			
			/*############################ END : GET EMPLOYEE DATA #############################*/

			/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
			$fields                         = array();
			$fields['employee_id']			= $emp_info["employee_id"];
			$fields['request_type_id']		= REQUEST_MANUAL_ADJUSTMENT;
			$fields['request_status_id']	= REQUEST_NEW;
			$fields['date_requested']		= date("Y-m-d H:i:s");

			$table 							= $this->requests->tbl_requests;
			$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

			$audit_table[]			= $this->requests->tbl_requests;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			/*############################ END : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/

			/*############################ START : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/
			$quotient = 100 / $request_id;
			$addedZeroes = "";
			if ($quotient > 10) {
				$addedZeroes = "00";
			}
			elseif ($quotient > 0) {
				$addedZeroes = "0";
			}
			else {
				$addedZeroes = "";
			}
			
		
			$fields 				= array() ;
			$fields['request_code']	= date("Ym").$addedZeroes.$request_id;
			$where					= array();
			$where['request_id']	= $request_id;
			$table 					= $this->requests->tbl_requests;

			$this->requests->update_general_data($table,$fields,$where);

			/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

			
			if($valid_data['time_in'])
			{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					if($params['attendance_status_id'] == REGULAR_DAY)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					}
					if($params['attendance_status_id'] == OFFICIAL_BUSINESS)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS;
					}

					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields = array(
							'request_sub_id'  => $request_sub_id,
							'employee_id'     => $emp_info['employee_id'],
							'attendance_date' => $valid_data['attendance_date'],
							'time_flag'       => FLAG_TIME_IN,
							'time_log'        => $valid_data['time_in'],
							'remarks'         => $valid_data['remarks']
						);
					$table 	= $this->dtr->tbl_requests_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
			}
				
			
			if($valid_data['break_out'])
			{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					if($params['attendance_status_id'] == REGULAR_DAY)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					}
					if($params['attendance_status_id'] == OFFICIAL_BUSINESS)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS;
					}
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

					$fields = array(
							'request_sub_id'  => $request_sub_id,
							'employee_id'     => $emp_info['employee_id'],
							'attendance_date' => $valid_data['attendance_date'],
							'time_flag'       => FLAG_BREAK_OUT,
							'time_log'        => $valid_data['break_out'],
							'remarks'         => $valid_data['remarks']
						);
					$table 	= $this->dtr->tbl_requests_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
			}
				
			
			if($valid_data['break_in'])
			{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					if($params['attendance_status_id'] == REGULAR_DAY)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					}
					if($params['attendance_status_id'] == OFFICIAL_BUSINESS)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS;
					}
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields = array(
							'request_sub_id'     => $request_sub_id,
							'employee_id'     => $emp_info['employee_id'],
							'attendance_date' => $valid_data['attendance_date'],
							'time_flag'       => FLAG_BREAK_IN,
							'time_log'        => $valid_data['break_in'],
							'remarks'         => $valid_data['remarks']
						);
					$table 	= $this->dtr->tbl_requests_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
			}
				
			if($valid_data['time_out'])
			{
					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields                          = array();
					$fields['employee_id']           = $emp_info["employee_id"];
					$fields['request_id']            = $request_id;
					if($params['attendance_status_id'] == REGULAR_DAY)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT;
					}
					if($params['attendance_status_id'] == OFFICIAL_BUSINESS)
					{
						$fields['request_sub_type_id']   = TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS;
					}
					$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
					$fields['action']                = ACTION_PROCESS;
					
					$table                           = $this->requests->tbl_requests_sub;
					$request_sub_id                  = $this->requests->insert_general_data($table,$fields,TRUE);

					$audit_table[]			= $this->requests->tbl_requests_sub;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields = array(
							'request_sub_id'  => $request_sub_id,
							'employee_id'     => $emp_info['employee_id'],
							'attendance_date' => $valid_data['attendance_date'],
							'time_flag'       => FLAG_TIME_OUT,
							'time_log'        => $valid_data['time_out'],
							'remarks'         => $valid_data['remarks']
						);
					$table 	= $this->dtr->tbl_requests_employee_attendance;
					$this->dtr->insert_general_data($table,$fields,false);
			}
			$workflow 		= $this->requests->get_initial_task($process_id);
			RLog::info('LINE 1112 =>'.json_encode($workflow));

			$fields 					= array() ;
			$fields['request_id']		= $request_id;
			$fields['task_detail']		= $workflow['name'];
			$fields['process_id']		= $workflow['process_id'];
			$fields['process_stage_id']	= $workflow['process_stage_id'];
			$fields['process_step_id']	= $workflow['process_step_id'];
			$fields['task_status_id']	= 1;

			$table 						= $this->requests->tbl_requests_tasks;
			$this->requests->insert_general_data($table,$fields,FALSE);
			/*INSERT NOTIFICATION*/
			$request_notifications = modules::load('main/request_notifications');
			$request_notifications->insert_request_notification($request_id);

			$audit_activity 		= "Attendance Manual Adjustment Add Request submitted for " . $emp_info['first_name'] . " " . $emp_info['last_name'];;

			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);

			Main_Model::commit();
			$status = true;
			$message = "Request has been successfully submitted and may take effect once approved.";
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
			$fields                    = array();
			$fields['attendance_date'] = "Attendace Date";
			$fields['remarks']         = "Remarks";
			
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
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	// davcorrea : START : validate if user has pending leave request on that day
	private function validate_pending_leave_request($id, $datefrom, $dateto)
	{
		try
		{
			$pending_leave_exist = false;

			$fields 						= array('*');
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where['employee_id']					= $id;
			$where['request_sub_status_id']	= SUB_REQUEST_NEW;
			$where['request_sub_type_id']	= TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED;
			$table 							= $this->requests->tbl_requests_sub;

			$requests_sub_id = $this->requests->get_general_data($fields,$table,$where, TRUE);
			
			foreach($requests_sub_id as $requests_sub_id)
			{
				$req_leaves = $this->requests->validate_requests_leaves($requests_sub_id['request_sub_id']);
				$datefrom = date('Y-m-d', strtotime($datefrom));
				$dateto = date('Y-m-d', strtotime($dateto));
				$pen_date_from = $req_leaves['date_from'];
				$pen_date_to = $req_leaves['date_to'];
				if((($datefrom >= $pen_date_from) && ($datefrom <= $pen_date_to)) || (($dateto >= $pen_date_from) && ($dateto <= $pen_date_to)))
				{
					return $pending_leave_exist = true;
				}

				
			}
			$fields 						= array('*');
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where['employee_id']					= $id;
			$where['request_sub_status_id']	= SUB_REQUEST_APPROVED;
			$where['request_sub_type_id']	= TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED;
			$table 							= $this->requests->tbl_requests_sub;

			$requests_sub_id = $this->requests->get_general_data($fields,$table,$where, TRUE);
			
			foreach($requests_sub_id as $requests_sub_id)
			{
				$req_leaves = $this->requests->validate_requests_leaves($requests_sub_id['request_sub_id']);
				$datefrom = date('Y-m-d', strtotime($datefrom));
				$dateto = date('Y-m-d', strtotime($dateto));
				$pen_date_from = $req_leaves['date_from'];
				$pen_date_to = $req_leaves['date_to'];
				if((($datefrom >= $pen_date_from) && ($datefrom <= $pen_date_to)) || (($dateto >= $pen_date_from) && ($dateto <= $pen_date_to)))
				{
					return $pending_leave_exist = true;
				}

				
			}
			return $pending_leave_exist;

		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	private function validate_exisiting_time_logs($id, $datefrom, $dateto)
	{
		try
		{
			$timeLogs_exist = false;
			
			$datefrom = date('Y-m-d', strtotime($datefrom));
			$dateto = date('Y-m-d', strtotime($dateto));
			$attendance_dates = $this->requests->validate_attendance_dates($id, $datefrom, $dateto);
			if(!empty($attendance_dates))
			{
				return $timeLogs_exist = true;				
			}
			return $timeLogs_exist;

		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	// END

	private function validate_returned_leave_request($id, $datefrom, $dateto)
	{
		try
		{
			$returned_leave_exist = "FALSE";

			$fields 						= array('*');
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where['employee_id']					= $id;
			$where['request_sub_status_id']	= SUB_REQUEST_APPROVED;
			$where['request_sub_type_id']	= TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED;
			$table 							= $this->requests->tbl_requests_sub;

			$requests_sub_id = $this->requests->get_general_data($fields,$table,$where, TRUE);
			
			foreach($requests_sub_id as $requests_sub_id)
			{
				$req_leaves = $this->requests->validate_requests_leaves($requests_sub_id['request_sub_id']);
				$datefrom = date('Y-m-d', strtotime($datefrom));
				$dateto = date('Y-m-d', strtotime($dateto));
				$pen_date_from = $req_leaves['date_from'];
				$pen_date_to = $req_leaves['date_to'];
				if((($datefrom >= $pen_date_from) && ($datefrom <= $pen_date_to)) || (($dateto >= $pen_date_from) && ($dateto <= $pen_date_to)))
				{
					$fields 						= array('*');
					$where							= array();
					$key 							= $this->get_hash_key('employee_id');
					$where['employee_id']				= $id;
					$where['leave_type_id']				= $req_leaves['leave_type_id'];
					$where['leave_transaction_type_id']	= LEAVE_REVERSE_LEAVE;
					$table 								= $this->requests->tbl_employee_leave_details;
					$returned_leaves = $this->requests->get_general_data($fields,$table,$where, TRUE);
					foreach($returned_leaves as $returned_leave)
					{
						$ret_date_from = $returned_leave['leave_start_date'];
						$ret_date_to = $returned_leave['leave_end_date'];
	
						if((($datefrom >= $ret_date_from) && ($datefrom <= $ret_date_to)) || (($dateto >= $ret_date_from) && ($dateto <= $ret_date_to)))
						{
							return $returned_leave_exist = "TRUE";
						}

					}
					$fields 						= array('*');
					$where							= array();
					$key 							= $this->get_hash_key('employee_id');
					$where['employee_id']				= $id;
					$where['leave_type_id']				= $req_leaves['leave_type_id'];
					$where['leave_transaction_type_id']	= LEAVE_FILE_LEAVE;
					$table 								= $this->requests->tbl_employee_leave_details;
					$filed_leaves = $this->requests->get_general_data($fields,$table,$where, TRUE);
					foreach($filed_leaves as $filed_leave)
					{
						$fields 						= array('*');
						$where							= array();
						$key 							= $this->get_hash_key('employee_id');
						$where['leave_detail_id']		= $filed_leave['leave_detail_id'];
						$table 							= $this->requests->tbl_employee_returned_leaves;
						$_returned_leaves = $this->requests->get_general_data($fields,$table,$where, TRUE);

						foreach($_returned_leaves as $_returned_leave)
						{
							if($_returned_leave['leave_date_returned'] == $datefrom && $_returned_leave['leave_date_returned'] == $dateto)
							return $returned_leave_exist = "TRUE";	
						}

					}

				}

			}
			return $returned_leave_exist;

		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
}


/* End of file Work_schedules.php */
/* Location: ./application/modules/main/controllers/Work_schedules.php */