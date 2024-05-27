<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Year_end_adjustment_report_per_office extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data = array();
			$office_list = $this->rm->get_office_child('', $params['office_list']);
			
			$fields = array('A.employee_id, B.statutory_employee_share, A.employee_name, A.employee_tin AS taxpayer_account, 
							B.month_pay_13, B.deminimis, B.salary_other_compensation, B.taxable_month_pay_13,
							"0.00" AS other_benifits, B.basic_salary as sal_comp, B.total_personal_exemption, B.tax_due, 
							ifnull(C.total_tax_withheld,0.00) AS total_tax_withheld, (B.tax_due - ifnull(C.tax_due,0)) AS tax_due_dec, 
							(B.total_tax_withheld - ifnull(C.total_tax_withheld,0)) AS overwithheld, D.employ_monthly_salary as basic_salary');

			$table = array(
				'main' => array(
					'table' => $this->rm->tbl_form_2316_header,
					'alias' => 'A'
				),
				't1' => array(
					'table' => $this->rm->tbl_form_2316_details,
					'alias' => 'B',
					'type' => 'JOIN',
					'condition' => 'A.form_2316_id = B.form_2316_id'
				),
				't2' => array(
					'table' => $this->rm->tbl_form_2316_monthly_details,
					'alias' => 'C',
					'type' => 'LEFT JOIN',
					'condition' => 'A.form_2316_id = C.form_2316_id AND C.month = (select max(month) from form_2316_monthly_details where form_2316_id = A.form_2316_id)'
				),
				't3' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'D',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = D.employee_id AND D.active_flag = "' . YES . '"'
				)
			);
			
			$where                       = array();
			$where['A.year']             = $params['year_only'];
			$where['D.employ_office_id'] = array($office_list, array('IN'));
			$data['year']                = $params['year_only'];
			$data['records']             = $this->rm->get_reports_data($fields, $table, $where, TRUE, array(), array('A.employee_name'));

			$field                           = array("format");		
			
			$table                           = $this->rm->tbl_param_identification_types;
			$where                           = array();
			$where["identification_type_id"] = TIN_TYPE_ID;
			$identification_type             = $this->rm->get_reports_data($field, $table, $where, FALSE);
			$data['tin_format']              = $identification_type['format'];

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


/* End of file Year_end_adjustment_report_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Year_end_adjustment_report_per_office.php */