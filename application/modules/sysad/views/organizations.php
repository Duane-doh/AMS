
<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s9 m9 l9">
                    <h5 class="breadcrumbs-title"> Organizations </h5>
                    <ol class="breadcrumb m-n p-b-sm">
                     <?php get_breadcrumbs();?>
                 </ol>
             </div>
             <div class="col l3 m3 s3 right-align p-t-xl">   
                <div class="input-field inline p-l-md">
                    <button type="button" class="btn  md-trigger btn-success" data-modal="modal_organizations" onclick="modal_init();" id="add_programs">Add Organization</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs end-->

<!--start container-->
<div class="container">
    <div class="section panel p-lg">
        <!--start section-->
        <div class="pre-datatable filter-left"></div>
        <div>
            <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="organizations_table">
                <thead>
                    <tr>
                        <th width="25%">Organization</th>
                        <th width="25%">Parent Organization</th>
                        <th width="20%">Website</th>
                        <th width="20%">Email</th>
                        <!-- <th width="10%" class="text-center">Actions</th> -->
                        <!-- NCOCAMPO:ADDED STATUS TO VIEWS:START -->
                        <th width="20%">Status</th>
                        <!-- NCOCAMPO:ADDED STATUS TO VIEWS:END -->
                        <th width="2%" class="text-center">Actions</th>
                    </tr>
                    <tr class="table-filters">
                      <td><input name="a-name" class="form-filter"></td>
                      <td><input name="b-name" class="form-filter"></td>
                      <td><input name="a-website" class="form-filter"></td>
                      <td><input name="a-email" class="form-filter"></td>
                      <td>
                        <input name="active_flag" class="form-filter">
                      </td> <!-- //ncocampo -->
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

</section>
<!-- END CONTENT -->
<!-- Modal -->
<div id="modal_organizations" class="md-modal md-effect-<?php echo MODAL_EFFECT ?>">
    <div class="md-content">
        <a class="md-close icon">&times;</a>
        <h3 class="md-header">Organization</h3>
        <div id="modal_organizations_content"></div>
    </div>
</div>
<div class="md-overlay"></div>

<script type="text/javascript">
    var modalObj  = new handleModal({ controller : 'organizations', modal_id: 'modal_organizations', module: '<?php echo PROJECT_CORE ?>' });
    var deleteObj = new handleData({ controller  : 'organizations', method : 'delete_organizations', module: '<?php echo PROJECT_CORE ?>' });

    $(function(){   

        $(document).on("click", "#cancel_organization", function(){
            modalObj.closeModal();
        });

    });
</script>