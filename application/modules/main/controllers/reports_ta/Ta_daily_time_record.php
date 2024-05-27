<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_daily_time_record extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'rm');
		//marvin
		//for work schedule
		$this->load->model('daily_time_record_model', 'dtr');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data        = array();
			
			//marvin
			//fix the parameter
			$employee_id = !empty($params['employee_filtered']) ? $params['employee_filtered'] : $params['employee']['employee_id'];				
			
			$office_id   = $params['office_filtered'];
			$start_date  = date("Y-m-d", strtotime($params['date_range_from']));
			$end_date    = date("Y-m-d", strtotime($params['date_range_to']));

			$max_end_date =  date("Y-m-t", strtotime($start_date));
			if($max_end_date < $end_date)
			{
				$end_date =	$max_end_date;	
			}
			$data['year_month'] = date('F Y',strtotime($start_date));
			
			$data['date_display_start']  = date("j", strtotime($start_date));
			$data['date_display_end']    = date("j", strtotime($end_date));

			$data['date_from']  = date("m/d/Y", strtotime($start_date));
			$data['date_to']    = date("m/d/Y", strtotime($end_date));

			$employee_list      = $this->rm->get_filtered_employee_per_office($office_id,$employee_id);
			
			$late_undertime = modules::load('main/attendance_late_undertime');
			if($employee_list)
			{
				
				foreach ($employee_list as $key => $employee) {

					$data['employee_name'][$key] = $employee['employee_name'];
					$time_logs               = $this->rm->get_print_dtr_list($employee['employee_id'],$start_date,$end_date);		
					//marvin
					//add work schedule in dtr
					// $this->load->model('daily_time_record_model', 'dtr');

					// $data['work_schedule'][$key] = $this->dtr->get_employee_work_schedule($employee['employee_id'],$end_date);
					//Added validation if employee has multiple work schedule - davcorrea
					//=================START=============================
					$multipleSchedule = $this->dtr->if_employee_has_multiple_work_schedule($employee['employee_id'],$params['date_range_from'],$end_date);
					if($multipleSchedule)
					{
						$data['work_schedule'][$key] = $this->dtr->get_employee_work_schedule($employee['employee_id'],$params['date_range_from'],$end_date);
						$data['work_schedule'][$key]['work_schedule_name'] = "With Multiple Work Schedule";
					}
					else
					{
					$data['work_schedule'][$key] = $this->dtr->get_employee_work_schedule($employee['employee_id'],$end_date);
					}
					//=================END=====================
					// Added function to reflect rest days in DTR : davcorrea 09/27/2023
					// =====================START =================================
					$restdays = $this->dtr->get_employee_rest_days($employee['employee_id'],$params['date_range_from'],$end_date);
					
					//=================END=====================
				
					$dtr                     = array();
					
					$total_undertime_hour = 0;
					$total_undertime_min = 0;
					if($time_logs)
					{
						foreach ($time_logs as $time_log) {
							
							$result            = $late_undertime->check_late_undertime($time_log);

							$day               = $time_log['attendance_day'];

							
							// $temp_undertime_hr = ($result['undertime_hour'] > 0 AND $result['undertime_hour'] < 24) ? $result['undertime_hour'] : 0;
							// $temp_tardiness_hr = ($result['tardiness_hour'] > 0 AND $result['tardiness_hour'] < 24) ? $result['tardiness_hour'] : 0;
							
							// $temp_undertime_mn = ($result['undertime_min'] > 0 AND $result['undertime_hour'] < 24) ? $result['undertime_min'] : 0;
							// $temp_tardiness_mn = ($result['tardiness_min'] > 0 AND $result['tardiness_hour'] < 24) ? $result['tardiness_min'] : 0;

							// marvin : fix absent in diff work schedule : Start
							$temp_undertime_hr = ($result['undertime_hour'] > 0 AND $result['undertime_hour'] <= 24) ? $result['undertime_hour'] : 0;
							$temp_tardiness_hr = ($result['tardiness_hour'] > 0 AND $result['tardiness_hour'] <= 24) ? $result['tardiness_hour'] : 0;
							
							$temp_undertime_mn = ($result['undertime_min'] > 0 AND $result['undertime_hour'] <= 24) ? $result['undertime_min'] : 0;
							$temp_tardiness_mn = ($result['tardiness_min'] > 0 AND $result['tardiness_hour'] <= 24) ? $result['tardiness_min'] : 0;
							// marvin : fix absent in diff work schedule : end

							$total_temp_dec_hr = 0;
							$total_temp_dec_mn = 0;
							$total_temp_dec_mn = $temp_undertime_mn + $temp_tardiness_mn;
							
							$total_temp_dec_hr = $temp_undertime_hr + $temp_tardiness_hr + floor($total_temp_dec_mn / 60);
							$total_temp_dec_mn = ($total_temp_dec_mn % 60);


							if(!EMPTY($time_log['attendance_status_name']))
							{
								$dtr[$day]['attendance_status_name'] = $time_log['attendance_status_name'];			
								$total_temp_dec_hr = 0;
								$total_temp_dec_mn = 0;
							}
							$attendance_day           = date('D',strtotime($time_log['attendance_date']));
							
							// switch (strtolower($attendance_day)) {
								// case 'sat':
									// $dtr[$day]['attendance_status_name'] = "***SATURDAY***";
									// break;
								// case 'sun':
									// $dtr[$day]['attendance_status_name'] = "***SUNDAY***";										
									// break;
							// }
							
							/*========================================= MARVIN : START : FIX LATE/UNDERTIME IN WEEKENDS =========================================*/
							switch (strtolower($attendance_day))
							{
								case 'sat':
									if(EMPTY($time_log['time_in']) AND EMPTY($time_log['break_out']) AND EMPTY($time_log['break_in']) AND EMPTY($time_log['time_out']))
									{
										$dtr[$day]['attendance_status_name'] = "***SATURDAY***";										
									}
									break;
								case 'sun':
									if(EMPTY($time_log['time_in']) AND EMPTY($time_log['break_out']) AND EMPTY($time_log['break_in']) AND EMPTY($time_log['time_out']))
									{																		
										$dtr[$day]['attendance_status_name'] = "***SUNDAY***";										
									}
									break;
							}
							/*========================================= MARVIN : END : FIX LATE/UNDERTIME IN WEEKENDS =========================================*/
							// Added function to reflect rest days : davcorrea : 09/27/2023 
							// ===================START ==========================
							foreach ($restdays as $i => $restday)
							{
								//davcorrea multiple days for rest day
								if(EMPTY($restday['end_date']))
								{
									$restday['end_date'] = $time_log['attendance_date'];
								}
								if($time_log['attendance_date']<= $restday['end_date'] && $time_log['attendance_date'] >= $restday['start_date'])
								{
									$dtr[$day]['attendance_status_name'] = "***REST DAY***";
								}
							}
							// =========================END ======================
							$dtr[$day]['undertime_hour'] = ($total_temp_dec_hr > 0) ? $total_temp_dec_hr: '';
							$dtr[$day]['undertime_min']  = ($total_temp_dec_mn > 0) ? $total_temp_dec_mn: '';
							
							
							// $dtr[$day]['time_in']        = $time_log['time_in_log'];
							// $dtr[$day]['break_out']      = $time_log['break_out_log'];
							// $dtr[$day]['break_in']       = $time_log['break_in_log'];
							// $dtr[$day]['time_out']       = $time_log['time_out_log'];
							// $dtr[$day]['holiday'] 		 = ! empty($result['holiday_name']) ? $result['holiday_name'] : '';
							
							/*====================== MARVIN : START : REMOVE TIME LOGS IF LEAVE IS PRESENT ===================*/
							if(EMPTY($dtr[$day]['attendance_status_name']))
							{
								$dtr[$day]['time_in']        = $time_log['time_in_log'];
								$dtr[$day]['break_out']      = $time_log['break_out_log'];
								$dtr[$day]['break_in']       = $time_log['break_in_log'];
								$dtr[$day]['time_out']       = $time_log['time_out_log'];
								$dtr[$day]['holiday'] 		 = ! empty($result['holiday_name']) ? $result['holiday_name'] : '';								
							}
							else
							{
								$dtr[$day]['time_in']        = '';
								$dtr[$day]['break_out']      = '';
								$dtr[$day]['break_in']       = '';
								$dtr[$day]['time_out']       = '';
								$dtr[$day]['holiday'] 		 = ! empty($result['holiday_name']) ? $result['holiday_name'] : '';
							}
							/*====================== MARVIN : END : REMOVE TIME LOGS IF LEAVE IS PRESENT ===================*/
							

							if($total_temp_dec_hr > 0)
							{
								$total_undertime_hour = $total_undertime_hour + $total_temp_dec_hr;
							}
							if($total_temp_dec_mn > 0)
							{
								$total_undertime_min = $total_undertime_min + $total_temp_dec_mn;
							}	

						}
						$floor_hour                     = floor($total_undertime_min / 60) + $total_undertime_hour;
						$data['total_undertime_hour'][$key] = ($floor_hour > 0) ? $floor_hour: '';
						$floor_minute                   = ($total_undertime_min%60);
						$data['total_undertime_min'][$key]  = ($floor_minute > 0) ? $floor_minute: '';
					}
					$data['dtrs'][$key]           = $dtr;
				}
			}
			
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


/* End of file Ta_daily_time_record.php */
/* Location: ./application/modules/main/controllers/reports/hr/Ta_daily_time_record.php */