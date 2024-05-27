<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s6 m6 l6">
                    <h5 class="breadcrumbs-title"> <?php echo ucwords(SUB_MENU_PERFORMANCE_EVALUATION); ?> </h5>
                    <ol class="breadcrumb m-n">
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
                            <select name="C-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
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
     <!--breadcrumbs end-->

    <!--start container-->
    <div class="container p-t-n">
        <div class="section panel p-lg p-t-lg">
          
            <!--start section-->
            <div class="pre-datatable filter-left"></div>
                <div>
                    <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_details">
                        <thead>
                            <tr>
                                <th width="15%">Employee Number</th>
                                <th width="25%">Employee Name</th>
                                <th width="25%">Office</th>
                                <th width="15%">Employment Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                            <tr class="table-filters">
                                <td><input name="A-agency_employee_id" class="form-filter"></td>
                                <td><input name="fullname" class="form-filter"></td>
                                <td><input name="E-name" class="form-filter"></td>
                                <td><input name="D-employment_status_name" class="form-filter"></td>
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
