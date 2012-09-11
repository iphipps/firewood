<?php 
/*
 * The footer template	
 *
 *
 */
?>

	</div><!-- / .main -->

	<?php do_atomic( 'before_footer' ); // firewood_before_footer ?>

		<footer class="contentinfo" role="contentinfo">

			<?php do_atomic( 'open_footer' ); // firewood_open_footer ?>

			<?php do_atomic( 'footer' ); // firewood_footer ?>

			<?php do_atomic( 'close_footer' ); // firewood_close_footer ?>

		</footer><!-- / .contentinfo -->

	<?php do_atomic( 'after_footer' ); // firewood_after_footer ?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo get_stylesheet_directory_uri() . '/js/jquery-1.7.2.min.js'?>"><\/script>')</script>
	
	<script src="<?php echo get_stylesheet_directory_uri() . '/js/plugins.js'?>"></script>
	<script src="<?php echo get_stylesheet_directory_uri() . '/js/functions.js'?>"></script>
	
	<?php wp_footer(); // wp_footer ?>

</body>
</html>