<?php
/**
 * Sutre v3 — functions.php (quiet luxury tasarım sistemi)
 * Asset enqueue + WooCommerce (block template kapalı, klasik şablonlar, wrapper, sidebar yok) + shop banner + scroll reveal.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SUTRE_VERSION', '3.6.0' );

/* ── Asset enqueue ── */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'sutre-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500&display=swap',
		[],
		null
	);
	wp_enqueue_style( 'sutre-style', get_stylesheet_uri(), [ 'sutre-fonts' ], SUTRE_VERSION );
} );

/* ── Font preconnect (performans) ── */
add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = [ 'href' => 'https://fonts.googleapis.com' ];
		$urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' ];
	}
	return $urls;
}, 10, 2 );

/* ── WooCommerce tema desteği (v1 beyanı restore) — klasik 'desteklenen tema' yolu sabit ── */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 350,
		'single_image_width'    => 800,
		'product_grid'          => array(
			'default_columns' => 3,   /* v3 grid: desktop 3 sütun (style.css ile tutarlı) */
			'default_rows'    => 6,
			'min_columns'     => 2,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}, 20 );

/* ── Block template'ler KAPALI (G1) ──
 * TT5 parent FSE; bu tema klasik PHP mimarisi. Not: Woo'nun woocommerce_has_block_template
 * filtresi yalnızca WP_Block_Templates_Registry::is_registered() sonucunu filtreler
 * (class-wc-template-loader.php L132-148) — WP çekirdeğinin locate_block_template()
 * kapısına dokunmaz; eski filtre bu yüzden işlevsizdi. Doğru kapı theme support kaldırmaktır. */
add_action( 'after_setup_theme', function () {
	remove_theme_support( 'block-templates' );
}, 20 );

/* ── WooCommerce: sidebar YOK + content wrapper override (G3) ──
 * Şablon dosyaları verbatim kalır (§3.1); hook katmanında: sidebar kalkar, Woo'nun
 * default <main id="main"> wrapper'ı yerine .woocommerce container gelir
 * (G5'teki #sutre-content .woocommerce padding kuralının hedefi). */
add_action( 'init', function () {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
} );
add_action( 'woocommerce_before_main_content', function () {
	echo '<div class="woocommerce">';
}, 10 );
add_action( 'woocommerce_after_main_content', function () {
	echo '</div>';
}, 10 );

/* ── WooCommerce: shop sayfa başlığı kaldır (banner'daki "Koleksiyon" h1 yerine geçer) ── */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/* ── Shop banner: mağaza/kategori arşivlerinde tam genişlik görsel başlık ── */
add_action( 'woocommerce_before_main_content', function () {
	if ( ! function_exists( 'is_woocommerce' ) ) { return; }
	if ( ! ( is_shop() || is_product_taxonomy() ) ) { return; }
	$img = get_stylesheet_directory_uri() . '/assets/img/site/shop-banner-sutre.png';
	?>
	<section class="sv-shop-banner" role="banner" style="background-image:url('<?php echo esc_url( $img ); ?>')">
		<div class="sv-shop-banner__inner">
			<h1 class="sv-shop-banner__title"><?php esc_html_e( 'Koleksiyon', 'sutre' ); ?></h1>
			<p class="sv-shop-banner__sub"><?php esc_html_e( 'El dokuması şallar', 'sutre' ); ?></p>
		</div>
	</section>
	<?php
}, 5 );

/* ── P39: kart/arşiv görselleri keskin — 350px thumbnail yerine 'large' (1024px).
 * 350px'lik woocommerce_thumbnail 504px kartı doldurmak için büyütülüyordu → bulanıklık.
 * 'large' tüm mevcut yüklemelerde zaten üretilmiş durumda → yeniden boyutlandırma GEREKMEZ. ── */
add_filter( 'single_product_archive_thumbnail_size', function () { return 'large'; } );

/* ── P40: Woo gizlilik metni Türkçe — RESMÎ filtre kapısı (Woo 11.1: metin option'dan gelir,
 * 'woocommerce_registration_privacy_policy_text' bir filtre DEĞİL, option adı; doğrusu
 * woocommerce_get_privacy_policy_text). [privacy_policy] placeholder'ı Woo link ile değiştirir. ── */
add_filter( 'woocommerce_get_privacy_policy_text', function ( $text, $type ) {
	if ( 'registration' === $type ) {
		return 'Kişisel verileriniz, hesap deneyiminizi desteklemek, hesabınıza erişimi yönetmek ve [privacy_policy] metninde açıklanan diğer amaçlar doğrultusunda kullanılır.';
	}
	return $text;
}, 10, 2 );

/* ── P46: stok bilgisi gösterimi — "X adet stokta" müşteriye ASLA gösterilmez;
 * yalnız eşik (varsayılan 3) altında aciliyet mesajı. Varyasyon seçiminde de çalışır.
 * Eşiği değiştirmek için: sv_stock_low_threshold filtresi. ── */
function sutre_stock_low_threshold() {
	return (int) apply_filters( 'sv_stock_low_threshold', 3 );
}
function sutre_stock_low_html() {
	return '<p class="stock sv-stock-low">Acele et — stokta az kaldı!</p>';
}
add_filter( 'woocommerce_get_stock_html', function ( $html, $product ) {
	if ( ! $product->managing_stock() || ! $product->is_in_stock() ) {
		return $html; // Tükendi vb. varsayılan davranış korunur
	}
	$qty = $product->get_stock_quantity();
	if ( $qty === null ) { return $html; }
	$t = sutre_stock_low_threshold();
	if ( $qty <= $t ) { return sutre_stock_low_html(); }
	return ''; // normal stokta hiçbir şey gösterme
}, 10, 2 );
add_filter( 'woocommerce_available_variation', function ( $data, $product, $variation ) {
	$qty = $variation->get_stock_quantity();
	if ( ! $variation->managing_stock() || $qty === null || ! $variation->is_in_stock() ) {
		return $data;
	}
	$t = sutre_stock_low_threshold();
	if ( $qty <= $t ) {
		$data['availability_html'] = sutre_stock_low_html();
	} else {
		$data['availability_html'] = '';
	}
	return $data;
}, 10, 3 );

/* ── P47: sepet sayacı AJAX fragmanı (sepete ekleme sonrası header'daki sayı güncellenir) ── */
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$count = WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
	ob_start();
	echo '<span class="sv-header__cart-count' . ( $count ? '' : ' is-empty' ) . '">' . (int) $count . '</span>';
	$fragments['.sv-header__cart-count'] = ob_get_clean();
	return $fragments;
} );

/* ── P47: sepet sayfası İngilizce stringler Türkçe (gettext — blok şablondan gelenler dahil;
 * domain farketmez: woo blocks kendi domain'ini kullanabiliyor) ──
 * P57: checkout klasikleştikçe ödeme sayfası stringleri de aynı kapıdan Türkçeleşir.
 * 'Additional information' bağlam-duyarlıdır: ürün sekmesinde 'Ürün Bilgileri' (P52),
 * checkout'ta aynı EN string sipariş notu bölümü başlığıdır → 'Sipariş Notu'. */
add_filter( 'gettext', function ( $translated, $text, $domain ) {
	$map = array(
		'Your cart is currently empty!'      => 'Sepetin şu an boş.',
		'New in store'                       => 'Mağazada yeni',
		'Return to shop'                     => 'Alışverişe devam et',
		'View my shopping cart'              => 'Sepeti görüntüle',
		'Billing details'                    => 'Fatura Bilgileri',
		'Billing &amp; Shipping'             => 'Fatura & Gönderim',
		'Ship to a different address?'       => 'Farklı bir adrese gönderilsin mi?',
		'Create an account?'                 => 'Hesap oluştur',
		'Your order'                         => 'Sipariş Özeti',
		'Order notes'                        => 'Sipariş Notu',
		'Place order'                        => 'Siparişi Onayla',
		'Update totals'                      => 'Toplamları Güncelle',
		'Have a coupon?'                     => 'Kuponunuz mu var?',
		'You must be logged in to checkout.' => 'Ödeme adımı için giriş yapmalısınız.',
		/* P58 — sepet hesaplayıcı çekirdek etiketleri (şema insan-dili; gettext global
		 * uygulanır ama bu stringler yalnız hesaplayıcı şablonunda geçer — alan şemasıyla
		 * checkout/defter etiketleri hizalanır). */
		'City:'            => 'İlçe / Semt',
		'State / County'   => 'Şehir',
		'Postcode / ZIP:'  => 'Posta Kodu',
		'Country / region' => 'Ülke',
		/* P75 — sepet baştan tasarım: özet paneli başlığı + güncelle butonu
		 * (yalnız sepet sayfasında geçen çekirdek stringler; dil paketi ne derse desin
		 * tasarım diliyle hizalı Türkçe döner). */
		'Cart totals' => 'Sepet Özeti',
		'Update cart' => 'Sepeti Güncelle',
	);
	if ( isset( $map[ $text ] ) ) { return $map[ $text ]; }
	if ( 'Additional information' === $text ) {
		return ( function_exists( 'is_checkout' ) && is_checkout() ) ? 'Sipariş Notu' : 'Ürün Bilgileri';
	}
	return $translated;
}, 10, 3 );

/* ── P47: sepet sayfası klasik shortcode'a zorlanır — sepet BLOĞU (JS i18n'li İngilizce
 * içerik + blok grid kartları) yerine klasik [woocommerce_cart]; TEK MİMARİ = klasik PHP
 * doktriniyle uyumlu. gettext haritası klasik şablonun string'lerini Türkçeleştirir. ── */
add_filter( 'the_content', function ( $content ) {
	if ( function_exists( 'is_cart' ) && is_cart() && ! has_shortcode( $content, 'woocommerce_cart' ) ) {
		return do_shortcode( '[woocommerce_cart]' );
	}
	return $content;
}, 20 );

/* ── P57: checkout sayfası klasik [woocommerce_checkout] shortcode'a zorlanır — checkout
 * BLOĞU (JS-i18n İngilizce + blok kartlar) yerine klasik şablon; P47 cart deseniyle AYNI
 * kapı (the_content:20). POST akışı render'dan bağımsızdır: WC_Form_Handler::checkout_action
 * wp_loaded'ta WC()->checkout()->process_checkout() çağırır (class-wc-form-handler.php:476)
 * → klasik zorlama POST'u etkilemez. order-pay / order-received uçları da WC_Shortcode_Checkout::output
 * içinde (kaynak:36-60) aynı shortcode'tan işlenir. ── */
add_filter( 'the_content', function ( $content ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! has_shortcode( $content, 'woocommerce_checkout' ) ) {
		return do_shortcode( '[woocommerce_checkout]' );
	}
	return $content;
}, 20 );

/* ── P53: ürün meta (Stok kodu/Kategoriler) sepete ekle altından alınıp ürün başlığının ÜSTÜNE taşınır ── */
add_action( 'wp_loaded', function () {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 3 );
}, 20 );

/* ── P55 sepet adres kaydetme akışı P56'da yeniden kuruldu (Adres Defteri tek kaynak):
 * hesaplayıcı checkbox'ı artık şablon override'ında (woocommerce/cart/shipping-calculator.php)
 * formun İÇİNDE render edilir ve wp_loaded işleyicisi sv56_handle_cart_save_address'tir.
 * Eski hook + işleyici kaldırıldı — Woo 9.7+ nonce alan adı ve form yapısı değiştiği için
 * eski akış zaten sessizce çalışmıyordu (nonce alan adı uyuşmuyordu + checkbox form dışındaydı). ── */

/* ── P58a: checkout teslimat odaklı — Woo varsayılanı "fatura adresine gönder" olduğu için
 * seçilen gönderim adresi gizli kalıyordu. Bu modda checkout GÖNDERİM alanlarını gösterir
 * (sepet seçicisinin yazdığı adres otomatik dolar), fatura "opsiyonel" checkbox'a düşer ── */
add_filter( 'option_woocommerce_ship_to_destination', function () { return 'shipping'; } );

/* ── P66: kayıt ekranı onay kutuları — KVKK (zorunlu) + Ticari Elektronik İleti (opsiyonel, boş) ── */
add_action( 'woocommerce_register_form', function () {
	/* P66-fix: wc_get_page_permalink('privacy_policy') canlıda ana sayfaya döndü (güvenilmez).
	 * Doğru kapı: page slug üzerinden çöz — Gizlilik ve KVKK Aydınlatma aynı sayfada birleşik. */
	$gizlilik_url = home_url( '/gizlilik-politikasi/' );
	$gizlilik_pg  = get_page_by_path( 'gizlilik-politikasi', OBJECT, 'page' );
	if ( $gizlilik_pg && 'publish' === get_post_status( $gizlilik_pg ) ) {
		$gizlilik_url = get_permalink( $gizlilik_pg );
	}
	$ticari = home_url( '/ticari-elektronik-ileti/' );
	?>
	<p class="form-row form-row-wide sv-register-consent">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
			<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="sv_kvkk_consent" id="sv_kvkk_consent" value="1" required>
			<span><a href="<?php echo esc_url( $gizlilik_url ); ?>" target="_blank" rel="noopener">Gizlilik Politikası</a> ve <a href="<?php echo esc_url( $gizlilik_url ); ?>" target="_blank" rel="noopener">KVKK Aydınlatma Metni</a>'ni okudum, anladım ve kişisel verilerimin belirtilen amaçlarla işlenmesini kabul ediyorum. <abbr class="sv-req" title="zorunlu">*</abbr></span>
		</label>
	</p>
	<p class="form-row form-row-wide sv-register-consent">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
			<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="sv_ticari_ileti" id="sv_ticari_ileti" value="1">
			<span>SUTRE tarafından yeni koleksiyonlar, indirimler ve kampanyalara ilişkin SMS ve e-posta ticari iletileri gönderilmesine onay veriyorum. (<a href="<?php echo esc_url( $ticari ); ?>" target="_blank" rel="noopener">Ticari Elektronik İleti Metni</a>)</span>
		</label>
	</p>
	<?php
} );

