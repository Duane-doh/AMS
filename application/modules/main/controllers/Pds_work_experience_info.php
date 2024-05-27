<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_work_experience_info extends Main_Controller {

	private $log_user_id       =  '';
	private $log_user_roles    = array();
	
	private $permission_view   = FALSE;
	private $permission_edit   = FALSE;
	private $permission_delete = FALSE;
	
	private $permission_module = MODULE_; // TBD 

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_model', 'pds');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
		$this->form2316    			= modules::load('main/payroll_form_2316');
	}
	
	public function get_pds_work_experience_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
	{
		try
		{
			$data 	= array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if($module == MODULE_PERSONNEL_PORTAL)
			{
				$controller = "pds_record_changes_requests";
			}
			else
			{
				$controller = __CLASS__;
			}
			
			$resources['load_css'][] 					= CSS_DATATABLE;
			$resources['load_js'][] 					= JS_DATATABLE;
			$resources['load_modal']    				= array(
						'modal_work_experience_non_doh' => array(
								'controller'			=> __CLASS__,
								'module'				=> PROJECT_MAIN,
								'method'				=> 'modal_work_experience_non_doh',
								'multiple'				=> true,
								'height'				=> '600px',
								'size'					=> 'md',
								'title'					=> 'Non DOH Work Experience'
						),
						'modal_work_experience_doh'  	=> array(
								'controller'			=> __CLASS__,
								'module'				=> PROJECT_MAIN,
								'method'				=> 'modal_work_experience_doh',
								'multiple'				=> true,
								'height'				=> '600px',
								'size'					=> 'md',
								'title'					=> 'DOH Work Experience',
						)
					);
					$resources['load_delete'] 	= array(
								$controller,
								'delete_work_experience',
								PROJECT_MAIN
					);

					$employee_id 				= $id;
					$resources['datatable'][]	= array('table_id' => 'pds_work_experience_table', 'path' => 'main/pds_work_experience_info/get_work_experience_list/'.$employee_id, 'advanced_filter' => true);
					$data['nav_page']			= PDS_WORK_EXPERIENCE;

			$data['if_separated'] = $this->pds->check_employee_separation();

		}
		catch(Exception $e)
		{
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/work_experience', $data);
		$this->load_resources->get_resource($resources);		
	}

	public function get_work_experience_list($employee_id)
	{
		try
		{
			$params          = get_params();
			$module          = $this->session->userdata("pds_module");
			$pds_action      = $this->session->userdata("pds_action");

			$aColumns        = array("DATE_FORMAT(A.employ_start_date,'%Y/%m/%d') as employ_start_date", "IFNULL(DATE_FORMAT(A.employ_end_date, '%Y/%m/%d'), 'PRESENT') as employ_end_date", "A.employ_type_flag, A.employee_work_experience_id","A.employee_id", "IFNULL(A.employ_office_name, E.name) as office", "IFNULL(A.employ_position_name, D.position_name) as position", "A.employ_monthly_salary", "B.employment_status_name", "A.relevance_flag");
			$bColumns        = array("DATE_FORMAT(A.employ_start_date,'%Y/%m/%d')", "IFNULL(DATE_FORMAT(A.employ_end_date, '%Y/%m/%d'), 'PRESENT')", "IFNULL(A.employ_position_name, D.position_name)", "IFNULL(A.employ_office_name, E.name)", "A.employ_monthly_salary", "B.employment_status_name", "A.relevance_flag");
			
			$work_experience = $this->pds->get_work_experience_list($aColumns, $bColumns, $params, $module);
			$iTotal          = $this->pds->work_experience_total_length();
			$iFilteredTotal  = $this->pds->work_experience_filtered_length($aColumns, $bColumns, $params);
			

			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			
			// $permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			// $permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			// $permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			
			$cnt = 0;
			foreach ($work_experience as $aRow):
				$cnt++;
				$row 			= array();
				$action 		= "";				

				$id 			= $this->hash($aRow['employee_work_experience_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;

				if($aRow['relevance_flag'] == "Y")
				{
					$relevance = "checked";
					$relevance_label = "Relevant";
				}
				else
				{
					$relevance = "";
					$relevance_label = "Not Relevant";
				}
				$row[] 	  = '<center>' . $aRow['employ_start_date'] . '</center>';
				$row[] 	  = '<center>' . $aRow['employ_end_date'] . '</center>';
				$row[] 	  = strtoupper($aRow['position']);
				$row[] 	  = strtoupper($aRow['office']);
				$row[] 	  = '<p class="m-n right">&#8369; ' . number_format($aRow['employ_monthly_salary'],2) . '</p>';
				$row[] 	  = $aRow['employment_status_name'];

				$relevance   = "<div class='switch responsive-tablet'><center>
						<label>
						    <input type='checkbox' class='filled-in' name='relevance".$cnt."' id='relevance".$cnt."' value='Y' onclick=\"update_relevance('".$aRow['employee_work_experience_id']."')\" ".(($aRow['relevance_flag'] == 'Y') ? "checked" : "")." ".($pds_action == ACTION_VIEW ? 'disabled' : '').">
						    <span class='lever'></span><br><br>
							".(($aRow['relevance_flag'] == 'Y') ? "Relevant" : "Not Relevant")."
						</label></center>
					</div>";

				$action   = "<div class='table-actions'>";

				if( (($aRow['employ_type_flag']) == (PRIVATE_WORK)) OR (($aRow['employ_type_flag']) == (NON_DOH_GOV)) ) 
				{
					$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_work_experience_non_doh' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_work_experience_non_doh_init('".$url_view."')\"></a>";
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_work_experience_non_doh' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_work_experience_non_doh_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}

				if($module != MODULE_PERSONNEL_PORTAL) 
				{
					if( (($aRow['employ_type_flag']) != (PRIVATE_WORK)) AND (($aRow['employ_type_flag']) != (NON_DOH_GOV)) ) 
					{
						//if (EMPTY($aRow['employ_end_date']) OR IS_NULL($aRow['employ_end_date'])) 
						//{ 
							// if($permission_view)
							$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_work_experience_doh' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_work_experience_doh_init('".$url_view."')\"></a>";
							if($pds_action != ACTION_VIEW) //ncocampo disabled edit and delete buttton in view pds mode 10/13/2023
							{
							// if($permission_edit) ncocampo//10
							$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_work_experience_doh' data-tooltip='Edit' data-position='bottom'  onclick=\"modal_work_experience_doh_init('".$url_edit."')\"></a>";
							$delete_action = 'content_delete("record", "'.$url_delete.'")';
							// if($permission_delete)
							$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a><br><br><br>";
							
						} //ncocampo disabled edit and delete buttton in view pds mode 10/13/2023
						/*else
						{
							// if($permission_view)
							$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_work_experience_doh' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_work_experience_doh_init('".$url_view."')\"></a>";
							
							$delete_action = 'content_delete("record", "'.$url_delete.'")';
							// if($permission_delete)
							$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
						}*/
					}
				}				

				if($module == MODULE_PERSONNEL_PORTAL)
				{
					if( (($aRow['employ_type_flag']) != (PRIVATE_WORK)) AND (($aRow['employ_type_flag']) != (NON_DOH_GOV)) ) 
					{
						// if($permission_view)
						$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_work_experience_doh' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_work_experience_doh_init('".$url_view."')\"></a>";
					}
				}				
				
				$action .= "</div>";

				if($module == MODULE_PERSONNEL_PORTAL) 
					{
						$row[] = $action;
					}
				else{
						$row[] = $relevance;
					}
				
				$row[] 				= $action;					
				$output['aaData'][] = $row;
			endforeach;					
		}
		catch (Exception $e)
		{
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}
		echo json_encode( $output );
	}

	//LIST OF ALL PLANTILLA
	public function get_param_position() 
	{
		try 
		{			
			$flag   = 0;
			$msg    = ERROR;
			$params = get_params();
			
			$id                        = $params['select_id'];
			$date                      = $params['employ_start_date'];

			$field             	       = array("sys_param_value");
			$table                     = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'GOVT_INTERN';
			$pos_id              	   = $this->pds->get_general_data($field, $table, $where, FALSE);

			if($id == $pos_id['sys_param_value'])
			{
				/*
				$fields             	   = array("salary_grade", "salary_step");
				$table                     = $this->pds->tbl_param_positions;
				$where                     = array();
				$where['position_id'] 	   = $id;
				$data              		   = $this->pds->get_general_data($fields, $table, $where, FALSE);
				$data['amount'] 		   = '0.00';
				*/
				
				// ====================== jendaigo : start : get dynamic gip amount and include position_name in getting $data ============= //
				//GET GOVERNMENT INTERNSHIP PROGRAM AMOUNT
				$where                     = array();
				$where['sys_param_type']   = 'GOVT_INTERN_AMOUNT';
				$gip_amount                = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields             	   = array("salary_grade", "salary_step" ,"position_name");
				$table                     = $this->pds->tbl_param_positions;
				$where                     = array();
				$where['position_id'] 	   = $id;
				$data              		   = $this->pds->get_general_data($fields, $table, $where, FALSE);
				$data['amount'] 		   = number_format($gip_amount['sys_param_value'],2);;
				// ====================== jendaigo : end : get dynamic gip amount and include position_name in getting $data ============= //
			}
			else
			{
				// $select_fields             = array("max(effectivity_date) date");
				//===== marvin : start : include effectivity_date_for and employment_status =====//
				$select_fields             = array("effectivity_date", "effectivity_date_for", "employment_status");
				//===== marvin : end : include effectivity_date_for and employment_status =====//
				$tables                    = $this->pds->tbl_param_salary_schedule;
				$where                     = array();
				// $where['YEAR(effectivity_date)'] = array(date('Y', strtotime($date . '-1 year')), array("="));
				$where['YEAR(effectivity_date)'] = array(date('Y', strtotime($date)), array("="));
				$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);

				//===== marvin : start : variables for effectivity_date_for and employment_status =====//
				if(($latest_date['effectivity_date_for'] != NULL AND $latest_date['employment_status'] != NULL) AND ($latest_date['effectivity_date_for'] != 'null' AND $latest_date['employment_status'] != 'null'))
				{
					$effectivity_date_for = json_decode(urldecode($latest_date['effectivity_date_for']));
					$employment_status = json_decode(urldecode($latest_date['employment_status']));
					
					foreach($employment_status as $k => $v)
					{
						if(in_array($params['employment_status'], $v))
						{
							if(date('Y-m-d', strtotime($params['employ_start_date'])) < date('Y-m-d', strtotime($effectivity_date_for[$k])))
							{
								$where 								= array();
								$where['YEAR(effectivity_date)'] 	= array(date('Y', strtotime($date . '-1 year')), array("="));
								$new_latest_date               			= $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
								
								if(($new_latest_date['effectivity_date_for'] != NULL AND $new_latest_date['employment_status'] != NULL) AND ($new_latest_date['effectivity_date_for'] != 'null' AND $new_latest_date['employment_status'] != 'null'))
								{
									$effectivity_date_for = json_decode(urldecode($new_latest_date['effectivity_date_for']));
									$employment_status = json_decode(urldecode($new_latest_date['employment_status']));
									
									foreach($employment_status as $k => $v)
									{
										if(in_array($params['employment_status'], $v))
										{
											if(date('Y-m-d', strtotime($params['employ_start_date'])) > date('Y-m-d', strtotime($effectivity_date_for[$k])))
											{
												$latest_date['date'] = $new_latest_date['effectivity_date'];
											}
											else
											{
												$select_fields 				= array("max(effectivity_date) date");
												$tables      				= $this->pds->tbl_param_salary_schedule;
												$where                     	= array();
												$where['effectivity_date'] 	= array($latest_date['effectivity_date'], array("<="));
												$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
											}
										}
										else
										{
											$select_fields 				= array("max(effectivity_date) date");
											$tables      				= $this->pds->tbl_param_salary_schedule;
											$where                     	= array();
											$where['effectivity_date'] 	= array($date, array("<="));
											$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
										}
									}
								}
								else
								{
									$latest_date['date'] = $new_latest_date['effectivity_date'];
								}
							}
							else
							{
								$select_fields 				= array("max(effectivity_date) date");
								$tables      				= $this->pds->tbl_param_salary_schedule;
								$where                     	= array();
								$where['effectivity_date'] 	= array($latest_date['effectivity_date'], array("<="));
								$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
							}
						}
						else
						{
							$select_fields 				= array("max(effectivity_date) date");
							$tables      				= $this->pds->tbl_param_salary_schedule;
							$where                     	= array();
							$where['effectivity_date'] 	= array($date, array("<="));
							$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
						}
					}
				}
				else
				{
					$z=5;
					for ($x = 1; $x <= $z; $x++) 
					{
						$y = "-".$x." year";
						$where 								= array();
						$where['YEAR(effectivity_date)'] 	= array(date('Y', strtotime($date . $y)), array("="));
						$new_latest_date               			= $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
						if(($new_latest_date['effectivity_date_for'] == NULL OR $new_latest_date['effectivity_date_for'] == "null") AND ($new_latest_date['employment_status'] == NULL OR $new_latest_date['employment_status'] == "null"))
						{
							
							$z++;
						}
						else
						{
							break;
						}
					}
					
					if(($new_latest_date['effectivity_date_for'] != NULL AND $new_latest_date['employment_status'] != NULL) AND ($new_latest_date['effectivity_date_for'] != 'null' AND $new_latest_date['employment_status'] != 'null'))
					{
						$effectivity_date_for = json_decode(urldecode($new_latest_date['effectivity_date_for']));
						$employment_status = json_decode(urldecode($new_latest_date['employment_status']));
						
						foreach($employment_status as $k => $v)
						{
							if(in_array($params['employment_status'], $v))
							{
								// if(date('Y', strtotime($params['employ_start_date'])) == date('Y', strtotime($effectivity_date_for[$k])))
								// {
									$latest_date['date'] = $new_latest_date['effectivity_date'];
								// }
								// else
								// {
								// 	$select_fields 				= array("max(effectivity_date) date");
								// 	$tables      				= $this->pds->tbl_param_salary_schedule;
								// 	$where                     	= array();
								// 	$where['effectivity_date'] 	= array($latest_date['effectivity_date'], array("<="));
								// 	$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
								// }
							}
							else
							{
								$select_fields 				= array("max(effectivity_date) date");
								$tables      				= $this->pds->tbl_param_salary_schedule;
								$where                     	= array();
								$where['effectivity_date'] 	= array($date, array("<="));
								$latest_date               = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
							}
						}
					}
					else
					{
						$latest_date['date'] = $latest_date['effectivity_date'];
					}
				}

				//===== marvin : end : variables for effectivity_date_for and employment_status =====//
				
				$select_fields             = array("A.position_id, A.position_name, A.salary_grade, A.salary_step, B.amount");

				$tables 			= array(
					'main'			=> array(
						'table'		=> "param_positions",
						'alias'		=> 'A',
					),
					't1'			=> array(
						'table'		=> "param_salary_schedule",
						'alias'		=> 'B',
						'type'		=> 'left join',
						'condition'	=> 'A.salary_grade = B.salary_grade and A.salary_step = B.salary_step',
					)
				);

				$where                       = array();
				$where["A.position_id"]      = $id;
				$where['B.effectivity_date'] = $latest_date['date'];
				
				$data           = $this->pds->get_specific_param_salary_schedule($select_fields, $tables, $where);
				$data['amount'] = number_format($data['amount'],2);

			}
			
			$flag = 1;
			$msg  = SUCCESS;

		} 
		catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info 		= array(
			"flag"  => $flag,
			"msg" 	=> $msg,
			"data"	=> $data
		);

		echo json_encode($info);
	}

	// GET APPOINTMENT - SALARY INCREASE MONTHLY SALARY
	public function get_monthly_salary() 
	{
		try 
		{			
			$flag                      = 0;
			$msg                       = ERROR;
			$params                    = get_params();
			
			if(empty($params['select_id'])) { throw new Exception("Invalid request plantilla is required."); } 
			// if(empty($params['select_id'])) { throw new Exception("Invalid request plantilla is required."); } //ncocampo
			
			$id                        = $params['select_id'];
			$emp_id 				   = $params['id'];
			$employ_start_date 		   = $params['date'];		
			
			$data                      = $this->pds->get_employee_monthly_salary($id, $emp_id, $employ_start_date);
			
			if(empty($data)) { throw new Exception("Invalid, No data associated on selected plantilla."); }
			// if(empty($data)) { throw new Exception("Invalid, No data associated on selected plantilla."); } //ncocampo
			
			$flag = 1;
			$msg  = SUCCESS;

		} 
		catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info 		= array(
			"flag"  => $flag,
			"msg" 	=> $msg,
			"amount"=> $data['amount']
		);

		echo json_encode($info);
	}

	// CHECK EMPLOYMENT STATUS - DETAIL
	public function get_employment_status() 
	{
		try 
		{			
			$flag                       = 0;
			$msg                        = ERROR;
			$params                     = get_params();		

			$select_fields              = array("sys_param_value");
			$tables                     = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
			$where                      = array();
			$where['sys_param_value'] 	= $params['select_id'];
			$where['sys_param_type'] 	= 'EMPLOY_STATUS_DETAIL';
			$data                       = $this->pds->get_general_data($select_fields, $tables, $where, FALSE);
			
			$flag = 1;
			$msg  = SUCCESS;

		} 
		catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info 			= array(
			"flag"  	=> $flag,
			"msg" 		=> $msg,
			"status_id" => $data['sys_param_value']
		);

		echo json_encode($info);
	}

	 // GET APPOINTMENT MONTHLY SALARY
	public function get_appt_monthly_salary() 
	{
		try 
		{			
			$flag                      = 0;
			$msg                       = ERROR;
			$params                    = get_params();
			
			if(empty($params['select_id'])) { throw new Exception("Invalid request plantilla is required."); }
			
			$id                        = $params['select_id'];
			$plantilla_id 			   = $params['id'];		
			$employ_start_date 		   = $params['date'];	
			
			//marvin : include sg : start
			// if($params['id'] != 00000)
			// {
				// $data                      = $this->pds->get_employee_appt_monthly_salary($id, $plantilla_id, $employ_start_date);
			// }
			// else
			// {
				// $sg = $params['sg'];
				// $data                      = $this->pds->get_employee_appt_monthly_salary_custom($id, $employ_start_date, $sg);
			// }
			//marvin : include sg : end
			
			$data                      = $this->pds->get_employee_appt_monthly_salary($id, $plantilla_id, $employ_start_date);
			
			if(empty($data)) { throw new Exception("Invalid, No data associated on selected plantilla."); }
			
			$flag = 1;
			$msg  = SUCCESS;
		}
		catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info 		= array(
			"flag"  => $flag,
			"msg" 	=> $msg,
			"amount"=> $data['amount']
		);

		echo json_encode($info);
	}

	//CHECK PERSONNEL MOVEMENT IF STEP INC
	public function check_personnel_movement() 
	{
		try 
		{
			
			$flag   = 0;
			$msg    = ERROR;
			$params = get_params();
						
			$id     = $params['select_id'];		
			$data   = $this->pds->get_personnel_movement_step_incr($id);
						
			$flag 	= 1;
			$msg  	= SUCCESS;
		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		 = array(
			"flag"   => $flag,
			"msg" 	 => $msg,
			"movt_type"=> $data['sys_param_type']
		);

		echo json_encode($info);
	}

	//CHECK SEPARATION MOVEMENT IF TRANSFERRED
	public function get_separation_mode() 
	{
		try 
		{
			
			$flag   = 0;
			$msg    = ERROR;
			$params = get_params();
						
			$id     = $params['select_id'];
			
			$select_fields              = array("sys_param_type");
			$tables                     = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
			$where                      = array();
			$where['sys_param_value'] 	= $params['select_id'];
			$where['sys_param_type'] 	= 'MOVT_TRANSFER_OUT';
			$data                       = $this->pds->get_general_data($select_fields, $tables, $where, FALSE);

						
			$flag 	= 1;
			$msg  	= SUCCESS;

		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		= array(
			"flag"  => $flag,
			"msg" 	=> $msg,
			"mode"	=> $data['sys_param_type']
		);

		echo json_encode($info);
	}

	// GENERATE FORM 2316
	public function generate_form_2316() 
	{
		try 
		{
			
			$flag   = 0;
			$msg    = ERROR;

			$params 			= get_params();
			$employee_id_2316 	= $params['id'];
			$employee_end_date 	= $params['date'];
 
	        $tax_table_flag         	= TAX_ANNUALIZED;
	        $payout_date                = !EMPTY($employee_end_date) ? $employee_end_date : NULL;
	        $included_employee_id		= !EMPTY($employee_id_2316) ? $employee_id_2316 : NULL;
	        $included_employees     	= array($included_employee_id);
	        $save                     	= TRUE;
	        $project_tax             	= FALSE;
	        $monthly_only             	= FALSE;
	        $mwe_denominator            = 0;
	        $separated_flag             = YES;
			
			$form_2316_ids    = $this->form2316->construct_form_2316($tax_table_flag, $payout_date, NULL, NULL, $included_employees, NULL, $save, $project_tax, $monthly_only, $mwe_denominator, $separated_flag);
						
			$flag 	= 1;
			$msg  	= $this->lang->line('data_processed');

		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		 = array(
			"flag"   => $flag,
			"msg" 	 => $msg
		);

		echo json_encode($info);
	}

	public function update_relevance() 
	{
		try 
		{
			

			$params 			= get_params();
			$field                  = array("relevance_flag");
			$table                  = $this->pds->tbl_employee_work_experiences;
			$where                  = array();
			$where['employee_work_experience_id']            = $params['employee_work_experience_id'];
			$work_experience        = $this->pds->get_general_data($field, $table, $where, FALSE);
			if($work_experience['relevance_flag'] == "Y")
			{
				$fields['relevance_flag']      	   = 'N';	
			}
			else
			{
				$fields['relevance_flag']      	   = 'Y';	
			}

					
			
			$this->pds->update_general_data($table,$fields,$where);

			$flag 	= 1;
			$msg  	= $this->lang->line('data_processed');

		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		 = array(
			"flag"   => $flag,
			"msg" 	 => $msg
		);

		echo json_encode($info);
	}

	//LIST OF ALL PLANTILLA
	public function get_param_plantilla() 
	{
		try 
		{	
			$flag                      = 0;
			$msg                       = ERROR;
			$params                    = get_params();
			
			if(empty($params['select_id'])) { throw new Exception("Invalid request plantilla is required."); }
			
			$id                        = $params['select_id'];
			$emp_start_date            = !EMPTY($params['service_start_date']) ? $params['service_start_date'] : $params['employ_start_date'];
			//$date                      = date('Y-m-d');
			
			$select_fields             = array("max(effectivity_date) date");
			$tables                    = $this->pds->tbl_param_salary_schedule;
			$where                     = array();
			$where['effectivity_date'] = array($emp_start_date, array("<="));
			$latest_date               = $this->pds->get_specific_param_plantilla($select_fields, $tables, $where);

			$select_fields             = array("A.plantilla_id, B.position_id, B.position_name, B.salary_grade, B.salary_step, C.amount, E.name, D.office_id, A.division_id");

			$tables 			= array(
				'main'			=> array(
					'table'		=> "param_plantilla_items",
					'alias'		=> 'A',
				),
				't3'			=> array(
					'table'		=> "param_positions",
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.position_id = B.position_id',
				),
				't5'			=> array(
					'table'		=> "param_salary_schedule",
					'alias'		=> 'C',
					'type'		=> 'left join',
					'condition'	=> 'B.salary_grade = C.salary_grade and B.salary_step = C.salary_step',
				),
				't6'			=> array(
					'table'		=> "param_offices",
					'alias'		=> 'D',
					'type'		=> 'left join',
					'condition'	=> 'A.office_id = D.office_id',
				),
				't7'			=> array(
					'table'		=> $this->pds->db_core.'.'.$this->pds->tbl_organizations,
					'alias'		=> 'E',
					'type'		=> 'left join',
					'condition'	=> 'D.org_code = E.org_code',
				)
			);

			$where                       = array();
			$where["A.plantilla_id"]     = $id;
			$where['C.effectivity_date'] = $latest_date['date'];
			
			$data                        = $this->pds->get_specific_param_plantilla($select_fields, $tables, $where);


			$select_fields             = array("*");

			$tables 			= array(
				'main'			=> array(
					'table'		=> "param_offices",
					'alias'		=> 'A',
				),
				't3'			=> array(
					'table'		=> $this->pds->db_core.'.'.$this->pds->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);

			$where                       = array();
			$where["A.office_id"]     = $data['division_id'];
			
			$division_id                        = $this->pds->get_specific_param_plantilla($select_fields, $tables, $where);
			$data['division_name'] 				= $division_id['name'];
			
			if(empty($data)) { throw new Exception("Invalid, No data associated on selected plantilla."); }
			
			$flag = 1;
			$msg  = SUCCESS;

		} catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info = array(
			"flag"  => $flag,
			"msg" 	=> $msg,
			"data"	=> $data
		);

		echo json_encode($info);
	}

	public function modal_work_experience_non_doh($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
	{
		try
		{
			$data = array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY, JS_NUMBER);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			if($action != ACTION_ADD)
			{				
				/*GET PREVIOUS DATA OF SERVICE RECORD*/
				$field                  = array("*") ;
				$table                  = $this->pds->tbl_employee_work_experiences;
				$where                  = array();
				$key                    = $this->get_hash_key('employee_work_experience_id');
				$where[$key]            = $id;
				$work_experience        = $this->pds->get_general_data($field, $table, $where, FALSE);
				if(empty($work_experience['employ_division_id']) AND !empty($work_experience['employ_plantilla_id']))
				{
					$plantilla_division_id = $this->pds->get_plantilla_division($work_experience['employ_plantilla_id']);
					$work_experience['employ_division_id'] = $plantilla_division_id['division_id'];
				}
				$data['experience']     = $work_experience;
			
				$resources['single']			= array(
					'employment_status_non_doh' => $work_experience['employment_status_id'],
					'branch_name'         		=> $work_experience['government_branch_id'],
					'separation_mode_non_doh'   => $work_experience['separation_mode_id']
				);
			}
			//GET EMPLOYMENT TYPE 
			$field                     		   = array("*") ;
			$table                     		   = $this->pds->tbl_param_employment_status;
			$where                     		   = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		   = YES;			
			}
			else
			{
				$where['active_flag'] 		   = array(YES, array("=", "OR", "("));
		 		$where['employment_status_id'] = array($work_experience['employment_status_id'], array("=", ")"));	
		 	}
			$data['employment_status'] 		   = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//GET BRANCH 
			$field                     = array("*") ;
			$table                     = $this->pds->tbl_param_government_branches;
			$where                     = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag']  = YES;			
			}
			else
			{
				$where['active_flag']  = array(YES, array("=", "OR", "("));
		 		$where['branch_id']    = array($work_experience['government_branch_id'], array("=", ")"));	
		 	}
			$data['branch']            = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//GET SEPARATION MODES 
			$field                     		= array("*") ;
			$table                     		= $this->pds->tbl_param_separation_modes;
			$where                     		= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['separation_mode_id']= array($work_experience['separation_mode_id'], array("=", ")"));	
		 	}
			$data['separation_mode']   		= $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//marvin : include position : start
			//GET PARAM POSITIONS
			$field                    = array("*");
			$table                    = $this->pds->tbl_param_positions;
			$where                    = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] = YES;			
			}
			else
			{
				$where['active_flag'] = array(YES, array("=", "OR", "("));
		 		$where['position_id'] = array($work_experience['employ_position_id'], array("=", ")"));
		 	}
			$data['position']         = $this->pds->get_general_data($field, $table, $where, TRUE);
			//marvin : include position : end

			$this->load->view('pds/modals/modal_work_experience_non_doh', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	}
	
	/*===================================================== MARVIN : START : GET PLANTILLA FOR SUBS =====================================================*/
	public function get_plantilla_subs()
	{
		if($_POST['employment_status_id'] == 88)
		{
			$where_plantilla = " A.active_flag = 'Y'";
			$plantilla_subs  = $this->pds->get_plantilla_subs($where_plantilla);
			
			echo json_encode($plantilla_subs);			
		}
		else
		{
			//GET THE ALL EMPLOYEE PLANTILLA ID 
			$field                        = array("employ_plantilla_id") ;
			$table                        = $this->pds->tbl_employee_work_experiences;
			$where                        = array();
			$where['active_flag']		  = 'Y';
			$employment_plantilla 		  = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			/*GET PREVIOUS DATA WORK EXPERIENCE*/
			$field              = array("*") ;
			$table              = $this->pds->tbl_employee_work_experiences;
			$where              = array();
			$key                = $this->get_hash_key('employee_work_experience_id');
			$where[$key]        = $id;
			$work_experience    = $this->pds->get_general_data($field, $table, $where, FALSE);
			if(empty($work_experience['employ_division_id']) AND !empty($work_experience['employ_plantilla_id']))
				{
					$plantilla_division_id = $this->pds->get_plantilla_division($work_experience['employ_plantilla_id']);
					$work_experience['employ_division_id'] = $plantilla_division_id['division_id'];
				}
			$data['experience'] = $work_experience;
			$employ_work_experience = $work_experience['employ_plantilla_id'];
			
			$plantilla_id_arr = array();
			foreach($employment_plantilla AS $row)
			{
				if($work_experience['employ_plantilla_id'] != $row['employ_plantilla_id'])
				$plantilla_id_arr[] = $row['employ_plantilla_id'];
			}

			$where_plantilla    = '';	
			if(!EMPTY($employ_work_experience))
			{
				$where_plantilla 	= "(A.active_flag = 'Y' OR A.plantilla_id = ". $employ_work_experience . ")";
			} else
			{
				$where_plantilla = " A.active_flag = 'Y'";
			}
		 	
			// $plantilla_subs  = $this->pds->get_plantilla_list($where_plantilla, $plantilla_id_arr);
			
			/*===== marvin : start : disable $plantilla_id_arr =====*/
			$plantilla_subs  = $this->pds->get_plantilla_subs($where_plantilla);
			/*===== marvin : end : disable $plantilla_id_arr =====*/
			
			echo json_encode($plantilla_subs);
		}
	}
	/*===================================================== MARVIN : END : GET PLANTILLA FOR SUBS =====================================================*/
	
	/*===================================================== MARVIN : START : GET SALARY FOR COMP. RET. =====================================================*/
	public function get_salary_sched()
	{
		if(!empty($_POST['employ_salary_grade']) AND !empty($_POST['salary_step']) AND !empty($_POST['service_start_step']))
		{
			$select_fields 				= array('max(effectivity_date) date');
			$table 						= $this->pds->tbl_param_salary_schedule;
			$where 						= array();			
			$where['effectivity_date'] 	= array($_POST['service_start_step'], array('<='));
			$salary_date 				= $this->pds->get_specific_param_salary_schedule($select_fields, $table, $where);
			
			$field 						= array('salary_grade, salary_step, amount');
			$table 						= $this->pds->tbl_param_salary_schedule;
			$where 						= array();
			$where['salary_grade'] 		= $_POST['action'] != 3 ? ++$_POST['employ_salary_grade'] : $_POST['employ_salary_grade'];
			$where['salary_step'] 		= $_POST['salary_step'];
			$where['effectivity_date'] 	= $salary_date['date'];
			$amount 					= $this->pds->get_general_data($field, $table, $where);
			
			echo json_encode(array(
				'message' => $amount,
				'status' => 1)
			);		
		}
		else
		{
			echo json_encode(array(
				'message' => '<b>Service Start</b> must not be empty!',
				'status' => 0)
			);
		}
	}
	/*===================================================== MARVIN : END : GET SALARY FOR COMP. RET. =====================================================*/
	
	/*===================================== MARVIN : START : GET STEP INCREMENT REASON =======================================*/
	public function get_step_increment_reason()
	{
		if(!EMPTY($_POST['service_start_step']))
		{
			//GET THE ALL STEP INCR REASON 
			$field                        	= array("sys_param_value", "sys_param_name") ;
			$table                        	= $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
			$where                        	= array();
			$where['sys_param_type']	  	= SALARY_INCR_CAUSE;
			$increment_reason				= $this->pds->get_general_data($field, $table, $where, TRUE);
			
			if(date(format_date($_POST['service_start_step'], 'Y')) <= 2015)
			{
				$increment_reason[] = array(
					'sys_param_value' => 'LS',
					'sys_param_name' => 'LENGTH OF SERVICE'
				);
			}		
			
			echo json_encode(array(
				'message' => $increment_reason,
				'status' => 1
			));			
		}
		else
		{
			echo json_encode(array(
				'message' => '<b>Service Start</b> must not be empty!',
				'status' => 0
			));
		}
	}
	/*===================================== MARVIN : END : GET STEP INCREMENT REASON =======================================*/

	public function modal_work_experience_doh($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL, $employee_id=NULL)
	{
		try
		{
			$data 					= array();
			
			$resources 				= array();
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			//GET THE ALL EMPLOYEE PLANTILLA ID 
			$field                        = array("employ_plantilla_id") ;
			$table                        = $this->pds->tbl_employee_work_experiences;
			$where                        = array();
			$where['active_flag']		  = 'Y';
			$employment_plantilla 		  = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//GET THE ALL STEP INCR REASON 
			$field                        = array("sys_param_value", "sys_param_name") ;
			$table                        = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
			$where                        = array();
			$where['sys_param_type']	  = SALARY_INCR_CAUSE;
			$data['incr_reason']	  	  = $this->pds->get_general_data($field, $table, $where, TRUE);			
			
			//GET PARAM PLANTILLA
			$tables 			= array(
				'main'			=> array(
					'table'		=> "param_plantilla_items",
					'alias'		=> 'A',
				),
				't1'			=> array(
					'table'		=> "param_positions",
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.position_id = B.position_id',
					)
			);
			
			//GET ALL DATA FROM EMPLOYEE WORK EXPERIENCE WHERE ACTIVE PLANTILLA
			$field                    = array("*");
			$table                    = $this->pds->tbl_employee_work_experiences;
			$where                    = array();
			$where['active_flag']     = "Y";
			$key                      = $this->get_hash_key('employee_id');
			$where[$key]              = $employee_id;
			$work_experience          = $this->pds->get_general_data($field, $table, $where, FALSE);
			if(empty($work_experience['employ_division_id']) AND !empty($work_experience['employ_plantilla_id']))
				{
					$plantilla_division_id = $this->pds->get_plantilla_division($work_experience['employ_plantilla_id']);
					$work_experience['employ_division_id'] = $plantilla_division_id['division_id'];
				}
			$data['employee_id']	  = $employee_id;
			$data['active_plantilla'] = $work_experience;
			$employ_work_experience   = $work_experience['employ_plantilla_id'];
			
			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA WORK EXPERIENCE*/
				$field              = array("*") ;
				
				$tables 			= array(
					'main'			=> array(
						'table'		=> "employee_work_experiences",
						'alias'		=> 'A',
					),
					't1'			=> array(
						'table'		=> "employee_work_experience_details",
						'alias'		=> 'B',
						'type'		=> 'left join',
						'condition'	=> 'A.employee_work_experience_id = B.employee_work_experience_id',
						)
					);
					//ncocampo 01/25/2024
					
				$where              = array();
				$key                = $this->get_hash_key('A.employee_work_experience_id');
				$where[$key]        = $id;
				$work_experience    = $this->pds->get_general_data($field, $tables, $where, FALSE);
				if(empty($work_experience['employ_division_id']) AND !empty($work_experience['employ_plantilla_id']))
				{
					$plantilla_division_id = $this->pds->get_plantilla_division($work_experience['employ_plantilla_id']);
					$work_experience['employ_division_id'] = $plantilla_division_id['division_id'];
				}
				$data['experience'] = $work_experience;

				$employ_work_experience = $work_experience['employ_plantilla_id'];	
				
				$resources['single']	   = array(
					'employment_status_id' => $work_experience['employment_status_id'],
					'position_id'          => $work_experience['employ_position_id'],
					'plantilla_id'         => $work_experience['employ_plantilla_id'],
					'office_id'            => $work_experience['employ_office_id'],
					'personnel_movement'   => $work_experience['employ_personnel_movement_id'],
					'separation_mode'      => $work_experience['separation_mode_id'],
					'step_incr_reason_code'=> $work_experience['step_incr_reason_code']	,
					'branch_id'			   => $work_experience['government_branch_id']					
				);
			}

			//GET EMPLOYMENT TYPE 
			$field                     		   = array("*") ;
			$table                     		   = $this->pds->tbl_param_employment_status;
			$where                     		   = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		   = YES;			
			}
			else
			{
				$where['active_flag'] 		   = array(YES, array("=", "OR", "("));
		 		$where['employment_status_id'] = array($work_experience['employment_status_id'], array("=", ")"));	
		 	}
			$data['employment_status'] 		   = $this->pds->get_general_data($field, $table, $where, TRUE);

			//GET EMPLOYEE BDAY
			$pds_employee_id = $this->session->userdata("pds_employee_id");
			
			$field           = array("birth_date");
			$table           = $this->pds->tbl_employee_personal_info;
			$where           = array();
			$key             = $this->get_hash_key('employee_id');
			$where[$key]     = $pds_employee_id;
			$data['personal_info']   = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			//GET SEPARATION MODES 
			$field                     		= array("*") ;
			$table                     		= $this->pds->tbl_param_separation_modes;
			$where                     		= array();
			// if($action == ACTION_ADD)
			if($action == ACTION_ADD || $action == ACTION_EDIT ) //NCOCAMPO 10/16/2023
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['separation_mode_id']= array($work_experience['separation_mode_id'], array("=", ")"));	
		 	}
			$data['separation_mode']   		= $this->pds->get_general_data($field, $table, $where, TRUE);

			//GET PARAM POSITIONS
			$field                    = array("*");
			$table                    = $this->pds->tbl_param_positions;
			$where                    = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] = YES;			
			}
			else
			{
				$where['active_flag'] = array(YES, array("=", "OR", "("));
		 		$where['position_id'] = array($work_experience['employ_position_id'], array("=", ")"));
		 	}
			$data['position']         = $this->pds->get_general_data($field, $table, $where, TRUE);
						
			//GET PARAM PERSONNEL MOVEMENT
			$field                        	   = array("*");
			$table                        	   = $this->pds->tbl_param_personnel_movements;
			$where                     		   = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		   = YES;			
			}
			else
			{
				$where['active_flag'] 		   = array(YES, array("=", "OR", "("));
		 		$where['personnel_movement_id']= array($work_experience['employ_personnel_movement_id'], array("=", ")"));	
		 	}
			$data['personnel_movement']   	   = $this->pds->get_general_data($field, $table, $where, TRUE, array('personnel_movement_name' => 'ASC'));

			//GET BRANCH 
			$field                     = array("*") ;
			$table                     = $this->pds->tbl_param_government_branches;
			$where                     = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag']  = YES;			
			}
			else
			{
				$where['active_flag']  = array(YES, array("=", "OR", "("));
		 		$where['branch_id']    = array($work_experience['government_branch_id'], array("=", ")"));	
		 	}
			$data['branch']            = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//GET PARAM OFFICE
			// $where                        	   = '';
			// if($action == ACTION_ADD)
			// {
			// 	$where 		   = "A.active_flag = 'Y'";			
			// }
			// else
			// {$data
			// 	if(!EMPTY($work_experience['employ_office_id']))
			// 	$where 		= "(A.active_flag = 'Y' OR A.office_id = ".$work_experience['employ_office_id']. ")";
		 // 	}
			$where           = array();
			$data['office']  = $this->pds->get_office_list($field, $table, $where, TRUE);


			$plantilla_id_arr = array();
			foreach($employment_plantilla AS $row)
			{
				if($work_experience['employ_plantilla_id'] != $row['employ_plantilla_id'])
				$plantilla_id_arr[] = $row['employ_plantilla_id'];
			}

			$where_plantilla    = '';	
			if(!EMPTY($employ_work_experience))
			{
				$where_plantilla 	= "(A.active_flag = 'Y' OR A.plantilla_id = ". $employ_work_experience . ")";
			} else
			{
				$where_plantilla = " A.active_flag = 'Y'";
			}
		 	
			$data['plantilla']  = $this->pds->get_plantilla_list($where_plantilla, $plantilla_id_arr);

			$this->load->view('pds/modals/modal_work_experience_doh', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	}

	/*PROCESS WORK EXPERIENCE NON DOH*/
	public function process_work_experience_non_doh()
	{
		try
		{
			
			$status     = FALSE;
			$message    = "";
			$reload_url = "";
			
			$params     = get_params();
			$action     = $params['action'];
			$token      = $params['token'];
			$salt       = $params['salt'];
			$id         = $params['id'];
			$module     = $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_work_experience_non_doh($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id = $this->session->userdata("pds_employee_id");

			if($action == ACTION_ADD)
			{
				$work_id 	= '';
			} else
			{
				$work_id = $id;
			}

			$start_date = $valid_data['employ_start_date_non_doh'];
			$end_date  	= $valid_data['employ_end_date_non_doh'];
		
			// TEST DATE OVERLAP
			$test_date = array();
			$test_date = $this->pds->check_date_overlap($start_date, $end_date, $work_id);

			if($test_date)
			{
				throw new Exception($this->lang->line('overlapped_date'));				
			}
			
			/*GET EMPLOYEE*/
			$field           = array("*");
			$table           = $this->pds->tbl_employee_personal_info;
			$where           = array();
			$key             = $this->get_hash_key('employee_id');
			$where[$key]     = $pds_employee_id;
			$personal_info   = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields          = array();

			if($params['govt_service_flag'] == ACTIVE OR $params['employ_type_flag'] == NON_DOH_GOV) 
			{
				//OUTSIDE DOH GOVERNMENT SERVICE
				$employ_type_flag               = NON_DOH_GOV;
				$fields['employ_salary_grade']  = $valid_data['employ_salary_grade_non_doh'];
				$fields['employ_salary_step']   = $valid_data['employ_salary_step_non_doh'];
				$fields['service_lwop']         =!EMPTY($valid_data['leaves']) ? $valid_data['leaves'] : 0;
				$fields['government_branch_id'] = $valid_data["branch_name"];
				
				//marvin : start : remove required
				// $fields['separation_mode_id']   = $valid_data["separation_mode_non_doh"];
				$fields['relevance_flag']		= isset($valid_data['relevance_flag']) ? "Y" : "N";
				$fields['govt_service_flag']    = 'Y';
			}
			else 
			{
				//PRIVATE COMPANY
				$employ_type_flag            = PRIVATE_WORK;
				$fields['govt_service_flag'] = 'N';
			}

			$fields['employ_start_date']     = $valid_data["employ_start_date_non_doh"];
			$fields['employ_end_date']       = $valid_data["employ_end_date_non_doh"];
			$fields['employ_position_name']  = $valid_data["employ_position"];
			$fields['employ_office_name']    = $valid_data["employ_company_name"];
			$fields['employ_monthly_salary'] = $valid_data["employ_monthly_salary_non_doh"];
			$fields['employment_status_id']  = $valid_data["employment_status_non_doh"];
			$fields['employ_type_flag']      = $employ_type_flag;
			$fields['remarks']  			 = !EMPTY($valid_data['remarks']) ? $valid_data['remarks'] : ' ';

			if(strtotime($params['employ_start_date_non_doh']) > strtotime($params['employ_end_date_non_doh']))
			{
				throw new Exception($this->lang->line('date_range'));
			}

			if($action == ACTION_ADD)
			{	
				$fields['employee_id']       = $personal_info["employee_id"];
				$table                       = $this->pds->tbl_employee_work_experiences;
				$employee_work_experience_id = $this->pds->insert_general_data($table,$fields,TRUE);
				
				// if(!EMPTY($params['govt_service_flag'])) :
				// 	//$fields['employee_work_experience_id'] = $employee_work_experience_id['employee_work_experience_id'];
				// 	$table                                 = $this->pds->tbl_employee_work_experiences;
				// 	$employee_work_experiences             = $this->pds->insert_general_data($table,$fields,TRUE);
				// endif;

				$audit_table[]               = $this->pds->tbl_employee_work_experiences;
				$audit_schema[]              = DB_MAIN;
				$prev_detail[]               = array();
				$curr_detail[]               = array($fields);
				$audit_action[]              = AUDIT_INSERT;	
				
				$activity                    = "New work experience with the position %s has been added.";
				$audit_activity              = sprintf($activity, $valid_data["employ_position"]);
				
				$status                      = true;
				$message                     = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field                        = array("*") ;
				$activity                     = "New work experience with the position %s has been added.";
				$table                        = $this->pds->tbl_employee_work_experiences;
				$where                        = array();
				$key                          = $this->get_hash_key('employee_work_experience_id');
				$where[$key]                  = $id;
				$work_experience              = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				// $fields['last_modified_by']   = $this->log_user_id;
				// $fields['last_modified_date'] = date("Y-m-d H:i:s");

				$where						= array();
				$key 						= $this->get_hash_key('employee_work_experience_id');
				$where[$key]				= $id;
				$table 						= $this->pds->tbl_employee_work_experiences;
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->pds->tbl_employee_work_experiences;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($work_experience);
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$activity 				= "Work experience with the position %s has been updated.";
				$audit_activity 		= sprintf($activity, $work_experience["employ_position"]);

				$status = true;
				$message = $this->lang->line('data_updated');
			}
			
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	/*PROCESS WORK EXPERIENCE INSIDE DOH*/
	public function process_work_experience_doh()
	{
		try
		{			
			$status     = FALSE;
			$message    = "";
			$reload_url = "";

			$params     = get_params();

			$action     = $params['action'];
			$token      = $params['token'];
			$salt       = $params['salt'];
			$id         = $params['id'];
			$module     = $params['module'];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_work_experience_doh($params);
			
			//marvin : set plantilla to NULL if selected plantilla is "-" : start
			if($valid_data['plantilla_id'] == 00000)
			{
				$valid_data['plantilla_id'] = null;
			}
			//marvin : set plantilla to NULL if selected plantilla is "-" : end
			
			Main_Model::beginTransaction();

			$pds_employee_id = $this->session->userdata("pds_employee_id");

			//ASIA TIMEZONE 
			date_default_timezone_set("Asia/Manila");

			if($action == ACTION_ADD)
			{
				$work_id 	= '';
			} else
			{
				$work_id = $id;
			}
	
			if(EMPTY($params['plantilla_flag']) OR $params['employ_type_flag'] == DOH_JO)
			{
				$start_date = $valid_data['employ_start_date'];
				$end_date  	= $valid_data['employ_end_date'];
			}
			if(((!EMPTY($params['plantilla_flag'])) AND (!EMPTY($params['appointment_flag']))) OR $params['employ_type_flag'] == DOH_GOV_APPT)
			{
				$start_date = $valid_data['employ_start_date'];
				$end_date  	= $valid_data['end_date'];
			}
			if(((!EMPTY($params['plantilla_flag'])) AND (EMPTY($params['appointment_flag']))) OR $params['employ_type_flag'] == DOH_GOV_NON_APPT)
			{
				$start_date = $valid_data['service_start_step'];
				$end_date  	= $valid_data['end_date'];
			}

			if($end_date)
			{
				if(strtotime($start_date) > strtotime($end_date))
				{
					throw new Exception($this->lang->line('date_range'));
				}
			}
			else
			{			
				//TEST START DATE IF EARLIER THAN ACTIVE WORK EXPERIENCE
				$test_start_date = $this->pds->get_max_start_date($id);
				if(strtotime($test_start_date['max_start_date']) > strtotime($start_date))
				{
					throw new Exception($this->lang->line('invalid_start_date'));
				}

			}

			// TEST DATE OVERLAP
			$test_date = array();
			$test_date = $this->pds->check_date_overlap($start_date, $end_date, $work_id);

			if($test_date)
			{
				throw new Exception($this->lang->line('overlapped_date'));
			}

			/*GET EMPLOYEE*/
			$field           = array("*");
			$table           = $this->pds->tbl_employee_personal_info;
			$where           = array();
			$key             = $this->get_hash_key('employee_id');
			$where[$key]     = $pds_employee_id;
			$personal_info   = $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields          = array();
			$audit_fields    = array();

			if(EMPTY($params['plantilla_flag']) OR $params['employ_type_flag'] == DOH_JO)
			{
				$fields['employ_start_date']     = $valid_data["employ_start_date"];
				$fields['employ_end_date']       = !EMPTY($valid_data["employ_end_date"]) ? $valid_data["employ_end_date"] : NULL;
				$fields['employ_position_id']    = $valid_data["position_id"];
				$fields['employ_office_id']      = $valid_data["office_id"];
				$fields['admin_office_id']       = $valid_data["office_id"];
				$fields['employ_monthly_salary'] = $valid_data["employ_monthly_salary"];
				$fields['employ_salary_grade']   = $valid_data['salary_grade'];
				$fields['employ_salary_step']    = $valid_data['salary_step_info'];
				$fields['employment_status_id']  = $valid_data["employment_status_id"];
				// ncocampo ADDED separation cause for JO's - START
				$fields['separation_mode_id']    = !EMPTY($valid_data["separation_mode"]) ? $valid_data["separation_mode"] : NULL;
				// ncocampo ADDED separation cause for JO's - END
				$fields['government_branch_id']  = BRANCH_NATIONAL;
				$fields['govt_service_flag']     = 'N';
				$fields['employ_type_flag']      = DOH_JO;
				$fields['active_flag']           = (EMPTY($valid_data["employ_end_date"]) OR IS_NULL($valid_data["employ_end_date"])) ? YES:NO;
				$fields['relevance_flag']		 = isset($valid_data['relevance_flag']) ? "Y" : "N"; //ncocampo 02/23/2024

				$audit_fields['employ_start_date']     = $valid_data["employ_start_date"];
				$audit_fields['employ_end_date']       = !EMPTY($valid_data["employ_end_date"]) ? $valid_data["employ_end_date"] : ' ';
				$audit_fields['employ_position_id']    = $valid_data["position_id"];
				$audit_fields['employ_office_id']      = $valid_data["office_id"];
				$audit_fields['admin_office_id']       = $valid_data["office_id"];
				$audit_fields['employ_monthly_salary'] = $valid_data["employ_monthly_salary"];
				//ncocampo
				$audit_fields['separation_mode_id']    = !EMPTY($valid_data["separation_mode"]) ? $valid_data["separation_mode"] : ' ';
				//ncocampo
				$audit_fields['employ_salary_grade']   = $valid_data['salary_grade'];
				$audit_fields['employ_salary_step']    = $valid_data['salary_step_info'];
				$audit_fields['employment_status_id']  = $valid_data["employment_status_id"];
				$audit_fields['government_branch_id']  = BRANCH_NATIONAL;
				$audit_fields['govt_service_flag']     = 'N';
				$audit_fields['employ_type_flag']      = DOH_JO;
				$audit_fields['active_flag']           = (EMPTY($valid_data["employ_end_date"]) OR IS_NULL($valid_data["employ_end_date"])) ? YES:NO;
				$audit_fields['relevance_flag']		   = isset($valid_data['relevance_flag']) ? "Y" : "N";

			}

			if(  ((!EMPTY($params['plantilla_flag'])) AND (!EMPTY($params['appointment_flag']))) OR $params['employ_type_flag'] == DOH_GOV_APPT) 
			{
				$fields['employ_personnel_movement_id'] = $valid_data["personnel_movement"];
				$fields['step_incr_reason_code'] 		= !EMPTY($valid_data["step_incr_reason_code"]) ? $valid_data["step_incr_reason_code"] : NULL;
				$fields['employ_start_date']            = $valid_data["employ_start_date"];
				$fields['employ_plantilla_id']          = !EMPTY($valid_data["plantilla_id"]) ? $valid_data["plantilla_id"] : NULL;
				$fields['employ_office_id']             = ($action == ACTION_ADD) ? $valid_data["office"] : $valid_data["office_id"];
				$fields['admin_office_id']       		= !EMPTY($valid_data["admin_office_id"]) ? $valid_data["admin_office_id"] : $valid_data["office"];
				$fields['employ_position_id']           = $valid_data["position"];
				$fields['employment_status_id']         = $valid_data["employment_status_id"];
				$fields['employ_salary_grade']          = $valid_data['salary_grade'];
				$fields['employ_salary_step']           = $valid_data['salary_step_info'];
				$fields['employ_monthly_salary']        = $valid_data["employ_monthly_salary"];
				$fields['separation_mode_id']           = !EMPTY($valid_data["separation_mode"]) ? $valid_data["separation_mode"] : NULL;
				$fields['transfer_to']           		= !EMPTY($valid_data["transfer_to"]) ? $valid_data["transfer_to"] : NULL;
				$transfer_flag_val 						= isset($valid_data['transfer_flag']) ? TRANSFER_IN : TRANSFER_OUT;
				$fields['transfer_flag']           		= !EMPTY($valid_data["transfer_to"]) ? $transfer_flag_val : NULL;
				$fields['employ_end_date']          	= !EMPTY($valid_data["end_date"]) ? $valid_data["end_date"] : NULL;
				$fields['government_branch_id']         = BRANCH_NATIONAL; 	
				$fields['govt_service_flag']            = 'Y';
				$fields['active_flag']                  = (EMPTY($valid_data["end_date"]) OR IS_NULL($valid_data["end_date"])) ? YES:NO;
				$fields['employ_type_flag']             = DOH_GOV_APPT;
				$fields['relevance_flag']				= isset($valid_data['relevance_flag']) ? "Y" : "N";

				$audit_fields['employ_personnel_movement_id'] = $valid_data["personnel_movement"];
				$audit_fields['step_incr_reason_code'] 		  = !EMPTY($valid_data["step_incr_reason_code"]) ? $valid_data["step_incr_reason_code"] : ' ';
				$audit_fields['employ_start_date']            = $valid_data["employ_start_date"];
				$audit_fields['employ_plantilla_id']          = !EMPTY($valid_data["plantilla_id"]) ? $valid_data["plantilla_id"] : NULL;
				$audit_fields['employ_office_id']             = ($action == ACTION_ADD) ? $valid_data["office"] : $valid_data["office_id"];
				$audit_fields['admin_office_id']       		  = !EMPTY($valid_data["admin_office_id"]) ? $valid_data["admin_office_id"] : $valid_data["office"];
				$audit_fields['employ_position_id']           = $valid_data["position"];
				$audit_fields['employment_status_id']         = $valid_data["employment_status_id"];
				$audit_fields['employ_salary_grade']          = $valid_data['salary_grade'];
				$audit_fields['employ_salary_step']           = $valid_data['salary_step_info'];
				$audit_fields['employ_monthly_salary']        = $valid_data["employ_monthly_salary"];
				$audit_fields['separation_mode_id']           = !EMPTY($valid_data["separation_mode"]) ? $valid_data["separation_mode"] : ' ';
				$audit_fields['employ_end_date']          	  = !EMPTY($valid_data["end_date"]) ? $valid_data["end_date"] : ' ';
				$audit_fields['government_branch_id']         = BRANCH_NATIONAL;
				$audit_fields['govt_service_flag']            = 'Y';
				$audit_fields['active_flag']                  = (EMPTY($valid_data["end_date"]) OR IS_NULL($valid_data["end_date"])) ? YES:NO;
				$audit_fields['employ_type_flag']             = DOH_GOV_APPT;
				$audit_fields['transfer_to']           		  = !EMPTY($valid_data["transfer_to"]) ? $valid_data["transfer_to"] : ' ';
				$audit_fields['transfer_flag']           	  = !EMPTY($valid_data["transfer_to"]) ? $transfer_flag_val : NULL;	
				$audit_fields['relevance_flag']				  = isset($valid_data['relevance_flag']) ? "Y" : "N";
			}

			if( ((!EMPTY($params['plantilla_flag'])) AND (EMPTY($params['appointment_flag']))) OR $params['employ_type_flag'] == DOH_GOV_NON_APPT)
			{
				$fields['employ_personnel_movement_id'] = $valid_data["personnel_movement"];
				$fields['step_incr_reason_code'] 		= !EMPTY($valid_data["step_incr_reason_code"]) ? $valid_data["step_incr_reason_code"] : NULL;
				$fields['employ_start_date']            = $valid_data["service_start_step"];
				$fields['employ_salary_step']           = $valid_data['salary_step'];
				$fields['employ_plantilla_id']          = !EMPTY($valid_data["plantilla_id"]) ? $valid_data["plantilla_id"] : NULL;
				$fields['employ_office_id']             = $valid_data["office_id"];
				$fields['admin_office_id']       		= !EMPTY($valid_data["admin_office_id"]) ? $valid_data["admin_office_id"] : $valid_data["office_id"];
				$fields['employ_position_id']           = $valid_data["position_id"];
				$fields['employment_status_id']         = $valid_data["employment_status_id"];
				$fields['employ_salary_grade']          = $valid_data['employ_salary_grade'];
				$fields['employ_monthly_salary']        = $valid_data["employ_monthly_salary_non"];
				$fields['separation_mode_id']           = !EMPTY($valid_data["separation_mode"]) ? $valid_data["separation_mode"] : NULL;
				$fields['employ_end_date']          	= !EMPTY($valid_data["end_date"]) ? $valid_data["end_date"] : NULL;
				$fields['government_branch_id']         = BRANCH_NATIONAL;
				$fields['govt_service_flag']            = 'Y';
				$fields['active_flag']                  = (EMPTY($valid_data["end_date"]) OR IS_NULL($valid_data["end_date"])) ? YES:NO;
				$fields['employ_type_flag']             = DOH_GOV_NON_APPT;
				$fields['transfer_to']           		= !EMPTY($valid_data["transfer_to"]) ? $valid_data["transfer_to"] : NULL;
				$transfer_flag_val 						= isset($valid_data['transfer_flag']) ? TRANSFER_IN : TRANSFER_OUT;
				$fields['transfer_flag']           		= !EMPTY($valid_data["transfer_to"]) ? $transfer_flag_val : NULL;
				$fields['relevance_flag']				= isset($valid_data['relevance_flag']) ? "Y" : "N";

				$audit_fields['employ_personnel_movement_id'] = $valid_data["personnel_movement"];
				$audit_fields['step_incr_reason_code'] 		  = !EMPTY($valid_data["step_incr_reason_code"]) ? $valid_data["step_incr_reason_code"] : ' ';
				$audit_fields['employ_start_date']            = $valid_data["service_start_step"];
				$audit_fields['employ_salary_step']           = $valid_data['salary_step'];
				$audit_fields['employ_plantilla_id']          = $valid_data["plantilla_id"];
				$audit_fields['employ_office_id']             = $valid_data["office_id"];
				$audit_fields['admin_office_id']       		  = !EMPTY($valid_data["admin_office_id"]) ? $valid_data["admin_office_id"] : $valid_data["office_id"];
				$audit_fields['employ_position_id']           = $valid_data["position_id"];
				$audit_fields['employment_status_id']         = $valid_data["employment_status_id"];
				$audit_fields['employ_salary_grade']          = $valid_data['employ_salary_grade'];
				$audit_fields['employ_monthly_salary']        = $valid_data["employ_monthly_salary_non"];
				$audit_fields['separation_mode_id']           = !EMPTY($valid_data["separation_mode"]) ? $valid_data["separation_mode"] : ' ';
				$audit_fields['employ_end_date']          	  = !EMPTY($valid_data["end_date"]) ? $valid_data["end_date"] : ' ';
				$audit_fields['government_branch_id']         = BRANCH_NATIONAL;
				$audit_fields['govt_service_flag']            = 'Y';
				$audit_fields['active_flag']                  = (EMPTY($valid_data["end_date"]) OR IS_NULL($valid_data["end_date"])) ? YES:NO;
				$audit_fields['employ_type_flag']             = DOH_GOV_NON_APPT;
				$audit_fields['transfer_to']           		  = !EMPTY($valid_data["transfer_to"]) ? $valid_data["transfer_to"] : ' ';
				$audit_fields['transfer_flag']           	  = !EMPTY($valid_data["transfer_to"]) ? $transfer_flag_val : NULL;
				$audit_fields['relevance_flag']				  = isset($valid_data['relevance_flag']) ? "Y" : "N";	
			}

			// if(EMPTY($params['publication_place']))
			// {
			// 	throw new Exception('<b>Publication Place</b>' . $this->lang->line('not_applicable'));
			// }

			$fields['publication_date']   		= !EMPTY($valid_data['publication_date']) ? $valid_data['publication_date'] : NULL;
			$fields['publication_date_to']   		= !EMPTY($valid_data['publication_date_to']) ? $valid_data['publication_date_to'] : NULL;
			$fields['publication_place']  		= !EMPTY($valid_data['publication_place']) ? $valid_data['publication_place'] : NULL;
			$fields['deliberation_date']   		= !EMPTY($valid_data['deliberation_date']) ? $valid_data['deliberation_date'] : NULL;
			$fields['posted_in']  		= !EMPTY($valid_data['posted_in']) ? $valid_data['posted_in'] : NULL;
			$fields['government_branch_id']  	= !EMPTY($valid_data['branch_id']) ? $valid_data['branch_id'] : NULL;
			$fields['remarks']  				= !EMPTY($valid_data['remarks']) ? $valid_data['remarks'] : NULL;
			$fields['relevance_flag']				  = isset($valid_data['relevance_flag']) ? "Y" : "N";	

			$audit_fields['publication_date']   = !EMPTY($valid_data['publication_date']) ? $valid_data['publication_date'] : ' ';
			$audit_fields['publication_date_to']   = !EMPTY($valid_data['publication_date_to']) ? $valid_data['publication_date_to'] : ' ';
			$audit_fields['publication_place']  = !EMPTY($valid_data['publication_place']) ? $valid_data['publication_place'] : ' ';
			$audit_fields['deliberation_date']   = !EMPTY($valid_data['deliberation_date']) ? $valid_data['deliberation_date'] : ' ';
			$audit_fields['posted_in']  = !EMPTY($valid_data['posted_in']) ? $valid_data['posted_in'] : ' ';
			$faudit_fieldsields['government_branch_id'] = !EMPTY($valid_data['branch_id']) ? $valid_data['branch_id'] : ' ';
			$audit_fields['remarks']  			= !EMPTY($valid_data['remarks']) ? $valid_data['remarks'] : ' ';
			$audit_fields['relevance_flag']  			= !EMPTY($valid_data['relevance_flag']) ? $valid_data['relevance_flag'] : ' ';

			/*GET POSITION NAME*/
			$field                        = array('position_name') ;
			$table                        = $this->pds->tbl_param_positions;
			$where                        = array();
			// $where['position_id']         = !EMPTY($params['position_id']) ? $params['position_id'] : $params['position'];
			$where['position_id']         = !EMPTY($params['position_id']) ? $params['position_id'] : $valid_data['position'];
			$emp_position             	  = $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields['employ_position_name'] 	  = !EMPTY($emp_position['position_name']) ? $emp_position['position_name'] : NULL;
			$audit_fields['employ_position_name'] = !EMPTY($emp_position['position_name']) ? $emp_position['position_name'] : ' ';

			/*GET OFFICE NAME*/
			$table 				= array(
				'main'  		=> array(
					'table' 	=> 'param_offices',
					'alias' 	=> 'A',
				),
				't2'    		=> array(
					'table' 	=> $this->pds->db_core.'.'.$this->pds->tbl_organizations,
					'alias' 	=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'A.org_code = B.org_code'
				)
			);

			$field                        = array('name');
			$where                        = array();
			// $where['office_id']           = !EMPTY($params['office_id']) ? $params['office_id'] : $params['office'];
			$where['office_id']           = !EMPTY($params['office_id']) ? $params['office_id'] : $valid_data['office'];
			$emp_office             	  = $this->pds->get_general_data($field, $table, $where, FALSE);

			if($params['admin_office_id'])
			{
				$where                    = array();
				$where['office_id']       = $params['admin_office_id'];
				$admin_office             = $this->pds->get_general_data($field, $table, $where, FALSE);

				$fields['admin_office_name'] 	   = !EMPTY($admin_office['name']) ? $admin_office['name'] : NULL;
				$audit_fields['admin_office_name'] = !EMPTY($admin_office['name']) ? $admin_office['name'] : ' ';
			}
			else
			{				
				$fields['admin_office_name'] 	   = !EMPTY($emp_office['name']) ? $emp_office['name'] : NULL;
				$audit_fields['admin_office_name'] = !EMPTY($emp_office['name']) ? $emp_office['name'] : ' ';
			}

			$fields['employ_office_name'] 	  	= !EMPTY($emp_office['name']) ? $emp_office['name'] : NULL;
			$audit_fields['employ_office_name'] = !EMPTY($emp_office['name']) ? $emp_office['name'] : ' ';

			

			/*---------------- START INSERT TO EMPLOYEE COMPENSATION TABLE ----------------*/
			$field_com          			= array("compensation_id");
			$table_com         				= $this->pds->tbl_param_compensations;
			$where              			= array();
			$where['basic_salary_flag']     = YES;
			
			// ====================== jendaigo : start : include employemnt_flag in the condition ============= //
			$employ_type_flags				= array(PAYROLL_TYPE_FLAG_ALL, $fields['employ_type_flag']);
			$where['employ_type_flag']		= array($employ_type_flags, array('IN'));
			// ====================== jendaigo : end : include employemnt_flag in the condition ============= //
			
			$basic_compensation  			= $this->pds->get_general_data($field_com, $table_com, $where, TRUE);

			$field_emp_com          		= array("*");
			$table_emp_com         			= $this->pds->tbl_employee_compensations;
			$where              			= array();
			$key                            = $this->get_hash_key('employee_id');
			$where[$key]                    = $pds_employee_id;
			$where['end_date']              = 'IS NULL';
			$employee_compensation  		= $this->pds->get_general_data($field_emp_com, $table_emp_com, $where, TRUE);

			if(EMPTY($employee_compensation))
			{
				foreach($basic_compensation as $compensation) 
				{
					$emp_com_fields['compensation_id'] = $compensation['compensation_id'];
					$emp_com_fields['employee_id'] 	   = $personal_info["employee_id"];
					$emp_com_fields['start_date'] 	   = !EMPTY($valid_data["employ_start_date"]) ? $valid_data["employ_start_date"] : $valid_data["service_start_step"];
					$this->pds->insert_general_data($table_emp_com, $emp_com_fields);
				}
			}

			$compensation_end_date = !EMPTY($valid_data['employ_end_date']) ? $valid_data['employ_end_date'] : $valid_data['end_date'];
			if((!EMPTY($employee_compensation)) AND !EMPTY($compensation_end_date) AND !EMPTY($valid_data['separation_mode']))
			{
				$where = array();
				foreach($employee_compensation as $emp_compensation) 
				{
					$key                            	= $this->get_hash_key('employee_id');
					$where[$key]                    	= $pds_employee_id;
					$emp_com_end_fields['end_date'] 	= $compensation_end_date;
					$this->pds->update_general_data($table_emp_com, $emp_com_end_fields, $where);
				}
			}
			/*---------------- END INSERT TO EMPLOYEE COMPENSATION TABLE ----------------*/

			if($action == ACTION_ADD)
			{
				/*---------------- START INSERT TO EMPLOYEE DEDUCTION TABLE ----------------*/
				if(EMPTY($params['prev_record']))
				{		
					// $fields['active_flag'] 	  	 = YES;
					// $audit_fields['active_flag'] = YES;

					$deduc_fields                 = array();
					$table_deduc                  = $this->pds->tbl_employee_deductions;
					$deduc_fields['employee_id']  = $personal_info["employee_id"];
					$deduc_fields['start_date']   = $valid_data["employ_start_date"];

					$field                        	 = array('identification_value');
					$table 		                  	 = $this->pds->tbl_employee_identifications;
					$where                        	 = array();
					$key             			 	 = $this->get_hash_key('employee_id');
					$where[$key]     			  	 = $pds_employee_id;
					$where['identification_type_id'] = DEDUC_BIR;
					$bir_identification_val    	  	 = $this->pds->get_general_data($field, $table, $where, FALSE);	

					if(EMPTY($params['plantilla_flag']))
					{
						// ====================== jendaigo : start : disable obsolete condition ============= //
						/*
						//CHECK EMPLOYEE IF PROFESSIONAL
						$field                        = array('*');
						$table                        = $this->pds->tbl_employee_other_info;
						$where                        = array();
						$key             			  = $this->get_hash_key('employee_id');
						$where[$key]     			  = $pds_employee_id;
						$where['other_info_type_id']  = 5;
						$if_professional              = $this->pds->get_general_data($field, $table, $where, FALSE);

						//DEDUCTIONS FOR JOB ORDER
						$field                    	  = array('sys_param_value');
						$table                    	  = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
						$where                    	  = array();
						$where['sys_param_type']  	  = array(array(DEDUCTION_ST_BIR_EWT_ID, DEDUCTION_ST_BIR_VAT_ID), array('IN'));
						$jo_deductions       	  	  = $this->pds->get_general_data($field, $table, $where, TRUE); 

						if($if_professional)
						{
							foreach ($jo_deductions as $key => $row)
							{
								$field                    	  = array('deduction_id');
								$table                    	  = $this->pds->tbl_employee_deductions;
								$where                    	  = array();
								$key             			  = $this->get_hash_key('employee_id');
								$where[$key]     			  = $pds_employee_id;
								$where['deduction_id']  	  = $row['sys_param_value'];
								$if_exists       	  	  	  = $this->pds->get_general_data($field, $table, $where, FALSE);

								if(EMPTY($if_exists))
								{
									// INSERT TO EMPLOYEE DEDUCTIONS
									$deduc_fields['deduction_id'] = $row['sys_param_value'];
									$employee_deduc_id 			  = $this->pds->insert_general_data($table_deduc,$deduc_fields,TRUE);

									// GET BIR IDENTIFICATION NUMBER
									$field                    	  = array('other_deduction_detail_id');
									$table                    	  = $this->pds->tbl_param_other_deduction_details;
									$where                    	  = array();
									$where['deduction_id']  	  = $row['sys_param_value'];
									$where['pk_flag']  	  		  = YES;
									$other_deduc_id       	  	  = $this->pds->get_general_data($field, $table, $where, FALSE);

									// INSERT TO EMPLOYEE OTHER DEDUCTION DETAILS
									$other_deduc_fields 		 					 	= array();
									$other_deduc_table 		  						 	= $this->pds->tbl_employee_deduction_other_details;
									$other_deduc_fields['employee_deduction_id'] 	 	= $employee_deduc_id;
									$other_deduc_fields['other_deduction_detail_id'] 	= $other_deduc_id['other_deduction_detail_id'];
									$other_deduc_fields['other_deduction_detail_value'] = !EMPTY($bir_identification_val['identification_value']) ? $bir_identification_val['identification_value'] : NOT_APPLICABLE;						
									$this->pds->insert_general_data($other_deduc_table,$other_deduc_fields,FALSE);
								}
							}
						}
						else
						{
							$field                    	  = array('deduction_id');
							$table                    	  = $this->pds->tbl_employee_deductions;
							$where                    	  = array();
							$key             			  = $this->get_hash_key('employee_id');
							$where[$key]     			  = $pds_employee_id;
							$where['deduction_id']  	  = $jo_deductions[1]['sys_param_value'];
							$if_exists       	  	  	  = $this->pds->get_general_data($field, $table, $where, FALSE);

							if(EMPTY($if_exists))
							{
								// INSERT TO EMPLOYEE DEDUCTIONS
								$deduc_fields['deduction_id'] = $jo_deductions[1]['sys_param_value'];
								$employee_deduc_id 			  = $this->pds->insert_general_data($table_deduc,$deduc_fields,TRUE);

								$field                    	  = array('other_deduction_detail_id');
								$table                    	  = $this->pds->tbl_param_other_deduction_details;
								$where                    	  = array();
								$where['deduction_id']  	  = $jo_deductions[1]['sys_param_value'];
								$where['pk_flag']  	  		  = YES;
								$other_deduc_id       	  	  = $this->pds->get_general_data($field, $table, $where, FALSE);

								// INSERT TO EMPLOYEE OTHER DEDUCTION DETAILS
								$other_deduc_fields 		 					 	= array();
								$other_deduc_table 		  						 	= $this->pds->tbl_employee_deduction_other_details;
								$other_deduc_fields['employee_deduction_id'] 	 	= $employee_deduc_id;
								$other_deduc_fields['other_deduction_detail_id'] 	= $other_deduc_id['other_deduction_detail_id'];	
								$other_deduc_fields['other_deduction_detail_value'] = !EMPTY($bir_identification_val['identification_value']) ? $bir_identification_val['identification_value'] : NOT_APPLICABLE;													
								$this->pds->insert_general_data($other_deduc_table,$other_deduc_fields,FALSE);
							}							
						}
						*/
						// ====================== jendaigo : end : disable obsolete condition ============= //
					}
					else
					{
						//DEDUCTIONS FOR NON JOB ORDER
						$field                    = array("sys_param_value");
						$table                    = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
						$where                    = array();
						$key                      = "sys_param_type";
						$where[$key]              = "AP_REQD_DED";
						$contract_service         = $this->pds->get_general_data($field, $table, $where, TRUE);		

						foreach ($contract_service as $key => $row)
						{
							$field                    	  = array('deduction_id');
							$table                    	  = $this->pds->tbl_employee_deductions;
							$where                    	  = array();
							$key             			  = $this->get_hash_key('employee_id');
							$where[$key]     			  = $pds_employee_id;
							$where['deduction_id']  	  = $row['sys_param_value'];
							$if_exists       	  	  	  = $this->pds->get_general_data($field, $table, $where, FALSE);

							if(EMPTY($if_exists))
							{
								$field                        	 = array('identification_value');
								$table 		                  	 = $this->pds->tbl_employee_identifications;
								$where                        	 = array();
								$key             			 	 = $this->get_hash_key('employee_id');
								$where[$key]     			  	 = $pds_employee_id;
								$where['identification_type_id'] = $row['sys_param_value'];
								$identification_val    	  	 	 = $this->pds->get_general_data($field, $table, $where, FALSE);

								// INSERT TO EMPLOYEE DEDUCTIONS
								$deduc_fields['deduction_id'] = $row['sys_param_value']; 
								$employee_deduc_id 			  = $this->pds->insert_general_data($table_deduc,$deduc_fields,TRUE);

								$field                    	  = array('other_deduction_detail_id');
								$table                    	  = $this->pds->tbl_param_other_deduction_details;
								$where                    	  = array();
								$where['deduction_id']  	  = $row['sys_param_value'];
								$where['pk_flag']  	  		  = YES;
								$other_deduc_id       	  	  = $this->pds->get_general_data($field, $table, $where, FALSE);

								// INSERT TO EMPLOYEE OTHER DEDUCTION DETAILS
								$other_deduc_fields 		 					 	= array();
								$other_deduc_table 		  						 	= $this->pds->tbl_employee_deduction_other_details;
								$other_deduc_fields['employee_deduction_id'] 	 	= $employee_deduc_id;
								$other_deduc_fields['other_deduction_detail_id'] 	= $other_deduc_id['other_deduction_detail_id'];	
								$other_deduc_fields['other_deduction_detail_value'] = !EMPTY($identification_val['identification_value']) ? $identification_val['identification_value'] : NOT_APPLICABLE;														
								$this->pds->insert_general_data($other_deduc_table,$other_deduc_fields,FALSE);
							}							
						}			
					}
				}
				/*---------------- END INSERT TO EMPLOYEE DEDUCTION TABLE ----------------*/
				
				//INSERT TO EMPLOYEE COMPENSATION TABLE  IF CONTRACT OF SERVICE
				if(EMPTY($params['plantilla_flag']) OR $params['employ_type_flag'] == DOH_JO)
				{
					$data_raw                          = $params['employ_start_date'];
					$first_date                        = strtotime($data_raw);
					$prev_date                         = strtotime('-1 day', $first_date);
					$date                              = date('Y-m-d', $prev_date);
					
					$table6                            = $this->pds->tbl_employee_work_experiences;
					$fields6['employ_end_date']        = $date;
					$fields6['active_flag']      	   = 'N';
					$where6                            = array();
					$where6['employ_end_date']         = 'IS NULL';
					$key6                              = $this->get_hash_key('employee_id');
					$where6[$key6]                     = $pds_employee_id;
					
					if(EMPTY($params['employ_end_date']))
					$this->pds->update_general_data($table6,$fields6,$where6);

					/*GET EMPLOYEE*/
					// $field                         = array("compensation_id") ;
					// $table                         = $this->pds->tbl_param_compensations;
					// $where                         = array();
					// $key                           = "basic_salary_flag";
					// $where[$key]                   = "Y";
					// $contract_service              = $this->pds->get_general_data($field, $table, $where, TRUE);

					// $com_fields                    = array();
					// $com_fields['employee_id']     = $personal_info["employee_id"];
					// $com_fields['start_date']      = $valid_data["employ_start_date"];
					// if(!EMPTY($valid_data['employ_end_date']))
					// $com_fields['end_date']        = !EMPTY($valid_data["employ_end_date"]) ? $valid_data["employ_end_date"] : NULL;
					
					// $table                         = $this->pds->tbl_employee_compensations;
					
					// foreach ($contract_service as $row) 
					// {
					// 	$com_fields['compensation_id'] = $row['compensation_id']; // ID FROM PARAM COMPENSATION WHERE COMPENSATION NAME IS BASIC SALARY
					// 	$employee_compesation_id       = $this->pds->insert_general_data($table,$com_fields,TRUE);
					// }
					
				}

				if(!EMPTY($params['plantilla_flag']))
				{
					$table3                      = $this->pds->tbl_employee_work_experiences;
					$fields3['active_flag']      = 'N';
					$where3                      = array();
					$where3['active_flag']       = 'Y';
					$key3                        = $this->get_hash_key('employee_id');
					$where3[$key3]               = $pds_employee_id;

					if(EMPTY($params['employ_end_date']) AND EMPTY($params['end_date']))
					$this->pds->update_general_data($table3,$fields3,$where3);
				}

				//NEW APPOINTMENT
				if( (!EMPTY($params['plantilla_flag'])) AND $params['appointment_flag'])
				{

					//INSERT THE PREVIOUS EMPLOYEE HOLDER OF PLANTILLA
					$plantilla_id                      = $params['plantilla_id'];
					$prev_employee                     = $this->pds->get_prev_plantilla_owner($plantilla_id, $pds_employee_id);

					$fields['prev_employee_id']        = !EMPTY($prev_employee["employee_id"]) ? $prev_employee["employee_id"] : NULL;
					$fields['prev_separation_mode_id'] = !EMPTY($prev_employee["separation_mode_id"]) ? $prev_employee["separation_mode_id"] : NULL;

					$audit_fields['prev_employee_id']        = !EMPTY($prev_employee["employee_id"]) ? $prev_employee["employee_id"] : ' ';
					$audit_fields['prev_separation_mode_id'] = !EMPTY($prev_employee["separation_mode_id"]) ? $prev_employee["separation_mode_id"] : ' ';
					
					//UPDATE THE PREVIOUS RECORD OF WORK EXPERIENCE
					$data_raw                          = $params['employ_start_date'];
					$first_date                        = strtotime($data_raw);
					$prev_date                         = strtotime('-1 day', $first_date);
					$date                              = date('Y-m-d', $prev_date);
					
					$table4                            = $this->pds->tbl_employee_work_experiences;
					$fields4['employ_end_date']        = $date;
					$where4                            = array();
					$where4['employ_end_date']         = 'IS NULL';
					$key4                              = $this->get_hash_key('employee_id');
					$where4[$key4]                     = $pds_employee_id;

					if(EMPTY($params['end_date']))
					$this->pds->update_general_data($table4,$fields4,$where4);

					// //UPDATE THE PREVIOUS RECORD OF COMPENSATION TABLE
					// $data_raw                      = $params['employ_start_date'];
					// $first_date                    = strtotime($data_raw);
					// $prev_date                     = strtotime('-1 day', $first_date);
					// $date                          = date('Y-m-d', $prev_date);
					
					// $table5                        = $this->pds->tbl_employee_compensations;
					// $fields5['end_date']           = $date;
					// $where5                        = array();
					// $where5['end_date']            = 'IS NULL';
					// $key5                          = $this->get_hash_key('employee_id');
					// $where5[$key5]                 = $pds_employee_id;

					// if(EMPTY($params['end_date']))
					// $this->pds->update_general_data($table5,$fields5,$where5);

					// //INSERT TO EMPLOYEE COMPENSATION TABLE
					// $field                         = array("compensation_id");
					// $table                         = $this->pds->tbl_param_compensations;
					// $where                         = array();
					// $key                           = "basic_salary_flag";
					// $where[$key]                   = "Y";
					// $contract_service              = $this->pds->get_general_data($field, $table, $where, TRUE);

					// $com_fields                    = array();
					// $com_fields['employee_id']     = $personal_info["employee_id"];
					// $com_fields['start_date']      = $valid_data["employ_start_date"];
					
					// $table                         = $this->pds->tbl_employee_compensations;
					
					// foreach ($contract_service as $row) 
					// {
					// 	$com_fields['compensation_id'] = $row['compensation_id']; // ID FROM PARAM COMPENSATION WHERE COMPENSATION NAME IS BASIC SALARY
					// 	$employee_compesation_id       = $this->pds->insert_general_data($table,$com_fields,TRUE);
					// }
				}

				//SALARY INCREASE
				if( (!EMPTY($params['plantilla_flag'])) AND (EMPTY($params['appointment_flag'])) )
				{
					// $curr_date                       = date('Y-m-d');
					
					// $select_fields                   = array("max(effectivity_date) date");
					// $tables                          = $this->pds->tbl_param_salary_schedule;
					// $where                           = array();
					// $where['effectivity_date']       = array($curr_date, array("<="));
					// $latest_date                     = $this->pds->get_specific_param_plantilla($select_fields, $tables, $where);
					
					// $field                           = array("amount");
					// $table                           = $this->pds->tbl_param_salary_schedule;
					// $where                           = array();
					// $where['effectivity_date']       = $latest_date['date'];
					// $where['salary_grade']           = $params['employ_salary_grade'];
					// $where['salary_step']            = $params['salary_step'];
					// $basic_salary                    = $this->pds->get_general_data($field, $table, $where, FALSE);

					// $field                    		 = array("sys_param_type");
					// $table                    		 = $this->pds->db_core.'.'.$this->pds->tbl_sys_param;
					// $where                    		 = array();
					// $key                      		 = "sys_param_type";
					// $where[$key]              		 = "MOVT_SALARY_ADJUSTMENT";
					// $salary_adj 	        		 = $this->pds->get_general_data($field, $table, $where, FALSE);

					// if($salary_adj['sys_param_type'] == 'MOVT_SALARY_ADJUSTMENT')
					// {
					// 	$fields['employ_monthly_salary'] 	   = $valid_data["employ_monthly_salary_non"];
					// }

					// $fields['employ_monthly_salary'] 	   = $basic_salary['amount'];
					// $audit_fields['employ_monthly_salary'] = !EMPTY($basic_salary["amount"]) ? $basic_salary["amount"] : ' ';					

					$data_raw                        = $params['service_start_step'];
					$first_date                      = strtotime($data_raw);
					$prev_date                       = strtotime('-1 day', $first_date);
					$date                            = date('Y-m-d', $prev_date);
					
					$table4                          = $this->pds->tbl_employee_work_experiences;
					$fields4['employ_end_date']      = $date;
					$where4                          = array();
					$where4['employ_end_date']       = 'IS NULL';
					$key4                            = $this->get_hash_key('employee_id');
					$where4[$key4]                   = $pds_employee_id;

					if(EMPTY($params['end_date']))
					$this->pds->update_general_data($table4,$fields4,$where4);

					//UPDATE THE PREVIOUS RECORD OF COMPENSATION TABLE
					// $data_raw                      = $params['employ_start_date'];
					// $first_date                    = strtotime($data_raw);
					// $prev_date                     = strtotime('-1 day', $first_date);
					// $date                          = date('Y-m-d', $prev_date);
					
					// $table5                        = $this->pds->tbl_employee_compensations;
					// $fields5['end_date']           = $date;
					// $where5                        = array();
					// $where5['end_date']            = 'IS NULL';
					// $key5                          = $this->get_hash_key('employee_id');
					// $where5[$key5]                 = $pds_employee_id;

					// if(EMPTY($params['end_date']))
					// $this->pds->update_general_data($table5,$fields5,$where5);

					// //INSERT TO EMPLOYEE COMPENSATION TABLE
					// $field                         = array("compensation_id") ;
					// $table                         = $this->pds->tbl_param_compensations;
					// $where                         = array();
					// $key                           = "basic_salary_flag";
					// $where[$key]                   = "Y";
					// $contract_service              = $this->pds->get_general_data($field, $table, $where, TRUE);

					// $com_fields                    = array();
					// $com_fields['employee_id']     = $personal_info["employee_id"];
					// $com_fields['start_date']      = $valid_data["service_start_step"];
					
					// $table                         = $this->pds->tbl_employee_compensations;
					
					// foreach ($contract_service as $row) 
					// {
					// 	$com_fields['compensation_id'] = $row['compensation_id']; // ID FROM PARAM COMPENSATION WHERE COMPENSATION NAME IS BASIC SALARY
					// 	$employee_compesation_id       = $this->pds->insert_general_data($table,$com_fields,TRUE);
					// }
				}

				//INSERT DATA TO EMPLOYEE WORK EXPERIENCE	//01/26/2017				
					$fields['employee_id']                       = $personal_info["employee_id"];
					$audit_fields['employee_id']                 = !EMPTY($personal_info["employee_id"]) ? $personal_info["employee_id"] : ' ';
					$table                                       = $this->pds->tbl_employee_work_experiences;
					$employee_work_experience_id                 = $this->pds->insert_general_data($table,$fields,TRUE);

					$fields_dtl['employee_work_experience_id']   = $employee_work_experience_id;
					$fields_dtl['employ_period_from']   		= !EMPTY($valid_data['employ_period_from']) ? $valid_data['employ_period_from'] : NULL;
					$fields_dtl['employ_period_to']   			= !EMPTY($valid_data['employ_period_to']) ? $valid_data['employ_period_to'] : NULL;
					$fields_dtl['signing_date']   			    = !EMPTY($valid_data['signing_date']) ? $valid_data['signing_date'] : NULL;
					$fields_dtl['hrmpsb_date']   			    = !EMPTY($valid_data['hrmpsb_date']) ? $valid_data['hrmpsb_date'] : NULL;
					$fields_dtl['plantilla_page']  					= !EMPTY($valid_data['plantilla_page']) ? $valid_data['plantilla_page'] : NULL;
					$table_dtl                    				= $this->pds->tbl_employee_work_experience_details;
					$employee_work_experience_details_id        = $this->pds->insert_general_data($table_dtl,$fields_dtl,TRUE);
					
					// if(!EMPTY($params['govt_service_flag'])) :	
					// 	//$fields['employee_work_experience_id'] = $employee_work_experience_id['employee_work_experience_id'];
					// 	$table                                   = $this->pds->tbl_employee_work_experiences;
					// 	$employee_work_experiences               = $this->pds->insert_general_data($table,$fields,TRUE);
					// endif;
					
					$audit_table[]                               = $this->pds->tbl_employee_work_experiences;
					$audit_schema[]                              = DB_MAIN;
					$prev_detail[]                               = array();
					$curr_detail[]                               = array($audit_fields);
					$audit_action[]                              = AUDIT_INSERT;	
					
					$activity                                    = "New work experience with the position %s has been added.";
					$audit_activity                              = sprintf($activity, $valid_data["employ_position"]);
					
					$status                                      = true;
					$message                                     = $this->lang->line('data_saved');
			}
			else
			{				
				// $fields['last_modified_by']   = $this->log_user_id;
				// $fields['last_modified_date'] = date("Y-m-d H:i:s");

				//if( ((!EMPTY($params['plantilla_flag'])) AND (EMPTY($params['appointment_flag']))) OR $params['employ_type_flag'] == DOH_GOV_NON_APPT) 
				//{
					// $curr_date                       = date('Y-m-d');
					
					// $select_fields                   = array("max(effectivity_date) date");
					// $tables                          = $this->pds->tbl_param_salary_schedule;
					// $where                           = array();
					// $where['effectivity_date']       = array($curr_date, array("<="));
					// $latest_date                     = $this->pds->get_specific_param_plantilla($select_fields, $tables, $where);
					
					// $field                           = array("amount");
					// $table                           = $this->pds->tbl_param_salary_schedule;
					// $where                           = array();
					// $where['effectivity_date']       = $latest_date['date'];
					// $where['salary_grade']           = $params['employ_salary_grade'];
					// $where['salary_step']            = $params['salary_step'];
					// $basic_salary                    = $this->pds->get_general_data($field, $table, $where, FALSE);
					
					//$fields['employ_monthly_salary'] = $basic_salary['amount'];
		
				//}
				
				$where                        = array();
				$key                          = $this->get_hash_key('employee_work_experience_id');
				$where[$key]                  = $id;
				$table                        = $this->pds->tbl_employee_work_experiences;
				$this->pds->update_general_data($table,$fields,$where);

				$field                        = array('*');
				$table_dtl                    = $this->pds->tbl_employee_work_experience_details;


				if($this->pds->get_general_data($field, $table_dtl, $where, FALSE))
				{
					$fields_dtl					  	  = array();
					$fields_dtl['employ_period_from'] = !EMPTY($valid_data['employ_period_from']) ? $valid_data['employ_period_from'] : NULL;
					$fields_dtl['employ_period_to']   = !EMPTY($valid_data['employ_period_to']) ? $valid_data['employ_period_to'] : NULL;
					$fields_dtl['signing_date']   	  = !EMPTY($valid_data['signing_date']) ? $valid_data['signing_date'] : NULL;
					$fields_dtl['hrmpsb_date']   	  = !EMPTY($valid_data['hrmpsb_date']) ? $valid_data['hrmpsb_date'] : NULL;
					$fields_dtl['plantilla_page']   	  = !EMPTY($valid_data['plantilla_page']) ? $valid_data['plantilla_page'] : NULL;

					$this->pds->update_general_data($table_dtl,$fields_dtl,$where);
				} else {
					$field                        	  = array('employee_work_experience_id AS emp_wexp_id');
					$get_emp_wexp_id 			   	  = $this->pds->get_general_data($field, $table, $where, FALSE);

					$fields_dtl					      			= array();

					$fields_dtl['employee_work_experience_id'] 	= $get_emp_wexp_id['emp_wexp_id'];
					$fields_dtl['employ_period_from']   		= !EMPTY($valid_data['employ_period_from']) ? $valid_data['employ_period_from'] : NULL;
					$fields_dtl['employ_period_to']   			= !EMPTY($valid_data['employ_period_to']) ? $valid_data['employ_period_to'] : NULL;
					$fields_dtl['signing_date']   				= !EMPTY($valid_data['signing_date']) ? $valid_data['signing_date'] : NULL;
					$fields_dtl['hrmpsb_date']   				= !EMPTY($valid_data['hrmpsb_date']) ? $valid_data['hrmpsb_date'] : NULL;
					$fields_dtl['plantilla_page']  					= !EMPTY($valid_data['plantilla_page']) ? $valid_data['plantilla_page'] : NULL;
					$this->pds->insert_general_data($table_dtl,$fields_dtl,TRUE);

				}
				
				$audit_table[]                = $this->pds->tbl_employee_work_experiences;
				$audit_schema[]               = DB_MAIN;
				$prev_detail[]                = array($work_experience);
				$curr_detail[]                = array($audit_fields);
				$audit_action[]               = AUDIT_UPDATE;	
				
				$activity                     = "Work experience with the position %s has been updated.";
				$audit_activity               = sprintf($activity, $work_experience["employ_position"]);

				$status  = true;
				$message = $this->lang->line('data_updated');
			}


			
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();


		} 
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);


	}

	private function _validate_work_experience_non_doh($params)
	{
		try
		{
			$fields 								 = array();
			$fields['employ_start_date_non_doh']     = "Date Started";
			$fields['employ_end_date_non_doh']       = "Date Ended";
			$fields['employ_position']       		 = "Position";
			$fields['employ_company_name']   		 = "Department/Agency/Office/Company";
			$fields['employ_monthly_salary_non_doh'] = "Monthly Salary";
			$fields['employment_status_non_doh']  	 = "Employment Status";			

			if(!EMPTY($params['govt_service_flag']) OR $params['employ_type_flag'] == NON_DOH_GOV)
			{
				if (!isset($params['employ_salary_grade_non_doh']) || !is_numeric($params['employ_salary_grade_non_doh'])) {
					$fields['employ_salary_grade_non_doh'] = "Salary Grade";
				}
				if (!isset($params['employ_salary_step_non_doh']) || !is_numeric($params['employ_salary_step_non_doh'])) {
					$fields['employ_salary_step_non_doh'] = "Salary Step";
				}
				$fields['branch_name']         		   = "Branch";
				//marvin : start : remove required
				// $fields['separation_mode_non_doh']     = "Separation Cause";
			}

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_work_experience($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_work_experience($params)
	{
		try
		{
			$validation['employ_start_date_non_doh'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['employ_end_date_non_doh'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended'
			);
			$validation['employ_position'] = array(
					'data_type' => 'string',
					'name'		=> 'Position',
					'max_len'	=> 50
			);
			$validation['employ_company_name'] = array(
					'data_type' => 'string',
					'name'		=> 'License Number',
					'max_len'	=> 100
			);
			$validation['employ_monthly_salary_non_doh'] = array(
					'data_type' => 'amount',
					'name'		=> 'Monthly Salary',
					'decimal'	=> 2,
					'max'		=> 999999
			);	
			$validation['employment_status_non_doh'] = array(
					'data_type' => 'digit',
					'name'		=> 'Status of Appointment'
			);
			$validation['govt_service_flag'] = array(
					'data_type' => 'digit',
					'name'		=> 'Government Service',
					'max_len'	=> 1
			);
			$validation['separation_mode_non_doh'] = array(
				'data_type' => 'digit',
				'name'		=> 'Separation Mode'
			);
			$validation['employ_salary_grade_non_doh'] = array(
				'data_type' => 'digit',
				'name'		=> 'Salary Grade',
				'max_len'	=> 3
			);		
			$validation['employ_salary_step_non_doh'] = array(
				'data_type' => 'digit',
				'name'		=> 'Pay Step',
				'max_len'	=> 3
			);
			$validation['branch_name'] = array(
				'data_type' => 'digit',
				'name'		=> 'Branch'
			);
			$validation['leaves'] = array(
				'data_type' => 'digit',
				'name'		=> 'Leave/s without Pay',
				'max_len'	=> 10
			);
			$validation['relevance_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Relevance Flag',
					'max_len' 			=> 1 
			);
			$validation['remarks'] = array (
					'data_type'    => 'string',
					'name' 		   => 'Remarks'
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_work_experience_doh($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();
			
			if(!EMPTY($params['plantilla_flag']) AND !EMPTY($params['appointment_flag']) OR (!EMPTY($params['prev_record']) ? $params['employ_type_flag'] == DOH_GOV_APPT : ''))
			{
				$fields["plantilla_id"]          = 'Plantilla Item';	
				$fields["personnel_movement"]    = 'Personnel Movement';
				$fields['employ_start_date']     = "Date Started";
				$fields['employ_monthly_salary'] = "Monthly Salary";
				$fields['salary_grade']          = "Salary Grade";
				$fields['salary_step_info']      = "Salary Step";
				//$fields['admin_office_id']       = "Administrator Office";
			}

			if(EMPTY($params['plantilla_flag']) OR (!EMPTY($params['prev_record']) ? $params['employ_type_flag'] == DOH_JO : ''))
			{
				$fields['position_id'] = "Position";
				$fields['office_id']   = "Department/Agency/Office/Company";
			}

			$fields['employment_status_id']  = "Employment Status";
			$fields['branch_id']  = "Branch";
			
			//change office value if plantilla_id is 00000
			if($params['plantilla_id'] == 00000)
			{
				$params['office'] = $params['office_id_read'];
				$params['position'] = $params['position_id_read'];
			}

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_work_experience_doh_input($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_work_experience_doh_input($params) 
	{
		try 
		{			
			$validation['plantilla_id'] = array(
				'data_type' => 'digit',
				'name'		=> 'Plantilla'
			);
			$validation['plantilla'] = array(	
				'data_type' => 'digit',
				'name'		=> 'Plantilla'
			);
			$validation['personnel_movement'] = array(
					'data_type' => 'string',
					'name'		=> 'Personnel Movement'
			);
			$validation['employ_start_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['step_incr_reason_code'] = array(
					'data_type' => 'string',
					'name'		=> 'Step Increment Reason',
					'max_len'	=> 2
			);
			$validation['employ_end_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended',
					'max_date'  => date("Y/m/d") //ncocampo 11/07/2023
			);
			$validation['end_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended',
					'max_date'  => date("Y/m/d") //ncocampo 11/07/2023
			);
			$validation['position_id'] = array(
					'data_type' => 'string',
					'name'		=> 'Position'
			);
			$validation['position'] = array(
					'data_type' => 'string',
					'name'		=> 'Position'
			);
			$validation['office_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Office'
			);
			$validation['office'] = array(
					'data_type' => 'string',
					'name'		=> 'Office'
			);
			$validation['employ_monthly_salary'] = array(
					'data_type' => 'amount',
					'name'		=> 'Monthly Salary',
					'decimal'	=> 2,
					'max'		=> 999999
			);
			$validation['salary_grade'] = array(
					'data_type' => 'digit',
					'name'		=> 'Salary Grade',
					'decimal'	=> 2,
					'max'		=> 40
			);
			$validation['salary_step_info'] = array(
					'data_type' => 'digit',
					'name'		=> 'Salary Step',
					'decimal'	=> 2,
					'max'		=> 10
			);	
			$validation['employment_status_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Status of Appointment'
			);

			$validation['govt_service_flag'] = array(
					'data_type' => 'digit',
					'name'		=> 'Government Service',
					'max_len'	=> 1
			);
			//NON APPOINTMENT
			$validation['service_start_step'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['employ_salary_grade'] = array(
					'data_type' => 'digit',
					'name'		=> 'Salary Grade',
					'max'		=> 40
			);
			$validation['salary_step'] = array(
					'data_type' => 'digit',
					'name'		=> 'Salary Step',
					'max'		=> 40
			);
			$validation['employ_monthly_salary_non'] = array(
					'data_type' => 'amount',
					'name'		=> 'Monthly Salary',
					'decimal'	=> 2,
					'max'		=> 999999
			);
			$validation['separation_mode'] = array(
				'data_type' => 'digit',
				'name'		=> 'Separation Mode'
			);	
			$validation['publication_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Publication Date'
			);
			$validation['publication_date_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Publication Date To'
			);
			$validation['publication_place'] = array(
					'data_type' => 'string',
					'name'		=> 'Publication Place',
					'max_len'	=> 45
			);
			$validation['deliberation_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Deliberation Date'
			);
			$validation['posted_in'] = array(
					'data_type' => 'string',
					'name'		=> 'Posted In',
					'max_len'	=> 100
			);
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks'
			);		
			$validation['admin_office_id'] = array(
					'data_type' => 'string',
					'name'		=> 'Administrator Office'
			);	
			$validation['transfer_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Transfer Flag',
					'max_len' 			=> 2 
			);
			$validation['transfer_to'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Transfer To',
					'max_len' 			=> 50
			);	
			$validation['branch_id'] = array(
				'data_type' => 'digit',
				'name'		=> 'Branch'
			);	
			$validation['employ_period_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Period of Employment From'
			);
			$validation['employ_period_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Period of Employment To'
			);
			$validation['signing_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date of Signing'
			);
			$validation['hrmpsb_date'] = array(
					'data_type' => 'date',
					'name'		=> 'HRMPSB started on'
			);
			$validation['plantilla_page'] = array(
				'data_type' => 'string',
				'name'		=> 'Plantilla Page'
			);	
			$validation['relevance_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Relevance Flag',
					'max_len' 			=> 2 
			);

			return $this->validate_inputs($params, $validation);
		} 
		catch ( Exception $e ) 
		{
			throw $e;
		}	
	}

	public function delete_work_experience()
	{
		try
		{
			$flag = 0;
			$params			= get_params();
			$url 			= $params['param_1'];
			$url_explode	= explode('/',$url);
			$action 		= $url_explode[0];
			$id				= $url_explode[1];
			$token 			= $url_explode[2];
			$salt 			= $url_explode[3];
			$module			= $url_explode[4];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			Main_Model::beginTransaction();
			//GET PREVIOUS DATA
			$prev_data			= array();
			/*GET PREVIOUS DATA*/
			$field 				= array("*");
			$table				= $this->pds->tbl_employee_work_experiences;
			$where				= array();
			$key 				= $this->get_hash_key('employee_work_experience_id');
			$where[$key]		= $id;
			$work_experience 	= $this->pds->get_general_data($field, $table, $where, FALSE);
			$prev_emp_id 		= $this->hash($work_experience['employee_id']);

			//DELETE DATA
			$where				= array();
			$key 				= $this->get_hash_key('employee_work_experience_id');
			$where[$key]		= $id;
			$table 				= $this->pds->tbl_employee_work_experiences;
			$table_dtl			= $this->pds->tbl_employee_work_experience_details;


			// IF PRESENT WORK EXPERIENCE IS DELETED, PREVIOUS WORK EXPERIENCE IS RE-ACTIVATED, ONLY IF THE SEPARATION CAUSE IS EMPTY
			
				if(($work_experience['employ_type_flag'] == DOH_GOV_APPT OR $work_experience['employ_type_flag'] == DOH_GOV_NON_APPT OR $work_experience['employ_type_flag'] == DOH_JO) AND (EMPTY($work_experience['employ_end_date']) OR $work_experience['active_flag'] == YES))
				{
					$prev_work_experience = $this->pds->get_prev_work_experience($prev_emp_id);
					if(!EMPTY($prev_work_experience) AND (EMPTY($prev_work_experience['separation_mode_id'])))
					{
						$where_prev						= array();
						$key 							= $this->get_hash_key('employee_work_experience_id');
						$where_prev[$key]				= $this->hash($prev_work_experience['employee_work_experience_id']);
						$prev_fields['employ_end_date'] = NULL;
						$prev_fields['active_flag'] 	= YES;
						$this->pds->update_general_data($table,$prev_fields,$where_prev);	
					}
				}

			$this->pds->delete_general_data($table,$where);
			$this->pds->delete_general_data($table_dtl,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$this->pds->update_pds_date_accomplished($prev_emp_id);
			
			$audit_table[]		= $this->pds->tbl_employee_work_experiences;
			$audit_schema[]		= DB_MAIN;
			$prev_detail[] 		= array($work_experience);
			$curr_detail[]		= array();
			$audit_action[] 	= AUDIT_DELETE;
			$activity 			= "Work experience with the position %s has been deleted.";
			$audit_activity 	= sprintf($activity, $work_experience["employ_position"]);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 				= $this->lang->line('data_deleted');
			$flag 				= 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$response 					= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'pds_work_experience_table',
			"path"					=> PROJECT_MAIN . '/pds_work_experience_info/get_work_experience_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	public function get_divisions()
	{
		if(!EMPTY($_POST['office_id']))
		{
			$select_fields             = array("C.office_id, C.org_code, B.name");

			$tables 			= array(
				'main'			=> array(
					'table'		=> "doh_ptis_module.param_offices",
					'alias'		=> 'A',
				),
				't1'			=> array(
					'table'		=> "doh_ptis_core.organizations",
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_parent OR A.org_code = B.org_code',
				),
				't2'			=> array(
					'table'		=> "doh_ptis_module.param_offices",
					'alias'		=> 'C',
					'type'		=> 'join',
					'condition'	=> " B.org_code = C.org_code AND C.active_flag = 'Y'",
				)

			);

			$where                       = array();
			$where["A.office_id"]      = $_POST['office_id'];
			
			$data           = $this->pds->get_general_data($select_fields, $tables, $where);
			
			echo json_encode(array(
				'message' => $data,
				'status' => 1
			));		
		}
		else
		{
			echo json_encode(array(
				'message' => '<b>Office</b> must not be empty!',
				'status' => 0
			));
		}
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */