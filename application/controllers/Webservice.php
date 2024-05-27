<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

@ini_set('soap.wsdl_cache_enabled', 0);
@ini_set('default_socket_timeout', 600);

class Webservice extends CI_Controller
{
	
	const REALM = 'PTIS Web Service';
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	}
	
	public function pds()
	{
		$this->_check_authorization();
		$this->_create_soap_service('Pds');
	}
	
	/* 
	 * TODO: remove this function. this is made to test the web service
	 * 
	 * URL: http://localhost/doh_ptis/webservice/client/<employee_id>
	 */

  public function add_pds($data = array())
  {
    
    $client = $this->_init_client_obj();
    
    $e_id = rand(20001200, 99999999);
    $data = array (
     'error_code'    => 0,
     'error_message' => 'No errors.',
     'personal_info' => array (
        'last_name'          => 'LAZIM',
        'first_name'         => 'JABALNUR',
        'middle_name'        => 'NAMOC',
        'ext_name'           => '',
        'birth_date'         => '1993-05-25',
        'birth_place'        => 'STO. TOMAS, BATANGAS',
        'gender_code'        => 'M',
        'civil_status_id'    => '1',
        'citizenship_id'     => '43',
        'height'             => '1.72',
        'weight'             => '60.00',
        'blood_type_id'      => '1',
        'agency_employee_id' => $e_id,
        'biometric_pin'      => $e_id,
        'pds_status_id'      => '3',
      ),
      'addresses' => array (
      ),
      'contacts' => array (
        0 => array (
          'employee_contact_id' => '33786',
          'contact_type_id'     => '1',
          'contact_value'       => '3187383',
        ),
        1 => array (
          'employee_contact_id' => '36579',
          'contact_type_id'     => '2',
          'contact_value'       => 'nicksonaustria@yahoo.com',
        ),
        2 => array (
          'employee_contact_id' => '38967',
          'contact_type_id'     => '3',
          'contact_value'       => '3187383',
        ),
        3 => 
        array (
          'employee_contact_id' => '40817',
          'contact_type_id'     => '4',
          'contact_value'       => '09175052814',
        ),
      ),
      'declaration' => array (
      ),
      'educations' => array (
        0 =>  array (
          'educational_level_id'  => '17',
          'school_id'             => '35006',
          'highest_level'         => '',
          'start_year'            => '1990',
          'end_year'              => '1996',
          'academic_honor'        => 'VALEDICTORIAN',
          'year_graduated_flag'   => 'Y',
        ),
        1 => array (
          'educational_level_id'  => '18',
          'school_id'             => '34583',
          'highest_level'         => '',
          'start_year'            => '1996',
          'end_year'              => '2000',
          'academic_honor'        => '',
          'year_graduated_flag'   => 'Y',
        ),
        2 => array (
          'educational_level_id'  => '19',
          'school_id'             => '36224',
          'education_degree_id'   => '9706',
          'highest_level'         => '',
          'start_year'            => '2000',
          'end_year'              => '2004',
          'academic_honor'        => 'CUM LAUDE',
          'year_graduated_flag'   => 'Y',
        ),
        3 => array (
          'educational_level_id'  => '21',
          'school_id'             => '36361',
          'education_degree_id'   => '9708',
          'highest_level'         => '',
          'start_year'            => '2004',
          'end_year'              => '2009',
          'academic_honor'        => '',
          'year_graduated_flag'   => 'Y',
        ),
      ),
      'eligibility' => array (
        0 => array (
          'eligibility_type_id' => '2110',
          'rating'              => '83.00',
          'exam_date'           => '2009-08-01',
          'exam_place'          => 'MANILA',
          'license_no'          => '116516',
          'release_date'        => '2009-08-01',
        ),
      ),
      'identifications' => array (
      ),
      'other_info' => array (
      ),
      'professions' => array (
      ),
      'questions' => array (
      ),
      'references' => array (
        0 => array (
          'reference_full_name'    => 'JOSE FLORENCIO LAPEA',
          'reference_address'      => 'UP-PGH ORC DEPARTMENT',
          'reference_contact_info' => '',
        ),
        1 => 
        array (
          'reference_full_name'    => 'JAINE GALVEZ TAN',
          'reference_address'      => 'UP-PGH DFCM',
          'reference_contact_info' => '',
        ),
        2 => 
        array (
          'reference_full_name'    => 'HAZEL MARAPAT REYES',
          'reference_address'      => 'UP-PGH DEPARTMENT OFINT. MED',
          'reference_contact_info' => '',
        ),
      ),
      'relations' => array (
        0 => array (
          'relation_type_id'              => '1',
          'relation_first_name'           => 'ISAIAS    FLORES    AUSTRIA',
          'relation_middle_name'          => NULL,
          'relation_last_name'            => 'AUSTRIA',
          'relation_ext_name'             => NULL,
          'relation_gender_code'          => NULL,
          'relation_occupation'           => NULL,
          'relation_company'              => NULL,
          'relation_company_address'      => NULL,
          'relation_contact_num'          => NULL,
          'relation_birth_date'           => NULL,
          'relation_civil_status_id'      => NULL,
          'relation_employment_status_id' => NULL,
          'pwd_flag'                      => 'N',
          'gsis_flag'                     => 'N',
          'bir_flag'                      => 'N',
          'pagibig_flag'                  => 'N',
          'philhealth_flag'               => 'N',
          'philhealth_number'             => NULL,
          'deceased_flag'                 => 'N',
          'death_date'                    => NULL,
        ),
        1 => array (
          'relation_type_id'              => '6',
          'relation_first_name'           => '',
          'relation_middle_name'          => NULL,
          'relation_last_name'            => 'AUSTRIA',
          'relation_ext_name'             => NULL,
          'relation_gender_code'          => NULL,
          'relation_occupation'           => '',
          'relation_company'              => '',
          'relation_company_address'      => '',
          'relation_contact_num'          => '',
          'relation_birth_date'           => NULL,
          'relation_civil_status_id'      => NULL,
          'relation_employment_status_id' => NULL,
          'pwd_flag'                      => 'N',
          'gsis_flag'                     => 'N',
          'bir_flag'                      => 'N',
          'pagibig_flag'                  => 'N',
          'philhealth_flag'               => 'N',
          'philhealth_number'             => NULL,
          'deceased_flag'                 => 'N',
          'death_date'                    => NULL,
        ),
      ),
      'trainings' => array (
      ),
      'voluntary_works' => array (
      ),
      'work_experiences' => array (
        0 => array (
          'employ_type_flag'             => 'OG',
          'employ_start_date'            => '2011-06-01',
          'employ_end_date'              => '2011-10-18',
          'employ_plantilla_id'          => NULL,
          'employ_position_id'           => NULL,
          'employ_position_name'         => 'Rural Health Physician',
          'employ_office_id'             => NULL,
          'employ_office_name'           => 'Office of the Secretary- Doctors to the Barrios, DOH',
          'employ_salary_grade'          => '24',
          'employ_salary_step'           => NULL,
          'employ_monthly_salary'        => '43612.00',
          'employ_personnel_movement_id' => NULL,
          'employment_status_id'         => NULL,
          'separation_mode_id'           => NULL,
          'govt_service_flag'            => 'Y',
          'government_branch_id'         => '39',
          'service_lwop'                 => NULL,
          'publication_date'             => NULL,
          'publication_place'            => NULL,
          'prev_employee_id'             => NULL,
          'prev_separation_mode_id'      => NULL,
          'active_flag'                  => 'N',
          'step_incr_reason_code'        => NULL,
        ),
        1 => array (
          'employ_type_flag'             => 'OG',
          'employ_start_date'            => '2010-06-24',
          'employ_end_date'              => '2011-05-31',
          'employ_plantilla_id'          => NULL,
          'employ_position_id'           => NULL,
          'employ_position_name'         => 'Rural Health Physician',
          'employ_office_id'             => NULL,
          'employ_office_name'           => 'Office of the Secretary - Doctors to the Barrios, DOH',
          'employ_salary_grade'          => '24',
          'employ_salary_step'           => NULL,
          'employ_monthly_salary'        => '37473.00',
          'employ_personnel_movement_id' => NULL,
          'employment_status_id'         => NULL,
          'separation_mode_id'           => NULL,
          'govt_service_flag'            => 'Y',
          'government_branch_id'         => '39',
          'service_lwop'                 => NULL,
          'publication_date'             => NULL,
          'publication_place'            => NULL,
          'prev_employee_id'             => NULL,
          'prev_separation_mode_id'      => NULL,
          'active_flag'                  => 'N',
          'step_incr_reason_code'        => NULL,
        ),
        2 => array (
          'employ_type_flag'             => 'OG',
          'employ_start_date'            => '2009-10-19',
          'employ_end_date'              => '2010-06-23',
          'employ_plantilla_id'          => NULL,
          'employ_position_id'           => NULL,
          'employ_position_name'         => 'Rural Health Physician',
          'employ_office_id'             => NULL,
          'employ_office_name'           => 'Office of the Secretary - Doctors to the Barrios, DOH',
          'employ_salary_grade'          => '24',
          'employ_salary_step'           => NULL,
          'employ_monthly_salary'        => '31334.00',
          'employ_personnel_movement_id' => NULL,
          'employment_status_id'         => NULL,
          'separation_mode_id'           => NULL,
          'govt_service_flag'            => 'Y',
          'government_branch_id'         => '39',
          'service_lwop'                 => NULL,
          'publication_date'             => NULL,
          'publication_place'            => NULL,
          'prev_employee_id'             => NULL,
          'prev_separation_mode_id'      => NULL,
          'active_flag'                  => 'N',
          'step_incr_reason_code'        => NULL,
        ),
      ),
    );
  
    $result = $client->add_pds($data); 

    $this->_show_result($result, $client);

  }

  public function add_pds_request($data = array(), $id = 0)
  {
   
    $client = $this->_init_client_obj();
  
    $data = array (
      'error_code'      => 0,  
      'error_message'   => 'No errors.',
      'identifications' => array (
        0 => array (
          'employee_id'            => '108768',
          'identification_type_id' => '6',
          'identification_value'   => '123456213'
        )
      )
      ,
      'contacts'        =>  array (
        0 => array (
          'employee_id'     => '108768',
          'contact_type_id' => '1',
          'contact_value'   => '3187383',
        ),
        1 => array (
          'employee_id'     => '108768',
          'contact_type_id' => '2',
          'contact_value'   => 'nicksonaustria@yahoo.com',
        ),
        2 => array (
          'employee_id'     => '108768',
          'contact_type_id' => '3',
          'contact_value'   => '3187383',
        ),
        3 => array (
          'employee_id'     => '108768',
          'contact_type_id' => '4',
          'contact_value'   => '09175052814',
        ),
      )
    );

    $result = $client->add_pds_request($data,'108768');

    
    $this->_show_result($result, $client);

  }


  public function get_pds($id) {

    $client = $this->_init_client_obj();
    
    $result = $client->get_pds($id);

    $this->_show_result($result, $client);

   
  }

  private function _init_client_obj() {
    
    return new SoapClient(NULL, array(
      'location'  => $this->_get_location() . '/pds/',
      'uri'       => $this->_get_uri(),
      'trace'     => 1,
      'exception' => 0,
      'login'     => 'superuser',
      'password'  => '12345678',
    ));

  }
  private function _show_result($result, $client) {

      $this->output
      ->set_status_header(200)
      ->set_content_type('text/html')
      ->set_output("<pre>LOCATION: " . $this->_get_location() . "\nURI: " . $this->_get_uri() . "\nREQUEST: \n" . $client->__getLastRequest() . "\n\n" . var_export($result, true) . '</pre>')
    ;

  }
	private function _create_soap_service($name)
	{
		Soap_Controller::set_instance($this);
		require_once($this->_get_soap_path() . $name . '.php');
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/xml')
		;
		
		$server = new SoapServer(NULL, array(
			'uri' => $this->_get_uri(),
		));
		$server->setObject(new $name());
		$server->handle();
	}
	
	private function _get_location()
	{
		return base_url(strtolower(get_class()) . '/');
	}
	
	private function _get_uri()
	{
		return base_url();
	}
	
	private function _get_soap_path()
	{
		return APPPATH . 'modules' . DS . 'soap' . DS . 'controllers' . DS;
	}
	
	private function _check_authorization()
	{
		if (!isset($_SERVER['PHP_AUTH_USER'])) $this->_show_unauthorized();
		
		$username = $_SERVER['PHP_AUTH_USER'];
		$password = $_SERVER['PHP_AUTH_PW'];
		
		$user_info = $this->auth_model->get_active_user($username);
		
		if (empty($user_info) || $user_info['password'] != in_salt($password, $user_info["salt"], TRUE)) $this->_show_unauthorized();
		
		$user_roles = $this->auth_model->get_user_roles($user_info["user_id"], $user_info["attempts"]);
		$has_role = FALSE;
		
		foreach ($user_roles as $r)
		{
			if ($r['role_code'] == 'WEB_SERVICE') $has_role = TRUE;
		}
		
		if (!$has_role) $this->_show_unauthorized();
	}
	
	private function _show_unauthorized()
	{
		$this->output
			->set_header('WWW-Authenticate: Basic realm="' . self::REALM . '"')
			->set_status_header(401)
			->set_content_type('application/xml')
// 			->set_output('')
		;
		
		exit;
	}

    // public function client($id = 0)
  // {
    
 //    $client = new SoapClient(NULL, array(
 //      'location'  => $this->_get_location() . '/pds/',
 //      'uri'       => $this->_get_uri(),
 //      'trace'     => 1,
 //      'exception' => 0,
 //      'login'     => 'superuser',
 //      'password'  => '12345678',
 //    ));
    
  
 //    // $data = array (
 //    //   'error_code'      => 0,  
 //    //   'error_message'   => 'No errors.',
 //    //   'identifications' => array (
 //    //     0 => array (
 //    //       'employee_id'            => '108768',
 //    //       'identification_type_id' => '6',
 //    //       'identification_value'   => '123456213'
 //    //     )
 //    //   )
 //    //   ,
 //    //   'contacts'        =>  array (
 //    //     0 => array (
 //    //       'employee_id'     => '108768',
 //    //       'contact_type_id' => '1',
 //    //       'contact_value'   => '3187383',
 //    //     ),
 //    //     1 => array (
 //    //       'employee_id'     => '108768',
 //    //       'contact_type_id' => '2',
 //    //       'contact_value'   => 'nicksonaustria@yahoo.com',
 //    //     ),
 //    //     2 => array (
 //    //       'employee_id'     => '108768',
 //    //       'contact_type_id' => '3',
 //    //       'contact_value'   => '3187383',
 //    //     ),
 //    //     3 => array (
 //    //       'employee_id'     => '108768',
 //    //       'contact_type_id' => '4',
 //    //       'contact_value'   => '09175052814',
 //    //     ),
 //    //   )
 //    // );

 //    // $result = $client->add_pds_request($data,'108768');

    
  //  $e_id = rand(20001200, 99999999);
 //    $data = array (
 //     'error_code'    => 0,
 //     'error_message' => 'No errors.',
 //     'personal_info' => array (
 //        'last_name'          => 'LAZIM',
 //        'first_name'         => 'JABALNUR',
 //        'middle_name'        => 'NAMOC',
 //        'ext_name'           => '',
 //        'birth_date'         => '1993-05-25',
 //        'birth_place'        => 'STO. TOMAS, BATANGAS',
 //        'gender_code'        => 'M',
 //        'civil_status_id'    => '1',
 //        'citizenship_id'     => '43',
 //        'height'             => '1.72',
 //        'weight'             => '60.00',
 //        'blood_type_id'      => '1',
 //        'agency_employee_id' => $e_id,
 //        'biometric_pin'      => $e_id,
 //        'pds_status_id'      => '3',
 //      ),
 //      'addresses' => array (
 //      ),
 //      'contacts' => array (
 //        0 => array (
 //          'employee_contact_id' => '33786',
 //          'contact_type_id'     => '1',
 //          'contact_value'       => '3187383',
 //        ),
 //        1 => array (
 //          'employee_contact_id' => '36579',
 //          'contact_type_id'     => '2',
 //          'contact_value'       => 'nicksonaustria@yahoo.com',
 //        ),
 //        2 => array (
 //          'employee_contact_id' => '38967',
 //          'contact_type_id'     => '3',
 //          'contact_value'       => '3187383',
 //        ),
 //        3 => 
 //        array (
 //          'employee_contact_id' => '40817',
 //          'contact_type_id'     => '4',
 //          'contact_value'       => '09175052814',
 //        ),
 //      ),
 //      'declaration' => array (
 //      ),
 //      'educations' => array (
 //        0 =>  array (
 //          'educational_level_id'  => '17',
 //          'school_id'             => '35006',
 //          'highest_level'         => '',
 //          'start_year'            => '1990',
 //          'end_year'              => '1996',
 //          'academic_honor'        => 'VALEDICTORIAN',
 //          'year_graduated_flag'   => 'Y',
 //        ),
 //        1 => array (
 //          'educational_level_id'  => '18',
 //          'school_id'             => '34583',
 //          'highest_level'         => '',
 //          'start_year'            => '1996',
 //          'end_year'              => '2000',
 //          'academic_honor'        => '',
 //          'year_graduated_flag'   => 'Y',
 //        ),
 //        2 => array (
 //          'educational_level_id'  => '19',
 //          'school_id'             => '36224',
 //          'education_degree_id'   => '9706',
 //          'highest_level'         => '',
 //          'start_year'            => '2000',
 //          'end_year'              => '2004',
 //          'academic_honor'        => 'CUM LAUDE',
 //          'year_graduated_flag'   => 'Y',
 //        ),
 //        3 => array (
 //          'educational_level_id'  => '21',
 //          'school_id'             => '36361',
 //          'education_degree_id'   => '9708',
 //          'highest_level'         => '',
 //          'start_year'            => '2004',
 //          'end_year'              => '2009',
 //          'academic_honor'        => '',
 //          'year_graduated_flag'   => 'Y',
 //        ),
 //      ),
 //      'eligibility' => array (
 //        0 => array (
 //          'eligibility_type_id' => '2110',
 //          'rating'              => '83.00',
 //          'exam_date'           => '2009-08-01',
 //          'exam_place'          => 'MANILA',
 //          'license_no'          => '116516',
 //          'release_date'        => '2009-08-01',
 //        ),
 //      ),
 //      'identifications' => array (
 //      ),
 //      'other_info' => array (
 //      ),
 //      'professions' => array (
 //      ),
 //      'questions' => array (
 //      ),
 //      'references' => array (
 //        0 => array (
 //          'reference_full_name'    => 'JOSE FLORENCIO LAPEA',
 //          'reference_address'      => 'UP-PGH ORC DEPARTMENT',
 //          'reference_contact_info' => '',
 //        ),
 //        1 => 
 //        array (
 //          'reference_full_name'    => 'JAINE GALVEZ TAN',
 //          'reference_address'      => 'UP-PGH DFCM',
 //          'reference_contact_info' => '',
 //        ),
 //        2 => 
 //        array (
 //          'reference_full_name'    => 'HAZEL MARAPAT REYES',
 //          'reference_address'      => 'UP-PGH DEPARTMENT OFINT. MED',
 //          'reference_contact_info' => '',
 //        ),
 //      ),
 //      'relations' => array (
 //        0 => array (
 //          'relation_type_id'              => '1',
 //          'relation_first_name'           => 'ISAIAS    FLORES    AUSTRIA',
 //          'relation_middle_name'          => NULL,
 //          'relation_last_name'            => 'AUSTRIA',
 //          'relation_ext_name'             => NULL,
 //          'relation_gender_code'          => NULL,
 //          'relation_occupation'           => NULL,
 //          'relation_company'              => NULL,
 //          'relation_company_address'      => NULL,
 //          'relation_contact_num'          => NULL,
 //          'relation_birth_date'           => NULL,
 //          'relation_civil_status_id'      => NULL,
 //          'relation_employment_status_id' => NULL,
 //          'pwd_flag'                      => 'N',
 //          'gsis_flag'                     => 'N',
 //          'bir_flag'                      => 'N',
 //          'pagibig_flag'                  => 'N',
 //          'philhealth_flag'               => 'N',
 //          'philhealth_number'             => NULL,
 //          'deceased_flag'                 => 'N',
 //          'death_date'                    => NULL,
 //        ),
 //        1 => array (
 //          'relation_type_id'              => '6',
 //          'relation_first_name'           => '',
 //          'relation_middle_name'          => NULL,
 //          'relation_last_name'            => 'AUSTRIA',
 //          'relation_ext_name'             => NULL,
 //          'relation_gender_code'          => NULL,
 //          'relation_occupation'           => '',
 //          'relation_company'              => '',
 //          'relation_company_address'      => '',
 //          'relation_contact_num'          => '',
 //          'relation_birth_date'           => NULL,
 //          'relation_civil_status_id'      => NULL,
 //          'relation_employment_status_id' => NULL,
 //          'pwd_flag'                      => 'N',
 //          'gsis_flag'                     => 'N',
 //          'bir_flag'                      => 'N',
 //          'pagibig_flag'                  => 'N',
 //          'philhealth_flag'               => 'N',
 //          'philhealth_number'             => NULL,
 //          'deceased_flag'                 => 'N',
 //          'death_date'                    => NULL,
 //        ),
 //      ),
 //      'trainings' => array (
 //      ),
 //      'voluntary_works' => array (
 //      ),
 //      'work_experiences' => array (
 //        0 => array (
 //          'employ_type_flag'             => 'OG',
 //          'employ_start_date'            => '2011-06-01',
 //          'employ_end_date'              => '2011-10-18',
 //          'employ_plantilla_id'          => NULL,
 //          'employ_position_id'           => NULL,
 //          'employ_position_name'         => 'Rural Health Physician',
 //          'employ_office_id'             => NULL,
 //          'employ_office_name'           => 'Office of the Secretary- Doctors to the Barrios, DOH',
 //          'employ_salary_grade'          => '24',
 //          'employ_salary_step'           => NULL,
 //          'employ_monthly_salary'        => '43612.00',
 //          'employ_personnel_movement_id' => NULL,
 //          'employment_status_id'         => NULL,
 //          'separation_mode_id'           => NULL,
 //          'govt_service_flag'            => 'Y',
 //          'government_branch_id'         => '39',
 //          'service_lwop'                 => NULL,
 //          'publication_date'             => NULL,
 //          'publication_place'            => NULL,
 //          'prev_employee_id'             => NULL,
 //          'prev_separation_mode_id'      => NULL,
 //          'active_flag'                  => 'N',
 //          'step_incr_reason_code'        => NULL,
 //        ),
 //        1 => array (
 //          'employ_type_flag'             => 'OG',
 //          'employ_start_date'            => '2010-06-24',
 //          'employ_end_date'              => '2011-05-31',
 //          'employ_plantilla_id'          => NULL,
 //          'employ_position_id'           => NULL,
 //          'employ_position_name'         => 'Rural Health Physician',
 //          'employ_office_id'             => NULL,
 //          'employ_office_name'           => 'Office of the Secretary - Doctors to the Barrios, DOH',
 //          'employ_salary_grade'          => '24',
 //          'employ_salary_step'           => NULL,
 //          'employ_monthly_salary'        => '37473.00',
 //          'employ_personnel_movement_id' => NULL,
 //          'employment_status_id'         => NULL,
 //          'separation_mode_id'           => NULL,
 //          'govt_service_flag'            => 'Y',
 //          'government_branch_id'         => '39',
 //          'service_lwop'                 => NULL,
 //          'publication_date'             => NULL,
 //          'publication_place'            => NULL,
 //          'prev_employee_id'             => NULL,
 //          'prev_separation_mode_id'      => NULL,
 //          'active_flag'                  => 'N',
 //          'step_incr_reason_code'        => NULL,
 //        ),
 //        2 => array (
 //          'employ_type_flag'             => 'OG',
 //          'employ_start_date'            => '2009-10-19',
 //          'employ_end_date'              => '2010-06-23',
 //          'employ_plantilla_id'          => NULL,
 //          'employ_position_id'           => NULL,
 //          'employ_position_name'         => 'Rural Health Physician',
 //          'employ_office_id'             => NULL,
 //          'employ_office_name'           => 'Office of the Secretary - Doctors to the Barrios, DOH',
 //          'employ_salary_grade'          => '24',
 //          'employ_salary_step'           => NULL,
 //          'employ_monthly_salary'        => '31334.00',
 //          'employ_personnel_movement_id' => NULL,
 //          'employment_status_id'         => NULL,
 //          'separation_mode_id'           => NULL,
 //          'govt_service_flag'            => 'Y',
 //          'government_branch_id'         => '39',
 //          'service_lwop'                 => NULL,
 //          'publication_date'             => NULL,
 //          'publication_place'            => NULL,
 //          'prev_employee_id'             => NULL,
 //          'prev_separation_mode_id'      => NULL,
 //          'active_flag'                  => 'N',
 //          'step_incr_reason_code'        => NULL,
 //        ),
 //      ),
  //  );
  
  //  $result = $client->add_pds($data); 

 //    $this->output
 //      ->set_status_header(200)
 //      ->set_content_type('text/html')
 //      ->set_output("<pre>REQUEST: \n" . $client->__getLastRequest() . "\n\n" . var_export($result, true) . '</pre>')
 //    ;
  // }

}
