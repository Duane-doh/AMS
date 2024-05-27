<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('dashboard_model', 'dashboard');
	}
	
	public function index()
	{
		try{
			$data = array();
			$resources = array();
			
			$resources['load_css'] = array();
			$resources['load_js'] = array('chart.min');
			
			$this->template->load('dashboard', $data, $resources);
			
			
			
		}
		catch(Exception $e)
		{
			
			
			echo $e->getMessage();
		}	
	}
}


/* End of file Dashboard.php */
/* Location: ./application/modules/budget/controllers/Dashboard.php */