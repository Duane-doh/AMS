<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_monthly_attendance extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'reports_ta');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($params,$with_signatories = true)
	{
		try
		{
			$data                 	= array();
			$data['with_signatories'] = $with_signatories;
			$mra_details      		= $this->reports_ta->get_monthly_report_of_attendance($params);
			if($with_signatories)
			{
				// SIGNATORIES
				$employee_id           	= $params['certified_by'];
				$data['certified_by']  	= $this->cm->get_report_signatory_details($employee_id);
				$employee_id            = $params['prepared_by'];
				$data['prepared_by']  	= $this->cm->get_report_signatory_details($employee_id);
			}

			$field                             	= array("DATE_FORMAT(date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(date_to,'%m/%d/%Y') as date_to") ;
			$table                             	= $this->reports_ta->tbl_attendance_period_hdr;
			$where                             	= array();
			$where["attendance_period_hdr_id"] 	= $params['mra_attendance_period'];
			$data['period_detail']				= $this->reports_ta->get_reports_data($field, $table, $where, FALSE);


			$old_office       = "";
			$old_employee     = "";
			$office_counter   = 0;
			$employee_counter = 0;
			$detail_counter   = 0;

			$mra_array = array();
			$attendances_statuses = array(ATTENDANCE_STATUS_LEAVE_WP,ATTENDANCE_STATUS_LEAVE_WOP,ATTENDANCE_STATUS_ABSENT);
			foreach ($mra_details as $key => $mra) 
			{ 
				if($mra['Charged_to_VL_flag'])
				{
					$mra['vacation_leave'] = 0;
					$mra['remarks'] = 'Sick Leave';
				}
				if($mra['attendance_status_id'] == ATTENDANCE_STATUS_REGULAR_HOLIDAY || $mra['attendance_status_id'] == ATTENDANCE_STATUS_SPECIAL_HOLIDAY)
				{
					$mra['sick_leave']  = 0;
					$mra['vacation_leave']  = 0;
					$mra['remarks']  = 0;
				}
				if(!EMPTY($mra['sick_leave']) OR !EMPTY($mra['vacation_leave']) OR !EMPTY($mra['undertime_hour']) OR !EMPTY($mra['undertime_min']) OR !EMPTY($mra['remarks']) OR in_array($mra['attendance_status_id'], $attendances_statuses))
				{

					if($mra['name'] != $old_office)
					{
						$employee_counter = 0;
						$office_counter++;
						$old_office = $mra['name'];
						$mra_array[$office_counter]['office'] = $mra['name'];
					}
					if($mra['employee_name'] != $old_employee)
					{
						$old_employee = $mra['employee_name'];
						$employee_counter++;

						$result	= $this->reports_ta->get_mra_leave_summary($mra['employee_id'],$mra['attendance_period_hdr_id']);
						$result_leaves	= $this->reports_ta->get_mra_leave_summary_v2($mra['employee_id'],$mra['attendance_period_hdr_id']);
						$_result_absents	= $this->reports_ta->get_absent_total_summary($mra['employee_id'],$mra['attendance_period_hdr_id']);
						$_total_sick_leave_wp = 0;
						$_total_vacation_leave_wp = 0;
						$_total_sick_leave_wop = 0;
						$_total_vacation_leave_wop = 0;
						$_sl_charged_to_vl_flag = "FALSE";
						foreach($result_leaves as $result_leave)
						{
							if(strtoupper($result_leave['remarks']) == "SL CHARGED TO VL")
							{				
								continue;
							}
							if($result_leave['attendance_status_id'] == LEAVE_WP)
							{
								if(!empty($result_leave['leave_earned_used']))
								{
									if($result_leave['leave_type_id'] == LEAVE_TYPE_SICK)
									{
										$_total_sick_leave_wp++;
									}
									if($result_leave['leave_type_id'] != LEAVE_TYPE_SICK)
									{
										$_total_vacation_leave_wp++;
									}
								}
							}
							if($result_leave['attendance_status_id'] == LEAVE_WOP)
							{
								if(!empty($result_leave['leave_wop']))
								{
									if($result_leave['leave_type_id'] == LEAVE_TYPE_SICK)
									{
										$_total_sick_leave_wop++;
									}
									if($result_leave['leave_type_id'] != LEAVE_TYPE_SICK)
									{									
										$_total_vacation_leave_wop++;
									}	
								}
							}
						}
						$leavecard_lwop	= $this->reports_ta->get_leavecard_wop_summary($mra['employee_id'],$mra['attendance_period_hdr_id']);

						$sixty_minutes = 60;
						$total_hour    = floor($result['undertime_min']/$sixty_minutes) + $result['undertime_hour'];
						$total_min     = ($result['undertime_min']%$sixty_minutes);
						if(!empty($total_hour))
						{
							$point_equivalent = $this->reports_ta->get_comp_table_equivalent($total_hour,TA_COMP_TABLE_HOUR);
							$hour_equivalent = $point_equivalent['point_equivalent'];
						}
						else
						{
							$hour_equivalent = 0;
						}
						if(!empty($total_min))
						{
							$point_equivalent = $this->reports_ta->get_comp_table_equivalent($total_min,TA_COMP_TABLE_MINUTE);
							$min_equivalent = $point_equivalent['point_equivalent'];
						}
						else
						{
							$min_equivalent = 0;
						}
						$equivalent    = $hour_equivalent + $min_equivalent;
						$mra_array[$office_counter]['employee'][$employee_counter]['sick_leave_wp']     = $_total_sick_leave_wp;
						$mra_array[$office_counter]['employee'][$employee_counter]['vacation_leave_wp'] = $_total_vacation_leave_wp;
						$mra_array[$office_counter]['employee'][$employee_counter]['sick_leave_wop']     = $_total_sick_leave_wop;
						$mra_array[$office_counter]['employee'][$employee_counter]['vacation_leave_wop'] = $_total_vacation_leave_wop;
						$mra_array[$office_counter]['employee'][$employee_counter]['total_hour']     = $total_hour;
						$mra_array[$office_counter]['employee'][$employee_counter]['total_min']      = $total_min;
						$mra_array[$office_counter]['employee'][$employee_counter]['equivalent']     = $equivalent;
						
						$mra_array[$office_counter]['employee'][$employee_counter]['employee_name']  = $mra['employee_name'];
						$mra_array[$office_counter]['employee'][$employee_counter]['absents_wo_official_leave_counter']  = $_result_absents['absents'];
						
						$additional_lwop_remarks = '';

						if($leavecard_lwop['lwop_ut_hr'] > 0 OR $leavecard_lwop['lwop_ut_min'] > 0)
						{
							$additional_lwop_remarks = '';
							$additional_lwop_remarks .= ($leavecard_lwop['lwop_ut_hr'] > 0) ? $leavecard_lwop['lwop_ut_hr']." hrs ":"";
							$additional_lwop_remarks .= ($leavecard_lwop['lwop_ut_min'] > 0) ? $leavecard_lwop['lwop_ut_min']." mins":"";
							$additional_lwop_remarks .= " Additional LWOP";
						}
						$mra_array[$office_counter]['employee'][$employee_counter]['additional_lwop_remarks']  = $additional_lwop_remarks;
					}
					if(in_array($mra['attendance_status_id'], $attendances_statuses))
					{
						if(strtoupper($mra['attendance_status_name']) == "LEAVE WP")
						{

							if( strtoupper($_sl_charged_to_vl_flag) == "TRUE")
							{
								$mra['remarks'] 	= "SICK LEAVE WP";
							}
							else{
								$mra['remarks']      = strtoupper($mra['remarks']) . " WP";
							}
						}
						if(strtoupper($mra['attendance_status_name']) == "LEAVE WOP")
						{

							if( strtoupper($_sl_charged_to_vl_flag) == "TRUE")
							{
								$mra['remarks'] 	= "SICK LEAVE WOP";
							}
							else{
								if($mra['sick_leave'] > 0)
								{
									$mra['remarks']      = "SICK LEAVE WOP";
								}
								elseif($mra['vacation_leave'] > 0)
								{
									$mra['remarks']      = "VACATION LEAVE WOP";
								}
								else
								{
									$mra['remarks']      = "LEAVE WOP";
								}
							}
						}
						
					}
					$mra_array[$office_counter]['employee'][$employee_counter]['mra_detail'][] = $mra;
				}
				
				//MARVIN
				else
				{
					$_absents_wo_official_leave_counter = 0;
					if($mra['attendance_status_id'] == ABSENT)
					{
						$_absents_wo_official_leave_counter ++;
					}
					if($mra['name'] != $old_office)
					{
						$employee_counter = 0;
						$office_counter++;
						$old_office = $mra['name'];
						$mra_array[$office_counter]['office'] = $mra['name'];
					}
					if($mra['employee_name'] != $old_employee)
					{
						$old_employee = $mra['employee_name'];
						$employee_counter++;

						$result	= $this->reports_ta->get_mra_leave_summary($mra['employee_id'],$mra['attendance_period_hdr_id']);
						$result_leaves	= $this->reports_ta->get_mra_leave_summary_v2($mra['employee_id'],$mra['attendance_period_hdr_id']);
						$_result_absents	= $this->reports_ta->get_absent_total_summary($mra['employee_id'],$mra['attendance_period_hdr_id']);
						$_total_sick_leave_wp = 0;
						$_total_vacation_leave_wp = 0;
						$_total_sick_leave_wop = 0;
						$_total_vacation_leave_wop = 0;
						$_sl_charged_to_vl_flag = "FALSE";
						foreach($result_leaves as $result_leave)
						{
							if(strtoupper($result_leave['remarks']) == "SL CHARGED TO VL")
							{				
								continue;
							}
							if($result_leave['attendance_status_id'] == LEAVE_WP)
							{
								if(!empty($result_leave['leave_earned_used']))
								{
									if($result_leave['leave_type_id'] == LEAVE_TYPE_SICK)
									{
										$_total_sick_leave_wp++;
									}
									if($result_leave['leave_type_id'] != LEAVE_TYPE_SICK)
									{
										$_total_vacation_leave_wp++;
									}
								}
							}
							if($result_leave['attendance_status_id'] == LEAVE_WOP)
							{
								if(!empty($result_leave['leave_wop']))
								{
									if($result_leave['leave_type_id'] == LEAVE_TYPE_SICK)
									{
										$_total_sick_leave_wop++;
									}
									if($result_leave['leave_type_id'] != LEAVE_TYPE_SICK)
									{									
										$_total_vacation_leave_wop++;
									}	
								}
							}
						}
						$leavecard_lwop	= $this->reports_ta->get_leavecard_wop_summary($mra['employee_id'],$mra['attendance_period_hdr_id']);

						$sixty_minutes = 60;
						$total_hour    = floor($result['undertime_min']/$sixty_minutes) + $result['undertime_hour'];
						$total_min     = ($result['undertime_min']%$sixty_minutes);
						if(!empty($total_hour))
						{
							$point_equivalent = $this->reports_ta->get_comp_table_equivalent($total_hour,TA_COMP_TABLE_HOUR);
							$hour_equivalent = $point_equivalent['point_equivalent'];
						}
						else
						{
							$hour_equivalent = 0;
						}
						if(!empty($total_min))
						{
							$point_equivalent = $this->reports_ta->get_comp_table_equivalent($total_min,TA_COMP_TABLE_MINUTE);
							$min_equivalent = $point_equivalent['point_equivalent'];
						}
						else
						{
							$min_equivalent = 0;
						}
						$equivalent    = $hour_equivalent + $min_equivalent;

						$mra_array[$office_counter]['employee'][$employee_counter]['sick_leave_wp']     = $_total_sick_leave_wp;
						$mra_array[$office_counter]['employee'][$employee_counter]['vacation_leave_wp'] = $_total_vacation_leave_wp;
						$mra_array[$office_counter]['employee'][$employee_counter]['sick_leave_wop']     = $_total_sick_leave_wop;
						$mra_array[$office_counter]['employee'][$employee_counter]['vacation_leave_wop'] = $_total_vacation_leave_wop;
						$mra_array[$office_counter]['employee'][$employee_counter]['total_hour']     = $total_hour;
						$mra_array[$office_counter]['employee'][$employee_counter]['total_min']      = $total_min;
						$mra_array[$office_counter]['employee'][$employee_counter]['equivalent']     = $equivalent;
						
						$mra_array[$office_counter]['employee'][$employee_counter]['employee_name']  = $mra['employee_name'];
						$mra_array[$office_counter]['employee'][$employee_counter]['absents_wo_official_leave_counter']  = $_result_absents['absents'];
						
						$additional_lwop_remarks = '';

						if($leavecard_lwop['lwop_ut_hr'] > 0 OR $leavecard_lwop['lwop_ut_min'] > 0)
						{
							$additional_lwop_remarks = '';
							$additional_lwop_remarks .= ($leavecard_lwop['lwop_ut_hr'] > 0) ? $leavecard_lwop['lwop_ut_hr']." hrs ":"";
							$additional_lwop_remarks .= ($leavecard_lwop['lwop_ut_min'] > 0) ? $leavecard_lwop['lwop_ut_min']." mins":"";
							$additional_lwop_remarks .= " Additional LWOP";
						}
						$mra_array[$office_counter]['employee'][$employee_counter]['additional_lwop_remarks']  = $additional_lwop_remarks;
					}
					if(in_array($mra['attendance_status_id'], $attendances_statuses))
					{
						if(strtoupper($mra['attendance_status_name']) == "LEAVE WP")
						{
							if( strtoupper($_sl_charged_to_vl_flag) == "TRUE")
							{
								$mra['remarks'] 	= "SICK LEAVE WP";
							}
							else{
								$mra['remarks']      = strtoupper($mra['remarks']) . " WP";
							}

						}
						if(strtoupper($mra['attendance_status_name']) == "LEAVE WOP")
						{

							if( strtoupper($_sl_charged_to_vl_flag) == "TRUE")
							{
								$mra['remarks'] 	= "SICK LEAVE WOP";
							}
							else{
								if($mra['sick_leave'] > 0)
								{
									$mra['remarks']      = "SICK LEAVE WOP";
								}
								elseif($mra['vacation_leave'] > 0)
								{
									$mra['remarks']      = "VACATION LEAVE WOP";
								}
								else
								{
									$mra['remarks']      = "LEAVE WOP";
								}
							}
						}
					}
					$mra_array[$office_counter]['employee'][$employee_counter]['mra_detail'][] = "";
				}
			}
			$data['mra_array'] = $mra_array;
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

		return $data;			
	}

}


/* End of file Ta_leave_card.php */
/* Location: ./application/modules/main/controllers/reports/ta/Ta_leave_card.php */