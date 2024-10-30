<?php

function jajadi_load_textdomain() {
	load_plugin_textdomain( 'jajadi-training', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function jajadi_admin_register_head() {
	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css';
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}

/************************************************************************************************/
/*	Date Picker																					*/
/************************************************************************************************/
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

/************************************************************************************************/
/*	Register the post type - Training															*/
/************************************************************************************************/
function jajadi_training_register() {
	$labels = array(
		'name' => _x('Training', 'post type general name', 'jajadi-training'),
		'singular_name' => _x('Training', 'post type singular name', 'jajadi-training'),
		'add_new' => _x('Add New', 'Training', 'jajadi-training'),
		'add_new_item' => __('Add New Training', 'jajadi-training'),
		'edit_item' => __('Edit Training', 'jajadi-training'),
		'new_item' => __('New Training', 'jajadi-training'),
		'view_item' => __('View Training', 'jajadi-training'),
		'search_items' => __('Search Training', 'jajadi-training'),
		'not_found' =>  __('Nothing found', 'jajadi-training'),
		'not_found_in_trash' => __('Nothing found in Trash', 'jajadi-training'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'menu_icon' => plugins_url( 'images/icon.png' , __FILE__ ),
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor'),
		'rewrite' => array('slug' => 'training','with_front' => false )
	); 
	register_post_type( 'jajadi_training' , $args );
	flush_rewrite_rules();
}

function my_rewrite_flush() {
	// First, we "add" the custom post type via the above written function.
	// Note: "add" is written with quotes, as CPTs don't get added to the DB,
	// They are only referenced in the post_type column with a post entry, 
	// when you add a post of this CPT.
	jajadi_training_register();
	if( get_option( 'usejajadicourse' ) == 'on' ){
		jajadi_course_register();
	}

	// ATTENTION: This is *only* done during plugin activation hook!
	// You should *NEVER EVER* do this on every page load!!
	flush_rewrite_rules();
}

// add filter to ensure the text Training is displayed when user updates a jajadi_training 
function jajadi_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['jajadi_training'] = array(
	0 => '', // Unused. Messages start at index 1.
	1 => sprintf( __('Training updated. <a href="%s">View Training</a>', 'jajadi-training'), esc_url( get_permalink($post_ID) ) ),
	2 => __('Custom field updated.', 'jajadi-training'),
	3 => __('Custom field deleted.', 'jajadi-training'),
	4 => __('Training updated.', 'jajadi-training'),
	/* translators: %s: date and time of the revision */
	5 => isset($_GET['revision']) ? sprintf( __('Training restored to revision from %s', 'jajadi-training'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, 
	6 => sprintf( __('Training published. <a href="%s">View Training</a>', 'jajadi-training'), esc_url( get_permalink($post_ID) ) ),
	7 => __('Training saved.', 'jajadi-training'),
	8 => sprintf( __('Training submitted. <a target="_blank" href="%s">Preview Training</a>', 'jajadi-training'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	9 => sprintf( __('Training scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Training</a>', 'jajadi-training'),
		/* translators: Publish box date format, see http://php.net/date */
		date_i18n( __( 'M j, Y @ G:i' , 'jajadi-training'), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ), 
	10 => sprintf( __('Training draft updated. <a target="_blank" href="%s">Preview Training</a>', 'jajadi-training'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

/************************************************************************************************/
/*	Register the post type - Course																*/
/************************************************************************************************/
function jajadi_course_register() {
	$labels = array(
		'name' => _x('Courses', 'post type general name', 'jajadi-training'),
		'singular_name' => _x('Course', 'post type singular name', 'jajadi-training'),
		'add_new' => _x('Add New', 'Course', 'jajadi-training'),
		'add_new_item' => __('Add New Course', 'jajadi-training'),
		'edit_item' => __('Edit Course', 'jajadi-training'),
		'new_item' => __('New Course', 'jajadi-training'),
		'view_item' => __('View Course', 'jajadi-training'),
		'search_items' => __('Search Courses', 'jajadi-training'),
		'not_found' =>  __('Nothing found', 'jajadi-training'),
		'not_found_in_trash' => __('Nothing found in Trash', 'jajadi-training'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		//'show_in_menu' => 'edit.php?post_type=jajadi_training',
		'menu_icon' => plugins_url( 'images/icon.png' , __FILE__ ),
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','taxonomy'),
		//'taxonomies' => true,
		'rewrite' => array('slug' => 'course','with_front' => false )
	); 
	register_post_type( 'jajadi_course' , $args );
	flush_rewrite_rules();
}

function jajadi_updated_messages_course( $messages ) {
	global $post, $post_ID;
	$messages['jajadi_course'] = array(
	0 => '', // Unused. Messages start at index 1.
	1 => sprintf( __('Course updated. <a href="%s">View Course</a>', 'jajadi-training'), esc_url( get_permalink($post_ID) ) ),
	2 => __('Custom field updated.', 'jajadi-training'),
	3 => __('Custom field deleted.', 'jajadi-training'),
	4 => __('Course updated.', 'jajadi-training'),
	/* translators: %s: date and time of the revision */
	5 => isset($_GET['revision']) ? sprintf( __('Course restored to revision from %s', 'jajadi-training'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, 
	6 => sprintf( __('Course published. <a href="%s">View Course</a>', 'jajadi-training'), esc_url( get_permalink($post_ID) ) ),
	7 => __('Course saved.', 'jajadi-training'),
	8 => sprintf( __('Course submitted. <a target="_blank" href="%s">Preview Course</a>', 'jajadi-training'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	9 => sprintf( __('Course scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Course</a>', 'jajadi-training'),
		/* translators: Publish box date format, see http://php.net/date */
		date_i18n( __( 'M j, Y @ G:i' , 'jajadi-training'), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ), 
	10 => sprintf( __('Course draft updated. <a target="_blank" href="%s">Preview Course</a>', 'jajadi-training'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

/************************************************************************************************/
/* Registers custom taxonomies for Course, so blog post categories/tags don't mix with these.	*/
/************************************************************************************************/
function jajadi_register_taxonomies() {
	// Course Categories
	$labels = array(
				'name' =>_x('Course Category', 'taxonomy single name', 'jajadi-training'),
				'singular_name' => _x('Course Category', 'taxonomy single name', 'jajadi-training'),
				'search_items' =>  _x( 'Search Course Categories', 'taxonomy label', 'jajadi-training'),
				'popular_items' => _x( 'Popular Course Categories', 'taxonomy label', 'jajadi-training'),
				'all_items' => _x( 'All Course Categories', 'taxonomy label', 'jajadi-training'),
				'edit_item' => _x( 'Edit Course Category', 'taxonomy label', 'jajadi-training'),
				'update_item' => _x( 'Update Course Category', 'taxonomy label', 'jajadi-training'),
				'add_new_item' => _x( 'Add New Course Category', 'taxonomy label', 'jajadi-training'),
				'new_item_name' => _x( 'New Course Category Name', 'taxonomy label', 'jajadi-training'),
				'add_or_remove_items' => _x( 'Add or Remove Course Categories', 'taxonomy label', 'jajadi-training'),
				'choose_from_most_used' => _x('Choose from the most used course categories', 'taxonomy label', 'jajadi-training'),
				'menu_name' => _x('Course Categories', 'taxonomy menu name', 'jajadi-training'),
			); 

	register_taxonomy('course_category', 'jajadi_course', array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'course-category' ),
	));
	
	// Course Tags
	$labels = array(
				'name' =>_x('Course Tag', 'taxonomy single name', 'jajadi-training'),
				'singular_name' => _x('Course Tag', 'taxonomy single name', 'jajadi-training'),
				'search_items' =>  _x( 'Search Course Tags', 'taxonomy label', 'jajadi-training'),
				'popular_items' => _x( 'Popular Course Tags', 'taxonomy label', 'jajadi-training'),
				'all_items' => _x( 'All Course Tags', 'taxonomy label', 'jajadi-training'),
				'edit_item' => _x( 'Edit Course Tag', 'taxonomy label', 'jajadi-training'),
				'update_item' => _x( 'Update Course Tag', 'taxonomy label', 'jajadi-training'),
				'add_new_item' => _x( 'Add New Course Tag', 'taxonomy label', 'jajadi-training'),
				'new_item_name' => _x( 'New Course Tag Name', 'taxonomy label', 'jajadi-training'),
				'add_or_remove_items' => _x( 'Add or Remove Course Tags', 'taxonomy label', 'jajadi-training'),
				'choose_from_most_used' => _x('Choose from the most used course tags', 'taxonomy label', 'jajadi-training'),
				'menu_name' => _x('Course Tags', 'taxonomy menu name', 'jajadi-training'),
			); 

	register_taxonomy('course_tag', 'jajadi_course', array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'course-tag' ),
	));
	
}

/************************************************************************************************/
/*	Display contextual help																		*/
/************************************************************************************************/

function jajadi_contextual_help() {
	$screen = get_current_screen();
	if ( 'jajadi_training' == $screen->id ) { //Training (new & Edit)
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help1',
			'title'   => __( 'Edit Training', 'jajadi-training' ),
			'content' => __( 'This screen you can edit your training. You can also attach courses and set a date of the training', 'jajadi-training' )
		) );
	}
	elseif ( 'edit-jajadi_training' == $screen->id ) { //Training (overview)
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help2',
			'title'   => __( 'Training Overview', 'jajadi-training' ),
			'content' => __( 'This screen provides access to all of your trainings.', 'jajadi-training' )
		) );
	}
	elseif ( 'jajadi_course' == $screen->id ) { //Course (new & Edit)
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help3',
			'title'   => __( 'Edit Course', 'jajadi-training' ),
			'content' => __( 'This screen you can edit your course. You can also write a summary, this summary would show by the course on the frontpage.', 'jajadi-training' )
		) );
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help4',
			'title'   => __( 'Course categories', 'jajadi-training' ),
			'content' => __( 'You can use categories to define sections of your site and group related courses.', 'jajadi-training' )
		) );
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help5',
			'title'   => __( 'Course tags', 'jajadi-training' ),
			'content' => __( 'You can assign keywords to your courses using tags. Unlike categories, tags have no hierarchy, meaning there’s no relationship from one tag to another.', 'jajadi-training' )
		) );
	}
	elseif ( 'edit-jajadi_course' == $screen->id ) { //Course (overview)
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help6',
			'title'   => __( 'Course Overview', 'jajadi-training' ),
			'content' => __( 'This screen provides access to all of your courses.', 'jajadi-training' )
		) );
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help7',
			'title'   => __( 'Course categories', 'jajadi-training' ),
			'content' => __( 'You can use categories to define sections of your site and group related courses.', 'jajadi-training' )
		) );
		$screen->add_help_tab( array(
			'id'      => 'jajadi-training-help8',
			'title'   => __( 'Course tags', 'jajadi-training' ),
			'content' => __( 'You can assign keywords to your courses using tags. Unlike categories, tags have no hierarchy, meaning there’s no relationship from one tag to another.', 'jajadi-training' )
		) );
	}


}


	/*if ( 'jajadi_training' == $screen->id ) { //Training (new & Edit)
	elseif ( 'edit-jajadi_training' == $screen->id ) { //Training (overview)
	elseif ( 'jajadi_course' == $screen->id ) { //Course (new & Edit)
	elseif ( 'edit-jajadi_course' == $screen->id ) { //Course (overview)*/

// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN  
function jajadi_columns_head_training_category($columns) {  
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title', 'jajadi-training'),
        'training_date' => __('Trainingdate', 'jajadi-training'),
        'date' =>__( 'Date Author', 'jajadi-training')
    );
}  
function jajadi_columns_content_training_category($column_name, $post_id) {  
	switch ( $column_name ) {
		/* If displaying the 'trainingdate' column. */
		case 'training_date':

			/* Get the post meta. */
			$format			= get_option('date_format');
			$trainingdate	= get_post_meta( $post_id, 'jajadi_training_selectdate_field', true );

			/* If no duration is found, output a default message. */
			if ( empty( $trainingdate ) )
				echo __( 'Unknown', 'jajadi-training' );

			/* If there is a duration, append 'minutes' to the text string. */
			else
				$trainingdate	= date( $format , $trainingdate); 
				echo $trainingdate;

			break;
	}  
	
}

// Make Columns sortable
function jajadi_sortable_columns( $columns ) {

	$columns['training_date'] = 'training_date';

	return $columns;
}  

function jajadi_edit_training_load() {
	add_filter( 'request', 'jajadi_sort_trainingdate' );
}

/* Sorts the jajadi_training-date. */
function jajadi_sort_trainingdate( $vars ) {

	/* Check if we're viewing the 'jajadi_training' post type. */
	if ( isset( $vars['post_type'] ) && 'jajadi_training' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'trainingdate'. */
		if ( isset( $vars['orderby'] ) && 'training_date' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(  'post_type' => 'jajadi_training',
					'meta_key'	=> 'jajadi_training_selectdate_field',
					'orderby'	=> 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN  
function jajadi_columns_head_course_category($columns) {  
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title', 'jajadi-training'),
        'course_category' => __('Course Categories', 'jajadi-training'),
        'course_tag' => __('Course Tags', 'jajadi-training'),
        'date' =>__( 'Date Author', 'jajadi-training')
    );
}  
function jajadi_columns_content_course_category($column_name, $post_id) {  
	switch ( $column_name ) {
		case 'course_category':
			$terms = get_the_term_list( $post_id , 'course_category' , '' , ', ' , '' );
			if ( is_string( $terms ) ) {
				echo $terms;
			} else {
				echo __('No Course Category', 'jajadi-training');
			}
			break;
		case 'course_tag':
			$terms = get_the_term_list( $post_id , 'course_tag' , '' , ', ' , '' );
			if ( is_string( $terms ) ) {
				echo $terms;
			} else {
				echo __('No Course Tags', 'jajadi-training');
			}
			break;
	}  
	
}

/************************************************************************************************/
/*	returns the content of $GLOBALS['post']														*/
/************************************************************************************************/
function jajadi_showcourses($content) {
	if ( get_post_type() == 'jajadi_training' ){
	
		$course = '';
		$meta_values = get_post_meta(get_the_ID(), 'jajadi_training');
		$getpostid = jajadi_get_post_ids($meta_values[0]['jajadi_selectcoursetax_field'], 'course_category');
		if($meta_values[0]['jajadi_selectcourse_field'] == ''){ $meta_values[0]['jajadi_selectcourse_field'] = array(); }
		$allpostid = array_merge($meta_values[0]['jajadi_selectcourse_field'], $getpostid);
		$allpostidunique = array_unique($allpostid);

		foreach($allpostidunique as $post_id){
			$coursetitle	= get_the_title($post_id);
			$coursesummary	= get_post_meta($post_id, 'jajadi_training');
			$courselink		= get_permalink($post_id);
			$course .= '<p><b><a href="'.$courselink.'">' . $coursetitle . '</a></b><br /><img src="' . plugins_url( 'images/i_name.gif' , __FILE__ ). '" /> ' . __('course', 'jajadi-training') . '<br />' . $coursesummary[0]['jajadi_course_summary_field'].'</p>';
		}
		$content = $content . $course;
	}
	return $content;
}

/************************************************************************************************/
/*	Add settings link on plugin page															*/
/************************************************************************************************/
function jajadi_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=jajadi-training/jajadi-training-settings.php">' . __('Settings', 'jajadi-training') . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
function jajadi_get_post_ids($cat, $taxonomy='course_category')
{
	return get_posts(array(
		'post_type' => 'jajadi_course',
		'numberposts'   => -1, // get all posts.
		'tax_query'     => array(
			array(
				'taxonomy'  => $taxonomy,
				'field'     => 'id',
				'terms'     => is_array($cat) ? $cat : array($cat),
			),
		),
		'fields'        => 'ids', // only get post IDs.
	));
}
?>