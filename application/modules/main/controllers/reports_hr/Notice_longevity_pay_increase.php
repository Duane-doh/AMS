<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_longevity_pay_increase extends Main_Controller {
	
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
			$data            		   = array();
			$data['records'] 		   = $this->rm->get_employee_longevity_pay_increase_list($param, $date);
			$last_record 			   = end($data['records']);
			$data['effective_date']    = $this->rm->get_employee_longevity_pay_increase_effective_date($last_record['tenure_effective_date']);

			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_LONGI_BODY';
			$data['body']	    	   = $this->rm->get_reports_data($field, $table, $where, FALSE);

			// SIGNATORIES
			$data['certified_by']  	   = $this->cm->get_report_signatory_details($office);
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

	// GET MILESTONE
	public function get_milestone()
	{
		
		$list      = array();
		
		$params    = get_params();
		$id 	   = $params['employee_longevity'];
		$milestone = $this->rm->get_employee_milestone($id);
		
		if(!EMPTY($milestone))
		{
			foreach ($milestone as $aRow):
				$list[] = array(
						"value" => $aRow["lp_num"],
						"text" 	=> $aRow["lp_num"]
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


/* End of file Notice_longevity_pay_increase.php */
/* Location: ./application/modules/main/controllers/reports/hr/Notice_longevity_pay_increase.php */