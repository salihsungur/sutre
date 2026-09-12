# Ortam Planı — Local / Staging / Production

> Belge türü: MİMARİ ŞEMA TASLAĞI. KARAR DEĞİL — ADR-001 (SAHİP ONAYI BEKLENİYOR) kapısı kapanmadan kurulum taahhüdü yok (anayasa §0.2).
> Kaynak: anayasa §0.6, §2.2, §3.3, §4.2, §7, §8, §9; docs/architecture/hosting-requirements.md (P3). Sahip: @architect. Tarih: 2026-09-12.
> Sürüm notu: Sürüm değerleri P6 çıktısından gelir: `docs/architecture/version-lock.md` (WP 7.1 / WC 11.1.0 / PHP 8.4, 2026-09-12 doğrulama). Kurulum anında bu belgenin §6 tekrar doğrulama kapısı geçerlidir.

## 1. Ortam şeması

```text
local (AI geliştirme)
  - Git dalı; tema, project-core-plugin, tests, compose şablonları Git'te
  - Veri: sentetik fixture; canlı veri YASAK
        │  Git dalı + anlamlı commit'ler (§3.4)
        ▼
staging (test ortamı)
  - PayTR sandbox; mail sandbox; sistem cron aktif
  - Restore testi ve E2E/ödeme senaryoları burada koşar (§12)
        │  deploy öncesi yedek (§8.3) → onay kapısı (OWNER_APPROVAL)
        ▼
production
  - Yalnız onaylı yayın; AI'ya kalıcı admin/SSH/DB yetkisi YASAK (§8.1)
```

## 2. Domain/subdomain önerileri (yalnız öneri — domain girdisi NEEDS_OWNER_INPUT)
- production: `www.<brand>.com` — birincil satış alanı (anayasa §18).
- staging: `staging.<brand>.com`.
- local: `local.<brand>.test` (hosts dosyası ile; DNS'e bağlanmaz, dışarıya kapalı).
- Kural: staging'de gerçek PayTR canlı anahtarları KULLANILMAZ; her ortamda `WP_ENV` değeri ayarlanır ve ödeme adapter'ı buna göre test/canlı secret seti seçer (§4.2).
- Tüm uçlarda HTTPS + otomatik sertifika; HTTP→HTTPS güvenli yönlendirme; HSTS değerlendirme sonucu ADR'ye yazılır (hosting-req §3).

## 3. Staging izolasyonu (§7)
- Staging'e canlı (production) müşteri verisi girişi YASAK. Test verisi sentetik üretilir; zorunluysa anonimleştirilmiş veri kullanılır ve anonimleştirme kaydı saklanır.
- Production DB dump'ı staging'e kopyalanmaz; istisna gerekirse anonimleştirilmiş + secret maskeli snapshot ve bunun kanıtı şarttır.
- Staging arama motoruna kapalı: `noindex` + robots Disallow + opsiyonel basic auth (yanlışlıkla production SEO etkisini engellemek için).
- Staging admin hesapları production kullanıcı hesaplarıyla paylaşılmaz (§8.1 ayrı hesaplar).

## 4. Secret akışı (§4.2, §3.3)
```text
Secret manager (host secret store / güvenli ortam değişkeni kaynağı)
        →  deploy-time env ara katmanı (.env.<ortam>, Git DIŞI, kısıtlı dosya izinleri)
        →  süreç ortamı (wp-config include / Docker secrets)
```
- `.env.*`, `wp-config.php`, yedekler ve DB dökümleri Git'e girmez (§2.2).
- PayTR Merchant ID/key/salt: test ve canlı anahtarlar kesin ayrı; yalnız secret kaynağından okunur; DB'de düz metin ayar olmaz; frontend JS'e ve loglara yazılmaz; admin'de maskeli gösterilir.
- Test secret gecikirse staging canlıya alınmaz; canlı secret asla staging ortamına yazılmaz.

## 5. Sistem cron (§3.3, §4.5)
- WP-Cron yerine güvenilir sistem cron kullanılır: `DISABLE_WP_CRON=true` + sistem crontab/servis ile eşdeğer tetikleme.
- Ortamlar arasında cron çakışması engellenir: aynı işin staging ve production'da yanlış veritabanına bağlanmaması config gate'iyle kontrol edilir; çift tetikleme çift e-posta/çift uzlaştırma üretmemelidir (§4.4).
- Günlük PayTR uzlaştırma job'ı (§4.5), callback retry/dead-letter işi ve yedek zamanlaması cron'a bağlıdır; cron başarısızlığı alarm üretir (§11).

## 6. E-posta teslimat ayrımı (§14, hosting-req §7)
- production: seçilen SMTP/e-posta servisi; SPF/DKIM/DMARC DNS kayıtları yapılandırılmış ve doğrulanmış.
- staging: e-posta yalnız sandbox hedefine gider (catch-all test kutusu veya mail-dump aracı); gerçek müşteriye e-posta çıkmaz.
- local: e-posta diske / maildump'a gider; dış SMTP çıkışı kapalı.
- E-posta arızası ödeme/fatura akışını DURDURMAZ: posta işleri yeniden çalıştırılabilir kuyruğa alınır (§12).

## 7. Ortam eşitliği ilkesi
- Mümkünse aynı stack üç ortamda (ADR-001 tavsiyesi B kapsamında): local = staging = production sürüm yapılandırması birebir.
- Ortamlar arası FARK yalnız: env değerleri, ölçek/worker sayısı, secret kaynağı, TLS operasyonu. Kod farkı olmaz.
- Cache/CDN: production'da cache açık; sepet, hesabım, checkout ve callback uçları her ortamda cache DIŞI (§9).

## 8. Açık kapılar
- NEEDS_OWNER_INPUT: domain; hosting tipi tercihi; e-posta sağlayıcı seçimi; aylık bütçe.
- OWNER_APPROVAL_REQUIRED: ADR-001 onayı; RPO/RTO hedefleri.
- LEGAL_REVIEW_REQUIRED: hosting/CDN/e-posta/yedek sağlayıcılarının KVKK veri aktarım etkisi; gerekirse yurtdışı aktarım mekanizması (hosting-req §8).
