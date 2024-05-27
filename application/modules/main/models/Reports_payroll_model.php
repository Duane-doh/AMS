<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Reports_payroll_model extends Main_Model {

  public function __construct() {
    parent:: __construct();
  }

  // SCHEMA NAME
  public $DB_MAIN = DB_MAIN;
  public $DB_CORE = DB_CORE;
  public $tbl_organizations = 'organizations';
  public $tbl_genders       = 'param_genders';
  public $tbl_barangays     = 'param_barangays';
  public $tbl_municities    = 'param_municities';
  public $tbl_provinces     = 'param_provinces';
  public $tbl_sys_param     = 'sys_param';
  public $OTHER_INFO_TYPE_TITLE = OTHER_INFO_TYPE_TITLE;

  public function get_voucher_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE) {
    try {
      $fields        = str_replace(" , ", " ", implode(", ", $aColumns));
      $cColumns      = array("voucher_name", "active_flag");
      $sWhere        = $this->filtering($cColumns, $params, FALSE);
      $sOrder        = $this->ordering($bColumns, $params);
      $sLimit        = $this->paging($params);
      $filter_str    = $sWhere["search_str"];
      $filter_params = $sWhere["search_params"];

      $query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY voucher_id
				$sOrder
				$sLimit
EOS;
      RLog::debug($query);
      $stmt  = $this->query($query, $filter_params, $multiple);

      return $stmt;
    } catch (PDOException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function get_philhealth_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE) {
    try {
      $fields        = str_replace(" , ", " ", implode(", ", $aColumns));
      $cColumns      = array("effectivity_date", "active_flag");
      $sWhere        = $this->filtering($cColumns, $params, FALSE);
      $sOrder        = $this->ordering($bColumns, $params);
      $sLimit        = $this->paging($params);
      $filter_str    = $sWhere["search_str"];
      $filter_params = $sWhere["search_params"];

      $query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY philhealth_id
				$sOrder
				$sLimit
EOS;
      RLog::debug($query);
      $stmt  = $this->query($query, $filter_params, $multiple);

      return $stmt;
    } catch (PDOException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function get_pagibig_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE) {
    try {
      $fields        = str_replace(" , ", " ", implode(", ", $aColumns));
      $cColumns      = array("effectivity_date", "active_flag");
      $sWhere        = $this->filtering($cColumns, $params, FALSE);
      $sOrder        = $this->ordering($bColumns, $params);
      $sLimit        = $this->paging($params);
      $filter_str    = $sWhere["search_str"];
      $filter_params = $sWhere["search_params"];

      $query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY pagibig_id
				$sOrder
				$sLimit
EOS;
      RLog::debug($query);
      $stmt  = $this->query($query, $filter_params, $multiple);

      return $stmt;
    } catch (PDOException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function get_gsis_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE) {
    try {
      $fields        = str_replace(" , ", " ", implode(", ", $aColumns));
      $cColumns      = array("insurance_coverage_type", "personal_share_life", "personal_share_retirement", "gov_share_life", "gov_share_retirement", "active_flag");
      $sWhere        = $this->filtering($cColumns, $params, FALSE);
      $sOrder        = $this->ordering($bColumns, $params);
      $sLimit        = $this->paging($params);
      $filter_str    = $sWhere["search_str"];
      $filter_params = $sWhere["search_params"];

      $query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY gsis_id
				$sOrder
				$sLimit
EOS;
      RLog::debug($query);
      $stmt  = $this->query($query, $filter_params, $multiple);

      return $stmt;
    } catch (PDOException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function get_remittance_report_header($param) {
    try {
      $val   = array($param);
      $query = <<<EOS
				SELECT
				sys_param_value
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_gsis_contributions_remittance_list($year_month, $report_name, $deduc_converted, $payroll_type_ids) {
    try {
      $year           = substr($year_month, 0, 3);
      $min_date       = $year . '-01-01';
      $gsis_detail_id = 17;
      $crn_detail_id  = 160;
      $val            = array($gsis_detail_id, $crn_detail_id, $min_date, $min_date, $year_month, $report_name);
      $query          = <<<EOS
				SELECT
				A.year_month date_processed,
                D.employee_id,
                DATE_FORMAT(E.birth_date,'%m/%d/%Y') birth_date,
                F.employ_monthly_salary as basic_salary,
                (
				  SELECT BB.other_deduction_detail_value
				  FROM
				    $this->tbl_employee_deductions AA
				    JOIN $this->tbl_employee_deduction_other_details BB on AA.employee_deduction_id = BB.employee_deduction_id
				  WHERE D.employee_id = AA.employee_id AND BB.other_deduction_detail_id = ?
				  LIMIT 1
				) pin,
                (
				  SELECT BB.other_deduction_detail_value
				  FROM
				    $this->tbl_employee_deductions AA
				    JOIN $this->tbl_employee_deduction_other_details BB on AA.employee_deduction_id = BB.employee_deduction_id
				  WHERE D.employee_id = AA.employee_id AND BB.other_deduction_detail_id = ?
				  LIMIT 1
				) crn,
				(
				  SELECT DATE_FORMAT(IF(MAX(AA.employ_start_date) IS NOT NULL,MAX(AA.employ_start_date),?),'%m/%d/%Y')
				  FROM
				    $this->tbl_employee_work_experiences AA
				  WHERE D.employee_id = AA.employee_id AND AA.employ_personnel_movement_id IS NOT NULL
				   AND AA.employ_start_date > ? AND AA.employ_start_date < NOW()
				  LIMIT 1
				) effectivity_date,
                E.last_name,
                E.first_name,
                LEFT(E.middle_name, 1) middle_init,
                E.ext_name,
				SUM(IF(C.deduction_id IN ($deduc_converted[ps]), B.amount,0)) ps,
				SUM(IF(C.deduction_id IN ($deduc_converted[ps]), C.employer_amount,0)) gs,
				SUM(IF(C.deduction_id IN ($deduc_converted[ec]), C.employer_amount,0)) ec,
				SUM(IF(C.deduction_id IN ($deduc_converted[consoloan]), B.amount,0)) consoloan,
				SUM(IF(C.deduction_id IN ($deduc_converted[ecardplus]), B.amount,0)) ecardplus,
				SUM(IF(C.deduction_id IN ($deduc_converted[salary_loan]), B.amount,0)) salary_loan,
				SUM(IF(C.deduction_id IN ($deduc_converted[cash_adv]), B.amount,0)) cash_adv,
				SUM(IF(C.deduction_id IN ($deduc_converted[emrgyln]), B.amount,0)) emrgyln,
				SUM(IF(C.deduction_id IN ($deduc_converted[educ_asst]), B.amount,0)) educ_asst,
				SUM(IF(C.deduction_id IN ($deduc_converted[ela]), B.amount,0)) ela,
				SUM(IF(C.deduction_id IN ($deduc_converted[sos]), B.amount,0)) sos,
				SUM(IF(C.deduction_id IN ($deduc_converted[plreg]), B.amount,0)) plreg,
				SUM(IF(C.deduction_id IN ($deduc_converted[plopt]), B.amount,0)) plopt,
				SUM(IF(C.deduction_id IN ($deduc_converted[rel]), B.amount,0)) rel,
				SUM(IF(C.deduction_id IN ($deduc_converted[lch_dcs]), B.amount,0)) lch_dcs,
				SUM(IF(C.deduction_id IN ($deduc_converted[stock_purchase]), B.amount,0)) stock_purchase,
				SUM(IF(C.deduction_id IN ($deduc_converted[opt_life]), B.amount,0)) opt_life,
				SUM(IF(C.deduction_id IN ($deduc_converted[ceap]), B.amount,0)) ceap,
				SUM(IF(C.deduction_id IN ($deduc_converted[edu_child]), B.amount,0)) edu_child,
				SUM(IF(C.deduction_id IN ($deduc_converted[genesis]), B.amount,0)) genesis,
				SUM(IF(C.deduction_id IN ($deduc_converted[genplus]), B.amount,0)) genplus,
				SUM(IF(C.deduction_id IN ($deduc_converted[genflexi]), B.amount,0)) genflexi,
				SUM(IF(C.deduction_id IN ($deduc_converted[genspcl]), B.amount,0)) genspcl,
				SUM(IF(C.deduction_id IN ($deduc_converted[helps]), B.amount,0)) helps
				FROM $this->tbl_param_remittance_type_deductions Z
				LEFT JOIN $this->tbl_param_remittance_types V ON V.remittance_type_id = Z.remittance_type_id
				JOIN $this->tbl_remittances A ON A.remittance_type_id = V.remittance_type_id 
				JOIN $this->tbl_remittance_details B ON A.remittance_id = B.remittance_id 
				JOIN $this->tbl_payout_details C ON B.payroll_dtl_id = C.payroll_dtl_id AND C.deduction_id = Z.deduction_id
				LEFT JOIN $this->tbl_payout_header D ON C.payroll_hdr_id = D.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info E on D.employee_id = E.employee_id
				LEFT JOIN $this->tbl_employee_work_experiences F on E.employee_id = F.employee_id AND F.active_flag = "Y"
				LEFT JOIN $this->tbl_param_offices G on F.employ_office_id = G.office_id
				WHERE A.year_month = ?
				AND ($payroll_type_ids)
	 			AND Z.file_name = ?
				GROUP BY employee_id
				ORDER BY G.org_code,E.last_name,E.first_name
EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_pagibig_contributions_remittance_list($date_range_from, $date_range_to, $report_name, $payroll_type_ids, $office_list = array()) {
    try {
      $val = array(PAGIBIG_TYPE_ID, TIN_TYPE_ID, $date_range_from, $date_range_to, $report_name);
      if ($office_list) {
        $q_mark = str_repeat(" ?,", count($office_list));
        $q_mark = rtrim($q_mark, ',');
        $where  = " AND F.office_id IN (" . $q_mark . ")";
        $val    = array_merge($val, $office_list);
      }
      $query = <<<EOS
				SELECT 
				DATE_FORMAT(E.effective_date, '%Y%m') AS effective_date,
				C.year_month  as month_covered,
				SUM(D.amount) amount,
				SUM(E.employer_amount) employer_amount,
				G.first_name,
				G.middle_name,
				G.last_name,
				G.ext_name,
				H.identification_value pagibig,
				I.identification_value tin,
				K.other_deduction_detail_value applno
				FROM $this->tbl_remittances C
				JOIN $this->tbl_remittance_details D ON C.remittance_id = D.remittance_id 
				JOIN $this->tbl_payout_details E ON D.payroll_dtl_id = E.payroll_dtl_id
				JOIN $this->tbl_payout_header F ON E.payroll_hdr_id = F.payroll_hdr_id 
				JOIN $this->tbl_employee_personal_info G ON F.employee_id = G.employee_id 
				LEFT JOIN $this->tbl_employee_identifications H ON G.employee_id = H.employee_id AND H.identification_type_id = ?
				LEFT JOIN $this->tbl_employee_identifications I ON G.employee_id = I.employee_id AND I.identification_type_id = ?
				LEFT JOIN $this->tbl_employee_deductions J ON G.employee_id = J.employee_id AND E.deduction_id = J.deduction_id
				LEFT JOIN $this->tbl_employee_deduction_other_details K ON J.employee_deduction_id = K.employee_deduction_id
				WHERE (E.effective_date BETWEEN ? AND ?)
				AND E.deduction_id IN (SELECT deduction_id FROM $this->tbl_param_remittance_type_deductions
										WHERE file_name = ?)
				AND ($payroll_type_ids)

				$where
				GROUP BY F.employee_id
				ORDER BY G.last_name
EOS;
      RLog::error('QUERY :' . $query);
      RLog::error('VALUE :' . json_encode($val));
      $stmt  = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_pagibig_deductions_remittance_list($date_range_from, $date_range_to, $payroll_type_ids) {
    try {
      $val   = array(TIN_TYPE_ID, $date_range_from, $date_range_to, REMITTANCE_PAGIBIG_LOAN);
      $query = <<<EOS
				SELECT 
				DATE_FORMAT(E.effective_date, '%Y%m') AS effective_date, 
				E.amount, 
				E.employer_amount, 
				G.first_name,
				G.middle_name,
				G.last_name,
				H.identification_value tin
				FROM $this->tbl_remittances C
				JOIN $this->tbl_remittance_details D ON D.remittance_id = C.remittance_id 
				JOIN $this->tbl_payout_details E ON E.payroll_dtl_id = D.payroll_dtl_id
				LEFT JOIN $this->tbl_payout_header F ON F.payroll_hdr_id = E.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info G ON G.employee_id = F.employee_id 
				LEFT JOIN $this->tbl_employee_identifications H ON H.employee_id = G.employee_id AND H.identification_type_id = ?
				WHERE (E.effective_date BETWEEN ? AND ?)				
				AND E.deduction_id IN (SELECT deduction_id FROM $this->tbl_param_remittance_files
										WHERE remittance_type_id = ?)
				AND ($payroll_type_ids)
EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_philhealth_contributions_remittance_list($year_month, $payroll_type_ids, $payout_types, $order_by) {
    try {

      $val   = array($year_month, PHILHEALTH_TYPE_ID, REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING);
      $query = <<<EOS
				SELECT 
					G.employee_id,
					G.last_name,
					G.ext_name,
					G.first_name,
					G.middle_name,
					H.identification_value ph_id,
					DATE_FORMAT(G.birth_date, '%m/%d/%Y') birth_date,
					G.gender_code,
					E.payroll_hdr_id,
					IFNULL(D.amount, 0.00) ps,
					IFNULL(E.employer_amount, 0.00) gs,
					IFNULL(C.or_no, "") or_no,
					IFNULL(C.or_date, "") or_date,
                    F.basic_amount,
                    F.office_id,
                    C.or_no,
                   	C.or_date,
                   	F.payroll_summary_id,
                   	J.attendance_period_hdr_id
				FROM $this->tbl_param_remittance_type_deductions A
				LEFT JOIN $this->tbl_param_remittance_types B
					ON A.remittance_type_id = B.remittance_type_id
				JOIN $this->tbl_remittances C
					ON B.remittance_type_id = C.remittance_type_id 
				JOIN $this->tbl_remittance_details D 
					ON C.remittance_id = D.remittance_id
				JOIN $this->tbl_payout_details E
					ON D.payroll_dtl_id = E.payroll_dtl_id AND A.deduction_id = E.deduction_id 
				JOIN $this->tbl_payout_header F
					ON E.payroll_hdr_id = F.payroll_hdr_id
				JOIN $this->tbl_employee_personal_info G
					ON F.employee_id = G.employee_id
				LEFT JOIN $this->tbl_employee_identifications H
					ON G.employee_id = H.employee_id
				LEFT JOIN $this->tbl_payout_summary J
					ON F.payroll_summary_id = J.payroll_summary_id
				WHERE C.year_month = ?
				AND H.identification_type_id = ?
				AND A.file_name = ?
				AND J.payout_type_flag IN ($payout_types)
				AND ($payroll_type_ids)
				GROUP BY G.employee_id
				$order_by
EOS;
      $stmt  = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_all_employees($fields, $table) {
    try {
      $fields = (!empty($fields)) ? $fields : array("*");

      return $this->select_all($fields, $table);
    } catch (PDOException $e) {
      throw $e;
    }
  }

  public function get_payroll_period($payroll_type_id) {
    try {
      $val = array($payroll_type_id);

      // $columns = array("A.attendance_period_hdr_id", "CONCAT(DATE_FORMAT(A.date_from,'%M %d'), ' - ', DATE_FORMAT(A.date_to,'%d, %Y')) payroll_period");

      //marvin : include batching : start
      $columns = array("A.attendance_period_hdr_id", "CONCAT(DATE_FORMAT(A.date_from,'%M %d'), ' - ', DATE_FORMAT(A.date_to,'%d, %Y')) payroll_period", "A.remarks");
      //marvin : include batching : end

      $fields = str_replace(" , ", " ", implode(", ", $columns));

      $query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_attendance_period_hdr A
				JOIN $this->tbl_payout_summary B
				ON A.attendance_period_hdr_id = B.attendance_period_hdr_id
				WHERE A.payroll_type_id  = ?
				
EOS;
      $stmt  = $this->query($query, $val);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_reports_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL) {
    try {
      if ($multiple) {
        return $this->select_all($fields, $table, $where, $order_by, $group_by, $limit);
      } else {
        return $this->select_one($fields, $table, $where, $order_by, $group_by, $limit);
      }
    } catch (PDOException $e) {
      throw $e;
    }
  }

  public function get_deduction_params($where) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					deduction_id,
					deduction_code
				FROM $this->tbl_param_deductions
				$where
EOS;
      $stmt  = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_payroll_summary_id($where) {
    try {
      $fields = array("payroll_summary_id");
      return $this->select_one($fields, $this->tbl_payout_summary, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_payout_header($where) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					A.payroll_hdr_id,
					A.payroll_summary_id,
					A.employee_id,
					A.employee_name,
					A.basic_amount,
					A.position_name,
					A.office_id,
					A.office_name,
					A.total_income,
					A.salary_grade,
					A.pay_step,
					A.total_deductions,
					A.net_pay,
					B.agency_employee_id, 
					E.payout_count
				FROM $this->tbl_payout_header A
				JOIN $this->tbl_employee_personal_info B
					ON A.employee_id = B.employee_id
				JOIN $this->tbl_payout_summary C 
					ON A.payroll_summary_id = C.payroll_summary_id 
				JOIN $this->tbl_attendance_period_hdr D 
					ON C.attendance_period_hdr_id = D.attendance_period_hdr_id
				JOIN $this->tbl_param_payroll_types E 
					ON D.payroll_type_id = E.payroll_type_id
				$where
				ORDER BY employee_name ASC
EOS;
      $stmt  = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_special_payout_header($where) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					A.payroll_hdr_id,
					A.payroll_summary_id,
					A.employee_id,
					A.employee_name,
					A.position_name,
					A.office_id,
					A.office_name,
					A.total_income,
					A.total_deductions,
					A.net_pay,
					B.agency_employee_id
				FROM $this->tbl_payout_header A
				JOIN $this->tbl_employee_personal_info B
					ON A.employee_id = B.employee_id
				JOIN $this->tbl_payout_summary C
					ON A.payroll_summary_id = C.payroll_summary_id
				$where
				ORDER BY employee_name ASC
EOS;
      $stmt  = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_compensations($where, $id) {

    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					A.compensation_id,
					A.report_short_code as compensation_code,
					SUM(B.amount) amount,
					SUM(B.less_amount) less_amount,
					A.less_absence_flag,
					A.basic_salary_flag,
					A.active_flag
				FROM $this->tbl_param_compensations A
				LEFT JOIN $this->tbl_payout_details B
				ON A.compensation_id = B.compensation_id AND B.payroll_hdr_id = $id
				$where
				GROUP BY A.compensation_id
EOS;
      $stmt  = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_deductions($where, $id) {

    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					A.deduction_id,
					A.report_short_code as deduction_code,
					A.statutory_flag,
					SUM(B.amount) amount,
					SUM(B.paid_count) paid_count,
				    C.remittance_type_id,
				    D.remittance_payee_id,
				    A.active_flag
				FROM $this->tbl_param_deductions A
				LEFT JOIN $this->tbl_payout_details B
				ON A.deduction_id = B.deduction_id AND B.payroll_hdr_id = $id
				LEFT JOIN $this->tbl_param_remittance_types C
				ON A.remittance_type_id = C.remittance_type_id
				LEFT JOIN $this->tbl_param_remittance_payees D
				ON C.remittance_payee_id = D.remittance_payee_id
				$where
				GROUP BY A.deduction_id
				ORDER BY D.remittance_payee_id
EOS;
      $stmt  = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_payroll_period_date($where) {
    try {
      $columns = array("CONCAT(DATE_FORMAT(date_from,'%M %d'), ' - ', DATE_FORMAT(date_to,'%d, %Y')) payroll_period");

      $fields = str_replace(" , ", " ", implode(", ", $columns));

      return $this->select_one($fields, $this->tbl_attendance_period_hdr, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_one_specific_id($where) {
    try {

      $fields = array("A.identification_value", "B.format");
      $tables = array(
          'main' => array(
              'table' => $this->tbl_employee_identifications,
              'alias' => 'A',
          ),
          't1'   => array(
              'table'     => $this->tbl_param_identification_types,
              'alias'     => 'B',
              'type'      => 'join',
              'condition' => 'A.identification_type_id = B.identification_type_id',
          )
      );
      return $this->select_one($fields, $tables, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_cutoff_net($where) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT 
					B.employee_id,
				    A.effective_date,
				    SUM(IF(A.compensation_id IS NOT NULL, A.amount, 0.00)) compensation_amount,
				    SUM(IF(A.deduction_id IS NOT NULL, A.amount, 0.00)) deduction_amount
				FROM $this->tbl_payout_details A
			    JOIN $this->tbl_payout_header B 
			    ON A.payroll_hdr_id = B.payroll_hdr_id
			    JOIN $this->tbl_payout_summary C 
			    ON B.payroll_summary_id = C.payroll_summary_id
				$where
				GROUP BY B.employee_id, A.effective_date
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_office_gen_pay($where) {
    try {

      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					A.payroll_type_id,
					A.office_id,
					C.name office_name
				FROM $this->tbl_param_payroll_type_status_offices A
				JOIN $this->tbl_param_offices B
				ON B.office_id = A.office_id
				JOIN $this->DB_CORE.$this->tbl_organizations C
				ON C.org_code = B.org_code
				$where
				GROUP BY A.office_id
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_employee_gen_pay($where) {
    try {

      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];
      /*
        $query = <<<EOS
        SELECT
        B.employee_id id,
        CONCAT(B.first_name, " ",  B.last_name) name
        FROM $this->tbl_employee_work_experiences A
        JOIN $this->tbl_employee_personal_info B
        ON A.employee_id = B.employee_id
        $where
        GROUP BY B.employee_id
        EOS;
       */
      // ====================== jendaigo : start : change name format ============= //
      $query     = <<<EOS
				SELECT
					B.employee_id id,
					CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '. ')), B.last_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name))) name
				FROM $this->tbl_employee_work_experiences A
				JOIN $this->tbl_employee_personal_info B
				ON A.employee_id = B.employee_id
				$where
				GROUP BY B.employee_id
EOS;
      // ====================== jendaigo : end : change name format ============= //

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_special_payslip_details($where) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					A.payroll_hdr_id,
					A.compensation_id, 
					B.compensation_code,
				    C.deduction_code,
				    A.amount
				FROM $this->tbl_payout_details A
				LEFT JOIN $this->tbl_param_compensations B
				ON A.compensation_id = B.compensation_id
				LEFT JOIN $this->tbl_param_deductions C
				ON A.deduction_id = C.deduction_id
				$where
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_specific_compensation($where) {
    try {
      $fields = array("compensation_name");

      return $this->select_one($fields, $this->tbl_param_compensations, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_2316_header($columns, $where) {
    try {
      $fields = str_replace(" , ", " ", implode(", ", $columns));

      return $this->select_one($fields, $this->tbl_form_2316_header, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_2316_dependents($where) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					dependent_name,
					DATE_FORMAT(birth_date,"%m%d%Y") birth_date
				FROM $this->tbl_form_2316_dependents
				$where
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_2316_details($where) {
    try {

      $table = $this->tbl_form_2316_details;
      if (ISSET($where['month'])) {
        $table = $this->tbl_form_2316_monthly_details;
      }

      $fields = array("*");
      return $this->select_one($fields, $table, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_agency_info($office) {
    try {
      $val   = array($office, YES);
      $query = <<<EOS
				SELECT
				B.name,
				A.office_id
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code
				WHERE A.office_id = ? AND A.active_flag = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_sysparam_value($param) {
    try {

      $where = array();

      $table = $this->DB_CORE . "." . $this->tbl_sys_param;

      $where['sys_param_type'] = $param;
      $fields                  = array("sys_param_value");

      return $this->select_one($fields, $table, $where);
    } catch (PDOException $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_previous_employer_detail($where) {
    try {

      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
					B.other_deduction_detail_value
				FROM $this->tbl_employee_deductions A
				LEFT JOIN $this->tbl_employee_deduction_other_details B
				ON A.employee_deduction_id = B.employee_deduction_id
				$where
EOS;

      $stmt = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_payroll_report_parent_id($where) {
    try {
      $fields = array("module_id");
      return $this->select_one($fields, $this->DB_CORE . "." . $this->tbl_modules, $where);
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_genpay_summary_per_office_compensation($where, $attendance_period_hdr_id) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT 
				A.compensation_name, 
				B.amount
				FROM $this->tbl_param_compensations A
				
				LEFT JOIN (
					SELECT 
						B.compensation_id,
						SUM(B.amount) amount
				    FROM $this->tbl_payout_details B 
				
					LEFT JOIN $this->tbl_payout_header C 
					ON B.payroll_hdr_id  = C.payroll_hdr_id 
				
					LEFT JOIN $this->tbl_payout_summary D 
					ON C.payroll_summary_id = D.payroll_summary_id 
				
					LEFT JOIN $this->tbl_attendance_period_hdr E 
					ON D.attendance_period_hdr_id = E.attendance_period_hdr_id 
					AND E.attendance_period_hdr_id = $attendance_period_hdr_id 
				
					LEFT JOIN $this->tbl_param_payroll_type_status_offices F 
					ON E.payroll_type_id = F.payroll_type_id 
				    
				    $where
				    
				    GROUP BY B.compensation_id
				    
				) B  ON A.compensation_id = B.compensation_id 
				
				
				WHERE A.general_payroll_flag = 'y' 
				GROUP BY A.compensation_name
				ORDER BY A.compensation_id ASC
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_genpay_summary_per_office_deduction($where, $attendance_period_hdr_id) {
    try {
      $where_arr = $this->get_where_statement($where);
      $where     = $where_arr['where'];
      $val       = $where_arr['val'];

      $query = <<<EOS
				SELECT
				A.deduction_name,
				B.amount
				FROM $this->tbl_param_deductions A
	
				LEFT JOIN (
					SELECT
						B.deduction_id,
						SUM(B.amount) amount
				    FROM $this->tbl_payout_details B
	
					LEFT JOIN $this->tbl_payout_header C
					ON B.payroll_hdr_id  = C.payroll_hdr_id
	
					LEFT JOIN $this->tbl_payout_summary D
					ON C.payroll_summary_id = D.payroll_summary_id
	
					LEFT JOIN $this->tbl_attendance_period_hdr E
					ON D.attendance_period_hdr_id = E.attendance_period_hdr_id
					AND E.attendance_period_hdr_id = $attendance_period_hdr_id
	
					LEFT JOIN $this->tbl_param_payroll_type_status_offices F
					ON E.payroll_type_id = F.payroll_type_id
	
				    $where
	
				    GROUP BY B.deduction_id
	
				) B  ON A.deduction_id = B.deduction_id
	
	
				WHERE A.general_payroll_flag = 'y'
				GROUP BY A.deduction_name
				ORDER BY A.deduction_id ASC
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_deduction_id_sys_param() {
    try {
      $val = array(SYS_PARAM_TYPE_DEDUC_ID);

      $query = <<<EOS
				SELECT
					sys_param_name,
					GROUP_CONCAT(sys_param_value) sys_param_value
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = ?
				GROUP BY sys_param_name
EOS;

      $stmt = $this->query($query, $val);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_gsis_certificate_of_contribution_list($param, $office, $date, $deduction_id) {
    try {
      $val   = array($param, $office, $date, REMITTANCE_REMITTED, $deduction_id);
      // REMOVED:
      // JOIN $this->tbl_remittance_history C ON C.remittance_id = A.remittance_id
      $query = <<<EOS
				SELECT 
				DATE_FORMAT(D.effective_date, '%M %Y') AS effective_date, 
				SUM(D.amount) amount, 
				SUM(D.employer_amount) employer_amount,
				A.or_no, 
				DATE_FORMAT(A.or_date, '%m/%d/%Y') AS or_date, 
				CONCAT(F.first_name, ' ', LEFT(F.middle_name, 1), '. ', F.last_name) employee_name,
				F.last_name, 
				F.agency_employee_id, 
				E.office_name name, 
				F.gender_code, 
				F.civil_status_id
				FROM $this->tbl_remittances A
				JOIN $this->tbl_remittance_details B ON B.remittance_id = A.remittance_id 
				LEFT JOIN $this->tbl_payout_details D ON D.payroll_dtl_id = B.payroll_dtl_id 
				LEFT JOIN $this->tbl_payout_header E ON E.payroll_hdr_id = D.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info F ON E.employee_id = F.employee_id 
				JOIN $this->tbl_param_deductions I ON D.deduction_id = I.deduction_id
				WHERE E.employee_id = ? 
				AND D.effective_date BETWEEN ? AND ?
				AND A.remittance_status_id = ?
				AND I.deduction_id = ?
				GROUP BY or_date

EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_pagibig_certificate_of_contribution_list($param, $office, $date) {
    try {
      $val   = array($param, $office, $date, REMITTANCE_REMITTED, PARAM_CERT_PAGIBIG_CODE);
      $query = <<<EOS
				SELECT 
				DATE_FORMAT(D.effective_date, '%M %Y') AS effective_date, 
				SUM(D.amount) amount, 
				SUM(D.employer_amount) employer_amount, 
				A.or_no, 
				DATE_FORMAT(A.or_date, '%m/%d/%Y') AS or_date
				FROM $this->tbl_remittances A
				JOIN $this->tbl_remittance_details B ON B.remittance_id = A.remittance_id 
				LEFT JOIN $this->tbl_payout_details D ON D.payroll_dtl_id = B.payroll_dtl_id 
				LEFT JOIN $this->tbl_payout_header E ON E.payroll_hdr_id = D.payroll_hdr_id 
				JOIN $this->tbl_param_deductions I ON D.deduction_id = I.deduction_id
				WHERE E.employee_id = ? 
				AND D.effective_date BETWEEN ? AND ?
				AND A.remittance_status_id = ?
				AND I.deduction_code IN (
					SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = ? ) 
				GROUP BY or_date

EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_philhealth_certificate_of_contribution_list($param, $office, $date) {
    try {
      $val   = array($param, $office, $date, REMITTANCE_REMITTED, DEDUCTION_ST_PHILHEALTH);
      $query = <<<EOS
				SELECT 
				DATE_FORMAT(D.effective_date, '%M %Y') AS effective_date, 
				SUM(D.amount) amount, 
				SUM(D.employer_amount) employer_amount,
				A.or_no, 
				DATE_FORMAT(A.or_date, '%m/%d/%Y') AS or_date, 
				CONCAT(F.first_name, ' ', LEFT(F.middle_name, 1), '. ', F.last_name) employee_name,
				F.last_name, 
				F.agency_employee_id, 
				E.office_name name, 
				F.gender_code, 
				F.civil_status_id
				FROM $this->tbl_remittances A
				JOIN $this->tbl_remittance_details B ON B.remittance_id = A.remittance_id 
				LEFT JOIN $this->tbl_payout_details D ON D.payroll_dtl_id = B.payroll_dtl_id 
				LEFT JOIN $this->tbl_payout_header E ON E.payroll_hdr_id = D.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info F ON E.employee_id = F.employee_id 
				JOIN $this->tbl_param_deductions I ON D.deduction_id = I.deduction_id
				WHERE E.employee_id = ? 
				AND D.effective_date BETWEEN ? AND ?
				AND A.remittance_status_id = ?
				AND I.deduction_code IN (
					SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = ? )
				GROUP BY or_date

EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_certified_by_info($id) {
    try {
      $val   = array($id);
      $query = <<<EOS
				SELECT 
				CONCAT(A.first_name, ' ', LEFT(A.middle_name, (1)), '. ', A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name)), IF(ISNULL(F.others_value), '', CONCAT(', ' , F.others_value))) employee_name,
				B.admin_office_name, 
				B.employ_office_name,
				B.employ_position_name position_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B 
				ON A.employee_id = B.employee_id
				LEFT JOIN $this->tbl_employee_other_info F
				ON A.employee_id =  F.employee_id AND F.other_info_type_id = $this->OTHER_INFO_TYPE_TITLE
				WHERE A.employee_id = ?
				AND B.active_flag = 'Y'
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_doh_address($param) {
    try {
      $val   = array($param);
      $query = <<<EOS
				SELECT
				sys_param_value
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  /* -------------GSIS MEMBERSHIP FORM-------------- */

  public function get_gsis_employment_info($param) {
    try {
      $val   = array($param, DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO);
      $query = <<<EOS
				SELECT 
				C.name,
				D.position_name, 
				E.employment_status_name,
				A.employ_monthly_salary,
				DATE_FORMAT(A.employ_start_date, '%m/%d/%Y') AS employ_start_date
				FROM $this->tbl_employee_work_experiences A
				JOIN $this->tbl_param_offices B ON A.employ_office_id = B.office_id
				JOIN $this->DB_CORE.$this->tbl_organizations C ON B.org_code = C.org_code
				LEFT JOIN $this->tbl_param_positions D ON A.employ_position_id = D.position_id
				LEFT JOIN $this->tbl_param_employment_status E ON A.employment_status_id = E.employment_status_id
				WHERE A.employee_id = ? 
				AND A.employ_type_flag IN(?,?,?)  
				AND A.employ_start_date = (SELECT max(employ_start_date)
				FROM employee_work_experiences
				WHERE employee_id = A.employee_id)
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_gsis_personal_info($param) {
    try {
      $val   = array(TIN_TYPE_ID, $param);
      $query = <<<EOS
				SELECT 
				A.last_name, 
				A.first_name, 
				A.middle_name, 
				A.ext_name,
				A.birth_place,
				B.civil_status_name, 
				C.citizenship_name,
				D.gender, 
				E.identification_value,
				F.format,
				DATE_FORMAT(A.birth_date, '%m/%d/%Y') AS birth_date
				FROM $this->tbl_employee_personal_info A 
				LEFT JOIN $this->tbl_param_civil_status B ON A.civil_status_id = B.civil_status_id
				LEFT JOIN $this->tbl_param_citizenships C ON A.citizenship_id = C.citizenship_id
				LEFT JOIN $this->DB_CORE.$this->tbl_genders D ON A.gender_code = D.gender_code
				LEFT JOIN $this->tbl_employee_identifications E ON A.employee_id = E.employee_id AND E.identification_type_id = ?
				LEFT JOIN $this->tbl_param_identification_types F ON E.identification_type_id = F.identification_type_id
				WHERE A.employee_id = ? 
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_gsis_residential_address_info($param) {
    try {
      $val   = array($param, RESIDENTIAL_ADDRESS);
      $query = <<<EOS
				SELECT
				A.address_value,
				A.postal_number,
				B.barangay_name,
				C.municity_name,
				D.province_name
				FROM $this->tbl_employee_addresses A
				LEFT JOIN $this->DB_CORE.$this->tbl_barangays B
				ON B.barangay_code = A.barangay_code
				LEFT JOIN $this->DB_CORE.$this->tbl_municities C
				ON C.municity_code = A.municity_code
				LEFT JOIN $this->DB_CORE.$this->tbl_provinces D
				ON D.province_code = A.province_code
				WHERE A.employee_id = ? AND A.address_type_id = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_gsis_contacts_info($param) {
    try {
      $val   = array(RESIDENTIAL_NUMBER, PERMANENT_NUMBER, MOBILE_NUMBER, EMAIL, $param);
      $query = <<<EOS
				SELECT
				A.employee_id,
				B.contact_value hometel,
				C.contact_value landline,
				D.contact_value mobile,
				E.contact_value email
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_contacts B
				ON A.employee_id = B.employee_id AND B.contact_type_id = ?
				JOIN $this->tbl_employee_contacts C
				ON A.employee_id = C.employee_id AND C.contact_type_id = ?
				JOIN $this->tbl_employee_contacts D
				ON A.employee_id = D.employee_id AND D.contact_type_id = ?
				JOIN $this->tbl_employee_contacts E
				ON A.employee_id = E.employee_id AND E.contact_type_id = ?
				WHERE A.employee_id = ?
EOS;
      $stmt  = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error();
    }
  }

  /* ----------PAGIBIG MEMBERSHIP FORM---------- */

  public function get_pagibig_personal_info($id) {
    try {
      $val   = array($id);
      $query = <<<EOS

				SELECT 
				A.last_name,
				A.first_name,
				A.middle_name,
				A.ext_name,
				A.gender_code,
				A.civil_status_id,
				DATE_FORMAT(A.birth_date, '%m/%d/%Y') AS birth_date,
				A.birth_place,
				A.height,
				A.weight,
				B.blood_type_name, 
				C.citizenship_name
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_param_blood_type B ON B.blood_type_id = A.blood_type_id
				LEFT JOIN $this->tbl_param_citizenships C ON C.citizenship_id = A.citizenship_id
				WHERE A.employee_id = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      RLog::error($e->getMessage());
      throw new Exception($e->getMessage());
    } catch (Exception $e) {
      RLog::error($e->getMessage());
      throw new Exception($e->getMessage());
    }
  }

  public function get_pagibig_address($id, $address) {
    try {
      $val   = array($id, $address);
      $query = <<<EOS
				SELECT 
				A.address_value,
				A.postal_number,
				B.barangay_name, 
				C.municity_name,
				D.province_name
				FROM $this->tbl_employee_addresses A
				LEFT JOIN $this->DB_CORE.$this->tbl_barangays B 
				ON B.barangay_code = A.barangay_code
				LEFT JOIN $this->DB_CORE.$this->tbl_municities C 
				ON C.municity_code = A.municity_code
				LEFT JOIN $this->DB_CORE.$this->tbl_provinces D 
				ON D.province_code = A.province_code 
				WHERE A.employee_id = ? AND A.address_type_id = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_pagibig_numbers_contacts($id) {
    try {
      $val   = array(PAGIBIG_TYPE_ID, TIN_TYPE_ID, SSS_TYPE_ID, PERMANENT_NUMBER, MOBILE_NUMBER, EMAIL, $id);
      $query = <<<EOS
				SELECT
				A.employee_id,
				B.identification_value pagibig,
				C.identification_value tin,
				D.identification_value sss,
				E.ctc_no crn,
				F.contact_value landline,
				G.contact_value mobile,
				H.contact_value email
				FROM employee_personal_info A
				LEFT JOIN employee_identifications B
				ON B.employee_id = A.employee_id AND B.identification_type_id = ?
				LEFT JOIN employee_identifications C
				ON C.employee_id = A.employee_id AND C.identification_type_id = ?
				LEFT JOIN employee_identifications D
				ON D.employee_id = A.employee_id AND D.identification_type_id = ?
				LEFT JOIN employee_declaration E
				ON E.employee_id = A.employee_id
				JOIN employee_contacts F
				ON F.employee_id = A.employee_id AND F.contact_type_id = ?
				JOIN employee_contacts G
				ON G.employee_id = A.employee_id AND G.contact_type_id = ?
				JOIN employee_contacts H
				ON H.employee_id = A.employee_id AND H.contact_type_id = ?
				WHERE A.employee_id = ?;
EOS;
      $stmt  = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_present_employment_details($id) {
    try {
      $val   = array($id, YES);
      $query = <<<EOS
				SELECT 
				A.employ_office_name office_name,
				C.position_name,
				DATE_FORMAT(employ_start_date, '%m-%d-%Y') start_date,
				B.employment_status_name status_name
				FROM employee_work_experiences A 
				LEFT JOIN param_employment_status B	ON B.employment_status_id = A.employment_status_id
				LEFT JOIN param_positions C	ON A.employ_position_id = C.position_id
				WHERE A.employee_id = ? AND A.active_flag = ?
EOS;
      $stmt  = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_pagibig_heirs($id) {
    try {
      $val   = array($id, NO);
      $query = <<<EOS
			SELECT
			A.relation_last_name,
			A.relation_first_name,
			A.relation_ext_name,
			A.relation_middle_name,
			B.relation_type_name,
			DATE_FORMAT(A.relation_birth_date, '%m %d %Y') birth_date
			FROM
			$this->tbl_employee_relations A
			LEFT JOIN $this->tbl_param_relation_types B	ON B.relation_type_id = A.relation_type_id
			WHERE A.employee_id = ? 
			AND A.pagibig_flag = ?
			ORDER BY A.relation_type_id ASC
EOS;
      $stmt  = $this->query($query, $val, TRUE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  /* ----------PHILHEALTH MEMBER REGISTRATION FORM---------- */

  public function get_philhealth_personal_info($id) {
    try {
      $val   = array(PHILHEALTH_TYPE_ID, TIN_TYPE_ID, $id);
      $query = <<<EOS
				SELECT
				A.last_name,
				A.first_name,
				A.middle_name,
				A.ext_name,
				DATE_FORMAT(A.birth_date, '%m-%d-%Y') AS birth_date,
				A.birth_place,
				A.gender_code,
				A.civil_status_id,
				B.citizenship_name,
				C.identification_value pin,
				D.identification_value tin
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_param_citizenships B
				ON B.citizenship_id = A.citizenship_id
				LEFT JOIN $this->tbl_employee_identifications C
				ON C.employee_id = A.employee_id AND C.identification_type_id = ?
				LEFT JOIN $this->tbl_employee_identifications D
				ON D.employee_id = A.employee_id AND D.identification_type_id = ?
				WHERE A.employee_id = ?
EOS;
      $stmt  = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      RLog::error($e->getMessage());
      throw new Exception($e->getMessage());
    } catch (Exception $e) {
      RLog::error($e->getMessage());
      throw new Exception($e->getMessage());
    }
  }

  public function get_philhealth_permanent_address_info($id) {
    try {
      $val   = array($id, PERMANENT_ADDRESS);
      $query = <<<EOS
				SELECT
				A.address_value,
				A.postal_number,
				B.barangay_name,
				C.municity_name,
				D.province_name
				FROM $this->tbl_employee_addresses A
				LEFT JOIN $this->DB_CORE.$this->tbl_barangays B
				ON B.barangay_code = A.barangay_code
				LEFT JOIN $this->DB_CORE.$this->tbl_municities C
				ON C.municity_code = A.municity_code
				LEFT JOIN $this->DB_CORE.$this->tbl_provinces D
				ON D.province_code = A.province_code
				WHERE A.employee_id = ? AND A.address_type_id = ?
EOS;
      $stmt  = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_philhealth_contacts_info($id) {
    try {
      $val   = array(PERMANENT_NUMBER, MOBILE_NUMBER, EMAIL, $id);
      $query = <<<EOS
				SELECT
				A.employee_id,
				B.contact_value landline,
				C.contact_value mobile,
				D.contact_value email
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_contacts B
				ON A.employee_id = B.employee_id AND B.contact_type_id = ?
				JOIN $this->tbl_employee_contacts C
				ON A.employee_id = C.employee_id AND C.contact_type_id = ?
				JOIN $this->tbl_employee_contacts D
				ON A.employee_id = D.employee_id AND D.contact_type_id = ?
				WHERE A.employee_id = ?
EOS;
      $stmt  = $this->query($query, $val, FALSE);
      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error();
    }
  }

  public function get_formal_economy_info($id) {
    try {
      $val   = array($id, YES);
      $query = <<<EOS
				SELECT 
				B.employment_status_name
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_param_employment_status B ON A.employment_status_id = B.employment_status_id
				WHERE A.employee_id = ? 
				AND A.active_flag = ?
EOS;
      $stmt  = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOExceptio $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_special_payroll_summary_per_office_deductions($fields, $where, $multiple = FALSE) {
    try {
      $fields = str_replace(" , ", " ", implode(", ", $fields));
      $query  = <<<EOS
				SELECT $fields
				FROM payout_header A
				JOIN payout_details B 
				ON A.payroll_hdr_id = B.payroll_hdr_id 
				JOIN payout_summary C 
				ON A.payroll_summary_id = C.payroll_summary_id 
				JOIN param_deductions D 
				ON B.deduction_id = D.deduction_id 
				WHERE B.deduction_id IS NOT NULL 
				AND EXTRACT(YEAR_MONTH FROM B.effective_date) = ?
				AND C.compensation_id = ? 
				AND C.payout_type_flag = ? 
				AND A.payroll_hdr_id IN(SELECT payroll_hdr_id FROM payout_details WHERE compensation_id IN (?))
				GROUP BY B.deduction_id
EOS;
      $stmt   = $this->query($query, $where, $multiple);

      return $stmt;
    } catch (PDOExceptio $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_special_payroll_summary_per_office_deductions_amounts($where, $multiple = FALSE) {
    try {
      $query = <<<EOS
					SELECT
					B.employee_id,
					A.deduction_id,
					SUM(A.amount) amount
					FROM payout_details A
					JOIN payout_header B
					ON A.payroll_hdr_id = B.payroll_hdr_id
					JOIN param_offices C
					ON B.office_id = C.office_id
					JOIN payout_summary D
					ON B.payroll_summary_id = D.payroll_summary_id
					WHERE EXTRACT(YEAR FROM A.effective_date) = ?
					AND EXTRACT(MONTH FROM A.effective_date) = ?
					AND D.payroll_summary_id = ?
					AND D.payout_type_flag = ?
					AND B.office_id IN(?)
					AND B.payroll_hdr_id IN
					(SELECT payroll_hdr_id FROM payout_details WHERE compensation_id IN (?))
					AND A.deduction_id = ?
					AND B.employee_id= ?
					GROUP BY B.employee_id
EOS;
      $stmt  = $this->query($query, $where, $multiple);

      return $stmt;
    } catch (PDOExceptio $e) {
      $this->rlog($e);
    } catch (Exception $e) {
      $this->rlog($e);
    }
  }

  public function get_period($period_type_id) {
    try {
      $fields = array("*");
      $where  = array('payroll_type_id' => $period_type_id);
      return $this->select_one($fields, $this->tbl_attendance_period_hdr, $where);
    } catch (PDOException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function get_office($office) {
    try {
      $fields = array("*");
      $where  = array('employ_office_id' => $office);
      return $this->select_one($fields, $this->tbl_employee_work_experiences, $where);
    } catch (PDOException $e) {
      throw $e;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function get_certification_employee_info($id, $deduc_code_param) {
    try {
      $val   = array(YES, YES, $deduc_code_param, $id);
      $query = <<<EOS
				SELECT 
				    GROUP_CONCAT(distinct D.other_deduction_detail_value) other_deduction_detail_value,
				    CONCAT(F.first_name,
				            ' ',
				            LEFT(F.middle_name, 1),
				            '. ',
				            F.last_name,
				            ' ',
				            F.ext_name) employee_name,
				    F.last_name,
				    F.agency_employee_id,
				    CONCAT(G.employ_office_name,' (',IFNULL(J.org_code,''),')') employ_office_name,
				    CONCAT(G.admin_office_name,' (',IFNULL(K.org_code,''),')') admin_office_name,
				    F.gender_code,
				    F.civil_status_id
				FROM    
					$this->tbl_employee_personal_info F
				        LEFT JOIN
				    $this->tbl_employee_work_experiences G ON F.employee_id = G.employee_id AND G.active_flag = ?
				        LEFT JOIN
				    $this->tbl_employee_deductions C ON C.employee_id = F.employee_id
				        LEFT JOIN
				    $this->tbl_employee_deduction_other_details D ON C.employee_deduction_id = D.employee_deduction_id
				        LEFT JOIN
				    $this->tbl_param_other_deduction_details E ON D.other_deduction_detail_id = E.other_deduction_detail_id
				        LEFT JOIN
				    $this->tbl_param_deductions A ON C.deduction_id = A.deduction_id
				    LEFT JOIN
				    $this->tbl_param_offices J ON G.employ_office_id = J.office_id
				    LEFT JOIN
				    $this->tbl_param_offices K ON G.admin_office_id = K.office_id
					    
				WHERE
					E.pk_flag = ? 
					AND A.deduction_code IN (SELECT 
					            sys_param_value
					        FROM
					            $this->DB_CORE.$this->tbl_sys_param
					        WHERE
				            sys_param_type = ?)
				    AND F.employee_id = ?
EOS;

      $stmt = $this->query($query, $val, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_other_ded_dlt_value($employees, $deduction_id) {
    try {
      $where = array();

      $deduction_filter = '';
      if ($deduction_id) {
        $ded_cnt          = count($deduction_id);
        $deduction_filter = 'AND C.deduction_id IN (';
        foreach ($deduction_id as $key => $value) {
          if ($ded_cnt > $key + 1) {
            $deduction_filter .= '?,';
          } else {
            $deduction_filter .= '?';
          }
          $where[] = $value;
        }
        $deduction_filter .= ')';
      }

      $employee_filter = '';
      if ($employees) {
        $emp_cnt         = count($employees);
        $employee_filter = ' AND C.employee_id IN (';
        foreach ($employees as $key => $value) {
          if ($emp_cnt > $key + 1) {
            $employee_filter .= '?,';
          } else {
            $employee_filter .= '?';
          }
          $where[] = $value;
        }
        $employee_filter .= ')';
      }

      $query = <<<EOS
				SELECT 
				    GROUP_CONCAT(DISTINCT D.other_deduction_detail_value SEPARATOR '/') other_deduction_detail_value,
				    GROUP_CONCAT(DISTINCT B.other_detail_name SEPARATOR '/<br>') other_detail_name,
				    A.deduction_type_flag,
				    C.employee_id
				FROM
				    $this->tbl_employee_deductions C
				        LEFT JOIN
				    $this->tbl_employee_deduction_other_details D ON C.employee_deduction_id = D.employee_deduction_id
				        LEFT JOIN
				    $this->tbl_param_other_deduction_details B ON C.deduction_id = B.deduction_id
				    	JOIN 
				    $this->tbl_param_deductions A ON C.deduction_id = A.deduction_id
				WHERE
				    B.pk_flag = 'Y'
				    $deduction_filter
				    $employee_filter
				GROUP BY C.employee_id
EOS;

      $stmt = $this->query($query, $where, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_other_ded_dlt_name($deduction_id) {
    try {
      $where = array();

      $deduction_filter = '';
      if ($deduction_id) {
        $ded_cnt          = count($deduction_id);
        $deduction_filter = ' A.deduction_id IN (';
        foreach ($deduction_id as $key => $value) {
          if ($ded_cnt > $key + 1) {
            $deduction_filter .= '?,';
          } else {
            $deduction_filter .= '?';
          }
          $where[] = $value;
        }
        $deduction_filter .= ')';
      }

      $query = <<<EOS
				 SELECT 
				    B.other_detail_name, A.deduction_type_flag
				FROM
				     $this->tbl_param_deductions A
				        LEFT JOIN
				     $this->tbl_param_other_deduction_details B ON A.deduction_id = B.deduction_id
				WHERE
				    $deduction_filter
				        AND B.pk_flag = 'Y'
				GROUP BY B.other_detail_name
EOS;

      $stmt = $this->query($query, $where, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_report_paper_size($report_type, $payroll_type) {
    try {
      $payroll_type_id = '%' . $payroll_type . '%';
      $where           = array($report_type, $payroll_type_id);
      $query           = <<<EOS
				SELECT 
				    size_width, size_length
				FROM
				    $this->tbl_param_report_paper_size
				WHERE
				    report_type = ?
				        AND payroll_type_ids LIKE ?;
EOS;

      $stmt = $this->query($query, $where, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_signatory_list($signatory_type) {
    try {
      $val   = array($signatory_type);
      $query = <<<EOS
				SELECT 
			    A.employee_id,
				UPPER(CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)))) as employee_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				WHERE A.agency_employee_id IN (SELECT sys_param_value
			        FROM
			            $this->DB_CORE.$this->tbl_sys_param
			        WHERE
			            sys_param_type = ?)
				GROUP BY A.employee_id
EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_report_signatories_ca() {
    try {
      $val   = array('%' . CODE_PAYROLL . '%', '%' . CASH_AVAILABLE_BY . '%', '%' . CERTIFIED_BY . '%');
      $query = <<<EOS
					SELECT 
					report_signatory_id, 
					signatory_name
					FROM param_report_signatories
					WHERE
					    sys_code_flags LIKE ?
					        AND (signatory_type_flags LIKE ?
					         OR signatory_type_flags LIKE ?)
EOS;

      $stmt = $this->query($query, $val, TRUE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_general_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group = array(), $limit = NULL) {
    try {

      if ($multiple) {
        return $this->select_all($fields, $table, $where, $order_by, $group, $limit);
      } else {
        return $this->select_one($fields, $table, $where, $order_by, $group, $limit);
      }
    } catch (PDOException $e) {
      RLog::error($e->getMessage());
      throw new Exception($e->getMessage());
    } catch (Exception $e) {
      RLog::error($e->getMessage());
      throw new Exception($e->getMessage());
    }
  }

  //modesto
  public function get_premium_active() {
    try {
      $query = <<<EOS
					SELECT 
					rate 
					FROM param_compensations
					WHERE report_short_code = 'PREM' AND active_flag = 'Y'
EOS;

      $stmt = $this->query($query, NULL, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function get_deduction_checks($id) {
    try {
      $query = <<<EOS
					SELECT GROUP_CONCAT(DISTINCT deduction_id) deduction_ids
					FROM employee_deductions
					WHERE employee_id = $id 
EOS;

      $stmt = $this->query($query, NULL, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }

  public function getRespDesc($employee) {
    try {
      $query = <<<EOS
					SELECT
	param_responsibility_centers.responsibility_center_desc
FROM
	employee_responsibility_codes
LEFT JOIN param_responsibility_centers ON param_responsibility_centers.responsibility_center_code = employee_responsibility_codes.responsibility_center_code
WHERE employee_responsibility_codes.employee_id = $employee
EOS;

      $stmt = $this->query($query, NULL, FALSE);

      return $stmt;
    } catch (PDOException $e) {
      $this->rlog_error($e);
    } catch (Exception $e) {
      $this->rlog_error($e);
    }
  }


}
