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

<?php /* P67: çerez bildirim bandı — zorunlu olmayan çerez eklenince Reddet/Tercihler genişletilir */ ?>
<div class="sv-cookie-band" id="sv-cookie-band" role="region" aria-label="Çerez bildirimi" hidden>
	<p class="sv-cookie-band__text">
		Sitemizde alışveriş oturumunun çalışması için zorunlu çerezler kullanılmaktadır. Detaylı bilgi için
		<a href="<?php echo esc_url( home_url( '/gizlilik-politikasi/' ) ); ?>">Gizlilik Politikamıza</a> göz atabilirsiniz.
	</p>
	<button type="button" class="sv-cookie-band__accept" id="sv-cookie-accept">Tamam, Anladım</button>
</div>
<script>
(function () {
	'use strict';
	var band = document.getElementById('sv-cookie-band');
	if (!band) { return; }
	var m = document.cookie.match(/(?:^|;\s*)sv_cookie_consent=1(?:;|$)/);
	if (!m) { band.hidden = false; }
	document.getElementById('sv-cookie-accept').addEventListener('click', function () {
		var d = new Date();
		d.setTime(d.getTime() + 180 * 24 * 60 * 60 * 1000);
		document.cookie = 'sv_cookie_consent=1; expires=' + d.toUTCString() + '; path=/; SameSite=Lax';
		band.hidden = true;
	});
})();
</script>
</body>
</html>
<script>
(function () {
	'use strict';
	var burger = document.getElementById('sv-burger');
	var drawer = document.getElementById('sv-drawer');
	var scrim  = document.getElementById('sv-drawer-scrim');
	var closeB = document.getElementById('sv-drawer-close');
	if (!burger || !drawer || !scrim) { return; }
	function openDrawer() {
		document.body.classList.add('sv-drawer-open');
		burger.setAttribute('aria-expanded', 'true');
		drawer.hidden = false; scrim.hidden = false;
		requestAnimationFrame(function () { drawer.classList.add('is-open'); scrim.classList.add('is-visible'); });
	}
	function closeDrawer() {
		document.body.classList.remove('sv-drawer-open');
		burger.setAttribute('aria-expanded', 'false');
		drawer.classList.remove('is-open'); scrim.classList.remove('is-visible');
		window.setTimeout(function () { drawer.hidden = true; scrim.hidden = true; }, 340);
	}
	burger.addEventListener('click', openDrawer);
	closeB.addEventListener('click', closeDrawer);
	scrim.addEventListener('click', closeDrawer);
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && document.body.classList.contains('sv-drawer-open')) { closeDrawer(); }
	});
	drawer.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeDrawer); });
})();
</script>
