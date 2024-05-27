<?php if( ! defined('BASEPATH') ) exit('No direct script allowed');

class Auth_settings extends SYSAD_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('settings_model');
	}
	
	public function get_constraints()
	{
		try
		{
			
		}
		catch(PDOException $e)
		{
			throw new PDOException($e->getMessage());
		}
	}
	
}