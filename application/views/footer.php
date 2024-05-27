<?php 
if(!EMPTY($load_css)){
	foreach($load_css as $css):
		echo '<link href="'. base_url() . PATH_CSS . $css .'.css" rel="stylesheet" type="text/css">';
	endforeach; 
}

if(!EMPTY($load_js)){
	foreach($load_js as $js):
		echo '<script src="'. base_url() . PATH_JS . $js .'.js" type="text/javascript"></script>';
	endforeach; 
}

$pass_data 	= array();
$resources  = array();

if( ISSET( $single ) )
{
	$resources['single'] 	= $single;
}

if( ISSET( $multiple ) )
{
	$resources['multiple'] 	= $multiple;
}

if( ISSET( $upload ) )
{
	$resources['upload'] 	= $upload;
}

if( ISSET( $datatable ) )
{
	$resources['datatable'] = $datatable;
}

if( ISSET( $load_js ) )
{
	$resources['load_js'] 	= $load_js;
}

if( ISSET( $loaded_init ) )
{
	$resources['loaded_init'] = $loaded_init;
}

if( ISSET( $load_modal ) )
{
	$resources['load_modal'] 	= $load_modal;
}

if( ISSET( $load_delete ) )
{
	$resources['load_delete'] 	= $load_delete;
}

$pass_data['resources']		= $resources;
?>

<?php 
	$this->view( 'modal_initialization', $pass_data );
?>

<?php 
	$this->view( 'initializations', $pass_data );
?>