/* P66: KVKK onayı zorunlu — işaretlenmezse kayıt reddedilir */
add_action( 'woocommerce_register_post', function ( $username, $email, $errors ) {
	if ( empty( $_POST['sv_kvkk_consent'] ) ) {
		$errors->add( 'sv_kvkk_consent', 'Kayıt için Gizlilik Politikası ve KVKK Aydınlatma Metni\'ni onaylamanız gerekmektedir.' );
	}
}, 10, 3 );

/* P66: kayıt sonrası onayları hesaba işle (İletişim Tercihleri ile aynı meta yapısı) */
add_action( 'woocommerce_created_customer', function ( $customer_id ) {
	$optin  = ! empty( $_POST['sv_ticari_ileti'] );
	update_user_meta( $customer_id, 'sv_contact_pref_email', $optin ? 'yes' : 'no' );
	update_user_meta( $customer_id, 'sv_contact_pref_sms', $optin ? 'yes' : 'no' );
	update_user_meta( $customer_id, 'sv_kvkk_consent', 'yes' );
	if ( $optin ) {
		update_user_meta( $customer_id, 'sv_ticari_optin_date', current_time( 'mysql' ) );
	}
}, 10, 1 );

/* ── P68: İletişim sayfası formu — shortcode the_content sonuna eklenir (DB düzenlemesi gerekmez) ── */
add_shortcode( 'sutre_contact_form', function () {
	$sent = isset( $_GET['sv_contact'] ) && $_GET['sv_contact'] === 'sent';
	ob_start();
	?>
	<div class="sv-contact-form-wrap" id="iletisim-formu">
		<h2 class="sv-contact-form__title">Bize Yazın</h2>
		<?php if ( $sent ) : ?>
			<div class="sv-account-notice" role="status">Mesajınız ulaştı — en geç 1 iş günü içinde size dönüş yapacağız. Teşekkürler!</div>
		<?php endif; ?>
		<form class="sv-contact-form" method="post" action="">
			<div class="sv-contact-form__grid">
				<div class="form-row">
					<label for="sv_c_name">Ad Soyad <span class="sv-req" aria-hidden="true">*</span></label>
					<input type="text" class="input-text" name="sv_c_name" id="sv_c_name" required autocomplete="name">
				</div>
				<div class="form-row">
					<label for="sv_c_email">E-Posta <span class="sv-req" aria-hidden="true">*</span></label>
					<input type="email" class="input-text" name="sv_c_email" id="sv_c_email" required autocomplete="email">
				</div>
				<div class="form-row">
					<label for="sv_c_phone">Telefon <span class="sv-account-field__opt">(opsiyonel)</span></label>
					<input type="tel" class="input-text" name="sv_c_phone" id="sv_c_phone" autocomplete="tel">
				</div>
			</div>
			<div class="form-row">
				<label for="sv_c_message">Mesajınız <span class="sv-req" aria-hidden="true">*</span></label>
				<textarea class="input-text" name="sv_c_message" id="sv_c_message" rows="6" required></textarea>
			</div>
			<p class="sv-honeypot" aria-hidden="true"><label>Web sitesi<input type="text" name="sv_c_website" tabindex="-1" autocomplete="off"></label></p>
			<input type="hidden" name="sv_contact_submit" value="1">
			<?php wp_nonce_field( 'sv_contact_form', 'sv_contact_nonce' ); ?>
			<button type="submit" class="sv-contact-form__submit">Mesajı Gönder</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
} );

add_filter( 'the_content', function ( $content ) {
	if ( ! is_admin() && function_exists( 'is_page' ) && is_page( 'iletisim' ) && in_the_loop() ) {
		$content .= do_shortcode( '[sutre_contact_form]' );
	}
	return $content;
}, 30 );

add_action( 'template_redirect', function () {
	if ( empty( $_POST['sv_contact_submit'] ) ) { return; }
	if ( ! isset( $_POST['sv_contact_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['sv_contact_nonce'] ) ), 'sv_contact_form' ) ) { return; }
	// Honeypot: bot doldurduysa sessizce "başarılı" göster (içerik yok)
	if ( ! empty( $_POST['sv_c_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'sv_contact', 'sent', get_permalink( get_page_by_path( 'iletisim' ) ) ) );
		exit;
	}
	$name    = isset( $_POST['sv_c_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sv_c_name'] ) ) : '';
	$email   = isset( $_POST['sv_c_email'] ) ? sanitize_email( wp_unslash( $_POST['sv_c_email'] ) ) : '';
	$phone   = isset( $_POST['sv_c_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['sv_c_phone'] ) ) : '';
	$message = isset( $_POST['sv_c_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sv_c_message'] ) ) : '';

	$errors = array();
	if ( '' === $name ) { $errors[] = 'Ad Soyad zorunludur.'; }
	if ( ! is_email( $email ) ) { $errors[] = 'Geçerli bir e-posta adresi giriniz.'; }
	if ( '' === trim( $message ) ) { $errors[] = 'Mesaj alanı boş olamaz.'; }
	if ( $errors ) {
		foreach ( $errors as $e ) { wc_add_notice( $e, 'error' ); }
		return; // form sayfası yeniden render olur, Woo notice'ları görünür
	}

	$to      = 'sutrescarfs@gmail.com';
	$subject = '[SUTRE İletişim Formu] ' . $name;
	$body    = "Ad Soyad: {$name}\nE-Posta: {$email}\nTelefon: {$phone}\n\nMesaj:\n{$message}\n\n— SUTRE web sitesi iletişim formu";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent    = wp_mail( $to, $subject, $body, $headers );

	$redirect = get_permalink( get_page_by_path( 'iletisim' ) );
	wp_safe_redirect( add_query_arg( 'sv_contact', $sent ? 'sent' : 'error', $redirect ) );
	exit;
}, 20 );

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

/* ── Scroll reveal: tek seferlik, IntersectionObserver, ~0.4KB ── */
add_action( 'wp_print_footer_scripts', function () {
	?>
	<script>
	(function () {
		'use strict';
		var els = document.querySelectorAll('.sv-reveal');
		if (!els.length) { return; }
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduce || !('IntersectionObserver' in window)) {
			els.forEach(function (el) { el.classList.add('is-visible'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) {
					e.target.classList.add('is-visible');
					io.unobserve(e.target);
				}
			});
		}, { threshold: 0.12 });
		els.forEach(function (el) { io.observe(el); });
	})();
	</script>
	<?php
} );

/* ═══════════════════════════════════════════════════════════
   P41 — HESABIM İÇERİK YENİLEMESİ (A: Pano formları)
   İletişim (telefon/e-posta) + Üyelik (ad-soyad) + Opsiyonel (cinsiyet/doğum).
   Güvenlik: her form kendi nonce'u + is_account_page() + is_user_logged_in();
   çıktılar escape'li, girişler beyaz-liste temizliği (anayasa §3.1/§8).
   POST işleyicileri template_redirect'te: init'te is_account_page()
   güvenilmez (WP query henüz kurulmamış); Woo çekirdek form handler'ları
   (WC_Form_Handler::save_account_details) da aynı hook'u kullanır.
   ═══════════════════════════════════════════════════════════ */

/**
 * Hesabım URL'i — PHP 8.5 permalink tuzağı guard'ı (AGENTS §5.5):
 * wc_get_page_permalink() array döndürebilir; string'e indirilir.
 */
function sv41_myaccount_url( $endpoint = '' ) {
	$base = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
	$base = is_array( $base ) ? (string) reset( $base ) : (string) $base;
	if ( '' !== $endpoint && function_exists( 'wc_get_endpoint_url' ) ) {
		return wc_get_endpoint_url( $endpoint, '', $base );
	}
	return $base;
}

/** Ham POST alanı (string, slash temizli). */
function sv41_raw_post( $key ) {
	return isset( $_POST[ $key ] ) ? (string) wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- değer okuyan her akış kendi nonce'unu doğrular
}

/** GET/POST parametresini metin temizliğinden geçirerek okur. */
function sv41_param( $key, $source = 'get' ) {
	$raw = 'post' === $source ? ( isset( $_POST[ $key ] ) ? (string) wp_unslash( $_POST[ $key ] ) : '' ) : ( isset( $_GET[ $key ] ) ? (string) wp_unslash( $_GET[ $key ] ) : '' );
	return sanitize_text_field( $raw );
}

/**
 * P41 durum mesajları (whitelist). Whitelist dışı sv_notice değeri hiçbir şey basmaz.
 */
function sv41_notices() {
	return array(
		'phone_saved'       => 'Cep telefonu numaranız güncellendi.',
		'email_saved'       => 'E-posta adresiniz güncellendi.',
		'name_saved'        => 'Üyelik bilgileriniz güncellendi.',
		'optional_saved'    => 'Opsiyonel bilgileriniz kaydedildi.',
		'prefs_saved'       => 'İletişim tercihleriniz kaydedildi.',
		'error_nonce'       => 'Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.',
		'error_phone'       => 'Lütfen geçerli bir cep telefonu numarası girin (örn. +90 5XX XXX XX XX).',
		'error_email'       => 'Lütfen geçerli bir e-posta adresi girin.',
		'error_email_taken' => 'Bu e-posta adresi başka bir hesapta kayıtlı.',
		'error_name'        => 'Ad ve soyad alanları zorunludur.',
		'error_optional'    => 'Doğum tarihi için gün, ay ve yıl birlikte seçilmelidir.',
		/* P56 — Adres Defteri */
		'address_saved'          => 'Adres kaydedildi.',
		'address_updated'        => 'Adres güncellendi.',
		'address_deleted'        => 'Adres silindi.',
		'address_default'        => 'Varsayılan adres güncellendi.',
		'address_applied'        => 'Teslimat adresi güncellendi.',
		'error_address'          => 'Lütfen zorunlu adres alanlarını doldurun.',
		'error_address_label'    => 'Lütfen adresinize bir isim (etiket) verin.',
		'error_address_notfound' => 'Adres bulunamadı.',
	);
}

/** Aktif durum mesajı anahtarı (whitelist dışıysa boş). */
function sv41_current_notice() {
	$key = sv41_param( 'sv_notice' );
	if ( '' === $key ) { return ''; }
	$notices = sv41_notices();
	return isset( $notices[ $key ] ) ? $key : '';
}

/** PRG deseni: beyaz-listeli mesajla güvenli yeniden yönlendirme + exit. */
function sv41_redirect_notice( $key, $endpoint = '' ) {
	wp_safe_redirect( add_query_arg( 'sv_notice', $key, sv41_myaccount_url( $endpoint ) ) );
	exit;
}

/**
 * P41 hesap formları POST işleyicisi (Pano + İletişim Tercihleri).
 * Yetkisiz / form'suz / nonce'suz istek sessizce bırakılır.
 */
add_action( 'template_redirect', 'sv41_handle_account_forms' );
function sv41_handle_account_forms() {
	if ( 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? (string) $_SERVER['REQUEST_METHOD'] : '' ) ) { return; }
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() || ! is_user_logged_in() ) { return; }

	$nonce_map = array(
		'phone'    => 'sv_account_phone_save',
		'email'    => 'sv_account_email_save',
		'name'     => 'sv_account_name_save',
		'optional' => 'sv_account_optional_save',
		'prefs'    => 'sv_contact_prefs_save',
	);
	$form = sv41_param( 'sv_form', 'post' );
	if ( ! isset( $nonce_map[ $form ] ) ) { return; }
	if ( ! wp_verify_nonce( sv41_param( 'sv_nonce', 'post' ), $nonce_map[ $form ] ) ) {
		sv41_redirect_notice( 'error_nonce', 'prefs' === $form ? 'iletisim-tercihleri' : '' );
	}

	$user_id = get_current_user_id();

	switch ( $form ) {
		case 'phone':
			/* wc-rich-register mu-plugin'iyle aynı beyaz liste; billing_phone
			   siparişlerin iletişim telefonu olarak da kullanılır. */
			$phone  = wp_check_invalid_utf8( preg_replace( '/[^0-9+\s()-]/', '', sv41_raw_post( 'sv_phone' ) ) );
			$digits = preg_replace( '/[^0-9]/', '', $phone );
			if ( strlen( (string) $digits ) < 10 ) { sv41_redirect_notice( 'error_phone' ); }
			update_user_meta( $user_id, 'billing_phone', $phone );
			sv41_redirect_notice( 'phone_saved' );
			break;

		case 'email':
			$email = sanitize_email( sv41_raw_post( 'sv_email' ) );
			if ( ! is_email( $email ) ) { sv41_redirect_notice( 'error_email' ); }
			if ( email_exists( $email ) && $email !== (string) wp_get_current_user()->user_email ) {
				sv41_redirect_notice( 'error_email_taken' );
			}
			wp_update_user( array( 'ID' => $user_id, 'user_email' => $email ) );
			sv41_redirect_notice( 'email_saved' );
			break;

		case 'name':
			$first = sanitize_text_field( sv41_raw_post( 'sv_first_name' ) );
			$last  = sanitize_text_field( sv41_raw_post( 'sv_last_name' ) );
			if ( '' === $first || '' === $last ) { sv41_redirect_notice( 'error_name' ); }
			update_user_meta( $user_id, 'first_name', $first );
			update_user_meta( $user_id, 'last_name', $last );
			update_user_meta( $user_id, 'billing_first_name', $first );
			update_user_meta( $user_id, 'billing_last_name', $last );
			sv41_redirect_notice( 'name_saved' );
			break;

		case 'optional':
			/* KVKK: opsiyonel veri — boş gönderim metaları siler (veri minimizasyonu). */
			$gender = sanitize_key( sv41_raw_post( 'sv_gender' ) );
			if ( ! in_array( $gender, array( '', 'female', 'male', 'prefer-not-to-say' ), true ) ) { $gender = ''; }
			$day       = absint( sv41_raw_post( 'sv_birth_day' ) );
			$month     = absint( sv41_raw_post( 'sv_birth_month' ) );
			$year      = absint( sv41_raw_post( 'sv_birth_year' ) );
			$chosen    = ( $day || $month || $year );
			$this_year = (int) current_time( 'Y' );
			if ( $chosen && ( ! $day || ! $month || ! $year || $day > 31 || $month > 12 || $year < 1900 || $year > $this_year ) ) {
				sv41_redirect_notice( 'error_optional' );
			}
			if ( '' !== $gender ) { update_user_meta( $user_id, 'sv_gender', $gender ); } else { delete_user_meta( $user_id, 'sv_gender' ); }
			if ( $chosen ) {
				update_user_meta( $user_id, 'sv_birth_day', $day );
				update_user_meta( $user_id, 'sv_birth_month', $month );
				update_user_meta( $user_id, 'sv_birth_year', $year );
			} else {
				delete_user_meta( $user_id, 'sv_birth_day' );
				delete_user_meta( $user_id, 'sv_birth_month' );
				delete_user_meta( $user_id, 'sv_birth_year' );
			}
			sv41_redirect_notice( 'optional_saved' );
			break;

		case 'prefs':
			/* Tercih formu yalnız kendi endpoint'inden işlenir (ekstra yetki sınırı).
			 * P57 FIX: is_wc_endpoint_url('iletisim-tercihleri') özel endpoint'te daima
			 * false dönerdi (Bug 1 ile AYNI kök — kanıt: sv56_handle_account_address_forms
			 * üstündeki not) → tercih formu hiç kaydetmiyordu. query_vars kontrolü. */
			global $wp;
			if ( ! isset( $wp->query_vars ) || ! is_array( $wp->query_vars ) || ! array_key_exists( 'iletisim-tercihleri', $wp->query_vars ) ) { return; }
			foreach ( array( 'email', 'sms', 'call' ) as $channel ) {
				update_user_meta( $user_id, 'sv_contact_pref_' . $channel, isset( $_POST[ 'sv_contact_pref_' . $channel ] ) ? 'yes' : 'no' );
			}
			sv41_redirect_notice( 'prefs_saved', 'iletisim-tercihleri' );
			break;
	}
}

/* ═══════════════════════════════════════════════════════════
   P41 — HESABIM İÇERİK YENİLEMESİ (B: İletişim Tercihleri)
   Yeni hesap endpoint'i: /my-account/iletisim-tercihleri/
   NOT (KULLANICI ADIMI): rewrite kuralları kendiliğinden yenilenmez;
   WP Admin → Ayarlar → Kalıcı Bağlantılar → Kaydet (tek tık) gerekli.
   ═══════════════════════════════════════════════════════════ */

add_action( 'init', function () {
	add_rewrite_endpoint( 'iletisim-tercihleri', EP_ROOT | EP_PAGES );
} );

/* Hesap nav: Pano → "Hesap bilgileri" (Hesap detayları pano ile birleşti — P49/P50);
 * İletişim Tercihleri — Siparişlerim'den hemen sonra.
 * NOT: erken-dönüş YOK — her uygulamada account-details çıkarılmalı (P50 bug fix). */
add_filter( 'woocommerce_account_menu_items', function ( $items ) {
	$pref_key = 'iletisim-tercihleri';
	$new = array();
	foreach ( $items as $key => $label ) {
		if ( 'dashboard' === $key ) {
			$new[ $key ] = 'Hesap bilgileri';   // P49: Pano + Hesap detayları birleşimi
			continue;
		}
		if ( 'edit-account' === $key ) { continue; }   // P50-fix: menü key'i 'edit-account' (account-details DEĞİL — P50'de yanlış anahtar elenmişti)
		if ( 'downloads' === $key ) { continue; }      // P60: dijital ürün satmıyoruz — İndirilenler sayfası menüden çıkar (fiziksel mağaza)
		if ( 'edit-address' === $key && ! isset( $new['adreslerim'] ) ) {   // P56: Adresler → Adreslerim (key §5a-b: wc_get_account_menu_items kaynak kodundan doğrulandı)
			$new['adreslerim'] = 'Adreslerim';
			continue;
		}
		$new[ $key ] = $label;
		if ( 'orders' === $key && ! isset( $new[ $pref_key ] ) ) {
			$new[ $pref_key ] = 'İletişim Tercihleri';
		}
	}
	if ( ! isset( $new[ $pref_key ] ) ) {
		$new[ $pref_key ] = 'İletişim Tercihleri';
	}
	return $new;
} );

/* P49: dashboard sayfa başlığı "Hesap bilgileri" (pano yerine) */
add_action( 'woocommerce_account_dashboard', function () {
	echo '<h1 class="sv-account-title">Hesap bilgileri</h1>';
}, 1 );

/* P50: edit-account endpoint'i kaldırıldı — doğrudan erişim Hesap bilgileri'ne yönlenir.
 * (WC_Form_Handler save_account_details template_redirect:20'de çalışır → POST önce
 * kaydedilir; buraya ancak kayıt sonrası/GET erişiminde düşülür.) */
add_action( 'template_redirect', function () {
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'edit-account' ) ) {
		wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
		exit;
	}
}, 30 );

