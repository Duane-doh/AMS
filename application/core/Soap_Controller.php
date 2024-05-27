<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soap_Controller
{
	
	private static $_ci;
	
	public function __construct()
	{
	}
	
	public static function set_instance($controller)
	{
		self::$_ci = $controller;
	}
	
	public static function get_instance()
	{
		return self::$_ci;
	}
	
	protected function _get_default_error()
	{
		return $this->_get_error(Soap_Error::NO_ERRORS);
	}
	
	protected function _get_error($error_no)
	{
		return $this->_construct_error($error_no, Soap_Error::get_message($error_no));
	}
	
	protected function _construct_error($error_no, $error_msg)
	{
		return array(
			'error_code' => $error_no,
			'error_message' => $error_msg,
		);
	}
	
}