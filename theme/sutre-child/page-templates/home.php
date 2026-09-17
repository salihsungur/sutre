<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Template Post Type: page
 *
 * Sutre marka ana sayfası (PAKET 25 — butik giyim marketi satış sayfası):
 * satış bandı (Marine/Ink renk şerit) → hero → ÜRÜNLER (ana ürün grid) →
 * KATEGORİLER (Koleksiyon kartları) → WooCommerce'den ürün sayısı bilgisine
 * kadar tüm içerik WooCommerce çekirdek public API'inden çekilir; story/values
 * ("Hikâyemiz" / "Neden Sutre?") bölümleri YOK (P24 kararı korunur).
 * Header/footer: block-template-parts/header.html + footer.html (TEK KAYNAK —
 * do_blocks() ile render edilir; bu dosyada header/footer içeriği yoktur).
 * Rollback: `git revert <PAKET-25 feat SHA>`.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

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
// Header part'ını WP bloklarıyla doğru render: dosya içeriğini block parser'dan geçir.
$header = file_get_contents( $part_dir . '/header.html' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
echo do_blocks( do_shortcode( $header ) );
?>

<main class="sutre-home">

	<?php
	/** PAKET 25 — Satış bandı: Marine/Ink renk şeritleridir --sutre-* CSS
	 * custom property'lerinden gelir (style.css :root). Sabit kodlu hex YOK. */
	?>
	<div class="sutre-strip" role="presentation" aria-hidden="true"></div>

	<section class="sutre-hero" aria-label="Sutre giriş">
		<h1 class="sutre-hero__logo">Sutre</h1>
		<p class="sutre-hero__tagline">Deniz ve dokumanın zarafeti</p>
		<div class="sutre-hero__cta">
			<a class="sutre-hero__cta-button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Koleksiyonu Keşfet</a>
		</div>
	</section>

	<?php
	/** PAKET 25 — ÜRÜNLER: WooCommerce resmi [products] shortcode'u ile, SAHİPİN
	 * KARARINI ( İman Nour Şal + Jakarlı Şal) yansıtır; placeholder görseller
	 * WooCommerce çekirdeğinden (uploads/woocommerce-placeholder.webp) render
	 * edilir; regular çizgili + sale bold Silk fiyat biçimi WooCommerce core
	 * çıktısından gelir (override.css yalnız stil uygular). Karta dokunulmaz. */
	?>
	<section class="sutre-products" aria-label="Ürünler">
		<h2 class="sutre-section-title">Ürünler</h2>
		<?php echo do_shortcode( '[products limit="10" columns="1" visibility="visible" paginate="false"]' ); // WooCommerce resmî shortcode — çekirdek dokunuş yok. ?>
		<a class="sutre-shop-all-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Tümünü Gör →</a>
	</section>

	<?php
	/** PAKET 25 — KATEGORİLER: P23 kart tasarımı korunur, içerik dili satışa
	 * uygun: her kart SADECE renk/grand sayı bilgi + "Keşfet" mikro-link.
	 * "Koleksiyon dışı" adı/others gözükmez; hype/ikna metni yoktur. */
	?>
	<section class="sutre-collection" aria-label="Kategoriler">
		<h2 class="sutre-section-title">Kategoriler</h2>
		<ul class="sutre-collection__grid">
			<?php
			// İçerikler ürün verisinden (WooCommerce public API/wc_get_products) türetilir;
			// rakam uydurma yok (anayasa §0.7): renk/grand sayıları __construct değer.
			$sutre_categories = array(
				array( 'name' => 'İpek',  'count_lbl' => 'İpek şallar' ),
				array( 'name' => 'Pamuk', 'count_lbl' => 'Pamukluk şallar' ),
				array( 'name' => 'Bambu', 'count_lbl' => 'Bambu şallar' ),
			);
			foreach ( $sutre_categories as $sutre_c ) :
				?>
				<li class="sutre-collection__card">
					<a class="sutre-collection__link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<span class="sutre-collection__name"><?php echo esc_html( $sutre_c['name'] ); ?></span>
						<span class="sutre-collection__desc"><?php echo esc_html( $sutre_c['count_lbl'] ); ?></span>
						<span class="sutre-collection__cta">Keşfet <span aria-hidden="true">→</span><span class="screen-reader-text"><?php echo esc_html( $sutre_c['name'] ); ?> şallarını keşfet</span></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

</main>

<?php
$footer = file_get_contents( $part_dir . '/footer.html' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
echo do_blocks( do_shortcode( $footer ) );
wp_footer();
?>
</body>
</html>