/**
 * İletişim Tercihleri içeriği: KVKK onay metni + üç kanal toggle'ı.
 * KVKK metni PLACEHOLDER'dır — LEGAL_REVIEW_REQUIRED (anayasa §6.1/§6.2):
 * docs/legal-placeholders/gizlilik-politikasi.md taslağıyla uyumludur;
 * avukat onayı ve işletme verileri olmadan yayına alınmamalıdır.
 */
add_action( 'woocommerce_account_iletisim-tercihleri_endpoint', 'sv41_contact_prefs_content' );
function sv41_contact_prefs_content() {
	$sv_notices = sv41_notices();
	$sv_notice  = sv41_current_notice();
	?>
	<p class="sv-account-intro">Ticari elektronik ileti onaylarınızı bu sayfadan dilediğiniz zaman güncelleyebilirsiniz.</p>

	<?php if ( '' !== $sv_notice && isset( $sv_notices[ $sv_notice ] ) ) : ?>
		<div class="sv-account-notice" role="status"><?php echo esc_html( $sv_notices[ $sv_notice ] ); ?></div>
	<?php endif; ?>

	<!-- LEGAL_REVIEW_REQUIRED: KVKK aydınlatma + pazarlama izni metni PLACEHOLDER'dır
	     (hukuki onay bekliyor; [TBD] alanları Blok A işletme girdileriyle doldurulacak). -->
	<div class="sv-account-notice sv-account-notice--legal" role="note">
		<strong>KVKK Aydınlatma ve Ticari Elektronik İleti Onayı</strong>
		Veri sorumlusu: [İŞLETME UNVANI TBD]. Kişisel verileriniz (ad, e-posta adresi, telefon numarası);
		yalnızca açık rızanız (KVKK m. 5/1) doğrultusunda, ticari elektronik ileti (e-posta, SMS, çağrı merkezi)
		yoluyla yapılacak pazarlama ve kampanya bilgilendirmeleri amacıyla işlenir. Onayınız isteğe bağlıdır ve
		sipariş vermenin şartı değildir; sipariş işlemleri, teslimat ve faturalama bildirimleri bu onaydan bağımsız yürütülür.
		Onayınızı bu sayfadan dilediğiniz zaman geri alabilirsiniz; çekim talebiniz pazarlama iletişimini sonlandırır.
		Ticari elektronik ileti izinleriniz İYS (İleti Yönetim Sistemi) kayıtlarıyla da yönetilir.
		Ayrıntılı bilgi: Gizlilik ve KVKK Aydınlatma Metni — [LİNK TBD: /gizlilik-politikasi sayfası hukuki onay bekliyor].
		Başvuru kanalları: [E-POSTA TBD] / [TEBLİGAT ADRESİ TBD].
	</div>

	<section class="sv-account-section">
		<h2>Pazarlama İletişimi</h2>
		<p class="sv-account-section__hint">Bilgilendirme kanallarını tek tek açıp kapatabilirsiniz.</p>

		<form class="sv-account-form sv-pref-form" method="post" action="<?php echo esc_url( sv41_myaccount_url( 'iletisim-tercihleri' ) ); ?>">
			<?php
			$sv_channels = array(
				'email' => array( 'E-Posta', 'Kampanya ve yeni koleksiyon e-postaları' ),
				'sms'   => array( 'SMS', 'Kampanya ve bilgilendirme SMS mesajları' ),
				'call'  => array( 'Çağrı Merkezi', 'Çağrı merkezi yoluyla bilgilendirme' ),
			);
			foreach ( $sv_channels as $sv_channel => $sv_meta ) :
				$sv_checked = 'yes' === (string) get_user_meta( get_current_user_id(), 'sv_contact_pref_' . $sv_channel, true );
				?>
				<label class="sv-pref-toggle">
					<input type="checkbox" name="sv_contact_pref_<?php echo esc_attr( $sv_channel ); ?>" value="1" <?php checked( true, $sv_checked ); ?>>
					<span class="sv-pref-toggle__track" aria-hidden="true"></span>
					<span class="sv-pref-toggle__label">
						<strong><?php echo esc_html( $sv_meta[0] ); ?></strong>
						<em><?php echo esc_html( $sv_meta[1] ); ?></em>
					</span>
				</label>
			<?php endforeach; ?>
			<input type="hidden" name="sv_form" value="prefs">
			<?php wp_nonce_field( 'sv_contact_prefs_save', 'sv_nonce' ); ?>
			<button type="submit" class="button sv-account-form__submit">Kaydet</button>
		</form>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════════════
   P41 — HESABIM İÇERİK YENİLEMESİ (C: Siparişlerim arama/filtre)
   GET: sv_q (sipariş no / ürün adı), sv_period (3m/6m/1y/all),
   sv_sort (date-desc/date-asc/price-asc/price-desc).
   Strateji: müşterinin TÜM siparişleri tek sorguda (limit -1) çekilir;
   dönem + arama (order item döngüsü) + sıralama + sayfalama PHP'de.
   Tek müşteri / düşük sipariş sayısı → basit döngü kabul (pack P41).
   Not: WC_Order_Query 'total' orderby'ı desteklemez → fiyat sıralaması
   PHP usort'ta; filtrelenmiş sonuçta doğru sayfa sayısı için pagination
   da PHP'de (per_page = woocommerce_account_orders_per_page, 15).
   ═══════════════════════════════════════════════════════════ */

/** Sipariş filtre GET parametreleri (whitelist dışı değerler varsayılana döner). */
function sv41_orders_filters() {
	$period = sv41_param( 'sv_period' );
	if ( ! in_array( $period, array( '3m', '6m', '1y', 'all' ), true ) ) { $period = 'all'; }
	$sort = sv41_param( 'sv_sort' );
	if ( ! in_array( $sort, array( 'date-desc', 'date-asc', 'price-asc', 'price-desc' ), true ) ) { $sort = 'date-desc'; }
	return array(
		'q'      => sv41_param( 'sv_q' ),
		'period' => $period,
		'sort'   => $sort,
	);
}

/* Woo sorgusu tüm adayları getirsin; filtreleme/sayfalama template hazırlığında (sv41_orders_prepare). */
add_filter( 'woocommerce_my_account_my_orders_query', function ( $args ) {
	$args['limit'] = -1;
	$args['page']  = 1;
	return $args;
} );

/** Siparişin tarih damgası (date_created null guard'lı). */
function sv41_order_ts( $order ) {
	$d = $order->get_date_created();
	return $d ? $d->getTimestamp() : 0;
}

/**
 * Dönem + arama + sıralama + sayfalama. $orders = WC_Order[] (paginate object orders).
 * Döner: items (sayfalı WC_Order[]), total, max_pages, page, filters.
 */
function sv41_orders_prepare( $orders, $current_page ) {
	$filters  = sv41_orders_filters();
	$per_page = max( 1, (int) apply_filters( 'woocommerce_account_orders_per_page', 15 ) );
	$items    = array();

	foreach ( $orders as $order ) {
		if ( ! $order instanceof WC_Order ) { continue; }

		if ( 'all' !== $filters['period'] ) {
			$months = array( '3m' => 3, '6m' => 6, '1y' => 12 );
			$cutoff = ( new DateTimeImmutable( 'now', wp_timezone() ) )->modify( '-' . $months[ $filters['period'] ] . ' months' );
			if ( sv41_order_ts( $order ) < $cutoff->getTimestamp() ) { continue; }
		}

		if ( '' !== $filters['q'] ) {
			$needle    = mb_strtolower( $filters['q'], 'UTF-8' );
			$haystacks = array( (string) $order->get_order_number() );
			foreach ( $order->get_items() as $item ) {
				$haystacks[] = (string) $item->get_name();
			}
			$match = false;
			foreach ( $haystacks as $haystack ) {
				if ( str_contains( mb_strtolower( $haystack, 'UTF-8' ), $needle ) ) { $match = true; break; }
			}
			if ( ! $match ) { continue; }
		}

		$items[] = $order;
	}

	switch ( $filters['sort'] ) {
		case 'date-asc':
			usort( $items, function ( $a, $b ) { return sv41_order_ts( $a ) <=> sv41_order_ts( $b ); } );
			break;
		case 'price-asc':
			usort( $items, function ( $a, $b ) { return (float) $a->get_total() <=> (float) $b->get_total(); } );
			break;
		case 'price-desc':
			usort( $items, function ( $a, $b ) { return (float) $b->get_total() <=> (float) $a->get_total(); } );
			break;
		default:
			usort( $items, function ( $a, $b ) { return sv41_order_ts( $b ) <=> sv41_order_ts( $a ); } );
	}

	$total     = count( $items );
	$max_pages = max( 1, (int) ceil( $total / $per_page ) );
	$page      = min( max( 1, $current_page ), $max_pages );

	return array(
		'items'     => array_slice( $items, ( $page - 1 ) * $per_page, $per_page ),
		'total'     => $total,
		'max_pages' => $max_pages,
		'page'      => $page,
		'filters'   => $filters,
	);
}

/* ═══════════════════════════════════════════════════════════
   P56 — ADRES DEFTERİ (çoklu kayıtlı adres + varsayılan + sepet entegrasyonu)
   Veri modeli (DB şeması YOK — user meta):
   - sv_address_book    : dizi; her öğe = alan şeması (sv56_address_fields) + id + created
   - sv_default_address : string id; saklanan değer geçersizse en eski kayıt otomatik
                          varsayılan olur (ve kalıcılaşır). Defter boşsa meta silinir.
   Hesap: /my-account/adreslerim/ endpoint'i (nav'da edit-address yerine; §5a-b key
   listesi wc_get_account_menu_items kaynak kodundan doğrulandı). edit-address → 302
   adreslerim (template_redirect:30 — WC_Form_Handler::save_address:10'un ARKASINDAN;
   POST kayıtları kırılmaz, P50 edit-account deseni).
   Sepet: kayıtlı adres seçici (woocommerce_before_shipping_calculator — form
   DIŞINDA, görünür) + hesaplayıcıya kaydet UI'i (şablon override'ı:
   woocommerce/cart/shipping-calculator.php — Woo 9.7.0 temelli; checkbox/alanlar
   form İÇİNDE olmak zorunda: Woo'nun before/after hook'ları form dışına basar;
   P55'teki checkbox bu yüzden submit'e hiç gitmiyordu, nonce alan adı da 9.7+
   ile uyuşmuyordu — bkz. rapor).
   NOT (KULLANICI ADIMI): rewrite flush — Ayarlar → Kalıcı Bağlantılar → Kaydet.
   ═══════════════════════════════════════════════════════════ */

add_action( 'init', function () {
	add_rewrite_endpoint( 'adreslerim', EP_ROOT | EP_PAGES );
} );

/**
 * P58 adres alan şeması — WOO-NATIVE (sahibin kararı, 21-09-2026). SIRA bu şemadır:
 * Ad → Soyad → Firma(ops) → Adres Satırı 1 → Adres Satırı 2(ops) → İlçe/Semt →
 * Posta Kodu → Ülke(TR kilit) → Şehir → Telefon → etiket (→ varsayılan/kaydet
 * yalnız sepet formunda). Key'ler Woo kanonik alan adlarıdır: defter öğeleri
 * doğrudan Woo shipping_ ve billing_ key'leriyle eşleşir.
 * - TC Kimlik ve Mahalle/Köy KALDIRILDI; eski kayıtlarda bu key'ler varsa
 *   sessizce yok sayılır (migrasyon yok — sv56_addresses yeni şemayla okur).
 * - TR eşlemesi (P57 normalizasyonu korunur): 'state' = İl (defterde insan-okur
 *   İL ADI, customer'da Woo kanonik KOD), 'city' = İlçe/Semt (serbest metin).
 * - country sabit TR (dış satış yok); telefon zorunlu (şema: (ops) işaretsiz).
 */
function sv56_address_fields() {
	return array(
		'first_name' => array( 'Ad', true ),
		'last_name'  => array( 'Soyad', true ),
		'company'    => array( 'Firma', false ),
		'address_1'  => array( 'Adres Satırı 1', true ),
		'address_2'  => array( 'Adres Satırı 2', false ),
		'city'       => array( 'İlçe / Semt', true ),
		'postcode'   => array( 'Posta Kodu', true ),
		'country'    => array( 'Ülke', true ),
		'state'      => array( 'Şehir', true ),
		'phone'      => array( 'Telefon', true ),
		'label'      => array( 'Adres Etiketi', true ),
	);
}

/**
 * P57 — TR İl normalizasyonu. GERÇEKLİK (canlı kanıt): hesaplayıcı İl alanı seçimlidir ve
 * post değeri KOD'dur ('TR01'..'TR81' — canlı /cart/ HTML'inde 82 seçenek; P56 raporundaki
 * "TR state listesi YOK → text input" kanısı yanlıştı). İki yön için tek kaynak,
 * çalışma anında WC()->countries->get_states('TR') (listeyi kimin eklediğinden bağımsız):
 * DEFTER insan-okur İL ADI saklar ('İstanbul'); customer uygulamasında Woo'nun kanonik
 * KOD'una döner ('TR34') — hesabım formunun serbest metniyle tutarlı, kod/ad karışımı olmaz.
 */
function sv56_state_list() {
	if ( ! function_exists( 'WC' ) || ! WC()->countries ) { return array(); }
	$states = WC()->countries->get_states( 'TR' );
	return is_array( $states ) ? $states : array();
}

/** 'İstanbul' → 'TR34'; zaten kodsa / listede yoksa ham değer döner. */
function sv56_state_name_to_code( $value ) {
	$value  = trim( (string) $value );
	$states = sv56_state_list();
	$needle = mb_strtolower( $value, 'UTF-8' );
	foreach ( $states as $code => $name ) {
		if ( mb_strtolower( (string) $name, 'UTF-8' ) === $needle ) { return (string) $code; }
	}
	return $value;
}

/** 'TR34' → 'İstanbul'; zaten adsa / listede yoksa ham değer döner. */
function sv56_state_code_to_name( $value ) {
	$value  = trim( (string) $value );
	$states = sv56_state_list();
	if ( isset( $states[ $value ] ) ) { return (string) $states[ $value ]; }
	return $value;
}

/** Adres defteri (user meta sv_address_book). Bozulmuş öğeler sessizce elenir. */
function sv56_addresses( $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	if ( $user_id <= 0 ) { return array(); }
	$raw   = get_user_meta( $user_id, 'sv_address_book', true );
	$items = is_array( $raw ) ? $raw : array();
	$out   = array();
	foreach ( $items as $item ) {
		if ( ! is_array( $item ) || empty( $item['id'] ) || ! is_string( $item['id'] ) ) { continue; }
		$clean = array( 'id' => $item['id'] );
		foreach ( sv56_address_fields() as $field => $meta ) {
			$clean[ $field ] = isset( $item[ $field ] ) ? (string) $item[ $field ] : '';
		}
		$clean['created'] = isset( $item['created'] ) ? (int) $item['created'] : 0;
		$out[] = $clean;
	}
	return $out;
}

/** id ile tek kayıt; yoksa null. */
function sv56_find_address( $user_id, $id ) {
	foreach ( sv56_addresses( $user_id ) as $item ) {
		if ( $item['id'] === (string) $id ) { return $item; }
	}
	return null;
}

/**
 * Varsayılan adres id. Saklanan değer defterde yoksa en eski (ilk) kayıt
 * otomatik varsayılan olur ve meta kalıcılaşır (paketteki kural).
 */
function sv56_default_address_id( $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	if ( $user_id <= 0 ) { return ''; }
	$items = sv56_addresses( $user_id );
	if ( empty( $items ) ) {
		if ( '' !== (string) get_user_meta( $user_id, 'sv_default_address', true ) ) {
			delete_user_meta( $user_id, 'sv_default_address' );
		}
		return '';
	}
	$stored = (string) get_user_meta( $user_id, 'sv_default_address', true );
	foreach ( $items as $item ) {
		if ( $item['id'] === $stored ) { return $stored; }
	}
	update_user_meta( $user_id, 'sv_default_address', $items[0]['id'] );
	return $items[0]['id'];
}

/** id defterde varsa varsayılan yapar; yoksa false (yetki/kayıt kontrolü). */
function sv56_set_default_address( $user_id, $id ) {
	foreach ( sv56_addresses( $user_id ) as $item ) {
		if ( $item['id'] === (string) $id ) {
			update_user_meta( $user_id, 'sv_default_address', $item['id'] );
			return true;
		}
	}
	return false;
}

/**
 * Girdi dizisini paket şemasına göre temizler. $values ham dizi (POST slash'li olabilir;
 * wc_clean kendi içinde wp_unslash yapar). Döner: array( 'data' => öğe, 'errors' => alan=>true ).
 * Ülke sabit TR. P58: İl ('state') KOD geldiyse insan-okur İL ADI'na çevrilir (P57
 * normalizasyonu); 'city' = İlçe/Semt serbest metindir; TC alanı şemadan çıktı.
 */
function sv56_sanitize_address_data( $values ) {
	$errors = array();
	$data   = array();
	foreach ( sv56_address_fields() as $field => $meta ) {
		if ( 'country' === $field ) { $data['country'] = 'TR'; continue; }
		$value = wc_clean( isset( $values[ $field ] ) ? $values[ $field ] : '' );
		if ( 'phone' === $field ) {
			$value = wp_check_invalid_utf8( preg_replace( '/[^0-9+\s()-]/', '', (string) $value ) );
		}
		if ( 'state' === $field ) {
			/* P58: hesaplayıcı İl select'i KOD post'lar ('TR34') → deftere İL ADI. */
			$value = sv56_state_code_to_name( $value );
		}
		if ( $meta[1] && '' === trim( (string) $value ) ) { $errors[ $field ] = true; }
		$data[ $field ] = (string) $value;
	}
	return array( 'data' => $data, 'errors' => $errors );
}

/**
 * Adresi deftere yazar (yeni veya id ile güncelleme). Güncellemede id defterde yoksa
 * null (kayıt/yetki kontrolü — yalnız KENDİ adresleri yazılabilir). Döner: id.
 */
function sv56_save_address( $user_id, $data, $id = '' ) {
	$items = sv56_addresses( $user_id );
	$now   = time();
	if ( '' === (string) $id ) {
		$id      = 'sva_' . uniqid() . '_' . (string) wp_rand( 100, 999 );
		$items[] = array_merge( $data, array( 'id' => $id, 'created' => $now ) );
	} else {
		$found = false;
		foreach ( $items as $k => $item ) {
			if ( $item['id'] === (string) $id ) {
				$created     = $item['created'] ? (int) $item['created'] : $now;
				$items[ $k ] = array_merge( $data, array( 'id' => $item['id'], 'created' => $created ) );
				$found       = true;
				break;
			}
		}
		if ( ! $found ) { return null; }
	}
	update_user_meta( $user_id, 'sv_address_book', $items );
	return $id;
}

/** Adresi siler; silinen varsayılanın yerine en eski kayıt otomatik varsayılan olur. */
function sv56_delete_address( $user_id, $id ) {
	$items = sv56_addresses( $user_id );
	$out   = array();
	foreach ( $items as $item ) {
		if ( $item['id'] !== (string) $id ) { $out[] = $item; }
	}
	if ( count( $out ) === count( $items ) ) { return false; }
	update_user_meta( $user_id, 'sv_address_book', $out );
	if ( (string) get_user_meta( $user_id, 'sv_default_address', true ) === (string) $id ) {
		if ( empty( $out ) ) {
			delete_user_meta( $user_id, 'sv_default_address' );
		} else {
			update_user_meta( $user_id, 'sv_default_address', $out[0]['id'] );
		}
	}
	return true;
}

/**
 * Adresi WC()->customer gönderim alanlarına uygular (sepet/checkout anında doğru).
 * TR eşlemesi (Woo TR locale — kaynak kodla doğrulandı): İl = shipping_state,
 * İlçe = shipping_city. P58: Ad/Soyad/Firma da yazılır (checkout prefill dolu gelsin).
 * Telefon set_shipping_phone (Woo 5.6+; varsa yazılır).
 */
function sv56_apply_address_to_customer( $address ) {
	if ( ! is_array( $address ) || ! function_exists( 'WC' ) || ! WC()->customer ) { return false; }
	$c = WC()->customer;
	$c->set_shipping_country( 'TR' );
	if ( method_exists( $c, 'set_shipping_first_name' ) ) {
		$c->set_shipping_first_name( isset( $address['first_name'] ) ? (string) $address['first_name'] : '' );
	}
	if ( method_exists( $c, 'set_shipping_last_name' ) ) {
		$c->set_shipping_last_name( isset( $address['last_name'] ) ? (string) $address['last_name'] : '' );
	}
	if ( method_exists( $c, 'set_shipping_company' ) ) {
		$c->set_shipping_company( isset( $address['company'] ) ? (string) $address['company'] : '' );
	}
	/* P57'den korunur: defterdeki İL ADI Woo'nun kanonik İl KODU'na döner (calc/checkout select'leri kod bekler). */
	$c->set_shipping_state( isset( $address['state'] ) ? sv56_state_name_to_code( (string) $address['state'] ) : '' );
	$c->set_shipping_city( isset( $address['city'] ) ? (string) $address['city'] : '' );
	$c->set_shipping_postcode( isset( $address['postcode'] ) ? (string) $address['postcode'] : '' );
	$c->set_shipping_address_1( isset( $address['address_1'] ) ? (string) $address['address_1'] : '' );
	$c->set_shipping_address_2( isset( $address['address_2'] ) ? (string) $address['address_2'] : '' );
	if ( method_exists( $c, 'set_shipping_phone' ) ) {
		$c->set_shipping_phone( isset( $address['phone'] ) ? (string) $address['phone'] : '' );
	}
	$c->save();
	return true;
}

/** Adresin müşterinin SEÇİLİ gönderim adresiyle birebir eşleşmesi (sepet seçici ön-seçim). */
function sv56_address_matches_customer( $address ) {
	if ( ! is_array( $address ) || ! function_exists( 'WC' ) || ! WC()->customer ) { return false; }
	$c     = WC()->customer;
	/* P57: İl karşılaştırması iki tarafta da İL ADI üzerinden (customer'da kod saklanır → ada çevrilir).
	 * P58 key'leri: state = İl, city = İlçe/Semt. */
	$pairs = array(
		array( (string) $address['state'], sv56_state_code_to_name( (string) $c->get_shipping_state() ) ),
		array( (string) $address['city'], (string) $c->get_shipping_city() ),
		array( (string) $address['postcode'], (string) $c->get_shipping_postcode() ),
		array( (string) $address['address_1'], (string) $c->get_shipping_address_1() ),
	);
	foreach ( $pairs as $p ) {
		if ( '' === $p[0] || $p[0] !== $p[1] ) { return false; }
	}
	return true;
}

/** Tek satır adres özeti (kart + sepet seçici). P58: Mahalle çıktı; İlçe→Şehir sırası. */
function sv56_address_summary( $address ) {
	$parts = array();
	foreach ( array( 'address_1', 'address_2', 'city', 'state', 'postcode' ) as $field ) {
		if ( isset( $address[ $field ] ) && '' !== (string) $address[ $field ] ) { $parts[] = (string) $address[ $field ]; }
	}
	return implode( ', ', $parts );
}

/**
 * P56 hesap POST işleyicisi (Adreslerim: kaydet / varsayılan / sil).
 * Yetkisiz / form'suz / nonce'suz istek sessizce bırakılır; yetki = giriş + current_user_can('read')
 * + endpoint + kayıt sahipliği (id kullanıcının KENDİ defterinde olmak zorunda).
 */
add_action( 'template_redirect', 'sv56_handle_account_address_forms' );
function sv56_handle_account_address_forms() {
	if ( 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? (string) $_SERVER['REQUEST_METHOD'] : '' ) ) { return; }
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() || ! is_user_logged_in() || ! current_user_can( 'read' ) ) { return; }
	/* P57 FIX: guard is_wc_endpoint_url('adreslerim') yerine query_vars kontrolü.
	 * KÖK NEDEN (Woo 11.1.0 kaynak kanıtı): is_wc_endpoint_url() (wc-conditional-functions.php:166-175)
	 * endpoint adını ÖNCE WC()->query->get_query_vars()'ta arar; orada yoksa DAİMA false döner.
	 * add_rewrite_endpoint() ile kaydedilen özel endpoint'ler ('adreslerim', 'iletisim-tercihleri')
	 * Woo'nun o listesinde YOKTUR → bu işleyici hiç çalışmıyordu: POST sessizce düşüyordu
	 * (kayıt YOK, redirect YOK, notice YOK — sahibin raporuyla birebir).
	 * Sayfanın GÖRÜNMESİ kandırmaz: içerik basımı farklı kapıdır — woocommerce_account_content()
	 * (wc-template-functions.php:3795-3808) $wp->query_vars + has_action('..._endpoint') ile
	 * özel endpoint'leri de basar. Doğru guard, çekirdeğin kendi dispatch mekanizmasıyla
	 * birebir aynıdır: query_vars'ta key var mı? */
	global $wp;
	if ( ! isset( $wp->query_vars ) || ! is_array( $wp->query_vars ) || ! array_key_exists( 'adreslerim', $wp->query_vars ) ) { return; }

	$nonce_map = array(
		'address_save'    => 'sv_address_save',
		'address_default' => 'sv_address_default',
		'address_delete'  => 'sv_address_delete',
	);
	$form = sv41_param( 'sv_form', 'post' );
	if ( ! isset( $nonce_map[ $form ] ) ) { return; }
	if ( ! wp_verify_nonce( sv41_param( 'sv_nonce', 'post' ), $nonce_map[ $form ] ) ) {
		sv41_redirect_notice( 'error_nonce', 'adreslerim' );
	}

	$user_id = get_current_user_id();

	switch ( $form ) {
		case 'address_save':
			$edit_id = sv41_param( 'sv_address_id', 'post' );
			$values  = array();
			foreach ( sv56_address_fields() as $field => $meta ) {
				$values[ $field ] = isset( $_POST[ 'sv_addr_' . $field ] ) ? $_POST[ 'sv_addr_' . $field ] : '';
			}
			$parsed = sv56_sanitize_address_data( $values );
			if ( ! empty( $parsed['errors'] ) ) {
				sv41_redirect_notice( 'error_address', 'adreslerim' );
			}
			$new_id = sv56_save_address( $user_id, $parsed['data'], '' === $edit_id ? '' : $edit_id );
			if ( null === $new_id ) { sv41_redirect_notice( 'error_address_notfound', 'adreslerim' ); }
			/* Kaydedilen adres varsayılan ise sepet/checkout anında doğru olsun (paket şartı). */
			if ( sv56_default_address_id( $user_id ) === $new_id ) {
				sv56_apply_address_to_customer( sv56_find_address( $user_id, $new_id ) );
			}
			sv41_redirect_notice( '' === $edit_id ? 'address_saved' : 'address_updated', 'adreslerim' );
			break;

		case 'address_default':
			$id = sv41_param( 'sv_address_id', 'post' );
			if ( ! sv56_set_default_address( $user_id, $id ) ) { sv41_redirect_notice( 'error_address_notfound', 'adreslerim' ); }
			sv56_apply_address_to_customer( sv56_find_address( $user_id, $id ) );
			sv41_redirect_notice( 'address_default', 'adreslerim' );
			break;

		case 'address_delete':
			$id = sv41_param( 'sv_address_id', 'post' );
			if ( ! sv56_delete_address( $user_id, $id ) ) { sv41_redirect_notice( 'error_address_notfound', 'adreslerim' ); }
			sv41_redirect_notice( 'address_deleted', 'adreslerim' );
			break;
	}
}

