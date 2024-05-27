<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adhoc_reports_model extends Main_Model {

	public function __construct() {
		parent:: __construct();
	
	}
	// SCHEMA NAME
	public $db_main                   = DB_MAIN;
	public $db_core                   = DB_CORE;
	public $tbl_users                 = 'users';
	

	public function get_group_list($columns, $filters, $params, $params_where = array(), $count_flag = FALSE, $multiple=TRUE, $group="")
	{
		try {
	
			if(!EMPTY($params['name']))
			$params["CONCAT(C-fname,' ', C-lname)"] = $params['name'];
			if(!EMPTY($params['A-created_date']))
			$params['A-created_date'] = format_date($params['A-created_date'],'Y-m-d');
			$cColumns      = array("A-group_name", "A-created_date", "CONCAT(C-fname,' ', C-lname)");
			
			$fields 	= str_replace(" , ", " ", implode(", ", $columns));
			$filter		= $this->filtering($cColumns, $params, FALSE);
			$order		= $this->ordering($filters, $params);
			$limit		= (!$count_flag) ?  $this->paging($params) : "";
	
			$where		= $filter["search_str"];
			$val		= $filter["search_params"];

			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_table_group_hdr A
				JOIN $this->tbl_table_group_dtl B ON A.group_hdr_id = B.group_hdr_id
				JOIN $this->db_core.$this->tbl_users C ON A.created_by = C.user_id
				LEFT JOIN $this->tbl_download_template_hdr D ON A.group_hdr_id = D.group_hdr_id
				$where
				$group		
    	    	$order	 
				$limit  
EOS;

			return $this->query($query, $val, $multiple);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_download_history($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields          = str_replace(" , ", " ", implode(", ", $aColumns));
			$sOrder          = $this->ordering($bColumns, $params);
			$sLimit          = $this->paging($params);
			$filter_params   = array();
			$key 			 = $this->get_hash_key('download_history_id');
			$filter_params[] = $params['download_history_id']; 

			$query = <<<EOS
				SELECT $fields 
				FROM $table
				WHERE $key = ?
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);

			return $this->query($query, $filter_params, $multiple);

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

	public function get_download_templates($aColumns, $bColumns, $params, $where, $multiple = TRUE)
	{
		try
		{
			if(!EMPTY($params['last_download']))
				$params['last_downloaded_time'] = format_date($params['last_download'], 'Y-m-d');

			$cColumns	   = array('A-name','last_downloaded_time');
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			$group_by      = "GROUP BY reference_no";
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_download_template_hdr

				$filter_str

				$group_by
				$sOrder
				$sLimit
EOS;
			// RLog::debug($query);

			return $this->query($query, $filter_params, $multiple);

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

	public function get_gsis_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("insurance_coverage_type", "personal_share_life", "personal_share_retirement", "gov_share_life", "gov_share_retirement", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY gsis_id
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

	public function update_adhoc_data($table, $fields, $where)
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
	public function insert_template_dtl($table, $params)
	{
		$where = array();
		$where['reference_no']	= $params[0]['reference_no'];

		$this->delete_adhoc_data($table, $where);

		foreach($params AS $r) 
		{
			$this->insert_data($table, $r);
		}
	}
	
	public function delete_adhoc_data($table, $where)
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

	public function get_download_details($fields, $table, $where, $order_by)
	{
		try 
		{
			$columns = str_replace(" , ", " ", implode(", ", $fields));
			$order_by = !EMPTY($order_by) ? " ORDER BY " . $order_by : "";
			
			$query = <<<EOS
				SELECT $columns FROM $table
				$where
				$order_by
EOS;
		
			return $this->query($query);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function execute($script, $val= NULL, $multiple = NULL, $execute = TRUE)
	{
		try 
		{
			return $this->query($script, $val, $multiple, $execute);
		}
		catch(PDOException $e)
		{
			
			throw $e;
		}
	}

	public function insert_adhoc_data($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);
	
		}
		catch(PDOException $e)
		{
			
			$msg = $e->getMessage();
			
			if (strpos($msg,1062))
			{	
				throw new PDOException($msg, 1062);
			}
			else if (strpos($msg,1048))
			{
				throw new PDOException($msg, 1048);
			}
			else 
			{
				throw $e;
			}
		}
	}

	public function get_adhoc_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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

	public function run_query($script, $val= NULL, $multiple = NULL, $execute = TRUE)
	{
		try 
		{
			return $this->query($script, $val, $multiple, $execute);
		}
		catch(PDOException $e)
		{
			if($e->getCode() == '42S02')
				return 0;
			else
			throw $e;
		}
	}
}