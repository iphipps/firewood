<?php
/**
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package ATLANTIC
 * @version 1.60
 * @author Matthew Kelley <matt@matthewkelley.net>
 * @copyright Copyright (c) 2011, Matthew Kelley
 * @link http://matthewkelley.net
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
$theme = new Hybrid();

add_action( 'after_setup_theme', 'atlantic_theme_setup' );

/**
 * Theme setup function.
 *
 * @since 1.0
 */
function atlantic_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
	
	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-template-hierarchy' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'footer', 'about' ) );

	/* Add theme support for framework extensions. */
	add_theme_support( 'dev-stylesheet' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	/* Atlantic Functions */
	add_action( 'after_setup_theme', 'atlantic_grid_strings' );
	add_action( 'after_setup_theme', 'atlantic_grid' );
	add_action( 'after_setup_theme', 'atlantic_body_class' );
	add_action( 'after_setup_theme', 'atlantic_entry_class' );
	
	/* Header actions. */
	add_action( "{$prefix}_branding", 'hybrid_site_title' );
	add_action( "{$prefix}_branding", 'hybrid_site_description' );

	/* Add the loop info template. */	
	add_action( "{$prefix}_open_content", 'atlantic_loop_info' );

	/* Add the navigation links. */
	add_action( "{$prefix}_close_content", 'atlantic_loop_nav' );

	/* Add the comment avatar and comment meta before individual comments. */
	add_action( "{$prefix}_before_comment", 'hybrid_avatar' );
	add_action( "{$prefix}_before_comment", 'atlantic_comment_meta' );

	/* Add the footer insert to the footer. */
	add_action( "{$prefix}_footer", 'atlantic_footer_insert' );

	/* Add functions and plugins, deregister jQuery on the frontend */
	add_action( 'wp_enqueue_scripts', 'atlantic_scripts' );
		
	/* Add HTML5 site title and description */	
	add_filter( "{$prefix}_site_title", 'atlantic_filter_site_title' );
	add_filter( "{$prefix}_site_description", 'atlantic_filter_site_description' );
	
	/* Add the title, byline, and entry meta before and after the entry. */
	add_action( "{$prefix}_open_entry", 'atlantic_entry_header' );
	add_action( "{$prefix}_close_entry", 'atlantic_entry_meta' );
	
}

/**
 * Create default layout values for atlantic grid
 *
 * @since 1.1
 * @updated 1.60
 */
function atlantic_grid_strings() {

	$defaults = array();

	return apply_filters( 'atlantic_grid_strings', $defaults );

}

/**
 * Controls layout by outputing css grid classes
 *
 * @since 1.1
 */
function atlantic_grid( $context ) {

	/* Get an array of layout strings. */
	$strings = atlantic_grid_strings();

	/*The ouput variable equals the context's string if it exists. Else, the output variable equals the context slug. */
	isset( $strings[$context] ) ? $output = $strings[$context] : $output = $context;
	
	echo apply_atomic( 'atlantic_grid', $output );

}

/**
 * Provides classes for the <body> element depending on page context.
 *
 * @since 1.2
 * @updated 1.60
 * @credit derived from hybrid_body_class via hybrid core 1.2
 */
function atlantic_body_class( $class = '' ) {
	global $wp_query;

	$classes = hybrid_get_context();

	/* Singular post (post_type) classes. */
	if ( is_singular() ) {

		/* Get the queried post object. */
		$post = get_queried_object();

		/* Checks for custom template. */
		$template = str_replace( array ( "{$post->post_type}-template-", "{$post->post_type}-", '.php' ), '', get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true ) );
		if ( !empty( $template ) )
			$classes[] = "{$post->post_type}-template-{$template}";

		/* Post format. */
		if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) ) {
			$post_format = get_post_format( get_queried_object_id() );
			$classes[] = ( ( empty( $post_format ) || is_wp_error( $post_format ) ) ? "{$post->post_type}-format-standard" : "{$post->post_type}-format-{$post_format}" );
		}

		/* Attachment mime types. */
		if ( is_attachment() ) {
			foreach ( explode( '/', get_post_mime_type() ) as $type )
				$classes[] = "attachment-{$type}";
		}
	}

	/* Paged views. */
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
		$classes[] = 'paged paged-' . intval( $page );

	/* Input class. */
	if ( !empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	}

	/* Apply the filters for WP's 'body_class'. */
	$classes = apply_filters( 'body_class', $classes, $class );

	/* Join all the classes into one string. */
	$class = join( ' ', $classes );

	/* Print the body class. */
	echo apply_atomic( 'body_class', $class );
}

