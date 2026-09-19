<?php
/**
 * Sutre Child — header.php (klasik PHP, TEK KAYNAK)
 * P29: TÜM sayfa tipleri bu header'ı kullanır (klasik + Woo klasik şablonlar).
 * Block part header.html İLE AYNI markup — tek görsel kaynak korunur.
 * Rollback: git revert.
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

<header class="sutre-header" role="banner">
	<a class="sutre-header__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-header.png' ); ?>" alt="Sutre" class="sutre-header__logo-img">
	</a>
	<nav class="sutre-header__nav" aria-label="<?php esc_attr_e( 'Ana menü', 'sutre' ); ?>">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Ana Sayfa', 'sutre' ); ?></a>
		<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Mağaza', 'sutre' ); ?></a>
		<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>"><?php esc_html_e( 'Hesabım', 'sutre' ); ?></a>
	</nav>
</header>

<div id="sutre-content" class="sutre-content">
