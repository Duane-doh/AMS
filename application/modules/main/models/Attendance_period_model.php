<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_period_model extends Main_Model {
     
	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";
	public $tbl_sys_param     = "sys_param";

	public function get_attendance_period_list($aColumns, $bColumns, $params)
	{
		try
		{
			
			$val = array();
			
			/* For Advanced Filters */
			if(!EMPTY($params['period_from']))
			$params["DATE_FORMAT(A-date_from,'%M %d, %Y')"] = $params['period_from'];

			if(!EMPTY($params['period_to']))
			$params["DATE_FORMAT(A-date_to,'%M %d, %Y')"] = $params['period_to'];


			// $cColumns      = array("B-payroll_type_name","DATE_FORMAT(A-date_from,'%M %d, %Y')","DATE_FORMAT(A-date_to,'%M %d, %Y')","C-period_status_name","A-remarks");
			// marvin : include remarks for batching : start
			$cColumns      = array("B-payroll_type_name","DATE_FORMAT(A-date_from,'%M %d, %Y')","DATE_FORMAT(A-date_to,'%M %d, %Y')","C-period_status_name","A-remarks");
			// marvin : include remarks for batching : end
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM 
				$this->tbl_attendance_period_hdr A
				JOIN $this->tbl_param_payroll_types B ON A.payroll_type_id = B.payroll_type_id
				JOIN $this->tbl_param_attendance_period_status C ON C.period_status_id = A.period_status_id

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
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

	

	public function attendance_period_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_attendance_period_list($aColumns, $bColumns, $params);
			
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
	public function get_attendance_date($employee_id, $date)
	{
		try
		{
			$val = array($employee_id, $date);
			$query = <<<EOS
				SELECT 
				A.employee_id,A.attendance_date,
				min(A.time_in) as time_in,
				max(A.break_out) as break_out,
				min(A.break_in) as break_in,
				max(A.time_out) as time_out,
				time_to_sec(ADDTIME(TIMEDIFF(A.break_out,A.time_in),TIMEDIFF(A.time_out,A.break_in)))/ (60 * 60) as total_time,
				B.*
				from employee_attendance A
				LEFT JOIN employee_leave_details B ON A.employee_id = B.employee_id AND leave_transaction_type_id > 3 AND A.attendance_date BETWEEN B.leave_start_date AND B.leave_end_date
				where A.employee_id = ?
				AND A.attendance_date = ?

				GROUP BY A.attendance_date
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
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
	
	
	public function attendance_period_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_attendance_period_hdr, $where);
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
	public function get_attendance_employee_list($aColumns, $bColumns, $params)
	{
		try
		{
			$attendance_period_hdr_ids	   = $this->get_general_data(('attendance_period_hdr_id'), $this->tbl_attendance_period_hdr);
			foreach($attendance_period_hdr_ids as $attendance_period_hdr_id)
			{
				$prep_string = "%$".$attendance_period_hdr_id['attendance_period_hdr_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $params['attendance_period_hdr_id'])
				{
					$params['attendance_period_hdr_id'] = $attendance_period_hdr_id['attendance_period_hdr_id'];
					break;
				}
			}
			$val = array($params['attendance_period_hdr_id']);
			
			$cColumns      = array("E-agency_employee_id", "E-fullname","E-name", "E-employment_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$add_where     = '';
			if(!EMPTY($params['E-office_id'])) {
				$office_list = '';
				$office_list = $this->get_office_child($office_list, $params['E-office_id']);
				$add_where   = ' AND E.office_id IN (' . implode(',', $office_list) . ')';
			}
			
			//marvin
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
			
			if(empty($filter_str) OR empty($add_where))
			{
				$add_where .= 'AND E.office_id IN('.$user_scopes['attendance_logs'].')';
			}
			//end

// 			$query = <<<EOS
				
// 				SELECT 
// 					SQL_CALC_FOUND_ROWS *
// 				FROM
// 					(SELECT 
// 						$fields
// 						FROM $this->tbl_employee_personal_info A				
// 						JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id and B.active_flag = 'Y'
// 						JOIN $this->tbl_param_employment_status C ON B.employment_status_id = C.employment_status_id
// 						JOIN $this->tbl_param_offices D ON B.employ_office_id = D.office_id
// 						JOIN $this->db_core.$this->tbl_organizations E ON D.org_code = E.org_code 
// 						group by A.employee_id 
// 					) E
//                 WHERE E.employee_id IN 
// 					(SELECT employee_id FROM attendance_period_dtl WHERE md5(CONCAT('%$', attendance_period_hdr_id, '%$')) = ? )

// 				$filter_str
// 				$add_where
// 	        	$sOrder
// 	        	$sLimit
// EOS;

			// marvin : change to left join : start
			$query = <<<EOS
			
			SELECT 
			SQL_CALC_FOUND_ROWS *
			FROM
			(SELECT 
			$fields
			FROM $this->tbl_employee_personal_info A				
			LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id and B.active_flag = 'Y'
			LEFT JOIN $this->tbl_param_employment_status C ON B.employment_status_id = C.employment_status_id
			LEFT JOIN $this->tbl_param_offices D ON B.employ_office_id = D.office_id
			LEFT JOIN $this->db_core.$this->tbl_organizations E ON D.org_code = E.org_code 
			group by A.employee_id 
			) E
			WHERE E.employee_id IN 
			(SELECT employee_id FROM attendance_period_dtl WHERE attendance_period_hdr_id = ? )
			
			$filter_str
			$add_where
			$sOrder
			$sLimit
EOS;
			// marvin : change to left join : end

			$val = array_merge($val,$filter_params);

			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw $e;
			$this->rlog_error($e);
			
		}
		catch (Exception $e)
		{			
			throw $e;
			$this->rlog_error($e);
		}
	}
	public function employee_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_attendance_employee_list($aColumns, $bColumns, $params);
			
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
	
	
	public function employee_total_length($attendance_period_hdr_id)
	{
		try
		{
			$val = array($attendance_period_hdr_id);
			$key = $this->get_hash_key('attendance_period_hdr_id');
			$query = <<<EOS
				SELECT count(DISTINCT employee_id) as cnt
				FROM
				$this->tbl_attendance_period_dtl
				WHERE $key = ?
EOS;

			
			
			$stmt = $this->query($query, $val, FALSE);
						
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
	public function get_employee_attendance($aColumns, $bColumns, $params)
	{
		try
		{
			$val                      = array($params['attendance_period_hdr_id'],$params['employee_id']);
			$attendance_period_hdr_id = $this->get_hash_key('A.attendance_period_hdr_id');
			$employee_id              = $this->get_hash_key('A.employee_id');
			
			/* For Advanced Filters */
			if(!EMPTY($params['attendance']))
				$params["DATE_FORMAT(A-attendance_date,'%M %d, %Y')"] = $params['attendance'];


			$cColumns      = array("DATE_FORMAT(A-attendance_date,'%M %d, %Y')", "A-basic_hours", "A-working_hours", "B-attendance_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
// 			$query = <<<EOS
// 				SELECT SQL_CALC_FOUND_ROWS $fields 
// 				FROM
// 				$this->tbl_attendance_period_dtl A
// 				JOIN $this->tbl_param_attendance_status B ON A.attendance_status_id = B.attendance_status_id
// 				WHERE $attendance_period_hdr_id = ?
// 				AND $employee_id = ?

// 				$filter_str
// 	        	$sOrder
// 	        	$sLimit
// EOS;

			// marvin : change to left join : start 
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_attendance_period_dtl A
				LEFT JOIN $this->tbl_param_attendance_status B ON A.attendance_status_id = B.attendance_status_id
				WHERE $attendance_period_hdr_id = ?
				AND $employee_id = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
			// marvin : change to left join : end

			$val = array_merge($val,$filter_params);
			
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
	public function employee_attendance_total_length($params)
	{
		try
		{
			$val                      = array($params['attendance_period_hdr_id'],$params['employee_id']);
			
			$attendance_period_hdr_id = $this->get_hash_key('attendance_period_hdr_id');
			$employee_id              = $this->get_hash_key('employee_id');

			$query = <<<EOS
				SELECT count(*) as cnt
				FROM
				$this->tbl_attendance_period_dtl
				WHERE $attendance_period_hdr_id = ?
				AND $employee_id = ?
				
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
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
	public function get_process_tenure($id)
	{
		try
		{
			$val                      = array($id);
			
			$attendance_period_hdr_id = $this->get_hash_key('A.attendance_period_hdr_id');

			$query = <<<EOS
				INSERT INTO $this->tbl_employee_tenure 
					SELECT employee_id,attendance_period_hdr_id,tenure_num_day,tenure_aggr_num_day
					FROM(
						SELECT 
						A.employee_id,
						A.attendance_period_hdr_id,
						ROUND((SUM(A.working_hours) / 8) + IF(B.tenure_num_day IS NULL,0,B.tenure_num_day)) as tenure_num_day,
						ROUND((SUM(IF(A.attendance_status_id IN (SELECT sys_param_value FROM $this->db_core.$this->tbl_sys_param where sys_param_type = 'ATTENDANCE_STAT_WITH_TENURE'),A.working_hours,0)) / 8)+ IF(B.tenure_aggr_num_day IS NULL,0,B.tenure_aggr_num_day)) as tenure_aggr_num_day
						FROM 
						$this->tbl_attendance_period_dtl A
						LEFT JOIN $this->tbl_employee_tenure B ON A.employee_id = B.employee_id
						WHERE $attendance_period_hdr_id = ?

						group by A.employee_id ) result_table

			  	ON DUPLICATE KEY UPDATE 
			  	employee_id=result_table.employee_id,
			  	attendance_period_hdr_id=result_table.attendance_period_hdr_id,
			  	tenure_num_day = result_table.tenure_num_day,
			  	tenure_aggr_num_day = result_table.tenure_aggr_num_day				
EOS;
			
			$stmt = $this->query($query, $val);
						
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
	public function check_no_biometric($employee_id)
	{
		try
		{
			$val = array($employee_id,YES);

			$query = <<<EOS
				SELECT count(A.employee_id) as cnt
				FROM
				$this->tbl_employee_other_info A
				JOIN $this->tbl_param_other_info_types B ON A.other_info_type_id = B.other_info_type_id
				WHERE A.employee_id = ?
				AND B.info_no_bio_flag = ?
				
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
	public function check_leave_wop($employee_id,$active_date)
	{
		try
		{
			$val = array($employee_id, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO, $active_date);

			$query = <<<EOS
				SELECT 
				employee_work_experience_id,service_lwop
				FROM
				$this->tbl_employee_work_experiences
				WHERE employee_id = ?
				AND employ_type_flag IN ( ?,?,?)
				AND ? BETWEEN employ_start_date AND IF(employ_end_date IS NULL,NOW(),employ_end_date);
				
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

	public function update_attendance_period_dtl($attendance_period_hdr_id, $employee_list)
	{
		try
		{
			/*==================== MARVIN ======================*/
			
			//get payroll type
			$payroll_type_id = <<<EOS
				SELECT 
					payroll_type_id
				FROM
					attendance_period_hdr
				WHERE
					attendance_period_hdr_id = $attendance_period_hdr_id
				
EOS;
			$payroll_type = $this->query($payroll_type_id);
			$payroll_type_id = $payroll_type[0]['payroll_type_id'];
			
			//get offices
			$office_id = <<<EOS
				SELECT 
					office_id
				FROM
					param_offices
				
EOS;
			$offices = $this->query($office_id);
			//insert payroll type status
			switch($payroll_type_id)
			{
				case 1:
					// $employment_status_id = array(78,79,80,81,83,84,87,88,89,90,91,93,94,95,96,97);
					$employment_status_id = array(78,79,80,81,83,84,87,88,89,90,91,93,94,95,96,97,124); //jendaigo: add 124 co-termimuns with the appointing authority
					break;
					
				case 3:
					$employment_status_id = 118;
					break;

				case 5:
					$employment_status_id = 119;
					break;
			}
			// if($payroll_type_id == 3)
			// {
				// $employment_status_id = 118;
			// }
			
			foreach($offices as $office)
			{
				if(is_array($employment_status_id))
				{
					foreach($employment_status_id as $emp_stat)
					{
						$this->insert_general_data('param_payroll_type_status_offices', array('payroll_type_id' => $payroll_type_id, 'employment_status_id' => $emp_stat, 'office_id' => $office['office_id'], 'active_flag' => 'Y'));
					}
				}
				else
				{
					$this->insert_general_data('param_payroll_type_status_offices', array('payroll_type_id' => $payroll_type_id, 'employment_status_id' => $employment_status_id, 'office_id' => $office['office_id'], 'active_flag' => 'Y'));					
				}
			}
			/*==================== MARVIN ======================*/
			
			$val = array($attendance_period_hdr_id,$attendance_period_hdr_id,$attendance_period_hdr_id,$attendance_period_hdr_id);
			
			//EMPLOYEE LIST TO EXCLUDE
			if(!is_null($employee_list))
			{
				$ex_emp = implode(',', $employee_list);
				$where = 'AND C.employee_id NOT IN ('.$ex_emp.')';
			}
/*
			$query = <<<EOS
				UPDATE $this->tbl_attendance_period_dtl 
				SET 
				    attendance_period_hdr_id = ?
				WHERE
				    attendance_period_hdr_id IS NULL
				        AND attendance_date BETWEEN (SELECT 
				            date_from
				        FROM
				            $this->tbl_attendance_period_hdr
				        WHERE
				            attendance_period_hdr_id = ?) AND (SELECT 
				            date_to
				        FROM
				            $this->tbl_attendance_period_hdr
				        WHERE
				            attendance_period_hdr_id = ?)
				        AND employee_id IN (SELECT 
				            C.employee_id
				        FROM
				            $this->tbl_attendance_period_hdr A
				                JOIN
				            $this->tbl_param_payroll_type_status_offices B ON A.payroll_type_id = B.payroll_type_id
				                JOIN
				            $this->tbl_employee_work_experiences C ON B.office_id = C.employ_office_id
				                AND B.employment_status_id = C.employment_status_id
				                AND C.active_flag = 'Y'
				        WHERE
				            A.attendance_period_hdr_id = ? $where)
				
EOS;
*/
			// ====================== jendaigo : start : include getting work exp based on period effectivity date ============= //
			$query = <<<EOS
				UPDATE $this->tbl_attendance_period_dtl 
				SET 
				    attendance_period_hdr_id = ?
				WHERE
				    attendance_period_hdr_id IS NULL
				        AND attendance_date BETWEEN (SELECT 
				            date_from
				        FROM
				            $this->tbl_attendance_period_hdr
				        WHERE
				            attendance_period_hdr_id = ?) AND (SELECT 
				            date_to
				        FROM
				            $this->tbl_attendance_period_hdr
				        WHERE
				            attendance_period_hdr_id = ?)
				        AND employee_id IN (SELECT 
				            C.employee_id
				        FROM
				            $this->tbl_attendance_period_hdr A
				                JOIN
				            $this->tbl_param_payroll_type_status_offices B ON A.payroll_type_id = B.payroll_type_id
				                JOIN
				            $this->tbl_employee_work_experiences C ON B.office_id = C.employ_office_id
				                AND B.employment_status_id = C.employment_status_id
				        WHERE
				            A.attendance_period_hdr_id = ?
							AND (IFNULL(C.employ_end_date, CURRENT_DATE) >= A.date_from AND C.employ_start_date <= A.date_to)
							$where)
				
EOS;
			// ====================== jendaigo : end : include getting work exp based on period effectivity date ============= //
				
			$stmt = $this->query($query, $val,NULL);
						
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
	public function insert_process_attendance_period_summary($attendance_period_hdr_id)
	{
		try
		{
			$val                      = array($attendance_period_hdr_id);
			

			$query = <<<EOS
				INSERT INTO $this->tbl_attendance_period_summary 
					SELECT 	
						NULL,
						attendance_period_hdr_id,
						employee_id,
						SUM(IFNULL(basic_hours, 0)) as basic_hours,
						SUM(IFNULL(tardiness, 0)) as tardiness_hours,
						SUM(IFNULL(undertime, 0)) as undertime_hours,
						SUM(IFNULL(overtime, 0)) as overtime_hours,
						SUM(IFNULL(night_diff, 0)) as night_diff_hours,
						SUM(IFNULL(working_hours, 0)) as working_hours,
						SUM(IFNULL(lwop_hours, 0)) as lwop_hours,
						0 lwop_ut_hour,
						0 lwop_ut_min
					FROM
						$this->tbl_attendance_period_dtl
						WHERE attendance_period_hdr_id = ?
						GROUP BY employee_id			
EOS;
			
			$stmt = $this->query($query, $val,NULL);
						
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

	public function get_employee_daily_mra($aColumns, $bColumns, $params)
	{
		try
		{
			
			// $employee_ids	   = $this->get_general_data(('employee_id'), $this->tbl_employee_personal_info);
			// foreach($employee_ids as $employee_id)
			// {
			// 	$prep_string = "%$".$employee_id['employee_id']."%$";
			// 	$hashed_id = md5($prep_string);
			// 	if($hashed_id == $params['employee_id'])
			// 	{
			// 		$params['employee_id'] = $employee_id['employee_id'];
			// 		break;
			// 	}
			// }
			$attendance_period_hdr_ids	   = $this->get_general_data(('attendance_period_hdr_id'), $this->tbl_attendance_period_hdr);
			foreach($attendance_period_hdr_ids as $attendance_period_hdr_id)
			{
				$prep_string = "%$".$attendance_period_hdr_id['attendance_period_hdr_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $params['attendance_period_hdr_id'])
				{
					$params['attendance_period_hdr_id'] = $attendance_period_hdr_id['attendance_period_hdr_id'];
					break;
				}
			}
			$val     = array($params['attendance_period_hdr_id'],$params['employee_id']);
			$cColumns      = array("A-attendance_date", "A-working_hours", "A-tardiness", "A-undertime","B-attendance_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
// 			$query = <<<EOS
// 				SELECT SQL_CALC_FOUND_ROWS $fields 
// 				FROM
// 				$this->tbl_attendance_period_dtl A
// 				JOIN $this->tbl_param_attendance_status B ON A.attendance_status_id = B.attendance_status_id
// 				WHERE $att_key = ?
// 				AND $emp_key = ?
// 				$filter_str
// 	        	$sOrder
// 	        	$sLimit
// EOS;

			// marvin : change to left join : start
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_attendance_period_dtl A
				LEFT JOIN $this->tbl_param_attendance_status B ON A.attendance_status_id = B.attendance_status_id
				WHERE A.attendance_period_hdr_id = ?
				AND A.employee_id = ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
			// marvin : change to left join : end

			$val = array_merge($val,$filter_params);

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
	public function daily_mra_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_employee_daily_mra($aColumns, $bColumns, $params);
			
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
	
	
	public function daily_mra_total_length($attendance_period_hdr_id)
	{
		try
		{
			$val = array($attendance_period_hdr_id);
			$key = $this->get_hash_key('attendance_period_hdr_id');
			$query = <<<EOS
				SELECT count(*) as cnt
				FROM
				$this->tbl_attendance_period_dtl
				WHERE $key = ?
EOS;

			
			
			$stmt = $this->query($query, $val, FALSE);
						
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
	public function insert_general_data($table, $params, $return_id = FALSE,$update = false)
	{
		try
		{
			return $this->insert_data($table, $params, $return_id,$update);
	
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
	
	public function get_attendance_period_dtl_lwop($attendance_period_hdr_id)
	{
		try
		{
			$val = array(LEAVE_WOP, LEAVE_HD_WOP, $attendance_period_hdr_id, LEAVE_WOP, LEAVE_HD_WOP);

			$query = <<<EOS
				SELECT
					employee_id,
					SUM( IF(attendance_status_id = ?, 8, 0) + IF(attendance_status_id = ?, 4, 0) ) total_hrs
				FROM $this->tbl_attendance_period_dtl
				WHERE attendance_period_hdr_id = ?
					AND attendance_status_id in ( ?, ? )
				GROUP BY employee_id
EOS;

			return $this->query($query, $val, TRUE);
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
	
	public function get_attendance_period_summary_lwop($attendance_period_hdr_id)
	{
		try
		{
			$val = array($attendance_period_hdr_id);

			$query = <<<EOS
				SELECT
					employee_id,
					lwop_ut_hr, 
					lwop_ut_min
				FROM $this->tbl_attendance_period_summary
				WHERE attendance_period_hdr_id = ?
EOS;

			return $this->query($query, $val, TRUE);
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
	
}