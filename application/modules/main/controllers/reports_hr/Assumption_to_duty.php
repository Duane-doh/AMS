<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assumption_to_duty extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $prepared_by = NULL, $reviewed_by = NULL)
	{
		try
		{
			
			$data           = array();

			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'MOVT_APPOINTMENT';
			$appointment    			= $this->rm->get_reports_data($field, $table, $where, TRUE);
			$movt_appointment 			= array();
			foreach($appointment AS $app)
			{
				$movt_appointment[] 	= $app['sys_param_value'];
			}
			
			$data['record']  		= $this->rm->get_assumption_to_duty_details($param, $movt_appointment);
			$data['record']['employ_monthly_salary_by_word'] = number_to_words($data['record']['employ_monthly_salary']);

			// GET ORIGINAL PERSONNEL MOVEMENT
			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'MOVT_ORIGINAL';
			$data['movt_original']    	= $this->rm->get_reports_data($field, $table, $where, FALSE);

			// SIGNATORIES			
			$data['certified_by']  		= $this->cm->get_report_signatory_details($date);
			$data['prepared_by']  		= $this->cm->get_report_signatory_details($prepared_by);
			$data['reviewed_by']  		= $this->cm->get_report_signatory_details($reviewed_by);
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


/* End of file Assumption_to_duty.php */
/* Location: ./application/modules/main/controllers/reports/hr/Assumption_to_duty.php */