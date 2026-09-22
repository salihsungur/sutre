<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#sutre-content">İçeriğe geç</a>

<?php // P54: duyuru şeridi — tüm sitede, header'ın üstünde (CSS marquee) ?>
<div class="sv-announce" role="note" aria-label="İlk siparişe %10 indirim — SUTRE10 kodu">
	<div class="sv-announce__track">
		<span>SUTRE'DE İLK SİPARİŞE ÖZEL %10 İNDİRİM İÇİN &ldquo;SUTRE10&rdquo; KODUNU KULLANABİLİRSİNİZ!</span>
		<span aria-hidden="true">SUTRE'DE İLK SİPARİŞE ÖZEL %10 İNDİRİM İÇİN &ldquo;SUTRE10&rdquo; KODUNU KULLANABİLİRSİNİZ!</span>
		<span aria-hidden="true">SUTRE'DE İLK SİPARİŞE ÖZEL %10 İNDİRİM İÇİN &ldquo;SUTRE10&rdquo; KODUNU KULLANABİLİRSİNİZ!</span>
		<span aria-hidden="true">SUTRE'DE İLK SİPARİŞE ÖZEL %10 İNDİRİM İÇİN &ldquo;SUTRE10&rdquo; KODUNU KULLANABİLİRSİNİZ!</span>
	</div>
</div>

<?php /* P72: mobil header düzeni — burger sol, logo orta, sepet sağ (sepet ikonu drawer'a TAŞINMAZ) */ ?>
<header class="sv-header">
	<button type="button" class="sv-header__burger" id="sv-burger" aria-label="Menüyü aç" aria-expanded="false" aria-controls="sv-drawer">
		<span></span><span></span><span></span>
	</button>
	<a class="sv-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-header.png' ); ?>" alt="Sutre — ana sayfa" decoding="async">
	</a>
	<div class="sv-header__right">
		<nav class="sv-header__nav" aria-label="Ana menü">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Ana Sayfa</a>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">Mağaza</a>
			<a href="<?php echo esc_url( home_url( '/hakkimizda/' ) ); ?>">Hakkımızda</a>
			<a href="<?php echo esc_url( home_url( '/iletisim/' ) ); ?>">İletişim</a>
			<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/') ); ?>">Hesabım</a>
			<a href="<?php echo esc_url( function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/') ); ?>">Sepetim</a>
		</nav>
		<?php /* P72: sepet ikonu — tıklanınca sepet sayfası; sayı AJAX fragmanıyla güncellenir */ ?>
		<?php $count = function_exists( 'WC' ) && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0; ?>
		<a class="sv-header__cart" href="<?php echo esc_url( function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/') ); ?>" aria-label="Sepet">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M6 7h12l-1.2 12.2a1.8 1.8 0 0 1-1.8 1.6H9a1.8 1.8 0 0 1-1.8-1.6L6 7Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
			<span class="sv-header__cart-count<?php echo $count ? '' : ' is-empty'; ?>"><?php echo (int) $count; ?></span>
		</a>
	</div>
</header>

<?php /* P72: yandan açılan mobil menü (drawer) — tüm sayfa yönlendirmeleri burada */ ?>
<div class="sv-drawer-scrim" id="sv-drawer-scrim" hidden></div>
<aside class="sv-drawer" id="sv-drawer" aria-label="Menü" hidden>
	<div class="sv-drawer__head">
		<span class="sv-drawer__brand">SUTRE</span>
		<button type="button" class="sv-drawer__close" id="sv-drawer-close" aria-label="Menüyü kapat">&times;</button>
	</div>
	<nav class="sv-drawer__nav" aria-label="Mobil menü">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Ana Sayfa</a>
		<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">Mağaza</a>
		<a href="<?php echo esc_url( home_url( '/hakkimizda/' ) ); ?>">Hakkımızda</a>
		<a href="<?php echo esc_url( home_url( '/iletisim/' ) ); ?>">İletişim</a>
		<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/') ); ?>">Hesabım</a>
		<a href="<?php echo esc_url( function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/') ); ?>">Sepetim</a>
	</nav>
</aside>

<div id="sutre-content">