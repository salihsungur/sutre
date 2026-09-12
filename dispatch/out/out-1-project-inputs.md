# PAKET 1 Raporu — PROJECT_INPUTS.md (@docs)

## Sonuç
- Tamamlanan hedef: Anayasa §13 Faz 0'ın ilk teslimi olan `/opt/data/workspace/proje/PROJECT_INPUTS.md` oluşturuldu. Anayasa §17 başlangıç şablonunun beş bölümü (İşletme, Domain ve altyapı, Katalog, Ödeme, Mali ve hukuk) aynen korundu; §13 Faz 0'ın istediği ek girdi alanları şablon eksiltilmeden eklendi: marka adı, ürün tipi/kategorisi, hedef müşteri, marka dili, sosyal hesap sahipliği, SKU sayısı/tipi, fiyat aralığı, kargo firması/politikası, iade politikası, garanti/servis sınıfı, hosting/DNS/e-posta gereksinim onayları, yedek konumu/saklama, RPO/RTO, PayTR üyelik/sandbox/test-canlı durumu, gelecek ödeme sağlayıcıları, stok kaynağı, misafir checkout, pazar yeri kanal planı, pazarlama bütçesi.
- Değiştirilen dosyalar: `/opt/data/workspace/proje/PROJECT_INPUTS.md` (yeni, 79 satır / 4410 bayt). Başka hiçbir dosya oluşturulmadı veya düzenlenmedi.
- Veritabanı/ayar etkisi: Yok. Git deposu henüz yok; commit/push yapılmadı (paketteki bağlayıcı sınır).

## Doğrulama
- Çalıştırılan testler: Anayasa (745 satır, tam) ve AGENTS.md `read_file` ile okundu; yazım sonrası dosya iki parça hâlinde geri okunarak disk içeriği satır satır doğrulandı.
- PASS sonuçları: (1) Dosya diskte mevcut ve içerik birebir doğrulandı — Verified. (2) §17'nin 5 bölümünün tamamı + Faz 0 ek alanları mevcut — Verified. (3) Dosyanın başında "durum: BOŞ ŞABLON — hiçbir alan doğrulanmadı" notu var — Verified. (4) Her satır dört etiketten biriyle işaretli; tek bir tahmin edilmiş/uydurulmuş değer yok — Verified (etiket satırları 19-79 arası tek tek kontrol edildi). (5) PayTR Merchant ID/key/salt yalnız SECRET_REFERENCE_ONLY olarak, değer yazılmadan referanslandı — Verified.
- FAIL / atlanan testler ve nedeni: Fonksiyonel test yok — bu teslim statik bir envanter dosyasıdır; test edilebilir davranış içermez.

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: Yok. Dosyada hiçbir secret değeri, TCKN, vergi numarası veya gerçek kişi verisi bulunmuyor; secret'lar yalnız tutulma yeriyle referanslandı (anayasa §4.2 ile uyumlu).
- Ödeme/fiyat/stok/fatura etkisi: Yok. Dosya yalnız girdi envanteridir; hiçbir fiyat, KDV oranı, komisyon oranı veya fatura kuralı belirtilmedi. KDV, garanti, cayma, kargo/iade politikaları LEGAL_REVIEW_REQUIRED olarak kapıya bırakıldı.
- Geri dönüş adımı: Dosyayı silmek (`rm PROJECT_INPUTS.md`) tamamen geri döndürür; başka sisteme dokunulmadı.

## Açık kapılar
- NEEDS_OWNER_INPUT (23 alan): marka adı, yasal unvan, işletme türü, vergi dairesi/no, tebligat/iade adresi, destek iletişimi, ürün tipi/kategorisi, hedef müşteri, marka dili, sosyal hesap sahipliği, domain + sahiplik, DNS hesabı, hosting, e-posta sağlayıcısı, SKU listesi/sayısı/tipi, fiyat aralığı, kargo firması/politikası, PayTR üyelik/sandbox/test-canlı durumu, sözleşme komisyon/valör şartları, gelecek ödeme sağlayıcıları, pazar yeri kanal planı.
- OWNER_APPROVAL_REQUIRED (8 alan): RPO/RTO, yedek konumu/şifreleme/saklama, hosting/DNS/e-posta gereksinim onayları, stok kaynağı (source_of_truth), misafir checkout, iade/chargeback operasyon sahibi, pazarlama bütçesi/reklam onayı.
- LEGAL_REVIEW_REQUIRED (12 alan): MERSİS/TCKN gereksinimi, KDV oranları, garanti/servis yükümlülüğü ve sınıfı, cayma istisnaları, kargo ve iade kargo politikası, iade politikası, ürün güvenliği/etiketleme, mali müşavir onayı, ETBİS, e-Fatura/e-Arşiv entegratörü, İYS, KVKK/çerez/mesafeli satış/ticari ileti metin onayları.
- SECRET_REFERENCE_ONLY (2 alan): PayTR Merchant ID, merchant key/salt ve ödeme secret'ları — değerler bu dosyaya asla yazılmaz.
- Sonraki en küçük güvenli adım: Salih'in NEEDS_OWNER_INPUT alanlarını doldurması; bu tamamlanmadan Faz 0'ın tehdit modeli/veri haritası adımı ve Faz 1 (repo kurulumu) başlatılmamalı.
