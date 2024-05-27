<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_payroll_alpha_list_per_office extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			
			$data                  = array();
			
			$office_list = $this->rm->get_office_child('', $params['office_list']);
			$field    = array("DATE_FORMAT(A.date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(A.date_to,'%m/%d/%Y') as date_to", "C.office_name");

			$table    = array(
				'main'		=> 		array(
					'table'		=> 	$this->rm->tbl_attendance_period_hdr,
					'alias'		=>  'A'
				),
				't1'		=> 		array(
					'table'		=>  $this->rm->tbl_payout_summary,
					'alias'		=>  'B',
					'type'		=>	'JOIN',
					'condition' =>	'A.attendance_period_hdr_id = B.attendance_period_hdr_id'
				),
				't2'		=>		array(
					'table'		=>  $this->rm->tbl_payout_header,
					'alias'		=>	'C',
					'type'		=>	'JOIN',
					'condition' =>	'B.payroll_summary_id = C.payroll_summary_id'
				)
			);
			$where                               = array();
			$where["A.attendance_period_hdr_id"] = $params['payroll_period'];
			$where['C.office_id']                = array($office_list,array('IN'));

			$data['period_detail']				 = $this->rm->get_reports_data($field, $table, $where, FALSE);

			// COMPENSATIONS CLUSTERING

			$fields = array('GROUP_CONCAT(sys_param_value) AS sys_param_values,sys_param_name');
			$table = $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where = array('sys_param_type' => REPORT_GENPAY_ALPHALIST_OFFICE);
			$group_by = array('sys_param_name');
			$order_by = array('sys_param_value' => 'ASC');

			$sys_par = $this->rm->get_reports_data($fields, $table, $where, TRUE, $order_by, $group_by);
			
			foreach ($sys_par as $key => $sys) {
				$sys_par[$key] = explode(',', $sys['sys_param_values']);
			}
			
			// COMPENSATIONS
			$tables 		= array(

				'main'      => array(
				'table'     => $this->rm->tbl_param_compensations,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_payout_details,
				'alias'     => 'B',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.compensation_id = B.compensation_id'
			 	),
				't3'        => array(
				'table'     => $this->rm->tbl_payout_header,
				'alias'     => 'C',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
			 	),
				't4'        => array(
				'table'     => $this->rm->tbl_payout_summary,
				'alias'     => 'D',
				'type'      => 'LEFT JOIN',
				'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
			 	), 
				't5'        => array(
				'table'     => $this->rm->tbl_attendance_period_hdr,
				'alias'     => 'E',
				'type'      => 'LEFT JOIN',
				'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
			 	), 
				't6'        => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'F',
				'type'      => 'LEFT JOIN',
				'condition' => 'C.employee_id = F.employee_id'
			 	)		
			);


			$select_fields = array('CONCAT(F.first_name, \' \', LEFT(F.middle_name, 1), \'. \', F.last_name, \' \', F.ext_name) employee_name, F.agency_employee_id, C.position_name,
			C.employee_id, C.office_id, A.compensation_id, D.attendance_period_hdr_id, A.compensation_name, SUM(ifnull(B.amount,0)) as amount');

			$where = array();
			$where['A.general_payroll_flag'] = 'Y';
			$where['A.inherit_parent_id_flag'] = NA;
			$where['D.attendance_period_hdr_id'] = $params['payroll_period'];
			$where['C.office_id'] = array($office_list,array('IN'));

			$compensation_temp = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('C.employee_id' => 'ASC'), array('A.compensation_id,C.employee_id'));
			
			$compensation_records = array();

			foreach ($compensation_temp as $key => $value) {
				$employee_id = $value['employee_id'];

				foreach ($sys_par as $key => $sys) {
					$compensation_records[$employee_id]['amount'][$key] += 0;
					if(in_array($value['compensation_id'], $sys))
						$compensation_records[$employee_id]['amount'][$key] += $value['amount'];
				}
				$compensation_records[$employee_id]['employee_id'] = $value['employee_id'];
				$compensation_records[$employee_id]['employee_name'] = $value['employee_name'];
				$compensation_records[$employee_id]['position_name'] = $value['position_name'];
				$compensation_records[$employee_id]['agency_employee_id'] = $value['agency_employee_id'];
			}

			$data['compensation_records'] = $compensation_records;
			
			//DEDUCTIONS
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
			 	), 
				't5'        => array(
				'table'     => $this->rm->tbl_attendance_period_hdr,
				'alias'     => 'E',
				'type'      => 'JOIN',
				'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
			 	), 
				't7'        => array(
				'table'     => $this->rm->tbl_param_remittance_types,
				'alias'     => 'G',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.remittance_type_id = G.remittance_type_id'
			 	)	, 
				't8'        => array(
				'table'     => $this->rm->tbl_param_remittance_payees,
				'alias'     => 'H',
				'type'      => 'LEFT JOIN',
				'condition' => 'G.remittance_payee_id = H.remittance_payee_id'
			 	)					
			);

			$select_fields  = array('C.employee_id, SUM(ifnull(B.amount,0)) as amount, A.deduction_id, H.remittance_payee_id');
			
			$where = array();
			$where['A.general_payroll_flag'] = 'Y';
			$where['D.attendance_period_hdr_id'] = $params['payroll_period'];
			$where['C.office_id'] = array($office_list,array('IN'));

			$deduction_arr 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('C.employee_id' => 'ASC'), array('C.employee_id,H.remittance_payee_id'));
			
			$table = $this->rm->tbl_param_remittance_payees;
			$fields = array('remittance_payee_id,remittance_payee_name');
			$where = array('active_flag' => YES);

			$data['deduction_headers'] = $this->rm->get_reports_data($fields, $table, $where);
			
			$deduction_records = array();
			$employee_id = 0;
			foreach ($deduction_arr as $key => $val) {
				
				if($employee_id == $val['employee_id']) {
					$deduction_records = $this->set_deductions($deduction_records, $employee_id, $val);
				}else{
					$employee_id = $val['employee_id'];
					$deduction_records = $this->set_deductions($deduction_records, $employee_id, $val);
				}
				
			}
			$data['deduction_records'] = $deduction_records;

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
	
	public function set_deductions($deduction_records, $employee_id, $val)
	{
		if(($val['deduction_id'] == DEDUC_BIR) || ($val['remittance_payee_id'] == DEDUC_BIR)){
			$deduction_records[$employee_id]['bir'] = $deduction_records[$employee_id]['bir'] + $val['amount'];
		}else if(($val['deduction_id'] == DEDUC_GSIS) || ($val['remittance_payee_id'] == DEDUC_GSIS)){
			$deduction_records[$employee_id]['gsis'] = $deduction_records[$employee_id]['gsis'] +  $val['amount'];
		}else if(($val['deduction_id'] == DEDUC_PAGIBIG) || ($val['remittance_payee_id'] == DEDUC_PAGIBIG)){
			$deduction_records[$employee_id]['pagibig'] = $deduction_records[$employee_id]['pagibig'] + $val['amount'];
		}else if(($val['deduction_id'] == DEDUC_PHILHEALTH) || ($val['remittance_payee_id'] == DEDUC_PHILHEALTH)){
			$deduction_records[$employee_id]['philhealth'] = $deduction_records[$employee_id]['philhealth'] + $val['amount'];
		}else{
			$deduction_records[$employee_id]['others'] = $deduction_records[$employee_id]['others'] + $val['amount'];
		}
		
		return $deduction_records;
	}

}


/* End of file General_payroll_alpha_list_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/General_payroll_alpha_list_per_office.php */