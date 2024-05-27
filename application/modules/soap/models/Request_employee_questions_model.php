<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_questions_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_questions';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'question_id',
		'employee_id',
		'question_answer_flag',
		'question_answer_txt',
		'question_answer_flag_old',
		'question_answer_txt_old',
	);
	
}