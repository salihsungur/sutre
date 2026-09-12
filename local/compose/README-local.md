# Local Compose İskeleti — README

> DURUM: ADR-001 KARAR BEKLEMEDE (TASLAK / OWNER_APPROVAL_REQUIRED — anayasa §0.2).
> Bu dizin, ADR-001'de TAVSİYE B (Docker Compose self-host) seçilirse kullanılacak
> LOCAL geliştirme iskeletini içerir. A (yönetilen host) seçilirse bu iskelet
> staging/local referans şablon olarak kalır.
> ADR-001 onaysız HİÇBİR kurulum taahhüdü yapılmaz; onaysız stack ÇALIŞTIRILMAZ.

## Uyarı — canlı veri ve secret YASAK

- LOCAL stack'e canlı secret, PayTR merchant bilgisi veya production veri kopyalanmaz (anayasa §7, §15, §4.2).
- Yalnız sentetik fixture kullanılır.
- Gerçek secret yalnız secret kaynağından gelir; bu dizindeki dosyalara yazılmaz.

## Dosyalar

| Dosya | Amaç |
|---|---|
| `docker-compose.local.yml` | Local-only WordPress + MariaDB iskeleti |
| `.env.example` | Placeholder env şablonu — gerçek secret İÇERMEZ |
| `wp.config.template.php` | env'den okur wp-config üretim şablonu |
| `README-local.md` | Bu belge |

## Compose yapı özeti

- `db` servisi: `mariadb:10.11` (version-lock §5 kayıt 6: WP resmî gereksinim MariaDB 10.11+).
  Healthcheck'li. DB portu host'a AÇIK DEĞİL (yalnız compose iç ağı).
- `wordpress` servisi: `wordpress:7.1-php8.4-apache` (version-lock: WP 7.1, PHP 8.4).
  HTTP port yalnız `127.0.0.1:8080` — dışarıya kapalı.
- Yan araç (Adminer/phpMyAdmin) YOK — anayasa §3.2 minimal eklenti ilkesi.
- `restart: "no"` — local geliştirme; production davranışı ayrı compose'ta (bu paket out-of-scope).
- WooCommerce 11.1.0 plugin BU pakette İNDİRİLMEZ/ETKİNLEŞTİRİLMEZ (Faz 3 kapısı ayrıdır).

## Sürüm kilidi (kurulum anında tekrar doğrulanır)

- `docs/architecture/version-lock.md` §6: kurulum başlarken WP 7.1 / WC 11.1.0 /
  PHP 8.4 sürümleri resmî kaynaklardan YENİDEN çekilir; farklılık varsa dosya
  güncellenir ve ancak o anda kilitlenir (anayasa §3.1).
- Bu dosyalardaki image tag'leri sürüm kilit referansıdır; kurulum anında güncellenir.

## Kullanım — komut seti (ADR-001 B ONAYLANDIKTAN SONRA)

```bash
cd /opt/data/workspace/proje/local/compose

# 1. Env dosyasını oluştur ve değerleri kendin belirle (secret'ları güçlü ve benzersiz yap):
cp .env.example .env

# 2. Sürüm tag'lerini version-lock §6 tekrar doğrulamasıyla güncelle (gerekirse).

# 3. WP-Cron'u sistem cron ile tetikle (local geliştirme sırasında):
#    DISABLE_WP_CRON zaten compose env içinde true olarak ayarlı.
#    Örnek crontab girişi (5 dakika): `*/5 * * * * curl -s http://127.0.0.1:8080/wp-cron.php?doing_wp_cron >/dev/null`

# 4. Stack'i başlat:
docker compose -f docker-compose.local.yml --env-file .env up -d

# 5. Durdur:
docker compose -f docker-compose.local.yml --env-file .env down

# (Not: volume'ler `down -v` ile silinir — local verisi sentetik fixture'dır.)

## Bekleme koşulu

Bu stack'in ÇALIŞTIRILMASI ayrı bir dispatch paketinde (stack çalıştırma/çalıştırma testi)
yapılacaktır. Bu pakette yalnız dosyalar ve docker compose config doğrulaması vardır.
```

## Doğrulama (bu paket kapsamı)

- Bu ortamda `docker compose` plugin'i YOK (yalnız docker binary mevcut) → `docker compose config -q` syntax doğrulaması ATLANDI (paketteki "docker yoksa not düş ve atla" kapsamında).
- Dosyalar diskte mevcut; içerik incelemesi `docker-compose.local.yml` üzerinde elle yapıldı.
