<?php
/************************************************************************/
/*	Hoe een Custom Metabox te bouwen:									*/
/*	bron: http://codex.wordpress.org/Function_Reference/add_meta_box	*/
/************************************************************************/

/************************************************************************/
/*	Define the custom box												*/
/************************************************************************/
add_action( 'add_meta_boxes', 'myplugin_add_custom_box' );

/************************************************************************/
/*	Do something with the data entered									*/
/************************************************************************/
add_action( 'save_post', 'myplugin_save_postdata' );
/************************************************************************/
/*	Adds a box to the main column on the Post and Page edit screens		*/
/************************************************************************/

function myplugin_add_custom_box() {
	add_meta_box( 
		'jajadi_training_selectdate',
		__( 'Select date', 'jajadi-training' ),
		'jajadi_training_selectdate',
		'jajadi_training',
		'side'
	);
	if( get_option( 'usejajadicourse' ) == 'on' ){
		add_meta_box( 
			'jajadi_training_selectcourse',
			__( 'Select courses', 'jajadi-training' ),
			'jajadi_training_selectcourse',
			'jajadi_training',
			'side'
		);
	}
	add_meta_box( 
		'jajadi_course_summary',
		__( 'Summary', 'jajadi-training' ),
		'jajadi_course_summary',
		'jajadi_course',
		'advanced'
	);
}

/************************************************************************/
/*	Prints the box content												*/
/************************************************************************/


function jajadi_training_selectdate( $post ) {

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'jajadi_noncename' );
	
	// Get Meta Values
	$meta_values = get_post_meta( $post->ID, 'jajadi_training_selectdate_field' );
	
	$datumtest = date(get_option('date_format'), $meta_values[0]);

	
	echo '<div class="jajadi-selectdate">';
	/*
	echo '<span>
		<a id="post_tag-check-num-0" class="ntdelbutton">X</a> '.$datumtest.'<br />
	</span>';
	
	echo '</div>
	<div class="misc-pub-section jajadiadddate">';
	*/
	echo '<input type="text" id="jajadi_training_date" name="jajadi_training_date" value="' . date('d-m-Y', $meta_values[0]) . '"/>

	<script type="text/javascript">

	jQuery(document).ready(function() {
		jQuery(\'#jajadi_training_date\').datepicker({
			dateFormat : \'dd-mm-yy\'
		});
	});

	</script>';
	/*
	echo '<input type="submit" name="addtrainingdate" id="newtrainingdate-submit" class="button" value="'. __('Add', 'jajadi-training'). '" /> 
	</div>';
	*/
	echo '<div style="clear: left;"></div><input type="hidden" value="1" /></div>';
}

function jajadi_training_selectcourse( $post ) {
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'jajadi_noncename' );
	
	//selected Categories
	$selecttaxonomy = get_terms( 'course_category' );
	$meta_values = get_post_meta( $post->ID, 'jajadi_training' );
	$meta_values_tax = $meta_values[0]['jajadi_selectcoursetax_field'];
	$meta_values_post = $meta_values[0]['jajadi_selectcourse_field'];
	/* Translators: Select Courses */
	echo '<div><p><b>'. __('By category:', 'jajadi-training') . '</b></p><div style="float: left; margin: 0 10px 0 10px;">';
	foreach($selecttaxonomy as $key => $value){
		$checkboxselected_tax = '';
		if($meta_values_tax != ''){
			foreach ($meta_values_tax as $value2) {
				if($value->term_taxonomy_id == $value2){
					$checkboxselected_tax = '  checked="yes"';
				}
			}
		}
		echo '<div><input type="checkbox" name="coursecategory[]" id="'.$value->term_taxonomy_id.'" value="'.$value->term_taxonomy_id.'" ' . $checkboxselected_tax . ' /> <label for="'.$value->term_taxonomy_id.'">';
		echo $value->name.'</label></div>';
	}
	echo '</div>
	<div style="clear: left;"></div>';

	//selected courses
	$args = array( 'post_type' => 'jajadi_course', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page'=>'-1' );
	$loop = new WP_Query( $args );
	$countpostbycolum = $loop->post_count/2;
	$countpostbycolum1 = ceil($countpostbycolum);
	$countpostbycolum2 = intval($countpostbycolum1);
	/* Translators: Select Courses */
	echo '<p><b>'. __('By courses:', 'jajadi-training') .'</b></p><div style="float: left; margin: 0 10px 0 10px;">';
	$i=1;
	$checkboxselected = '';
	while ( $loop->have_posts() ) : $loop->the_post();
		if($meta_values_post != ''){
			foreach ($meta_values_post as $value) {
				if($loop->post->ID == $value){
					$checkboxselected = '  checked="yes"';
				}
			}
		}
		echo '<div><input type="checkbox" name="courses[]" id="'.$loop->post->ID.'" value="'.$loop->post->ID.'"'.$checkboxselected.' /> <label for="'.$loop->post->ID.'">';
		the_title();
		echo '</label></div>';
		if(is_int($i/$countpostbycolum2)){
			echo '</div><div style="float: left; margin: 0 10px 0 10px;">';
		}
		$checkboxselected = '';
		$i++;
	endwhile;

	echo '</div>
	<div style="clear: left;"></div><input type="hidden" value="1" /></div>';
}


function jajadi_course_summary( $post ) {

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'jajadi_noncename' );
	
	// Get Meta Values
	/*$custom = get_post_custom($post->ID);
	$jajadi_course_summary_field = $custom['jajadi_course_summary_field'][0];
	if(!$jajadi_course_summary_field) { $jajadi_course_summary_field = 'data'; }
*/
	$meta_values = get_post_meta( $post->ID, 'jajadi_training' );
	$jajadi_course_summary_field = $meta_values[0]['jajadi_course_summary_field'];
	echo '<div><textarea name="jajadi_course_summary" cols="100" rows="3">'.$jajadi_course_summary_field.'</textarea>
	<input type="hidden" value="2" /></div>';
}

/************************************************************************/
/*	When the post is saved, saves our custom data						*/
/************************************************************************/
function myplugin_save_postdata( $post_id ) {
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times	  
	if ( !wp_verify_nonce( $_POST['jajadi_noncename'], plugin_basename( __FILE__ ) ) )
		return;

	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ) )
		return;

	// OK, we're authenticated: we need to find and save the data

	
	$jajadi_post_meta	= array();
	if(isset($_POST['courses'])){
		$jajadi_post_meta['jajadi_selectcourse_field']		= $_POST['courses'];
		}
	if(isset($_POST['coursecategory'])){
		$jajadi_post_meta['jajadi_selectcoursetax_field']	= $_POST['coursecategory'];
		}
	if(isset($_POST['jajadi_course_summary'])){
		$jajadi_post_meta['jajadi_course_summary_field']	= $_POST['jajadi_course_summary'];
		}
	if(isset($_POST['jajadi_training_date'])){
		$jajadi_training_selectdate_field = strtotime($_POST['jajadi_training_date']);
		//$jajadi_post_meta['jajadi_selectdate_field']		= $jajadi_training_selectdate_field;
		update_post_meta($post_id, "jajadi_training_selectdate_field", $jajadi_training_selectdate_field);
	}
	

	update_post_meta($post_id, "jajadi_training", $jajadi_post_meta);
}
?>