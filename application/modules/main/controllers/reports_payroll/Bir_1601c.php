<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_1601c extends Main_Controller {
	
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
			
			$data['month'] 		= $month;
			$year['year']		= $year;
			
			$data['month_year'] = $month.$year;
			
			// STORES DOH DETAILS
			// ADDRESS
			$building		= $this->rm->get_sysparam_value(DOH_ADDRESS_BUILDING);
			$street			= $this->rm->get_sysparam_value(DOH_ADDRESS_STREET);
			$subdivision	= $this->rm->get_sysparam_value(DOH_ADDRESS_SUBDIVISION);
			$barangay		= $this->rm->get_sysparam_value(DOH_ADDRESS_BARANGAY);
			$doh_municity	= $this->rm->get_sysparam_value(DOH_ADDRESS_MUNICITY);
				
			$data['doh_add'] 	= $building['sys_param_value'] . " " . $street['sys_param_value'] . ", " . $subdivision['sys_param_value'] . ", " . $barangay['sys_param_value'] . ", " . $doh_municity['sys_param_value'];
				
			// ZIP CODE
			$doh_zip_code		= $this->rm->get_sysparam_value(DOH_ADDRESS_ZIP_CODE);
			$data['doh_zip_code'] = $doh_zip_code['sys_param_value'];

			$rdo_code		= $this->rm->get_sysparam_value(PARAM_DOH_RDO_CODE);
			$data['rdo_code'] = $rdo_code['sys_param_value'];
				
			// TIN
			$result_tin	= $this->rm->get_sysparam_value(DOH_TIN);
			$result_tin = str_replace("-","", $result_tin);
			$data['doh_tin'] = $result_tin['sys_param_value'];
			
			
			
			// STORES REMITTANCE TYPE ID
			$where							= array();
			$where['sys_param_type']		= 'REMITTANCE_TYPE_WITHHOLDING_TAX';
			$fields 						= array('sys_param_value');
			$table							= $this->rm->DB_CORE . '.' . $this->rm->tbl_sys_param;
			$remittance_type				= $this->rm->get_reports_data($fields, $table, $where, FALSE);
			
			// COMMON TABLES
			$tables			= array(
					'main'		=> array(
							'table'	=> $this->rm->tbl_param_remittance_types,
							'alias' => 'A',
					),
					't1'		=> array(
							'table'		=> $this->rm->tbl_remittances,
							'alias'		=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.remittance_type_id = B.remittance_type_id'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_remittance_details,
							'alias'		=> 'C',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.remittance_id = C.remittance_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias'		=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'C.payroll_dtl_id = D.payroll_dtl_id'
					),
					't4'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias'		=> 'E',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'D.payroll_hdr_id = E.payroll_hdr_id'
					),
					't5'		=> array(
							'table'		=> $this->rm->tbl_form_2316_header,
							'alias'		=> 'F',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'E.employee_id = F.employee_id'
					),
					't6'		=> array(
							'table'		=> $this->rm->tbl_form_2316_monthly_details,
							'alias'		=> 'G',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'F.form_2316_id = G.form_2316_id'
					)
			);
			
			$where							= array();
			$where['B.year_month']			= $year_month;
			$where['A.remittance_type_id']	= $remittance_type['sys_param_value'];
			$fields = array('SUM(G.gross_income_present_employer) col_15', 'SUM(G.total_non_tax_exemption) col_16c',
			'SUM(G.taxable_income_present_employer) col_17', 'SUM(G.tax_due) col_18', 'SUM(G.total_tax_withheld) col_20');
			
			$data['part_2']					= $this->rm->get_reports_data($fields, $tables, $where, FALSE);
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


/* End of file Bir_1601c.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_1601c.php */