/* P56: edit-address endpoint'i kaldırıldı — doğrudan erişim Adreslerim'e yönlenir.
 * template_redirect:30 → WC_Form_Handler::save_address (öncelik 10) POST'u ÖNCE işler
 * (nonce: woocommerce-edit_address); kayıt kırılmaz. P50 edit-account deseni. */
add_action( 'template_redirect', function () {
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'edit-address' ) ) {
		wp_safe_redirect( sv41_myaccount_url( 'adreslerim' ) );
		exit;
	}
}, 30 );

/**
 * Adreslerim sayfası içeriği: kart listesi + Yeni/Düzenle formu (?duzenle=id|yeni)
 * + silme onayı (?sil=id — iki adımlı, JS'siz onay).
 */
add_action( 'woocommerce_account_adreslerim_endpoint', 'sv56_address_book_content' );
function sv56_address_book_content() {
	$user_id = get_current_user_id();
	$items   = sv56_addresses( $user_id );
	$def_id  = sv56_default_address_id( $user_id );
	$notices = sv41_notices();
	$notice  = sv41_current_notice();

	$duzenle  = sv41_param( 'duzenle' );
	$sil      = sv41_param( 'sil' );
	$editing  = null;
	$base_url = sv41_myaccount_url( 'adreslerim' );

	if ( '' !== $duzenle && 'yeni' !== $duzenle ) {
		$editing = sv56_find_address( $user_id, $duzenle );
		if ( null === $editing ) { $duzenle = ''; }
	}
	$confirm_delete = ( '' !== $sil ) ? sv56_find_address( $user_id, $sil ) : null;
	?>
	<h1 class="sv-account-title">Adreslerim</h1>

	<?php if ( '' !== $notice && isset( $notices[ $notice ] ) ) : ?>
		<div class="sv-account-notice" role="status"><?php echo esc_html( $notices[ $notice ] ); ?></div>
	<?php endif; ?>

	<?php if ( $confirm_delete ) : ?>
		<section class="sv-account-section sv56-addr-confirm">
			<h2>&quot;<?php echo esc_html( $confirm_delete['label'] ); ?>&quot; adresi silinsin mi?</h2>
			<p class="sv56-addr-confirm__summary"><?php echo esc_html( sv56_address_summary( $confirm_delete ) ); ?></p>
			<form method="post" action="<?php echo esc_url( $base_url ); ?>" class="sv56-addr-confirm__actions">
				<input type="hidden" name="sv_form" value="address_delete">
				<input type="hidden" name="sv_address_id" value="<?php echo esc_attr( $confirm_delete['id'] ); ?>">
				<?php wp_nonce_field( 'sv_address_delete', 'sv_nonce' ); ?>
				<button type="submit" class="button sv56-addr-confirm__yes">Evet, sil</button>
				<a class="sv-btn-outline" href="<?php echo esc_url( $base_url ); ?>">Vazgeç</a>
			</form>
		</section>
	<?php endif; ?>

	<?php if ( '' !== $duzenle ) : ?>
		<section class="sv-account-section">
			<h2><?php echo $editing ? 'Adresi Düzenle' : 'Yeni Adres Ekle'; ?></h2>
			<p class="sv-account-section__hint">Teslimat adresiniz; sipariş gönderiminde kullanılır. * işaretli alanlar zorunludur.</p>
			<form class="sv-account-form sv56-addr-form" method="post" action="<?php echo esc_url( $base_url ); ?>">
				<?php foreach ( sv56_address_fields() as $field => $meta ) : ?>
					<?php if ( 'country' === $field ) : ?>
						<div class="form-row sv56-addr-row--country">
							<label for="sv_addr_country">Ülke</label>
							<input type="text" id="sv_addr_country" value="Türkiye" readonly class="input-text sv56-addr-country">
							<input type="hidden" name="sv_addr_country" value="TR">
							<span class="sv-account-field__hint">Dış satış yapmıyoruz; teslimat yalnızca Türkiye içindir.</span>
						</div>
					<?php elseif ( 'state' === $field ) :
						/* P59-fix: P58 sonrası ŞEHİR'in key'i 'state' (city=İlçe/Semt).
						 * Şehir = TR il listesinden select (checkout ile aynı kaynak); mevcut
						 * kayıt listede yoksa (eski serbest metin) korumak için ek seçenek basılır. */
						$sv_current_state = $editing ? (string) $editing[ $field ] : '';
						$sv_cities        = ( function_exists( 'WC' ) && WC()->countries ) ? WC()->countries->get_states( 'TR' ) : array();
						?>
						<div class="form-row">
							<label for="sv_addr_state">
								<?php echo esc_html( $meta[0] ); ?><?php echo $meta[1] ? ' <span class="sv-req" aria-hidden="true">*</span>' : ''; ?>
							</label>
							<select class="input-select" name="sv_addr_state" id="sv_addr_state">
								<option value="">Şehir seçin…</option>
								<?php foreach ( $sv_cities as $sv_state_name ) : ?>
									<option value="<?php echo esc_attr( $sv_state_name ); ?>" <?php selected( $sv_current_state, $sv_state_name ); ?>><?php echo esc_html( $sv_state_name ); ?></option>
								<?php endforeach; ?>
								<?php if ( '' !== $sv_current_state && ! in_array( $sv_current_state, $sv_cities, true ) ) : ?>
									<option value="<?php echo esc_attr( $sv_current_state ); ?>" selected><?php echo esc_html( $sv_current_state ); ?></option>
								<?php endif; ?>
							</select>
						</div>
					<?php else : ?>
						<div class="form-row">
							<label for="sv_addr_<?php echo esc_attr( $field ); ?>">
								<?php echo esc_html( $meta[0] ); ?><?php echo $meta[1] ? ' <span class="sv-req" aria-hidden="true">*</span>' : ' <span class="sv-account-field__opt">(opsiyonel)</span>'; ?>
							</label>
							<input type="<?php echo esc_attr( 'phone' === $field ? 'tel' : 'text' ); ?>" class="input-text"
								name="sv_addr_<?php echo esc_attr( $field ); ?>" id="sv_addr_<?php echo esc_attr( $field ); ?>"
								value="<?php echo esc_attr( $editing ? $editing[ $field ] : '' ); ?>"
								<?php echo 'phone' === $field ? 'placeholder="+90 5XX XXX XX XX" autocomplete="tel"' : ''; ?>
								<?php echo 'first_name' === $field ? 'autocomplete="given-name"' : ''; ?>
								<?php echo 'last_name' === $field ? 'autocomplete="family-name"' : ''; ?>
								<?php echo 'company' === $field ? 'autocomplete="organization"' : ''; ?>>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<input type="hidden" name="sv_address_id" value="<?php echo esc_attr( $editing ? $editing['id'] : '' ); ?>">
				<input type="hidden" name="sv_form" value="address_save">
				<?php wp_nonce_field( 'sv_address_save', 'sv_nonce' ); ?>
				<button type="submit" class="button sv-account-form__submit">Kaydet</button>
				<a class="sv56-addr-cancel" href="<?php echo esc_url( $base_url ); ?>">Vazgeç</a>
			</form>
		</section>
	<?php else : ?>
		<p class="sv-account-intro">Kayıtlı teslimat adresleriniz; sepet ve ödeme adımında seçmek üzere burada saklanır.</p>
		<a class="sv56-addr-new button" href="<?php echo esc_url( add_query_arg( 'duzenle', 'yeni', $base_url ) ); ?>">Yeni Adres Ekle</a>

		<?php if ( empty( $items ) ) : ?>
			<p class="sv56-addr-empty">Henüz kayıtlı adresiniz yok. &quot;Yeni Adres Ekle&quot; ile ilk adresinizi kaydedin — ilk adresiniz varsayılan olarak atanır.</p>
		<?php else : ?>
			<div class="sv56-addr-cards">
				<?php foreach ( $items as $item ) : ?>
					<div class="sv56-addr-card<?php echo $item['id'] === $def_id ? ' is-default' : ''; ?>">
						<div class="sv56-addr-card__head">
							<span class="sv56-addr-card__label"><?php echo esc_html( $item['label'] ); ?></span>
							<?php if ( $item['id'] === $def_id ) : ?><span class="sv56-addr-badge">Varsayılan</span><?php endif; ?>
						</div>
						<div class="sv56-addr-card__body">
							<p class="sv56-addr-card__name"><?php echo esc_html( trim( $item['first_name'] . ' ' . $item['last_name'] ) ); ?> · <?php echo esc_html( $item['phone'] ); ?></p>
							<p class="sv56-addr-card__summary"><?php echo esc_html( sv56_address_summary( $item ) ); ?></p>
						</div>
						<div class="sv56-addr-card__actions">
							<a class="sv56-addr-action" href="<?php echo esc_url( add_query_arg( 'duzenle', $item['id'], $base_url ) ); ?>">Düzenle</a>
							<?php if ( $item['id'] !== $def_id ) : ?>
								<form class="sv56-addr-action-form" method="post" action="<?php echo esc_url( $base_url ); ?>">
									<input type="hidden" name="sv_form" value="address_default">
									<input type="hidden" name="sv_address_id" value="<?php echo esc_attr( $item['id'] ); ?>">
									<?php wp_nonce_field( 'sv_address_default', 'sv_nonce' ); ?>
									<button type="submit" class="sv56-addr-action">Varsayılan Yap</button>
								</form>
							<?php endif; ?>
							<a class="sv56-addr-action sv56-addr-action--danger" href="<?php echo esc_url( add_query_arg( 'sil', $item['id'], $base_url ) ); ?>">Sil</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif;
}

