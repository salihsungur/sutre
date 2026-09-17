<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Template Post Type: page
 *
 * Sutre marka ana sayfası (PAKET 24 — %100 satış odaklı):
 * hero → koleksiyon kartları → koleksiyon (öne çıkan ürünler).
 * story ("Hikâyemiz") ve values ("Neden Sutre?") bölümleri sahibin
 * kararıyla KALDIRILDI (P23'ten kısmi geri alma).
 * Header/footer: block-template-parts/header.html + footer.html (TEK KAYNAK —
 * frontend'te her sayfa bunlardan render edilir; bu şablon da o part'ları
 * do_blocks() ile render ederek include eder).
 * Koleksiyon kartları: sabit tanım (İpek / Pamuk / Bambu) — sahibin talebi;
 * /shop/ ana mağaza linkine bağlanır. Öne çıkan ürünler WooCommerce resmi
 * [products] shortcode'u ile çekilir (public API; çekirdek değişikliği yok).
 * Rollback: bu dosyayı P22b sürümüne döndürmek (git).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Block part'lar WP block markup'ı içerir; doğru render için do_blocks().
$part_dir          = get_stylesheet_directory() . '/block-template-parts';
$shop_url          = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
// Koleksiyon kartları: sabit tanım (sahip talebi). Slugılara bağlanmaz; hepsi /shop/ main mağaza görünümüne gider.
$collections = array(
	array( 'no' => '01', 'name' => 'İpek',  'desc' => 'Yumuşak dokunuş, hafif parlaklık' ),
	array( 'no' => '02', 'name' => 'Pamuk', 'desc' => 'Günlük zarafet, nefes alan doku' ),
	array( 'no' => '03', 'name' => 'Bambu', 'desc' => 'Doğal elyaf, modern konfor' ),
);
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
// Header part'ını WP bloklarıyla doğru render: dosya içeriğini block parser'dan geçir.
$header = file_get_contents( $part_dir . '/header.html' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
echo do_blocks( do_shortcode( $header ) );
?>

<main class="sutre-home">

	<section class="sutre-hero" aria-label="Sutre giriş">
		<h1 class="sutre-hero__logo">Sutre</h1>
		<p class="sutre-hero__tagline">Deniz ve dokumanın zarafeti</p>
		<div class="sutre-hero__cta">
			<a class="sutre-hero__cta-button" href="<?php echo esc_url( $shop_url ); ?>">Koleksiyonu Keşfet</a>
		</div>
	</section>

	<p class="sutre-home__intro">Sutre şal koleksiyonu — el dokuması ipek, pamuk ve bambu şallar.</p>

	<section class="sutre-collection" aria-label="Koleksiyon">
		<h2 class="sutre-section-title">Koleksiyon</h2>
		<ul class="sutre-collection__grid">
			<?php foreach ( $collections as $c ) : ?>
			<li class="sutre-collection__card">
				<a class="sutre-collection__link" href="<?php echo esc_url( $shop_url ); ?>">
					<span class="sutre-collection__no" aria-hidden="true"><?php echo esc_html( $c['no'] ); ?></span>
					<span class="sutre-collection__name"><?php echo esc_html( $c['name'] ); ?></span>
					<span class="sutre-collection__desc"><?php echo esc_html( $c['desc'] ); ?></span>
					<span class="sutre-collection__cta">Keşfet <span aria-hidden="true">→</span><span class="screen-reader-text"><?php echo esc_html( $c['name'] ); ?> koleksiyonu</span></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<p class="sutre-collection__all">
			<a href="<?php echo esc_url( $shop_url ); ?>">Tümünü Gör</a>
		</p>
	</section>

	<section class="sutre-featured" aria-label="Öne çıkan ürünler">
		<h2 class="sutre-section-title">Koleksiyon</h2>
		<div class="sutre-featured__grid">
			<?php echo do_shortcode( '[products limit="2" columns="2" visibility="featured" class="sutre-featured-products"]' ); // WooCommerce resmi shortcode'u — çekirdek dokunuş yok. ?>
		</div>
	</section>

</main>

<?php
$footer = file_get_contents( $part_dir . '/footer.html' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
echo do_blocks( do_shortcode( $footer ) );
wp_footer();
?>
</body>
</html>
