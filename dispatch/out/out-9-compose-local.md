# PAKET 9 RAPOR — FAZ 1/5 LOCAL COMPOSE İSKELETİ (@devops)

## Sonuç
- Tamamlanan hedef: ADR-001 tavsiye B için local-only compose iskeleti hazırlandı. Stack ÇALIŞTIRILMADI (pakette yasak — ayrı paket işi). ADR-001 karar beklemede; dosyalar "TASLAK / OWNER_APPROVAL_REQUIRED" notu taşır.
- Değiştirilen dosyalar (commit `e655732`): `local/compose/docker-compose.local.yml` (wordpress:7.1-php8.4-apache + mariadb:10.11; WP portu yalnız 127.0.0.1:8080; DB portu host'a kapalı; adminer YOK; DISABLE_WP_CRON + DISALLOW_FILE_EDIT), `local/compose/.env.example` (yalnız placeholder), `local/compose/wp.config.template.php` (env'den okuyan şablon — `.gitignore`daki `wp-config*.php` desenine takılmaması için nokta-ayraçlı ad), `local/compose/README-local.md` (komut seti + bekleme koşulu + canlı veri/secret yasağı).
- Veritabanı/ayar etkisi: YOK (dosya ekleme; canlı sistem yok).

## Doğrulama
- Çalıştırılan testler: git check-ignore (4/4 dosya takip edilebilir); git commit (5 dosya, 300 satır); PyYAML compose parse (services: db, wordpress); compose↔.env.example interpolasyon çapraz kontrol (tam eşleşme); adminer/phpmyadmin config taraması (yok).
- PASS: hepsi. FAIL/atlanan: `docker compose config -q` — compose plugin'i yok (yalnız docker 26.1.5), paket kapsamında not düşülüp atlandı; PyYAML parse ikamesi. `php -l` — php-cli yok.

## Risk ve güvenlik
- Secret/kişisel veri etkisi: YOK — `.env.example` placeholder; şablonda sabit secret yok, tümü env'den (anayasa §4.2).
- Ödeme/fiyat/stok/fatura etkisi: YOK.
- Geri dönüş: `git revert e655732`.

## Açık kapılar
- NEEDS_OWNER_INPUT: yok.
- OWNER_APPROVAL_REQUIRED: ADR-001 onayı — stack çalıştırma paketinin ön koşulu.
- LEGAL_REVIEW_REQUIRED: yok.
- Sonraki en küçük güvenli adım: ADR-001 onayı sonrası ayrı pakette local stack başlatma + compose doğrulama testi.

## Not
- Sürüm tag'leri version-lock.md'ye dayanır; kurulum anında version-lock §6 tekrar doğrulama kapısı işaretli.
- ADR-001 A seçilirse iskelet local/staging referans şablon olarak kalır.
