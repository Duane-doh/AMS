<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Soap_Model extends Main_Model
{

	protected static $table;
	protected static $pk;
	protected static $fk;
	protected static $fields;
	
	private $error_code = 0;
	private $error_message = '';
	private $db_core              = DB_CORE;
	private $tbl_process_stages   = "process_stages";
	private $tbl_process_steps    = "process_steps";
	
	public function add_data(array $data)
	{
		try
		{
			return $this->insert_data(static::$table, self::_filter_values($data), TRUE);
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
			
			self::rollback();
		}
		
		return NULL;
	}
	
	public function add_list(array $list)
	{
		try
		{
			foreach ($list as $data)
			{
				$this->insert_data(static::$table, self::_filter_values($data));
			}
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
			
			self::rollback();
		}
		
		return NULL;
	}
	
	public function get_data($id)
	{
		try
		{
			$where = array(
				static::$agency_employee_id => $id,
			);
			
			return $this->select_one(static::$fields, static::$table, $where);
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
		}
		
		return NULL;
	}
	
	public function get_list($id)
	{
		try
		{
			$where = array(
				static::$pk => $id,
			);
			
			return $this->select_all(static::$fields, static::$table, $where);
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
		}
		
		return NULL;
	}
	
	public function get_list_by_ref($id)
	{
		try
		{
			$where = array(
				static::$fk => $id,
			);
			
			return $this->select_all(static::$fields, static::$table, $where);
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
		}
		
		return NULL;
	}

	public function edit_data(array $data, $id)
	{
		try
		{
			$where = array(
				static::$pk => $id,
			);
			
			$this->update_data(static::$table, self::_filter_values($data), $where);
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
		}
		
		return NULL;
	}
	
	public function get_error_code()
	{
		return $this->error_code;
	}
	
	public function get_error_message()
	{
		return $this->error_message;
	}
	
	public function get_pk_name()
	{
		return static::$pk;
	}

	public function get_request_sub_type_id()
	{
		return static::$type_id;
	}
	
	private static function _filter_values(array $data)
	{
		foreach ($data as $k => $v)
		{
			if (!in_array($k, static::$fields)) unset($data[$k]);
		}
		
		return $data;
	}

	public function get_initial_task($process_id)
	{
		try
		{
			$val = array($process_id);
			
			$query = <<<EOS
				SELECT A.name, B.process_id, B.process_stage_id, B.process_step_id
				FROM $this->db_core.$this->tbl_process_stages A 
				JOIN $this->db_core.$this->tbl_process_steps B ON A.process_id = B.process_id
				WHERE A.process_id = ?
EOS;
	
			return $this->query($query, $val, FALSE);
		}
		catch (Exception $e)
		{
			$this->error_code = $e->getCode();
			$this->error_message = $e->getMessage();
		}
		
		return NULL;
	}
	
}

