<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Sutre v2 — temiz yeniden kurulum (2026-09-19)
 * Tek mimari: klasik PHP. Block theme karışımı YOK.
 * Görseller: /assets/img/ (logo + site görselleri)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<main class="sv-home">

	<section class="sv-hero">
		<img class="sv-hero__bg" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/site/hero-banner-sutre.png' ); ?>" alt="">
		<div class="sv-hero__overlay">
			<img class="sv-hero__lockup" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-hero-lockup.png' ); ?>" alt="Sutre">
			<a class="sv-hero__cta" href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">
				<?php esc_html_e( 'Koleksiyonu Keşfet', 'sutre' ); ?>
			</a>
		</div>
	</section>

	<section class="sv-products">
		<div class="sv-section-head">
			<h2 class="sv-section-title"><?php esc_html_e( 'Ürünler', 'sutre' ); ?></h2>
			<a class="sv-shop-all" href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">
				<?php esc_html_e( 'Tümünü Gör', 'sutre' ); ?> <span aria-hidden="true">→</span>
			</a>
		</div>
		<?php echo do_shortcode( '[products limit="6" columns="3" paginate="false"]' ); ?>
	</section>

	<section class="sv-cats">
		<h2 class="sv-section-title"><?php esc_html_e( 'Kategoriler', 'sutre' ); ?></h2>
		<a class="sv-cat-card" href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">
			<img class="sv-cat-card__bg" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/site/kategori-giyim-kart.png' ); ?>" alt="">
			<span class="sv-cat-card__num" aria-hidden="true">01</span>
			<span class="sv-cat-card__name"><?php esc_html_e( 'Giyim', 'sutre' ); ?></span>
			<span class="sv-cat-card__cta"><?php esc_html_e( 'Keşfet', 'sutre' ); ?> <span aria-hidden="true">→</span></span>
		</a>
	</section>

</main>

<?php get_footer();
