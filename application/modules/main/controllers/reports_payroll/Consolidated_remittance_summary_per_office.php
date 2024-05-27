<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consolidated_remittance_summary_per_office extends Main_Controller {
	
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
			$data				= array();
			$year				= $params['year'];
			$month				= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month			= $year.$month;
			$data['month_text']	= $params['month_text'];
			$data['year']		= $year;

			$field                    	= array("remit_type_flag", "remittance_type_name");
			$table                    	= $this->rm->tbl_param_remittance_types;
			$where                    	= array();
			$where['remittance_type_id']= $params['remittance_type'];
			$remit_type_flag      		= $this->rm->get_reports_data($field, $table, $where, FALSE);
			$remit_flag 				= $remit_type_flag['remit_type_flag'];
			$data['remittance_type_name'] 	= $remit_type_flag['remittance_type_name'];
			
			// STORES DEDUCTION HEADER
			$where							= array();
			$fields							= array("deduction_id, deduction_name, report_short_code");
			$table							= $this->rm->tbl_param_deductions;
			sort($params['deduction_type_multi']);
			foreach($params['deduction_type_multi'] as $deduction_id)
			{
				$where['deduction_id'] 	= $deduction_id;
				$data['columns'][]	= $this->rm->get_reports_data($fields, $table, $where, FALSE);
			}
				
			// COMMON TABLES
			$tables 		= array(
					'main'      => array(
							'table'     => $this->rm->tbl_param_remittance_type_deductions,
							'alias'     => 'A',
					),
					't2'      => array(
							'table'     => $this->rm->tbl_remittances,
							'alias'     => 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.remittance_type_id = B.remittance_type_id'						
					),
					't3'        => array(
							'table'     => $this->rm->tbl_remittance_details,
							'alias'     => 'C',
							'type'      => 'JOIN',
							'condition' => 'B.remittance_id = C.remittance_id'
					),
					't4'        => array(
							'table'     => $this->rm->tbl_payout_details,
							'alias'     => 'D',
							'type'      => 'JOIN',
							'condition' => 'C.payroll_dtl_id = D.payroll_dtl_id AND A.deduction_id = D.deduction_id'
					),
					't5'      => array(
							'table'     => $this->rm->tbl_payout_header,
							'alias'     => 'E',
							'type'      => 'JOIN',
							'condition' => 'D.payroll_hdr_id = E.payroll_hdr_id'
					),
					't6'      => array(
							'table'     => $this->rm->tbl_param_deductions,
							'alias'     => 'F',
							'type'      => 'JOIN',
							'condition' => 'A.deduction_id = F.deduction_id'
					),
					't7'      => array(
							'table'     => $this->rm->tbl_employee_personal_info,
							'alias'     => 'G',
							'type'      => 'JOIN',
							'condition' => 'E.employee_id = G.employee_id'
					)
			);
			
			// COMMON WHERE
			$where								= array();
			$where['A.remittance_type_id'] 		= $params['remittance_type'];
			
			foreach($params['payroll_type_rem'] as $pt)
			{
				if(count($params['payroll_type_rem'])>1)
				{
					if($pt == reset($params['payroll_type_rem']))
					{
						$where['OR'][]['B.payroll_type_ids'] = array( '%' . $pt . '%', array("LIKE", "OR", "("));
					}
					else if($pt == end($params['payroll_type_rem']))
					{
						$where['OR'][]['B.payroll_type_ids'] = array( '%' . $pt . '%', array("LIKE", ")"));
					}else{
						$where['OR'][]['B.payroll_type_ids'] = array('%' . $pt . '%', array("LIKE", "OR"));
					}
				}else{
					$where['B.payroll_type_ids']	= array( '%' . $pt . '%', array("LIKE"));
				}
				
			}
			

			if(!EMPTY($params['office_list']))
			{
				$data['office_list'] 	  = $params['office_list'];
				$where['E.office_id']     = array($this->rm->get_office_child('', $params['office_list']),array('IN'));
			}

			$where['B.year_month']		= $year_month;
			//$where['E.office_id']		= $params['office_list'];
			$where['D.deduction_id']	= array($params['deduction_type_multi'], array("IN"));
			
			// GET EMPLOYEE ID AND NAME (ROW)
			$fields						= array('G.agency_employee_id employee_id', 'E.employee_name', 'F.deduction_id', 'E.employee_id emp_id');
			$group_by					= array('G.employee_id');
			$order_by				  	= array('E.employee_name' => 'ASC');	
			$data['rows']	 			= $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
			
			$employees 			= array();
			foreach($data['rows'] AS $rec)
			{
				$employees[] 	= $rec['emp_id'];
			}

			// GET OTHER DETAIL VALUE	
			$deduction_ids     			= $params['deduction_type_multi'];
			$other_ded_dtl_val  		= $this->rm->get_other_ded_dlt_value($employees, $deduction_ids);
			$data['other_ded_dtl_val']  = $other_ded_dtl_val;

			// GET REPORT DATA
			$fields						= array('G.agency_employee_id employee_id', 'E.employee_name', 'A.deduction_id', 'SUM(C.amount) amount', 'SUM(D.employer_amount) employer_amount');
			$group_by					= array('E.employee_id', 'D.deduction_id');
			$order_by				  	= array('E.employee_name' => 'ASC');		
			$results		 			= $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
			
			$data['results']			= $this->group_results($data['columns'], $data['rows'], $results, $remit_flag, $other_ded_dtl_val);

			//SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($params['rem_certified_by']);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($params['rem_prepared_by']);
			$agency						=  $this->rm->get_agency_info($params['office_list']);

			$data['office_name'] 		= !EMPTY($agency) ? $agency['name'] : 'DEPARTMENT OF HEALTH - CENTRAL OFFICE';
		
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
	
	public function group_results($columns, $rows, $results, $remit_flag, $other_ded_dtl_val){
		$data = array();
		$ctr = 0;
	
		foreach ($rows as $row)
		{
			$data[$ctr][0] = $row['employee_id'];
			$data[$ctr][1] = $row['employee_name'];
			$data[$ctr][2] = '';
			foreach ($other_ded_dtl_val as $dtl)
			{
				if($row['emp_id'] == $dtl['employee_id'])
				{
					$data[$ctr][2] = !EMPTY($dtl['other_deduction_detail_value']) ? $dtl['other_deduction_detail_value'] : '';
				}
			}

			$ctr2 = 3;
			foreach ($columns as $col){
				foreach($results as $res){
					if(($res['employee_id'] == $row['employee_id']) && ($res['deduction_id'] == $col['deduction_id'])){
						if($remit_flag == REMIT_ALL)
						{
							$data[$ctr][$ctr2] = $res['amount'] + $res['employer_amount'];
						}
						else if($remit_flag == REMIT_PERSONAL_SHARE)
						{
							$data[$ctr][$ctr2] = $res['amount'];
						}
						else
						{
							$data[$ctr][$ctr2] = $res['employer_amount'];
						}
						break;
					}else{
						$data[$ctr][$ctr2] = 0;
						continue;
					}
				}
				$ctr2++;
			}
			$ctr++;
		}
	
		
		return $data;
	}

}


/* End of file Consolidated_remittance_summary_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Consolidated_remittance_summary_per_office.php */