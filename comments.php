<?php
/*
 * The comments template displays the top-level
 * container calls comment.php for individual comments.
 *
 * @since 1.0
 * @package firewood
 * @subpackage Template
 */

	// Kill the page if trying to access this template directly.
	if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die( __( 'Please do not load this page directly. Thanks!', hybrid_get_textdomain() ) );

	// If a post password is required or no comments are given and comments/pings are closed, return.
	if ( post_password_required() || ( !have_comments() && !comments_open() && !pings_open() ) )
		return; ?>


<div id="comments-template">

	<?php if ( have_comments() ) : ?>

		<div id="comments">

			<h3 id="comments-number" class="comments-header"><?php comments_number( sprintf( __( 'No responses to %1$s', hybrid_get_textdomain() ), the_title( '&#8220;', '&#8221;', false ) ), sprintf( __( 'One response to %1$s', hybrid_get_textdomain() ), the_title( '&#8220;', '&#8221;', false ) ), sprintf( __( '%1$s responses to %2$s', hybrid_get_textdomain() ), '%', the_title( '&#8220;', '&#8221;', false ) ) ); ?></h3>

			<?php do_atomic( 'before_comment_list' ); // firewoord_before_comment_list ?>

			<ol class="comment-list">
				<?php wp_list_comments( hybrid_list_comments_args() ); ?>
			</ol><!-- .comment-list -->

			<?php do_atomic( 'after_comment_list' ); // firewoord_after_comment_list ?>

			<?php if ( get_option( 'page_comments' ) ) : ?>
				<div class="comment-navigation comment-pagination paged-navigation">
					<?php paginate_comments_links(); ?>
				</div><!-- .comment-navigation -->
			<?php endif; ?>

		</div><!-- #comments -->

	<?php else : ?>

		<?php if ( pings_open() && !comments_open() ) : ?>

			<p class="comments-closed pings-open">
				<?php printf( __( 'Comments are closed, but <a href="%1$s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', hybrid_get_textdomain() ), trackback_url( '0' ) ); ?>
			</p><!-- .comments-closed .pings-open -->

		<?php endif; ?>

	<?php endif; ?>

	<?php comment_form(); // Load the comment form. ?>

</div><!-- #comments-template -->