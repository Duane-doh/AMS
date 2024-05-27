<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_of_salary_step_increment extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data            		= array();			
			$personnel_movt 		= MOVT_STEP_INCR;
			$data['sec_record'] 	= $this->rm->get_employee_salary_step_adjustment($param, $personnel_movt);
			$data['first_record'] 	= $this->rm->get_first_record_wo_salary_step_adjustment($param, $personnel_movt);

			$data['step_incr_reason'] = ($data['sec_record']['step_incr_reason_code'] == SALARY_INCR_CAUSE_MI) ? 'merit increase' : 'completion of post graduate degree';

			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_SALARY_STEP_BODY';
			$data['body']	    	   = $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($office);

			//CC FOOTER
			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'HR_NOTICE_CC';
			$data['cc']      			= $this->rm->get_reports_data($field, $table, $where, FALSE);
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


/* End of file Notice_of_salary_adjustment.php */
/* Location: ./application/modules/main/controllers/reports/hr/Notice_of_salary_adjustment.php */