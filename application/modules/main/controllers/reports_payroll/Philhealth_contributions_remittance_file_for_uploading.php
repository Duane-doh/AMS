<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Philhealth_contributions_remittance_file_for_uploading extends Main_Controller {
	
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
			$data                  		= array();
			$year						= $params['year'];
			$month						= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month					= $year.$month;
			
			// STORES YEAR AND MONTH
			$data['year'] 				= $params['year'];
			$data['month']				= $params['month'];
			$data['month_text']			= $params['month_text'];
			
			$field						= array('B.compensation_id');
			$table						= array(
					'main'	=> array(
							'table' => $this->rm->DB_CORE.".".$this->rm->tbl_sys_param,
							'alias'	=> 'A'
					),
					't1'	=> array(
							'table' 	=> $this->rm->tbl_param_compensations,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.sys_param_value = B.compensation_code'
					)
			);
			$where						= array();
			$where['sys_param_type']	= PARAM_COMPENSATION_BASIC_SALARY;
			$com_id_bs					= $this->rm->get_reports_data($field, $table, $where, FALSE, NULL, NULL);
					
			$payroll_type_ids = "";
			foreach($params['payroll_type_rem'] as $pt)
			{
				if($pt == end($params['payroll_type_rem']))
				{
					$payroll_type_ids .= " C.payroll_type_ids LIKE '%" . $pt . "%'";
				}else{
					$payroll_type_ids .= " C.payroll_type_ids LIKE '%" . $pt . "%' OR";
				}
					
			}
			$payout_types             = "'".implode("','", $params['payout_type'])."'";
			$data['payout_type_flag'] = $params['payout_type'];
			$order_by = (in_array(DEFAULT_PHILHEALTH_REMITTANCE_TEMPLATE, $params['payout_type'])) ? " ORDER BY G.last_name,G.first_name" : " ORDER BY F.office_name,G.last_name,G.first_name";
			$results                  = $this->rm->get_philhealth_contributions_remittance_list($year_month, $payroll_type_ids,$payout_types,$order_by);

			$field						= array('B.short_name');
			$table						= array(
					'main'		=>	array(
							'table'		=> $this->rm->tbl_param_offices,
							'alias'		=> 'A'
					),
					't1'		=> array(
							'table'		=> $this->rm->DB_CORE.".".$this->rm->tbl_organizations,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.org_code = B.org_code'
					)
			);
			
			$data['phil_remittances']	= array();
			$total_ps					= 0;
			$total_gs 					= 0;
			$total_ps_gs 				= 0;
			
			foreach($results as $result){
				$where                       = array();
				$where['A.office_id']        = $result['office_id'];
				$office                      = $this->rm->get_reports_data($field, $table, $where, FALSE);
				$result['office_short_name'] = $office['short_name'];			
				
				$total_ps                    = $total_ps + $result['ps'];
				$total_gs                    = $total_gs + $result['gs'];
				$total_ps_gs                 = $total_ps_gs + $total_ps + $total_gs;
				if($result['or_no'])
				{
					$data['or_no'] = $result['or_no'];
				}
				if($result['or_date'])
				{
					$data['or_date'] = $result['or_date'];
				}
				if(!in_array(DEFAULT_PHILHEALTH_REMITTANCE_TEMPLATE, $params['payout_type']))
				{
					$result = $this->_construct_remarks($result);
				}

				$data['phil_remittances'][]  = $result;
			}
			
			// DOH ADDRESS
			$doh_sub                         = $this->rm->get_sysparam_value(DOH_ADDRESS_SUBDIVISION);
			$doh_brgy						= $this->rm->get_sysparam_value(DOH_ADDRESS_BARANGAY);
			$doh_municity					= $this->rm->get_sysparam_value(DOH_ADDRESS_MUNICITY);
			$data['doh_address']			= $doh_sub['sys_param_value'] . ", " . $doh_brgy['sys_param_value'] . ", " . $doh_municity['sys_param_value'];
			
			// DOH TIN
			$doh_tin						= $this->rm->get_sysparam_value(DOH_TIN);
			$data['doh_tin']				= $doh_tin['sys_param_value'];
			
			// STORES TOTAL PS, TOTAL GS, AND SUM OF PS AND GS TOTAL
			$data['total_ps']		= $total_ps;
			$data['total_gs']		= $total_gs;
			$data['total_ps_gs']	= $total_ps_gs;
			
			$data['prepared_by']	= $this->cm->get_report_signatory_details($params['rem_prepared_by']);
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
	
	private function _construct_remarks($result)
	{
		try {
			
			$where                       = array();
			$where['employee_id']        = $result['employee_id'];
			$where['payroll_summary_id'] = $result['payroll_summary_id'];
			$payout_table                = $this->rm->tbl_payout_employee;
			$payout_field                = array('included_flag');
			$payout_result               = $this->rm->get_reports_data($payout_field, $payout_table, $where, FALSE);

			

			$result['remarks1']            = "A";
			if($payout_result['included_flag'] == NO)
			{
				$result['remarks1']            = "NE";
			}
			else
			{
				$where                             = array();
				$where['attendance_period_hdr_id'] = $result['attendance_period_hdr_id'];
				$ap_table                          = $this->rm->tbl_attendance_period_hdr;
				$ap_field                          = array('date_from','date_to');
				$ap_result                         = $this->rm->get_reports_data($ap_field, $ap_table, $where, FALSE);

				$where                     = array();
				$where['employee_id']      = $result['employee_id'];
				$where['employ_type_flag'] = array(array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
				$wx_table                  = $this->rm->tbl_employee_work_experiences;
				$wx_field                  = array('min(employ_start_date) employ_start_date');
				$wx_result                 = $this->rm->get_reports_data($wx_field, $wx_table, $where, FALSE);

				if($wx_result['employ_start_date'] >= $ap_result['date_from'] AND $wx_result['employ_start_date'] <= $ap_result['date_to'])
				{
					$result['remarks1']            = "NH";
					$result['remarks2']            = format_date($wx_result['employ_start_date'],'m/d/Y');
				}else
				{
					$where                       = array();
					$where['employee_id']        = $result['employee_id'];
					$where['employ_type_flag'] 	 = array(array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
					$wx_table                    = $this->rm->tbl_employee_work_experiences;
					$wx_field                    = array('max(employ_start_date) employ_start_date','employ_end_date','separation_mode_id');
					$wx_result                   = $this->rm->get_reports_data($wx_field, $wx_table, $where, FALSE);
					if(!EMPTY($wx_result['employ_end_date']))
					{
						$where                       = array();
						$where['employee_id']        = $result['employee_id'];
						$where['employ_type_flag'] 	 = array(array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
						$where['employ_start_date']  = $wx_result['employ_start_date'];
						$wx_table                    = $this->rm->tbl_employee_work_experiences;
						$wx_field                    = array('employ_start_date','employ_end_date','separation_mode_id');
						$wx_result                   = $this->rm->get_reports_data($wx_field, $wx_table, $where, FALSE);
					}
					

					if(!EMPTY($wx_result['employ_end_date']) AND !EMPTY($wx_result['separation_mode_id']))
					{
						$result['remarks1']            = "S";
						$result['remarks2']            = format_date($wx_result['employ_end_date'],'m/d/Y');
					}
				}
			}

			return $result;
		} 
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e) 
		{
			throw $e;
		}
		
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


/* End of file Philhealth_contributions_remittance_file_for_uploading.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Philhealth_contributions_remittance_file_for_uploading.php */