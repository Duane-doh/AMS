<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_model extends Main_Model {

	
	public $db_core              = DB_CORE;
	public $tbl_process_stages   = "process_stages";
	public $tbl_process_steps    = "process_steps";
	public $tbl_requests_tasks   = "requests_tasks";
	public $tbl_organizations    = "organizations";
	public $tbl_users            = "users";
	
	public $tbl_param_regions    = 'param_regions';
	public $tbl_param_provinces  = 'param_provinces';
	public $tbl_param_municities = 'param_municities';
	public $tbl_param_barangays  = 'param_barangays';
	public $tbl_param_genders    = 'param_genders';

	public function get_employee_list($aColumns, $bColumns, $params, $module_id)
	{
		try
		{
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))
			$params["CONCAT(A-last_name, IF(A-ext_name='' OR A.ext_name IS NULL,'',CONCAT(' ', A-ext_name)), ', ', A-first_name, ' ',A-last_name)"] = $params['fullname'];
			
			// $cColumns      = array("A-agency_employee_id", "CONCAT(A-last_name, IF(A-ext_name='','',CONCAT(' ', A-ext_name)), ', ', A-first_name, ' ',A-last_name)", "E-name", "D-employment_status_name");
			
			//==== MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : START
			$cColumns      = array("A-agency_employee_id", "CONCAT(A-last_name, IF(A-ext_name='' OR A.ext_name IS NULL,'',CONCAT(' ', A-ext_name)), ', ', A-first_name, ' ',A-last_name)", "E-name", "D-employment_status_name", "B-employ_start_date");
			//==== MARVIN : INCLUDE LAST ENTRY WORK EXPERIENCE : END
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));			

			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			$sOrder        = $this->ordering($bColumns, $params);
			$group_by	   = 'GROUP BY A.agency_employee_id, A.employee_id, fullname, E.name, D.employment_status_name, employ_office_id';
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$add_where     = '';
			if(!EMPTY($params['C-office_id'])) {
				$office_list = '';
				$office_list = $this->get_office_child($office_list, $params['C-office_id']);
				$add_where   = (EMPTY($filter_str) ? 'WHERE C.office_id IN (' . implode(',', $office_list) . ')' : ' AND C.office_id IN (' . implode(',', $office_list) . ')');
				$add_where 	 = ' AND A.last_name != "ADMINISTRATOR"'; //jendaigo: exclude superuser in pds viewing list
			}
			// ====================== jendaigo : start : exclude superuser in pds viewing list ============= //
			else
			{
				$add_where   = (EMPTY($filter_str) ? 'WHERE A.last_name != "ADMINISTRATOR"' : ' AND A.last_name != "ADMINISTRATOR"');
			}
			// ====================== jendaigo : end : exclude superuser in pds viewing list ============= //

			$work_experienxe_office_id = 'B.employ_office_id';
			if(!EMPTY($module_id))
			{
				$where              = array();
				$where['module_id'] = $module_id;
				$employee_office    = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
				if($employee_office['use_admin_office'] > 0)
				{
					$work_experienxe_office_id = 'B.admin_office_id';
				}
			}
			
			//marvin
			//added user_offices_scope
			// $user_offices_arr = $_SESSION['user_offices'];
			// sort($user_offices_arr);
			// $user_office_scope = $user_offices_arr[0];
			
			// if(empty($filter_str))
			// {
				// if(empty($add_where))
				// {
					// $add_where .= 'WHERE C.office_id IN('.$user_office_scope.')';
				// }
			// }
			// else
			// {
				// $add_where .= 'AND C.office_id IN('.$user_office_scope.')';
			// }
			//end	

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag ='Y'
				LEFT JOIN $this->tbl_param_offices C ON C.office_id = $work_experienxe_office_id
				LEFT JOIN $this->tbl_param_employment_status D ON B.employment_status_id = D.employment_status_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code

				$filter_str
				$add_where
				$group_by
	        	$sOrder
	        	$sLimit
