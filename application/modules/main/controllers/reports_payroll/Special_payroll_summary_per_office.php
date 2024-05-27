<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll_summary_per_office extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			
			$data               = array();
			$year				= $params['year'];
			$month				= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month			= $year.$month;
			
			// STORES SPECIAL COMPENSATION NAME
			$where						= array();
			$where['A.compensation_id']	= $params['compensation_special_type'];
			$where['B.payout_type_flag']= PAYOUT_TYPE_FLAG_SPECIAL;
			$fields						= array("A.compensation_id, A.compensation_name, A.inherit_parent_id_flag");
			
			
			$tables				= array(
					'main'		=> array(
						'table' => $this->rm->tbl_param_compensations,
						'alias' => 'A'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_summary,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.compensation_id = B.compensation_id'
					),
			);
			
			$com_type  					= $this->rm->get_reports_data($fields, $tables, $where, FALSE);
			
			$data['compensation_type']  = $com_type;
			
			// PARAMS
			$data['year'] 				= $params['year'];
			$data['month_text']			= $params['month_text'];
			
			// COMPENSATIONS
			// START
			$where				= array();
			
			$result_compen		= $this->get_compensation_child($com_type['compensation_id']);	
			$result_compen = array_merge($com_type,$result_compen);
			

			$compensations		= array();
			$data['multi_child']= TRUE;

			if($com_type)
			{
				$compensations[] = $com_type;
				if($result_compen)
				{
					foreach ($result_compen as $result)
					{
						$compensations[] = $result;
					}
				}else
				{
					$data['multi_child']		= FALSE;
				}

			}
			
			$inherit_compensation = array();
			$inherit_cnt = 0;			
			foreach ($compensations as $com)
			{
				if($com['compensation_id'] != $com_type['compensation_id']) {
					if($com['inherit_parent_id_flag'] == 'N') {
						$inherit_compensation[$inherit_cnt]['compensation_id'] = $com['compensation_id'];
						$inherit_compensation[$inherit_cnt]['compensation_name'] = $com['compensation_name'];					
					}
					else {
						$inherit_compensation[$inherit_cnt]['compensation_id'] = $com_type['compensation_id'];
						$inherit_compensation[$inherit_cnt]['compensation_name'] = $com_type['compensation_name'];	
						break;
					}
				}					
				$inherit_cnt++;
			}	
			
			$data['compensations'] = $inherit_compensation;		
			
			$where												= array();
			$where['B.raw_compensation_id']						= "IS NOT NULL";
			$fields												= array('A.office_id', 'A.office_name', 'B.compensation_id', 'SUM(B.amount) amount');
			
			$tables 		= array(
					'main'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias'		=> 'A'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_hdr_id = B.payroll_hdr_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_payout_summary,
							'alias'		=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = C.payroll_summary_id'
					)
						
			);
			
			$group_by 	= array('A.office_id');
			$order_by 	= array('A.office_name' => 'ASC');
			
			$data['total_per_com'] = array();
			$data['total_per_ded'] = array();
			$com_ctr = 0;
			foreach($inherit_compensation as $compensation){
 				$where['B.raw_compensation_id'] = array($compensation['compensation_id'], array("=", "OR"));
 				$where['B.compensation_id'] 	= array($compensation['compensation_id'], array("="));
 				$where['EXTRACT(YEAR_MONTH FROM B.effective_date)']	= $year_month;
 				$where['C.payout_type_flag']	= PAYOUT_TYPE_FLAG_SPECIAL;
				$data['comp_amounts'][]			= $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
				
				foreach($data['comp_amounts'][$com_ctr] as $com_amt)
				{
					$amt = !EMPTY($com_amt['amount']) ? $com_amt['amount'] : 0;
					$data['total_per_com'][$compensation['compensation_id']]	+= $amt;
				}
				$com_ctr++;
			}
			
			// IF COMPENSATION HAS CHILD FIX VALUES
			$com_amt_w_child = array();
			if($data['multi_child'])
			{
				foreach ($data['comp_amounts'] as $com_amts)
				{
					foreach($com_amts as $k=>$amt)
					{
						$com_amt_w_child[$amt['office_id']]['office_name'] = $amt['office_name'];
						$com_amt_w_child[$amt['office_id']][] = $amt['amount'];
						
					}
				}
			}
			
			$data['com_amt_w_child'] = $com_amt_w_child;
			//END COMPENSATIONS
			
			
			
			// DEDUCTIONS
			// START

			// GET DIFFERENT DEDUCTIONS
			$where			= array();
			$fields			= array('B.deduction_id', 'D.deduction_name');
			
			$results_deduct										= $this->get_deduction_header($params, $data['compensations'], $year_month);
			
			$com_ids 											= $this->get_compensation_ids($data['compensations']);
			// GET DEDUCTION AMOUNTS
			$where												= array();
			$where['B.deduction_id']							= "IS NOT NULL";
			$where['EXTRACT(YEAR_MONTH FROM B.effective_date)']	= $year_month;
			$where['A.payroll_hdr_id']							= array(explode(",", $results_deduct[0]['payroll_hdr_id']), array("IN"));
			$fields												= array('A.office_id', 'B.deduction_id', 'SUM(B.amount) amount');
			
			$tables 		= array(
					'main'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias'		=> 'A'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_hdr_id = B.payroll_hdr_id'
					)
			
			);
			
			$data['deduct_amounts'] = array();
			
			foreach ($results_deduct as $deduc)
			{
				$where['B.deduction_id']	= $deduc['deduction_id'];
				$group_by 					= array('A.office_id');
				$data['deduct_amounts'][]	= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, $group_by);
			}
			
			$data['deductions']				= $results_deduct;
			
			
			
			$ded_ctr = 0;
			$data['total_per_ded'] = array();
			foreach($data['deductions'] as $deduction)
			{
				foreach($data['deduct_amounts'][$ded_ctr] as $ded_am)
				{
					if($deduction['deduction_id'] == $ded_am['deduction_id'])
					{
						$amt = !EMPTY($ded_am['amount']) ? $ded_am['amount'] : 0;
						$data['total_per_ded'][$deduction['deduction_id']] += $amt;
					}
  				
				}
				$ded_ctr++;
			}
			
			// END DEDUCTIONS

			// SIGNATORY
			$data['prepared_by']		= $this->cm->get_report_signatory_details($params['prep_by']);

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
	
	public function get_compensation_child($compensation_id)
	{
		try
		{
			$result                          = array();
			$where                           = array();
			$where['parent_compensation_id'] = $compensation_id;
			$fields                          = array("compensation_id", "compensation_name", "inherit_parent_id_flag");
			$table                           = $this->rm->tbl_param_compensations;
			$result                          = $this->rm->get_reports_data($fields, $table, $where, TRUE);
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

		return $result;	
		 
	}
	
	public function get_deduction_header($params, $com_hdr, $year_month)
	{
		// GET DIFFERENT DEDUCTIONS
		$com_ids 		= $this->get_compensation_ids($com_hdr);
		
		$where			= array();
		$fields			= array('GROUP_CONCAT(B.payroll_hdr_id) payroll_hdr_id', 'B.deduction_id', 'D.deduction_name');
	
		$tables 		= array(
				'main'		=> array(
						'table'		=> $this->rm->tbl_payout_header,
						'alias'		=> 'A'
				),
				't2'		=> array(
						'table'		=> $this->rm->tbl_payout_details,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.payroll_hdr_id = B.payroll_hdr_id'
				),
				't3'		=> array(
						'table'		=> $this->rm->tbl_payout_summary,
						'alias'		=> 'C',
						'type'		=> 'JOIN',
						'condition'	=> 'A.payroll_summary_id = C.payroll_summary_id'
				),
				't4'		=> array(
						'table'		=> $this->rm->tbl_param_deductions,
						'alias'		=> 'D',
						'type'		=> 'JOIN',
						'condition'	=> 'B.deduction_id = D.deduction_id'
				)
					
		);
		$where	= array($year_month, $params['compensation_special_type'], PAYOUT_TYPE_FLAG_SPECIAL, $com_ids);
			
		return $this->rm->get_special_payroll_summary_per_office_deductions($fields, $where, TRUE, "B.deduction_id");
	}
	
	public function get_compensation_ids($com_hdr)
	{
		// GET DIFFERENT DEDUCTIONS
		foreach ($com_hdr as $hdr)
		{
			$ded_id[]		= $hdr['compensation_id'];
		}
	
		return implode(", ",$ded_id);
	}
}


/* End of file Special_payroll_summary_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Special_payroll_summary_per_office.php */