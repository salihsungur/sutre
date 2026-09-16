<?php
/**
 * Template Name: Sutre Ana Sayfa
 * Template Post Type: page
 * PAKET 21 (Faz 2/5) — Marka Hero ana sayfası.
 * İçerik %100 Türkçe; tipografi/palet design-system.md'den (§3, §4, §7).
 * Kategori bölümü (Giyim → Kadın → Şal) ADR-003 onaylı akış; bot dokunmaz.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header(); // Twenty Twenty-Five header'ı (blok) devralınır.
?>

<main class="sutre-home">
	<section class="sutre-hero" aria-label="Sutre giriş">
		<h1 class="sutre-hero__logo">Sutre</h1>
		<p class="sutre-hero__tagline">Deniz ve dokumanın zarafeti</p>

		<div class="sutre-hero__cta">
			<a class="sutre-hero__cta-button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Koleksiyonu Keşfet</a>
		</div>
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
