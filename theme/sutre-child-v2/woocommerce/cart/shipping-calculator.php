<?php
/**
 * Shipping Calculator — Sutre P56/P57 override.
 * Kaynak: WooCommerce 11.1.0 templates/cart/shipping-calculator.php (@version 9.7.0)
 *
 * P56: Kayıtlı adres seçici hook'u (woocommerce_before_shipping_calculator) form DIŞINA
 * basar; kaydet UI'i form İÇİNDE olmak zorunda → şablon override (P55'in sessiz çalışmama
 * nedeni: hook tabanlı checkbox submit'e hiç gitmiyordu + nonce alan adı 9.7+ değişti).
 *
 * P57 değişiklikler (yalnız GİRİŞLİ kullanıcı; misafirde çekirdek çıktısıyla birebir):
 * 1) Alan sırası sahibin şeması: İsim Soyisim → Telefon → Adres 1 → Adres 2 → Ülke →
 *    Şehir(İl) → İlçe → Mahalle/Köy → Posta Kodu → TC → etiket/varsayılan/kaydet.
 *    (Misafirde sv alanları render edilmediği için Woo sırası country→state→city→postcode
 *    korunmaya devam eder.)
 * 2) TC Kimlik No alanı eklendi (opsiyonel; ^\d{11}$ sanitize sv56_handle_cart_save_address'te).
 * 3) Mahalle prefill: adres defterinde seansın gönderim adresiyle eşleşen kayıt > varsayılan.
 * İşleyici: sv56_handle_cart_save_address (functions.php; wp_loaded:30).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_shipping_calculator' ); ?>

<form class="woocommerce-shipping-calculator" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<?php printf( '<a href="#" class="shipping-calculator-button" aria-expanded="false" aria-controls="shipping-calculator-form" role="button">%s</a>', esc_html( ! empty( $button_text ) ? $button_text : __( 'Calculate shipping', 'woocommerce' ) ) ); ?>

	<section class="shipping-calculator-form" id="shipping-calculator-form" style="display:none;">

		<?php if ( is_user_logged_in() ) : ?>
			<?php /* ── P57: adres defteri alanları ÖNCE (sahibin şeması); misafirde render edilmez ── */ ?>
			<p class="form-row form-row-wide" id="sv_addr_name_field">
				<label for="sv_addr_name">İsim Soyisim</label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_first_name() . ' ' . WC()->customer->get_shipping_last_name() ); ?>" name="sv_addr_name" id="sv_addr_name" autocomplete="name" />
			</p>
			<p class="form-row form-row-wide" id="sv_addr_phone_field">
				<label for="sv_addr_phone">Cep Telefonu</label>
				<input type="tel" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_phone() ); ?>" name="sv_addr_phone" id="sv_addr_phone" placeholder="+90 5XX XXX XX XX" autocomplete="tel" />
			</p>
			<p class="form-row form-row-wide" id="sv_addr_address_1_field">
				<label for="sv_addr_address_1">Adres Satırı 1</label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_address_1() ); ?>" name="sv_addr_address_1" id="sv_addr_address_1" autocomplete="address-line1" />
			</p>
			<p class="form-row form-row-wide" id="sv_addr_address_2_field">
				<label for="sv_addr_address_2">Adres Satırı 2 <span class="sv-account-field__opt">(opsiyonel)</span></label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_address_2() ); ?>" name="sv_addr_address_2" id="sv_addr_address_2" autocomplete="address-line2" />
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_country', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_country_field">
				<label for="calc_shipping_country"><?php esc_html_e( 'Country / region', 'woocommerce' ); ?></label>
				<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state country_select" rel="calc_shipping_state">
					<option value="default"><?php esc_html_e( 'Select a country / region&hellip;', 'woocommerce' ); ?></option>
					<?php
					foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					}
					?>
				</select>
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_state', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_state_field">
				<?php
				$current_cc = WC()->customer->get_shipping_country();
				$current_r  = WC()->customer->get_shipping_state();
				$states     = WC()->countries->get_states( $current_cc );

				if ( is_array( $states ) && empty( $states ) ) {
					?>
					<input type="hidden" name="calc_shipping_state" id="calc_shipping_state" />
					<?php
				} elseif ( is_array( $states ) ) {
					?>
					<span>
						<label for="calc_shipping_state"><?php esc_html_e( 'State / County', 'woocommerce' ); ?></label>
						<select name="calc_shipping_state" class="state_select" id="calc_shipping_state">
							<option value=""><?php esc_html_e( 'Select an option&hellip;', 'woocommerce' ); ?></option>
							<?php
							foreach ( $states as $ckey => $cvalue ) {
								echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
							}
							?>
						</select>
					</span>
					<?php
				} else {
					?>
					<label for="calc_shipping_state"><?php esc_html_e( 'State / County', 'woocommerce' ); ?></label>
					<input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" name="calc_shipping_state" id="calc_shipping_state" />
					<?php
				}
				?>
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_city_field">
				<label for="calc_shipping_city"><?php esc_html_e( 'City:', 'woocommerce' ); ?></label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
			</p>
		<?php endif; ?>

		<?php if ( is_user_logged_in() ) : ?>
			<?php /* ── P57: Mahalle/Köy — İlçe'den hemen sonra; prefill defterden ── */
			$sv_nb      = '';
			$sv_nb_id   = '';
			$sv_items   = function_exists( 'sv56_addresses' ) ? sv56_addresses( get_current_user_id() ) : array();
			foreach ( $sv_items as $sv_item ) {
				if ( function_exists( 'sv56_address_matches_customer' ) && sv56_address_matches_customer( $sv_item ) ) { $sv_nb_id = $sv_item['id']; break; }
			}
			if ( '' === $sv_nb_id && function_exists( 'sv56_default_address_id' ) ) { $sv_nb_id = sv56_default_address_id( get_current_user_id() ); }
			foreach ( $sv_items as $sv_item ) {
				if ( $sv_item['id'] === $sv_nb_id ) { $sv_nb = $sv_item['neighborhood']; break; }
			}
			?>
			<p class="form-row form-row-wide" id="sv_addr_neighborhood_field">
				<label for="sv_addr_neighborhood">Mahalle / Köy</label>
				<input type="text" class="input-text" value="<?php echo esc_attr( $sv_nb ); ?>" name="sv_addr_neighborhood" id="sv_addr_neighborhood" autocomplete="address-level3" />
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_postcode_field">
				<label for="calc_shipping_postcode"><?php esc_html_e( 'Postcode / ZIP:', 'woocommerce' ); ?></label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
			</p>
		<?php endif; ?>

		<?php if ( is_user_logged_in() ) : ?>
			<?php /* ── P57: TC (opsiyonel) → etiket/varsayılan/kaydet ── */ ?>
			<p class="form-row form-row-wide" id="sv_addr_tc_field">
				<label for="sv_addr_tc">TC Kimlik No <span class="sv-account-field__opt">(opsiyonel)</span></label>
				<input type="text" class="input-text" value="" name="sv_addr_tc" id="sv_addr_tc" inputmode="numeric" maxlength="11" autocomplete="off" />
			</p>
			<p class="form-row form-row-wide sv56-save-row">
				<label for="sv_address_label">Adres Etiketi <span class="sv-req" aria-hidden="true">*</span></label>
				<input type="text" class="input-text" value="" name="sv_address_label" id="sv_address_label" placeholder="Ev, İş vb." />
			</p>
			<p class="form-row form-row-wide sv56-save-row">
				<label class="checkbox">
					<input type="checkbox" class="input-checkbox" name="sv_make_default" value="1">
					<span>Varsayılan adres yap</span>
				</label>
			</p>
			<p class="form-row form-row-wide sv56-save-row">
				<label class="checkbox">
					<input type="checkbox" class="input-checkbox" name="sv_save_address" value="1" checked>
					<span>Bu adresi hesabıma kaydet</span>
				</label>
			</p>
		<?php endif; ?>

		<p><button type="submit" name="calc_shipping" value="1" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button></p>
		<?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
	</section>
</form>

<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>
