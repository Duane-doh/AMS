<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees_not_included_in_payroll extends Main_Controller {
	
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
			
			if($params['office_list'] != ''){
				$office_list = $this->rm->get_office_child('', $params['office_list']);
			}
			
			// $fields    = array("C.employee_id", "C.agency_employee_id", "CONCAT(C.last_name,  ', ', C.first_name, ' ', C.middle_name, ' ', C.ext_name) employee_name ",
							// "D.employ_office_name","D.employ_position_name", "E.period_date"
			
			// ====================== jendaigo : start : change name format ============= //
			$fields    = array("C.employee_id", "C.agency_employee_id", "CONCAT(C.last_name, ', ', C.first_name, IF(C.ext_name='', '', CONCAT(' ', C.ext_name)), IF((C.middle_name='NA' OR C.middle_name='N/A' OR C.middle_name='-' OR C.middle_name='/'), '', CONCAT(' ', C.middle_name))) employee_name ",
							"D.employ_office_name","D.employ_position_name", "E.period_date"
			// ====================== jendaigo : end : change name format ============= //
			);
			
			$sub_table_dte = <<<EOS
				(SELECT payout_summary_id, GROUP_CONCAT(DISTINCT DATE_FORMAT(effective_date, '%M %Y') ORDER BY effective_date SEPARATOR '-') period_date
					FROM  payout_summary_dates GROUP BY payout_summary_id) 
EOS;
				
			$table    = array(
					'main'		=> 		array(
							'table'		=> 	$this->rm->tbl_payout_summary,
							'alias'		=>  'A'
					),
					't1'		=> 		array(
							'table'		=>  $this->rm->tbl_payout_employee,
							'alias'		=>  'B',
							'type'		=>	'JOIN',
							'condition' =>	'A.payroll_summary_id = B.payroll_summary_id'
					),
					't2'		=>		array(
							'table'		=>  $this->rm->tbl_employee_personal_info,
							'alias'		=>	'C',
							'type'		=>	'JOIN',
							'condition' =>	'B.employee_id = C.employee_id'
					),
					't3'		=>		array(
							'table'		=>  $this->rm->tbl_employee_work_experiences,
							'alias'		=>	'D',
							'type'		=>	'JOIN',
							'condition' =>	'C.employee_id = D.employee_id'
					),
					't4'		=>		array( 
							'table'		=>  $sub_table_dte,
							'alias'		=>	'E',
							'type'		=>	'JOIN',
							'condition' =>	'E.payout_summary_id = A.payroll_summary_id'
					)
			);
			
			$where                               	= array();
			$where["A.attendance_period_hdr_id"] 	= $params['payroll_period'];
			if($params['office_list'] != ''){
				$where['D.employ_office_id']            = array($office_list,array('IN'));
			}
			$where['B.included_flag']				= 'N';
			$where['D.active_flag']					= 'Y';
			
			$order_by 								= array('employee_name' => 'ASC');
			
			$data['records']				 		= $this->rm->get_reports_data($fields, $table, $where, TRUE, $order_by);
			
			$payroll_type_id 						= $params['payroll_type'];
			$office 								= $params['office_list'];
			
			$info 									= $this->rm->get_period($payroll_type_id);
			$infos 									= $this->rm->get_office($office);
			
			$data['date_from'] 						= $info['date_from'];
			$data['date_to'] 						= $info['date_to'];
			
			$data['office'] 						= $infos['employ_office_name'];
			$data['off'] 							= $office;
			
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

