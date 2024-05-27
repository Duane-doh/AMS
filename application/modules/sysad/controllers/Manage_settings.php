<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_settings extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('site_settings_model', 'settings', TRUE);
	}
	
	public function index()
	{
		try{
			$data = array();
			$resources = array();
			
			$this->template->load('manage_settings', $data, $resources);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}	
	}
}