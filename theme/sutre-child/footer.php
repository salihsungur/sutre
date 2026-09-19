<?php
/**
 * Sutre Child — footer.php (klasik PHP, TEK KAYNAK)
 * P29: TÜM sayfa tipleri bu footer'ı kullanır.
 * Sıra: logo → nav linkler → © satırı (sahibin kararı).
 * Rollback: git revert.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div><!-- #sutre-content -->

<footer class="sutre-footer" role="contentinfo">
	<div class="sutre-footer__inner">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-footer.png' ); ?>" alt="Sutre" class="sutre-footer__logo-img">

		<nav class="sutre-footer__legal" aria-label="<?php esc_attr_e( 'Hukuki', 'sutre' ); ?>">
			<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>"><?php esc_html_e( 'Gizlilik', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/mesafeli-satis-sozlesmesi/' ) ); ?>"><?php esc_html_e( 'Mesafeli Satış', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/iade-ve-cayma/' ) ); ?>"><?php esc_html_e( 'İade &amp; Cayma', 'sutre' ); ?></a>
		</nav>

		<p class="sutre-footer__copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sutre &mdash; <?php esc_html_e( 'Tüm hakları saklıdır', 'sutre' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
