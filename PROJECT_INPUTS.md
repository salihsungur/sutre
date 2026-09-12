# Project Inputs

> **durum: BOŞ ŞABLON — hiçbir alan doğrulanmadı.**
> Bu dosya anayasa §13 Faz 0'ın envanteridir. Buradaki hiçbir değer tahmin edilmemiştir;
> her satır bir açık kapı etiketi taşır. Değerler yalnız işletme sahibi (Salih) tarafından
> doğrulanarak doldurulur. PayTR merchant ID/key/salt gibi secret değerler bu dosyaya
> ASLA yazılmaz; yalnız referans gösterilir (SECRET_REFERENCE_ONLY).
> Kaynak sözleşme: `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` (§0.2, §13 Faz 0, §17).

Etiket sözlüğü:
- `NEEDS_OWNER_INPUT` — değer bilinmiyor; işletme sahibinden talep edilecek.
- `OWNER_APPROVAL_REQUIRED` — teknik değer önerilebilir ama sahibin açık onayı olmadan kesinleşmez.
- `LEGAL_REVIEW_REQUIRED` — güncel resmî mevzuat ve gerektiğinde mali müşavir/avukat doğrulaması olmadan kesinleşmez; AI uydurma YAPMAZ.
- `SECRET_REFERENCE_ONLY` — değer bu dosyada saklanmaz; yalnız nerede tutulduğu referanslanır.

---

## İşletme
- Marka adı: NEEDS_OWNER_INPUT
- Yasal unvan: NEEDS_OWNER_INPUT
- İşletme türü (şahıs / tüzel): NEEDS_OWNER_INPUT
- Vergi dairesi/no: NEEDS_OWNER_INPUT
- MERSİS/TCKN gereksinimi: LEGAL_REVIEW_REQUIRED
- Tebligat/iade adresi: NEEDS_OWNER_INPUT
- Destek e-posta/telefon/WhatsApp: NEEDS_OWNER_INPUT
- Ürün tipi/kategorisi: NEEDS_OWNER_INPUT
- Hedef müşteri (segment, kanal davranışı): NEEDS_OWNER_INPUT
- Marka dili/tonu (içerik ve iletişim için): NEEDS_OWNER_INPUT
- Sosyal medya hesaplarının sahiplik/erişim durumu (Instagram/WhatsApp): NEEDS_OWNER_INPUT

## Domain ve altyapı
- Domain: NEEDS_OWNER_INPUT
- Domain sahipliği/kayıt durumu: NEEDS_OWNER_INPUT
- DNS hesabı/sahibi: NEEDS_OWNER_INPUT
- Hosting: NEEDS_OWNER_INPUT
- Hosting gereksinim onayı (yönetilen WP, staging, otomatik yedek, izole ortam — anayasa §8.1): OWNER_APPROVAL_REQUIRED
- DNS gereksinim onayı (SPF/DKIM/DMARC kayıtları, HTTPS, subdomain planı): OWNER_APPROVAL_REQUIRED
- E-posta sağlayıcısı: NEEDS_OWNER_INPUT
- E-posta teslimat gereksinim onayı (işlemsel e-posta ayrı, SPF/DKIM/DMARC doğrulanmış): OWNER_APPROVAL_REQUIRED
- Yedek konumu/şifreleme/saklama süresi: OWNER_APPROVAL_REQUIRED
- RPO/RTO: OWNER_APPROVAL_REQUIRED

## Katalog
- Ürün kategorisi: NEEDS_OWNER_INPUT
- SKU listesi: NEEDS_OWNER_INPUT
- SKU sayısı/tipi (fiziksel/dijital, varyasyon yapısı): NEEDS_OWNER_INPUT
- Fiyat aralığı (KDV dahil/haricî politika): NEEDS_OWNER_INPUT
- KDV oranları: LEGAL_REVIEW_REQUIRED
- Garanti/servis yükümlülüğü: LEGAL_REVIEW_REQUIRED
- Garanti/servis sınıfı (SKU bazında): LEGAL_REVIEW_REQUIRED
- Cayma istisnaları: LEGAL_REVIEW_REQUIRED
- Kargo ve iade kargo politikası: LEGAL_REVIEW_REQUIRED
- Kargo firması/ücret ve teslim süresi politikası: NEEDS_OWNER_INPUT
- İade politikası (cayma süreci, masraf, self-service akışı): LEGAL_REVIEW_REQUIRED
- Ürün güvenliği/etiketleme/satış kısıtları (kategoriye göre): LEGAL_REVIEW_REQUIRED
- Stok kaynağı (source_of_truth) kararı: OWNER_APPROVAL_REQUIRED
- Hesap/misafir checkout kararı: OWNER_APPROVAL_REQUIRED

## Ödeme
- PayTR Merchant ID: SECRET_REFERENCE_ONLY (değer bu dosyaya yazılmaz; secret manager / güvenli ortam değişkeninde tutulur — anayasa §4.2)
- PayTR merchant key/salt ve diğer ödeme secret'ları: SECRET_REFERENCE_ONLY (aynı kural; test ve canlı anahtarlar kesin ayrı)
- PayTR üyelik/sözleşme durumu: NEEDS_OWNER_INPUT
- PayTR sandbox erişim durumu: NEEDS_OWNER_INPUT
- PayTR test/canlı durumu: NEEDS_OWNER_INPUT
- Sözleşme komisyon/valör/taksit şartları: NEEDS_OWNER_INPUT
- İade/chargeback operasyon sahibi: OWNER_APPROVAL_REQUIRED
- Gelecek ödeme sağlayıcıları (iyzico/banka Sanal POS) planı: NEEDS_OWNER_INPUT

## Mali ve hukuk
- Mali müşavir onay tarihi: LEGAL_REVIEW_REQUIRED
- ETBİS durumu: LEGAL_REVIEW_REQUIRED
- e-Fatura/e-Arşiv yöntemi ve entegratör: LEGAL_REVIEW_REQUIRED
- İYS durumu: LEGAL_REVIEW_REQUIRED
- KVKK/hukuki metin onayı: LEGAL_REVIEW_REQUIRED
- Çerez politikası/tercih yönetimi onayı: LEGAL_REVIEW_REQUIRED
- Mesafeli satış/ön bilgilendirme metin onayı: LEGAL_REVIEW_REQUIRED
- Ticari elektronik ileti/pazarlama izin akışı onayı: LEGAL_REVIEW_REQUIRED
- Pazar yeri kanal planı ve sırası (Trendyol/Hepsiburada/N11/Etsy): NEEDS_OWNER_INPUT
- Pazarlama kanal bütçesi/reklam onayı: OWNER_APPROVAL_REQUIRED
