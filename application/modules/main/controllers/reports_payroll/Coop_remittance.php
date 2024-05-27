<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coop_remittance extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data	= array();
			
			$year				= $params['year'];
			$month				= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month			= $year.$month;
			
			$data['deduction']   = $this->rm->get_reports_data(array("deduction_name"), $this->rm->tbl_param_deductions, array("deduction_id" => $params['deduction_type']), FALSE);
			
			$tables = array(
				'main'  	=> array (
					'table' 	=>	$this->rm->tbl_remittances,
					'alias'		=> 'A'
				),
				't1'		=> array (
					'table'		=> $this->rm->tbl_param_remittance_types,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.remittance_type_id = B.remittance_type_id'
				),
				't2'		=> array (
					'table'		=> $this->rm->tbl_remittance_details,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.remittance_id = C.remittance_id'
				),
				't3'		=> array (
					'table'		=> $this->rm->tbl_payout_details,
					'alias'		=> 'D',
					'type'		=> 'LEFT JOIN',
					'condition' => 'C.payroll_dtl_id = D.payroll_dtl_id'
				),
				't4'		=> array (
					'table'		=> $this->rm->tbl_payout_header,
					'alias'		=> 'E',
					'type'		=> 'LEFT JOIN',
					'condition' => 'D.payroll_hdr_id = E.payroll_hdr_id'
				),
				't5'		=> array (
					'table'		=> $this->rm->tbl_employee_personal_info,
					'alias'		=> 'F',
					'type'		=> 'JOIN',
					'condition' => 'E.employee_id = F.employee_id'
				),
				't6'		=> array (
						'table'		=> $this->rm->tbl_employee_deductions,
						'alias'		=> 'G',
						'type'		=> 'JOIN',
						'condition' => 'E.employee_id = G.employee_id AND G.deduction_id = ' . $params['deduction_type']
				)
			);
			
			$select_fields            = array('E.employee_name', 'F.agency_employee_id', 'SUM(D.amount) amount', 'MAX(G.payment_count) pay_num', 'MAX(D.paid_count) ins_num', 'B.remittance_type_name','CONCAT(DATE_FORMAT(A.deduction_start_date,\'%M\'), \', \', DATE_FORMAT(A.deduction_end_date,\'%Y\')) remittance_period', 'E.office_id', 'E.office_name');

			$where                    = array();
			
			$where['A.remittance_type_id'] 	= $params['remittance_type'];
			$where['A.year_month'] 	  		= $year_month;
			$where['D.deduction_id']  		= $params['deduction_type'];
			$where['E.office_id']    		= array($this->rm->get_office_child('', $params['office_list']),array('IN'));
			
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
			
			$group_by                 		= array('E.employee_id');
			$order_by				  		= array('E.office_id' => 'ASC');
			$data['results']         		= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by); 
			
			$data['prepared_by']			= $this->get_signatory_details($params['rem_prepared_by']);
			$data['certified_by']	  		= $this->get_signatory_details($params['rem_certified_by']);
				
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
	
	
	public function get_signatory_details($emp_id)
	{
		// GET EMPLOYEE INFO
		$result						= array();
		$fields						= array("CONCAT(A.first_name, ' ', LEFT(A.middle_name, (1)), '. ', A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name)), IF(ISNULL(C.others_value), '', CONCAT(', ' , C.others_value))) full_name", "B.employ_position_name position",  "B.employ_office_name office") ;
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
		$where['A.employee_id']	= $emp_id;
		$where['B.active_flag']			= YES;
		$result 						= $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, NULL);
		
		return $result;
	}

}


/* End of file Remittance_list_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Remittance_list_per_office.php */