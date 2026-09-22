<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div><!-- #sutre-content -->

<footer class="sv-footer" role="contentinfo">
	<div class="sv-footer__grid">

		<div class="sv-footer__brand">
			<img class="sv-footer__logo" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-footer.png' ); ?>" alt="Sutre">
		</div>

		<div class="sv-footer__col">
			<h3 class="sv-footer__heading"><?php esc_html_e( 'Alışveriş', 'sutre' ); ?></h3>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>"><?php esc_html_e( 'Tüm Koleksiyon', 'sutre' ); ?></a>
			<?php
			// P51: ürün linkleri Woo'dan dinamik çekilir — ürün adı değişirse footer değişir,
			// ürün kaldırılırsa link kaybolur. Slug'lar kararlı kimlik olarak kullanılır.
			foreach ( array( 'jakarli-sal', 'iman-nour-sal' ) as $sv_footer_slug ) {
				$sv_footer_prod = get_page_by_path( $sv_footer_slug, OBJECT, 'product' );
				if ( $sv_footer_prod && 'publish' === get_post_status( $sv_footer_prod ) ) {
					echo '<a href="' . esc_url( get_permalink( $sv_footer_prod ) ) . '">' . esc_html( get_the_title( $sv_footer_prod ) ) . '</a>';
				}
			}
			?>
		</div>

		<div class="sv-footer__col">
			<h3 class="sv-footer__heading"><?php esc_html_e( 'Kurumsal', 'sutre' ); ?></h3>
			<a href="<?php echo esc_url( home_url( '/hakkimizda/' ) ); ?>">Hakkımızda</a>
			<a href="<?php echo esc_url( home_url( '/iletisim/' ) ); ?>">İletişim</a>
			<a href="<?php echo esc_url( home_url( '/mesafeli-satis-sozlesmesi/' ) ); ?>">Mesafeli Satış Sözleşmesi</a>
			<a href="<?php echo esc_url( home_url( '/iade-ve-cayma/' ) ); ?>">İade ve Cayma</a>
			<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>">Gizlilik Politikası</a>
			<a href="<?php echo esc_url( home_url( '/kullanim-kosullari/' ) ); ?>">Kullanım Koşulları</a>
			<a href="<?php echo esc_url( home_url( '/on-bilgilendirme-formu/' ) ); ?>">Ön Bilgilendirme Formu</a>
			<a href="<?php echo esc_url( home_url( '/ticari-elektronik-ileti/' ) ); ?>">Ticari Elektronik İleti Açık Rıza Metni</a>
		</div>

		<div class="sv-footer__col">
			<h3 class="sv-footer__heading"><?php esc_html_e( 'Hesabım', 'sutre' ); ?></h3>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/') ); ?>"><?php esc_html_e( 'Giriş / Kayıt', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/') ); ?>"><?php esc_html_e( 'Siparişlerim', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('cart') : home_url('/cart/') ); ?>"><?php esc_html_e( 'Sepetim', 'sutre' ); ?></a>
		</div>

	</div>

	<div class="sv-footer__bottom">
		<p class="sv-footer__copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sutre &mdash; <?php esc_html_e( 'Tüm hakları saklıdır', 'sutre' ); ?></p>
		<p class="sv-footer__origin"><?php esc_html_e( 'İstanbul', 'sutre' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
