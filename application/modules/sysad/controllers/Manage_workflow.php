<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_workflow extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('workflow_model', 'workflow', TRUE);
	}
	
	public function index()
	{
		try{
			$data = array();
			$resources = array();
			
			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js'] = array(JS_DATATABLE);
			$resources['datatable'] = array('table_id' => 'workflow_table', 'path' => PROJECT_CORE.'/manage_workflow/get_workflows', 'advanced_filter' => true);
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "System"; 
			$breadcrumbs[$key]		= PROJECT_CORE."/manage_workflow";
			$key					= "Request Workflows"; 
			$breadcrumbs[$key]		= PROJECT_CORE."/manage_workflow";
			set_breadcrumbs($breadcrumbs, TRUE);
			$this->template->load('workflow_list', $data, $resources);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function create()
	{
		try{
			$data = array();
			
			$process_id = $this->uri->segment(4, 0);
			$data["process_id"] = $process_id;
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Create Workflow"; 
			$breadcrumbs[$key]		= PROJECT_CORE."/manage_workflow/create";
			set_breadcrumbs($breadcrumbs, FALSE);
			$this->template->load('workflow', $data);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function get_workflows()
	{
		try{
			$params = get_params();
			
			$center = array("process_id", "num_stages");
			
			$aColumns = array("A.process_id", "A.name", "A.description", "A.num_stages", "CONCAT(B.fname, ' ', B.lname) as creator");
			$bColumns = array("process_id", "name", "description", "num_stages", "creator");
		
			$workflows = $this->workflow->get_workflows($aColumns, $bColumns, $params);
			$iTotal = $this->workflow->total_length();
			$iFilteredTotal = $this->workflow->filtered_length($aColumns, $bColumns, $params);
		
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			
			foreach ($workflows as $aRow):
				$row = array();
				$action = "<div class='table-actions center-align'>";
			
				$process_id = $aRow["process_id"];
				$id = base64_url_encode($process_id);
				$salt = gen_salt();
				$token = in_salt($process_id, $salt);
				$url = base_url().PROJECT_CORE."/manage_workflow/create/".$id."/".$salt."/".$token;
				$delete_action = 'content_delete("delete_workflow_process","'.$id.'")';
				
				for ($i=0; $i<count($bColumns); $i++)
				{
					if(in_array($bColumns[$i], $center)) { 
						$row[] = "<div class='center-align'>".$aRow[ $bColumns[$i] ]."</div>";
					} else {
						$row[] = $aRow[ $bColumns[$i] ];
					}	
				}
							
				// if($this->permission->check_permission(USER_MODULE, ACTION_EDIT))
				$action .= "<a href='".$url."' title='Edit' class='edit'></a>";
				
				// if($this->permission->check_permission(USER_MODULE, ACTION_DELETE))
				$action .= "<a href='javascript:;' onclick='".$delete_action."' title='Delete' class='delete' ></a>";
				
				$action .= "</div>";
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		
			echo json_encode( $output );
		}
		catch(PDOException $e)
		{
			SYSAD_Model::rollback();
		}
		catch(Exception $e)
		{
			$msg = $this->rlog_error($e, TRUE);
		}
	}
}