<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salary_schedule extends Main_Controller {
	private $module = MODULE_HR_CL_SALARY_SCHEDULE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
		$this->load->model('pds_model', 'pds');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     	= array();
			$resources                	= array();
			$data['action_id']        	= $action_id;
			$resources['load_css'][]  	= CSS_DATATABLE;
			$resources['load_js'][]   	= JS_DATATABLE;
			$resources['datatable'][]	= array('table_id' => 'salary_schedule_table', 'path' => 'main/code_library_hr/salary_schedule/get_salary_schedule_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_salary_schedule' => array(
					'controller'		=> 'code_library_hr/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_salary_schedule',
					'multiple'			=> true,
					'height'			=> '450px',
					'size'				=> 'xl',
					'title'				=> 'Salary Schedule'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_hr/'.__CLASS__,
				'delete',
				PROJECT_MAIN
			);
			
			$data['action_id'] = $action_id;
			
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

		$this->load->view('code_library/tabs/salary_schedule', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_salary_schedule_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns 		= array(
					"DATE_FORMAT(effectivity_date, '%Y/%m/%d') as effectivity_date",
					"IF(other_fund_flag = 'Y', 'Yes', 'No') as other_fund_flag",
					"IF(active_flag = 'Y', 'Active', 'Inactive') as active_flag"
			);
			$bColumns 		= array(
					"DATE_FORMAT(effectivity_date, '%Y/%m/%d')",
					"IF(other_fund_flag = 'Y', 'Yes', 'No')",
					"IF(active_flag = 'Y', 'Active', 'Inactive')"
			);
			$salary_schedule			= $this->cl->get_salary_grade_steps_list($aColumns, $bColumns, $params);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(effectivity_date)) AS count"), $this->cl->tbl_param_salary_schedule, NULL, false);
			$iFilteredTotal 			= $this->cl->salary_sched_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_SALARY_SCHEDULE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_SALARY_SCHEDULE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_SALARY_SCHEDULE, ACTION_DELETE);

			$cnt = 0;
			foreach ($salary_schedule as $aRow):
				$cnt++;
				$row 					= array();
				$action 				= "<div class='table-actions'>";
			
				$effectivity_date		= $aRow["effectivity_date"];
				$id 					= $this->hash ($effectivity_date);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("salary schedule", "'.$url_delete.'")';
				
				$row[] = $aRow['effectivity_date'];
				$row[] = strtoupper($aRow['other_fund_flag']);
				$row[] = strtoupper($aRow['active_flag']);
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_salary_schedule' onclick=\"modal_salary_schedule_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_salary_schedule' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_salary_schedule_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				$action .= "</div>";
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		
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

		echo json_encode( $output );
	}

	public function modal_salary_schedule($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			// $resources['load_css']			= array(CSS_DATETIMEPICKER);
			// $resources['load_js'] 			= array(JS_DATETIMEPICKER, 'jquery.number.min');

			//===== marvin : start : include css/js selectize =====//
			$resources['load_css']			= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');
			//===== marvin : end : include css/js selectize =====//

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data ['action_id'] 					= $action;
			$data ['action'] 						= $action;
			$data ['salt'] 							= $salt;
			$data ['token'] 						= $token;
			$data ['id'] 							= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table               				= $this->cl->tbl_param_salary_schedule;
				$where              				= array();
				$key                 				= $this->get_hash_key("DATE_FORMAT(effectivity_date, '%Y/%m/%d')");
				$where[$key]         				= $id;
				// $data['salary']         			= $this->cl->get_code_library_data(array("max(salary_grade) AS grade", "max(salary_step) AS step", "effectivity_date", "other_fund_flag", "active_flag", "budget_circular_number", "budget_circular_date", "executive_order_number", "execute_order_date", "inserted_flag"), $table, $where, FALSE);		
				
				//===== marvin : start : include effectivity_date_for and employment_status =====//
				$data['salary']         			= $this->cl->get_code_library_data(array("max(salary_grade) AS grade", "max(salary_step) AS step", "effectivity_date", "other_fund_flag", "active_flag", "budget_circular_number", "budget_circular_date", "executive_order_number", "execute_order_date", "inserted_flag", "effectivity_date_for", "employment_status"), $table, $where, FALSE);		
				//===== marvin : end : include effectivity_date_for and employment_status =====//

				//EDIT
				$table               				= $this->cl->tbl_param_salary_schedule;
				$where              				= array();
				$key                 				= $this->get_hash_key("DATE_FORMAT(effectivity_date, '%Y/%m/%d')");
				$where[$key]         				= $id;
				$amount 				      	  	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				$data['amount']						= array();

				foreach ($amount as $value) {
					$grade 							= $value['salary_grade'];
					$step 							= $value['salary_step'];
					$data['amount'][$grade][$step] 	= $value['amount'];
				}	
			}

			//===== marvin : start : include employment_status =====//
			$fields 					= array('employment_status_id', 'employment_status_name');
			$table 						= 'param_employment_status';
			$data['employment_status'] 	= $this->pds->get_general_data($fields, $table);
			//===== marvin : end : include employment_status =====//
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
		
		$this->load->view('code_library/modals/modal_salary_schedule', $data);
		$this->load_resources->get_resource($resources);
	}

	//===== marvin : start : create new function for row effectivity date =====//
	public function create_row_effectivity_date_for($row_counter = null)
	{
		$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
		$resources['load_js'] = array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');
		
		$fields = array('employment_status_id', 'employment_status_name');
		$table  = 'param_employment_status';
		$data 	= $this->pds->get_general_data($fields, $table);
		
		$html = '';
		$html .= '<div class="row" id="row_effectivity_date_for_'.$row_counter.'" style="border-bottom: 1px solid #e5e5e5;">';
			$html .= '<div class="col s3 input-field">';
				$html .= '<label>Effectivity Date for:</label>';
				$html .= '<input type="text" class="datepicker" name="effectivity_date_for[]" />';
			$html .= '</div>';
			$html .= '<div class="col s7 input-field">';
				$html .= '<select name="employment_status['.$row_counter.'][]" id="employment_status_'.$row_counter.'" class="selectize employmentstatus" placeholder="Select Employment Status" onchange="prevent_duplicate(id)" multiple>';
					foreach($data as $d)
					{
						$html .= '<option value="'.$d['employment_status_id'].'">'.$d['employment_status_name'].'</option>';						
					}
				$html .= '</select>';
			$html .= '</div>';
			$html .= '<div class="col s2 input-field">';
				$html .= '<a href="#" id="'.$row_counter.'" class="btn p-r-md p-l-md" onclick="delete_row_effectivity_date_for(id)">Remove</a>';
			$html .= '<div>';
		$html .= '</div>';
		
		echo $html;
		
		$this->load_resources->get_resource($resources);
	}
	//===== marvin : end : create new function for row effectivity date =====//

	public function process()
	{
		try
		{
			$status = 0;
			$params	= get_params();

			//===== marvin : start : include effectivity_date_for and employment_status =====//
			//reset array keys
			// $params['effectivity_date_for'] = implode(',', $params['effectivity_date_for']);
			// $params['employment_status'] = implode(',', $params['employment_status'][0]);

			$params['effectivity_date_for'] = urlencode(json_encode($params['effectivity_date_for']));
			$params['employment_status'] = urlencode(json_encode($params['employment_status']));
			//===== marvin : end : include effectivity_date_for and employment_status =====//

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 		= $this->_validate_data_salary_schedule($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();

			if(EMPTY($params['id']))
			{
				//INSERT DATA TO PARAM SALARY SCHEDULE
				$amt_cnt 		= count($params['amount']);

				foreach ($params['amount'] as $grade => $salary_steps) {

					foreach ($salary_steps as $step => $amount) {
						if($amount > 0)
						{							
							$fields 							= array();
							$fields['effectivity_date']			= $valid_data['effectivity_date'];
							$fields['budget_circular_number']	= $valid_data['budget_circular_number'];
							$fields['budget_circular_date']		= $valid_data['budget_circular_date'];
							$fields['executive_order_number']	= $valid_data['executive_order_number'];
							$fields['execute_order_date']		= $valid_data['execute_order_date'];
							$fields['salary_grade']				= $grade;
							$fields['salary_step']				= $step;
							$fields['amount'] 					= $amount;
							$fields['other_fund_flag']    		= isset($valid_data['other_fund_flag']) ? "Y" : "N";   
							$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";	
							
							//===== marvin : start : include effective_date_for and employment_status =====//
							$fields['effectivity_date_for']		= isset($valid_data['effectivity_date_for']) ? $valid_data['effectivity_date_for'] : NULL;
							$fields['employment_status']		= isset($valid_data['employment_status']) ? $valid_data['employment_status'] : NULL;
							//===== marvin : end : include effective_date_for and employment_status =====//

							$this->cl->insert_code_library($this->cl->tbl_param_salary_schedule, $fields);
						}
					}
				}

				$remarks = $valid_data['budget_circular_number'] . '/' . $valid_data['budget_circular_date'] . ', ' . $valid_data['executive_order_number'] . '/' . $valid_data['execute_order_date'];					

				// GET MAX STEP AND GRADE BASED ON NEW SALARY SCHEDULE
				$field                    = array('MAX(salary_grade) AS max_grade', 'MAX(salary_step) AS max_step', 'active_flag', 'DATE_FORMAT(effectivity_date, "%Y") AS effectivity_date', 'other_fund_flag');
				$table                    = $this->pds->tbl_param_salary_schedule;
				$where                    = array();
				$where['effectivity_date']= $valid_data['effectivity_date'];
				$max_step_grade_sched     = $this->pds->get_general_data($field, $table, $where, FALSE);
				$effective_date		   	  = $valid_data['effectivity_date'];

				$this->_check_max_grade_step($max_step_grade_sched);

				$this->_check_other_fund_flag($max_step_grade_sched, $effective_date);

				//===== marvin : start : include effective_date_for and employment_status =====//
				// $eff_date_for = json_decode(urldecode($max_step_grade_sched['effectivity_date_for']));
				// $orig_effective_date = $valid_data['effectivity_date'];
				// $effective_date = date('Y-m-d H:i:s', strtotime($eff_date_for[0]));
				// $max_step_grade_sched['effectivity_date_for'] = date('Y', strtotime($eff_date_for[0]));
				// $max_step_grade_sched['employment_status'] = json_decode(urldecode($max_step_grade_sched['employment_status']));
				//===== marvin : end : include effective_date_for and employment_status =====//

				// INSERT NEW WORK EXPERIENCE TO ALL ACTIVE EMPLOYEES
				// //===== marvin : start : include effective_date_for and employment_status =====//
				// if($max_step_grade_sched['active_flag'] == NO)
				// //===== marvin : end : include effective_date_for and employment_status =====//
				if($max_step_grade_sched['active_flag'] == YES)
				{
					$this->_check_current_year($max_step_grade_sched);
					//===== marvin : start : include effective_date_for and employment_status =====//
					// $this->_check_current_year_custom($max_step_grade_sched);
					//===== marvin : end : include effective_date_for and employment_status =====//

					// GET ALL ACTIVE EMPLOYEES
					$field                    = array('employee_id');
					$table                    = $this->pds->tbl_employee_work_experiences;
					$where                    = array();
					$where['active_flag']     = YES;
					$where['employ_end_date'] = 'IS NULL';
					//===== marvin : start : include effective_date_for and employment_status =====//
					// $where['employment_status_id'] = array($max_step_grade_sched['employment_status'][0], array('IN'));
					//===== marvin : end : include effective_date_for and employment_status =====//
					$active_employee          = $this->pds->get_general_data($field, $table, $where, TRUE);	

					if($active_employee)
					{
						$this->_add_employee_work_experience($active_employee, $remarks, $effective_date);
						//===== marvin : start : include effective_date_for and employment_status =====//
						// $this->_add_employee_work_experience($active_employee, $remarks, $effective_date, $orig_effective_date);
						//===== marvin : end : include effective_date_for and employment_status =====//

						$inserted_flag_field['inserted_flag'] = YES;
						$where 								  = array();
						$where['effectivity_date'] 			  = $effective_date;
						//===== marvin : start : include effective_date_for and employment_status =====//
						// $where['effectivity_date'] 			  = $effective_date;
						//===== marvin : end : include effective_date_for and employment_status =====//
						$this->cl->update_code_library($this->cl->tbl_param_salary_schedule, $inserted_flag_field, $where);
					}					
				}
				else
				{
					$inserted_flag_field['inserted_flag'] = NO;
					$where 								  = array();
					$where['effectivity_date'] 			  = $orig_effective_date;
					$this->cl->update_code_library($this->cl->tbl_param_salary_schedule, $inserted_flag_field, $where);
				}			
				
				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_salary_schedule;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;		

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_saved');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been added";
			}
			else
			{	
				//WHERE 
				$where						= array();
				$key 						= $this->get_hash_key('effectivity_date');
				$where[$key]				= $params['id'];

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  					= $this->cl->get_code_library_data(array("*"), $this->cl->tbl_param_salary_schedule, $where, TRUE);

				//DELETE DATA
				$where 						= array();
				$where['effectivity_date'] 	= $valid_data['prev_date'];

				$this->cl->delete_code_library($this->cl->tbl_param_salary_schedule, $where);

				$amt_cnt 					= count($params['amount']);

				foreach ($params['amount'] as $grade => $salary_steps) {

					foreach ($salary_steps as $step => $amount) {
						if($amount > 0)
						{					
							$fields 							= array();
							$fields['effectivity_date']			= $valid_data['effectivity_date'];
							$fields['effectivity_date']			= $valid_data['effectivity_date'];
							$fields['budget_circular_number']	= $valid_data['budget_circular_number'];
							$fields['budget_circular_date']		= $valid_data['budget_circular_date'];
							$fields['executive_order_number']	= $valid_data['executive_order_number'];
							$fields['execute_order_date']		= $valid_data['execute_order_date'];
							$fields['salary_grade']				= $grade;
							$fields['salary_step']				= $step;
							$fields['amount'] 					= $amount;	   
							$fields['other_fund_flag']    		= isset($valid_data['other_fund_flag']) ? "Y" : "N";  
							$fields['active_flag']    			= isset($valid_data['active_flag']) ? "Y" : "N";

							//===== marvin : start : include effective_date_for and employment_status =====//
							$fields['effectivity_date_for']		= isset($valid_data['effectivity_date_for']) ? $valid_data['effectivity_date_for'] : NULL;
							$fields['employment_status']		= isset($valid_data['employment_status']) ? $valid_data['employment_status'] : NULL;
							//===== marvin : end : include effective_date_for and employment_status =====//

							$this->cl->insert_code_library($this->cl->tbl_param_salary_schedule, $fields);
						}
					}
				}

				$remarks = $valid_data['budget_circular_number'] . '/' . $valid_data['budget_circular_date'] . ', ' . $valid_data['executive_order_number'] . '/' . $valid_data['execute_order_date'];					

				// GET MAX STEP AND GRADE BASED ON NEW SALARY SCHEDULE
				//===== marvin : start : include effective_date_for and employment_status =====//
				// $field                    = array('MAX(salary_grade) AS max_grade', 'MAX(salary_step) AS max_step', 'active_flag', 'DATE_FORMAT(effectivity_date, "%Y") AS effectivity_date', 'other_fund_flag', 'effectivity_date_for', 'employment_status');
				//===== marvin : end : include effective_date_for and employment_status =====//
				$field                    = array('MAX(salary_grade) AS max_grade', 'MAX(salary_step) AS max_step', 'active_flag', 'DATE_FORMAT(effectivity_date, "%Y") AS effectivity_date', 'other_fund_flag');
				$table                    = $this->pds->tbl_param_salary_schedule;
				$where                    = array();
				$where['effectivity_date']= $valid_data['effectivity_date'];
				$max_step_grade_sched     = $this->pds->get_general_data($field, $table, $where, FALSE);
				$effective_date		   	  = $valid_data['effectivity_date'];

				$this->_check_max_grade_step($max_step_grade_sched);

				$this->_check_other_fund_flag($max_step_grade_sched, $effective_date);	
				
				//===== marvin : start : include effective_date_for and employment_status =====//
				// $eff_date_for = json_decode(urldecode($max_step_grade_sched['effectivity_date_for']));
				// $orig_effective_date = $valid_data['effectivity_date'];
				// $effective_date = date('Y-m-d H:i:s', strtotime($eff_date_for[0]));
				// $max_step_grade_sched['effectivity_date_for'] = date('Y', strtotime($eff_date_for[0]));
				// $max_step_grade_sched['employment_status'] = json_decode(urldecode($max_step_grade_sched['employment_status']));
				//===== marvin : end : include effective_date_for and employment_status =====//

				// INSERT NEW WORK EXPERIENCE TO ALL ACTIVE EMPLOYEES
				//===== marvin : start : include effective_date_for and employment_status =====//
				// if($valid_data['prev_inserted_flag'] == NO)
				//===== marvin : end : include effective_date_for and employment_status =====//
				if($max_step_grade_sched['active_flag'] == YES AND $valid_data['prev_inserted_flag'] == NO)
				{

					$this->_check_current_year($max_step_grade_sched);

					//===== marvin : start : include effective_date_for and employment_status =====//
					// $this->_check_current_year_custom($max_step_grade_sched);
					//===== marvin : end : include effective_date_for and employment_status =====//

					// GET ALL ACTIVE EMPLOYEES
					$field                    = array('employee_id');
					$table                    = $this->pds->tbl_employee_work_experiences;
					$where                    = array();
					$where['active_flag']     = YES;
					$where['employ_end_date'] = 'IS NULL';
					//===== marvin : start : include effective_date_for and employment_status =====//
					// $where['employment_status_id'] = array($max_step_grade_sched['employment_status'][0], array('IN'));
					//===== marvin : end : include effective_date_for and employment_status =====//
					$active_employee          = $this->pds->get_general_data($field, $table, $where, TRUE);

					if($active_employee)
					{
						$this->_add_employee_work_experience($active_employee, $remarks, $effective_date);
						//===== marvin : start : include effective_date_for and employment_status =====//
						// $this->_add_employee_work_experience($active_employee, $remarks, $effective_date, $orig_effective_date);
						//===== marvin : end : include effective_date_for and employment_status =====//

						$inserted_flag_field['inserted_flag'] = YES;
						$where 								  = array();
						// $where['effectivity_date'] 			  = $effective_date;
						//===== marvin : start : include effective_date_for and employment_status =====//
						// $where['effectivity_date'] 			  = $orig_effective_date;
						//===== marvin : end : include effective_date_for and employment_status =====//
						$this->cl->update_code_library($this->cl->tbl_param_salary_schedule, $inserted_flag_field, $where);
					}
				}

				// UPDATE WORK EXPERIENCE TO ALL ACTIVE EMPLOYEES
				//===== marvin : start : include effective_date_for and employment_status =====//
				// if($valid_data['prev_inserted_flag'] == YES)
				//===== marvin : end : include effective_date_for and employment_status =====//
				if($max_step_grade_sched['active_flag'] == YES AND $valid_data['prev_inserted_flag'] == YES)
				{

					$this->_check_current_year($max_step_grade_sched);

					//===== marvin : start : include effective_date_for and employment_status =====//
					// $this->_check_current_year_custom($max_step_grade_sched);
					//===== marvin : end : include effective_date_for and employment_status =====//
					
					// GET ALL ACTIVE EMPLOYEES
					$field                    	= array('employee_id', 'employ_salary_step', 'employ_salary_grade');
					$table                    	= $this->pds->tbl_employee_work_experiences;
					$where                    	= array();
					$where['active_flag']     	= YES;
					$where['employ_end_date'] 	= 'IS NULL';
					$where['employ_start_date'] = array($effective_date, array(">="));
					//===== marvin : start : include effective_date_for and employment_status =====//
					// $where['employment_status_id'] = array($max_step_grade_sched['employment_status'][0], array('IN'));
					//===== marvin : end : include effective_date_for and employment_status =====//					
					$active_employee          	= $this->pds->get_general_data($field, $table, $where, TRUE);

					if($active_employee)
					{
						$this->_update_employee_work_experience($active_employee, $effective_date);
						//===== marvin : start : include effective_date_for and employment_status =====//
						// $this->_update_employee_work_experience($active_employee, $effective_date, $orig_effective_date);
						//===== marvin : end : include effective_date_for and employment_status =====//
					}
				}		

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_salary_schedule;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}

			$activity = sprintf($activity, $params['effectivity_date']);
	
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
		$data['message'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function delete()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_salary_schedule;
			$where				= array();
			$key 				= $this->get_hash_key("DATE_FORMAT(effectivity_date, '%Y/%m/%d')");
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= array();
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['effectivity_date']);
	
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
			$flag = 1;
				
		}
		catch(PDOException $e)
		{
			$msg = $this->get_user_message($e);	
			Main_Model::rollback();
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info 					= array(
			"flag"            	=> $flag,
			"msg"             	=> $msg,
			"reload"          	=> 'datatable',
			"table_id"        	=> 'salary_schedule_table',
			"path"            	=> PROJECT_MAIN . '/code_library_hr/salary_schedule/get_salary_schedule_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_salary_schedule($params)
	{
		//SPECIFY HERE INPUTS FROM USER
			$fields 							= array();
			$fields['effectivity_date']			= "Effectivity Date";
			$fields['budget_circular_number']	= "Budget Circular Number";
			$fields['budget_circular_date']		= "Budget Circular Date";
			$fields['executive_order_number']	= "Executive Order Number";
			$fields['execute_order_date']		= "Executive Order Date";
	
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_data_salary_schedule_input($params);
	}

	private function _validate_data_salary_schedule_input($params) 
	{
		try {
			
			$validation ['effectivity_date'] 		= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Effectivity Date',
			);
			$validation ['prev_date'] 				= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Previous Date',
			);
			$validation ['prev_inserted_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Previous Insert Flag',
			);
			$validation ['budget_circular_number'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Budget Circular Name',
					'max_len' 						=> 45 
			);
			$validation ['budget_circular_date'] 	= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Budget Circular Date',
			);
			$validation ['executive_order_number'] 	= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Executive Order Name',
					'max_len' 						=> 45 
			);
			$validation ['execute_order_date'] 		= array (
					'data_type' 					=> 'date',
					'name' 							=> 'Executive Order Date',
			);
			$validation ['other_fund_flag'] 		= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Fund Flag',
					'max_len' 						=> 1 
			);
			$validation ['active_flag'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Active Flag',
					'max_len' 						=> 1 
			);

			//===== marvin : start : include effective_date_for and employment_status=====//
			$validation ['effectivity_date_for'] 			= array (
				'data_type' 					=> 'string',
				'name' 							=> 'Effectivity Date for'
			);
			$validation ['employment_status'] 			= array (
					'data_type' 					=> 'string',
					'name' 							=> 'Employment Status'
			);
			//===== marvin : end : include effective_date_for and employment_status=====//
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	//===== marvin : start : include effective_date_for and employment_status =====//
	// private function _add_employee_work_experience($active_employee, $remarks, $effective_date, $orig_effective_date)
	//===== marvin : end : include effective_date_for and employment_status =====//
	private function _add_employee_work_experience($active_employee, $remarks, $effective_date)
	{

		try
		{
			// GET SALARY ADJUSTMENT PERSONNEL MOVEMENT
			$field                    	  = array('sys_param_value');
			$table                    	  = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
			$where                    	  = array();
			$where['sys_param_type']  	  = MOVT_SALARY_ADJUSTMENT;
			$personnel_movement       	  = $this->pds->get_general_data($field, $table, $where, FALSE); 

			foreach ($active_employee as $key => $row) 
			{
				$employee_id 			  = $this->hash($row['employee_id']);

				// GET PREVIOUS EMPLOYEE WORK EXPERIENCE
				$field                    = array('*');
				$table                    = $this->pds->tbl_employee_work_experiences;
				$where                    = array();
				$where['active_flag']     = YES;
				$key     				  = $this->get_hash_key('employee_id');
				$where[$key]	  		  = $employee_id;
				$prev_emp_work_exp        = $this->pds->get_general_data($field, $table, $where, FALSE);
				$prev_work_exp_id         = $this->hash($prev_emp_work_exp['employee_work_experience_id']);

				$data_raw   			  = $effective_date;
				$first_date 			  = strtotime($data_raw);
				$prev_date  			  = strtotime('-1 day', $first_date);
				$date       			  = date('Y-m-d', $prev_date);				

				// GET MONTHLY SALARY BASED ON NEW SALARY SCHEDULE
				$field                    = array('amount');
				$table                    = $this->pds->tbl_param_salary_schedule;
				$where                    = array();
				$where['salary_grade']	  = $prev_emp_work_exp['employ_salary_grade'];
				$where['salary_step']	  = $prev_emp_work_exp['employ_salary_step'];
				$where['effectivity_date']= $effective_date;
				//===== marvin : start : include effective_date_for and employment_status =====//
				// $where['effectivity_date']= $orig_effective_date;
				//===== marvin : end : include effective_date_for and employment_status =====//
				$salary        			  = $this->pds->get_general_data($field, $table, $where, FALSE);

				$work_fields['employee_id'] 				 = $prev_emp_work_exp['employee_id'];
				$work_fields['employ_type_flag']             = $prev_emp_work_exp['employ_type_flag'];
				$work_fields['employ_start_date']            = $effective_date;
				$work_fields['employ_plantilla_id']          = $prev_emp_work_exp['employ_plantilla_id'];
				$work_fields['employ_position_id']           = $prev_emp_work_exp['employ_position_id'];
				$work_fields['employ_position_name']         = $prev_emp_work_exp['employ_position_name'];
				$work_fields['employ_office_id']             = $prev_emp_work_exp['employ_office_id'];
				$work_fields['employ_office_name']           = $prev_emp_work_exp['employ_office_name'];
				$work_fields['admin_office_id']              = $prev_emp_work_exp['admin_office_id'];
				$work_fields['admin_office_name']            = $prev_emp_work_exp['admin_office_name'];
				$work_fields['employ_salary_step']           = $prev_emp_work_exp['employ_salary_step'];
				$work_fields['employ_salary_grade']          = $prev_emp_work_exp['employ_salary_grade'];
				$work_fields['employ_monthly_salary']        = $salary['amount'];
				$work_fields['employ_personnel_movement_id'] = $personnel_movement['sys_param_value'];
				$work_fields['employment_status_id']         = $prev_emp_work_exp['employment_status_id'];
				$work_fields['separation_mode_id']           = !EMPTY($prev_emp_work_exp['separation_mode_id']) ? $prev_emp_work_exp['separation_mode_id'] : NULL;
				$work_fields['govt_service_flag']            = $prev_emp_work_exp['govt_service_flag'];
				$work_fields['government_branch_id']         = $prev_emp_work_exp['government_branch_id'];
				$work_fields['step_incr_reason_code'] 		 = !EMPTY($prev_emp_work_exp['step_incr_reason_code']) ? $prev_emp_work_exp['step_incr_reason_code'] : NULL;
				$work_fields['active_flag']                  = YES;
				$work_fields['remarks']                  	 = $remarks;

				$this->pds->insert_general_data($this->pds->tbl_employee_work_experiences, $work_fields, FALSE);

				// UPDATE PREVIOUS EMPLOYEE WORK EXP INFO
				// $prev_work_fields['employee_id'] 				  = $prev_emp_work_exp['employee_id'];
				// $prev_work_fields['employ_type_flag']             = $prev_emp_work_exp['employ_type_flag'];
				// $prev_work_fields['employ_start_date']            = $prev_emp_work_exp['employ_start_date'];
				// $prev_work_fields['employ_plantilla_id']          = $prev_emp_work_exp['employ_plantilla_id'];
				// $prev_work_fields['employ_position_id']           = $prev_emp_work_exp['employ_position_id'];
				// $prev_work_fields['employ_position_name']         = $prev_emp_work_exp['employ_position_name'];
				// $prev_work_fields['employ_office_id']             = $prev_emp_work_exp['employ_office_id'];
				// $prev_work_fields['employ_office_name']           = $prev_emp_work_exp['employ_office_name'];
				// $prev_work_fields['admin_office_id']              = $prev_emp_work_exp['admin_office_id'];
				// $prev_work_fields['employ_salary_step']           = $prev_emp_work_exp['employ_salary_step'];
				// $prev_work_fields['employ_salary_grade']          = $prev_emp_work_exp['employ_salary_grade'];
				// $prev_work_fields['employ_monthly_salary']        = $prev_emp_work_exp['employ_monthly_salary'];
				// $prev_work_fields['employ_personnel_movement_id'] = $prev_emp_work_exp['employ_personnel_movement_id'];
				// $prev_work_fields['employment_status_id']         = $prev_emp_work_exp['employment_status_id'];
				// $prev_work_fields['separation_mode_id']           = !EMPTY($prev_emp_work_exp['separation_mode_id']) ? $prev_emp_work_exp['separation_mode_id'] : NULL;
				// $prev_work_fields['govt_service_flag']            = $prev_emp_work_exp['govt_service_flag'];
				// $prev_work_fields['government_branch_id']         = $prev_emp_work_exp['government_branch_id'];
				// $prev_work_fields['step_incr_reason_code'] 		  = !EMPTY($prev_emp_work_exp['step_incr_reason_code']) ? $prev_emp_work_exp['step_incr_reason_code'] : NULL;
				$prev_work_fields['employ_end_date']     	   	  = $date;
				$prev_work_fields['active_flag']        		  = NO;
				$prev_work_fields['remarks']        		 	  = 'NOSA';

				$where                            				  = array();
				$key     				  						  = $this->get_hash_key('employee_work_experience_id');
				$where[$key]	  		  						  = $prev_work_exp_id;
				$this->pds->update_general_data($this->pds->tbl_employee_work_experiences, $prev_work_fields, $where, FALSE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->pds->tbl_employee_work_experiences;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($work_fields);
				$audit_action[] = AUDIT_INSERT;		

			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return true;
	}

	private function _update_employee_work_experience($active_employee, $effective_date)
	//===== marvin : start : include effective_date_for and employment_status =====//
	// private function _update_employee_work_experience($active_employee, $effective_date, $orig_effective_date)
	//===== marvin : end : include effective_date_for and employment_status =====//
	{

		try
		{
			foreach ($active_employee as $key => $row) 
			{
				$employee_id 			  = $this->hash($row['employee_id']);

				// GET MONTHLY SALARY BASED ON NEW SALARY SCHEDULE
				$field                    = array('amount');
				$table                    = $this->pds->tbl_param_salary_schedule;
				$where                    = array();
				$where['salary_grade']	  = $row['employ_salary_grade'];
				$where['salary_step']	  = $row['employ_salary_step'];
				$where['effectivity_date']= $effective_date;
				//===== marvin : start : include effective_date_for and employment_status =====//
				// $where['effectivity_date']= $orig_effective_date;
				//===== marvin : end : include effective_date_for and employment_status =====//
				$salary        			  = $this->pds->get_general_data($field, $table, $where, FALSE);

				$amount_field['employ_monthly_salary']        	  = $salary['amount'];
				$where                            				  = array();
				$key     				  						  = $this->get_hash_key('employee_id');
				$where[$key]	  		  						  = $employee_id;
				$this->pds->update_general_data($this->pds->tbl_employee_work_experiences, $amount_field, $where, FALSE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->pds->tbl_employee_work_experiences;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($amount_field);
				$audit_action[] = AUDIT_INSERT;		

			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return true;
	}

	private function _check_max_grade_step($max_step_grade_sched)
	{

		try
		{
			// GET MAX STEP AND GRADE
			$field                    = array('MAX(employ_salary_grade) AS max_grade', 'MAX(employ_salary_step) AS max_step');
			$table                    = $this->pds->tbl_employee_work_experiences;
			$where                    = array();
			$where['active_flag']     = YES;
			$where['employ_end_date'] = 'IS NULL';
			$max_step_grade_work_exp  = $this->pds->get_general_data($field, $table, $where, FASLE);	

			if($max_step_grade_sched['max_grade'] < $max_step_grade_work_exp[0]['max_grade'] OR $max_step_grade_sched['max_step'] < $max_step_grade_work_exp[0]['max_step'])
			{
				throw new Exception($this->lang->line('err_max_grade_step'));
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return true;
	}

	private function _check_current_year($max_step_grade_sched)
	{

		try
		{
			$this_year = date('Y');
			if($max_step_grade_sched['effectivity_date'] != $this_year)
			{
				throw new Exception($this->lang->line('err_diff_year'));					
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return true;
	}

	//===== marvin : start : include effective_date_for and employment_status =====//
	private function _check_current_year_custom($max_step_grade_sched)
	{

		try
		{
			$this_year = date('Y');
			if($max_step_grade_sched['effectivity_date_for'] != $this_year)
			{
				throw new Exception($this->lang->line('err_diff_year'));					
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return true;
	}
	//===== marvin : end : include effective_date_for and employment_status =====//

	private function _check_other_fund_flag($max_step_grade_sched, $effective_date)
	{

		try
		{			
			$other_fund_flag  		 = $max_step_grade_sched['other_fund_flag'];
			$active_flag  		     = $max_step_grade_sched['active_flag'];

			if($active_flag == YES)
			{					
				$active_salary_schedule  = $this->cl->get_active_salary_schedules($other_fund_flag, $effective_date);
				if(!EMPTY($active_salary_schedule))
				{					
					throw new Exception($this->lang->line('other_fund_flag'));
				}
			}

		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		return true;
	}

}

/* End of file Salary_schedule.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Salary_schedule.php */