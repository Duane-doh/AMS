<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_Controller extends Base_Controller
{
	const MODULE = PROJECT_MAIN;
	public function __construct() 
	{
		// Getting values from the configuration
		$rlog_level = $this->config->item('rlog_level');
		$rlog_enable = $this->config->item('rlog_enable');
		$rlog_error_handler = $this->config->item('rlog_error_handler');
			
		// Setting up RLog
		RLog::location(realpath(APPPATH).'/logs/'.PROJECT_MAIN);
		RLog::level($rlog_level);
		RLog::enable($rlog_enable);
		RLog::setErrorHandler($rlog_error_handler);
	}
}
