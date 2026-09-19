<?php
/**
 * Plugin Name: SUTRE — Rich Register Form (My Account)
 * Description: My Account kayıt formuna Ad / Soyad / Telefon alanları ekler (P19, Faz 2/3). mu-plugin; rollback = bu dosyayı silmek.
 * Version: 1.0.0
 * Author: @coder (bot) — Salih'in elle kurulumu için üretildi
 * Text Domain: sutre-rich-register
 *
 * anayasa §3.1: çekirdek değişikliği YOK; yalnız WooCommerce public hook'ları.
 * anayasa §3.2: küçük özellik, eklenti YOK — mu-plugins düz snippet; rollback = dosya silme.
 * STAGING amaçlıdır; canlıya taşınmadan önce review gerekir (OWNER_APPROVAL_REQUIRED).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Doğrudan erişim kapalı.
}

/**
 * Girdileri temizlemek: anayasa §3.1 sanitize/validate zorunlu.
 */
function sutre_rr_sanitize_input( $value, $type ) {
	$value = wp_unslash( $value ?? '' );
	switch ( $type ) {
		case 'text':
			return sanitize_text_field( $value );
		case 'phone':
			// Telefon opsiyonel alan; yalnız karakter beyaz listesi temizliği uygulanır (§3.1).
			return wp_check_invalid_utf8( preg_replace( '/[^0-9+\s()-]/', '', (string) $value ) );
		case 'email':
			$clean = sanitize_email( $value );
			return is_email( $clean ) ? $clean : '';
	}
	return '';
}

/**
 * 1) My Account kayıt formuna alanlar eklenir (WooCommerce hook; core değişikliği yok).
 *    Ad + Soyad: zorunlu. Telefon: opsiyonel (SUTRE tasarımda placeholder yalnızca).
 */
add_action( 'woocommerce_register_form_start', 'sutre_rr_add_fields' );
function sutre_rr_add_fields() {
	$first = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- kayıt formu public; nonce WooCommerce kayıt akışına aittir
	$last  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';  // phpcs:ignore
	$phone = isset( $_POST['billing_phone'] ) ? sutre_rr_sanitize_input( $_POST['billing_phone'], 'phone' ) : ''; // phpcs:ignore
	?>
	<p class="form-row form-row-first">
		<label for="reg_first_name"><?php echo esc_html__( 'Adı', 'sutre-rich-register' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="first_name" id="reg_first_name" value="<?php echo esc_attr( $first ); ?>" autocomplete="given-name" />
	</p>
	<p class="form-row form-row-last">
		<label for="reg_last_name"><?php echo esc_html__( 'Soyadı', 'sutre-rich-register' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="last_name" id="reg_last_name" value="<?php echo esc_attr( $last ); ?>" autocomplete="family-name" />
	</p>
	<p class="form-row form-row-wide">
		<label for="reg_billing_phone"><?php echo esc_html__( 'Telefon (opsiyonel)', 'sutre-rich-register' ); ?></label>
		<input type="tel" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="05XX XXX XX XX" autocomplete="tel-national" />
	</p>
	<?php
}

/**
 * 2) Sunucu tarafı doğrulama: Ad/Soyad zorunlu; e-posta WooCommerce kendi akışında dışarıda kontrol eder.
 */
add_action( 'woocommerce_register_post', 'sutre_rr_validate_fields', 10, 3 );
function sutre_rr_validate_fields( $username, $email, $validation_errors ) {
	$first = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : ''; // phpcs:ignore
	$last  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';  // phpcs:ignore
	if ( '' === $first ) {
		$validation_errors->add( 'first_name_error', __( '<strong>Hata:</strong> Ad zorunludur.','sutre-rich-register' ) );
	}
	if ( '' === $last ) {
		$validation_errors->add( 'last_name_error', __( '<strong>Hata:</strong> Soyad zorunludur.', 'sutre-rich-register' ) );
	}
	$phone = isset( $_POST['billing_phone'] ) ? sutre_rr_sanitize_input( $_POST['billing_phone'], 'phone' ) : '';
	// Opsiyonel alan; sadece biçim kırıksa reddediyoruz.
	if ( '' !== $phone && '' === preg_replace( '/[^0-9]/', '', $phone ) ) {
		$validation_errors->add( 'phone_error', __( '<strong>Hata:</strong> Geçerli bir telefon numarası girin.', 'sutre-rich-register' ) );
	}
	// Gizlilik (anayasa §4.2/§15): ham secret/kart/parola asla loglanmaz; yalnız doğrulama hatası kullanıcıya gösterilir.
	return $validation_errors;
}

/**
 * 3) Kayıt tamamlanınca meta yazılır: first_name / last_name / billing_phone (WooCommerce billing meta).
 *    idempotent: update_user_meta bool dönüyor; tekrar çağrı çift kayıt oluşturmaz (anayasa §4.4 çift-engelleme mantığıyla uyumlu).
 */
add_action( 'user_register', 'sutre_rr_save_fields', 20 );
function sutre_rr_save_fields( $user_id ) {
	if ( ! $user_id ) {
		return;
	}
	$first = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : ''; // phpcs:ignore
	$last  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';  // phpcs:ignore
	$phone = isset( $_POST['billing_phone'] ) ? sutre_rr_sanitize_input( $_POST['billing_phone'], 'phone' ) : '';
	if ( $first ) {
		update_user_meta( $user_id, 'first_name', $first );
	}
	if ( $last ) {
		update_user_meta( $user_id, 'last_name', $last );
	}
	if ( '' !== $phone ) {
		update_user_meta( $user_id, 'billing_phone', $phone );
	}
}

/**
 * 2.5) Parola kuralları: min 8 char, en az 1 buyuk harf, en az 1 sayi (ozel isaret zorunlu degil — sahibin karari).
 */
add_action( 'woocommerce_register_post', 'sutre_rr_password_policy', 5, 3 );
function sutre_rr_password_policy( $username, $email, $validation_errors ) {
	$password = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : ''; // phpcs:ignore
	if ( '' === $password ) {
		return; // Sifre alani yoksa (baska akis) mudahale yok.
	}
	if ( strlen( $password ) < 8 ) {
		$validation_errors->add( 'pwd_len', __( '<strong>Hata:</strong> Parola en az 8 karakter olmali.', 'sutre-rich-register' ) );
	}
	if ( ! preg_match( '/[A-Z]/', $password ) ) {
		$validation_errors->add( 'pwd_upper', __( '<strong>Hata:</strong> Parolada en az 1 buyuk harf olmali.', 'sutre-rich-register' ) );
	}
	if ( ! preg_match( '/[0-9]/', $password ) ) {
		$validation_errors->add( 'pwd_digit', __( '<strong>Hata:</strong> Parolada en az 1 sayi olmali.', 'sutre-rich-register' ) );
	}
}

/**
 * 4) Klavye UX notu: parola alanı My Account kayıt formu WooCommerce çekirdeğinde
 *    zaten mevcut (generate-password / password hint); bu snippet dokunmaz.
 *    Checkout 'during checkout' kayıt davranışı P17 sahibin kararıyla açık: snippet checkout'a dokunmaz.
 */
