# PAKET 6 — FAZ 1/2: SÜRÜM SEÇİMİ ARAŞTIRMASI (@researcher)

## Görev
Anayasa §3.1 bağlayıcı: "Güncel, kararlı, birbirleriyle uyumlu WordPress, WooCommerce ve PHP sürümleri seçilmelidir. Sürüm seçimi kurulum anında resmî uyumluluk dokümanlarıyla doğrulanıp kilitlenmelidir." Bu paket o doğrulamayı web'den güncel kaynaklarla yapar ve sürüm karar belgesini üretir.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (web_search veya read_file). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §3.1, §3.3, §8.1
2. `/opt/data/workspace/proje/docs/architecture/hosting-requirements.md` — §1 Sürüm politikası

## Kapsam / görev
1. Resmî kaynaklardan güncel sürümleri URL kanıtlı doğrula:
   - WordPress en güncel stabil major/minor sürüm (wordpress.org/news veya resmî release sayfası) + yayın tarihi.
   - WooCommerce en güncel stabil sürüm + minimum WP/PHP gereksinimleri (developer.woocommerce.com veya resmî changelog).
   - PHP aktif destek sürümleri (php.net supported versions) — WooCommerce'in önerdiği sürümle kesişim.
   - PayTR WooCommerce resmî eklentisinin güncel sürümü ve uyumluluk beyanı (wordpress.org plugin listing veya paytr.com entegrasyon sayfası).
2. Karar tablosu üret: `WORDPRESS=<sürüm>`, `WOOCOMMERCE=<sürüm>`, `PHP=<sürüm>` + her biri için kaynak URL ve doğrulama tarihi. HPOS uyumluluk notu (WooCommerce sürümü bariерisiz HPOS destekliyor mu).
3. `/opt/data/workspace/proje/docs/architecture/version-lock.md` yaz:
   - Seçilen sürümler + gerekçe (resmî kaynak linkleri, geçerlilik tarihi 2026-09-12)
   - "Bu kilit anayasada geçici bilgi — kurulumda tekrar doğrulanır" notu
   - PHP EOL takvim satırları (planlı yükseltme gereği)
   - Kaynak URL listesi + erişim tarihi
4. Sürüm numarası yoksa veya kanıt çelişiyorsa TAHMİN ETME — ara raporuBlocked olarak bırak.

## Bağlayıcı sınırlar (out-of-scope)
- Yalnız version-lock.md yaz; başka dosya YOK.
- Kurulum YOK, eklenti kurma YOK.
- Sürümü resmî kaynakla doğrulamadan yazma.

## Kanıt / kapılar
- Kanıt: her sürüm için erişilen resmî URL — rapor içinde listeli; version-lock.md diskte.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-6-version-lock.md`
Anayasa §16 formatı. Rapor 150-350 kelime.
