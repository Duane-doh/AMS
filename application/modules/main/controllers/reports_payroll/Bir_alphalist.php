<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_alphalist extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$office_list = array();
			if(!EMPTY($params['office_list']))
			{
				$office_list = $this->rm->get_office_child('', $params['office_list']);
			}
			/* SCHEDULE 7.1 */
			$doh_tin = '(SELECT REPLACE(sys_param_value,"-","") FROM '.$this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param.' WHERE sys_param_type = "'.DOH_TIN.'" LIMIT 1)';
			$fields = array("'D7.1' AS '0', '1604CF' AS '1', ".$doh_tin." AS '2', '0000' AS '3', CONCAT('12/31/',A.year) AS '4', @rownum := @rownum + 1 AS '5', A.employee_tin AS '6', A.rdo_code AS '7',
							CONCAT('\"',D.last_name,'\"') AS '8', CONCAT('\"',D.first_name,'\"') AS '9', CONCAT('\"',D.middle_name,'\"') AS '10',(SELECT DATE_FORMAT(MIN(employ_start_date),'%m/%d/%Y') FROM employee_work_experiences where employee_id = A.employee_id) AS '11', DATE_FORMAT(MAX(E.employ_end_date),'%m/%d/%Y') AS '12', B.gross_income_present_employer AS '13', B.month_pay_13 AS '14', B.deminimis AS '15', B.statutory_employee_share AS '16',
							B.salary_other_compensation AS '17', B.total_personal_exemption AS '18', B.basic_salary AS '19', B.taxable_month_pay_13 AS '20', '0.00' AS '21', B.total_taxable_income AS '22', G.exempt_short_code AS '23',
							B.total_exempt_income AS '24', B.health_insurance_paid AS '25', B.net_taxable_income AS '26', B.tax_due AS '27', ifnull(C.total_tax_withheld,0.00) AS '28', (B.tax_due - ifnull(C.total_tax_withheld,0.00)) AS '29',
							(ifnull(C.total_tax_withheld,0.00) - B.tax_due) AS '30', (ifnull(C.total_tax_withheld,0.00) + (B.tax_due - ifnull(C.total_tax_withheld,0.00))) AS '31', 'Y' AS '32'");
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
					'condition' => 'A.form_2316_id = C.form_2316_id AND C.month = 11'
				),
				't3' => array(
					'table' => $this->rm->tbl_employee_personal_info,
					'alias' => 'D',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = D.employee_id'
				),
				't4' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'E',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = E.employee_id AND E.employ_type_flag IN (\''.DOH_GOV_APPT.'\', \''.DOH_GOV_NON_APPT.'\')'
				),
				't5' => array(
					'table' => '(SELECT @rownum := 0)',
					'alias' => 'F',
					'type' => 'JOIN',
					'condition' => '1=1'
				),
				't6' => array(
					'table' => $this->rm->tbl_param_tax_exempt_code,
					'alias' => 'G',
					'type' => 'JOIN',
					'condition' => 'A.exemption_status = G.exempt_code'
				)
			);
			$where                         = array();
			$where['A.year']               = $params['year_only'];
			$where['E.employ_end_date']    = array(date($params['year_only'].'-12-01'), array('<'));
			$where['E.separation_mode_id'] = 'IS NOT NULL';
			$where['E.active_flag']        = YES;
			$where['A.mwe_flag']           = NO;
			if(!EMPTY($office_list))
			{
				$where['E.employ_office_id']                = array($office_list, array('IN'));
			}
			$data['records']['D7.1'] = $this->rm->get_reports_data($fields, $table, $where, TRUE, array(), array('E.employee_id'));

			/* SCHEDULE 7.2 */
			// $doh_tin = '(SELECT REPLACE(sys_param_value,'-','') FROM '.$this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param.' WHERE sys_param_type = "'.DOH_TIN.'" LIMIT 1)';
			$fields = array("'D7.2' AS '0', '1604CF' AS '1', ".$doh_tin." AS '2', '0000' AS '3', CONCAT('12/31/',A.year) AS '4', @rownum := @rownum + 1 AS '5', A.employee_tin AS '6', A.rdo_code AS '7',
							CONCAT('\"',D.last_name,'\"') AS '8', CONCAT('\"',D.first_name,'\"') AS '9', CONCAT('\"',D.middle_name,'\"') AS '10', B.gross_income_present_employer AS '11', B.month_pay_13 AS '12', B.deminimis AS '13', B.statutory_employee_share AS '14',
							B.salary_other_compensation AS '15', B.total_personal_exemption AS '16', B.basic_salary AS '17', '0.00' AS '18', G.exempt_short_code AS '19',
							B.total_exempt_income AS '20', B.health_insurance_paid AS '21', B.net_taxable_income AS '22', B.tax_due AS '23'");
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
				't3' => array(
					'table' => $this->rm->tbl_employee_personal_info,
					'alias' => 'D',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = D.employee_id'
				),
				't4' => array(
					'table' => '(SELECT @rownum := 0)',
					'alias' => 'E',
					'type' => 'JOIN',
					'condition' => '1=1'
				),
				't5' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'F',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = F.employee_id AND F.active_flag = "Y"'
				),
				't6' => array(
					'table' => $this->rm->tbl_param_tax_exempt_code,
					'alias' => 'G',
					'type' => 'JOIN',
					'condition' => 'A.exemption_status = G.exempt_code'
				)
			);
			$where = array('A.year' => $params['year_only'], 'A.mwe_flag' => NO);
			if(!EMPTY($office_list))
			{
				$where['F.employ_office_id']                = array($office_list, array('IN'));
			}
			$data['records']['D7.2'] = $this->rm->get_reports_data($fields, $table, $where);

			/* SCHEDULE 7.3 */
			$fields = array("'D7.3' AS '0', '1604CF' AS '1', ".$doh_tin." AS '2', '0000' AS '3', CONCAT('12/31/',A.year) AS '4', @rownum := @rownum + 1 AS '5', A.employee_tin AS '6', A.rdo_code AS '7',
							CONCAT('\"',D.last_name,'\"') AS '8', CONCAT('\"',D.first_name,'\"') AS '9', CONCAT('\"',D.middle_name,'\"') AS '10', B.gross_income_present_employer AS '11', B.month_pay_13 AS '12', B.deminimis AS '13', B.statutory_employee_share AS '14',
							B.salary_other_compensation AS '15', B.total_personal_exemption AS '16', B.basic_salary AS '17', B.taxable_month_pay_13 AS '18', '0.00' AS '19', B.total_taxable_income AS '20', G.exempt_short_code AS '21',
							B.total_exempt_income AS '22', B.health_insurance_paid AS '23', B.net_taxable_income AS '24', B.tax_due AS '25', ifnull(C.total_tax_withheld,0.00) AS '26', (B.tax_due - ifnull(C.total_tax_withheld,0.00)) AS '27',
							(ifnull(C.total_tax_withheld,0.00) - B.tax_due) AS '28', (ifnull(C.total_tax_withheld,0.00) + (B.tax_due - ifnull(C.total_tax_withheld,0.00))) AS '29', 'Y' AS '30'");
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
					'condition' => 'A.form_2316_id = C.form_2316_id AND C.month = 11'
				),
				't3' => array(
					'table' => $this->rm->tbl_employee_personal_info,
					'alias' => 'D',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = D.employee_id'
				),
				't4' => array(
					'table' => '(SELECT @rownum := 0)',
					'alias' => 'E',
					'type' => 'JOIN',
					'condition' => '1=1'
				),
				't5' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'F',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = F.employee_id AND F.active_flag = "Y"'
				),
				't6' => array(
					'table' => $this->rm->tbl_param_tax_exempt_code,
					'alias' => 'G',
					'type' => 'JOIN',
					'condition' => 'A.exemption_status = G.exempt_code'
				)
			);
			$where = array('A.year' => $params['year_only'], 'A.mwe_flag' => NO);
			if(!EMPTY($office_list))
			{
				$where['F.employ_office_id']                = array($office_list, array('IN'));
			}
			$data['records']['D7.3'] = $this->rm->get_reports_data($fields, $table, $where);

			/* SCHEDULE 7.4 */
			$fields = array("'D7.4' AS '0', '1604CF' AS '1', ".$doh_tin." AS '2', '0000' AS '3', CONCAT('12/31/',A.year) AS '4', @rownum := @rownum + 1 AS '5', A.employee_tin AS '6', A.rdo_code AS '7',
							CONCAT('\"',D.last_name,'\"') AS '8', CONCAT('\"',D.first_name,'\"') AS '9', CONCAT('\"',D.middle_name,'\"') AS '10', B.gross_income_present_employer AS '11', 
							IFNULL((SELECT 
				                    GROUP_CONCAT(IFNULL(F.other_deduction_detail_value, 0))
				                FROM
				                    employee_deduction_other_details F
				                        JOIN
				                    employee_deductions G ON F.employee_deduction_id = G.employee_deduction_id
				                WHERE
				                    F.other_deduction_detail_id IN (8 , 9, 10, 11, 12)
				                        AND G.employee_deduction_id = F.employee_deduction_id
				                        AND G.employee_id = A.employee_id),
				            '0,0,0,0,0') AS '12', 
							IFNULL((SELECT 
				                    GROUP_CONCAT(IFNULL(F.other_deduction_detail_value, 0))
				                FROM
				                    employee_deduction_other_details F
				                        JOIN
				                    employee_deductions G ON F.employee_deduction_id = G.employee_deduction_id
				                WHERE
				                    F.other_deduction_detail_id IN (13 , 14, 15, 16)
				                        AND G.employee_deduction_id = F.employee_deduction_id
				                        AND G.employee_id = A.employee_id),
				            '0,0,0,0') AS '13',
							B.month_pay_13 AS '14', 
							B.deminimis AS '15', B.statutory_employee_share AS '16', B.salary_other_compensation AS '17', B.total_personal_exemption AS '18', 
							B.basic_salary AS '19', B.taxable_month_pay_13 AS '20', '0.00' AS '21', B.taxable_income_present_employer AS '22', (B.taxable_income_present_employer + B.taxable_income_previous_employer) AS '23', 
							G.exempt_short_code AS '24', B.total_exempt_income AS '25', B.health_insurance_paid AS '26', B.net_taxable_income AS '27', B.tax_due AS '28', ifnull(C.tax_withheld_previous_employer,0.00) AS '29', 
							ifnull(C.tax_withheld_present_employer,0.00) AS '30', (B.tax_due - ifnull(C.total_tax_withheld,0.00)) AS '31', (ifnull(C.total_tax_withheld,0.00) - B.tax_due) AS '32', 
							(ifnull(C.tax_withheld_present_employer,0.00) + (B.tax_due - ifnull(C.total_tax_withheld,0.00))) AS '33'");
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
					'condition' => 'A.form_2316_id = C.form_2316_id AND C.month = 11'
				),
				't3' => array(
					'table' => $this->rm->tbl_employee_personal_info,
					'alias' => 'D',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = D.employee_id'
				),
				't4' => array(
					'table' => '(SELECT @rownum := 0)',
					'alias' => 'E',
					'type' => 'JOIN',
					'condition' => '1=1'
				),
				't5' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'F',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = F.employee_id AND F.active_flag = "Y"'
				),
				't6' => array(
					'table' => $this->rm->tbl_param_tax_exempt_code,
					'alias' => 'G',
					'type' => 'JOIN',
					'condition' => 'A.exemption_status = G.exempt_code'
				)
			);
			$where = array('A.year' => $params['year_only'], 'A.mwe_flag' => NO);
			if(!EMPTY($office_list))
			{
				$where['F.employ_office_id']                = array($office_list, array('IN'));
			}
			$data['records']['D7.4'] = $this->rm->get_reports_data($fields, $table, $where);

			/* SCHEDULE 7.5 */
			$fields = array("'D7.5' AS '0', '1604CF' AS '1', ".$doh_tin." AS '2', '0000' AS '3', CONCAT('12/31/',A.year) AS '4', @rownum := @rownum + 1 AS '5',
						    A.employee_tin AS '6', A.rdo_code AS '7', CONCAT('\"',D.last_name,'\"') AS '8', CONCAT('\"',D.first_name,'\"') AS '9', CONCAT('\"',D.middle_name,'\"') AS '10', 'NCR' AS '11',
						    '0.00' AS '12', '0.00' AS '13', '0.00' AS '14', '0.00' AS '15', '0.00' AS '16', '0.00' AS '17',
						    IFNULL((SELECT 
								GROUP_CONCAT(IFNULL(F.other_deduction_detail_value, 0))
							FROM
								employee_deduction_other_details F
									JOIN
								employee_deductions G ON F.employee_deduction_id = G.employee_deduction_id
							WHERE
								F.other_deduction_detail_id IN (8 , 9, 10, 11, 12)
									AND G.employee_deduction_id = F.employee_deduction_id
									AND G.employee_id = A.employee_id),
							'0,0,0,0,0') AS '18', 
							IFNULL((SELECT 
								GROUP_CONCAT(IFNULL(F.other_deduction_detail_value, 0))
							FROM
								employee_deduction_other_details F
									JOIN
								employee_deductions G ON F.employee_deduction_id = G.employee_deduction_id
							WHERE
								F.other_deduction_detail_id IN (14, 15, 16)
									AND G.employee_deduction_id = F.employee_deduction_id
									AND G.employee_id = A.employee_id),
							'0,0,0') AS '19',
						    (SELECT DATE_FORMAT(MIN(employ_start_date),'%m/%d/%Y') FROM employee_work_experiences where employee_id = A.employee_id) AS '20',
						    DATE_FORMAT(MAX(E.employ_end_date),'%m/%d/%Y') AS '21',
						    B.gross_income_present_employer AS '22', B.basic_salary/22 AS '23', B.basic_salary AS '24',
						    B.basic_salary*12 AS '25', '0/0' AS '26', B.mwe_holiday_pay AS '27', B.mwe_overtime_pay AS '28',
						    B.mwe_night_diff_pay AS '29', B.mwe_hazard_pay AS '30', B.month_pay_13 AS '31', B.deminimis AS '32',
						    B.statutory_employee_share AS '33', B.salary_other_compensation AS '34', B.taxable_month_pay_13 AS '35',
						    '0.00' AS '36', (B.taxable_month_pay_13) AS '37',
						    (B.taxable_month_pay_13 + IFNULL((SELECT 
								SUM(F.other_deduction_detail_value)
							FROM
								employee_deduction_other_details F
									JOIN
								employee_deductions G ON F.employee_deduction_id = G.employee_deduction_id
							WHERE
								F.other_deduction_detail_id IN (14, 15, 16)
									AND G.employee_deduction_id = F.employee_deduction_id
									AND G.employee_id = A.employee_id),
							0)) AS '39',
						    G.exempt_short_code AS '40',  B.total_exempt_income AS '41', B.health_insurance_paid AS '42', B.net_taxable_income AS '43',
						    B.tax_due AS '44', IFNULL(C.tax_withheld_previous_employer, 0.00) AS '45',
						    IFNULL(C.tax_withheld_present_employer, 0.00) AS '46',
						    (B.tax_due - (IFNULL(C.tax_withheld_previous_employer, 0.00) + IFNULL(C.tax_withheld_present_employer, 0.00))) AS '47',
						    ((IFNULL(C.tax_withheld_previous_employer, 0.00) + IFNULL(C.tax_withheld_present_employer, 0.00)) - B.tax_due) AS '48',
						    (IFNULL(C.tax_withheld_present_employer, 0.00) + (B.tax_due - (IFNULL(C.tax_withheld_previous_employer, 0.00) + IFNULL(C.tax_withheld_present_employer, 0.00)))) AS '49'");
			
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
					'condition' => 'A.form_2316_id = C.form_2316_id AND C.month = 11'
				),
				't3' => array(
					'table' => $this->rm->tbl_employee_personal_info,
					'alias' => 'D',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = D.employee_id'
				),
				't4' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'E',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = E.employee_id AND E.active_flag = "Y"'
				),
				't5' => array(
					'table' => '(SELECT @rownum := 0)',
					'alias' => 'F',
					'type' => 'JOIN',
					'condition' => '1=1'
				),
				't6' => array(
					'table' => $this->rm->tbl_param_tax_exempt_code,
					'alias' => 'G',
					'type' => 'JOIN',
					'condition' => 'A.exemption_status = G.exempt_code'
				)
			);
			$where = array('A.year' => $params['year_only'], 'A.mwe_flag' => YES);
			if(!EMPTY($office_list))
			{
				$where['E.employ_office_id']                = array($office_list, array('IN'));
			}
			$data['records']['D7.5'] = $this->rm->get_reports_data($fields, $table, $where);
			
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


/* End of file Bir_alphalist.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_alphalist.php */