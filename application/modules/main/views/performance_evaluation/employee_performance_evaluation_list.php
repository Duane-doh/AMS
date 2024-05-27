<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
 
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s7 m7 l7">
                    <h5 class="breadcrumbs-title"><?php echo SUB_MENU_PERFORMANCE_EVALUATION; ?></h5>
                    <ol class="breadcrumb m-n p-b-sm">
                        <?php get_breadcrumbs();?>
                    </ol>
                    <!-- <span>Manage all <?php echo SUB_MENU_PERFORMANCE_EVALUATION; ?></span> -->
                </div>
                <div class="col l5 m5 s5 p-t-n user-avatar">
                    <div class="row m-n">
                        <?php 
                            /* GET USER AVATAR */
                            $emp_avatar_src = base_url() . PATH_USER_UPLOADS . $personal_info['photo'];
                            $emp_avatar_src = @getimagesize($emp_avatar_src) ? $emp_avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
                          ?>
                        <img class="circle" width="65" height="65" src="<?php echo  $emp_avatar_src?>"/> 
                        <label class="dark font-xl"><?php echo ucwords($personal_info['fullname']); ?></label><br>
                        <label class="font-lg"><?php echo $personal_info['position_name']; ?></label><br>
                        <label class="font-md"><?php echo $personal_info['name']; ?></label>
                    </div>
                </div>
            </div>
        </div>
    <!--breadcrumbs end-->

    <!--start container-->
    <div class="container p-t-n">
        <div class="section panel p-lg p-t-sm">
            <?php if($module != MODULE_PERSONNEL_PORTAL && $allow_office):?>
            <div class="col l6 m6 s3 right-align m-b-n-lg">
                <div class="input-field inline p-l-md m-t-sm">
                    <?php 
                        $salt      = gen_salt();
                        $token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
                        $url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module."/".$employee_id;
                    ?>
                    <button class="btn btn-success md-trigger" data-modal="modal_performance_evaluation" onclick="modal_performance_evaluation_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add Evaluation</button>   
                </div>
            </div>
            <?php else : ?>
            <div class="m-t-md"></div>
            <?php endif;?>
            <!--start section-->
            <div class="pre-datatable filter-left"></div>
            <div class="p-t-xl">
                 <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_performance_evaluation">
                    <thead>
                        <tr>
                            <th width="10%">Start Date</th>
                            <th width="10%">End Date</th>
                            <th width="10%">Rating</th>
                            <th width="20%">Description</th>
                            <th width="20%">Remarks</th>
                            <th width="10%">Classification</th>
                            <th width="10%">Actions</th>
                        </tr>
                        <tr class="table-filters">
                            <td><input name="evaluation_start_date" class="form-filter"></td>
                            <td><input name="evaluation_end_date" class="form-filter"></td>
                            <td><input name="A-rating" class="form-filter"></td>
                            <td><input name="A-rating_description" class="form-filter"></td>
                            <td><input name="A-remarks" class="form-filter"></td>
                            <td><input name="B-classification_field_name" class="form-filter"></td>
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
    <!--end container-->

    </div>
</section>
<!-- END CONTENT -->