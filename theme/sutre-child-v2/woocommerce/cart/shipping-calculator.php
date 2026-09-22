<?php
/**
 * Shipping Calculator — Sutre P56/P58 override.
 * Kaynak: WooCommerce 11.1.0 templates/cart/shipping-calculator.php (@version 9.7.0)
 *
 * P56: Kayıtlı adres seçici hook'u (woocommerce_before_shipping_calculator) form DIŞINA
 * basar; kaydet UI'i form İÇİNDE olmak zorunda → şablon override (P55'in sessiz çalışmama
 * nedeni: hook tabanlı checkbox submit'e hiç gitmiyordu + nonce alan adı 9.7+ değişti).
 *
 * P58 değişiklikler (alan/sıra değişimi yalnız GİRİŞLİ kullanıcı; misafir dalı çekirdek
 * çıktısı ve sırasıyla birebir — P56 dersi):
 * 1) Alan seti + sıra Woo-native şema (sahibin kararı): Ad → Soyad → Firma(ops) →
 *    Adres Satırı 1 → Adres Satırı 2(ops) → İlçe/Semt (çekirdek calc_shipping_city) →
 *    Posta (calc_shipping_postcode) → Ülke (calc_shipping_country, TR kilitli mağaza) →
 *    Şehir/İl (calc_shipping_state, KOD post'lar) → Telefon → etiket/varsayılan/kaydet.
 *    Key'ler sv56_address_fields() ile aynıdır (Woo shipping_ ve billing_ kanonik eşleşmesi).
 * 2) TC Kimlik ve Mahalle/Köy KALDIRILDI.
 * 3) İl normalizasyonu korunur: calc_shipping_state KOD post'lar → sv56_sanitize_address_data
 *    İL ADI yazar; sv56_apply_address_to_customer ad→KOD çevirir.
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
			<?php /* ── P58 şema sırası (girişli): Ad → Soyad → Firma → Adres 1 → Adres 2 ── */ ?>
			<p class="form-row form-row-wide" id="sv_addr_first_name_field">
				<label for="sv_addr_first_name">Ad</label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_first_name() ); ?>" name="sv_addr_first_name" id="sv_addr_first_name" autocomplete="given-name" />
			</p>
			<p class="form-row form-row-wide" id="sv_addr_last_name_field">
				<label for="sv_addr_last_name">Soyad</label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_last_name() ); ?>" name="sv_addr_last_name" id="sv_addr_last_name" autocomplete="family-name" />
			</p>
			<p class="form-row form-row-wide" id="sv_addr_company_field">
				<label for="sv_addr_company">Firma <span class="sv-account-field__opt">(opsiyonel)</span></label>
				<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_company() ); ?>" name="sv_addr_company" id="sv_addr_company" autocomplete="organization" />
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

		<?php if ( is_user_logged_in() ) : ?>
			<?php /* ── P58 girişli sıra: İlçe → Posta → Ülke → Şehir (çekirdek blokları, yalnız sıra farklı) ── */ ?>
			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', true ) ) : ?>
				<p class="form-row form-row-wide" id="calc_shipping_city_field">
					<label for="calc_shipping_city"><?php esc_html_e( 'City:', 'woocommerce' ); ?></label>
					<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
				</p>
			<?php endif; ?>

			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
				<p class="form-row form-row-wide" id="calc_shipping_postcode_field">
					<label for="calc_shipping_postcode"><?php esc_html_e( 'Postcode / ZIP:', 'woocommerce' ); ?></label>
					<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
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

			<?php /* ── P58: Telefon — şemada Şehir'den sonra ── */ ?>
			<p class="form-row form-row-wide" id="sv_addr_phone_field">
				<label for="sv_addr_phone">Telefon</label>
				<input type="tel" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_phone() ); ?>" name="sv_addr_phone" id="sv_addr_phone" placeholder="+90 5XX XXX XX XX" autocomplete="tel" />
			</p>
		<?php else : ?>
			<?php /* ── Misafir: çekirdek sırasıyla birebir (country → state → city → postcode) ── */ ?>
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

			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
				<p class="form-row form-row-wide" id="calc_shipping_postcode_field">
					<label for="calc_shipping_postcode"><?php esc_html_e( 'Postcode / ZIP:', 'woocommerce' ); ?></label>
					<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
				</p>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( is_user_logged_in() ) : ?>
			<?php /* ── P58: etiket / varsayılan / kaydet (TC yok) ── */ ?>
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
