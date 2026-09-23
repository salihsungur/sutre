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
		<?php /* P76: kabul edilen ödeme yöntemi logoları (Visa · Mastercard · TROY · PayTR) — inline SVG, harici istek yok. */ ?>
		<div class="sv-footer__payments" role="group" aria-label="<?php esc_attr_e( 'Kabul edilen ödeme yöntemleri', 'sutre' ); ?>">
			<span class="sv-footer__pay"><svg role="img" aria-label="Visa" viewBox="0 0 1000 324.68" xmlns="http://www.w3.org/2000/svg">
				<path fill="#1434cb" d="m651.19.5c-70.93,0-134.32,36.77-134.32,104.69,0,77.9,112.42,83.28,112.42,122.42,0,16.48-18.88,31.23-51.14,31.23-45.77,0-79.98-20.61-79.98-20.61l-14.64,68.55s39.41,17.41,91.73,17.41c77.55,0,138.58-38.57,138.58-107.66,0-82.32-112.89-87.54-112.89-123.86,0-12.91,15.5-27.05,47.66-27.05,36.29,0,65.89,14.99,65.89,14.99l14.33-66.2S696.61.5,651.18.5h0ZM2.22,5.5L.5,15.49s29.84,5.46,56.72,16.36c34.61,12.49,37.07,19.77,42.9,42.35l63.51,244.83h85.14L379.93,5.5h-84.94l-84.28,213.17-34.39-180.7c-3.15-20.68-19.13-32.48-38.68-32.48,0,0-135.41,0-135.41,0Zm411.87,0l-66.63,313.53h81L494.85,5.5h-80.76Zm451.76,0c-19.53,0-29.88,10.46-37.47,28.73l-118.67,284.8h84.94l16.43-47.47h103.48l9.99,47.47h74.95L934.12,5.5h-68.27Zm11.05,84.71l25.18,117.65h-67.45l42.28-117.65h0Z"/>
				</svg></span>
			<span class="sv-footer__pay"><svg role="img" aria-label="Mastercard" viewBox="0 0 1000 618" xmlns="http://www.w3.org/2000/svg">
				<path fill="#EB001B" d="m308,0a309,309 0 1,0 2,0z"/>
				<path fill="#F79E1B" d="m690,0a309,309 0 1,0 2,0z"/>
				<path fill="#FF5F00" d="m500,66a309,309 0 0,0 0,486 309,309 0 0,0 0-486"/>
				</svg></span>
			<span class="sv-footer__pay"><svg role="img" aria-label="TROY" viewBox="0 0 298.04 137.25" xmlns="http://www.w3.org/2000/svg">
				<path fill="#485156" d="M273.17,22.07c-4.16,0-8.75,2.3-10.57,6.48l-19.43,44.52l-7-44.52c-0.82-4.17-3.64-6.48-8.12-6.48l-24.61,0 l21.05,73.96c0.38,1.42,0.46,2.99,0.18,4.65c-1.11,6.17-7,11.18-13.18,11.18l-13.77,0c-3.5,0-5.8,2.16-6.94,7.07l-3.06,18.32 l24.27,0c12.67,0,27.54-6.36,37.46-23.95l48.6-91.23L273.17,22.07z"/>
				<path fill="#485156" d="M34.27,0c7.27,0,10.78,2.9,9.51,10.18l-2.11,11.87l16.7,0L54.7,42.9l-16.7,0l-4.55,25.83 c-1.58,8.98,7.33,10.19,12.44,10.19c1.02,0,1.86-0.04,2.47-0.06l-4.09,23.25c-1.25,0.13-2.57,0.31-5.31,0.31 c-12.68,0-36.66-3.39-31.94-30.15l5.15-29.37L0,42.9l3.67-20.85l12.04,0L19.6,0L34.27,0z"/>
				<path fill="#00ADC1" d="M174.59,23.01l-4.29,24.32c5.3,2.69,8.92,8.19,8.92,14.53c0,8.36-6.25,15.2-14.33,16.18l-4.28,24.32 c0.76,0.05,1.53,0.07,2.29,0.07c22.41,0,40.57-18.17,40.57-40.57C203.46,43.53,191.3,28.04,174.59,23.01"/>
				<path fill="#00ADC1" d="M155.49,76.38c-5.28-2.68-8.91-8.19-8.91-14.53c0-8.31,6.28-15.2,14.34-16.17l4.28-24.31 c-0.77-0.05-1.54-0.08-2.3-0.08c-22.39,0-40.56,18.18-40.56,40.56c0,18.32,12.16,33.83,28.88,38.86L155.49,76.38z"/>
				<path fill="#485156" d="M68.04,22.07l14.35,0c7.27,0,10.77,2.91,9.48,10.19l-1.57,8.82c5.34-10.85,16.94-19.8,28.84-19.8 c1.56,0,3.06,0.31,3.06,0.31l-4.65,26.29c0,0-2.08-0.48-5.33-0.48c-6.34,0-17.04,2.01-23.02,13.92c-1.43,2.93-2.52,6.5-3.27,10.81 l-5.2,29.54l-26.73,0L68.04,22.07z"/>
				<path fill="#485156" d="M298.04,105.37c0,2.38-0.84,4.4-2.52,6.08c-1.67,1.67-3.69,2.51-6.06,2.51c-2.37,0-4.39-0.84-6.07-2.51 c-1.68-1.68-2.52-3.7-2.52-6.08c0-2.36,0.84-4.39,2.52-6.06c1.68-1.67,3.7-2.51,6.07-2.51c2.37,0,4.4,0.84,6.06,2.51 C297.2,100.98,298.04,103,298.04,105.37 M296.7,105.37c0-2.02-0.71-3.75-2.13-5.16c-1.41-1.43-3.12-2.15-5.11-2.15 c-1.99,0-3.69,0.72-5.1,2.15c-1.41,1.42-2.1,3.14-2.1,5.16c0,2.03,0.7,3.76,2.1,5.18c1.41,1.43,3.11,2.15,5.1,2.15 c1.98,0,3.7-0.72,5.11-2.15C295.99,109.13,296.7,107.4,296.7,105.37 M293.07,110.06h-1.53l-2.15-3.83h-1.42v3.83h-1.3v-9.4h3.19 c0.83,0,1.53,0.27,2.1,0.82c0.57,0.54,0.86,1.21,0.86,2c0,1.31-0.67,2.18-2.02,2.59L293.07,110.06z M291.5,103.47 c0-0.47-0.17-0.86-0.5-1.18c-0.33-0.32-0.76-0.47-1.28-0.47h-1.75l0,3.29h1.75c0.52,0,0.95-0.15,1.28-0.46 C291.33,104.34,291.5,103.95,291.5,103.47"/>
				</svg></span>
			<span class="sv-footer__pay"><svg role="img" aria-label="PayTR" viewBox="0 0 135 24" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" fill="#177FC3" d="M22.9758 12.9827H16.8273C16.0221 12.9827 15.2901 13.5683 15.1071 14.3734L13.6798 20.8513C13.4236 21.9493 14.485 23.0107 15.5829 23.0107H34.0285C35.5657 23.0107 36.9198 21.8761 37.1394 20.339L38.6399 11.1527L39.8111 3.94285C40.1405 2.00313 38.6399 0.283005 36.7002 0.283005H18.95C18.0716 0.283005 17.3396 0.905179 17.1932 1.74694L17.0468 2.7351C16.8639 3.79645 17.7056 4.78461 18.8036 4.78461H23.8542C25.9769 4.78461 27.5872 6.72433 27.1846 8.77384L26.965 10.5306C26.6722 12.1409 25.4645 13.0193 23.8176 13.0193"/>
				<path fill-rule="evenodd" clip-rule="evenodd" fill="#177FC3" d="M8.95858 19.2776H5.88431C5.15234 19.2776 4.49356 19.7168 4.31057 20.3024L4.01778 21.2905C3.76159 22.1323 4.53016 22.9741 5.59152 22.9741H8.66579C9.39776 22.9741 10.0565 22.5349 10.2395 21.9493L10.5323 20.9611C10.7885 20.0828 10.0199 19.2776 8.95858 19.2776Z"/>
				<path fill-rule="evenodd" clip-rule="evenodd" fill="#177FC3" d="M10.1297 13.0193H2.48065C1.74868 13.0193 1.0899 13.5316 0.906912 14.227L0.614123 15.3616C0.357933 16.3497 1.1265 17.3013 2.18786 17.3013H9.83694C10.5689 17.3013 11.2277 16.7889 11.4107 16.0935L11.7035 14.959C11.9597 14.0074 11.1911 13.0193 10.1297 13.0193Z"/>
				<path fill-rule="evenodd" clip-rule="evenodd" fill="#177FC3" d="M6.0673 4.63821H11.9597C12.6916 4.63821 13.3504 4.12583 13.5334 3.39386L13.8262 2.22271C14.0824 1.19795 13.3138 0.209793 12.2524 0.209793H6.36008C5.62811 0.209793 4.96934 0.722169 4.78635 1.45414L4.49356 2.62529C4.27397 3.65005 5.04254 4.63821 6.0673 4.63821Z"/>
				<path fill-rule="evenodd" clip-rule="evenodd" fill="#177FC3" d="M6.76267 7.93207L6.46988 9.10323C6.21369 10.128 6.98226 11.1161 8.04362 11.1161H19.5721C20.3041 11.1161 20.9629 10.6038 21.1459 9.87179L21.4387 8.70064C21.6949 7.67589 20.9263 6.68773 19.8649 6.68773H8.33641C7.60444 6.68773 6.94566 7.20011 6.76267 7.93207Z"/>
				<path fill="#177FC3" d="M46.472 0.100006H54.2675C59.7207 0.100006 62.8681 2.91808 62.8681 7.67588C62.8681 12.5435 59.6841 15.325 54.2675 15.325H51.3762V22.8643H46.472V0.100006ZM54.1577 11.3357C56.4634 11.3357 57.8541 10.0182 57.8541 7.74909C57.8541 5.47998 56.4634 4.16244 54.1577 4.16244H51.3396V11.3723H54.1577V11.3357Z"/>
				<path fill="#177FC3" d="M75.6776 17.9967H66.7842L65.2104 22.8643H60.0867L68.2115 0.100006H74.6528L82.7777 22.8643H77.2879L75.6776 17.9967ZM74.4699 14.3734L71.3224 4.74801H71.0662L67.9187 14.3734H74.4699Z"/>
				<path fill="#177FC3" d="M85.2298 14.2636L77.1781 0.136612H82.6679L87.6087 9.65222H87.9015L92.9521 0.136612H98.1125L90.0974 14.2636V22.9009H85.2298V14.2636Z"/>
				<path fill="#454D50" d="M104.041 4.16244H97.6733L99.9424 0.136612H115.314V4.16244H108.909V22.9009H104.041V4.16244Z"/>
				<path fill="#454D50" d="M129.221 22.9009L124.829 15.3616H124.683H121.792V22.9009H116.924V0.136612H124.72C130.173 0.136612 133.32 2.95469 133.32 7.71249C133.32 11.0795 131.82 13.4585 129.038 14.593L134.565 22.9009H129.221ZM121.792 11.3357H124.61C126.915 11.3357 128.306 9.9816 128.306 7.71249C128.306 5.47998 126.915 4.16244 124.61 4.16244H121.792V11.3357Z"/>
				</svg></span>
		</div>
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
