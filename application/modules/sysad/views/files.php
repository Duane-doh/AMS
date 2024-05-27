
<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s6 m6 l6">
                <h5 class="breadcrumbs-title">Files </h5>
                <ol class="breadcrumb m-n p-b-sm">
                     <?php get_breadcrumbs();?>
                </ol>
              </div>
              	<div class="col s6 right-align p-t-lg">
				  <div class="btn-group">
					<button id="files_sort_date" class=" sort-btn" type="button" onclick="sort($(this),'file_name|created_date', '#file_list', '<?php echo PROJECT_CORE ?>/files/sort_by/', 'input.search-box', 'li.list-item')"><i class="flaticon-down95"></i> Date</button>
					<button id="files_sort_name" class=" sort-btn" type="button" onclick="sort($(this),'file_name', '#file_list', '<?php echo PROJECT_CORE ?>/files/sort_by/', 'input.search-box', 'li.list-item')"><i class="flaticon-down95"></i> Name</button>
				  </div>
				  
				  <div class="input-field inline p-l-md">
					<button type="button" class="btn  md-trigger btn-success" data-modal="modal_upload_file" id="add_role" name="add_role" onclick="modal_init()">Upload Files</button>
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
      			<div class="m-t-lg">
  
					  <div class="row">
					    <div class="col l9 m12 s12">
						  
						  <div id="file_list">
							<?php 
								$previous_date = $class = '';
								
								foreach($files as $file): 
								  $filename = $file['file_name'];
								  $display_name = !EMPTY($file['display_name']) ? $file['display_name'] : $file['file_name'];
								  $version = $file['version'];
								  $date = date("F d", strtotime($file['created_date']));
								  $path = PATH_TASK_UPLOADS . $filename;
								  
								  $ext = pathinfo($path, PATHINFO_EXTENSION);
								  
								  $id = $file['file_id'];
								  $file_version_id = !IS_NULL($file['file_version_id']) ? base64_url_encode($file['file_version_id']) : NULL;
								  $base_id = base64_url_encode($id);
								  $salt = gen_salt();
								  $token = in_salt($id, $salt);
								  $url = $base_id . '/' . $salt . '/' . $token . '/' . $file_version_id;
								  
								  if($ext == "JPEG" || $ext == "JPG" || $ext == "jpeg" || $ext == "jpg" || $ext == "png"){
								    $image_path = base_url(). PATH_FILE_UPLOADS . $file['file_name'];
								    list($width, $height) = @getimagesize($image_path);
								    $class = ($width > $height) ? " landscape": " portrait";
								  }
									
								  if(!EMPTY($previous_date) && $previous_date !== $date) 
									echo '</ul>';
								  
								  if($previous_date !== $date){
									$title = ($date == date("F d")) ? "Today" : $date;
									echo '<h5 class="page-content-title">'.$title.'</h5><ul class="list-grid file-type m-b-lg">';
								  }
							?><li class="list-item">
							  <div class="<?php echo $ext ?> box-shadow">
							    <?php if($ext == "JPEG" || $ext == "JPG" || $ext == "jpeg" || $ext == "jpg" || $ext == "png"){ ?>
								  <div class="<?php echo $class ?>">
							        <img src="<?php echo $image_path ?>"/>
								  </div>
								<?php } ?>
								
								<?php if($version > 1){ ?>
								  <div class="list-counter">Version <?php echo $version ?></div>
								<?php } ?>
								<div class="row m-b-n list-details">
								  <div class="col s9">
									<p class="truncate m-n"><?php echo $display_name; ?></p>
							      </div>
									
								  <div class="col s3">
									<a class="dropdown-button" href="#!" data-activates="dropdown<?php echo $id ?>"><i class="material-icons">more_vert</i></a>
							      </div>
							    </div>
							  </div>
							  
							  <ul id="dropdown<?php echo $id ?>" class="dropdown-content box-shadow">
							    <li><a href="#!" class="md-trigger" data-modal="modal_upload_file" onclick="modal_init('<?php echo $url ?>')"><i class="material-icons">mode_edit</i> Edit</a></li>
							    <li><a href="#!" class="md-trigger" data-modal="modal_file_version_list" onclick="version_list_modal_init('<?php echo $base_id . '/' . $salt . '/' . $token ?>')"><i class="material-icons">add_to_photos</i> Versions</a></li>
							    <li><a href="#!" onclick="content_delete('file', '<?php echo $base_id ?>', '<?php echo $file_version_id ?>')"><i class="material-icons">delete</i> Delete</a></li>
							    <li><a href="#!" class="md-trigger" id="upload_version_<?php echo $url ?>" data-modal="modal_upload_file_revision" onclick="revision_modal_init('<?php echo $url ?>')"><i class="material-icons">backup</i> Upload a new version</a></li>
							  </ul>
							</li><?php 					
								  $previous_date = $date;
								endforeach; 
							?>
						  </div>&nbsp;
						</div>
					    <div class="col l3 m12 s12 bg-white">
						  <div class="m-t-md m-b-sm b-b" style="border-color:#ddd!important; border-width:2px!important">
						    <input type="text" name="search" placeholder="Search for files..." class="search-box"/>
						  </div>
						  <div class="list-basic">
						    <div class="list-header">
							  <h5><i class="flaticon-archive29"></i> Archive</h5>
						    </div>
						  </div>
						</div>
					  </div>
					</div>
      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->



<!-- Modal -->
<div id="modal_upload_file" class="md-modal lg md-effect-<?php echo MODAL_EFFECT ?>">
  <div class="md-content">
	<a class="md-close icon">&times;</a>
	<h3 class="md-header">Upload Files</h3>
	<div id="modal_upload_file_content"></div>
  </div>
</div>

<div id="modal_upload_file_revision" class="md-modal lg md-effect-<?php echo MODAL_EFFECT ?>">
  <div class="md-content">
	<a class="md-close icon">&times;</a>
	<h3 class="md-header">Upload File Version</h3>
	<div id="modal_upload_file_revision_content"></div>
  </div>
</div>

<div id="modal_file_version_list" class="md-modal md md-effect-<?php echo MODAL_EFFECT ?>">
  <div class="md-content">
	<a class="md-close icon" onclick="filter('file_name|created_date','DESC', '#file_list', '<?php echo PROJECT_CORE ?>/files/sort_by/', 'input.search-box', 'li.list-item')">&times;</a>
	<h3 class="md-header">File Versions</h3>
	<div id="modal_file_version_list_content" class="p-b-md"></div>
  </div>
</div>
<div class="md-overlay"></div>


<script type="text/javascript">
var modalObj = new handleModal({ controller : 'files', modal_id: 'modal_upload_file', module: '<?php echo PROJECT_CORE ?>' }),
	deleteObj = new handleData({ controller : 'files', method : 'delete_file', module: '<?php echo PROJECT_CORE ?>' });
	
$(function(){
  search_wrapper('#file_list .list-grid', 'input.search-box', 'li.list-item', 1);
});
    
function revision_modal_init(data_id){
	var revisionmodalObj = new handleModal({ controller : 'files', modal_id: 'modal_upload_file_revision', method: 'modal_file_revision', module: '<?php echo PROJECT_CORE ?>' });
	revisionmodalObj.loadView({ id : data_id });
}

function version_list_modal_init(data_id){
	var versionmodalObj = new handleModal({ controller : 'files', modal_id: 'modal_file_version_list', method: 'modal_version_list', module: '<?php echo PROJECT_CORE ?>' });
	versionmodalObj.loadView({ id : data_id });
}
</script>

