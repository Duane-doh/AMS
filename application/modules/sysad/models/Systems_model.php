<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systems_model extends SYSAD_Model {
                
	var $system_table = "systems";
	
	public function get_systems()
	{
		try
		{
			$where = array();
			
			$fields = array("system_code", "system_name");
			$where["on_off_flag"] = SYSTEM_ON;
				
			return $this->select_all($fields, $this->system_table, $where);
		}	
		catch(PDOException $e)
		{
			throw $e;
		}
	}
		
}