<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pds_report extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('pds_model', 'pds');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL, $format = 'pdf')
	{
		try
		{
			
			$data                  				 = array();
			$id                                  = $param;
			$id 								 = $this->hash($id);

			// GET CITIZENSHIP BASIS
			$field                       		 = array("sys_param_name", "sys_param_value");
			$table					 			 = $this->pds->db_core.".".$this->pds->tbl_sys_param;
			$where                       		 = array();
			$where['sys_param_type'] 			 = 'CITIZENSHIP_BASIS';
			$citizenship_basis      			 = $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['citizenship_basis'] 			 = $citizenship_basis;					

			$data['personal_info']				 = $this->pds->get_pds_personal_info($id);

			$field                               = array("*");
			$table                               = $this->pds->db_core.'.'.$this->pds->tbl_param_genders;
			$where                               = array();
			$data['per_params']['gender']        = $this->pds->get_general_data($field, $table, $where, TRUE);

			$field                               = array("*") ;
			$table                               = $this->pds->tbl_param_civil_status;
			$where                               = array();
			$civil_status  				 		 = $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['civil_status']  				 = $civil_status;

			//GET MUNICIPALITY PROVINCE AND REGION 
			$tables_address 	= array(
				'main'			=> array(
					'table'		=> $this->pds->tbl_employee_addresses,
					'alias'		=> 'A',
				),
				't1'			=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_barangays,
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.barangay_code = B.barangay_code',
				),
				't2'			=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_municities,
					'alias'		=> 'C',
					'type'		=> 'left join',
					'condition'	=> 'A.municity_code = C.municity_code',
				),
				't3'			=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_provinces,
					'alias'		=> 'D',
					'type'		=> 'left join',
					'condition'	=> 'A.province_code = D.province_code',
				),
				't4'			=> array(
					'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_regions,
					'alias'		=> 'E',
					'type'		=> 'left join',
					'condition'	=> 'A.region_code = E.region_code',
				)
			);
			$field = array (
					"A.address_type_id, A.postal_number, A.address_value, B.barangay_name, C.municity_name, D.province_name, E.region_name, A.region_code, A.province_code" 
			);
			$where                       	  = array();
			$key                         	  = $this->get_hash_key('A.employee_id');
			$where[$key]                 	  = $id;
			$where['address_type_id'] 	 	  = RESIDENTIAL_ADDRESS;
			$residential_address_info         = $this->pds->get_general_data($field, $tables_address, $where, FALSE);
			$data['residential_address_info'] = $residential_address_info;

			$residential_address_value 		  = $residential_address_info['address_value'];
			$address_value_parts 			  = explode('|', $residential_address_value);
			$data['residential_house_no'] 	  = $address_value_parts[0]; 
			$data['residential_street'] 	  = $address_value_parts[1]; 
			$data['residential_subdivision']  = $address_value_parts[2]; 
	
			$where                       	  = array();
			$key                         	  = $this->get_hash_key('A.employee_id');
			$where[$key]                 	  = $id;
			$where['address_type_id'] 	 	  = PERMANENT_ADDRESS;
			$permanent_address_info           = $this->pds->get_general_data($field, $tables_address, $where, FALSE);
			$data['permanent_address_info']   = $permanent_address_info;

			$permanent_address_value 		  = $permanent_address_info['address_value'];
			$permanent_value_parts 			  = explode('|', $permanent_address_value);
			$data['permanent_house_no'] 	  = $permanent_value_parts[0]; 
			$data['permanent_street'] 	  	  = $permanent_value_parts[1]; 
			$data['permanent_subdivision']    = $permanent_value_parts[2]; 
			
			// GET IDENTIFICATION TYPE FORMAT
			$field                       	= array("identification_type_id, format") ;
			$table                       	= $this->pds->tbl_param_identification_types;
			$where                       	= array();
			$where['builtin_flag']     		= 'Y';
			$identification_format      	= $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['identification_format'] 	= $identification_format;
			
			//GET IDENTIFICATIONS
			$table = array(
				'main' => array(
					'table' => $this->pds->tbl_employee_identifications,
					'alias' => 'A'
				),
				't2' => array(
					'table' => $this->pds->tbl_param_identification_types,
					'alias' => 'B',
					'type' => 'JOIN',
					'condition' => 'B.identification_type_id = A.identification_type_id'
				)
			);
			$where                               = array();
			$key                                 = $this->get_hash_key('A.employee_id');
			$where[$key]                         = $id;
			$where['B.builtin_flag']	         = 'Y';
			$order_by 							 = array('B.identification_type_id' => 'ASC');
			$identification_info                 = $this->pds->get_general_data(array("*"), $table, $where, TRUE, $order_by);

			$data['identification_info']         = $identification_info;			

			//GET CONTACTS
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_contacts;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$contact_info                        = $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['contact_info']                = $contact_info;
				
			/*FAMILY BACKGROUND DATA*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_SPOUSE;
			$data['spouse']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_FATHER;
			$data['father']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_MOTHER;
			$data['mother']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			
			$field                               = array("*","CONCAT(relation_first_name, ' ',relation_last_name) as name", "DATE_FORMAT(relation_birth_date, '%m/%d/%Y') AS relation_birth_date") ;
			$table                               = $this->pds->tbl_employee_relations;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['relation_type_id']           = FAMILY_CHILD;
			$data['child']                       = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//EDUCATIONAL BACKGROUND
			$data['educ_details']                = $this->pds->get_pds_education($id);
			
			$where                               = array();
			$where['active_flag']                = 'Y';
			$data['educ_list']                   = $this->pds->get_general_data(array('*'), $this->pds->tbl_param_educational_levels);
			
			$data['govt_exam']                   = $this->pds->get_pds_eligibility($id);
			$data['work_exp']                    = $this->pds->get_pds_work_experience($id);
			
			$field                               = array("*", "DATE_FORMAT(volunteer_start_date, '%m/%d/%Y') AS volunteer_start_date", "DATE_FORMAT(volunteer_end_date, '%m/%d/%Y') AS volunteer_end_date") ;
			$table                               = $this->pds->tbl_employee_voluntary_works;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['vol_details']                 = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			// $field                               = array("*", "DATE_FORMAT(training_start_date, '%m/%d/%Y') AS training_start_date", "DATE_FORMAT(training_end_date, '%m/%d/%Y') AS training_end_date") ;
			// ====================== jendaigo : start : include reference for sorting ============= //
			$field                               = array("*", "training_start_date AS sort_start_date", "DATE_FORMAT(training_start_date, '%m/%d/%Y') AS training_start_date", "DATE_FORMAT(training_end_date, '%m/%d/%Y') AS training_end_date") ;
			// ====================== jendaigo : end : include reference for sorting ============= //
			
			$table                               = $this->pds->tbl_employee_trainings;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			
			// $data['train_details']               = $this->pds->get_general_data($field, $table, $where, TRUE);
			// ====================== jendaigo : start : sort based on straining_start_date ============= //
			$order_by 				   			 = array('sort_start_date' => 'DESC');
			$data['train_details']               = $this->pds->get_general_data($field, $table, $where, TRUE, $order_by);
			// ====================== jendaigo : end : sort based on straining_start_date ============= //
			
			/*OTHER INFORMATION DATA*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_other_info;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['other_info_type_id']         = OTHER_SKILLS;
			$data['other_params']['skills_list'] = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_other_info;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['other_info_type_id']         = OTHER_RECOGNITION;
			$data['other_params']['recog_list']  = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_other_info;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$where['other_info_type_id']         = OTHER_ASSOCIATION;
			$data['other_params']['member_list'] = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			
			$data['questions']                   = $this->pds->get_pds_questions($id);
			/*START QUESTIONS*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_param_questions;
			$where                               = array();
			$where['parent_question_id']         = "IS NULL";
			$data['parent_questions']            = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_param_questions;
			$where                               = array();
			$where['parent_question_flag']       = "N";
			$data['child_questions']             = $this->pds->get_general_data($field, $table, $where, TRUE);
			/*GET EMPLOYEE PREVIOUS ANSWERS*/
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_questions;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['answers']                     = $this->pds->get_general_data($field, $table, $where, TRUE);
			/*END QUESTIONS*/
			
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_references;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['refn_details']                = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			$field                               = array("*") ;
			$table                               = $this->pds->tbl_employee_declaration;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_id');
			$where[$key]                         = $id;
			$data['declaration']                 = $this->pds->get_general_data($field, $table, $where, FALSE);
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


/* End of file Pds_report.php */
/* Location: ./application/modules/main/controllers/reports/hr/Pds_report.php */