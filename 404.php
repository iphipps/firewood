<?php 
/*
 * The 404 template
 *
 * @since 1.0
 * @package firewood
 * @subpackage Template
 */
@header( 'HTTP/1.1 404 Not found', true, 404 );

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // firewood_before_content ?>

	<div class="hfeed <?php firewood_grid( 'content' ); ?>" role="main">

		<?php do_atomic( 'open_content' ); // firewood_open_content ?>

			<article id="post-0" class="<?php firewood_entry_class(); ?>">

				<h1 class="error-404-title entry-title"><?php _e( 'Oh No! Not Found', hybrid_get_textdomain() ); ?></h1>

				<div class="entry-content">

					<p><?php printf( __( 'You tried going to %1$s, and it doesn\'t exist.', hybrid_get_textdomain() ), '<code>' . home_url( esc_url( $_SERVER['REQUEST_URI'] ) ) . '</code>' ); ?></p>

				</div><!-- / .entry-content -->
				
			</article><!-- / article -->

		<?php do_atomic( 'close_content' ); // firewood_close_content ?>

	</div><!-- / .content -->

	<?php do_atomic( 'after_content' ); // firewood_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>