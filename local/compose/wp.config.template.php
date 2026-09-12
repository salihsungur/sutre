<?php
/**
 * wp.config.template.php — LOCAL compose wp-config şablonu (env'den okur).
 *
 * DURUM: ADR-001 KARAR BEKLEMEDE (TASLAK / OWNER_APPROVAL_REQUIRED) — anayasa §0.2.
 *
 * NEDEN BU İSİM: Kök .gitignore `wp-config.php` ve `wp-config-*.php` desenlerini
 * hariç tuttuğu için şablon `wp-config-*.php` adıyla takip edilemezdi. Bu dosya
 * `wp.config.template.php` adıyla Git'te takip edilir; çalıştırılabilir gerçek
 * wp-config.php asla Git'e girmez (anayasa §2.2, hosting-req §9).
 *
 * KULLANIM: Local stack çalıştırma paketinde bu şablon, değerleri .env'den okuyan
 * bir üretime adımına tabi tutulur (sed/envsubst benzeri) ve container'a
 * wp-config.php olarak yazılır. Bu dosyanın kendisi ÇALIŞTIRILMAZ.
 *
 * SECRET KURALI: Bu şablonda gerçek secret YOKTUR; tüm değerler ortam
 * değişkenlerinden okunur (anayasa §4.2). Auth key/salt değerleri de .env'den gelir.
 */

// Bu dosya tek başına çalıştırılamaz; üretim adımına girdi şablonudur.
if (!defined('ABSPATH') && php_sapi_name() !== 'cli') {
    // Şablon web'den direkt erişilmemeli.
    http_response_code(403);
    exit('Template file — not executable.');
}

/* --- Ortam değişkenlerinden değerler (placeholder değişken adları) --- */
// DB
$env_db_name     = getenv('MYSQL_DATABASE')     ?: '';
$env_db_user     = getenv('MYSQL_USER')         ?: '';
$env_db_password = getenv('MYSQL_PASSWORD')     ?: '';
$env_db_host     = getenv('WORDPRESS_DB_HOST')  ?: 'db:3306';

// Auth key/salt — değerleri .env'den; bu şablonda sabit değer YOKTUR.
$env_auth_key          = getenv('WORDPRESS_AUTH_KEY');
$env_secure_auth_key   = getenv('WORDPRESS_SECURE_AUTH_KEY');
$env_logged_in_key     = getenv('WORDPRESS_LOGGED_IN_KEY');
$env_nonce_key         = getenv('WORDPRESS_NONCE_KEY');
$env_auth_salt         = getenv('WORDPRESS_AUTH_SALT');
$env_secure_auth_salt  = getenv('WORDPRESS_SECURE_AUTH_SALT');
$env_logged_in_salt    = getenv('WORDPRESS_LOGGED_IN_SALT');
$env_nonce_salt        = getenv('WORDPRESS_NONCE_SALT');

// Ortam
$env_wp_env    = getenv('WP_ENV') ?: 'local';
$env_table_pre = getenv('WORDPRESS_TABLE_PREFIX') ?: 'wp_';
$env_locale    = getenv('WORDPRESS_LOCALE') ?: 'tr_TR';

/* --- ŞABLON GÖVDE (üretime tabi tutulacak gerçek wp-config içeriği) --- */
$template_body = <<<PHP
<?php
// ÜRETİLDİ: local/compose/wp.config.template.php şablonundan — elle düzenlemeyin.
// Secret'lar .env'den gelir; bu dosya Git'e girmez (anayasa §2.2, §4.2).

define( 'DB_NAME', '{$env_db_name}' );
define( 'DB_USER', '{$env_db_user}' );
define( 'DB_PASSWORD', '{$env_db_password}' );
define( 'DB_HOST', '{$env_db_host}' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',          '{$env_auth_key}' );
define( 'SECURE_AUTH_KEY',   '{$env_secure_auth_key}' );
define( 'LOGGED_IN_KEY',     '{$env_logged_in_key}' );
define( 'NONCE_KEY',         '{$env_nonce_key}' );
define( 'AUTH_SALT',         '{$env_auth_salt}' );
define( 'SECURE_AUTH_SALT',  '{$env_secure_auth_salt}' );
define( 'LOGGED_IN_SALT',    '{$env_logged_in_salt}' );
define( 'NONCE_SALT',        '{$env_nonce_salt}' );

define( 'WP_ENV', '{$env_wp_env}' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

// Anayasa §8.1: panelden tema/eklenti dosya düzenleme kapalı.
define( 'DISALLOW_FILE_EDIT', true );

// Anayasa §3.3: WP-Cron yerine sistem cron (local: cron job ayrı tetiklenir).
define( 'DISABLE_WP_CRON', true );

define( 'WPLANG', '{$env_locale}' );
\$table_prefix = '{$env_table_pre}';

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
PHP;

// Local stack çalıştırma paketinde bu değişken hedef wp-config.php'ye yazılır.
// Şablon tek başına çıktı ÜRETMEZ — sadece görüntüleme/inceleme amaçlıdır.
// (Bu dosyanın yürütülmesi yerine, üretim adımı ayrı script'te yapılır.)
if (defined('WP_TEMPLATE_DEBUG_DUMP') && WP_TEMPLATE_DEBUG_DUMP) {
    echo $template_body;
}
