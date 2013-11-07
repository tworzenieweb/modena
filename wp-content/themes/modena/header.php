<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header" role="banner">
                    
                    <div class="folded"></div>
                    <a href="<?php echo home_url( '/bonprix' ); ?>" class="folded-link"><span>-30% ceny katalogowej na BONPRIX</span></a>
                    <div id="sequence">
                        <a href="#" class="sequence-prev">Poprzednie</a>
                        <a href="#" class="sequence-next">Następne</a>
                        <ul class="sequence-canvas unstyled">       
                          <?php
                              $args = array('post_type' => 'slide', 'posts_per_page' => 5);
                              $loop = new WP_Query($args);
                              while ($loop->have_posts()) : $loop->the_post(); ?>
                                  <li class="animate-in ">
                                      <div class="start_anime">
                                              <?php the_post_thumbnail(null, array('class' => 'bg_slider')); ?>  
                                              <div class="box_area_slider">
                                                <div class="panel" >
                                                  
                                                    <h2 class="title"><?php echo the_title(); ?></h2>
                                                    <div class="description"><?php the_content(); ?></div>
                                                </div>
                                      </div>
                                  </li>
                              <?php endwhile; ?> 

                        </ul>
                      </div>
                    

			<div id="navbar" class="navbar">
				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<h3 class="menu-toggle"><?php _e( 'Menu', 'twentythirteen' ); ?></h3>
					<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentythirteen' ); ?>"><?php _e( 'Skip to content', 'twentythirteen' ); ?></a>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
					<?php get_search_form(); ?>
				</nav><!-- #site-navigation -->
			</div><!-- #navbar -->
		</header><!-- #masthead -->

		<div id="main" class="site-main">
