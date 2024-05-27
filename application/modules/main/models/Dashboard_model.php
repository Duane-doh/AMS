<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends Main_Model {
                
	//protected static $dsn = SYSAD_DB;
	public $db_core              = DB_CORE;
	public $tbl_organizations    = "organizations";

	public function __construct()
	{
		
		parent::__construct();
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

	public function get_incomplete_attendance($user_id)
	{
		try
		{	
			$year  = date('Y-m');
			$key   = $this->get_hash_key('A.employee_id');
			$val   = array($year, YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO, $user_id);
			
			$query = <<<EOS
				SELECT 
    			COUNT(A.attendance_period_dtl_id) AS count
				FROM attendance_period_dtl A
				JOIN employee_work_experiences B ON A.employee_id = B.employee_id
				LEFT JOIN param_offices C ON B.employ_office_id = C.office_id
				WHERE (A.attendance_status_id = 5
				        OR A.tardiness > 0)
		        AND DATE_FORMAT(A.attendance_date, "%Y-%m") = ?
		        AND B.active_flag = ?
		        AND B.employ_type_flag IN (?, ?, ?)
				AND $key = ?
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

	public function get_monthly_employee_count($month, $offices)
	{
		try
		{

			$curr_date = date("Y");
			$date 	   = $curr_date . '-' . $month;

			$val  	   = array(YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO, $date);

			$office_filter = '';
			if($offices)
			{
				$office_cnt = count($offices);
				$office_filter = ' AND B.office_id IN(';
				foreach ($offices as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$val[] = $value;				
				}
				$office_filter .= ')';
			}

			$query = <<<EOS
				SELECT 
				COUNT(DISTINCT A.employee_id) AS monthly_employee_count
				FROM employee_work_experiences A
				LEFT JOIN $this->tbl_param_offices B ON A.employ_office_id = B.office_id 
				WHERE A.active_flag = ?
				AND A.employ_type_flag IN (?, ?, ?)
				AND A.employ_start_date = (SELECT max(employ_start_date)
								FROM employee_work_experiences
								WHERE DATE_FORMAT(employ_start_date, '%Y-%m') <= ? 
				AND employee_id = A.employee_id)
				$office_filter
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

	public function get_total_incomplete_attendance($offices, $select_field)
	{
		try
		{
			$month = date("Y-m");
			$val   = array($month, YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO);

			$office_filter = '';
			if($offices)
			{
				$office_cnt = count($offices);
				$office_filter = ' AND B.employ_office_id IN(';
				foreach ($offices as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$val[] = $value;				
				}
				$office_filter .= ')';
			}

			$query = <<<EOS
				SELECT $select_field
				FROM attendance_period_dtl A
				JOIN employee_work_experiences B ON A.employee_id = B.employee_id 
				LEFT JOIN param_offices C ON B.employ_office_id = C.office_id
				WHERE (A.attendance_status_id = 5
						OR A.tardiness > 0)
				AND DATE_FORMAT(A.attendance_date, "%Y-%m") = ?
				AND B.active_flag = ?
				AND B.employ_type_flag IN (?, ?, ?)
				$office_filter
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

	public function get_monthly_leave_count($month, $offices)
	{
		try
		{
			$curr_date = date("Y");
			$date 	   = $curr_date . '-' . $month;

			$val  	   = array(REQUEST_LEAVE_APPLICATION, $date, YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO);

			$office_filter = '';
			if($offices)
			{
				$office_cnt = count($offices);
				$office_filter = ' AND B.employ_office_id IN(';
				foreach ($offices as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$val[] = $value;				
				}
				$office_filter .= ')';
			}

			$query = <<<EOS
				SELECT 
				COUNT(DISTINCT date_requested) AS count
				FROM requests A
				JOIN employee_work_experiences B ON A.employee_id = B.employee_id
				LEFT JOIN param_offices C ON B.employ_office_id = C.office_id
				WHERE request_type_id = ?
				AND DATE_FORMAT(date_requested, '%Y-%m') <= ?
				AND B.active_flag = ?
				AND B.employ_type_flag IN (?, ?, ?)
				$office_filter
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

	public function get_monthly_payroll_amount($month, $offices)
	{
		try
		{
			$curr_date = date("Y");
			$date 	   = $curr_date . '-' . $month;

			$val  = array(YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO, $date);

			$office_filter = '';
			if($offices)
			{
				$office_cnt = count($offices);
				$office_filter = ' AND C.employ_office_id IN(';
				foreach ($offices as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$val[] = $value;				
				}
				$office_filter .= ')';
			}

			$query = <<<EOS
				SELECT 
				SUM(B.net_pay) AS sum
				FROM payout_summary_dates A
				LEFT JOIN payout_header B ON A.payout_summary_id = B.payroll_summary_id
				LEFT JOIN employee_work_experiences C ON B.employee_id = C.employee_id
				LEFT JOIN param_offices D ON C.employ_office_id = D.office_id
				WHERE C.separation_mode_id IS NULL 
				AND C.active_flag = ?
				AND C.employ_type_flag IN (?, ?, ?)
				AND DATE_FORMAT(A.effective_date, '%Y-%m') <= ?
				$office_filter
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

	public function get_monthly_employee_late($user_id)
	{
		try
		{
			$curr_date 	= date("Y");
			$key   		= $this->get_hash_key('A.employee_id');

			for($x =1 ;$x <=12;$x++)
			{
				$val[]  = $curr_date . '-' . $x;
			}

			$val[]  = $user_id;

			$query = <<<EOS
				SELECT 
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_jan,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_feb,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_mar,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_apr,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_may,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_jun,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_jul,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_aug,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_sep,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_oct,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_nov,
			    SUM(IF(DATE_FORMAT(A.attendance_date, '%Y-%c') = ? ,A.tardiness,0)) AS sum_dec
				FROM attendance_period_dtl A
			    LEFT JOIN employee_work_experiences B ON A.employee_id = B.employee_id
			    LEFT JOIN param_offices C ON B.employ_office_id = C.office_id
				WHERE A.tardiness > 0
				AND $key = ?
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

}
