<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pds extends Soap_Controller
{
	
	private static $tables = array(
		'personal_info',
		'addresses',
		'contacts',
		'declaration',
		'educations',
		'eligibility',
		'identifications',
		'other_info',
		'professions',
		'questions',
		'references',
		'relations',
		'trainings',
		'voluntary_works',
		'work_experiences',
	);

	private static $request_tables = array(
		'requests',
		'requests_sub',
		'requests_tasks'

	);
	
	private $instance;
	private $models;
	private $request_models;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->instance = self::get_instance();
		
		foreach (self::$tables as $t)
		{
			$this->pds_models[$t] = $this->_load_model('soap/Employee_' . $t . '_model');
		}

		foreach (self::$tables as $t)
		{
			$this->models[$t] = $this->_load_model('soap/Request_employee_' . $t . '_model');
		}
		foreach (self::$request_tables as $t)
		{
			$this->request_models[$t] = $this->_load_model('soap/' . $t . '_model');
		}
	}
	
	public function get_pds($id)
	{
		$data = $this->_get_default_error();
	
		foreach (self::$tables as $t)
		{
			$m = $this->pds_models[$t];
			
			if ($t == 'personal_info')
			{
				$data[$t] = $m->get_data($id);
				
				if (empty($data[$t])) return $this->_get_error(Soap_Error::DATA_NOT_FOUND);
			}
			else
			{
				$data[$t] = $m->get_list_by_ref($id);
			}
			
			if ($this->_has_db_error($m)) return $this->_get_db_error($m);
		}
			
		return $data;
	}
	
	public function add_pds($pds)
	{
		Soap_Model::beginTransaction();

		$data = $this->_get_default_error();

		$t  = 'personal_info';
		$m  = $this->pds_models[$t];
		
		$id = $m->add_data($pds[$t]);
		
		$pk_name        = $m->get_pk_name();
		$data[$pk_name] = $id;
		
		foreach (self::$tables as $t)
		{
			if ($t != 'personal_info' AND isset($pds[$t]))
			{
				$m = $this->pds_models[$t];
				
				foreach ($pds[$t] as $d)
				{
					unset($d[$m->get_pk_name()]);
					$d[$pk_name] = $id;
					$m->add_data($d);
					
					if ($this->_has_db_error($m)) return $this->_get_db_error($m);
				}
			}
			// if ($this->_has_db_error($m)) return $this->_get_db_error($m);
		}
		
		Soap_Model::commit();
		
		return $data;
	}

	public function add_pds_request($pds, $id)
	{
		Soap_Model::beginTransaction();

		$data = $this->_get_default_error();

		$m = $this->request_models['requests'];

		$request = array(
				'employee_id'       => '108768',
				'request_type_id'   => 1,
				'request_status_id' => 1,
				'date_requested'    => date('Y-m-d H:i:s')
			);

		$id = $m->add_data($request);
		$pk_name = $m->get_pk_name();
		$data[$pk_name] = $id;
		
		$quotient = 100 / intval($id);
		$addedZeroes = "";
		if ($quotient > 10) {
			$addedZeroes = "00";
		}
		elseif ($quotient > 0) {
			$addedZeroes = "0";
		}
		else {
			$addedZeroes = "";
		}			
	
		$fields 				= array() ;
		$fields['request_code']	= date("Ym").$addedZeroes.$id;

		$m->edit_data($fields, $id);

		$rm = $this->request_models['requests_sub'];

		foreach (self::$tables as $t)
		{
			if ($t != 'personal_info' AND isset($pds[$t]))
			{
				$m = $this->models[$t];

				foreach ($pds[$t] as $d)
				{
						
					$request_sub = array(
						'request_id'            => $id,
						'request_sub_type_id'   => 2,
						'action'				=> 2,
						'employee_id'           => '108768',
						'request_sub_status_id' => 1,
						'remarks'				=> 'Request PDS update from e-JOBS'
					);

					$sub_id = $rm->add_data($request_sub);

					$pk_name = $m->get_pk_name();
					$d[$pk_name] = $sub_id;

					$m->add_data($d);
					
					if ($this->_has_db_error($m)) return $this->_get_db_error($m);
				}
			}
			
		// 	// if ($this->_has_db_error($m)) return $this->_get_db_error($m);
		}
		$m        = $this->request_models['requests_tasks'];
		$workflow = $m->get_initial_task(REQUEST_WORKFLOW_PDS_RECORD_CHANGES);

		$fields 					= array() ;
		$fields['request_id']		= $id;
		$fields['task_detail']		= $workflow['name'];
		$fields['process_stage_id']	= $workflow['process_stage_id'];
		$fields['process_step_id']	= $workflow['process_step_id'];
		$fields['process_id']		= REQUEST_WORKFLOW_PDS_RECORD_CHANGES;
		$fields['task_status_id']	= TASK_NOT_YET_STARTED;

		$m->add_data($fields);

		Soap_Model::commit();
		
		return $data;
	}
	
	private function _has_db_error(Soap_Model $model)
	{
		return ($model->get_error_code() != 0);
	}
	
	private function _get_db_error(Soap_Model $model)
	{
		Main_Model::rollback();
		
		return $this->_construct_error($model->get_error_code(), $model->get_error_message());
	}
	
	private function _load_model($name)
	{
		$alias = strtolower(basename($name));
		$this->instance->load->model($name, $alias);
		
		return $this->instance->$alias;
	}
	
	private function _get_hash_key($name)
	{
		return md5('%$' . $name . '%$');
	}

	

 	public function insert_sub_request($type, $id, $action)
	{
		try
		{
			$fields                          = array() ;
			$fields['request_sub_type_id']   = $type;
			$fields['employee_id']           = $id;
			$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
			$fields['action']                = $action;
			
			$sub_request_id                  = $m->add_data($fields);

			return $sub_request_id;
		}
		catch(PDOException $e){
			return $message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			return $message = $e->getMessage();
		}
	}
	
}