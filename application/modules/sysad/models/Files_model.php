<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files_model extends SYSAD_Model {
                
	var $file_table = "file";
	var $file_version_table = "file_versions";
	var $core_db = SYSAD_DB;
	
	public function __construct() {
		parent::__construct(); 
	}
	
	public function get_files($order_arr = array())
	{
		try
		{
			$order_by = "";
			
			if(!EMPTY($order_arr)){
				$order_by .= " ORDER BY ";
					
				foreach ($order_arr as $a => $b):
					$order_by .= $a." ".$b.", ";
				endforeach;

				$order_by = rtrim($order_by, ", ");
			}else{
				$order_by = " ORDER BY created_date DESC";
			}
			$query = <<<EOS
				SELECT *
				FROM $this->file_table
				$order_by
EOS;
			$stmt = $this->query($query);
			
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
	
	public function get_file_details($file_id)
	{
		try
		{
			$where = array();
			
			$fields = array("*");
			$where["file_id"] = $file_id;
				
			return $this->select_all($fields, $this->file_table, $where);
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
	
	public function get_file_version_details($file_version_id = NULL, $file_id = NULL)
	{
		try
		{
			$where = array();
			
			$fields = array("*");
			
			if(!IS_NULL($file_version_id))
				$where["file_version_id"] = $file_version_id;
			
			if(!IS_NULL($file_id))
				$where["file_id"] = $file_id;
				
			return $this->select_all($fields, $this->file_version_table, $where);
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
	
	
	public function get_file_versions($file_id)
	{
		try
		{
			$val = array();
			$val[] = $file_id;
			$val[] = $file_id;
			
			$query = <<<EOS
				SELECT file_id, file_version_id, file_name, display_name, version
				FROM $this->file_version_table
				WHERE file_id = ?
				UNION
				SELECT file_id, NULL id, file_name, display_name, 1 version
				FROM $this->file_table
				WHERE file_id = ?
				ORDER BY version DESC
EOS;
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
	
	public function insert_files($file, $params)
	{
		try
		{
			$val = array();
			
			$val["cy"] = filter_var($params['file_budget_year'], FILTER_SANITIZE_NUMBER_INT);
			$val["file_name"] = filter_var($file, FILTER_SANITIZE_STRING);
			$val["display_name"] = filter_var($file, FILTER_SANITIZE_STRING);
			$val["description"] = filter_var($params['file_description'], FILTER_SANITIZE_STRING);
			$val["created_by"] = $this->session->userdata("user_id");
			$val["created_date"] = date('Y-m-d H:i:s');
			
			$file_id = $this->insert_data($this->file_table, $val, TRUE);
			
			return $file_id;
			
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
	
	public function update_files($params)
	{
		try
		{
			$val = array();
			$where = array();
			
			$is_versioned = !EMPTY($params['file_version_id']) ? 1 : 0;
			$table = ($is_versioned) ? $this->file_version_table : $this->file_table;
			
			$val["display_name"] = filter_var($params['file_display_name'], FILTER_SANITIZE_STRING);
			$val["description"] = filter_var($params['file_description'], FILTER_SANITIZE_STRING);
			$val["modified_by"]	= $this->session->userdata("user_id");
			
			if(!$is_versioned){
				$val["cy"] = filter_var($params['file_budget_year'], FILTER_SANITIZE_NUMBER_INT);
			}else{
				$where["file_version_id"] = filter_var($params["file_version_id"], FILTER_SANITIZE_NUMBER_INT);
			}
			
			$where["file_id"] = filter_var($params["id"], FILTER_SANITIZE_NUMBER_INT);
			
			$this->update_data($table, $val, $where);
			
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
	
	public function delete_file($id, $file_version_id = NULL)
	{
		try
		{
			$where = array();
				
			$where['file_id'] = $id;
			$table = $this->file_table;
			
			if(!IS_NULL($file_version_id)){
				$where['file_version_id'] = $file_version_id;
				$table = $this->file_version_table;
			}
				
			$this->delete_data($table, $where);
				
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
	
	public function insert_file_version($params)
	{
		try
		{
			$val = array();
			
			$version = ISSET($params['minor_revision_flag']) ? 0.1 : 1;
			$id = $params['file_id'];
			$data = $this->get_latest_attachments($id);
			$version += ISSET($params['minor_revision_flag']) ? $data['version'] : floor($data['version']);
			
			$val["file_id"] = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$val["file_name"] = filter_var($params['file_version'], FILTER_SANITIZE_STRING);
			$val["display_name"] = filter_var($params['file_version'], FILTER_SANITIZE_STRING);
			$val["description"] = filter_var($params['file_version_description'], FILTER_SANITIZE_STRING);
			$val["minor_revision_flag"] = ISSET($params['minor_revision_flag']) ? $params['minor_revision_flag'] : 0;
			$val["version"] = $version;
			$val["created_by"] = $this->session->userdata("user_id");
			$val["created_date"] = date('Y-m-d H:i:s');
			
			$file_version_id = $this->insert_data($this->file_version_table, $val, TRUE);
			
			return $file_version_id;
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
	
	public function get_latest_attachments($file_id = NULL, $file_version_id = NULL, $order_arr = array())
	{
		try
		{
			$val = array();
			
			$where = "1";
			
			if(!IS_NULL($file_id)){
				$where.= " AND A.file_id = ? ";
				$val[] = $file_id;
			}
			
			if(!IS_NULL($file_version_id)){
				$where.= " AND B.file_version_id = ? ";
				$val[] = $file_version_id;
			}
			
			$order_by = "";
			
			if(!EMPTY($order_arr)){
				$order_by .= " ORDER BY ";
					
				foreach ($order_arr as $a => $b):
					$order_by .= $a." ".$b.", ";
				endforeach;

				$order_by = rtrim($order_by, ", ");
			}else{
				$order_by = " ORDER BY created_date DESC";
			}
			
			$query = <<<EOS
				SELECT A.file_id, B.file_version_id, IF(B.version IS NULL, 1, B.version) version, 
					CONCAT(C.fname, ' ', C.lname) created_by,
					IF(B.version IS NULL, A.created_date, B.created_date) created_date,
					IF(B.version IS NULL, A.file_name, B.file_name) file_name,
					IF(B.version IS NULL, A.display_name, B.display_name) display_name,
					IF(B.version IS NULL, A.description, B.description) description
				FROM file A
				LEFT JOIN file_versions B ON A.file_id = B.file_id
					AND B.version = (SELECT MAX(version) FROM file_versions WHERE file_id = A.file_id)
				INNER JOIN users C ON ((IF(B.version IS NULL, A.created_by, B.created_by)) = C.user_id)
				WHERE $where
				$order_by
EOS;
			// $stmt = $this->db->query($query, $val);
		
			if(IS_NULL($file_id)){
				$stmt = $this->query($query, $val);
			}else{
				$stmt = $this->query($query, $val, FALSE);
			}
			
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
			
}