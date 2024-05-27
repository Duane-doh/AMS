<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_of_step_increment extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data           		= array();
			
			$personnel_movt 		= MOVT_STEP_INCR;
			$data['sec_record'] 	= $this->rm->get_employee_step_incr($param, $personnel_movt);
			$data['first_record'] 	= $this->rm->get_first_record_wo_step_incr($param, $personnel_movt);
			
			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($office);

			// BODY HEADER
			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_STEP_BODY';
			$data['body']	    	   = $this->rm->get_reports_data($field, $table, $where, FALSE);

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


/* End of file Notice_of_step_increment.php */
/* Location: ./application/modules/main/controllers/reports/hr/Notice_of_step_increment.php */