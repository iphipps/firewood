<?php
/*
 * The comment template displays a single comment.
 *
 * @since 1.0
 * @package firewood
 * @subpackage Template
 */
 ?>

	<?php global $post, $comment; ?>

	<li class="<?php firewood_comment_class(); ?>">

		<?php do_atomic( 'before_comment' ); // firewood_before_comment ?>

		<div class="avatar">

			<?php hybrid_avatar(); ?>

		</div><!-- / .avatar -->

		<div class="comment-header">

			<?php $comment_meta = __('[comment-author] [comment-published] [comment-permalink before="| "] [comment-edit-link before="| "] [comment-reply-link before="| "]', 'firewood' ); ?>

			<?php echo apply_atomic_shortcode( 'comment_meta', $comment_meta ); ?>

		</div><!-- / .comment-header -->

		<div class="comment-text">

			<?php if ( '0' == $comment->comment_approved ) : ?>

				<p class="alert moderation"><?php _e( 'Your comment is awaiting moderation.', 'firewood' ); ?></p>

			<?php endif; ?>

			<?php comment_text( $comment->comment_ID ); ?>

		</div><!-- / .comment-text -->

		<?php do_atomic( 'after_comment' ); // firewood_after_comment ?>

	<?php /* No closing </li> is needed */ ?>