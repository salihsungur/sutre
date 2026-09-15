<?php
/**
 * Template Name: Sutre Ürün Detay
 * Template Post Type: page
 * PAKET 20 — Ürün detay görünümü (sayfa; gerçek ürün detay WooCommerce şablonu
 * single-product.php akışını kullanır, tema yalnız stil ve çözüm sağlar).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<main class="sutre-product-detail">
	<section class="sutre-hero sutre-product__intro">
		<h1 class="sutre-hero__heading">Ürün Detayı</h1>
		<p class="sutre-hero__tagline">Kumaş · Dokuma · Zarafet</p>
	</section>

	<?php
	// Ürün detay, WooCommerce çekirdek akışından okunur. AÇIKLAMA:
	// - "breadcrumb" Giyim → Kadın → Şal trendi Woo çekirdeğinden (ADR-003 kategorileri);
	// - "sepete ekle" butonunda main.css'te .sutre-touch-target 44px dokunma hedefi.
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content(); // WooCommerce "shop page" veya ürün shortcode içeriği.
		endwhile;
	endif;
	?>
</main>

<?php
get_footer();
