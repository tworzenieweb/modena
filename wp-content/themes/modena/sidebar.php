<?php
/**
 * The sidebar containing the secondary widget area, displays on posts and pages.
 *
 * If no active widgets in this sidebar, it will be hidden completely.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<div id="tertiary" class="sidebar-container" role="complementary">
		<div class="sidebar-inner">
                    
			<div class="widget-area">
                            
                                <div class="logo">
                                    <h1><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h1>
                                    <h2><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></h2>

                                </div>
                            
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</div><!-- #tertiary -->
<?php endif; ?>