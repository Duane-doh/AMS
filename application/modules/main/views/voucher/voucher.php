<section id="content" class="p-t-n m-t-n ">
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s6 m6 l6">
                    <h5 class="breadcrumbs-title"><?php echo SUB_MENU_VOUCHER_PAYROLL; ?></h5>
                    <ol class="breadcrumb m-n p-b-sm">
                         <?php get_breadcrumbs();?>
                    </ol>
                </div>
                <div class="col s6 m6 l6 right-align">
                    <!-- <span>Manage all <?php echo SUB_MENU_PERFORMANCE_EVALUATION; ?></span> -->
                    <!-- <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p> -->
                    <div class="row form-vertical form-styled form-basic">
                        <div class="input-field col s4">
                              <label class="label position-left active">You are currently viewing: </label>
                        </div>
                        <div class="col s8">
                            <select name="D-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
                                <option></option>
                                <?php 
                                    foreach ($office_list as $key => $value) {
                                        echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container p-t-n">
            <div class="section panel p-lg p-t-n">
                <div class="col l6 m6 s3 right-align m-b-n-lg">
                    <div class="input-field inline p-l-md">
                        <?php 
                            $salt      = gen_salt();
                            $module    = MODULE_PAYROLL_VOUCHER;
                            $token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
                            $url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
                        ?>
                        <button class="btn btn-success  md-trigger" data-modal="modal_voucher" onclick="modal_voucher_init('<?php echo $url_add; ?>')"> Prepare <?php echo SUB_MENU_VOUCHER_PAYROLL; ?></button>
                    </div>
                </div>
                <div class="pre-datatable filter-left"></div>
                <div class="p-t-xl">
                    <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="payroll_list">
                        <thead>
                            <tr>
                                <th width="20%">Employee Name</th>
                                <th width="20%">Voucher Description</th>
                                <th width="15%">Net Amount</th>
                                <th width="15%">Prepared Date</th>
                                <th width="15%">Status</th>
                                <th width="15%">Action</th>
                            </tr>
                            <tr class="table-filters">
                                <td><input name="D-employee_name" class="form-filter"></td>
                                <td><input name="A-voucher_description" class="form-filter"></td>
                                <td><input name="D-net_pay" class="form-filter"></td>
                                <td><input name="process_date" class="form-filter"></td>
                                <td><input name="C-payout_status_name" class="form-filter"></td>
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
            </div>
        </div>
    </div>
</section>