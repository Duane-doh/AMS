<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remittance_summary_per_office extends Main_Controller {
	
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
			
			$data     	= array();
			$tables		= array(
				'main'      => array(
				'table'     => $this->rm->tbl_param_remittance_types,
				'alias'     => 'A'
				),
				't1'        => array(
				'table'     => $this->rm->tbl_remittances,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.remittance_type_id = B.remittance_type_id'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_remittance_details,
				'alias'     => 'C',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.remittance_id = C.remittance_id'
				),
				't3'        => array(
				'table'     => $this->rm->tbl_payout_details,
				'alias'     => 'D',
				'type'      => 'LEFT JOIN',
				'condition' => 'C.payroll_dtl_id = D.payroll_dtl_id'
				),
				't4'        => array(
				'table'     => $this->rm->tbl_payout_header,
				'alias'     => 'E',
				'type'      => 'LEFT JOIN',
				'condition' => 'D.payroll_hdr_id = E.payroll_hdr_id'
				),
				't5'        => array(
				'table'     => $this->rm->tbl_param_deductions,
				'alias'     => 'F',
				'type'      => 'LEFT JOIN',
				'condition' => 'D.deduction_id = F.deduction_id'
				)
			);

			$select_fields             = array('F.deduction_name','A.remittance_type_name','A.remit_type_flag', 'SUM(C.amount) amount', 'SUM(D.employer_amount) employer_amount', 'E.office_name', 'DATE_FORMAT(B.deduction_start_date,\'%M %Y\') remittance_period');
			
			$where                     = array();
			$where['A.active_flag']    = 'Y';
			
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
			
			$where["CONCAT(DATE_FORMAT(B.deduction_start_date,'%M %d'), ' - ', DATE_FORMAT(B.deduction_end_date,'%d, %Y'))"] = $params['remittance_period_text'];

			if($params['office_list'])
			$where['E.office_id']          = array($this->rm->get_office_child('', $params['office_list']),array('IN'));

			$where['A.remittance_type_id'] = array($params['remittance_type_multiple'],array('IN'));
			$group_by                  = array('D.deduction_id');
			
			$data['records']           = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array(), $group_by); 

			$field                    	= array("remit_type_flag");
			$table                    	= $this->rm->tbl_param_remittance_types;
			$where                    	= array();
			$where['remittance_type_id']= $params['remittance_type'];
			$remit_type_flag      		= $this->rm->get_reports_data($field, $table, $where, FALSE);
			$data['display_office_flag'] = ($params['office_list']) ? TRUE : FALSE;
			//SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($params['rem_certified_by']);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($params['rem_prepared_by']);
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


/* End of file Remittance_summary_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Remittance_summary_per_office.php */