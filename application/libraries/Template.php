<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	
	var $template_data = array();

	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	private function _set($name, $value)
	{
		$this->template_data[$name] = $value;
	}
	
	public function load($view = '' , $data = array(), $resources = array())
	{   
		try 
		{
			$contents = $this->CI->load->view($view, $data, TRUE);
			$this->_set('contents', $contents);			
			
			if(!EMPTY($resources))
				$this->_set('resources', $resources);			
			
			$this->CI->load->view('template', $this->template_data);
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}	
	}		
		
}

/* End of file */