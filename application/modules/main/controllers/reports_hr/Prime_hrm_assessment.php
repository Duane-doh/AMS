<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prime_hrm_assessment extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
	}	
	
	public function generate_report_data($param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data            		  = array();

			$date_arr 				  = explode('A', $date);
			$date_from 				  = $date_arr[0];
			$date_to 				  = $date_arr[1];

			$field                    = array('sys_param_value');
			$table                    = $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    = array();
			$where['sys_param_type']  = 'AGENCY_HEAD_ID';
			$agency_head       	  	  = $this->rm->get_reports_data($field, $table, $where, FALSE); 
			$agency_head_id 		  = $agency_head['sys_param_value'];

			$field                    = array('sys_param_value');
			$table                    = $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    = array();
			$where['sys_param_type']  = 'HRM_OFFICER_ID';
			$hrm_officer       	  	  = $this->rm->get_reports_data($field, $table, $where, FALSE); 
			$hrm_officer_id 		  = $hrm_officer['sys_param_value'];
			
			$data['agency_head'] 	  = $this->rm->get_certified_by_info($agency_head_id);
			$data['hrm_officer'] 	  = $this->rm->get_certified_by_info($hrm_officer_id);
			$data['records'] 		  = $this->rm->get_prime_hrm_assessment_record($date_from, $date_to);

		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}	

		return $data;			
	}

}


/* End of file Prime_hrm_assessment.php */
/* Location: ./application/modules/main/controllers/reports/hr/Prime_hrm_assessment.php */