/**
 * Provides classes for the post contianer based on context.
 *
 * @since 1.60
 * @credit Original script hybrid_entry_class via Hybrid Core 1.3.1
 */
function atlantic_entry_class( $class = '', $post_id = null ) {
	static $post_alt;

	$post = get_post( $post_id );

	/* Make sure we have a real post first. */
	if ( !empty( $post ) ) {

		$post_id = $post->ID;

		/* Add hentry for microformats compliance, the post type, could use post status, $post->post_status . */
		$classes = array( 'hentry', $post->post_type );

		/* Post alt class. */
		$classes[] = 'post-' . ++$post_alt;
		$classes[] = ( $post_alt % 2 ) ? 'odd' : 'even alt';

	}

	/* If not a post. */
	else {
		$classes = array( 'hentry', 'error' );
	}

	/* User-created classes. */
	if ( !empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	}

	/* Apply the filters for WP's 'post_class'. */
	$classes = apply_filters( 'post_class', $classes, $class, $post_id );

	/* Join all the classes into one string and echo them. */
	$class = join( ' ', $classes );

	echo apply_atomic( 'entry_class', $class );
}

/**
 * Loads the loop-info.php template if the page isn't singular
 *
 * @since 1.60
 */
function atlantic_loop_info() {
	
	if ( !is_singular() )
		get_template_part( 'loop', 'info' );
}

/**
 * Loads the loop-nav.php template.
 *
 * @since 1.0
 */
function atlantic_loop_nav() {

	get_template_part( 'loop', 'nav' );
}

/**
 * Function for displaying a comment's metadata.
 *
 * @since 1.60
 */
function atlantic_comment_meta() {

	echo apply_atomic_shortcode( 'comment_meta', '<div class="comment-meta comment-meta-data">[comment-author] [comment-published] [comment-permalink before="| "] [comment-edit-link before="| "] [comment-reply-link before="| "]</div>' );
}

/**
 * Displays the footer insert from the theme settings page.
 *
 * @since 1.0
 */
function atlantic_footer_insert() {

	$footer_insert = hybrid_get_setting( 'footer_insert' );

	if ( !empty( $footer_insert ) )
		echo do_shortcode( $footer_insert );
}

/** 
 * Deregister WordPress jQuery, I am adding google hosted jQuery manually with a local fallback.
 * Register plugins and functions the WordPress way.
 * @since 1.0
 * @updated 1.60
 */
function atlantic_scripts() {

	wp_deregister_script('jquery');

}

/**
 * Filter hybrid site title
 *
 * @since 1.0
 * @updated 1.60
 */
function atlantic_filter_site_title() {

	if ( $title = get_bloginfo( 'name' ) )

		$title = '<h1 class="title site-title"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home">' . $title . '</a></h1>';

	return $title;

}

/**
 * Filter hybrid site description
 *
 * @since 1.3
 * @updated 1.60
 */
function atlantic_filter_site_description() {

	if ( $description = get_bloginfo( 'description' ) )

		$description = '<h2 class="description site-description">' . $description . '</h2>';

	return $description;

}

/**
 * Default entry header for posts.
 *
 * @since 1.0
 */
function atlantic_entry_header() {

	$byline = '';

	if ( 'post' == get_post_type() && 'link_category' !== get_query_var( 'taxonomy' ) )

		$byline = '<p class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</p>';

	echo '<header class="entry-header">';

	echo apply_atomic_shortcode( 'entry_title', '[entry-title]' );

	echo apply_atomic_shortcode( 'byline', $byline );

	echo '</header><!-- / .entry-header -->';
}

/**
 * Displays the default entry metadata.
 *
 * @since 1.0
 * @updated 1.60
 */
function atlantic_entry_meta() {

	$meta = '';

	if ( 'post' == get_post_type() )

		$meta = '<p>' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms taxonomy="post_tag" before="| Tagged "] [entry-comments-link before="| "]', hybrid_get_textdomain() ) . '</p>';

	elseif ( is_page() && current_user_can( 'edit_page', get_the_ID() ) )

		$meta = '<p>[entry-edit-link]</p>';

	echo '<footer class="entry-meta">';

	echo apply_atomic_shortcode( 'entry_meta', $meta );

	echo '</footer><!-- / .entry-meta -->';

}

?>