<?php
/**
 * My Account dashboard — Sutre P41 özelleştirmesi.
 * Kaynak: WooCommerce 11.1.0 templates/myaccount/dashboard.php (@version 4.4.0)
 * Değişiklik: Türkçe intro + İletişim / Üyelik / Opsiyonel bilgi formları.
 * do_action('woocommerce_account_dashboard') ve deprecated action'lar korunur.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sv_user = wp_get_current_user();

$sv_notice_key = function_exists( 'sv41_current_notice' ) ? sv41_current_notice() : '';
$sv_notices    = function_exists( 'sv41_notices' ) ? sv41_notices() : array();

$sv_phone  = (string) get_user_meta( $sv_user->ID, 'billing_phone', true );
$sv_gender = (string) get_user_meta( $sv_user->ID, 'sv_gender', true );
$sv_bday   = (int) get_user_meta( $sv_user->ID, 'sv_birth_day', true );
$sv_bmonth = (int) get_user_meta( $sv_user->ID, 'sv_birth_month', true );
$sv_byear  = (int) get_user_meta( $sv_user->ID, 'sv_birth_year', true );

$sv_months = array(
	1  => 'Ocak',
	2  => 'Şubat',
	3  => 'Mart',
	4  => 'Nisan',
	5  => 'Mayıs',
	6  => 'Haziran',
	7  => 'Temmuz',
	8  => 'Ağustos',
	9  => 'Eylül',
	10 => 'Ekim',
	11 => 'Kasım',
	12 => 'Aralık',
);
$sv_this_year = (int) current_time( 'Y' );
?>
<p class="sv-account-intro">
	Merhaba <strong><?php echo esc_html( $sv_user->display_name ); ?></strong> (hesabınız değil mi? <a href="<?php echo esc_url( wc_logout_url() ); ?>">Çıkış yapın</a>)
</p>
<p class="sv-account-intro">
	Hesap panonuzdan iletişim ve üyelik bilgilerinizi yönetebilir, <a href="<?php echo esc_url( sv41_myaccount_url( 'orders' ) ); ?>">siparişlerinizi</a>, <a href="<?php echo esc_url( sv41_myaccount_url( 'edit-address' ) ); ?>">adreslerinizi</a> ve <a href="<?php echo esc_url( sv41_myaccount_url( 'edit-account' ) ); ?>">şifre/hesap ayrıntılarınızı</a> görüntüleyebilirsiniz.
</p>

<?php
/**
 * My Account dashboard (WooCommerce çekirdek uyumu — korundu).
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_before_my_account' );

if ( '' !== $sv_notice_key && isset( $sv_notices[ $sv_notice_key ] ) ) :
	?>
	<div class="sv-account-notice" role="status"><?php echo esc_html( $sv_notices[ $sv_notice_key ] ); ?></div>
	<?php
endif;
?>

<section class="sv-account-section">
	<h2>İletişim Bilgileri</h2>
	<p class="sv-account-section__hint">Sipariş süreçlerindeki bildirim ve teslimat iletişimi için kullanılır.</p>

	<form class="sv-account-form" method="post" action="<?php echo esc_url( sv41_myaccount_url() ); ?>">
		<div class="form-row">
			<label for="sv_phone">Cep Telefonu</label>
			<input type="tel" class="input-text" name="sv_phone" id="sv_phone" value="<?php echo esc_attr( $sv_phone ); ?>" placeholder="+90 5XX XXX XX XX" autocomplete="tel-national" inputmode="tel">
			<span class="sv-account-field__hint">Ülke kodu ile birlikte girin (öntanımlı: +90).</span>
		</div>
		<input type="hidden" name="sv_form" value="phone">
		<?php wp_nonce_field( 'sv_account_phone_save', 'sv_nonce' ); ?>
		<button type="submit" class="button sv-account-form__submit">Kaydet</button>
	</form>

	<form class="sv-account-form" method="post" action="<?php echo esc_url( sv41_myaccount_url() ); ?>">
		<div class="form-row">
			<label for="sv_email">E-Posta</label>
			<input type="email" class="input-text" name="sv_email" id="sv_email" value="<?php echo esc_attr( $sv_user->user_email ); ?>" autocomplete="email">
			<span class="sv-account-field__hint">Hesabınıza bu adresle giriş yapılır.</span>
		</div>
		<input type="hidden" name="sv_form" value="email">
		<?php wp_nonce_field( 'sv_account_email_save', 'sv_nonce' ); ?>
		<button type="submit" class="button sv-account-form__submit">Kaydet</button>
	</form>
</section>

<section class="sv-account-section">
	<h2>Üyelik Bilgilerim</h2>

	<form class="sv-account-form" method="post" action="<?php echo esc_url( sv41_myaccount_url() ); ?>">
		<div class="sv-account-form__grid">
			<div class="form-row">
				<label for="sv_first_name">Adı</label>
				<input type="text" class="input-text" name="sv_first_name" id="sv_first_name" value="<?php echo esc_attr( $sv_user->first_name ); ?>" autocomplete="given-name">
			</div>
			<div class="form-row">
				<label for="sv_last_name">Soyadı</label>
				<input type="text" class="input-text" name="sv_last_name" id="sv_last_name" value="<?php echo esc_attr( $sv_user->last_name ); ?>" autocomplete="family-name">
			</div>
		</div>
		<input type="hidden" name="sv_form" value="name">
		<?php wp_nonce_field( 'sv_account_name_save', 'sv_nonce' ); ?>
		<button type="submit" class="button sv-account-form__submit">Kaydet</button>
	</form>
</section>

<!-- LEGAL_REVIEW_REQUIRED: KVKK aydınlatma metni PLACEHOLDER'dır;
     final metin docs/legal-placeholders/gizlilik-politikasi.md şablonundan
     hukuki onay sonrası doldurulacaktır (Blok A NEEDS_OWNER_INPUT). -->
<section class="sv-account-section">
	<h2>Opsiyonel Bilgiler <span class="sv-account-section__opt">(opsiyonel)</span></h2>
	<p class="sv-account-section__hint">
		Bu bilgiler tamamen isteğe bağlıdır; dilediğiniz zaman boş bırakabilir veya silebilirsiniz.
		Sağlamanız hâlinde kişisel verileriniz, [İŞLETME UNVANI TBD] tarafından Gizlilik ve KVKK
		Aydınlatma Metni kapsamında işlenir (metnin yayımı ve hukuki onayı süreçtedir).
	</p>

	<form class="sv-account-form" method="post" action="<?php echo esc_url( sv41_myaccount_url() ); ?>">
		<div class="form-row">
			<label for="sv_gender">Cinsiyet <span class="sv-account-field__opt">(opsiyonel)</span></label>
			<select class="input-select" name="sv_gender" id="sv_gender">
				<option value="" <?php selected( '', $sv_gender ); ?>>Seçmek istemiyorum</option>
				<option value="female" <?php selected( 'female', $sv_gender ); ?>>Kadın</option>
				<option value="male" <?php selected( 'male', $sv_gender ); ?>>Erkek</option>
				<option value="prefer-not-to-say" <?php selected( 'prefer-not-to-say', $sv_gender ); ?>>Belirtmek istemiyorum</option>
			</select>
		</div>

		<div class="form-row">
			<span class="sv-account-form__label">Doğum Tarihi <span class="sv-account-field__opt">(opsiyonel)</span></span>
			<div class="sv-account-form__grid sv-account-form__grid--date">
				<select class="input-select" name="sv_birth_day" id="sv_birth_day" aria-label="Doğum günü">
					<option value="" <?php selected( 0, $sv_bday ); ?>>Gün</option>
					<?php for ( $sv_d = 1; $sv_d <= 31; $sv_d++ ) : ?>
						<option value="<?php echo esc_attr( (string) $sv_d ); ?>" <?php selected( $sv_d, $sv_bday ); ?>><?php echo esc_html( (string) $sv_d ); ?></option>
					<?php endfor; ?>
				</select>
				<select class="input-select" name="sv_birth_month" id="sv_birth_month" aria-label="Doğum ayı">
					<option value="" <?php selected( 0, $sv_bmonth ); ?>>Ay</option>
					<?php foreach ( $sv_months as $sv_m => $sv_m_name ) : ?>
						<option value="<?php echo esc_attr( (string) $sv_m ); ?>" <?php selected( $sv_m, $sv_bmonth ); ?>><?php echo esc_html( $sv_m_name ); ?></option>
					<?php endforeach; ?>
				</select>
				<select class="input-select" name="sv_birth_year" id="sv_birth_year" aria-label="Doğum yılı">
					<option value="" <?php selected( 0, $sv_byear ); ?>>Yıl</option>
					<?php for ( $sv_y = $sv_this_year; $sv_y >= 1900; $sv_y-- ) : ?>
						<option value="<?php echo esc_attr( (string) $sv_y ); ?>" <?php selected( $sv_y, $sv_byear ); ?>><?php echo esc_html( (string) $sv_y ); ?></option>
					<?php endfor; ?>
				</select>
			</div>
		</div>

		<input type="hidden" name="sv_form" value="optional">
		<?php wp_nonce_field( 'sv_account_optional_save', 'sv_nonce' ); ?>
		<button type="submit" class="button sv-account-form__submit">Kaydet</button>
	</form>
</section>

<?php
/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