/* ═══════════════════════════════════════════════════════════
   P56 — SEPET ENTEGRASYONU
   1) Kayıtlı adres seçici: woocommerce_before_shipping_calculator (Woo 9.7.0 şablonunda
      bu hook <form> DIŞINA basar → görünür "hesaplayıcı üstü" konumu). Radio + Uygula
      POST'u (nonce sv_address_apply) → seçilen adres WC()->customer shipping alanlarına
      yazılır (İl→state, İlçe→city, postcode, adres satırları, telefon) + save().
   2) Hesaplayıcıya kaydet UI'i şablon override'ındadır (alanlar form İÇİNDE olmak
      zorunda). İşleyici: sv56_handle_cart_save_address (wp_loaded:30 — Woo kendi calc
      işleyicisini render anında WC_Shortcode_Cart::output içinde çalıştırır; wp_loaded
      POST'u okur, çıktıya karışmaz). P55'teki doğrudan shipping_* user-meta persist
      KALDIRILDI — adres defteri tek kaynak.
   Misafirlerde iki akış da hiç render edilmez/çalışmaz.
   ═══════════════════════════════════════════════════════════ */

/* Sepette kayıtlı adres seçici (girişli + defter doluysa görünür). */
add_action( 'woocommerce_before_shipping_calculator', 'sv56_cart_address_selector' );
function sv56_cart_address_selector() {
	if ( ! is_user_logged_in() ) { return; }
	$user_id = get_current_user_id();
	$items   = sv56_addresses( $user_id );
	if ( empty( $items ) ) { return; }

	/* Ön-seçim: seansın seçili gönderim adresiyle birebir eşleşen kayıt; yoksa varsayılan. */
	$preselect = '';
	foreach ( $items as $item ) {
		if ( sv56_address_matches_customer( $item ) ) { $preselect = $item['id']; break; }
	}
	if ( '' === $preselect ) { $preselect = sv56_default_address_id( $user_id ); }
	?>
	<div class="sv56-ship-selector">
		<h3 class="sv56-ship-selector__title">Kayıtlı Adresinize Gönder</h3>
		<form class="sv56-ship-selector__form" method="post" action="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ) ); ?>">
			<div class="sv56-ship-selector__list" role="radiogroup" aria-label="Teslimat adresi seçimi">
				<?php foreach ( $items as $item ) : ?>
					<label class="sv56-ship-option">
						<input type="radio" name="sv_apply_address" value="<?php echo esc_attr( $item['id'] ); ?>" <?php checked( $item['id'], $preselect ); ?>>
						<span class="sv56-ship-option__body">
							<strong class="sv56-ship-option__label"><?php echo esc_html( $item['label'] ); ?></strong>
							<span class="sv56-ship-option__summary"><?php echo esc_html( sv56_address_summary( $item ) ); ?></span>
						</span>
					</label>
				<?php endforeach; ?>
			</div>
			<input type="hidden" name="sv_form" value="address_apply">
			<?php wp_nonce_field( 'sv_address_apply', 'sv_nonce' ); ?>
			<button type="submit" class="button sv56-ship-apply">Bu Adrese Gönder</button>
		</form>
	</div>
	<?php
}

