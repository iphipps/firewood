<?php
/*
 * The loop navigation template part
 *
 * @since 1.0
 * @package firewood
 * @subpackage Part
 */
?>

	<?php if ( is_attachment() ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '&larr; Return to entry', 'firewood' ) . '</span>' ); ?>
		</div><!-- / .loop-nav -->

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '<div class="previous">' . __( 'Previous Entry: %link', 'firewood' ) . '</div>', '%title' ); ?>
			<?php next_post_link( '<div class="next">' . __( 'Next Entry: %link', 'firewood' ) . '</div>', '%title' ); ?>
		</div><!-- / .loop-nav -->

	<?php elseif ( !is_singular() && current_theme_supports( 'loop-pagination' ) ) : ?>
		
		<?php $pagination_args = array( 'before' => '<div class="loop-nav">', 'after' => '</div><!-- / .loop-nav -->'); ?>
		<?php loop_pagination( $pagination_args ); ?>

	<?php endif; ?>