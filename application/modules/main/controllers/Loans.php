<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loans extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data = array();
		$resources = array();
		$modals = array(
				'modal_loan' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal',
						'multiple'		=> true,
						'height'		=> '160px',
						'size'			=> 'md',
						'title'			=> 'Loan'
				)
		);
		$resources['load_modal']	= $modals;
		$resources['load_css'] 		= array('jquery.dataTables','selectize.default');
		$resources['load_js'] 		= array('jquery.dataTables.min','selectize');
		$resources['datatable'][]	= array('table_id' => 'loans_list', 'path' => 'main/loans/get_loans_list');
		
		$this->template->load('loans', $data, $resources);
		
	}

	public function get_loans_list()
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
						'loan_type'		=> 'Housing Loan',
						'start_date' 	=> 'May 12, 2016',
						'end_date'		=> 'December 12, 2016',
						'amount'		=> '25 000',
						'status'		=> 'Active'
					),
				1 => array(
						'loan_type'		=> 'Salary Loan',
						'start_date' 	=> 'June 15, 2015',
						'end_date'		=> 'January 12, 2016',
						'amount'		=> '30 000',
						'status'		=> 'Inactive'
					),
				2 => array(
						'loan_type'		=> 'Housing Loan',
						'start_date' 	=> 'Sept 12, 2014',
						'end_date'		=> 'May 30, 2015',
						'amount'		=> '45 000',
						'status'		=> 'Inactive'
					)	
			);
			
			foreach ($loans as $aRow):
				$cnt++;
				$row = array();
				$row[] = $aRow['loan_type'];
				$row[] = $aRow['start_date'];
				$row[] = $aRow['end_date'];
				$row[] = $aRow['amount'];
				$row[] = $aRow['status'];
				$action = "<div class='table-actions'>";
			
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_loan' onclick=\"modal_loan_init('".ACTION_VIEW."')\" data-delay='50'></a>";
				$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_loan' onclick=\"modal_loan_init('".ACTION_ADD."')\" data-delay='50'></a>";

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

	public function modal($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data 					= array();
			$resources['load_js'] 	= array('jquery.datetimepicker');
			$resources['load_css']	= array('jquery.datetimepicker');
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
		$this->load->view('modals/modal_loan', $data);
		$this->load_resources->get_resource($resources);
	}
	
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */