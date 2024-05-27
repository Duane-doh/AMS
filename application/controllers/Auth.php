<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Base_Controller {

	public function __construct() 
	{
		parent::__construct();
	}
		
	public function index()
	{	
		$this->load->view('login');
		// $this->load->view('maintenance');
	}	
	
	public function sign_in($username = NULL, $password = NULL) 
	{

		$flag = 0;
		$msg = "";
		$salted = FALSE;


		
		try {
			if(!IS_NULL($username) AND !IS_NULL($password)){ 
				$username = filter_var($username, FILTER_SANITIZE_STRING);
				$username = base64_url_decode($username);
				$password = filter_var(base64_url_decode($password), FILTER_SANITIZE_STRING);

				$this->auth_model->update_status($username);
				
				$salted = TRUE;
			} else {
				$params = get_params();
			
				$username = filter_var($params['username'], FILTER_SANITIZE_STRING);
				$password = filter_var($params['password'], FILTER_SANITIZE_STRING);
				//NCOCAMPO: Data Privacy Statement and DOH Privacy Policy :START
				$agree = filter_var($params['agree']);
				//NCOCAMPO: Data Privacy Statement and DOH Privacy Policy :END
			}	
			
			// if(EMPTY($username)) throw new Exception($this->lang->line('username_required'));
			if(EMPTY($username)) throw new Exception($this->lang->line('email_required'));
			if(EMPTY($password)) throw new Exception($this->lang->line('password_required'));

			//NCOCAMPO: Data Privacy Statement and DOH Privacy Policy ERROR MESSAGE:START
			if(EMPTY($agree)) throw new Exception($this->lang->line('agree_required'));
			//NCOCAMPO: Data Privacy Statement and DOH Privacy Policy ERROR MESSAGE:END


			$user_info = $this->auth_model->get_active_user($username);

			if($user_info['status_id'] == EXPIRED) {
				$flag = 2;
				$msg = 'Your password has been expired';
			} else {
				$this->authenticate->sign_in($username, $password, $salted);
				/*START: RUEL CODE FOR PTIS ONLY*/
				// GET AND CHECK USER PDS RECORD ID
				$pds_ids	= $this->auth_model->get_user_pds_account($this->session->userdata("user_id"));

				if($pds_ids)
				{
					$this->session->set_userdata('user_pds_id', $this->hash($pds_ids['employee_id']));
				}

				/*END: RUEL CODE FOR PTIS ONLY*/
				
				$flag = 1;		
			}

							
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
		}
		
		$result = array(
			"flag" => $flag,
			"msg" => $msg
		);
		
		if($salted){
			$this->authenticate->check_user();	
		} else {
			echo json_encode($result);
		}	
		
	}
	
	public function sign_out()
	{
		$flag = 0;
		$msg = "";
		
		try
		{
			$this->authenticate->sign_out();
			
			// Unset autologin variable
			delete_cookie('autologin');
			$flag = 1;							
		}
					
		catch(Exception $e)
		{
			$msg = $e->getMessage();
		}
		
		$result	= array(
			"flag" => $flag,
			"msg" => $msg
		); 
												
		echo json_encode($result);
			
	}
		
	public function change_password()
	{
		$this->load->view("modals/change_password");
	}
}


/* End of file auth.php */
/* Location: ./application/controllers/auth.php */