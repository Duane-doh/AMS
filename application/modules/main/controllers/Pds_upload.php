<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pds_upload extends Main_Controller {

	private $log_user_id		=  '';
	private $log_user_roles		= array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_upload_model', 'pds_upload');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	
	public function modal_upload_pds()
	{
		try
		{
			$data                  = array();
			$resources             = array();
			
			$resources['load_css'] = array(CSS_UPLOAD);
			$resources['load_js']  = array(JS_UPLOAD);

			$this->load->view('pds/modals/modal_upload_pds', $data);
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
	public function process_pds_upload(){
	
		$current_file_name = NULL;
		
		try{

			$status        = false;
			$message       = "";
			$params        = get_params();
			
			$this->load->helper("php_excel");
			
			$show_log        = FALSE;
			$log_content     = "\t\t\t PERSONAL DATA SHEET UPLOAD LOGS \r\n\n\n";
			$log_content     .= "*--------------------------------";
			$log_content     .= "---------------------------------*\r\n\n\n";
			$file_count      = 0;
			$success_count   = $params['success_count'];
			$last_count      = $params['last_count'];
			$commit_flag_cnt = $params['commit_flag_cnt'];
			$proceed_flag 	= "Y";

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
						
						$last_name   = ISSET($sheet_data_1[7]['C']) ? strtoupper($sheet_data_1[7]['C']):NULL;
						$first_name  = ISSET($sheet_data_1[8]['C']) ? strtoupper($sheet_data_1[8]['C']):NULL;
						$birth_date  = ISSET($sheet_data_1[10]['F']) ? date('Y-m-d',strtotime($sheet_data_1[10]['F'])):NULL;
						
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
					$log_content   .= "File Name :   ".$attachment."\r\n\n\n";
					
					// TODO:
					$current_file_name	= $attachment;

					if(strtolower($sheet_data_1[3]['A']) != strtolower("PERSONAL DATA SHEET"))
					{
						$show_log      = TRUE;
						$log_content   .= "Error : Uploaded File is invalid. Please use the given PDS template. \r\n\n";
						$log_content   .= "Upload Status :   FAIL\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
						$error_checker = TRUE;
					}
					else
					{

					$sheet_data_2 = open_excel($file,1);
					$sheet_data_3 = open_excel($file,2);
					$sheet_data_4 = open_excel($file,3);

	/*=============== START : PEROSNAL INFORMATION INSERT===================================================*/

						$fields                = array();
						$fields['last_name']   = ISSET($sheet_data_1[7]['C']) ? $sheet_data_1[7]['C']:NULL;
						$fields['first_name']  = ISSET($sheet_data_1[8]['C']) ? $sheet_data_1[8]['C']:NULL;
						$fields['middle_name'] = ISSET($sheet_data_1[9]['C']) ? $sheet_data_1[9]['C']:NULL;
						$fields['ext_name']    = (ISSET($sheet_data_1[9]['O']) AND $sheet_data_1[9]['O'] != NOT_APPLICABLE) ? $sheet_data_1[9]['O']:'';
						$fields['birth_date']  = ISSET($sheet_data_1[10]['F']) ? date('Y/m/d',strtotime($sheet_data_1[10]['F'])):NULL;
						$fields['birth_place'] = ISSET($sheet_data_1[11]['C']) ? $sheet_data_1[11]['C']:NULL;

						if(ISSET($sheet_data_1[12]['C']))
						{
							$fields['gender_code'] = 'M';
						}
						elseif(ISSET($sheet_data_1[12]['E']))
						{
							$fields['gender_code'] = 'F';
						}
						else
						{
							$fields['gender_code'] = '';
							//$log_content              .= "Error : Sex field is required.  \r\n\n";
							//$error_checker            = TRUE;

						}
						/*CITIZENSHIPS*/
						if(ISSET($sheet_data_1[16]['C']) AND (($sheet_data_1[16]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[16]['C']) != 'n/a') AND (strtolower($sheet_data_1[16]['C']) != 'not applicable'))
						{
							$where                            = array();
							$where['LOWER(citizenship_name)'] = strtolower($sheet_data_1[16]['C']);
							$citizenship                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_citizenships, $where, FALSE);
							
							if($citizenship)
							{
								$fields['citizenship_id'] = $citizenship['citizenship_id'];
							}
							else
							{
								$fields['citizenship_id'] = NULL;
								//$log_content              .= "Error : Citizenship ".$sheet_data_1[16]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								//$error_checker            = TRUE;
							}
						}
						else
						{
							$fields['citizenship_id'] = NULL;
						}
						/*CIVIL STATUS*/
						if(ISSET($sheet_data_1[13]['C']))
						{
							$fields['civil_status_id'] = 1; 
						}
						elseif(ISSET($sheet_data_1[14]['C']))
						{
							$fields['civil_status_id'] = 2; 
						}
						elseif(ISSET($sheet_data_1[15]['C']))
						{
							$fields['civil_status_id'] = 3; 
						}
						elseif(ISSET($sheet_data_1[13]['E']))
						{
							$fields['civil_status_id'] = 4; 
						}
						elseif(ISSET($sheet_data_1[14]['E']))
						{
							$fields['civil_status_id'] = 5; 
						}
						else
						{
							$fields['civil_status_id'] = NULL; 
							//$log_content               .= "Error : Civil Status must be filled up.\r\n\n";
							//$error_checker             = TRUE;
						}
						$weight_val 	  = filter_var($sheet_data_1[17]['C'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
						$height_val 	  = filter_var($sheet_data_1[18]['C'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
						$fields['height'] = ISSET($weight_val) ? $weight_val:NULL;
						$fields['weight'] = ISSET($height_val) ? $height_val:NULL;
						/*BLOOD TYPE*/
						if(ISSET($sheet_data_1[19]['C']) AND (($sheet_data_1[19]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[19]['C']) != 'n/a') AND (strtolower($sheet_data_1[19]['C']) != 'not applicable'))
						{
							$where                           = array();
							$where['LOWER(blood_type_name)'] = strtolower($sheet_data_1[19]['C']);
							$blood_type                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_blood_type, $where, FALSE);
							if($blood_type)
							{
								$fields['blood_type_id'] = $blood_type['blood_type_id'];
							}
							else
							{
								$fields['blood_type_id'] = NULL;
								//$log_content             .= "Error : Blood type ".$sheet_data_1[19]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								//$error_checker            = TRUE;
							}
						}
						else
						{
							$fields['blood_type_id'] = NULL;
						}
						if($sheet_data_1[22]['J'] AND (($sheet_data_1[22]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[22]['J']) != 'n/a') AND (strtolower($sheet_data_1[22]['J']) != 'not applicable'))
						{
							$where								= array();
							$where['LOWER(agency_employee_id)']	= strtolower($sheet_data_1[22]['J']);
							$agency_employee_id 				= $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_employee_personal_info, $where, FALSE);
							if($agency_employee_id)
							{
								throw new Exception('Agency employee ID already exist.');
								
								$log_content           		  .= "Error : Agency employee ID ".strtolower($sheet_data_1[22]['J'])." already exist. \r\n\n";
								$fields['agency_employee_id '] = NULL;
								$fields['biometric_pin']       = NULL;
								$error_checker         		   = TRUE;
							}
							else
							{
								$fields['agency_employee_id '] = $sheet_data_1[22]['J'];
								$fields['biometric_pin']       = $sheet_data_1[22]['J'];								
							}
						}
						$fields['pds_status_id']       = 1;
						$fields['date_accomplished']   = ISSET($sheet_data_4[54]['D']) ? date('Y-m-d',strtotime($sheet_data_4[54]['D'])):NULL;
						
						$employee_id                   = $this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_personal_info,$fields,TRUE);

	/*============= END: PEROSNAL INFORMATION INSERT===================================================*/

	/*=============== START : IDENTIFICATION INSERT ===================================================*/
					if(isset($sheet_data_1[20]['C']) AND (($sheet_data_1[20]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[20]['C']) != 'n/a') AND (strtolower($sheet_data_1[20]['C']) != 'not applicable'))
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[20]['C']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= GSIS_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}
					if(isset($sheet_data_1[21]['C']) AND (($sheet_data_1[21]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[21]['C']) != 'n/a') AND (strtolower($sheet_data_1[21]['C']) != 'not applicable'))
					{

						$identification_value 				= str_replace('-', '', $sheet_data_1[21]['C']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= PAGIBIG_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}
					if(isset($sheet_data_1[22]['C']) AND (($sheet_data_1[22]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[22]['C']) != 'n/a') AND (strtolower($sheet_data_1[22]['C']) != 'not applicable'))
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[22]['C']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= PHILHEALTH_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}
					if(isset($sheet_data_1[23]['C']) AND (($sheet_data_1[23]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[23]['C']) != 'n/a') AND (strtolower($sheet_data_1[23]['C']) != 'not applicable'))
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[23]['C']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= SSS_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}	
					if(isset($sheet_data_1[23]['J']) AND (($sheet_data_1[23]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[23]['J']) != 'n/a') AND (strtolower($sheet_data_1[23]['J']) != 'not applicable'))
					{
						$identification_value 				= str_replace('-', '', $sheet_data_1[23]['J']);
						$fields 							= array();
						$fields['employee_id'] 				= $employee_id;
						$fields['identification_type_id'] 	= TIN_TYPE_ID;
						$fields['identification_value'] 	= $identification_value;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_identifications,$fields,FALSE);
					}	
	/*=============== END : IDENTIFICATION INSERT =====================================================*/


	/*=============== START : CONTACT INFORMATION INSERT ===================================================*/
					if(isset($sheet_data_1[10]['J']) AND (($sheet_data_1[10]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[10]['J']) != 'n/a') AND (strtolower($sheet_data_1[10]['J']) != 'not applicable'))
					{
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['address_type_id'] = RESIDENTIAL_ADDRESS;
						$fields['address_value']   = ISSET($sheet_data_1[10]['J']) ? $sheet_data_1[10]['J']:'';						
						$fields['address_value']   .= ' ';
						$fields['address_value']   .= ISSET($sheet_data_1[12]['J']) ? $sheet_data_1[12]['J']:'';
						$fields['postal_number']   = ISSET($sheet_data_1[13]['J']) ? $sheet_data_1[13]['J']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_addresses,$fields,FALSE);
					}
					if(isset($sheet_data_1[15]['J']) AND (($sheet_data_1[15]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[15]['J']) != 'n/a') AND (strtolower($sheet_data_1[15]['J']) != 'not applicable'))
					{
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['address_type_id'] = PERMANENT_ADDRESS;
						$fields['address_value']   = ISSET($sheet_data_1[15]['J']) ? $sheet_data_1[15]['J']:NULL;
						$fields['address_value']   .= ' ';
						$fields['address_value']   .= ISSET($sheet_data_1[17]['J']) ? $sheet_data_1[17]['J']:'';
						$fields['postal_number']   = ISSET($sheet_data_1[18]['J']) ? $sheet_data_1[18]['J']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_addresses,$fields,FALSE);
					}

					if(isset($sheet_data_1[14]['J']) AND (($sheet_data_1[14]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[14]['J']) != 'n/a') AND (strtolower($sheet_data_1[14]['J']) != 'not applicable'))
					{
						$identification_value 	   = str_replace('-', '', $sheet_data_1[14]['J']);
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = RESIDENTIAL_NUMBER;
						$fields['contact_value']   = ISSET($identification_value) ? $identification_value:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}
					if(isset($sheet_data_1[20]['J']) AND (($sheet_data_1[20]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[20]['J']) != 'n/a') AND (strtolower($sheet_data_1[20]['J']) != 'not applicable'))
					{
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = EMAIL;
						$fields['contact_value']   = ISSET($sheet_data_1[20]['J']) ? $sheet_data_1[20]['J']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}
					if(isset($sheet_data_1[19]['J']) AND (($sheet_data_1[19]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[19]['J']) != 'n/a') AND (strtolower($sheet_data_1[19]['J']) != 'not applicable'))
					{
						$identification_value 	   = str_replace('-', '', $sheet_data_1[19]['J']);
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = PERMANENT_NUMBER;
						$fields['contact_value']   = ISSET($identification_value) ? $identification_value:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}
					if(isset($sheet_data_1[21]['J']) AND (($sheet_data_1[21]['J']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[21]['J']) != 'n/a') AND (strtolower($sheet_data_1[21]['J']) != 'not applicable'))
					{
						$identification_value 	   = str_replace('-', '', $sheet_data_1[21]['J']);
						$fields                    = array();
						$fields['employee_id']     = $employee_id;
						$fields['contact_type_id'] = MOBILE_NUMBER;
						$fields['contact_value']   = ISSET($identification_value) ? $identification_value:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_contacts,$fields,FALSE);
					}
	/*=============== END : CONTACT INFORMATION INSERT =====================================================*/


	/*=============== START : FAMILY INFORMATION INSERT ===================================================*/
					
					if(ISSET($sheet_data_1[25]['C']) AND (($sheet_data_1[25]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[25]['C']) != 'n/a') AND (strtolower($sheet_data_1[25]['C']) != 'not applicable'))
					{
						$fields                             = array();
						$fields['employee_id']              = $employee_id;
						$fields['relation_type_id']         = FAMILY_SPOUSE;
						$fields['relation_first_name']      = ISSET($sheet_data_1[26]['C']) ? $sheet_data_1[26]['C']:NULL;
						$fields['relation_middle_name']     = ISSET($sheet_data_1[27]['C']) ? $sheet_data_1[27]['C']:NULL;
						$fields['relation_last_name']       = ISSET($sheet_data_1[25]['C']) ? $sheet_data_1[25]['C']:NULL;
						$fields['relation_occupation']      = ISSET($sheet_data_1[28]['C']) ? $sheet_data_1[28]['C']:NULL;
						$fields['relation_company']         = ISSET($sheet_data_1[29]['C']) ? $sheet_data_1[29]['C']:NULL;
						$fields['relation_company_address'] = ISSET($sheet_data_1[30]['C']) ? $sheet_data_1[30]['C']:NULL;
						$fields['relation_contact_num']     = ISSET($sheet_data_1[31]['C']) ? $sheet_data_1[31]['C']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);				
					}

					if(ISSET($sheet_data_1[33]['D']) AND (($sheet_data_1[33]['D']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[33]['D']) != 'n/a') AND (strtolower($sheet_data_1[33]['D']) != 'not applicable'))
					{
						$fields                         = array();
						$fields['employee_id']          = $employee_id;
						$fields['relation_type_id']     = FAMILY_FATHER;
						$fields['relation_first_name']  = ISSET($sheet_data_1[34]['D']) ? $sheet_data_1[34]['D']:NULL;
						$fields['relation_middle_name'] = ISSET($sheet_data_1[35]['D']) ? $sheet_data_1[35]['D']:NULL;
						$fields['relation_last_name']   = ISSET($sheet_data_1[33]['D']) ? $sheet_data_1[33]['D']:NULL;
						
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);				
					}
					if(ISSET($sheet_data_1[37]['D']) AND (($sheet_data_1[37]['D']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[37]['D']) != 'n/a') AND (strtolower($sheet_data_1[37]['D']) != 'not applicable'))
					{
						$fields                         = array();
						$fields['employee_id']          = $employee_id;
						$fields['relation_type_id']     = FAMILY_MOTHER;
						$fields['relation_first_name']  = ISSET($sheet_data_1[38]['D']) ? $sheet_data_1[38]['D']:NULL;
						$fields['relation_middle_name'] = ISSET($sheet_data_1[39]['D']) ? $sheet_data_1[39]['D']:NULL;
						$fields['relation_last_name']   = ISSET($sheet_data_1[37]['D']) ? $sheet_data_1[37]['D']:NULL;

						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_relations,$fields,FALSE);				
					}

