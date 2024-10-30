<?php
 
class jajadi_TrainingenWidget extends WP_Widget
{
	function jajadi_TrainingenWidget()
	{
		$widget_ops = array('classname' => 'jajadi_TrainingenWidget', 'description' => __('Displays all trainings of the training plugin.', 'jajadi-training') );
		$this->WP_Widget('jajadi_TrainingenWidget', 'JaJaDi Training', $widget_ops);
	}

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title:', 'jajadi-training') . ' <input class="widefat" id="' . $this->get_field_id('title') . '" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></label></p>';
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
 
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
	 
		echo $before_widget;
		/* translators: Default title of the widget */
		$title = empty($instance['title']) ? __('Trainings', 'jajadi-training') : apply_filters('widget_title', $instance['title']);
	 
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		$currentmonthi1 = date('m');
		for ($i1 = $currentmonthi1; $i1 <= $currentmonthi1+12; $i1++) {
			$i2					= $i1+1;
			$fromdate1			= mktime(0, 0, 0, $i1,   1,   date("Y"));
			$fromdate2			= mktime(0, 0, 0, $i2,   1,   date("Y"));
			$currentmonth		= date('m', $fromdate1);
			switch ($currentmonth) {
				case 1:
					$month = __('January', 'jajadi-training');
					break;
				case 2:
					$month = __('February', 'jajadi-training');
					break;
				case 3:
					$month = __('March', 'jajadi-training');
					break;
				case 4:
					$month = __('April', 'jajadi-training');
					break;
				case 5:
					$month = __('May', 'jajadi-training');
					break;
				case 6:
					$month = __('June', 'jajadi-training');
					break;
				case 7:
					$month = __('July', 'jajadi-training');
					break;
				case 8:
					$month = __('August', 'jajadi-training');
					break;
				case 9:
					$month = __('September', 'jajadi-training');
					break;
				case 10:
					$month = __('October', 'jajadi-training');
					break;
				case 11:
					$month = __('November', 'jajadi-training');
					break;
				case 12:
					$month = __('December', 'jajadi-training');
					break;
			}
			$currentmonthtitle	= $month . ' ' . date("Y", $fromdate1);

			query_posts( array(
				'post_type' => 'jajadi_training',
				'meta_query' => array(
					array(
						'key' => 'jajadi_training_selectdate_field',
						'value' => array( $fromdate1, $fromdate2 ),
						'compare' => 'BETWEEN',
						'type' => 'numeric',
					),
				)
			) );
			if (have_posts()) : 
				echo $currentmonthtitle."<ul>";
				while (have_posts()) : the_post(); 
					echo "<li><a href='".get_permalink()."'>".get_the_title();
					echo "</a></li>";	
				endwhile;
				echo "</ul><br />";
			endif; 
			wp_reset_query(); 
		}

		echo $after_widget;
	} 

}
add_action( 'widgets_init', create_function('', 'return register_widget("jajadi_TrainingenWidget");') );
?>