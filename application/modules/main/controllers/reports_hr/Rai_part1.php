<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rai_part1 extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $salary_grade_rai = NULL, $reviewed_by = NULL)
	{
		try
		{	
				
			$date_arr 				= explode('A', $date);
			$date_from 				= $date_arr[0];
			$date_to 				= $date_arr[1];
			
			if($office != 'A')
			{
				$office_child = array();
				foreach( $office as $aRow)
				{
					$result 		= $this->rm->get_office_child('', $aRow);
					$office_child 	= array_merge($result, $office_child) ;
				}
			}	
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
			
			$data['records'] 		= $this->rm->get_personnel_movement_list($date_from, $date_to, $office_child, $movt_appointment, $salary_grade_rai);
			$data['office'] 		= $this->rm->get_office_child_list($date_from, $date_to, $office_child, $movt_appointment);			
			$office_count 			= count($office);
			// echo"<pre>";
			// print_r($office_count);
			// die();
			if($office_count == 1)
			{
				$agency					=  $this->rm->get_agency_info($office[0]);
				$data['office_name'] 	= $agency['name'];
			} 
			else
			{
				$data['office_name'] 	=  'DEPARTMENT OF HEALTH';
			}


			//DATE HEADER
			$date_from_hdr		= date_format(date_create($date_from), 'F  Y');
			$date_to_hdr		= date_format(date_create($date_to), 'F  Y');

			if ($date_from_hdr != $date_to_hdr) {
				echo $data['date_hdr'] 	= strtoupper($date_from_hdr) . ' AND ' .  strtoupper($date_to_hdr);
			}else{
				echo $data['date_hdr'] 	= strtoupper($date_from_hdr);
			}
			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($param);
			$data['prepared_by']  		= $this->cm->get_report_signatory_details($prepared_by);
			$data['reviewed_by']  		= $this->cm->get_report_signatory_details($reviewed_by);

			$data['salary_grade_rai'] 	= $salary_grade_rai;		
			
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


/* End of file Rai_part1.php */
/* Location: ./application/modules/main/controllers/reports/hr/Rai_part1.php */