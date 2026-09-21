<?php
/**
 * The template for displaying product content within loops
 *
 * Sutre override (P44): Woo 11.1.0 content-product.php (@version 9.4.0) temelli.
 * DEĞİŞİKLİK: sale flash görselin üstünden alındı → fiyatın soluna, .sv-card__meta
 * satırına taşındı (sahip kararı: rozet beyaz bantta fiyatın yanında). Rating hook'u
 * tasarımda kullanılmadığı için meta satırı doğrudan çağrıyla kurar.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	// P44: sale flash BU konumdan kaldırıldı (aşağıda .sv-card__meta içinde).
	woocommerce_template_loop_product_thumbnail();

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	// P44: rozet + fiyat tek satırda (beyaz bant, sol hizalı).
	echo '<div class="sv-card__meta">';
	woocommerce_show_product_loop_sale_flash();
	woocommerce_template_loop_price();
	echo '</div>';

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