/** Sepet akışlarında Woo oturum bildirimi + cart URL'e dönüş (PRG). */
function sv56_cart_notice_redirect( $key, $type = 'success' ) {
	$texts = sv41_notices();
	$msg   = isset( $texts[ $key ] ) ? $texts[ $key ] : '';
	if ( '' !== $msg && function_exists( 'wc_add_notice' ) ) {
		wc_add_notice( $msg, 'error' === $type ? 'error' : 'success' );
	}
	if ( function_exists( 'wc_get_cart_url' ) ) {
		wp_safe_redirect( wc_get_cart_url() );
	}
	exit;
}

/* Seçilen kayıtlı adresi sepette uygula (Uygula POST'u). */
add_action( 'template_redirect', 'sv56_handle_cart_apply' );
function sv56_handle_cart_apply() {
	if ( 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? (string) $_SERVER['REQUEST_METHOD'] : '' ) ) { return; }
	if ( ! is_user_logged_in() || ! current_user_can( 'read' ) ) { return; }
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) { return; }
	if ( 'address_apply' !== sv41_param( 'sv_form', 'post' ) ) { return; }
	if ( ! wp_verify_nonce( sv41_param( 'sv_nonce', 'post' ), 'sv_address_apply' ) ) {
		sv56_cart_notice_redirect( 'error_nonce', 'error' );
	}
	$address = sv56_find_address( get_current_user_id(), sv41_param( 'sv_apply_address', 'post' ) );
	if ( null === $address ) { sv56_cart_notice_redirect( 'error_address_notfound', 'error' ); }
	sv56_apply_address_to_customer( $address );
	sv56_cart_notice_redirect( 'address_applied' );
}

/**
 * Hesaplayıcıdaki adresi deftere kaydet ("Bu adresi hesabıma kaydet" + "Varsayılan yap").
 * Woo 9.7+ hesaplayıcı nonce'u 'woocommerce-shipping-calculator' (alan: woocommerce-shipping-calculator-nonce);
 * P55'in doğruladığı 'woocommerce-cart'/_wpnonce artık formda YOK. Woo'nun kendi fallback'iyle
 * aynı ikili kabul korunur. Çıkışta redirect YOK — Woo calc işleyicisi render anında çalışmaya
 * devam etsin diye (bildirimler sepet üstünde Woo oturum bildirimiyle basılır).
 *
 * P57 FIX'ler:
 * (1) Nonce başarısızlığı ARTIK SESSİZ DEĞİL — bayat sekme/cache'li sayfada 'Güncelle'
 *     hiçbir şey yapmıyordu (sahibin raporu; Woo'nun kendi calc kapısı da aynı POST'ta
 *     sessizce atlıyor → hiçbir notice yok). Görünür hata bildirimi basılır.
 * (2) İki checkbox da işaretsizse (yalnız hesaplama) görünür 'address_applied' bildirimi —
 *     buton asla ölü hissettirmesin.
 * (3) calc_shipping_state İl KODU post'lar → sanitize sv56_state_code_to_name ile deftere
 *     İL ADI yazar (bkz. sv56_state_list kanıt notu).
 * (4) P58: TC ve Mahalle alanları şemadan çıkarıldı; alan seti Woo-native key'ler
 *     (first_name/last_name/company/state/city) — bkz. sv56_address_fields().
 */
add_action( 'wp_loaded', 'sv56_handle_cart_save_address', 30 );
function sv56_handle_cart_save_address() {
	if ( empty( $_POST['calc_shipping'] ) || ! is_user_logged_in() || ! current_user_can( 'read' ) ) { return; }

	$nonce_value = isset( $_REQUEST['woocommerce-shipping-calculator-nonce'] ) ? (string) wp_unslash( $_REQUEST['woocommerce-shipping-calculator-nonce'] ) : ( isset( $_REQUEST['_wpnonce'] ) ? (string) wp_unslash( $_REQUEST['_wpnonce'] ) : '' );
	if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-shipping-calculator' ) && ! wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) {
		wc_add_notice( sv41_notices()['error_nonce'], 'error' );
		return;
	}

	$save_checked = isset( $_POST['sv_save_address'] );
	$make_default = isset( $_POST['sv_make_default'] );
	if ( ! $save_checked && ! $make_default ) {
		wc_add_notice( sv41_notices()['address_applied'], 'success' );
		return;
	}

	$label = wc_clean( isset( $_POST['sv_address_label'] ) ? $_POST['sv_address_label'] : '' );
	if ( '' === trim( (string) $label ) ) {
		wc_add_notice( sv41_notices()['error_address_label'], 'error' );
		return;
	}

	/* Hesaplayıcıdaki eşleme (Woo TR locale, P58 key'leri): calc_shipping_state = İl
	 * (POST'ta KOD → sanitize İL ADI yazar), calc_shipping_city = İlçe/Semt,
	 * calc_shipping_postcode = Posta. TC/Mahalle alanları şemadan çıktı. */
	$values = array(
		'label'      => $label,
		'first_name' => isset( $_POST['sv_addr_first_name'] ) ? $_POST['sv_addr_first_name'] : '',
		'last_name'  => isset( $_POST['sv_addr_last_name'] ) ? $_POST['sv_addr_last_name'] : '',
		'company'    => isset( $_POST['sv_addr_company'] ) ? $_POST['sv_addr_company'] : '',
		'address_1'  => isset( $_POST['sv_addr_address_1'] ) ? $_POST['sv_addr_address_1'] : '',
		'address_2'  => isset( $_POST['sv_addr_address_2'] ) ? $_POST['sv_addr_address_2'] : '',
		'city'       => isset( $_POST['calc_shipping_city'] ) ? $_POST['calc_shipping_city'] : '',
		'state'      => isset( $_POST['calc_shipping_state'] ) ? $_POST['calc_shipping_state'] : '',
		'postcode'   => isset( $_POST['calc_shipping_postcode'] ) ? $_POST['calc_shipping_postcode'] : '',
		'phone'      => isset( $_POST['sv_addr_phone'] ) ? $_POST['sv_addr_phone'] : '',
	);
	$parsed = sv56_sanitize_address_data( $values );
	if ( ! empty( $parsed['errors'] ) ) {
		wc_add_notice( sv41_notices()['error_address'], 'error' );
		return;
	}

	$new_id = sv56_save_address( get_current_user_id(), $parsed['data'] );
	if ( $make_default ) {
		sv56_set_default_address( get_current_user_id(), $new_id );
		sv56_apply_address_to_customer( sv56_find_address( get_current_user_id(), $new_id ) );
	}
	wc_add_notice( sv41_notices()['address_saved'], 'success' );
}

