<?php
/**
 * Sutre Child — header.php (klasik PHP)
 * PAKET 22c: TT5 block-theme header.php YOK → get_header() theme-compat fallback'e düşüyor.
 * Bu dosya klasik template'lerin (page-templates/home.php vb.) header'ını sağlar.
 * Rollback: bu dosyayı silmek.
 */
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

<a class="skip-link screen-reader-text" href="#sutre-content"><?php esc_html_e( 'İçeriğe geç', 'sutre' ); ?></a>

<header id="masthead" class="sutre-header" role="banner">
	<div class="sutre-header__inner">
		<a class="sutre-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Sutre</a>
		<nav class="sutre-header__nav" aria-label="<?php esc_attr_e( 'Ana menü', 'sutre' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Ana Sayfa', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'Mağaza', 'sutre' ); ?></a>
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'Hesabım', 'sutre' ); ?></a>
		</nav>
	</div>
</header>

<div id="sutre-content" class="sutre-content">
