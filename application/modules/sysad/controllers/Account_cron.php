<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_cron extends SYSAD_Controller {
	
	public function __construct(){
		parent::__construct();
		if(!$this->input->is_cli_request()) show_404();
		$this->load->model('users_model','users', TRUE);
	}
	
	public function index(){ $this->run_cron_job_pass(); }
	public function run_cron_job_pass()
	{
		try
		{
		
			$pass_expiry_arr = $this->users->get_settings_arr(PASSWORD_EXPIRY);
			
		
			if(!ISSET($pass_expiry_arr[PASS_EXP_EXPIRY]) || $pass_expiry_arr[PASS_EXP_EXPIRY] == '0' || EMPTY(PASS_EXP_EXPIRY)) return TRUE;
		
		
			if(!$this->db->inTransaction()) $this->db->beginTransaction();
			//$this->users->update_password_status($pass_expiry_arr[PASS_EXP_DURATION]);
			if($this->db->inTransaction()) $this->db->commit();
		
			$accounts = $this->users->get_accounts_to_remind($pass_expiry_arr[PASS_EXP_DURATION]);
			
			$this->initialize_email();
		
			foreach ($accounts as $account)
			{
				$email = array();
				$email['to'] = $account['email'];
				$email['subject'] = 'LGU 360 account expiration';
				$email['message'] = <<<EOS
			To <b>{$account['full_name']}</b>,
			<br />
			<br />
			This is to remind you that your account would expire in {$account['remaning']} days.
			<br />
			<br />
					<br />
			Thank You,
					<br />
			<b>LGU Administrator</b>
EOS;
				$this->send_email($email);
			}
			echo 'Success';
		}
		catch(PDOException $e)
		{
			if($this->db->inTransaction()) $this->db->rollback();
			echo $e->getMessage();
		}
		catch(Exception $e)
		{
			if($this->db->inTransaction()) $this->db->rollback();
			echo $e->getMessage();
		}
	}
}