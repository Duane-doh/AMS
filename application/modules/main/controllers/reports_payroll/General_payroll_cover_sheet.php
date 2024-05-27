<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_payroll_cover_sheet extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data                   = array();
			$data['job_order_flag'] = false;
			// GET DATAS
			$table 				= array(
					'main'		=> array(
							'table'	=> $this->rm->tbl_payout_summary,
							'alias'	=> 'A'
					),
					't2'		=> array(
							'table'	=> $this->rm->tbl_payout_header,
							'alias'		=> 'B',
							'type'  	=> 'LEFT JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id'
					)
			);
			$fields		= array('A.attendance_period_hdr_id', 'A.approved_by', 'A.certified_by', 'A.certified_cash_by', 'count(B.employee_id) emp_count');
			$where									= array();
			$where['A.attendance_period_hdr_id']	= $params['payroll_period'];
			$group_by								= array('B.employee_id');
			$data['results']						= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);

			

			/*START: GET EMPLOYMENT TYPE FLAG*/
			$field                    = array("employ_type_flag");		
			
			$table                    = $this->rm->tbl_param_payroll_types;
			$where                    = array();
			$where["payroll_type_id"] = $params['payroll_type'];
			$employ_type_flag         = $this->rm->get_reports_data($field, $table, $where, FALSE);

			/*END: GET EMPLOYMENT TYPE FLAG*/
			if($employ_type_flag['employ_type_flag'] == PAYROLL_TYPE_FLAG_REG)
			{

				// GET PAYOUT DATE
				$table 				= array(
						'main'		=> array(
								'table'	=> $this->rm->tbl_payout_summary,
								'alias'	=> 'A'
						),
						't2'		=> array(
								'table'	=> $this->rm->tbl_payout_summary_dates,
								'alias'		=> 'B',
								'type'  	=> 'LEFT JOIN',
								'condition'	=> 'A.payroll_summary_id = B.payout_summary_id'
						)
				);
				$fields		= array('B.effective_date');
				$where									= array();
				$where['A.attendance_period_hdr_id']	= $params['payroll_period'];
				$payroll_date							= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);


				$month =  date("F", strtotime($payroll_date['effective_date']));
				$year =  date("Y", strtotime($payroll_date['effective_date']));
				$max_date =  date("t", strtotime($payroll_date['effective_date']));

				$data['payroll_date_text']				= $month. ' 01-'.$max_date.', '.$year;
			}
			else
			{
				// GET PAYOUT DATE
				$table                             = $this->rm->tbl_attendance_period_hdr;
				$fields                            = array('date_from','date_to');
				$where                             = array();
				$where['attendance_period_hdr_id'] = $params['payroll_period'];
				$payroll_date                      = $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);


				$month    =  date("F", strtotime($payroll_date['date_from']));
				$year     =  date("Y", strtotime($payroll_date['date_from']));
				$min_date =  date("d", strtotime($payroll_date['date_from']));
				$max_date =  date("d", strtotime($payroll_date['date_to']));

				$data['payroll_date_text'] = $month.' '.$min_date.'-'.$max_date.', '.$year;
				$data['job_order_flag']    = true;
			}
			
			switch ($params['payroll_type']) {
				case 3:
					$data['payroll_type_display'] = " - JO Personnel ";
					break;
				case 4:
					$data['payroll_type_display'] = " - Consultant Personnel ";
					break;
				case 5:
					$data['payroll_type_display'] = " - GIP Personnel ";
					break;
				
				default:
					$data['payroll_type_display'] = " ";
					break;
			}
			// GET PERIOD
			$field						= array("A.attendance_period_hdr_id", "CONCAT(DATE_FORMAT(A.date_from,'%M %d'), ' - ', DATE_FORMAT(A.date_to,'%d, %Y')) payroll_period");
			$table  					= array(
					'main'	=> array(
							'table' =>	$this->rm->tbl_attendance_period_hdr,
							'alias' => 'A'
					),
					't1' 	=> array(
							'table' => $this->rm->tbl_payout_summary,
							'alias' => 'B',
							'type'  => 'JOIN',
							'condition' => 'A.attendance_period_hdr_id = B.attendance_period_hdr_id'
					)
			);
			$where									= array();
			$where['A.attendance_period_hdr_id']	= $params['payroll_period'];
			$group_by								= array("payroll_period");
			$data['payroll_period']					= $this->rm->get_reports_data($field, $table, $where, FALSE, NULL, $group_by);
			
			
			// GET EMPLOYEE INFO
			
			if($params['signatory_a'])
				$data['signatory_a']			= $this->cm->get_report_signatory_details($params['signatory_a']);

			if($params['signatory_b'])
				$data['signatory_b']			= $this->cm->get_report_signatory_details($params['signatory_b']);

			if($params['signatory_c'])
				$data['signatory_c']			= $this->cm->get_report_signatory_details($params['signatory_c']);

			if($params['signatory_d'])
				$data['signatory_d']			= $this->cm->get_report_signatory_details($params['signatory_d']);



			$data['certified_by'] 		= $this->get_employee_info($data['results']['certified_by']);
			$data['approved_by']		= $this->get_employee_info($data['results']['approved_by']);
			$data['certified_cash_by'] 	= $this->get_employee_info($data['results']['certified_cash_by']);
			
			//GET DEDUCTION CODES AND DEDUCTION NAMES AS LEGENDS
			
			$tables 		= array(
			
					'main'      => array(
							'table'     => $this->rm->tbl_param_deductions,
							'alias'     => 'A'
					),
					't2'        => array(
							'table'     => $this->rm->tbl_payout_details,
							'alias'     => 'B',
							'type'      => 'JOIN',
							'condition' => 'A.deduction_id = B.deduction_id'
					),
					't3'        => array(
							'table'     => $this->rm->tbl_payout_header,
							'alias'     => 'C',
							'type'      => 'JOIN',
							'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
					),
					't4'        => array(
							'table'     => $this->rm->tbl_payout_summary,
							'alias'     => 'D',
							'type'      => 'JOIN',
							'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
					)
			);

			$where                               = array();
			$where['A.general_payroll_flag']     = YES;
			$where['A.active_flag']              = YES;
			$where['D.attendance_period_hdr_id'] = $params['payroll_period'];

			/*GET REMITTANCE PAYEES WITH VALUES*/
			$select_fields 				= array('A.report_short_code as deduction_code','A.deduction_name');
			$data['deductions'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('A.deduction_id' => 'ASC'), array('A.deduction_id'));
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
}


/* End of file General_payroll_cover_sheet.php */
/* Location: ./application/modules/main/controllers/reports/payroll/General_payroll_cover_sheet.php */