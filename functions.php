<?php


/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_setup')) :
	function neofukasawa_setup()
	{
		// Automatic feed
		add_theme_support('automatic-feed-links');

		// Set content-width
		global $content_width;
		if (!isset($content_width)) $content_width = 620;

		// Post thumbnails
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(88, 88, true);

		add_image_size('post-image', 973, 9999);
		add_image_size('post-thumb', 508, 9999);

		// Post formats
		add_theme_support('post-formats', array('gallery', 'image', 'video'));

		// Jetpack infinite scroll
		add_theme_support('infinite-scroll', array(
			'type' 		=> 'click',
			'container'	=> 'posts',
			'footer' 	=> false,
		));

		// Custom logo
		add_theme_support('custom-logo', array(
			'height'      => 240,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array('blog-title'),
		));

		// Title tag
		add_theme_support('title-tag');

		// Add nav menu
		register_nav_menu('primary', __('Primary Menu', 'neofukasawa'));

		// Make the theme translation ready
		load_theme_textdomain('neofukasawa', get_template_directory() . '/languages');
	}

	add_action('after_setup_theme', 'neofukasawa_setup');
endif;

/*	-----------------------------------------------------------------------------------------------
	REQUIRED FILES
	Include required files
--------------------------------------------------------------------------------------------------- */

// Handle Customizer settings
require get_template_directory() . '/inc/classes/class-neofukasawa-customize.php';

/* ---------------------------------------------------------------------------------------------
   GET THEME VERSION
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_get_version')) :
	function neofukasawa_get_version()
	{
		return wp_get_theme('neofukasawa')->theme_version;
	}
endif;

/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_load_javascript_files')) :
	function neofukasawa_load_javascript_files()
	{
		wp_register_script('neofukasawa_flexslider', get_template_directory_uri() . '/assets/js/flexslider.js', '2.7.0', true);

		wp_enqueue_script('neofukasawa_global', get_template_directory_uri() . '/assets/js/global.js', array('jquery', 'masonry', 'imagesloaded', 'neofukasawa_flexslider'), neofukasawa_get_version(), true);

		if (is_singular()) wp_enqueue_script('comment-reply');
	}

	add_action('wp_enqueue_scripts', 'neofukasawa_load_javascript_files');
endif;

/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_load_style')) :
	function neofukasawa_load_style()
	{
		if (!is_admin()) {
			$dependencies = array();

			wp_register_style('neofukasawa_googleFonts', get_theme_file_uri('/assets/css/fonts.css'));
			$dependencies[] = 'neofukasawa_googleFonts';

			wp_register_style('neofukasawa_genericons', get_theme_file_uri('/assets/fonts/genericons/genericons.css'));
			$dependencies[] = 'neofukasawa_genericons';

			wp_enqueue_style('neofukasawa_style', get_stylesheet_uri(), $dependencies, neofukasawa_get_version());
		}
	}

	add_action('wp_print_styles', 'neofukasawa_load_style');
endif;

/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_add_editor_styles')) :
	function neofukasawa_add_editor_styles()
	{
		add_editor_style(array('assets/css/neofukasawa-block-editor-styles.css', 'assets/css/fonts.css'));
	}

	add_action('init', 'neofukasawa_add_editor_styles');
endif;

/* ---------------------------------------------------------------------------------------------
   REGISTER WIDGET AREAS
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_sidebar_registration')) :
	function neofukasawa_sidebar_registration()
	{
		register_sidebar(array(
			'name' 			=> __('Sidebar', 'neofukasawa'),
			'id' 			=> 'sidebar',
			'description' 	=> __('Widgets in this area will be shown in the sidebar.', 'neofukasawa'),
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content clear">',
			'after_widget' 	=> '</div></div>'
		));
	}

	add_action('widgets_init', 'neofukasawa_sidebar_registration');
endif;

/* ---------------------------------------------------------------------------------------------
   ADD THEME WIDGETS
   --------------------------------------------------------------------------------------------- */

require_once(get_template_directory() . '/widgets/recent-posts.php');

