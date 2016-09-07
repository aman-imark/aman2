<?php
class EWD_URP_Recent_Reviews_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_urp_recent_reviews_widget', // Base ID
			__('Recent Reviews', 'EWD_URP'), // Name
			array( 'description' => __( 'Insert a number of recent reviews', 'EWD_URP' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$Posts = get_posts('posts_per_page=' . $instance['post_count'] . '&post_type=urp_review');
		foreach ($Posts as $Post) {$Post_IDs .= $Post->ID . ",";}
		trim($Post_IDs, ",");

		echo $args['before_widget'];
		echo $instance['before_text'];
		echo do_shortcode("[select-review review_id='" . $Post_IDs . "']");
		echo $instance['after_text'];
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$before_text = ! empty( $instance['before_text'] ) ? $instance['before_text'] : __( '', 'EWD_URP' );
		$post_count = ! empty( $instance['post_count'] ) ? $instance['post_count'] : __( '3', 'EWD_URP' );
		$after_text = ! empty( $instance['after_text'] ) ? $instance['after_text'] : __( '', 'EWD_URP' );

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'before_text' ); ?>"><?php _e( 'Text Before:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'before_text' ); ?>" name="<?php echo $this->get_field_name( 'before_text' ); ?>" type="text" value="<?php echo esc_attr( $before_text ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of Reviews:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'after_text' ); ?>"><?php _e( 'Text After:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'after_text' ); ?>" name="<?php echo $this->get_field_name( 'after_text' ); ?>" type="text" value="<?php echo esc_attr( $after_text ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['before_text'] = ( ! empty( $new_instance['before_text'] ) ) ? strip_tags( $new_instance['before_text'] ) : '';
		$instance['post_count'] = ( ! empty( $new_instance['post_count'] ) ) ? strip_tags( $new_instance['post_count'] ) : '';
		$instance['after_text'] = ( ! empty( $new_instance['after_text'] ) ) ? strip_tags( $new_instance['after_text'] ) : '';
		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_URP_Recent_Reviews_Widget");'));

class EWD_URP_Selected_Reviews_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_urp_selected_reviews_widget', // Base ID
			__('Selected Reviews', 'EWD_URP'), // Name
			array( 'description' => __( 'Insert a number of selected reviews', 'EWD_URP' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		echo $instance['before_text'];
		echo do_shortcode("[select-review review_id='" . $instance['post_ids'] . "']");
		echo $instance['after_text'];
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$before_text = ! empty( $instance['before_text'] ) ? $instance['before_text'] : __( '', 'EWD_URP' );
		$post_ids = ! empty( $instance['post_ids'] ) ? $instance['post_ids'] : __( '', 'EWD_URP' );
		$after_text = ! empty( $instance['after_text'] ) ? $instance['after_text'] : __( '', 'EWD_URP' );

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'before_text' ); ?>"><?php _e( 'Text Before:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'before_text' ); ?>" name="<?php echo $this->get_field_name( 'before_text' ); ?>" type="text" value="<?php echo esc_attr( $before_text ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'post_ids' ); ?>"><?php _e( 'IDs of Posts to Display', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'post_ids' ); ?>" name="<?php echo $this->get_field_name( 'post_ids' ); ?>" type="text" value="<?php echo esc_attr( $post_ids ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'after_text' ); ?>"><?php _e( 'Text After:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'after_text' ); ?>" name="<?php echo $this->get_field_name( 'after_text' ); ?>" type="text" value="<?php echo esc_attr( $after_text ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['before_text'] = ( ! empty( $new_instance['before_text'] ) ) ? strip_tags( $new_instance['before_text'] ) : '';
		$instance['post_ids'] = ( ! empty( $new_instance['post_ids'] ) ) ? strip_tags( $new_instance['post_ids'] ) : '';
		$instance['after_text'] = ( ! empty( $new_instance['after_text'] ) ) ? strip_tags( $new_instance['after_text'] ) : '';
		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_URP_Selected_Reviews_Widget");'));

class EWD_URP_Popular_Reviews_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_urp_popular_reviews_widget', // Base ID
			__('Popular Reviews', 'EWD_URP'), // Name
			array( 'description' => __( 'Insert a number of popular reviews', 'EWD_URP' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$args =  array('posts_per_page' => $instance['post_count'],
						'post_type' => 'urp_review',
						'orderby' => 'meta_value_num',
						'meta_key' => 'urp_view_count'
					);
		$Posts = get_posts($args);
		foreach ($Posts as $Post) {$Post_IDs .= $Post->ID . ",";}
		trim($Post_IDs, ",");

		echo $args['before_widget'];
		echo $instance['before_text'];
		echo do_shortcode("[select-review review_id='" . $Post_IDs . "']");
		echo $instance['after_text'];
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$before_text = ! empty( $instance['before_text'] ) ? $instance['before_text'] : __( '', 'EWD_URP' );
		$post_count = ! empty( $instance['post_count'] ) ? $instance['post_count'] : __( '3', 'EWD_URP' );
		$after_text = ! empty( $instance['after_text'] ) ? $instance['after_text'] : __( '', 'EWD_URP' );

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'before_text' ); ?>"><?php _e( 'Text Before:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'before_text' ); ?>" name="<?php echo $this->get_field_name( 'before_text' ); ?>" type="text" value="<?php echo esc_attr( $before_text ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of Reviews:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="text" value="<?php echo esc_attr( $post_count ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'after_text' ); ?>"><?php _e( 'Text After:', 'EWD_URP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'after_text' ); ?>" name="<?php echo $this->get_field_name( 'after_text' ); ?>" type="text" value="<?php echo esc_attr( $after_text ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['before_text'] = ( ! empty( $new_instance['before_text'] ) ) ? strip_tags( $new_instance['before_text'] ) : '';
		$instance['post_count'] = ( ! empty( $new_instance['post_count'] ) ) ? strip_tags( $new_instance['post_count'] ) : '';
		$instance['after_text'] = ( ! empty( $new_instance['after_text'] ) ) ? strip_tags( $new_instance['after_text'] ) : '';
		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_URP_Popular_Reviews_Widget");'));
?>