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

<header class="sv-header">
	<a class="sv-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-header.png' ); ?>" alt="Sutre — ana sayfa" decoding="async">
	</a>
	<nav class="sv-header__nav" aria-label="Ana menü">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Ana Sayfa</a>
		<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">Mağaza</a>
		<a href="<?php echo esc_url( function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/') ); ?>">Hesabım</a>
	</nav>
</header>

<div id="sutre-content">
