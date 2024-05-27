<?php

class Migrate_users extends SYSAD_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Migrate_user_model', 'mig_users', TRUE);
	}
	
	public function index()
	{
		RLog::error("*** S: Migrate_users ***");
		$users = $this->mig_users->get_migrated_users();
		RLog::error("*** E: Migrate_users ***");
	}
	
}