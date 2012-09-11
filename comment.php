<?php //Comment Template ?>

	<?php global $post, $comment;?>

	<li id="comment-<?php comment_ID(); ?>" class="<?php hybrid_comment_class(); ?>">

		<?php do_atomic( 'before_comment' ); // hybrid_before_comment ?>

		<div class="comment-text">
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="alert moderation"><?php _e( 'Your comment is awaiting moderation.', hybrid_get_textdomain() ); ?></p>
			<?php endif; ?>

			<?php comment_text( $comment->comment_ID ); ?>
		</div><!-- .comment-text -->

		<?php do_atomic( 'after_comment' ); // hybrid_after_comment ?>

	<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>