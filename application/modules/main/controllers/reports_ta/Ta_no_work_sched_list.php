<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_no_work_sched_list extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'reports_ta');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data                 	= array();
			$attendance = $this->reports_ta->get_employee_no_work_sched_list($params['payroll_type_work_sched'],$params['date_range_from'],$params['date_range_to']);
			$employee_list = array();
			foreach ($attendance as $value) {
				$work_sched = $this->reports_ta->get_employee_work_sched($value['employee_id'],$params['date_range_from'],$params['date_range_to']);
				if(empty($work_sched))
				{
					array_push($employee_list, $value['employee_id']);
				}
			}
			
			// echo"<pre>";
			// print_r($employee_list);
			// die();
			
			
			// $employees_with_work_sched = array();
			// foreach ($attendance as $key => $value) {
			// 	if(!in_array($value['employee_id'],$employee_list))
			// 	{
			// 		array_push($employee_list, $value['employee_id']);
			// 	}

			// 	$biometric_logs = modules::load('main/biometric_logs');
			// 	$result         = $biometric_logs->check_work_sched($value['employee_id'],$value['attendance_date']);
			// 	if($result == "TRUE")
			// 	{
			// 		if(!in_array($value['employee_id'],$employees_with_work_sched))
			// 		{
			// 			array_push($employees_with_work_sched, $value['employee_id']);
			// 		}
					
			// 	}
				
			// }
			// foreach($employees_with_work_sched as $row)
			// {
			// 	$key_add = array_search($row, $employee_list);
			// 	unset($employee_list[$key_add]);
			// }
			$list = implode(',' , $employee_list);
			$employee_detail = $this->reports_ta->get_employee_no_work_sched_details($list);
				
			
			$data['employees_list'] = $employee_detail;
			return $data;
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


/* End of file Ta_leave_card.php */
/* Location: ./application/modules/main/controllers/reports/ta/Ta_leave_card.php */