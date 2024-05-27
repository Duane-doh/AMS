<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class General_Payslip_for_contracts_of_service extends Main_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('reports_payroll_model', 'rm');
  }

  public function generate_report_data($params) {
    try {
      $data               = array();
      $data['month_text'] = $params['month_text'];
      $data['month']      = $params['month'];
      $data['year']       = $params['year'];
      //modesto get the current active premium
      $data['premium']    = $this->rm->get_premium_active();

      // STORES OFFICE DATA
      $data['office'] = $this->get_office_details($params['office_list']);

      // STORES PAYROLL TYPE
      $payroll_type = $this->rm->get_sysparam_value(SYS_PARAM_TYPE_PAYROLL_TYPE_JOB_ORDER);

      // STORES HEADER
      $where                       = array();
      $where['C.payroll_type_id']  = $payroll_type['sys_param_value'];
      $where['YEAR(C.date_from)']  = $params['year'];
      $where['MONTH(C.date_from)'] = $params['month'];
      $where['A.office_id']        = $params['office_list'];
      if (!EMPTY($params['employee_filtered'])) {
        $where['A.employee_id'] = $params['employee_filtered'];
      }
      $data['header'] = $this->get_header($where);

      // PAYOUT DATE
      $payout_date = $this->get_payout_date($where);

      // EMPLOYEE IDS
      $employee_ids = array();
      foreach ($data['header'] as $hdr) {
        $employee_ids[] = $hdr['employee_id'];
      }


      // COMPENSATION IDS AVAILABLE FOR AN EMPLOYEE
//      $where                            = array();
//      $where['A.office_id']             = $params['office_list'];
//      $where['YEAR(B.effective_date)']  = $params['year'];
//      $where['MONTH(B.effective_date)'] = $params['month'];
//      $where['D.payroll_type_id']       = $payroll_type['sys_param_value'];
//      $where['B.compensation_id']       = "IS NOT NULL";
//      $where['E.basic_salary_flag']     = NO;

      $where                        = array();
      $where['employ_type_flag']    = array(array(PAYROLL_TYPE_FLAG_JO), array("IN"));
      $where['active_flag']         = YES;
      $compensation_ids             = $this->rm->get_reports_data('GROUP_CONCAT(DISTINCT compensation_id) compensation_ids', $this->rm->tbl_param_compensations, $where, FALSE, array('compensation_id' => 'ASC'));
      $employee_responsibility_name = array();
      foreach ($employee_ids as $key => $value) {
        $employee_responsibility_name[$value] = $this->rm->getRespDesc($value)['responsibility_center_desc'];
      }

      $data['responsibility_center'] = $employee_responsibility_name;

      foreach ($employee_ids as $id) {
        $data['compensation_hdr'][$id] = explode(',', $compensation_ids['compensation_ids']);

        if (($key = array_search('1006', $data['compensation_hdr'][$id])) !== false) {
          unset($data['compensation_hdr'][$id][$key]);
        }
        sort($data['compensation_hdr'][$id]);
      }

      foreach ($employee_ids as $id) {
        $deductions_id                 = $this->rm->get_deduction_checks($id);
        $data['deduction_checks'][$id] = explode(',', $deductions_id['deduction_ids']);
      }

      $where                     = array();
      $where['employ_type_flag'] = array(array(PAYROLL_TYPE_FLAG_ALL, PAYROLL_TYPE_FLAG_JO), array("IN"));
      $where['active_flag']      = YES;
      $deduction_ids             = $this->rm->get_reports_data('GROUP_CONCAT(DISTINCT deduction_id) deduction_ids', $this->rm->tbl_param_deductions, $where, FALSE, array('deduction_id' => 'ASC'));

      foreach ($employee_ids as $id) {
        $explode_hdr = explode(',', $deduction_ids['deduction_ids']);

        $rearrange_hdr = array(
            0 => $explode_hdr['4'], //philhealth
            1 => $explode_hdr['0'], //pagibig
            2 => $explode_hdr['1'], //ewtax
            3 => $explode_hdr['2'], //gmptax
            4 => $explode_hdr['6'], //8% income tax
            5 => $explode_hdr['5'], //sss
            6 => $explode_hdr['3'], //over payment
            7 => $explode_hdr['7']  //mp2
        );

        $data['deduction_hdr'][$id] = $rearrange_hdr;
      }





      $payroll_common = modules::load('main/payroll_common');

      // LABEL FOR 1st AND 2nd HALF
      $label[0]       = "1st";
      $label[1]       = "2nd";
      $orig_label_ctr = 0;
      if (count($payout_date) < 2) {
        if ($payout_date[0]['day'] > 15) {
          $orig_label_ctr = 1;
        }
      }
      /* GET WORKING DAYS PER PAYOUT */
      $label_ctr = $orig_label_ctr;
      foreach ($payout_date as $date) {
        $working_days[$label_ctr] = $payroll_common->compute_working_days($date['date_from'], $date['date_to']);
        $label_ctr++;
      }

      // RECORDS
      $fields                     = array('D.attendance_period_hdr_id', 'A.employee_id', 'A.employee_name', 'A.basic_amount', 'A.net_pay', 'A.month_work_days', 'A.daily_rate', 'GROUP_CONCAT(DISTINCT A.payroll_hdr_id) hdr_ids', 'GROUP_CONCAT(DISTINCT B.payroll_dtl_id) dtl_ids');
      $where                      = array();
      $where['A.office_id']       = $params['office_list'];
      $where['D.payroll_type_id'] = $payroll_type['sys_param_value'];

      foreach ($data['header'] as $hdr) {
        $label_ctr = $orig_label_ctr;
        foreach ($payout_date as $date) {

          $where['A.employee_id']                                                   = $hdr['employee_id'];
          $where['D.attendance_period_hdr_id']                                      = $date['attendance_period_hdr_id'];
          $data['results'][$hdr['employee_id']][$label[$label_ctr]]                 = $this->get_records($fields, $where);
          $data['results'][$hdr['employee_id']][$label[$label_ctr]]['working_days'] = $working_days[$label_ctr];

          $label_ctr++;
        }
      }

      $new_date = $params['year'] . '-' . $params['month'] . '-' . '01';

      $rates = array();
      foreach ($data['results'] as $key => $result) {
        $label_ctr = $orig_label_ctr;
        $net_pay   = 0;
        foreach ($payout_date as $date) {
          $rates[$key][$label[$label_ctr]] = $this->compute_rates($key, $result[$label[$label_ctr]]['daily_rate']);

          // BASIC SALARY
          $data['basic_salary'][$key][$label[$label_ctr]] = $this->get_compensations_basic($result[$label[$label_ctr]]['hdr_ids'], NULL, TRUE);

          // COMPENSATIONS
          foreach ($data['compensation_hdr'][$key] as $com_id) {
            $data['compensations'][$key][$label[$label_ctr]][$com_id] = $this->get_compensations($result[$label[$label_ctr]]['hdr_ids'], $com_id);

            if (EMPTY($data['compensations'][$key][$label[$label_ctr]][$com_id]['amount']) AND $data['compensations'][$key][$label[$label_ctr]][$com_id]['active_flag'] == NO) {
              unset($data['compensations'][$key][$com_id]);
            }
          }

          // DEDUCTIONS
          foreach ($data['deduction_hdr'][$key] as $ded_id) {

            $data['deductions'][$key][$label[$label_ctr]][$ded_id] = $this->get_deductions($result[$label[$label_ctr]]['dtl_ids'], $ded_id);

            if (EMPTY($data['deductions'][$key][$label[$label_ctr]][$ded_id]['amount']) AND $data['deductions'][$key][$label[$label_ctr]][$ded_id]['active_flag'] == NO) {
              // unset($data['deductions'][$key][$label[$label_ctr]][$ded_id]);
              unset($data['deduction_hdr'][$key][$ded_id]);
            }
          }

          $label_ctr++;
        }
      }

      $data['rates'] = $rates;

      // STORES UNDERTIME, ABSENCE LESS AMOUNTS
      $data['less_amounts'] = array();
      foreach ($rates as $key => $rate) {
        foreach ($data['results'][$key] as $label => $result) {
          if ($rate[$label]['rate_day'] > 0) {
            $data['less_amounts'][$key][$label] = $this->get_less_amount($result['attendance_period_hdr_id'], $rate[$label]);
          }
        }
      }

    } catch (PDOException $e) {
      $message = $e->getMessage();
      RLog::error($message);
    } catch (Exception $e) {
      $message = $e->getMessage();
      RLog::error($message);
    }

    return $data;
  }

  // OFFICE NAME AND CODE
  public function get_office_details($office_id) {
    $field                  = array('A.name', 'A.short_name');
    $tables                 = array(
        'main' => array(
            'table' => $this->rm->DB_CORE . "." . $this->rm->tbl_organizations,
            'alias' => 'A'
        ),
        't1'   => array(
            'table'     => $this->rm->tbl_param_offices,
            'alias'     => 'B',
            'type'      => 'JOIN',
            'condition' => 'A.org_code = B.org_code'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_header,
            'alias'     => 'C',
            'type'      => 'JOIN',
            'condition' => 'B.office_id = C.office_id'
        )
    );
    $where                  = array();
    $where['B.office_id']   = $office_id;
    $where['B.active_flag'] = 'Y';

    return $this->rm->get_reports_data($field, $tables, $where, FALSE);
  }

  // HEADER
  public function get_header($where) {
    /*
      $fields				= array("A.employee_id", "D.agency_employee_id", "CONCAT(D.last_name, ', ', D.first_name, ' ', LEFT(D.middle_name, (1)), '. ', D.ext_name) full_name",
      "A.position_name", "A.salary_grade", "G.identification_value tin", "H.identification_value phic", "F.employment_status_name status");
     */
    // ====================== jendaigo : start : change name format ============= //
    $fields = array("A.employee_id", "D.agency_employee_id", "CONCAT(D.last_name, ', ', D.first_name, IF(D.ext_name='', '', CONCAT(' ', D.ext_name)), IF((D.middle_name='NA' OR D.middle_name='N/A' OR D.middle_name='-' OR D.middle_name='/'), '', CONCAT(' ', D.middle_name))) full_name",
        "A.position_name", "A.salary_grade", "G.identification_value tin", "H.identification_value phic", "F.employment_status_name status");
    // ====================== jendaigo : end : change name format ============= //

    $tables = array(
        'main' => array(
            'table' => $this->rm->tbl_payout_header,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_summary,
            'alias'     => 'B',
            'type'      => 'JOIN',
            'condition' => 'A.payroll_summary_id = B.payroll_summary_id'
        ),
        't3'   => array(
            'table'     => $this->rm->tbl_attendance_period_hdr,
            'alias'     => 'C',
            'type'      => 'JOIN',
            'condition' => 'B.attendance_period_hdr_id = C.attendance_period_hdr_id'
        ),
        't4'   => array(
            'table'     => $this->rm->tbl_employee_personal_info,
            'alias'     => 'D',
            'type'      => 'JOIN',
            'condition' => 'A.employee_id = D.employee_id'
        ),
        't5'   => array(
            'table'     => $this->rm->tbl_employee_work_experiences,
            'alias'     => 'E',
            'type'      => 'JOIN',
            'condition' => 'D.employee_id = E.employee_id'
        ),
        't6'   => array(
            'table'     => $this->rm->tbl_param_employment_status,
            'alias'     => 'F',
            'type'      => 'JOIN',
            'condition' => 'E.employment_status_id = F.employment_status_id'
        ),
        't7'   => array(
            'table'     => $this->rm->tbl_employee_identifications,
            'alias'     => 'G',
            'type'      => 'LEFT JOIN',
            'condition' => 'A.employee_id = G.employee_id AND G.identification_type_id = ' . TIN_TYPE_ID
        ),
        't8'   => array(
            'table'     => $this->rm->tbl_employee_identifications,
            'alias'     => 'H',
            'type'      => 'LEFT JOIN',
            'condition' => 'A.employee_id = H.employee_id AND H.identification_type_id = ' . PHILHEALTH_TYPE_ID
        )
    );

    $order_by = array('full_name' => 'ASC');
    $group_by = array('A.employee_id');

    return $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
  }

  public function get_payout_date($where) {
    $fields = array('C.attendance_period_hdr_id', 'C.date_from', 'C.date_to', 'DAY(C.date_from) day');
    $tables = array(
        'main' => array(
            'table' => $this->rm->tbl_payout_header,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_attendance_period_summary,
            'alias'     => 'B',
            'type'      => 'JOIN',
            'condition' => 'A.employee_id = B.employee_id'
        ),
        't3'   => array(
            'table'     => $this->rm->tbl_attendance_period_hdr,
            'alias'     => 'C',
            'type'      => 'JOIN',
            'condition' => 'B.attendance_period_hdr_id = C.attendance_period_hdr_id'
        )
    );

    $order_by = array('C.date_from' => 'ASC');
    $group_by = array('C.attendance_period_hdr_id');

    return $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
  }

  public function get_records($fields, $where, $order_by = NULL) {
    $tables = array(
        'main' => array(
            'table' => $this->rm->tbl_payout_header,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_details,
            'alias'     => 'B',
            'type'      => 'JOIN',
            'condition' => 'A.payroll_hdr_id = B.payroll_hdr_id'
        ),
        't3'   => array(
            'table'     => $this->rm->tbl_payout_summary,
            'alias'     => 'C',
            'type'      => 'JOIN',
            'condition' => 'A.payroll_summary_id = C.payroll_summary_id'
        ),
        't4'   => array(
            'table'     => $this->rm->tbl_attendance_period_hdr,
            'alias'     => 'D',
            'type'      => 'JOIN',
            'condition' => 'C.attendance_period_hdr_id = D.attendance_period_hdr_id'
        )
    );

    return $this->rm->get_reports_data($fields, $tables, $where, FALSE, $order_by, NULL);
  }

  public function get_compensation_ids($fields, $where, $order_by = NULL) {
    $tables = array(
        'main' => array(
            'table' => $this->rm->tbl_payout_header,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_details,
            'alias'     => 'B',
            'type'      => 'JOIN',
            'condition' => 'A.payroll_hdr_id = B.payroll_hdr_id'
        ),
        't3'   => array(
            'table'     => $this->rm->tbl_payout_summary,
            'alias'     => 'C',
            'type'      => 'JOIN',
            'condition' => 'A.payroll_summary_id = C.payroll_summary_id'
        ),
        't4'   => array(
            'table'     => $this->rm->tbl_attendance_period_hdr,
            'alias'     => 'D',
            'type'      => 'JOIN',
            'condition' => 'C.attendance_period_hdr_id = D.attendance_period_hdr_id'
        ),
        't5'   => array(
            'table'     => $this->rm->tbl_param_compensations,
            'alias'     => 'E',
            'type'      => 'JOIN',
            'condition' => 'B.compensation_id = E.compensation_id'
        )
    );

    return $this->rm->get_reports_data($fields, $tables, $where, FALSE, $order_by, NULL);
  }

  // RATES
  public function compute_rates($employee_id, $daily_rate) {
    $rates = array();

    $rates['employee_id'] = $employee_id;

    //COMPUTE RATE/DAY
    $rates['rate_day']    = round($daily_rate, 4);
    //COMPUTE RATE/HOUR
    $rates['rate_hour']   = round(($rates['rate_day'] / 8), 4);
    //COMPUTE RATE/MINUTE
    $rates['rate_minute'] = round(($rates['rate_hour'] / 60), 4);

    return $rates;
  }

  public function get_less_amount($attendance_period_hdr_id, $rate) {
    $fields                            = array('SUM(tardiness_hr) tardiness_hr', 'SUM(tardiness_min) tardiness_min', 'SUM(undertime_hr) undertime_hr', 'SUM(undertime_min) undertime_min', 'SUM(lwop_hours) lwop_hours');
    $table                             = $this->rm->tbl_attendance_period_dtl;
    $where['attendance_period_hdr_id'] = $attendance_period_hdr_id;
    $where['employee_id']              = $rate['employee_id'];
    $record                            = $this->rm->get_reports_data($fields, $table, $where, FALSE);

    $fields                        = array('COUNT(attendance_status_id) count');
    $where['attendance_status_id'] = ATTENDANCE_STATUS_ABSENT; // REUSE EXISTING WHERE ABOVE
    $absent_count                  = $this->rm->get_reports_data($fields, $table, $where, FALSE);
    $absent_hours                  = $absent_count['count'] * 8;

    // STORES LESS AMOUNTS
    $less_amount = array();

    // LWOP + ABSENT_HOURS
    $total_lwop_hours = $record['lwop_hours'] + $absent_hours;

    $remarks = "";
    $time    = 0;
    if (($record['tardiness_hr'] != NULL && $record['tardiness_hr'] > 0) || ($record['tardiness_min'] != NULL && $record['tardiness_min'] > 0)) {
      $time = $record['tardiness_hr'] + ($record['tardiness_min'] / 60);
    }

    if (($record['undertime_hr'] != NULL && $record['undertime_hr'] > 0) || ($record['undertime_min'] != NULL && $record['undertime_min'] > 0)) {
      $time = $time + $record['undertime_hr'] + ($record['undertime_min'] / 60);
    }
    if ($total_lwop_hours > 0) {
      $lwop = $this->count_days_hours_minutes($total_lwop_hours, true);
    }
    if ($time > 0) {
      $undertime         = $this->count_days_hours_minutes($time, true);
      if ($undertime['days'] > 0)
        $lwop['days']      += $undertime['days'];
      $undertime['days'] = 0;
    }

    if ($lwop) {
      $remarks = $remarks . " " . $this->get_remarks($lwop) . " LWOP<br>";
    }
    if ($undertime) {
      $remarks = $remarks . " " . $this->get_remarks($undertime) . " UT";
    }

    $time = $record['tardiness_hr'] + $record['undertime_hr'] + $total_lwop_hours + (($record['tardiness_min'] + $record['undertime_min']) / 60);

    $time_arr = $this->count_days_hours_minutes($time);

    $less_amount['remarks'] = rtrim($remarks, ',');
    $less_amount['day']     = round($time_arr['days'], 4);
    $less_amount['hour']    = round($time_arr['hours'], 4);
    $less_amount['minute']  = round($time_arr['minutes'], 4);

    $less_amount['rate_day']    = round(($time_arr['days'] * $rate['rate_day']), 4);
    $less_amount['rate_hour']   = round(($time_arr['hours'] * $rate['rate_hour']), 4);
    $less_amount['rate_minute'] = round(($time_arr['minutes'] * $rate['rate_minute']), 4);
    $less_amount['total_less']  = $less_amount['rate_day'] + $less_amount['rate_hour'] + $less_amount['rate_minute'];

    return $less_amount;
  }

  // COMPENSATIONS
  public function get_compensations_basic($hdr_ids, $com_id, $basic_salary_flag = FALSE) {
    $fields  = array('A.compensation_name', 'SUM(B.amount) amount', 'A.active_flag');
    $hdr_ids = EMPTY($hdr_ids) ? "1" : $hdr_ids;

    $tables = array(
        'main' => array(
            'table' => $this->rm->tbl_param_compensations,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_details,
            'alias'     => 'B',
            'type'      => 'JOIN',
            'condition' => 'A.compensation_id = B.compensation_id AND B.payroll_hdr_id IN (' . $hdr_ids . ')'
        )
    );
    $where  = array();
    if ($basic_salary_flag) {
      $where['A.basic_salary_flag'] = YES;
      $where['B.compensation_id']   = "IS NOT NULL";
    }
    $where['B.payroll_hdr_id'] = array($value = explode(",", $hdr_ids), array("IN"));
    return $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, $group_by);
  }

  public function get_compensations($hdr_ids, $com_id) {
    $fields  = array('A.compensation_name', 'SUM(B.amount) amount', 'A.active_flag');
    $hdr_ids = EMPTY($hdr_ids) ? "1" : $hdr_ids;

    $tables                     = array(
        'main' => array(
            'table' => $this->rm->tbl_param_compensations,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_details,
            'alias'     => 'B',
            'type'      => 'LEFT JOIN',
            'condition' => 'A.compensation_id = B.compensation_id AND B.payroll_hdr_id IN (' . $hdr_ids . ')'
        )
    );
    $where                      = array();
    $where['A.compensation_id'] = $com_id;
    $group_by                   = array('A.compensation_name');

    return $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, $group_by);
  }

  //DEDUCTIONS
  public function get_deductions($dtl_ids, $ded_id) {
    $fields  = array('A.deduction_name', 'A.deduction_id', 'SUM(B.amount) amount', 'A.active_flag');
    $dtl_ids = EMPTY($dtl_ids) ? "1" : $dtl_ids;

    $tables                  = array(
        'main' => array(
            'table' => $this->rm->tbl_param_deductions,
            'alias' => 'A'
        ),
        't2'   => array(
            'table'     => $this->rm->tbl_payout_details,
            'alias'     => 'B',
            'type'      => 'LEFT JOIN',
            'condition' => 'A.deduction_id = B.deduction_id AND B.payroll_dtl_id IN (' . $dtl_ids . ')'
        )
    );
    $where                   = array();
    $where['A.deduction_id'] = $ded_id;
    $group_by                = array('A.deduction_name');

    return $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, $group_by);
  }

  public function count_days_hours_minutes($time, $convert_to_day = true) {
    $time_arr = array();
    $days     = 0;
    $hours    = 0;
    $minutes  = 0;

    if ($time >= 8 AND $convert_to_day == TRUE) {
      $days = floor($time / 8);
      $mod  = floor($time / 8);
      $time = $time - ($mod) * 8;
    }

    if ($time >= 1) {
      $hours = floor($time);
      $time  = $time - $hours;
    }

    if ($time != 0) {
      $minutes = $time * 60;
    }

    $time_arr['days']    = $days;
    $time_arr['hours']   = $hours;
    $time_arr['minutes'] = $minutes;

    return $time_arr;
  }

  public function get_remarks($time) {
    $remarks = "";

    if ($time['days'] > 0) {
      $days = floatval(number_format($time['days'], 4));
      if ($time['days'] > 1) {
        $remarks = $remarks . $days . " days ";
      } else {
        $remarks = $remarks . $days . " day ";
      }
    }


    if ($time['hours'] > 0) {
      $hours = floatval(number_format($time['hours'], 4));
      if ($time['hours'] > 1) {
        $remarks = $remarks . $hours . " hrs ";
      } else {
        $remarks = $remarks . $hours . " hr ";
      }
    }

    if ($time['minutes'] != 0) {
      $minutes = floatval(number_format($time['minutes'], 4));
      if ($time['minutes'] > 1) {
        $remarks = $remarks . $minutes . " mins ";
      } else {
        $remarks = $remarks . $minutes . " min ";
      }
    }

    return $remarks;
  }

}

/* End of file General_Payslip_for_contracts_of_service.php */
/* Location: ./application/modules/main/controllers/reports/payroll/General_Payslip_for_contracts_of_service.php */