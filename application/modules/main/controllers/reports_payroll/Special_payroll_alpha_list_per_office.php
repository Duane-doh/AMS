<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll_alpha_list_per_office extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data  				= array();
			$year				= $params['year'];
			$month				= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month			= $year.$month;
			
			//GET COMPENSATION NAME
			$field						= array("compensation_name");
			$table 						= $this->rm->tbl_param_compensations;
			$where						= array();
			$where['compensation_id']	= $params['compensation_special_type'];
			$data['compensation_name'] 	= $this->rm->get_reports_data($field, $table, $where, FALSE, NULL);

			$field                           = array("*");
			$table                           = $this->rm->tbl_param_compensations;
			$where                           = array();
			$where['parent_compensation_id'] = $params['compensation_special_type'];
			$where['inherit_parent_id_flag'] = NO;
			$child_comp                      = $this->rm->get_reports_data($field, $table, $where, FALSE, NULL);

			$include_parent_flag = ($child_comp) ? FALSE:TRUE;
			
			$data['com_hdr']	= $this->get_compensation_header($params,$include_parent_flag);
			$data['ded_hdr']	= $this->get_deduction_header($params, $data['com_hdr'], $year_month);
			
			$fields   = array('A.name');
			$tables   		=  array(
					'main' 			=> array(
							'table' 	=> $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
							'alias' 	=> 'A'
					),
					't1'			=> array(
							'table' 	=> $this->rm->tbl_param_offices,
							'alias'		=> 'B',
							'type'  	=> 'JOIN',
							'condition' => 'A.org_code = B.org_code'
					)
			);
			$where   = array('B.office_id' => $params['office_list']);
			
			$data['office_name'] = $this->rm->get_reports_data($fields, $tables, $where, FALSE);
			
			$tables 		= array(
				'main'      => array(
				'table'     => $this->rm->tbl_payout_details,
				'alias'     => 'A'
			 	),
				't1'        => array(
				'table'     => $this->rm->tbl_payout_header,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.payroll_hdr_id = B.payroll_hdr_id'
			 	), 
				't2'        => array(
				'table'     => $this->rm->tbl_param_compensations,
				'alias'     => 'C',
				'type'      => 'JOIN',
				'condition' => 'A.compensation_id = C.compensation_id'
			 	),	
			 	't3'        => array(
				'table'     => $this->rm->tbl_param_offices,
				'alias'     => 'D',
				'type'      => 'JOIN',
				'condition' => 'B.office_id = D.office_id'
			 	),
			 	't4'		=> array(
				'table'		=> $this->rm->tbl_payout_summary,
				'alias'		=> 'F',
				'type'		=> 'JOIN',
				'condition'	=> 'B.payroll_summary_id = F.payroll_summary_id'
				),
			 	't5'        => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'G',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.employee_id = G.employee_id'
			 	),
				't6'        => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'H',
				'type'      => 'JOIN',
				'condition' => 'B.employee_id = H.employee_id'
				)
					
			);

			$select_fields   = array(); 
			$select_fields[] = 'B.employee_id';
			$select_fields[] = 'CONCAT(H.last_name,", ",H.first_name," ",ifnull(H.ext_name,\'\'), H.middle_name) employee_name'; 
			$select_fields[] = 'B.position_name'; 
			$select_fields[] = 'A.amount';
			$select_fields[] = 'A.orig_amount';
			$select_fields[] = 'A.less_amount';
			$select_fields[] = 'C.compensation_code';
			$select_fields[] = 'G.employ_salary_grade';
			$offices		 = $this->rm->get_office_child('', $params['office_list']);
			 
			$where = array();
			$where['EXTRACT(YEAR FROM A.effective_date)'] 	= $params['year'];
			$where['EXTRACT(MONTH FROM A.effective_date)'] 	= $params['month'];
			$where['G.active_flag'] 						= YES;
			$where['F.payroll_summary_id']					= $data['com_hdr'][0]['payroll_summary_id'];
			$where['F.payout_type_flag']					= PAYOUT_TYPE_FLAG_SPECIAL;
			$where['B.office_id']							= array($offices, array('IN'));;
			
			$group_by  	= array('B.employee_id');
			$order_by	= array('B.employee_name' => 'ASC');

			foreach($data['com_hdr'] as $com_hdr)
			{
				$where['A.compensation_id']								= $com_hdr['compensation_id'];
				$data['compensations'][$com_hdr['compensation_id']] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);
			}
			
			
			$select_fields   	= array();
			$select_fields[] 	= 'B.employee_id';
			$select_fields[] 	= 'CONCAT(H.last_name,", ",H.first_name," ",ifnull(H.ext_name, ""), " ", H.middle_name) employee_name';
			unset($where['A.compensation_id']);
			$data['employees']	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);
			
			
			$data['results']	= $this->consolidate_data($data['employees'], $data['com_hdr'], $data['compensations']);

			$cna_detail = get_sysparam_value(PARAM_COMPENSATION_CODE_CNA);
			$data['cna_code'] = $cna_detail['sys_param_value'];
			$data['cna_count'] = 0;
			foreach($data['com_hdr'] as $key => $hdr)
			{
				if($hdr['inherit_parent_id_flag'] == 'Y')
				{
					unset($data['com_hdr'][$key]);
				}
				if($hdr['compensation_code'] == $cna_detail['sys_param_value'])
				{
					$data['cna_count']++;
				}
			}
			// DEDUCTIONS
			$where 			= array();
			
			$com_ids 		= $this->get_compensation_ids($data['com_hdr']);
			$test			= array();
			foreach ($data['employees'] as $emp)
			{
				foreach($data['ded_hdr'] as $ded_hdr)
				{
					$where	= array($year, $month, $data['com_hdr'][0]['payroll_summary_id'], PAYOUT_TYPE_FLAG_SPECIAL, implode(", ",$offices), $com_ids, $ded_hdr['deduction_id'], $emp['employee_id']);
					$data['deductions'][$emp['employee_id']][$ded_hdr['deduction_id']] 	= $this->rm->get_special_payroll_summary_per_office_deductions_amounts($where, FALSE);
				}
			}
			$data['year'] = $params['year'];
			$data['total_per_com'] = $this->get_total_per_compensation($data['results'], $data['com_hdr']);
			$data['total_per_ded'] = $this->get_total_per_deduction($data['deductions'],$data['ded_hdr']);


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
	
	public function get_compensation_header($params,$include_parent_flag = TRUE)
	{
		// STORES SPECIAL COMPENSATION NAME
		$where								= array();
		$where['A.compensation_id']			= $params['compensation_special_type'];
		$where['A.special_payroll_flag']	= YES;
		$where['B.payout_type_flag']		= PAYOUT_TYPE_FLAG_SPECIAL;
		$fields								= array("A.compensation_code,A.compensation_id, A.compensation_name,A.inherit_parent_id_flag, A.parent_compensation_id, B.payroll_summary_id");
		$com = array();
		if($include_parent_flag)
		{
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
			
			$com = $this->rm->get_reports_data($fields, $tables, $where, TRUE);
		}
		
		
		
		$tables				= array(
				'main'		=> array(
						'table' => $this->rm->tbl_param_compensations,
						'alias' => 'A'
				),
				't2'		=> array(
						'table'		=> $this->rm->tbl_payout_summary,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.parent_compensation_id = B.compensation_id'
				),
		);
		$where                       		= array();
		$where["A.parent_compensation_id"]  = $params['compensation_special_type'];
		$where['A.special_payroll_flag']	= YES;
		$where['B.payout_type_flag']		= PAYOUT_TYPE_FLAG_SPECIAL;
		$com_child 	 						= $this->rm->get_reports_data($fields, $tables, $where, TRUE);
		
		$merged_comp = array_merge($com,$com_child);
		return $merged_comp;
		
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
			
		return $this->rm->get_special_payroll_summary_per_office_deductions($fields, $where, TRUE);
	}
	
	public function get_deduction_amt($ded_hdr, $employees, $deductions)
	{
	}
	
	public function consolidate_data($employees, $com_hdr, $compensations)
	{
		$cna_detail = get_sysparam_value(PARAM_COMPENSATION_CODE_CNA);
		$cna_code = $cna_detail['sys_param_value'];

		$new_array = array();
		foreach ($employees as $emp)
		{
			foreach($com_hdr as $hdr)
			{
				foreach($compensations as $k => $compensation)
				{
					foreach ($compensation as $amt)
					{
						if(($emp['employee_id'] == $amt['employee_id']) && ($hdr['compensation_id'] == $k))
						{
							if($hdr['inherit_parent_id_flag'] == 'Y')
							{
								$new_array[$emp['employee_id']][$hdr['parent_compensation_id']] += $amt['amount'];
							}
							else
							{
								$new_array[$emp['employee_id']][$k] = ($amt['compensation_code'] == $cna_code) ? $amt['orig_amount']:$amt['amount'];

								$new_array[$emp['employee_id']]['cna_less']  = $amt['less_amount'];
							}
							$new_array[$emp['employee_id']]['employee_name'] 		= $emp['employee_name'];
							$new_array[$emp['employee_id']]['position_name'] 		= $amt['position_name'];
							$new_array[$emp['employee_id']]['employ_salary_grade'] 	= $amt['employ_salary_grade'];
							
						}
					}
				}								
			}
		}
		return $new_array;
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
	
	
	public function get_total_per_compensation($results, $com_hdr)
	{
		$total_per_com = array();
		
		foreach ($results as $result)
		{
			foreach ($com_hdr as $com)
			{
				$amt = !EMPTY($result[$com['compensation_id']]) ? $result[$com['compensation_id']] : 0;
				$total_per_com[$com['compensation_id']] += $amt;
			}
		}
		
		return $total_per_com;
	}
	
	public function get_total_per_deduction($deductions, $ded_hdr)
	{
		$total_per_ded = array();
		foreach ($deductions as $result)
		{
			foreach ($ded_hdr as $ded)
			{
				$amt = !EMPTY($result[$dom['deduction_id']]) ? $result[$dom['deduction_id']] : 0;
				$total_per_ded[$ded['deduction_id']] += $amt;
			}
		}
		
		return $total_per_ded;
	}
}


/* End of file Special_payroll_alpha_list_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Special_payroll_alpha_list_per_office.php */