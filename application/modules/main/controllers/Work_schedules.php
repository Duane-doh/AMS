<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_schedules extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data =  array();
		$resources = array();
		$modals = array(
				'modal_work_schedule' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal',
						'multiple'		=> true,
						'height'		=> '430px',
						'size'			=> 'md',
						'title'			=> 'Work Schedule'
				)
		);

		$resources['load_modal']	= $modals;
		$resources['load_css'] 		= array(CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_DATATABLE);
		$resources['datatable'][]	= array('table_id' => 'table_work_schedules', 'path' => 'main/work_schedules/get_work_schedules');
			
		$this->template->load('work_schedules', $data, $resources);
		
	}
	public function get_work_schedules()
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
						'employee_no'	=> '198761-1',
						'employee'		=> 'Juan Dela Cruz',
						'office' 		=> 'Bureau Of Quarantine',
						'status'		=> 'Active'
					),
				1 => array(
						'employee_no'	=> '198534-2',
						'employee'		=> 'Glenn Espejo',
						'office' 		=> 'Department of Health - Office of the Secretary',
						'status'		=> 'Active'
					),
				2 => array(
						'employee_no'	=> '158761-2',
						'employee'		=> 'Raji von Arx',
						'office' 		=> 'Central Office',
						'status'		=> 'Active'
					)	
			);
			
			foreach ($loans as $aRow):
				$cnt++;
				$row = array();

				$row[] =  $aRow['employee_no'];
				$row[] =  $aRow['employee'];	
				$row[] =  $aRow['office'];	
				$row[] =  $aRow['status'];
				
				$action = "<div class='table-actions'>";
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_work_schedule' onclick=\"modal_work_schedule_init('".ACTION_VIEW."')\" data-delay='50'></a>";
				$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_work_schedule' onclick=\"modal_work_schedule_init('".ACTION_EDIT."')\" data-delay='50'></a>";
				$action .= "<a href='javascript:;' onclick='content_delete()' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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
	public function modal($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data 					= array();
			$data['action_id'] 		= $action_id;
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE,CSS_LABELAUTY);
			$resources['load_js']	= array(JS_DATETIMEPICKER,JS_SELECTIZE,JS_LABELAUTY);
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
		$this->load->view('modals/modal_work_schedule', $data);
		$this->load_resources->get_resource($resources);
	}
	
}


/* End of file Work_schedules.php */
/* Location: ./application/modules/main/controllers/Work_schedules.php */