<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div><!-- #sutre-content -->

<footer class="sv-footer">
	<div class="sv-footer__inner">
		<div class="sv-footer__top">
			<a class="sv-footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img class="sv-footer__logo" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-footer.png' ); ?>" alt="Sutre">
			</a>
			<nav class="sv-footer__legal" aria-label="Yasal">
				<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>"><?php esc_html_e( 'Gizlilik Politikası', 'sutre' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/mesafeli-satis-sozlesmesi/' ) ); ?>"><?php esc_html_e( 'Mesafeli Satış', 'sutre' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/iade-ve-cayma/' ) ); ?>"><?php esc_html_e( 'İade & Cayma', 'sutre' ); ?></a>
			</nav>
		</div>
		<p class="sv-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sutre &mdash; <?php esc_html_e( 'Tüm hakları saklıdır', 'sutre' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
