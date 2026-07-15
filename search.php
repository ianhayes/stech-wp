<?php
/**
 * Generic listing fallback (blog, search, archives without a bespoke template).
 *
 * @package STECH
 */
defined( 'ABSPATH' ) || exit;
get_header(); ?>
<div class="container" style="padding-block: var(--section-pad);">
	<header class="section-head">
		<h1 class="h2"><?php echo esc_html( wp_get_document_title() ); ?></h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<div class="news__grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'news-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="news-card__media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'stech-card' ); ?></a>
					<?php endif; ?>
					<div class="news-card__body">
						<h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'stech' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer();
