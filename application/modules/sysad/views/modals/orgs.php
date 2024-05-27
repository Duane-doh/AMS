<?php 
$org_id = "";
$short_name = "";
$name = "";
$website = "";
$email = "";
$phone = "";
$fax = "";
$header = "Create a New Agency";
if(ISSET($org)){
	$org_id = $org["org_id"];
	$short_name = (!EMPTY($org["short_name"]))? $org["short_name"] : "";
	$name = (!EMPTY($org["name"]))? $org["name"] : "";
	$website = (!EMPTY($org["website"]))? $org["website"] : "";
	$email = (!EMPTY($org["email"]))? $org["email"] : "";
	$phone = (!EMPTY($org["phone"]))? $org["phone"] : "";
	$fax = (!EMPTY($org["fax"]))? $org["fax"] : "";
	$header = "Update Agency";
}

$salt = gen_salt();
$token = in_salt($org_id, $salt);
?>
<div class="md-header"><?php echo $header ?></div>		  
<form id="org_form">
	<input type="hidden" name="org_id" value="<?php echo $org_id ?>">
	<input type="hidden" name="salt" value="<?php echo $salt ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">
  	<div class="p-lg area">
		<div class="form-group col-8 p-r-sm">
			<label class="required">Name</label>
			<div class="sub-label">Enter name of the agency.</div>
			<div><input type="text" name="name" id="name" data-parsley-required="true" data-parsley-maxlength="255" data-parsley-trigger="keyup" value="<?php echo $name ?>"/></div>
		</div>
		<div class="form-group col-4 p-l-sm">
			<label class="required">Acronym</label>
			<div class="sub-label">Enter a short name of the agency.</div>
			<div><input type="text" name="short_name" id="short_name" data-parsley-required="true" data-parsley-maxlength="50" data-parsley-trigger="keyup" value="<?php echo $short_name ?>"/></div>
		</div>
		<div class="form-group col-8 p-r-sm">
			<label>Email</label>
			<div class="sub-label">Enter a valid email of the agency.</div>
			<input type="text" name="email" id="email" data-parsley-maxlength="100" data-parsley-type="email" data-parsley-trigger="keyup" value="<?php echo $email ?>"/>
		</div>
		<div class="form-group col-4 p-l-sm">
			<label>Phone</label>
			<div class="sub-label">Enter the phone number of the agency.</div>
			<input type="text" name="phone" id="phone" data-parsley-maxlength="100" data-parsley-trigger="keyup" value="<?php echo $phone ?>"/>
		</div>
		<div class="clearfix"></div>
		<div class="form-group col-8 p-r-sm">
			<label>Website</label>
			<div class="sub-label">Enter the website of the agency.</div>
			<input type="text" name="website" id="website" data-parsley-maxlength="100" data-parsley-type="url" data-parsley-trigger="keyup" value="<?php echo $website ?>"/>
		</div>
		<div class="form-group col-4 p-l-sm">
			<label>Fax</label>
			<div class="sub-label">Enter the fax number of the agency.</div>
			<input type="text" name="fax" id="fax" data-parsley-maxlength="100" data-parsley-trigger="keyup" value="<?php echo $fax ?>"/>
		</div>
		<div class="clearfix"></div>			  
	</div>
	<div class="md-footer p text-right">
	    <div class="p-r-n">
	      <a onclick="close_modal('modal_orgs')" title="Cancel" class="action-cancel"><?php echo BTN_CANCEL ?></a> 
	      <button type="submit" class="btn-default green" id="org_btn" name="org_btn" value="<?php echo BTN_SAVING ?>"><?php echo BTN_SAVE ?></button>
	    </div>
	</div>
</form>

<script type="text/javascript">
$(function(){
	
var $base_url = "<?php echo base_url() ?>";
	$module = "<?php echo PROJECT_CORE ?>";

	$('#org_form').parsley();
	
	$('#org_form').submit(function(e) { 
        e.preventDefault();
        button_loader('org_btn', 1);
        if ( $(this).parsley().isValid() ) {
			$.post($base_url + $module + "/orgs/process/", $(this).serialize(), function(result) {

    			if(result.flag == 0){
    				alert_msg("<?php echo ERROR ?>", result.msg);
    				button_loader('org_btn', 0);
    			} else {
    				load_index('<?php echo $this->session->userdata("tab_id") ?>', '<?php echo $this->session->userdata("tab_path") ?>');
    				alert_msg("<?php echo SUCCESS ?>", result.msg);
    			}
    		}, 'json');       
        }
    });
    
});
</script>