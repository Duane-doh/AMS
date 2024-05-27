<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requests_model extends Main_Model {

	public $db_core                 = DB_CORE;
	public $tbl_users               = "users";
	public $tbl_process_stage_roles = "process_stage_roles";
	public $tbl_actions             = "actions";
	public $tbl_process_stages      = "process_stages";
	public $tbl_process_steps       = "process_steps";
	public $tbl_process_actions     = "process_actions";
	public $tbl_user_offices        = "user_offices";
	public $tbl_param_barangays     = "param_barangays";
	public $tbl_param_municities    = "param_municities";
	public $tbl_param_provinces     = "param_provinces";
	public $tbl_param_regions       = "param_regions";
	public $tbl_notifications       = "notifications";

	public function get_requests_list($aColumns, $bColumns, $params, $date_start = null, $date_end = null)
	{
		try
		{
			$user_id = $this->session->userdata('user_id');
			
			if(isset($params['H-office_id']))
			{
				$val = array(
					$user_id,
					REQUEST_PDS_RECORD_CHANGES, MODULE_HR_PERSONAL_DATA_SHEET,
					REQUEST_LEAVE_APPLICATION, MODULE_TA_LEAVES,
					REQUEST_CERTIFICATE_EMPLOYMENT, MODULE_HUMAN_RESOURCES,
					REQUEST_SERVICE_RECORD, MODULE_HUMAN_RESOURCES,
					REQUEST_CERTIFICATE_CONTRIBUTION, MODULE_PAYROLL,
					REQUEST_MANUAL_ADJUSTMENT, MODULE_TA_DAILY_TIME_RECORD,
					REQUEST_DEDUCTION_RECORD_CHANGES, MODULE_TA_DAILY_TIME_RECORD,
					REQUEST_PAYSLIP,MODULE_PAYROLL
				);
			}
			else
			{
				$val 	= array();				
			}

			if(isset($params["date_requested"]))
				$params["DATE_FORMAT(A-date_requested,'%M %d, %Y')"] = $params["date_requested"];

			if(isset($params["full_name"]))
				$params["CONCAT(B-first_name,' ',B-last_name)"] = $params["full_name"];

			
			/* For Advanced Filters */
			$cColumns = array("A-request_code", "CONCAT(B-first_name,' ',B-last_name)", "B-agency_employee_id", "C-request_type_name", "DATE_FORMAT(A-date_requested,'%M %d, %Y')", "D-request_status_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			if(isset($params['H-office_id']))
			{
				$sWhere = $this->filtering($cColumns, $params, TRUE);
			}
			else
			{
				$sWhere = $this->filtering($cColumns, $params, FALSE);				
			}
			
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			if(isset($params['H-office_id']))
			{
				$add_where     = '';
				if(!EMPTY($params['H-office_id']))
				{
					$office_list = '';
					$office_list = $this->get_office_child($office_list, $params['H-office_id']);
					$add_where   =  ' AND H.office_id IN (' . implode(',', $office_list) . ')';
				}
			}
			
			$work_experience_office_id = 'G.employ_office_id';
			
			if(isset($params['H-office_id']))
			{
				$where              = array();
				$where['module_id'] = MODULE_REQUESTS_APPROVALS;
				$employee_office    = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
				if($employee_office['use_admin_office'] > 0)
				{
					$work_experience_office_id = 'G.admin_office_id';
				}
			}

			// marvin : include date range filter : start
			if(!empty($date_start) AND !empty($date_end))
			{
				$date_start = format_date($date_start,'Y-m-d');
				$date_end = format_date($date_end,'Y-m-d');
				$add_date_filter = ' AND A.date_requested BETWEEN "'.$date_start.' 00:00:00" AND "'.$date_end.' 23:59:59"';
			}
			// marvin : include date range filter : end
			
			//======================================================================marvin======================================================================
			//specify office/user scopes
			$user_scopes['human_resources'] 		= isset($_SESSION['user_offices'][9]) ? $_SESSION['user_offices'][9] : '';
			$user_scopes['personal_data_sheets'] 	= isset($_SESSION['user_offices'][10]) ? $_SESSION['user_offices'][10] : '';
			$user_scopes['performance_evaluation'] 	= isset($_SESSION['user_offices'][11]) ? $_SESSION['user_offices'][11] : '';
			$user_scopes['time_and_attendance'] 	= isset($_SESSION['user_offices'][49]) ? $_SESSION['user_offices'][49] : '';
			$user_scopes['attendance_logs'] 		= isset($_SESSION['user_offices'][50]) ? $_SESSION['user_offices'][50] : '';
			$user_scopes['daily_time_record'] 		= isset($_SESSION['user_offices'][51]) ? $_SESSION['user_offices'][51] : '';
			$user_scopes['leaves'] 					= isset($_SESSION['user_offices'][53]) ? $_SESSION['user_offices'][53] : '';
			$user_scopes['payroll'] 				= isset($_SESSION['user_offices'][61]) ? $_SESSION['user_offices'][61] : '';
			$user_scopes['general_payroll'] 		= isset($_SESSION['user_offices'][63]) ? $_SESSION['user_offices'][63] : '';
			$user_scopes['special_payroll'] 		= isset($_SESSION['user_offices'][64]) ? $_SESSION['user_offices'][64] : '';
			$user_scopes['voucher'] 				= isset($_SESSION['user_offices'][65]) ? $_SESSION['user_offices'][65] : '';
			$user_scopes['remittance'] 				= isset($_SESSION['user_offices'][66]) ? $_SESSION['user_offices'][66] : '';
			$user_scopes['compensation'] 			= isset($_SESSION['user_offices'][12]) ? $_SESSION['user_offices'][12] : '';
			$user_scopes['deductions'] 				= isset($_SESSION['user_offices'][13]) ? $_SESSION['user_offices'][13] : '';
			$user_scopes['request_approvals'] 		= isset($_SESSION['user_offices'][125]) ? $_SESSION['user_offices'][125] : '';
			
			if(isset($params['H-office_id']))
			{
				if(empty($filter_str))
				{
					if(isset($params['H-office_id']))
					{
						if(empty($params['H-office_id']))
						{
							$filter_str .= 'AND H.office_id IN('.$user_scopes['request_approvals'].')';						
						}
						else
						{
							$filter_str .= 'AND LOWER(H.office_id) = '.$params['H-office_id'].' AND H.office_id IN('.$user_scopes['request_approvals'].')';
						}
					}
					else
					{
						if(empty($params['L-user_id']))
						{
							$filter_str .= 'AND L.user_id IN('.$user_scopes['request_approvals'].')';
						}
						else
						{
							$filter_str .= 'AND LOWER(L.user_id) = '.$params['L-user_id'].' AND L.user_id IN('.$user_scopes['request_approvals'].')';
						}
					}
				}
				else
				{
					if(isset($params['H-office_id']))
					{
						if(empty($params['H-office_id']))
						{
							$filter_str .= 'AND H.office_id IN('.$user_scopes['request_approvals'].')';						
						}
						else
						{
							$filter_str .= 'AND LOWER(H.office_id) = '.$params['H-office_id'].' AND H.office_id IN('.$user_scopes['request_approvals'].')';
						}
					}
					else
					{
						if(empty($params['L-user_id']))
						{
							$filter_str .= 'AND L.user_id IN('.$user_scopes['request_approvals'].')';						
						}
						else
						{
							$filter_str .= 'AND LOWER(L.user_id) = '.$params['L-user_id'].' AND L.user_id IN('.$user_scopes['request_approvals'].')';
						}
					}
				}
			}
			else
			{
				if(empty($filter_str))
				{
					if(isset($params['H-office_id']))
					{
						if(empty($params['H-office_id']))
						{
							$filter_str .= 'WHERE H.office_id IN('.$user_scopes['request_approvals'].')';						
						}
						else
						{
							$filter_str .= 'WHERE LOWER(H.office_id) = '.$params['H-office_id'].' AND H.office_id IN('.$user_scopes['request_approvals'].')';
						}
					}
					else
					{
						if(empty($params['L-user_id']))
						{
							$filter_str .= 'WHERE L.user_id IN('.$user_scopes['request_approvals'].')';
						}
						else
						{
							$filter_str .= 'WHERE LOWER(L.user_id) = '.$params['L-user_id'].' AND L.user_id IN('.$user_scopes['request_approvals'].')';
						}
					}
				}
				else
				{
					if(isset($params['H-office_id']))
					{
						if(empty($params['H-office_id']))
						{
							$filter_str .= 'AND H.office_id IN('.$user_scopes['request_approvals'].')';						
						}
						else
						{
							$filter_str .= 'AND LOWER(H.office_id) = '.$params['H-office_id'].' AND H.office_id IN('.$user_scopes['request_approvals'].')';
						}
					}
					else
					{
						if(empty($params['L-user_id']))
						{
							$filter_str .= 'AND L.user_id IN('.$user_scopes['request_approvals'].')';						
						}
						else
						{
							$filter_str .= 'AND LOWER(L.user_id) = '.$params['L-user_id'].' AND L.user_id IN('.$user_scopes['request_approvals'].')';
						}
					}
				}
			}
			
			//======================================================================marvin======================================================================
			
			if(isset($params['H-office_id']))
			{
				$query = <<<EOS
					SELECT SQL_CALC_FOUND_ROWS $fields 
					FROM $this->tbl_requests A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					JOIN $this->tbl_param_request_types C ON A.request_type_id = C.request_type_id
					JOIN $this->tbl_param_request_status D ON A.request_status_id = D.request_status_id
					LEFT JOIN $this->tbl_employee_work_experiences G ON B.employee_id = G.employee_id AND G.active_flag ='Y'
					LEFT JOIN $this->tbl_param_offices H ON $work_experience_office_id = H.office_id

					WHERE $work_experience_office_id IN (
							SELECT 
								office_id
							FROM 
								$this->db_core.$this->tbl_user_offices
							WHERE  
								user_id = ?
									AND module_id = (CASE A.request_type_id
									WHEN ? THEN ?
									WHEN ? THEN ?
									WHEN ? THEN ?
									WHEN ? THEN ?
									WHEN ? THEN ?
									WHEN ? THEN ?
									WHEN ? THEN ?
									WHEN ? THEN ?	
								END))
					
					$filter_str
					$add_where
					$add_date_filter
					GROUP BY A.request_id
					$sOrder
					$sLimit
EOS;
				$val = array_merge($val,$filter_params);
				$stmt['res'] = $this->query($query, $val, TRUE);
				
				//filtered_length
				$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
				$stmt['cnt'] = $this->query($query, NULL, FALSE);

				return $stmt;
			}
			else
			{
				$query = <<<EOS
					SELECT SQL_CALC_FOUND_ROWS $fields 
					FROM $this->tbl_requests A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					JOIN $this->tbl_param_request_types C ON A.request_type_id = C.request_type_id
					JOIN $this->tbl_param_request_status D ON A.request_status_id = D.request_status_id
					LEFT JOIN $this->tbl_employee_work_experiences G ON B.employee_id = G.employee_id AND G.active_flag ='Y'
					LEFT JOIN $this->tbl_param_offices H ON $work_experience_office_id = H.office_id
					LEFT JOIN $this->tbl_associated_accounts L ON A.employee_id = L.employee_id
					
					$filter_str
					GROUP BY A.request_id
					$sOrder
					$sLimit
EOS;

				$val = array_merge($val,$filter_params);
				// $stmt = $this->query($query, $val, TRUE);
				$stmt['res'] = $this->query($query, $val, TRUE);
				
				//filtered_length
				$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
				$stmt['cnt'] = $this->query($query, NULL, FALSE);
				
				return $stmt;
			}
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_requests_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	
	public function total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_requests, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	public function get_request_details($id)
	{
		try
		{
			
			$val 	= array($id);
			// $key 	= $this->get_hash_key('A.request_id');
			
			$query = <<<EOS
				SELECT  *,DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_requested, DATE_FORMAT(A.date_processed,'%M %d, %Y') as date_processed
				FROM $this->tbl_requests A
				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
				JOIN $this->tbl_param_request_types C ON A.request_type_id = C.request_type_id
				JOIN $this->tbl_param_request_status D ON A.request_status_id = D.request_status_id
				LEFT JOIN $this->tbl_associated_accounts E ON B.employee_id = E.employee_id
				LEFT JOIN $this->db_core.$this->tbl_users F ON F.user_id = E.user_id
				LEFT JOIN $this->tbl_employee_work_experiences G ON A.employee_id = G.employee_id AND G.active_flag = 'Y'
				WHERE A.request_id = ?

EOS;
	
			return $this->query($query, $val,FALSE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function get_task_list($aColumns, $bColumns, $params)
	{
		try
		{
			$request_id 	= $params['request_id'];
			$val 			= array($request_id);
			// $key 			= $this->get_hash_key('A.request_id');

			if(isset($params["full_name"]))
				$params["CONCAT(C-fname,' ',C-lname)"] = $params["full_name"];

			if(isset($params["assigned_date"]))
				$params["DATE_FORMAT(A-assigned_date,'%Y/%m/%d %l:%i %p')"] = $params["assigned_date"];

			if(isset($params["processed_date"]))
				$params["DATE_FORMAT(A-processed_date,'%Y/%m/%d %l:%i %p')"] = $params["processed_date"];
			/* For Advanced Filters */
			$cColumns = array("A-task_detail", "CONCAT(C-fname,' ',C-lname)", "DATE_FORMAT(A-assigned_date,'%Y/%m/%d %l:%i %p')", "DATE_FORMAT(A-processed_date,'%Y/%m/%d %l:%i %p')", "B-task_status_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
// 			$query = <<<EOS
// 				SELECT  SQL_CALC_FOUND_ROWS $fields 
// 				FROM $this->tbl_requests_tasks A
// 				JOIN $this->tbl_param_task_status B ON A.task_status_id = B.task_status_id
// 				LEFT JOIN $this->db_core.$this->tbl_users C ON C.user_id = A.assigned_to
// 				WHERE $key = ?

// 				$filter_str
// 	        	$sOrder
// 	        	$sLimit
// EOS;
// davcorrea : START : Optimized Query speed
			$query = <<<EOS
				SELECT  SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_requests_tasks A
				LEFT JOIN $this->db_core.$this->tbl_users C ON C.user_id = A.assigned_to
				WHERE A.request_id = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
// davcorrea : END

	
			return $this->query($query, $val,TRUE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function task_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_task_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	
	public function task_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_requests, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	public function get_task_roles($process_id,$process_stage_id)
	{
		try
		{
			
			$val 	= array($process_id,$process_stage_id);
			
			$query = <<<EOS
				SELECT  role_code
				FROM $this->db_core.$this->tbl_process_stage_roles 
				WHERE 
				process_id = ?
				AND process_stage_id = ?

EOS;
	
			return $this->query($query, $val,TRUE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function get_sub_request_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id  = $params['request_id'];
			$val = array($id);
			$key = $this->get_hash_key('A.request_id');

			$cColumns = array('C-request_sub_type_name', 'D-name', 'E-requests_sub_status_name');
			
			$fields   = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere   = $this->filtering($cColumns, $params, TRUE);
			$sOrder   = $this->ordering($bColumns, $params);
			$sLimit   = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
// 			$query = <<<EOS
// 				SELECT SQL_CALC_FOUND_ROWS $fields 
// 				FROM $this->tbl_requests A
// 				JOIN $this->tbl_requests_sub B ON A.request_id = B.request_id
// 				JOIN $this->tbl_param_request_sub_types C ON B.request_sub_type_id = C.request_sub_type_id
// 				JOIN $this->db_core.$this->tbl_actions D ON B.action = D.action_id
// 				JOIN $this->tbl_param_request_sub_status E ON E.request_sub_status_id = B.request_sub_status_id
// 				WHERE $key = ?
// 				$filter_str
// 	        	$sOrder
// 	        	$sLimit
// EOS;

// davcorrea : START : changed query to optimize speed
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_requests A
				JOIN $this->tbl_requests_sub B ON A.request_id = B.request_id
				LEFT JOIN $this->tbl_param_request_sub_types C ON B.request_sub_type_id = C.request_sub_type_id
				JOIN $this->db_core.$this->tbl_actions D ON B.action = D.action_id
				JOIN $this->tbl_param_request_sub_status E ON E.request_sub_status_id = B.request_sub_status_id
				WHERE A.request_id = ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
// davcorrea : end 
			$val = array_merge($val,$filter_params);	
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function sub_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_sub_request_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	
	public function sub_total_length($id)
	{
		try
		{
			$where       = array();
			
			$fields      = array("COUNT(*) cnt");
			$key         = $this->get_hash_key('request_id');
			$where[$key] = $id;
			return $this->select_one($fields, $this->tbl_requests_sub, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	public function get_supporting_documents_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id  = $params['request_id'];
			$val = array($id);
			$key = $this->get_hash_key('A.request_id');

			$cColumns = array('A-date_received','B-supp_doc_type_name','A-remarks');
			
			$fields   = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere   = $this->filtering($cColumns, $params, TRUE);
			$sOrder   = $this->ordering($bColumns, $params);
			$sLimit   = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_supporting_docs A
				JOIN $this->tbl_param_supporting_document_types B ON A.supp_doc_type_id= B.supp_doc_type_id
				WHERE $key = ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);		
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function sup_doc_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_supporting_documents_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	
	public function sup_doc_total_length($id)
	{
		try
		{
			$where       = array();
			
			$fields      = array("COUNT(*) cnt");
			$key         = $this->get_hash_key('request_id');
			$where[$key] = $id;
			return $this->select_one($fields, $this->tbl_requests_sub, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	public function get_initial_task($process_id)
	{
		try
		{
			
			$val = array($process_id);
			
			$query = <<<EOS
				select A.name,B.process_id,B.process_stage_id,B.process_step_id
				from $this->db_core.$this->tbl_process_stages A 
				JOIN $this->db_core.$this->tbl_process_steps B ON A.process_id = B.process_id
				WHERE A.process_id = ?
EOS;
	
			return $this->query($query, $val,FALSE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function view_task_details($task_id)
	{
		try
		{
			
			$val 	= array($task_id);
			// $key 	= $this->get_hash_key('G.request_task_id');
			
// 			$query = <<<EOS
// 				SELECT  *,DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_requested, DATE_FORMAT(A.date_processed,'%M %d, %Y') as date_processed
// 				FROM $this->tbl_requests A
// 				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
// 				JOIN $this->tbl_param_request_types C ON A.request_type_id = C.request_type_id
// 				JOIN $this->tbl_param_request_status D ON A.request_status_id = D.request_status_id
// 				LEFT JOIN $this->tbl_associated_accounts E ON E.employee_id = B.employee_id
// 				LEFT JOIN $this->db_core.$this->tbl_users F ON F.user_id = E.user_id
// 				JOIN $this->tbl_requests_tasks G ON G.request_id = A.request_id
// 				WHERE $key = ?

// EOS;
			// marvin : change to left join : start
			$query = <<<EOS
				SELECT  *,DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_requested, DATE_FORMAT(A.date_processed,'%M %d, %Y') as date_processed
				FROM $this->tbl_requests A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
				LEFT JOIN $this->tbl_param_request_types C ON A.request_type_id = C.request_type_id
				LEFT JOIN $this->tbl_param_request_status D ON A.request_status_id = D.request_status_id
				LEFT JOIN $this->tbl_associated_accounts E ON E.employee_id = B.employee_id
				LEFT JOIN $this->db_core.$this->tbl_users F ON F.user_id = E.user_id
				LEFT JOIN $this->tbl_requests_tasks G ON G.request_id = A.request_id
				WHERE G.request_task_id = ?

EOS;
			// marvin : change to left join : end
	
			return $this->query($query, $val,FALSE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function get_task_actions($task_id)
	{
		try
		{
			
			$val 	= array($task_id);

			// $key 	= $this->get_hash_key('A.request_task_id');
			
			$query 	= <<<EOS
				select B.process_action_id,B.name
				from $this->tbl_requests_tasks A 
				JOIN $this->db_core.$this->tbl_process_actions B ON A.process_id = B.process_id AND A.process_step_id = B.process_step_id
				WHERE A.request_task_id = ?

EOS;
	
			return $this->query($query, $val,TRUE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function get_next_task($process_id, $process_step_id)
	{
		try
		{
			
			$val = array($process_id, $process_step_id);
			
			$query = <<<EOS
				select A.name,B.process_id,B.process_stage_id,B.process_step_id 
				from $this->db_core.$this->tbl_process_stages A 
				JOIN $this->db_core.$this->tbl_process_steps B ON A.process_stage_id = B.process_stage_id AND A.process_id = B.process_id
				WHERE A.process_id = ? AND B.process_step_id = ?
EOS;
	
			return $this->query($query, $val,FALSE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function get_request_notif_info($request_id)
	{
		try
		{
			
			$val = array($request_id);
			
			$query = <<<EOS
				
				SELECT 
					A.request_type_id,
				    G.request_type_name,
				    F.user_id,
				    D.employ_office_id,
				    GROUP_CONCAT(DISTINCT C.role_code) AS notify_roles
				FROM
				    $this->tbl_requests A
				        JOIN
				    $this->tbl_requests_tasks B ON A.request_id = B.request_id
				        AND B.assigned_to IS NULL
				        JOIN
				    $this->db_core.$this->tbl_process_stage_roles C ON B.process_id = C.process_id
				        AND B.process_stage_id = C.process_stage_id
				        LEFT JOIN
				    $this->tbl_employee_work_experiences D ON A.employee_id = D.employee_id
				        AND D.active_flag = 'Y'
				        JOIN
				    $this->tbl_associated_accounts F ON A.employee_id = F.employee_id
				        JOIN
				    $this->tbl_param_request_types G ON A.request_type_id = G.request_type_id
				WHERE A.request_id = ?
				GROUP BY A.request_id,
					A.request_type_id,
				    G.request_type_name,
				    F.user_id,
				    D.employ_office_id
EOS;
	
			return $this->query($query, $val,FALSE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}

	public function get_task_notif_info($request_task_id)
	{
		try
		{
			
			$val = array($request_task_id);
			$key = $this->get_hash_key('B.request_task_id');

			$query = <<<EOS
				
				SELECT 
				    A.request_id,A.request_type_id, D.request_type_name, C.user_id,B.assigned_to
				FROM
				    $this->tbl_requests A
				        JOIN
				    $this->tbl_requests_tasks B ON A.request_id = B.request_id
				        JOIN
				    $this->tbl_associated_accounts C ON A.employee_id = C.employee_id
				        JOIN
				    $this->tbl_param_request_types D ON A.request_type_id = D.request_type_id
				WHERE $key = ?
EOS;

	

			return $this->query($query, $val,FALSE);
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	public function get_leave_last_trans_date($employee_id)
	{
		try
		{
			$val = array($employee_id,$employee_id);
			
			$query = <<<EOS
				SELECT MAX(if(leave_end_date = '' OR leave_end_date IS NULL,leave_transaction_date,leave_end_date)) as last_trans_date 
				FROM $this->tbl_employee_leave_details
				WHERE employee_id = ? 
				AND leave_transaction_date = (
					SELECT max(leave_transaction_date) 
					FROM $this->tbl_employee_leave_details where employee_id = ?
				)

EOS;

			$stmt = $this->query($query, $val, false);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	public function get_general_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group = array(), $limit = NULL)
	{
		try{
	
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group, $limit);
			
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group, $limit);
			}
		}
		catch (PDOException $e){
			throw $e;
		}
	}
	public function insert_general_data($table, $params, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $params, $return_id);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function update_general_data($tables, $params, $where)
	{
		try
		{
			return $this->update_data($tables, $params, $where);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function delete_general_data($table,$where)
	{
		try
		{
			return $this->delete_data($table, $where);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	
	// MARVIN
	public function get_ta_requests($request_sub_id)
	{
		try
		{
			$val = array($request_sub_id);
			
			$query = <<<EOS
				SELECT attendance_date, time_log, remarks
				FROM requests_employee_attendance
				WHERE request_sub_id = ?
EOS;
			$stmt = $this->query($query, $val, false);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	
	// davcorrea : START : validate if user has pending leave request on that day
	public function validate_requests_leaves($id)
	{
		try
		{
			$val = array($id,"N");
			
			$query = <<<EOS
				SELECT *
				FROM requests_leaves
				WHERE request_sub_id = ?
				AND commutation_flag = ?
EOS;
			$stmt = $this->query($query, $val, false);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}

	public function validate_attendance_dates($id,$datefrom,$dateto)
	{
		try
		{
			// $key = $this->get_hash_key('employee_id');
			$val = array($id,$datefrom,$dateto);
			
			$query = <<<EOS
				SELECT *
				FROM employee_attendance
				WHERE employee_id = ?
				AND attendance_date BETWEEN ? AND ?
EOS;
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	// END
	
	// public function validate_ta_raw($ta_results)
	// {
		// try
		// {
			// $table 					= 'employee_attendance';
			// $params = array(
				// 'time_log' 			=> NULL,
				// 'edited_flag' 		=> 'Y'
			// );
			
			// $where = array(
				// 'attendance_date' 	=> $ta_results['attendance_date'],
				// 'time_log' 			=> $ta_results['time_log']
			// );
			
			// return $this->update_data($table, $params, $where);
	
		// }
		// catch(PDOException $e)
		// {
			// throw $e;
		// }
	// }
}