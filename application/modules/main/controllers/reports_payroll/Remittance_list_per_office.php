<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remittance_list_per_office extends Main_Controller {
	
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
			$data['month_text']	= $params['month_text'];
			$data['year']		= $year;

			//CHECK IF PRIMARY
			$ecip = '';
			if(in_array(DEDUC_ECIP, $params['deduction_type_multi'])) 
			{
			   $ecip = TRUE;
			}
			else
			{
				$ecip = FALSE;
			}
			$data['ecip'] = $ecip;

			$tables = array(
				'main'  	=> array (
					'table' 	=>	$this->rm->tbl_remittances,
					'alias'		=> 'A'
				),
				't1'		=> array (
					'table'		=> $this->rm->tbl_param_remittance_types,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.remittance_type_id = B.remittance_type_id'
				),
				't2'		=> array (
					'table'		=> $this->rm->tbl_remittance_details,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.remittance_id = C.remittance_id'
				),
				't3'		=> array (
					'table'		=> $this->rm->tbl_payout_details,
					'alias'		=> 'D',
					'type'		=> 'LEFT JOIN',
					'condition' => 'C.payroll_dtl_id = D.payroll_dtl_id'
				),
				't4'		=> array (
					'table'		=> $this->rm->tbl_payout_header,
					'alias'		=> 'E',
					'type'		=> 'LEFT JOIN',
					'condition' => 'D.payroll_hdr_id = E.payroll_hdr_id'
				),
				't5'		=> array (
					'table'		=> $this->rm->tbl_employee_work_experiences,
					'alias'		=> 'F',
					'type'		=> 'LEFT JOIN',
					'condition' => "E.employee_id = F.employee_id AND F.active_flag = 'Y'"
				)
			);

			$select_fields            = array('F.employee_id', 'E.employee_name', 'D.reference_text', 'F.employ_monthly_salary', 'E.position_name', 'SUM(D.amount) amount', 'SUM(D.employer_amount) employer_amount', 'E.office_name', 'B.remittance_type_name', 'CONCAT(DATE_FORMAT(A.deduction_start_date,\'%M\'), \', \', DATE_FORMAT(A.deduction_end_date,\'%Y\')) remittance_period', 'E.office_id', 'D.paid_count', '(SELECT payment_count FROM employee_deductions H WHERE D.deduction_id = H.deduction_id AND E.employee_id = H.employee_id) payment_count');

			$where                    		= array();
			$where['A.remittance_type_id'] 	= $params['remittance_type'];
			if(!$ecip)
			{
				$where['D.amount'] 				= array('0.00', array("!="));
			}
			$where['D.deduction_id'] 		= array($params['deduction_type_multi'], array('IN'));
			$where['A.year_month'] 	  		= $year_month;
			
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
			if(!EMPTY($params['office_list']))
			{
				$data['office_list'] = $params['office_list'];
				$where['E.office_id']     = array($this->rm->get_office_child('', $params['office_list']),array('IN'));
			}
			$group_by                 = array('E.employee_id');
			$order_by				  = array('E.employee_name' => 'ASC');
			$records         		  = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);
			$data['records'] 		  = $records;

			$employees 			= array();
			foreach($records AS $rec)
			{
				$employees[] 	= $rec['employee_id'];
			}

			// GET OTHER DETAIL VALUE	
			$deduction_ids     			= $params['deduction_type_multi'];
			$other_ded_dtl_val  		= $this->rm->get_other_ded_dlt_value($employees, $deduction_ids);
			$data['other_ded_dtl_val']  = $other_ded_dtl_val;

			// CHECK IF PK
			$field                    	= array("DISTINCT pk_flag");
			$table                    	= $this->rm->tbl_param_other_deduction_details;
			$where                    	= array();
			$where['deduction_id']		= array($params['deduction_type_multi'], array('IN'));
			$pk_flag      				= $this->rm->get_reports_data($field, $table, $where, TRUE);

			$primary_key 		= array();
			foreach($pk_flag AS $pk)
			{
				$primary_key[] 	= $pk['pk_flag'];
			}

			//CHECK IF PRIMARY
			if(in_array("Y", $primary_key)) 
			{
			   $data['primary'] = TRUE;
			}
			else
			{
				$data['primary'] = FALSE;
			}

			// CHECK IF DEDUCTION IS STATUTORY
			$data['statutory'] = ($other_ded_dtl_val[0]['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SYSTEM) ? TRUE : FALSE;

			$field                    	= array("employer_share_flag","statutory_flag");
			$table                    	= $this->rm->tbl_param_deductions;
			$where                    	= array();
			$where['deduction_id']		= array($params['deduction_type_multi'], array('IN'));
			$employer_share_flag      	= $this->rm->get_reports_data($field, $table, $where, TRUE);

			$employer_share 			= array();
			$display_inst_cnt 			= array();
			foreach($employer_share_flag AS $emp_share)
			{
				if($emp_share['employer_share_flag'])
				{
					$employer_share[] 	= $emp_share['employer_share_flag'];
				}
				if($emp_share['statutory_flag'])
				{
					$display_inst_cnt[] 	= $emp_share['statutory_flag'];
				}
			}
			// FOR REPORT HEADER			
			$field                    	= array("remittance_type_name");
			$table                    	= $this->rm->tbl_param_remittance_types;
			$where                    	= array();
			$where['remittance_type_id']= $params['remittance_type'];
			$header      				= $this->rm->get_reports_data($field, $table, $where, FALSE);
			$data['header'] 			= $header['remittance_type_name'];

			// CHECK IF DEDUCTION GOVT SHARE
			$emp_share_flag = FALSE;
			if(in_array("Y", $employer_share)) 
			{
			   $emp_share_flag = TRUE;
			}
			// CHECK IF DEDUCTION GOVT SHARE
			$inst_cnt_flag = TRUE;
			if(in_array("Y", $display_inst_cnt)) 
			{
			   $inst_cnt_flag = FALSE;
			}
			/*
			| IF Remittance Type is UKKS
			| Remove Installment Display
			*/
			$ukks_remittance_type_id = 16;
			if($params['remittance_type'] == $ukks_remittance_type_id)
				$inst_cnt_flag = FALSE;

			$data['show_employer_share']= $emp_share_flag;
			$data['show_installment_flag']= $inst_cnt_flag;

			$data['title_hdr'] = $this->_get_report_title($params['remittance_type'], $params['deduction_type_multi']);

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

	private function _get_report_title($remittance_type, $deduction_type_multi)
	{

		try
		{	
			$title_hdr = array();					
			// FOR REPORT HEADER			
			$field                  = array("GROUP_CONCAT(deduction_name SEPARATOR ' / ') deduction_name");
			$table                  = $this->rm->tbl_param_deductions;
			$where                  = array();
			$where['deduction_id']	= array($deduction_type_multi, array('IN'));
			$title      			= $this->rm->get_reports_data($field, $table, $where, FALSE);
			$title_hdr[] 			= $title['deduction_name'];

			$table_remittance 	= array(
				'main'  		=> array (
					'table' 	=>	$this->rm->tbl_param_remittance_type_deductions,
					'alias'		=> 'A'
				),
				't1'			=> array (
					'table'		=> $this->rm->tbl_param_deductions,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.deduction_id = B.deduction_id'
				)
			);

			$field                  	 	= array("A.deduction_id");
			$where                  	 	= array();
			$where['A.remittance_type_id'] 	= $remittance_type;
			$where['B.employ_type_flag'] 	= array(array(PAYROLL_TYPE_FLAG_ALL, PAYROLL_TYPE_FLAG_REG), array('IN'));
			$deduction_id_cnt    		 	= $this->rm->get_reports_data($field, $table_remittance, $where, TRUE);

			if(count($deduction_id_cnt) == count($deduction_type_multi)) {
				$title_hdr[] = TRUE;
			}
			elseif (count($deduction_id_cnt) < count($deduction_type_multi)) {
				$title_hdr[] = TRUE;
			}
			else {
				$title_hdr[] = FALSE;
			}

		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return $title_hdr;
	}

}

/* End of file Remittance_list_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Remittance_list_per_office.php */