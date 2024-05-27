<html>
<head>
  <title>Department of Health, PTIS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>login.css">
<!-- NCOCAMPO::START -->
<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>custom.css">
<!-- NCOCAMPO::END -->
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>skins.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>component.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>materialize.css"  media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>flaticon.css">
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>jquery.jscrollpane.css" rel="stylesheet" media="all" />
  <script src="<?php echo base_url().PATH_JS ?>less.min.js" type="text/javascript"></script>
  <!-- JQUERY 2.1.1+ IS REQUIRED BY MATERIALIZE TO FUNCTION -->
  <script src="<?php echo base_url().PATH_JS ?>jquery-2.1.1.min.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>jquery-ui.min.js" type="text/javascript"></script>
  <!-- NCOCAMPO -->
  <style>
  #more {display: none;}
  </style>
  <!-- NCOCAMPO -->
</head>
<body class="default" onmouseup="hidePassword()" >
  <div id="wrapper">
    <div>
    <div class="panel">
    <!-- <div class="left-panel"> -->
    <!-- NCOCAMPO: added custom css :START -->
	  <div class="left-panel left-panel-custom">
    <!-- NCOCAMPO: added custom css :END -->
      <!-- <div id="welcome-text"><span>Welcome.</span> Please log in</div> -->
      <!-- NCOCAMPO: added custom css :START -->
	    <div id="welcome-text-custom"><span>Welcome.</span> Please log in</div>
		  <!-- NCOCAMPO: added custom css :END -->
		<form id="login_form">
		  <input type="hidden" id="base_url" value="<?php echo base_url() ?>"/>
		  <input type="hidden" id="home_page" value="<?php echo HOME_PAGE ?>"/>
		  <input class="none" type="password" />
		  
		  <div class="input-field">
		    <i class="flaticon-user153 prefix"></i>
		    <input id="icon_username" name="username" type="text" class="validate" style="width:290px;">
		    <label for="icon_username">Username</label>
		  </div>
		  <div class="input-field">
		    <i class="flaticon-lock72 prefix"></i>
		    <input id="icon_password" name="password" type="password" class="validate" style="width:290px;">
		    <label for="icon_password">Password</label>
        <i class="flaticon-invisible3" id="visibility-icon" onmousedown="showPassword()" onmouseup="hidePassword()" style="color:#9e9e9e;"></i>
		  </div>
		  <div class="input-field">
		    <button type="submit" id="submit_login" class="btn-large waves-effect waves-light" value="<?php echo BTN_LOGGING_IN ?>"><?php echo BTN_LOG_IN ?></button>
		  </div>
    <!-- NCOCAMPO: DATA PRIVACY & PRIVACY POLICY :START -mdified that let the user click the checkbox-->
    <div>
        <p>
        <!-- <input type="checkbox" id="agree" name="agree"  class="validate"   checked>  -->
        <input type="checkbox" id="agree" name="agree"  class="validate">        
         <label for="agree" style="text-align:justify;">
            <small style="line-height: normal;">I have read about the Data Privacy Statement as well as the DOH Privacy Policy and express my consent there too.<span id="dots">...</span><span id="more"> In the same manner, I hereby express my consent for DOH Personnel Administration Division, Administrative Service to collect, record, organize, update or modify, retrieve, consult, use, consolidate, block, erase or destruct my personal data as part of my information.<br>I hereby affirm my right to: (a) be informed; (b) object to processing; (c) access; (d) rectify, suspend or withdraw my personal data; (e) damages; and (f) data portability pursuant to the provisions of the Act and its corresponding Implementing Rules and Regulations.</span><a onclick="data_privacy(event)" id="myBtn">See more</a></small>
          </label>
        </p>         
       </div>
     <!-- NCOCAMPO: DATA PRIVACY & PRIVACY POLICY :END -->
	    </form>
	  </div>
    <!-- <div class="right-panel center-align"> -->
	  <!-- NCOCAMPO: added custom css :START -->
	  <div class="right-panel right-panel-custom center-align">
    <!-- NCOCAMPO: added custom css :END -->
	    <img src="<?php echo base_url().PATH_IMAGES ?>logo_login.png" class="logo"/>
		<div class="title">PTIS</div>
		<div class="sub-title">Department of Health</div>
	    
		<div id="panel-slider" class="owl-carousel" style="width:290px;">
		  <div class="item"><p>A Project of Department of Health</p></div>
		  <div class="item">For inquiries, please email <br> PTIS Helpdesk at <br> <a href="mailto:ptishelpdesk@doh.gov.ph">ptishelpdesk@doh.gov.ph	</a></div>
	    </div>
	  </div>
    </div><br><br>
	<div class="panel-footer">
	  <?php 
	  $visitor = get_setting(ACCOUNT, "account_creator");
	  
	  if($visitor == VISITOR){  ?>
		<a href="javascript:;" class="md-trigger btn-success hide" data-modal="modal_sign_up" id="sign_up" name="sign_up" onclick="modal_init('',1)">Don't have an account? / </a> 
	  <?php } ?>
	    <a href="javascript:;" class="md-trigger" data-modal="modal_forgot_password" id="forgot_password" name="forgot_password" onclick="modal_forgot_init('',1)">Forgot Password?</a>
      <button href="javascript:;" class="md-trigger none" data-modal="modal_change_password" id="change_password" name="change_password" onclick="modal_change_init('',1)">Change</button>
    </div>
  </div>
  
  <!-- NOTIFICATION SECTION -->
  <div class="notify success none"><div class="success"><h4><span>Success</span></h4><p></p></div></div>
  <div class="notify error none"><div class="error"><h4><span>Warning</span></h4><p></p></div></div>
  
  <?php if($visitor == VISITOR){  ?>
  <!-- MODAL -->
  <div id="modal_sign_up" class="md-modal lg md-effect-<?php echo MODAL_EFFECT ?>">
	<div class="md-content">
	  <a class="md-close icon">&times;</a>
	  <h3 class="md-header">&nbsp;</h3>
	  <div id="modal_sign_up_content" style="height: 480px"></div>
	</div>
  </div>
  <div class="md-overlay"></div>
  <!-- END MODAL -->
  <?php } ?>
  <div id="modal_forgot_password" class="md-modal md md-effect-<?php echo MODAL_EFFECT ?>">
  <div class="md-content">
    <a class="md-close icon" style="display: none">&times;</a>
    <h3 class="md-header">Forgot your password?</h3>
    <div id="modal_forgot_password_content" style="height: 250px"></div>
  </div>
  </div>

  <div id="modal_change_password" class="md-modal md md-effect-<?php echo MODAL_EFFECT ?>">
    <div class="md-content">
      <a class="md-close icon" style="display: none">&times;</a>
      <h3 class="md-header">Change password?</h3>
      <div id="modal_change_password_content" style="height: 500px"></div>
    </div>
  </div>

  <div class="md-overlay"></div>
  <!-- PLATFORM SCRIPT -->
  <script src="<?php echo base_url().PATH_JS ?>materialize.js"></script>
  <!-- END PLATFORM SCRIPT -->
  
  <script src="<?php echo base_url().PATH_JS ?>script.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>common.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>auth.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>parsley.min.js" type="text/javascript"></script>
  
  <!-- NIFTY MODAL SCRIPT -->
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>component.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>modalEffects.js"></script>
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>classie.js"></script>
  <!-- END NIFTY MODAL  SCRIPT -->
  
  <!-- OWL CAROUSEL SCRIPT -->
  <link href="<?php echo base_url().PATH_CSS ?>owl.carousel.css" rel="stylesheet" />
  <link href="<?php echo base_url().PATH_CSS ?>owl.theme.css" rel="stylesheet" />
  <script src="<?php echo base_url().PATH_JS ?>owl.carousel.js"></script>
  <!-- END OWL CAROUSEL SCRIPT -->
  
  <!-- MODAL SCRIPT -->
  <script src="<?php echo base_url().PATH_JS ?>classie.js" type="text/javascript"></script>
  <script src="<?php echo base_url().PATH_JS ?>modalEffects.js" type="text/javascript"></script>
  <!-- END MODAL SCRIPT -->
  
  <!-- JSCROLLPANE SCRIPT -->
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>jquery.mousewheel.js"></script>
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>jquery.jscrollpane.js"></script>
  <!-- END JSCROLLPANE SCRIPT -->
  
  <!-- POPMODAL SCRIPT -->
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>popModal.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="<?php echo base_url().PATH_JS ?>popModal.min.js"></script>
  <!-- END POPMODAL SCRIPT -->
  
  <script type="text/javascript">
    var modalObj = new handleModal({ controller : 'sign_up', modal_id: 'modal_sign_up' });
     var forgotmodalObj = new handleModal({ controller : 'forgot_password', modal_id: 'modal_forgot_password' });
	function modal_forgot_init(data_id){
    forgotmodalObj.loadView({ id : data_id });
  } 
  var changemodalObj = new handleModal({ controller : 'change_password', modal_id: 'modal_change_password' });
  function modal_change_init(data_id){
    changemodalObj.loadView({ id : data_id });
  } 
    $(function(){	  
      $("#panel-slider").owlCarousel({
		navigation : false,
		slideSpeed : 300,
		paginationSpeed : 400,
		singleItem : true
      });
	  
	  $("#cancel_role").on("click", function(){
		modalObj.closeModal();
	  });
    });
    // $('#change_password').click(function(){
    //   console.log('1');
    //   modal_change_init();
    // });
  </script>

<!-- NCOCAMPO: ADDED SCRIPT :START -->
<script>
function data_privacy(event) {
  event.preventDefault();
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "See more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = " See less"; 
    moreText.style.display = "inline";
  }
}
</script>
<script>
function showPassword() {
  var x = document.getElementById("icon_password");
  document.getElementById("visibility-icon").classList.remove('flaticon-invisible3');
  document.getElementById("visibility-icon").classList.add('flaticon-visible9');
  x.type = "text";
}
function hidePassword() {
  var x = document.getElementById("icon_password");
  document.getElementById("visibility-icon").classList.remove('flaticon-visible9');
  document.getElementById("visibility-icon").classList.add('flaticon-invisible3');
  x.type = "password";
}
</script>
<!-- //NCOCAMPO:ADDED SCRIPT:END -->
</body>
</html>