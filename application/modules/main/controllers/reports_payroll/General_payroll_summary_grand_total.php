<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class General_payroll_summary_grand_total extends Main_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('reports_payroll_model', 'rm');
  }

  public function generate_report_data($params) {
    try {

      $data = array();

      /* START: GET EMPLOYMENT TYPE FLAG */
      $field = array("employ_type_flag");

      $table                    = $this->rm->tbl_param_payroll_types;
      $where                    = array();
      $where["payroll_type_id"] = $params['payroll_type'];
      $employ_type_flag         = $this->rm->get_reports_data($field, $table, $where, FALSE);
      /* END: GET EMPLOYMENT TYPE FLAG */
      if ($employ_type_flag['employ_type_flag'] == PAYROLL_TYPE_FLAG_REG) {

        // GET PAYOUT DATE
        $table                               = array(
            'main' => array(
                'table' => $this->rm->tbl_payout_summary,
                'alias' => 'A'
            ),
            't2'   => array(
                'table'     => $this->rm->tbl_payout_summary_dates,
                'alias'     => 'B',
                'type'      => 'LEFT JOIN',
                'condition' => 'A.payroll_summary_id = B.payout_summary_id'
            )
        );
        $fields                              = array('B.effective_date');
        $where                               = array();
        $where['A.attendance_period_hdr_id'] = $params['payroll_period'];
        $payroll_date                        = $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);

        $month    = date("F", strtotime($payroll_date['effective_date']));
        $year     = date("Y", strtotime($payroll_date['effective_date']));
        $max_date = date("t", strtotime($payroll_date['effective_date']));

        $data['payroll_date_text'] = $month . ' 01-' . $max_date . ', ' . $year;
      } else {
        // GET PAYOUT DATE
        $table                             = $this->rm->tbl_attendance_period_hdr;
        $fields                            = array('date_from', 'date_to');
        $where                             = array();
        $where['attendance_period_hdr_id'] = $params['payroll_period'];
        $payroll_date                      = $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);

        $month    = date("F", strtotime($payroll_date['date_from']));
        $year     = date("Y", strtotime($payroll_date['date_from']));
        $min_date = date("d", strtotime($payroll_date['date_from']));
        $max_date = date("d", strtotime($payroll_date['date_to']));

        $data['payroll_date_text'] = $month . ' ' . $min_date . '-' . $max_date . ', ' . $year;
      }

      // COMPENSATIONS
      $tables = array(
          'main' => array(
              'table' => $this->rm->tbl_param_compensations,
              'alias' => 'A'
          ),
          't2'   => array(
              'table'     => $this->rm->tbl_payout_details,
              'alias'     => 'B',
              'type'      => 'LEFT JOIN',
              'condition' => 'A.compensation_id = B.compensation_id'
          ),
          't3'   => array(
              'table'     => $this->rm->tbl_payout_header,
              'alias'     => 'C',
              'type'      => 'LEFT JOIN',
              'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
          ),
          't4'   => array(
              'table'     => $this->rm->tbl_payout_summary,
              'alias'     => 'D',
              'type'      => 'LEFT JOIN',
              'condition' => 'C.payroll_summary_id = D.payroll_summary_id AND D.attendance_period_hdr_id = ' . $params['payroll_period']
          ),
          't5'   => array(
              'table'     => $this->rm->tbl_attendance_period_hdr,
              'alias'     => 'E',
              'type'      => 'LEFT JOIN',
              'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
          )
      );

      $select_fields                   = array();
      $select_fields[]                 = 'A.less_absence_flag,A.compensation_id, D.attendance_period_hdr_id, A.compensation_name, SUM(ifnull(B.amount,0)) as amount,SUM(ifnull(B.orig_amount,0)) as orig_amount,SUM(ifnull(B.less_amount,0)) as less_amount, A.active_flag, A.inherit_parent_id_flag, A.parent_compensation_id';
      $where                           = array();
      $where['A.general_payroll_flag'] = 'Y';
      // $where['A.inherit_parent_id_flag'] 	= NA;
      $where['A.employ_type_flag']     = array($value = array($employ_type_flag['employ_type_flag'], PAYROLL_TYPE_FLAG_ALL), array("IN"));
      $compensation_temp               = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array(), array('A.compensation_id,D.attendance_period_hdr_id'));
