<?php
class rpwe_widget extends WP_Widget {

	/**
	 * Widget setup
	 */
	function __construct() {

		$widget_ops = array(
			'classname'		=> 'rpwe_widget recent-posts-extended',
			'description'	=> __( 'Advanced recent posts widget.', 'rpwe' )
		);

		$control_ops = array(
			'width'		=> 800,
			'height'	=> 350,
			'id_base'	=> 'rpwe_widget'
		);

		parent::__construct( 'rpwe_widget', __( '&raquo; Recent Posts Widget Extended', 'rpwe' ), $widget_ops, $control_ops );

	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title 			= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$title_url		= $instance['title_url'];
		$cssID 			= $instance['cssID'];
		$limit 			= (int)( $instance['limit'] );
		$offset 		= (int)( $instance['offset'] );
		$order 			= $instance['order'];
		$excerpt 		= $instance['excerpt'];
		$length 		= (int)( $instance['length'] );
		$thumb 			= $instance['thumb'];
		$thumb_height 	= (int)( $instance['thumb_height'] );
		$thumb_width 	= (int)( $instance['thumb_width'] );
		$thumb_default 	= esc_url( $instance['thumb_default'] );
		$thumb_align 	= $instance['thumb_align'];
		$cat 			= $instance['cat'];
		$tag 			= $instance['tag'];
		$post_type 		= $instance['post_type'];
		$date 			= $instance['date'];
		$date_format 	= strip_tags( $instance['date_format'] );
		$readmore 		= $instance['readmore'];
		$readmore_text 	= strip_tags( $instance['readmore_text'] );
		$styles_default = $instance['styles_default'];
		$css 			= $instance['css'];

		echo $before_widget;

		if ( $styles_default == true )
			rpwe_custom_styles();

		if ( $styles_default == false && ! empty( $css ) )
			echo '<style>' . $css . '</style>';

		if ( ! empty( $title_url ) && ! empty( $title ) )
			echo $before_title . '<a href="' . esc_url( $title_url ) . '" title="' . $title . '">' . $title . '</a>' . $after_title;
		elseif ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		global $post;

			$args = array(
				'numberposts'	=> $limit,
				'category__in'	=> $cat,
				'tag__in'		=> $tag,
				'post_type'		=> $post_type,
				'offset'		=> $offset,
				'order'			=> $order
			);

			$default_args 		= apply_filters( 'rpwe_default_query_arguments', $args ); // Allow developer to filter the query.
			$rpwewidget 		= get_posts( $default_args );

		?>

		<div <?php echo( ! empty( $cssID ) ? 'id="' . $cssID . '"' : '' ); ?> class="rpwe-block">

			<ul class="rpwe-ul">

				<?php foreach ( $rpwewidget as $post ) : setup_postdata( $post ); ?>

					<li class="rpwe-clearfix clearfix cl">

						<?php if ( $thumb == true ) { ?>

							<?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'rpwe' ), the_title_attribute('echo=0' ) ); ?>" rel="bookmark">
									<?php
									if ( current_theme_supports( 'get-the-image' ) )
										get_the_image( array( 'meta_key' => 'Thumbnail', 'height' => $thumb_height, 'width' => $thumb_width, 'image_class' => $thumb_align . ' rpwe-thumb', 'link_to_post' => false ) );
									else
										the_post_thumbnail( array( $thumb_height, $thumb_width ), array( 'class' => $thumb_align . ' rpwe-thumb', 'alt' => esc_attr(get_the_title() ), 'title' => esc_attr( get_the_title() ) ) );
									?>
								</a>
							<?php } else { ?>				
								<?php if ( $thumb_default ) echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><img class="' . $thumb_align . ' rpwe-thumb" src="' . $thumb_default . '" alt="' . esc_attr( get_the_title() ) . '"></a>'; ?>
							<?php } ?>

						<?php } ?>

						<h3 class="rpwe-title">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'rpwe' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h3>

						<?php if ( $date == true ) { ?>
                                                        <span class="categories-links">
                                                        <?php
                                                        $post_categories = wp_get_post_categories($post->ID);
                                                        
                                                        $post_categories = array_slice($post_categories, 0, 2);
                                                        
                                                        foreach($post_categories as $c):
                                                                $cat = get_category( $c );
                                                                
                                                        ?>      
                                                        <a href="<?php echo get_category_link($cat); ?>" rel="category_tag"><?php echo $cat->name; ?></a> 
                                                        <? endforeach; ?>
                                                        <br />
                                                        </span>
                                                
							<time class="rpwe-time published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" pubdate><?php echo esc_html( get_the_date( $date_format ) ); ?></time>
						<?php } ?>

						<?php if ( $excerpt == true ) { ?>
							<div class="rpwe-summary"><?php echo rpwe_excerpt( $length ); ?> <?php if ( $readmore == true ) { echo '<a href="' . esc_url( get_permalink() ) . '" class="more-link">' . $readmore_text . '</a>'; } ?></div>
						<?php } ?>

					</li>

				<?php endforeach; wp_reset_postdata(); ?>

			</ul>

		</div><!-- .rpwe-block - http://wordpress.org/extend/plugins/recent-posts-widget-extended/ -->

		<?php

		echo $after_widget;

	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {

		$instance 					= $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['title_url'] 		= esc_url_raw( $new_instance['title_url'] );
		$instance['cssID'] 			= sanitize_html_class( $new_instance['cssID'] );
		$instance['limit'] 			= (int)( $new_instance['limit'] );
		$instance['offset'] 		= (int)( $new_instance['offset'] );
		$instance['order'] 			= $new_instance['order'];
		$instance['excerpt'] 		= $new_instance['excerpt'];
		$instance['length'] 		= (int)( $new_instance['length'] );
		$instance['thumb'] 			= $new_instance['thumb'];
		$instance['thumb_height'] 	= (int)( $new_instance['thumb_height'] );
		$instance['thumb_width'] 	= (int)( $new_instance['thumb_width'] );
		$instance['thumb_default'] 	= esc_url_raw( $new_instance['thumb_default'] );
		$instance['thumb_align'] 	= $new_instance['thumb_align'];
		$instance['cat'] 			= $new_instance['cat'];
		$instance['tag'] 			= $new_instance['tag'];
		$instance['post_type'] 		= $new_instance['post_type'];
		$instance['date'] 			= $new_instance['date'];
		$instance['date_format'] 	= strip_tags( $new_instance['date_format'] );
		$instance['readmore'] 		= $new_instance['readmore'];
		$instance['readmore_text'] 	= strip_tags( $new_instance['readmore_text'] );
		$instance['styles_default'] = $new_instance['styles_default'];
		$instance['css'] 			= $new_instance['css'];

		return $instance;

	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {

		$css_defaults = ".rpwe-block ul{\n}\n\n.rpwe-block li{\n}\n\n.rpwe-block a{\n}\n\n.rpwe-block h3{\n}\n\n.rpwe-thumb{\n}\n\n.rpwe-summary{\n}\n\n.rpwe-time{\n}\n\n.rpwe-alignleft{\n}\n\n.rpwe-alignright{\n}\n\n.rpwe-alignnone{\n}\n\n.rpwe-clearfix:before,\n.rpwe-clearfix:after{\ncontent: \"\";\ndisplay: table;\n}\n\n.rpwe-clearfix:after{\nclear:both;\n}\n\n.rpwe-clearfix{\nzoom: 1;\n}";

		/* Set up some default widget settings. */
		$defaults = array(
			'title' 		=> '',
			'title_url' 	=> '',
			'cssID' 		=> '',
			'limit' 		=> 5,
			'offset' 		=> 0,
			'order' 		=> 'DESC',
			'excerpt' 		=> false,
			'length' 		=> 10,
			'thumb' 		=> true,
			'thumb_height' 	=> 45,
			'thumb_width' 	=> 45,
			'thumb_default' => 'http://placehold.it/45x45/f0f0f0/ccc',
			'thumb_align' 	=> 'rpwe-alignleft',
			'cat' 			=> '',
			'tag' 			=> '',
			'post_type' 	=> '',
			'date' 			=> true,
			'date_format' 	=> 'F, Y',
			'readmore' 		=> false,
			'readmore_text'	=> __( 'Read More &raquo;', 'rpwe' ),
			'styles_default'=> true,
			'css' 			=> $css_defaults
		);

		$instance 		= wp_parse_args( (array)$instance, $defaults );
		$title 			= strip_tags( $instance['title'] );
		$title_url 		= esc_url( $instance['title_url'] );
		$cssID 			= sanitize_html_class( $instance['cssID'] );
		$limit 			= (int)( $instance['limit'] );
		$offset 		= (int)( $instance['offset'] );
		$order 			= $instance['order'];
		$excerpt 		= $instance['excerpt'];
		$length 		= (int)($instance['length']);
		$thumb 			= $instance['thumb'];
		$thumb_height 	= (int)( $instance['thumb_height'] );
		$thumb_width 	= (int)( $instance['thumb_width'] );
		$thumb_default 	= $instance['thumb_default'];
		$thumb_align 	= $instance['thumb_align'];
		$cat 			= $instance['cat'];
		$tag 			= $instance['tag'];
		$post_type 		= $instance['post_type'];
		$date 			= $instance['date'];
		$date_format 	= strip_tags( $instance['date_format'] );
		$readmore 		= $instance['readmore'];
		$readmore_text 	= strip_tags( $instance['readmore_text'] );
		$styles_default	= $instance['styles_default'];
		$css 			= $instance['css'];

		?>

		<div class="rpwe-columns-3">

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title_url' ) ); ?>"><?php _e( 'Title URL:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_url' ) ); ?>" type="text" value="<?php echo $title_url; ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cssID' ) ); ?>"><?php _e( 'CSS ID:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('cssID')); ?>" name="<?php echo esc_attr($this->get_field_name('cssID')); ?>" type="text" value="<?php echo $cssID; ?>"/>
			</p>
			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'styles_default' ) ); ?>"><?php _e( 'Use Default Styles', 'rpwe' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'styles_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'styles_default' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $styles_default ); ?> />&nbsp;
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'css' ) ); ?>"><?php _e( 'CSS:', 'rpwe' ); ?></label>
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'css' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'css' ) ); ?>" style="height:100px;"><?php echo $css; ?></textarea>
				<small><?php _e( 'If you turn off the default styles, please create your own style.', 'rpwe' ); ?></small>
			</p>

		</div>
		
		<div class="rpwe-columns-3">

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Limit:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo $limit; ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php _e( 'Offset (the number of posts to skip):', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="text" value="<?php echo $offset; ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e( 'Order:', 'rpwe' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" style="width:100%;">
					<option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php _e( 'DESC', 'rpwe' ) ?></option>
					<option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php _e( 'ASC', 'rpwe' ) ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php _e( 'Limit to Category: ', 'rpwe' ); ?></label>
			   	<select class="widefat" multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cat' ) ); ?>[]" style="width:100%;">
					<optgroup label="Categories">
						<?php $categories = get_terms( 'category' ); ?>
						<?php foreach( $categories as $category ) { ?>
							<option value="<?php echo $category->term_id; ?>" <?php if ( is_array( $cat ) && in_array( $category->term_id, $cat ) ) echo ' selected="selected"'; ?>><?php echo $category->name; ?></option>
						<?php } ?>
					</optgroup>
   			    </select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tag' ) ); ?>"><?php _e( 'Limit to Tag: ', 'rpwe' ); ?></label>
			   	<select class="widefat" multiple="multiple" id="<?php echo esc_attr( $this->get_field_id( 'tag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tag' ) ); ?>[]" style="width:100%;">
					<optgroup label="Tags">
						<?php $tags = get_terms( 'post_tag' ); ?>
						<?php foreach( $tags as $post_tag ) { ?>
							<option value="<?php echo $post_tag->term_id; ?>" <?php if ( is_array( $tag ) && in_array( $post_tag->term_id, $tag ) ) echo ' selected="selected"'; ?>><?php echo $post_tag->name; ?></option>
						<?php } ?>
					</optgroup>
   			    </select>

			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php _e( 'Choose the Post Type: ', 'rpwe' ); ?></label>
				<?php /* pros Justin Tadlock - http://themehybrid.com/ */ ?>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
					<?php foreach ( get_post_types( '', 'objects' ) as $post_type ) { ?>
						<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>><?php echo esc_html( $post_type->labels->singular_name ); ?></option>
					<?php } ?>
				</select>
			</p>

		</div>

		<div class="rpwe-columns-3 rpwe-column-last">

			<?php if ( current_theme_supports( 'post-thumbnails' ) ) { ?>

				<p>
					<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"><?php _e( 'Display Thumbnail', 'rpwe' ); ?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $thumb ); ?> />&nbsp;
				</p>
				<p>
					<label class="rpwe-block" for="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>"><?php _e( 'Thumbnail (height, width, align):', 'rpwe' ); ?></label>
					<input class= "small-input" id="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_height' ) ); ?>" type="text" value="<?php echo $thumb_height; ?>"/>
					<input class="small-input" id="<?php echo esc_attr( $this->get_field_id( 'thumb_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_width' ) ); ?>" type="text" value="<?php echo $thumb_width; ?>"/>
					<select class="small-input" id="<?php echo esc_attr( $this->get_field_id( 'thumb_align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_align' ) ); ?>">
						<option value="rpwe-alignleft" <?php selected( $thumb_align, 'rpwe-alignleft' ); ?>><?php _e( 'Left', 'rpwe' ) ?></option>
						<option value="rpwe-alignright" <?php selected( $thumb_align, 'rpwe-alignright' ); ?>><?php _e( 'Right', 'rpwe' ) ?></option>
						<option value="rpwe-alignnone" <?php selected( $thumb_align, 'rpwe-alignnone' ); ?>><?php _e( 'Center', 'rpwe' ) ?></option>
					</select>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'thumb_default' ) ); ?>"><?php _e( 'Default Thumbnail:', 'rpwe' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'thumb_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_default' ) ); ?>" type="text" value="<?php echo $thumb_default; ?>"/>
					<small><?php _e( 'Leave it blank to disable.', 'rpwe' ); ?></small>
				</p>

			<?php } ?>

			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"><?php _e( 'Display Excerpt', 'rpwe' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $excerpt ); ?> />&nbsp;
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"><?php _e( 'Excerpt Length:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'length' ) ); ?>" type="text" value="<?php echo $length; ?>"/>
			</p>
			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"><?php _e( 'Display Readmore', 'rpwe' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $readmore ); ?> />&nbsp;
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>"><?php _e( 'Readmore Text:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore_text' ) ); ?>" type="text" value="<?php echo $readmore_text; ?>"/>
			</p>
			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php _e( 'Display Date', 'rpwe' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $date ); ?> />&nbsp;
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'date_format' ) ); ?>"><?php _e( 'Date Format:', 'rpwe' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date_format' ) ); ?>" type="text" value="<?php echo $date_format; ?>"/>
				<small><?php _e( '<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Date reference</a>', 'rpwe' ) ?></small>
			</p>

		</div>

		<div class="clear"></div>

	<?php
	}

}

