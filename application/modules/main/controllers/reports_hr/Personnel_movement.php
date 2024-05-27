<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personnel_movement extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($param = NULL, $office = NULL, $date = NULL, $prepared_by = NULL)
	{
		try
		{				
			$date_arr 				= explode('A', $date);
			$date_from 				= $date_arr[0];
			$date_to 				= $date_arr[1];

			if($office != 'A')
			{
				$office_child 		= $this->rm->get_office_child('', $office);
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

			$data['records'] 		= $this->rm->get_personnel_movement_list($date_from, $date_to, $office_child, $movt_appointment);
			$agency					=  $this->rm->get_agency_info($office);
			$data['office_name'] 	= !EMPTY($agency) ? $agency['name'] : 'DEPARTMENT OF HEALTH - CENTRAL OFFICE';

			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($param);			
			$data['prepared_by']  		= $this->cm->get_report_signatory_details($prepared_by);
			
			$date_from_hdr		= date_format(date_create($date_from), 'F d, Y');
			$date_to_hdr		= date_format(date_create($date_to), 'F d, Y');
			$data['date_hdr'] 	= $date_from_hdr . ' - ' .  $date_to_hdr;
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


/* End of file Personnel_movement.php */
/* Location: ./application/modules/main/controllers/reports/hr/Personnel_movement.php */