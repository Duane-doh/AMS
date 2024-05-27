<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audit_trail_model extends SYSAD_Model {
                
	var $audit_trail_table = "audit_trail";
	var $audit_trail_detail_table = "audit_trail_detail";
	
	public function insert_audit_trail($params = array())
	{
		try
		{
			
			
			$user_id	= $this->session->userdata('user_id');
			$username	= $this->session->userdata('username');
			
			$user_id	= ($user_id === FALSE)? ANONYMOUS_ID : $user_id;
			$username	= ($username === FALSE)? ANONYMOUS_USERNAME : $username;
			
			$val				= array();
			$val["user_id"]		= $user_id;
			$val["username"]	= $username;
			$val["module_id"]	= filter_var($params["module"], FILTER_SANITIZE_NUMBER_INT);
			$val["activity"]	= filter_var($params["activity"], FILTER_SANITIZE_STRING);
			$val["ip_address"]	= $_SERVER['REMOTE_ADDR'];
			$val["user_agent"]	= $this->input->user_agent();
				
			$id = $this->insert_data($this->audit_trail_table, $val, TRUE);

			return $id;
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
		
	public function insert_audit_trail_detail($params)
	{
		try
		{
			$this->insert_data($this->audit_trail_detail_table, $params);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	
		
}

/* End of file audit_trail_model.php */
/*/application/models/audit_trail_model.php*/
