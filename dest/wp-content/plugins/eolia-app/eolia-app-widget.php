<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

class wp_eolia_app_widget_lastoffers extends WP_Widget {

	private $current_plugin_options = null;
	private $plugin_name = 'eolia-app';

	// constructor
	public function __construct() {
		parent::__construct( false, $name = _x( 'Latest Offers', 'widget', $this->plugin_name ) );
		$this->current_options = get_option( $this->plugin_name );
	}

	// widget form creation
	public function form( $instance ) {
		if ( $instance ) {
			$title    = esc_attr( $instance['title'] );
			$textarea = esc_textarea( $instance['textarea'] );
			$nb       = intval( esc_attr( $instance['nb'] ) ) ? intval( esc_attr( $instance['nb'] ) ) : 5;
			$template = esc_attr( $instance['template'] );
		} else {
			$title    = '';
			$textarea = '';
			$nb       = 5;
			$template = "%nomposte%";
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex(
					'Widget Title',
					'widget',
					$this->plugin_name
				); ?>
				:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'textarea' ); ?>"><?php _ex(
					'Intro text',
					'widget',
					$this->plugin_name
				); ?>
				:</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'textarea' ); ?>"
			          name="<?php echo $this->get_field_name( 'textarea' ); ?>"><?php echo $textarea; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'nb' ); ?>"><?php _ex(
					'Offer count',
					'widget',
					$this->plugin_name
				); ?>
				:</label>
			<input class="" id="<?php echo $this->get_field_id( 'nb' ); ?>"
			       name="<?php echo $this->get_field_name( 'nb' ); ?>" type="text" value="<?php echo $nb; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php _ex(
					'Template',
					'widget',
					$this->plugin_name
				); ?>
				:</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'template' ); ?>"
			          name="<?php echo $this->get_field_name( 'template' ); ?>"><?php echo $template; ?></textarea>
		</p>
		<?php
	}

	// widget update
	public function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['nb']       = intval( $new_instance['nb'] );
		$instance['template'] = $new_instance['template'];
		$instance['textarea'] = strip_tags( $new_instance['textarea'] );

		return $instance;
	}

	// widget display
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = apply_filters( 'widget_title', $instance['title'] );
		$nb       = $instance['nb'];
		$template = $instance['template'];
		$textarea = $instance['textarea'];
		$jobs     = eolia_get_jobs();
		if ( ! $jobs || ! isset( $jobs[ get_locale() ] ) ) {
			return;
		}
		$jobs = array_reverse(array_slice($jobs[get_locale()], $nb * -1));
		echo $before_widget;
		echo '<div>';
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		if ( $textarea ) {
			echo '<p>' . $textarea . '</p>';
		}
		echo '<ul class="joblist">';

		preg_match_all( '/%(?:\\\\.|[^\\\\%])*%/', $template, $matches );
		/** @var \Eolia\Controllers\JobController $job */
		foreach ( $jobs as $job ) {
			echo '<li><a href="' . get_the_permalink( $job->get_postId() ) . '">';
			$title = $template;
			if ( $matches && is_array( $matches[0] ) ) {
				foreach ( $matches[0] as $match ) {
					$key   = str_replace( '%', '', $match );
					$title = str_replace( $match, $job->get_additionnal_field( $key ), $title );
				}
				echo $title;
			} else {
				echo $job->get_title();
			}
			echo '</a></li>';
		}
		echo '</ul>';
		echo '</div>';
		echo $after_widget;
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("wp_eolia_app_widget_lastoffers");' ) );