/* ═══════════════════════════════════════════════════════════
   P58 — CHECKOUT: WOO-NATIVE ALAN ŞEMASI + TEK-FORM AYNALAMA
   Alan şeması sv56_address_fields() ile aynı key/sıradadır (herkes; misafir dahil —
   şema veri katmanıdır, şablon UX'i ise yalnız girişlide değişir):
   - P57'nin shipping_neighborhood custom alanı ve mahalle prefill filtresi KALDIRILDI.
   - Fatura formunda Firma/Telefon YOK (şema; telefon aynalamayla gelir). E-posta
     çekirdek zorunlu alanı olarak kalır (hesap/sipariş e-postası).
   - Gönderimde Firma TR locale'de gizli geldiği için opsiyonel geri eklenir.
   - Ülke alanları tek seçenekli TR select (şema: "Ülke (TR kilit, readonly)").
     Mağaza ülke ayarına dokunulmaz (KDV/ülke ayarı LEGAL_REVIEW_REQUIRED kapısı).
   - TEK-FORM UX şablon katmanında: woocommerce/checkout/form-shipping.php +
     form-billing.php override'ları (girişli: "Teslimat Bilgileri" birincil form +
     "Faturayı da aynı adrese gönderilsin" checkbox'ı; misafir: çekirdek birebir).
   SUNUCU AYNALAMASI (veri bütünlüğü) — kaynak kanıtı (Woo 11.1.0):
   get_posted_data() çıktısı (filtre: :858) update_session → validate_checkout →
   create_order zincirine GEÇER (:1381-1411); create_order adresleri $data argümanından
   yazar (:435-445) → işaretliyken sipariş fatura adresi daima teslimat adresiyle dolu
   (e-arşiv/iade); validate_checkout da aynı veriyi doğrular → gizli fatura alanları
   hata üretmez. update_session aynı veriyle customer fatura alanlarını da günceller
   (set_customer_address_fields) → oturum tutarlı kalır.
   ═══════════════════════════════════════════════════════════ */
add_filter( 'woocommerce_checkout_fields', 'sv58_checkout_fields', 20, 1 );
function sv58_checkout_fields( $fields ) {
	if ( ! isset( $fields['shipping'] ) || ! is_array( $fields['shipping'] ) ) { return $fields; }

	/* P57 custom alanı kaldırılır (Mahalle şemadan çıktı). */
	unset( $fields['shipping']['shipping_neighborhood'] );

	/* Fatura formunda Firma ve Telefon YOK (şema). */
	if ( isset( $fields['billing'] ) && is_array( $fields['billing'] ) ) {
		unset( $fields['billing']['billing_company'] );
		unset( $fields['billing']['billing_phone'] );
	}

	/* Gönderimde Firma: TR locale gizli gelir → opsiyonel olarak geri eklenir. */
	if ( ! isset( $fields['shipping']['shipping_company'] ) ) {
		$fields['shipping']['shipping_company'] = array(
			'label'        => 'Firma',
			'type'         => 'text',
			'required'     => false,
			'class'        => array( 'form-row-wide' ),
			'autocomplete' => 'organization',
			'priority'     => 30,
		);
	}

	/* Şema etiket + sıra + zorunluluk (sv56_address_fields ile aynı insan-dili). */
	$sv_schema = array(
		'first_name' => array( 'Ad', 10, true ),
		'last_name'  => array( 'Soyad', 20, true ),
		'company'    => array( 'Firma', 30, false ),
		'address_1'  => array( 'Adres Satırı 1', 40, true ),
		'address_2'  => array( 'Adres Satırı 2', 50, false ),
		'city'       => array( 'İlçe / Semt', 60, true ),
		'postcode'   => array( 'Posta Kodu', 70, true ),
		'country'    => array( 'Ülke', 80, true ),
		'state'      => array( 'Şehir', 90, true ),
		'phone'      => array( 'Telefon', 100, true ),
	);
	foreach ( array( 'billing', 'shipping' ) as $sv_fs ) {
		if ( ! isset( $fields[ $sv_fs ] ) || ! is_array( $fields[ $sv_fs ] ) ) { continue; }
		foreach ( $sv_schema as $sv_key => $sv_def ) {
			$sv_full = $sv_fs . '_' . $sv_key;
			if ( ! isset( $fields[ $sv_fs ][ $sv_full ] ) ) { continue; }
			$fields[ $sv_fs ][ $sv_full ]['label']    = $sv_def[0];
			$fields[ $sv_fs ][ $sv_full ]['priority'] = $sv_def[1];
			if ( $sv_def[2] ) { $fields[ $sv_fs ][ $sv_full ]['required'] = true; }
		}
	}

	/* Ülke TR kilit: tek seçenekli select — POST daima TR. Çekirdek ülke select'iyle
	 * aynı input_class'lar korunur (selectWoo/state JS etkilenmez: tek seçenek). */
	foreach ( array( 'billing' => 'billing_country', 'shipping' => 'shipping_country' ) as $sv_fs => $sv_cc ) {
		if ( isset( $fields[ $sv_fs ][ $sv_cc ] ) ) {
			$fields[ $sv_fs ][ $sv_cc ]['type']    = 'select';
			$fields[ $sv_fs ][ $sv_cc ]['options'] = array( 'TR' => 'Türkiye' );
			$fields[ $sv_fs ][ $sv_cc ]['default'] = 'TR';
		}
	}

	return $fields;
}

/**
 * P58 sunucu aynalaması — "Faturayı da aynı adrese gönderilsin" işaretliyken (veya
 * işaretin hiç POST edilmediği bağlamlarda güvenlik ağı olarak) posted billing
 * alanlarını shipping alanlarından yazar. KURALLAR:
 * - Yalnız girişli kullanıcı (misafir çekirdek iki-form akışına dokunulmaz).
 * - İşaret kalktıysa (marker var, sv_invoice_same yok) aynalama YAPILMAZ — kullanıcı
 *   fatura adresini ayrı girmiştir.
 * - Gönderim adı boşsa aynalanmaz (sipariş faturası asla boşaltılmaz).
 * - E-posta ayrıca yedeklenir: çekirdek get_value'da e-posta fallback'i YOK
 *   (class-wc-customer.php:676-678) → gizli fatura formunda boş post'lanabilir.
 * - Checkbox durumu oturuma yazılır (doğrulama hatası dönüşünde korunur).
 */