/**
 * Register widget.
 *
 * @since 0.1
 */
function rpwe_register_widget() {
	register_widget( 'rpwe_widget' );
}
add_action( 'widgets_init', 'rpwe_register_widget' );

/**
 * Print a custom excerpt.
 * http://bavotasan.com/2009/limiting-the-number-of-words-in-your-excerpt-or-content-in-wordpress/
 *
 * @since 0.1
 */
function rpwe_excerpt( $length ) {

	$excerpt = explode( ' ', get_the_excerpt(), $length );
	if ( count( $excerpt ) >= $length ) {
		array_pop( $excerpt );
		$excerpt = implode( " ", $excerpt );
	} else {
		$excerpt = implode( " ", $excerpt );
	}
	$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

	return $excerpt;

}

/**
 * Custom Styles.
 *
 * @since 0.8
 */
function rpwe_custom_styles() {
	?>
<style>
.rpwe-block ul{list-style:none!important;margin-left:0!important;padding-left:0!important;}.rpwe-block li{border-bottom:1px solid #eee;margin-bottom:10px;padding-bottom:10px;}.rpwe-block a{display:inline!important;text-decoration:none;}.rpwe-block h3{background:none!important;clear:none;margin-bottom:0!important;font-weight:400;font-size:12px!important;line-height:1.5em;}.rpwe-thumb{border:1px solid #EEE!important;box-shadow:none!important;margin:2px 10px 2px 0;padding:3px!important;}.rpwe-summary{font-size:12px;}.rpwe-time{color:#bbb;font-size:11px;}.rpwe-alignleft{display:inline;float:left;}.rpwe-alignright{display:inline;float:right;}.rpwe-alignnone{display:block;float:none;}.rpwe-clearfix:before,.rpwe-clearfix:after{content:"";display:table;}.rpwe-clearfix:after{clear:both;}.rpwe-clearfix{zoom:1;}
</style>
	<?php
}
?>