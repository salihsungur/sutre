<?php
/**
 * Sutre v3 — functions.php (quiet luxury tasarım sistemi)
 * Asset enqueue + WooCommerce (block template kapalı, klasik şablonlar, wrapper, sidebar yok) + shop banner + scroll reveal.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SUTRE_VERSION', '3.2.0' );

/* ── Asset enqueue ── */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'sutre-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500&display=swap',
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

/* ── WooCommerce tema desteği (v1 beyanı restore) — klasik 'desteklenen tema' yolu sabit ── */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 350,
		'single_image_width'    => 800,
		'product_grid'          => array(
			'default_columns' => 3,   /* v3 grid: desktop 3 sütun (style.css ile tutarlı) */
			'default_rows'    => 6,
			'min_columns'     => 2,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}, 20 );

/* ── Block template'ler KAPALI (G1) ──
 * TT5 parent FSE; bu tema klasik PHP mimarisi. Not: Woo'nun woocommerce_has_block_template
 * filtresi yalnızca WP_Block_Templates_Registry::is_registered() sonucunu filtreler
 * (class-wc-template-loader.php L132-148) — WP çekirdeğinin locate_block_template()
 * kapısına dokunmaz; eski filtre bu yüzden işlevsizdi. Doğru kapı theme support kaldırmaktır. */
add_action( 'after_setup_theme', function () {
	remove_theme_support( 'block-templates' );
}, 20 );

/* ── WooCommerce: sidebar YOK + content wrapper override (G3) ──
 * Şablon dosyaları verbatim kalır (§3.1); hook katmanında: sidebar kalkar, Woo'nun
 * default <main id="main"> wrapper'ı yerine .woocommerce container gelir
 * (G5'teki #sutre-content .woocommerce padding kuralının hedefi). */
add_action( 'init', function () {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
} );
add_action( 'woocommerce_before_main_content', function () {
	echo '<div class="woocommerce">';
}, 10 );
add_action( 'woocommerce_after_main_content', function () {
	echo '</div>';
}, 10 );

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
}, 5 );

/* ── P39: kart/arşiv görselleri keskin — 350px thumbnail yerine 'large' (1024px).
 * 350px'lik woocommerce_thumbnail 504px kartı doldurmak için büyütülüyordu → bulanıklık.
 * 'large' tüm mevcut yüklemelerde zaten üretilmiş durumda → yeniden boyutlandırma GEREKMEZ. ── */
add_filter( 'single_product_archive_thumbnail_size', function () { return 'large'; } );

/* ── P40: Woo gizlilik metni Türkçe — RESMÎ filtre kapısı (Woo 11.1: metin option'dan gelir,
 * 'woocommerce_registration_privacy_policy_text' bir filtre DEĞİL, option adı; doğrusu
 * woocommerce_get_privacy_policy_text). [privacy_policy] placeholder'ı Woo link ile değiştirir. ── */
add_filter( 'woocommerce_get_privacy_policy_text', function ( $text, $type ) {
	if ( 'registration' === $type ) {
		return 'Kişisel verileriniz, hesap deneyiminizi desteklemek, hesabınıza erişimi yönetmek ve [privacy_policy] metninde açıklanan diğer amaçlar doğrultusunda kullanılır.';
	}
	return $text;
}, 10, 2 );

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
