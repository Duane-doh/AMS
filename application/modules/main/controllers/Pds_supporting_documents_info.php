<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_supporting_documents_info extends Main_Controller {

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
	}	

	public function get_pds_supporting_documents_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			$resources['load_css'][] 	= CSS_DATETIMEPICKER;
			$resources['load_js'][] 	= JS_DATETIMEPICKER;
			$resources['load_css'][] 	= CSS_DATATABLE;
			$resources['load_js'][] 	= JS_DATATABLE;

			//GET PERVEIOUS RECORD
			$resources['datatable'][]	= array('table_id' => 'table_supporting_documents', 'path' => 'main/pds_supporting_documents_info/get_supporting_documents_list', 'advanced_filter' => true);
			
			$data['nav_page']			= PDS_SUPPORTING_DOCUMENTS;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
		}

		$this->load->view('pds/tabs/supporting_documents', $data);
		$this->load_resources->get_resource($resources);		
	}

	public function get_supporting_documents_list()
	{
		try
		{
			$params               = get_params();

			$aColumns             = array("D.supp_doc_type_name, A.date_received, A.remarks");
			$bColumns             = array("D.supp_doc_type_name, A.date_received, A.remarks");
			
			$supporting_documents = $this->pds->get_supporting_documents_list($aColumns, $bColumns, $params);
			$iTotal               = $this->pds->supporting_documents_total_length();
			$iFilteredTotal       = $this->pds->supporting_documents_filtered_length($aColumns, $bColumns, $params);
			
			$output      			   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module                = $this->session->userdata("pds_module");
			$pds_action            = $this->session->userdata("pds_action");
			/*
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			*/

			$cnt = 0;			
			foreach ($supporting_documents as $aRow):
				$cnt++;
				$row 	= array();
				$action = "";

				$row[] 	= strtoupper($aRow['supp_doc_type_name']);
				$row[] 	= '<center>' . format_date($aRow['date_received']) . '</center>';
				$row[] 	= strtoupper($aRow['remarks']);

				$action = "<div class='table-actions'>";
				
				// $action        .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_supporting_documents' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_supporting_documents_init()\"></a>";
				// $delete_action = 'content_delete("ID", "'.$url_delete.'")';
				// $action        .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				$action .= "</div>";				
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;			
		}
		catch (Exception $e)
		{
			$output      		  	   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}

		echo json_encode( $output );
	}

	public function modal_supporting_documents()
	{
		try
		{
			$data = array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$this->load->view('pds/modals/modal_supporting_documents', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */