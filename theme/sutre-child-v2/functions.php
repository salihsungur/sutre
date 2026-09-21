<?php
/**
 * Sutre v3 — functions.php (quiet luxury tasarım sistemi)
 * Asset enqueue + WooCommerce (block template kapalı, klasik şablonlar, wrapper, sidebar yok) + shop banner + scroll reveal.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SUTRE_VERSION', '3.4.6' );

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
 * domain farketmez: woo blocks kendi domain'ini kullanabiliyor) ── */
add_filter( 'gettext', function ( $translated, $text, $domain ) {
	$map = array(
		'Your cart is currently empty!'       => 'Sepetin şu an boş.',
		'New in store'                        => 'Mağazada yeni',
		'Return to shop'                      => 'Alışverişe devam et',
		'View my shopping cart'               => 'Sepeti görüntüle',
	);
	return $map[ $text ] ?? $translated;
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

/* ── Placeholder görsel: Woo core'dan ── */
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
			/* Tercih formu yalnız kendi endpoint'inden işlenir (ekstra yetki sınırı). */
			if ( ! ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'iletisim-tercihleri' ) ) ) { return; }
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

/* Hesap nav: İletişim Tercihleri — Siparişlerim'den hemen sonra. */
add_filter( 'woocommerce_account_menu_items', function ( $items ) {
	$pref_key = 'iletisim-tercihleri';
	if ( isset( $items[ $pref_key ] ) ) { return $items; }
	$new = array();
	foreach ( $items as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'orders' === $key ) {
			$new[ $pref_key ] = 'İletişim Tercihleri';
		}
	}
	if ( ! isset( $new[ $pref_key ] ) ) {
		$new[ $pref_key ] = 'İletişim Tercihleri';
	}
	return $new;
} );

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
