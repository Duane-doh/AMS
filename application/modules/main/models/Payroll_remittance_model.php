<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_remittance_model extends Main_Model {

	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";
	public $tbl_sys_param     = "sys_param";
	public $tbl_users         = "users";

	public function get_payroll_remittance($aColumns, $bColumns, $params)
	{
		try
		{
			$val      = array();
			$val[]    = 'Y';
			$cColumns = array("B-remittance_type_name", "DATE_FORMAT(A-deduction_start_date, '%Y/%m/%d')", "DATE_FORMAT(A-deduction_end_date, '%Y/%m/%d')", "C-remittance_status_name", "A-year_month");
			
			$fields   = str_replace(" , ", " ", implode(", ", $aColumns));
			
			if(!EMPTY($params['A-deduction_start_date']))
				$params["DATE_FORMAT(A-deduction_start_date, '%Y/%m/%d')"] = $params['A-deduction_start_date'];
// 				$params['A-deduction_start_date'] = DATE_FORMAT(A-deduction_start_date, '%Y/%m/%d');

			if(!EMPTY($params['A-deduction_end_date']))
				$params["DATE_FORMAT(A-deduction_end_date, '%Y/%m/%d')"] = $params['A-deduction_end_date'];
				// 				$params['A-deduction_end_date'] = DATE_FORMAT(A-deduction_end_date, '%Y/%m/%d');
			$params['A-year_month'] = str_replace('-', '', format_date($params['month_year'],'Y-m'));

			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$group_by	   = 'GROUP BY A.remittance_id';
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 

				FROM $this->tbl_remittances A
				LEFT JOIN $this->tbl_param_remittance_types B ON B.remittance_type_id = A.remittance_type_id
				LEFT JOIN $this->tbl_param_remittance_status C ON C.remittance_status_id = A.remittance_status_id
				WHERE B.active_flag = ?
				$filter_str
				$group_by
	        	$sOrder
	        	$sLimit
EOS;
		
			$val = array_merge($val,$filter_params);

			$stmt = $this->query($query, $val, TRUE);

			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	} 
	

	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_payroll_remittance($aColumns, $bColumns, $params);

			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
		
	public function total_length($table=NULL)
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
	
			return $this->select_one($fields, $table, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw new Exception($e->getMessage());
		}	
	}
	public function get_remittance_attachment_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array($params['remittance_id']);

			$key = $this->get_hash_key('A.remittance_id');
			
			$params["CONCAT(B-fname,' ',B-lname)"] = $params['uploader'];
			$cColumns = array( "A-date_uploaded","A-file_name", "CONCAT(B-fname,' ',B-lname)");
			
			$fields   = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 

				FROM $this->tbl_remittance_upload A
				JOIN $this->db_core.$this->tbl_users B ON A.uploaded_by = B.user_id
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
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	} 
	

	public function attachment_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_remittance_attachment_list($aColumns, $bColumns, $params);

			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
		
	public function attachment_total_length($id)
	{
		try
		{
			$where = array();
			$key = $this->get_hash_key('remittance_id');
			$where[$key] = $id;
			
			$fields = array("COUNT(remittance_id) cnt");
	
			return $this->select_one($fields, $this->tbl_remittance_upload, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw new Exception($e->getMessage());
		}	
	}
	public function get_total_length($table, $field, $where)
	{
		try
		{
			$fields = array(" COUNT(DISTINCT $field) cnt ");
			return $this->select_one($fields, $table, $where);
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

	public function get_payout_employee_list($aColumns, $bColumns, $id, $params, $multiple = TRUE)
	{
		try
		{
			$val = array(0 => YES, 1 => $id, 2 => YES);
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))
			$params['CONCAT(D-last_name, IF(D-ext_name=\'\',\'\',CONCAT(\' \', D-ext_name)), \', \', D-first_name, \' \',LEFT(D-middle_name,1), \'.\')'] = $params['fullname'];

			if(!EMPTY($params['amount']))
			$params["SUM(B-amount)"] = $params['amount'];
			

			$cColumns = array('D-agency_employee_id', 'CONCAT(D-last_name, IF(D-ext_name=\'\',\'\',CONCAT(\' \', D-ext_name)), \', \', D-first_name, \' \',LEFT(D-middle_name,1), \'.\')', 'H-name', 'G-employment_status_name' , 'SUM(B-amount)', 'F-office_id');
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);

			$filter_remittance_id = $this->get_hash_key('C.remittance_id') . ' = ?';
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$add_where     = '';
			if(!EMPTY($params['F-office_id'])) {
				$office_list = '';
				$office_list = $this->get_office_child($office_list, $params['C-office_id']);
				$add_where   = ' AND F.office_id IN (' . implode(',', $office_list) . ')';
			}

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_payout_header A 
				LEFT JOIN $this->tbl_payout_details B ON B.payroll_hdr_id = A.payroll_hdr_id
				LEFT JOIN $this->tbl_remittance_details C ON C.payroll_dtl_id = B.payroll_dtl_id
				JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				JOIN $this->tbl_employee_work_experiences E ON A.employee_id = E.employee_id AND E.active_flag = ?
				JOIN $this->tbl_param_offices F ON A.office_id = F.office_id
				LEFT JOIN $this->tbl_param_employment_status G ON E.employment_status_id = G.employment_status_id
				JOIN $this->db_core.$this->tbl_organizations H ON F.org_code = H.org_code
				WHERE $filter_remittance_id
				AND B.include_flag = ?
				$filter_str
				$add_where
				GROUP BY A.employee_id
				$sOrder
			$sLimit
					
EOS;
			$val                     = array_merge($val,$filter_params);
			
			$stmt['data']            = $this->query($query, $val, $multiple);
			$stmt['filtered_length'] = $this->_get_filtered_length();	
						
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

	public function get_employee_deduction_list($aColumns, $bColumns, $remittance_id, $employee_id, $params, $multiple = TRUE)
	{
		try
		{
			$val = array(0 => $remittance_id, 1 => $employee_id);
			/* For Advanced Filters */
			$cColumns = array('C-deduction_name', 'A-effective_date', 'A-amount' , 'A-reference_text');
			
			if(!EMPTY($params['A-effective_date']))
				$params['A-effective_date'] = date_format(date_create($params['A-effective_date']),'Y-m-d');	

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);

			$filter_remittance_id = $this->get_hash_key('E.remittance_id') . ' = ?';
			$filter_employee_id   = $this->get_hash_key('B.employee_id') . ' = ?';
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			

			$query = <<<EOS
				SELECT $fields 
				FROM
				$this->tbl_payout_details A 
				LEFT JOIN $this->tbl_payout_header B ON B.payroll_hdr_id = A.payroll_hdr_id
				LEFT JOIN $this->tbl_param_deductions C ON C.deduction_id = A.deduction_id
				JOIN $this->tbl_remittance_details D ON D.payroll_dtl_id = A.payroll_dtl_id
				JOIN $this->tbl_remittances E ON E.remittance_id = D.remittance_id
				WHERE $filter_remittance_id AND $filter_employee_id
				$filter_str
				$sOrder
				$sLimit
				
EOS;
			$val                     = array_merge($val,$filter_params);

			$stmt['data']            = $this->query($query, $val, $multiple);
			$stmt['filtered_length'] = $this->_get_filtered_length();	
						
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

	public function get_payroll_remittance_history($aColumns, $bColumns, $id, $params, $multiple = TRUE)
	{
		try
		{
			$val = array(0 => $id);
			
			/* For Advanced Filters */

			if(!EMPTY($params['processed_by']))
			$params["CONCAT(D-last_name, if(D-ext_name='','',CONCAT(' ', D-ext_name)), ', ', D-first_name, ' ',LEFT(D-middle_name,1), '.')"] = $params['processed_by'];

			if(!EMPTY($params['hist_date']))
			$params["A-hist_date"] = format_date($params['hist_date'],'Y-m-d');

			$cColumns = array("CONCAT(D-last_name, if(D-ext_name='','',CONCAT(' ', D-ext_name)), ', ', D-first_name, ' ',LEFT(D-middle_name,1), '.')", 'A-hist_date', 'C-remittance_status_name', 'A-remarks');
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_remittance_id = $this->get_hash_key('A.remittance_id') . ' = ?';
			$filter_str           = $sWhere["search_str"];
			$filter_params        = $sWhere["search_params"];
			

			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_remittance_history A 
				LEFT JOIN $this->tbl_remittances B ON B.remittance_id = A.remittance_id
				LEFT JOIN $this->tbl_param_remittance_status C ON C.remittance_status_id = A.remittance_status_id
				JOIN $this->tbl_employee_personal_info D ON D.employee_id = A.employee_id
				WHERE $filter_remittance_id
				$filter_str
				$sOrder
				$sLimit
				
EOS;
			$val = array_merge($val,$filter_params);

			$stmt['data'] = $this->query($query, $val, $multiple);
			$stmt['filtered_length'] = $this->_get_filtered_length();	
						
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

	private function _get_filtered_length()
	{
		try
		{
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

	public function get_payroll_remittance_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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

	public function insert_payroll_remittance($table, $fields, $return_id = FALSE)
	{
		try
		{

			return $this->insert_data($table, $fields, $return_id);

		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	public function update_payroll_remittance($table, $fields, $where)
	{
		try
		{
			$this->update_data($table, $fields, $where);
			return TRUE;

		}
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	public function delete_payroll_remittance($table, $where)
	{
		try
		{
			return $this->delete_data($table, $where);

		}
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	public function get_payroll_remittance_details($select_fields, $tables, $where)
	{
		try
		{
			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			return $this->select_all($fields, $tables, $where);
			
		
		}
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	public function get_updated_remittance_amount($remittance_id = NULL) 
	{
		try
		{
			$val = array( $remittance_id );
			$filter_remittance_id = $this->get_hash_key('remittance_id') . ' = ?';

			$query = <<<EOS
				SELECT SUM(amount) amount
				FROM
				$this->tbl_payout_details
				WHERE payroll_dtl_id IN (
											SELECT payroll_dtl_id
											FROM $this->tbl_remittance_details
											WHERE $filter_remittance_id
										) 
				
EOS;
			return $this->query($query, $val, FALSE);
		}
		catch (PDOException $e)
		{
			throw $e;
		}
	}

	public function get_notification_params($module_id, $remittance_status_id)
	{
		try
		{
			//SET SESSION FOR GROUP_CONCATAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			

			$field = array('action_id', 'remitted_flag');
			$where = array('remittance_status_id' => $remittance_status_id);
			$table = $this->tbl_param_remittance_status;
			$params = $this->select_one($field, $table, $where);			
		
			$val = array($module_id, $params['action_id']);
			
			$query = <<<EOS
				SELECT 	GROUP_CONCAT(DISTINCT role_code) notify_roles, 
						GROUP_CONCAT(DISTINCT C.office_id) notify_orgs
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

	public function get_payroll_details($remittance_type_id, $is_voucher, $remittance_id = NULL) 
	{
		try
		{
			$add_where = '';
			$val = array( $remittance_type_id, ($is_voucher ? PAYOUT_TYPE_FLAG_VOUCHER : PAYOUT_TYPE_FLAG_REGULAR));
			if(ISSET($remittance_id) AND !EMPTY($remittance_id)) $add_where = 'AND '.$this->get_hash_key('remittance_id').' != "' . $remittance_id . '"';
			$query = <<<EOS
				SELECT 
				    GROUP_CONCAT(payroll_dtl_id) AS payroll_dtl_id
				FROM
				    $this->tbl_remittance_details
				WHERE
				    remittance_id IN (SELECT 
				            remittance_id
				        FROM
				            $this->tbl_remittances
				        WHERE
				            remittance_type_id = ? AND payroll_type_flag = ? $add_where
						)
				
EOS;

			return $this->query($query, $val, FALSE);
		}
		catch (PDOException $e)
		{
			throw $e;
		}
	}

}
	
