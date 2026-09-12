# SECURITY.md — Güvenlik Kontrol Listesi ve Tehdit Modeli

> Belge türü: PLAN dokümanı — uygulama değil, kontrol listesi.
> Kaynak: `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` §8 (Güvenlik Tabanı), §4 (PayTR), §7 (KVKK), §15.
> Tarih: 2026-09-12. Sahip: @architect.
> Durum etiketleri: tüm maddeler `durum: PLANLANDI` — Faz 1'de uygulamaya alınır, kanıt üretildikçe `UYGULANDI + kanıt referansı` olur.

---

## 1. Varlıklar (korunacak şeyler)

| Varlık | Açıklama | Kaynak gerçeklik |
|---|---|---|
| Müşteri verisi | Ad, adres, telefon, e-posta, sipariş geçmişi | WooCommerce DB (HPOS) |
| Sipariş finansal gerçekliği | WooCommerce siparişi + doğrulanmış callback + işlem kimliği uzlaştırması | Anayasa §2.1 |
| PayTR secret'ları | Merchant ID / merchant key / salt | SECRET_REFERENCE_ONLY; host secret manager / ortam değişkeni |
| Admin erişimi | WP admin, hosting paneli, DNS, e-posta, PayTR paneli | Ayrı hesaplar + MFA |
| Site bütünlüğü | WordPress/WooCommerce çekirdeği, tema, eklentiler | Git + staging + yedek |

## 2. Tehdit modeli (STRIDE-uyumlu özet)

Her tehdit için: vektör → etki → kontrol (anayasa referansı).

| # | Tehdit | Hedef varlık | Vektör | Etki | Kontrol (anayasadan) | Durum |
|---|---|---|---|---|---|---|
| T1 | Credential stuffing / brute force | Admin erişimi | wp-login, XML-RPC, hosting/DNS/e-posta panelleri | Yetki yükseltme, veri sızıntısı | §8.1 MFA tüm panellerde; §8.1 paylaşılan admin hesabı yok; §8.2 brute-force + rate-limit | PLANLANDI |
| T2 | Callback sahteciliği | Sipariş finansal gerçekliği | Doğrulanmamış webhook ile siparişi ödenmiş yapmak | Ürünsüz teslimat, finansal kayıp | §4.3 imza/hash doğrulaması; §4.3 tarayıcı dönüşüne güvenmeme; §4.4 `Paid` geçişi yalnız doğrulanmış sunucu callback'i | PLANLANDI |
| T3 | Secret sızıntısı | PayTR secret'ları | Git, log, frontend JS, DB düz metin ayar, ekran görüntüsü | Sahte işlem, mağaza itibarı | §4.2 secret manager / ortam değişkeni; §4.2 test-canlı anahtar ayrımı; §8.2 log temizliği; §15 canlı anahtar koda yazmama | PLANLANDI |
| T4 | Eklenti zafiyeti | Site bütünlüğü, müşteri verisi | Güncel olmayan/korsan eklenti, nulled tema | RCE, veri sızıntısı, checkout bozulması | §3.2 eklenti politikası kaydı; §15 nulled yasak; §8.2 CI'da bağımlılık/zafiyet taraması; çekirdek patch'lenmez | PLANLANDI |
| T5 | Yedek kaybı / felaket | Tümü | Sunucu kaybı, ransomware, bozuk migration | İş sürekliliği kaybı | §8.3 şifreli + sunucudan ayrı yedek; §8.3 staging'de restore testi; §8.3 deploy öncesi yedek + rollback planı; RPO/RTO sahibi onaylı | PLANLANDI |
| T6 | Web uygulaması saldırıları (XSS/SQLi/CSRF/SSRF/dosya yükleme) | Müşteri verisi, admin | Form, REST endpoint, upload | Veri sızıntısı, yetki yükseltme | §3.1 sanitize/validate/escape; §8.2 CSRF/XSS/SQLi/SSRF/upload/yetki testleri; §8.2 REST auth + rate limit; §3.1 capability + nonce | PLANLANDI |
| T7 | KVKK / kişisel veri ihlali | Müşteri verisi | Aşırı veri toplama, staging'e canlı veri kopyalama | İdari para cezası, itibar | §7 veri minimizasyonu; §7 staging'e canlı veri kopyalama yasağı; §7 silme/erişim talebi akışı | PLANLANDI |
| T8 | İç/yetkili hesap kötüye kullanımı | Müşteri verisi, siparişler | Aşırı yetkili AI/insan hesabı | Geri döndürülemez veri kaybı | §8.1 AI'ya kalıcı tam admin/SSH/DB yetkisi yok; §4.4 manuel finansal statü değişikliği audit log'lu | PLANLANDI |

## 3. Kontrol listesi — anayasa §8.1 Altyapı

| Madde | Gereksinim | durum |
|---|---|---|
| 8.1.1 | HTTPS zorunlu; HTTP güvenli yönlendirme | PLANLANDI |
| 8.1.2 | Otomatik sertifika yenileme; HSTS uygunluk değerlendirmesi | PLANLANDI |
| 8.1.3 | Yönetilen, izole, güncel, staging/otomatik yedek destekli hosting | PLANLANDI (bkz. docs/architecture/hosting-requirements.md) |
| 8.1.4 | En az yetki dosya izinleri; panelden tema/eklenti dosya düzenleme kapalı | PLANLANDI |
| 8.1.5 | XML-RPC kapat veya sıkı sınırla | PLANLANDI |
| 8.1.6 | WP admin, hosting, DNS, e-posta, ödeme hesaplarında MFA | PLANLANDI |
| 8.1.7 | Ayrı kullanıcı hesapları; paylaşılan admin hesabı yok | PLANLANDI |
| 8.1.8 | AI'ya kalıcı tam admin / production SSH / DB yetkisi verilmez | PLANLANDI |

## 4. Kontrol listesi — anayasa §8.2 Uygulama

| Madde | Gereksinim | durum |
|---|---|---|
| 8.2.1 | Brute-force ve rate-limit koruması | PLANLANDI |
| 8.2.2 | Rol/capability denetimi, en az yetki | PLANLANDI |
| 8.2.3 | Güvenlik başlıkları ve güvenli cookie ayarları | PLANLANDI |
| 8.2.4 | CSRF, XSS, SQLi, SSRF, dosya yükleme, yetki yükseltme testleri | PLANLANDI |
| 8.2.5 | REST endpoint'lerinde authn, authz, validation, rate limit | PLANLANDI |
| 8.2.6 | Admin işlemleri, ödeme statüleri, fiyat/stok değişiklikleri, entegrasyon hataları için denetlenebilir log | PLANLANDI |
| 8.2.7 | Loglarda parola/secret/kart/tam kimlik/gereksiz adres yok | PLANLANDI |
| 8.2.8 | Bağımlılık ve bilinen zafiyet taraması CI'da | PLANLANDI |

## 5. Kontrol listesi — anayasa §8.3 Yedekleme ve felaket kurtarma

| Madde | Gereksinim | durum |
|---|---|---|
| 8.3.1 | DB + dosyalar: düzenli, otomatik, şifreli, sunucudan ayrı konum | PLANLANDI |
| 8.3.2 | Günlük artımlı + periyodik tam yedek politikası (iş hacmine göre) | PLANLANDI |
| 8.3.3 | Saklama süreleri tanımlı | PLANLANDI |
| 8.3.4 | Restore yalnız kâğıt üstünde değil; staging'de düzenli test | PLANLANDI |
| 8.3.5 | Hedef RPO/RTO `PROJECT_INPUTS.md` içinde sahibi onaylı | PLANLANDI — kapı: OWNER_APPROVAL_REQUIRED |
| 8.3.6 | Deploy öncesi yedek + tek komutla geri dönüş planı | PLANLANDI |

## 6. PayTR'ye özel güvenlik gereksinimleri (anayasa §4.2–§4.3 özeti)

- Secret'lar (merchant ID/key/salt): Git'e, DB düz metin ayara, frontend JS'e, loglara yazılmaz — `SECRET_REFERENCE_ONLY` envanter (bkz. docs/integrations/paytr-readiness.md).
- Test ve canlı anahtarlar kesin ayrı; admin panelinde maskeli gösterim; API yanıtlarında asla dönmez.
- Callback: güncel resmî yöntemle imza doğrulaması, idempotency, sipariş no/tutar/para birimi/merchant bağlam doğrulaması; doğrulanmamış callback ile `processing/completed` yapılmaz.
- Ham secret/kart/kişisel veri loglanmaz; correlation ID ile denetlenebilir log.
- Sızıntı şüphesinde rotasyon prosedürü `RUNBOOK.md`'de olacak (Faz 1+).

## 7. Kapanış kapıları

- RPO/RTO değerleri: OWNER_APPROVAL_REQUIRED (anayasa §8.3, §17).
- Bu doküman uygulama değildir; Faz 1 kabul kapısı (anayasa §13 Faz 1, §14 "Güvenlik ve dayanıklılık") maddelerin `UYGULANDI + kanıt` durumuna geçmesini zorunlu kılar.
