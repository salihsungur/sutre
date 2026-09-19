<?php
/**
 * Sutre v3 — functions.php (quiet luxury tasarım sistemi)
 * Asset enqueue + WooCommerce klasik şablon/arka plan ayarları + shop banner + scroll reveal.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SUTRE_VERSION', '3.0.0' );

/* ── Asset enqueue ── */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'sutre-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Jost:wght@300;400;500&display=swap',
		[],
		null
	);
	wp_enqueue_style( 'sutre-style', get_stylesheet_uri(), [ 'sutre-fonts' ], SUTRE_VERSION );
} );

/* ── Font preconnect (performans) ── */
add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = [ 'href' => 'https://fonts.googleapis.com' ];
		$urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' ];
	}
	return $urls;
}, 10, 2 );

/* ── WooCommerce: block template'leri kapalı — klasik şablonlar ── */
add_filter( 'woocommerce_has_block_template', '__return_false', 999 );

/* ── WooCommerce: sidebar YOK (shop archive) ── */
add_action( 'init', function () {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
} );

/* ── WooCommerce: shop sayfa başlığı kaldır (banner'daki "Koleksiyon" h1 yerine geçer) ── */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/* ── Shop banner: mağaza/kategori arşivlerinde tam genişlik görsel başlık ── */
add_action( 'woocommerce_before_main_content', function () {
	if ( ! function_exists( 'is_woocommerce' ) ) { return; }
	if ( ! ( is_shop() || is_product_taxonomy() ) ) { return; }
	$img = get_stylesheet_directory_uri() . '/assets/img/site/shop-banner-sutre.png';
	?>
	<section class="sv-shop-banner" role="banner" style="background-image:url('<?php echo esc_url( $img ); ?>')">
		<div class="sv-shop-banner__inner">
			<h1 class="sv-shop-banner__title"><?php esc_html_e( 'Koleksiyon', 'sutre' ); ?></h1>
			<p class="sv-shop-banner__sub"><?php esc_html_e( 'El dokuması şallar', 'sutre' ); ?></p>
		</div>
	</section>
	<?php
}, 20 );

/* ── Placeholder görsel: Woo core'dan ── */
add_filter( 'woocommerce_placeholder_img', function ( $html, $size, $dimensions, $src ) {
	$src = is_array( $src ) ? (string) reset( $src ) : (string) $src;
	if ( empty( $src ) ) { return ''; }
	return sprintf(
		'<img src="%s" alt="%s" class="woocommerce-placeholder wp-post-image" width="%s" height="%s" loading="lazy" />',
		esc_url( $src ),
		esc_attr__( 'Ürün görseli', 'sutre' ),
		esc_attr( isset( $dimensions['width'] ) ? $dimensions['width'] : 400 ),
		esc_attr( isset( $dimensions['height'] ) ? $dimensions['height'] : 500 )
	);
}, 10, 4 );

/* ── Scroll reveal: tek seferlik, IntersectionObserver, ~0.4KB ── */
add_action( 'wp_print_footer_scripts', function () {
	?>
	<script>
	(function () {
		'use strict';
		var els = document.querySelectorAll('.sv-reveal');
		if (!els.length) { return; }
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduce || !('IntersectionObserver' in window)) {
			els.forEach(function (el) { el.classList.add('is-visible'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) {
					e.target.classList.add('is-visible');
					io.unobserve(e.target);
				}
			});
		}, { threshold: 0.12 });
		els.forEach(function (el) { io.observe(el); });
	})();
	</script>
	<?php
} );
