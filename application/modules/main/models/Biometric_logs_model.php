<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biometric_logs_model extends Main_Model {
	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";
	public $tbl_sys_param     = "sys_param";
	public function __construct() {
		parent:: __construct();
	
	}

	public function get_biometric_files($aColumns, $bColumns, $params, $table, $multiple = TRUE)
	{
		try
		{
			$val = array();
			
			/* For Advanced Filters */
			$cColumns = array("attendance_date","date_uploaded", "file_status_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			// davcorrea : include date range fitler : start
			$date_start = $params['date_start'];
			$date_end = $params['date_end'];
			if(!empty($date_start) AND !empty($date_end))
			{
				if(empty($filter_str)){
					$date_start = format_date($date_start,'Y-m-d');
				$date_end = format_date($date_end,'Y-m-d');
				$add_date_filter = ' WHERE attendance_date BETWEEN "'.$date_start.' 00:00:00" AND "'.$date_end.' 23:59:59"';
			
				}
				else{
					$date_start = format_date($date_start,'Y-m-d');
				$date_end = format_date($date_end,'Y-m-d');
				$add_date_filter = ' AND attendance_date BETWEEN "'.$date_start.' 00:00:00" AND "'.$date_end.' 23:59:59"';
				}
			}
// 			$query = <<<EOS
// 				SELECT SQL_CALC_FOUND_ROWS $fields
// 				FROM (
// 					SELECT A.dtr_upload_hdr_id, A.attendance_date, A.date_uploaded, A.file_status_id,
// 					B.file_status_name,
// 					concat(C.total_terminal,'/',A.terminal_count) terminal_count
// 					from $this->tbl_dtr_upload_hdr A
// 					JOIN param_biometric_file_status B ON A.file_status_id = B.file_status_id
// 					LEFT JOIN (
// 						SELECT dtr_upload_hdr_id, count(dtr_upload_sub_id)  total_terminal
// 						FROM $this->tbl_dtr_upload_sub
// 						GROUP BY dtr_upload_hdr_id
// 					) C ON A.dtr_upload_hdr_id = C.dtr_upload_hdr_id	
// 				) T
// 				$filter_str
// 	        	$sOrder
// 	        	$sLimit
				
// EOS;

$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM (
					SELECT A.dtr_upload_hdr_id, A.attendance_date, A.date_uploaded, A.file_status_id,
					B.file_status_name,
					concat(C.total_terminal,'/',A.terminal_count) terminal_count
					from $this->tbl_dtr_upload_hdr A
					JOIN param_biometric_file_status B ON A.file_status_id = B.file_status_id
					LEFT JOIN (
						SELECT dtr_upload_hdr_id, count(dtr_upload_sub_id)  total_terminal
						FROM $this->tbl_dtr_upload_sub
						GROUP BY dtr_upload_hdr_id
					) C ON A.dtr_upload_hdr_id = C.dtr_upload_hdr_id	
				) T
				$filter_str
				$add_date_filter
	        	$sOrder
	        	$sLimit
				
EOS;

			// davcorrea : include date range fitler : end
			
			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, $multiple);
						
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
			return $this->select_one($fields, $this->tbl_dtr_upload_hdr, $where);
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
	public function get_biometric_sub($aColumns, $bColumns, $params)
	{
		try
		{
			$val	= array($params['header_id']); 
			
			$key 	= $this->get_hash_key('A.dtr_upload_hdr_id');
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			$cColumns =  array("B-file_name", "C-terminal_code","A-date_uploaded");

			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
		
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_dtr_upload_hdr A
				JOIN $this->tbl_dtr_upload_sub B ON A.dtr_upload_hdr_id = B.dtr_upload_hdr_id
				JOIN $this->tbl_param_terminal C ON B.terminal_id = C.terminal_id

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
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;			
		}	
	}

	public function check_previous_attendance($employee_id,$attendance_date)
	{
		try
		{
			$val = array($employee_id,$employee_id,$attendance_date);

			$query = <<<EOS
				SELECT 
					employee_attendance_id,
					attendance_date,
					time_log,
				    time_flag,
				    MAX(IF(time_flag = 'TI',time_log,NULL)) as time_in,
					MAX(IF(time_flag = 'BO',time_log,NULL)) as break_out,
					MAX(IF(time_flag = 'BI',time_log,NULL)) as break_in,
					MAX(IF(time_flag = 'TO',time_log,NULL)) as time_out
				FROM
					$this->tbl_employee_attendance
				WHERE 
				 employee_id = ?	
			        AND attendance_date = (
			        		SELECT MAX(attendance_date)
				        	FROM
				        		$this->tbl_employee_attendance
				        		WHERE
				        		employee_id = ?
						        AND attendance_date < ?
			        	)
				
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

	public function get_csv_bio_temp_data($id)
	{
		try
		{
			$val = array($id);

			$key = $this->get_hash_key('B.dtr_upload_hdr_id');

			$query = <<<EOS
				SELECT 
					A.dtr_upload_sub_id,
					B.dtr_upload_hdr_id, 
					A.biometric_id,
					A.date,
					C.employee_id,
					GROUP_CONCAT(CONCAT(A.time_flag,'|', A.time) ORDER BY A.time) attendance_time
				FROM $this->tbl_dtr_temp_upload_data A
				JOIN $this->tbl_dtr_upload_sub B ON A.dtr_upload_sub_id = B.dtr_upload_sub_id
				JOIN $this->tbl_employee_personal_info C ON A.biometric_id = C.biometric_pin
				WHERE A.date > '0000-00-00'
				AND $key = ?
				 
				GROUP BY
					A.biometric_id, A.date
EOS;
			
			return $this->query($query, $val, TRUE);
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

	public function get_bio_tem_data($id)
	{
		try
		{
			$val = array($id);

			$key 	= $this->get_hash_key('B.dtr_upload_hdr_id');

			$query = <<<EOS
				SELECT 
					A.dtr_upload_sub_id,
					B.dtr_upload_hdr_id, 
					A.biometric_id,
					A.date,
					C.employee_id,
					GROUP_CONCAT(A.time ORDER BY A.time) attendance_time
				FROM $this->tbl_dtr_temp_upload_data A
				JOIN $this->tbl_dtr_upload_sub B ON A.dtr_upload_sub_id = B.dtr_upload_sub_id
				JOIN $this->tbl_employee_personal_info C ON A.biometric_id = C.biometric_pin
				WHERE A.date > '0000-00-00'
				AND $key = ?
				 
				GROUP BY
					A.biometric_id, A.date
EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
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

	public function get_attendance_dtl_employees()
	{
		try
		{
			$val = array();
/*
			$query = <<<EOS
				SELECT 
					A.employee_id
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				 
				GROUP BY
					A.employee_id
EOS;
*/
			// ====================== jendaigo : start : include work_exp end_date ============= //
			$query = <<<EOS
				SELECT 
					A.employee_id, B.start_date, B.end_date
				FROM $this->tbl_employee_personal_info A
				JOIN (
						  SELECT employee_id, MAX(IFNULL(employ_end_date, current_date)) end_date, MIN(employ_start_date) start_date
							FROM $this->tbl_employee_work_experiences
						GROUP BY employee_id ASC
					 ) B 
				  ON A.employee_id = B.employee_id
			GROUP BY A.employee_id
EOS;
			// ====================== jendaigo : end : include work_exp end_date ============= //

			$stmt = $this->query($query, $val, TRUE);
						
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

	public function get_employee_work_schedule($id,$date)
	{
		try
		{
			$val = array($id,$date,$date,$date);
/*
			$query = <<<EOS
				SELECT 
					B.*
				FROM
				$this->tbl_employee_work_schedules A
				JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
				WHERE A.employee_id = ?
				AND ? BETWEEN A.start_date AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
EOS;
*/
			// ====================== jendaigo : start : include work_sched start_date ============= //
			$query = <<<EOS
				SELECT A.start_date, B.*
				  FROM $this->tbl_employee_work_schedules A
				  JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
				 WHERE A.employee_id = ?
				   AND ? BETWEEN A.start_date AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
EOS;
			// ====================== jendaigo : end : include work_sched start_date ============= //
			
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

	public function get_employee_attendance($employee_id,$attendance_date)
	{
		try
		{
			$val = array($employee_id,$attendance_date);

			$query = <<<EOS
				SELECT 
					attendance_date,
				   	MAX(IF(time_flag='TI', time_log, NULL)) time_in,
					MAX(IF(time_flag='BO', time_log, NULL)) break_out,
					MAX(IF(time_flag='BI', time_log, NULL)) break_in,
					MAX(IF(time_flag='TO', time_log, NULL)) time_out
				FROM
					$this->tbl_employee_attendance
				WHERE 
				 employee_id = ?	
			        AND attendance_date = ?
				
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

	public function get_attendance_leave_dtl($employee_id, $date)
	{
		try
		{

			$val = array($employee_id, $date,LEAVE_FILE_LEAVE,LEAVE_COMMUTATION);
			$query = <<<EOS
				SELECT * FROM
				$this->tbl_employee_leave_details
				WHERE employee_id = ?
				AND ? BETWEEN leave_start_date AND leave_end_date
				AND leave_transaction_type_id IN (?,?)
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
	public function insert_biometric_log($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_biometric_log($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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

	public function delete_biometric_log($table, $where)
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
	public function update_biometric_log($table, $fields, $where)
	{
		try
		{
			$this->update_data($table, $fields, $where);
			return TRUE;

		}
		catch (PDOException $e)
		{
			throw $e;
		}
	}

	public function get_attendance_specific_employee_dtl($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$query = <<<EOS
				SELECT 
					A.employee_id, B.start_date, B.end_date
				FROM $this->tbl_employee_personal_info A
				JOIN (
						  SELECT employee_id, MAX(IFNULL(employ_end_date, current_date)) end_date, MIN(employ_start_date) start_date
							FROM $this->tbl_employee_work_experiences
						GROUP BY employee_id ASC
					 ) B 
				  ON A.employee_id = B.employee_id
				  WHERE A.employee_id = ?
			GROUP BY A.employee_id
EOS;
			// ====================== jendaigo : end : include work_exp end_date ============= //

			$stmt = $this->query($query, $val, TRUE);
						
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
	
}