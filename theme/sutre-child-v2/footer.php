<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div><!-- #sutre-content -->

<footer class="sv-footer" role="contentinfo">
	<div class="sv-footer__grid">

		<div class="sv-footer__brand">
			<img class="sv-footer__logo" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-footer.png' ); ?>" alt="Sutre">
			<p class="sv-footer__desc"><?php esc_html_e( 'El dokuması ipek, pamuk ve bambu şallar. İstanbul.', 'sutre' ); ?></p>
		</div>

		<div class="sv-footer__col">
			<h3 class="sv-footer__heading"><?php esc_html_e( 'Alışveriş', 'sutre' ); ?></h3>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>"><?php esc_html_e( 'Tüm Koleksiyon', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Jakarlı Şallar', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Pamuk Şallar', 'sutre' ); ?></a>
		</div>

		<div class="sv-footer__col">
			<h3 class="sv-footer__heading"><?php esc_html_e( 'Yardım', 'sutre' ); ?></h3>
			<a href="<?php echo esc_url( home_url( '/mesafeli-satis-sozlesmesi/' ) ); ?>"><?php esc_html_e( 'Mesafeli Satış Sözleşmesi', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/iade-ve-cayma/' ) ); ?>"><?php esc_html_e( 'İade &amp; Cayma', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>"><?php esc_html_e( 'Gizlilik Politikası', 'sutre' ); ?></a>
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
