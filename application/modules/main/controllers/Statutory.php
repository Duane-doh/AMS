<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statutory extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('statutory_model', 'statutory');
		$this->load->model('pds_model', 'pds');
	}
	
	public function index()
	{
		$data    = array();
		$resources= array();
		$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_SELECTIZE,JS_DATATABLE);
		
		$this->template->load('statutory/display_statutory_info', $data, $resources);
		
	}

	public function get_deduction_employee_tab($form, $action, $employee_id, $token, $salt, $module)
	{
	
		try
		{
			$data 					= array();
			$resources['load_css'] 	= array();
			$resources['load_js']   = array();
			
			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				// throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				// throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$this->session->set_userdata("pds_employee_id", $employee_id);
	
			switch ($form)
			{
				case 'display_statutory_info':
					$view_form = $form;
					break;
	
				// case 'loans':
				// 	//$path = "statutory/loans";
				// 	$modals = array(
				// 			'modal_loan' => array(
				// 					'controller'	=> 'loans',
				// 					'module'		=> PROJECT_MAIN,
				// 					'method'		=> 'modal',
				// 					'multiple'		=> true,
				// 					'height'		=> '160px',
				// 					'size'			=> 'md',
				// 					'title'			=> 'Loan'
				// 			)
				// 	);
				// 	$resources['load_modal']	= $modals;
					$resources['datatable'][]	= array('table_id' => 'table_loans', 'path' => 'main/loans/get_loans_list');
					$view_form = $form;
				break;
	
				case 'other_deductions':
					$resources['load_css'] 		= array(CSS_DATATABLE,CSS_MODAL_COMPONENT);
					$resources['load_js'] 		= array(JS_DATATABLE,JS_MODAL_CLASSIE,JS_MODAL_EFFECTS);
					$resources['datatable'][]	= array('table_id' => 'table_other_deduction', 'path' => 'main/statutory/get_other_deduction_list');
					$view_form = $form;
				break;
	
			}
			$this->load->view('statutory/'.$view_form, $data);
			$this->load_resources->get_resource($resources);
	
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
	}

	public function get_statutory_info($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{	
		try
		{

			$data 					= array();
			$resources['load_css'] 	= array();
			$resources['load_js'  ] = array();

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			//GET PDS INFORMATION
			$field 							= array("*") ;
			$table							= $this->compensation->tbl_employee_personal_info;
			$key 							= $this->get_hash_key('employee_id');
			$where							= array($key => $employee_id);			
			//$data['pds_information'] 	    = $this->compensation->get_compensation_data($field, $table, $where, FALSE);
			$data['pds_information'] 	    = '';

			
			
			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$this->template->load('statutory/display_statutory_info', $data, $resources);		
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
	}

	public function get_statutory_history($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
	
		try
		{
			
		$params = get_params();
				
			
			$iTotal["cnt"]		= 6;
			$iFilteredTotal["cnt"] = 1;
	
			$output = array(
					"sEcho" => intval($_POST['sEcho']),
					"iTotalRecords" => $iTotal["cnt"],
					"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
					"aaData" => array()
			);
			$cnt = 0;
	
			$loans = array(
	
					0 => array(
							'deduction_statutory_id'	=> 'Statutory',
							'deduction_statutory_code'	=> 'GSIS',
							'details' 	=> '25'
					),
					1 => array(
							'deduction_statutory_id'	=> 'Loan',
							'deduction_statutory_code'	=> 'HDMF Loan',
							'details' 	=> '35'
					),
					2 => array(
							'deduction_statutory_id'		=> 'Loan',
							'deduction_statutory_code'		=> 'Housing Loan',
							'details' 	=> '112'
					)
			);
				
			foreach ($loans as $aRow):
			$cnt++;
			$row = array();
				
			$row[] =  $aRow['deduction_statutory_id'];	
			$row[] =  $aRow['deduction_statutory_code'];	
			$row[] =  $aRow['details'];

			$action = "<div class='table-actions'>";
			$action .= "<a href='#' class='view tooltipped' data-tooltip='View' data-position='bottom' onclick=\"content_form('deductions/deduction_type_employees','main')\" data-delay='50'></a>";
			$action .= "<a href='#' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' onclick=\"content_form('deductions/deduction_type_employees','main')\" data-delay='50'></a>";
			$action .= "</div>";
			if($cnt == count($loans))
			{
				$resources['load_js'] = array('modalEffects','classie');
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
	
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

	public function get_statutory_details($action = NULL, $employee_id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
	
		try
		{
			// $params = get_params();		


			// $aColumns = array("*");
			// $bColumns = array("deduction_statutory_id", "deduction_statutory_code", "details");
			// $table 	  = $this->statutory->tbl_employee_compensation_hdr;
			// $where	  = array();
			// $statutory_list = $this->statutory->get_statutory_list($aColumns, $bColumns, $params, $table, $where);
			// $iTotal   = $this->statutory->get_statutory_list(array("COUNT(DISTINCT(deduction_statutory_id)) as count" ), $bColumns, $params, $table, $where, false);

			// $output = array(
			// 	"sEcho" => intval($_POST['sEcho']),
			// 	"iTotalRecords" => $iTotal["cnt"],
			// 	"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
			// 	"aaData" => array()
			// );
			
	
			// $cnt = 0;
			// foreach ($statutory_list as $aRow):
			// 	$cnt++;
			// 	$row = array();
			// 	$action = "";
				

			// 	$id 			= $this->hash($aRow['employee_id']);
			// 	$salt			= gen_salt();
			// 	$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
			// 	$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
			// 	$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
			// 	$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
			// 	$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
			// 	$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

	
				
		$params = get_params();
			
			$iTotal["cnt"]		= 6;
			$iFilteredTotal["cnt"] = 1;
	
			$output = array(
					"sEcho" => intval($_POST['sEcho']),
					"iTotalRecords" => $iTotal["cnt"],
					"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
					"aaData" => array()
			);
			$cnt = 0;
	
			$loans = array(
	
					0 => array(
							'deduction_statutory_id'	=> 'Statutory',
							'deduction_statutory_code'	=> 'GSIS',
							'details' 	=> '25'
					),
					1 => array(
							'deduction_statutory_id'	=> 'Statutory',
							'deduction_statutory_code'	=> 'HDMF Loan',
							'details' 	=> '35'
					),
					2 => array(
							'deduction_statutory_id'	=> 'Statutory',
							'deduction_statutory_code'	=> 'Housing Loan',
							'details' 	=> '112'
					)
			);
				
			foreach ($loans as $aRow):
			$cnt++;
			$row = array();
				
			$row[] =  $aRow['deduction_statutory_id'];	
			$row[] =  $aRow['deduction_statutory_code'];	
			$row[] =  $aRow['details'];

			$action = "<div class='table-actions'>";
			$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_statutory_info' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_statutory_info_init('".$view_action."')\"></a>";
			$action .= "<a href='#' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' onclick=\"content_form('deductions/deduction_type_employees','main')\" data-delay='50'></a>";
			$action .= "</div>";
			if($cnt == count($loans))
			{
				$resources['load_js'] = array('modalEffects','classie');
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
	
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

	public function get_tab_statutory($form, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data 					= array();
			$resources['load_css'] 	= array();
			$resources['load_js']   = array();

			$data['action']			= $action;
			$data['employee_id']	= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				//throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($employee_id . '/' . $action . '/' . $module , $salt))
			{
				//throw new Exception($this->lang->line('err_unauthorized_access'));
			}
	
			switch ($form)
			{
				case 'statutory_details':
					$resources['load_css'] 		= array(CSS_DATATABLE,CSS_MODAL_COMPONENT);
					$resources['load_js'] 		= array(JS_DATATABLE,JS_MODAL_CLASSIE,JS_MODAL_EFFECTS);
					$resources['load_modal'] = array(
						'modal_statutory_info' => array(
								'controller'	=> __CLASS__,
								'module'		=> PROJECT_MAIN,
								'method'		=> 'modal_statutory_info',
								'multiple'		=> true,
								'height'		=> '160px',
								'size'			=> 'md',
								'title'			=> 'Statutory Info'
							),
							'modal_statutory_bir' => array(
								'controller'	=> __CLASS__,
								'module'		=> PROJECT_MAIN,
								'method'		=> 'modal_statutory_bir',
								'multiple'		=> true,
								'height'		=> '560px',
								'size'			=> 'md',
								'title'			=> 'BIR'
							),
							'modal_statutory_gsis' => array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_statutory_gsis',
									'multiple'		=> true,
									'height'		=> '560px',
									'size'			=> 'md',
									'title'			=> 'GSIS'
							),
							'modal_statutory_pagibig' => array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_statutory_pagibig',
									'multiple'		=> true,
									'height'		=> '560px',
									'size'			=> 'md',
									'title'			=> 'PAG-IBIG'
							),
							'modal_statutory_philhealth' => array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_statutory_philhealth',
									'multiple'		=> true,
									'height'		=> '560px',
									'size'			=> 'md',
									'title'			=> 'Philhealth'
							)
					);
					$resources['datatable'][]	= array('table_id' => 'table_statutory_details', 'path' => 'main/statutory/get_statutory_details', 'advanced_filter' => TRUE);
					$view_form = $form;
					break;
	
				case 'statutory_history':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$resources['datatable'][]	= array('table_id' => 'table_statutory_history', 'path' => 'main/statutory/get_statutory_history', 'advanced_filter' => TRUE);
					// $resources['load_modal'] = array(
					// 	'modal_statutory_details' => array(
					// 		'controller'	=> __CLASS__,
					// 		'module'		=> PROJECT_MAIN,
					// 		'method'		=> 'modal_statutory_details',
					// 		'multiple'		=> true,
					// 		'height'		=> '400px',
					// 		'size'			=> 'md',
					// 		'title'			=> 'Add Benefits'
					// 	)	
	
					// );

					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_employee_benefits',
						PROJECT_MAIN
					);
					$view_form = $form;
					break;
	
			}
	
			$this->load->view('statutory/tabs/'.$form, $data);
			$this->load_resources->get_resource($resources);
	
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
	}


	
public function get_philhealth_dependent_list()
	{
		try
		{
			$params = get_params();
			
			$iTotal["cnt"]		= 6;
			$iFilteredTotal["cnt"] = 1;
	
			$output = array(
					"sEcho" => intval($_POST['sEcho']),
					"iTotalRecords" => $iTotal["cnt"],
					"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
					"aaData" => array()
			);
			$cnt = 0;

			
	
			$loans = array(
	
					0 => array(
							'pin'				=> '<input type="text"',
							'beneficiary_name'	=> 'Marian Rizal',
							'birthdate'			=> '1965-02-01',
							'relationship' 		=> 'Mother'
							
					),
					1 => array(
							'pin'				=> '<input type="text"',
							'beneficiary_name'		=> 'Dingdong Rizal',
							'birthdate'			=> '1965-02-01',
							'relationship' 	=> 'Father'

					),
					2 => array(
							'pin'				=> '<input type="text"',
							'beneficiary_name'		=> 'Zia Rizal',
							'birthdate'			=> '1965-02-01',
							'relationship' 	=> 'Daugther'
					)
			);
				
			foreach ($loans as $aRow):
			$cnt++;
			$row = array();
			
			$row[] =  $aRow['pin'];
			$row[] =  $aRow['beneficiary_name'];
			$row[] =  $aRow['birthdate'];
			$row[] =  $aRow['relationship'];
			
			
			$action = "<div class='table-actions'>";
			// $action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_VIEW."'')\" data-delay='50'></a>";
			// $action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_ADD."')\" data-delay='50'></a>";
			$action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
	
			$action .= "</div>";
			if($cnt == count($loans))
			{
				$resources['load_js'] = array('modalEffects','classie');
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
	
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

	
	public function get_pagibig_beneficiary_list()
	{
		try
		{
			$params = get_params();
			
			$iTotal["cnt"]		= 6;
			$iFilteredTotal["cnt"] = 1;
	
			$output = array(
					"sEcho" => intval($_POST['sEcho']),
					"iTotalRecords" => $iTotal["cnt"],
					"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
					"aaData" => array()
			);
			$cnt = 0;

			
	
			$loans = array(
	
					0 => array(
							'beneficiary_name'		=> 'Marian Rizal',
							'relationship' 	=> 'Mother',
							'status'		=> 'Active'
					),
					1 => array(
							'beneficiary_name'		=> 'Dingdong Rizal',
							'relationship' 	=> 'Father',
							'status'		=> 'Active'
					),
					2 => array(
							'beneficiary_name'		=> 'Zia Rizal',
							'relationship' 	=> 'Daugther',
							'status'		=> 'Active'
					)
			);
				
			foreach ($loans as $aRow):
			$cnt++;
			$row = array();
				
			$row[] =  $aRow['beneficiary_name'];
			$row[] =  $aRow['relationship'];
			$row[] =  $aRow['status'];
			
			$action = "<div class='table-actions'>";
			// $action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_VIEW."'')\" data-delay='50'></a>";
			// $action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_ADD."')\" data-delay='50'></a>";
			$action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
	
			$action .= "</div>";
			if($cnt == count($loans))
			{
				$resources['load_js'] = array('modalEffects','classie');
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
	
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
	

	public function get_gsis_beneficiary_list()
	{
		try
		{
			$params = get_params();
			
			$iTotal["cnt"]		= 6;
			$iFilteredTotal["cnt"] = 1;
	
			$output = array(
					"sEcho" => intval($_POST['sEcho']),
					"iTotalRecords" => $iTotal["cnt"],
					"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
					"aaData" => array()
			);
			$cnt = 0;

			
	
			$loans = array(
	
					0 => array(
							'beneficiary_name'	=> 'Marian Rizal',
							'relationship' 		=> 'Mother',
							'status'			=> 'Active'
					),
					1 => array(
							'beneficiary_name'		=> 'Dingdong Rizal',
							'relationship' 	=> 'Father',
							'status'		=> 'Active'
					),
					2 => array(
							'beneficiary_name'		=> 'Zia Rizal',
							'relationship' 	=> 'Daugther',
							'status'		=> 'Active'
					)
			);
				
			foreach ($loans as $aRow):
			$cnt++;
			$row = array();
				
			$row[] =  $aRow['beneficiary_name'];
			$row[] =  $aRow['relationship'];
			$row[] =  $aRow['status'];
			
			$action = "<div class='table-actions'>";
			// $action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_VIEW."'')\" data-delay='50'></a>";
			// $action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_ADD."')\" data-delay='50'></a>";
			$action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
	
			$action .= "</div>";
			if($cnt == count($loans))
			{
				$resources['load_js'] = array('modalEffects','classie');
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
	
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
	


	public function get_other_deduction_list()
	{
	
		try
		{
			$params = get_params();
			
			$iTotal["cnt"]		= 6;
			$iFilteredTotal["cnt"] = 1;
	
			$output = array(
					"sEcho" => intval($_POST['sEcho']),
					"iTotalRecords" => $iTotal["cnt"],
					"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
					"aaData" => array()
			);
			$cnt = 0;
	
			$loans = array(
	
					0 => array(
							'deduction_type'		=> 'School Uniform',
							'start_date' 			=> 'May 12, 2016',
							'deduction_schedule' 	=> 'Mid Month',
							'frequency'				=> 'Monthly'
					),
					1 => array(
							'deduction_type'		=> 'Garments',
							'start_date' 			=> 'May 12, 2016',
							'deduction_schedule' 	=> 'Mid Month',
							'frequency'				=> 'Annually'
					),
					2 => array(
							'deduction_type'		=> 'Fines',
							'start_date' 			=> 'June 01, 2016',
							'deduction_schedule' 	=> 'Month End',
							'frequency'				=> 'Quarterly'
					)
			);
				
			foreach ($loans as $aRow):
			$cnt++;
			$row = array();
				
			$row[] =  $aRow['deduction_type'];
			$row[] =  $aRow['start_date'];
			$row[] =  $aRow['deduction_schedule'];
			$row[] =  $aRow['frequency'];
			
			$action = "<div class='table-actions'>";
			//$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_VIEW."'')\" data-delay='50'></a>";
			//$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_default' onclick=\"modal_init('".ACTION_ADD."')\" data-delay='50'></a>";
			// $action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
	
			$action .= "</div>";
			if($cnt == count($loans))
			{
				$resources['load_js'] = array('modalEffects','classie');
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
	
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

	public function modal_statutory_info($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			 $resources['load_css']	= array(CSS_SELECTIZE);
			 $resources['load_js'] 	= array(JS_SELECTIZE);


		

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('statutory/modals/modal_statutory_info', $data);
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

	public function modal_statutory_bir($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			 $resources['load_css']	= array(CSS_SELECTIZE);
			 $resources['load_js'] 	= array(JS_SELECTIZE);


			// $field 							= array("*") ;
			// $table							= $this->deductions->tbl_param_deductions;
			// $where							= array();
			// $data['deduction_types'] 	    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
		

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('statutory/modals/modal_statutory_bir', $data);
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

	public function modal_statutory_gsis($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			 $resources['load_css']	= array(CSS_SELECTIZE, CSS_DATATABLE);
			 $resources['load_js'] 	= array(JS_SELECTIZE, JS_DATATABLE);


			// $field 							= array("*") ;
			// $table							= $this->deductions->tbl_param_deductions;
			// $where							= array();
			// $data['deduction_types'] 	    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
			
			$resources['datatable'][]	= array('table_id' => 'table_gsis_beneficiary', 'path' => 'main/statutory/get_gsis_beneficiary_list', 'advanced_filter' => TRUE);

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('statutory/modals/modal_statutory_gsis', $data);
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

	public function modal_statutory_pagibig($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			 $resources['load_css']	= array(CSS_SELECTIZE, CSS_DATATABLE);
			 $resources['load_js'] 	= array(JS_SELECTIZE, JS_DATATABLE);

			 $resources['datatable'][]	= array('table_id' => 'table_pagibig_beneficiary', 'path' => 'main/statutory/get_pagibig_beneficiary_list', 'advanced_filter' => TRUE);


			// $field 							= array("*") ;
			// $table							= $this->deductions->tbl_param_deductions;
			// $where							= array();
			// $data['deduction_types'] 	    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
		

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('statutory/modals/modal_statutory_pagibig', $data);
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

	public function modal_statutory_philhealth($action = NULL, $employee_id = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data = array();

			 $resources['load_css']	= array(CSS_SELECTIZE, CSS_DATATABLE);
			 $resources['load_js'] 	= array(JS_SELECTIZE, JS_DATATABLE);

			 $resources['datatable'][]	= array('table_id' => 'table_philhealth_dependent', 'path' => 'main/statutory/get_philhealth_dependent_list', 'advanced_filter' => TRUE);


			// $field 							= array("*") ;
			// $table							= $this->deductions->tbl_param_deductions;
			// $where							= array();
			// $data['deduction_types'] 	    = $this->deductions->get_deduction_data($field, $table, $where, TRUE);
		

			$data['action']			= $action;
			$data['employee_id']	= $employee_id;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;


			$this->load->view('statutory/modals/modal_statutory_philhealth', $data);
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
	

	public function process_statutory()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_peronal_info($params);

			
			Main_Model::beginTransaction();
			$fields 					= array() ;
			$fields['deduction_type']	= $valid_data["deduction_type"];
			$fields['amount']		    = $valid_data["amount"];
			
			if($action == ACTION_ADD)
			{	
				
				$fields['status']			= 1;

				
				$fields['created_by']			= $this->log_user_id;
				$fields['created_date']			= date("Y-m-d H:i:s");

				$table 							= $this->statutory->tbl_employee_deduction_hdr;
				$employee_system_id				= $this->statutory->insert_general_data($table,$fields,TRUE);


				$audit_table[]			= $this->statutory->tbl_employee_deduction_hdr;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "%s has been added to employee records.";
				$audit_activity 		= sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);

				/*RE-INITIALIZE SECURITY VARIABLES*/
				/*Updating from action add to action edit*/
				$edit_salt			= gen_salt();
				$edit_id 			= $this->hash($employee_system_id);
				$token_edit	 		= in_salt($edit_id  . '/' . ACTION_EDIT  . '/' . $module, $edit_salt);
				$reload_url 		= ACTION_EDIT."/".$edit_id ."/".$token_edit."/".$edit_salt."/".$module;
				

				$status = true;
				$message = $this->lang->line('data_saved');


			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 						= array("*") ;
				$table						= $this->statutory->tbl_employee_deduction_hdr;
				$where						= array();
				$key 						= $this->get_hash_key('deduction_hdr_id');
				$where[$key]				= $id;
				$personal_info 				= $this->s->get_general_data($field, $table, $where, FALSE);
				
				$fields['last_modified_by']		= $this->log_user_id;
				$fields['last_modified_date']	= date("Y-m-d H:i:s");

				$where						= array();
				$key 						= $this->get_hash_key('deduction_hdr_id');
				$where[$key]				= $id;
				$table 						= $this->statutory->tbl_employee_deduction_hdr;
				$this->statutory->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->statutory->tbl_employee_deduction_hdr;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($personal_info);
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$activity 				= "%s has been Updated.";
				$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);

				
				$status = true;
				$message = $this->lang->line('data_updated');
			}
			
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
		$data['reload_url'] 	= $reload_url;
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}
	private function _validate_data($params)
	{
		if(EMPTY($params['input_type']))
			throw new Exception('Deduction Type is required.');	

		return $this->_validate_deduction_input ($params);
	}

	private function _validate_deduction_input($params) {
		try {
			
			$validation ['input_type'] = array (
					'data_type' => 'string',
					'name' => 'Deduction Type',
					'max_len' => 50 
			);
			
			return $this->validate_inputs($params, $validation );
		} catch ( Exception $e ) {
			throw $e;
		}
	}


}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */