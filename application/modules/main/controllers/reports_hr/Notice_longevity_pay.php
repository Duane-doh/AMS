<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_longevity_pay extends Main_Controller {
	
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
			$data            		= array();
			$records				= $this->rm->get_employee_longevity_pay_list($param);
			$data['records'] 		= $records;
			$first_record 			= end($records);
			$min_start_date 		= $first_record['start_date'];
			$employee_id 			= $first_record['employee_id'];

			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_LONGI_BODY';
			$data['body']	    	   = $this->rm->get_reports_data($field, $table, $where, FALSE);

			$field                    	= array("DISTINCT(effectivity_date)");
			$table                    	= $this->rm->tbl_param_salary_schedule;
			$where                    	= array();
			$where['amount']  			= $first_record['basic_amount'];
			$where['salary_grade']  	= $first_record['salary_grade'];
			$where['salary_step']  		= $first_record['salary_step'];
			$fiscal_year      			= $this->rm->get_reports_data($field, $table, $where, FALSE);
			$data['fy'] 				= date('Y',strtotime($fiscal_year['effectivity_date']));
		
			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($office);
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


/* End of file Notice_longevity_pay.php */
/* Location: ./application/modules/main/controllers/reports/hr/Notice_longevity_pay.php */