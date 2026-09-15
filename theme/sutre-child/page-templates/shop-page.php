<?php
/**
 * Template Name: Sutre Mağaza Sayfası
 * Template Post Type: page
 * PAKET 20 — Shop / kategori görünümü, mobil-first 2 sütun grid.
 * WooCommerce verisi (WC_Loop / products şablon hook'ları) üzerinden okunur;
 * kategori ağacı Giyim → Kadın → Şal (ADR-003).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<main class="sutre-shop">
	<section class="sutre-hero sutre-shop__intro">
		<h1 class="sutre-hero__heading">Mağaza</h1>
		<p class="sutre-hero__tagline">Şal &amp; Foulard Koleksiyonu</p>
		<p class="sutre-hero__desc">
			Tüm ürünlerimizi burada bulabilirsiniz. Kategorilere göz atmaktan çekinmeyin.
		</p>
	</section>

	<section class="sutre-cats" aria-label="Kategoriler">
		<h2 class="sutre-section-title">Kategoriler</h2>
		<div class="sutre-cat-grid">
			<?php
			$terms = sutre_child_get_shop_categories();
			if ( empty( $terms ) ) :
				?>
				<p>Kategori hazırlanıyor. Lütfen daha sonra tekrar deneyin.</p>
				<?php
			else :
				foreach ( $terms as $term ) :
					$term_link = get_term_link( $term );
					if ( is_wp_error( $term_link ) ) { continue; }
					?>
					<a class="button-secondary button" href="<?php echo esc_url( $term_link ); ?>">
						<?php echo esc_html( $term->name ); ?>
					</a>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</section>

	<?php
	// WooCommerce çekirdek loop shortcode: sahane çekirdek magasin API'si (§3.1 hook katmanı).
	echo do_shortcode( '[products limit="12" columns="2" orderby="date" order="DESC" paginate="true"]' ); // phpcs:ignore WordPress.Security.EscapeOutput -- do_shortcode çıktısı shortcode içinde escape edilir; veri WC çekirdeğinden gelir.
	?>
</main>

<?php
get_footer();
