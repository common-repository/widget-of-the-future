<?php
/*
Plugin Name: Future Widget
Plugin URI: 
Description: This plugin adds a widget to list future posts in your sidebar. You can choose how many posts to display.
Author: Shinichi Nishikawa
Version: 0.7
Author URI: http://en.nskw-style.com/
*/

add_action(
	'widgets_init',
	create_function('', 'return register_widget("WidgetFuturePosts");')
);
 
class WidgetFuturePosts extends WP_Widget {
 
function __construct() {
	$widget_ops = array('description' => 'Displays future posts to your sidebar.');
	$control_ops = array();
	parent::__construct(
		false,
		'Future Posts Widget',
		$widget_ops,
		$control_ops
	);
}
 
public function form($par) {

	// Title
	$title = (isset($par['title']) && $par['title']) ? $par['title'] : '';
	$id = $this->get_field_id('title');
	$name = $this->get_field_name('title');
	echo 'Title: <br />';
	echo '<input type="text" id="'.$id.'" name="'.$name.'" value="';
	echo esc_attr($title);
	echo '" />';
	echo '<br />';
	echo '<br />';
	 
	// input howmany posts to display. default:5
	$count = (isset($par['pcount']) && $par['pcount']) ? $par['pcount'] : 5;
	$id = $this->get_field_id('pcount');
	$name = $this->get_field_name('pcount');
	echo 'Count: <br />';
	echo '<input type="text" id="'.$id.'" name="'.$name.'" value="';
	echo esc_attr( $count );
	echo '" />';
	echo '<br />Default: 5';
	echo '<br />';
	echo '<br />';
	
	$hide_date = (isset($par['hidedate']) && $par['hidedate']) ? $par['hidedate'] : false;
	
	$id = $this->get_field_id('hidedate');
	$name = $this->get_field_name('hidedate');
	echo 'Hide Dates?:<br>';
	echo '<input type="checkbox" id="'.$id.'" name="'.$name.'"';
	if ( $hide_date == 'on' ) {
		echo ' checked="checked"';
	}
	echo ' />Yes, hide them.';

}
 
public function update($new_instance, $old_instance) {
	return $new_instance;
}
 
public function widget($args, $par) {
	$count = (isset($par['pcount']) && $par['pcount']) ? (int)$par['pcount'] : 5;
	$hide_date = (isset($par['hidedate']) && $par['hidedate']) ? $par['hidedate'] : false;
	$post_staus = array( 'future' );
	$args2 = array(
		'post_type' => 'post',
		'post_status' => $post_staus,
		'posts_per_page' => $count,
		'orderby' => 'date',
		'order' => 'ASC',
		'ignore_sticky_posts' => 1
	);
	$my_query = new WP_Query( $args2 );	

	if ( $my_query->have_posts() ) {
	
		echo $args['before_widget'];
		echo $args['before_title'];
		echo esc_html( $par['title'] );
		echo $args['after_title'];
	
		
		echo '<ul>';
		global $previousday;
		
	
		while ( $my_query->have_posts() ):
		$my_query->the_post();
			$previousday = null;
		?>
		
			<li>
			<?php if ( 'on' != $hide_date ) { ?>
				<span class="futuredate"><?php the_date(); ?>: </span>
			<?php } ?>
				<?php the_title(); ?>
			</li>
		
		<?php
		endwhile;
		echo '</ul>';
		echo $args['after_widget'];
		wp_reset_postdata();
	}
}

}