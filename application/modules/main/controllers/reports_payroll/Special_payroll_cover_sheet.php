<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll_cover_sheet extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$year			= $params['year'];
			$month			= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month		= $year.$month;
				
			$data['year']	= $params['year'];
			
			//GET COMPENSATION NAME
			$field						= array("compensation_name");
			$table 						= $this->rm->tbl_param_compensations;
			$where						= array();
			$where['compensation_id']	= $params['compensation_special_type'];
			$data['compensation_name'] 	= $this->rm->get_reports_data($field, $table, $where, FALSE, NULL);
			
			// GET DATAS
			$tables 				= array(
					'main'		=> array(
							'table'	=> $this->rm->tbl_payout_summary,
							'alias'	=> 'A'
					),
					't2'		=> array(
							'table'	=> $this->rm->tbl_payout_summary_dates,
							'alias'		=> 'B',
							'type'  	=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payout_summary_id'
					),
					't3'		=> array(
							'table'	=> $this->rm->tbl_payout_header,
							'alias'		=> 'C',
							'type'  	=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = C.payroll_summary_id'
					)
			);
			$fields		= array('A.approved_by', 'A.certified_by', 'A.certified_cash_by', 'count(C.employee_id) emp_count');
			$where												= array();
			$where['A.compensation_id']							= $params['compensation_special_type'];
			$where['EXTRACT(YEAR_MONTH FROM B.effective_date)']	= $year_month;
			$group_by											= array('C.employee_id');
			$data['results']									= $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL);
			
			// GET EMPLOYEE INFO
			
			if($params['signatory_a'])
				$data['signatory_a']			= $this->cm->get_report_signatory_details($params['signatory_a']);

			if($params['signatory_b'])
				$data['signatory_b']			= $this->cm->get_report_signatory_details($params['signatory_b']);

			if($params['signatory_c'])
				$data['signatory_c']			= $this->cm->get_report_signatory_details($params['signatory_c']);

			if($params['signatory_d'])
				$data['signatory_d']			= $this->cm->get_report_signatory_details($params['signatory_d']);
			
			//GET DEDUCTION CODES AND DEDUCTION NAMES AS LEGENDS
			$where							 = array();
			$field                           = array('deduction_code','deduction_name');
			$table                           = $this->rm->tbl_param_deductions;
			$data['deductions']		         = $this->rm->get_reports_data($field, $table, $where);
			
				
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
	
	public function get_employee_info($emp_id){
		// GET EMPLOYEE INFO
		$result						= array();
		$fields						= array("CONCAT(A.first_name, ' ', LEFT(A.middle_name, (1)), '. ', A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name)), IF(ISNULL(C.others_value), '', CONCAT(', ' , C.others_value))) full_name", "B.employ_position_name position") ;
		$tables						= array(
			'main' =>		array( 
					'table' => $this->rm->tbl_employee_personal_info,
					'alias'	=> 'A'
			),
			't2'		=> array(
					'table'	=> $this->rm->tbl_employee_work_experiences,
					'alias'		=> 'B',
					'type'  	=> 'JOIN',
					'condition'	=> 'A.employee_id = B.employee_id'
			),
			't3'		=> array(
					'table'	=> $this->rm->tbl_employee_other_info,
					'alias'		=> 'C',
					'type'  	=> 'LEFT JOIN',
					'condition'	=> 'A.employee_id =  C.employee_id AND C.other_info_type_id = ' . OTHER_INFO_TYPE_TITLE
			)
		);
		$where						= array();
			
		// APPROVED BY
		$where['A.employee_id']		= $emp_id;
		$where['B.active_flag']		= YES;
		$result 					= $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, NULL);
		
		return $result;
	}

	
	public function get_payroll_period_text($month, $year){
		$num_days 	= cal_days_in_month (CAL_GREGORIAN, $month, $year);
		
		$date_obj   = DateTime::createFromFormat('!m', $month);
		$month_name = $date_obj->format('F');
		
		$payroll_date_text = $month_name . " 1 - " . $num_days . ", " . $year;
		return $payroll_date_text;
	}

}


/* End of file Special_payroll_cover_sheet.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Special_payroll_cover_sheet.php */