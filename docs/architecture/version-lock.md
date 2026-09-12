# Sürüm Kilit Kararı — WordPress / WooCommerce / PHP

> Belge türü: SÜRÜM KARAR BELGESİ (anayasa §3.1). Sahip: @researcher. Tarih: 2026-09-12.
> **⚠️ Bu kilit anayasada geçici bilgidir — kurulum anında resmî uyumluluk dokümanlarıyla TEKRAR doğrulanır ve ancak o zaman kilitlenir (anayasa §3.1).**
> Bu dosya sürüm seçimini kaydeder; kurulum yapmaz, eklenti yüklemez.

## 1. Seçilen sürümler (karar tablosu)

| Değişken | Seçilen sürüm | Gerekçe (özet) | Resmî kaynak | Doğrulama tarihi |
|---|---|---|---|---|
| WORDPRESS | 7.1 | Resmî API `current: 7.1`; news duyurusu 2026-08-19; 7.1.x nokta sürümü stabil kanalda yok (7.1.1 yalnız RC) | https://api.wordpress.org/core/version-check/1.7/ + https://wordpress.org/news/2026/08/mary-lou/ | 2026-09-12 |
| WOOCOMMERCE | 11.1.0 | Resmî releases sayfası "11.1.0 (stable) | 2026-09-03"; wp.org eklenti kaydı `version: 11.1.0`, `Tested up to: 7.1` | https://developer.woocommerce.com/releases + https://wordpress.org/plugins/woocommerce/ | 2026-09-12 |
| PHP | 8.4 | WordPress önerisi "PHP 8.3+"; WooCommerce önerisi "PHP 8.3+"; 8.4 aktif destek 31.12.2026'ya kadar → önerilenin üstünde, aktif destekli en güncel dal | https://wordpress.org/about/requirements/ + https://woocommerce.com/document/update-php-wordpress + https://www.php.net/supported-versions.php | 2026-09-12 |

Uyumluluk çapraz kontrolü (wp.org eklenti API, 2026-09-12):

- WooCommerce 11.1.0: `Requires at least: WP 7.0`, `Tested up to: WP 7.1`, `Requires PHP: 7.4 (minimum)` → WP 7.1 + PHP 8.4 minimumların ÜSTÜNDE, uyumlu.
- WordPress 7.1 resmî gereksinimi: PHP 8.3+ (öneri), 7.4+ (minimum, EOL) → PHP 8.4 uyumlu.
- Kesişim kuralı (hosting-requirements.md §1): WooCommerce'in desteklediği en güncel PHP dalı = PHP 8.4 ✓.

## 2. HPOS uyumluluk notu

- WooCommerce 10.8+ çekirdeği HPOS'u varsayılan sipariş deposu olarak kullanır; 11.1.0'ın release notes ve changelog'unda HPOS tabloları (`wc_orders`, `wc_orders_meta`) üzerinde aktif performans çalışması görülür → 11.1.0 bariyerisiz HPOS destekliyor. RESMİ KAYNAK.
- PayTR resmî eklentisi `paytr-sanal-pos-woocommerce-iframe-api` v3.0.0 changelog: "High-Performance Order Storage (HPOS) desteği eklenmiştir" → eklenti HPOS uyumluluk beyanı var (v3.1.2, 2026-05-14).

## 3. PayTR WooCommerce resmî eklentisi (bilgi notu — kurulum Faz 3 kapısı)

| Alan | Değer | Kaynak |
|---|---|---|
| Eklenti slug | `paytr-sanal-pos-woocommerce-iframe-api` | wordpress.org eklenti dizini |
| Güncel sürüm | 3.1.2 (2026-05-14) | wp.org plugin API + eklenti sayfası |
| Uyumluluk beyanı | `Tested up to: WP 6.8.8`, `Requires: WP 4.4`, `Requires PHP: 5.6` | wp.org plugin API (2026-09-12) |
| HPOS | Desteği 3.0.0'da eklendi (changelog) | wp.org eklenti changelog |
| GELİŞTİRİCİ | PayTR Ödeme ve Elektronik Para Kuruluşu A.Ş. (resmî) | wp.org |

**ÇELİŞKİ / RİSK NOTU (BELİRSİZ → Faz 3 öncesi tekrar doğrulanacak):** Eklentinin wp.org "tested up to" beyanı WP 6.8'de kalıyor; WP 7.1 için resmî test beyanı YOK. Bu bir engel değil (minimum 4.4, PHP minimumu çok eski beyan) ama anayasa §3.2 eklenti politikası gereği Faz 3'te sandbox'ta WP 7.1 + WooCommerce 11.1 + PHP 8.4 üçlüsüyle test kanıtı olmadan canlıya alınmaz. wp.org incelemelerinde de güncel sürüm uyumsuzluk şikâyetleri mevcut — alternatif özel entegrasyon (anayasa §4.1) Faz 3'te değerlendirilir.

## 4. PHP EOL takvimi (planlı yükseltme gereği — php.net, erişim 2026-09-12)

| Dal | Aktif destek bitişi | Güvenlik desteği bitişi | Not |
|---|---|---|---|
| 8.2 | 31.12.2024 (bitti) | **31.12.2026** | Yalnız güvenlik; seçimimiz DEĞİL |
| 8.3 | 31.12.2025 (bitti) | 31.12.2027 | Güvenlik-dönemi yedek dal |
| **8.4** | **31.12.2026** | 31.12.2028 | **SEÇİMİMİZ** — aktif destek 3,5 ay sonra bitiyor |
| 8.5 | 31.12.2027 | 31.12.2029 | Sonraki planlı yükseltme hedefi |

Planlı yükseltme kuralı (hosting-requirements.md §1): PHP 8.4 güvenlik desteği 31.12.2028'de biter; PHP 8.5'e geçiş 2027 içinde staging testiyle planlanmalı. PHP 8.2 kullanan hiçbir ortamda canlıya çıkılmaz (güvenlik desteği 2026 sonunda bitiyor).

## 5. Kaynak URL listesi (tümü 2026-09-12'de erişildi)

1. https://api.wordpress.org/core/version-check/1.7/ — WP güncel stabil: 7.1 (resmî API)
2. https://wordpress.org/news/2026/08/mary-lou/ — WP 7.1 yayın duyurusu, 19.08.2026
3. https://developer.woocommerce.com/releases — "11.1.0 (stable) | 2026-09-03"
4. https://developer.woocommerce.com/2026/09/03/wc-11-1-release-notes — WC 11.1.0 release notes
5. https://wordpress.org/plugins/woocommerce/ — WP 7.1 tested, WP 7.0 minimum
6. https://wordpress.org/about/requirements/ — WP önerisi PHP 8.3+ / MariaDB 10.11+ / MySQL 8.0+
7. https://woocommerce.com/document/update-php-wordpress — WooCommerce önerisi PHP 8.3+
8. https://www.php.net/supported-versions.php — PHP dal destek takvimi
9. https://wordpress.org/plugins/paytr-sanal-pos-woocommerce-iframe-api/ — PayTR eklentisi v3.1.2, HPOS changelog
10. wp.org Plugin API (https://api.wordpress.org/plugins/info/1.2/) — sürüm/tested/requires alanları

## 6. Kurulum anı tekrar doğrulama kapısı

Bu belge 2026-09-12 anlık görüntüsüdür. Kurulum (Faz 1) başlarken §1'deki üç sürüm, §5'teki resmî kaynaklardan yeniden çekilir ve farklılık varsa bu dosya güncellenir; ancak o anda anayasa §3.1 anlamında "kilitlenme" olur.
