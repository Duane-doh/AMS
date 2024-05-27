<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class pdf {
   
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }

    function load()
    {
        include_once APPPATH.'/third_party/MPDF57/mpdf.php';

        $args = func_get_args();

        $reflection = new ReflectionClass('mpdf');

        
        if (!EMPTY($args))
        {
            $param = $args;          
        }
        
        return $reflection->newInstanceArgs($param);
    }
    
    public function create()
    {
    	$pdf = new ReflectionClass('mPDF');
    	$var = func_get_args();
    
    	return $pdf->newInstanceArgs(func_get_args());
    }
}