<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_voucher_model extends Main_Model {
     
	public $db_core              = DB_CORE;
	public $tbl_organizations    = "organizations";
	public $tbl_sys_param    = "sys_param";

	public function get_payroll_voucher_list($aColumns, $bColumns, $params)
	{
		try
		{
			
			$val = array();

			/* For Advanced Filters */
			if(!EMPTY($params['process_date']))
			$params["DATE_FORMAT(B-process_start_date,'%M %d, %Y')"] = $params['process_date'];
			$cColumns      = array("D-employee_name", "A-voucher_description", "DATE_FORMAT(B-process_start_date,'%M %d, %Y')", "D-net_pay", "C-payout_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$add_where     = '';
			if(!EMPTY($params['D-office_id'])) {
				$office_list = '';
				$office_list = $this->get_office_child($office_list, $params['D-office_id']);
				$add_where   = (EMPTY($filter_str) ? 'WHERE D.office_id IN (' . implode(',', $office_list) . ')' : ' AND D.office_id IN (' . implode(',', $office_list) . ')');
			}

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_vouchers A
				JOIN $this->tbl_payout_summary B ON A.payroll_summary_id = B.payroll_summary_id
				JOIN $this->tbl_param_payout_status C ON A.voucher_status_id = C.payout_status_id
				JOIN $this->tbl_payout_header D ON B.payroll_summary_id = D.payroll_summary_id
				$filter_str
				$add_where
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

	

	public function voucher_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_payroll_voucher_list($aColumns, $bColumns, $params);
			
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
	
	
	public function voucher_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_vouchers, $where);
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
	public function get_voucher_history_list($aColumns, $bColumns, $params)
	{
		try
		{
			
			$val = array($params['payroll_summary_id']);
			$key = $this->get_hash_key('A.payout_summary_id');
			/* For Advanced Filters */
			if(!EMPTY($params['processed_by']))
			$params["CONCAT(B-first_name, ' ',LEFT(B-middle_name, 1),' ',B-last_name, ' ', LEFT(B-ext_name, 3))"] = $params['processed_by'];
			if(!EMPTY($params['hist_date']))
			$params["DATE_FORMAT(A-hist_date,'%M %d, %Y %h:%i %p')"] = $params['hist_date'];
			$cColumns      = array("CONCAT(B-first_name, ' ',LEFT(B-middle_name, 1),' ',B-last_name, ' ', LEFT(B-ext_name, 3))", "DATE_FORMAT(A-hist_date,'%M %d, %Y %h:%i %p')", "A-remarks", "C-payout_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_payout_history A
				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
				JOIN $this->tbl_param_payout_status C ON A.payout_status_id = C.payout_status_id
				WHERE $key = ?

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

	

	public function history_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_voucher_history_list($aColumns, $bColumns, $params);
			
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
	
	
	public function history_total_length($payroll_summary_id)
	{
		try
		{
			$where        = array();
			$key          = $this->get_hash_key('payout_summary_id');
			$where[$key]  = $payroll_summary_id;
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_payout_history, $where);
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
	public function get_employee_personal_info($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			// $fields = "A.employee_id, A.agency_employee_id, CONCAT( A.first_name, ' ',LEFT(A.middle_name, 1),' ',A.last_name, ' ', LEFT(A.ext_name, 3)) as employee_name, ifnull(B.employ_office_name,E.name) as office_name, ifnull(B.employ_position_name, D.position_name) AS position_name, B.employ_plantilla_id,B.employ_office_id as office_id, B.employ_salary_grade as salary_grade,B.employ_salary_step as salary_step";
			// ====================== jendaigo : start : change name format ============= //
			$fields = "A.employee_id, A.agency_employee_id, CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', A.middle_name))) as employee_name, ifnull(B.employ_office_name,E.name) as office_name, ifnull(B.employ_position_name, D.position_name) AS position_name, B.employ_plantilla_id,B.employ_office_id as office_id, B.employ_salary_grade as salary_grade,B.employ_salary_step as salary_step";
			// ====================== jendaigo : end : change name format ============= //

			$query = <<<EOS
				SELECT  $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id  AND B.active_flag ='Y'
				LEFT JOIN $this->tbl_param_offices C ON B.employ_office_id = C.office_id 
				LEFT JOIN $this->tbl_param_positions D ON B.employ_position_id = D.position_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code
				WHERE $key = ?
EOS;
	
			RLog::debug($query);
			$val = array_merge($val);
			$stmt = $this->query($query, $val, false);
						
			return $stmt;

		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
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
	
	public function get_notification_params($module_id, $voucher_status_id)
	{
		try
		{
			//SET SESSION FOR GROUP_CONCATAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			

			$field = array('action_id', 'approved_flag');
			$where = array('payout_status_id' => $voucher_status_id);
			$table = $this->tbl_param_payout_status;
			$params = $this->select_one($field, $table, $where);			
			
			$val = array($module_id, $params['action_id']);
			
			$query = <<<EOS
				SELECT GROUP_CONCAT(DISTINCT role_code) notify_roles, GROUP_CONCAT(DISTINCT C.office_id) notify_orgs
				FROM $this->db_core.$this->tbl_module_action_roles A 
				JOIN $this->db_core.$this->tbl_module_actions B 
					ON A.module_action_id = B.module_action_id 
				JOIN $this->db_core.$this->tbl_user_offices C
					ON B.module_id = C.module_id 
						AND B.module_id = ? AND B.action_id = ?
EOS;

			$data = $this->query($query, $val, FALSE);

			$params['notify_roles'] = $data['notify_roles'];
			$params['notify_orgs'] = $data['notify_orgs'];
			return $params;
		}
		catch (Exception $e)
		{
		}
	}	

	public function get_bir_deduction($payroll_summary_id)
	{
		try
		{
						
			
			$val = array($payroll_summary_id, DEDUC_BIR, DEDUC_BIR_EWT, DEDUC_BIR_VAT);
			
			$query = <<<EOS
				SELECT 
					    GROUP_CONCAT(deduction_id) AS deduction_id
					FROM
					    $this->tbl_payout_details
					WHERE
					    payroll_hdr_id = (SELECT 
					            payroll_hdr_id
					        FROM
					            $this->tbl_payout_header
					        WHERE
					            payroll_summary_id = ?)
					    AND deduction_id IN (?, ?, ?)
					GROUP BY payroll_hdr_id;
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			return $stmt['deduction_id'];
		}
		catch (Exception $e)
		{
		}
	}	
}