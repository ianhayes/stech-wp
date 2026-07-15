<?php
/**
 * Single post (News article). Renders the migrated article content inside the
 * brand chrome: a plain page-header, the article body in a prose column, and a
 * closing CTA. Keeps all 76 migrated news posts on-brand without per-post work.
 *
 * @package STECH
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>

		<section class="page-header page-header--plain">
			<div class="container">
				<div class="page-header__inner">
					<?php stech_breadcrumb(); ?>
					<p class="overline"><?php echo esc_html( get_the_date() ); ?></p>
					<h1 class="page-header__title h1"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="page-header__lede lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<section class="block">
			<div class="container">
				<div class="prose">
					<?php
					if ( has_post_thumbnail() ) {
						echo '<div class="prose__figure">' . get_the_post_thumbnail( get_the_ID(), 'stech-hero' ) . '</div>';
					}
					the_content();

					wp_link_pages( array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Article pages', 'stech' ) . '">',
						'after'  => '</nav>',
					) );
					?>
				</div>
			</div>
		</section>

		<?php
		// Related news: latest 3 in the same category.
		$cats = wp_get_post_categories( get_the_ID() );
		$related = new WP_Query( array(
			'category__in'        => $cats ?: array(),
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
		if ( $related->have_posts() ) :
			?>
			<section class="block news">
				<div class="container">
					<div class="news__head"><div class="news__head-text">
						<span class="overline"><?php esc_html_e( 'More News', 'stech' ); ?></span>
						<h2 class="h2"><?php esc_html_e( 'Keep reading', 'stech' ); ?></h2>
					</div></div>
					<div class="news__grid">
						<?php while ( $related->have_posts() ) : $related->the_post(); ?>
							<article class="news-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="news-card__media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'stech-card' ); ?></a>
								<?php endif; ?>
								<div class="news-card__body">
									<span class="news-card__tag"><?php echo esc_html( get_the_date() ); ?></span>
									<h3><?php the_title(); ?></h3>
									<a class="card-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'stech' ); ?> &rarr;</a>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				</div>
			</section>
			<?php
			wp_reset_postdata();
		endif;
		?>
	</article>
<?php endwhile;

get_footer();
