<?php //Archive Template

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // atlantic_before_content ?>

	<div class="hfeed <?php atlantic_grid( 'content' ); ?>" role="main">

		<?php do_atomic( 'open_content' ); // atlantic_open_content ?>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php do_atomic( 'before_entry' ); // atlantic_before_entry ?>
					
					<article id="post-<?php the_ID(); ?>" class="<?php atlantic_entry_class(); ?>">

						<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail' ) ); ?>

						<?php do_atomic( 'open_entry' ); // atlantic_open_entry ?>

						<div class="entry-content summary">
							<?php the_excerpt(); ?>
							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
						</div><!-- / .entry-content -->

						<?php do_atomic( 'close_entry' ); // atlantic_close_entry ?>

					</article><!-- / .hentry -->

					<?php do_atomic( 'after_entry' ); // atlantic_after_entry ?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

		<?php do_atomic( 'close_content' ); // atlantic_close_content ?>

	</div><!-- / .content -->

	<?php do_atomic( 'after_content' ); // atlantic_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>