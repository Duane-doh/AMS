<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_time_record_model extends Main_Model {
     
	public $db_core              = DB_CORE;
	public $tbl_organizations    = "organizations";
	public function get_employee_list($aColumns, $bColumns, $params,$module_id)
	{
		try
		{
			$val = array();
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))
			$params["CONCAT(A-last_name,', ', A-first_name, ' ',A-last_name)"] = $params['fullname'];

			$cColumns      = array("A-agency_employee_id", "CONCAT(A-last_name,', ', A-first_name, ' ',A-last_name)", "E-name", "D-employment_status_name");
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$work_experienxe_office_id = 'B.employ_office_id';
			if(!EMPTY($module_id))
			{
				$where              = array();
				$where['module_id'] = $module_id;
				$employee_office    = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
				if($employee_office['use_admin_office'] > 0)
				{
					$work_experienxe_office_id = 'B.admin_office_id';
				}
			}
			// davcorrea : include date range fitler : start
			$date_start = $params['date_start'];
			$date_end = $params['date_end'];
			$status_filter = $params['status_filter'];

				if($status_filter == "Y")
				{
						
						$date_start = format_date($date_start,'Y-m-d');
						$date_end = format_date($date_end,'Y-m-d');
						// AND A.employee_id IN (SELECT employee_id FROM attendance_period_dtl WHERE attendance_status_id = '1' AND attendance_date BETWEEN '2023-01-01' AND '2023-11-23'
						$add_date_filter = 'AND B.employee_id IN (SELECT DISTINCTROW employee_id FROM employee_work_experiences WHERE (employ_start_date <= "'.$date_end.'" AND employ_start_date >= "'.$date_start.'") OR (employ_end_date >= "'.$date_start.'" AND employ_end_date <= "'.$date_end.'") OR (employ_start_date <= "'.$date_start.'" AND employ_start_date<= "'.$date_end.'" AND employ_end_date >= "'.$date_start.'" AND employ_end_date >= "'.$date_end.'") OR (employ_start_date <= "'.$date_start.'" AND employ_start_date <= "'.$date_end.'" AND employ_end_date IS NULL))
						
						AND B.employee_work_experience_id IN (SELECT DISTINCTROW employee_work_experience_id FROM employee_work_experiences WHERE (employ_start_date <= "'.$date_end.'" AND employ_start_date >= "'.$date_start.'") OR (employ_end_date >= "'.$date_start.'" AND employ_end_date <= "'.$date_end.'") OR (employ_start_date <= "'.$date_start.'" AND employ_start_date<= "'.$date_end.'" AND employ_end_date >= "'.$date_start.'" AND employ_end_date >= "'.$date_end.'") OR (employ_start_date <= "'.$date_start.'" AND employ_start_date <= "'.$date_end.'" AND employ_end_date IS NULL))
						';
						
				}
				
				if($status_filter == "N")
				{
					
						$date_start = format_date($date_start,'Y-m-d');
						$date_end = format_date($date_end,'Y-m-d');
						// AND A.employee_id IN (SELECT employee_id FROM attendance_period_dtl WHERE attendance_status_id = '1' AND attendance_date BETWEEN '2023-01-01' AND '2023-11-23'
						$add_date_filter = ' AND B.employee_id IN (SELECT DISTINCTROW employee_id FROM employee_work_experiences WHERE (employ_start_date > "'.$date_start.'" AND employ_start_date > "'.$date_end.'" AND employ_end_date > "'.$date_start.'" AND employ_end_date > "'.$date_end.'") OR (employ_start_date < "'.$date_start.'" AND employ_start_date < "'.$date_end.'" AND employ_end_date < "'.$date_start.'" AND employ_end_date < "'.$date_end.'") OR (employ_start_date > "'.$date_start.'" AND employ_start_date > "'.$date_end.'" AND employ_end_date IS NULL))
						
						AND B.employee_work_experience_id IN (SELECT DISTINCTROW employee_work_experience_id FROM employee_work_experiences WHERE (employ_start_date > "'.$date_start.'" AND employ_start_date > "'.$date_end.'" AND employ_end_date > "'.$date_start.'" AND employ_end_date > "'.$date_end.'") OR (employ_start_date < "'.$date_start.'" AND employ_start_date < "'.$date_end.'" AND employ_end_date < "'.$date_start.'" AND employ_end_date < "'.$date_end.'") OR (employ_start_date > "'.$date_start.'" AND employ_start_date > "'.$date_end.'" AND employ_end_date IS NULL))
						';
				

				}
			
			// davcorrea : include date range fitler : end

			//=============================================================marvin=============================================================
			//added user_offices_scope
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
			
			if(empty($filter_str))
			{
				if(isset($params['C-office_id']))
				{
					if(empty($params['C-office_id']))
					{
						$filter_str .= 'WHERE C.office_id IN('.$user_scopes['daily_time_record'].')';						
					}
					else
					{
						$filter_str .= 'WHERE LOWER(C.office_id) = '.$params['C-office_id'].' AND C.office_id IN('.$user_scopes['daily_time_record'].')';
					}
				}
				else
				{
					if(empty($params['F-user_id']))
					{
						$filter_str .= 'WHERE F.user_id IN('.$user_scopes['daily_time_record'].')';
					}
					else
					{
						$filter_str .= 'WHERE LOWER(F.user_id) = '.$params['F-user_id'].' AND F.user_id IN('.$user_scopes['daily_time_record'].')';
					}
				}
			}
			else
			{
				if(isset($params['C-office_id']))
				{
					if(empty($params['C-office_id']))
					{
						$filter_str .= 'AND C.office_id IN('.$user_scopes['daily_time_record'].')';						
					}
					else
					{
						$filter_str .= 'AND LOWER(C.office_id) = '.$params['C-office_id'].' AND C.office_id IN('.$user_scopes['daily_time_record'].')';
					}
				}
				else
				{
					if(empty($params['F-user_id']))
					{
						$filter_str .= 'AND F.user_id IN('.$user_scopes['daily_time_record'].')';						
					}
					else
					{
						$filter_str .= 'AND LOWER(F.user_id) = '.$params['F-user_id'].' AND F.user_id IN('.$user_scopes['daily_time_record'].')';
					}
				}
			}
			//=============================================================marvin=============================================================
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS DISTINCTROW A.employee_id,$fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employee_work_experience_id = (SELECT MAX(employee_work_experience_id) FROM employee_work_experiences WHERE employee_id = B.employee_id AND employ_type_flag IN ('JO','AP','WP'))
				LEFT JOIN $this->tbl_param_offices C ON C.office_id = $work_experienxe_office_id
				LEFT JOIN $this->tbl_param_employment_status D ON B.employment_status_id = D.employment_status_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code
				/*change office to user filter*/
				LEFT JOIN $this->tbl_associated_accounts F ON A.employee_id = F.employee_id


				$filter_str
				$add_date_filter
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			RLog::info($query);
			RLog::info($val);
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

	

	public function filtered_length($aColumns, $bColumns, $params,$module_id)
	{
		try
		{
			$this->get_employee_list($aColumns, $bColumns, $params,$module_id);
			
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
			return $this->select_one($fields, $this->tbl_employee_personal_info, $where);
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
	public function get_attendance_list($aColumns, $bColumns, $params)
	{
		try
		{
			$user_pds_id  = $params['employee_id'];
			$key 	= $this->get_hash_key('A.employee_id');
			$val = array($user_pds_id );
			
			/* For Advanced Filters */
			$cColumns = array("DATE_FORMAT(B-attendance_date,'%M %d, %Y')","DATE_FORMAT(B-attendance_date,'%W')", "min(TIME_FORMAT(B-time_in,'%h:%i %p'))", "max(TIME_FORMAT(B-time_out,'%h:%i %p'))", "min(TIME_FORMAT(B-break_in,'%h:%i %p'))", "max(TIME_FORMAT(B-break_out,'%h:%i %p'))");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_attendance B ON A.employee_id = B.employee_id
				WHERE $key  = ?
				$filter_str
				group by B.employee_id, B.attendance_date
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			RLog::info($query);
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
	public function attendance_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_attendance_list($aColumns, $bColumns, $params);
			
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
	public function attendance_total_length($employee_id)
	{
		try
		{

			$key         = $this->get_hash_key('A.employee_id');
			$val         = array($employee_id );
			

			$query = <<<EOS
				SELECT COUNT(*) cnt
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_attendance B ON A.employee_id = B.employee_id
				WHERE $key  = ?
				group by B.employee_id, B.attendance_date
				
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
	public function get_attendance_breakdown_list($aColumns, $bColumns, $params)
	{
		try
		{
			$employee_attendance_id  = $params['employee_attendance_id'];
			$key 	= $this->get_hash_key('A.employee_attendance_id');
			$val = array($employee_attendance_id );
			
			/* For Advanced Filters */
			$cColumns = array("TIME_FORMAT(B-time_in,'%r')", "TIME_FORMAT(B-time_out,'%r')", "TIME_FORMAT(B-break_in,'%r')", "TIME_FORMAT(B-break_out,'%r')");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_employee_attendance A
				JOIN $this->tbl_employee_attendance B ON A.attendance_date = B.attendance_date AND A.employee_id = B.employee_id
				WHERE $key  = ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			RLog::info($query);
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
	public function attendance_breakdown_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_attendance_breakdown_list($aColumns, $bColumns, $params);
			
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
	public function attendance_breakdown_total_length($employee_attendance_id)
	{
		try
		{

			$key         = $this->get_hash_key('A.employee_attendance_id');
			$val         = array($employee_attendance_id );
			$query = <<<EOS
				SELECT COUNT(*) cnt
				FROM
				$this->tbl_employee_attendance A
				JOIN $this->tbl_employee_attendance B ON A.attendance_date = B.attendance_date AND A.employee_id = B.employee_id
				WHERE $key  = ?
				
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
	
	public function get_attendance_time_logs($employee_id,$date_start,$date_end)
	{
		try
		{

			// $key         = $this->get_hash_key('employee_id');
			$val         = array($employee_id,$employee_id,$date_start,$date_end);
			$query = <<<EOS

				SELECT 
				    employee_id,
				    attendance_date,
					attendance_status_id,
				    MAX(IF(time_flag='TI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_in,
					MAX(IF(time_flag='BO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_out,
					MAX(IF(time_flag='BI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_in,
					MAX(IF(time_flag='TO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_out,
					MAX(IF(time_flag='TI', employee_attendance_id, 0)) time_in_id,
					MAX(IF(time_flag='BO', employee_attendance_id, 0)) break_out_id,
					MAX(IF(time_flag='BI', employee_attendance_id, 0)) break_in_id,
					MAX(IF(time_flag='TO', employee_attendance_id, 0)) time_out_id,
					(IF (attendance_date NOT IN (SELECT 
				            attendance_date
				        FROM
				            $this->tbl_attendance_period_dtl
				        WHERE
						employee_id = ? 
				            AND attendance_period_hdr_id IS NOT NULL),
					0,
					1)) attendance_period_flag,
					GROUP_CONCAT(DISTINCT remarks) remarks
				FROM
				    $this->tbl_employee_attendance
				WHERE
				employee_id = ?
				        AND attendance_date BETWEEN ? AND ?
				GROUP BY employee_id,attendance_date
				ORDER BY attendance_date ASc
				
EOS;
			$stmt = $this->query($query, $val, true);
						
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

	public function get_employee_work_schedule($id,$date)
	{
		try
		{
			$val = array($id,$date,$date,$date);

			$query = <<<EOS
				SELECT 
					B.*
				FROM
				$this->tbl_employee_work_schedules A
				JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
				WHERE A.employee_id = ?
				AND ? BETWEEN A.start_date AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
				 
				
EOS;
			
			$stmt = $this->query($query, $val, FALSE);


						
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}	
	}

	public function get_employee_type($id,$date)
	{
		try
		{
			$val = array($id,$date,$date,$date);

			$query = <<<EOS
				SELECT 
					employ_type_flag
				FROM
				employee_work_experiences WHERE employee_id = ? 
				AND ? BETWEEN employ_start_date AND  (IFNULL(employ_end_date,IF( ? > NOW(), ? ,NOW())))
				 
				
EOS;
			
			$stmt = $this->query($query, $val, FALSE);


						
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}	
	}
	//Added function to check if employee has multiple work schedule - davcorrea
	//============START==============
	public function if_employee_has_multiple_work_schedule($id,$datefrom,$dateto)
	{
		try
		{
			$val = array($id,$datefrom,$dateto,$dateto);


			$query = <<<EOS
			SELECT 
			COUNT(*) as cnt
			FROM
			$this->tbl_employee_work_schedules A
			JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
			WHERE A.employee_id = ?
			AND A.start_date BETWEEN ? AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
				 
				
EOS;
			$stmt = $this->query($query, $val, false);
			if($stmt['cnt'] <= 1)
			{
				$stmt = false;
			}
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}	
	}
	//=======================END=========================
	//Added function get rest days from work schedule
	// davcorrea: 09/27/2023 ============START==========================
	public function get_employee_rest_days($id,$datefrom,$dateto)
	{
		try
		{	
			$restDays = "21";
			$val = array($id,$restDays,$datefrom,$dateto,$dateto,$datefrom,$dateto,$dateto);


			$query = <<<EOS
			SELECT 
			A.*
			FROM
			$this->tbl_employee_work_schedules A
			JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
			WHERE A.employee_id = ?
			AND A.work_schedule_id = ?
			AND (A.start_date BETWEEN ? AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
			OR A.end_date BETWEEN ? AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW()))))
				 
				
EOS;
			$stmt = $this->query($query, $val, true);

			if(EMPTY($stmt))
			{
				$stmt = false;
			}
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}	
	}

	// ============================== END =================================
	public function get_affected_attendance_time_logs($employee_id,$affected_dates = null,$affected_ids = null)
	{
		try
		{

			$val         = array();
			$val[] = $employee_id;
			$where = '';
			$where_dates = '';
			$where_ids = '';
			
			if($affected_dates)
			{
				$new_dates = array_unique($affected_dates);
				$where_dates .= ' attendance_date IN (';
				foreach ($new_dates as $key => $value) {

					if(count($new_dates) == $key+1)
					{
						$where_dates .= ' ? ';
						$val[] = $value;
					}
					else
					{
						$where_dates .= ' ? ,';
						$val[] = $value;
					}
					
					
				}
				$where_dates .= ' )';
			}

			if($affected_ids)
			{
				$new_ids = array_unique($affected_ids);
				$where_ids .= ' employee_attendance_id IN (';
				foreach ($new_ids as $key => $value) {
					
					if(count($new_ids) == $key+1)
					{
						$where_ids .= ' ? ';
						$val[] = $value;
					}
					else
					{
						$where_ids .= ' ? ,';
						$val[] = $value;
					}
					
					
				}
				$where_ids .= ' )';
			}
			if(!EMPTY($where_dates) AND !EMPTY($where_ids))
			{
				$where = 'AND (';
				$where .= $where_dates;
				$where .= ' OR ';
				$where .= $where_ids;
				$where .= ')';
			}
			elseif(!EMPTY($where_dates))
			{
				$where = 'AND (';
				$where .= $where_dates;
				$where .= ')';
			}
			elseif(!EMPTY($where_ids))
			{
				$where = 'AND (';
				$where .= $where_ids;
				$where .= ')';
			}
			$query = <<<EOS

				SELECT 
				    employee_id,
				    attendance_date,
				    remarks,
				    MAX(IF(time_flag='TI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_in,
					MAX(IF(time_flag='BO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_out,
					MAX(IF(time_flag='BI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_in,
					MAX(IF(time_flag='TO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_out
				FROM
				    $this->tbl_employee_attendance
				WHERE
				   		employee_id = ?
				   		$where
				        
				GROUP BY attendance_date,remarks
				ORDER BY attendance_date DESC
				
EOS;

			$stmt = $this->query($query, $val, true);
						
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
	
	public function get_specific_time_log_dtl($employee_attendance_id)
	{
		try
		{

			$val         = array($employee_attendance_id);
			$query = <<<EOS

				SELECT 
				    *
				FROM
				    $this->tbl_employee_attendance
				WHERE
				   	employee_attendance_id = ?
				
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
	
	/*====================== MARVIN : START : GET TIME LOGS FROM BIOMETRICS AND NOT EDITED ====================*/
	public function custom_get_attendance_time_logs($employee_id,$date_start,$date_end)
	{
		try
		{

			$key         = $this->get_hash_key('employee_id');
			$val         = array($employee_id,$employee_id,$date_start,$date_end);
			$query = <<<EOS

				SELECT 
				    employee_id,
				    attendance_date,
				    MAX(IF(time_flag='TI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_in,
					MAX(IF(time_flag='BO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_out,
					MAX(IF(time_flag='BI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_in,
					MAX(IF(time_flag='TO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_out,
					MAX(IF(time_flag='TI', employee_attendance_id, 0)) time_in_id,
					MAX(IF(time_flag='BO', employee_attendance_id, 0)) break_out_id,
					MAX(IF(time_flag='BI', employee_attendance_id, 0)) break_in_id,
					MAX(IF(time_flag='TO', employee_attendance_id, 0)) time_out_id,
					(IF (attendance_date NOT IN (SELECT 
				            attendance_date
				        FROM
				            $this->tbl_attendance_period_dtl
				        WHERE
				            $key = ? 
				            AND attendance_period_hdr_id IS NOT NULL),
					0,
					1)) attendance_period_flag,
					GROUP_CONCAT(DISTINCT remarks) remarks
				FROM
				    $this->tbl_employee_attendance
				WHERE
				   		$key = ?
				        AND attendance_date BETWEEN ? AND ?
						AND source_flag = 'B'
						AND edited_flag = 'N'
				GROUP BY employee_id,attendance_date
				ORDER BY attendance_date ASc
				
EOS;

			$stmt = $this->query($query, $val, true);
						
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
	/*====================== MARVIN : END : GET TIME LOGS FROM BIOMETRICS AND NOT EDITED ====================*/
}