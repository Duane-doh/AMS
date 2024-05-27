<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Position_description extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $prepared_by = NULL, $reviewed_by = NULL)
	{
		try
		{
			$data		= array();
			$fields 	= array();

			$fields[]	= 'A.last_name ln';
			$fields[]	= 'A.first_name fn';
			$fields[]	= 'LEFT(A.middle_name, 1) mi';
			$fields[]	= 'B.employ_office_id';
			$fields[]	= 'B.employ_office_name office';
			$fields[]	= 'B.employ_monthly_salary salary';
			$fields[]	= 'B.employ_position_name position';
			$fields[]	= 'B.employ_position_id position_id';
			$fields[]	= 'D.position_class_level_name class';
			$fields[]	= 'F.duties';
			$fields[]	= 'E.general_function plantilla_gen_function';
			$fields[]	= 'C.general_function';			
			$fields[]	= 'C.eligibility';			
			$fields[]	= 'C.education';			
			$fields[]	= 'C.experience';			
			$fields[]	= 'E.plantilla_code';			
			
			$tables = array(
				'main'      => array(
					'table'     => $this->rm->tbl_employee_personal_info,
					'alias'     => 'A',
				),
				't2'      => array(
					'table'     => $this->rm->tbl_employee_work_experiences,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.employee_id = B.employee_id'
				),
				't3'      => array(
					'table'     => $this->rm->tbl_param_positions,
					'alias'     => 'C',
					'type'      => 'JOIN',
					'condition' => 'B.employ_position_id = C.position_id'
				),
				't4'      => array(
						'table'     => $this->rm->tbl_param_position_class_levels,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.position_class_id = D.position_class_level_id'
				),
				't5'      => array(
						'table'     => $this->rm->tbl_param_plantilla_items,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_plantilla_id = E.plantilla_id' 
				),
				't6'      => array(
						'table'     => $this->rm->tbl_param_position_duties,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.position_id = F.position_id' 
				)
			);
			
			$where                          = array();
			$where["A.employee_id"] 		= $param;
			$where['B.active_flag']         = YES;
			
			$data['info']					= $this->rm->get_reports_data($fields, $tables, $where, FALSE);

			$child_office_id				= $data['info']['employ_office_id'];
			$parent_office_id				= $this->rm->get_parent_office_id($child_office_id);
			$parent_office 					= array(); 
			if(!EMPTY($parent_office_id))
				$parent_office 				= $this->rm->get_parent_office_name($parent_office_id['parent']);
			$data['other_compensations']	= $this->rm->get_other_compensations($param);
			$position_description			= $this->rm->get_position_description($param);

			
			$data['position_description']	= $position_description;
			$data['degrees']				= $this->rm->get_employee_educations($param);
			$data['eligibility']			= $this->rm->get_employee_eligibility($param);
			$data['contacts']				= explode(",",$position_description['contacts']);
			$data['working_condition']		= explode(",",$position_description['working_condition']);
			$data['parent_office']			= $parent_office;


			// $item						= explode("-",$data['info']['plantilla_code']);
			// $data['item'] 				= $item[0];

			$field                    	= array("GROUP_CONCAT(employ_position_name SEPARATOR ', ') as position_name");
			$table                    	= $this->rm->tbl_employee_work_experiences;
			$where                    	= array();
			$where['employee_id']  		= $param;
			$where['relevance_flag']  	= YES;
			$where['employ_type_flag']  = NON_DOH_GOV;
			$data['work_exp']      		= $this->rm->get_reports_data($field, $table, $where, TRUE);

			// SIGNATORIES
			$data['signatory_2']  		= $this->cm->get_report_signatory_details($prepared_by);
			$data['signatory_3']  		= $this->cm->get_report_signatory_details($reviewed_by);
			
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