//      echo "<pre>";
//      print_r($compensation_temp);
//      die();

      $compensation_records = array();
      foreach ($compensation_temp as $key => $value) {
        $compensation_id = $value['compensation_id'];

        if ($value['inherit_parent_id_flag'] == YES) {
          $compensation_records[$value['parent_compensation_id']]['amount'] += $value['amount'];
          $compensation_records[$compensation_id]['exclude']                = TRUE;
        }

        if (EMPTY($compensation_records[$compensation_id]['attendance_period_hdr_id'])) {
          if ($value['attendance_period_hdr_id'] != $params['payroll_period'])
            $compensation_records[$compensation_id]['amount']                   = 0;
          else
            $compensation_records[$compensation_id]['amount']                   = $value['amount'];
          $compensation_records[$compensation_id]['orig_amount']              = $value['orig_amount'];
          $compensation_records[$compensation_id]['less_amount']              = $value['less_amount'];
          $compensation_records[$compensation_id]['less_absence_flag']        = $value['less_absence_flag'];
          $compensation_records[$compensation_id]['compensation_name']        = $value['compensation_name'];
          $compensation_records[$compensation_id]['compensation_id']          = $value['compensation_id'];
          $compensation_records[$compensation_id]['attendance_period_hdr_id'] = $value['attendance_period_hdr_id'];
          $compensation_records[$compensation_id]['active_flag']              = $value['active_flag'];
          continue;
        }

        if ($value['attendance_period_hdr_id'] == $params['payroll_period'])
          $compensation_records[$compensation_id]['amount'] += $value['amount'];
      }


      foreach ($compensation_records as $key => $record) {
//              remove premium 20% -- modesto
        if (($record['active_flag'] == NO AND $record['amount'] <= 0) OR ($record['exclude'] == TRUE) OR ($record['compensation_id'] == '1006')) {
          unset($compensation_records[$key]);
        }
      }

      $data['compensation_records'] = $compensation_records;

      //DEDUCTIONS
      $tables = array(
          'main' => array(
              'table' => $this->rm->tbl_param_deductions,
              'alias' => 'A'
          ),
          't2'   => array(
              'table'     => $this->rm->tbl_payout_details,
              'alias'     => 'B',
              'type'      => 'LEFT JOIN',
              'condition' => 'A.deduction_id = B.deduction_id'
          ),
          't3'   => array(
              'table'     => $this->rm->tbl_payout_header,
              'alias'     => 'C',
              'type'      => 'LEFT JOIN',
              'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
          ),
          't4'   => array(
              'table'     => $this->rm->tbl_payout_summary,
              'alias'     => 'D',
              'type'      => 'LEFT JOIN',
              'condition' => 'C.payroll_summary_id = D.payroll_summary_id AND D.attendance_period_hdr_id = ' . $params['payroll_period']
          ),
          't5'   => array(
              'table'     => $this->rm->tbl_attendance_period_hdr,
              'alias'     => 'E',
              'type'      => 'LEFT JOIN',
              'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
          )
      );

      $select_fields   = array();
      $select_fields[] = 'A.deduction_id, D.attendance_period_hdr_id, A.deduction_name, SUM(ifnull(B.amount,0)) as amount, A.active_flag, A.parent_deduction_id, A.inherit_parent_id_flag';

      $where                           = array();
      $where['A.general_payroll_flag'] = 'Y';
      // $where['A.inherit_parent_id_flag'] 	= NO;
      $where['A.employ_type_flag']     = array($value = array($employ_type_flag['employ_type_flag'], PAYROLL_TYPE_FLAG_ALL), array("IN"));

      $deduction_temp    = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array(), array('A.deduction_id,D.attendance_period_hdr_id'));
      
      $deduction_records = array();
      foreach ($deduction_temp as $key => $value) {
        if (EMPTY($deduction_records[$value['deduction_id']]['attendance_period_hdr_id'])) {
          $deduction_records[$value['deduction_id']] = $value;
          if ($value['attendance_period_hdr_id'] != $params['payroll_period']) {
            $deduction_records[$value['deduction_id']]['active_flag'] = $value['active_flag'];
            $deduction_records[$value['deduction_id']]['amount']      = 0;
          }
        }
        if ($value['inherit_parent_id_flag'] == YES) {
          $deduction_records[$value['parent_deduction_id']]['amount'] += $value['amount'];
          $deduction_records[$value['deduction_id']]['exclude']       = TRUE;
        }
      }

      foreach ($deduction_records as $key => $record) {
        if (($record['active_flag'] == NO AND $record['amount'] <= 0) OR $record['exclude'] == TRUE) {
          unset($deduction_records[$key]);
        }
      }


      $rearrange_hdr = array(
          98  => $deduction_records['98'], //philhealth
          3   => $deduction_records['3'], //pagibig
          6   => $deduction_records['6'], //ewtax
          7   => $deduction_records['7'], //gmptax
          100 => $deduction_records['100'], //8% income tax
          99  => $deduction_records['99'], //sss
          97  => $deduction_records['97'], //over payment
          101 => $deduction_records['101']  //mp2  
      );

      $deduction_records = $rearrange_hdr;

