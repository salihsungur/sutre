# OUT-3 — Tehdit Modeli + Hosting/PayTR Readiness (@architect)

## Sonuç
- Tamamlanan hedef: Anayasa §13 Faz 0'ın kalan iki kalemi tamamlandı: (a) tehdit modeli + güvenlik kontrol listesi, (b) hosting gereksinim dokümanı, (c) PayTR readiness checklist.
- Değiştirilen dosyalar (3, kapsam dışı dosya YOK):
  - `/opt/data/workspace/proje/SECURITY.md` — varlıklar, 8 tehditlik model (T1–T8, vektör/etki/kontrol + anayasa referansı), §8.1/§8.2/§8.3 kontrol listeleri (her madde `durum: PLANLANDI`), PayTR'ye özel güvenlik gereksinimleri.
  - `/opt/data/workspace/proje/docs/architecture/hosting-requirements.md` — sürüm politikası, HPOS, SSL/TLS, yedek, staging izolasyonu, sistem cron, SPF/DKIM/DMARC, veri konumu değerlendirmesi; satın alma kararı yok.
  - `/opt/data/workspace/proje/docs/integrations/paytr-readiness.md` — resmî entegrasyon doğrulama adımı, sandbox erişimi, secret envanteri (SECRET_REFERENCE_ONLY), callback imza/idempotency gereksinimleri, §12.2'den 20 test senaryosu.
- Veritabanı/ayar etkisi: YOK (plan dokümanları).

## Doğrulama
- Çalıştırılan testler: Bu paket dokümantasyon turudur; kod/test çalıştırılması kapsamda değil. Doğrulama disk kanıtıyla yapıldı: üç dosyanın write_file çıktısı `verified: true`; dosya yolları ve içerik diskte mevcut.
- PASS sonuçları: SECURITY.md tehdit modeli bölümü içeriyor; hosting gereksinimleri anayasa §8.1–§8.3 maddelerini birebir kontrol listesine çeviriyor; PayTR checklist §4.1–§4.5 + §12.2 senaryolarının tamamını kapsıyor; komisyon oranı sabitlenmedi (%2,19 yalnız promosyon referansı olarak not edildi — §1.3).
- FAIL / atlanan: Uygulama testleri atlandı — bu dokümanlar PLAN'dır; uygulama Faz 1+'dir.

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: YOK. Secret'lar yalnız SECRET_REFERENCE_ONLY olarak referanslandı; örnek key/üretim yapılmadı; kişisel veri yazılmadı.
- Ödeme/fiyat/stok/fatura etkisi: YOK (kod/entegrasyon yazılmadı).
- Geri dönüş adımı: Üç dosya silinerek önceki duruma dönüş; başka dosyaya dokunulmadı, git etkisi yok (repo yok, commit/push yapılmadı).

## Açık kapılar
- NEEDS_OWNER_INPUT: hosting/domain/e-posta sağlayıcı seçimi ve satın alma; PayTR sözleşme komisyon/valör/taksit şartları; PayTR test/canlı hesap durumu.
- OWNER_APPROVAL_REQUIRED: RPO/RTO hedefleri; canlı PayTR aktivasyonu ve merchant bilgileri; iade/chargeback operasyon sahipliği.
- LEGAL_REVIEW_REQUIRED: Yurtdışı veri aktarımı değerlendirmesi (barındırma Türkiye dışı olursa); veri haritası kalemleri (Faz 4 kapsamı).
- Sonraki en küçük güvenli adım: Faz 0 kapanışı — PROJECT_INPUTS.md'yi (pack-1 çıktısı) bu üç dokümanın açık kapılarıyla birleştirip sahibine Faz 0 onayı için sunmak; ardından Faz 1'e (Git repo + ortam ayrımı) geçiş.
