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
 * @package firewood
 * @version 1.1
 * @author Hunt & Gather <dev@huntandgather.com>
 * @copyright Copyright (c) 2012, Hunt & Gather
 * @link http://huntandgather.com
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
$theme = new Hybrid();

add_action( 'after_setup_theme', 'firewood_theme_setup' );

/**
 * Theme setup function.
 *
 * @since 1.0
 */
function firewood_theme_setup() {

	/* Get action/filter hook prefix */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features */
	add_theme_support( 'hybrid-core-template-hierarchy' );
	add_theme_support( 'hybrid-core-shortcodes' );

	/* Add theme support for framework extensions */
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );

	/* Add theme support for WordPress features */
	add_theme_support( 'automatic-feed-links' );

	/* Firewood Functions */
	add_action( 'after_setup_theme', 'firewood_body_class' );
	add_action( 'after_setup_theme', 'firewood_entry_class' );

	/* Add the title, byline, and entry meta */
	add_action( "{$prefix}_open_entry", 'firewood_entry_header' );
	add_action( "{$prefix}_close_entry", 'firewood_entry_meta' );

	/* Add the comment avatar and comment meta before individual comments */
	add_action( "{$prefix}_before_comment", 'hybrid_avatar' );
	add_action( "{$prefix}_before_comment", 'firewood_comment_meta' );

	/* Deregister WordPress jQuery on the frontend */
	add_action( 'wp_enqueue_scripts', 'firewood_remove_jquery' );

	/* Add post navigation links */
	add_action( "{$prefix}_close_content", 'firewood_loop_nav' );


}


/**
 * Provides classes for the <body> element depending on page context.
 *
 * @since 1.0
 * @updated 1.1
 * @credit hybrid_body_class via hybrid core 1.4.3
 */
function firewood_body_class( $class = '' ) {
	global $wp_query;

	$classes = hybrid_get_context();

	/* Singular post (post_type) classes. */
	if ( is_singular() ) {

		/* Get the queried post object. */
		$post = get_queried_object();

		/* Checks for custom template. */
		$template = str_replace( array ( "{$post->post_type}-template-", "{$post->post_type}-" ), '', basename( get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true ), '.php' ) );
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
 * @since 1.0
 * @credit Original script hybrid_entry_class via Hybrid Core 1.3.1
 */
function firewood_entry_class( $class = '', $post_id = null ) {
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
 * Default entry header for posts.
 *
 * @since 1.0
 */
function firewood_entry_header() {

	$entry_byline = '';

	if ( 'post' == get_post_type() )

		$entry_byline = '<p class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'firewood' ) . '</p>';

	echo '<div class="entry-header">';

	echo apply_atomic_shortcode( 'entry_title', '[entry-title]' );

	echo apply_atomic_shortcode( 'byline', $entry_byline );

	echo '</div><!-- / .entry-header -->';
}

/**
 * Displays the default entry metadata.
 *
 * @since 1.0
 */
function firewood_entry_meta() {

	$entry_meta = '';

	if ( 'post' == get_post_type() )

		$entry_meta = '<p>' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms taxonomy="post_tag" before="| Tagged "] [entry-comments-link before="| "]', 'firewood' ) . '</p>';

	elseif ( is_page() && current_user_can( 'edit_page', get_the_ID() ) )

		$entry_meta = '<p>[entry-edit-link]</p>';

	echo '<div class="entry-meta">';

	echo apply_atomic_shortcode( 'entry_meta', $entry_meta );

	echo '</div><!-- / .entry-meta -->';

}

/**
 * Function for displaying a comment's metadata.
 *
 * @since 1.0
 */
function firewood_comment_meta() {

	$comment_meta = __( '[comment-author] [comment-published] [comment-permalink before="| "] [comment-edit-link before="| "] [comment-reply-link before="| "]', 'firewood' );

	echo '<div class="comment-meta">';

	echo apply_atomic_shortcode( 'comment_meta', $comment_meta );

	echo '</div><!-- / .comment-meta -->';
}

/**
 * Deregister WordPress jQuery, Google hosted jQuery is added manually with a local fallback.
 *
 * @since 1.0
 */
function firewood_remove_jquery() {

	wp_deregister_script('jquery');

}

/**
 * Loads the loop-nav.php template.
 *
 * @since 1.0
 */
function firewood_loop_nav() {

	get_template_part( 'loop', 'nav' );
}

?>