<?php
// create custom plugin settings menu
add_action('admin_menu', 'jajadi_training_settings_menu');

function jajadi_training_settings_menu() {

	//create new top-level menu
	add_options_page('JaJaDi Training and Courses', 'Training and Courses', 'manage_options', __FILE__, 'jajadi_training_settings_page');

	//call register settings function
	add_action( 'admin_init', 'jajadi_register_training_settings' );
}


function jajadi_register_training_settings() {
	//register our settings
	register_setting( 'jajadi-training-settings', 'usejajadicourse' );
	register_setting( 'jajadi-training-settings', 'usejajadiforms' );
	register_setting( 'jajadi-training-settings', 'usejajadicalendars' );
	register_setting( 'jajadi-training-settings', 'usejajadielearning' );
}

function jajadi_admin_tabs( $current = 'homepage' ) {
    $tabs = array( 'general' => __('General Settings', 'jajadi-training'));
	if(get_option( 'usejajadiwidget' ) == 'on'){ $tabs['widget'] = __('Widget', 'jajadi-training'); }
	if(get_option( 'usejajadiforms' ) == 'on'){ $tabs['forms'] = __('Forms', 'jajadi-training'); }
	if(get_option( 'usejajadicalendars' ) == 'on'){ $tabs['calendar'] = __('Calendar', 'jajadi-training'); }
	if(get_option( 'usejajadielearning' ) == 'on'){ $tabs['e-learning'] = __('e-Learning', 'jajadi-training'); }
	$tabs['about'] = __('About', 'jajadi-training');
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=jajadi-training/jajadi-training-settings.php&tab=$tab'>$name</a>";

    }
}

function jajadi_training_settings_page() {
	?>
	<div id="icon-options-general" class="icon32"></div><h2><?php echo __('JaJaDi Training & Courses Settings', 'jajadi-training'); ?></h2>
	<h2 class="nav-tab-wrapper">
	<div class="wrap">
	<?php
	if ( isset ( $_GET['tab'] ) ){
		jajadi_admin_tabs($_GET['tab']);
		$tab = $_GET['tab'];
	}
	else{
		jajadi_admin_tabs('general');
		$tab = 'general';
	}
	?>
	</h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'jajadi-training-settings' ); ?>
		<?php do_settings_sections( 'jajadi-training-settings' ); ?>
		<?php
		if($tab == 'general'){
			?>
			<table class="form-table">
				<tr valign="top">
				<th scope="row"><?php echo __('Use Courses', 'jajadi-training'); ?>: </th>
				<td><input type="checkbox" name="usejajadicourse" <?php if( get_option( 'usejajadicourse' ) == 'on' ){ echo 'checked'; } ?> /></td>
				</tr>
				 
				<!-- <tr valign="top">
				<th scope="row"><?php echo __('Use Forms', 'jajadi-training'); ?>: </th>
				<td><input type="checkbox" name="usejajadiforms" <?php if( get_option( 'usejajadiforms' ) == 'on' ){ echo 'checked'; } ?> /></td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php echo __('Use Calendar', 'jajadi-training'); ?>: </th>
				<td><input type="checkbox" name="usejajadicalendars" <?php if( get_option( 'usejajadicalendars' ) == 'on' ){ echo 'checked'; } ?>  /></td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php echo __('Use e-Learning', 'jajadi-training'); ?>: </th>
				<td><input type="checkbox" name="usejajadielearning" <?php if( get_option( 'usejajadielearning' ) == 'on' ){ echo 'checked'; } ?>  /></td>
				</tr> -->
			</table>
		<?php
		}
		elseif($tab == 'widget'){
			include( plugin_dir_path( __FILE__ ) . 'jajadi-training-widget-settings.php');
		}
		elseif($tab == 'forms'){
			include( plugin_dir_path( __FILE__ ) . 'jajadi-training-forms.php');
		}
		elseif($tab == 'calendar'){
			include( plugin_dir_path( __FILE__ ) . 'jajadi-training-calender.php');
		}
		elseif($tab == 'e-learning'){
			include( plugin_dir_path( __FILE__ ) . 'jajadi-training-e-learning.php');
		}
		elseif($tab == 'about'){
			include( plugin_dir_path( __FILE__ ) . 'jajadi-training-about.php');
		}
		?>
		
		<?php 
		if($tab != 'about'){
			submit_button(); 
		}
			?>

	</form>
	<?php 
		if($tab == 'about'){
			?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
		">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
		</form>
	<?php
		}
		?>
	</div>
	<?php
}
 
 ?>