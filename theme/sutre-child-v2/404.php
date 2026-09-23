<?php
/**
 * 404 — "sayfa bulunamadı" şablonu (P83).
 *
 * Kök neden (pack-81 kanıtı): temada 404.php YOKTU; index.php have_posts() false
 * iken hiçbir şey basmıyordu → #sutre-content tamamen boş kalıyordu.
 * Klasik mimari korunur: get_header()/get_footer() + tek CSS (style.css .sv-404*).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<main class="sv-404">
	<div class="sv-404__inner">
		<p class="sv-404__code">404</p>
		<h1 class="sv-404__title"><?php esc_html_e( 'Aradığınız sayfayı bulamadık', 'sutre' ); ?></h1>
		<p class="sv-404__text"><?php esc_html_e( 'Bağlantı taşınmış veya adres yanlış yazılmış olabilir.', 'sutre' ); ?></p>

		<form class="sv-404__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="sv-404__label" for="sv-404-search"><?php esc_html_e( 'Sitede ara', 'sutre' ); ?></label>
			<input class="sv-404__input" type="search" id="sv-404-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Ürün veya sayfa ara', 'sutre' ); ?>">
			<button class="sv-404__submit" type="submit"><?php esc_html_e( 'Ara', 'sutre' ); ?></button>
		</form>

		<div class="sv-404__actions">
			<a class="sv-404__btn sv-404__btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Ana sayfaya dön', 'sutre' ); ?></a>
			<a class="sv-404__btn sv-404__btn--ghost" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Mağazaya git', 'sutre' ); ?></a>
		</div>
	</div>

	<?php
	/* Öne çıkan ürünler (maks 3): mevcut WooCommerce sorguları kullanılır,
	   kart tasarımı global ul.products'tan gelir. Sorgu sonrası wp_reset_postdata() şart. */
	?>
	<?php if ( function_exists( 'wc_get_products' ) ) : ?>
		<?php
		$sv404_ids = wc_get_products(
			array(
				'status'     => 'publish',
				'limit'      => 3,
				'featured'   => true,
				'visibility' => 'catalog',
				'return'     => 'ids',
				'orderby'    => 'date',
				'order'      => 'DESC',
			)
		);
		if ( empty( $sv404_ids ) ) {
			/* Öne çıkan işaretli ürün yoksa: mağazada görünür en yeni ürünler. */
			$sv404_ids = wc_get_products(
				array(
					'status'     => 'publish',
					'limit'      => 3,
					'visibility' => 'catalog',
					'return'     => 'ids',
					'orderby'    => 'date',
					'order'      => 'DESC',
				)
			);
		}

		$sv404_query = $sv404_ids ? new WP_Query(
			array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'post__in'            => $sv404_ids,
				'orderby'             => 'post__in',
				'posts_per_page'      => 3,
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			)
		) : null;

		if ( $sv404_query && $sv404_query->have_posts() ) :
			?>
			<section class="sv-404__featured" aria-labelledby="sv-404-featured-title">
				<h2 class="sv-404__featured-title" id="sv-404-featured-title"><?php esc_html_e( 'Öne Çıkanlar', 'sutre' ); ?></h2>
				<?php
				woocommerce_product_loop_start();
				while ( $sv404_query->have_posts() ) :
					$sv404_query->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				woocommerce_product_loop_end();
				?>
			</section>
			<?php
		endif;
		wp_reset_postdata();
		?>
	<?php endif; ?>
</main>

<?php
get_footer();