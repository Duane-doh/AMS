<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_dtr_model extends Main_Model {
    
	
	public $db_core                                   = DB_CORE;
	public $tbl_process_stages                        = "process_stages";
	public $tbl_process_steps                         = "process_steps";
	public $tbl_requests_tasks                        = "requests_tasks";

	public function get_attendance_list($aColumns, $bColumns, $params)
	{
		try
		{
			$user_pds_id  = $this->session->userdata("user_pds_id");
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
	public function attendance_total_length()
	{
		try
		{
			$user_pds_id = $this->session->userdata("user_pds_id");
			$key         = $this->get_hash_key('A.employee_id');
			$val         = array($user_pds_id );
			

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
	public function get_print_dtr_list($date_from,$date_to)
	{
		try
		{
			$user_pds_id  = $this->session->userdata("user_pds_id");
			$key 	= $this->get_hash_key('employee_id');
			$val = array($user_pds_id,$date_from,$date_to);
			
			
			$query = <<<EOS
				SELECT 
				    employee_id,
				    DATE_FORMAT(attendance_date,'%M %d, %Y') as attendance_date,
				    DATE_FORMAT(attendance_date,'%W') as attendance_day,
				    MAX(IF(time_flag='TI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_in,
					MAX(IF(time_flag='BO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_out,
					MAX(IF(time_flag='BI', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) break_in,
					MAX(IF(time_flag='TO', DATE_FORMAT(time_log,'%Y/%m/%d %h:%i %p'), NULL)) time_out
				FROM
				    $this->tbl_employee_attendance
				WHERE
				   		$key = ?
				        AND attendance_date BETWEEN ? AND ?
				GROUP BY attendance_date
				ORDER BY attendance_date ASC
				
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
	public function get_attendance_time_logs($employee_id,$date_start,$date_end)
	{
		try
		{

			$key         = $this->get_hash_key('employee_id');
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
				ORDER BY attendance_date ASC
				
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
	// davcorrea 10/04/2023 added function approving officer list : START
	public function employee_get_approving_officer_list($params,$aColumns, $bColumns)
	{
		try
		{
			//$val              = array($params['employee_id']);
			// davcorrea: START : remove inactive approving officers 
			$val              = array('1',$params['employee_id'],'AO','IMMSUP','LVAPPOFF');
			// END
			$key 	= $this->get_hash_key('employee_id');

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			// davcorrea: START : remove inactive approving officers 
// 			$query = <<<EOS
// 			SELECT SQL_CALC_FOUND_ROWS DISTINCT $fields
// 			FROM doh_ptis_module.associated_accounts A 
// 			JOIN doh_ptis_core.user_offices B ON A.user_id = B.office_id
// 			JOIN doh_ptis_module.associated_accounts C ON C.user_id = B.user_id
// 			JOIN doh_ptis_module.employee_personal_info D ON D.employee_id = C.employee_id
// 			WHERE A.employee_id = (SELECT employee_id FROM doh_ptis_module.employee_personal_info WHERE $key = ?)
// 			$sOrder
// 			$sLimit
// EOS;
			$query = <<<EOS
			SELECT SQL_CALC_FOUND_ROWS DISTINCT $fields
			FROM doh_ptis_module.associated_accounts A 
			JOIN doh_ptis_core.user_offices B ON A.user_id = B.office_id
			JOIN doh_ptis_module.associated_accounts C ON C.user_id = B.user_id
			JOIN doh_ptis_module.employee_personal_info D ON D.employee_id = C.employee_id
			JOIN doh_ptis_core.users E ON E.user_id = B.user_id AND E.status_id = ?
			LEFT JOIN doh_ptis_core.user_roles F
					ON B.user_id = F.user_id
			WHERE A.employee_id = (SELECT employee_id FROM doh_ptis_module.employee_personal_info WHERE $key = ?)
			AND (F.role_code = ? OR F.role_code = ? OR F.role_code = ?) 
			$sOrder
			$sLimit
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

	public function employee_get_approving_officer_filtered_list($params,$aColumns)
	{
		try
		{
			$val              = array($params['employee_id']);
			$key 	= $this->get_hash_key('employee_id');

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			$query = <<<EOS
			SELECT SQL_CALC_FOUND_ROWS DISTINCT $fields
			FROM doh_ptis_module.associated_accounts A 
			JOIN doh_ptis_core.user_offices B ON A.user_id = B.office_id
			JOIN doh_ptis_module.associated_accounts C ON C.user_id = B.user_id
			JOIN doh_ptis_module.employee_personal_info D ON D.employee_id = C.employee_id
			WHERE A.employee_id = (SELECT employee_id FROM doh_ptis_module.employee_personal_info WHERE $key = ?)
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

	public function get_approving_officer_roles($id)
	{
		try
		{
			$val              = array(PERSONNEL,$id);
			$fields = "B.role_name";
			$query = <<<EOS
			SELECT $fields
			FROM $this->db_core.$this->tbl_user_roles A 
			JOIN $this->db_core.$this->tbl_roles B ON B.role_code = A.role_code
			WHERE B.role_code != ? AND A.user_id = ?

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

		//Added function to check if employee has multiple work schedule - davcorrea
	//============START==============
	public function if_employee_has_multiple_work_schedule($id,$datefrom,$dateto)
	{
		try
		{
			$val = array($id,$datefrom,$dateto,$dateto);


			$query = <<<EOS
			SELECT 
			B.*
			FROM
			$this->tbl_employee_work_schedules A
			JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
			WHERE A.employee_id = ?
			AND A.start_date BETWEEN ? AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
				 
				
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
	//=======================END=========================
	//Added function get rest days from work schedule
	// davcorrea: 09/27/2023 ============START==========================
	public function get_employee_rest_days($id,$datefrom,$dateto)
	{
		try
		{	
			$restDays = "21";
			$val = array($id,$restDays,$datefrom,$dateto,$dateto);


			$query = <<<EOS
			SELECT 
			A.*
			FROM
			$this->tbl_employee_work_schedules A
			JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
			WHERE A.employee_id = ?
			AND A.work_schedule_id = ?
			AND A.start_date BETWEEN ? AND ( IFNULL(A.end_date,IF( ? > NOW(), ? ,NOW())))
				 
				
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
}
