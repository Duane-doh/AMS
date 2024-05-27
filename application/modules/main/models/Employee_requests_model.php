<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_requests_model extends Main_Model {

	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";


	
	public function get_employee_requests_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id  = $this->session->userdata("user_pds_id");
			$employee_ids_valid	   = $this->get_general_data(('employee_id'), $this->tbl_employee_personal_info);
			foreach($employee_ids_valid as $employee_id_valid)
			{
				$prep_string = "%$".$employee_id_valid['employee_id']."%$";
				$hashed_id = md5($prep_string);
				if($hashed_id == $id)
				{
					$id = $employee_id_valid['employee_id'];
					break;
				}
			}
			$val = array($id);
			// $key = $this->get_hash_key('A.employee_id');

			if(isset($params["DATE_FORMAT(A-date_requested,'%M_%d,_%Y')"]))
			$params["DATE_FORMAT(A-date_requested,'%M %d, %Y')"] = $params["DATE_FORMAT(A-date_requested,'%M_%d,_%Y')"];

			$cColumns = array("A-request_code","B-request_type_name", "DATE_FORMAT(A-date_requested,'%M %d, %Y')", "C-request_status_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
				
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_requests A
				JOIN $this->tbl_param_request_types B ON A.request_type_id = B.request_type_id
				JOIN $this->tbl_param_request_status C ON A.request_status_id = C.request_status_id
				JOIN $this->tbl_requests_sub D ON D.request_id = A.request_id
				WHERE A.employee_id = ?
				$filter_str
				GROUP BY A.request_id
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
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_employee_requests_list($aColumns, $bColumns, $params);
			
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

	public function get_leave_application_detail($request_id)
	{
		try
		{
			
			$val = array($request_id);
			$key = $this->get_hash_key('A.request_id');
			
			$query = <<<EOS
				SELECT 
				A.employee_id,
				K.amount,
				DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_of_filing,H.name,E.first_name,E.last_name,E.middle_name,J.position_name,
				DATE_FORMAT(C.date_from,'%b %d, %Y') as inclusive_date_from,
				DATE_FORMAT(C.date_to,'%b %d, %Y') as inclusive_date_to,
				DATE_FORMAT(C.date_processed,'%M %d, %Y') as approved_date,
				C.*
				FROM $this->tbl_requests A
				JOIN $this->tbl_requests_sub B ON A.request_id = B.request_id
				JOIN $this->tbl_requests_leaves C ON C.request_sub_id = B.request_sub_id
				JOIN $this->tbl_employee_personal_info E ON E.employee_id = A.employee_id
				LEFT JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.employ_end_date IS NULL
				LEFT JOIN $this->tbl_param_offices G ON G.office_id = F.employ_office_id
				LEFT JOIN $this->db_core.$this->tbl_organizations H ON G.org_code = H.org_code
				LEFT JOIN $this->tbl_param_positions J ON J.position_id = F.employ_position_id
				LEFT JOIN param_salary_schedule K ON F.employ_salary_grade = K.salary_grade AND F.employ_salary_step = K.salary_step AND K.effectivity_date<= NOW() AND K.active_flag = 'Y'


				WHERE $key = ?
				order by K.effectivity_date desc
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

	public function get_supporting_docs_list($aColumns, $bColumns, $params)
	{
		try
		{
			if(!EMPTY($params['date_received']))
				$params["DATE_FORMAT(F-date_received, '%Y/%m/%d')"] = $params['date_received'];

			$val              = array($params['id']);
			$key              = $this->get_hash_key('A.request_id');
			/* For Advanced Filters */
			$cColumns = array('E-supp_doc_type_name', "DATE_FORMAT(F-date_received, '%Y/%m/%d')");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS DISTINCT D.supp_doc_type_id,$fields 
				FROM $this->tbl_requests_sub A
				JOIN $this->tbl_param_request_sub_types B ON A.request_sub_type_id = B.request_sub_type_id
				JOIN $this->tbl_param_checklists C ON B.check_list_id = C.check_list_id
				JOIN $this->tbl_param_checklist_docs D ON C.check_list_id = D.check_list_id
				JOIN $this->tbl_param_supporting_document_types E ON E.supp_doc_type_id = D.supp_doc_type_id
			    LEFT JOIN $this->tbl_employee_supporting_docs F ON A.request_id =F.request_id AND E.supp_doc_type_id = F.supp_doc_type_id
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

	public function get_supporting_docs_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_supporting_docs_list($aColumns, $bColumns, $params, $table);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
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

	public function get_supporting_docs_total_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_supporting_docs_list($aColumns, $bColumns, $params, $table);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
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
}