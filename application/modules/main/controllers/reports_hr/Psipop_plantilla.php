<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Psipop_plantilla extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $prepared_by=NULL)
	{
		try
		{
			
			$data           		= array();

			$select_data 			= '';
			$select_data 			= "DISTINCT
								    A.plantilla_id,
									R.plantilla_level_code,
								    A.office_id,
								    B.employ_office_name,
									B.employ_monthly_salary salary,
									O.office_id division_id,
									Q.name,
								    B.employee_id,
								    B.employ_start_date employ_start_date_raw,
								    (B.employ_monthly_salary * 12) actual_amount,
								    (K.amount * 12) authorized_amount,
								    A.plantilla_code,
								    IFNULL(B.employ_position_name, Y.position_name) position,
								    IFNULL(B.employ_salary_grade, Y.salary_grade) sg,
								    IFNULL(B.employ_salary_step, Y.salary_step) step,
								    IFNULL(F.position_class_level_code,
								            V.position_class_level_code) level,
								    CONCAT(C.last_name, ',',
								            ' ',
								            C.first_name, ',',
								            ' ',
								            C.middle_name,
								            IF(C.ext_name IS NULL, '', C.ext_name)) employee_name,
								    C.gender_code,
								    G.identification_value tin,
								    L.format,
								    DATE_FORMAT(B.employ_start_date, '%m/%d/%Y') date_of_appointment,
								    DATE_FORMAT(C.birth_date, '%m/%d/%Y') birth_date,
								    D.employment_status_code status_code,
								    I.eligibility_type_flag";
			$offices 				= array();
			$offices 				= $this->rm->get_office_child('', $office);
			$group 					= 'GROUP BY A.plantilla_id';
			$order 					= 'ORDER BY sg DESC, A.plantilla_code ASC';
			$data['records']  		= $this->rm->get_plantilla_list($offices, $date, $select_data, $group, $order);
			$records 				= $data['records'];

			$total_authorized 		   = 0;
			$total_actual 			   = 0;
			$employees 				   = array();
			$emp_appt_date 			   = array();
			foreach($records AS $record)
			{
				$total_actual 		= $record['actual_amount'] + $total_actual;
				$total_authorized 	= $record['authorized_amount'] + $total_authorized;

				if($record['employee_id'])
				{
					$employees[] 	 		 = $record['employee_id'];
					$emp_appt_date[] 		 = $record['employ_start_date_raw'];
				}
			}
			$data['total_actual_amount']   	   = $total_actual;
			$data['total_authorized_amount']   = $total_authorized;

			// GET NATURE OF APPOINTMENTS
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
			//GET LAST EMPLOYMENT DATE OF EMPLOYEE

			$tables 		= array(
				'main'      => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_param_employment_status,
				'alias'     => 'B',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.employment_status_id = A.employment_status_id'
			 	)
			);	

			if($employees)
			{
				$prev_emp_data = array();
				$cnt 		   = 0;
				foreach ($employees AS $emp) 
				{
					$field                     	= array("DATE_FORMAT(MIN(A.employ_start_date), '%m/%d/%Y') original_appt_date", "(SELECT DATE_FORMAT(MAX(employ_start_date), '%m/%d/%Y') employ_start_date FROM employee_work_experiences WHERE  employ_start_date < '". $emp_appt_date[$cnt] ."'
        AND employee_id = ".$emp." AND employ_personnel_movement_id IN ('642', '645')) last_promotion_date", "employee_id");
					//$table                     	= $this->rm->tbl_employee_work_experiences;
					$where                     	= array();
					$where['A.employee_id']	   	= $emp;
					$where['B.jo_flag']	   		= NO;
					$where['A.employ_type_flag']= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT), array("IN"));
					$result	   				  	= $this->rm->get_reports_data($field, $tables, $where, FALSE);
					$prev_emp_data[] 		   	= $result;
					$cnt ++;
				}
			}

			//die();

			$data['prev_start_date']= $prev_emp_data;

			$fields 				 		= array('sys_param_name', 'sys_param_value');
			$table 					 		= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where					 		= array();
			$where['sys_param_type'] 		= 'ELIGIBILITY_TYPE_FLAG';
			$data['eligibility_type_flag'] 	= $this->rm->get_reports_data($fields, $table, $where, TRUE);

			$select_data 			= '';
			$select_data 			= 'DISTINCT A.office_id, B.employ_office_name';
			$group 					= 'GROUP BY A.office_id';
			$data['office'] 		= $this->rm->get_plantilla_list($offices, $date, $select_data, $group);

			$select_data 			= '';
			$select_data 			= 'DISTINCT O.office_id, Q.name';
			$group 					= 'ORDER BY Q.name';
			$data['division'] 		= $this->rm->get_plantilla_list($offices, $date, $select_data, $group);
			
			$office_director 		= array_search('OFFICE OF THE DIRECTOR', array_column($data['division'], 'name'));
			
			if($office_director !== FALSE)
			{
				$director_office 	= $data['division'][$office_director];
				unset($data['division'][$office_director]);
				array_unshift($data['division'], $director_office);
			}

			$data['certified_by']  		= $this->cm->get_report_signatory_details($param);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($prepared_by);

			$data['agency']  		= $this->rm->get_agency_info($office);
			$data['date']    		= $date;
		
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


/* End of file Psipop_plantilla.php */
/* Location: ./application/modules/main/controllers/reports/hr/Psipop_plantilla.php */