EOS;
			
			$stmt = $this->query($query, $filter_params);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
	}
	public function filtered_length($aColumns, $bColumns, $params, $module_id)
	{
		try
		{
			$this->get_employee_list($aColumns, $bColumns, $params, $module_id);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_employee_personal_info, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_identification_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id            = $this->session->userdata("pds_employee_id");
			$val           = array($id, "N");
			$key           = $this->get_hash_key('A.employee_id');

			if(!EMPTY($params['A-identification_value']))
			$params['A-identification_value'] = str_replace('-', '', $params['A-identification_value']);

			/* For Advanced Filters */
			$cColumns      = array("B-identification_type_name", "A-identification_value");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_identifications A
				LEFT JOIN $this->tbl_param_identification_types B ON A.identification_type_id = B.identification_type_id
				WHERE $key = ? AND B.builtin_flag = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function identification_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_identification_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function identification_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_identifications, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}		
	}
	public function get_address_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id, "N");
			$key 	= $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns = array("B-address_type_name", "A-address_value", "A-postal_number");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_addresses A
				LEFT JOIN $this->tbl_param_address_types B ON A.address_type_id = B.address_type_id
				WHERE $key = ? AND B.builtin_flag = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function address_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_address_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function address_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_addresses, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_contacts_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id, "N");
			$key 	= $this->get_hash_key('A.employee_id');

			if(!EMPTY($params['A-contact_value']))
			$params['A-contact_value'] = str_replace('-', '', $params['A-contact_value']);

			/* For Advanced Filters */
			$cColumns = array("B-contact_type_name", "A-contact_value");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_contacts A
				JOIN $this->tbl_param_contact_types B ON A.contact_type_id = B.contact_type_id
				WHERE $key = ? AND B.builtin_flag = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function contacts_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_contacts_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function contacts_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_contacts, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_family_list($aColumns, $bColumns, $params)
	{
		try
		{
			if(!EMPTY($params['fullname']))
			$params["CONCAT(IFNULL(A-relation_last_name, ''), IFNULL(A-relation_ext_name, ''), IF(A-relation_last_name = '' OR A-relation_last_name IS NULL, '',', '), IFNULL(A-relation_first_name, ''), ' ', IFNULL(A-relation_middle_name, ''))"] = $params['fullname'];
			
			if(!EMPTY($params['relation_birth_date']))
				$params["DATE_FORMAT(A-relation_birth_date, '%Y/%m/%d')"] = $params['relation_birth_date'];

			$id            = $this->session->userdata("pds_employee_id");
			$val           = array($id);
			$key           = $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns      = array("CONCAT(IFNULL(A-relation_last_name, ''), IFNULL(A-relation_ext_name, ''), IF(A-relation_last_name = '' OR A-relation_last_name IS NULL, '',', '), IFNULL(A-relation_first_name, ''), ' ', IFNULL(A-relation_middle_name, ''))", "B-relation_type_name", "DATE_FORMAT(A-relation_birth_date, '%Y/%m/%d')");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_relations A
				LEFT JOIN $this->tbl_param_relation_types B ON A.relation_type_id = B.relation_type_id
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function family_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_family_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function family_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_relations, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_education_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns = array("B-educ_level_name", "C-school_name", "D-degree_name", "A-end_year");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON A.educational_level_id = B.educ_level_id
				 JOIN $this->tbl_param_schools C ON A.school_id = C.school_id
				LEFT JOIN $this->tbl_param_education_degrees D ON A.education_degree_id = D.degree_id
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function education_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_education_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function education_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_educations, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_eligibility_list($aColumns, $bColumns, $params)
	{
		try
		{
			if(!EMPTY($params['exam_date']))
				$params["DATE_FORMAT(A-exam_date, '%Y/%m/%d')"] = $params['exam_date'];

			if(!EMPTY($params['A-rating']))
				$params["IF(A-rating IS NULL, 'NA', A-rating)"] = $params['A-rating'];

			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns = array("B-eligibility_type_name", "IF(A-rating IS NULL, 'NA', A-rating)", "DATE_FORMAT(A-exam_date, '%Y/%m/%d')", "A-exam_place");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_eligibility A
				LEFT JOIN $this->tbl_param_eligibility_types B ON A.eligibility_type_id = B.eligibility_type_id
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function eligibility_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_eligibility_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}		
	}
	
	
	public function eligibility_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_eligibility, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}		
	}
	public function get_work_experience_list($aColumns, $bColumns, $params)
	{
		try
		{

			if(!EMPTY($params['employ_start_date']))
				$params["DATE_FORMAT(A-employ_start_date,'%Y/%m/%d')"] = $params['employ_start_date'];

			if(!EMPTY($params['employ_end_date']))
				$params["IFNULL(DATE_FORMAT(A-employ_end_date, '%Y/%m/%d'), 'PRESENT')"] = $params['employ_end_date'];

			if(!EMPTY($params['position']))
			$params["IFNULL(A-employ_position_name, D-position_name)"] = $params['position'];

			if(!EMPTY($params['office']))
			$params["IFNULL(A-employ_office_name, E-name)"] = $params['office'];

			$id            = $this->session->userdata("pds_employee_id");
			$val           = array($id);
			$key           = $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns      = array("DATE_FORMAT(A-employ_start_date,'%Y/%m/%d')", "IFNULL(DATE_FORMAT(A-employ_end_date, '%Y/%m/%d'), 'PRESENT')", "IFNULL(A-employ_position_name, D-position_name)", "IFNULL(A-employ_office_name, E-name)", "A-employ_monthly_salary", "B-employment_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
		
			$condition = "WHERE $key = ?";			
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_param_employment_status B ON A.employment_status_id = B.employment_status_id
				LEFT JOIN $this->tbl_param_offices C ON C.office_id = A.employ_office_id
				LEFT JOIN $this->tbl_param_positions D on A.employ_position_id = D.position_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code

				$condition
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function work_experience_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_work_experience_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function work_experience_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_work_experiences, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_profession_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id            = $this->session->userdata("pds_employee_id");
			$val           = array($id, "Y");
			$key           = $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns      = array("profession_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_professions A
				LEFT JOIN $this->tbl_param_professions B ON A.profession_id = B.profession_id
				WHERE $key = ? AND B.active_flag = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function profession_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_profession_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	public function profession_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_professions, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	public function get_professions_other_id(){
		try
		{
			$where = array();
				
			$fields = array("profession_id");
			$where['others_flag'] = YES;
			$where['active_flag'] = YES;
				
			return $this->select_one($fields, $this->tbl_param_professions, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
	}

	public function get_professions($other_id)
	{
		try
		{
			$id    = $this->session->userdata("pds_employee_id");
			$key   = $this->get_hash_key('employee_id');
			$val   = array($id, $other_id, YES);

			$query = <<<EOS
				SELECT 
				profession_id,
				profession_name
				FROM $this->tbl_param_professions
				WHERE profession_id NOT IN
					(SELECT profession_id
						FROM $this->tbl_employee_professions
						WHERE $key = ?
						AND profession_id != ?)
				AND active_flag = ?
EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	public function get_edit_professions($other_id, $prof_id)
	{
		try
		{
			$id    			= $this->session->userdata("pds_employee_id");
			$key   			= $this->get_hash_key('employee_id');
			$profession_id  = $this->get_hash_key('employee_profession_id');
			$val   = array($id, $other_id, $prof_id);

			$query = <<<EOS
				SELECT 
				profession_id,
				profession_name
				FROM $this->tbl_param_professions
				WHERE profession_id NOT IN
					(SELECT profession_id
						FROM $this->tbl_employee_professions
						WHERE $key = ?
						AND profession_id != ?
						AND profession_id !=  
							(SELECT profession_id
						        FROM $this->tbl_employee_professions
						        WHERE $profession_id = ?))
EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_voluntary_work_list($aColumns, $bColumns, $params)
	{
		try
		{  
			if(!EMPTY($params['volunteer_start_date']))
				$params["DATE_FORMAT(volunteer_start_date,'%Y/%m/%d')"] = $params['volunteer_start_date'];

			if(!EMPTY($params['volunteer_end_date']))
				$params["DATE_FORMAT(volunteer_end_date,'%Y/%m/%d')"] = $params['volunteer_end_date'];

			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id);
			$key 	= $this->get_hash_key('employee_id');
			/* For Advanced Filters */
			$cColumns = array("volunteer_org_name", "DATE_FORMAT(volunteer_start_date,'%Y/%m/%d')", "DATE_FORMAT(volunteer_end_date,'%Y/%m/%d')", "volunteer_hour_count", "volunteer_position");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_voluntary_works
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function voluntary_work_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_voluntary_work_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function voluntary_work_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_voluntary_works, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_trainings_list($aColumns, $bColumns, $params)
	{
		try
		{
			if(!EMPTY($params['training_start_date']))
			$params["DATE_FORMAT(A-training_start_date,'%Y/%m/%d')"] = $params['training_start_date'];

			if(!EMPTY($params['training_end_date']))
			$params["DATE_FORMAT(A-training_end_date,'%Y/%m/%d')"] = $params['training_end_date'];

			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id);
			$key 	= $this->get_hash_key('employee_id');
			/* For Advanced Filters */
			$cColumns = array("A-training_name", "DATE_FORMAT(A-training_start_date,'%Y/%m/%d')", "DATE_FORMAT(A-training_end_date,'%Y/%m/%d')", "A-training_hour_count", "A-training_conducted_by", "A-training_type"); 
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_trainings A
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);

			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function trainings_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_trainings_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function trainings_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_trainings, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_other_info_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			/* For Advanced Filters */
			$cColumns = array("A-others_value", "B-other_info_type_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_other_info A
				LEFT JOIN $this->tbl_param_other_info_types B ON A.other_info_type_id = B.other_info_type_id
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function other_info_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_other_info_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function other_info_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_other_info, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_previous_other_info($other_info_id)
	{
		try
		{
			$key   = $this->get_hash_key('A.employee_other_info_id');
			$val   = array($other_info_id);

			$query = <<<EOS
				SELECT
				B.with_info_flag,
				A.*
				FROM $this->tbl_employee_other_info A
				LEFT JOIN $this->tbl_param_other_info_types B ON A.other_info_type_id = B.other_info_type_id
				WHERE $key = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_reference_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$val 	= array($id);
			$key 	= $this->get_hash_key('employee_id');

			if(!EMPTY($params['reference_contact_info']))
			$params['reference_contact_info'] = str_replace('-', '', $params['reference_contact_info']);

			/* For Advanced Filters */
			$cColumns = array("reference_full_name", "reference_address", "reference_contact_info");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_references
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function reference_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_reference_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function reference_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_references, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_personal_info($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			/* $query = <<<EOS
				SELECT A.*,D.citizenship_name,D.country_name,B.blood_type_name, DATE_FORMAT(A.birth_date, '%m/%d/%Y') AS birth_date, DATE_FORMAT(A.date_accomplished, '%m/%d/%Y') AS date_accomplished
				FROM 
				$this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_param_blood_type B ON B.blood_type_id = A.blood_type_id
				LEFT JOIN $this->tbl_param_citizenships D ON D.citizenship_id = A.citizenship_id
				WHERE $key = ?

EOS;
			*/
			// ====================== jendaigo : start : modify date_accomplished to current date ============= //
			$query = <<<EOS
				SELECT A.*,D.citizenship_name,D.country_name,B.blood_type_name, DATE_FORMAT(A.birth_date, '%m/%d/%Y') AS birth_date, DATE_FORMAT(CURRENT_DATE(),'%m/%d/%Y') AS date_accomplished
				FROM 
				$this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_param_blood_type B ON B.blood_type_id = A.blood_type_id
				LEFT JOIN $this->tbl_param_citizenships D ON D.citizenship_id = A.citizenship_id
				WHERE $key = ?

EOS;
			// ====================== jendaigo : end : modify date_accomplished to current date ============= //

			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_education($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				LEFT JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				LEFT JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				WHERE $key = ?
				ORDER BY B.educ_level_id

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	/*public function get_pds_educ_elementary($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				LEFT JOIN $this->tbl_param_academic_honors E ON E.academic_honor_id = A.academic_honor
				WHERE $key = ? AND B.educ_level_id = $this->level_elementary

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_educ_graduate($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				LEFT JOIN $this->tbl_param_academic_honors E ON E.academic_honor_id = A.academic_honor
				WHERE $key = ? AND B.educ_level_id = $this->level_graduate

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_educ_secondary($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				LEFT JOIN $this->tbl_param_academic_honors E ON E.academic_honor_id = A.academic_honor
				WHERE $key = ? AND B.educ_level_id = $this->level_secondary

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_educ_vocational($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				LEFT JOIN $this->tbl_param_academic_honors E ON E.academic_honor_id = A.academic_honor
				WHERE $key = ? AND B.educ_level_id = $this->level_vocational

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_educ_college($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				LEFT JOIN $this->tbl_param_academic_honors E ON E.academic_honor_id = A.academic_honor
				WHERE $key = ? AND B.educ_level_id = $this->level_college

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}*/
	public function get_pds_eligibility($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT A.*, B.*, DATE_FORMAT(A.release_date, '%m/%d/%Y') AS release_date, DATE_FORMAT(A.exam_date, '%m/%d/%Y') AS exam_date
				FROM $this->tbl_employee_eligibility A
				LEFT JOIN $this->tbl_param_eligibility_types B ON A.eligibility_type_id = B.eligibility_type_id
				WHERE $key = ?

EOS;

			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_work_experience($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			$query = <<<EOS

				SELECT *, DATE_FORMAT(A.employ_start_date, '%m/%d/%Y') AS employ_start_date, DATE_FORMAT(A.employ_end_date, '%m/%d/%Y') AS employ_end_date
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_param_employment_status B ON B.employment_status_id = A.employment_status_id
				LEFT JOIN $this->tbl_param_positions C ON C.position_id = A.employ_position_id
				LEFT JOIN $this->tbl_param_offices D ON D.office_id = A.employ_office_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON E.org_code = D.org_code
				WHERE $key = ?
				ORDER BY A.employ_start_date DESC

EOS;

			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_pds_questions($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('B.employee_id');
			
			$query = <<<EOS

				SELECT * 
				FROM $this->tbl_param_questions A
				LEFT JOIN $this->tbl_employee_questions B ON A.question_id = B.question_id
				WHERE $key = ?

EOS;

			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_general_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group = array(), $limit = NULL)
	{
		try{
	
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group, $limit);
			
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group, $limit);
			}
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function insert_general_data($table, $params, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $params, $return_id);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function update_general_data($tables, $params, $where)
	{
		try
		{
			$this->update_data($tables, $params, $where);
			return TRUE;

		}
		catch (PDOException $e)
		{
			throw $e;
		}
	}
	public function delete_general_data($table,$where)
	{
		try
		{
			return $this->delete_data($table, $where);
	
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function check_pds_record($table,$field,$value)
	{
		try
		{
			$val 	= array(SUB_REQUEST_NEW,$value);
			$key 	= $this->get_hash_key($field);
			$query = <<<EOS

				SELECT * 
				FROM $table A
				LEFT JOIN $this->tbl_requests_sub B ON A.request_sub_id = B.request_sub_id
				WHERE 
				B.request_sub_status_id = ?
				AND
				$key = ?
				AND B.request_id IS NULL

EOS;

			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function count_pending_requests($id,$request_id = NULL)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('employee_id');

			if($request_id)
			{
				$request_where 	= "request_id = ?";
				$val[] 			= $request_id;
			}
			else
			{
				$request_where = "request_id IS NULL";
			}

			$TYPE_REQUEST_PDS_PERSONAL_INFO 	= TYPE_REQUEST_PDS_PERSONAL_INFO;
			$TYPE_REQUEST_PDS_IDENTIFICATION 	= TYPE_REQUEST_PDS_IDENTIFICATION;
			$TYPE_REQUEST_PDS_ADDRESS_INFO 		= TYPE_REQUEST_PDS_ADDRESS_INFO;
			$TYPE_REQUEST_PDS_CONTACT_INFO 		= TYPE_REQUEST_PDS_CONTACT_INFO;
			$TYPE_REQUEST_PDS_FAMILY_INFO 		= TYPE_REQUEST_PDS_FAMILY_INFO;
			$TYPE_REQUEST_PDS_EDUCATION			= TYPE_REQUEST_PDS_EDUCATION;
			$TYPE_REQUEST_PDS_ELIGIBILITY 		= TYPE_REQUEST_PDS_ELIGIBILITY;
			$TYPE_REQUEST_PDS_WORK_EXPERIENCE 	= TYPE_REQUEST_PDS_WORK_EXPERIENCE;
			$TYPE_REQUEST_PDS_VOLUNTARY_WORK 	= TYPE_REQUEST_PDS_VOLUNTARY_WORK;
			$TYPE_REQUEST_PDS_TRAININGS 		= TYPE_REQUEST_PDS_TRAININGS;
			$TYPE_REQUEST_PDS_OTHER_INFO 		= TYPE_REQUEST_PDS_OTHER_INFO;
			$TYPE_REQUEST_PDS_QUESTION 			= TYPE_REQUEST_PDS_QUESTIONNAIRE;
			$TYPE_REQUEST_PDS_REFERENCES 		= TYPE_REQUEST_PDS_REFERENCES;
			$TYPE_REQUEST_PDS_DECLARATION 		= TYPE_REQUEST_PDS_DECLARATION;
			$TYPE_REQUEST_PDS_PROFESSION		= TYPE_REQUEST_PDS_PROFESSION;

			$ACTION_ADD 	= ACTION_ADD;
			$ACTION_EDIT 	= ACTION_EDIT;
			$ACTION_DELETE 	= ACTION_DELETE;
			$query = <<<EOS

				SELECT 
					count(*) as record_count,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_PERSONAL_INFO then 1 else 0 end) as personal_info,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_IDENTIFICATION then 1 else 0 end) as identification,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_IDENTIFICATION and action = $ACTION_ADD then 1 else 0 end) as identification_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_IDENTIFICATION and action = $ACTION_EDIT then 1 else 0 end) as identification_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_IDENTIFICATION and action = $ACTION_DELETE then 1 else 0 end) as identification_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ADDRESS_INFO then 1 else 0 end) as address,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ADDRESS_INFO and action = $ACTION_ADD then 1 else 0 end) as address_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ADDRESS_INFO and action = $ACTION_EDIT then 1 else 0 end) as address_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ADDRESS_INFO and action = $ACTION_DELETE then 1 else 0 end) as address_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_CONTACT_INFO then 1 else 0 end) as contact,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_CONTACT_INFO and action = $ACTION_ADD then 1 else 0 end) as contact_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_CONTACT_INFO and action = $ACTION_EDIT then 1 else 0 end) as contact_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_CONTACT_INFO and action = $ACTION_DELETE then 1 else 0 end) as contact_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_FAMILY_INFO then 1 else 0 end) as family,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_FAMILY_INFO and action = $ACTION_ADD then 1 else 0 end) as family_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_FAMILY_INFO and action = $ACTION_EDIT then 1 else 0 end) as family_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_FAMILY_INFO and action = $ACTION_DELETE then 1 else 0 end) as family_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_EDUCATION then 1 else 0 end) as education,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_EDUCATION and action = $ACTION_ADD then 1 else 0 end) as education_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_EDUCATION and action = $ACTION_EDIT then 1 else 0 end) as education_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_EDUCATION and action = $ACTION_DELETE then 1 else 0 end) as education_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ELIGIBILITY  then 1 else 0 end) as eligibility,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ELIGIBILITY  and action = $ACTION_ADD then 1 else 0 end) as eligibility_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ELIGIBILITY  and action = $ACTION_EDIT then 1 else 0 end) as eligibility_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_ELIGIBILITY  and action = $ACTION_DELETE then 1 else 0 end) as eligibility_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_WORK_EXPERIENCE then 1 else 0 end) as work_experience,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_WORK_EXPERIENCE and action = $ACTION_ADD then 1 else 0 end) as work_experience_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_WORK_EXPERIENCE and action = $ACTION_EDIT then 1 else 0 end) as work_experience_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_WORK_EXPERIENCE and action = $ACTION_DELETE then 1 else 0 end) as work_experience_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_VOLUNTARY_WORK then 1 else 0 end) as voluntary_work,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_VOLUNTARY_WORK and action = $ACTION_ADD then 1 else 0 end) as voluntary_work_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_VOLUNTARY_WORK and action = $ACTION_EDIT then 1 else 0 end) as voluntary_work_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_VOLUNTARY_WORK and action = $ACTION_DELETE then 1 else 0 end) as voluntary_work_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_TRAININGS then 1 else 0 end) as training,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_TRAININGS and action = $ACTION_ADD then 1 else 0 end) as training_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_TRAININGS and action = $ACTION_EDIT then 1 else 0 end) as training_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_TRAININGS and action = $ACTION_DELETE then 1 else 0 end) as training_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_OTHER_INFO then 1 else 0 end) as other_info,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_OTHER_INFO and action = $ACTION_ADD then 1 else 0 end) as other_info_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_OTHER_INFO and action = $ACTION_EDIT then 1 else 0 end) as other_info_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_OTHER_INFO and action = $ACTION_DELETE then 1 else 0 end) as other_info_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_QUESTION then 1 else 0 end) as question,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_REFERENCES then 1 else 0 end) as reference,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_REFERENCES and action = $ACTION_ADD then 1 else 0 end) as reference_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_REFERENCES and action = $ACTION_EDIT then 1 else 0 end) as reference_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_REFERENCES and action = $ACTION_DELETE then 1 else 0 end) as reference_delete,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_DECLARATION then 1 else 0 end) as declaration,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_PROFESSION then 1 else 0 end) as profession,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_PROFESSION and action = $ACTION_ADD then 1 else 0 end) as profession_add,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_PROFESSION and action = $ACTION_EDIT then 1 else 0 end) as profession_edit,
					SUM(case when request_sub_type_id = $TYPE_REQUEST_PDS_PROFESSION and action = $ACTION_DELETE then 1 else 0 end) as profession_delete

				FROM 
				$this->tbl_requests_sub
				WHERE 
				$key = ?
				AND
				$request_where

