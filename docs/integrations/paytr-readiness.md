# PayTR Entegrasyon Readiness Checklist (Faz 3 Öncesi)

> Belge türü: READINESS checklist — gerçek entegrasyon/kod YOK (Faz 3 işi).
> Kaynak: anayasa §4 (PayTR Ödeme Tasarımı), §12.2 (Zorunlu Ödeme Senaryoları), §1.3, §13, §14, §17.
> Sahip: @architect. Tarih: 2026-09-12.

## 0. Kapı durumu

- Canlı PayTR aktivasyonu ve merchant bilgileri: **OWNER_APPROVAL_REQUIRED** (anayasa §0.2, §17).
- Sözleşme komisyon/valör/taksit şartları: **NEEDS_OWNER_INPUT**.
- Komisyon oranı SABİTLENMEZ: `%2,19` yalnız 11 Eylül 2026 tarihli yeni üye işyeri promosyon referansıdır; kod, panel veya mali model bu oranı sabit gerçek kabul etmez (anayasa §1.3). Teklif karşılaştırma veri modeli tüm maliyet alanlarını (tek çekim, ticari kart, yabancı kart, taksit, valör/bloke, iade/chargeback, BSMV, sabit ücret) desteklemelidir.

## 1. Resmî entegrasyon doğrulama adımı

- [ ] PayTR'nin resmî, güncel WooCommerce entegrasyonu (resmî eklenti/modül) varlığı kontrol edilir; varsa önce o değerlendirilir (anayasa §4.1).
- [ ] Entegrasyonun WooCommerce sürümü ve HPOS uyumluluğu resmî kaynakla doğrulanır; anayasa §3.2 eklenti politikası kaydı oluşturulur (iş gerekçesi, yayıncı, güncellik, güvenlik geçmişi, kaldırma planı).
- [ ] Özel entegrasyon gerekiyorsa PayTR'nin güncel resmî dokümantasyonu tek teknik kaynak kabul edilir; doküman sürümü/tarihi kayda geçirilir (anayasa §4.1).
- [ ] Kart verisi PayTR'nin güvenli checkout/iFrame bileşeninde kalır; sistem kart numarası/CVV kaydetmez, loglamaz, kendi sunucusundan gereksiz geçirmez (anayasa §4.1).
- [ ] Ödeme katmanı adapter/interface ile soyutlanır; ileride iyzico/banka POS eklenebilir; PayTR devre dışı kalınca mağaza/yönetim paneli çökmez (anayasa §1.2).

## 2. Sandbox erişimi

- [ ] PayTR hesap açılış/üyelik durumu belirlenir (duruma göre — AGENTS.md §5).
- [ ] Sandbox/test mağazası erişimi talep edilir ve doğrulanır.
- [ ] Sandbox test kartları ve test senaryoları dokümanından güncel bilgi alınır.
- [ ] Test ve canlı anahtarların kesin ayrımı doğrulanır (anayasa §4.2).
- [ ] Test modunda gerçek tahsilat/iade oluşmaz; canlıya geçişte düşük tutarlı gerçek işlem + iade doğrulaması yapılır (anayasa §13 Faz 6) — o ayrı kapıdır.

## 3. Merchant bilgi envanteri

| Alan | Konum kaydı | Kural |
|---|---|---|
| Merchant ID | SECRET_REFERENCE_ONLY | Git'e, DB düz metin ayara, frontend JS'e, loglara yazılmaz (anayasa §4.2, §17) |
| Merchant key | SECRET_REFERENCE_ONLY | Host secret manager veya güvenli ortam değişkeninden okunur |
| Salt | SECRET_REFERENCE_ONLY | Test/canlı anahtar ayrımı korunur; admin panelinde maskeli gösterim; API yanıtında dönmez |
| Callback URL | Yapılandırma (secret değil) | Ortama özel değer koddan ayrı tutulur (anayasa §3.3) |
| Sızıntı rotasyon prosedürü | `RUNBOOK.md` (Faz 1+) | Sızıntı şüphesinde izlenecek adımlar (anayasa §4.2) |

