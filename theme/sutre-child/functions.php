<?php
/**
 * Sutre Child Theme — functions.php
 *
 * PAKET 20 (Faz 2/4). Anayasa §3.1: iş mantığı BURAYA yazılmaz (project-core plugin'e aittir);
 * yalnızca görünsel/theme-runtime enqueue ve WooCommerce şablon hook bağlamı içerir.
 * Tüm çıktılar context'e göre escape edilir; girdi yok (salt enqueue + güvenli hook çıktısı).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SUTRE_CHILD_VERSION', '1.0.0' );

/**
 * Asset dosya versiyonu: dosya mtime'ından üretilir (cache-safe).
 *
 * @param string $rel Dosyanın tema köküne göre yolu.
 * @return string
 */
function sutre_child_asset_version( $rel ) {
	$path = get_stylesheet_directory() . '/' . ltrim( $rel, '/' );
	return file_exists( $path ) && is_readable( $path )
		? (string) filemtime( $path )
		: SUTRE_CHILD_VERSION;
}

/**
 * Stil ve font kaynakları. Cormorant Garamond + Jost (Google Fonts CDN, design-system.md §4).
 * Yalnızca ön yüzda yüklenir; admin/checkout'ta checkout işlevine dokunulmaz.
 */
function sutre_child_enqueue_assets() {
	$theme_uri = get_stylesheet_directory_uri();

	// Google Fonts preconnect + stylesheet (kit §4 CDN linki).
	add_action( 'wp_head', function () {
		?>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
		<?php
	}, 5 );

	wp_enqueue_style(
		'sutre-child-fonts',
		$theme_uri . '/fonts.css',
		array(),
		sutre_child_asset_version( 'fonts.css' )
	);
	wp_enqueue_style(
		'sutre-child-style',
		$theme_uri . '/style.css',
		array(),
		sutre_child_asset_version( 'style.css' )
	);
	wp_enqueue_style(
		'sutre-child-main',
		$theme_uri . '/main.css',
		array( 'sutre-child-style', 'sutre-child-fonts' ),
		sutre_child_asset_version( 'main.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'sutre_child_enqueue_assets' );

/**
 * Tema desteği. Editör + blok şablonları aktif; checkout.html blok şablonu
 * (block-templates/) yalnızca WooCommerce checkout wrapper'ını devralır;
 * P17 kuralı: Checkout sayfasındaki Login block SAHİBİN sayfa içeriğinde kalır,
 * bu tema onu kaldırmaz, değiştirmez veya gizlemez.
 */
function sutre_child_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	// WooCommerce görsel standardı (mobil-first, anayasa §9).
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 350,
		'single_image_width'    => 800,
		'product_grid'          => array(
			'default_columns' => 2,   // mobil: 2 sütun
			'default_rows'    => 6,
			'min_columns'     => 2,
			'max_columns'     => 4,   // desktop: en fazla 4
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'sutre_child_theme_support' );

/**
 * Üçüncü adım breadcrumb'ı: Giyim → Kadın → Şal akışı Woo çekirdeği
 * (WooCommerce breadcrumb + ADR-003 kategori ağacı) aracılığıyla render edilir;
 * tema yalnız stil uygular, kategori mantığı koymaz.
 */

/**
 * HEADER taklidi yok: Twenty Twenty-Five blok temasının header/footer/template_parts
 * yapısı korunur (anayasa §3.1 — child mantle minimal, override yerine devralma).
 * Footer metni %100 Türkçe; hook üzerinden filter ile düzeltilir, şablon ezilmez.
 */

/**
 * Checkout'ta "There are no payment methods available" uyarısı (P17 placeholder)
 * bilgilendirici Türkçe metne çevrilir — itibar/örgü yok, PayTR Faz 3.
 */
function sutre_child_no_payment_methods_notice( $notice ) {
	return __( 'Ödeme yöntemi hazırlanıyor. PayTR entegrasyonu çok yakında aktif olacaktır. Sorularınız için bize ulaşabilirsiniz.', 'sutre-child' );
}
add_filter( 'woocommerce_no_available_payment_methods_message', 'sutre_child_no_payment_methods_notice' );

/**
 * Sepete ekleme butonunda 44px dokunma hedefi CSS sınıfı (§9 erişilebilirlik).
 * (Styling main.css'te; burada yalnız sınıf eklenir.)
 */
function sutre_child_add_to_cart_button_class( $html ) {
	return str_replace( 'class="', 'class="sutre-touch-target ', $html );
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'sutre_child_add_to_cart_button_class', 10, 1 );

/**
 * Collection/kategori şablonunun yüklenmesi (page-templates/shop-page.php için).
 */
function sutre_child_get_shop_categories() {
	// Yalnızca terimleri okur (read-only proto);
	// kategori ağacı: Giyim (root) → Kadın → Şal (ADR-003).
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	return $terms;
}
