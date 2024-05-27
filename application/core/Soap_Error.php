<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soap_Error
{
	
	const NO_ERRORS = 0;
	const UNKNOWN_ERROR = 99;
	const DATA_NOT_FOUND = 100;
	
	public function __construct()
	{
	}
	
	public static function get_message($error_no)
	{
		switch ($error_no)
		{
			case self::NO_ERRORS:
				return 'No errors.';
			case self::DATA_NOT_FOUND:
				return 'Data not found.';
			case self::UNKNOWN_ERROR:
			default:
				return 'Unknown error.';
		}
	}
	
}