//      echo "<pre>";
//      print_r($deduction_records);
//      print_r($rearrange_hdr);
//      die();

      $count_ded = count($deduction_records);

      if (($count_ded % 2) == 0) {
        $count_1 = $count_ded / 2;
        $count_2 = $count_ded / 2;
      } else {
        $count_1 = floor($count_ded / 2);
        $count_2 = ceil($count_ded / 2);
      }

      $ctr = 0;
      foreach ($deduction_records as $record) {
        if ($ctr < $count_1) {
          $data['deduction_records'][1][] = $record;
        } else {
          $data['deduction_records'][2][] = $record;
        }

        $ctr++;
      }



      //NET PAYS
      $tables = array(
          'main' => array(
              'table' => $this->rm->tbl_payout_details,
              'alias' => 'A'
          ),
          't3'   => array(
              'table'     => $this->rm->tbl_payout_header,
              'alias'     => 'B',
              'type'      => 'JOIN',
              'condition' => 'A.payroll_hdr_id  = B.payroll_hdr_id'
          ),
          't4'   => array(
              'table'     => $this->rm->tbl_payout_summary,
              'alias'     => 'C',
              'type'      => 'JOIN',
              'condition' => 'B.payroll_summary_id = C.payroll_summary_id'
          ),
          't5'   => array(
              'table'     => $this->rm->tbl_attendance_period_hdr,
              'alias'     => 'D',
              'type'      => 'JOIN',
              'condition' => 'C.attendance_period_hdr_id = D.attendance_period_hdr_id'
          )
      );

      $select_fields   = array();
      $select_fields[] = 'SUM(if(A.compensation_id IS NULL,0,A.amount)) - SUM(if(A.deduction_id IS NULL,0,A.amount)) AS net_pays';

      $where                               = array();
      $where['C.attendance_period_hdr_id'] = $params['payroll_period'];

      $data['net_pay_records'] = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array(), array('A.effective_date'));
      $data['display_half']    = true;
      if ($employ_type_flag['employ_type_flag'] == PAYROLL_TYPE_FLAG_JO) {
        $data['display_half'] = false;
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

  public function get_payroll_period_text($month, $year) {
    $num_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $date_obj = DateTime::createFromFormat('!m', $month);

    $month_name = $date_obj->format('F');

    $payroll_date_text = $month_name . " 1 - " . $num_days . ", " . $year;
    return $payroll_date_text;
  }

}

/* End of file General_payroll_summary_grand_total.php */
/* Location: ./application/modules/main/controllers/reports/payroll/General_payroll_summary_grand_total.php */