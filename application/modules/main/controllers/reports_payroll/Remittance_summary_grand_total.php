<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remittance_summary_grand_total extends Main_Controller {
	
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
			
			$data               = array();
			$year				= $params['year'];
			$month				= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month			= $year.$month;
			$report_month		= $year.'-'.$month.'-1';
			$data['report_month'] = date('F Y',strtotime($report_month));
			
			// STORES REMITTANCE TYPE NAMES
			$where							= array();
			$where['remittance_type_id'] 	= $params['remittance_type'];
			$fields							= array("remittance_type_name","remit_type_flag");
			$table							= $this->rm->tbl_param_remittance_types;
			$data['remittance_type']		= $this->rm->get_reports_data($fields, $table, $where, FALSE);
			
			// STORES DEDUCTION HEADER
			$where							= array();
			$fields							= array("deduction_id, deduction_name, employer_share_flag");
			$table							= $this->rm->tbl_param_deductions;
			sort($params['deduction_type_multi']);
			foreach($params['deduction_type_multi'] as $deduction_id)
			{
				$where['deduction_id'] 	= $deduction_id;
				$data['deductions'][]	= $this->rm->get_reports_data($fields, $table, $where, FALSE);
			}
			
			
			// COMMON TABLES
			$tables			= array(
					'main'		=> array(
							'table'	=> $this->rm->tbl_remittances,
							'alias' => 'A',
					),
					't1'		=> array(
							'table'		=> $this->rm->tbl_remittance_details,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.remittance_id = B.remittance_id'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias'		=> 'C',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.payroll_dtl_id = C.payroll_dtl_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias'		=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'C.payroll_hdr_id =D.payroll_hdr_id'
					),
					't4'		=> array(
							'table'		=> $this->rm->tbl_param_deductions,
							'alias'		=> 'E',
							'type'		=> 'JOIN',
							'condition'	=> 'C.deduction_id = E.deduction_id'
					)
			);
			
			
			// STORES TABLE HEADERS
			$where 								= array();
			$where['A.remittance_type_id']	 	= $params['remittance_type'];
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
			
			// STORES OFFICES
			$fields								= array("D.office_id", "D.office_name");
			$group_by							= array("D.office_id");
			$data['offices']					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, $group_by);
			
			// STORES RECORD
			$where 								= array();
			$where['A.remittance_type_id'] 		= $params['remittance_type'];
			$where['A.year_month']				= $year_month;
			$fields								= array("C.raw_deduction_id as deduction_id", "D.office_id", "SUM(B.amount) amount", "SUM(C.employer_amount) employer_amount", "C.payroll_hdr_id");

			$group_by							= array("D.office_id");
			$display_share_flag = FALSE;
			$display_total_flag = FALSE;
			$display_label_flag = FALSE;
			$lanscape_flag = FALSE;
			foreach ($data['deductions'] as $deduction){
				$where['C.raw_deduction_id']    = $deduction['deduction_id'];
				$data['results'][]              = $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, $group_by);
			}

			if($data['remittance_type']['remit_type_flag'] == REMIT_ALL)
				$display_share_flag     = TRUE;


			if(count($data['deductions']) > 1 OR $display_share_flag == TRUE)
				$display_total_flag = TRUE;

			if(count($data['deductions']) > 1)
				$display_label_flag = TRUE;

			if(count($data['deductions']) > 1)
				$lanscape_flag = TRUE;

			$data['remit_type_flag'] 	= $data['remittance_type']['remit_type_flag'];
			
			//SIGNATORIES	
			$data['display_share_flag'] = $display_share_flag;
			$data['display_total_flag'] = $display_total_flag;
			$data['display_label_flag'] = $display_label_flag;
			$data['lanscape_flag']      = $lanscape_flag;
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


/* End of file Remittance_summary_grand_total.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Remittance_summary_grand_total.php */