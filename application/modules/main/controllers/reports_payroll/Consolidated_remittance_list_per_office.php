<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consolidated_remittance_list_per_office extends Main_Controller {
	
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
			
			$data			= array();
			$year			= $params['year'];
			$month			= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month		= $year.$month;
			$data['month_text']	= $params['month_text'];
			$data['year']		= $year;

			$field                    	= array("remit_type_flag", "remittance_type_name");
			$table                    	= $this->rm->tbl_param_remittance_types;
			$where                    	= array();
			$where['remittance_type_id']= $params['remittance_type'];
			$remit_type_flag      		= $this->rm->get_reports_data($field, $table, $where, FALSE);
			$data['remit_type_flag'] 	= $remit_type_flag['remit_type_flag'];
			$data['remittance_type_name'] 	= $remit_type_flag['remittance_type_name'];
			
			$fields			= array('D.employee_id', 'E.agency_employee_id', 'D.employee_name', 'B.payroll_dtl_id', 'SUM(B.amount) amount', 'SUM(C.employer_amount) employer_amount', 'C.paid_count', '(SELECT payment_count FROM employee_deductions H WHERE C.deduction_id = H.deduction_id AND D.employee_id = H.employee_id) payment_count');
			$tables 		= array(
				'main'      => array(
				'table'     => $this->rm->tbl_remittances,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_remittance_details,
				'alias'     => 'B',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.remittance_id = B.remittance_id'
			 	),
			 	't3'        => array(
				'table'     => $this->rm->tbl_payout_details,
				'alias'     => 'C',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
			 	),
			 	't4'      => array(
				'table'     => $this->rm->tbl_payout_header,
				'alias'     => 'D',
				'type'      => 'JOIN',
				'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
				),
			 	't5'      => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'E',
				'type'      => 'JOIN',
				'condition' => 'E.employee_id = D.employee_id'
				)
			);

			$where								= array();
			$where['A.remittance_type_id'] 		= $params['remittance_type'];
			$where['A.year_month']				= $year_month;
			
			foreach($params['payroll_type_rem'] as $pt)
			{
				if(count($params['payroll_type_rem'])>1)
				{
					if($pt == reset($params['payroll_type_rem']))
					{
						$where['OR'][]['A.payroll_type_ids'] = array( '%' . $pt . '%', array("LIKE", "OR", "("));
					}
					else if($pt == end($params['payroll_type_rem']))
					{
						$where['OR'][]['A.payroll_type_ids'] = array( '%' . $pt . '%', array("LIKE", ")"));
					}else{
						$where['OR'][]['A.payroll_type_ids'] = array('%' . $pt . '%', array("LIKE", "OR"));
					}
				}else{
					$where['A.payroll_type_ids']	= array( '%' . $pt . '%', array("LIKE"));
				}
				
			}
			if(!EMPTY($params['office_list']))
			{
				$data['office_list'] 	  = $params['office_list'];
				$where['D.office_id']     = array($this->rm->get_office_child('', $params['office_list']),array('IN'));
			}

			$group_by					  = array('D.employee_id', 'C.reference_text');	
			$order_by				  	  = array('D.employee_name' => 'ASC');		
			$results 			  		  = $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
			$data['results'] 			  = $results;

			//SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($params['rem_certified_by']);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($params['rem_prepared_by']);
			$agency						=  $this->rm->get_agency_info($params['office_list']);

			$data['office_name'] 		= !EMPTY($agency) ? $agency['name'] : 'DEPARTMENT OF HEALTH - CENTRAL OFFICE';

			$employees 			= array();
			foreach($results AS $rec)
			{
				$employees[] 	= $rec['employee_id'];
			}
			// GET DEDUCTION IDS BASED ON REMITTANCE TYPE SELECTED
			$field                    	= array("deduction_id");
			$table                    	= $this->rm->tbl_param_remittance_type_deductions;
			$where                    	= array();
			$where['remittance_type_id']= $params['remittance_type'];
			$deduction_id       		= $this->rm->get_reports_data($field, $table, $where, TRUE);

			$deduction_ids 			= array();
			foreach($deduction_id AS $deduc)
			{
				$deduction_ids[] 	= $deduc['deduction_id'];
			}
			// GET OTHER DETAIL VALUE	
			$other_ded_dtl_val  		= $this->rm->get_other_ded_dlt_value($employees, $deduction_ids);
			$data['other_ded_dtl_val']  = $other_ded_dtl_val;

			$detail_name 				= $this->rm->get_other_ded_dlt_name($deduction_ids);
			$deduction_flag 			= array();
			$other_detail_name 			= array();
			foreach($detail_name AS $dtl)
			{
				if($dtl['other_detail_name'])
				{
					$other_detail_name[] = $dtl['other_detail_name'];
				}
			}
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


/* End of file Consolidated_remittance_list_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Consolidated_remittance_list_per_office.php */