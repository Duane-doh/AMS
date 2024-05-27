<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_position extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $status = NULL)
	{
		try
		{
			
			$date_arr 		= explode('A', $date);
			$date_from 		= $date_arr[0];
			$date_to 		= $date_arr[1];
			
			$table_max_date = <<<EOS
					(SELECT MAX(M.employ_start_date) max_start_date, M.employee_id from employee_work_experiences M
			        WHERE M.employ_start_date <= '$date_to'
			        AND IFNULL(M.employ_end_date,
			            IF('$date_to' < CURRENT_DATE,
			                '$date_to',
			                CURRENT_DATE)) > '$date_from'
			                GROUP BY M.employee_id)
EOS;
			
			$data           = array();
			$tables 		= array(
				'main'      => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.employee_id = B.employee_id'
			 	),
				't5'        => array(
				'table'     => $this->rm->tbl_param_employment_status,
				'alias'     => 'E',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.employment_status_id = E.employment_status_id'
			 	),
				't6'        => array(
				'table'     => $this->rm->tbl_param_positions,
				'alias'     => 'F',
				'type'      => 'LEFT JOIN',
				'condition' => 'F.position_id = B.employ_position_id'
			 	),
				't7'        => array(
				'table'     => $this->rm->tbl_param_position_levels,
				'alias'     => 'G',
				'type'      => 'LEFT JOIN',
				'condition' => 'G.position_level_id = F.position_level_id'
			 	),
				't8'        => array(
				'table'     => $table_max_date,
				'alias'     => 'H',
				'type'      => 'JOIN',
				'condition' => 'H.employee_id = B.employee_id AND H.max_start_date = B.employ_start_date'
			 	)				
			);

			$select_fields   = array();
			$select_fields[] = 'B.employ_start_date';
			$select_fields[] = 'A.agency_employee_id AS personnel_number';
			
			// $select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
			// ====================== jendaigo : start : change name format ============= //
			$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) employee_name';
			// ====================== jendaigo : end : change name format ============= //
			
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name AS office' : 'B.employ_office_name AS office';
			$select_fields[] = 'E.employment_status_name AS service_status';
			$select_fields[] = 'G.position_level_name';
			$select_fields[] = 'B.employ_position_name as position_name';
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_id office_id' : 'B.employ_office_id office_id';
			
			$where 						  																		= array();
			$where['F.position_level_id'] 																		= $param;
			$where['B.employ_type_flag']  																		= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
			
			if($status == REGULAR_PERMANENT) {
				
				$where['E.jo_flag'] = NO;
			}
			else
			{
				$where['E.jo_flag'] = YES;
			}


			if($office != 'A')
			{
				if(USE_ADMIN_OFFICE == YES)
				{
					$where['B.admin_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
				else
				{					
					$where['B.employ_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
			}

			$office_fields = array();
			$office_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_id office_id' : 'B.employ_office_id office_id';
			$office_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name AS office' : 'B.employ_office_name AS office';

			$order_by 			= array('office_id' => 'ASC', 'employee_name' => 'ASC');
			$group_by 			= array('A.employee_id');
			$data['records'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);

			$order_by 			= array('office_id' => 'ASC');
			$group_by 			= array('office_id', 'office');
			$data['office'] 	= $this->rm->get_reports_data($office_fields, $tables, $where, TRUE, $order_by, $group_by);
			
			$data['agency']		= $this->rm->get_agency_info($office);

			//DATE HEADER
			$date_from_hdr		= date_format(date_create($date_from), 'F d, Y');
			$date_to_hdr		= date_format(date_create($date_to), 'F d, Y');
			$data['date_hdr'] 	= 'For the period of ' . strtoupper($date_from_hdr) . ' - ' .  strtoupper($date_to_hdr);

			$field                    	= array("position_level_name");
			$table                    	= $this->rm->tbl_param_position_levels;
			$where                    	= array();
			$where['position_level_id'] = $param;
			$data['level']      	 	= $this->rm->get_reports_data($field, $table, $where, FALSE);
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


/* End of file List_position.php */
/* Location: ./application/modules/main/controllers/reports/hr/List_position.php */