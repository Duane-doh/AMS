<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends SYSAD_Model {
                
	//protected static $dsn = SYSAD_DB;
	
	public function __construct()
	{
		
		parent::__construct();
	}
	
	public function select()
	{
		
		return $this->select_one("*", "actions");
	}
	
	public function insert($val)
	{
		try
		{		
			
			
			
			$this->insert_data('actions', $val, TRUE);
			
			
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
	
}