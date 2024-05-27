<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_of_salary_adjustment extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $prepared_by=NULL)
	{
		try
		{
			$data = array();

			if($param != 'A')
			{
				$data['sec_record'] 	= $this->rm->get_employee_salary_adjustment($param, $date);
				$data['first_record'] 	= $this->rm->get_first_record_wo_salary_adjustment($param, $date);
			}
			if($param == 'A')
			{
				$data['sec_record'] 	= $this->rm->get_salary_adjustment_per_office($office, $date);
				$employees 				= $data['sec_record'];

				$record = array();
				foreach ($employees as $aRow):
					$emp_id 	= $aRow['employee_id'];
					$record[] 	= $this->rm->get_first_record_wo_salary_adjustment($emp_id, $date);			
				endforeach;			

				$data['first_record'] = $record;
			}

			$data['param'] 				= $param;
			$data['date'] 				= date('Y',strtotime($date));
			
			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($prepared_by);

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

	// GET EMPLOYEES WITH SALARY ADJUSTMENT
	public function get_employees()
	{
		
		$list      = array();
		
		$params    = get_params();
		$id 	   = $params['id'];

		$where_salary_adj  = 'MOVT_SALARY_ADJUSTMENT';
		$salary_adjusted   = $this->rm->get_employees_with_personnel_movt_list($where_salary_adj, $id);

		if(!EMPTY($salary_adjusted))
		{
			foreach ($salary_adjusted as $aRow):
				$list[] = array(
						"value" => $aRow["employee_id"],
						"text" 	=> $aRow["employee_name"]
				);
			endforeach;
		}	
		
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}

}


/* End of file Notice_longevity_pay.php */
/* Location: ./application/modules/main/controllers/reports/hr/Notice_longevity_pay.php */