<?php
/**
 * Sutre Child — footer.php (klasik PHP)
 * PAKET 22c: TT5 block-theme footer.php YOK → get_footer() WP theme-compat
 * fallback'e (Kubrick) düşüyor → "WordPress ile gururla sunuluyor" çıkıyordu.
 * Bu dosya onu durdurur; Sutre footer'ı sağlar.
 * Rollback: bu dosyayı silmek.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div><!-- #sutre-content -->

<footer id="colophon" class="sutre-footer" role="contentinfo">
	<div class="sutre-footer__inner">
		<p class="sutre-footer__brand">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sutre &mdash; <?php esc_html_e( 'Tüm hakları saklıdır', 'sutre' ); ?></p>
		<nav class="sutre-footer__legal" aria-label="<?php esc_attr_e( 'Hukuki', 'sutre' ); ?>">
			<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>"><?php esc_html_e( 'Gizlilik', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/mesafeli-satis-sozlesmesi/' ) ); ?>"><?php esc_html_e( 'Mesafeli Satış', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/iade-ve-cayma/' ) ); ?>"><?php esc_html_e( 'İade &amp; Cayma', 'sutre' ); ?></a>
		</nav>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
