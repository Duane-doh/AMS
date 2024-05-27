<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audit_log extends SYSAD_Controller {
	
	public function __construct()
	{
		$this->load->model('audit_log_model', 'audit_log', TRUE);
		$this->load->model('systems_model', 'systems', TRUE);
	}
	
	public function index()
	{	
		$this->filter();
	}
	
	
	public function filter($system_code = NULL)
	{	
		$data = $resources = array();
		
		// $permission = $this->permission->check_permission(MODULE_AUDIT_TRAIL, ACTION_VIEW, TRUE);
		$data['systems'] = $this->systems->get_systems();
		
		$resources['load_css'] = array(CSS_DATATABLE, CSS_SELECTIZE);
		$resources['load_js'] = array(JS_DATATABLE, JS_SELECTIZE,'tableExport','jquery.base64','html2canvas','jspdf/libs/sprintf','jspdf/jspdf','jspdf/libs/base64');
		$resources['datatable'] = array('table_id' => 'audit_log_table', 'path' => PROJECT_CORE . '/audit_log/get_audit_log/' .$system_code,'advanced_filter'=>true);
		
		if(!IS_NULL($system_code)){
			$data['system_code'] = $system_code;
		}
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "User Management"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/audit_log";
		$key					= "Audit Trail"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/audit_log";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('audit_log', $data, $resources);
	}
	
	public function get_audit_log($system_code = NULL)
	{
		$params = get_params();
		
		if(!IS_NULL($system_code))
			$params["system_code"] = $system_code;
		
		$cnt = 0;
	
		$aColumns = array("A.*", "B.photo", "DATE_FORMAT(activity_date,'%m/%d/%Y %T') activity_date", "IF(A.user_id = 0, B.fname ,CONCAT(B.fname,' ',B.lname)) name", "C.module_name");
		$bColumns = array("name", "module_name", "activity", "activity_date", "ip_address");
	
		$audit_log = $this->audit_log->get_audit_log_list($aColumns, $bColumns, $params);
		$iTotal = $this->audit_log->total_length();
		$iFilteredTotal = $this->audit_log->filtered_length($aColumns, $bColumns, $params);
	
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"iTotalRecords" => $iTotal["cnt"],
			"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
			"aaData" => array()
		);
	
		foreach ($audit_log as $aRow):
			$cnt++;
			$row = array();
			$action = "";
		
			$audit_log_id = $aRow["audit_trail_id"];
			$id = base64_url_encode($audit_log_id);
			$salt = gen_salt();
			$token = in_salt($audit_log_id, $salt);			
			$url = $id."/".$salt."/".$token;
			$img_src = (!EMPTY($aRow["photo"]))? PATH_USER_UPLOADS . $aRow["photo"] : PATH_IMAGES . "avatar.jpg";
			
			for ( $i=0 ; $i<count($bColumns) ; $i++ )
			{
				
				$avatar = ($i == 0) ? '<img class="avatar" src="'.base_url(). $img_src.'" /> ' : '';
				$row[] = $avatar . $aRow[ $bColumns[$i] ];
			}
				
			$action = "<div class='table-actions'><a class='md-trigger view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50'  data-modal='modal_audit_log' onclick=\"modal_init('".$url."')\"></a></div>";
			
			if($cnt == count($audit_log)){
				$resources['load_js'] = array('classie','modalEffects');
				$action.= $this->load_resources->get_resource($resources, TRUE);
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
			}
			
			$row[] = $action;
				
			$output['aaData'][] = $row;
		endforeach;
		
		echo json_encode( $output );
	}
	
	
	public function modal($id = NULL, $salt = NULL, $token = NULL){
		
		try{
			$data = array();
			
			if(!IS_NULL($id)){
				$id = base64_url_decode($id);
				
				// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
				check_salt($id, $salt, $token);
			
				$data["audit_trail_id"] = $id;
				$data["audit_trail"] = $this->audit_log->get_audit_log($id);
				$data["audit_trail_detail"] = $this->audit_log->get_audit_log_details($id);
			}	
			
			$resources['load_js'] = array('jquery.jscrollpane');
			$this->load->view("modals/audit_log", $data);
			$this->load_resources->get_resource($resources);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}	
	}
}


/* End of file audit_log.php */
/* Location: ./application/modules/sysad/controllers/audit_log.php */