/*=============== START : EDUCATIONAL BACKGROUND INSERT ===================================================*/

					$validate_elementary = $this->_validate_na_value($sheet_data_1[44]['C']);
					if($validate_elementary)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[44]['C']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[44]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[56]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[44]['G']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[44]['G']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_ELEMENTARY;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[44]['I']) ? YES: NO;
						if ( (strpos($sheet_data_1[44]['M'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[44]['M']));
						else
							$fields['start_year']         = $sheet_data_1[44]['M'];
						if ( (strpos($sheet_data_1[44]['N'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[44]['N']));
						else
							$fields['end_year']           = $sheet_data_1[44]['N'];			
						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[44]['J']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[44]['O']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[44]['J']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[44]['O']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_secondary = $this->_validate_na_value($sheet_data_1[45]['C']);
					if($validate_secondary)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[45]['C']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[45]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[56]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[45]['G']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[45]['G']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_SECONDARY;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[45]['I']) ? YES: NO;
						if ( (strpos($sheet_data_1[45]['M'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[45]['M']));
						else
							$fields['start_year']         = $sheet_data_1[45]['M'];
						if ( (strpos($sheet_data_1[45]['N'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[45]['N']));
						else
							$fields['end_year']           = $sheet_data_1[45]['N'];			
						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[45]['J']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[45]['O']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[45]['J']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[45]['O']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_vocational = $this->_validate_na_value($sheet_data_1[46]['C']);
					if($validate_vocational)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[46]['C']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[46]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[56]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[46]['G']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[46]['G']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_VOCATIONAL;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[46]['I']) ? YES: NO;
						if ( (strpos($sheet_data_1[46]['M'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[46]['M']));
						else
							$fields['start_year']         = $sheet_data_1[46]['M'];
						if ( (strpos($sheet_data_1[46]['N'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[46]['N']));
						else
							$fields['end_year']           = $sheet_data_1[46]['N'];			
						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[46]['J']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[46]['O']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[46]['J']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[46]['O']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_college = $this->_validate_na_value($sheet_data_1[47]['C']);
					if($validate_college)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[47]['C']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[47]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[56]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[47]['G']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[47]['G']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_COLLEGE;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[47]['I']) ? YES: NO;
						if ( (strpos($sheet_data_1[47]['M'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[47]['M']));
						else
							$fields['start_year']         = $sheet_data_1[47]['M'];
						if ( (strpos($sheet_data_1[47]['N'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[47]['N']));
						else
							$fields['end_year']           = $sheet_data_1[47]['N'];			
						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[47]['J']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[47]['O']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[47]['J']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[47]['O']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}

					$validate_graduate = $this->_validate_na_value($sheet_data_1[48]['C']);
					if($validate_graduate)
					{
						$school = array();
						$honor  = array();
						$degree = array();
						$where                       = array();
						$where['LOWER(school_name)'] = strtolower($sheet_data_1[48]['C']);
						$school                      = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_schools, $where, FALSE);
						if(EMPTY($school))
						{
							$log_content   .= "Error : School name ".$sheet_data_1[48]['C']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
							$error_checker  = TRUE;
						}	
						if($sheet_data_1[56]['H'])
						{
							$where                       = array();
							$where['LOWER(degree_name)'] = strtolower($sheet_data_1[48]['G']);
							$degree  					 = $this->pds_upload->get_general_data(array('*'), $this->pds_upload->tbl_param_education_degrees, $where, FALSE);
							if(EMPTY($degree))
							{
								$log_content   .= "Error : Degree course ".$sheet_data_1[48]['G']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker  = TRUE;
							}
						}

						$fields                           = array();
						$fields['employee_id']            = $employee_id;
						$fields['educational_level_id']   = LEVEL_GRADUATE;
						$fields['school_id']              = $school['school_id'];
						$fields['education_degree_id']    = $degree['degree_id'];
						$fields['year_graduated_flag']    = !EMPTY($sheet_data_1[48]['I']) ? YES: NO;
						if ( (strpos($sheet_data_1[48]['M'], '/') !== false) )
							$fields['start_year']         = date('Y', strtotime($sheet_data_1[48]['M']));
						else
							$fields['start_year']         = $sheet_data_1[48]['M'];
						if ( (strpos($sheet_data_1[48]['N'], '/') !== false) )
							$fields['end_year']           = date('Y', strtotime($sheet_data_1[48]['N']));
						else
							$fields['end_year']           = $sheet_data_1[48]['N'];			
						$validate_highest_level 		  = $this->_validate_na_value($sheet_data_1[48]['J']);
						$validate_academic_honor 		  = $this->_validate_na_value($sheet_data_1[48]['O']);
						$fields['highest_level']    	  = ISSET($validate_highest_level) ? $sheet_data_1[48]['J']:NULL;
						$fields['academic_honor']    	  = ISSET($validate_academic_honor) ? $sheet_data_1[48]['O']:NULL;
											
						$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_educations,$fields,FALSE);				
					}
					
	/*=============== END : EDUCATIONAL BACKGROUND INSERT =====================================================*/
					

	/*=============== START : CIVIL SERVICE ELIGIBILITY INSERT =====================================================*/

					for($x = 5; $x <=11; $x++)
					{
						if(ISSET($sheet_data_2[$x]['A']) AND (($sheet_data_2[$x]['A']) != NOT_APPLICABLE) AND (strtolower($sheet_data_1[$x]['A']) != 'n/a') AND (strtolower($sheet_data_1[$x]['A']) != 'not applicable'))
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
								$fields['exam_date'] 			= ISSET($sheet_data_2[$x]['G']) ? date('Y/m/d',strtotime($sheet_data_2[$x]['G'])):NULL;
								$fields['exam_place'] 			= ISSET($sheet_data_2[$x]['I']) ? $sheet_data_2[$x]['I']:NULL;
								$fields['license_no'] 			= ISSET($sheet_data_2[$x]['L']) ? $sheet_data_2[$x]['L']:NULL;
								$fields['release_date'] 		= ISSET($sheet_data_2[$x]['M']) ? date('Y/m/d',strtotime($sheet_data_2[$x]['M'])):NULL;
								
								$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_eligibility,$fields,FALSE);

							}	
							/*else
							{
								$log_content   .= "Error : Eligibility type".$sheet_data_2[$x]['A']." does not exist in systems current record. This must be spelled exactly with the systems current record. \r\n\n";
								$error_checker = TRUE;
							}	*/
						}
					}

	/*=============== END 	: CIVIL SERVICE ELIGIBILITY INSERT =====================================================*/


	/*=============== START : WORK EXPERIENCE INSERT =====================================================*/

					for($x = 17; $x <=36; $x++)
					{
						if(ISSET($sheet_data_2[$x]['A']) AND (($sheet_data_2[$x]['A']) != NOT_APPLICABLE) AND (strtolower($sheet_data_2[$x]['A']) != 'n/a') AND (strtolower($sheet_data_2[$x]['A']) != 'not applicable'))
						{
							if (strpos($sheet_data_2[$x]['A'], '/       /') === false)
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
									$fields['employ_start_date']            = !EMPTY($sheet_data_2[$x]['A']) ? date('Y/m/d',strtotime($sheet_data_2[$x]['A'])):NULL;
								}
								// CHECK IF DATE ENDED INPUT IS DATE ONLY
								if($sheet_data_2[$x]['C'] == 'PRESENT' OR $sheet_data_2[$x]['C'] == 'present' OR $sheet_data_2[$x]['C'] == 'Present')
								{
									$fields['employ_end_date']          = NULL;
								}
								elseif($end_year_len == 4)
								{
									$fields['employ_end_date'] = $sheet_data_2[$x]['C'] . '-01-01';
								}
								else
								{
									$fields['employ_end_date']          = !EMPTY($sheet_data_2[$x]['C']) ? date('Y/m/d',strtotime($sheet_data_2[$x]['C'])):NULL;
								}
								
								$fields['employ_position_name']         = ISSET($sheet_data_2[$x]['D']) ? $sheet_data_2[$x]['D']:NULL;
								$fields['employ_office_name']           = ISSET($sheet_data_2[$x]['G']) ? $sheet_data_2[$x]['G']:NULL;								
								$salary_amt 							= filter_var($sheet_data_2[$x]['J'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
								$fields['employ_monthly_salary']        = !EMPTY($salary_amt) ? $salary_amt:0;

								if($sheet_data_2[$x]['K'])
								{
									if (strpos($sheet_data_2[$x]['K'], 'N') === false)
									{
										$data_explode = explode('-',$sheet_data_2[$x]['K']);

										$salary_grade = $data_explode[0];
										$grade_val    = filter_var($salary_grade, FILTER_SANITIZE_NUMBER_FLOAT);
										if($grade_val)
										{											
											$grade    = $grade_val;
										}
										else
										{
											$grade = NULL;
										}

										$salary_step  = $data_explode[1];
										$step_val     = filter_var($salary_step, FILTER_SANITIZE_NUMBER_FLOAT);
										if($step_val)
										{											
											$step   = $salary_step;
										}
										else
										{
											$step = NULL;
										}
									}								
								}

								$fields['employ_salary_grade']  = ISSET($grade) ? $grade:NULL;
								$fields['employ_salary_step']   = ISSET($step) ? $step:NULL;
								$fields['employment_status_id'] = ISSET($appointment['employment_status_id']) ? $appointment ['employment_status_id']:NULL;
								$fields['govt_service_flag']    = (strtolower($sheet_data_2[$x]['M']) == "yes") ? YES:NO;
								
								if(strtolower($sheet_data_2[$x]['M']) == "yes")
								{
									$fields['employ_type_flag'] = NON_DOH_GOV;
								}
								else
								{
									$fields['employ_type_flag'] = PRIVATE_WORK;
								}

								$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_work_experiences,$fields,FALSE);				
								
							}
						}
					}
	/*=============== END 	: WORK EXPERIENCE INSERT =====================================================*/


	/*=============== START : VOLUNTARY WORK INSERT =====================================================*/

					for($x = 6; $x <=10; $x++)
					{
						if(ISSET($sheet_data_3[$x]['A']) AND (($sheet_data_3[$x]['A']) != NOT_APPLICABLE) AND (strtolower($sheet_data_3[$x]['A']) != 'n/a') AND (strtolower($sheet_data_3[$x]['A']) != 'not applicable'))
						{							
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['volunteer_org_name'] 	= ISSET($sheet_data_3[$x]['A']) ? $sheet_data_3[$x]['A']:NULL;
							$fields['volunteer_org_address']= NULL;
							$fields['volunteer_start_date'] = ISSET($sheet_data_3[$x]['E']) ? date('Y/m/d',strtotime($sheet_data_3[$x]['E'])):NULL;
							$fields['volunteer_end_date'] 	= ISSET($sheet_data_3[$x]['F']) ? date('Y/m/d',strtotime($sheet_data_3[$x]['F'])):NULL;
							$volunteer_hour_cnt 		    = filter_var($sheet_data_3[$x]['G'], FILTER_SANITIZE_NUMBER_FLOAT);
							$fields['volunteer_hour_count'] = !EMPTY($volunteer_hour_cnt) ? $volunteer_hour_cnt:0;
							$fields['volunteer_position']	= ISSET($sheet_data_3[$x]['H']) ? $sheet_data_3[$x]['H']:NULL;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_voluntary_works,$fields,FALSE);
						}
					}
	/*=============== END 	: VOLUNTARY WORK INSERT =====================================================*/


	/*=============== START : TRAININGS INSERT =====================================================*/

					
					for($x = 16; $x <=30; $x++)
					{
						if(ISSET($sheet_data_3[$x]['A']) AND (($sheet_data_3[$x]['A']) != NOT_APPLICABLE) AND (strtolower($sheet_data_3[$x]['A']) != 'n/a') AND (strtolower($sheet_data_3[$x]['A']) != 'not applicable'))
						{
							if(!EMPTY($sheet_data_3[$x]['E']) AND !EMPTY($sheet_data_3[$x]['F']))
							{
								$fields                          = array();
								$fields['employee_id']           = $employee_id;
								$fields['training_type']         = ' ';
								$fields['training_name']         = ISSET($sheet_data_3[$x]['A']) ? $sheet_data_3[$x]['A']:NULL;
								$fields['training_start_date']   = ISSET($sheet_data_3[$x]['E']) ? date('Y/m/d',strtotime($sheet_data_3[$x]['E'])):NULL;
								$fields['training_end_date']     = ISSET($sheet_data_3[$x]['F']) ? date('Y/m/d',strtotime($sheet_data_3[$x]['F'])):'';
								$training_hour_cnt 				 = filter_var($sheet_data_3[$x]['G'], FILTER_SANITIZE_NUMBER_FLOAT);
								$fields['training_hour_count']   = !EMPTY($training_hour_cnt) ? $training_hour_cnt:0;
								$fields['training_conducted_by'] = ISSET($sheet_data_3[$x]['H']) ? $sheet_data_3[$x]['H']:'';
							
								$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_trainings,$fields,FALSE);
							}							
						}
					}
	/*=============== END 	: TRAININGS INSERT =====================================================*/


	/*=============== START : OTHER INFORMATION INSERT =====================================================*/

					for($x = 34; $x <=38; $x++)
					{
						if(ISSET($sheet_data_3[$x]['A']) AND (($sheet_data_3[$x]['A']) != NOT_APPLICABLE) AND (strtolower($sheet_data_3[$x]['A']) != 'n/a') AND (strtolower($sheet_data_3[$x]['A']) != 'not applicable'))
						{
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['other_info_type_id'] 	= OTHER_SKILLS;
							$fields['others_value']			= ISSET($sheet_data_3[$x]['A']) ? $sheet_data_3[$x]['A']:NULL;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_other_info,$fields,FALSE);

						}
						if(ISSET($sheet_data_3[$x]['C']) AND (($sheet_data_3[$x]['C']) != NOT_APPLICABLE) AND (strtolower($sheet_data_3[$x]['C']) != 'n/a') AND (strtolower($sheet_data_3[$x]['C']) != 'not applicable'))
						{
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['other_info_type_id'] 	= OTHER_RECOGNITION;
							$fields['others_value']			= ISSET($sheet_data_3[$x]['C']) ? $sheet_data_3[$x]['C']:NULL;
						
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_other_info,$fields,FALSE);
						}
						if(ISSET($sheet_data_3[$x]['H']) AND (($sheet_data_3[$x]['H']) != NOT_APPLICABLE) AND (strtolower($sheet_data_3[$x]['H']) != 'n/a') AND (strtolower($sheet_data_3[$x]['H']) != 'not applicable'))
						{
							$fields 						= array();
							$fields['employee_id'] 			= $employee_id;
							$fields['other_info_type_id'] 	= OTHER_ASSOCIATION;
							$fields['others_value']			= ISSET($sheet_data_3[$x]['H']) ? $sheet_data_3[$x]['H']:NULL;
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_other_info,$fields,FALSE);
						}
					}
	/*=============== END 	: OTHER INFORMATION INSERT =====================================================*/

	/*=============== START : REFERENCES INSERT =====================================================*/
					for($x = 41; $x <=43; $x++)
					{
						if(ISSET($sheet_data_4[$x]['A']) AND (($sheet_data_4[$x]['A']) != NOT_APPLICABLE) AND (strtolower($sheet_data_4[$x]['A']) != 'n/a') AND (strtolower($sheet_data_4[$x]['A']) != 'not applicable'))
						{
							$fields                           = array();
							$fields['employee_id']            = $employee_id;
							$fields['reference_full_name']    = ISSET($sheet_data_4[$x]['A']) ? $sheet_data_4[$x]['A']:NULL;
							$fields['reference_address']      = ISSET($sheet_data_4[$x]['D']) ? $sheet_data_4[$x]['D']:' ';
							$fields['reference_contact_info'] = ISSET($sheet_data_4[$x]['E']) ? str_replace('-', '', $sheet_data_4[$x]['E']):'';
							
							$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_references,$fields,FALSE);

						}
					}
	/*=============== END 	: REFERENCES INSERT =====================================================*/


	/*=============== START : DECLARATION INSERT =====================================================*/

					$fields                      = array();
					$fields['employee_id']       = $employee_id;
					$fields['ctc_no']            = ISSET($sheet_data_4[48]['B']) ? $sheet_data_4[48]['B']:NULL;
					$fields['issued_place']      = ISSET($sheet_data_4[51]['B']) ? $sheet_data_4[51]['B']:NULL;
					$fields['issued_date']       = ISSET($sheet_data_4[54]['B']) ? date('Y-m-d',strtotime($sheet_data_4[54]['B'])):NULL;
				
					$this->pds_upload->insert_general_data($this->pds_upload->tbl_employee_declaration,$fields,FALSE);

	/*=============== END 	: DECLARATION INSERT =====================================================*/
					if($error_checker)
					{
						Main_Model::rollback();
						$show_log = TRUE;
						$log_content .= "Upload Status :   FAIL\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
					}
					else
					{
						Main_Model::commit();
						$success_count++;
						$log_content .= "Upload Status :   SUCCESS\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
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
				$status = true;
				$message = $success_count . " out of ".$file_count." files was successfully uploaded.<br> Please see log file to view the errors.";
			}
			elseif($success_count == 0)
			{
				$status = false;
				$message = "Failed to upload all files.<br> Please see log file to view the errors.";
				if($show_log == false)
				{
					$message = "Failed to upload all files.";
				}
			}
			elseif($success_count == $file_count)
			{
				$status = true;
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
				$status = FALSE;
				$show_log = TRUE;
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