<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compensation_type_payroll extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_COMPENSATION_TYPE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function modal_compensation_type_payroll($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{			
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE, JS_NUMBER);

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 			= $action;
			$data ['nav_page']				= CODE_LIBRARY_COMPENSATION;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;

			$table							= $this->cl->db_core.'.'.$this->cl->tbl_sys_param;
			$field 							= array("sys_param_value");
			
			$resources['multiple'] = array();
			
			$data['payroll_types']	  		= $this->get_payroll_types();;
			
			if(!EMPTY($id))
			{
				
				$data['compensation']	= $this->get_compensation_payroll_details($id);
				
				if(!EMPTY($data['compensation'][0]['payroll_compensation_id']))
				{
					foreach($data['compensation'] as $value)
					{
						$resources['multiple']['payout_num_' . $value['payroll_type_id']] = explode(',', $value['payout_date_num']);
					}
				}
			}
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		
		$this->load->view('code_library/modals/modal_compensation_type_payroll', $data);
		$this->load_resources->get_resource($resources);

	}
	
	public function process()
	{
		try
		{
			$status = 0;
			$params	= get_params();
				
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			//STORES PAYROLL TYPES
			$payroll_types	= $this->get_payroll_types();
			
			// SERVER VALIDATION
			$valid 	= $this->_validate_data_compensation_type_payroll($params, $payroll_types);
			if(!$valid)
			{
				throw new Exception("Please select atleast 1 <b>Payroll Count</b>.");
			}

			Main_Model::beginTransaction();

			$table 			= $this->cl->tbl_param_payroll_compensations;
			$where			= array();
			$key 			= $this->get_hash_key('compensation_id');
			$where[$key]	= $params['id'];
			$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

			// START:: DELETE PARAM PAYROLL COMPENSATION
			$this->cl->delete_code_library($table, $where);
			// END:: DELETE FROM param_payroll_compensations FIRST THEN INSERT

			$result			  			= $this->cl->get_code_library_data(array('compensation_id'), $this->cl->tbl_param_compensations, $where, FALSE);
			$fields 					= array();

			$fields['compensation_id']	= $result['compensation_id'];
			
			
			foreach ($payroll_types as $payroll)
			{
				if(ISSET($params['payout_num_'.$payroll['payroll_type_id']]))
				{
					foreach($params['payout_num_'.$payroll['payroll_type_id']] as $payout)
					{
						sort($payout);
						$fields['payroll_type_id']	= $params['payroll_type_id_'.$payroll['payroll_type_id']];
						$fields['payout_date_num']	= $payout;
						$this->cl->insert_code_library($table, $fields, $where);
					}
				}
			}
			

			//SET AUDIT TRAIL DETAILS
			$audit_table[]	= $this->cl->tbl_param_payroll_compensations;
			$audit_schema[]	= DB_MAIN;
			$prev_detail[]  = array($previous);
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;

			//MESSAGE ALERT
			$message 		= $this->lang->line('data_updated');
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 		= "%s has been updated";		
			
			$activity = sprintf($activity, $params['compensation_name']);
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
			
			Main_Model::commit();
			$status = TRUE;
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	private function _validate_data_compensation_type_payroll($params, $payroll_types)
	{
		
		$valid = false;
		
		foreach ($payroll_types as $payroll)
		{
			if($params['payout_num_'.$payroll['payroll_type_id']] != null)
			{
				$valid = true;
				break;
			}
		}
		
		return $valid;
	}

	
	public function get_compensation_payroll_details($id)
	{
		$fields			= array('A.compensation_name', 'B.payroll_compensation_id', 'B.payroll_type_id', 'GROUP_CONCAT(DISTINCT(B.payout_date_num)) payout_date_num', 'B.compensation_id');
		$tables 		= array(
				'main'		=> array(
						'table'		=>  $this->cl->tbl_param_compensations,
						'alias'		=>  'A'
				),
				't2'		=> array(
						'table'		=>  $this->cl->tbl_param_payroll_compensations,
						'alias'		=>  'B',
						'type'		=>	'LEFT JOIN',
						'condition' =>	'A.compensation_id = B.compensation_id'
				)
		);
		$where						= array();
		$key 						= $this->get_hash_key('A.compensation_id');
		$where[$key]				= $id;
		$order_by					= array('B.payroll_type_id' => 'ASC');
		$group_by					= array('B.payroll_type_id');
	
		return $this->cl->get_code_library_data($fields, $tables, $where, TRUE, $order_by, $group_by);
	}
	
	public function get_payroll_types()
	{
		$table							= $this->cl->tbl_param_payroll_types;
		$field 							= array("payroll_type_id", "payroll_type_name", "IF(payout_count>monthly_payroll_count, payout_count, monthly_payroll_count) payout_count");
		$where							= array();
		$where['active_flag']			= YES;
		return $this->cl->get_code_library_data($field, $table, $where, TRUE);
	}
}

/* End of file Compensation_type_payroll.php */
/* Location: ./application/modules/main/controllers/code_library_payroll/Compensation_type_payroll.php */