- [ ] Her secret için konum kaydı `PROJECT_INPUTS.md` / envanterde SECRET_REFERENCE_ONLY olarak tutulur; gerçek değer hiçbir dokümana, prompt'a, log'a, fixture'a yazılmaz.
- [ ] Admin panelinde secret'lar maskeli gösterilir; API yanıtlarında asla dönmez (anayasa §4.2).

## 4. Callback / webhook gereksinimleri (anayasa §4.3)

- [ ] PayTR imza/hash doğrulaması güncel resmî yönteme göre uygulanır.
- [ ] Yalnız tarayıcı dönüşüne güvenilmez; `Paid` geçişi yalnız doğrulanmış sunucu callback'i ile olur (anayasa §4.4).
- [ ] Idempotency: aynı callback defalarca işlense bile yalnız bir finansal sonuç üretilir; çift tahsilat/çift stok/çift fatura/çift e-posta engellenir (anayasa §4.4).
- [ ] Sipariş no, tutar, para birimi ve merchant bağlamı doğrulanır.
- [ ] Başarı ile hata/ret olayları açıkça ayrılır; doğrulanmamış callback ile sipariş `processing/completed` yapılmaz.
- [ ] Ham secret/kart/kişisel veri loglanmaz; correlation ID ile denetlenebilir log üretilir.
- [ ] Geçici hatalarda güvenli retry + dead-letter/reconciliation kuyruğu; hızlı, deterministik, bağımlılık arızalarına dayanıklı cevap.
- [ ] Günlük uzlaştırma (reconciliation) işi tasarlanır: mağazada ödenmiş/sağlayıcıda yok, sağlayıcıda başarılı/mağazada bekleyen, tutar/para birimi uyuşmazlığı, çoklu işlem, iade/chargeback uyuşmazlığı; uyuşmazlıklar otomatik gizlenmez, alarm + manuel inceleme kuyruğuna gider (anayasa §4.5).

## 5. Zorunlu test senaryoları (anayasa §12.2)

Sandbox'ta her senaryo kanıtlı olarak çalıştırılır; kanıt test çıktısı/log'dur (AGENTS.md §6):

- [ ] Başarılı ödeme.
- [ ] Reddedilen ödeme.
- [ ] Kullanıcı ödeme sayfasını kapatır.
- [ ] Callback tarayıcı dönüşünden önce gelir.
- [ ] Callback tarayıcı dönüşünden sonra gelir.
- [ ] Callback hiç gelmez.
- [ ] Callback geç gelir.
- [ ] Callback birden fazla gelir (idempotency kanıtı — anayasa §14).
- [ ] Geçersiz hash/imza.
- [ ] Yanlış tutar / para birimi / sipariş no.
- [ ] Network timeout.
- [ ] Sağlayıcı 5xx.
- [ ] Aynı sepetten eşzamanlı deneme.
- [ ] Tam iade.
- [ ] Kısmi iade.
- [ ] Başarısız iade ve retry.
- [ ] Chargeback kaydı.
- [ ] Cache/WAF callback'i engeller.
- [ ] E-posta veya fatura servisi ödeme sonrası arızalanır (ödeme başarısı kaybedilmez; iş kuyruğa alınır — anayasa §12.2).

## 6. Kabul bağlantısı

- Faz 3 tamamlandı sayılmadan önce: imza doğrulaması geçti, callback idempotency kanıtlandı, tarayıcı yönlendirmesi ödeme gerçeği olarak kullanılmıyor, tutar/sipariş/para birimi uyuşmazlığı engelleniyor, iade ve reconciliation test edildi, secret'lar Git/log/frontend/DB export'unda yok (anayasa §14 Ödeme).
- Canlıya geçiş: tüm Faz 6 maddeleri + sahibi onayı — OWNER_APPROVAL_REQUIRED.
