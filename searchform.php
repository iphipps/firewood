<?php
/*
 * The searchform template
 *
 * @since 1.0
 * @package firewood
 * @subpackage Template
 */
?>

	<div class="search">
	
		<form method="get" class="search-form" action="<?php echo trailingslashit( home_url() ); ?>">
				<input type="search" results="5" name="s" placeholder="<?php if ( is_search() ) echo esc_attr( get_search_query() ); else esc_attr_e( 'Search', 'firewood' ); ?>">
				<input class="submit button" name="submit" type="submit" value="<?php esc_attr_e( 'Search', 'firewood' ); ?>" />
		</form><!-- / .search-form -->
	
	</div><!-- / .search -->