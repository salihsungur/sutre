<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Template Post Type: page
 *
 * Sutre marka ana sayfası (PAKET 26 — ana sayfa görsel bug düzeltmeleri):
 * satış bandı (Marine/Ink renk şerit) → hero → ÜRÜNLER ([products] +
 * WooCommerce placeholder görsel) → KATEGORİLER (sahibin 2026-09-17 kararı:
 * yalnız "Giyim" tek kart; İpek/Pamuk/Bambu kartları kaldırıldı — kumaş
 * dili yalnız minik alt not satırında) içeriği WooCommerce çekirdek public
 * API'inden gelir; story/values bölümleri YOK (P24 kararı korunur).
 * Header/footer: block-template-parts/header.html + footer.html (TEK KAYNAK
 * — do_blocks() ile render edilir; bu dosyada header/footer içeriği yoktur).
 * Ürün görsel placeholder düzeltmesi (D4): woocommerce_placeholder_img()
 * filtresi — WooCommerce çekirdek placeholder görselini loop'ta garanti eder.
 * Rollback: `git revert <PAKET-26 feat SHA>`.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * D4 — Kırık ürün görselleri: WooCommerce placeholder görsel yolu çekirdek
 * public API'sinden doğru hesaplanır (kodda URL yazılmaz; sabit yol yok).
 * Placeholder img işaretlemesi WooCommerce core placeholder markup'ıyla aynı
 * markup'a zorlanır; `[products]` thumb render'ı bozuksa dahi düzelir.
 */
function sutre_fix_placeholder_img( $html, $size, $dimensions, $placeholder_img_src ) {
	if ( empty( $placeholder_img_src ) ) {
		return '';
	}
	// WooCommerce çekirdeği placeholder'ı img etiketiyle verir; kendi fallback:
	$img = sprintf(
		'<img src="%s" alt="%s" class="woocommerce-placeholder wp-post-image" width="%s" height="%s" loading="lazy" />',
		esc_url( $placeholder_img_src ),
		esc_attr__( 'Ürün görseli yüklenmedi', 'sutre-child' ),
		esc_attr( isset( $dimensions['width'] ) ? $dimensions['width'] : 400 ),
		esc_attr( isset( $dimensions['height'] ) ? $dimensions['height'] : 500 )
	);
	return $img;
}
add_filter( 'woocommerce_placeholder_img', 'sutre_fix_placeholder_img', 10, 4 );


/**
 * Güvenli URL: wc_get_page_permalink / get_permalink bazen dizi döndürebilir
 * (PHP 8.5 strict) — cast + boş fallback; sonra esc_url.
 */
function sutre_safe_url( $value ) {
    $url = is_array( $value ) ? reset( $value ) : $value;
    $url = (string) ( is_object( $url ) ? '' : $url );
    return esc_url( $url );
}
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
			<a class="sutre-hero__cta-button" href="<?php echo sutre_safe_url( wc_get_page_permalink( 'shop' ) ); ?>">Koleksiyonu Keşfet</a>
		</div>
	</section>

	<?php
	/** PAKET 26 — ÜRÜNLER: WooCommerce resmi [products] shortcode'u. D6:
	 * başlık + silk divider aynı flex satırda; "TÜMÜNÜ GÖR →" başlığın
	 * SAĞINDA; başlık gridin ÜSTÜNDE tek satır header yapısı. */
	?>
	<section class="sutre-products" aria-label="Ürünler">
		<div class="sutre-products__header">
			<h2 class="sutre-section-title">Ürünler</h2>
			<a class="sutre-shop-all-link" href="<?php echo sutre_safe_url( wc_get_page_permalink( 'shop' ) ); ?>">Tümünü Gör <span aria-hidden="true">→</span></a>
		</div>
		<?php echo do_shortcode( '[products limit="10" columns="2" paginate="false"]' ); // WooCommerce resmî shortcode — çekirdek dokunuş yok; default tüm görünür ürünler. ?>
	</section>

	<?php
	/** PAKET 26 — KATEGORİLER: sahibin 2026-09-17 KARARI: yalnız
	 * "Giyim" tek kart (01 numaralı büyük hero-benzeri panel, /shop/ link).
	 * İpek/Pamuk/Bambu kartları KALDIRILDI; kumaş dili yalnız minik alt
	 * not satırında tutulur. Koleksiyon linki WooCommerce public API
	 * (wc_get_page_permalink) üzerinden /shop/ sayfasına gider. */
	?>
	<section class="sutre-collection" aria-label="Kategoriler">
		<h2 class="sutre-section-title">Kategoriler</h2>
		<ul class="sutre-collection__grid">
			<li class="sutre-collection__card sutre-collection__card--single">
				<a class="sutre-collection__link" href="<?php echo sutre_safe_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<span class="sutre-collection__number" aria-hidden="true">01</span>
					<span class="sutre-collection__name">Giyim</span>
					<span class="sutre-collection__desc">Sezonun tamamı /shop/ sayfasında</span>
					<span class="sutre-collection__cta">Keşfet <span aria-hidden="true">→</span><span class="screen-reader-text">Giyim ürünlerini keşfet</span></span>
				</a>
			</li>
		</ul>
		<p class="sutre-collection__note">İpek &middot; Pamuk &middot; Bambu kumaşları</p>
	</section>

</main>

<?php
$footer = file_get_contents( $part_dir . '/footer.html' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
echo do_blocks( do_shortcode( $footer ) );
wp_footer();
?>
</body>
</html>
