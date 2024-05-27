<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_model extends SYSAD_Model {
                
	var $process_table = "process";
	var $user_table = "users";
	
	public function get_workflows($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array();
			$cColumns = array("A-process_id", "A-name", "A-description", "A-num_stages", "CONCAT(B-fname, ' ', B-lname) as creator");
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->process_table A, $this->user_table B
				WHERE A.created_by = B.user_id 
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			$stmt = $this->query($query, $val);
			
			return $stmt;
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}
	}
	
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_workflows($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}
	
	
	public function total_length()
	{
		try
		{
			$fields = array("COUNT(process_id) cnt");
			
			return $this->select_one($fields, $this->process_table);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}
			
}