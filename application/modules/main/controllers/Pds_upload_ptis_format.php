<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pds_upload_ptis_format extends Main_Controller {

	private $log_user_id		=  '';
	private $log_user_roles		= array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_upload_model', 'pds_upload');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	
	public function modal_pds_upload_ptis_format()
	{
		try
		{
			$data                  = array();
			$resources             = array();
			
			$resources['load_css'] = array(CSS_UPLOAD);
			$resources['load_js']  = array(JS_UPLOAD);

			$this->load->view('pds/modals/modal_pds_upload_ptis_format', $data);
			$this->load_resources->get_resource($resources);			
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}		
	}

	public function process_pds_upload()
	{	
		$current_file_name = NULL;
		
		try
		{

			$status        = false;
			$message       = "";
			$params        = get_params();
			
			$this->load->helper("php_excel");
			
			$show_log        = FALSE;
			$log_content     = "\t\t\t PERSONAL DATA SHEET UPLOAD LOGS \r\n\n\n";
			$log_content    .= "*--------------------------------";
			$log_content    .= "---------------------------------*\r\n\n\n";
			$file_count      = 0;
			$success_count   = $params['success_count'];
			$last_count      = $params['last_count'];
			$commit_flag_cnt = $params['commit_flag_cnt'];
			$proceed_flag 	 = "Y";

			if($params['attachment'])
			{
				$file_count = count($params['attachment']);
				for($file_cntr = $last_count;$file_cntr < $file_count;$file_cntr++ )
				{
					$attachment = $params['attachment'][$file_cntr];					
					
					Main_Model::beginTransaction();

					$dir = FCPATH.PATH_PDS_UPLOADS;
					$file          = $dir.$attachment;

					if (!file_exists($dir)) mkdir($dir, 0777, TRUE);
					
					$sheet_data_1  = open_excel($file,0);
					
					if($commit_flag_cnt != $file_cntr)
					{						
						$last_name   = ISSET($sheet_data_1[10]['D']) ? strtoupper($sheet_data_1[10]['D']):NULL;
						$first_name  = ISSET($sheet_data_1[11]['D']) ? strtoupper($sheet_data_1[11]['D']):NULL;
						$birth_date  = ISSET($sheet_data_1[13]['D']) ? date('Y-m-d',strtotime($sheet_data_1[13]['D'])):NULL;
						
						$field                      = array('*');
						$table                      = $this->pds_upload->tbl_employee_personal_info;
						$where                      = array();
						$where['UPPER(last_name)']  = $last_name;
						$where['UPPER(first_name)'] = $first_name;
						$where['birth_date']        = $birth_date;
						$duplicate_record           = $this->pds_upload->get_general_data($field, $table, $where);

						if(!EMPTY($duplicate_record))
						{
							$status 		= TRUE;
							$proceed_flag 	= "N";
							$last_count 	= $file_cntr;
							RLog::info('LINE 101:');
							throw new Exception();
						}						
					}
					
					$error_checker = FALSE;
					$log_content  .= "File Name :   ".$attachment."\r\n\n\n";
					
					// TODO:
					$current_file_name	= $attachment;

					if(strtolower($sheet_data_1[3]['A']) != strtolower("PERSONAL DATA SHEET"))
					{
						$show_log      = TRUE;
						$log_content  .= "Error : Uploaded File is invalid. Please use the given PDS template. \r\n\n";
						$log_content  .= "Upload Status :   FAIL\r\n\n\n";
						$log_content  .= "*--------------------------------";
						$log_content  .= "---------------------------------*\r\n\n\n";
						$error_checker = TRUE;
					}
					else
					{
						$sheet_data_2 = open_excel($file,1);
						$sheet_data_3 = open_excel($file,2);
						$sheet_data_4 = open_excel($file,3);

	/*=============== START : PEROSNAL INFORMATION INSERT===================================================*/

						$fields                = array();
						$fields['last_name']   = ISSET($sheet_data_1[10]['D']) ? $sheet_data_1[10]['D']:NULL;
						$fields['first_name']  = ISSET($sheet_data_1[11]['D']) ? $sheet_data_1[11]['D']:NULL;
						$fields['middle_name'] = ISSET($sheet_data_1[12]['D']) ? $sheet_data_1[12]['D']:NULL;						
						$ext_name 	   		   = str_replace('NAME EXTENSION (JR., SR)', '', $sheet_data_1[11]['O']);
						$validate_ext_name     = $this->_validate_na_value($ext_name);
						$fields['ext_name']    = ISSET($validate_ext_name) ? $ext_name:'';
						$fields['birth_date']  = ISSET($sheet_data_1[13]['D']) ? date('Y-m-d',strtotime($sheet_data_1[13]['D'])):NULL;
						$fields['birth_place'] = ISSET($sheet_data_1[17]['D']) ? $sheet_data_1[17]['D']:NULL;
						$weight_val 	  	   = filter_var($sheet_data_1[24]['D'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
						$height_val 	       = filter_var($sheet_data_1[26]['D'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
						$fields['height']      = ISSET($weight_val) ? $weight_val:NULL;
						$fields['weight']      = ISSET($height_val) ? $height_val:NULL;
						$fields['date_accomplished']  = ISSET($sheet_data_4[69]['F']) ? date('Y-m-d',strtotime($sheet_data_4[69]['F'])):NULL;

						if ( empty($weight_val) )
						{
							$fields['weight'] = 0.00;
							$log_content          .= "Error : Weight field is required.  \r\n\n";
							$error_checker         = TRUE;							
						}
						if ( empty($height_val) )
						{
							$fields['height'] = 0.00;
							$log_content          .= "Error : Height field is required.  \r\n\n";
							$error_checker         = TRUE;							
						}

						/*GENDER CODE*/
						if(ISSET($sheet_data_1[18]['D']))
						{
							$fields['gender_code'] = MALE;
						}
						elseif(ISSET($sheet_data_1[18]['F']))
						{
							$fields['gender_code'] = FEMALE;
						}
						else
						{
							$fields['gender_code'] = '';
							$log_content          .= "Error : Sex field is required.  \r\n\n";
							$error_checker         = TRUE;
						}

						/*CITIZENSHIPS*/
						$validate_citizenship = $this->_validate_na_value($sheet_data_1[18]['K']);
						if($validate_citizenship)
						{
							$where                            = array();
							$where['LOWER(citizenship_name)'] = strtolower($sheet_data_1[18]['K']);
							$citizenship                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_citizenships, $where, FALSE);
							
							if($citizenship)
							{
								$fields['citizenship_id'] = $citizenship['citizenship_id'];
							}
							else
							{
								$fields['citizenship_id'] = NULL;
								$log_content             .= "Error : Citizenship ".$sheet_data_1[18]['K']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker            = TRUE;
							}
						}
						else
						{
							$fields['citizenship_id'] = NULL;
						}

						/*FILIPINO*/
						if(ISSET($sheet_data_1[14]['L']))
						{
							$fields['citizenship_id'] = CITIZENSHIP_FILIPINO;
						}

						/*CITIZENSHIP BASIS*/
						if(ISSET($sheet_data_1[16]['N']))
						{
							$fields['citizenship_basis_id'] = CITIZENSHIP_BASIS_BIRTH;
						}
						elseif(ISSET($sheet_data_1[16]['Q']))
						{
							$fields['citizenship_basis_id'] = CITIZENSHIP_BASIS_NATURALIZATION;
						}
						else
						{
							$fields['citizenship_basis_id'] = NULL;
						}

						/*CIVIL STATUS*/
						if(ISSET($sheet_data_1[19]['D']))
						{
							$fields['civil_status_id'] = CIVIL_STATUS_SINGLE; 
						}
						elseif(ISSET($sheet_data_1[19]['F']))
						{
							$fields['civil_status_id'] = CIVIL_STATUS_MARRIED; 
						}
						elseif(ISSET($sheet_data_1[20]['D']))
						{
							$fields['civil_status_id'] = CIVIL_STATUS_WIDOW_ER;
						}
						elseif(ISSET($sheet_data_1[20]['F']))
						{
							$fields['civil_status_id'] = CIVIL_STATUS_LEGALLY_SEPERATED; 
						}
						elseif(ISSET($sheet_data_1[22]['D']))
						{
							$where                           	= array();
							$where['LOWER(civil_status_name)'] 	= strtolower($sheet_data_1[22]['F']);
							$civil_status                     	= $this->pds_upload->get_general_data(array('civil_status_id'), $this->pds_upload->tbl_param_civil_status, $where, FALSE);

							if ( ! empty ($civil_status) )
							{
								$fields['civil_status_id'] = $civil_status['civil_status_id']; 
							}
							else
							{
								$fields['civil_status_id'] = NULL;
								$log_content              .= "Error : Civil status ".$sheet_data_1[22]['F']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker             = TRUE;								
							}
						}
						else
						{
							$fields['civil_status_id'] = NULL; 
							$log_content              .= "Error : Civil Status must be filled up.\r\n\n";
							$error_checker             = TRUE;
						}

						/*BLOOD TYPE*/
						$validate_blood_type = $this->_validate_na_value($sheet_data_1[27]['D']);
						if($validate_blood_type)
						{
							$where                           = array();
							$where['LOWER(blood_type_name)'] = strtolower($sheet_data_1[27]['D']);
							$blood_type                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_blood_type, $where, FALSE);
							if($blood_type)
							{
								$fields['blood_type_id'] = $blood_type['blood_type_id'];
							}
							else
							{
								$fields['blood_type_id'] = NULL;
								$log_content            .= "Error : Blood type ".$sheet_data_1[27]['D']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker           = TRUE;
							}
						}
						else
						{
							$fields['blood_type_id'] = NULL;
						}

						$validate_agency_number = $this->_validate_na_value($sheet_data_1[36]['D']);
						if($validate_agency_number)
						{
							$where								= array();
							$where['LOWER(agency_employee_id)']	= strtolower($sheet_data_1[36]['D']);
							$agency_employee_id 				= $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_employee_personal_info, $where, FALSE);
							if($agency_employee_id)
							{
								//throw new Exception('Agency employee Number already exist.');
								
								$log_content           		 .= "Error : Agency employee Number ".strtolower($sheet_data_1[36]['D'])." already exist. \r\n\n";
								$fields['agency_employee_id'] = NULL;
								$fields['biometric_pin']      = NULL;
								$error_checker         		  = TRUE;
							}
							else
							{								
								$fields['agency_employee_id '] = $sheet_data_1[36]['D'];
								$fields['biometric_pin']       = $sheet_data_1[36]['D'];
							}
						}
						
						$fields['pds_status_id']       = 1;
						
						$employee_id                   = $this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_personal_info,$fields,TRUE);
						
	/*============= END: PEROSNAL INFORMATION INSERT===================================================*/

	/*=============== START : IDENTIFICATION INSERT ===================================================*/

					$validate_gsis = $this->_validate_na_value($sheet_data_1[29]['D']);
					if($validate_gsis)
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[29]['D']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= GSIS_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}

					$validate_pagibig = $this->_validate_na_value($sheet_data_1[31]['D']);
					if($validate_pagibig)
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[31]['D']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= PAGIBIG_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}					
					
					$validate_philhealth = $this->_validate_na_value($sheet_data_1[33]['D']);
					if($validate_philhealth)
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[33]['D']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= PHILHEALTH_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}
					
					$validate_sss = $this->_validate_na_value($sheet_data_1[34]['D']);
					if($validate_sss)
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[34]['D']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= SSS_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}	

					$validate_tin = $this->_validate_na_value($sheet_data_1[35]['D']);
					if($validate_tin)
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[35]['D']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= TIN_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}

	/*=============== END : IDENTIFICATION INSERT =====================================================*/

	/*=============== START : CONTACT INFORMATION INSERT ===================================================*/
					
					$validate_residential_addr = $this->_validate_na_value($sheet_data_1[24]['J']);
					if($validate_residential_addr)
					{
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['address_type_id'] = RESIDENTIAL_ADDRESS;
						$fields['address_value']   = ISSET($sheet_data_1[19]['J']) ? $sheet_data_1[19]['J'] . ', ' :'';						
						$fields['address_value']  .= ISSET($sheet_data_1[19]['O']) ? $sheet_data_1[19]['O'] . ', ' :'';
						$fields['address_value']  .= ISSET($sheet_data_1[21]['J']) ? $sheet_data_1[21]['J'] . ', ' :'';						
						$fields['address_value']  .= ISSET($sheet_data_1[21]['O']) ? $sheet_data_1[21]['O'] . ', ' :'';
						$fields['address_value']  .= ISSET($sheet_data_1[24]['J']) ? $sheet_data_1[24]['J'] . ', ' :'';						
						$fields['address_value']  .= ISSET($sheet_data_1[24]['O']) ? $sheet_data_1[24]['O'] :'';
						$fields['postal_number']   = ISSET($sheet_data_1[26]['J']) ? $sheet_data_1[26]['J']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_addresses,$fields,FALSE);
					}

					$validate_permanent_addr = $this->_validate_na_value($sheet_data_1[31]['J']);
					if($validate_permanent_addr)
					{
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['address_type_id'] = PERMANENT_ADDRESS;
						$fields['address_value']   = ISSET($sheet_data_1[27]['J']) ? $sheet_data_1[27]['J'] . ', ' :'';						
						$fields['address_value']  .= ISSET($sheet_data_1[27]['O']) ? $sheet_data_1[27]['O'] . ', ' :'';
						$fields['address_value']  .= ISSET($sheet_data_1[29]['J']) ? $sheet_data_1[29]['J'] . ', ' :'';						
						$fields['address_value']  .= ISSET($sheet_data_1[29]['O']) ? $sheet_data_1[29]['O'] . ', ' :'';
						$fields['address_value']  .= ISSET($sheet_data_1[31]['J']) ? $sheet_data_1[31]['J'] . ', ' :'';						
						$fields['address_value']  .= ISSET($sheet_data_1[31]['O']) ? $sheet_data_1[31]['O'] :'';
						$fields['postal_number']   = ISSET($sheet_data_1[33]['J']) ? $sheet_data_1[33]['J']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_addresses,$fields,FALSE);
					}

					$validate_permanent_no = $this->_validate_na_value($sheet_data_1[34]['J']);
					if($validate_permanent_no)
					{
						$identification_value 	   = str_replace('-', '', $sheet_data_1[34]['J']);
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = PERMANENT_NUMBER;
						$fields['contact_value']   = ISSET($identification_value) ? $identification_value:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}

					$validate_email = $this->_validate_na_value($sheet_data_1[36]['J']);
					if($validate_email)
					{
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = EMAIL;
						$fields['contact_value']   = ISSET($sheet_data_1[36]['J']) ? $sheet_data_1[36]['J']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}

					$validate_mobile_no = $this->_validate_na_value($sheet_data_1[35]['J']);
					if($validate_mobile_no)
					{
						$identification_value 	   = str_replace('-', '', $sheet_data_1[35]['J']);
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = MOBILE_NUMBER;
						$fields['contact_value']   = ISSET($identification_value) ? $identification_value:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}
					
	/*=============== END : CONTACT INFORMATION INSERT =====================================================*/

	/*=============== START : FAMILY INFORMATION INSERT ===================================================*/
					
					$validate_spouse = $this->_validate_na_value($sheet_data_1[38]['D']);
					if($validate_spouse)
					{
						$fields                             = array();
						$fields['employee_id']              = $employee_id;
						$fields['relation_type_id']         = FAMILY_SPOUSE;
						$fields['relation_first_name']      = ISSET($sheet_data_1[39]['D']) ? $sheet_data_1[39]['D']:NULL;
						$fields['relation_middle_name']     = ISSET($sheet_data_1[40]['D']) ? $sheet_data_1[40]['D']:NULL;
						$fields['relation_last_name']       = ISSET($sheet_data_1[38]['D']) ? $sheet_data_1[38]['D']:NULL;
						$spouse_ext_name 					= str_replace('NAME EXTENSION (JR., SR)', '', $sheet_data_1[39]['H']);
						$validate_spouse_ext_name    		= $this->_validate_na_value($spouse_ext_name);
						$fields['relation_ext_name']    	= ISSET($validate_spouse_ext_name) ? $spouse_ext_name:NULL;
						$fields['relation_occupation']      = ISSET($sheet_data_1[41]['D']) ? $sheet_data_1[41]['D']:NULL;
						$fields['relation_company']         = ISSET($sheet_data_1[42]['D']) ? $sheet_data_1[42]['D']:NULL;
						$fields['relation_company_address'] = ISSET($sheet_data_1[43]['D']) ? $sheet_data_1[43]['D']:NULL;
						$fields['relation_contact_num']     = ISSET($sheet_data_1[44]['D']) ? $sheet_data_1[44]['D']:NULL;
						$fields['deceased_flag']     		= NO;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);				
					}

					$validate_father = $this->_validate_na_value($sheet_data_1[45]['D']);
					if($validate_father)
					{
						$fields                         = array();
						$fields['employee_id']          = $employee_id;
						$fields['relation_type_id']     = FAMILY_FATHER;
						$fields['relation_first_name']  = ISSET($sheet_data_1[46]['D']) ? $sheet_data_1[46]['D']:NULL;
						$fields['relation_middle_name'] = ISSET($sheet_data_1[47]['D']) ? $sheet_data_1[47]['D']:NULL;
						$fields['relation_last_name']   = ISSET($sheet_data_1[45]['D']) ? $sheet_data_1[45]['D']:NULL;
						$father_ext_name 				= str_replace('NAME EXTENSION (JR., SR)', '', $sheet_data_1[46]['H']);
						$validate_father_ext_name   	= $this->_validate_na_value($father_ext_name);
						$fields['relation_ext_name']    = ISSET($validate_father_ext_name) ? $father_ext_name:NULL;
						$fields['deceased_flag']     	= NO;
						
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);				
					}

					$validate_mother = $this->_validate_na_value($sheet_data_1[49]['D']);
					if($validate_mother)
					{
						$fields                         = array();
						$fields['employee_id']          = $employee_id;
						$fields['relation_type_id']     = FAMILY_MOTHER;
						$fields['relation_first_name']  = ISSET($sheet_data_1[50]['D']) ? $sheet_data_1[50]['D']:NULL;
						$fields['relation_middle_name'] = ISSET($sheet_data_1[51]['D']) ? $sheet_data_1[51]['D']:NULL;
						$fields['relation_last_name']   = ISSET($sheet_data_1[49]['D']) ? $sheet_data_1[49]['D']:NULL;
						$fields['deceased_flag']     	= NO;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);				
					}

					for($x = 39; $x <=50; $x++)
					{
						$validate_child = $this->_validate_na_value($sheet_data_1[$x]['J']);
						if($validate_child)
						{
							$fields                        = array();
							$fields['employee_id']         = $employee_id;
							$fields['relation_type_id']    = FAMILY_CHILD;
							$fields['relation_first_name'] = ISSET($sheet_data_1[$x]['J']) ? $sheet_data_1[$x]['J']:NULL;
							$fields['relation_last_name']  = '';
							$fields['relation_birth_date'] = ISSET($sheet_data_1[$x]['P']) ? date('Y-m-d',strtotime($sheet_data_1[$x]['P'])):NULL;
							$fields['deceased_flag']       = NO;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);		
						}
					}

	/*=============== END : FAMILY INFORMATION INSERT =====================================================*/
	
	/*=============== START : EDUCATIONAL BACKGROUND INSERT ===================================================*/

					$validate_elementary = $this->_validate_na_value($sheet_data_1[56]['D']);
					if($validate_elementary)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[56]['D']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[56]['D']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[56]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[56]['H']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[56]['H']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_ELEMENTARY;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[56]['P']) ? YES: NO;

						if ( (strpos($sheet_data_1[56]['K'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[56]['K']));
						else
							$fields['start_year']         = $sheet_data_1[56]['K'];
						if ( (strpos($sheet_data_1[56]['M'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[56]['M']));
						else
							$fields['end_year']           = $sheet_data_1[56]['M'];

						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[56]['N']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[56]['Q']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[56]['N']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[56]['Q']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_secondary = $this->_validate_na_value($sheet_data_1[57]['D']);
					if($validate_secondary)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[57]['D']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[57]['D']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[57]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[57]['H']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[57]['H']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}							

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_SECONDARY;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[57]['P']) ? YES: NO;

						if ( (strpos($sheet_data_1[57]['K'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[57]['K']));
						else
							$fields['start_year']         = $sheet_data_1[57]['K'];
						if ( (strpos($sheet_data_1[57]['M'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[57]['M']));
						else
							$fields['end_year']           = $sheet_data_1[57]['M'];

						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[57]['N']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[57]['Q']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[57]['N']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[57]['Q']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_vocational = $this->_validate_na_value($sheet_data_1[58]['D']);
					if($validate_vocational)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[58]['D']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[58]['D']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[58]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[58]['H']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[58]['H']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}							

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_VOCATIONAL;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[58]['P']) ? YES: NO;

						if ( (strpos($sheet_data_1[58]['K'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[58]['K']));
						else
							$fields['start_year']         = $sheet_data_1[58]['K'];
						if ( (strpos($sheet_data_1[58]['M'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[58]['M']));
						else
							$fields['end_year']           = $sheet_data_1[58]['M'];

						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[58]['N']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[58]['Q']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[58]['N']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[58]['Q']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_college = $this->_validate_na_value($sheet_data_1[59]['D']);
					if($validate_college)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[59]['D']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[59]['D']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[59]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[59]['H']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[59]['H']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}							

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_COLLEGE;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[59]['P']) ? YES: NO;

						if ( (strpos($sheet_data_1[59]['K'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[59]['K']));
						else
							$fields['start_year']         = $sheet_data_1[59]['K'];
						if ( (strpos($sheet_data_1[59]['M'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[59]['M']));
						else
							$fields['end_year']           = $sheet_data_1[59]['M'];

						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[59]['N']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[59]['Q']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[59]['N']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[59]['Q']:NULL;			

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_graduate = $this->_validate_na_value($sheet_data_1[60]['D']);
					if($validate_graduate)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[60]['D']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[60]['D']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[60]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[60]['H']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[60]['H']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}							

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_GRADUATE;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[60]['P']) ? YES: NO;

						if ( (strpos($sheet_data_1[60]['K'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[60]['K']));
						else
							$fields['start_year']         = $sheet_data_1[60]['K'];
						if ( (strpos($sheet_data_1[60]['M'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[60]['M']));
						else
							$fields['end_year']           = $sheet_data_1[60]['M'];

						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[60]['N']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[60]['Q']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[60]['N']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[60]['Q']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

	/*=============== END : EDUCATIONAL BACKGROUND INSERT =====================================================*/

	/*=============== START : CIVIL SERVICE ELIGIBILITY INSERT =====================================================*/

					for($x = 5; $x <=11; $x++)
					{
						$validate_eligibility = $this->_validate_na_value($sheet_data_2[$x]['A']);
						if($validate_eligibility)
						{							
							$where									= array();
							$where['LOWER(eligibility_type_name)']	= strtolower($sheet_data_2[$x]['A']);
							$eligibility 							= $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_eligibility_types, $where, FALSE);
							if($eligibility)
							{
								$fields 						= array();
								$fields['employee_id'] 			= $employee_id;
								$fields['eligibility_type_id'] 	= $eligibility['eligibility_type_id'];
								$fields['rating'] 				= ISSET($sheet_data_2[$x]['F']) ? $sheet_data_2[$x]['F']:NULL;
								$fields['exam_date'] 			= ISSET($sheet_data_2[$x]['G']) ? date('Y-m-d',strtotime($sheet_data_2[$x]['G'])):NULL;
								$fields['exam_place'] 			= ISSET($sheet_data_2[$x]['I']) ? $sheet_data_2[$x]['I']:NULL;
								$fields['license_no'] 			= ISSET($sheet_data_2[$x]['L']) ? $sheet_data_2[$x]['L']:NULL;
								$fields['release_date'] 		= ISSET($sheet_data_2[$x]['M']) ? date('Y-m-d',strtotime($sheet_data_2[$x]['M'])):NULL;
								
								$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_eligibility,$fields,FALSE);
							}	
							else
							{
								$log_content  .= "Error : Eligibility type".$sheet_data_2[$x]['A']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker = TRUE;
							}	
						}
					}

	/*=============== END 	: CIVIL SERVICE ELIGIBILITY INSERT =====================================================*/


	/*=============== START : WORK EXPERIENCE INSERT =====================================================*/

					for($x = 18; $x <=45; $x++)
					{
						$validate_employ_status = $this->_validate_na_value($sheet_data_2[$x]['A']);
						if($validate_employ_status)
						{
							$where                                  = array();
							$where['LOWER(employment_status_name)'] = strtolower($sheet_data_2[$x]['L']);
							$appointment                            = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_employment_status, $where, FALSE);
							
							$fields                                 = array();
							$fields['employee_id']                  = $employee_id;

							$start_year_len = strlen($sheet_data_2[$x]['A']);
							$end_year_len 	= strlen($sheet_data_2[$x]['C']);
							// CHECK IF DATE STARTED INPUT IS DATE ONLY
							if($start_year_len == 4)
							{
								$fields['employ_start_date'] = $sheet_data_2[$x]['A'] . '-01-01';
							}
							else
							{
								$fields['employ_start_date'] = !EMPTY($sheet_data_2[$x]['A']) ? date('Y-m-d',strtotime($sheet_data_2[$x]['A'])):NULL;
							}
							// CHECK IF DATE ENDED INPUT IS DATE ONLY
							if(strtolower($sheet_data_2[$x]['C']) == 'present')
							{
								$fields['employ_end_date']   = NULL;
							}
							elseif($end_year_len == 4)
							{
								$fields['employ_end_date'] 	 = $sheet_data_2[$x]['C'] . '-01-01';
							}
							else
							{
								$fields['employ_end_date']   = !EMPTY($sheet_data_2[$x]['C']) ? date('Y-m-d',strtotime($sheet_data_2[$x]['C'])):NULL;
							}
							
							$fields['employ_position_name']  = ISSET($sheet_data_2[$x]['D']) ? $sheet_data_2[$x]['D']:NULL;
							$fields['employ_office_name']    = ISSET($sheet_data_2[$x]['G']) ? $sheet_data_2[$x]['G']:NULL;								
							$salary_amt 					 = filter_var($sheet_data_2[$x]['J'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
							$fields['employ_monthly_salary'] = !EMPTY($salary_amt) ? $salary_amt : 0;

							$validate_step_grade = $this->_validate_na_value($sheet_data_2[$x]['K']);
							if($validate_step_grade)
							{
								$data_explode = explode('-',$sheet_data_2[$x]['K']);

								$salary_grade = $data_explode[0];
								$grade_val    = filter_var($salary_grade, FILTER_SANITIZE_NUMBER_FLOAT);
								$grade 		  = ISSET($grade_val) ? $grade_val : NULL;

								$salary_step  = $data_explode[1];
								$step_val     = filter_var($salary_step, FILTER_SANITIZE_NUMBER_FLOAT);								
								$step 		  = ISSET($step_val) ? $step_val : NULL;
							}

							$fields['employ_salary_grade']  = ISSET($grade) ? $grade:NULL;
							$fields['employ_salary_step']   = ISSET($step) ? $step:NULL;
							$fields['employment_status_id'] = ISSET($appointment['employment_status_id']) ? $appointment ['employment_status_id']:NULL;
							$fields['govt_service_flag']    = (strtoupper($sheet_data_2[$x]['M']) == YES) ? YES:NO;
							$fields['employ_type_flag']  	= (strtoupper($sheet_data_2[$x]['M']) == YES) ? NON_DOH_GOV:PRIVATE_WORK;

							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_work_experiences,$fields,FALSE);				
							
						}
					}
	/*=============== END 	: WORK EXPERIENCE INSERT =====================================================*/

	/*=============== START : VOLUNTARY WORK INSERT =====================================================*/

					for($x = 6; $x <=12; $x++)
					{
						$validate_volunteer = $this->_validate_na_value($sheet_data_3[$x]['A']);
						if($validate_volunteer)
						{							
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['volunteer_org_name'] 	= ISSET($sheet_data_3[$x]['A']) ? $sheet_data_3[$x]['A']:NULL;
							$fields['volunteer_org_address']= NULL;
							$fields['volunteer_start_date'] = ISSET($sheet_data_3[$x]['E']) ? date('Y-m-d',strtotime($sheet_data_3[$x]['E'])):NULL;
							$fields['volunteer_end_date'] 	= ISSET($sheet_data_3[$x]['F']) ? date('Y-m-d',strtotime($sheet_data_3[$x]['F'])):NULL;
							$volunteer_hour_cnt 		    = filter_var($sheet_data_3[$x]['G'], FILTER_SANITIZE_NUMBER_FLOAT);
							$fields['volunteer_hour_count'] = !EMPTY($volunteer_hour_cnt) ? $volunteer_hour_cnt:0;
							$fields['volunteer_position']	= ISSET($sheet_data_3[$x]['H']) ? $sheet_data_3[$x]['H']:NULL;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_voluntary_works,$fields,FALSE);
						}
					}
	/*=============== END 	: VOLUNTARY WORK INSERT =====================================================*/

	/*=============== START : TRAININGS INSERT =====================================================*/
					
					for($x = 19; $x <=39; $x++)
					{
						$validate_training = $this->_validate_na_value($sheet_data_3[$x]['A']);
						if($validate_training)
						{
							if(!EMPTY($sheet_data_3[$x]['E']) AND !EMPTY($sheet_data_3[$x]['F']))
							{
								$fields                          = array();
								$fields['employee_id']           = $employee_id;
								$fields['training_name']         = ISSET($sheet_data_3[$x]['A']) ? $sheet_data_3[$x]['A']:NULL;
								$fields['training_start_date']   = ISSET($sheet_data_3[$x]['E']) ? date('Y-m-d',strtotime($sheet_data_3[$x]['E'])):NULL;
								$fields['training_end_date']     = ISSET($sheet_data_3[$x]['F']) ? date('Y-m-d',strtotime($sheet_data_3[$x]['F'])):'';
								$training_hour_cnt 				 = filter_var($sheet_data_3[$x]['G'], FILTER_SANITIZE_NUMBER_FLOAT);
								$fields['training_hour_count']   = !EMPTY($training_hour_cnt) ? $training_hour_cnt:0;
								$fields['training_type'] 		 = ISSET($sheet_data_3[$x]['H']) ? $sheet_data_3[$x]['H']:'';
								$fields['training_conducted_by'] = ISSET($sheet_data_3[$x]['I']) ? $sheet_data_3[$x]['I']:'';
							
								$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_trainings,$fields,FALSE);
							}							
						}
					}

	/*=============== END 	: TRAININGS INSERT =====================================================*/

	/*=============== START : OTHER INFORMATION INSERT =====================================================*/

					for($x = 43; $x <=49; $x++)
					{
						$validate_other_skills = $this->_validate_na_value($sheet_data_3[$x]['A']);
						if($validate_other_skills)
						{
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['other_info_type_id'] 	= OTHER_SKILLS;
							$fields['others_value']			= ISSET($sheet_data_3[$x]['A']) ? $sheet_data_3[$x]['A']:NULL;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_other_info,$fields,FALSE);
						}

						$validate_other_recognition = $this->_validate_na_value($sheet_data_3[$x]['C']);
						if($validate_other_recognition)
						{
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['other_info_type_id'] 	= OTHER_RECOGNITION;
							$fields['others_value']			= ISSET($sheet_data_3[$x]['C']) ? $sheet_data_3[$x]['C']:NULL;
						
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_other_info,$fields,FALSE);
						}

						$validate_other_association = $this->_validate_na_value($sheet_data_3[$x]['I']);
						if($validate_other_association)
						{
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['other_info_type_id'] 	= OTHER_ASSOCIATION;
							$fields['others_value']			= ISSET($sheet_data_3[$x]['I']) ? $sheet_data_3[$x]['I']:NULL;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_other_info,$fields,FALSE);
						}
					}

	/*=============== END 	: OTHER INFORMATION INSERT =====================================================*/

	/*=============== START : QUESTION INSERT =====================================================*/

					if(ISSET($sheet_data_4[6]['H']) OR ISSET($sheet_data_4[6]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 2;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[6]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[6]['H']) AND ISSET($sheet_data_4[11]['H'])) ? $sheet_data_4[11]['H']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[8]['H']) OR ISSET($sheet_data_4[8]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 3;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[8]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[8]['H']) AND ISSET($sheet_data_4[11]['H'])) ? $sheet_data_4[11]['H']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[14]['H']) OR ISSET($sheet_data_4[14]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 5;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[14]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[14]['H']) AND ISSET($sheet_data_4[16]['H'])) ? $sheet_data_4[16]['H']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[19]['H']) OR ISSET($sheet_data_4[19]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 6;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[19]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[19]['H']) AND (ISSET($sheet_data_4[21]['K']) OR ISSET($sheet_data_4[22]['K']))) ? $sheet_data_4[21]['K'] . ', ' . $sheet_data_4[22]['K']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[25]['H']) OR ISSET($sheet_data_4[25]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 7;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[25]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[25]['H']) AND ISSET($sheet_data_4[27]['H'])) ? $sheet_data_4[27]['H']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[30]['H']) OR ISSET($sheet_data_4[30]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 8;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[30]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[30]['H']) AND ISSET($sheet_data_4[32]['H'])) ? $sheet_data_4[32]['H']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[35]['H']) OR ISSET($sheet_data_4[35]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 10;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[35]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[35]['H']) AND ISSET($sheet_data_4[36]['K'])) ? $sheet_data_4[36]['K']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[38]['H']) OR ISSET($sheet_data_4[38]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 11;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[38]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[38]['H']) AND ISSET($sheet_data_4[39]['K'])) ? $sheet_data_4[39]['K']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[42]['H']) OR ISSET($sheet_data_4[42]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 12;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[42]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[42]['H']) AND ISSET($sheet_data_4[44]['H'])) ? $sheet_data_4[44]['H']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[48]['H']) OR ISSET($sheet_data_4[48]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 14;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[48]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[48]['H']) AND ISSET($sheet_data_4[49]['L'])) ? $sheet_data_4[49]['L']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[50]['H']) OR ISSET($sheet_data_4[50]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 15;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[50]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[50]['H']) AND ISSET($sheet_data_4[51]['L'])) ? $sheet_data_4[51]['L']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}

					if(ISSET($sheet_data_4[52]['H']) OR ISSET($sheet_data_4[52]['J']))
					{
						$fields 						= array();
						$fields['employee_id'] 			= $employee_id;
						$fields['question_id'] 			= 16;
						$fields['question_answer_flag']	= ISSET($sheet_data_4[52]['H']) ? YES:NO;
						$fields['question_answer_txt']	= (ISSET($sheet_data_4[52]['H']) AND ISSET($sheet_data_4[53]['L'])) ? $sheet_data_4[53]['L']:NULL;
					
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_questions,$fields,FALSE);
					}
					
	/*=============== END 	: QUESTION INSERT =====================================================*/

	/*=============== START : REFERENCES INSERT =====================================================*/

					for($x = 57; $x <=59; $x++)
					{
						$validate_reference = $this->_validate_na_value($sheet_data_4[$x]['A']);
						if($validate_reference)
						{
							$fields                           = array();
							$fields['employee_id']            = $employee_id;
							$fields['reference_full_name']    = ISSET($sheet_data_4[$x]['A']) ? $sheet_data_4[$x]['A']:NULL;
							$fields['reference_address']      = ISSET($sheet_data_4[$x]['F']) ? $sheet_data_4[$x]['F']:' ';
							$fields['reference_contact_info'] = ISSET($sheet_data_4[$x]['G']) ? str_replace('-', '', $sheet_data_4[$x]['G']):'';
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_references,$fields,FALSE);						
						}
					}

	/*=============== END 	: REFERENCES INSERT =====================================================*/

	/*=============== START : DECLARATION INSERT =====================================================*/

					$fields                      = array();
					$fields['employee_id']       = $employee_id;
					$fields['govt_issued_id']    = ISSET($sheet_data_4[66]['D']) ? $sheet_data_4[66]['D']:NULL;
					$fields['ctc_no']    		 = ISSET($sheet_data_4[67]['D']) ? $sheet_data_4[67]['D']:NULL;
					$fields['issued_place']      = ISSET($sheet_data_4[69]['D']) ? $sheet_data_4[69]['D']:NULL;
					$fields['issued_date']       = NULL;
				
					$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_declaration,$fields,FALSE);

	/*=============== END 	: DECLARATION INSERT =====================================================*/
					if($error_checker)
					{
						Main_Model::rollback();
						$show_log = TRUE;
						$log_content .= "Upload Status :   FAIL\r\n\n\n";
						$log_content .= "*--------------------------------";
						$log_content .= "---------------------------------*\r\n\n\n";
					}
					else
					{
						Main_Model::commit();
						$success_count++;
						$log_content .= "Upload Status :   SUCCESS\r\n\n\n";
						$log_content .= "*--------------------------------";
						$log_content .= "---------------------------------*\r\n\n\n";
					}
				}					

				}
			}
			else
			{
				throw new Exception('Upload File is required.');
			}
		
			if($show_log)
			{
				
				$this->load->helper('file');
				$path = PATH_PDS_UPLOAD_ERROR_LOGS;		
				$name = 'PDS UPLOAD ERROR LOG -' . date('Y-m-d h-i-s') . '.txt' ;
				$error_log = $name;
				if(!is_dir($path))
				{
				  mkdir($path,0777,TRUE);
				}
				$path .= $name;
				write_file($path , $log_content);

			}
			
			if($success_count != $file_count AND $success_count > 0)
			{
				$status  = true;
				$message = $success_count . " out of ".$file_count." files was successfully uploaded.<br> Please see log file to view the errors.";
			}
			elseif($success_count == 0)
			{
				$status  = false;
				$message = "Failed to upload all files.<br> Please see log file to view the errors.";
				if($show_log == false)
				{
					$message = "Failed to upload all files.";
				}
			}
			elseif($success_count == $file_count)
			{
				$status  = true;
				$message = $success_count . " out of ".$file_count." files was successfully uploaded.";
			}
			
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			Main_Model::rollback();
			//$message = $this->lang->line('err_internal_server');
			
			if ( ! empty($current_file_name))
				$message = 'Filename: ' . $current_file_name . '<br>' . 'Data error.  Please see log for more details.';
			
			RLog::error('START: --==========PDS UPLOAD ERR==========--');
			RLog::error('PDO Ex: [' . 'Filename: ' . $current_file_name . '] ' . $e->getMessage());
			RLog::error('END: --==========PDS UPLOAD ERR==========--');
			
			if($proceed_flag == 'Y')
			{
				$status    = FALSE;
				$show_log  = TRUE;
				$error_log = $this->_show_log($current_file_name, $e->getMessage());	
			}
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			if ( ! empty($current_file_name))
				$message = 'Filename: ' . $current_file_name . '<br>' . $message;
			
			$error_log = $name;
			
			RLog::error('START: --==========PDS UPLOAD ERR==========--');
			RLog::error('Gen Ex: [' . 'Filename: ' . $current_file_name . '] ' . $e->getMessage());
			RLog::error('END: --==========PDS UPLOAD ERR==========--');
			if($proceed_flag == 'Y')
			{
				$status = FALSE;
				$show_log = TRUE;
				$error_log = $this->_show_log($current_file_name, $e->getMessage());	
			}
				
		}
		
		$data                    = array();
		$data['status']          = $status;
		$data['message']         = $message;
		$data['file_name']       = $error_log;
		$data['show_log']        = $show_log;
		
		$data['proceed_flag']    = $proceed_flag;
		$data['last_count']      = $last_count;
		$data['commit_flag_cnt'] = $commit_flag_cnt;
		$data['success_count']   = $success_count;
	
		echo json_encode($data);
	}
	
	private function _show_log($current_file_name, $message)
	{
		try
		{
			$this->load->helper('file');
			$path = PATH_PDS_UPLOAD_ERROR_LOGS;		
			$name = 'PDS UPLOAD ERROR LOG -' . date('Y-m-d h-i-s') . '.txt' ;
			$error_log = $name;
			if(!is_dir($path))
			{
			  mkdir($path,0777,TRUE);
			}
			$path .= $name;
			$message = '[' . $current_file_name . '] ' . $message;
			write_file($path , $message);
			
			return $name;
		}
		catch(Exception $e)
		{
		
		} 		
	}

	private function _validate_na_value($input_value)
	{
		$valid	   = true;
		if(ISSET($input_value) AND (strtolower($input_value) != 'na') AND (strtolower($input_value) != 'n/a') AND (strtolower($input_value) != 'not applicable'))
		{
			$valid = true;
		}
		else
		{
			$valid = false;
		}
		
		return $valid;
	}
}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */