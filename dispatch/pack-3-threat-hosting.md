# PAKET 3 — TEHDİT MODELİ + HOSTİNG/PAYTR READINESS (@architect)

## Görev
Anayasa §13 Faz 0'ın kalan iki kalemi: (a) tehdit modeli, (b) hosting/DNS/e-posta gereksinim dokümanı + PayTR entegrasyon readiness checklist'i. Çıktılar `/opt/data/workspace/proje/SECURITY.md` ve `/opt/data/workspace/proje/docs/` altında.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile anayasa'yı oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` (TAMAMI — özellikle §2, §4, §8, §13 Faz 0)
2. `/opt/data/workspace/proje/AGENTS.md`

## Kapsam / görev
1. `SECURITY.md` yaz: anayasa §8'i (8.1 altyapı, 8.2 uygulama, 8.3 yedekleme) kontrol listesine çevir; her madde için `durum: PLANLANDI` + tehdit modeli bölümü (varlıklar: müşteri verisi, sipariş finansal gerçekliği, PayTR secret'ları, admin erişimi; tehditler: credential stuffing, callback sahteciliği, secret sızıntısı, eklenti zafiyeti, yedek kaybı; kontroller anayasadan). Bu bir PLAN dokümanıdır — uygulama değil.
2. `docs/architecture/hosting-requirements.md` yaz: Türkiye pazarlı WooCommerce için hosting gereksinimleri (PHP/WP/WC sürüm politikası, HPOS, SSL, yedek, staging izolasyonu, sistem cron, e-posta teslimatı SPF/DKIM/DMARC, Avrupa/Türkiye veri konumu değerlendirmesi) — TEKNİK GEREKSİNİM listesi; satın alma kararı/ücretli sözleşme YOK (o kapı NEEDS_OWNER_INPUT).
3. `docs/integrations/paytr-readiness.md` yaz: anayasa §4'e göre PayTR readiness checklist — resmî entegrasyon doğrulama adımı, sandbox erişimi, merchant bilgi envanteri (ID/key/salt konum olarak `SECRET_REFERENCE_ONLY`), callback imza/idempotency gereksinimleri, test senaryoları listesi (§12.2'den). Komisyon oranı sabitlenmez (%2,19 promosyon referansı — §1.3).

## Bağlayıcı sınırlar (out-of-scope)
- Yalnız bu 3 dosyayı oluştur; başka dosya YOK.
- Hosting/domain/e-posta sağlayıcı seçimi veya satın alma YOK — yalnız gereksinim.
- PayTR gerçek entegrasyonu/kod YOK (Faz 3); yalnız readiness dokümanı.
- Secret üretme, örnek key yazma YOK.
- Git repo yok — commit/push YAPMA.
- Provider/model override YOK.

## Kanıt / kapılar
- Kanıt: 3 dosya diskte; SECURITY.md tehdit modeli bölümü içeriyor; hosting gereksinimleri anayasa §8 maddeleriyle eşleşiyor; PayTR checklist §4 + §12.2 kapsıyor.
- Kapı: satın alma/hosting seçimi `NEEDS_OWNER_INPUT`, canlı PayTR aktivasyonu `OWNER_APPROVAL_REQUIRED`.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-3-threat-hosting.md`
Anayasa §16 formatı (Sonuç / Doğrulama / Risk ve güvenlik / Açık kapılar). Rapor 200-400 kelime.