/* ---------------------------------------------------------------------------------------------
   DELIST WIDGETS REPLACED BY THEME ONES
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_unregister_default_widgets')) {
	function neofukasawa_unregister_default_widgets()
	{
		unregister_widget('WP_Widget_Recent_Posts');
	}

	add_action('widgets_init', 'neofukasawa_unregister_default_widgets', 11);
}

/* ---------------------------------------------------------------------------------------------
   CHECK FOR JAVASCRIPT SUPPORT
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_html_js_class')) {
	function neofukasawa_html_js_class()
	{
		echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>' . "\n";
	}

	add_action('wp_head', 'neofukasawa_html_js_class', 1);
}

/* ---------------------------------------------------------------------------------------------
   ADD CLASSES TO PAGINATION
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_posts_link_attributes_1')) {
	function neofukasawa_posts_link_attributes_1()
	{
		return 'class="archive-nav-older fleft"';
	}

	add_filter('next_posts_link_attributes', 'neofukasawa_posts_link_attributes_1');
}

if (!function_exists('neofukasawa_posts_link_attributes_2')) {
	function neofukasawa_posts_link_attributes_2()
	{
		return 'class="archive-nav-newer fright"';
	}

	add_filter('previous_posts_link_attributes', 'neofukasawa_posts_link_attributes_2');
}

/* ---------------------------------------------------------------------------------------------
   CHANGE LENGTH OF EXCERPTS
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_custom_excerpt_length')) {
	function neofukasawa_custom_excerpt_length($length)
	{
		return 28;
	}

	add_filter('excerpt_length', 'neofukasawa_custom_excerpt_length', 999);
}

/* ---------------------------------------------------------------------------------------------
   CHANGE EXCERPT SUFFIX
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_new_excerpt_more')) {
	function neofukasawa_new_excerpt_more($more)
	{
		return '...';
	}

	add_filter('excerpt_more', 'neofukasawa_new_excerpt_more');
}


/* ---------------------------------------------------------------------------------------------
   BODY CLASSES
   --------------------------------------------------------------------------------------------- */


if (!function_exists('neofukasawa_body_classes')) {
	function neofukasawa_body_classes($classes)
	{
		// Check for mobile visitor
		$classes[] = wp_is_mobile() ? 'wp-is-mobile' : 'wp-is-not-mobile';

		return $classes;
	}

	add_filter('body_class', 'neofukasawa_body_classes');
}

/* ---------------------------------------------------------------------------------------------
   GET COMMENT EXCERPT
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_get_comment_excerpt')) {
	function neofukasawa_get_comment_excerpt($comment_ID = 0, $num_words = 20)
	{
		$comment = get_comment($comment_ID);
		$comment_text = strip_tags($comment->comment_content);
		$blah = explode(' ', $comment_text);

		if (count($blah) > $num_words) {
			$k = $num_words;
			$use_dotdotdot = 1;
		} else {
			$k = count($blah);
			$use_dotdotdot = 0;
		}

		$excerpt = '';
		for ($i = 0; $i < $k; $i++) {
			$excerpt .= $blah[$i] . ' ';
		}
		$excerpt .= ($use_dotdotdot) ? '...' : '';

		return apply_filters('get_comment_excerpt', $excerpt);
	}
}

/* ---------------------------------------------------------------------------------------------
   ADD ADMIN CSS
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_admin_css')) {
	function neofukasawa_admin_css()
	{
		echo '
		<style type="text/css">

			#postimagediv #set-post-thumbnail img {
				max-width: 100%;
				height: auto;
			}

		</style>';
	}

	add_action('admin_head', 'neofukasawa_admin_css');
}

/* ---------------------------------------------------------------------------------------------
   FLEXSLIDER FUNCTION
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_flexslider')) {
	function neofukasawa_flexslider($size = 'thumbnail')
	{
		$attachment_parent = is_page() ? $post->ID : get_the_ID();

		$image_args = array(
			'numberposts'    => -1, // show all
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_parent'    => $attachment_parent,
			'post_type'      => 'attachment',
			'post_status'    => null,
			'post_mime_type' => 'image',
		);

		$images = get_posts($image_args);

		if ($images) : ?>

			<div class="flexslider">

				<ul class="slides">

					<?php foreach ($images as $image) :

						$attimg = wp_get_attachment_image($image->ID, $size); ?>

						<li>
							<?php echo $attimg; ?>
						</li>

					<?php endforeach; ?>

				</ul>

			</div>

			<?php

		endif;
	}
}

/* ---------------------------------------------------------------------------------------------
   COMMENT FUNCTION
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_comment')) {
	function neofukasawa_comment($comment, $args, $depth)
	{
		switch ($comment->comment_type):
			case 'pingback':
			case 'trackback':
			?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

					<?php __('Pingback:', 'neofukasawa'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('Edit', 'neofukasawa'), '<span class="edit-link">', '</span>'); ?>

				</li>
			<?php
				break;
			default:
				global $post;
			?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

					<div id="comment-<?php comment_ID(); ?>" class="comment">

						<div class="comment-header">

							<?php echo get_avatar($comment, 160); ?>

							<div class="comment-header-inner">

								<h4><?php echo get_comment_author_link(); ?></h4>

								<div class="comment-meta">
									<a class="comment-date-link" href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>"><?php echo get_comment_date(get_option('date_format')); ?></a>
								</div><!-- .comment-meta -->

							</div><!-- .comment-header-inner -->

						</div>

						<div class="comment-content post-content">

							<?php comment_text(); ?>

						</div><!-- .comment-content -->

						<div class="comment-actions clear">

							<?php if (0 == $comment->comment_approved) : ?>

								<p class="comment-awaiting-moderation fright"><?php _e('Your comment is awaiting moderation.', 'neofukasawa'); ?></p>

							<?php endif; ?>

							<div class="fleft">

								<?php
								comment_reply_link(
									array(
										'reply_text' 	=> __('Reply', 'neofukasawa'),
										'depth'			=> $depth,
										'max_depth' 	=> $args['max_depth'],
										'before'		=> '',
										'after'			=> ''
									)
								);
								edit_comment_link(__('Edit', 'neofukasawa'), '<span class="sep">/</span>', '');
								?>

							</div>

						</div><!-- .comment-actions -->

					</div><!-- .comment-## -->

	<?php
				break;
		endswitch;
	}
}

/* ---------------------------------------------------------------------------------------------
   SPECIFY BLOCK EDITOR SUPPORT
------------------------------------------------------------------------------------------------ */

