<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pds_personal_info extends Main_Controller {

	private $log_user_id       =  '';
	private $log_user_roles    = array();
	
	private $permission_view   = FALSE;
	private $permission_edit   = FALSE;
	private $permission_delete = FALSE;
	
	private $permission_module = MODULE_; // TBD 

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_model', 'pds');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}	

	public function get_pds_personal_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
	{
		try
		{
			$data 	= array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			$resources['load_css'] 	= array( CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js'  ] = array( JS_SELECTIZE, JS_DATETIMEPICKER);

			$this->session->set_userdata("pds_employee_id", $id);
			$this->session->set_userdata("pds_module", $module);
			$this->session->set_userdata("pds_action", $action);

			if($action != ACTION_ADD)
			{
				//GET CITIZENSHIPS
				$field              = array("A.*, DATE_FORMAT(A.birth_date,'%m/%d/%Y') as birthday,  B.civil_status_name");
				$tables 			= array(
					'main' 			=> array(
						'table'		=> $this->pds->tbl_employee_personal_info,
						'alias' 	=> 'A'
					),
					't1' 			=> array(
						'table' 	=> $this->pds->tbl_param_civil_status,
						'alias' 	=> 'B',
						'type' 		=> 'LEFT JOIN',
						'condition' => 'A.civil_status_id = B.civil_status_id'
					)
				);
				$table                       = $this->pds->tbl_employee_personal_info;
				$where                       = array();
				$key                         = $this->get_hash_key('employee_id');
				$where[$key]                 = $id;
				$personal_info               = $this->pds->get_general_data($field, $tables, $where, FALSE);
				$data['personal_info']       = $personal_info;
				
				//GET IDENTIFICATIONS
				$field              = array("*");
				$tables 			= array(
					'main' 			=> array(
						'table'		=> $this->pds->tbl_employee_identifications,
						'alias' 	=> 'A'
					),
					't1' 			=> array(
						'table' 	=> $this->pds->tbl_param_identification_types,
						'alias' 	=> 'B',
						'type' 		=> 'LEFT JOIN',
						'condition' => 'A.identification_type_id = B.identification_type_id'
					)
				);

				$where                       = array();
				$key                         = $this->get_hash_key('A.employee_id');
				$where[$key]                 = $id;
				$where['B.builtin_flag']     = 'Y';
				$order_by 			         = array('A.identification_type_id' => 'ASC');
				$identification_info         = $this->pds->get_general_data($field, $tables, $where, TRUE, $order_by);
				$data['identification_info'] = $identification_info;

				//GET CONTACTS
				$field                       = array("*") ;
				$table                       = $this->pds->tbl_employee_contacts;
				$where                       = array();
				$key                         = $this->get_hash_key('employee_id');
				$where[$key]                 = $id;
				$contact_info                = $this->pds->get_general_data($field, $table, $where, TRUE);
				$data['contact_info']        = $contact_info;
				
				//GET ADDRESS
				$field                       	  = array("address_type_id", "address_value", "postal_number", "barangay_code", "municity_code", "province_code", "region_code");
				$table                       	  = $this->pds->tbl_employee_addresses;

				$where                       	  = array();
				$key                         	  = $this->get_hash_key('employee_id');
				$where[$key]                 	  = $id;
				$where['address_type_id'] 	 	  = RESIDENTIAL_ADDRESS;
				$residential_address_info         = $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['residential_address_info'] = $residential_address_info;
				$data['residential_address_values'] = explode("|", $residential_address_info['address_value']);
				

				$where                       	  = array();
				$key                         	  = $this->get_hash_key('employee_id');
				$where[$key]                 	  = $id;
				$where['address_type_id'] 	 	  = PERMANENT_ADDRESS;
				$permanent_address_info           = $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['permanent_address_info'] = $permanent_address_info;
				$data['permanent_address_values'] = explode("|", $permanent_address_info['address_value']);

				$resources['single']		   = array(
					'ext_name'                 => $personal_info['ext_name'],
					'civil_status'             => $personal_info['civil_status_id'],
					'citizenships'             => $personal_info['citizenship_id'],
					'citizenship_basis'        => $personal_info['citizenship_basis_id'],
					'blood_type'               => $personal_info['blood_type_id'],
					// 'municipality_residential' => $residential_address_info['codes'],
					// 'municipality_permanent'   => $permanent_address_info['codes']
				);
			}
			else
			{
				//GET NEW EMPLOYEE ID				
				$field 						= array("MAX(employee_id + 1) as new_id");
				$table						= $this->pds->tbl_employee_personal_info;
				$where						= array();
				$data['new_id'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);
				$resources['single']		= array(
					'citizenships'      	=> '43',
				);
			}

			// GET CITIZENSHIP BASIS
			$field                       	= array("sys_param_name", "sys_param_value");
			$table					 		= $this->pds->db_core.".".$this->pds->tbl_sys_param;
			$where                       	= array();
			$where['sys_param_type'] 		= CITIZENSHIP_BASIS;
			$citizenship_basis      		= $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['citizenship_basis'] 		= $citizenship_basis;

			// GET IDENTIFICATION TYPE FORMAT
			$field                       	= array("identification_type_id, format") ;
			$table                       	= $this->pds->tbl_param_identification_types;
			$where                       	= array();
			$where['builtin_flag']     		= 'Y';
			$identification_format      	= $this->pds->get_general_data($field, $table, $where, TRUE);
			$data['identification_format'] 	= $identification_format;

			//GET CIVIL STATUSES
			$field 						  = array('civil_status_id', 'UPPER(civil_status_name) AS civil_status_name');
			$table						  = $this->pds->tbl_param_civil_status;
			$where						  = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 	  = YES;			
			}
			else
			{
				$where['active_flag'] 	  = array(YES, array("=", "OR", "(")); 
				$where['civil_status_id'] = array($personal_info['civil_status_id'], array("=", ")"));
			}
			$data['civil_status'] 		  = $this->pds->get_general_data($field, $table, $where, TRUE);

			//GET BLOOD TYPES
			$field 						= array('blood_type_id', 'UPPER(blood_type_name) AS blood_type_name');
			$table						= $this->pds->tbl_param_blood_type;
			$where						= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 	= YES;			
			}
			else
			{
				$where['active_flag'] 	= array(YES, array("=", "OR", "(")); 
				$where['blood_type_id'] = array($personal_info['blood_type_id'], array("=", ")"));
			}
			$data['blood_types'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//GET CITIZENSHIPS
			$field 						= array('citizenship_id', 'UPPER(citizenship_name) AS citizenship_name');
			$table						= $this->pds->tbl_param_citizenships;
			$where						= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 	= YES;			
			}
			else
			{
				$where['active_flag'] 	= array(YES, array("=", "OR", "(")); 
				$where['citizenship_id']= array($personal_info['citizenship_id'], array("=", ")"));
			}
			$data['citizenships'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);			

			//GET MUNICIPALITY PROVINCE AND REGION 			
			$regions_values 				= $this->pds->get_regions();
			$provinces_values 				= $this->pds->get_provinces();
			$municipalities_values 			= $this->pds->get_municipalities();
			$barangays_values 				= $this->pds->get_barangays();
			// echo"<pre>";
			// print_r($regions_values);
			// die();
			$data['regions_values']  		= $regions_values;
			$data['provinces_value']  		= $provinces_values;
			$data['municipalities_value'] 	= $municipalities_values;
			$data['barangays_value']		= $barangays_values;
			// $province_code = "01028";
			// $municipalities_values = $this->pds->get_municipalities($province_code);
			// foreach($municipalities_values as $municipalities_value)
			// {
			// 	$data['municipalities_value']   .= "<option value='".$municipalities_value['municity_code']."'>".$municipalities_value['municity_name']."</option>";
			// }
			// $barangays_values = $this->pds->get_barangays();
			// foreach($barangays_values as $barangays_value)
			// {
			// 	$data['barangays_value']   .= "<option value='".$barangays_value['barangay_code']."'>".$barangays_value['barangay_name']."</option>";
			// }

			$field                   = array('*');
			$table                   = $this->pds->tbl_param_identification_types;
			$where                   = array( 'builtin_flag' => 'Y' );
			$data['identifications'] = $this->pds->get_general_data($field, $table, $where);

		}
		catch(Exception $e)
		{
			$data['message'] = $e->getMessage();
		}
		
		$this->load->view('pds/tabs/personal_info', $data);
		$this->load_resources->get_resource($resources);		
	}
	
	/*PROCESS PERSONAL INFO*/
	public function process()
	{
		try
		{			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";
			$proceed_flag 	= "Y";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id']; 
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
						
			// REMOVE THE SYMBOLS IN EACH IDENTIFICATIONS
			$field                   = array('*');
			$table                   = $this->pds->tbl_param_identification_types;
			$where                   = array( 'builtin_flag' => 'Y' );
			$identifications 		 = $this->pds->get_general_data($field, $table, $where);

			$separator = array();
			foreach ($identifications as $key => $value) 
			{
				$concat = explode('x', $value['format']);
				$separator[] = $concat[count($concat)-1];
			}

			$params['tin_value']        = str_replace($separator[0], '', $params['tin_value']);
			$params['sss_value']        = str_replace($separator[1], '', $params['sss_value']);
			$params['gsis_value']       = str_replace($separator[2], '', $params['gsis_value']);
			$params['pagibig_value']    = str_replace($separator[3], '', $params['pagibig_value']);
			$params['philhealth_value'] = str_replace($separator[4], '', $params['philhealth_value']);

			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_personal_info($params);
			//PERSONAL INFO
			//davcorrea : START: Middle Name validation
			if(strpos($params['middle_name'] , '-') !== false || strpos($params['middle_name'], '/') !== false || strpos($params['middle_name'], '\\') !== false)
			{
				// throw new Exception("Middle Name Must Not Contain Special Characters");
				 throw new Exception("Middle Name Must Not Contain Special Characters. Please Put NA If Middle Name is Not Applicable."); // davcorrea : 10/24/2023 modif to error message 
			}
			// davcorrea END

			if($params['proceed_flag'] == "N")
			{
				$field                      = array('*');
				$table                      = $this->pds->tbl_employee_personal_info;
				$where                      = array();
				$where['UPPER(last_name)']  = strtoupper($params['last_name']);
				$where['UPPER(first_name)'] = strtoupper($params['first_name']);
				$where['UPPER(middle_name)']= strtoupper($params['middle_name']); // ncocampo
				$where['birth_date']        = format_date($params['birth_date'],'Y-m-d');
				$duplicate_record           = $this->pds->get_general_data($field, $table, $where);

				// NCOCAMPO : CHECKS IF WITH DUPLICATE EID OR LNAME, FNAME & MNAME : START
				$where                      = array();
				$where['UPPER(last_name)']  = strtoupper($params['last_name']);
				$where['UPPER(first_name)'] = strtoupper($params['first_name']);
				$where['UPPER(middle_name)']= strtoupper($params['middle_name']); // ncocampo
				$duplicate_record_name      = $this->pds->get_general_data($field, $table, $where);

				$where                      = array();
				$where['biometric_pin']  	= strtoupper($params['biometric_pin']);
				$duplicate_record_eid       = $this->pds->get_general_data($field, $table, $where);

				// if(!EMPTY($duplicate_record))
				if(!EMPTY($duplicate_record) || !EMPTY($duplicate_record_name)  || !EMPTY($duplicate_record_eid))

				{
					$status 		= TRUE;
					$proceed_flag 	= "N";
					throw new Exception();
				}
			}
			// NCOCAMPO : CHECKS IF WITH DUPLICATE EID OR LNAME, FNAME & MNAME : END

			$fields                       	= array() ;
			// $fields['agency_employee_id'] = $valid_data["agency_employee_id"];
			// $fields['biometric_pin']      = $valid_data["biometric_pin"];
			$fields['first_name']         	= $valid_data["first_name"];
			$fields['last_name']          	= $valid_data["last_name"];
			$fields['middle_name']        	= $valid_data["middle_name"];
			$fields['ext_name']           	= $valid_data["ext_name"];
			$fields['birth_date']         	= $valid_data["birth_date"];
			$fields['birth_place']        	= $valid_data["birth_place"];
			$fields['gender_code']        	= $valid_data["gender"];
			$fields['civil_status_id']    	= $valid_data["civil_status"];
			$fields['citizenship_id']     	= $valid_data["citizenships"];
			$fields['citizenship_basis_id'] = $valid_data["citizenship_basis"];
			$fields['height']             	= $valid_data["height"];
			$fields['weight']             	= $valid_data["weight"];
			$fields['blood_type_id']      	= $valid_data["blood_type"];				
			
			$fields_identification   = array();
			$fields_identification[0]['identification_value']   = $valid_data['tin_value'];
			$fields_identification[1]['identification_value']   = !EMPTY($valid_data['sss_value']) ? $valid_data['sss_value'] : '';
			$fields_identification[2]['identification_value']   = $valid_data['gsis_value'];
			$fields_identification[3]['identification_value']   = $valid_data['pagibig_value'];
			$fields_identification[4]['identification_value']   = $valid_data['philhealth_value'];
			
			//IDENTIFICATION ID
			$fields_identification[0]['identification_type_id'] = TIN_TYPE_ID;
			$fields_identification[1]['identification_type_id'] = SSS_TYPE_ID;
			$fields_identification[2]['identification_type_id'] = GSIS_TYPE_ID;
			$fields_identification[3]['identification_type_id'] = PAGIBIG_TYPE_ID;
			$fields_identification[4]['identification_type_id'] = PHILHEALTH_TYPE_ID;
			
			//CONTACT INFORMATION
			$fields_contact = array();

			$fields_contact[0]['contact_value']   = str_replace(CONTACT_SEPARATOR, '', $valid_data["telephone_residential_value"]);
			$fields_contact[1]['contact_value']   = $valid_data["email_value"];
			$fields_contact[2]['contact_value']   = str_replace(CONTACT_SEPARATOR, '', $valid_data["telephone_permanent_value"]);
			$fields_contact[3]['contact_value']   = str_replace(CONTACT_SEPARATOR, '', $valid_data["cellphone_value"]);
			
			$fields_contact[0]['contact_type_id'] = PERMANENT_NUMBER;
			$fields_contact[1]['contact_type_id'] = EMAIL;
			$fields_contact[2]['contact_type_id'] = RESIDENTIAL_NUMBER;
			$fields_contact[3]['contact_type_id'] = MOBILE_NUMBER;
			
			//ADDRESS INFORMATION 
			$fields_address    = array();
			
			$str_residential   = $params["municipality_residential"];
			$codes_residential = explode(' ',$str_residential);

			$fields_address[0]['barangay_code'] = $params['barangay_residential'];
			$fields_address[0]['municity_code'] = $params['municipalities_residential'];
			$fields_address[0]['province_code'] = $params['province_residential'];
			$fields_address[0]['region_code']   = $params['region_residential'];
			$fields_address[0]['address_value'] = $params["residential_house_num"]."|".$params["residential_street_name"]."|".$params["residential_subdivision_name"];	
			$fields_address[0]['postal_number'] = $valid_data["residential_zip_code"];

			$str_permanent   = $params["municipality_permanent"];
			$codes_permanent = explode(' ',$str_permanent);

			$fields_address[1]['barangay_code'] = $params['barangay_permanent'];
			$fields_address[1]['municity_code'] = $params['municipalities_permanent'];
			$fields_address[1]['province_code'] = $params['province_permanent'];
			$fields_address[1]['region_code']   = $params['region_permanent'];
			$fields_address[1]['address_value'] = $params["permanent_house_num"]."|".$params["permanent_street_name"]."|".$params["permanent_subdivision_name"];	
			$fields_address[1]['postal_number'] = $valid_data["permanent_zip_code"];

			$fields_address[0]['address_type_id'] = RESIDENTIAL_ADDRESS;
			$fields_address[1]['address_type_id'] = PERMANENT_ADDRESS;

			Main_Model::beginTransaction();

			// GET ID PADDING
			$field 						= array("sys_param_value") ;
			$table						= $this->pds->db_core.".".$this->pds->tbl_sys_param;
			$where						= array();
			$where['sys_param_type']	= AGENCY_EMPLOYEE_NUM_PAD;
			$data['padding'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);

			// GET UPDATED AGENCY NUMBERS
			$field 						= array('year', 'last_seq_num');
			$table						= $this->pds->tbl_param_agency_numbers;
			$where						= array();
			$where['year']				= date('Y');
			$agency_number 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			// IF NO RECORD FOR CURRENT YEAR, INSERT THIS YEAR AND 1 FOR LAST SEQ NUM
			if(EMPTY($agency_number))
			{
				$fields_agency_number				  = array();
				$fields_agency_number['year']   	  = date('Y');
				$fields_agency_number['last_seq_num'] = '0';
				$this->pds->insert_general_data($this->pds->tbl_param_agency_numbers,$fields_agency_number, FALSE);

				// GET UPDATED AGENCY NUMBERS
				$field 			  = array('year', 'last_seq_num');
				$table			  = $this->pds->tbl_param_agency_numbers;
				$where			  = array();
				$where['year']	  = date('Y');
				$agency_number 	  = array();
				$agency_number 	  = $this->pds->get_general_data($field, $table, $where, FALSE);

				// echo '<pre>';
				// print_r($agency_number);
				// die();
			}

			// SEPARATE YEAR AND LAST NUM FROM INPUT FIELD AGENCY EMPLOYEE ID
			$agency_employee_id   = $valid_data['agency_employee_id'];
			$encoded_year 		  = substr($agency_employee_id, 0, -4);
			$encoded_last_num	  = substr($agency_employee_id, 4);

			// CHECKS IF ENCODED OR GENERATED
			if(strpos($encoded_last_num, '#') !== false) {
				$encoded_last_num = str_pad($agency_number['last_seq_num'] + 1, $data['padding']['sys_param_value'], "0", STR_PAD_LEFT);
				$last_seq_num 	  = $agency_number['last_seq_num'] + 1;
			}
			else {
				$last_seq_num 	  = round($encoded_last_num);
			}
			// RESULT ID
			$encoded_agency_id = $encoded_year . $encoded_last_num;			

			// CHECKS IF AGENCY EMPLOYEE ID ALREADY EXISTS
			$where						  = array();
			$where['agency_employee_id']  = $encoded_agency_id;
			$key                          = $this->get_hash_key('employee_id');
			$where[$key] 				  = array($id, array("!="));
			$agency_employee_id 		  = $this->pds->get_general_data(array('*'), $this->pds->tbl_employee_personal_info, $where, FALSE);
			
			// CONTINUE TO LOOP IF ID ALREADY EXISTS
			if($agency_employee_id)
			{
				$generated_id = TRUE;
			} else
			{
				$generated_id = FALSE;
				$fields['agency_employee_id'] = $encoded_agency_id;
				$fields['biometric_pin']      = $encoded_agency_id;
			}
			
			// GET NUMBER OF LOOP
			$field 					 = array("sys_param_value") ;
			$table					 = $this->pds->db_core.".".$this->pds->tbl_sys_param;
			$where					 = array();
			$where['sys_param_type'] = GENERATE_ID_LOOP_COUNT;
			$loop			 		 = $this->pds->get_general_data($field, $table, $where, FALSE);

			/*------------- GENERATION OF ID LOOP START -------------*/
			$cnt = 0;
			while ($generated_id) {
				$cnt++;
				$encoded_agency_id 			  = $encoded_agency_id + 1;

				$where						  = array();
				$where['agency_employee_id']  = $encoded_agency_id;
				$key                          = $this->get_hash_key('employee_id');
				$where[$key] 				  = array($id, array("!="));
				$agency_employee_id 		  = $this->pds->get_general_data(array('*'), $this->pds->tbl_employee_personal_info, $where, FALSE);

				if(EMPTY($agency_employee_id))
				{
					$fields['agency_employee_id'] = $encoded_agency_id;
					$fields['biometric_pin']      = $encoded_agency_id;
					break;
				}
				if(!EMPTY($agency_employee_id) AND $cnt == $loop['sys_param_value'])
				{
					throw new Exception($this->lang->line('generate_id'));
				}
			}
			/*------------- GENERATION OF ID LOOP END -------------*/

			// SEPARATE YEAR AND LAST NUM FROM INPUT FIELD AGENCY EMPLOYEE ID
			$year 		  = substr($encoded_agency_id, 0, -4);
			$last_num	  = substr($encoded_agency_id, 4);
			$num	  	  = round($last_num);

			// UPDATE PARAM AGENCY NUMBERS IF CURRENT LAST SEQ NUM IS LESS THAN NEW LAST SEQ NUM, OTHERWISE, RETAIN OLD.
			if($agency_number['last_seq_num'] <= $num)
			{
				$fields_agency_number				  = array();
				$fields_agency_number['last_seq_num'] = $num;
				$where								  = array();
				$where['year']						  = $year;	
				$this->pds->update_general_data($this->pds->tbl_param_agency_numbers, $fields_agency_number, $where);
			}

			if($action == ACTION_ADD)
			{					
				$fields['pds_status_id'] 	= 1;		
				
				$table                   = $this->pds->tbl_employee_personal_info;
				$employee_system_id      = $this->pds->insert_general_data($table,$fields, TRUE);

				$table_identification    = $this->pds->tbl_employee_identifications;
				$table_contact           = $this->pds->tbl_employee_contacts;
				$table_address           = $this->pds->tbl_employee_addresses;
				
				foreach ($fields_identification as $key => $value) {
					$value['employee_id'] = $employee_system_id;
					$this->pds->insert_general_data($table_identification,$value, FALSE);
				}

				foreach ($fields_contact as $key => $value) {
					$value['employee_id'] = $employee_system_id;
					$this->pds->insert_general_data($table_contact, $value, FALSE);
				}

				foreach ($fields_address as $key => $value) {
					$value['employee_id'] = $employee_system_id;
					$this->pds->insert_general_data($table_address, $value, FALSE);
				}

				$audit_table[]  = $this->pds->tbl_employee_personal_info;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	
				
				$activity       = "%s has been added to employee records.";
				$audit_activity = sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);
				
				/*RE-INITIALIZE SECURITY VARIABLES*/
				/*Updating from action add to action edit*/
				$edit_salt      = gen_salt();
				$edit_id        = $this->hash($employee_system_id);
				$token_edit     = in_salt($edit_id  . '/' . ACTION_EDIT  . '/' . $module, $edit_salt);
				$reload_url     = ACTION_EDIT."/".$edit_id ."/".$token_edit."/".$edit_salt."/".$module;
				$status         = true;
				$message        = $this->lang->line('data_saved');

			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field          = array("*") ;
				$table          = $this->pds->tbl_employee_personal_info;
				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $id;
				$personal_info  = $this->pds->get_general_data($field, $table, $where, FALSE);

				//UPDATE PERSONAL INFO

				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $id;
				$table          = $this->pds->tbl_employee_personal_info;
				$this->pds->update_general_data($table,$fields,$where);
				
				// UPDATE EMPLOYEE IDENTIFICATION				
				// CHECK IF THE CURRENT EMPLOYEE HAS A RECORD ON `employee_identifications` TABLE
				foreach ($fields_identification as $key => $value) 
				{
					$table 				= array(
						'main'  		=> array(
							'table' 	=> $this->pds->tbl_employee_identifications,
							'alias' 	=> 'A',
						),
						't2'    		=> array(
							'table' 	=> $this->pds->tbl_param_identification_types,
							'alias' 	=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.identification_type_id = B.identification_type_id'
						)
					);

					$where                   		   = array();
					$key                     		   = $this->get_hash_key('A.employee_id');
					$where[$key]             		   = $id;
					$where['B.builtin_flag'] 		   = 'Y';
					$where['A.identification_type_id'] = $value['identification_type_id'];
					$prev_info               		   = $this->pds->get_general_data(array('A.employee_id'), $table, $where, FALSE);

					$table       = $this->pds->tbl_employee_identifications;
					$key         = $this->get_hash_key('employee_id');
					$where       = array();
					$where[$key] = $id;

					if($prev_info) 
					{ 
						// UPDATE RECORD IF EXIST						
						$where['identification_type_id'] = $value['identification_type_id'];

						unset($value['identification_type_id']);

						$this->pds->update_general_data($table,$value,$where);
					}
					else 
					{ // INSERT IF NOT
						$prev_info            = $this->pds->get_general_data(array('employee_id'), $this->pds->tbl_employee_personal_info, $where, FALSE);
						$value['employee_id'] = $prev_info['employee_id'];

						$this->pds->insert_general_data($table,$value,$where);
					}
				}

				// UPDATE EMPLOYEE CONTACTS				
				// CHECK IF THE CURRENT EMPLOYEE HAS A RECORD ON `employee_contacts` TABLE
				foreach ($fields_contact as $key => $value) 
				{
					$table 				= array(
						'main'  		=> array(
							'table' 	=> $this->pds->tbl_employee_contacts,
							'alias' 	=> 'A',
						),
						't2'    		=> array(
							'table' 	=> $this->pds->tbl_param_contact_types,
							'alias' 	=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.contact_type_id = B.contact_type_id'
						)
					);

					$where                   		   = array();
					$key                     		   = $this->get_hash_key('A.employee_id');
					$where[$key]             		   = $id;
					$where['B.builtin_flag'] 		   = 'Y';
					$where['A.contact_type_id'] 	   = $value['contact_type_id'];
					$prev_info               		   = $this->pds->get_general_data(array('A.employee_id'), $table, $where, FALSE);

					$table       = $this->pds->tbl_employee_contacts;
					$key         = $this->get_hash_key('employee_id');
					$where       = array();
					$where[$key] = $id;

					if($prev_info) 
					{ 
						// UPDATE RECORD IF EXIST						
						$where['contact_type_id'] = $value['contact_type_id'];
						
						unset($value['contact_type_id']);

						$this->pds->update_general_data($table,$value,$where);
					}
					else 
					{ // INSERT IF NOT
						$prev_info            = $this->pds->get_general_data(array('employee_id'), $this->pds->tbl_employee_personal_info, $where, FALSE);
						$value['employee_id'] = $prev_info['employee_id'];

						$this->pds->insert_general_data($table,$value,$where);
					}
				}

				// UPDATE EMPLOYEE ADDRESSES				
				// CHECK IF THE CURRENT EMPLOYEE HAS A RECORD ON `employee_addresses` TABLE
				foreach ($fields_address as $key => $value) 
				{
					$table 				= array(
						'main'  		=> array(
							'table' 	=> $this->pds->tbl_employee_addresses,
							'alias' 	=> 'A',
						),
						't2'    		=> array(
							'table' 	=> $this->pds->tbl_param_address_types,
							'alias' 	=> 'B',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'A.address_type_id = B.address_type_id'
						)
					);

					$where                   		   = array();
					$key                     		   = $this->get_hash_key('A.employee_id');
					$where[$key]             		   = $id;
					$where['B.builtin_flag'] 		   = 'Y';
					$where['A.address_type_id'] 	   = $value['address_type_id'];
					$prev_info               		   = $this->pds->get_general_data(array('A.employee_id'), $table, $where, FALSE);

					$table       = $this->pds->tbl_employee_addresses;
					$key         = $this->get_hash_key('employee_id');
					$where       = array();
					$where[$key] = $id;

					if($prev_info) 
					{ 
						// UPDATE RECORD IF EXIST						
						$where['address_type_id'] = $value['address_type_id'];
						
						unset($value['address_type_id']);

						$this->pds->update_general_data($table,$value,$where);
					}
					else 
					{ // INSERT IF NOT
						$prev_info            = $this->pds->get_general_data(array('employee_id'), $this->pds->tbl_employee_personal_info, $where, FALSE);
						$value['employee_id'] = $prev_info['employee_id'];

						$this->pds->insert_general_data($table,$value,$where);
					}
				}				
				
				//AUDIT TRAIL
				$audit_table[]  = $this->pds->tbl_employee_personal_info;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($personal_info);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				$activity       = "%s has been Updated.";
				$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
				$edit_salt      = gen_salt();
				$reload_url     = ACTION_EDIT."/".$id ."/".$token."/".$salt."/".$module;
				$status         = true;
				$message        = $this->lang->line('data_updated');
			}
			
			$this->pds->update_pds_date_accomplished($id);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);

			Main_Model::commit();			
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}

		$data					= array();
		$data['proceed_flag'] 	= $proceed_flag;
		$data['reload_url'] 	= $reload_url;
		$data['status']			= $status;
		$data['message']		= $message;
		
		echo json_encode($data);
	}

	public function get_generated_id()
	{
		try 
		{
			// $field 						= array("year", "last_seq_num");
			// $table						= $this->pds->tbl_param_agency_numbers;
			// $where						= array();
			// $where['year']				= date('Y');
			// $data['new_id'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);

			$field 						= array("sys_param_value") ;
			$table						= $this->pds->db_core.".".$this->pds->tbl_sys_param;
			$where						= array();
			$where['sys_param_type']	= AGENCY_EMPLOYEE_NUM_PAD;
			$data['padding'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);

			//$str 		  = '';
			$num 		  = '';
			$year 		  = '';
			//$str 		  = $data['new_id']['last_seq_num'] + 1;
			$year 		  = date('Y');
			$num 		  = $data['padding']['sys_param_value'];

			// ADD PADDING TO LAST SEQ NUM
			$generated_id = $year . str_pad('', $num, "#", STR_PAD_LEFT);
			
		} catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info = array(
			"generated_id"	=> $generated_id
		);

		echo json_encode($info);
	}
	public function get_provinces()
	{
		try 
		{
			$params 					= get_params();
			$field 						= array("*") ;
			$table						= $this->pds->db_core.".".$this->pds->tbl_param_provinces;
			$where						= array();		
			$provinces_values = $this->pds->get_general_data($field, $table, $where, TRUE);
			
		} catch (Exception $e) {
			$msg =  $e->getMessage();
		}



		echo json_encode($info);
	}

	private function _validate_personal_info($params)
	{
		try
		{
			$fields 							   = array();
			$fields['agency_employee_id']          = "Employee Number";
			$fields['biometric_pin']               = "Biometric Pin";
			$fields['first_name']                  = "First Name";
			$fields['last_name']                   = "Last Name";
			$fields['middle_name']                 = "Middle Name";
			$fields['birth_date']                  = "Birth Date";
			$fields['birth_place']                 = "Birth Place";
			$fields['gender']                      = "Gender";
			$fields['civil_status']                = "Civil Status";
			$fields['citizenships']                = "Citizenship";
			if ($params['citizenships'] != CITIZENSHIP_FILIPINO )
				$fields['citizenship_basis']       = "Basis of Citizenship";
			$fields['height']                      = "Height";
			$fields['weight']                      = "Weight";
			$fields['blood_type']                  = "Blood Type";
			$fields['email_value']                 = "Email";
			$fields['province_residential']        = "Residential Province";
			$fields['municipalities_residential']  = "Residential Municipality";
			$fields['barangay_residential']        = "Residential Barangay";
			$fields['residential_house_num']       = "Residential House/ Block/ Lot Number";
			$fields['residential_street_name']     = "Residential Street";
			$fields['residential_subdivision_name']= "Residential Subdivision/ Village";
			$fields['residential_zip_code']        = "Residential Zip Code";
			$fields['province_permanent']          = "Permanent Province";
			$fields['municipalities_permanent']    = "Permanent Municipality";
			$fields['barangay_permanent']          = "Permanent Barangay";
			$fields['permanent_house_num']         = "Permanent House/ Block/ Lot Number";
			$fields['permanent_street_name']       = "Permanent Street";
			$fields['permanent_subdivision_name']  = "Permanent Subdivision/ Village";
			$fields['permanent_zip_code']          = "Permanent Zip Code";

			// $fields['residential_address_value']   = "Residential Address";
			// $fields['permanent_address_value']     = "Permanent Address";
			// $param['municipality_residential']     = "Residential Municipality";
			// $param['municipality_permanent']       = "Permanent Municipality";

			if(EMPTY($params['gsis_value']))
			{
				throw new Exception('<b>GSIS Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['pagibig_value']))
			{
				throw new Exception('<b>Pag-IBIG Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['philhealth_value']))
			{
				throw new Exception('<b>Philhealth Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['sss_value']))
			{
				throw new Exception('<b>SSS Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['tin_value']))
			{
				throw new Exception('<b>TIN Number</b>' . $this->lang->line('not_applicable'));
			}

			//NCOCAMPO: NOTIFICATION ADDED :START
			if(EMPTY($params['cellphone_value']))
			{
				throw new Exception('<b>Cellphone Number</b>' . $this->lang->line('not_applicable'));
			}
			//NCOCAMPO: NOTIFICATION ADDED :END

			/*if(EMPTY($params['telephone_residential_value']))
			{
				throw new Exception('<b>Residential Telephone Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['telephone_permanent_value']))
			{
				throw new Exception('<b>Permanent Telephone Number</b>' . $this->lang->line('not_applicable'));
			}*/
			$this->check_required_fields($params, $fields);

			return $this->_validate_input_personal_info($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}

	}
 
	private function _validate_input_personal_info($params)
	{
		try
		{
			// CHECK "NA" OR "N/A" FOR IDENTIFICATION NUMBERS
			$gsis_str 		= str_replace("-", "", $params['gsis_value']);
			$gsis_valid 	= $this->check_na($gsis_str);
			if(!$gsis_valid)
			{
				throw new Exception('Invalid input in <b>GSIS Number</b>.');
			}
			
			$sss_str 		= str_replace("-", "", $params['sss_value']);
			$sss_valid		= $this->check_na($sss_str);
			if(!$sss_valid)
			{
				throw new Exception('Invalid input in <b>SSS Number</b>.');
			}

			$tin_str 		= str_replace("-", "", $params['tin_value']);
			$tin_valid		= $this->check_na($tin_str);
			if(!$tin_valid)
			{
				throw new Exception('Invalid input in <b>TIN Number</b>.');
			}

			$phil_str 		= str_replace("-", "", $params['philhealth_value']);
			$phil_valid		= $this->check_na($phil_str);
			if(!$phil_valid)
			{
				throw new Exception('Invalid input in <b>Philhealth Number</b>.');
			}

			$pagibig_str 		= str_replace("-", "", $params['pagibig_value']);
			$pagibig_valid		= $this->check_na($pagibig_str);
			if(!$pagibig_valid)
			{
				throw new Exception('Invalid input in <b>Pag-IBIG Number</b>.');
			}

			//NCOCAMPO:CHECK "NA" OR "N/A" FOR CONTACT NUMBERS :START
			$cellphone_number_str 		= str_replace("-", "", $params['cellphone_value']);
			$cellphone_number_valid		= $this->check_na($cellphone_number_str);
			if(!$cellphone_number_valid)
			{
				throw new Exception('Invalid input in <b>Cellphone Number</b>.');
			}
			//NCOCAMPO:CHECK "NA" OR "N/A" FOR CONTACT NUMBERS :END	

			// $perm_tel_str 		= str_replace("-", "", $params['telephone_permanent_value']);
			// $perm_tel_valid		= $this->check_na($perm_tel_str);
			// if(!$perm_tel_valid)
			// {
			// 	throw new Exception('Invalid input in <b>Permanent Telephone Number</b>.');
			// }

			// $perm_res_str 		= str_replace("-", "", $params['telephone_residential_value']);
			// $perm_res_valid		= $this->check_na($perm_res_str);
			// if(!$perm_res_valid)
			// {
			// 	throw new Exception('Invalid input in <b>Residential Telephone Number</b>.');
			// }

			
			$validation['agency_employee_id'] = array(
				'data_type' => 'string',
				'name'		=> 'Employee Number',
				'max_len'	=> 8,
				'min_len'	=> 8
			);
			$validation['biometric_pin'] = array(
				'data_type' => 'string',
				'name'		=> 'Biometric Pin',
				'max_len'	=> 8,
				'min_len'	=> 8
			);
			$validation['first_name'] = array(
				'data_type' => 'string',
				'name'		=> 'First Name',
				'max_len'	=> 50
			);
			$validation['last_name'] = array(
				'data_type' => 'string',
				'name'		=> 'Last Name',
				'max_len'	=> 50
			);
			$validation['middle_name'] = array(
				'data_type' => 'string',
				'name'		=> 'Middle Name',
				'max_len'	=> 50
			);	
			$validation['ext_name'] = array(
				'data_type' => 'string',
				'name'		=> 'Last Name',
				'max_len'	=> 50
			);	
			$validation['birth_date'] = array(
				'data_type' => 'date',
				'name'		=> 'Birth Date',
				'max_date'	=> date("Y/m/d")
			);
			$validation['birth_place'] = array(
				'data_type' => 'string',
				'name'		=> 'Birth Place',
				'max_len'	=> 100
			);	
			$validation['civil_status'] = array(
				'data_type' => 'digit',
				'name'		=> 'Civil Status'
			);	
			$validation['citizenships'] = array(
				'data_type' => 'digit',
				'name'		=> 'Citizenship'
			);				
			$validation['citizenship_basis'] = array(
				'data_type' => 'digit',
				'name'		=> 'Basis of Citizenship'
			);	
			$validation['height'] = array(
				'data_type' => 'amount',
				'name'		=> 'Height',
				'decimal'	=> 2
			);	
			$validation['weight'] = array(
				'data_type' => 'amount',
				'name'		=> 'Weight',
				'decimal'	=> 2
			);	
			$validation['blood_type'] = array(
				'data_type' => 'digit',
				'name'		=> 'Blood Type'
			);
			$validation['gender'] = array(
				'data_type' => 'string',
				'name'		=> 'Gender',
				'max_len'	=> 1
			);
			
			// CHECK IF IDENTIFICATION VALUES IS NUMERIC AND ADD MINIMUM LENGTH
			$validation['gsis_value'] = array(
				'data_type' => 'string',
				'name'		=> 'GSIS Number',
			);
			if(is_numeric($gsis_str))
			{
				$validation['gsis_value']['min_len']	= 8;
			}
			
			$validation['pagibig_value'] = array(
				'data_type' => 'string',
				'name'		=> 'Pag-ibig Number',
			);			
			if(is_numeric($pagibig_str))
			{
				$validation['pagibig_value']['min_len']	= 12;
			}

			$validation['philhealth_value'] = array(
				'data_type' => 'string',
				'name'		=> 'Philhealth Number',
			);		
			if(is_numeric($phil_str))
			{
				$validation['philhealth_value']['min_len']	= 12;
			}			
			
			$validation['sss_value'] = array(
				'data_type' => 'string',
				'name'		=> 'SSS Number',
			);
			if(is_numeric($sss_str))
			{
				$validation['sss_value']['min_len']	= 10;
			}	
			
			$validation['tin_value'] = array(
				'data_type' => 'string',
				'name'		=> 'TIN Number',
			);
			if(is_numeric($tin_str))
			{
				$validation['tin_value']['min_len']	= 12;
			}

//NCOCAMPO: ADDED CELLPHONE NUMBER VALIDATION :START
			$validation['cellphone_value'] = array(
				'data_type' => 'string',
				'name'		=> 'Cellphone Number',
			);
			if(is_numeric($cellphone_number_str))
			{
				$validation['cellphone_value']['min_len']	= 11;
			}
//NCOCAMPO:ADDED CELLPHONE NUMBER VALIDATION :END		
			
			/*$validation['tin_value'] = array(
					'data_type' => 'string',
					'name'		=> 'TIN Number',
					'min_len'	=> 12
				);*/
				// $validation['residential_address_value'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Address',
				// 	'max_len'	=> 100
				// );
				// $validation['municipality_residential'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Municipality',
				// 	'max_len'	=> 100
				// );
				// $validation['residential_zip_code'] = array(
				// 	'data_type' => 'digit',
				// 	'name'		=> 'Residential Zip Code',
				// 	'max_len'	=> 6
				// );
				// $validation['telephone_residential_value'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Telephone Number',
				// 	'max_len'	=> 15
				// );
				// $validation['permanent_address_value'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Permanent Address',
				// 	'max_len'	=> 100
				// );
				// $validation['municipality_permanent'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Municipality',
				// 	'max_len'	=> 100
				// );
				$validation['permanent_house_num'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent House/ Block/ Lot Number',
					'max_len'	=> 100
				);
				$validation['permanent_street_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent Street',
					'max_len'	=> 100
				);
				$validation['permanent_subdivision_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent Subdivision/ Village',
					'max_len'	=> 100
				);
				$validation['permanent_zip_code'] = array(
					'data_type' => 'digit',
					'name'		=> 'Permanent Zip Code',
					'max_len'	=> 6
				);
				$validation['residential_house_num'] = array(
					'data_type' => 'string',
					'name'		=> 'Residential House/ Block/ Lot Number',
					'max_len'	=> 100
				);
				$validation['residential_street_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Residential Street',
					'max_len'	=> 100
				);
				$validation['residential_subdivision_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Residential Subdivision/ Village',
					'max_len'	=> 100
				);
				$validation['residential_zip_code'] = array(
					'data_type' => 'digit',
					'name'		=> 'Residential Zip Code',
					'max_len'	=> 6
				);


				$validation['telephone_permanent_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent Telephone Number',
					'max_len'	=> 15
				);

				if(!EMPTY($param['email_value'])):
					$validation['email_value'] = array(
						'data_type' => 'email',
						'name'		=> 'Email Address',
						'max_len'	=> 15
					);
				else:
					$validation['email_value'] = array(
						'data_type' => 'string',
						'name'		=> 'Email Address',
					);
				endif;

				$validation['cellphone_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Cellphone Number',
					'max_len'	=> 15
				);

				return $this->validate_inputs($params, $validation);
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}


		public function check_na($val)
		{
			$valid	= true;
			if(!is_numeric($val))
			{
				if(strcmp($val, "NA") === 0)
				{
					$valid	= true;
				}elseif(strcmp($val, "N/A") === 0){
					$valid	= true;
				}else{
					$valid = false;
				}
			}

			return $valid;
		}


	}
	/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */