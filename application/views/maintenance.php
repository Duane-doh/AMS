<html>
<head>
  <title>Department of Health, PTIS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>login.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>skins.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>component.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>materialize.css"  media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>flaticon.css">
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>jquery.jscrollpane.css" rel="stylesheet" media="all" />
  <script src="<?php echo base_url().PATH_JS ?>less.min.js" type="text/javascript"></script>
  
  <!-- JQUERY 2.1.1+ IS REQUIRED BY MATERIALIZE TO FUNCTION -->
  <script src="<?php echo base_url().PATH_JS ?>jquery-2.1.1.min.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>jquery-ui.min.js" type="text/javascript"></script>
</head>
<body class="default">
<style>
body {
	text-align: center; padding: 150px;
}
h1 {
	font-size: 50px;
}
body {
	font: 20px Helvetica, sans-serif;
	color: #fff;
}
article {
	display: block;
	text-align: left;
	width: 650px;
	margin: 0 auto;
}
  a {
	color: #dc8100;
	text-decoration: none;
}
  a:hover {
	color: #333;
	text-decoration: none;
}
</style>

<img src="https://cdn3.iconfinder.com/data/icons/forall/1062/cono-256.png" height="150" width="150">
<article>
    <h1>We&rsquo;ll be back soon!</h1>
    <div>
        <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. <!--If you need to you can always <a href="javascript:;" class="md-trigger" data-modal="modal_contact_us" id="contact_us" onclick="modal_contact_init('',1)">contact us</a>, otherwise we&rsquo;ll be back online shortly!--></p>
        <p class="pull-right" style="margin-top: 20%;">&mdash; Administrative Services</p>
    </div>
</article>
<div id="modal_contact_us" class="md-modal md md-effect-<?php echo MODAL_EFFECT ?>">
	<div class="md-content">
		<h3 class="md-header">Contact Us</h3>
		<div id="modal_forgot_password_content" style="height: 250px"></div>
		<h6><a class="md-close pull-right icon" >X</a></h6>
	</div>
</div>

<!-- NOTIFICATION SECTION -->
<div class="notify success none"><div class="success"><h4><span>Success</span></h4><p></p></div></div>
<div class="notify error none"><div class="error"><h4><span>Warning</span></h4><p></p></div></div>

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
var forgotmodalObj = new handleModal({ controller : 'contact_us', modal_id: 'modal_contact_us' });
function modal_contact_init(data_id){
	forgotmodalObj.loadView({ id : data_id });
}
</script>
</body>
</html>