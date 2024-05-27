<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_leave_without_pay_cert extends Main_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'rm');
		$this->load->model('common_model', 'cm');
	}
	
	public function generate_report_data($params)
	{
		try
		{
			$data   = array();
			
			// GET/STORES EMPLOYEE INFO
			$field						= array("CONCAT(first_name, IF((middle_name='NA' OR middle_name='N/A' OR middle_name='-' OR middle_name='/' OR middle_name IS NULL), '', CONCAT(' ', LEFT(middle_name,1), '.')), ' ', last_name, IF(ext_name=''  OR ext_name IS NULL, '', CONCAT(' ', ext_name))) full_name", "last_name", "gender_code", "civil_status_id") ;
			$table						= $this->rm->tbl_employee_personal_info;
			$where						= array();
			$where['employee_id']		= $params['employee_filtered'];
			$data['employee'] 			= $this->rm->get_reports_data($field, $table, $where, FALSE, NULL, NULL);
			
			// GET/STORES EMPLOYEE POSITION AND OFFICE
			$field                = array('MAX(employee_work_experience_id)','employ_position_name', 'employ_office_name');
			$table						= $this->rm->tbl_employee_work_experiences;
			$where						= array();
			$where['employee_id']		= $params['employee_filtered'];
			$data['details'] 			= $this->rm->get_reports_data($field, $table, $where, FALSE, NULL, NULL);
			
			
			// GET/STORES REPORT DATA
			$where 						= array();
			$where['A.employee_id']		= $params['employee_filtered'];
			$where['A.leave_transaction_type_id']	= LEAVE_FILE_LEAVE;
			$where['A.leave_start_date']= array($value = array($params['date_range_from'],$params['date_range_to']), array("BETWEEN"));
			$results					= $this->rm->leave_without_pay_cert($where, $date_from, $date_to);
			
			$dates	  = "";
			$lwop_sum = 0;
			$year	  = "";
			
			foreach ($results as $result)
			{
				if(EMPTY($dates))
				{
					$dates = $result['leave_dates'];
					$year  = $result['leave_years'];
					
				}else
				{
					$dates = $dates . ", " . $result['leave_dates'];
				}
				
				$lwop_sum = $lwop_sum + $result['lwop_days'];
			}
			
			$data['date_from']    = date('F j, Y', strtotime($params['date_range_from']));
			$data['date_to']      = date('F j, Y', strtotime($params['date_range_to']));
			$data['year']         = $year;
			$data['dates']        = $dates;
			$data['lwop_sum']     = $lwop_sum;
			$data['results']      = $results;
			
			$employee_id          = $params['certified_by'];
			$data['certified_by'] = $this->cm->get_report_signatory_details($employee_id);
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