function sv58_mirror_billing_from_shipping( $data ) {
	if ( ! is_array( $data ) || ! is_user_logged_in() ) { return $data; }

	$sv_form_ours = isset( $_POST['sv_invoice_same_present'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- işleyici yalnız checkout POST zincirinde çalışır
	$sv_checked   = isset( $_POST['sv_invoice_same'] ) && '1' === (string) wp_unslash( $_POST['sv_invoice_same'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( $sv_form_ours && function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( 'sv_invoice_same', $sv_checked ? 'yes' : 'no' );
	}
	if ( $sv_form_ours && ! $sv_checked ) { return $data; }

	$sv_source = isset( $data['shipping_first_name'] ) ? trim( (string) $data['shipping_first_name'] ) : '';
	if ( '' === $sv_source ) { return $data; }

	foreach ( array( 'first_name', 'last_name', 'address_1', 'address_2', 'city', 'postcode', 'state', 'phone' ) as $sv_key ) {
		$data[ 'billing_' . $sv_key ] = isset( $data[ 'shipping_' . $sv_key ] ) ? $data[ 'shipping_' . $sv_key ] : '';
	}
	$data['billing_country'] = isset( $data['shipping_country'] ) ? $data['shipping_country'] : 'TR';

	if ( empty( $data['billing_email'] ) ) {
		$sv_user = wp_get_current_user();
		if ( ! empty( $sv_user->user_email ) ) { $data['billing_email'] = (string) $sv_user->user_email; }
	}
	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'sv58_mirror_billing_from_shipping', 10, 1 );

/* ═══════════════════════════════════════════════════════════
   P75 — SEPET BAŞTAN TASARIM: KART GÖRÜNÜMÜ + ÜRÜN SEÇİMİ + BEKLEYEN ÜRÜNLER
   "Seç ve Öde": her sepet kartında checkbox (varsayılan işaretli); "Seçilenlerle
   Ödemeye Geç" seçilmeyenleri sepatten çıkarıp WC()->session 'sv_cart_parked'
   listesine taşır (misafir + üye; Woo session cookie), sonra checkout'a yönlenir.
   Bekleyen ürünler sepette ayrı bölümde "Sepete Geri Al" ile sepete döner.
   Güvenlik: her POST kendi nonce'u (sv_cart_selection / sv_restore_parked) +
   sanitize/validate; item key yalnız sunucunun ürettiği gerçek sepet key'iyle
   eşleşir (POST'tan key UYDURULAMAZ); payTR/checkout akışına kod DOKUNMAZ —
   yalnız sepetteki ürün kümesi değişir.
   Kaynak kanıtı (Woo 11.1.0): templates/cart/cart.php @11.0.0 — checkbox kapısı
   woocommerce_after_cart_item_name (td.product-name İÇİNDE); kupon/güncelle
   td.actions'ta; cart-totals.php — panel eki woocommerce_after_cart_totals
   (.cart_totals İÇİNDE); boş sepet ayrı şablon (cart-empty.php) olduğundan
   bekleyen bölüm woocommerce_cart_is_empty hook'unda da basılır.
   JS: inline vanilla (P67 deseni), fetch + JSON; JS yoksa buton checkout linki
   olarak kalır → tüm ürünlerle checkout (bilinçli graceful degradation).
   ═══════════════════════════════════════════════════════════ */

/** PHP 8.5 permalink tuzağı guard'ı (AGENTS §5.5): array dönen permalink'ü string'e indirir. */
function sv75_resolve_url( $url, $fallback ) {
	$url = is_array( $url ) ? (string) reset( $url ) : (string) $url;
	return '' !== $url ? $url : $fallback;
}

function sv75_cart_url() {
	$url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
	return sv75_resolve_url( $url, home_url( '/cart/' ) );
}

function sv75_checkout_url() {
	$url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
	return sv75_resolve_url( $url, home_url( '/checkout/' ) );
}

/** Bekleyen ürün listesi (oturum) — okuma anında beyaz-liste temizliği. */
function sv75_parked_items() {
	if ( ! function_exists( 'WC' ) || ! WC()->session ) { return array(); }
	$items = WC()->session->get( 'sv_cart_parked' );
	if ( ! is_array( $items ) ) { return array(); }
	$out = array();
	foreach ( $items as $entry ) {
		if ( ! is_array( $entry ) ) { continue; }
		$pid = isset( $entry['product_id'] ) ? absint( $entry['product_id'] ) : 0;
		$vid = isset( $entry['variation_id'] ) ? absint( $entry['variation_id'] ) : 0;
		$qty = isset( $entry['quantity'] ) ? absint( $entry['quantity'] ) : 0;
		if ( $pid < 1 || $qty < 1 ) { continue; }
		$out[] = array(
			'id'           => isset( $entry['id'] ) ? sanitize_text_field( (string) $entry['id'] ) : '',
			'product_id'   => $pid,
			'variation_id' => $vid,
			'variation'    => ( isset( $entry['variation'] ) && is_array( $entry['variation'] ) ) ? $entry['variation'] : array(),
			'quantity'     => $qty,
		);
	}
	return $out;
}

/** Bekleyen ürün listesini oturuma yazar. */
function sv75_parked_set( $items ) {
	if ( function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( 'sv_cart_parked', array_values( $items ) );
	}
}

/* ── P75: her sepet kartında "Ödemeye dahil et" checkbox'ı (varsayılan İŞARETLİ) ── */
add_action( 'woocommerce_after_cart_item_name', 'sv75_cart_item_checkbox', 10, 2 );
function sv75_cart_item_checkbox( $cart_item, $cart_item_key ) {
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) { return; }
	?>
	<label class="sv-select-item">
		<input type="checkbox" name="sv_cart_selected[]" value="<?php echo esc_attr( $cart_item_key ); ?>" checked="checked" />
		<span><?php esc_html_e( 'Ödemeye dahil et', 'sutre' ); ?></span>
	</label>
	<?php
}

/* ── P75: Sepet Özeti paneli eki — seçim sayacı + "Seçilenlerle Ödemeye Geç" +
   kupon/güncelle taşıma yuvaları (JS bunları td.actions'tan buraya taşır;
   JS yoksa yerinde kalır, işlevli). ── */
add_action( 'woocommerce_after_cart_totals', 'sv75_panel_actions' );
function sv75_panel_actions() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) { return; }
	$lines = count( (array) WC()->cart->get_cart() );
	?>
	<div class="sv-cart-actions">
		<p class="sv-pay-count" aria-live="polite"><?php echo esc_html( sprintf( '%d ürün ödemeye dahil edilecek', $lines ) ); ?></p>
		<a href="<?php echo esc_url( sv75_checkout_url() ); ?>" class="sv-pay-selected"><?php esc_html_e( 'Seçilenlerle Ödemeye Geç', 'sutre' ); ?></a>
		<div class="sv-coupon-slot"></div>
		<div class="sv-update-slot"></div>
	</div>
	<?php
}

/* ── P75: Bekleyen Ürünler bölümü — dolu sepette (woocommerce_after_cart) VE
   tüm ürünler park edilmişse boş sepet görünümünde (woocommerce_cart_is_empty).
   Statik guard çift basımı önler. ── */
add_action( 'woocommerce_after_cart', 'sv75_render_parked', 20 );
add_action( 'woocommerce_cart_is_empty', 'sv75_render_parked', 20 );
function sv75_render_parked() {
	static $done = false;
	if ( $done ) { return; }
	$done = true;
	$items = sv75_parked_items();
	if ( empty( $items ) ) { return; }
	?>
	<section class="sv-parked" aria-labelledby="sv-parked-title">
		<h2 class="sv-parked__title" id="sv-parked-title"><?php esc_html_e( 'Bekleyen Ürünler', 'sutre' ); ?></h2>
		<p class="sv-parked__desc"><?php esc_html_e( 'Ödemeye dahil etmediğin ürünler burada bekliyor. Dilediğinde sepete geri alabilirsin.', 'sutre' ); ?></p>
		<ul class="sv-parked__list">
			<?php foreach ( $items as $entry ) :
				$sv_product = wc_get_product( $entry['variation_id'] > 0 ? $entry['variation_id'] : $entry['product_id'] );
				if ( ! $sv_product instanceof WC_Product ) { continue; }
				$sv_name = $sv_product->get_name();
				$sv_link = $sv_product->is_visible() ? $sv_product->get_permalink() : '';
				$sv_img  = $sv_product->get_image( 'woocommerce_thumbnail' );
				?>
				<li class="sv-parked-card">
					<div class="sv-parked-card__thumb">
						<?php if ( '' !== $sv_link ) { echo '<a href="' . esc_url( $sv_link ) . '">' . wp_kses_post( $sv_img ) . '</a>'; } else { echo wp_kses_post( $sv_img ); } ?>
					</div>
					<div class="sv-parked-card__body">
						<h3 class="sv-parked-card__name">
							<?php if ( '' !== $sv_link ) { echo '<a href="' . esc_url( $sv_link ) . '">' . esc_html( $sv_name ) . '</a>'; } else { echo esc_html( $sv_name ); } ?>
						</h3>
						<p class="sv-parked-card__meta"><?php echo esc_html( sprintf( 'Adet: %d', $entry['quantity'] ) ); ?></p>
						<form method="post" action="<?php echo esc_url( sv75_cart_url() ); ?>" class="sv-parked-card__form">
							<?php wp_nonce_field( 'sv_restore_parked', 'sv_restore_nonce' ); ?>
							<input type="hidden" name="sv_restore_parked" value="1" />
							<input type="hidden" name="sv_park_id" value="<?php echo esc_attr( $entry['id'] ); ?>" />
							<button type="submit" class="sv-parked-card__btn"><?php esc_html_e( 'Sepete Geri Al', 'sutre' ); ?></button>
						</form>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
}

/* ── P75: "Seçilenlerle Ödemeye Geç" AJAX işleyicisi — seçilmeyenleri park eder.
   Key doğrulaması: POST gelen key yalnız sunucunun get_cart() ürettiği key'lerle
   eşleşir; uydurma key sessizce düşer. Tüm ürünler park edilirse redirect sepete
   döner (klasik boş sepet mesajı + bekleyen bölümü görünür); aksi hâlde checkout. ── */
add_action( 'wp_ajax_sv_park_unselected', 'sv75_ajax_park_unselected' );
add_action( 'wp_ajax_nopriv_sv_park_unselected', 'sv75_ajax_park_unselected' );
function sv75_ajax_park_unselected() {
	if ( ! check_ajax_referer( 'sv_cart_selection', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => 'Güvenlik doğrulaması başarısız.' ), 403 );
	}
	if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
		wp_send_json_error( array( 'message' => 'Sepet boş.' ), 400 );
	}
	$sv_selected = array();
	if ( isset( $_POST['selected'] ) && is_array( $_POST['selected'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- tek tek sanitize edilir
		foreach ( array_values( wp_unslash( $_POST['selected'] ) ) as $sv_key ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$sv_key = sanitize_text_field( (string) $sv_key );
			if ( '' !== $sv_key ) { $sv_selected[ $sv_key ] = true; }
		}
	}
	$sv_parked    = sv75_parked_items();
	$sv_remaining = 0;
	foreach ( WC()->cart->get_cart() as $sv_key => $sv_item ) {
		if ( isset( $sv_selected[ $sv_key ] ) ) { $sv_remaining++; continue; }
		$sv_parked[] = array(
			'id'           => md5( 'sv-park|' . $sv_key . '|' . microtime() . '|' . wp_rand() ),
			'product_id'   => isset( $sv_item['product_id'] ) ? absint( $sv_item['product_id'] ) : 0,
			'variation_id' => isset( $sv_item['variation_id'] ) ? absint( $sv_item['variation_id'] ) : 0,
			'variation'    => ( isset( $sv_item['variation'] ) && is_array( $sv_item['variation'] ) ) ? $sv_item['variation'] : array(),
			'quantity'     => isset( $sv_item['quantity'] ) ? max( 1, absint( $sv_item['quantity'] ) ) : 1,
		);
		WC()->cart->remove_cart_item( $sv_key );
	}
	sv75_parked_set( $sv_parked );
	WC()->cart->calculate_totals();
	wp_send_json_success( array(
		'redirect'  => $sv_remaining > 0 ? sv75_checkout_url() : sv75_cart_url(),
		'parked'    => count( $sv_parked ),
		'remaining' => $sv_remaining,
	) );
}

/* ── P75: "Sepete Geri Al" — bekleyen üründen sepete (PRG). Ürün stokta yoksa
   Woo'nun kendi hata bildirimine düşer ve ürün bekleyen listede KALIR (kayıp yok).
   P57 dersi: nonce başarısızlığı SESSİZ bırakılmaz — görünür bildirim basılır. ── */
add_action( 'template_redirect', 'sv75_handle_restore', 20 );
function sv75_handle_restore() {
	if ( empty( $_POST['sv_restore_parked'] ) ) { return; } // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce bir sonraki satırda doğrulanır
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) { return; }
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) { return; }
	if ( ! isset( $_POST['sv_restore_nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['sv_restore_nonce'] ) ), 'sv_restore_parked' ) ) {
		wc_add_notice( 'Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.', 'error' );
		return;
	}
	$sv_park_id = isset( $_POST['sv_park_id'] ) ? sanitize_text_field( wp_unslash( $_POST['sv_park_id'] ) ) : '';
	$sv_items   = sv75_parked_items();
	$sv_found   = null;
	$sv_rest    = array();
	foreach ( $sv_items as $sv_entry ) {
		if ( null === $sv_found && '' !== $sv_entry['id'] && $sv_entry['id'] === $sv_park_id ) { $sv_found = $sv_entry; continue; }
		$sv_rest[] = $sv_entry;
	}
	if ( null === $sv_found ) {
		wc_add_notice( 'Bekleyen ürün bulunamadı. Sepet sayfasını yenileyip tekrar deneyin.', 'error' );
		wp_safe_redirect( sv75_cart_url() );
		exit;
	}
	$sv_added = WC()->cart->add_to_cart( $sv_found['product_id'], $sv_found['quantity'], $sv_found['variation_id'], $sv_found['variation'] );
	if ( $sv_added ) {
		sv75_parked_set( $sv_rest );
		wc_add_notice( 'Ürün sepete geri alındı.', 'success' );
	}
	wp_safe_redirect( sv75_cart_url() );
	exit;
}

/* ── P75: inline vanilla JS (yalnız sepet) — kupon + güncelle butonunu özet
   paneline taşır (form dışına çıkan alanlar `form` niteliğiyle ilişkilendirilir),
   seçim sayacını tutar, "Seçilenlerle Ödemeye Geç" için fetch → JSON redirect
   yapar. Hata halinde native href fallback (tüm ürünlerle checkout). ── */
add_action( 'wp_print_footer_scripts', 'sv75_cart_scripts' );
function sv75_cart_scripts() {
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) { return; }
	$sv_ajax  = admin_url( 'admin-ajax.php' );
	$sv_nonce = wp_create_nonce( 'sv_cart_selection' );
	?>
	<script>
	(function () {
		'use strict';
		var form = document.querySelector('form.woocommerce-cart-form');
		if (!form) { return; }
		var cfg = { ajax: <?php echo wp_json_encode( $sv_ajax ); ?>, nonce: <?php echo wp_json_encode( $sv_nonce ); ?> };
		if (!form.id) { form.id = 'sv-cart-form'; }
		var fid = form.id;

		/* Kupon + Güncelle → özet paneli. Form dışına çıkan input/button `form`
		   niteliğiyle ilişkilendirilir (native submit bozulmaz); JS yoksa öğeler
		   native yerinde kalır ve çalışır. */
		function assoc(el) { if (el) { el.setAttribute('form', fid); } }
		var coupon = form.querySelector('.coupon');
		var couponSlot = document.querySelector('.sv-coupon-slot');
		if (coupon && couponSlot) {
			coupon.querySelectorAll('input,button,select,textarea').forEach(assoc);
			var det = document.createElement('details');
			det.className = 'sv-coupon-details';
			var sum = document.createElement('summary');
			sum.textContent = 'Kupon kodunuz var mı?';
			det.appendChild(sum);
			det.appendChild(coupon);
			couponSlot.appendChild(det);
		}
		var update = form.querySelector('button[name="update_cart"]');
		var updateSlot = document.querySelector('.sv-update-slot');
		if (update && updateSlot) { assoc(update); updateSlot.appendChild(update); }
		var actions = form.querySelector('td.actions');
		if (actions) {
			var left = Array.prototype.some.call(actions.children, function (c) {
				return !(c.tagName === 'INPUT' && c.type === 'hidden');
			});
			if (!left) { actions.classList.add('sv-actions-drained'); }
		}

		/* Seçim sayacı. */
		var boxes = form.querySelectorAll('input[name="sv_cart_selected[]"]');
		var count = document.querySelector('.sv-pay-count');
		var pay = document.querySelector('a.sv-pay-selected');
		function refreshCount() {
			if (!count) { return; }
			var n = 0;
			boxes.forEach(function (b) { if (b.checked) { n++; } });
			count.textContent = n > 0
				? n + ' ürün ödemeye dahil edilecek'
				: 'Seçili ürün yok — tüm ürünler bekleyenlere geçecek';
		}
		boxes.forEach(function (b) { b.addEventListener('change', refreshCount); });
		refreshCount();

		if (!pay || !boxes.length) { return; }
		pay.addEventListener('click', function (ev) {
			ev.preventDefault();
			var fd = new FormData();
			fd.append('action', 'sv_park_unselected');
			fd.append('nonce', cfg.nonce);
			boxes.forEach(function (b) { if (b.checked) { fd.append('selected[]', b.value); } });
			pay.classList.add('is-busy');
			pay.setAttribute('aria-busy', 'true');
			fetch(cfg.ajax, { method: 'POST', credentials: 'same-origin', body: fd })
				.then(function (r) { return r.json(); })
				.then(function (j) {
					if (j && j.success && j.data && j.data.redirect) {
						window.location.href = j.data.redirect;
						return;
					}
					throw new Error('park-failed');
				})
				.catch(function () {
					window.location.href = pay.getAttribute('href');
				});
		});
	})();
	</script>
	<?php
}

