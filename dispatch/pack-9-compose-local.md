# PAKET 9 — FAZ 1/5: COMPOSEŞABLON ISKELETİ (LOCAL-ONLY, ONAYSIZ ÖN HAZIRLIK) (@devops)

## Görev
ADR-001 tavsiye B (Docker Compose self-host) beklemede — KARAR yok. Bu paket "B seçilirse kullanılacak local compose iskeletini" hazırlar: tamamen LOCAL geliştirme kullanımına yönelik, external bağımlılık ve canlı key yok. Eğer ADR-001 A (yönetilen host) seçilirse bu iskelet staging/local referans olarak kalmaya devam eder — dolayısıyla iş boşa gitmez either way.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile repo ve kaynak oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/ADR/ADR-001-kurulum-stratejisi.md` + `/opt/data/workspace/proje/docs/architecture/environment-plan.md`
2. `/opt/data/workspace/proje/docs/architecture/version-lock.md` (WP 7.1 / WC 11.1.0 / PHP 8.4)
3. `/opt/data/workspace/proje/docs/architecture/hosting-requirements.md`

## Kapsam / görev
1. `/opt/data/workspace/proje/local/compose/` dizininde :
   - `docker-compose.local.yml`: WordPress (php8.4-apache veya php8.4-fpm + nginx — basit tut; tek hosting container), MariaDB (hosting-req §6 sürüm uyumu — WP resmî gereksinimle eşleşen sürüm), tümü local-only: port`- 127.0.0.1 eksposure`s, adminer gibi arızi tooldojo YOK.
   - `.env.example` (SADECE placeholder değerler; secret value YAZMA): `WORDPRESS_*, MYSQL_*, WP_ENV=local` gibi. Gerçek secret YAZMA ve dosyada toplama — env consistency-hosting req §4 doğrultusunda.
   - `wp-config-docker.php.tpl` şablon (env'den okur wp-config include'u — wp-config.php kök .gitignore'da olduğu için farklı yol: `local/compose/wp-config-docker.php` template'i, .gitignore'da `wp-config*.php` olduğundan bu dosyanın diğer bir isimle (`wp.config.template.php gibi) takip edilebilir tomu et      ) — `.gitignore`'a uygun şekilde adlandır.
   - `README-local.md`: hazırlanan komut seti + bekleme koşulu + "canlı secret/toplu data KULLANMAZ" uyarısı.
2. Compose ilkesi: "enster" — tek yapımits amaçla (anayasa §3.2 eklenti politicia mant στη локально inter) — sadececept, kub küçük artacaksmakessor.
3. `LOCAL stack ÇALIŞTIRMA YOK` (docker kurulu olmayabilir; başlatma testi yapma — bu ayrı bir pakettir); yalnız dosya hazırda lahat örnek config'inь дill sürüme göre doldur (PHP 8.4 image tag, MariaDB 10.11, WP 7.1 etiket anovaLatest stable, WC 11.1.0 eklentisi henüz indirilmeyecek).
4. Kommit: `feat(local): Faz 1 compose iskeleti (local-only, ADR-001 B kapsamında şablon)` — docs/pack-9 takip pmn.

## Bağlayıcı sınırlar (out-of-scope)
- WordPress/WooCommerce.indirtme, plugin instalação, staging/production compose YOK.
- Secret/merchant bilgisi dosyalara YAZILMAZ.
- ADR-001 onaysız kurul taahhüdü yok — bu dosyalar "B seçilirse прототип local" işaretli olmalı (dosya başlarına not düş).

## Kanıt / kapılar
- Kanıt: dosyalar diskte; `docker compose config -q` ortamda docker varsa syntax validation (dопок yoksa not düş ve atla — bu kadarı yeter tanır).

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-9-compose-local.md`
Anayasa §16 formatı. Rapor 100-250 kelime.
