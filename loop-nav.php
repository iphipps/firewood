<?php // Loop Nav Template ?>

	<?php if ( is_attachment() ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '&larr; Return to entry', hybrid_get_textdomain() ) . '</span>' ); ?>
		</div><!-- / .loop-nav -->

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '<div class="previous">' . __( 'Previous Entry: %link', hybrid_get_textdomain() ) . '</div>', '%title' ); ?>
			<?php next_post_link( '<div class="next">' . __( 'Next Entry: %link', hybrid_get_textdomain() ) . '</div>', '%title' ); ?>
		</div><!-- / .loop-nav -->

	<?php elseif ( !is_singular() && current_theme_supports( 'loop-pagination' ) ) :
		
		$args =	array( 'before' => '<div class="loop-nav">', 'after' => '</div><!-- / .loop-nav -->');
		loop_pagination( $args );
	 	?>


	<?php endif; ?>