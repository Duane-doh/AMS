<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Load_resources {
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	 * $resources - js and css files that will be needed by a particular page.
	 */
	
	public function get_resource($resources, $print = FALSE)
	{	
		try 
		{
			if($print){
				return $this->CI->load->view('footer', $resources, $print);
			} else {
				$this->CI->load->view('footer', $resources);
			}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}							
	}	
	
}