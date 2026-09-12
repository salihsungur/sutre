# Hosting Gereksinimleri — Türkiye Pazarlı WooCommerce

> Belge türü: TEKNİK GEREKSİNİM listesi. Satın alma kararı, sağlayıcı seçimi veya ücretli sözleşme YOK — o kapı NEEDS_OWNER_INPUT (anayasa §0.2).
> Kaynak: anayasa §2, §3.1, §3.3, §8; AGENTS.md. Sahip: @architect. Tarih: 2026-09-12.

## 1. Sürüm politikası

- MUST: Güncel, kararlı, birbirleriyle uyumlu PHP + WordPress + WooCommerce sürümleri; kurulum anında resmî uyumluluk dokümanlarıyla doğrulanıp kilitlenir (anayasa §3.1).
- MUST: PHP sürümü WooCommerce'in güncel kararlı dalının resmî olarak desteklediği en güncel sürümü; PHP sürüm sonu (EOL) tarihinden önce planlı yükseltme.
- MUST: Sürüm yükseltmeleri local/staging'de uyumluluk testi geçmeden production'a alınmaz (anayasa §0.6).
- SHOULD: Hosting, WordPress çekirdeği ve WooCommerce'i otomatik major yükseltmeyen, yönetilebilir sürüm kilidi sunan yapı tercih edilir (uyumsuz eklenti/tema ile ani kırılma riski).
- MUST: Tema değişse bile işlevler çalışmalı; bu, sürüm kararlarından bağımsız mimari gerekliliktir (§3.1).

## 2. HPOS (High-Performance Order Storage)

- MUST: Hosting ortamı HPOS ile uyumlu olmalı; sipariş verisine asla doğrudan `wp_posts/wp_postmeta` varsayımıyla erişilmez (anayasa §3.1, AGENTS.md).
- SHOULD: HPOS tabloları için yeterli InnoDB/DB kaynağı; sipariş tabloları düzenli optimize edilebilir olmalı.
- MUST: HPOS açılış/kapanış ayarı yalnız project-core-plugin + test kanıtıyla değiştirilir.

## 3. SSL/TLS

- MUST: HTTPS zorunlu; HTTP güvenli biçimde HTTPS'e yönlendirilir (anayasa §8.1).
- MUST: Otomatik sertifika yenileme (anayasa §8.1).
- SHOULD: HSTS uygunluk değerlendirmesi yapılır ve sonuç ADR'ye yazılır (anayasa §8.1).
- MUST: Callback endpoint (PayTR) dahil tüm uçlar sertifika geçerli olmalı; sertifika süresi izlenmelidir (anayasa §11).

## 4. Yedek

- MUST: Veritabanı + dosyalar düzenli, otomatik, şifreli ve site sunucusundan ayrı konumda yedeklenir (anayasa §8.3).
- MUST: Günlük artımlı + periyodik tam yedek politikası iş hacmine göre belirlenir; saklama süreleri tanımlıdır (anayasa §8.3).
- MUST: Restore yalnız kâğıt üstünde değil; staging'de düzenli test edilir (anayasa §8.3).
- MUST: RPO/RTO hedefleri `PROJECT_INPUTS.md` içinde sahibi onaylı olur (anayasa §8.3) — OWNER_APPROVAL_REQUIRED.
- MUST: Yedek dökümleri ve secret dosyaları Git'e alınmaz (anayasa §2.2).

## 5. Staging izolasyonu

- MUST: Üç ortam: `local`, `staging`, `production` (anayasa §3.3).
- MUST: Ortama özel değerler koddan ayrılır (anayasa §3.3).
- MUST: Canlı müşteri verisi development/staging'e kopyalanmaz; zorunluysa anonimleştirilir (anayasa §7).
- MUST: AI'ya kalıcı tam admin, production SSH veya production DB yetkisi verilmez (anayasa §8.1).
- MUST: Akış: Git dalı → local/staging → test → yedek → onay kapısı → production (anayasa §0.6).

## 6. Sistem cron

- MUST: Trafik bağımlı WP-Cron yerine güvenilir sistem cron kullanılır (anayasa §3.3).
- MUST: WP-Cron devre dışı bırakılırsa sistem cron girişleri izlenir; ödeme callback retry ve günlük uzlaştırma işi (anayasa §4.5) cron'a bağlıdır ve kaybolamaz.
- SHOULD: Cron logları izlenebilir; başarısız cron alarmı üretir (anayasa §11).

## 7. E-posta teslimatı (SPF/DKIM/DMARC)

- MUST: Sipariş e-postaları güvenilir teslim edilmeli; SPF/DKIM/DMARC kurulumu ve kontrolü Faz 1 kabul maddesidir (anayasa §13 Faz 1, §14).
- MUST: SPF, DKIM, DMARC kayıtları domain DNS'inde yapılandırılır ve doğrulanır.
- SHOULD: Transactional e-posta için paylaşımlı sunucu maili yerine ayrı bir SMTP/e-posta servisi değerlendirilir (test/sandbox modu zorunlu — anayasa §3.3).
- MUST: E-posta teslim hataları izlenir (anayasa §11).
- Not: Sağlayıcı seçimi NEEDS_OWNER_INPUT.

## 8. Veri konumu değerlendirmesi (Avrupa/Türkiye)

- MUST: Hosting, CDN, e-posta, yedekleme ve hata izleme sağlayıcılarının veri aktarım etkisi KVKK açısından incelenir ve `PRIVACY-DATA-MAP.md`'ye yazılır (anayasa §7).
- SHOULD: Türkiye'de barındırma; gecikme avantajı + KVKK aktarım basitleşmesi. ABD/AB barındırma zorunluysa yurtdışı aktarım değerlendirmesi (KVKK mekanizmaları) `LEGAL_REVIEW_REQUIRED` kapısıyla yapılır.
- MUST: Üçüncü taraf servislerin (özellikle yedekleme ve CDN) veri saklama konumu dokümante edilir.
- Bu madde karar değil, değerlendirme gereksinimidir; nihai seçim sahibi onaylıdır.

## 9. Genel dayanıklılık ve uyum

- MUST: Cache/CDN ödeme, sepet, hesabım ve callback endpoint'lerini bozmamalı (anayasa §9).
- MUST: Bir entegrasyonun (ödemе dahil) devre dışı kalması mağazayı veya yönetim panelini çökertmemeli (anayasa §1.2).
- MUST: `wp-config.php`, uploads, cache, yedekler, DB dökümleri, secret dosyaları Git dışında (anayasa §2.2).
- MUST: Dosya izinleri en az yetki; panelden tema/eklenti dosya düzenleme kapalı; XML-RPC kapalı/sınırlı; MFA tüm yönetim panellerinde (anayasa §8.1).

## 10. Açık kapılar

- Hosting/domain/e-posta sağlayıcı seçimi ve satın alma: NEEDS_OWNER_INPUT.
- RPO/RTO onayı: OWNER_APPROVAL_REQUIRED.
- Yurtdışı veri aktarımı değerlendirmesi (gerekirse): LEGAL_REVIEW_REQUIRED.
