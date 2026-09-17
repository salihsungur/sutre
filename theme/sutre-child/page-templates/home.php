<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Template Post Type: page
 *
 * Sutre marka ana sayfası — hero + kategori breadcrumb.
 * Header/footer: block-template-parts/header.html + footer.html (TEK KAYNAK —
 * frontend'te her sayfa bunlardan render edilir; bu şablon da o part'ları include eder).
 * Rollback: bu dosyayı silmek.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Block part'lar saf HTML içerir (WP block yorumları tarayıcıya zararsız);
// Ancak correct yaklaşım: WP block markup'ını render etmek — do_blocks().
$part_dir = get_stylesheet_directory() . '/block-template-parts';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Tick header part'ını WP bloklarıyla doğru render: dosya içeriğini block parser'dan geçir.
$header = file_get_contents( $part_dir . '/header.html' );
echo do_blocks( do_shortcode( $header ) );
?>

<main class="sutre-home">
	<section class="sutre-hero" aria-label="Sutre giriş">
		<h1 class="sutre-hero__logo">Sutre</h1>
		<p class="sutre-hero__tagline">Deniz ve dokumanın zarafeti</p>
		<div class="sutre-hero__cta">
			<a class="sutre-hero__cta-button" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>">Koleksiyonu Keşfet</a>
		</div>
	</section>

	<section class="sutre-cats" aria-label="Kategoriler">
		<h2 class="sutre-section-title">Kategoriler</h2>
		<p class="sutre-cat-breadcrumb">
			<span>Giyim</span><span class="sep" aria-hidden="true">→</span>
			<span>Kadın</span><span class="sep" aria-hidden="true">→</span>
			<span>Şal</span>
		</p>
		<p class="sutre-cat-view"><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>">Tümünü Gör</a></p>
	</section>
</main>

<?php
$footer = file_get_contents( $part_dir . '/footer.html' );
echo do_blocks( do_shortcode( $footer ) );
wp_footer();
?>
</body>
</html>