if (!function_exists('neofukasawa_add_gutenberg_features')) :
	function neofukasawa_add_gutenberg_features()
	{
		/* Block Editor Features ------------- */

		add_theme_support('align-wide');

		/* Block Editor Palette -------------- */

		$accent_color = get_theme_mod('accent_color', '#818fff');

		add_theme_support('editor-color-palette', array(
			array(
				'name' 	=> _x('Accent', 'Name of the accent color in the Gutenberg palette', 'neofukasawa'),
				'slug' 	=> 'accent',
				'color' => $accent_color,
			),
			array(
				'name' 	=> _x('Black', 'Name of the black color in the Gutenberg palette', 'neofukasawa'),
				'slug' 	=> 'black',
				'color' => '#46556E',
			),
			array(
				'name' 	=> _x('Dark Gray', 'Name of the dark gray color in the Gutenberg palette', 'neofukasawa'),
				'slug' 	=> 'dark-gray',
				'color' => '#444',
			),
			array(
				'name' 	=> _x('Medium Gray', 'Name of the medium gray color in the Gutenberg palette', 'neofukasawa'),
				'slug' 	=> 'medium-gray',
				'color' => '#666',
			),
			array(
				'name' 	=> _x('Light Gray', 'Name of the light gray color in the Gutenberg palette', 'neofukasawa'),
				'slug' 	=> 'light-gray',
				'color' => '#767676',
			),
			array(
				'name' 	=> _x('White', 'Name of the white color in the Gutenberg palette', 'neofukasawa'),
				'slug' 	=> 'white',
				'color' => '#fff',
			),
		));

		/* Block Editor Font Sizes ----------- */

		add_theme_support('editor-font-sizes', array(
			array(
				'name' 		=> _x('Small', 'Name of the small font size in Gutenberg', 'neofukasawa'),
				'shortName' => _x('S', 'Short name of the small font size in the Gutenberg editor.', 'neofukasawa'),
				'size' 		=> 16,
				'slug' 		=> 'small',
			),
			array(
				'name' 		=> _x('Regular', 'Name of the regular font size in Gutenberg', 'neofukasawa'),
				'shortName' => _x('M', 'Short name of the regular font size in the Gutenberg editor.', 'neofukasawa'),
				'size' 		=> 18,
				'slug' 		=> 'normal',
			),
			array(
				'name' 		=> _x('Large', 'Name of the large font size in Gutenberg', 'neofukasawa'),
				'shortName' => _x('L', 'Short name of the large font size in the Gutenberg editor.', 'neofukasawa'),
				'size' 		=> 24,
				'slug' 		=> 'large',
			),
			array(
				'name' 		=> _x('Larger', 'Name of the larger font size in Gutenberg', 'neofukasawa'),
				'shortName' => _x('XL', 'Short name of the larger font size in the Gutenberg editor.', 'neofukasawa'),
				'size' 		=> 27,
				'slug' 		=> 'larger',
			),
		));
	}

	add_action('after_setup_theme', 'neofukasawa_add_gutenberg_features');
endif;

/* ---------------------------------------------------------------------------------------------
   BLOCK EDITOR STYLES
   --------------------------------------------------------------------------------------------- */

if (!function_exists('neofukasawa_block_editor_styles')) :
	function neofukasawa_block_editor_styles()
	{
		$dependencies = array();

		wp_register_style('neofukasawa-block-editor-styles-font', get_theme_file_uri('/assets/css/fonts.css'));
		$dependencies[] = 'neofukasawa-block-editor-styles-font';

		wp_register_style('neofukasawa-block-editor-styles-genericons', get_theme_file_uri('/assets/fonts/genericons/genericons.css'));
		$dependencies[] = 'neofukasawa-block-editor-styles-genericons';

		// Enqueue the editor styles
		wp_enqueue_style('neofukasawa-block-editor-styles', get_theme_file_uri('/assets/css/neofukasawa-block-editor-styles.css'), $dependencies, neofukasawa_get_version(), 'all');
	}

	add_action('enqueue_block_editor_assets', 'neofukasawa_block_editor_styles', 1);
endif;
