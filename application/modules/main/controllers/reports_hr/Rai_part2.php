<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rai_part2 extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	// public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $prepared_by = NULL, $reviewed_by = NULL)
	// {
	// 	try
	// 	{
			
	// 		$date_arr 				= explode('A', $date);
	// 		$date_from 				= $date_arr[0];
	// 		$date_to 				= $date_arr[1];
			
	// 		if($office != 'A')
	// 		{
	// 			$office_child 		= $this->rm->get_office_child('', $office);			
	// 		}

	// 		$field                    	= array("sys_param_value");
	// 		$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
	// 		$where                    	= array();
	// 		$where['sys_param_type']  	= 'MOVT_APPOINTMENT';
	// 		$appointment    			= $this->rm->get_reports_data($field, $table, $where, TRUE);
	// 		$movt_appointment 			= array();
	// 		foreach($appointment AS $app)
	// 		{
	// 			$movt_appointment[] 	= $app['sys_param_value'];
	// 		}

	// 		$data['records'] 		= $this->rm->get_appointment_issued2_list($date_from, $date_to, $office_child, $movt_appointment);
	// 		$data['office'] 		= $this->rm->get_office_child_rai_list($date_from, $date_to, $office_child, $movt_appointment);
	// 		$agency					= $this->rm->get_agency_info($office);
	// 		$data['office_name'] 	= !EMPTY($agency) ? $agency['name'] : 'DEPARTMENT OF HEALTH - CENTRAL OFFICE';

	// 		//DATE HEADER
	// 		$date_from_hdr		= date_format(date_create($date_from), 'F d, Y');
	// 		$date_to_hdr		= date_format(date_create($date_to), 'F d, Y');
	// 		$data['date_hdr'] 	= 'For the period of ' . strtoupper($date_from_hdr) . ' - ' .  strtoupper($date_to_hdr);

	// 		$fields 				 		= array('sys_param_name', 'sys_param_value');
	// 		$table 					 		= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
	// 		$where					 		= array();
	// 		$where['sys_param_type'] 		= 'ELIGIBILITY_TYPE_FLAG';
	// 		$data['eligibility_type_flag'] 	= $this->rm->get_reports_data($fields, $table, $where, TRUE);

	// 		// SIGNATORIES
	// 		$data['certified_by']  		= $this->cm->get_report_signatory_details($param);
	// 		$data['prepared_by']  		= $this->cm->get_report_signatory_details($prepared_by);
	// 		$data['reviewed_by']  		= $this->cm->get_report_signatory_details($reviewed_by);
	// 	}
	// 	catch (PDOException $e)
	// 	{
	// 		$message = $e->getMessage();
	// 		RLog::error($message);
	// 	}
	// 	catch (Exception $e)
	// 	{
	// 		$message = $e->getMessage();
	// 		RLog::error($message);
	// 	}	

	// 	return $data;			
	// }

public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $reviewed_by = NULL, $prepared_by = NULL)
	{
		try
		{
			
			$data           = array();

			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'MOVT_APPOINTMENT';
			$appointment    			= $this->rm->get_reports_data($field, $table, $where, TRUE);
			$movt_appointment 			= array();
			foreach($appointment AS $app)
			{
				$movt_appointment[] 	= $app['sys_param_value'];
			}
			
			$data['emp_record']  		= $this->rm->get_appointment_cert_details($param, $movt_appointment);
			$data['record'] 		= $this->rm->get_appointment_issued2_list($param, $movt_appointment);
			$data['wexp_record']  		= $this->rm->rai_part2_details($param);

			$data['record']['employ_monthly_salary_by_word'] = number_to_words($data['record']['employ_monthly_salary']);

			// GET ORIGINAL PERSONNEL MOVEMENT
			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'MOVT_ORIGINAL';
			$data['movt_original']    	= $this->rm->get_reports_data($field, $table, $where, FALSE);


			$fields 				 		= array('sys_param_name', 'sys_param_value');
			$table 					 		= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where					 		= array();
			$where['sys_param_type'] 		= 'ELIGIBILITY_TYPE_FLAG';
			$data['eligibility_type_flag'] 	= $this->rm->get_reports_data($fields, $table, $where, TRUE);

			

			// SIGNATORIES			
			$data['certified_by']  		= $this->cm->get_report_signatory_details($date);
			$data['prepared_by']  		= $this->cm->get_report_signatory_details($prepared_by);
			$data['reviewed_by']  		= $this->cm->get_report_signatory_details($reviewed_by);
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


/* End of file Rai_part2.php */
/* Location: ./application/modules/main/controllers/reports/hr/Rai_part2.php */