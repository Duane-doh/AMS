<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$params	= get_params();
		$output_dir = $params['dir'];		
		//$cmd = 'icacls '.SERVER_UPLOAD_FOLDER.' /grant "Everyone":(OI)(CI)F';
		
		if(!is_dir($output_dir))
		{
		  mkdir($output_dir,0777,TRUE);
		}
		
		if(isset($params["file"]))
		{
			$ret = array();

			$error =$params["file"]["error"];
			//You need to handle both cases
			//If any browser does not support serializing of multiple files using FormData() 
			
			if(!is_array($params["file"]["name"])) //single file
			{
				$fileName = str_replace(" ","",$params["file"]["name"]);
				$extension = pathinfo($fileName, PATHINFO_EXTENSION);
				$fileName = pathinfo($fileName, PATHINFO_FILENAME);
				$fileName = preg_replace('/[^A-Za-z0-9]/u','', strip_tags($fileName));
				$newfilename = $fileName.'_'.date('His').'.'.$extension;
				move_uploaded_file($params["file"]["tmp_name"],$output_dir.$newfilename);
				//exec($cmd);
				$ret[]= $newfilename;				
			}
			else  //Multiple files, file[]
			{
				$fileCount = count($params["file"]["name"]);
				  
				for($i=0; $i < $fileCount; $i++)
				{
						$fileName = str_replace(" ","_",$params["file"]["name"][$i]);
						$newfilename = preg_replace('/[^A-Za-z0-9 _.]/u','', strip_tags($fileName));
						move_uploaded_file($params["file"]["tmp_name"][$i],$output_dir.$newfilename);
						exec($cmd);
						
						$ret[]= $newfilename;
				}
			}
			
			echo json_encode($ret);
		 }
	}
	
	public function existing_files()
	{
		$params	= get_params();
		$output_dir = $params['dir'];
		$files = scandir($output_dir);
		
		$db_file = $params['file'];
		
		$ret= array();
		
		if(ISSET($db_file)){
			foreach($files as $file)
			{
				if($file == "." || $file == "..")
					continue;
				
				if($file == $db_file)
					$ret[]=$file;
			}
		}

		echo json_encode($ret);
	}
	
	public function delete($params = array())
	{
		$params	= (!EMPTY($params))? $params : get_params();
		$output_dir = $params['dir'];
		
		if(isset($params["op"]) && $params["op"] == "delete" && isset($params['name']))
		{
			$fileName = $params['name'];
			$fileName = str_replace("..",".",$fileName); //required. if somebody is trying parent folder files	
			$filePath = $output_dir. $fileName;
			if (file_exists($filePath))
				unlink($filePath);
			
			if(!ISSET($params["no_echo"]))
				echo "Deleted File ".$fileName."<br>";
		}	
	}
	
	public function get_files()
	{
		$params	= get_params();
		$info = array();
		$files_arr = array();
		
		switch ($params["module"])
		{
			case "press_release":
				$path = PATH_PR_UPLOADS; 
				$params["table"] = "press_release";
				$params["primary"] = "press_release_id";
			break;	
			case "events":
				$params["table"] = "event";
				$params["primary"] = "event_id";
			break;	
			case "applications":
				$path = PATH_APPLICATIONS_UPLOADS;
				$params["table"] = "app";
				$params["primary"] = "app_id";
			break;
			case "announcement":
				$path = PATH_ANNOUNCEMENT_UPLOADS; 
				$params["table"] = "announcement";
				$params["primary"] = "announcement_id";
			break;
			case "issuances":
				$path = PATH_ISSUANCE_UPLOADS;
				$params["table"] = "issuance";
				$params["primary"] = "issuance_id";
			break;
			case "downloads":
				$path = PATH_DOWNLOADS_UPLOADS;
				$params["table"] = "file";
				$params["primary"] = "file_id";
			break;
			case "basic":
				$path = PATH_PAGE_UPLOADS;
				$params["table"] = "page_file";
				$params["primary"] = "page_id";
			break;
			case "item":
				$path = PATH_ITEM_UPLOADS;
				$params["table"] = "item";
				$params["primary"] = "item_id";
			break;
			case "group_project":
				$path = PATH_PROJECT_UPLOADS;
				$params["table"] = "group_project";
				$params["primary"] = "group_project_id";
			break;
			case "wikis":
				$path = PATH_WIKI_UPLOADS;
				$params["table"] = "wiki";
				$params["primary"] = "wiki_id";
			break;
		}
		
		$files = $this->uploads->get_files($params);
		
		if($params["module"] == "basic"){
			$params["table"] = "page";
			$files2 = $this->uploads->get_files($params);
		}
		
		if(!EMPTY($files)){
			foreach ($files as $file):
				$files_arr[] = $file["system_file_name"];
			endforeach;
			
			if($params["module"] == "events"){
				$path = ($files[0]["type"] == "EVENT")? PATH_EVENT_UPLOADS : PATH_TRAINING_UPLOADS;
			} 
		}
		
		if(ISSET($files2) && !EMPTY($files2)){
			foreach ($files2 as $file2):
				$files_arr[] = $file2["system_featured_image"];
			endforeach;
		}
		
		$info["files_arr"] = $files_arr;
		$info["path"] = $path;
		
		echo json_encode($info);
		
	}
}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */