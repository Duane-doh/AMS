<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atm_alpha_list extends Main_Controller {
	
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
			$data				= array();
			
			$tables				= array(
			
					'main'		=> array(
							'table'		=> $this->rm->tbl_payout_summary,
							'alias' 	=> 'A'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias' 	=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'B.payroll_hdr_id = C.payroll_hdr_id'
					),
					't4'		=> array(
							'table'		=> $this->rm->tbl_employee_identifications,
							'alias' 	=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.employee_id = D.employee_id AND D.identification_type_id = ' . BANKACCT_TYPE_ID
					),
					't5'		=> array(
							'table'		=> $this->rm->tbl_employee_personal_info,
							'alias' 	=> 'E',
							'type'		=> 'JOIN',
							'condition'	=> 'B.employee_id =  E.employee_id'
					),
					't6'		=> array(
							'table'		=> $this->rm->tbl_payout_summary_dates,
							'alias' 	=> 'F',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id =  F.payout_summary_id AND C.effective_date = F.effective_date'
					)
			);
			
			$where                               = array();
			$where['A.bank_id']                  = $params['bank'];
			$where['A.attendance_period_hdr_id'] = $params['payroll_period'];
			$where['F.payout_summary_date_id']   = $params['payout_date'];
			
			//STORES EFFECTIVE DATE
			$fields 								= array('C.effective_date');
			$data['effective_date']					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, array('C.effective_date' => 'ASC'), array('C.effective_date'));
			
			//STORES EMPLOYEE DETAILS
			// $fields									= array('B.payroll_hdr_id', 'D.identification_value acct_no', 'CONCAT(E.last_name, " ", E.ext_name, ", ", E.first_name, " ", E.middle_name) full_name','LEFT(D.identification_value, (3)) branch', 'B.batch_code');
			// ====================== jendaigo : start : change name format ============= //
			$fields									= array('B.payroll_hdr_id', 'D.identification_value acct_no', 'CONCAT(E.last_name, ", ", E.first_name, IF(E.ext_name="", "", CONCAT(" ", E.ext_name)), IF((E.middle_name="NA" OR E.middle_name="N/A" OR E.middle_name="-" OR E.middle_name="/"), "", CONCAT(" ", E.middle_name))) full_name');
			// ====================== jendaigo : end : change name format ============= //
			
			$group_by								= array('B.payroll_hdr_id');
			$employee_details						= $this->rm->get_reports_data($fields, $tables, $where, TRUE, array('full_name' => 'ASC'), $group_by);
			
			// IDENTIFICATION TYPE FORMAT
			$format  = $this->rm->get_reports_data(array('format'), $this->rm->tbl_param_identification_types, array('identification_type_id' => BANKACCT_TYPE_ID), FALSE);
			
			foreach ($data['effective_date'] as $date)
			{
				$effective_date	= $date['effective_date'];
				//STORES COMPENSATION PER EFFECTIVE DATE
				$compensation[$effective_date] = $this->get_compensation($tables, $where, $date);
			
				//STORES DEDUCTION PER EFFECTIVE DATE
				$deduction[$effective_date]    = $this->get_deduction($tables, $where, $date);
			
				foreach ($employee_details as $dtl)
				{
			
					$com_amt = ISSET($compensation[$effective_date][$dtl['payroll_hdr_id']]) ? $compensation[$effective_date][$dtl['payroll_hdr_id']] : 0;
					$ded_amt = ISSET($deduction[$effective_date][$dtl['payroll_hdr_id']]) ? $deduction[$effective_date][$dtl['payroll_hdr_id']] : 0;
			
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['acct_no']	= $dtl['acct_no'];
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['full_name']	= $dtl['full_name'];
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['amount']		= $com_amt - $ded_amt;
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['branch']		= $params['branch_code'];
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['batch_code']	= $params['alphalist_batch_no'];
				}
					
			}
			
			// GET BANK DETAILS
			$fields				= array('bank_name', 'branch_code','branch_detail_code');
			$table				= $this->rm->tbl_param_banks;
			$where 				= array();
			$where['bank_id']	= $params['bank'];
			$data['bank']		= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);
			
			
			// GET SIGNATORY DETAILS
			$fields				= array('batch_code', 'approved_by', 'certified_by', 'certified_cash_by');
			$table				= $this->rm->tbl_payout_summary;
			$where 				= array();
			$where['attendance_period_hdr_id']	= $params['payroll_period'];
			$data['details']	= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);

			if($params['signatory_a'])
				$data['certified_by']		= $this->cm->get_report_signatory_details($params['signatory_a']);

			if($params['signatory_b'])
				$data['approved_by']		= $this->cm->get_report_signatory_details($params['signatory_b']);

			if($params['signatory_c'])
				$data['certified_cash_by']	= $this->cm->get_report_signatory_details($params['signatory_c']);

			$data['batch_code'] = $params['alphalist_batch_no'];
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
	
	
	public function get_compensation($tables, $where, $date)
	{
		$fields						= array('B.payroll_hdr_id', 'SUM(C.amount) amount');
		$where['C.effective_date']	= $date['effective_date'];
		$where['C.compensation_id']	= "IS NOT NULL";
		$com_temp					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, array('B.payroll_hdr_id'));
	
		$com_final = array();
		foreach ($com_temp as $temp)
		{
			$com_final[$temp['payroll_hdr_id']] = $temp['amount'];
		}
	
		return $com_final;
	}
	
	public function get_deduction($tables, $where, $date)
	{
		$fields						= array('B.payroll_hdr_id', 'SUM(C.amount) amount');
		$where['C.effective_date']	= $date['effective_date'];
		$where['C.deduction_id']	= "IS NOT NULL";
		$ded_temp				 	= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, array('B.payroll_hdr_id'));
			
		$ded_final = array();
		foreach ($ded_temp as $temp)
		{
			$ded_final[$temp['payroll_hdr_id']] = $temp['amount'];
		}
	
		return $ded_final;
	}
	public function create_report_dbf()
	{

		try
		{

			$params  = get_params();
			$status  = FALSE;
			$message = "";

			# Constants for dbf field types
			define ('BOOLEAN_FIELD',   'L');
			define ('CHARACTER_FIELD', 'C');
			define ('DATE_FIELD',      'D');
			define ('NUMBER_FIELD',    'N');
			
			# Constants for dbf file open modes
			define ('READ_ONLY',  '0');
			define ('WRITE_ONLY', '1');
			define ('READ_WRITE', '2');
			
			$filename = "Atm_alpha_list_".date('Ymdhis').".dbf";
			# Path to dbf file
			$db_file = PATH_DBF_REPORT.$filename;
			
			# dbf database definition
			# Each element in the first level of the array represents a row
			# Each array stored in the various elements represent the properties for the row
			
			$dbase_definition = array (
			   array ('ACCT_NO',  CHARACTER_FIELD, 10), # string
			   array ('ACCT_NAME',  CHARACTER_FIELD, 40), # string
			   array ('AMOUNT',  CHARACTER_FIELD, 15),  # string
			   array ('BRANCH_CD',  CHARACTER_FIELD, 3),    # number (length, precision)
			   array ('BATCH_NO', CHARACTER_FIELD, 5),     # number (length, precision)
			   array ('FILLER', CHARACTER_FIELD, 7)     # number (length, precision)
			);
			# create dbf file using the
			$create = @ dbase_create($db_file, $dbase_definition)
		   		or die ("Could not create requested dbf file.");

			# open dbf file for reading and writing
			$id = @ dbase_open ($db_file, READ_WRITE)
				or die ("Could not create requested dbf file.");

			$data = $this->generate_report_data($params);

			$effective_dates = $data['effective_date'];
			$results         = $data['results'];

			foreach ($effective_dates as $date){
				foreach ($results[$date['effective_date']] as $result)
				{
					/*
					 * AMOUNT PADDING
					 * REMOVE DECIMAL POINT AND COMMA
					 */
					if($result['amount'] > 0)
					{
						$amount = ($result['amount'] > 0) ? $result['amount'] : 0;
						$amount = number_format($amount,2);
						$amount = str_replace(",", "", $amount);
						$amount = str_replace(".", "", $amount);
						$length = 15 - strlen($amount);
						$amount = str_repeat("0",$length).$amount;

						# Records to insert into the dbf file 
						$insert_record   = array ();
						$insert_record[] = ($result['acct_no']) ? $result['acct_no'] : "0000000000";
						$insert_record[] = ($result['full_name']) ? str_replace('Ñ', 'N', $result['full_name']) : "";
						$insert_record[] = $amount;
						$insert_record[] = ($result['branch']) ? $result['branch'] : "";
						$insert_record[] = ($result['batch_code']) ? $result['batch_code'] : "";
						$insert_record[] = "";

						$succes_create = dbase_add_record ($id, $insert_record)
							or die ("An error occured while creating the requested dbf file.");
					}
					
				}
			}
			# close the dbf file
			dbase_close($id);
			$status = TRUE;
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
		$status = (EMPTY($message)) ? TRUE : FALSE;
		$info = array(
				"status"   => $status,
				"msg"      => $message,
				"filename" => $filename
		);
		echo json_encode($info);	
	}
}


/* End of file Atm_alpha_list.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Atm_alpha_list.php */