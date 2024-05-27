<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_ta_model extends Main_Model {

	public function __construct() {
		parent:: __construct();
	
	}
	// SCHEMA NAME
	public $DB_MAIN                     = DB_MAIN;
	public $DB_CORE                     = DB_CORE;
	
	public $tbl_organizations           = 'organizations';
	public $tbl_genders            		= 'param_genders';
	public $tbl_barangays            	= 'param_barangays';
	public $tbl_municities            	= 'param_municities';
	public $tbl_provinces           	= 'param_provinces';

	public function get_employee_basic_info($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			/*
			$fields = "A.employee_id, 
					A.agency_employee_id, 
					CONCAT(A.last_name, ', ', A.first_name, ' ',A.middle_name, ' ', LEFT(A.ext_name,2)) as fullname, 
					B.employ_office_name as office_name, 
					B.employ_position_name position_name,  
					B.admin_office_name,
					F.employment_status_name, 
					CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name))) as employee_name";
			*/
			
			// ====================== jendaigo : start : change name format ============= //
			$fields = "A.employee_id, 
					A.agency_employee_id, 
					CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', A.middle_name))) as fullname, 
					B.employ_office_name as office_name, 
					B.employ_position_name position_name,  
					B.admin_office_name,
					F.employment_status_name, 
					CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='' OR A.ext_name IS NULL,'',CONCAT(' ', A.ext_name))) as employee_name";
			// ====================== jendaigo : end : change name format ============= //
			
			$query = <<<EOS
				SELECT  $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B 
				ON A.employee_id = B.employee_id AND B.active_flag = "Y"
				LEFT JOIN $this->tbl_param_employment_status F 
				ON B.employment_status_id = F.employment_status_id
				WHERE $key = ?
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
	
	public function get_all_employees($fields, $table)
	{
		try
		{
			$fields = (!empty($fields)) ? $fields : array("*");

			return $this->select_all($fields, $table);
			
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_print_dtr_list($employee_id,$start_date,$end_date)
	{
		try
		{
			$val = array(OFFICIAL_BUSINESS,OFFICIAL_BUSINESS,OFFICIAL_BUSINESS,OFFICIAL_BUSINESS,OFFICIAL_BUSINESS,OFFICIAL_BUSINESS,LEAVE_FILE_LEAVE,$employee_id,$start_date,$end_date);
		
			$query = <<<EOS
				SELECT 
				    A.employee_id,
				    A.attendance_date,
				    A.undertime,
				    A.tardiness,
				    E.leave_type_name,
					IF(MAX(IF(B.attendance_status_id = ?, C.attendance_status_name, NULL)) != "",MAX(IF(B.attendance_status_id = ?, C.attendance_status_name, NULL)),IF(D.orig_leave_type_id IS NOT NULL, F.leave_type_name, E.leave_type_name )) attendance_status_name, 
				    DATE_FORMAT(A.attendance_date,'%e') as attendance_day,
				    MAX(IF(B.time_flag='TI', DATE_FORMAT(B.time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_in,
					MAX(IF(B.time_flag='BO', DATE_FORMAT(B.time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_out,
					MAX(IF(B.time_flag='BI', DATE_FORMAT(B.time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_in,
					MAX(IF(B.time_flag='TO', DATE_FORMAT(B.time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_out,
				    MAX(IF(B.time_flag='TI', IF(B.attendance_status_id != ?, DATE_FORMAT(B.time_log,'%h:%i %p'), ''), NULL)) time_in_log,
					MAX(IF(B.time_flag='BO', IF(B.attendance_status_id != ?, DATE_FORMAT(B.time_log,'%h:%i %p'), ''), NULL)) break_out_log,
					MAX(IF(B.time_flag='BI', IF(B.attendance_status_id != ?, DATE_FORMAT(B.time_log,'%h:%i %p'), ''), NULL)) break_in_log,
					MAX(IF(B.time_flag='TO', IF(B.attendance_status_id != ?, DATE_FORMAT(B.time_log,'%h:%i %p'), ''), NULL)) time_out_log
				FROM
                $this->tbl_attendance_period_dtl A
				LEFT JOIN $this->tbl_employee_attendance B ON A.employee_id = B.employee_id AND A.attendance_date = B.attendance_date
				LEFT JOIN $this->tbl_param_attendance_status C ON B.attendance_status_id = C.attendance_status_id
				LEFT JOIN $this->tbl_employee_leave_details D ON A.employee_id = D.employee_id AND A.attendance_date BETWEEN D.leave_start_date AND D.leave_end_date AND D.leave_transaction_type_id = ?
				LEFT JOIN $this->tbl_param_leave_types E ON D.leave_type_id = E.leave_type_id
				LEFT JOIN $this->tbl_param_leave_types F ON D.orig_leave_type_id = F.leave_type_id
                WHERE
				   	A.employee_id = ?
				AND 
				    A.attendance_date BETWEEN ? AND ?

				GROUP BY A.attendance_date
				ORDER BY A.attendance_date ASC
				
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

	public function get_leave_application_detail($request_id)
	{
		try
		{
			
			$val = array($request_id);
			$key = $this->get_hash_key('A.request_id');
			
			$query = <<<EOS
				SELECT 
				A.employee_id,
				A.request_status_id,
				F.employ_monthly_salary as amount,
				DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_of_filing, F.employ_office_name name,E.first_name,E.last_name,E.ext_name,E.middle_name,F.employ_position_name as position_name,
				DATE_FORMAT(C.date_from,'%b %d, %Y') as inclusive_date_from,
				DATE_FORMAT(C.date_to,'%b %d, %Y') as inclusive_date_to,
				DATE_FORMAT(C.date_processed,'%M %d, %Y') as approved_date,
				C.*
				FROM $this->tbl_requests A
				JOIN $this->tbl_requests_sub B ON A.request_id = B.request_id
				JOIN $this->tbl_requests_leaves C ON C.request_sub_id = B.request_sub_id
				JOIN $this->tbl_employee_personal_info E ON E.employee_id = A.employee_id
				LEFT JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.active_flag = 'Y'


				WHERE $key = ?
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

	public function get_leave_remarks($request_id)
	{
		try
		{
			
			$val = array($request_id);
			$key = $this->get_hash_key('request_id');
			
			$query = <<<EOS
				SELECT * FROM $this->tbl_requests_tasks
				WHERE request_task_id = (
					SELECT max(request_task_id) from $this->tbl_requests_tasks
					WHERE $key = ?
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
	public function get_leave_requests($employee_id)
	{
		try
		{
			
			$val = array($employee_id, REQUEST_LEAVE_APPLICATION, REQUEST_CANCELLED);
			$key = $this->get_hash_key('A.request_id');
			
			$query = <<<EOS
				SELECT *
				FROM $this->tbl_requests 

				WHERE employee_id = ?
				AND request_type_id = ?
				AND request_status_id != ?

				order by date_requested desc
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
	public function get_employee_leave_card($employee_id)
	{
		try
		{
			$stmt = $this->query("select set_leave_card_table($employee_id)", NULL, NULL);

			$val = array($employee_id);
			$query = <<<EOS
				
				select a.*, 
					if(a.vl_flag = 'Y',(sum(if(b.vl_flag = 'Y',b.vl_earned,0)) - sum(if(b.vl_flag = 'Y',b.vl_used,0))),null) as vl_balance,
					(sum(b.sl_earned) - sum(b.sl_used)) as sl_balance
					from temp_leave_card_a a
					JOIN temp_leave_card_b b ON a.employee_id = b.employee_id AND a.row_num >= b.row_num
					where a.employee_id = ?
					group by a.period,a.particulars
					order by a.transaction_date,a.leave_start_date

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
	
	public function get_mra_office($attendance_period_hdr_id)
	{
		try
		{
			
			$val = array($attendance_period_hdr_id);
			// $query = <<<EOS
				
				// SELECT 
				// E.office_id, E.name
				// FROM
                // (SELECT E.employee_id, G.office_id, H.name
					// FROM $this->tbl_employee_personal_info E
					// JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.active_flag = 'Y'
					// JOIN $this->tbl_param_offices G ON F.employ_office_id = G.office_id
					// JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code 
				// ) E
                // WHERE E.employee_id IN 
					// (SELECT employee_id FROM $this->tbl_attendance_period_dtl WHERE attendance_period_hdr_id = ?  GROUP BY employee_id)
					// group by E.office_id 
// EOS;

/*
			$query = <<<EOS
				
				SELECT 
				E.office_id, E.name
				FROM
                (SELECT E.employee_id, G.office_id, H.name
					FROM $this->tbl_employee_personal_info E
					JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.active_flag = 'Y'
					JOIN $this->tbl_param_offices G ON F.employ_office_id = G.office_id
					JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code 
				) E
					group by E.office_id 
EOS;
*/
			// ====================== jendaigo : start : include work exp date range based on attendance_period_hdr ============= //
			$query = <<<EOS
				SELECT E.office_id, E.name
				FROM
					(SELECT E.employee_id, G.office_id, H.name, F.employ_end_date, I.date_from, F.employ_start_date, I.date_to
						FROM $this->tbl_employee_personal_info E
						JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id
						JOIN $this->tbl_param_offices G ON F.employ_office_id = G.office_id
						JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code 
						LEFT JOIN $this->tbl_attendance_period_hdr I ON I.attendance_period_hdr_id =  ?
					) E
                WHERE (IFNULL(E.employ_end_date, CURRENT_DATE) >= E.date_from AND E.employ_start_date <= E.date_to)
				group by E.office_id 
EOS;
			// ====================== jendaigo : end : include work exp date range based on attendance_period_hdr ============= //
			
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
	public function get_mra_employee($params)
	{
		try
		{
			$where = "";
			// $val = array($params['mra_attendance_period']);
			$val = array($params['mra_attendance_period'], $params['mra_attendance_period']); //jendaigo: add attendance_period_hdr
			if($params['mra_office'])
			{
				$where = " AND ";
				$where .= " E.employ_office_id IN (";
				$cnt = 0;
				foreach ($params['mra_office'] as  $value) {

					$cnt++;
					if($cnt < count($params['mra_office']))
					{
						$where .= " ? ,";
					}
					else
					{
						$where .= " ?";
					}
					$val[] = $value;
					
				}
				$where .= ")";
			}
			// $query = <<<EOS
				
				// SELECT 
				// E.employee_id, E.employee_name
				// FROM
                // (SELECT A.employee_id,B.employ_office_id, CONCAT(A.first_name, ' ', IF(LEFT(A.middle_name, 1) != '',CONCAT(LEFT(A.middle_name, 1),'. '),'') , A.last_name, ' ', LEFT(A.ext_name, 3)) employee_name
					// FROM $this->tbl_employee_personal_info A	                   
					// JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id and B.active_flag = 'Y'
                     
					// group by A.employee_id 
				// ) E
                // WHERE E.employee_id IN 
					// (SELECT employee_id FROM $this->tbl_attendance_period_dtl WHERE attendance_period_hdr_id = ? )
                // $where
                // ORDER BY E.employee_name

// EOS;

/*
			//marvin : change name format : start
			$query = <<<EOS
				
				SELECT 
				E.employee_id, E.employee_name
				FROM
                (SELECT A.employee_id,B.employ_office_id, CONCAT(A.last_name, ', ', A.first_name, ' ', IF(LEFT(A.middle_name, 1) != '',CONCAT(LEFT(A.middle_name, 1),'. '),'') , ' ', LEFT(A.ext_name, 3)) employee_name
					FROM $this->tbl_employee_personal_info A	                   
					JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id and B.active_flag = 'Y'
                     
					group by A.employee_id 
				) E
                WHERE E.employee_id IN 
					(SELECT employee_id FROM $this->tbl_attendance_period_dtl WHERE attendance_period_hdr_id = ? )
                $where
                ORDER BY E.employee_name

EOS;
			//marvin : change name format : end
*/
			// ====================== jendaigo : start : include work exp date range based on attendance_period_hdr ============= //
			$query = <<<EOS
				
				SELECT E.employee_id, E.employee_name
				FROM
					(SELECT A.employee_id,B.employ_office_id, CONCAT(A.last_name, ', ', A.first_name, ' ', IF(LEFT(A.middle_name, 1) != '',CONCAT(LEFT(A.middle_name, 1),'. '),'') , ' ', LEFT(A.ext_name, 3)) employee_name,
						B.employ_end_date, C.date_from, B.employ_start_date, C.date_to
						FROM $this->tbl_employee_personal_info A	                   
						JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
						JOIN $this->tbl_attendance_period_hdr C ON C.attendance_period_hdr_id =  ?
					) E
                WHERE (IFNULL(E.employ_end_date, CURRENT_DATE) >= E.date_from AND E.employ_start_date <= E.date_to)
				AND	E.employee_id IN 
					(SELECT employee_id FROM $this->tbl_attendance_period_dtl WHERE attendance_period_hdr_id = ? )
                $where
				GROUP by E.employee_id 
                ORDER BY E.employee_name

EOS;
			// ====================== jendaigo : end : include work exp date range based on attendance_period_hdr ============= //

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
	
	public function get_filtered_employee_per_office($office_id,$employee_id)
	{
		try
		{
			$val = array();

			if(!EMPTY($office_id)) {
				$office_list = array();
				$office_list = $this->get_office_child($office_list, $office_id);
				$add_where   = 'WHERE B.employ_office_id IN (' . implode(',', $office_list) . ')';
			}
			if(!EMPTY($employee_id)) {
				$add_where   = 'WHERE A.employee_id = ?';
				$val[] = $employee_id;
			}
/*
			$query = <<<EOS
				
				SELECT 
				A.employee_id, CONCAT(A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name)),', ',A.first_name, ' ', LEFT(A.middle_name, (1)), '. ') employee_name
				FROM
				$this->tbl_employee_personal_info A 
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id and B.active_flag = 'Y'

				$add_where

				group by A.employee_id 
				ORDER BY employee_name
EOS;
*/
			// ====================== jendaigo : start : change name format ============= //
			$query = <<<EOS
				
				SELECT 
				A.employee_id, CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/OR A.middle_name IS NULL'), '', CONCAT(' ', LEFT(A.middle_name, (1)), '.'))) employee_name
				FROM
				$this->tbl_employee_personal_info A 
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id and B.active_flag = 'Y'

				$add_where

				group by A.employee_id 
				ORDER BY employee_name
EOS;
			// ====================== jendaigo : end : change name format ============= //
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


public function get_payroll_period($payroll_type_id)
	{
		try
		{
			$val = array($payroll_type_id);
			
		
			$columns = array("attendance_period_hdr_id", "DATE_FORMAT(date_from,'%M %d, %Y') as date_from","DATE_FORMAT(date_to,'%M %d, %Y') as date_to");
						
			$fields = str_replace(" , ", " ", implode(", ", $columns));
			
			
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_attendance_period_hdr
				WHERE payroll_type_id  = ?
				
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

	public function get_monthly_report_of_attendance($params)
	{
		try
		{
			$where = "";
			$val = array($params['mra_attendance_period'],ATTENDANCE_STATUS_REST_DAY);
			if($params['mra_employee'])
			{
				$question_mark = rtrim(str_repeat("?, ",count($params['mra_employee'])), ", ");
					
				$where   .= ' AND A.employee_id IN ('.$question_mark.')';
				$val         = array_merge($val,$params['mra_employee']);
			}
			elseif($params['mra_office'])
			{
				$question_mark = rtrim(str_repeat("?, ",count($params['mra_office'])), ", ");
					
				$where   .= ' AND D.employ_office_id IN ('.$question_mark.')';
				$val         = array_merge($val,$params['mra_office']);
			}
/*
			$query = <<<EOS
				
				SELECT 
				A.employee_id,
				A.attendance_period_hdr_id,
				G.name,
				CONCAT(B.last_name, ' ', LEFT(B.ext_name, 3),', ',B.first_name, ' ', LEFT(B.middle_name, 1),' ') employee_name,
				DATE_FORMAT(A.attendance_date,'%e') as attendance_date, 
				(
					SELECT SUM(IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1))
					FROM $this->tbl_employee_leave_details C 
					WHERE A.employee_id = C.employee_id 
					AND C.leave_type_id = 1
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				) as sick_leave,
				(
					SELECT SUM(IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1))
					FROM $this->tbl_employee_leave_details C 
					WHERE A.employee_id = C.employee_id 
					AND C.leave_type_id <> 1
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				) as vacation_leave,
				IFNULL(A.undertime_hr,0) + IFNULL(A.tardiness_hr,0) as undertime_hour,
				IFNULL(A.undertime_min,0) + IFNULL(A.tardiness_min,0) as undertime_min,
				H.attendance_status_name,
				A.attendance_status_id,				
				IF(K.leave_type_name IS NOT NULL,K.leave_type_name,GROUP_CONCAT(DISTINCT J.remarks)) as remarks
				FROM
				attendance_period_dtl A
				JOIN $this->tbl_employee_personal_info B ON B.employee_id = A.employee_id
				LEFT JOIN $this->tbl_employee_leave_details C ON C.employee_id = B.employee_id AND A.attendance_date BETWEEN C.leave_start_date AND DATE_SUB(C.leave_end_date,INTERVAL C.leave_wop DAY)
				JOIN $this->tbl_employee_work_experiences D ON D.employee_id = A.employee_id and D.active_flag = 'Y'
				JOIN $this->tbl_param_offices F ON F.office_id = D.employ_office_id
				JOIN $this->DB_CORE.$this->tbl_organizations G ON F.org_code = G.org_code
				JOIN $this->tbl_param_attendance_status H ON H.attendance_status_id = A.attendance_status_id
				LEFT JOIN $this->tbl_employee_attendance J ON A.employee_id = J.employee_id AND A.attendance_date = J.attendance_date
				LEFT JOIN $this->tbl_param_leave_types K ON C.leave_type_id = K.leave_type_id AND C.leave_type_id NOT IN (1,2)

				WHERE A.attendance_period_hdr_id = ?
				AND A.attendance_status_id != ?
				$where

				group by F.office_id,A.employee_id,A.attendance_date
				order by F.office_id,employee_name,A.attendance_date
EOS;
*/
/*
// ====================== jendaigo : start : change name format ============= //
			$query = <<<EOS
				
				SELECT 
				A.employee_id,
				A.attendance_period_hdr_id,
				G.name,
				CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '.'))) employee_name,
				DATE_FORMAT(A.attendance_date,'%e') as attendance_date, 
				(
					SELECT SUM(IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1))
					FROM $this->tbl_employee_leave_details C 
					WHERE A.employee_id = C.employee_id 
					AND C.leave_type_id = 1
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				) as sick_leave,
				(
					SELECT SUM(IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1))
					FROM $this->tbl_employee_leave_details C 
					WHERE A.employee_id = C.employee_id 
					AND C.leave_type_id <> 1
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				) as vacation_leave,
				IFNULL(A.undertime_hr,0) + IFNULL(A.tardiness_hr,0) as undertime_hour,
				IFNULL(A.undertime_min,0) + IFNULL(A.tardiness_min,0) as undertime_min,
				H.attendance_status_name,
				A.attendance_status_id,				
				IF(K.leave_type_name IS NOT NULL,K.leave_type_name,GROUP_CONCAT(DISTINCT J.remarks)) as remarks
				FROM
				attendance_period_dtl A
				JOIN $this->tbl_employee_personal_info B ON B.employee_id = A.employee_id
				LEFT JOIN $this->tbl_employee_leave_details C ON C.employee_id = B.employee_id AND A.attendance_date BETWEEN C.leave_start_date AND DATE_SUB(C.leave_end_date,INTERVAL C.leave_wop DAY)
				JOIN $this->tbl_employee_work_experiences D ON D.employee_id = A.employee_id and D.active_flag = 'Y'
				JOIN $this->tbl_param_offices F ON F.office_id = D.employ_office_id
				JOIN $this->DB_CORE.$this->tbl_organizations G ON F.org_code = G.org_code
				JOIN $this->tbl_param_attendance_status H ON H.attendance_status_id = A.attendance_status_id
				LEFT JOIN $this->tbl_employee_attendance J ON A.employee_id = J.employee_id AND A.attendance_date = J.attendance_date
				LEFT JOIN $this->tbl_param_leave_types K ON C.leave_type_id = K.leave_type_id AND C.leave_type_id NOT IN (1,2)

				WHERE A.attendance_period_hdr_id = ?
				AND A.attendance_status_id != ?
				$where

				group by F.office_id,A.employee_id,A.attendance_date
				order by F.office_id,employee_name,A.attendance_date
EOS;
// ====================== jendaigo : end : change name format ============= //
*/
			// ====================== jendaigo : start : include work exp date range based on attendance_period_hdr ============= //
			$query = <<<EOS
				
				SELECT 
				A.employee_id,
				A.attendance_period_hdr_id,
				G.name,
				CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='' OR B.ext_name IS NULL, '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/' OR B.middle_name IS NULL), '', CONCAT(' ', LEFT(B.middle_name, 1), '.'))) employee_name,
				DATE_FORMAT(A.attendance_date,'%e') as attendance_date, 
				(
					SELECT SUM(IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1))
					FROM $this->tbl_employee_leave_details C 
					WHERE A.employee_id = C.employee_id 
					AND C.leave_type_id = 1
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				) as sick_leave,
				(
					SELECT SUM(IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1))
					FROM $this->tbl_employee_leave_details C 
					WHERE A.employee_id = C.employee_id 
					AND C.leave_type_id <> 1
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				) as vacation_leave,
				IFNULL(A.undertime_hr,0) + IFNULL(A.tardiness_hr,0) as undertime_hour,
				IFNULL(A.undertime_min,0) + IFNULL(A.tardiness_min,0) as undertime_min,
				H.attendance_status_name,
				A.attendance_status_id,				
				IF(K.leave_type_name IS NOT NULL,K.leave_type_name,GROUP_CONCAT(DISTINCT J.remarks)) as remarks,
				IF(C.remarks = 'SL Charged to VL' , 1,0) as Charged_to_VL_flag
				FROM
				attendance_period_dtl A
				JOIN $this->tbl_employee_personal_info B ON B.employee_id = A.employee_id
				LEFT JOIN $this->tbl_employee_leave_details C ON C.employee_id = B.employee_id AND A.attendance_date BETWEEN C.leave_start_date AND DATE_SUB(C.leave_end_date,INTERVAL C.leave_wop DAY)
				JOIN $this->tbl_employee_work_experiences D ON D.employee_id = A.employee_id
				JOIN $this->tbl_param_offices F ON F.office_id = D.employ_office_id
				JOIN $this->DB_CORE.$this->tbl_organizations G ON F.org_code = G.org_code
				JOIN $this->tbl_param_attendance_status H ON H.attendance_status_id = A.attendance_status_id
				LEFT JOIN $this->tbl_employee_attendance J ON A.employee_id = J.employee_id AND A.attendance_date = J.attendance_date
				LEFT JOIN $this->tbl_param_leave_types K ON C.leave_type_id = K.leave_type_id
				LEFT JOIN $this->tbl_attendance_period_hdr L ON L.attendance_period_hdr_id =  A.attendance_period_hdr_id
				WHERE A.attendance_period_hdr_id = ?
				AND A.attendance_status_id != ?
				AND (IFNULL(D.employ_end_date, CURRENT_DATE) >= L.date_from AND D.employ_start_date <= L.date_to)
				$where

				group by F.office_id,A.employee_id,A.attendance_date
				order by F.office_id,employee_name,A.attendance_date
EOS;
			// ====================== jendaigo : end : include work exp date range based on attendance_period_hdr ============= //
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

	public function get_mra_leave_summary($employee_id,$attendance_period_hdr_id)
	{
		try
		{
			$val = array($employee_id,$attendance_period_hdr_id);
			

			$query = <<<EOS
				
				SELECT 
				SUM(IF(C.leave_type_id = 1,IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1),0)) as sick_leave, 
				SUM(IF(C.leave_type_id != 1,IF(C.leave_earned_used + C.leave_wop < 1,C.leave_earned_used + C.leave_wop,1),0)) as vacation_leave,
				SUM(IFNULL(A.undertime_hr,0) + IFNULL(A.tardiness_hr,0)) as undertime_hour,
				SUM(IFNULL(A.undertime_min,0) + IFNULL(A.tardiness_min,0)) as undertime_min

				FROM
					$this->tbl_attendance_period_dtl A
				LEFT JOIN $this->tbl_employee_leave_details C ON A.employee_id = C.employee_id 
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				WHERE  A.employee_id = ?
					AND A.attendance_period_hdr_id = ?
					
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

	public function get_mra_leave_summary_v2($employee_id,$attendance_period_hdr_id)
	{
		try
		{
			$val = array($employee_id,$attendance_period_hdr_id);
			

// 			$query = <<<EOS
				
// 				SELECT 
// 					SUM(IF(C.leave_type_id = 1,IF(C.leave_earned_used < 1,C.leave_earned_used,1),0)) as sick_leave_wp, 
// 					SUM(IF(C.leave_type_id != 1,IF(C.leave_earned_used < 1,C.leave_earned_used,1),0)) as vacation_leave_wp,
// 					SUM(IF(C.leave_type_id = 1,IF(C.leave_wop < 1,C.leave_wop,1),0)) as sick_leave_wop, 
// 					SUM(IF(C.leave_type_id != 1,IF(C.leave_wop < 1,C.leave_wop,1),0)) as vacation_leave_wop,
// 					SUM(IFNULL(A.undertime_hr,0) + IFNULL(A.tardiness_hr,0)) as undertime_hour,
// 					SUM(IFNULL(A.undertime_min,0) + IFNULL(A.tardiness_min,0)) as undertime_min

// 				FROM
// 					$this->tbl_attendance_period_dtl A
// 				LEFT JOIN $this->tbl_employee_leave_details C ON A.employee_id = C.employee_id 
// 					AND C.leave_transaction_type_id IN (4,5)
// 					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
// 				WHERE  A.employee_id = ?
// 					AND A.attendance_period_hdr_id = ?
					
// EOS;
			$query = <<<EOS
				
				SELECT 
					*

				FROM
					$this->tbl_attendance_period_dtl A
				LEFT JOIN $this->tbl_employee_leave_details C ON A.employee_id = C.employee_id 
					AND C.leave_transaction_type_id IN (4,5)
					AND A.attendance_date BETWEEN C.leave_start_date AND C.leave_end_date
				WHERE  A.employee_id = ?
					AND A.attendance_period_hdr_id = ?
					
EOS;
$stmt = $this->query($query, $val, TRUE);

	// echo"<pre>";
	// print_r($query);
	// print_r($val);
	// die();

						
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
	public function get_absent_total_summary($employee_id,$attendance_period_hdr_id)
	{
		try
		{
			$val = array($employee_id,$attendance_period_hdr_id);
			

			$query = <<<EOS
				
				SELECT 
				SUM(IF(attendance_status_id = 5,1,0)) as absents

				FROM
					$this->tbl_attendance_period_dtl 
				WHERE  employee_id = ?
					AND attendance_period_hdr_id = ?
					
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
	public function get_leavecard_wop_summary($employee_id,$attendance_period_hdr_id)
	{
		try
		{
			$val = array($employee_id,$attendance_period_hdr_id);
			

			$query = <<<EOS
				
				SELECT 
					lwop_ut_hr,
					lwop_ut_min
				FROM
					$this->tbl_attendance_period_summary
				WHERE  employee_id = ?
					AND attendance_period_hdr_id = ?
					
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
	public function get_leave_balance_statement($params = array(), $office)
	{
		try
		{
			$val = array(LEAVE_TYPE_SICK, LEAVE_TYPE_VACATION,LEAVE_FILE_LEAVE, LEAVE_TYPE_FORCED, LEAVE_TYPE_FORCED,LEAVE_FILE_LEAVE, LEAVE_TYPE_SPECIAL_PRIVILEGE, LEAVE_TYPE_SPECIAL_PRIVILEGE);
			
			$office_filter = '';
			if($office)
			{
				$office_cnt = count($office);
				$office_filter = ' AND C.employ_office_id IN(';
				foreach ($office as $key => $value) {
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
			$val[] = DOH_GOV_NON_APPT;
			$val[] = DOH_GOV_APPT;
/*
			$query = <<<EOS
				
				SELECT 
				    CONCAT(B.last_name,
				            IF(B.ext_name = '',
				                '',
				                CONCAT(' ', B.ext_name)),
				            ', ',
				            B.first_name,
				            ' ',
				            LEFT(B.middle_name, 1),
				            '.') AS fullname,
				    SUM(IF(A.leave_type_id = ?,
				        A.leave_balance,
				        0)) AS total_sl,
				    SUM(IF(A.leave_type_id = ?,
				        A.leave_balance,
				        0)) AS total_vl,
				    ( SELECT  SUM(IFNULL(E.leave_earned_used,0)) AS availed_fl 
						FROM $this->tbl_employee_leave_details E
						WHERE  A.employee_id = E.employee_id AND E.leave_transaction_type_id = ? 
						AND (E.leave_type_id = ? OR E.orig_leave_type_id = ?)) as availed_fl,
                    ( SELECT  SUM(IFNULL(E.leave_earned_used,0)) AS availed_fl 
						FROM $this->tbl_employee_leave_details E 
						WHERE  A.employee_id = E.employee_id AND E.leave_transaction_type_id = ? 
						AND (E.leave_type_id = ? OR E.orig_leave_type_id = ?)) as availed_spl
				FROM
				    $this->tbl_employee_leave_balances A
				        JOIN
				    $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
				    JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id AND C.active_flag = 'Y' $office_filter
					WHERE C.employ_type_flag IN (?,?)			
				GROUP BY A.employee_id 
				ORDER BY fullname
EOS;
*/
			// ====================== jendaigo : start : change name format ============= //
// 			$query = <<<EOS
// 				SELECT 
// 				    CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '.'))) AS fullname,
// 				    SUM(IF(A.leave_type_id = ?,
// 				        A.leave_balance,
// 				        0)) AS total_sl,
// 				    SUM(IF(A.leave_type_id = ?,
// 				        A.leave_balance,
// 				        0)) AS total_vl,
// 				    ( SELECT  SUM(IFNULL(E.leave_earned_used,0)) AS availed_fl 
// 						FROM $this->tbl_employee_leave_details E
// 						WHERE  A.employee_id = E.employee_id AND E.leave_transaction_type_id = ? 
// 						AND (E.leave_type_id = ? OR E.orig_leave_type_id = ?)) as availed_fl,
//                     ( SELECT  SUM(IFNULL(E.leave_earned_used,0)) AS availed_fl 
// 						FROM $this->tbl_employee_leave_details E 
// 						WHERE  A.employee_id = E.employee_id AND E.leave_transaction_type_id = ? 
// 						AND (E.leave_type_id = ? OR E.orig_leave_type_id = ?)) as availed_spl
// 				FROM
// 				    $this->tbl_employee_leave_balances A
// 				        JOIN
// 				    $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
// 				    JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id AND C.active_flag = 'Y' $office_filter
// 					WHERE C.employ_type_flag IN (?,?)			
// 				GROUP BY A.employee_id 
// 				ORDER BY fullname
// EOS;
			// ====================== jendaigo : end : change name format ============= //
			// ====================== davcorrea : start :  spl and fpl from prev years not included ============= //
			$query = <<<EOS
				SELECT 
				    CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='' OR B.ext_name IS NULL, '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/' OR B.middle_name IS NULL), '', CONCAT(' ', LEFT(B.middle_name, 1), '.'))) AS fullname,
				    SUM(IF(A.leave_type_id = ?,
				        A.leave_balance,
				        0)) AS total_sl,
				    SUM(IF(A.leave_type_id = ?,
				        A.leave_balance,
				        0)) AS total_vl,
				    ( SELECT  SUM(IFNULL(E.leave_earned_used,0)) AS availed_fl 
						FROM $this->tbl_employee_leave_details E
						WHERE  A.employee_id = E.employee_id AND E.leave_transaction_type_id = ? 
						AND (E.leave_type_id = ? OR E.orig_leave_type_id = ?)AND YEAR(E.leave_transaction_date) = YEAR(CURRENT_DATE())) as availed_fl,
                    ( SELECT  SUM(IFNULL(E.leave_earned_used,0)) AS availed_fl 
						FROM $this->tbl_employee_leave_details E 
						WHERE  A.employee_id = E.employee_id AND E.leave_transaction_type_id = ? 
						AND (E.leave_type_id = ? OR E.orig_leave_type_id = ?)AND YEAR(E.leave_transaction_date) = YEAR(CURRENT_DATE())) as availed_spl
				FROM
				    $this->tbl_employee_leave_balances A
				        JOIN
				    $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
				    JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id AND C.active_flag = 'Y' $office_filter
					WHERE C.employ_type_flag IN (?,?)			
				GROUP BY A.employee_id 
				ORDER BY fullname
EOS;
			// ====================== davcorrea : end : spl and fpl from prev years not included ============= //

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
	public function get_reports_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
	{
		try
		{
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group_by, $limit);
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group_by, $limit);
			}
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	
	public function get_employee_by_office($where)
	{
		try
		{
	
			$where_arr	= $this->get_where_statement($where);
			$where 		= $where_arr['where'];
			$val		= $where_arr['val'];
/*
			$query = <<<EOS
				SELECT
					B.employee_id id,
					CONCAT(B.first_name, " ",  B.last_name) name
				FROM $this->tbl_employee_work_experiences A
				JOIN $this->tbl_employee_personal_info B
				ON A.employee_id = B.employee_id
				$where
				GROUP BY B.employee_id
EOS;
*/
			// ====================== jendaigo : start : change name format ============= //
			$query = <<<EOS
				SELECT
					B.employee_id id,
					CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/' OR B.middle_name IS NULL), '', CONCAT(' ', LEFT(B.middle_name,1), '.')), ' ', B.last_name, IF(B.ext_name=''  OR B.ext_name IS NULL, '', CONCAT(' ', B.ext_name))) name
				FROM $this->tbl_employee_work_experiences A
				JOIN $this->tbl_employee_personal_info B
				ON A.employee_id = B.employee_id
				$where
				GROUP BY B.employee_id
EOS;
			// ====================== jendaigo : end : change name format ============= //
			
			$stmt 	= $this->query($query, $val);
			
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
	// dont include job order employees certificate of leave
	// davcorrea 10/03/2023
	// ===================START===================
	public function get_employee_by_office_wo_jo($where)
	{
		try
		{
	
			$where_arr	= $this->get_where_statement($where);
			$where 		= $where_arr['where'];
			$val		= $where_arr['val'];
			$query = <<<EOS
				SELECT
					B.employee_id id,
					CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/' OR B.middle_name IS NULL), '', CONCAT(' ', LEFT(B.middle_name,1), '.')), ' ', B.last_name, IF(B.ext_name=''  OR B.ext_name IS NULL, '', CONCAT(' ', B.ext_name))) name
				FROM $this->tbl_employee_work_experiences A
				JOIN $this->tbl_employee_personal_info B
				ON A.employee_id = B.employee_id
				$where 
				AND
				A.employ_type_flag != 'JO'
				GROUP BY B.employee_id
EOS;
			$stmt 	= $this->query($query, $val);
			
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
	// =======================END================================
	public function leave_without_pay_cert($where, $date_from, $date_to){
		try
		{
			$where_arr	= $this->get_where_statement($where);
			$where 		= $where_arr['where'];
			$val		= $where_arr['val'];
		
			$query = <<<EOS
				SELECT 
						YEAR(A.leave_start_date) leave_years,  
				        MONTH(A.leave_start_date) leave_month,
				        DATE_FORMAT(A.leave_start_date, '%M') as display_month,
						B.leave_type_name,
				   		GROUP_CONCAT(IF(if(leave_earned_used>0,DATE_SUB(A.leave_end_date, INTERVAL ROUND(A.leave_wop)-1 DAY),A.leave_start_date) = A.leave_end_date,DATE_FORMAT(A.leave_end_date, '%d'),CONCAT(DATE_FORMAT(if(leave_earned_used>0,DATE_SUB(A.leave_end_date, INTERVAL ROUND(A.leave_wop)-1 DAY),A.leave_start_date), '%d'), '-', 
						DATE_FORMAT(A.leave_end_date, '%d')) ) ORDER BY A.leave_start_date) leave_dates,
				        SUM(A.leave_wop) lwop_days
				FROM $this->tbl_employee_leave_details A
				JOIN $this->tbl_param_leave_types B ON A.leave_type_id = B.leave_type_id
				$where
				AND A.leave_wop > 0
				GROUP BY leave_years,leave_month, leave_type_name
				ORDER BY A.leave_start_date
EOS;
		
			$stmt 	= $this->query($query, $val);
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

	public function get_office_list()
	{
		try
		{
			$val   = array(YES);
			$query = <<<EOS
				SELECT 
				B.name, 
				A.office_id, 
				A.org_code 
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code
				WHERE A.active_flag = ?
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

	public function get_agency_info($office)
	{
		try
		{
			$val = array($office, YES);
			$query = <<<EOS
				SELECT 
				B.name, 
				A.office_id
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code
				WHERE A.office_id = ? AND A.active_flag = ?
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
	public function get_raw_bio_logs($employee_id,$start_date,$end_date)
	{
		try
		{
			$val = array($employee_id,$start_date,$end_date);
/*
			$query = <<<EOS
				SELECT date,group_concat( TIME_FORMAT(time,' %h:%i %p') order by time) as time_log 
				FROM $this->tbl_dtr_temp_upload_data A
				WHERE biometric_id = ?
				AND date BETWEEN ? AND ?
				group by date
				order by date
EOS;
*/
// ====================== jendaigo : start : get distinct data and log type ============= //
$query = <<<EOS
				SELECT date, group_concat( DISTINCT TIME_FORMAT(time,' %h:%i %p'), " (", 
				CASE
					WHEN time_flag = 'TI' THEN 'Time-in'
					WHEN time_flag = 'TO' THEN 'Time-out'
					WHEN time_flag = 'BI' THEN 'Break-in'
					ELSE 'Break-out'
				END 
				, ")"  order by time) as time_log
				FROM $this->tbl_dtr_temp_upload_data A
				WHERE biometric_id = ?
				AND date BETWEEN ? AND ?
				group by date
				order by date
EOS;
// ====================== jendaigo : end : get distinct data and log type ============= //

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

	public function get_coe_special_benefits($employee_id)
	{
		try
		{
			$year = date('Y') - 1;
			$val = array($year,$employee_id,YES);
			
			$query = <<<EOS
				SELECT     
				b.employee_id,
				        c.compensation_id, i.compensation_name,
				        SUM(c.amount) compensation_amount
				FROM $this->tbl_payout_summary a
				JOIN $this->tbl_payout_header b ON b.payroll_summary_id = a.payroll_summary_id
				JOIN $this->tbl_payout_details c ON c.payroll_hdr_id = b.payroll_hdr_id
				JOIN $this->tbl_param_compensations i ON i.compensation_id = c.compensation_id AND c.compensation_id IS NOT NULL

				WHERE YEAR(c.effective_date) = ?
				AND b.employee_id = ?
				AND i.special_payroll_flag = ?
				GROUP BY compensation_id
				ORDER BY compensation_name ASC
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
	
	/*========================= MARVIN : START : GET LEAVE TRANSACTION TYPE IDAND REMARKS =========================*/
	public function get_leave_transaction_type_id($employee_id, $leave_type_id, $transaction_date)
	{
		try
		{
			$val = array($employee_id, $leave_type_id, $transaction_date);
			
			$query = <<<EOS
				SELECT leave_transaction_type_id, remarks
				FROM $this->tbl_employee_leave_details
				WHERE employee_id = ? 
				AND leave_type_id = ?
				AND leave_transaction_date = ?

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
	/*========================= MARVIN : END : GET LEAVE TRANSACTION TYPE IDAND REMARKS =========================*/

	public function get_comp_table_equivalent($total,$table_type)
	{
		try
		{
			$val = array("Y",$table_type,$total);
			
			$query = <<<EOS
				
				SELECT 
					A.point_equivalent 
				FROM
					$this->tbl_param_computation_table_detail A
				JOIN $this->tbl_param_computation_table  B ON A.computation_table_id = B.computation_table_id AND B.active_flag = ? AND B.computation_table_type_id = ?
				WHERE A.computation_type_equivalent = ?
				
					
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

	public function get_early_suspension_details($date)
	{
		try
		{
			$val = array($date);
			

			$query = <<<EOS
				
				SELECT 
				*

				FROM
					$this->tbl_param_work_calendar
				WHERE  holiday_date = ?
					
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

	public function get_employee_no_work_sched_list($payroll_type_id, $datefrom, $dateto)
	{
		try
		{
			switch($payroll_type_id)
			{
				case 1:
					// $employment_status_id = array(78,79,80,81,83,84,87,88,89,90,91,93,94,95,96,97);
					$employment_status_id = "78,79,80,81,83,84,87,88,89,90,91,93,94,95,96,97,124"; //jendaigo: add 124 co-termimuns with the appointing authority
					break;
					
				case 3:
					$employment_status_id = 118;
					break;

				case 5:
					$employment_status_id = 119;
					break;
			}
			$val = array($datefrom,$dateto);
			

			$query = <<<EOS
				
			
			SELECT B.employee_id
			FROM param_employment_status A
			JOIN employee_work_experiences B ON A.employment_status_id = B.employment_status_id 
			WHERE A.employment_status_id IN ($employment_status_id)
			AND (IFNULL(B.employ_end_date, CURRENT_DATE) >= ? AND B.employ_start_date <= ?)
			
					
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

	public function get_employee_work_sched($employee_id, $datefrom, $dateto)
	{
		try
		{

			$val = array($employee_id, $datefrom,$dateto);
			

			$query = <<<EOS
				
			
			SELECT *
			FROM employee_work_schedules 
			WHERE employee_id = ?
			AND (IFNULL(end_date, CURRENT_DATE) >= ? AND start_date <= ?)
			
					
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


	public function get_employee_no_work_sched_details($employees)
	{
		try
		{
			
			$val = array($employees);
			$query = <<<EOS
				
			SELECT * FROM employee_personal_info A
			JOIN employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag = 'Y'
			WHERE A.employee_id IN ($employees)			
EOS;
		
			$stmt = $this->query($query, NULL, TRUE);
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
	public function get_employee_salutation($employee_id)
	{
		try
		{
			
			$val = array($employee_id);
			$query = <<<EOS
				
			SELECT employ_position_id FROM employee_work_experiences WHERE employee_work_experience_id = (SELECT MAX(employee_work_experience_id) FROM employee_work_experiences WHERE employ_personnel_movement_id IS NOT NULL AND employee_id = ?)	
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

	public function get_employee_latest_work_experience($employee_id)
	{
		try
		{
			
			$val = array($employee_id, $employee_id);
			$query = <<<EOS
				
			SELECT employ_type_flag FROM employee_work_experiences WHERE employ_start_date = (SELECT MAX(employ_start_date) FROM employee_work_experiences WHERE employee_id = ?) AND employee_id = ?
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