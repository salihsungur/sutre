<?php
/**
 * Sutre v2 — functions.php (temiz kurulum)
 * Yalnızca asset enqueue + Woo placeholder düzeltmesi.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_stylesheet_directory_uri();
	wp_enqueue_style( 'sutre-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Jost:wght@300;400;500;600&display=swap', [], null );
	wp_enqueue_style( 'sutre-style', get_stylesheet_uri(), [], '2.0.0' );
} );

// Woo block template'leri kapalı — klasik şablonlar kullanılır
add_filter( 'woocommerce_has_block_template', '__return_false', 999 );

// Placeholder görsel: Woo core'dan
add_filter( 'woocommerce_placeholder_img', function ( $html, $size, $dimensions, $src ) {
	$src = is_array( $src ) ? (string) reset( $src ) : (string) $src;
	if ( empty( $src ) ) { return ''; }
	return sprintf(
		'<img src="%s" alt="%s" class="woocommerce-placeholder wp-post-image" width="%s" height="%s" loading="lazy" />',
		esc_url( $src ),
		esc_attr__( 'Ürün görseli', 'sutre' ),
		esc_attr( isset( $dimensions['width'] ) ? $dimensions['width'] : 400 ),
		esc_attr( isset( $dimensions['height'] ) ? $dimensions['height'] : 500 )
	);
}, 10, 4 );
