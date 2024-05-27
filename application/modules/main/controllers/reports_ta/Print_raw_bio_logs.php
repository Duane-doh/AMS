<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_raw_bio_logs extends Main_Controller {
	
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'reports');
	}	
	public function index()
	{
		
		$this->load_employee_dtr();
	}
	public function print_employee_dtr()
	{
		try
		{
			$params = get_params();
			
			$data   = array();		
			$action = $params['action'];
			$id     = $params['id'];
			$salt   = $params['salt'];
			$token  = $params['token'];
			$module = $params['module'];
			RLog::info('PARAMS :'.json_encode($params));
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}	
			
			$table             = $this->reports->tbl_employee_personal_info;
			$where             = array();
			$key               = $this->get_hash_key('employee_id');
			$where[$key]       = $id;
			$data['emp_info']  = $this->reports->get_reports_data(array("*"), $table, $where, FALSE);
			
			$data['time_logs'] = $this->reports->get_raw_bio_logs($data['emp_info']['biometric_pin'], $params['date_from'], $params['date_to']);
			
			$data['date_from'] = format_date($params['date_from']);
			$data['date_to']   = format_date($params['date_to']);

			$this->load->helper('html');
			ini_set('memory_limit', '512M');
			$this->load->library('pdf');
			
			$pdf 	= $this->pdf->load('utf-8', array(210,297));
			
			$html 	= $this->load->view('forms/reports/ta_raw_bio_logs', $data, TRUE);
			$pdf->WriteHTML($html);
			$pdf->Output();
			
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		
	}

}


/* End of file Print_raw_bio_logs.php */
/* Location: ./application/modules/main/controllers/reports_ta/Print_raw_bio_logs.php */