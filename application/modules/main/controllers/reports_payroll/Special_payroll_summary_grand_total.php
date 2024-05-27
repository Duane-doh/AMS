<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll_summary_grand_total extends Main_Controller {
	
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
			$field                             = array("compensation_name") ;
			$table                             = $this->rm->tbl_param_compensations;
			$where                             = array();
			$where["compensation_id"] 		   = $params['compensation_type'];
			$data['compensation_details']	   = $this->rm->get_reports_data($field, $table, $where, FALSE);

			$where["parent_compensation_id"]   = $params['compensation_type'];
			$data['compensation_sub_details']  = $this->rm->get_reports_data($field, $table, $where, FALSE);



			$tables 		= array(

				'main'      => array(
				'table'     => $this->rm->tbl_remittances,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_remittance_details,
				'alias'     => 'B',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.remittance_id = B.remittance_id'
			 	),
				't3'        => array(
				'table'     => $this->rm->tbl_payout_details,
				'alias'     => 'C',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
			 	),
				't4'        => array(
				'table'     => $this->rm->tbl_payout_header,
				'alias'     => 'D',
				'type'      => 'LEFT JOIN',
				'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
			 	), 
				't5'        => array(
				'table'     => $this->rm->tbl_param_remittance_status,
				'alias'     => 'E',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.remittance_status_id = E.remittance_status_id'
			 	),	
			 	't6'        => array(
				'table'     => $this->rm->tbl_param_compensations,
				'alias'     => 'F',
				'type'      => 'LEFT JOIN',
				'condition' => 'C.compensation_id = F.compensation_id'
			 	),	
			 	't7'        => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'G',
				'type'      => 'LEFT JOIN',
				'condition' => 'D.employee_id = G.employee_id'
			 	),
			 	't8'        => array(
				'table'     => $this->rm->tbl_payout_details,
				'alias'     => 'H',
				'type'      => 'LEFT JOIN',
				'condition' => 'H.compensation_id = F.parent_compensation_id'
			 	)		
			);

			$select_fields   = array();
			$select_fields[] = 'D.employee_name';  
			$select_fields[] = 'D.position_name';
			$select_fields[] = 'G.employ_salary_grade'; 
			$select_fields[] = 'H.amount as amount'; 
			$select_fields[] = 'C.amount as other_amount'; 
			$select_fields[] = 'H.amount + C.amount as net_amount'; 
			
			$where         	= array();  
			$where['F.parent_compensation_id']  = $params['compensation_type'];
			

			$group_by  = array('D.employee_id');

			$data['records'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, NULL, $group_by); 

			
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


/* End of file Special_payroll_summary_grand_total.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Special_payroll_summary_grand_total.php */