EOS;

			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function check_request($id)
	{
		try
		{
			$val 	= array($id,REQUEST_PDS_RECORD_CHANGES,REQUEST_NEW,REQUEST_PENDING,REQUEST_ONGOING);
			$key 	= $this->get_hash_key('employee_id');

			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_requests
				WHERE 
				$key = ?
				AND request_type_id = ?
				AND
				(
					request_status_id = ?
					OR
					request_status_id = ?
					OR
					request_status_id = ?
				)

EOS;

			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function update_requests_sub($request_id, $id)
	{
		try
		{
			$val 	= array(
				$id,
				TYPE_REQUEST_PDS_PERSONAL_INFO,
				TYPE_REQUEST_PDS_IDENTIFICATION,
				TYPE_REQUEST_PDS_ADDRESS_INFO,
				TYPE_REQUEST_PDS_CONTACT_INFO,
				TYPE_REQUEST_PDS_FAMILY_INFO,
				TYPE_REQUEST_PDS_EDUCATION,
				TYPE_REQUEST_PDS_ELIGIBILITY,
				TYPE_REQUEST_PDS_WORK_EXPERIENCE,
				TYPE_REQUEST_PDS_VOLUNTARY_WORK,
				TYPE_REQUEST_PDS_TRAININGS,
				TYPE_REQUEST_PDS_OTHER_INFO,
				TYPE_REQUEST_PDS_QUESTIONNAIRE,
				TYPE_REQUEST_PDS_REFERENCES,
				TYPE_REQUEST_PDS_DECLARATION
				);
			$key 	= $this->get_hash_key('employee_id');

			
			$query = <<<EOS

				UPDATE 
				$this->tbl_requests_sub
				 SET
				request_id = $request_id
				WHERE 
				$key = ?
				AND
				request_id IS NULL
				AND
				(
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
					OR
					request_sub_type_id = ?
				)

EOS;

			
			$stmt = $this->query($query, $val, NULL);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_initial_task($process_id)
	{
		try
		{
			
			$val = array($process_id);
			
			$query = <<<EOS
				select A.name,B.process_id,B.process_stage_id,B.process_step_id
				from $this->db_core.$this->tbl_process_stages A 
				JOIN $this->db_core.$this->tbl_process_steps B ON A.process_id = B.process_id
				WHERE A.process_id = ?
EOS;
	
			return $this->query($query, $val,FALSE);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

		public function get_office_list($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group = array(), $limit = NULL)
	{
		try
		{
			$fields = "*";
			
			$query  = <<<EOS
					SELECT SQL_CALC_FOUND_ROWS $fields 
					FROM $this->tbl_param_offices A
					LEFT JOIN $this->db_core.$this->tbl_organizations B ON A.org_code = B.org_code
					GROUP BY office_id
EOS;
			RLog::debug($query);
			$stmt = $this->query($query);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_param_plantilla($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$val = array();
					
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS *
				FROM $this->tbl_param_plantilla_items A
				JOIN $this->tbl_param_positions B ON A.position_id = B.position_id

EOS;
			RLog::debug($query);
			$val = array_merge($val);
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_specific_param_salary_schedule($select_fields, $tables, $where)
	{
		try
		{


			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			$stmt = $this->select_one($fields, $tables, $where);
			
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_specific_param_plantilla($select_fields, $tables, $where)
	{
		try
		{


			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			$stmt = $this->select_one($fields, $tables, $where);
			
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_employee_info($id)
	{
		try
		{
			$val 	= array(YES,$id);
			// $key 	= $this->get_hash_key('A.employee_id');
			/*
			$fields = "A.employee_id, 
						A.agency_employee_id, 
						CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname, 
						ifnull(B.employ_office_name,E.name) AS name, 
						ifnull(B.employ_position_name, D.position_name) AS position_name, 
						B.employ_office_id,
						G.photo";
			*/	
			// ====================== jendaigo : start : change name format ============= //			
			$fields = "A.employee_id, 
						A.agency_employee_id, 
						CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', LEFT(A.middle_name,1), '.'))) as fullname, 
						ifnull(B.employ_office_name,E.name) AS name, 
						ifnull(B.employ_position_name, D.position_name) AS position_name, 
						B.employ_office_id,
						G.photo";
			// ====================== jendaigo : end : change name format ============= //
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag = ?
				LEFT JOIN $this->tbl_param_offices C ON B.employ_office_id = C.office_id 
				LEFT JOIN $this->tbl_param_positions D ON B.employ_position_id = D.position_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code
				LEFT JOIN $this->tbl_associated_accounts F ON A.employee_id = F.employee_id OR A.employee_id = F.agency_employee_id
				LEFT JOIN $this->db_core.$this->tbl_users G ON F.user_id = G.user_id

				WHERE A.employee_id = ?				
				ORDER BY B.employee_work_experience_id DESC;
EOS;
	
			$stmt = $this->query($query, $val, false);
						
			return $stmt;

		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_supporting_documents_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id  = $this->session->userdata("pds_employee_id");
			$val = array($id);
			$key = $this->get_hash_key('B.employee_id');
			/* For Advanced Filters */
			$cColumns = array("D-supp_doc_type_name", "A-date_received", "A-remarks");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_supporting_docs A
				JOIN $this->tbl_requests B ON A.request_id = B.request_id
				JOIN $this->tbl_param_supporting_document_types D on A.supp_doc_type_id = D.supp_doc_type_id
				 
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	public function supporting_documents_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_supporting_documents_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function supporting_documents_total_length()
	{
		try
		{
			$id           = $this->session->userdata("pds_employee_id");
			$where        = array();
			
			$fields       = array("COUNT(*) cnt");
			$key          = $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_requests, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function check_date_overlap($start_date, $end_date, $work_id)
	{
		try
		{
			$date_now = date('Y-m-d');

			$reduce_start_date= date('Y-m-d',strtotime('-1 day' , strtotime ( $start_date ) ) );	

			$min_end_date = ($start_date < $date_now) ? $reduce_start_date: $date_now;

			$id		= $this->session->userdata("pds_employee_id");
			$val 	= array($id, $work_id, $start_date, $end_date,$min_end_date, $start_date, $end_date, $start_date,$min_end_date, $end_date,$min_end_date);
			$key 	= $this->get_hash_key('employee_id');
			$work_key 	= $this->get_hash_key('employee_work_experience_id');

			
			$query = <<<EOS
				SELECT employ_start_date, 
				employ_end_date
				FROM $this->tbl_employee_work_experiences
				WHERE $key = ?
				AND $work_key != ?
				AND (employ_start_date BETWEEN ? AND ? 
					OR IFNULL(employ_end_date, ?) BETWEEN ? AND ?
					OR ? BETWEEN employ_start_date AND IFNULL(employ_end_date, ?)
					OR ? BETWEEN employ_start_date AND IFNULL(employ_end_date, ?))
EOS;

			$stmt = $this->query($query, $val, TRUE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_employee_monthly_salary($id, $emp_id, $employ_start_date)
	{
		try
		{
			$val 	= array($employ_start_date, $emp_id, YES, $id);
			$key 	= $this->get_hash_key('employee_id');
			
			$query = <<<EOS
				SELECT 
				amount
				FROM $this->tbl_param_salary_schedule 
				WHERE effectivity_date = 
					(SELECT MAX(effectivity_date)  
						FROM $this->tbl_param_salary_schedule
						WHERE effectivity_date <= ?)
				AND salary_grade = 
					(SELECT employ_salary_grade 
						FROM $this->tbl_employee_work_experiences
						WHERE $key = ? AND 
						active_flag = ?)
				AND salary_step = ?
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_employee_appt_monthly_salary($id, $plantilla_id, $employ_start_date)
	{
		try
		{
			$val 	= array($employ_start_date, $plantilla_id, $id);
			
			$query = <<<EOS
				SELECT 
				amount
				FROM $this->tbl_param_salary_schedule 
				WHERE effectivity_date = 
					(SELECT 
						MAX(effectivity_date)  
						FROM $this->tbl_param_salary_schedule
						WHERE effectivity_date <= ?)
				AND salary_grade = 
					(SELECT 
			            B.salary_grade
			        	FROM param_plantilla_items A
						JOIN param_positions B ON A.position_id = B.position_id
			       		WHERE A.plantilla_id = ?)
				AND salary_step = ?
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	//marvin : include sg : start
	// public function get_employee_appt_monthly_salary_custom($id, $employ_start_date, $sg)
	// {
		// try
		// {
			// $val 	= array($employ_start_date, $sg, $id);
			
			// $query = <<<EOS
				// SELECT 
				// amount
				// FROM $this->tbl_param_salary_schedule 
				// WHERE effectivity_date = 
					// (SELECT 
						// MAX(effectivity_date)  
						// FROM $this->tbl_param_salary_schedule
						// WHERE effectivity_date <= ?)
				// AND salary_grade = ?
				// AND salary_step = ?
// EOS;

			// $stmt = $this->query($query, $val, FALSE);

			// return $stmt;
		// }
		// catch (PDOException $e)
		// {
			// RLog::error($e->getMessage());
			// throw new Exception($e->getMessage());
		// }
		// catch (Exception $e)
		// {			
			// RLog::error($e->getMessage());
			// throw new Exception($e->getMessage());
		// }	
	// }
	//marvin : include sg : end

	public function get_personnel_movement_step_incr($id)
	{
		try
		{
			$val 	= array('MOVT_SALARY_INCR', 'MOVT_SALARY_ADJUSTMENT', 'MOVT_DETAIL', 'MOVT_TRANSFER_IN', 'MOVT_PROMOTION', $id);

			$query = <<<EOS
				SELECT 
				sys_param_type 
				FROM $this->db_core.$this->tbl_sys_param
				WHERE sys_param_type IN (?,?,?,?,?)
					AND sys_param_value = ?
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_max_start_date($work_id)
	{
		try
		{
			$id			= $this->session->userdata("pds_employee_id");
			$val 		= array($id, $work_id);
			$key 		= $this->get_hash_key('employee_id');
			$work_key 	= $this->get_hash_key('employee_work_experience_id');

			$query = <<<EOS
				SELECT 
				max(employ_start_date) as max_start_date
				FROM $this->tbl_employee_work_experiences
				WHERE $key = ?
				AND $work_key != ?
				AND employ_end_date IS NULL
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function check_employee_separation()
	{
		try
		{
			$id			= $this->session->userdata("pds_employee_id");
			$val 		= array($id, DOH_GOV_APPT, DOH_GOV_NON_APPT);
			$key 		= $this->get_hash_key('A.employee_id');

			$query = <<<EOS
				SELECT 
				A.employ_end_date,
				A.employee_id
				FROM $this->tbl_employee_work_experiences A
				WHERE $key = ?
				AND A.employ_start_date = (SELECT 
					max(employ_start_date) 
					FROM $this->tbl_employee_work_experiences
					WHERE employee_id = A.employee_id)
				AND A.employ_end_date IS NOT NULL
				AND A.employ_type_flag IN (?, ?)
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_plantilla_list($where_plantilla, $plantilla_id_arr)
	{
		try
		{
			$plantilla_filter = '';
			$condition = $where_plantilla;
			if($plantilla_id_arr)
			{
				$plantilla_cnt = count($plantilla_id_arr);
				$plantilla_filter = ' AND A.plantilla_id NOT IN(';
				foreach ($plantilla_id_arr as $key => $value) {
					if($plantilla_cnt > $key +1)
					{
						$plantilla_filter .= '?,';
					}
					else
					{
						$plantilla_filter .= '?';
					}
					$where[] = !EMPTY($value) ? $value : '';				
				}
				$plantilla_filter .= ')';
			}
			
			$query = <<<EOS
				SELECT *
				FROM param_plantilla_items A
				LEFT JOIN param_positions B ON A.position_id = B.position_id 
				WHERE 
				$condition
				$plantilla_filter
EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	/*===== marvin : start : disable plantilla_id_arr =====*/
	public function get_plantilla_subs($where_plantilla)
	{
		try
		{
			$plantilla_filter = '';
			$condition = $where_plantilla;
			
			$query = <<<EOS
				SELECT *
				FROM param_plantilla_items A
				LEFT JOIN param_positions B ON A.position_id = B.position_id 
				WHERE 
				$condition
EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	/*===== marvin : end : disable plantilla_id_arr =====*/

	public function get_addresses()
	{
		try
		{
			$stmt = $this->query('SET SESSION group_concat_max_len = 10000000', NULL, NULL);
			$query = <<<EOS
				SELECT 
				    group_concat(concat('<option value="',
				            concat_ws(' ',
				                    D.barangay_code,
				                    A.municity_code,
				                    B.province_code,
				                    C.region_code),
				            '">',
				            concat_ws(',',
				                    D.barangay_name,
				                    A.municity_name,
				                    B.province_name,
				                    C.region_name),
				            '</option>') SEPARATOR '') as addresses
				FROM
				    $this->db_core.$this->tbl_param_barangays D
				        left join
				    $this->db_core.$this->tbl_param_municities A ON D.municity_code = A.municity_code
				        left join
				    $this->db_core.$this->tbl_param_provinces B ON A.province_code = B.province_code
				        left join
				    $this->db_core.$this->tbl_param_regions C ON B.region_code = C.region_code
EOS;
			
			$stmt = $this->query($query, NULL, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_regions()
	{
		try
		{
			$stmt = $this->query('SET SESSION group_concat_max_len = 10000000', NULL, NULL);
			$query = <<<EOS
				SELECT 
				   *
				FROM
				    $this->db_core.$this->tbl_param_regions
				ORDER BY region_name

EOS;
			
			$stmt = $this->query($query, NULL, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_provinces()
	{
		try
		{
			$stmt = $this->query('SET SESSION group_concat_max_len = 10000000', NULL, NULL);
			$query = <<<EOS
				SELECT 
				   *
				FROM
				    $this->db_core.$this->tbl_param_provinces
				ORDER BY province_name

EOS;
			
			$stmt = $this->query($query, NULL, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_municipalities()
	{
		try
		{
			$stmt = $this->query('SET SESSION group_concat_max_len = 10000000', NULL, NULL);
			$query = <<<EOS
				SELECT 
				   *
				FROM
				    $this->db_core.$this->tbl_param_municities
					ORDER BY municity_name

EOS;

			$stmt = $this->query($query, NULL, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	public function get_barangays()
	{
		try
		{
			$stmt = $this->query('SET SESSION group_concat_max_len = 10000000', NULL, NULL);
			$query = <<<EOS
				SELECT 
				   *
				FROM
				    $this->db_core.$this->tbl_param_barangays
					ORDER BY barangay_name

EOS;
			
			$stmt = $this->query($query, NULL, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_employees_with_request_list()
	{
		try
		{
			$stmt = $this->query('SET SESSION group_concat_max_len = 10000000', NULL, NULL);

			$val   = array(REQUEST_PDS_RECORD_CHANGES, REQUEST_CANCELLED, REQUEST_APPROVED, REQUEST_REJECTED, REQUEST_PDS_RECORD_CHANGES);

			$query = <<<EOS
				SELECT GROUP_CONCAT(DISTINCT B.employee_id) AS employee_id
				FROM $this->tbl_requests A
				LEFT JOIN $this->tbl_requests_sub B ON A.request_id = B.request_id 
					OR B.request_id IS NULL
				LEFT JOIN param_request_sub_types C ON B.request_sub_type_id = C.request_sub_type_id
				WHERE (A.request_type_id = ?
					AND A.request_status_id NOT IN(?, ?, ?))
				OR (C.request_type_id = ? AND B.request_id IS NULL)		
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_other_info_flag($id)
	{
		try
		{
			$val = array($id);
			$query = <<<EOS
				SELECT
				with_info_flag, 
				info_professional_flag 
				FROM $this->tbl_param_other_info_types
				WHERE other_info_type_id = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_employee_eligibility($emp_id)
	{
		try
		{
			$val 	= array($emp_id);
			$key 	= $this->get_hash_key('A.employee_id');
			$query 	= <<<EOS
				SELECT 
				GROUP_CONCAT(B.eligibility_type_name, ' - ', A.license_no SEPARATOR ', ') AS eligibility
				FROM $this->tbl_employee_eligibility A
				LEFT JOIN $this->tbl_param_eligibility_types B ON A.eligibility_type_id = B.eligibility_type_id
				WHERE $key = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_prev_work_experience($id)
	{
		try
		{
			$key 	= $this->get_hash_key('employee_id');
			$val    = array($id, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO);

			$query 	= <<<EOS
				SELECT A.employee_work_experience_id, A.separation_mode_id
				FROM $this->tbl_employee_work_experiences A
				WHERE $key = ?
				AND A.employ_start_date < (SELECT 
											MAX(employ_start_date) 
											FROM $this->tbl_employee_work_experiences
											WHERE employee_id = A.employee_id)
											AND A.employ_type_flag IN (?, ?, ?)
				ORDER BY A.employ_start_date DESC
				LIMIT 1
EOS;
			
			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function update_pds_date_accomplished($employee_id)
	{
		try
		{
			$date  = date('Y-m-d');
			$key   = $this->get_hash_key('employee_id');
			$val   = array($date, $employee_id);

			$query = <<<EOS
				UPDATE $this->tbl_employee_personal_info
				SET date_accomplished = ?
				WHERE $key = ?
EOS;
			
			$stmt = $this->query($query, $val, NULL);
						
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
	}

	public function get_prev_plantilla_owner($plantilla_id, $pds_employee_id)
	{
		try
		{
			$val   = array($plantilla_id, $pds_employee_id, $plantilla_id);
			$key   = $this->get_hash_key('employee_id');
			$query = <<<EOS
				SELECT 
				employee_id, 
				separation_mode_id
				FROM $this->tbl_employee_work_experiences	
				WHERE employ_plantilla_id = ?
				AND $key != ?
			    AND employ_start_date = (SELECT MAX(A.employ_start_date) 
			    						FROM $this->tbl_employee_work_experiences A 
			    						WHERE A.employ_plantilla_id = ?)
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
	}
	
	//================= MARVIN : START : FIX EMPLOYEE WITH PENDIN REQUEST ===============================//
	public function get_employee_request($employee_id)
	{
		// $val   = array(REQUEST_PDS_RECORD_CHANGES, REQUEST_CANCELLED, REQUEST_APPROVED, REQUEST_REJECTED, $employee_id, REQUEST_PDS_RECORD_CHANGES);

			// $query = <<<EOS
				// SELECT
				// DISTINCT(B.employee_id) AS employee_id

				// FROM
				// requests A

				// LEFT JOIN
				// requests_sub B ON A.request_id = B.request_id

				// LEFT JOIN
				// param_request_sub_types C ON B.request_sub_type_id = C.request_sub_type_id

				// WHERE
				// (A.request_type_id = ? AND A.request_status_id NOT IN(?, ?, ?) AND A.employee_id = ?)
				// OR
				// (C.request_type_id = ? AND B.request_id IS NULL)		
// EOS;

			$val = array(REQUEST_PDS_RECORD_CHANGES, REQUEST_CANCELLED, REQUEST_APPROVED, REQUEST_REJECTED, $employee_id);
			$query = <<<EOS
				SELECT
				A.employee_id AS employee_id

				FROM
				requests A

				WHERE
				A.request_type_id = ? AND A.request_status_id NOT IN (?, ?, ?) AND A.employee_id = ?
EOS;

		$stmt = $this->query($query, $val, FALSE);

			return $stmt;
	}
	//================= MARVIN : END : FIX EMPLOYEE WITH PENDIN REQUEST ===============================//

	public function get_plantilla_division($employ_plantilla_id)
	{
			$val = array($employ_plantilla_id);
			$query = <<<EOS
				SELECT
				division_id
				FROM param_plantilla_items WHERE plantilla_id = ?
EOS;

		$stmt = $this->query($query, $val, FALSE);

			return $stmt;
	}

}