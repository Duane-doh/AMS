<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function open_excel ($file,$sheet_index)
{
	//require_once(APPPATH . "third_party/PHPExcel.php");
	require_once(APPPATH . "third_party/PHPExcel/IOFactory.php");
	
	if (!file_exists($file))
		throw new Exception("File Not Found!");
	$sheet_index = is_numeric($sheet_index)? $sheet_index : 0;
	$objPHPExcel = PHPExcel_IOFactory::load($file);
	$objPHPExcel->setActiveSheetIndex($sheet_index);
	$sheet1 = $objPHPExcel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
	
	return $sheet1;
	//$objPHPExcel->setActiveSheetIndex(0);
	//$sheet1 = $objPHPExcel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
	//RLog::debug(var_export($sheet1, true));
}
