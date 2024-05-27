<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statutory_model extends Main_Model {

	public function __construct() {
		parent:: __construct();
	
	}

	public function get_employee_statutory_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array();
			
			/* For Advanced Filters */
			$cColumns = array("*");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM employee_satutory_hdr A
				LEFT JOIN param_deductions B ON A.deduction_id = B.deduction_id
				JOIN param_deductions_type C ON C.deduction_type_id = B.deduction_type_id
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
	
	public function get_statutory_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns = array("deduction_statutory_id", "deduction_statutory_code", "details");
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY deduction_statutory_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_benefit_list($aColumns, $bColumns, $params);
			
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
			/*
			$where["status_id"]["!="] = INACTIVE;
			$where["user_id"]["!="] = ANONYMOUS_ID;
			*/
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

	public function get_statutory_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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

	public function insert_statutory($table, $fields, $return_id = FALSE)
	{
		try
		{

			//print_r($fields);die;
			//$fields['employee_id'] = $this->session->userdate('employee_id');
			return $this->insert_data($table, $fields, $return_id);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function update_statutory($table, $fields, $where)
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

	public function delete_statutory($table, $where)
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

}
	
