<?php $data_id = 'modal_remittance/' . ACTION_ADD; ?>

<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s6 m6 l6">
                    <h5 class="breadcrumbs-title"><?php echo SUB_MENU_REMITTANCE_PAYROLL; ?></h5>
                    <ol class="breadcrumb m-n p-b-sm">
                         <?php get_breadcrumbs();?>
                    </ol>
                </div>
                <div class="col s6 m6 l6 right-align">
                </div>
            </div>
        </div>
    <!--breadcrumbs end-->

    <!--start container-->
    <div class="container p-t-n">
        <div class="section panel p-lg p-t-n">
            <div class="col l6 m6 s3 right-align m-b-n-lg">
                <div class="input-field inline p-l-md p-b-xl">
                    <?php if($this->permission->check_permission(MODULE_PAYROLL_REMITTANCE, ACTION_ADD)) :?>
                        <button class="btn btn-success  md-trigger" data-modal="modal_remittance" onclick="modal_remittance_init('<?php echo $data_id; ?>')"> Prepare <?php echo SUB_MENU_REMITTANCE_PAYROLL; ?></button>
                    <?php endif; ?>
                </div>
            </div>
            <!--start section-->

            <!-- TABLE -->
            <div class="pre-datatable filter-left"></div>
            <div>
                <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="payroll_remittance_list_tbl">
                <thead>
                    <tr>
                        <th width="20%">Remittance Type</th>
                        <th width="15%">Remittance For</th>
                        <th width="15%">Deduction Start Date</th>
                        <th width="15%">Deduction End Date</th>
                        <th width="15%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                    <tr class="table-filters">
                        <td><input name="B-remittance_type_name" class="form-filter"></td>
                        <td><input name="month_year" class="form-filter"></td>
                        <td><input name="A-deduction_start_date" class="form-filter"></td>
                        <td><input name="A-deduction_end_date" class="form-filter"></td>
                        <td><input name="C-remittance_status_name" class="form-filter"></td>
                        <td class="table-actions">
                            <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
                            <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
                        </td>
                    </tr>
                </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--end section-->              
        </div>
     </div>
    </div>
    <!--end container-->

</section>
<!-- END CONTENT -->


