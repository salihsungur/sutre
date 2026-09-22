<?php
/**
 * Checkout shipping form — Sutre P58 override.
 * Kaynak: WooCommerce 11.1.0 templates/checkout/form-shipping.php (@version 3.6.0,
 * zip sha256 6bae9bf74d722b6d… ile doğrulanmış kaynak).
 *
 * P58 TEK-FORM UX — yalnız GİRİŞLİ kullanıcı (misafir dalı çekirdekle birebir; P56
 * dersi + dispatch kuralı "misafir çıktısı çekirdekle tutarlı"):
 * - Gönderim bölümü BİRİNCİL formdur: başlık "Teslimat Bilgileri", alanlar her zaman
 *   görünür (çekirdek "Ship to a different address?" checkbox'ı girişlide YOKTUR).
 * - Çekirdek ship_to_different_address POST'unun yerini gizli input alır (value=1):
 *   gönderim alan seti daima toplanır (maybe_skip_fieldset kaynağı: class-wc-checkout.php:773
 *   — POST yoksa gönderim seti atlanır ve fatura→gönderim kopyalanır, 849-853).
 * - "Faturayı da aynı adrese gönderilsin" checkbox'ı (#sv_invoice_same, varsayılan
 *   İŞARETLİ): işaretliyken fatura formu gizli (style.css :has()) ve sunucu aynalaması
 *   sipariş faturasını teslimat adresiyle doldurur (sv58_mirror_billing_from_shipping);
 *   işaret kalkarsa form-billing.php'deki "Farklı fatura adresi" bölümü açılır.
 * - Checkbox durumu oturumda saklanır (sv_invoice_same) — doğrulama hatası dönüşünde
 *   korunur (çekirdek ship_to_different_address oturum deseni).
 * - Misafir: çekirdek çıktısı aynen (checkbox dahil).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<?php if ( is_user_logged_in() ) : ?>
<div class="woocommerce-shipping-fields sv-teslimat">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h3 class="sv-teslimat-heading"><?php esc_html_e( 'Teslimat Bilgileri', 'sutre' ); ?></h3>

		<div class="shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="woocommerce-shipping-fields__field-wrapper">
				<?php
				$fields = $checkout->get_checkout_fields( 'shipping' );

				foreach ( $fields as $key => $field ) {
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
				?>
			</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

			<div class="sv-invoice-same-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<?php
					$sv_session = function_exists( 'WC' ) ? WC()->session : null;
					$sv_invoice_same_checked = ( ! $sv_session ) || 'no' !== $sv_session->get( 'sv_invoice_same' );
					?>
					<input id="sv_invoice_same" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( $sv_invoice_same_checked, true ); ?> type="checkbox" name="sv_invoice_same" value="1" /> <span><?php esc_html_e( 'Faturayı da aynı adrese gönderilsin', 'sutre' ); ?></span>
				</label>
				<?php /* marker: POST'un bu formdan geldiğini ispatlar (aynalama kararı) */ ?>
				<input type="hidden" name="sv_invoice_same_present" value="1" />
				<?php /* gönderim alan seti daima toplanır (maybe_skip_fieldset:773 kaynağı) */ ?>
				<input type="hidden" name="ship_to_different_address" value="1" />
			</div>

		</div>

	<?php endif; ?>
</div>
<?php else : ?>
<div class="woocommerce-shipping-fields">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h3 id="ship-to-different-address">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
			</label>
		</h3>

		<div class="shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="woocommerce-shipping-fields__field-wrapper">
				<?php
				$fields = $checkout->get_checkout_fields( 'shipping' );

				foreach ( $fields as $key => $field ) {
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
				?>
			</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>
</div>
<?php endif; ?>
<div class="woocommerce-additional-fields">
	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

			<h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<div class="woocommerce-additional-fields__field-wrapper">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
