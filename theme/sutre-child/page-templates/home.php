<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Template Post Type: page
 * PAKET 20 — Mobil-first ana sayfa: hero + kategori girişleri.
 * İçerik %100 Türkçe; logo/tagline design-system.md'den.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header(); // Twenty Twenty-Five header'ı (blok) devralınır.
?>

<main class="sutre-home">
	<section class="sutre-hero" aria-label="Giriş">
		<h1 class="sutre-hero__logo">SUTRE</h1>
		<p class="sutre-hero__tagline">Şal &amp; Foulard</p>
		<h2 class="sutre-hero__heading">Deniz ve dokumanın zarafeti</h2>
		<p class="sutre-hero__desc">
			Elle dokunan şallar; kumaş denizden, tasarım zarafetten doğar.
			Sezonluk koleksiyon mağazamızda sizi bekliyor.
		</p>
		<p>
			<a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Koleksiyonu Keşfet</a>
		</p>
	</section>

	<section class="sutre-cats" aria-label="Kategoriler">
		<h2 class="sutre-section-title">Kategoriler</h2>
		<table class="sutre-cat-table">
			<tbody>
				<tr>
					<td class="sutre-cat-breadcrumb">
						<span>Giyim</span><span class="sep" aria-hidden="true">→</span>
						<span>Kadın</span><span class="sep" aria-hidden="true">→</span>
						<span>Şal</span>
					</td>
					<td><a class="button-secondary button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Tümünü Gör</a></td>
				</tr>
			</tbody>
		</table>
	</section>
</main>

<?php
get_footer();
