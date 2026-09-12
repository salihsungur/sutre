# OUT-6 — SÜRÜM SEÇİMİ ARAŞTIRMASI (@researcher)

Paket: 6 — Faz 1/2. Tarih: 2026-09-12. Durum: **TAMAM** (tek çelişki notuyla; tahmin yok).

## Doğrudan cevap

Karar: `WORDPRESS=7.1`, `WOOCOMMERCE=11.1.0`, `PHP=8.4`. Üçü de resmî kaynaklardan URL kanıtlı doğrulandı (erişim 2026-09-12). WooCommerce 11.1.0 resmî beyanı: WP 7.0 minimum / WP 7.1 tested; WordPress önerisi PHP 8.3+; WooCommerce önerisi PHP 8.3+ → PHP 8.4 (aktif destekli en güncel dal) kesişimi sağlıyor. HPOS: WooCommerce 11.1.0 bariyerisiz HPOS destekliyor (HPOS tabloları üzerinde aktif çekirdek performans çalışması); PayTR resmî eklentisi HPOS desteğini v3.0.0'da beyan etti. Karar belgesi diskte: `docs/architecture/version-lock.md`.

## Kanıt tablosu

| Bulgu | Kaynak | Tarih | Güven |
|---|---|---|---|
| WP güncel stabil = 7.1 (7.1.1 yalnız RC, stabil kanalda yok) | api.wordpress.org/core/version-check/1.7/ → `current: 7.1`; wordpress.org/news/2026/08/mary-lou/ (yayın 2026-08-19) | 2026-09-12 | RESMİ KAYNAK |
| WooCommerce stabil = 11.1.0, yayın 2026-09-03; WP 7.0 min / 7.1 tested; PHP 7.4 min | developer.woocommerce.com/releases; wordpress.org/plugins/woocommerce/; wp.org Plugin API `readme.txt` | 2026-09-12 | RESMİ KAYNAK |
| WordPress önerisi PHP 8.3+ (MariaDB 10.11+/MySQL 8.0+) | wordpress.org/about/requirements/ | 2026-09-12 | RESMİ KAYNAK |
| WooCommerce önerisi PHP 8.3+ | woocommerce.com/document/update-php-wordpress | 2026-09-12 | RESMİ KAYNAK |
| PHP 8.4 aktif destek 31.12.2026, güvenlik 31.12.2028; 8.2 güvenlik desteği 31.12.2026'da bitiyor | php.net/supported-versions.php | 2026-09-12 | RESMİ KAYNAK |
| PayTR WooCommerce eklentisi v3.1.2 (2026-05-14), resmî yayıncı; HPOS v3.0.0'da eklendi | wordpress.org/plugins/paytr-sanal-pos-woocommerce-iframe-api/ + wp.org Plugin API | 2026-09-12 | RESMİ KAYNAK |
| PayTR eklentisi "tested up to: 6.8.8" — WP 7.1 test beyanı YOK; incelemelerde uyumsuzluk şikâyetleri | wp.org eklenti sayfası/API | 2026-09-12 | BELİRSİZ (çelişki) |

## Çelişki / risk notu

PayTR eklentisinin wp.org uyumluluk beyanı WP 6.8'de kalıyor; WP 7.1 + WooCommerce 11.1 + PHP 8.4 üçlüsü için test kanıtı yok. Engel değil ama anayasa §3.2/§4.1 gereği Faz 3'te sandbox testi veya özel entegrasyon kararı olmadan canlıya alınamaz. version-lock.md §3'e işlendi.

## Üretilen dosya

`/opt/data/workspace/proje/docs/architecture/version-lock.md` — karar tablosu, HPOS notu, PHP EOL takvimi, 10 kaynak URL + erişim tarihi, "kurulum anında tekrar doğrulanır" geçicilik notu.

## Açık sorular

1. Faz 3'te PayTR: wp.org eklentisi mi (sandbox test şartıyla) yoksa anayasa §4.1 özel entegrasyon mu? — OWNER_APPROVAL_REQUIRED, sandbox erişimi gerekiyor.
2. PHP 8.5'e planlı yükseltme penceresi (2027) hosting sağlayıcı seçimine bağlı — NEEDS_OWNER_INPUT zaten açık.
3. WooCommerce 11.1.0 "database update: yes" — kurulum sırasında migration planı @data ile koordine edilmeli.
