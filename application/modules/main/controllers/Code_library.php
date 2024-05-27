<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//RLog::info('LINE 2209 =>'.json_encode($fields));
class Code_library extends Main_Controller {
	private $module = MODULE_ROLE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function module($page)
	{
		try
		{

			$data 						= array();
			$resources 					= array();
			$data['page'] 				= $page;
			
			$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATATABLE);

			$resources['load_modal']	= array(
				'modal_code_library'	=> array(
					'controller'		=> strtolower(__CLASS__),
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal',
					'multiple'			=> true,
					'height'			=> '100px',
					'size'				=> 'sm',
					'title'				=> 'Code Library'
				)
			);
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			switch ($page) {
				case CODE_LIBRARY_HUMAN_RESOURCES:
					$key					= "Human Resources"; 
					$breadcrumbs[$key]		= PROJECT_MAIN."/code_library/module/".$page;
				break;

				case CODE_LIBRARY_ATTENDANCE:
					$key					= "Time & Attendance"; 
					$breadcrumbs[$key]		= PROJECT_MAIN."/code_library/module/".$page;
				break;

				case CODE_LIBRARY_PAYROLL:
					$key					= "Payroll"; 
					$breadcrumbs[$key]		= PROJECT_MAIN."/code_library/module/".$page;
				break;

				case CODE_LIBRARY_SYSTEM:
					$key					= "System"; 
					$breadcrumbs[$key]		= PROJECT_MAIN."/code_library/module/".$page;
				break;
			}
			$data['action_id'] 				= ACTION_EDIT;
			$key							= "Code Library"; 
			$breadcrumbs[$key]				= PROJECT_MAIN."/code_library/module/".$page;

			set_breadcrumbs($breadcrumbs, TRUE);

			$this->template->load('code_library/display_code_library_info', $data, $resources);

		}
		catch (PDOException $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
	}

	
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library.php */