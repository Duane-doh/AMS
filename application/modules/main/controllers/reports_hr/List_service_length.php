<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_service_length extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $status= NULL)
	{
		try
		{
			$data                = array();
			$param_arr           = explode('-', $param);
			$service_length_from = $param_arr[0];
			$service_length_to   = $param_arr[1];

			$data['from']   	 = $service_length_from;
			$data['to']     	 = $service_length_to;

			$data['date']  		 = $date;
			$data['agency']		 = $this->rm->get_agency_info($office);

			if($office != 'A')
			{
				$offices  = $this->rm->get_office_child('', $office);				
			}

			if($status == REGULAR_PERMANENT) 
			{
				
				$jo_flag = NO;
			}
			else
			{
				$jo_flag = YES;
			}

			$records 		= $this->rm->get_employee_service_length($offices, $date, $jo_flag);

			$result = array();
			$result_office = array();
			foreach($records as $rec)
			{
				if ($rec['service_length'] >= $service_length_from AND $rec['service_length'] <= $service_length_to)
				{
					$result[] = $rec;
					$result_office[] = $rec['office_id'];
				}
			}

			$data['records'] = $result;

			$table_offices 	= array(
				'main'      => array(
				'table'     => $this->rm->tbl_param_offices,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.org_code = B.org_code'
			 	)					
			); 
			$office_fields              = array("A.office_id", "B.name office");
			$where                    	= array();
			$where['A.office_id']  		= array($result_office, array('IN'));
			$data['office']    			= $this->rm->get_reports_data($office_fields, $table_offices, $where, TRUE);
			
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


/* End of file List_service_length.php */
/* Location: ./application/modules/main/controllers/reports/hr/List_service_length.php */