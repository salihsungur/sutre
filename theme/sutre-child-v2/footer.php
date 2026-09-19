<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div><!-- #sutre-content -->

<footer class="sv-footer">
	<img class="sv-footer__logo" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-footer.png' ); ?>" alt="Sutre">
	<nav class="sv-footer__legal" aria-label="Hukuki">
		<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>">Gizlilik</a>
		<a href="<?php echo esc_url( home_url( '/mesafeli-satis-sozlesmesi/' ) ); ?>">Mesafeli Satış</a>
		<a href="<?php echo esc_url( home_url( '/iade-ve-cayma/' ) ); ?>">İade &amp; Cayma</a>
	</nav>
	<p class="sv-footer__copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sutre &mdash; Tüm hakları saklıdır</p>
</footer>

<?php wp_footer(); ?>
</body>
</html>
