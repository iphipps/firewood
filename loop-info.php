<?php
/*
 * The loop info template part
 * @since 1.0
 * @package firewood
 * @subpackage Part
 */
?>

	<?php if ( is_home() && !is_front_page() ) : ?>

		<?php global $wp_query; ?>

		<header class="loop-info">
			<h1 class="title loop-title"><?php echo get_post_field( 'post_title', $wp_query->get_queried_object_id() ); ?></h1>
		</header><!-- / .loop-info -->

	<?php elseif ( is_category() ) : ?>

		<header class="loop-info">

			<h1 class="title loop-title"><?php single_cat_title(); ?></h1>

			<div class="description loop-description">
				<?php echo category_description(); ?>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php elseif ( is_tag() ) : ?>

		<header class="loop-info">

			<h1 class="title loop-title"><?php single_tag_title(); ?></h1>

			<div class="description loop-description">
				<?php echo tag_description(); ?>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php elseif ( is_tax() ) : ?>

		<header class="loop-info">

			<h1 class="title loop-title"><?php single_term_title(); ?></h1>

			<div class="description loop-description">
				<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php elseif ( is_author() ) : ?>

		<?php $id = get_query_var( 'author' ); ?>

		<div id="hcard-<?php the_author_meta( 'user_nicename', $id ); ?>" class="loop-info vcard user-profile">

			<h1 class="title loop-title fn n"><?php the_author_meta( 'display_name', $id ); ?></h1>

			<div class="description loop-description">
				<?php echo get_avatar( get_the_author_meta( 'user_email', $id ), '100', '', get_the_author_meta( 'display_name', $id ) ); ?>

				<p class="user-bio">
					<?php the_author_meta( 'description', $id ); ?>
				</p><!-- / .user-bio -->
			</div><!-- / .loop-description -->

		</div><!-- / .loop-info -->

	<?php elseif ( is_search() ) : ?>

		<header class="loop-info">

			<h1 class="title loop-title"><?php echo esc_attr( get_search_query() ); ?></h1>

			<div class="description loop-description">
				<p>
				<?php printf( __( 'You are browsing the search results for &quot;%1$s&quot;', hybrid_get_textdomain() ), esc_attr( get_search_query() ) ); ?>
				</p>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php elseif ( is_date() ) : ?>

		<header class="loop-info">
			<h1 class="title loop-title"><?php _e( 'Archives by date', hybrid_get_textdomain() ); ?></h1>

			<div class="description loop-description">
				<p>
				<?php _e( 'You are browsing the site archives by date.', hybrid_get_textdomain() ); ?>
				</p>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php elseif ( is_post_type_archive() ) : ?>

		<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

		<header class="loop-info">

			<h1 class="title loop-title"><?php post_type_archive_title(); ?></h1>

			<div class="description loop-description">
				<?php if ( !empty( $post_type->description ) ) echo "<p>{$post_type->description}</p>"; ?>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php elseif ( is_archive() ) : ?>

		<header class="loop-info">

			<h1 class="title loop-title"><?php _e( 'Archives', hybrid_get_textdomain() ); ?></h1>

			<div class="description loop-description">
				<p>
				<?php _e( 'You are browsing the site archives.', hybrid_get_textdomain() ); ?>
				</p>
			</div><!-- / .loop-description -->

		</header><!-- / .loop-info -->

	<?php endif; ?>